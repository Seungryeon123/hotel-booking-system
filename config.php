<?php
$servername = "localhost";
$username = "root"; // default untuk XAMPP
$password = ""; // default untuk XAMPP
$dbname = "hotel_booking"; // Gantikan dengan nama pangkalan data anda

// Cipta sambungan
$conn = new mysqli($servername, $username, $password, $dbname);

// Semak sambungan
if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}
?>
