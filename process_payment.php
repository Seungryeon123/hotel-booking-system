<?php
// Sambungan ke pangkalan data
include('db_connection.php');

// Ambil data daripada form
$booking_id = $_POST['booking_id'];
$payment_method = $_POST['payment_method'];
$amount = $_POST['amount'];

// Tentukan status pembayaran (boleh diubah berdasarkan logik anda)
$payment_status = 'pending'; // Status awal

// Masukkan data pembayaran ke dalam database
$query = "INSERT INTO payments (booking_id, payment_method, amount, payment_status) 
          VALUES ($booking_id, '$payment_method', $amount, '$payment_status')";

if (mysqli_query($conn, $query)) {
    echo "Payment is being processed. You will be notified once completed.";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Alihkan pengguna ke halaman lain atau paparkan mesej
header("Location: payment_status.php?id=" . mysqli_insert_id($conn)); // Mengalih ke status pembayaran
exit;
?>
