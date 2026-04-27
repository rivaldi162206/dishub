import cv2
from ultralytics import YOLO
import threading
from fastapi import FastAPI
from fastapi.responses import StreamingResponse
import mysql.connector

app = FastAPI()

class TrafficCamera:
    def __init__(self, cam_id, source):
        self.cam_id = cam_id
        self.source = source
        # REVISI 1: Load model sekali saja
        self.model = YOLO('yolov8n.pt')
        self.count = 0
        self.counted_ids = set()
        self.line_position = 300
        self.current_frame = None

    def update_db(self):
        try:
            db = mysql.connector.connect(
                host="localhost",
                user="root",
                password="",
                database="dishub_db"
            )
            cursor = db.cursor()
            sql = "UPDATE traffic_counts SET total = %s WHERE camera_id = %s"
            cursor.execute(sql, (self.count, self.cam_id))
            db.commit()
            db.close()
        except Exception as e:
            print(f"Error Database Cam {self.cam_id}: {e}")

    def update_frame(self):
        cap = cv2.VideoCapture(self.source, cv2.CAP_FFMPEG)
        
        # REVISI 2: Set Buffer ke paling kecil agar tidak menumpuk frame lama
        cap.set(cv2.CAP_PROP_BUFFERSIZE, 1)
        
        # REVISI 3: Counter untuk Frame Skipping
        frame_count = 0

        while True:
            ret, frame = cap.read()
            if not ret:
                cap.set(cv2.CAP_PROP_POS_FRAMES, 0)
                continue

            frame_count += 1

            frame = cv2.resize(frame, (640, 360))
            
            results = self.model.track(frame, persist=True, verbose=False, imgsz=256,tracker="bytetrack.yaml")

            if results[0].boxes.id is not None:
                boxes = results[0].boxes.xyxy.cpu().numpy()
                ids = results[0].boxes.id.cpu().numpy()
                classes = results[0].boxes.cls.cpu().numpy()

                for box, track_id, cls in zip(boxes, ids, classes):
                    x1, y1, x2, y2 = map(int, box)
                    cy = int((y1 + y2) / 2)
                    cx = int((x1 + x2)/2)
                    
                    label = self.model.names[int(cls)]
                    if label in ["car", "motorcycle", "bus", "truck"]:
                        cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
                        cv2.putText(frame, f"ID: {int(track_id)}", (x1, y1 - 10), 
                        cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

                        if cy > self.line_position and track_id not in self.counted_ids:
                            self.count += 1
                            self.counted_ids.add(track_id)
                            self.update_db()
                            
                            with open(f"count_{self.cam_id}.txt", "w") as f:
                                f.write(str(self.count))

            cv2.line(frame, (0, self.line_position), (640, self.line_position), (255, 0, 0), 2)
            cv2.putText(frame, f"Cam {self.cam_id} Count: {self.count}", (20, 40),
                        cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)
            
            self.current_frame = frame

cameras = {
    1: TrafficCamera(1, "vidio.mp4"),
    2: TrafficCamera(2, "vidio2.mp4"),
    3: TrafficCamera(3, "vidio3.mp4"),
    4: TrafficCamera(4, "vidio4.mp4"),
}

for cam in cameras.values():
    threading.Thread(target=cam.update_frame if hasattr(cam, 'update_frame') else cam.update_frame, daemon=True).start()

@app.get("/video/{cam_id}")
async def video_feed(cam_id: int):
    if cam_id not in cameras:
        return {"error": "Camera ID not found"}
    def generate():
        while True:
            if cameras[cam_id].current_frame is not None:
                _, buffer = cv2.imencode('.jpg', cameras[cam_id].current_frame)
                yield (b'--frame\r\n' b'Content-Type: image/jpeg\r\n\r\n' + buffer.tobytes() + b'\r\n')
    return StreamingResponse(generate(), media_type="multipart/x-mixed-replace; boundary=frame")

@app.get("/api/count/{cam_id}")
async def get_count(cam_id: int):
    return {"camera_id": cam_id, "total": cameras[cam_id].count}