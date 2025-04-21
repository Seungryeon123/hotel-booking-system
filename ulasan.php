<?php
session_start();
include('config.php');

// Pastikan pengguna sudah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Dapatkan id pengguna yang sedang log masuk
$user_id = $_SESSION['user_id'];

// Semak jika pengguna sudah membuat tempahan dan sudah tiba masa untuk memberi ulasan
if (isset($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];

    // Periksa jika pengguna sudah menginap di hotel ini
    $query = "SELECT * FROM bookings WHERE user_id = $user_id AND hotel_id = $hotel_id AND status = 'Selesai'";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        // Jika tiada tempahan yang sesuai, arahkan semula ke halaman tempahan
        echo "<script>alert('Anda perlu menginap di hotel ini untuk memberikan ulasan.'); window.location.href = 'tempahan.php';</script>";
        exit();
    }
}

// Proses borang apabila dihantar
if (isset($_POST['submit_review'])) {
    $hotel_id = $_POST['hotel_id'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    // Masukkan ulasan ke dalam jadual reviews
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, hotel_id, review, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $user_id, $hotel_id, $review, $rating);
    $stmt->execute();
    $stmt->close();

    // Alihkan kembali ke halaman butiran hotel selepas hantar
    header("Location: hotel_details.php?hotel_id=" . $hotel_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Ulasan Hotel</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        select, input[type="number"] {
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Berikan Ulasan untuk Hotel</h2>

        <!-- Borang ulasan -->
        <form method="POST">
            <input type="hidden" name="hotel_id" value="<?= htmlspecialchars($hotel_id) ?>">

            <label for="rating">Penilaian (1 hingga 5):</label>
            <select name="rating" required>
                <option value="1">1 - Sangat Buruk</option>
                <option value="2">2 - Buruk</option>
                <option value="3">3 - Sederhana</option>
                <option value="4">4 - Baik</option>
                <option value="5">5 - Sangat Baik</option>
            </select>

            <label for="review">Ulasan Anda:</label>
            <textarea name="review" required></textarea>

            <button type="submit" name="submit_review">Hantar Ulasan</button>
        </form>
    </div>
</body>
</html>
