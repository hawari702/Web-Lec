<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "concert_system");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['concert_id'])) {
    $concert_id = $_GET['concert_id'];
} else {
    die("Concert ID tidak ditemukan.");
}

$concert_query = $conn->prepare("SELECT name, max_participants FROM concerts WHERE id = ?");
$concert_query->bind_param("i", $concert_id);
$concert_query->execute();
$concert_query->bind_result($concert_name, $max_participants);
$concert_query->fetch();
$concert_query->close();

$registered_query = $conn->prepare("SELECT COUNT(*) as total_registered FROM registrations WHERE concert_id = ?");
$registered_query->bind_param("i", $concert_id);
$registered_query->execute();
$registered_query->bind_result($total_registered);
$registered_query->fetch();
$registered_query->close();

if ($total_registered >= $max_participants) {
    echo "<h2 style='color: red; text-align: center;'>Pendaftaran untuk konser ini sudah penuh.</h2>";
    $conn->close();
    exit();  
}

$warning_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $jumlah_tiket = $conn->real_escape_string($_POST['jumlah_tiket']);
    $nomor_hp = $conn->real_escape_string($_POST['nomor_hp']);
    
    if (isset($_FILES['bukti_transfer'])) {
        $file = $_FILES['bukti_transfer'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file["name"]);
        $file_size = $file["size"];
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if ($file_size > 100 * 1024 * 1024) {
            $warning_message = "Ukuran bukti transfer tidak boleh lebih dari 100MB.";
        } elseif ($file_type != "jpg" && $file_type != "jpeg" && $file_type != "png" && $file_type != "pdf") {
            $warning_message = "Format file tidak didukung. Hanya JPG, PNG, dan PDF yang diperbolehkan.";
        } else {
            $check_registration = $conn->prepare("SELECT * FROM registrations WHERE concert_id = ? AND email = ?");
            $check_registration->bind_param("is", $concert_id, $email);
            $check_registration->execute();
            $result = $check_registration->get_result();

            if ($result->num_rows > 0) {
                $warning_message = "Anda sudah terdaftar untuk konser ini.";
            } else {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    $stmt = $conn->prepare("INSERT INTO registrations (concert_id, name, email, jumlah_tiket, nomor_hp, bukti_transfer) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ississ", $concert_id, $name, $email, $jumlah_tiket, $nomor_hp, $target_file);

                    if ($stmt->execute()) {
                        header("Location: daftar_berhasil.php");
                        exit();
                    } else {
                        echo "Pendaftaran gagal: " . $conn->error;
                    }

                    $stmt->close();
                } else {
                    $warning_message = "Terjadi kesalahan saat mengunggah bukti transfer.";
                }
            }

            $check_registration->close();
        }
    } else {
        $warning_message = "Silakan unggah bukti transfer.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Konser: <?php echo htmlspecialchars($concert_name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(180deg, #4A148C, #6A1B9A, #8E24AA);
            background-size: cover;
            min-height: 100vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 400px;  
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
            padding: 20px;     
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            color: #6D28D9;
            font-size: 25px !important;
            font-weight: bold !important;
        }

        .input-field {
            width: 100%;
            padding: 10px;      
            margin: 8px 0;      
            border: 1px solid #7E22CE;
            border-radius: 5px;
            transition: border-color 0.3s;
            font-size: 14px;     
        }

        button {
            background-color: #B47EDE !important;
            color: white !important;
            padding: 10px;      
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            font-size: 14px;    
            transition: background-color 0.3s !important;
        }

        button:hover {
            background-color: #9966CB !important;
            color: white !important;
        }

        .warning {
            color: #D32F2F;
            text-align: center;
            margin-bottom: 15px; 
            font-weight: bold;
        }

        .back-button {
            background-color: #6D28D9 !important;
            color: white;
            margin-top: 15px;   
            font-size: 14px;     
        }

        .back-button:hover {
            background-color: #5B21B6 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Konser: <?php echo htmlspecialchars($concert_name); ?></h1>

        <?php if (!empty($warning_message)): ?>
            <div class="warning"><?php echo $warning_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="daftar_konser.php?concert_id=<?php echo $concert_id; ?>" enctype="multipart/form-data">
            <label for="name">Nama Lengkap:</label>
            <input type="text" id="name" name="name" class="input-field" required>

            <label for="jumlah_tiket">Jumlah Tiket:</label>
            <input type="number" id="jumlah_tiket" name="jumlah_tiket" class="input-field" required>

            <label for="nomor_hp">Nomor HP:</label>
            <input type="text" id="nomor_hp" name="nomor_hp" class="input-field" required>

            <label for="email">Email: (Harus email yang terdaftar pada akun)</label>
            <input type="email" id="email" name="email" class="input-field" required>

            <label for="bukti_transfer">Bukti Transfer (max 100MB):</label>
            <input type="file" id="bukti_transfer" name="bukti_transfer" class="input-field" required>

            <button type="submit">Daftar</button>
        </form>

        <form action="list_concert.php" method="get">
            <button type="submit" class="back-button">Kembali ke Daftar Konser</button>
        </form>
    </div>
</body>
</html>