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

// Ambil email user dari session (misalnya jika user login)
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

// Mengambil daftar konser dengan jumlah pendaftar
$result = $conn->query("SELECT c.*, 
                               (SELECT COUNT(*) FROM registrations r WHERE r.concert_id = c.id) as total_registered 
                        FROM concerts c");

$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $result = $conn->query("SELECT c.*, 
                                   (SELECT COUNT(*) FROM registrations r WHERE r.concert_id = c.id) as total_registered 
                            FROM concerts c WHERE c.name LIKE '%$search_query%'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Konser</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('uploads/bg-login.png');
            background-size: cover;
            background-position: center;
            background-color: #e3f2fd;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        h1 {
            color: white;
            margin-bottom: 20px;
        }

        .navbar {
            width: 100%;
            background-color: #6a1b9a;
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .navbar input[type="text"] {
            padding: 5px;
            border: none;
            border-radius: 5px;
            width: 300px;
        }

        .navbar button {
            padding: 5px 10px;
            border: none;
            background-color: #9c27b0;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .navbar button:hover {
            background-color: #7b1fa2;
        }

        /* Style untuk tautan Home dan Contact */
        .navbar nav {
            display: flex;
            gap: 20px;
            justify-content: flex-end; /* Mengatur link ke kanan */
        }

        .navbar nav a {
            color: white; /* Warna teks menjadi putih */
            font-weight: bold;
            text-decoration: none;
        }

        .navbar nav a:hover {
            text-decoration: underline; /* Tambahkan efek hover */
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px; /* Increased padding for more space */
            margin: 15px;
            width: 250px; /* Fixed width for uniformity */
            height: 400px; /* Set a fixed height */
            transition: transform 0.2s;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            text-align: left;
            display: flex;
            flex-direction: column; /* Flex column to stack contents */
            justify-content: space-between; /* Space out content */
        }

        .card img {
            max-width: 100%; /* Ensure image fits card width */
            height: 150px; /* Fixed height for uniform image size */
            border-radius: 10px;
            margin-bottom: 15px; /* Add space below image */
            object-fit: cover; /* Crop the image to fit */
        }

        .card-title {
            font-size: 1.2em;
            color: #6a1b9a;
            margin-bottom: 10px; /* Add space below title */
        }

        .card-text {
            margin: 5px 0; /* Reduce margin for consistency */
            flex-grow: 1; /* Allow text to grow and fill space */
        }

        .register-button {
            background-color: #6a1b9a;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center; /* Center the button */
        }

        .register-button:hover {
            background-color: #4a148c;
        }

        /* Modal styles */
        .details-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-radius: 8px;
            width: 80%;
            max-width: 800px;
        }

        .modal-content {
            display: flex;
        }

        .modal-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-image img {
            width: auto;
            height: auto;
            max-height: 400px;
            max-width: 100%;
            border-radius: 10px 0 0 10px;
            object-fit: contain;
        }

        .modal-details {
            flex: 1;
            padding: 20px;
        }

        .modal-details h2 {
            margin-bottom: 10px;
        }

        .modal-details p {
            margin: 5px 0;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div>
        <h2>Cari Konser yang Kamu Mau</h2>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by concert name" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <nav>
        <a href="about.php">About Us</a>
        <a href="view_registered_events.php">View Registered Events</a>
        <a href="view_profile.php">View Profile</a>
    </nav>
</div>

<h1>Daftar Konser</h1>

<div style="display: flex; flex-wrap: wrap; justify-content: center;">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card" onclick="showDetails('<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['date']); ?>', '<?php echo htmlspecialchars($row['location']); ?>', '<?php echo htmlspecialchars($row['max_participants']); ?>', '<?php echo htmlspecialchars($row['image']); ?>', '<?php echo htmlspecialchars($row['description']); ?>')">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Concert Image">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text">Tanggal: <?php echo htmlspecialchars($row['date']); ?></p>
                <p class="card-text">Lokasi: <?php echo htmlspecialchars($row['location']); ?></p>
                <p class="card-text">Max Peserta: <?php echo htmlspecialchars($row['max_participants']); ?></p>
                <p class="card-text">Jumlah Pendaftar: <?php echo htmlspecialchars($row['total_registered']); ?>/<?php echo htmlspecialchars($row['max_participants']); ?></p>
                
                <p class="card-text">
                    Status: 
                    <strong style="color: <?php echo ($row['status'] === 'open') ? 'green' : 'red'; ?>;">
                        <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                    </strong>
                </p>

                <?php if ($row['status'] === 'cancel'): ?>
                    <p style="color: red;">Event Cancelled</p>
                <?php else: ?>
                    <form method="GET" action="daftar_konser.php" style="display:inline-block;">
                        <input type="hidden" name="concert_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="register-button">Daftar Konser</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Modal for displaying concert details -->
<div class="modal-overlay" id="modalOverlay"></div>
<div class="details-modal" id="detailsModal">
    <div class="modal-content">
        <div class="modal-image">
            <img id="concertImage" src="" alt="Concert Image">
        </div>
        <div class="modal-details">
            <h2 id="concertTitle"></h2>
            <p id="concertDate"></p>
            <p id="concertLocation"></p>
            <p id="concertMaxParticipants"></p>
            <p id="concertDescription"></p>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>
</div>

<script>
    function showDetails(name, date, location, maxParticipants, image, description) {
        document.getElementById('concertTitle').innerText = name;
        document.getElementById('concertDate').innerText = "Tanggal: " + date;
        document.getElementById('concertLocation').innerText = "Lokasi: " + location;
        document.getElementById('concertMaxParticipants').innerText = "Max Peserta: " + maxParticipants;
        document.getElementById('concertImage').src = image;
        document.getElementById('concertDescription').innerText = description;

        document.getElementById('detailsModal').style.display = 'block';
        document.getElementById('modalOverlay').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('detailsModal').style.display = 'none';
        document.getElementById('modalOverlay').style.display = 'none';
    }
</script>
<!-- Tombol Logout -->
<form method="POST" action="index.php" style="margin-top: 20px;">
    <button type="submit" class="logout-button" onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</button>
</form>
</body>
</html>
