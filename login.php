<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "concert_system");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = $_POST['login_input']; 
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $login_input, $login_input); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username']; 
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: list_concert.php");
            }
            exit;
        } else {
            $login_error = "Password salah";
        }
    } else {
        $login_error = "Username atau email tidak ditemukan";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="min-height: 100vh; overflow: hidden; display: flex; align-items: center; justify-content: center; background-image: url('uploads/bg-login.png'); background-size: cover; background-position: center;">

    <a href="index.php" style="position: absolute; top: 20px; left: 20px; text-decoration: none; color: white; font-size: 1.5rem;">&#8592; Kembali</a> 

    <div style="background-color: rgba(255, 255, 255, 0.6); padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px;">
        <h2 style="font-size: 1.875rem; font-weight: bold; text-align: center; color: #7E22CE; margin-bottom: 1.5rem;">Login</h2>

        <?php if (isset($login_error)): ?>
            <p style="color: #960019; text-align: center; margin-bottom: 1rem;"><?php echo $login_error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <label for="login_input" style="display: block; font-size: 1.125rem; font-weight: medium; color: #374151;">Username atau Email</label>
                <input 
                    type="text" 
                    name="login_input" 
                    id="login_input" 
                    required 
                    style="width: 100%; margin-top: 0.25rem; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: black; outline: none;"
                    onfocus="this.style.borderColor='#7E22CE'; this.style.boxShadow='0 0 0 2px rgba(126, 34, 206, 0.5)';"
                    onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                >
            </div>

            <div>
                <label for="password" style="display: block; font-size: 1.125rem; font-weight: medium; color: #374151;">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    style="width: 100%; margin-top: 0.25rem; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; color: black; outline: none;"
                    onfocus="this.style.borderColor='#7E22CE'; this.style.boxShadow='0 0 0 2px rgba(126, 34, 206, 0.5)';"
                    onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none';"
                >
            </div>

            <button 
                type="submit" 
                style="width: 100%; background-color: #6D28D9; color: white; padding: 0.5rem; border-radius: 0.5rem; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='#5B21B6';" 
                onmouseout="this.style.backgroundColor='#6D28D9';"
            >
                Login
            </button>
        </form>

        <footer style="margin-top: 1.5rem; text-align: center;">
            <p style="color: #4B5563;">
                Belum punya akun? 
                <a href="register.php" style="color: #7E22CE; text-decoration: none;" 
                onmouseover="this.style.textDecoration='underline';" 
                onmouseout="this.style.textDecoration='none';">Daftar di sini</a>
            </p>
            <p style="color: #4B5563;">
                <a href="forgot_password.php" style="color: #7E22CE; text-decoration: none;" 
                onmouseover="this.style.textDecoration='underline';" 
                onmouseout="this.style.textDecoration='none';">Lupa Password?</a>
            </p>
        </footer>
    </div>

</body>
</html>