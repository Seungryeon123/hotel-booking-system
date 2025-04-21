<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];
    $query = "SELECT * FROM rooms WHERE id = $room_id";
    $result = $conn->query($query);
    $room = $result->fetch_assoc();
}

// Mengemas kini bilik
if (isset($_POST['update_room'])) {
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];

    $update_query = "UPDATE rooms SET room_type = '$room_type', price = '$price' WHERE id = $room_id";
    if ($conn->query($update_query) === TRUE) {
        echo "Bilik berjaya dikemaskini!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Kemaskini Bilik</title>
</head>
<body>

<h2>Kemaskini Bilik</h2>

<form method="POST" action="update_room.php?id=<?php echo $room_id; ?>">
    <label for="room_type">Jenis Bilik:</label>
    <input type="text" name="room_type" value="<?php echo $room['room_type']; ?>" required><br><br>
    <label for="price">Harga (RM):</label>
    <input type="number" name="price" value="<?php echo $room['price']; ?>" step="0.01" required><br><br>
    <button type="submit" name="update_room">Kemaskini Bilik</button>
</form>

</body>
</html>
