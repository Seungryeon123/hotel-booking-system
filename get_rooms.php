<?php
include 'config.php';

$hotel_id = $_GET['hotel_id'];
$result = mysqli_query($conn, "SELECT * FROM rooms WHERE hotel_id='$hotel_id'");

$rooms = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rooms[] = $row;
}

header('Content-Type: application/json');
echo json_encode($rooms);
