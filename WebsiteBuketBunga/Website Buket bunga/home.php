<?php
session_start();
include 'koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?message=NotLoggedIn");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buket Bunga</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="style.css">
    <style>
    body, html {
    background-color: #FFF8F2;
    }

    .footer {
    background-color: #FFF8F2; /* Warna latar belakang footer */
    color: #000; /* Warna teks footer */
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav>
            <div class="logo">Byouqet</div>
                <ul class="nav-links">
                    <div id="home" class="hidden">
                        <a href="home.php">Home</a>
                        <a href="about.php">Tentang Kami</a>
                        <a href="menu.php">Menu</a>
                        <a href="contact.php">Kontak Kami</a>
                        <a href="profil.php" class="icon-link"><i data-feather="user"></i></a>
                        <a href="logout.php" class="icon-link"><i data-feather="log-out"></i></a>
                    </div>
                </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-image">
            <img src="foto/bg.jpg" alt="Buket Besar">
        </div>
        <div class="hero-content">
            <h1>Selamat Datang di Toko Buket Kami</h1>
            <p>Temukan Buket Bunga Terindah untuk Setiap Momen</p>
            <button id="order-now" class="order-button" onclick="window.location.href='menu.php'">Order Sekarang</button>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </div>

    <script src=" script.js"></script>
    <script>feather.replace();</script>
</body>

</html>
