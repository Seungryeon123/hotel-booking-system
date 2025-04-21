<?php
// Sambung ke pangkalan data
$conn = mysqli_connect("localhost", "root", "", "hotel_booking");

// Semak sambungan
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Semak sama ada 'booking_id' wujud dalam URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Dapatkan maklumat tempahan daripada pangkalan data
    $sql = "SELECT * FROM bookings WHERE booking_id = '$booking_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
    } else {
        echo "Tempahan tidak dijumpai.";
        exit;
    }
} else {
    echo "Booking ID tidak wujud.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar Tempahan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            font-size: 18px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-size: 16px;
            color: #333;
        }
        select, input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bayar Untuk Tempahan ID: <?php echo $booking['booking_id']; ?></h2>
        
        <div class="details">
            <p><strong>Nama Pengguna:</strong> <?php echo $booking['user_id']; ?></p>
            <p><strong>Tarikh Masuk:</strong> <?php echo $booking['check_in']; ?></p>
            <p><strong>Tarikh Keluar:</strong> <?php echo $booking['check_out']; ?></p>
            <p><strong>Jumlah Yang Perlu Dibayar:</strong> RM<?php echo $booking['nights'] * 120; // Harga bilik anggaran ?></p>
        </div>

        <!-- Borang pembayaran -->
        <form action="process_payment.php" method="POST">
            <div class="form-group">
                <label for="payment_method">Pilih Kaedah Pembayaran:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="credit_card">Kad Kredit</option>
                    <option value="bank_transfer">Pemindahan Bank</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Jumlah Bayaran (RM):</label>
                <input type="text" name="amount" id="amount" value="<?php echo $booking['nights'] * 120; ?>" readonly>
            </div>

            <input type="submit" value="Bayar Sekarang">
        </form>
    </div>

    <div class="footer">
        <p>&copy; 2025 Hotel Booking. Semua hak cipta terpelihara.</p>
    </div>
</body>
</html>

<?php
// Tutup sambungan pangkalan data
mysqli_close($conn);
?>
