<?php
session_start();
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

// Mengambil konser untuk diedit
$concert = null; // Inisialisasi variabel konser
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM concerts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $concert = $result->fetch_assoc();
    $stmt->close();
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
        echo "<script>alert('Konser berhasil diperbarui.'); window.location.href='admin.php';</script>"; // Redirect back to admin panel
    } else {
        echo "<script>alert('Gagal memperbarui konser.');</script>";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Konser</title>
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

        input[type="text"], input[type="date"], input[type="number"], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            background-color: #5e3c92; /* Dark purple */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #d1a1d4; /* Lighter purple */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Konser</h1>

    <?php if ($concert): ?>
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
    <?php else: ?>
        <p>Konser tidak ditemukan.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
$conn->close();
?>
