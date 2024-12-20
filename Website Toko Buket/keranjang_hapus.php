<?php
session_start();
echo '<pre>';
print_r($_SESSION);
echo '</pre>';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = $_POST['index'];

    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
    }
    echo "success";

    header("Location: keranjang.php");
    exit();
}
