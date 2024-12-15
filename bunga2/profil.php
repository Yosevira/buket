<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/stayle.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Buket Bunga</div>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.html">Kontak</a></li>
                <li><a href="#" id="logout-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section id="profil">
        <h2>Profil Pengguna</h2>
        <div class="profil-info">
            <p><strong>Email:</strong> <span id="user-email"></span></p>
            <p><strong>Full Name:</strong> <span id="user-fullname">John Doe</span></p>
            <p><strong>Riwayat Transaksi:</strong> <a href="riwayat.html">Lihat Riwayat</a></p>
        </div>
    </section>

    <footer>
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </footer>

    <script>
        // Menampilkan email pengguna dari localStorage
        const userEmail = localStorage.getItem('user');
        if (userEmail) {
            document.getElementById('user-email').innerText = userEmail;
        } else {
            alert("Silakan login terlebih dahulu.");
            window.location.href = "login.html";
        }

        // Logout
        document.getElementById('logout-btn').addEventListener('click', () => {
            localStorage.removeItem('user');
            alert("Logout berhasil!");
            window.location.href = "index.html";
        });
    </script>
</body>
</html>
