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
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="admin-container">
        <h1>ADMIN PANEL</h1>
        <div class="admin-menu">
            <a href="admin.php" class="riwayat-trx">Kembali</a>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>
    <div class="admin-content">
        <h2>Edit Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
            <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
            <textarea name="description" required><?php echo $product['description']; ?></textarea>
            <input type="file" name="image">
            <button type="submit" name="update_product">Update Produk</button>
        </form>
        <!-- Tombol Kembali -->
        <a href="admin.php" class="back-button">Kembali ke Daftar Produk</a>
    </div>
</body>

</html>