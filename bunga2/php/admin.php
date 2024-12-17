<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Tambah Produk
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target = "assets/images/" . basename($image);

    $query = "INSERT INTO products (name, price, description, image) VALUES ('$name', '$price', '$description', '$image')";
    if (mysqli_query($conn, $query) && move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        echo "<script>alert('Produk berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan produk');</script>";
    }
}

// Hapus Produk
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header("Location: admin.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Produk</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <h1>Kelola Produk</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Nama Produk" required>
        <input type="number" name="price" placeholder="Harga" required>
        <textarea name="description" placeholder="Deskripsi"></textarea>
        <input type="file" name="image" required>
        <button type="submit" name="add_product">Tambah Produk</button>
    </form>

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
        $products = mysqli_query($conn, "SELECT * FROM products");
        while ($row = mysqli_fetch_assoc($products)) {
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
                    <td>{$row['description']}</td>
                    <td><img src='assets/images/{$row['image']}' width='50'></td>
                    <td><a href='?delete_id={$row['id']}'>Hapus</a></td>
                </tr>";
        }
        ?>
    </table>
</body>

</html>