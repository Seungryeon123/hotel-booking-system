<?php
// Gantikan dengan butiran sambungan sebenar
$servername = "localhost"; // Nama server, 'localhost' untuk XAMPP atau jika menggunakan server lain, gantikan dengan alamat IP atau domain
$username = "root"; // Nama pengguna MySQL, default untuk XAMPP adalah 'root'
$password = ""; // Kata laluan, kosongkan untuk XAMPP jika tiada kata laluan
$dbname = "hotel_booking"; // Nama database yang digunakan, pastikan ianya betul

// Sambungan ke MySQL
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Semak sambungan
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // Jika sambungan gagal, mesej error akan dipaparkan
}
?>
