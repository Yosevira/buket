<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "INSERT INTO users (fullname, username, email, password, role) VALUES ('$fullname', '$username', '$email', '$password', 'customer')";
        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Validasi data (misalnya, pastikan username/email unik)
        $check_user = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $check_user);
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registrasi berhasil, silakan login!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stayleb.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <title>Register Page</title>
</head>

<body>
    <div class="input">
        <h1>REGISTER</h1>
        <form method="POST" action="">
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
            <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
            </div>
        </form>
    </div>
</body>

</html>
