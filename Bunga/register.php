<?php
include 'koneksi.php';

// Fungsi untuk mendapatkan nama wilayah berdasarkan ID
function getNameById($data, $id) {
    foreach ($data as $row) {
        if ($row[0] == $id) {
            return $row[2]; // Kolom 2 adalah nama wilayah (di CSV)
        }
    }
    return null;
}

function readCSV($filename) {
    $data = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        fgetcsv($handle); // Skip header
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

// Baca data dari file CSV
$provinces = readCSV('data/provinces.csv');
$regencies = readCSV('data/regencies.csv');
$districts = readCSV('data/districts.csv');
$villages = readCSV('data/villages.csv');

if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $no_telepon = $_POST['no_telepon'];
    $dusun = $_POST['dusun'];

    // Validasi Password
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak sama!');</script>";
        exit;
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        echo "<script>alert('Password harus terdiri dari minimal 8 karakter, mengandung huruf alfabet dan angka.');</script>";
        exit;
    }

    // Validasi Nomor Telepon
    if (!preg_match('/^0\d{11,13}$/', $no_telepon)) {
        echo "<script>alert('Nomor telepon harus dimulai dengan 0 dan memiliki panjang 12-14 angka.');</script>";
        exit;
    }

    // Jika validasi lolos, hash password dan lanjutkan proses penyimpanan
    $password = password_hash($password, PASSWORD_BCRYPT);
    $desa_id = $_POST['desa'];
    $kecamatan_id = $_POST['kecamatan'];
    $kota_id = $_POST['kota'];
    $provinsi_id = $_POST['provinsi'];

    // Dapatkan nama wilayah berdasarkan ID
    $provinsi = getNameById($provinces, $provinsi_id);
    $kota = getNameById($regencies, $kota_id);
    $kecamatan = getNameById($districts, $kecamatan_id);
    $desa = getNameById($villages, $desa_id);

    // Format alamat
    $alamat = "Dusun: $dusun, Desa: $desa, Kecamatan: $kecamatan, Kota: $kota, Provinsi: $provinsi";

    $query = "INSERT INTO users (fullname, username, email, password, no_telepon, alamat, role) 
            VALUES ('$fullname', '$username', '$email', '$password', '$no_telepon', '$alamat', 'customer')";

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="input">
        <h1>REGISTER</h1>
        <form method="POST" action="">
            <div class="box-input">
                <input type="text" name="fullname" placeholder="Full Name" required>
            </div>
            <div class="box-input">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="box-input">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password"><i data-feather="eye"></i></span>
            </div>
            <div class="box-input">
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="box-input">
                <input type="text" id="no_telepon" name="no_telepon" placeholder="Nomor Telepon" required>
            </div>
            <div class="box-input">
                <input type="text" name="dusun" placeholder="Dusun" required>
            </div>
            <div class="box-input">
                <label for="provinsi">Provinsi:</label>
                <select id="provinsi" name="provinsi" required>
                    <option value="">Pilih Provinsi</option>
                    <?php foreach ($provinces as $provinsi): ?>
                        <option value="<?= $provinsi[0] ?>"><?= $provinsi[1] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="box-input">
                <label for="kota">Kota/Kabupaten:</label>
                <select id="kota" name="kota" required>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>
            <div class="box-input">
                <label for="kecamatan">Kecamatan:</label>
                <select id="kecamatan" name="kecamatan" required>
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>
            <div class="box-input">
                <label for="desa">Desa:</label>
                <select id="desa" name="desa" required>
                    <option value="">Pilih Desa</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn-input">Register</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            const regencies = <?= json_encode($regencies) ?>;
            const districts = <?= json_encode($districts) ?>;
            const villages = <?= json_encode($villages) ?>;

            $('#provinsi').on('change', function() {
                const provinsiId = $(this).val();
                $('#kota').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
                regencies.forEach(function(regency) {
                    if (regency[1] === provinsiId) {
                        $('#kota').append(`<option value="${regency[0]}">${regency[2]}</option>`);
                    }
                });
            });

            $('#kota').on('change', function() {
                const kotaId = $(this).val();
                $('#kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');
                districts.forEach(function(district) {
                    if (district[1] === kotaId) {
                        $('#kecamatan').append(`<option value="${district[0]}">${district[2]}</option>`);
                    }
                });
            });

            $('#kecamatan').on('change', function() {
                const kecamatanId = $(this).val();
                $('#desa').empty().append('<option value="">Pilih Desa</option>');
                villages.forEach(function(village) {
                    if (village[1] === kecamatanId) {
                        $('#desa').append(`<option value="${village[0]}">${village[2]}</option>`);
                    }
                });
            });
        });
        $(document).ready(function () {
            // Show/Hide Password
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

            // Validasi Password
            $('#password, #confirm-password').on('input', function () {
                const password = $('#password').val();
                const confirmPassword = $('#confirm-password').val();
                const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d).{8,}$/;

                if (!passwordRegex.test(password)) {
                    $('#password').get(0).setCustomValidity('Password harus terdiri dari minimal 8 karakter, mengandung huruf alfabet dan angka.');
                } else {
                    $('#password').get(0).setCustomValidity('');
                }

                if (confirmPassword !== password) {
                    $('#confirm-password').get(0).setCustomValidity('Password tidak sama.');
                } else {
                    $('#confirm-password').get(0).setCustomValidity('');
                }
            });

            // Validasi Nomor Telepon
            $('#no_telepon').on('input', function () {
                const phone = $(this).val();
                const phoneRegex = /^0\d{11,13}$/; // Dimulai dengan 0, panjang 12-14 karakter.

                if (!phoneRegex.test(phone)) {
                    $('#no_telepon').get(0).setCustomValidity('Nomor telepon harus dimulai dengan 0 dan memiliki panjang 12-14 angka.');
                } else {
                    $('#no_telepon').get(0).setCustomValidity('');
                }
            });
        });
    </script>
</body>

</html>
