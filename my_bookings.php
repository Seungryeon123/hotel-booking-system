<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT b.booking_id, b.check_in, b.check_out, b.nights, b.status,
               h.hotel_name, h.location, h.hotel_image,
               r.room_type, r.price,
               h.rating, h.review
        FROM bookings b
        JOIN hotels h ON b.hotel_id = h.id
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tempahan Saya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #eef2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 30px;
            color: #333;
        }
        .message {
            text-align: center;
            color: #28a745;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 25px;
        }
        .booking {
            display: flex;
            flex-wrap: wrap;
            background: #f9f9f9;
            border-radius: 12px;
            margin-bottom: 30px;
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        .booking:hover {
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }
        .booking img {
            width: 300px;
            height: 100%;
            object-fit: cover;
        }
        .details {
            flex: 1;
            padding: 20px;
            position: relative;
        }
        .details h3 {
            margin: 0 0 10px;
            color: #2c3e50;
        }
        .details p {
            margin: 8px 0;
            color: #555;
            font-size: 15px;
        }
        .details p i {
            color: #3498db;
            margin-right: 8px;
        }
        .cancel-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .cancel-btn:hover {
            background: #c0392b;
        }
        .rating {
            margin-top: 10px;
        }
        .rating span {
            color: #f39c12;
            font-size: 20px;
        }
        .review {
            margin-top: 15px;
            font-style: italic;
            color: #777;
        }
        @media screen and (max-width: 768px) {
            .booking {
                flex-direction: column;
            }
            .booking img {
                width: 100%;
                height: 200px;
            }
            .cancel-btn {
                position: static;
                margin-top: 15px;
                display: inline-block;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tempahan Saya</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="booking">
                <img src="uploads/<?= $row['hotel_image']; ?>" alt="Hotel Image">
                <div class="details">
                    <h3><?= htmlspecialchars($row['hotel_name']); ?> (<?= htmlspecialchars($row['room_type']); ?>)</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Lokasi: <?= htmlspecialchars($row['location']); ?></p>
                    <p><i class="fa-solid fa-calendar-day"></i> Check-in: <?= $row['check_in']; ?> | Check-out: <?= $row['check_out']; ?></p>
                    <p><i class="fa-solid fa-moon"></i> Malam: <?= $row['nights']; ?></p>
                    <p><i class="fa-solid fa-tags"></i> Harga: <strong>RM <?= number_format($row['price'], 2); ?></strong></p>
                    <p><i class="fa-solid fa-info-circle"></i> Status: <strong><?= $row['status']; ?></strong></p>

                    <!-- Rating -->
                    <div class="rating">
                        <span>Rating: <?= number_format($row['rating'], 1); ?> ⭐</span>
                    </div>

                    <!-- Review -->
                    <div class="review">
                        <strong>Ulasan:</strong><br>
                        <?= !empty($row['review']) ? htmlspecialchars($row['review']) : "Tiada ulasan." ?>
                    </div>

                    <?php if ($row['status'] == 'Dalam Proses'): ?>
                        <a href="cancel_booking.php?id=<?= $row['booking_id']; ?>" class="cancel-btn" onclick="return confirm('Anda pasti mahu batalkan tempahan ini?')">
                            ❌ Batal Tempahan
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">Tiada tempahan dijumpai.</p>
    <?php endif; ?>

</div>

</body>
</html>
