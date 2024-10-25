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
        echo "Pendaftaran berhasil dihapus.";
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
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button[type="submit"] {
            background-color: #ff0000;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #ff3333;
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
                <td><img src="<?php echo htmlspecialchars($row['bukti_transfer']); ?>" alt="Bukti Transfer" width="100"></td>
                <td>
                    <form method="POST" action="" onsubmit="return confirmDelete();">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="delete">Delete</button>
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
