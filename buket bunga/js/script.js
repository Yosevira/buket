// Redirect to login page when "Order Sekarang" or "Pilih Buket" is clicked
document.getElementById('order-now').addEventListener('click', () => {
    alert("Silakan login untuk memesan.");
    window.location.href = "login.html";
});

const addToCartButtons = document.querySelectorAll('.add-to-cart');
addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {
        alert("Silakan login untuk memilih buket.");
        window.location.href = "login.html";
    });
});

// Login form submission
if (document.getElementById('login-form')) {
    document.getElementById('login-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        localStorage.setItem('user', email);
        alert("Login berhasil!");
        window.location.href = "index.html";
    });
}

// Show login status in index.html
if (document.getElementById('login-btn')) {
    const user = localStorage.getItem('user');
    if (user) {
        document.getElementById('login-btn').innerText = `Hi, ${user}`;
    }
}

// Cek status login di seluruh halaman
const isLoggedIn = localStorage.getItem('user');

if (isLoggedIn) {
    const loginBtn = document.getElementById('login-btn');
    if (loginBtn) loginBtn.innerText = `Hi, ${isLoggedIn}`;
} else {
    // Redirect ke login jika belum login dan berada di halaman menu atau transaksi
    const currentPage = window.location.pathname.split('/').pop();
    if (currentPage === 'menu.html' || currentPage === 'transaksi.html') {
        alert('Silakan login terlebih dahulu!');
        window.location.href = 'login.html';
    }
}

// Keranjang Belanja
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Tambah ke keranjang
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        if (!isLoggedIn) {
            alert('Login untuk menambahkan ke keranjang!');
            window.location.href = 'login.html';
            return;
        }

        const name = button.dataset.name;
        const price = parseInt(button.dataset.price);
        const product = { name, price };

        cart.push(product);
        localStorage.setItem('cart', JSON.stringify(cart));

        alert(`${name} berhasil ditambahkan ke keranjang!`);
    });
});

// Tampilkan keranjang di halaman transaksi
if (window.location.pathname.split('/').pop() === 'transaksi.html') {
    const orderSummary = document.getElementById('order-summary');
    const totalPriceElem = document.getElementById('total-price');
    let total = 0;

    if (cart.length > 0) {
        cart.forEach((item, index) => {
            const productDiv = document.createElement('div');
            productDiv.innerHTML = `
                <p><strong>${item.name}</strong> - Rp${item.price.toLocaleString()}</p>
            `;
            orderSummary.appendChild(productDiv);
            total += item.price;
        });
    } else {
        orderSummary.innerHTML = '<p>Keranjang kosong. Silakan kembali ke menu.</p>';
    }

    totalPriceElem.value = `Rp${total.toLocaleString()}`;
}

// Checkout di halaman transaksi
if (document.getElementById('checkout-form')) {
    document.getElementById('checkout-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const name = e.target.fullname.value;
        const address = e.target.address.value;
        const paymentMethod = e.target['payment-method'].value;

        alert(`Transaksi berhasil!\nNama: ${name}\nAlamat: ${address}\nMetode Pembayaran: ${paymentMethod}`);
        localStorage.removeItem('cart'); // Kosongkan keranjang
        window.location.href = 'menu.html'; // Kembali ke menu
    });
}

// Login form submission
if (document.getElementById('login-form')) {
    document.getElementById('login-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        localStorage.setItem('user', email);
        alert("Login berhasil!");
        window.location.href = "index.html";
    });
}
