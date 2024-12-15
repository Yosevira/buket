<?php
// Konfigurasi database
$servername = "localhost"; // Nama host
$username = "root"; // Username database Anda
$password = ""; // Password database Anda
$dbname = "buket_bunga"; // Nama database Anda

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>