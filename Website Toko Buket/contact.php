<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="stayle.css">
</head>

<body>
    <header>
        <nav>
            <div class="logo">Byouqet</div>
                <ul class="nav-links">
                    <div id="kontak" class="hidden">
                        <a href="home.php">Home</a>
                        <a href="about.php">Tentang Kami</a>
                        <a href="menu.php">Menu</a>
                        <a href="contact.php">Kontak Kami</a>
                        <a href="profil.php" class="icon-link"><i data-feather="user"></i></a>
                        <a href="logout.php" class="icon-link"><i data-feather="log-out"></i></a>
                    </div>
                </ul>
        </nav>
    </header>
    <section id="contact">
        <h2>Kontak Kami</h2>
        <div class="content">
            <form id="contactForm">
                <div class="form-group"><label for="name" data-key="name">Nama</label><input type="text"
                        class="form-control" id="name" placeholder="Nama Anda" required></div>
                <div class="form-group"><label for="email" data-key="email">Email</label><input type="text"
                        class="form-control" id="email" placeholder="Email Anda" required></div>
                <div class="form-group"><label for="message" data-key="message">Pesan</label><textarea
                        class="form-control" id="message" rows="4" placeholder="Pesan Anda" required></textarea></div>
                <button type="submit" class="btn btn-warning" data-key="send">Kirim</button>
            </form>
        </div>
        <div class="contact-list">
            <div class="contact-item"><i data-feather="phone"></i><a href="https://wa.me/085231569104">WhatsApp</a>
            </div>
            <div class="contact-item"><i data-feather="instagram"></i><a
                    href="https://instagram.com/byouqet">Instagram</a></div>
            <div class="contact-item"><i data-feather="facebook"></i><a href="https://facebook.com/byouqet">Facebook</a>
            </div>
            <div class="contact-item"><i data-feather="mail"></i><a href="byouqet@gmail.com?subject=Halo%20Byouqet&body=Halo%20tim%20Byouqet,%20saya%20ingin%20bertanya%20mengenai...">Email</a>
            </div>
    </section>
    <div class="footer">
        <p>© 2024 BYOUQET BUNGA. All rights reserved.</p>
    </div>
    <script src="script.js"></script>
    <script>
    feather.replace();

    document.getElementById('contactForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Ambil nilai dari form
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var message = document.getElementById('message').value;

            // Validasi email
            if (!validateEmail(email)) {
                alert("Alamat email tidak valid, mohon periksa kembali.");
                return;
            }

            // Format pesan WhatsApp
            var whatsappUrl =
                `https://wa.me/6285231569104?text=Nama:%20${encodeURIComponent(name)}%0AEmail:%20${encodeURIComponent(email)}%0APesan:%20${encodeURIComponent(message)}`;

            // Buka WhatsApp
            window.open(whatsappUrl, '_blank');
        }

    );

    // Fungsi validasi email
    function validateEmail(email) {
        var re = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
        return re.test(String(email).toLowerCase());
    }
    </script>
</body>

</html>