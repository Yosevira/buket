<?php
require 'koneksi.php'; // Memanggil file koneksi

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST["fullname"];
    $address = $_POST["address"];
    $total_price = $_POST["total_price"];
    $payment_method = $_POST["payment_method"];
    $cart = json_decode($_POST["cart"], true); // Keranjang dalam bentuk JSON

    // Menyimpan transaksi ke database
    $query = "INSERT INTO transaksi (fullname, address, total_price, payment_method) 
            VALUES ('$fullname', '$address', '$total_price', '$payment_method')";

    if (mysqli_query($conn, $query)) {
        $transaksi_id = mysqli_insert_id($conn); // ID transaksi terbaru

        // Menyimpan detail produk dalam keranjang
        foreach ($cart as $item) {
            $name = $item['name'];
            $price = $item['price'];
            $detail_query = "INSERT INTO transaksi_detail (transaksi_id, product_name, product_price) 
                            VALUES ('$transaksi_id', '$name', '$price')";
            mysqli_query($conn, $detail_query);
        }

        echo json_encode(["status" => "success", "message" => "Transaksi berhasil disimpan."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak valid."]);
}
?>
