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

// Tambahkan ini sebelum menghapus keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $_SESSION['checkout_products'] = $cart; // Salin produk ke sesi checkout
    unset($_SESSION['cart']); // Hapus keranjang
    header("Location: transaksi.php"); // Redirect ke halaman transaksi
    exit();
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
    <link rel="stylesheet" href="style.css">
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
            margin-bottom: -6px;
            margin-top: 8px;
            padding: 5px;
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

        .cart-buttons .checkout {
            padding: 5px 5px;
            font-size: 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgb(0, 255, 60);
        }

        .cart-buttons .checkout:hover {
            background: #218838;
        }

        .cart-buttons .back-button {
            margin-bottom: -6px;
            margin-top: 110px;
            padding: 5px;
            background:#1600e0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cart-buttons .back-button:hover {
            background:#1600e0;
        }

        /* Tambahan CSS untuk Tombol */
        .cart-container a {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease;
        }

        .cart-container a:hover {
        background-color: #0056b3;
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
                        <!-- Tombol Ubah Jumlah -->
                            <input type="number" class="update-quantity" data-index="<?= $index; ?>" min="1" value="<?= htmlspecialchars($item['quantity']); ?>">
                            <button class="update-btn" data-index="<?= $index; ?>">Ubah Jumlah</button>
                        <!-- Tombol Hapus Produk -->
                            <button class="delete-btn" data-index="<?= $index; ?>">Hapus</button>
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
            <button id="back-button" class="back-button" onclick="window.location.href='menu.php'">Kembali Ke Menu</button>
                <!-- Tombol Checkout mengirim POST -->
                <form action="keranjang.php" method="POST">
                    <button id="checkout" class="checkout" type="submit" name="checkout">Checkout</button>
                </form>
            </div>

        <?php endif; ?>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Ubah Jumlah Produk
    $('.update-btn').click(function(e) {
        e.preventDefault();
        let index = $(this).data('index');
        let quantity = $(this).siblings('.update-quantity').val();

        $.post('keranjang_update.php', { index: index, quantity: quantity }, function(response) {
            location.reload(); // Refresh bagian keranjang saja
        });
    });

    // Hapus Produk
    $('.delete-btn').click(function(e) {
        e.preventDefault();
        let index = $(this).data('index');

        $.post('keranjang_hapus.php', { index: index }, function(response) {
            location.reload(); // Refresh bagian keranjang saja
        });
    });
});
</script>

</html>
