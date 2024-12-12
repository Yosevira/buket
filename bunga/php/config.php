<?php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username Anda
$password = ""; // Sesuaikan dengan password Anda
$dbname = "buket_bunga"; // Nama database baru yang telah diganti

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
