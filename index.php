<?php
session_start();
include('config.php');

// Dapatkan senarai hotel
$query = "SELECT * FROM hotels";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Hotel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Selamat datang, <?php echo $_SESSION['name']; ?>!</h2>
        <p><a href="logout.php">Log Keluar</a></p>

        <h3>Senarai Hotel</h3>
        <?php while($hotel = $result->fetch_assoc()) { ?>
            <div class="hotel">
                <h4><?php echo $hotel['hotel_name']; ?></h4>
                <p>Harga: RM <?php echo $hotel['price']; ?></p>
                <p>Lokasi: <?php echo $hotel['location']; ?></p>
                <a href="booking.php?hotel_id=<?php echo $hotel['id']; ?>">Tempah</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>
