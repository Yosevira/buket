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
    SELECT transaksi.kode_pesanan, transaksi.created_at AS tanggal, users.fullname, transaksi.total
    FROM transaksi
    INNER JOIN users ON transaksi.user_id = users.id
    WHERE transaksi.kode_pesanan = ?
";
$stmt_transaksi = $conn->prepare($query_transaksi);
$stmt_transaksi->bind_param("s", $kode_pesanan); // Menggunakan tipe string karena kode pesanan sering berupa string
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
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>Bukti Pembayaran</h1>
    <div class='info'>
        <p><strong>Nama Pemesan:</strong> {$data_transaksi['fullname']}</p>
        <p><strong>Kode Pesanan:</strong> {$data_transaksi['kode_pesanan']}</p>
        <p><strong>Waktu:</strong> {$data_transaksi['tanggal']}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
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
    <p><strong>Total Keseluruhan:</strong> Rp " . number_format($total_semua, 0, ',', '.') . "</p>
    <div class='footer'>Terima kasih telah berbelanja!</div>
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
