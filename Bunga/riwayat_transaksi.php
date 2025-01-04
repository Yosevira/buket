<?php
session_start();
include 'koneksi.php';

// Validasi apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ambil informasi sesi
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Validasi role
if ($role !== 'admin' && $role !== 'customer') {
    header("Location: index.php");
    exit();
}

// Query berdasarkan role
if ($role === 'admin') {
    $query = "SELECT transaksi.kode_pesanan AS transaksi_id, transaksi.created_at, transaksi.total, 
                    transaksi.tanggal_pengambilan, users.fullname, users.no_telepon
            FROM transaksi 
            INNER JOIN users ON transaksi.user_id = users.id 
            ORDER BY transaksi.created_at DESC";
} elseif ($role === 'customer') {
    $query = "SELECT transaksi.kode_pesanan AS transaksi_id, transaksi.created_at, transaksi.total, 
                    transaksi.tanggal_pengambilan, users.fullname, users.no_telepon
            FROM transaksi 
            INNER JOIN users ON transaksi.user_id = users.id 
            WHERE transaksi.user_id = '$user_id'
            ORDER BY transaksi.created_at DESC";
}
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
        <div class="admin-menu">
            <!-- Tombol Kembali Dinamis -->
            <?php if ($role === 'admin'): ?>
                <a href="admin.php" class="riwayat-trx">Kembali</a>
            <?php elseif ($role === 'customer'): ?>
                <a href="profil.php" class="riwayat-trx">Kembali</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="admin-content">
        <h2>Riwayat Transaksi</h2>

        <table>
            <tr>
                <th>No Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Customer</th>
                <th>No Telepon</th>
                <th>Total</th>
                <th>Tanggal Pengambilan</th>
                <?php if ($role === 'admin'): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['transaksi_id']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['tanggal_pengambilan']) ?></td>
                <?php if ($role === 'admin'): ?>
                    <td>
                        <a href="cetak_struk.php?id=<?= htmlspecialchars($row['transaksi_id']) ?>" class="edit-button">Cetak Struk</a>
                    </td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>
