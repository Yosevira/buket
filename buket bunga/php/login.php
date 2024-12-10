<?php
require 'koneksi.php';

$email = $_POST["email"];
$password = $_POST["password"];

// Gunakan password hashing untuk keamanan
$query_sql = "SELECT * FROM tbl_users WHERE email = '$email'";
$result = mysqli_query($conn, $query_sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Periksa kecocokan password
    if ($password === $row["password"]) { // Gunakan password_verify jika ada hash
        header("Location: dashboard.html");
    } else {
        echo "<center><h1>Password Anda Salah. Silahkan Coba Login Kembali.</h1>
                <button><strong><a href='login.html'>Login</a></strong></button></center>";
    }
} else {
    echo "<center><h1>Email Tidak Ditemukan. Silahkan Register Terlebih Dahulu.</h1>
            <button><strong><a href='register.html'>Register</a></strong></button></center>";
}
?>
