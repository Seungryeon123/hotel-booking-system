<?php
// Gantikan dengan butiran sambungan sebenar
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_booking";

// Sambungan ke MySQL
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Semak sambungan
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
