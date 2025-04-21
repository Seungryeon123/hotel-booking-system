<?php
session_start();
include('config.php');

// Semak jika admin telah log masuk
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Menambah bilik baru
if (isset($_POST['add_room'])) {
    $hotel_id = $_POST['hotel_id'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];

    $query = "INSERT INTO rooms (hotel_id, room_type, price) VALUES ('$hotel_id', '$room_type', '$price')";
    if ($conn->query($query) === TRUE) {
        echo "Bilik berjaya ditambah!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Menghapuskan bilik
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query = "DELETE FROM rooms WHERE id = $delete_id";
    if ($conn->query($query) === TRUE) {
        header('Location: manage_rooms.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Mengambil semua bilik
$query = "SELECT rooms.id, rooms.hotel_id, rooms.room_type, rooms.price, hotels.hotel_name 
          FROM rooms
          JOIN hotels ON rooms.hotel_id = hotels.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Pengurusan Bilik Hotel</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        a.button {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
        }
        a.button.delete {
            background-color: #f44336;
        }
        h2, h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>

<h2>Pengurusan Bilik Hotel</h2>

<h3>Tambah Bilik Baru</h3>
<form method="POST" action="manage_rooms.php">
    <label for="hotel_id">Hotel:</label>
    <select name="hotel_id" required>
        <?php
        // Dapatkan semua hotel untuk pilihan
        $hotel_query = "SELECT * FROM hotels";
        $hotel_result = $conn->query($hotel_query);
        while ($hotel = $hotel_result->fetch_assoc()) {
            echo "<option value='{$hotel['id']}'>{$hotel['hotel_name']}</option>";
        }
        ?>
    </select><br><br>
    <label for="room_type">Jenis Bilik:</label>
    <input type="text" name="room_type" required><br><br>
    <label for="price">Harga (RM):</label>
    <input type="number" name="price" step="0.01" required><br><br>
    <button type="submit" name="add_room">Tambah Bilik</button>
</form>

<h3>Senarai Bilik</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Hotel</th>
            <th>Jenis Bilik</th>
            <th>Harga (RM)</th>
            <th>Tindakan</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['hotel_name']; ?></td>
            <td><?php echo $row['room_type']; ?></td>
            <td><?php echo number_format($row['price'], 2); ?></td>
            <td>
                <a class="button" href="update_room.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a class="button delete" href="manage_rooms.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Padam bilik ini?');">Padam</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
