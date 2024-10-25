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
if (!isset($_SESSION['user_username'])) {
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

// Ambil ID registrasi dari form
if (isset($_POST['registration_id'])) {
    $registration_id = $_POST['registration_id'];

    // Query untuk menghapus registrasi
    $stmt = $conn->prepare("DELETE FROM registrations WHERE id = ?");
    $stmt->bind_param("i", $registration_id);
    
    if ($stmt->execute()) {
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
                    background-color: #dff2bf; 
                    border: 1px solid #4f8f2f; 
                    color: #4f8f2f; 
                    padding: 20px; 
                    border-radius: 5px; 
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
                    max-width: 400px; 
                    text-align: center; 
                }
              </style>";
        echo "<div class='message'>Registrasi berhasil dibatalkan.</div>";
    } else {
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
        echo "<div class='message'>Gagal membatalkan registrasi: " . htmlspecialchars($stmt->error) . "</div>";
    }

    $stmt->close();
} else {
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
    echo "<div class='message'>ID registrasi tidak ditemukan.</div>";
}

$conn->close();
?>
