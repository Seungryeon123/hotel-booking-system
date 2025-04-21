<?php
session_start();
include('config.php');

error_reporting(0);
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set("error_log", "error_log.txt");

// Pastikan pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel_id = $_POST['hotel_id'];
    
    // Memproses gambar hotel
    if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] == 0) {
        $hotel_image = $_FILES['hotel_image']['name'];
        $hotel_image_tmp = $_FILES['hotel_image']['tmp_name'];
        $hotel_image_path = 'images/hotels/' . basename($hotel_image);
        
        if (move_uploaded_file($hotel_image_tmp, $hotel_image_path)) {
            $hotel_image_url = $hotel_image_path;
        }
    }
    
    // Memproses gambar bilik
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] == 0) {
        $room_image = $_FILES['room_image']['name'];
        $room_image_tmp = $_FILES['room_image']['tmp_name'];
        $room_image_path = 'images/rooms/' . basename($room_image);
        
        if (move_uploaded_file($room_image_tmp, $room_image_path)) {
            $room_image_url = $room_image_path;
        }
    }
    
    // Kemaskini gambar dalam pangkalan data
    if (isset($hotel_image_url) || isset($room_image_url)) {
        $update_query = "UPDATE hotels SET 
                         hotel_image = '" . ($hotel_image_url ?? '') . "',
                         room_image = '" . ($room_image_url ?? '') . "'
                         WHERE id = '$hotel_id'";

        if ($conn->query($update_query) === TRUE) {
            echo "Gambar berjaya dikemas kini!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Upload Gambar Hotel dan Bilik</title>
</head>
<body>
    <h2>Upload Gambar Hotel dan Bilik</h2>
    
    <form action="upload_images.php" method="post" enctype="multipart/form-data">
        <label for="hotel_id">Pilih Hotel:</label>
        <select name="hotel_id" required>
            <?php
            // Dapatkan senarai hotel
            $hotel_query = "SELECT * FROM hotels";
            $hotel_result = $conn->query($hotel_query);
            while ($hotel = $hotel_result->fetch_assoc()) {
                echo "<option value='" . $hotel['id'] . "'>" . $hotel['hotel_name'] . "</option>";
            }
            ?>
        </select><br><br>
        
        <label for="hotel_image">Upload Gambar Hotel:</label>
        <input type="file" name="hotel_image" accept="image/*"><br><br>
        
        <label for="room_image">Upload Gambar Bilik:</label>
        <input type="file" name="room_image" accept="image/*"><br><br>
        
        <button type="submit">Upload Gambar</button>
    </form>
</body>
</html>
