<?php
session_start();
include('config.php');

// Sembunyikan error kepada pengguna akhir (log jika perlu)
error_reporting(0);
ini_set('display_errors', 0);

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_email = trim($_POST['username_email']);
    $password = trim($_POST['password']);

    // Elakkan SQL injection (guna prepared statement)
    $stmt = $conn->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND password = ?");
    $stmt->bind_param("sss", $username_email, $username_email, $password);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name']; // Menyimpan nama pengguna ke dalam sesi

            // Redirect ke dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Nama pengguna/email atau kata laluan salah!";
        }
    } else {
        $error = "Ralat semasa cuba log masuk. Sila cuba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Log Masuk Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007BFF;
            color: white;
            font-weight: bold;
            border: none;
            margin-top: 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            color: #007BFF;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Log Masuk</h2>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form action="login.php" method="POST">
            <label for="username_email">Nama Pengguna atau Email:</label>
            <input type="text" name="username_email" required>

            <label for="password">Kata Laluan:</label>
            <input type="password" name="password" required>

            <button type="submit">Log Masuk</button>
        </form>

        <div class="register-link">
            <p>Tiada akaun? <a href="register.php">Daftar Akaun</a></p>
            <p>Admin? <a href="admin_login.php">Log Masuk Admin</a></p>
        </div>
    </div>
</body>
</html>
