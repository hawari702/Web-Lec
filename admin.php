<?php
session_start();
// Nonaktifkan tampilan error ke pengguna
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Cek jika token belum ada, buat token baru
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32)); // Buat token baru
}

$conn = new mysqli("localhost", "root", "", "concert_system");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangani pengubahan status konser
if (isset($_POST['toggle_status'])) {
    $id = $_POST['id'];
    $current_status = $_POST['current_status'];
    
    // Update logika status
    $new_status = ($current_status === 'open') ? 'cancel' : 'open';

    $stmt = $conn->prepare("UPDATE concerts SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    $stmt->close();
}

// Menangani penambahan konser
// Menangani penambahan konser
// Menangani penambahan konser
if (isset($_POST['add'])) {
    // Verifikasi token
    if ($_POST['token'] !== $_SESSION['token']) {
        die("Token tidak valid, permintaan dibatalkan.");
    }
    
    // Setelah verifikasi, hapus token untuk mencegah penggunaan ulang
    unset($_SESSION['token']);
    
    $name = trim($_POST['name']);
    $date = trim($_POST['date']);
    $location = trim($_POST['location']);
    $max_participants = $_POST['max_participants'];
    $description = trim($_POST['description']);
    

    // Debug: Print location value
    echo "<script>console.log('Location value: " . $location . "');</script>";
    echo "<script>console.log('POST data: " . json_encode($_POST) . "');</script>"; // Tambahkan ini

    // Validasi lokasi tidak kosong
    if (empty($location)) {
        echo "<script>alert('Lokasi tidak boleh kosong!');</script>";
        return;
    }
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO concerts (name, date, location, max_participants, image, description) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                echo "<script>console.log('Prepare failed: " . $conn->error . "');</script>";
            }
            
            $stmt->bind_param("ssisss", $name, $date, $location, $max_participants, $target_file, $description);
            
            // Debug: Print query values
            echo "<script>console.log('Values being inserted: Name=" . $name . ", Date=" . $date . ", Location=" . $location . ", Max=" . $max_participants . "');</script>";
            
            if (!$stmt->execute()) {
                echo "<script>console.log('Execute failed: " . $stmt->error . "');</script>";
            } else {
                echo "<script>alert('Konser berhasil ditambahkan.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Gagal mengupload gambar.');</script>";
        }
    }
    
    // Menangani upload gambar
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file adalah gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
    }

    // Cek ukuran file (maksimal 5MB)
    if ($_FILES["image"]["size"] > 5000000) {
        $uploadOk = 0;
    }

    // Cek tipe file
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO concerts (name, date, location, max_participants, image, description) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                echo "<script>console.log('Prepare failed: " . $conn->error . "');</script>";
            }
            
            $stmt->bind_param("ssisss", $name, $date, $location, $max_participants, $target_file, $description);
            
            // Debug: Print query values
            echo "<script>console.log('Values being inserted: Name=" . $name . ", Date=" . $date . ", Location=" . $location . ", Max=" . $max_participants . "');</script>";
            
            if (!$stmt->execute()) {
                echo "<script>console.log('Execute failed: " . $stmt->error . "');</script>";
            } else {
                echo "<script>alert('Konser berhasil ditambahkan.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Gagal mengupload gambar.');</script>";
        }
    }
    
} 
    

// Menangani penghapusan konser
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM concerts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Menangani pengeditan konser
$concert = null; // Inisialisasi variabel konser

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM concerts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $concert = $result->fetch_assoc();
    $stmt->close(); // Menutup statement setelah digunakan
}

// Memperbarui konser
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $max_participants = $_POST['max_participants'];
    $description = $_POST['description'];

    // Update gambar jika ada
    $target_file = ""; // Inisialisasi variabel untuk gambar baru
    if (!empty($_FILES["image"]["name"])) {
        // Hanya jika gambar baru diupload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Jika gambar berhasil diupload, update dengan gambar baru
        } else {
            echo "<script>alert('Maaf, ada kesalahan saat mengupload gambar.');</script>";
        }
    }

    // Update data konser
    if ($target_file === "") {
        // Jika tidak ada gambar baru, gunakan gambar yang sudah ada
        $stmt = $conn->prepare("UPDATE concerts SET name = ?, date = ?, location = ?, max_participants = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $date, $location, $max_participants, $description, $id);
    } else {
        // Jika ada gambar baru, update gambar juga
        $stmt = $conn->prepare("UPDATE concerts SET name = ?, date = ?, location = ?, max_participants = ?, image = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $name, $date, $location, $max_participants, $target_file, $description, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Konser berhasil diperbarui.');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui konser.');</script>";
    }
    $stmt->close();
}

// Mengambil daftar konser dan menghitung jumlah pendaftar
$result = $conn->query("SELECT c.*, COUNT(r.id) AS registrant_count 
                        FROM concerts c 
                        LEFT JOIN registrations r ON c.id = r.concert_id 
                        GROUP BY c.id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f2f2f2;
            background-image: url('uploads/bg-login.png');
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        h1 {
            color: #5e3c92; /* Dark purple */
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        input[type="text"], input[type="date"], input[type="number"], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        button[type="submit"], button[type="button"] {
            background-color: #5e3c92; /* Dark purple */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s;
        }
        
        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: #d1a1d4; /* Lighter purple */
        }

        .purple-button {
            background-color: #5e3c92; /* Dark purple */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .purple-button:hover {
            background-color: #d1a1d4; /* Lighter purple */
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
            background-color: #5e3c92; /* Dark purple */
            color: #fff;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #e6e6e6;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Panel</h1>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
        <input type="text" name="name" placeholder="Nama Konser" required>
        <input type="date" name="date" required>
        <input type="text" name="location" placeholder="Lokasi" required oninvalid="this.setCustomValidity('Mohon isi lokasi konser')" oninput="this.setCustomValidity('')">
        <input type="number" name="max_participants" placeholder="Max Pendaftar" required>
        <textarea name="description" rows="5" placeholder="Deskripsi"></textarea>
        <input type="file" name="image" required>
        <button type="submit" name="add">Tambah Konser</button>
    </form>
    <!-- Buttons for View User Account and Data Pendaftar -->
    <button class="purple-button" onclick="window.location.href='view_user.php';">View User Account</button>
    <button class="purple-button" onclick="window.location.href='list_users.php';">Data Pendaftar</button>
    <button class="purple-button" onclick="window.location.href='register.php';">User Registration</button>

    <!-- Tabel konser -->
<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Lokasi</th>
            <th>Peserta Terdaftar</th>
            <th>Status</th>
            <th>Gambar</th>
            <th>Deskripsi</th> <!-- New Description Header -->
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($concert = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($concert['name']); ?></td>
                <td><?php echo htmlspecialchars($concert['date']); ?></td>
                <td><?php echo htmlspecialchars($concert['location']); ?></td>
                <td><?php echo $concert['registrant_count']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $concert['id']; ?>">
                        <input type="hidden" name="current_status" value="<?php echo $concert['status']; ?>">
                        <button type="submit" name="toggle_status"><?php echo ucfirst($concert['status']); ?></button>
                    </form>
                </td>
                <td><img src="<?php echo htmlspecialchars($concert['image']); ?>" alt="Concert Image"></td>
                <td><?php echo htmlspecialchars($concert['description']); ?></td> <!-- New Description Data -->
                <td>
                    <form action="edit_concert.php" method="GET" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $concert['id']; ?>">
                        <button type="submit">Edit</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $concert['id']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus konser ini?');">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


    <?php if ($concert): ?>
        <h2>Edit Konser</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <input type="hidden" name="id" value="<?php echo $concert['id']; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($concert['name']); ?>" required>
            <input type="date" name="date" value="<?php echo htmlspecialchars($concert['date']); ?>" required>
            <input type="text" name="location" value="<?php echo htmlspecialchars($concert['location']); ?>" required>
            <input type="number" name="max_participants" value="<?php echo htmlspecialchars($concert['max_participants']); ?>" required>
            <textarea name="description" rows="5" required><?php echo htmlspecialchars($concert['description']); ?></textarea>
            <input type="file" name="image">
            <button type="submit" name="update">Update Konser</button>
        </form>
    <?php endif; ?>
    <h2>Jumlah Pendaftar per Konser</h2>
        <table>
            <tr>
                <th>Nama Konser</th>
                <th>Jumlah Pendaftar</th>
            </tr>
            <?php 
            // Query untuk mengambil jumlah pendaftar
            $registrants = $conn->query("SELECT c.name AS concert_name, COUNT(r.id) AS registrant_count 
                                           FROM registrations r 
                                           JOIN concerts c ON r.concert_id = c.id 
                                           GROUP BY c.name");

            while ($reg_row = $registrants->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($reg_row['concert_name']); ?></td>
                <td><?php echo htmlspecialchars($reg_row['registrant_count']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

    </div>
</body>
</html>

</div>

</body>
</html>

<?php
$conn->close();
?>