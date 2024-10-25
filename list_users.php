<?php
session_start();
$conn = new mysqli("localhost", "root", "", "concert_system");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Handle delete action
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $delete_query = $conn->prepare("DELETE FROM registrations WHERE id = ?");
    $delete_query->bind_param("i", $id);
    if ($delete_query->execute()) {
        
    } else {
        echo "Gagal menghapus pendaftaran: " . $conn->error;
    }
    $delete_query->close();
}

// Query untuk mengambil daftar pendaftaran dan informasi konser
$query = "SELECT r.id, r.name, r.email, c.name AS concert_name, r.registration_date, r.jumlah_tiket, r.nomor_hp, r.bukti_transfer
          FROM registrations r
          JOIN concerts c ON r.concert_id = c.id
          ORDER BY r.registration_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pendaftar</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f2f2f2;
            background-image: url('uploads/bg-login.png'); 
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #5e3c92; /* Dark purple */
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            overflow: hidden; /* Prevent overflow */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            word-wrap: break-word; /* Allow text to wrap */
        }

        th {
            background-color: #5e3c92; /* Dark purple */
            color: #ffffff;
        }

        td {
            background-color: #f9f9f9;
        }

        button[type="submit"] {
            background-color: #5e3c92; /* Dark purple */
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #d1a1d4; /* Lighter purple */
        }

        img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            margin: 10px;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("Apakah Anda yakin ingin menghapus pendaftaran ini?");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Daftar Pendaftar dan Konser</h1>
        <table>
            <tr>
                <th>Nama Pendaftar</th>
                <th>Email</th>
                <th>Nama Konser</th>
                <th>Tanggal Registrasi</th>
                <th>Jumlah Tiket</th>
                <th>Nomor HP</th>
                <th>Bukti Transfer</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['concert_name']); ?></td>
                <td><?php echo htmlspecialchars($row['registration_date']); ?></td>
                <td><?php echo htmlspecialchars($row['jumlah_tiket']); ?></td>
                <td><?php echo htmlspecialchars($row['nomor_hp']); ?></td>
                <td><img src="<?php echo htmlspecialchars($row['bukti_transfer']); ?>" alt="Bukti Transfer"></td>
                <td>
                    <form method="POST" action="" onsubmit="return confirmDelete();">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="delete">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
