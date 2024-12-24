<?php
session_start();
include 'koneksi.php';

// Validasi apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ambil riwayat transaksi pengguna yang sedang login
$user_id = $_SESSION['user_id'];
$query = "SELECT transaksi.kode_pesanan AS transaksi_id, transaksi.created_at, transaksi.total, 
                users.fullname, users.email
        FROM transaksi 
        INNER JOIN users ON transaksi.user_id = users.id 
        WHERE transaksi.user_id = '$user_id'
        ORDER BY transaksi.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
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
        <h2>Riwayat Transaksi</h2>

        <table>
            <tr>
                <th>No Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['transaksi_id'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                <td>
                    <a href="cetak_struk.php?id=<?= $row['transaksi_id'] ?>" class="edit-button">Cetak Struk</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>