<?php
session_start();
include('config.php');

// Semak login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

// Kemaskini status
if (isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];
    $conn->query("UPDATE bookings SET status = '$new_status' WHERE booking_id = $booking_id");
    header("Location: admin_dashboard.php");
    exit();
}

// Padam tempahan
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE booking_id = $delete_id");
    header("Location: admin_dashboard.php");
    exit();
}

// Carian dan tapisan
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$filter_sql = "";
if (!empty($search)) {
    $filter_sql .= " AND (users.name LIKE '%$search%' OR hotels.hotel_name LIKE '%$search%')";
}
if (!empty($status_filter)) {
    $filter_sql .= " AND bookings.status = '$status_filter'";
}

// Query tempahan
$query = "SELECT bookings.booking_id, users.name AS user_name, hotels.hotel_name AS hotel_name, 
                 hotels.review AS hotel_review, check_in, check_out, status, hotels.id AS hotel_id
          FROM bookings 
          JOIN users ON bookings.user_id = users.id 
          JOIN hotels ON bookings.hotel_id = hotels.id 
          WHERE 1 $filter_sql
          ORDER BY bookings.booking_id DESC";
$result = $conn->query($query);

// Query pembayaran
$query_payments = "SELECT p.id, b.booking_id, p.payment_method, p.amount, p.payment_status, p.payment_date
                   FROM payments p
                   INNER JOIN bookings b ON p.booking_id = b.booking_id";
$result_payments = $conn->query($query_payments);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { margin: 0; padding: 0; background: #f0f4f8; }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        h2 { text-align: center; color: #333; margin-bottom: 30px; }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .top-bar .welcome { font-size: 18px; font-weight: 600; color: #333; }

        .logout {
            background: #ef4444;
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout:hover { background: #dc2626; }

        .search-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-filter input, .search-filter select {
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .search-filter button {
            padding: 8px 16px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        .search-filter button:hover { background: #2563eb; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #3b82f6;
            color: white;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            background: #f9fafb;
            color: #333;
            font-size: 14px;
        }

        .btn-update {
            background: #10b981;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-update:hover { background: #059669; }

        .btn-delete {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-delete:hover { background: #dc2626; }

        .action-form {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr { display: block; }
            th { display: none; }
            td {
                position: relative;
                padding-left: 50%;
                text-align: left;
                border: none;
                border-bottom: 1px solid #ddd;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 16px;
                top: 14px;
                font-weight: bold;
                font-size: 12px;
                color: #555;
                text-transform: uppercase;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <div class="welcome">Selamat datang, <?= htmlspecialchars($admin_name) ?>!</div>
        <a href="logout.php" class="logout">Log Keluar</a>
    </div>

    <form method="GET" class="search-filter">
        <input type="text" name="search" placeholder="Cari nama pengguna / hotel..." value="<?= htmlspecialchars($search) ?>">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="Dalam Proses" <?= $status_filter == 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
            <option value="Selesai" <?= $status_filter == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="Dibatalkan" <?= $status_filter == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
        </select>
        <button type="submit">Cari</button>
    </form>

    <h2>Senarai Tempahan Hotel</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pengguna</th>
                <th>Hotel</th>
                <th>Ulasan</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
                <th>Kemaskini</th>
                <th>Padam</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td data-label="ID"><?= $row['booking_id'] ?></td>
                <td data-label="Pengguna"><?= htmlspecialchars($row['user_name']) ?></td>
                <td data-label="Hotel">
                    <a href="hotel_details.php?id=<?= $row['hotel_id'] ?>" style="color:#3b82f6;text-decoration:none;">
                        <?= htmlspecialchars($row['hotel_name']) ?>
                    </a>
                </td>
                <td data-label="Ulasan"><?= htmlspecialchars($row['hotel_review']) ?></td>
                <td data-label="Check-in"><?= $row['check_in'] ?></td>
                <td data-label="Check-out"><?= $row['check_out'] ?></td>
                <td data-label="Status"><?= htmlspecialchars($row['status']) ?></td>
                <td data-label="Kemaskini">
                    <form method="POST" class="action-form">
                        <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
                        <select name="status">
                            <option value="Dalam Proses" <?= $row['status'] == 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
                            <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="Dibatalkan" <?= $row['status'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                        <button type="submit" name="update_status" class="btn-update">Kemaskini</button>
                    </form>
                </td>
                <td data-label="Padam">
                    <a href="admin_dashboard.php?delete=<?= $row['booking_id'] ?>" class="btn-delete" onclick="return confirm('Padam tempahan ini?');">Padam</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Laporan Pembayaran</h2>
    <table>
        <thead>
            <tr>
                <th>ID Tempahan</th>
                <th>Kaedah</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tarikh</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result_payments->fetch_assoc()): ?>
            <tr>
                <td><?= $row['booking_id'] ?></td>
                <td><?= $row['payment_method'] ?></td>
                <td>RM <?= number_format($row['amount'], 2) ?></td>
                <td><?= $row['payment_status'] ?></td>
                <td><?= $row['payment_date'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
