<?php
require 'koneksi.php';

// Ambil data dari form
$fullname = $_POST["fullname"];
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

// Validasi data (opsional: tambahkan validasi lebih lanjut jika perlu)

// Simpan data ke database
$query_sql = "INSERT INTO tbl_users (fullname, username, email, password) 
            VALUES ('$fullname', '$username', '$email', '$password')";

if (mysqli_query($conn, $query_sql)) {
    echo "<center><h1>Registrasi Berhasil!</h1>
        <button><strong><a href='login.html'>Login Sekarang</a></strong></button></center>";
} else {
    echo "<center><h1>Registrasi Gagal: " . mysqli_error($conn) . "</h1>
        <button><strong><a href='register.html'>Kembali</a></strong></button></center>";
}
?>
