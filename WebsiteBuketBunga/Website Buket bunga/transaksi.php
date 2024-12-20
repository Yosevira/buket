<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi ke database

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?message=NotLoggedIn");
    exit();
}

// Ambil total harga dari sesi
$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : 0;

// Ambil data produk dari sesi checkout
$checkout_products = isset($_SESSION['checkout_products']) ? $_SESSION['checkout_products'] : [];

// Proses transaksi ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $nama_pemesan = mysqli_real_escape_string($conn, $_POST['fullname']);
    $alamat_pengiriman = mysqli_real_escape_string($conn, $_POST['address']);
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['payment-method']);

    // Generate kode pesanan unik (8 digit acak)
    $kode_pesanan = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);

    // Simpan transaksi ke tabel transaksi
    $stmt = $conn->prepare("INSERT INTO transaksi (kode_pesanan, user_id, nama_pemesan, total, alamat_pengiriman, metode_pembayaran) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisdss", $kode_pesanan, $user_id, $nama_pemesan, $total_price, $alamat_pengiriman, $metode_pembayaran);
    $stmt->execute();
    $transaksi_id = $stmt->insert_id; // Dapatkan ID transaksi terakhir
    $stmt->close();

    // Simpan detail transaksi
    foreach ($checkout_products as $product) {
        $produk_id = $product['id'];
        $produk_nama = $product['name'];
        $jumlah = $product['quantity'];
        $harga = $product['price'];
        $total_harga_produk = $jumlah * $harga;

        $stmt_detail = $conn->prepare("INSERT INTO detail_transaksi (transaksi_id, produk_id, nama_produk, jumlah, harga, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_detail->bind_param("iisidd", $transaksi_id, $produk_id, $produk_nama, $jumlah, $harga, $total_harga_produk);
        $stmt_detail->execute();
        $stmt_detail->close();
    }

    // Hapus data checkout dari sesi
    unset($_SESSION['checkout_products']);
    unset($_SESSION['total_price']);

    // Kirim pesan WhatsApp dengan kode pesanan
    $productDetails = array_map(function ($item) {
        return "- {$item['name']} ({$item['quantity']} x Rp " . number_format($item['price'], 0, ',', '.') . ")";
    }, $checkout_products);
    $productDetailsText = implode("%0A", $productDetails);

    $whatsappUrl = 
        "https://wa.me/6285231569104?text=Kode%20Pesanan:%20{$kode_pesanan}%0ANama:%20" . urlencode($nama_pemesan) .
        "%0AAlamat:%20" . urlencode($alamat_pengiriman) .
        "%0AProduk:%0A{$productDetailsText}%0ATotal:%20Rp%20" . number_format($total_price, 0, ',', '.') .
        "%0AMetode%20Pembayaran:%20" . urlencode($metode_pembayaran);

    header("Location: $whatsappUrl");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body, html {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        footer {
            background-color: #FFF8F2; /* Warna latar belakang footer */
            color: #000; /* Warna teks footer */
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Buket Bunga</div>
            <ul class="nav-links">
                <li><a href="menu.php">Kembali ke Menu</a></li>
            </ul>
        </nav>
    </header>

    <section id="transaksi">
        <h2>Transaksi</h2>
        <div id="order-summary">
            <!-- Produk akan ditampilkan secara dinamis -->
        </div>
        <form id="checkout-form" method="POST">
            <label>Nama Pemesan:</label>
            <input type="text" name="fullname" required>
            <label>Alamat Pengiriman:</label>
            <textarea name="address" required></textarea>
            <label>Total Harga:</label>
            <input type="text" id="total-price" value="Rp <?= number_format($total_price, 0, ',', '.'); ?>" readonly>
            <label>Metode Pembayaran:</label>
            <select name="payment-method" required>
                <option value="qris">QRIS</option>
                <option value="cod">Cash on Delivery</option>
                <option value="dana">DANA</option>
                <option value="shopee">ShopeePay</option>
                <option value="ovo">OVO</option>
                <option value="gopay">GoPay</option>
            </select>
            <button type="submit">Bayar</button>
        </form>
    </section>

    <footer>
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </footer>
</body>
</html>
