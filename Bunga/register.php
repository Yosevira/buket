<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (fullname, username, email, password, role) VALUES ('$fullname', '$username', '$email', '$password', 'customer')";
    $check_user = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username atau email sudah digunakan!');</script>";
    } else {
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi berhasil, silakan login!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="styleb.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <div class="input">
        <h1>REGISTER</h1>
        <form method="POST" action="">
            <div class="box-input">
                <i data-feather="user"></i>
                <input type="text" name="fullname" placeholder="Full Name" required>
            </div>
            <div class="box-input">
                <i data-feather="user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <i data-feather="mail"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="box-input">
                <i data-feather="lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span id="togglePassword" style="cursor: pointer;" data-feather="eye"></span>
            </div>
            <button type="submit" name="register" class="btn-input">Register</button>
            <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
        </form>
    </div>

    <!-- Script untuk mengganti ikon mata -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordField = document.querySelector('#password');

            togglePassword.addEventListener('click', function () {
                // Toggle tipe input antara 'password' dan 'text'
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle ikon antara eye dan eye-off
                this.setAttribute('data-feather', type === 'password' ? 'eye' : 'eye-off');
                feather.replace(); // Refresh ikon Feather untuk mengganti tampilan
            });
        });

        // Refresh semua ikon Feather saat halaman dimuat
        feather.replace();
    </script>
</body>

</html>