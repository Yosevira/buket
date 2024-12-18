<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?message=NotLoggedIn");
    exit();
}

// Ambil total harga dari sesi
$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : 0;

// Ambil data produk dari sesi checkout
$checkout_products = isset($_SESSION['checkout_products']) ? $_SESSION['checkout_products'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stayle.css">
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
        <form id="checkout-form">
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

    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const name = document.querySelector('input[name="fullname"]').value;
    const address = document.querySelector('textarea[name="address"]').value;
    const paymentMethod = document.querySelector('select[name="payment-method"]').value;
    const totalPrice = document.getElementById('total-price').value;

    // Data produk dari PHP
    const products = <?php echo json_encode($checkout_products); ?>;

    if (products.length > 0) {
        const productDetails = products
            .map(item => `- ${item.name} (${item.quantity} x Rp ${item.price.toLocaleString('id-ID')})`)
            .join('%0A');

        const whatsappUrl = 
            `https://wa.me/6285231569104?text=Nama:%20${encodeURIComponent(name)}%0AAlamat:%20${encodeURIComponent(address)}%0AProduk:%0A${productDetails}%0ATotal:%20${encodeURIComponent(totalPrice)}%0AMetode%20Pembayaran:%20${encodeURIComponent(paymentMethod)}`;

        // Buka WhatsApp
        window.open(whatsappUrl, '_blank', 'noopener,noreferrer');

        // Hapus data checkout dari sesi setelah pembayaran
        fetch('transaksi_clear.php')
            .then(() => {
                alert('Pesanan Anda telah dikirim. Terima kasih!');
                window.location.href = 'menu.php';
            })
            .catch(err => console.error('Error:', err));
    } else {
        alert('Tidak ada produk untuk diproses.');
    }
});
    </script>
</body>
</html>
