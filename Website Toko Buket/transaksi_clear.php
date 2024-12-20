<?php
session_start();
unset($_SESSION['checkout_products']); // Hapus data checkout
unset($_SESSION['total_price']); // Hapus total harga
echo json_encode(['success' => true]);
?>
