<?php
session_start();
include 'koneksi.php';

// Cek apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Tambah Produk
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = $_FILES['image']['name'];
    $target = "foto/" . basename($image);

    // Validasi file gambar
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = pathinfo($image, PATHINFO_EXTENSION);
    if (!in_array(strtolower($file_extension), $allowed_extensions)) {
        header("Location: admin.php?error=invalid_image");
        exit();
    }

    $category_id = intval($_POST['category_id']); // Ambil nilai dari form
$stmt = $conn->prepare("INSERT INTO produk (name, price, description, image, category_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sdssi", $name, $price, $description, $image, $category_id);

    if ($stmt->execute() && move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        header("Location: admin.php?success=product added");
    } else {
        header("Location: admin.php?error=product add failed");
    }
    $stmt->close();
    exit();
}

// Hapus Produk
if (isset($_GET['delete_id'])) {
    $id = filter_var($_GET['delete_id'], FILTER_SANITIZE_NUMBER_INT);
    $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin.php?success=product deleted");
    } else {
        header("Location: admin.php?error=product delete failed");
    }
    $stmt->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Kelola Produk</title>
<link rel="stylesheet" href="style.css">

</head>

<div class="admin-container">
    <h1>ADMIN PANEL</h1>
    <div class="admin-menu">
        <a href="riwayat_transaksi.php" class="riwayat-trx">Riwayat Transaksi</a>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</div>

<div class="admin-content">
    <h2 class="judul-produk">Kelola Produk</h2>
    <!-- Konten lainnya -->


    <!-- Notifikasi -->
    <?php if (isset($_GET['success'])): ?>
    <p class="success"><?= htmlspecialchars($_GET['success']) ?></p>
    <?php elseif (isset($_GET['error'])): ?>
    <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <!-- Form Tambah Produk -->
    <form method="POST" enctype="multipart/form-data" class="admin-form">
        <label for="name">Nama Produk</label>
        <input type="text" id="name" name="name" placeholder="Nama Produk" required>

        <label for="price">Harga</label>
        <input type="number" id="price" name="price" placeholder="Harga" step="0.01" required>

        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" placeholder="Deskripsi" required></textarea>

        <label for="image">Gambar</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <label for="category">Kategori</label>
        <select id="category" name="category_id" required>
            <?php
        // Query untuk mengambil data kategori
        $categories = mysqli_query($conn, "SELECT * FROM categories"); 
        if (mysqli_num_rows($categories) > 0) { 
            while ($cat = mysqli_fetch_assoc($categories)) { 
                echo "<option value='{$cat['id']}'>{$cat['nama_kategori']}</option>"; 
            } 
        } else { 
            echo "<option value=''>Tidak ada kategori tersedia</option>"; 
        }
        ?>
        </select>

        <button <button type="submit" name="add_product">Tambah Produk</button>
    </form>
</div>

<!-- Daftar Produk -->
<div class="tabel">
    <h2>Daftar Produk</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
        <?php
        $products = mysqli_query($conn, "SELECT * FROM produk");
            while ($row = mysqli_fetch_assoc($products)) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
                <td>{$row['description']}</td>
                <td><img src='foto/{$row['image']}' width='50'></td>
                <td>
                    <a href='edit_product.php?id={$row['id']}' class='edit-button'>Edit</a>
                    <a href='?delete_id={$row['id']}' class='delete-button' onclick='return confirm(\"Apakah Anda yakin ingin menghapus produk ini?\")'>Hapus</a>
                </td>
            </tr>";
            }
            ?>
    </table>
</div>
</body>

</html>