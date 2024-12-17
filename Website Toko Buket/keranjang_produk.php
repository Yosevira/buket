<?php
session_start();

// Ambil data keranjang
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Format produk untuk JSON
$products = array_map(function ($item) {
    return [
        'name' => $item['name'],
        'price' => $item['price'],
        'quantity' => $item['quantity']
    ];
}, $cart);

header('Content-Type: application/json');
echo json_encode(['products' => $products]);
?>