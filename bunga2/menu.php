<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login sebagai customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: index.php");
    exit();
}

// Ambil data produk dari database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Jika query gagal
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Toko Bunga</title>
    <link rel="stylesheet" href="css/style.css">
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
    <!-- Navigasi -->
    <nav>
        <button id="menu-toggle">☰</button>
        <div id="menu" class="hidden">
            <a href="home.php">Home</a>
            <a href="about.php">Tentang Kami</a>
            <a href="menu.php">Menu</a>
            <a href="contact.php">Kontak Kami</a>
            <a href="profile.php">Profil</a>
            <a href="logout.php">Logout</a>
        </div>
        <button id="cart-toggle">🛒</button>
    </nav>

    <!-- Header -->
    <header>
        <h1>Menu Produk</h1>
        <p>Pilih buket bunga terbaik untuk momen spesial Anda!</p>
    </header>

    <!-- Daftar Produk -->
    <div class="products">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="product">
            <img src="assets/images/<?= htmlspecialchars($row['image']); ?>"
                alt="<?= htmlspecialchars($row['name']); ?>">
            <h3><?= htmlspecialchars($row['name']); ?></h3>
            <p>Rp <?= number_format($row['price'], 0, ',', '.'); ?></p>
            <button class="add-to-cart" data-id="<?= $row['id']; ?>">Tambah ke Keranjang</button>
        </div>
        <?php } ?>
    </div>

    <!-- Keranjang -->
    <div id="cart" class="hidden">
        <h2>Keranjang</h2>
        <ul id="cart-items"></ul>
        <button id="checkout">Checkout</button>
    </div>

    <!-- Footer -->
    <footer>© 2024 BYOUQET BUNGA. All rights reserved.</footer>

    <!-- JavaScript -->
    <script src="js/script.js"></script>
</body>

</html>