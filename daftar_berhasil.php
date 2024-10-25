<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
            margin: 0;
        }
        .success-container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .success-container h2 {
            color: #4B0082;
        }
        .success-container button {
            padding: 12px 20px;
            background-color: #4B0082;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .success-container button:hover {
            background-color: #3A006B;
        }
    </style>
</head>
<body>

<div class="success-container">
    <h2>Pendaftaran Berhasil!</h2>
    <p>Terima kasih telah mendaftar. Kami akan segera menghubungi Anda melalui email.</p>
    <button onclick="window.location.href='list_concert.php'">Kembali ke Pendaftaran</button>
</div>

</body>
</html>
