-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2024 at 01:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buket_bunga`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Buket Bunga', '2024-12-10 10:09:00'),
(2, 'Buket Boneka', '2024-12-10 10:09:00'),
(3, 'Buket Snack', '2024-12-10 10:09:00'),
(4, 'Buket Kerudung', '2024-12-10 10:09:00'),
(5, 'Buket Uang', '2024-12-10 10:09:00');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `transaksi_id`, `produk_id`, `nama_produk`, `jumlah`, `harga`, `total_harga`) VALUES
(11, 5, 2, 'Buket Boneka', 4, 38000.00, 152000.00),
(12, 5, 6, 'Buket Tulip', 3, 43000.00, 129000.00),
(13, 6, 1, 'Buket Bunga', 1, 45000.00, 45000.00),
(14, 6, 6, 'Buket Tulip', 1, 43000.00, 43000.00),
(15, 7, 16, 'Buket Snack', 5, 85000.00, 425000.00),
(16, 7, 1, 'Buket Bunga', 3, 45000.00, 135000.00),
(17, 7, 8, 'Buket Snack Uang', 1, 110000.00, 110000.00),
(18, 7, 6, 'Buket Tulip', 2, 43000.00, 86000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `category_id`, `name`, `price`, `description`, `created_at`, `image`) VALUES
(1, 1, 'Buket Bunga', 45000.00, 'Mawar Merah Muda', '2024-12-10 10:09:14', 'buket_bunga.jpg'),
(2, 2, 'Buket Boneka', 38000.00, 'Boneka yang lucu', '2024-12-10 10:09:14', 'buket_boneka.jpg'),
(4, 4, 'Buket Kerudung', 37000.00, 'Dua Kerudung Paris', '2024-12-10 10:09:14', 'buket_kerudung.jpg'),
(5, 5, 'Buket Uang', 30000.00, '15 Lembar 20k', '2024-12-10 10:09:14', 'buket_uang.jpg'),
(6, 1, 'Buket Tulip', 43000.00, 'Bunga Tulip Yang cantik', '2024-12-25 07:04:20', 'tulip.jpg'),
(7, 1, 'Buket Bunga Biru', 40000.00, '5 Mawar Biru', '2024-12-26 15:22:18', 'IMG-20241208-WA0006.jpg'),
(8, 1, 'Buket Snack Uang', 110000.00, 'Beng2 & Uang 5k', '2024-12-26 15:28:21', 'IMG-20241208-WA0021.jpg'),
(9, 1, 'Buket Snack', 40000.00, 'Snack Kalpa', '2024-12-26 15:29:22', 'IMG-20241208-WA0010.jpg'),
(12, 2, 'Buket Kupu -kupu', 40000.00, 'Toper Kupu2 & Lampu Led', '2024-12-26 15:35:57', 'IMG-20241208-WA0022.jpg'),
(13, 1, 'Buket Snack', 60000.00, 'Beng2 & Nextar', '2024-12-26 15:38:55', 'IMG-20241226-WA0007.jpg'),
(14, 1, 'Buket Toper', 38000.00, 'Toper Kupu- kupu', '2024-12-26 15:41:41', 'IMG-20241208-WA0019.jpg'),
(15, 1, 'Buket Karakter', 250000.00, 'Karakter Batman', '2024-12-26 15:42:50', 'IMG-20241226-WA0006.jpg'),
(16, 1, 'Buket Snack', 85000.00, 'Snack & kupu- kupu', '2024-12-26 15:43:42', 'IMG-20241226-WA0008.jpg'),
(17, 1, 'Buket Mix', 55000.00, 'Boneka & Snack', '2024-12-26 15:44:55', 'IMG-20241215-WA0003.jpg'),
(18, 1, 'Buket Snack', 38000.00, 'Snack Dilan Favorit', '2024-12-26 15:45:48', 'IMG-20241226-WA0009.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `kode_pesanan` varchar(8) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_pemesan` varchar(255) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `kode_pesanan`, `user_id`, `nama_pemesan`, `total`, `alamat_pengiriman`, `metode_pembayaran`, `created_at`) VALUES
(5, '1CMH2ZG5', 16, 'burhanudin', 421000.00, 'plemahan', 'shopee', '2024-12-25 09:05:59'),
(6, 'YU1KJ5SH', 18, 'aku', 88000.00, 'sugihwaras', 'dana', '2024-12-25 09:07:03'),
(7, 'YEW54823', 19, 'Lani', 756000.00, 'Kalimantan Timur', 'qris', '2024-12-26 15:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(225) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `alamat`, `no_telepon`, `password`, `role`, `created_at`) VALUES
(16, 'burhanudin', 'burhan', 'gmgdoni729@gmail.com', NULL, NULL, '$2y$10$B938ltKNTG4Y9H5EUIBRzORorRk4q0qthHzmRZg0i0lYR11fgzC3m', 'customer', '2024-12-25 08:17:14'),
(17, 'admin', 'admin', 'ADMIN SELALU JAGO', NULL, NULL, '$2y$10$CLRQf240UvDz2kLccngCqed9cbZ08OAgjltr9MuKKFFSXO8WX9cB.', 'admin', '2024-12-25 09:02:58'),
(18, 'aku', 'aku', 'aku@aku.aku', NULL, NULL, '$2y$10$o.CRADCyw/aB7Kptt9.Qx.tatzOfcD5XPFgZxwQDmokYz.YWk.jMy', 'customer', '2024-12-25 09:04:54'),
(19, 'yosevira', 'yose', 'yose@gmail.com', 'jogoroto,jombang', '0987654321', '$2y$10$a70qC8dyxCQgvIrlAQKCIu7.LdGRvd9o0kJldtCL3c1lyB7FgSQwq', 'customer', '2024-12-26 14:57:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`order_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
