<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php"); // Arahkan ke halaman login jika bukan admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Link ke file CSS Anda -->
</head>

<body>
    <h1>Selamat Datang, Admin!</h1>
    <p>Gunakan halaman ini untuk mengelola produk dan harga.</p>

    <nav>
        <a href="manage_products.php">Kelola Produk</a>
        <a href="logout.php">Logout</a>
    </nav>
</body>

</html>