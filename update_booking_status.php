<?php
session_start();
include('config.php');

// Semak jika admin log masuk
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $booking_id = intval($_POST['booking_id']);

    // Kemas kini status tempahan
    $update_query = "UPDATE bookings SET status = '$status' WHERE id = $booking_id";
    $conn->query($update_query);

    header('Location: admin_dashboard.php');
    exit();
}
?>
