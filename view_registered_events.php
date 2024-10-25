<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "concert_system");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan user sudah login
if (!isset($_SESSION['user_username'])) {
    echo "Harap login terlebih dahulu.";
    exit();
}

// Query untuk mendapatkan daftar event yang telah didaftarkan
$sql = "
    SELECT 
        r.id AS registration_id,
        c.id AS concert_id,
        c.name AS concert_name,
        c.date AS concert_date,
        c.location AS concert_location,
        c.description AS concert_description,
        r.jumlah_tiket AS ticket_quantity,
        r.registration_date AS registration_date,
        r.bukti_transfer AS transfer_proof
    FROM 
        registrations r
    JOIN 
        concerts c ON r.concert_id = c.id
";

// Menjalankan query
$result = $conn->query($sql);

// Cek apakah ada event yang terdaftar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            background-image: url('uploads/bg-login.png');
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            max-width: 100px; /* Adjust width for images */
            border-radius: 4px;
        }

        /* Tambahkan style untuk deskripsi */
        .concert-description {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            th, td {
                padding: 8px;
                font-size: 14px;
            }

            img {
                max-width: 80px; /* Adjust width for images on small screens */
            }
        }
    </style>
    <script>
        function confirmCancel() {
            return confirm("Apakah Anda yakin ingin membatalkan pendaftaran ini?");
        }
    </script>
</head>
<body>

<?php
if ($result->num_rows > 0) {
    echo "<h2>Daftar Event yang Terdaftar:</h2>";
    echo "<table>";
    echo "<tr>
            <th>Nama Event</th>
            <th>Tanggal Event</th>
            <th>Lokasi</th>
            <th>Jumlah Tiket</th>
            <th>Tanggal Registrasi</th>
            <th>Bukti Transfer</th>
            <th>Deskripsi</th>
            <th>Aksi</th> <!-- Tambahkan kolom aksi -->
          </tr>";
          
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['concert_name']) . "</td>
                <td>" . htmlspecialchars($row['concert_date']) . "</td>
                <td>" . htmlspecialchars($row['concert_location']) . "</td>
                <td>" . htmlspecialchars($row['ticket_quantity']) . "</td>
                <td>" . htmlspecialchars($row['registration_date']) . "</td>
                <td><img src='" . htmlspecialchars($row['transfer_proof']) . "' alt='Bukti Transfer'></td>
                <td><div class='concert-description'>" . htmlspecialchars($row['concert_description']) . "</div></td>
                <td>
                    <form method='POST' action='cancel_registration.php' onsubmit='return confirmCancel();'>
                        <input type='hidden' name='registration_id' value='" . $row['registration_id'] . "'>
                        <button type='submit' class='bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600'>Cancel</button>
                    </form>
                </td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "<h2>Belum ada event yang terdaftar.</h2>";
}

// Tutup koneksi
$conn->close();
?>
</body>
</html>
