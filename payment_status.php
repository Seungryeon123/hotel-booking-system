<?php
// Sambungan ke pangkalan data
include('db_connection.php');

// Ambil id pembayaran dari URL
$payment_id = $_GET['id'];

// Dapatkan maklumat pembayaran
$query = "SELECT * FROM payments WHERE id = $payment_id";
$result = mysqli_query($conn, $query);
$payment = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
</head>
<body>

<h1>Payment Status</h1>

<p>Booking ID: <?php echo $payment['booking_id']; ?></p>
<p>Payment Method: <?php echo ucfirst($payment['payment_method']); ?></p>
<p>Amount Paid: RM <?php echo number_format($payment['amount'], 2); ?></p>
<p>Status: <?php echo ucfirst($payment['payment_status']); ?></p>

<?php
// Jika status pembayaran adalah pending, anda boleh memaparkan pautan untuk melengkapkan pembayaran
if ($payment['payment_status'] == 'pending') {
    echo "<p>Your payment is still pending. Please complete the transaction.</p>";
} elseif ($payment['payment_status'] == 'completed') {
    echo "<p>Your payment was completed successfully!</p>";
} else {
    echo "<p>Your payment failed. Please try again.</p>";
}
?>

</body>
</html>
