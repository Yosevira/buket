<?php
require 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

include 'koneksi.php';

// Cek apakah kode pesanan diterima
if (!isset($_GET['id'])) {
    die("Kode pesanan tidak ditemukan.");
}

$kode_pesanan = $_GET['id'];

// Ambil data transaksi berdasarkan kode pesanan
$query_transaksi = "
    SELECT transaksi.kode_pesanan, transaksi.created_at AS tanggal, transaksi.tanggal_pengambilan, 
        users.fullname, transaksi.alamat_pengiriman AS alamat, transaksi.total, transaksi.metode_pembayaran AS payment, transaksi.catatan
    FROM transaksi
    INNER JOIN users ON transaksi.user_id = users.id
    WHERE transaksi.kode_pesanan = ?
";
$stmt_transaksi = $conn->prepare($query_transaksi);
$stmt_transaksi->bind_param("s", $kode_pesanan);
$stmt_transaksi->execute();
$result_transaksi = $stmt_transaksi->get_result();
$data_transaksi = $result_transaksi->fetch_assoc();

if (!$data_transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Ambil detail produk yang dibeli
$query_produk = "
    SELECT produk.name AS nama_produk, produk.price AS harga_satuan, detail_transaksi.jumlah, 
           (produk.price * detail_transaksi.jumlah) AS subtotal
    FROM detail_transaksi
    INNER JOIN produk ON detail_transaksi.produk_id = produk.id
    WHERE detail_transaksi.transaksi_id = (
        SELECT id FROM transaksi WHERE kode_pesanan = ?
    )
";
$stmt_produk = $conn->prepare($query_produk);
$stmt_produk->bind_param("s", $kode_pesanan);
$stmt_produk->execute();
$result_produk = $stmt_produk->get_result();

// Bangun HTML untuk struk
$html = "
<!DOCTYPE html>
<html>
<head>
    <title>{$data_transaksi['fullname']}</title>
    <style>
        @font-face {
            font-family: 'Pristina';
            src: url('fonts/Pristina 400.ttf') format('truetype');
        }
        @font-face {
            font-family: 'SimSunExtB';
            src: url('fonts/simsunb.ttf') format('truetype');
        }
        body {
            font-family: 'SimSunExtB';
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100%;
            height: auto;
        }
        h1 {
            font-family: 'Pristina';
            font-size: 24px;
            margin: 10px 0;
        }
        .info {
            font-size: 14px;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
            font-family: 'SimSunExtB';
            font-size: 14px;
        }
        table td {
            font-family: 'SimSunExtB';
            font-size: 14px;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
            font-family: 'SimSunExtB';
        }
        .footer {
            text-align: center;
            font-family: 'Pristina';
            margin-top: 20px;
            font-style: italic;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class='header'>
        <img src='foto/Logo Struk.png' alt='Logo Toko'>
        <h1>Struk Transaksi</h1>
    </div>
    <div class='info'>
        <p><strong>Nama Pemesan:</strong> {$data_transaksi['fullname']}</p>
        <p><strong>Alamat:</strong> {$data_transaksi['alamat']}</p>
        <p><strong>Kode Pesanan:</strong> {$data_transaksi['kode_pesanan']}</p>
        <p><strong>Waktu Pemesanan:</strong> {$data_transaksi['tanggal']}</p>
        <p><strong>Tanggal Pengambilan:</strong> {$data_transaksi['tanggal_pengambilan']}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>";

$total_semua = 0;
while ($row_produk = $result_produk->fetch_assoc()) {
    $total_semua += $row_produk['subtotal'];
    $html .= "
        <tr>
            <td>{$row_produk['nama_produk']}</td>
            <td>Rp " . number_format($row_produk['harga_satuan'], 0, ',', '.') . "</td>
            <td>{$row_produk['jumlah']}</td>
            <td>Rp " . number_format($row_produk['subtotal'], 0, ',', '.') . "</td>
        </tr>";
}

$html .= "
        </tbody>
    </table>
    <div class='totals'>
        <p><strong>Total Keseluruhan:</strong> Rp " . number_format($total_semua, 0, ',', '.') . "</p>
        <p><strong>Metode Pembayaran:</strong> {$data_transaksi['payment']}</p>
    </div>
    <p><strong>Catatan:</strong> {$data_transaksi['catatan']}</p>
    <div class='footer'>
        Buket Cantik Di Tangan Anda<br>
        Terima Kasih Sudah Berbelanja
    </div>
</body>
</html>
";

// Buat PDF menggunakan DOMPDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output ke browser
$dompdf->stream("struk_transaksi_{$data_transaksi['kode_pesanan']}.pdf", ["Attachment" => false]);
?>