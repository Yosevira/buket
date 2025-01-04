<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Bunga</title>
    <link rel="stylesheet" href="styleb.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        .box-input {
            position: relative;
            margin-bottom: 15px;
        }

        .box-input input {
            width: 100%;
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="input">
        <h1>LOGIN</h1>
        <form method="POST" action="">
            <div class="box-input">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password"><i data-feather="eye"></i></span>
            </div>
            <button type="submit" class="btn-input" name="login">Login</button>
            <p>Belum punya akun? <a href="register.php">Register disini</a></p>
        </form>
    </div>

    <script>
        feather.replace();

        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            // Toggle tipe password
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // Ubah ikon secara langsung
            togglePassword.innerHTML = isPassword ? '<i data-feather="eye-off"></i>' : '<i data-feather="eye"></i>';
            feather.replace(); // Gambar ulang ikon baru
        });
    </script>
</body>
</html>

