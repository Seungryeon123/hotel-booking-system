<?php
// Mulakan session dan sambungan ke pangkalan data
session_start();
include('connection.php');

// Semak jika admin sudah log masuk
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Arahkan ke halaman login jika admin belum log masuk
    exit;
}

// Proses borang jika dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari borang
    $hotel_name = $_POST['hotel_name'];
    $price = $_POST['price'];
    $room_type = $_POST['room_type'];
    $location = $_POST['location'];
    $rating = $_POST['rating'];

    // Query untuk memasukkan data hotel ke pangkalan data
    $query = "INSERT INTO hotels (hotel_name, price, room_type, location, rating)
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsss", $hotel_name, $price, $room_type, $location, $rating);

    // Semak jika berjaya dimasukkan
    if ($stmt->execute()) {
        echo "Hotel berjaya ditambah!";
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
    <title>Tambah Hotel Baru</title>
</head>
<body>

    <h2>Tambah Hotel Baru</h2>
    
    <form action="add_hotel.php" method="POST">
        <label for="hotel_name">Nama Hotel:</label><br>
        <input type="text" name="hotel_name" id="hotel_name" required><br><br>

        <label for="price">Harga:</label><br>
        <input type="number" name="price" id="price" step="0.01" required><br><br>

        <label for="room_type">Jenis Bilik:</label><br>
        <input type="text" name="room_type" id="room_type" required><br><br>

        <label for="location">Lokasi:</label><br>
        <input type="text" name="location" id="location" required><br><br>

        <label for="rating">Penilaian (1-5):</label><br>
        <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>

        <input type="submit" value="Tambah Hotel">
    </form>

</body>
</html>

<?php
// Tutup sambungan pangkalan data
$conn->close();
?>
