<?php
// Inisialisasi sesi dan koneksi database
session_start();
include 'koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?message=NotLoggedIn");
    exit();
}

// Tangani permintaan untuk menambahkan produk ke keranjang
if (isset($_POST['product_id'])) {
    // Ambil ID produk dari POST
    $productId = $_POST['product_id'];
    
    // Ambil data produk dari database
    $query = "SELECT * FROM produk WHERE id = $productId";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    // Jika produk ditemukan, tambahkan ke keranjang
    if ($product) {
        // Jika keranjang belum ada, buat array kosong
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tambahkan produk ke keranjang (cek apakah produk sudah ada)
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity'] += 1; // Tambah jumlah jika produk sudah ada di keranjang
                $found = true;
                break;
            }
        }

        // Jika produk belum ada, tambahkan produk baru ke keranjang
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }
}

// Jalankan query untuk mengambil produk
$query = "SELECT * FROM produk"; // Ganti dengan nama tabel "produk"
$result = mysqli_query($conn, $query);

// Tangani kesalahan query
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Buket Bunga</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        @keyframes fly-to-cart {
        0% {
            opacity: 1;
            transform: translate(0, 0) scale(1);
        }
        50% {
            opacity: 0.7;
            transform: translate(calc(50vw - 50%), calc(50vh - 50%)) scale(0.5);
        }
        100% {
            opacity: 0;
            transform: translate(var(--cart-x), var(--cart-y)) scale(0.2);
        }
    }

    .fly-animation {
        position: fixed;
        pointer-events: none;
        z-index: 9999;
        animation: fly-to-cart 1s ease-in-out forwards;
    }
        body, html {
            background-color: #f4f4f9;
        }

        .footer {
            background-color: #f4f4f9; /* Warna latar belakang footer */
            color: #000; /* Warna teks footer */
        }

        .cart-icon {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Navigasi -->
    <header>
        <nav>
            <div class="logo">Byouqet</div>
            <ul class="nav-links">
                <div id="home" class="hidden">
                    <a href="home.php">Home</a>
                    <a href="about.php">Tentang Kami</a>
                    <a href="menu.php">Menu</a>
                    <a href="contact.php">Kontak Kami</a>
                    <a href="profil.php" class="icon-link"><i data-feather="user"></i></a>
                    <a href="logout.php" class="icon-link"><i data-feather="log-out"></i></a>
                    <a href="keranjang.php" class="icon-link" id="shoppingCartIcon"><i data-feather="shopping-cart"></i></a>
                </div>
            </ul>
        </nav>
    </header>

    <!-- Header -->
    <headerh1 h1>
        <h1>Menu Produk</h1>
        <h2>Pilih buket bunga terbaik untuk momen spesial Anda!</h2>
    </header h1>

    <!-- Daftar Produk -->
    <div class="buket-list">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="buket-item">
                <img src="foto/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                <h3><?= htmlspecialchars($row['name']); ?></h3>
                <p><?= htmlspecialchars($row['description']); ?></p>
                <p>Rp <?= number_format($row['price'], 0, ',', '.'); ?></p>
                <!-- Tombol untuk menambahkan produk ke keranjang -->
                <form action="menu.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                    <button type="submit" class="add-to-cart">Tambah ke Keranjang</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </div>

    <!-- JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const cartIcon = document.getElementById('shoppingCartIcon');

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', event => {
                event.preventDefault(); // Cegah form submit langsung

                const productItem = button.closest('.buket-item');
                const productImage = productItem.querySelector('img');

                // Salin gambar produk untuk animasi
                const flyImage = productImage.cloneNode(true);
                const rect = productImage.getBoundingClientRect();
                const cartRect = cartIcon.getBoundingClientRect();

                // Set posisi awal gambar
                flyImage.style.position = 'fixed';
                flyImage.style.left = `${rect.left}px`;
                flyImage.style.top = `${rect.top}px`;
                flyImage.style.width = `${rect.width}px`;
                flyImage.style.height = `${rect.height}px`;
                flyImage.classList.add('fly-animation');

                // Hitung posisi akhir menuju ikon keranjang
                flyImage.style.setProperty('--cart-x', `${cartRect.left - rect.left}px`);
                flyImage.style.setProperty('--cart-y', `${cartRect.top - rect.top}px`);

                // Tambahkan gambar ke dokumen untuk animasi
                document.body.appendChild(flyImage);

                // Hapus elemen setelah animasi selesai
                flyImage.addEventListener('animationend', () => {
                    document.body.removeChild(flyImage);

                    // Lakukan submit form ke server setelah animasi
                    button.closest('form').submit();
                });
            });
        });
    });

        feather.replace();
    </script>
</body>

</html>
