<?php
session_start();
include('db.php'); // Pastikan sambungan ke database

// Ambil senarai hotel dari database
$query = "SELECT * FROM hotels";
$result = mysqli_query($conn, $query);

// Semak jika ada hotel dalam database
if(mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Nama Hotel</th><th>Harga</th><th>Jenis Bilik</th><th>Lokasi</th><th>Rating</th><th>Gambar</th><th>Butiran</th><th>Tempah</th></tr>";

    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['hotel_name'] . "</td>";
        echo "<td>RM " . number_format($row['price'], 2) . "</td>";
        echo "<td>" . $row['room_type'] . "</td>";
        echo "<td>" . $row['location'] . "</td>";
        echo "<td>" . $row['rating'] . " ★</td>";
        echo "<td><img src='images/" . $row['image'] . "' width='100'></td>";
        echo "<td><a href='booking.php?hotel_id=" . $row['id'] . "'>Tempah</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Tiada hotel tersedia.";
}
?>

<br>
<a href="logout.php">Log Keluar</a>
