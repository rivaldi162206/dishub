import cv2
from ultralytics import YOLO

def start_counting(camera_id, source_path):
    # Load model secara lokal di dalam fungsi agar tidak bertabrakan
    model = YOLO('yolov8n.pt')
    cap = cv2.VideoCapture(source_path)

    line_position = 300
    count = 0
    counted_ids = set()

    while True:
        ret, frame = cap.read()
        if not ret:
            # Loop video jika untuk testing
            cap.set(cv2.CAP_PROP_POS_FRAMES, 0)
            continue

        results = model.track(frame, persist=True, verbose=False)

        if results[0].boxes.id is not None:
            boxes = results[0].boxes.xyxy.cpu().numpy()
            ids = results[0].boxes.id.cpu().numpy()
            classes = results[0].boxes.cls.cpu().numpy()

            for box, track_id, cls in zip(boxes, ids, classes):
                x1, y1, x2, y2 = map(int, box)
                cx = int((x1 + x2) / 2)
                cy = int((y1 + y2) / 2)
                
                label = model.names[int(cls)]
                if label in ["car", "motorcycle", "bus", "truck"]:
                    cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)

                    # LOGIKA COUNTING BERDASARKAN ID KAMERA
                    if cy > line_position and track_id not in counted_ids:
                        count += 1
                        counted_ids.add(track_id)
                        
                        # Simpan ke file spesifik per kamera (misal: count_1.txt)
                        with open(f"Ai/count_{camera_id}.txt", "w") as f:
                            f.write(str(count))
        
        # Tambahkan visualisasi ID kamera di frame
        cv2.putText(frame, f"Cam: {camera_id} Count: {count}", (50, 50),
                    cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)
        
        # Jika menggunakan Flask/FastAPI, di sini nanti kita yield framenya
        # Untuk sementara, kita tampilkan saja
        cv2.imshow(f"Camera {camera_id}", frame)

        if cv2.waitKey(1) == 27:
            break

    cap.release()
    cv2.destroyAllWindows()