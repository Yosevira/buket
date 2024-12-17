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
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/stayle.css">
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav>
            <div class="logo">Buket Bunga</div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="logout.php" id="logout-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Bagian Profil -->
    <section id="profil">
        <h2>Profil Pengguna</h2>
        <div class="profil-info">
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($user['fullname']); ?></p>
            <p><strong>Riwayat Transaksi:</strong> <a href="riwayat.php">Lihat Riwayat</a></p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </footer>
</body>

</html>
