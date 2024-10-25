<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StubHub - Welcome to StubHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100" style="font-family: Arial, sans-serif; background-color: #f3f4f6;">

    <header class="p-4 flex items-center justify-between" style="background-image: linear-gradient(135deg, #6b46c1, #b794f4);">
        <div class="flex items-center space-x-3">
            <img src="uploads/Event Kuy.png" alt="StubHub Logo" class="h-12">
            <h1 class="text-white text-xl font-bold" style="color: #fff; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">StubHub</h1>
        </div>
        <nav class="space-x-6">
            <h3>
                <a href="index.php" class="text-white font-semibold hover:text-gray-200">Home</a>
                <a href="about_nolog.php" class="text-white font-semibold hover:text-gray-200">About Us</a>
            </h3>
        </nav>
    </header>

    <section id="home" class="bg-cover bg-center py-20" style="background-image: linear-gradient(135deg, #0f0c29, #302b63, #24243e);">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-white" style="color: #fff; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Welcome to StubHub</h1>
            <p class="text-gray-300 text-xl mt-4">Find your favorite concerts and events here.</p>
            <div class="mt-6">
                <a href="login.php" class="bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-600" style="transition: background 0.3s; filter: brightness(100%);">Login</a>
                <a href="register.php" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600" style="transition: background 0.3s; filter: brightness(100%);">Register</a>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden">
        <div class="carousel flex transition-transform duration-500">
            <div class="min-w-full">
                <img src="uploads/1.jpeg" class="w-full" alt="Event 1">
            </div>
        </div>
    </section>

    <footer class="p-6" style="background-image: linear-gradient(135deg, #6b46c1, #b794f4);">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="font-bold mb-4 text-white">About Us</h4>
                <p class="text-gray-200">StubHub is your one-stop platform for tickets to concerts or events.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4 text-white">Created by</h4>
                <p class="text-gray-200">Natan Adi Chandra</p>
                <p class="text-gray-200">Lifkie Lie</p>
                <p class="text-gray-200">Rio Hawari Putra Hakim</p>
                <p class="text-gray-200">Alya Virgia Aurelline</p>
            </div>
            <div>
                <h4 class="font-bold mb-4 text-white">Follow Us</h4>
                <a href="https://www.instagram.com/natan.adi_?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="block mb-2 text-gray-200 hover:text-gray-300"><i class="fab fa-facebook-f"></i> Natan Adi Chandra</a>
                <a href="https://www.instagram.com/riohawarii?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="block mb-2 text-gray-200 hover:text-gray-300"><i class="fab fa-twitter"></i> Rio Hawari Putra Hakim</a>
                <a href="https://www.instagram.com/alyavrgia/?utm_source=ig_web_button_share_sheet" class="block mb-2 text-gray-200 hover:text-gray-300"><i class="fab fa-twitter"></i> Alya Virgia Aurelline</a>
                <a href="https://www.instagram.com/lifkiehhhhhh/profilecard/?igsh=MTZleHBtYmliNHRpYw==" class="block text-gray-200 hover:text-gray-300"><i class="fab fa-instagram"></i> Lifkie Lie</a>
            </div>
        </div>
        <p class="text-center mt-4 text-gray-200">&copy; 2024 StubHub. All rights reserved.</p>
    </footer>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="text-center p-4">
            <p class="text-gray-700">Anda telah login sebagai pengguna dengan ID: <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
            <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Logout</a>
        </div>
    <?php endif; ?>

    <script src="js/scripts.js"></script>
</body>
</html>