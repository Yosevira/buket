<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = $_POST['index'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$index]) && $quantity > 0) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
    }

    header("Location: keranjang.php");
    exit();
}
