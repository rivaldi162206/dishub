<?php
$conn = mysqli_connect("localhost", "root", "", "dishub_db");

// Ambil ID dari URL 
$camera_id = isset($_GET['camera_id']) ? intval($_GET['camera_id']) : 1;

// Query harus pakai variabel $camera_id
$result = mysqli_query($conn, "SELECT total FROM traffic_counts WHERE camera_id = $camera_id");
$row = mysqli_fetch_assoc($result);

echo json_encode(["total" => ($row ? $row['total'] : 0)]);
?>