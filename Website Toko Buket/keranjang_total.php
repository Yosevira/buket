<?php
session_start();
$conn = new mysqli("localhost", "root", "", "produk"); // Gunakan nama database "produk"

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data keranjang dari sesi
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$total = 0;

foreach ($cart as $item) {
    // Query untuk mendapatkan harga produk berdasarkan ID
    $sql = "SELECT harga FROM produk WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total += $row['harga'] * $item['quantity'];
    }
}

header('Content-Type: application/json');
echo json_encode(['total' => $total]);
?>
