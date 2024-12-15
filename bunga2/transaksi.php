<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/stayle.css">
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
            <input type="text" id="total-price" readonly>
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

    <script src="js/script.js"></script>
</body>
</html>