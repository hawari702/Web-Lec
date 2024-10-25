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

// Pastikan pengguna sudah login
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

// Handle form submission for updating profile
$message = ""; // Variable to hold success or error message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    
    // Prepare the SQL update statement
    if (!empty($new_password)) {
        // If a new password is provided, hash it
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $new_username, $new_email, $password_hash, $_SESSION['user_id']);
    } else {
        // If no new password is provided, just update username and email
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_username, $new_email, $_SESSION['user_id']);
    }

    // Execute the statement
    if ($stmt->execute()) {
        // Update the session with the new username
        $_SESSION['username'] = $new_username; // Update session with new username
        $message = "<div class='message'>Profil berhasil diperbarui.</div>";
    } else {
        $message = "<div class='message'>Terjadi kesalahan saat memperbarui profil.</div>";
    }

    $stmt->close();
}

// Ambil informasi pengguna berdasarkan ID setelah update
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            background-image: url('uploads/bg-login.png'); background-size: cover; background-position: center;">
        }
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .message { 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb; 
            color: #155724; 
            padding: 15px; 
            border-radius: 5px; 
            margin-top: 20px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

    <div class="card w-full max-w-lg">
        <h2 class="text-3xl font-bold text-center text-pink-700 mb-6">Edit Profile</h2>

        <form method="POST" action="edit_profile.php" class="space-y-6">
            <div>
                <label for="username" class="block text-lg font-medium text-gray-700">Username</label>
                <input 
                    type="text" 
                    name="username" 
                    id="username" 
                    value="<?php echo htmlspecialchars($user['username']); ?>" 
                    required 
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none"
                >
            </div>

            <div>
                <label for="email" class="block text-lg font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="<?php echo htmlspecialchars($user['email']); ?>" 
                    required 
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none"
                >
            </div>

            <div>
                <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    placeholder="Masukkan password baru" 
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none"
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors"
            >
                Update Profile
            </button>
        </form>

        <?php if ($message): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="view_profile.php" class="text-orange-500 hover:underline">Kembali ke Profil</a>
        </div>
    </div>

</body>
</html>
