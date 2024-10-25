<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "concert_system");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = "";

// Menangani pembelian tiket
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ticket_count = (int)$_POST['ticket_count'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $bukti_transfer = $_FILES['bukti_transfer'];

    // Validasi ukuran file maksimal 100MB
    if ($bukti_transfer['size'] > 100 * 1024 * 1024) {
        $message = "<div class='bg-red-500 text-white p-3 rounded-lg'>Ukuran file maksimal 100MB!</div>";
    } else {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_path = $upload_dir . basename($bukti_transfer['name']);

        if (move_uploaded_file($bukti_transfer['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO ticket_purchases (name, ticket_count, phone, email, bukti_transfer) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sisss", $name, $ticket_count, $phone, $email, $file_path);

            if ($stmt->execute()) {
                $message = "<div class='bg-green-500 text-white p-3 rounded-lg'>Pembelian berhasil!</div>";
            } else {
                $message = "<div class='bg-red-500 text-white p-3 rounded-lg'>Gagal menyimpan data: " . $conn->error . "</div>";
            }

            $stmt->close();
        } else {
            $message = "<div class='bg-red-500 text-white p-3 rounded-lg'>Gagal mengunggah bukti transfer!</div>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Background gradient */
        .background-gradient {
            background: linear-gradient(135deg, #ff7e5f, #feb47b, #86a8e7, #d16ba5);
            background-size: 300% 300%;
            animation: gradient-animation 10s ease infinite;
        }

        /* Gradient animation */
        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="background-gradient min-h-screen flex items-center justify-center">

    <div class="bg-white/30 p-10 rounded-lg shadow-2xl w-full max-w-lg text-center backdrop-blur-md">
        <h1 class="text-4xl font-extrabold text-white mb-6">Pembelian Tiket</h1>

        <?php if ($message): ?>
            <div class="mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
            <div class="relative">
                <input 
                    type="text" 
                    name="name" 
                    placeholder="Nama Lengkap" 
                    required 
                    class="w-full px-4 py-3 bg-white/60 text-gray-800 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <div class="relative">
                <input 
                    type="number" 
                    name="ticket_count" 
                    placeholder="Jumlah Tiket" 
                    required 
                    min="1"
                    class="w-full px-4 py-3 bg-white/60 text-gray-800 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <div class="relative">
                <input 
                    type="tel" 
                    name="phone" 
                    placeholder="Nomor Handphone" 
                    required 
                    pattern="[0-9]{10,13}"
                    class="w-full px-4 py-3 bg-white/60 text-gray-800 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <div class="relative">
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email" 
                    required 
                    class="w-full px-4 py-3 bg-white/60 text-gray-800 rounded-lg placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <div class="relative">
                <label for="bukti_transfer" class="block text-white mb-2">Upload Bukti Transfer (Max: 100MB)</label>
                <input 
                    type="file" 
                    name="bukti_transfer" 
                    accept="image/*" 
                    required 
                    class="w-full px-4 py-2 bg-white/60 text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 text-white py-3 rounded-lg font-semibold transition-all duration-300"
            >
                Beli Tiket
            </button>
        </form>
    </div>

</body>
</html>
