<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Pastikan booking ini memang milik user
    $sql = "UPDATE bookings SET status = 'Dibatalkan' WHERE booking_id = ? AND user_id = ? AND status = 'Dalam Proses'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Tempahan berjaya dibatalkan.";
    } else {
        $_SESSION['message'] = "Gagal batal tempahan atau tempahan telah diproses.";
    }

    $stmt->close();
}

$conn->close();
header("Location: my_bookings.php");
exit();
?>
