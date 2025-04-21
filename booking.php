<?php
session_start();
include 'config.php';

// Inisialisasi pembolehubah message
$message = "";

// Cek jika pengguna belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil senarai hotel untuk dimasukkan ke dalam bilik
$hotel_query = mysqli_query($conn, "SELECT * FROM hotels");
$hotels = [];
while ($row = mysqli_fetch_assoc($hotel_query)) {
    $hotels[] = $row;
}

// Proses tempahan bila submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $hotel_id = $_POST['hotel_id']; // hotel_id untuk simpan dalam tempahan
    $room_id = $_POST['room_id'];
    $checkin = $_POST['checkin_date'];
    $checkout = $_POST['checkout_date'];

    // Validasi
    if ($checkin >= $checkout) {
        $message = "<p class='text-red-600'>Tarikh tidak sah. Tarikh keluar mesti selepas tarikh masuk.</p>";
    } else {
        // Semak kekosongan bilik
        $check_room = mysqli_query($conn, "SELECT * FROM bookings WHERE room_id='$room_id' AND (
            ('$checkin' BETWEEN checkin_date AND checkout_date) OR
            ('$checkout' BETWEEN checkin_date AND checkout_date)
        )");

        if (mysqli_num_rows($check_room) > 0) {
            $message = "<p class='text-yellow-600'>Bilik telah ditempah pada tarikh tersebut.</p>";
        } else {
            // Masukkan tempahan
            $query = "INSERT INTO bookings (user_id, hotel_id, room_id, checkin_date, checkout_date, status)
                      VALUES ('$user_id', '$hotel_id', '$room_id', '$checkin', '$checkout', 'Booked')";
            if (mysqli_query($conn, $query)) {
                $message = "<p class='text-green-600'>Tempahan berjaya dibuat!</p>";
            } else {
                $message = "<p class='text-red-600'>Ralat semasa membuat tempahan. Sila cuba lagi.</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Tempah Bilik</title>
    <script>
    function loadRooms(hotelId) {
        if (hotelId === "") {
            document.getElementById("room_select").innerHTML = "<option value=''>Pilih hotel dahulu</option>";
            return;
        }

        fetch('get_rooms.php?hotel_id=' + hotelId)
            .then(res => res.json())
            .then(data => {
                let options = "<option value=''>Pilih bilik</option>";
                data.forEach(room => {
                    options += `<option value="${room.id}">${room.room_type} - RM${room.price}/mlm</option>`;
                });
                document.getElementById("room_select").innerHTML = options;
            });
    }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4">Tempah Bilik</h2>

        <!-- Paparan Mesej -->
        <?php if (!empty($message)): ?>
            <div class="mb-4 p-3 <?php echo strpos($message, 'text-red-600') ? 'bg-red-100' : (strpos($message, 'text-yellow-600') ? 'bg-yellow-100' : 'bg-green-100'); ?> rounded">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <!-- Pilih Hotel -->
            <div>
                <label class="block font-semibold">Hotel</label>
                <select name="hotel_id" id="hotel_select" onchange="loadRooms(this.value)" required class="w-full border p-2 rounded">
                    <option value="">Pilih hotel</option>
                    <?php foreach ($hotels as $hotel): ?>
                        <option value="<?= $hotel['id'] ?>"><?= $hotel['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pilih Bilik -->
            <div>
                <label class="block font-semibold">Jenis Bilik</label>
                <select name="room_id" id="room_select" required class="w-full border p-2 rounded">
                    <option value="">Pilih bilik dahulu</option>
                </select>
            </div>

            <!-- Tarikh Masuk -->
            <div>
                <label class="block font-semibold">Tarikh Masuk</label>
                <input type="date" name="checkin_date" required class="w-full border p-2 rounded">
            </div>

            <!-- Tarikh Keluar -->
            <div>
                <label class="block font-semibold">Tarikh Keluar</label>
                <input type="date" name="checkout_date" required class="w-full border p-2 rounded">
            </div>

            <!-- Butang Tempah -->
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tempah</button>
        </form>
    </div>
</body>
</html>
