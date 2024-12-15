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
        <button><strong><a href='register.php'>Kembali</a></strong></button></center>";
} else {
    // Simpan data ke database
    $query_sql = "INSERT INTO users (fullname, username, email, password, role) 
                VALUES ('$fullname', '$username', '$email', '$hashed_password', '$role')";

    if (mysqli_query($conn, $query_sql)) {
        echo "<center><h1>Registrasi Berhasil!</h1>
            <button><strong><a href='../login.php'>Login Sekarang</a></strong></button></center>";
    } else {
        echo "<center><h1>Registrasi Gagal: " . mysqli_error($conn) . "</h1>
            <button><strong><a href='../register.php'>Kembali</a></strong></button></center>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stayleb.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <title>Register Page</title>
</head>

<body>
    <div class="input">
        <h1>REGISTER</h1>
        <form action="php/register.php" method="POST">
            <div class="box-input">
                <i class="fas fa-user"></i>
                <input type="text" name="fullname" placeholder="Full Name" required>
            </div>
            <div class="box-input">
                <i class="fas fa-user-circle"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="register" class="btn-input">Register</button>
            <div class="bottom">
                <p>Sudah punya akun?
                    <a href="login.html">Login disini</a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>