<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        if ($row['role'] == 'admin') {
            header("Location: php/admin.php");
        } else {
            header("Location: home.php");
        }
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
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
                    <a href="register.html">Register disini</a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>
