<?php
session_start();
include('config.php');

error_reporting(0);
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set("error_log", "error_log.txt");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

// Padam tempahan
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($delete_id > 0) {
        $delete_query = "DELETE FROM bookings WHERE id = $delete_id AND user_id = $user_id";
        if ($conn->query($delete_query) === TRUE) {
            $_SESSION['message'] = "Tempahan berjaya dipadam.";
        } else {
            $_SESSION['message'] = "Ralat berlaku semasa memadam tempahan.";
        }
    }
    header("Location: dashboard.php");
    exit();
}

$booking_query = "SELECT bookings.*, hotels.hotel_name, hotels.location 
                  FROM bookings 
                  JOIN hotels ON bookings.hotel_id = hotels.id 
                  WHERE bookings.user_id = $user_id";
$booking_result = $conn->query($booking_query);

$hotel_query = "SELECT * FROM hotels";
$hotel_result = $conn->query($hotel_query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengguna</title>
    <!-- Gantikan bahagian <style> anda dengan yang ini -->
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
        background: #f9f9f9;
        color: #333;
    }

    header {
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
        color: white;
        padding: 25px;
        text-align: center;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        z-index: 100;
    }

    .top-right {
        position: absolute;
        top: 20px;
        right: 30px;
    }

    .logout-btn {
        background: #f44336;
        padding: 8px 16px;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .logout-btn:hover {
        background: #d32f2f;
        transform: scale(1.05);
    }

    .container {
        padding: 30px;
        max-width: 1200px;
        margin: auto;
    }

    h3 {
        margin-top: 40px;
        margin-bottom: 15px;
        font-size: 1.5rem;
        border-bottom: 2px solid #4CAF50;
        display: inline-block;
    }

    a.button {
        background-color: #4CAF50;
        color: white;
        padding: 8px 14px;
        text-decoration: none;
        border-radius: 6px;
        transition: background 0.3s ease, transform 0.3s ease;
        display: inline-block;
    }

    a.button:hover {
        background-color: #388E3C;
        transform: scale(1.05);
    }

    a.button.delete {
        background-color: #f44336;
    }

    a.button.delete:hover {
        background-color: #c62828;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
        animation: fadeIn 1s ease;
    }

    th, td {
        padding: 14px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    th {
        background-color: #f4f4f4;
    }

    .hotel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        animation: slideUp 1s ease;
    }

    .hotel-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .hotel-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .hotel-card img {
        width: 100%;
        height: 190px;
        object-fit: cover;
    }

    .hotel-info {
        padding: 18px;
        flex-grow: 1;
    }

    .hotel-info h4 {
        margin: 0 0 12px;
        color: #2E7D32;
    }

    .hotel-info p {
        margin: 4px 0;
        color: #555;
        font-size: 0.95rem;
    }

    .hotel-info a {
        margin-top: 12px;
        text-align: center;
    }

    /* Animasi */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(50px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

</head>
<body>

<header>
    <h2>Selamat Datang, <?php echo htmlspecialchars($user_name); ?>!</h2>
    <div class="top-right"><a class="logout-btn" href="logout.php">Log Keluar</a></div>
</header>

<div class="container">
    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <h3>Tempahan Anda</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Hotel</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Kemaskini</th>
                <th>Padam</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($booking_result->num_rows > 0) {
                while ($booking = $booking_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['hotel_name']) ?></td>
                        <td><?= htmlspecialchars($booking['check_in']) ?></td>
                        <td><?= htmlspecialchars($booking['check_out']) ?></td>
                        <td><?= htmlspecialchars($booking['location']) ?></td>
                        <td><?= $booking['status'] ?? 'Dalam Proses' ?></td>
                        <td><a class="button update" href="update_booking.php?booking_id=<?= urlencode($booking['id']) ?>">Kemaskini</a></td>
                        <td><a class="button delete" href="dashboard.php?delete_id=<?= urlencode($booking['id']) ?>" onclick="return confirm('Padam tempahan ini?');">Padam</a></td>
                    </tr>
            <?php }} else { ?>
                <tr><td colspan="7">Tiada tempahan ditemui.</td></tr>
            <?php } ?>
        </tbody>
    </table>

    <h3>Senarai Hotel</h3>
    <div class="hotel-grid">
        <?php if ($hotel_result->num_rows > 0) {
            while ($hotel = $hotel_result->fetch_assoc()) { 
                $image = !empty($hotel['image_url']) ? $hotel['image_url'] : 'https://via.placeholder.com/400x200?text=Hotel'; ?>
                <div class="hotel-card">
                    <img src="<?= $image ?>" alt="Gambar Hotel">
                    <div class="hotel-info">
                        <h4><?= htmlspecialchars($hotel['hotel_name']) ?></h4>
                        <p><strong>Jenis:</strong> <?= htmlspecialchars($hotel['room_type']) ?></p>
                        <p><strong>Harga:</strong> RM<?= htmlspecialchars($hotel['price']) ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($hotel['location']) ?></p>
                        <a class="button book" href="booking.php?hotel_id=<?= urlencode($hotel['id']) ?>">Tempah</a>
                    </div>
                </div>
        <?php }} else { ?>
            <p>Tiada hotel tersedia.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
