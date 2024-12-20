<?php
session_start();
include 'koneksi.php';

// Validasi apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?message=NotLoggedIn");
    exit();
}

// Ambil data pengguna
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit();
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);

    // Update data ke database
    $update_query = "UPDATE users SET fullname = '$fullname', email = '$email', alamat = '$alamat', no_telepon = '$no_telepon' WHERE id = '$user_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: profil.php?message=ProfileUpdated");
        exit();
    } else {
        echo "Gagal memperbarui profil.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="stayle.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <!-- Navbar -->
    <header>
        <nav>
            <div class="logo">Byouqet</div>
            <ul class="nav-links">
                <li><a href="profil.php" class="back-btn"><i data-feather="arrow-left"></i> Kembali ke Profil</a></li>
            </ul>
        </nav>
    </header>


    <!-- Edit Profil Section -->
    <section class="profil-container">
        <h2><i data-feather="edit"></i> Edit Profil</h2>
        <form method="POST" action="edit_profil.php" class="profil-form">
            <label>Full Name:</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label>Alamat:</label>
            <input type="text" name="alamat" value="<?= htmlspecialchars($user['alamat']); ?>" required>

            <label>Nomor Telepon:</label>
            <input type="text" name="no_telepon" value="<?= htmlspecialchars($user['no_telepon']); ?>" required>

            <button type="submit" class="btn-submit"><i data-feather="save"></i> Simpan</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </footer>

    <script>
        feather.replace();
    </script>
</body>

</html>
