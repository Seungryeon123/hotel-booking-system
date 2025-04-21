<?php
session_start();
include('config.php');

// Dapatkan ID hotel dari URL
$hotel_id = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;

// Dapatkan maklumat hotel
$query = "SELECT * FROM hotels WHERE id = $hotel_id";
$hotel_result = $conn->query($query);

if ($hotel_result && $hotel_result->num_rows > 0) {
    $hotel = $hotel_result->fetch_assoc();
} else {
    echo "<script>alert('Hotel tidak dijumpai.'); window.location.href='hotel_list.php';</script>";
    exit;
}

// Dapatkan semua ulasan
$review_query = "SELECT reviews.*, users.name AS user_name FROM reviews 
                 JOIN users ON reviews.user_id = users.id 
                 WHERE reviews.hotel_id = $hotel_id";
$review_result = $conn->query($review_query);

// Proses ulasan baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'] ?? 0;
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    if ($user_id > 0) {
        $insert_query = "INSERT INTO reviews (hotel_id, user_id, rating, review_text) 
                         VALUES ($hotel_id, $user_id, $rating, '$review_text')";
        if ($conn->query($insert_query) === TRUE) {
            echo "<script>alert('Ulasan anda telah disimpan.'); window.location.href='hotel_details.php?hotel_id=$hotel_id';</script>";
        } else {
            echo "<script>alert('Ralat menyimpan ulasan.');</script>";
        }
    } else {
        echo "<script>alert('Sila log masuk untuk memberi ulasan.'); window.location.href='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($hotel['hotel_name']) ?> - Butiran Hotel</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        .hotel-header img {
            width: 100%;
            border-radius: 10px;
            height: 350px;
            object-fit: cover;
        }
        h1 {
            margin: 20px 0;
            font-size: 32px;
            color: #222;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .info-box {
            background: #f2f2f2;
            padding: 15px;
            border-radius: 8px;
        }
        .reviews-section {
            margin-top: 40px;
        }
        .review {
            background: #f7f7f7;
            padding: 15px;
            border-left: 5px solid #4caf50;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        .review-rating {
            color: #ff9800;
        }
        .review-body {
            margin-top: 8px;
        }
        .review-form {
            margin-top: 30px;
        }
        .review-form textarea, .review-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .review-form button {
            padding: 10px 18px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .review-form button:hover {
            background: #0056b3;
        }
        .book-now {
            margin-top: 30px;
            text-align: center;
        }
        .book-now a {
            background: #28a745;
            padding: 12px 24px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
        }
        .book-now a:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Hotel + Gambar -->
        <div class="hotel-header">
            <img src="uploads/<?= htmlspecialchars($hotel['image']) ?>" alt="Gambar Hotel">
        </div>

        <h1><?= htmlspecialchars($hotel['hotel_name']) ?></h1>

        <!-- Info Hotel -->
        <div class="info-grid">
            <div class="info-box">
                <p><strong>Harga / Malam:</strong> RM<?= number_format($hotel['price'], 2) ?></p>
                <p><strong>Jenis Bilik:</strong> <?= htmlspecialchars($hotel['room_type']) ?></p>
                <p><strong>Kemudahan:</strong> <?= htmlspecialchars($hotel['facilities']) ?></p>
            </div>
            <div class="info-box">
                <p><strong>Lokasi:</strong> <?= htmlspecialchars($hotel['location']) ?></p>
                <p><strong>Rating:</strong> <?= number_format($hotel['rating'], 1) ?> / 5</p>
            </div>
        </div>

        <!-- Butang Tempah -->
        <div class="book-now">
            <a href="booking.php?hotel_id=<?= $hotel_id ?>">Tempah Sekarang</a>
        </div>

        <!-- Ulasan Pengguna -->
        <div class="reviews-section">
            <h2>Ulasan Pengguna</h2>
            <?php while ($review = $review_result->fetch_assoc()): ?>
                <div class="review">
                    <div class="review-header">
                        <span><?= htmlspecialchars($review['user_name']) ?></span>
                        <span class="review-rating"><?= str_repeat("★", $review['rating']) ?></span>
                    </div>
                    <div class="review-body">
                        <?= htmlspecialchars($review['review_text']) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Borang Ulasan -->
        <div class="review-form">
            <h2>Beri Ulasan Anda</h2>
            <form method="POST">
                <label for="rating">Rating:</label>
                <select name="rating" required>
                    <option value="1">1 - Teruk</option>
                    <option value="2">2 - Boleh Diterima</option>
                    <option value="3">3 - Baik</option>
                    <option value="4">4 - Sangat Baik</option>
                    <option value="5">5 - Cemerlang</option>
                </select>

                <label for="review_text">Ulasan:</label>
                <textarea name="review_text" rows="4" required></textarea>

                <button type="submit" name="submit_review">Hantar Ulasan</button>
            </form>
        </div>
    </div>
</body>
</html>
