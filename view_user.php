<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "concert_system");

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses hapus akun jika parameter 'delete' ada
if (isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);
    
    // Query untuk menghapus akun
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    
    // Menyiapkan dan menjalankan statement
    if ($stmt = $conn->prepare($deleteQuery)) {
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $message = "Akun berhasil dihapus.";
        } else {
            $message = "Kesalahan saat menghapus akun: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
}

// Query untuk mengambil data pengguna
$query = "
    SELECT u.id, u.username, u.email, GROUP_CONCAT(c.name SEPARATOR ', ') AS concerts 
    FROM users u 
    LEFT JOIN registrations r ON u.email = r.email 
    LEFT JOIN concerts c ON r.concert_id = c.id 
    GROUP BY u.id
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-image: url('uploads/bg-login.png'); 
            margin: 0;
            padding: 20px;
            color: #fff; /* Text color for better contrast */
        }
        
        h2 {
            color: white;    
            text-align: center;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); /* Shadow for depth */
            border-radius: 8px; /* Rounded corners */
            overflow: hidden; /* Prevents overflow from rounding */
            background-color: rgba(128, 0, 128, 0.8); /* Semi-transparent purple background for the table */
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 12px; /* Increased padding for better spacing */
            text-align: left;
        }
        
        th {
            background-color: #800080; /* Dark purple header */
            color: white; /* White text */
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1); /* Slightly transparent for even rows */
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Light gray on hover */
        }

        a {
            color: #ffccff; /* Light purple for links */
            text-decoration: none;
            font-weight: bold; /* Bold for emphasis */
        }

        a:hover {
            color: #e0b0ff; /* Lighter purple on hover */
        }

        /* Responsive styling */
        @media (max-width: 1200px) {
            th, td {
                padding: 10px; /* Adjust padding for medium screens */
            }
        }

        @media (max-width: 992px) {
            table {
                display: block; /* Make table scrollable */
                overflow-x: auto; /* Enable horizontal scroll */
                border: none; /* No border for medium screens */
            }

            thead {
                display: none; /* Hide table header */
            }

            tr {
                display: block; /* Stack table rows */
                margin-bottom: 10px; /* Space between rows */
                border-bottom: 1px solid #ddd; /* Border for visual separation */
            }

            td {
                display: flex; /* Flexbox for responsive cells */
                justify-content: space-between; /* Space between label and data */
                padding: 10px; /* Less padding for medium screens */
                text-align: right; /* Align text to the right */
            }

            td::before {
                content: attr(data-label); /* Use data-label for cell labels */
                font-weight: bold; /* Bold for emphasis */
                text-align: left; /* Align text to the left */
                flex: 1; /* Label takes available space */
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 10px; /* Less padding for small screens */
            }

            td {
                padding: 8px; /* Less padding for small screens */
            }

            td::before {
                font-size: 14px; /* Smaller font for labels */
            }
        }
    </style>
</head>
<body>
    <a href="admin.php" style="color: #ffccff; text-decoration: none; font-weight: bold;">
        &#8592; Kembali ke Admin
    </a>
    <h2>Data Pengguna Terdaftar</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Concerts</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td data-label="ID">' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td data-label="Username">' . htmlspecialchars($row['username']) . '</td>';
                    echo '<td data-label="Email">' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td data-label="Concerts">' . htmlspecialchars($row['concerts']) . '</td>';
                    // Tombol hapus akun
                    echo '<td data-label="Aksi"><a href="?delete=' . htmlspecialchars($row['id']) . '" onclick="return confirm(\'Anda yakin ingin menghapus akun ini?\');">Hapus</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">Error in query: ' . htmlspecialchars($conn->error) . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
if (isset($conn)) {
    $conn->close();
}
?>
