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
    <title>Profil Pengguna</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/feather-icons"></script>
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
                    <a href="logout.php" class="icon-link"><i data-feather="log-out"></i></a>
                </div>
            </ul>
        </nav>
    </header>

    <!-- Bagian Profil -->
    <section id="profil">
        <div class="profil-container">
            <h2><i data-feather="user"></i> Profil Pengguna</h2>
            <div class="profil-info">
                <p><i data-feather="user"></i> <strong> Full Name : </strong>
                    <?= htmlspecialchars($user['fullname']); ?></p>
                <p><i data-feather="mail"></i> <strong> Email : </strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><i data-feather="map-pin"></i> <strong> Alamat : </strong> <?= htmlspecialchars($user['alamat']); ?>
                </p>
                <p><i data-feather="phone"></i> <strong> Nomor Telepon : </strong>
                    <?= htmlspecialchars($user['no_telepon']); ?></p>
                <p><i data-feather="clock"></i> <strong> Riwayat Transaksi : </strong> <a
                        href="riwayat_transaksi.php">Lihat Riwayat</a></p>
            </div>
            <div class="edit-button">
                <a href="edit_profil.php" class="btn"><i data-feather="edit"></i> Edit Profil</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </footer>
    <script>
    feather.replace();
    </script>
</body>

</html>