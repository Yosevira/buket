<?php
session_start();
include 'php/koneksi.php';

// Periksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
$userRole = $_SESSION['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Buket Bunga</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/stayle.css">
    <style>
    body {
        background-color: #f0a5ce;
    }

    /* Tambahkan CSS untuk merapikan tampilan */
    h1 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 32px;
        font-family: 'Dancing Script', cursive;
        color: white;
        /* Font untuk logo */
        font-weight: bold;
    }

    .buket-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .buket-item {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        text-align: center;
        width: 200px;
    }

    .buket-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        margin-bottom: 15px;
        border-radius: 10px;
    }

    .buket-item button {
        background-color: #ff7f7f;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }

    .buket-item button:hover {
        background-color: #ff4d4d;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav>
            <div class="logo">Byouqet</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">Tentang Kami</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <?php if ($isLoggedIn): ?>
                <li><a href="profile.php">Profil</a></li>
                <li><a href="cart.php">Keranjang</a></li>
                <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="menu-section">
        <h1>Menu Buket Kami</h1>
        <div class="buket-list">
            <?php
            // Query untuk mengambil produk dari database
            $query = "SELECT p.*, c.name AS category_name 
                    FROM produk p 
                    LEFT JOIN categories c 
                    ON p.id = c.id";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0):
                while ($product = mysqli_fetch_assoc($result)):
            ?>
            <div class="buket-item">
                <img src="foto/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                <h3><?= $product['name'] ?></h3>
                <p>Kategori: <?= $product['category_name'] ?></p>
                <p>Rp<?= number_format($product['price'], 0, ',', '.') ?></p>
                <?php if ($isLoggedIn): ?>
                <button class="add-to-cart" data-name="<?= $product['name'] ?>"
                    data-price="<?= $product['price'] ?>">Tambah ke Keranjang</button>
                <?php else: ?>
                <button onclick="alert('Silakan login untuk membeli produk!'); window.location.href='login.php';">Tambah
                    ke
                    Keranjang</button>
                <?php endif; ?>
            </div>
            <?php
                endwhile;
            else:
            ?>
            <p>Produk tidak tersedia.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </div>

    <script>
    // Footer muncul saat scroll ke bawah
    document.addEventListener(" scroll", function() {
        const footer = document.querySelector("footer");
        const
            scrollHeight = window.scrollY + window.innerHeight;
        const totalHeight = document.body.offsetHeight;
        if (scrollHeight >= totalHeight - 10) { // Cek jika hampir di bawah
            footer.style.display = "block";
        } else {
            footer.style.display = "none";
        }
    });

    // Footer disembunyikan di awal
    window.onload = function() {
        const footer = document.querySelector("footer");
        footer.style.display = "none";
    }
    </script>

</body>

</html>