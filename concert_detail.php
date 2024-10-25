<?php
// Membuat koneksi ke database
$conn = new mysqli("localhost", "root", "", "concert_system");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Mengambil detail konser berdasarkan ID
$result = $conn->query("SELECT * FROM concerts WHERE id = $id");
$concert = $result->fetch_assoc();

if (!$concert) {
    echo "Konser tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konser</title>
    <link rel="stylesheet" href="style.css"> <!-- Link CSS di sini -->
</head>
<body>
    <h1>Detail Konser</h1>
    
    <p><strong>Name:</strong> <?php echo htmlspecialchars($concert['name']); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($concert['date']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($concert['location']); ?></p>
    <p><strong>Max Participants:</strong> <?php echo htmlspecialchars($concert['max_participants']); ?></p>

    <!-- Cek apakah 'description' ada dan tidak kosong -->
    <p><strong>Description:</strong> 
    <?php echo !empty($concert['description']) ? htmlspecialchars($concert['description']) : "Tidak ada deskripsi."; ?>
    </p>

    <p><strong>Banner:</strong></p>
    <img src="<?php echo htmlspecialchars($concert['image']); ?>" alt="Concert Image" width="200" height="200">

    <br />
    <a href="list_concert.php">Kembali ke Daftar Konser</a>

</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
