<?php
require 'koneksi.php';

$username = $_POST["username"];
$password = $_POST["password"];

// Cari pengguna berdasarkan username
$query_sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query_sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        // Redirect ke menu setelah login
        header("Location: menu.php");
    } else {
        echo "<script>alert('Password salah!'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('Username tidak ditemukan!'); window.location.href='login.html';</script>";
}
?>
