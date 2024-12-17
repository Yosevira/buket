<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?message=NotLoggedIn");
    exit();
}

// Ambil data keranjang dari sesi
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Inisialisasi total harga
$total = 0;

// Hitung total harga keranjang
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Simpan total harga ke sesi agar bisa digunakan di transaksi.php
$_SESSION['total_price'] = $total;

// Hapus keranjang setelah checkout
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Ambil total harga dari sesi
$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="stayle.css">
    <style>
        body, html{
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .cart-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .cart-item-details {
            flex: 1;
            margin-left: 15px;
        }

        .cart-item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .cart-item-actions button {
            margin-bottom: 5px;
            padding: 5px 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cart-item-actions button:hover {
            background: #0056b3;
        }

        .cart-summary {
            text-align: right;
            margin-top: 20px;
        }

        .cart-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .cart-buttons button {
            padding: 5px 5px;
            font-size: 16px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cart-buttons button:hover {
            background: #218838;
        }

        .cart-buttons .back-button {
            background: #ffc107;
        }

        .cart-buttons .back-button:hover {
            background: #e0a800;
        }
    </style>
</head>

<body>
    <div class="cart-container">
        <div class="cart-header">
            <h1>Keranjang Belanja Anda</h1>
        </div>

        <?php if (empty($cart)) : ?>
            <p>Keranjang Anda kosong. <a href="menu.php">Kembali ke Menu</a></p>
        <?php else : ?>
            <div class="cart-items">
                <?php foreach ($cart as $index => $item) : ?>
                    <div class="cart-item">
                        <img src="foto/<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                        <div class="cart-item-details">
                            <h3><?= htmlspecialchars($item['name']); ?></h3>
                            <p>Harga: Rp <?= number_format($item['price'], 0, ',', '.'); ?></p>
                            <p>Jumlah: <?= htmlspecialchars($item['quantity']); ?></p>
                        </div>
                        <div class="cart-item-actions">
                            <form action="keranjang_update.php" method="POST">
                                <input type="hidden" name="index" value="<?= $index; ?>">
                                <input type="number" name="quantity" min="1" value="<?= htmlspecialchars($item['quantity']); ?>">
                                <button type="submit">Ubah Jumlah</button>
                            </form>
                            <form action="keranjang_hapus.php" method="POST">
                                <input type="hidden" name="index" value="<?= $index; ?>">
                                <button type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Total: Rp <?= number_format(array_sum(array_map(function ($item) {
                    return $item['price'] * $item['quantity'];
                }, $cart)), 0, ',', '.'); ?></h3>
            </div>

            <div class="cart-buttons">
                <button class="back-button" onclick="window.location.href='menu.php'">Kembali ke Menu</button>
                <button onclick="window.location.href='transaksi.php'">Checkout</button>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
