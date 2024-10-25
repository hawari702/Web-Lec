<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f3e5f5, #e1bee7);
            color: #6a1b9a; 
            font-family: 'Arial', sans-serif;
        }

        h1 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #4a0072;
        }

        .carousel-item img {
            height: 600px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease; 
        }

        .carousel-item img:hover {
            transform: scale(1.05); 
        }

        .carousel-caption {
            backdrop-filter: blur(5px);
            background-color: rgba(255, 255, 255, 0.6);
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .carousel-caption h5 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #6a1b9a;
        }

        .carousel-caption h6 {
            font-size: 1.5rem;
            color: #4a0072;
        }

        .back-button {
            background-color: #6a1b9a;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 30px;
            margin-bottom: 30px;
            display: block;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            font-size: 1.1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #4a0072;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1>About Us</h1>
        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="uploads/natan.png" class="d-block w-100" alt="Natan Adi Chandra Image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Natan Adi Chandra</h5>
                        <h6>00000079860</h6>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="uploads/lifkie.png" class="d-block w-100" alt="Lifkie Lie Image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Lifkie Lie</h5>
                        <h6>00000081835</h6>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="uploads/haw2.png" class="d-block w-100" alt="Rio Hawari Image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Rio Hawari Putra Hakim</h5>
                        <h6>00000109470</h6>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="uploads/ale2.jpg" class="d-block w-100" alt="Ale Image">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Alya Virgia Aurelline</h5>
                        <h6>00000111025</h6>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        
        <button class="back-button" onclick="window.location.href='index.php'">Kembali</button>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>