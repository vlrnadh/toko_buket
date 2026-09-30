-- Database: toko_buket (Nadha Florist)
-- Import lewat phpMyAdmin > tab Import, atau salin ke tab SQL.

CREATE DATABASE IF NOT EXISTS `toko_buket` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `toko_buket`;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Login admin: username = admin, password = password  (segera ganti setelah berhasil login)
INSERT INTO `admin` (`nama`, `username`, `password`) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Data contoh (foto bisa diunggah lewat halaman admin)
INSERT INTO `produk` (`nama`, `kategori`, `deskripsi`, `harga`, `stok`, `gambar`) VALUES
('Rose Blush Bouquet', 'Hand Bouquet', 'Dua belas mawar pink muda dengan baby breath, dibungkus kertas kraft dan pita satin. Cocok untuk hadiah ulang tahun atau anniversary.', 185000.00, 12, NULL),
('Sunny Gerbera', 'Hand Bouquet', 'Gerbera kuning dan oranye yang ceria, dipadukan daun eucalyptus segar.', 150000.00, 10, NULL),
('Graduation Sunflower', 'Bouquet Wisuda', 'Bunga matahari dengan boneka wisuda kecil dan selempang ucapan selamat.', 210000.00, 8, NULL),
('Money Bouquet 10 Lembar', 'Money Bouquet', 'Uang pecahan disusun menyerupai bunga, dengan hiasan pita dan kartu ucapan. Uang disediakan pembeli.', 120000.00, 15, NULL),
('Tulip Pastel Box', 'Flower Box', 'Tulip pastel dalam box bulat. Tahan lama, mudah dibawa, dan siap dihadiahkan.', 275000.00, 5, NULL),
('Baby Breath Cloud', 'Bunga Meja', 'Rangkaian baby breath putih dalam vas kaca untuk mempercantik meja tamu atau meja kerja.', 95000.00, 20, NULL),
('Lily Wedding Bouquet', 'Bouquet Pernikahan', 'Bouquet pengantin dari lily putih dan mawar krem, dapat disesuaikan dengan tema acara.', 450000.00, 3, NULL);
