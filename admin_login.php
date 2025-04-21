<?php
session_start();
include('config.php');

// Semak jika admin sedang login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Semak dalam jadual admin (bukan users)
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Nama pengguna atau kata laluan salah!";
        }
    } else {
        $error = "Ralat semasa cuba log masuk.";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Log Masuk Admin</title>
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
            background: #28a745;
            color: white;
            font-weight: bold;
            border: none;
            margin-top: 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            color: #007BFF;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Log Masuk Admin</h2>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form action="admin_login.php" method="POST">
            <label for="username">Nama Pengguna Admin:</label>
            <input type="text" name="username" required>

            <label for="password">Kata Laluan:</label>
            <input type="password" name="password" required>

            <button type="submit">Log Masuk Admin</button>
        </form>

        <div class="back-link">
            <p><a href="login.php">← Kembali ke Log Masuk Pengguna</a></p>
        </div>
    </div>
</body>
</html>
