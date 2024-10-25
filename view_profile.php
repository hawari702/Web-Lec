<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "concert_system");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo "<style>
            body { 
                font-family: Arial, sans-serif; 
                background-color: #f3f4f6; 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                height: 100vh; 
                margin: 0; 
            }
            .message { 
                background-color: #ffdddd; 
                border: 1px solid #ff0000; 
                color: #d8000c; 
                padding: 20px; 
                border-radius: 5px; 
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
                max-width: 400px; 
                text-align: center; 
            }
          </style>";
    echo "<div class='message'>Harap login terlebih dahulu.</div>";
    exit();
}

$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "Pengguna tidak ditemukan.";
    exit();
}

$stmt = $conn->prepare("SELECT registrations.registration_date, concerts.name, concerts.location 
                          FROM registrations 
                          JOIN concerts ON registrations.concert_id = concerts.id 
                          WHERE registrations.email = ?");
$stmt->bind_param("s", $user['email']);
$stmt->execute();
$registration_result = $stmt->get_result();
$registrations = $registration_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo htmlspecialchars($user['username']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            background-image: url('uploads/bg-login.png'); background-size: cover; background-position: center;">
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

    <div class="card w-full max-w-lg">
        <h2 class="text-3xl font-bold text-center text-purple-600 mb-6">User Profile</h2>

        <div class="mb-4">
            <h3 class="text-lg font-semibold text-purple-500">Profile Information:</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <h3 class="text-lg font-semibold text-purple-500 mb-2">Event Registration History:</h3>
        <?php if (count($registrations) > 0): ?>
            <ul class="list-disc list-inside mb-4">
                <?php foreach ($registrations as $registration): ?>
                    <li class="text-purple-700">
                        <strong>Event Name:</strong> <?php echo htmlspecialchars($registration['name']); ?>, 
                        <strong>Registration Date:</strong> <?php echo htmlspecialchars($registration['registration_date']); ?>, 
                        <strong>Location:</strong> <?php echo htmlspecialchars($registration['location'] ?? 'Lokasi tidak tersedia'); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-600">Tidak ada registrasi event.</p>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="edit_profile.php" class="text-purple-500 hover:underline">Edit Profile</a>
        </div>
    </div>

</body>
</html>