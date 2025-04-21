<?php
session_start();
include('config.php');

// Semak jika borang telah dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input pengguna
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // Semak jika pengguna sudah ada dalam pangkalan data berdasarkan email
    $check_query = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // Pengguna sudah ada
        $error = "Username atau email sudah digunakan!";
    } else {
        // Jika pengguna tidak ada, simpan ke dalam pangkalan data
        $insert_query = "INSERT INTO users (username, email, password, name) VALUES ('$username', '$email', '$password', '$name')";
        
        if ($conn->query($insert_query) === TRUE) {
            // Pengguna berjaya didaftarkan, alihkan ke halaman login
            $success_message = "Pendaftaran berjaya! Sila log masuk.";
        } else {
            // Jika ada masalah semasa memasukkan data
            $error = "Ralat semasa mendaftar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akaun</title>
</head>
<body>
    <h2>Daftar Akaun</h2>

    <!-- Papar mesej error jika ada -->
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    
    <!-- Papar mesej kejayaan jika pendaftaran berjaya -->
    <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>

    <!-- Borang Pendaftaran -->
    <form action="register.php" method="POST">
        <label for="name">Nama Penuh:</label>
        <input type="text" name="name" required>
        <br><br>

        <label for="username">Nama Pengguna:</label>
        <input type="text" name="username" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label for="password">Kata Laluan:</label>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Daftar</button>
    </form>

    <!-- Butang Log Masuk -->
    <p>Sudah ada akaun? <a href="login.php"><button>Log Masuk</button></a></p>
</body>
</html>
