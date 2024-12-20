<?php
session_start();
echo '<pre>';
print_r($_SESSION);
echo '</pre>';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = $_POST['index'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$index]) && $quantity > 0) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
    }
    echo "success";

    header("Location: keranjang.php");
    exit();
}
