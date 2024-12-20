<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id'");
    $product = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = $_FILES['image']['name'];
    $target = "assets/images/" . basename($image);

    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $query = "UPDATE produk SET name='$name', price='$price', description='$description', image='$image' WHERE id='$id'";
    } else {
        $query = "UPDATE produk SET name='$name', price='$price', description='$description' WHERE id='$id'";
    }

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Produk berhasil diperbarui'); window.location = 'admin.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui produk');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <style>
        /* CSS untuk Halaman Edit Produk */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 0 auto;
}

form input, form textarea, form button {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 16px;
}

form textarea {
    height: 150px;
    resize: vertical;
}

form input[type="file"] {
    padding: 5px;
}

form button {
    background-color: #5bc0de;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
}

form button:hover {
    background-color: #31b0d5;
}

a {
    color: #5bc0de;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Tombol Kembali */
.back-button {
    display: inline-block;
    padding: 6px 12px;
    font-size: 14px;
    color: #fff;
    background-color: #f0ad4e; /* Warna oranye */
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #ec971f; /* Warna oranye lebih gelap saat hover */
}
    </style>
</head>
<body>
    <h1>Edit Produk</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
        <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
        <textarea name="description" required><?php echo $product['description']; ?></textarea>
        <input type="file" name="image">
        <button type="submit" name="update_product">Update Produk</button>
    </form>
    <!-- Tombol Kembali -->
    <a href="admin.php" class="back-button">Kembali ke Daftar Produk</a>
</body>
</html>
