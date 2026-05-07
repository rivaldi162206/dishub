import cv2
import threading
import time
import queue
from collections import deque
from ultralytics import YOLO
from fastapi import FastAPI
from fastapi.responses import StreamingResponse
import mysql.connector
from contextlib import contextmanager

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
            self._queue.put_nowait((cam_id, count, density))
        except queue.Full:
            pass  # drop write under backpressure — do not block detection

    def _worker(self):
        while True:
            cam_id, count, density = self._queue.get()
            try:
                conn = mysql.connector.connect(
                    host="localhost", user="root",
                    password="", database="dishub_db"
                )
                cur = conn.cursor()
                cur.execute(
                    "UPDATE traffic_counts SET total=%s, density=%s WHERE camera_id=%s",
                    (count, density, cam_id)
                )
                conn.commit()
                conn.close()
            except Exception as e:
                print(f"[DB] Error: {e}")

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
            # Keep the most recent half
            self.counted_ids = set(list(self.counted_ids)[self.MAX_TRACKED_IDS // 2:])

    def update_frame(self):
        cap = self._open_capture()
        last_results = None

        while True:
            ret, frame = cap.read()
            if not ret:
                cap.release()
                time.sleep(2)
                cap = self._open_capture()
                continue

            frame = cv2.resize(frame, (640, 360))
            vehicle_count = 0

            # ── Inference ──────────────────────────────────────────
            results = self.model.track(
                frame,
                persist=True,
                verbose=False,
                imgsz=320,
                classes=self.VEHICLE_CLASSES,
                tracker="bytetrack.yaml",
                conf=0.4,
            )
            last_results = results

            if results[0].boxes.id is not None:
                boxes   = results[0].boxes.xyxy.cpu().numpy()
                ids     = results[0].boxes.id.cpu().numpy().astype(int)
                classes = results[0].boxes.cls.cpu().numpy()

                for box, track_id, cls in zip(boxes, ids, classes):
                    x1, y1, x2, y2 = map(int, box)
                    cy = (y1 + y2) // 2
                    vehicle_count += 1

                    cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)

                    # Counting line crossing
                    if cy > self.line_position and track_id not in self.counted_ids:
                        self.count += 1
                        self.counted_ids.add(track_id)
                        self._prune_counted_ids()

            # ── Density update ──────────────────────────────────────
            self.density = self.density_ema.update(vehicle_count)

            # ── DB write (throttled) ────────────────────────────────
            now = time.time()
            if now - self._last_db_write >= 1.0:
                db_pool.write(self.cam_id, self.count, self.density)
                self._last_db_write = now

            # ── Annotation ──────────────────────────────────────────
            status, color = classify_traffic(self.density)
            cv2.line(frame, (0, self.line_position), (640, self.line_position), (255, 0, 0), 2)
            cv2.putText(frame, status,
                        (20, 115), cv2.FONT_HERSHEY_SIMPLEX, 1.0, color, 3)

            self.current_frame = frame

    def _open_capture(self):
        cap = cv2.VideoCapture(self.source, cv2.CAP_FFMPEG)
        cap.set(cv2.CAP_PROP_BUFFERSIZE, 1)
        return cap


# ─────────────────────────────────────────
# FastAPI routes
# ─────────────────────────────────────────
cameras = {
    1: TrafficCamera(1, "vidio.mp4"),
    2: TrafficCamera(2, "vidio2.mp4"),
    3: TrafficCamera(3, "vidio3.mp4"),
    4: TrafficCamera(4, "vidio4.mp4"),
}

for cam in cameras.values():
    threading.Thread(target=cam.update_frame, daemon=True).start()


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
            time.sleep(max(0.0, interval - elapsed))   # FPS cap

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