<?php
session_start();
include 'koneksi.php';

// Validasi apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?message=NotLoggedIn");
    exit();
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/stayle.css">
    <style>
    #profil {
        padding: 20px;
        background-color: #f0a5ce !important;
        text-align: center;
        font-weight: bold;
    }

    #profil h2 {
        font-size: 52px;
        font-family: 'Dancing Script', cursive;
        color: #fcf9f9;
        margin-bottom: 20px;
    }

    .profil-box {
        background-color: #fff;
        border: 2px solid #f0a5ce;
        border-radius: 10px;
        padding: 20px;
        display: inline-block;
        text-align: left;
    }

    .profil-box p {
        font-size: 18px;
        margin: 10px 0;
    }

    .profil-box a {
        color: #333;
        text-decoration: none;
        font-weight: bold;
    }

    .profil-box a:hover {
        text-decoration: underline;
    }

    header nav,
    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #D8A7B1;
        color: white;
    }

    header .logo,
    nav .logo {
        font-size: 35px;
        font-family: 'Dancing Script', cursive;
        font-weight: bold;
    }

    .nav-links,
    #menu,
    #home,
    #tentang,
    #kontak {
        display: flex;
        gap: 20px;
    }

    .nav-links a,
    #menu,
    #home,
    #tentang,
    #kontak a {
        color: white;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        transition: color 0.3s;
    }

    .nav-links a:hover,
    #menu,
    #home,
    #tentang,
    #kontak a:hover {
        color: #ffe6e6;
    }

    footer {
        text-align: center;
        background-color: #fff;
        padding: 1px 0;
        margin: 0;
        width: 100%;
        position: relative;
        bottom: 0;
        left: 0;
        font-size: 14px;
        color: #333;
    }
    </style>

</head>

<body>
    <!-- Navbar -->
    <header>
        <nav>
            <div class="logo">Byouqet</div>
            <ul class="nav-links">
                <div id="kontak" class="hidden">
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


    <section id="profil">
        <h2>Profil Pengguna</h2>
        <div class="profil-">
            <p><strong>Email : </strong><?=htmlspecialchars($user['email']);
    ?></p>
            <p><strong>Full Name :</strong><?=htmlspecialchars($user['fullname']);
    ?></p>
            <p><strong>Riwayat Transaksi : </strong><a href="riwayat.php">Lihat Riwayat</a></p>
        </div>
    </section>

    <footer>
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </footer>
</body>

</html>