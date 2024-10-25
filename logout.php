<?php
session_start(); // Mulai sesi

// Hapus semua variabel sesi
$_SESSION = [];

// Hapus sesi
session_destroy();

// Alihkan ke halaman login
header("Location: login.php");
exit();
?>
