<?php
header('Content-Type: application/json');
$conn = mysqli_connect("localhost", "root", "", "dishub_db");

$camera_id = isset($_GET['camera_id']) ? intval($_GET['camera_id']) : 1;

$query = "
    SELECT vehicle_type, COUNT(*) as total 
    FROM traffic_log 
    WHERE camera_id = $camera_id 
    GROUP BY vehicle_type
";

$result = mysqli_query($conn, $query);
$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

echo json_encode($data);
?>