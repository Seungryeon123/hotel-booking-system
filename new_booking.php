<?php
// Mulakan sesi dan sambungan ke pangkalan data
session_start();
include('connection.php');

// Semak jika pengguna sudah log masuk
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Arahkan ke halaman login jika pengguna belum log masuk
    exit;
}

// Proses borang jika dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari borang
    $hotel_id = $_POST['hotel_id'];
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $user_id = $_SESSION['user_id'];  // Dapatkan user_id dari sesi

    // Query untuk memasukkan data tempahan ke pangkalan data
    $query = "INSERT INTO bookings (user_id, hotel_id, check_in, check_out) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $user_id, $hotel_id, $checkin_date, $checkout_date);

    // Semak jika berjaya dimasukkan
    if ($stmt->execute()) {
        echo "Tempahan berjaya!";
    } else {
        echo "Ralat: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempahan Baru</title>
</head>
<body>

    <h2>Buat Tempahan Baru</h2>

    <form action="new_booking.php" method="POST">
        <label for="hotel_id">Pilih Hotel:</label><br>
        <select name="hotel_id" id="hotel_id" required>
            <?php
            // Dapatkan senarai hotel untuk dropdown
            $hotel_query = "SELECT * FROM hotels";
            $result = $conn->query($hotel_query);
            
            // Paparkan senarai hotel dalam dropdown
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['hotel_name'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="checkin_date">Tarikh Check-in:</label><br>
        <input type="date" name="checkin_date" id="checkin_date" required><br><br>

        <label for="checkout_date">Tarikh Check-out:</label><br>
        <input type="date" name="checkout_date" id="checkout_date" required><br><br>

        <input type="submit" value="Buat Tempahan">
    </form>

</body>
</html>

<?php
// Tutup sambungan pangkalan data
$conn->close();
?>
