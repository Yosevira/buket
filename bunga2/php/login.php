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
        echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Username tidak ditemukan!'); window.location.href='login.php';</script>";
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
    <title>Login Page</title>
</head>

<body>
    <div class="input">
        <h1>LOGIN</h1>
        <form action="menu.php" method="POST">
            <div class="box-input">
                <i class="fas fa-user-circle"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn-input">Login</button>
            <div class="bottom">
                <p>Belum punya akun?
                    <a href="register.php">Register disini</a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>