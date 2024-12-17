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

    $stmt = $conn->prepare("INSERT INTO produk (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $description, $image);

    if ($stmt->execute() && move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        header("Location: admin.php?success=product_added");
    } else {
        header("Location: admin.php?error=product_add_failed");
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
        header("Location: admin.php?success=product_deleted");
    } else {
        header("Location: admin.php?error=product_delete_failed");
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
    <link rel="stylesheet" href= "stayle.css">
<style>
    body, html{
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

h1, h2 {
    text-align: center;
    color: #555;
    margin-bottom: 20px;
}

form {
    max-width: 500px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

form input, form textarea, form button {
    width: calc(100% - 20px);
    margin: 10px 10px 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

form button {
    background-color: #28a745;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #218838;
}

table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #007bff;
    color: #fff;
}

table tr:hover {
    background-color: #f1f1f1;
}

table td img {
    max-width: 50px;
    border-radius: 4px;
}

table a {
    text-decoration: none;
    color: #dc3545;
    font-weight: bold;
}

table a:hover {
    color: #bd2130;
}

footer {
    text-align: center;
    margin-top: 20px;
    padding: 10px;
    background: #007bff;
    color: #fff;
    position: fixed;
    width: 100%;
    bottom: 0;
}

.logout-button {
    display: inline-block;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color: #d9534f; /* Warna merah */
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.logout-button:hover {
    background-color: #c9302c; /* Warna merah lebih gelap saat hover */
    text-decoration: none;
}

/* Tombol Edit */
.edit-button {
    display: inline-block;
    padding: 6px 12px;
    font-size: 14px;
    color: #fff;
    background-color: #5bc0de; /* Warna biru */
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    margin-right: 5px;
    transition: background-color 0.3s ease;
}

.edit-button:hover {
    background-color: #31b0d5; /* Warna biru lebih gelap saat hover */
}

/* Tombol Hapus */
.delete-button {
    display: inline-block;
    padding: 6px 12px;
    font-size: 14px;
    color: #fff;
    background-color: #d9534f; /* Warna merah */
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-button:hover {
    background-color: #c9302c; /* Warna merah lebih gelap saat hover */
}
</style>
</head>

<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <h2>Admin Panel</h2>
            <ul>
            <a href="logout.php" class="logout-button">Logout</a>
            </ul>
        </div>
        <div class="admin-content">
            <h1>Kelola Produk</h1>

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
                
                <button type="submit" name="add_product">Tambah Produk</button>
            </form>

            <!-- Daftar Produk -->
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
    </div>
</body>

</html>
