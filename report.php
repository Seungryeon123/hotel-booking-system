<?php
// Mulakan session dan sambungan ke pangkalan data
session_start();
include('connection.php');
require('fpdf/fpdf.php');

// Cek jika admin telah log masuk
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect ke halaman login jika tidak sah
    exit;
}

// Jika butang Generate PDF ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dapatkan laporan PDF
    generate_pdf($conn);
    exit;
}

function generate_pdf($conn) {
    ob_start(); // Elak output sebelum PDF
    
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 14);
            $this->SetFillColor(200, 220, 255); // Header background color
            $this->Cell(0, 10, 'Laporan Tempahan Bilik', 0, 1, 'C', true);
            $this->Ln(5);

            // Table headers
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(20, 10, 'ID', 1, 0, 'C', true);
            $this->Cell(40, 10, 'Nama Pengguna', 1, 0, 'C', true);
            $this->Cell(50, 10, 'Nama Hotel', 1, 0, 'C', true);
            $this->Cell(40, 10, 'Check-in', 1, 0, 'C', true);
            $this->Cell(40, 10, 'Check-out', 1, 1, 'C', true);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Gunakan prepared statement untuk mengelakkan SQL Injection
    $query = "SELECT bookings.booking_id, users.name AS user_name, hotels.hotel_name, bookings.check_in, bookings.check_out
              FROM bookings
              JOIN users ON bookings.user_id = users.id
              JOIN hotels ON bookings.hotel_id = hotels.id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Formatkan tarikh ke dalam format yang mesra pengguna
            $check_in = date('d-m-Y', strtotime($row['check_in']));
            $check_out = date('d-m-Y', strtotime($row['check_out']));

            // Add data to table rows
            $pdf->Cell(20, 10, $row['booking_id'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['user_name'], 1, 0, 'C');
            $pdf->Cell(50, 10, $row['hotel_name'], 1, 0, 'C');
            $pdf->Cell(40, 10, $check_in, 1, 0, 'C');
            $pdf->Cell(40, 10, $check_out, 1, 1, 'C');
        }
    } else {
        $pdf->Cell(0, 10, 'Tiada data tempahan.', 0, 1);
    }

    $conn->close();
    ob_end_clean(); // Bersihkan buffer sebelum output PDF
    $pdf->Output();
}

?>

<!-- HTML untuk paparkan Butang Generate PDF -->
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tempahan Bilik</title>
    <link rel="stylesheet" href="style.css"> <!-- Tambahkan gaya CSS jika perlu -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Butang untuk Generate Laporan PDF -->
    <div class="container">
        <h2>Laporan Tempahan Bilik</h2>
        <form action="report.php" method="post">
            <button type="submit" class="btn">
                Generate Laporan PDF
            </button>
        </form>
        <br>

        <!-- Tunjukkan laporan dalam bentuk jadual -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>Nama Hotel</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT bookings.booking_id, users.name AS user_name, hotels.hotel_name, bookings.check_in, bookings.check_out
                          FROM bookings
                          JOIN users ON bookings.user_id = users.id
                          JOIN hotels ON bookings.hotel_id = hotels.id";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $check_in = date('d-m-Y', strtotime($row['check_in']));
                        $check_out = date('d-m-Y', strtotime($row['check_out']));
                        echo "<tr>
                                <td>{$row['booking_id']}</td>
                                <td>{$row['user_name']}</td>
                                <td>{$row['hotel_name']}</td>
                                <td>{$check_in}</td>
                                <td>{$check_out}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tiada data tempahan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
