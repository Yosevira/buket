// Navbar login/logout dinamis
const authLink = document.getElementById('auth-link');
const isLoggedIn = localStorage.getItem('user'); // Periksa apakah user sudah login

if (isLoggedIn) {
    authLink.innerHTML = `<a href="#" id="logout-btn">Logout</a>`;
    document.getElementById('logout-btn').addEventListener('click', () => {
        localStorage.removeItem('user'); // Hapus data login dari localStorage
        alert("Anda telah logout!");
        window.location.href = 'index.php'; // Arahkan ke halaman utama
    });
} else {
    authLink.innerHTML = `<a href="login.php">Login</a>`;
}

// Menu Toggle
document.getElementById('menu-toggle').addEventListener('click', function () {
    document.getElementById('menu').classList.toggle('active');
});

// Keranjang Toggle (jika diperlukan)
document.getElementById('cart-toggle').addEventListener('click', function () {
    alert("Fitur keranjang belum tersedia.");
});

// Inisialisasi keranjang belanja
let cart = JSON.parse(localStorage.getItem('cart')) || []; // Ambil data keranjang dari localStorage

// Tambahkan produk ke keranjang belanja
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        if (!isLoggedIn) {
            alert('Silakan login terlebih dahulu!');
            window.location.href = 'login.php'; // Arahkan ke halaman login jika belum login
            return;
        }

        const name = button.dataset.name; // Nama produk
        const price = parseInt(button.dataset.price); // Harga produk
        const product = { name, price }; // Buat objek produk

        cart.push(product); // Tambahkan produk ke keranjang
        localStorage.setItem('cart', JSON.stringify(cart)); // Simpan keranjang ke localStorage

        alert(`${name} berhasil ditambahkan ke keranjang!`);
    });
});

// Tampilkan keranjang di halaman transaksi
if (document.getElementById('order-summary')) {
    const orderSummary = document.getElementById('order-summary'); // Elemen untuk menampilkan ringkasan pesanan
    const totalPriceElem = document.getElementById('total-price'); // Elemen untuk menampilkan total harga
    let total = 0;

    if (cart.length > 0) {
        cart.forEach(item => {
            const productDiv = document.createElement('div');
            productDiv.innerHTML = `
                <p><strong>${item.name}</strong> - Rp${item.price.toLocaleString()}</p>
            `;
            orderSummary.appendChild(productDiv); // Tambahkan elemen produk ke ringkasan
            total += item.price; // Tambahkan harga ke total
        });
    } else {
        orderSummary.innerHTML = '<p>Keranjang kosong. Silakan kembali ke menu.</p>';
    }

    totalPriceElem.value = `Rp${total.toLocaleString()}`; // Tampilkan total harga
}

// Cek status login di halaman menu atau transaksi
const currentPage = window.location.pathname.split('/').pop();
if (!isLoggedIn && (currentPage === 'menu.php' || currentPage === 'transaksi.html')) {
    alert('Silakan login terlebih dahulu!');
    window.location.href = 'login.php';
}

// Tombol "Order Sekarang"
const orderNowButton = document.getElementById('order-now');

if (orderNowButton) {
    orderNowButton.addEventListener('click', () => {
        if (!isLoggedIn) {
            alert('Silakan login untuk memesan.');
            window.location.href = 'login.php';
        } else {
            window.location.href = 'menu.php'; // Arahkan ke halaman menu jika sudah login
        }
    });
}
