import cv2
import threading
import time
import queue
from ultralytics import YOLO
from fastapi import FastAPI
from fastapi.responses import StreamingResponse
import mysql.connector
from datetime import datetime, date
 
app = FastAPI()
from fastapi.middleware.cors import CORSMiddleware
 
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
 
# ─────────────────────────────────────────
# Database connection pool (thread-safe)
# ─────────────────────────────────────────
class DBPool:
    def __init__(self):
        self._queue = queue.Queue(maxsize=100)
        threading.Thread(target=self._worker, daemon=True).start()
 
    def write(self, cam_id: int, count: int, density: float):
        try:
            self._queue.put_nowait(("update", cam_id, count, density))
        except queue.Full:
            pass  # drop write under backpressure — do not block detection
 
    def save_history(self, cam_id: int, count: int, density: float, snapshot_date: date):
        """Queue a daily snapshot write to traffic_history table."""
        try:
            self._queue.put_nowait(("history", cam_id, count, density, snapshot_date))
        except queue.Full:
            pass
 
    def _get_conn(self):
        return mysql.connector.connect(
            host="localhost", user="root",
            password="", database="dishub_db"
        )
 
    def _worker(self):
        while True:
            item = self._queue.get()
            op = item[0]
            try:
                conn = self._get_conn()
                cur = conn.cursor()
 
                if op == "update":
                    _, cam_id, count, density = item
                    cur.execute(
                        "UPDATE traffic_counts SET total=%s, density=%s WHERE camera_id=%s",
                        (count, density, cam_id)
                    )
 
                elif op == "history":
                    _, cam_id, count, density, snapshot_date = item
                    # Upsert: jika sudah ada record hari ini untuk kamera ini, update saja
                    cur.execute(
                        """
                        INSERT INTO traffic_history (camera_id, total_count, avg_density, snapshot_date)
                        VALUES (%s, %s, %s, %s)
                        ON DUPLICATE KEY UPDATE
                            total_count  = VALUES(total_count),
                            avg_density  = VALUES(avg_density),
                            recorded_at  = CURRENT_TIMESTAMP
                        """,
                        (cam_id, count, density, snapshot_date)
                    )
 
                conn.commit()
                conn.close()
            except Exception as e:
                print(f"[DB] Error ({op}): {e}")
 
 
db_pool = DBPool()
 
 
# ─────────────────────────────────────────
# Density computation (Exponential Moving Average)
# ─────────────────────────────────────────
class EMADensity:
    def __init__(self, alpha=0.3):
        self.alpha = alpha
        self.value = 0.0
 
    def update(self, count: int) -> float:
        self.value = self.alpha * count + (1 - self.alpha) * self.value
        return round(self.value, 2)
 
    def reset(self):
        self.value = 0.0
 
 
def classify_traffic(density: float) -> tuple[str, tuple]:
    """Returns (status_label, BGR_color)"""
    if density > 10:
        return "MACET",  (0, 0, 255)
    elif density > 5:
        return "PADAT",  (0, 165, 255)
    return "LANCAR", (0, 255, 0)
 
 
# ─────────────────────────────────────────
# Core camera class
# ─────────────────────────────────────────
class TrafficCamera:
    VEHICLE_CLASSES = [2, 3, 5, 7]   # COCO: car, motorcycle, bus, truck
    MAX_TRACKED_IDS = 5000            # Bound memory for counted_ids
 
    def __init__(self, cam_id: int, source: str):
        self.cam_id = cam_id
        self.source = source
        self.model = YOLO('yolov8n.pt')
 
        # State
        self.count = 0
        self.counted_ids: set = set()
        self.line_position = 300
 
        # Density
        self.density_ema = EMADensity(alpha=0.3)
        self.density = 0.0
 
        # Streaming
        self._frame_lock = threading.Lock()
        self._current_frame = None
 
        # DB throttle: write at most once per second
        self._last_db_write = 0.0
 
        # ── TAMBAHAN: lock untuk operasi reset ──────────────────────
        self._state_lock = threading.Lock()
 
    @property
    def current_frame(self):
        with self._frame_lock:
            return self._current_frame
 
    @current_frame.setter
    def current_frame(self, frame):
        with self._frame_lock:
            self._current_frame = frame
 
    def _prune_counted_ids(self):
        """Prevent unbounded memory growth in long-running sessions."""
        if len(self.counted_ids) > self.MAX_TRACKED_IDS:
            self.counted_ids = set(list(self.counted_ids)[self.MAX_TRACKED_IDS // 2:])
 
    # ── TAMBAHAN: reset harian ───────────────────────────────────────
    def daily_reset(self):
        """
        Simpan snapshot hari ini ke DB lalu reset semua counter.
        """
        with self._state_lock:
            snapshot_date = date.today()
            db_pool.save_history(self.cam_id, self.count, self.density, snapshot_date)
            print(
                f"[RESET] Cam {self.cam_id} | "
                f"date={snapshot_date} count={self.count} density={self.density} → saved & reset"
            )
            self.count = 0
            self.counted_ids.clear()
            self.density_ema.reset()
            self.density = 0.0
 
    def update_frame(self):
        cap = self._open_capture()
 
        while True:
            ret, frame = cap.read()
            if not ret:
                cap.release()
                time.sleep(2)
                cap = self._open_capture()
                continue
 
            frame = cv2.resize(frame, (640, 360))
            vehicle_count = 0
 
            # ── Inference ───────────────────────────────────────────
            results = self.model.track(
                frame,
                persist=True,
                verbose=False,
                imgsz=320,
                classes=self.VEHICLE_CLASSES,
                tracker="bytetrack.yaml",
                conf=0.4,
            )
 
            if results[0].boxes.id is not None:
                boxes   = results[0].boxes.xyxy.cpu().numpy()
                ids     = results[0].boxes.id.cpu().numpy().astype(int)
                classes = results[0].boxes.cls.cpu().numpy()
 
                for box, track_id, cls in zip(boxes, ids, classes):
                    x1, y1, x2, y2 = map(int, box)
                    cy = (y1 + y2) // 2
                    vehicle_count += 1
 
                    cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
 
                    # Counting line crossing — gunakan _state_lock agar aman saat reset
                    with self._state_lock:
                        if cy > self.line_position and track_id not in self.counted_ids:
                            self.count += 1
                            self.counted_ids.add(track_id)
                            self._prune_counted_ids()
 
            # ── Density update ───────────────────────────────────────
            with self._state_lock:
                self.density = self.density_ema.update(vehicle_count)
 
            # ── DB write (throttled, once per second) ───────────────
            now = time.time()
            if now - self._last_db_write >= 1.0:
                db_pool.write(self.cam_id, self.count, self.density)
                self._last_db_write = now
 
            # ── Annotation ──────────────────────────────────────────
            status, color = classify_traffic(self.density)
            cv2.line(frame, (0, self.line_position), (640, self.line_position), (255, 0, 0), 2)
 
            self.current_frame = frame
 
    def _open_capture(self):
        cap = cv2.VideoCapture(self.source, cv2.CAP_FFMPEG)
        cap.set(cv2.CAP_PROP_BUFFERSIZE, 1)
        return cap
 
 
# ─────────────────────────────────────────
# TAMBAHAN: Penjadwal reset otomatis 24 jam
# ─────────────────────────────────────────
class DailyScheduler:
    """
    Menjalankan reset harian tepat pada jam RESET_HOUR (default 00:00).
    Berjalan di background thread sendiri — tidak memblokir proses lain.
    """
    RESET_HOUR = 0   # jam reset (0 = tengah malam / 00:00)
    RESET_MIN  = 0   # menit reset
 
    def __init__(self, cameras: dict):
        self.cameras = cameras
        self._thread = threading.Thread(target=self._run, daemon=True, name="DailyScheduler")
        self._thread.start()
        print(f"[Scheduler] Daily reset aktif — akan reset setiap hari pukul "
              f"{self.RESET_HOUR:02d}:{self.RESET_MIN:02d}")
 
    def _seconds_until_next_reset(self) -> float:
        now = datetime.now()
        next_reset = now.replace(
            hour=self.RESET_HOUR, minute=self.RESET_MIN,
            second=0, microsecond=0
        )
        if next_reset <= now:
            # sudah lewat hari ini → jadwalkan besok
            from datetime import timedelta
            next_reset += timedelta(days=1)
        return (next_reset - now).total_seconds()
 
    def _run(self):
        while True:
            wait_sec = self._seconds_until_next_reset()
            print(f"[Scheduler] Reset berikutnya dalam {wait_sec/3600:.2f} jam "
                  f"({wait_sec:.0f} detik)")
            time.sleep(wait_sec)
 
            # Jalankan reset semua kamera
            for cam in self.cameras.values():
                try:
                    cam.daily_reset()
                except Exception as e:
                    print(f"[Scheduler] Gagal reset cam {cam.cam_id}: {e}")
 
 
# ─────────────────────────────────────────
# FastAPI routes
# ─────────────────────────────────────────
def load_cameras():
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="dishub_db"
    )

    cur = conn.cursor(dictionary=True)

    cur.execute("SELECT * FROM cameras WHERE is_active = 1")

    rows = cur.fetchall()

    conn.close()

    loaded_cameras = {}

    for row in rows:
        loaded_cameras[row["id"]] = TrafficCamera(
            row["id"],
            row["camera_url"]
        )

    return loaded_cameras


cameras = load_cameras()
 
# Mulai thread deteksi per kamera
for cam in cameras.values():
    threading.Thread(target=cam.update_frame, daemon=True).start()
 
# TAMBAHAN: mulai scheduler reset harian
scheduler = DailyScheduler(cameras)
 
 
@app.get("/video/{cam_id}")
async def video_feed(cam_id: int):
    if cam_id not in cameras:
        return {"error": "Camera not found"}
 
    def generate():
        target_fps = 25
        interval = 1.0 / target_fps
        while True:
            t0 = time.time()
            frame = cameras[cam_id].current_frame
            if frame is not None:
                ok, buf = cv2.imencode('.jpg', frame, [cv2.IMWRITE_JPEG_QUALITY, 80])
                if ok:
                    yield (b'--frame\r\n'
                           b'Content-Type: image/jpeg\r\n\r\n'
                           + buf.tobytes()
                           + b'\r\n')
            elapsed = time.time() - t0
            time.sleep(max(0.0, interval - elapsed))
 
    return StreamingResponse(generate(), media_type="multipart/x-mixed-replace; boundary=frame")
 
 
@app.get("/api/count/{cam_id}")
async def get_count(cam_id: int):
    if cam_id not in cameras:
        return {"error": "Camera not found"}
    cam = cameras[cam_id]
    status, _ = classify_traffic(cam.density)
    return {
        "camera_id": cam_id,
        "total_count": cam.count,
        "density_ema": cam.density,
        "status": status,
    }
 
 
# ── TAMBAHAN: endpoint riwayat per kamera ───────────────────────────
@app.get("/api/history/{cam_id}")
async def get_history(cam_id: int, limit: int = 30):
    """
    Mengembalikan riwayat harian kamera tertentu.
    Query param `limit` mengontrol berapa hari terakhir yang dikembalikan (default 30).
    """
    if cam_id not in cameras:
        return {"error": "Camera not found"}
    try:
        conn = mysql.connector.connect(
            host="localhost", user="root",
            password="", database="dishub_db"
        )
        cur = conn.cursor(dictionary=True)
        cur.execute(
            """
            SELECT camera_id, total_count, avg_density, snapshot_date, recorded_at
            FROM traffic_history
            WHERE camera_id = %s
            ORDER BY snapshot_date DESC
            LIMIT %s
            """,
            (cam_id, limit)
        )
        rows = cur.fetchall()
        conn.close()
        # Konversi date/datetime ke string agar JSON-serializable
        for row in rows:
            row["snapshot_date"] = str(row["snapshot_date"])
            row["recorded_at"]   = str(row["recorded_at"])
        return {"camera_id": cam_id, "history": rows}
    except Exception as e:
        return {"error": str(e)}
 
 
# ── TAMBAHAN: endpoint riwayat semua kamera (untuk laporan) ─────────
@app.get("/api/history")
async def get_all_history(limit: int = 30):
    """
    Riwayat harian semua kamera, berguna untuk halaman laporan/dashboard.
    """
    try:
        conn = mysql.connector.connect(
            host="localhost", user="root",
            password="", database="dishub_db"
        )
        cur = conn.cursor(dictionary=True)
        cur.execute(
            """
            SELECT camera_id, total_count, avg_density, snapshot_date, recorded_at
            FROM traffic_history
            ORDER BY snapshot_date DESC, camera_id ASC
            LIMIT %s
            """,
            (limit,)
        )
        rows = cur.fetchall()
        conn.close()
        for row in rows:
            row["snapshot_date"] = str(row["snapshot_date"])
            row["recorded_at"]   = str(row["recorded_at"])
        return {"history": rows}
    except Exception as e:
        return {"error": str(e)}
 
 
# ── TAMBAHAN: endpoint manual reset (opsional, untuk keperluan testing) ─
@app.post("/api/reset/{cam_id}")
async def manual_reset(cam_id: int):
    """
    Trigger reset manual untuk satu kamera.
    Berguna saat testing atau kebutuhan operasional darurat.
    """
    if cam_id not in cameras:
        return {"error": "Camera not found"}
    cameras[cam_id].daily_reset()
    return {"message": f"Camera {cam_id} berhasil direset", "reset_at": datetime.now().isoformat()}