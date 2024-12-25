<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database benar

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Mencegah SQL Injection
    $password = $_POST['password'];

    // Query untuk mendapatkan data pengguna
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password (gunakan password_hash dan password_verify jika password di-hash)
        if ($password === $row['password']) {
            // Set session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            // Redirect sesuai role
            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            // Password salah
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        // Username tidak ditemukan
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Bunga</title>
    <link rel="stylesheet" href="styleb.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>

<body>
    <div class="input">
        <h1>LOGIN</h1>
        <form method="POST" action="">
            <div class="box-input">
                <i class="fas fa-user-circle"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-input" name="login">Login</button>
            <p>Belum punya akun?
                <a href="register.php">Register disini</a>
            </p>
        </form>
    </div>
</body>

</html>
