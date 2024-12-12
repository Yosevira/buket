<?php
require 'koneksi.php';

// Ambil data dari form
$fullname = $_POST["fullname"];
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$role = "customer"; // Default role adalah customer

// Enkripsi password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Validasi data (misalnya, pastikan username/email unik)
$check_user = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
$result = mysqli_query($conn, $check_user);

if (mysqli_num_rows($result) > 0) {
    echo "<center><h1>Username atau Email sudah terdaftar!</h1>
        <button><strong><a href='register.html'>Kembali</a></strong></button></center>";
} else {
    // Simpan data ke database
    $query_sql = "INSERT INTO users (fullname, username, email, password, role) 
                VALUES ('$fullname', '$username', '$email', '$hashed_password', '$role')";

    if (mysqli_query($conn, $query_sql)) {
        echo "<center><h1>Registrasi Berhasil!</h1>
            <button><strong><a href='../login.html'>Login Sekarang</a></strong></button></center>";
    } else {
        echo "<center><h1>Registrasi Gagal: " . mysqli_error($conn) . "</h1>
            <button><strong><a href='../register.html'>Kembali</a></strong></button></center>";
    }
}
?>
