<?php
session_start();
include('config.php');

// Semak jika admin telah log masuk
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

// Dapatkan ID tempahan daripada URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    
    // Dapatkan tempahan berdasarkan booking_id menggunakan prepared statement
    $query = "SELECT * FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booking_id); // "i" bermaksud integer
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        echo "Tempahan tidak dijumpai!";
        exit();
    }
}

// Update tempahan jika borang dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    
    // Kemaskini status tempahan menggunakan prepared statement
    $update_query = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("si", $status, $booking_id); // "si" bermaksud string dan integer
    if ($stmt_update->execute()) {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Kemaskini Tempahan</title>
</head>
<body>

<h2>Kemaskini Tempahan</h2>

<form action="update_booking.php?booking_id=<?php echo $booking['booking_id']; ?>" method="POST">
    <label for="status">Status:</label>
    <select name="status" required>
        <option value="Dalam Proses" <?php if ($booking['status'] == 'Dalam Proses') echo 'selected'; ?>>Dalam Proses</option>
        <option value="Selesai" <?php if ($booking['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
    </select>
    <br><br>

    <button type="submit">Kemaskini</button>
</form>

</body>
</html>
