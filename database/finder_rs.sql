-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 07, 2025 at 06:50 AM
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
-- Database: `finder_rs`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun_admin`
--

CREATE TABLE `akun_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `akun_admin`
--

INSERT INTO `akun_admin` (`id_admin`, `username`, `email`, `password`, `role`) VALUES
(1, 'superadmin', 'admin@example.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'super');

-- --------------------------------------------------------

--
-- Table structure for table `akun_rumah_sakit`
--

CREATE TABLE `akun_rumah_sakit` (
  `id_rs_akun` int(11) NOT NULL,
  `id_rs` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `status_akun` varchar(30) DEFAULT 'aktif',
  `role_rs` varchar(50) DEFAULT 'rs'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `akun_rumah_sakit`
--

INSERT INTO `akun_rumah_sakit` (`id_rs_akun`, `id_rs`, `username`, `password`, `email`, `status_akun`, `role_rs`) VALUES
(1, 1, 'rs_fatma', 'rs_pw', 'contact@fatmawati.id', 'aktif', 'rs'),
(2, 2, 'rs_rcm', 'rs_pw', 'contact@rscm.id', 'aktif', 'rs');

-- --------------------------------------------------------

--
-- Table structure for table `akun_user`
--

CREATE TABLE `akun_user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telpon` varchar(30) DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `akun_user`
--

INSERT INTO `akun_user` (`id_user`, `nama`, `email`, `password`, `no_telpon`, `tanggal_daftar`) VALUES
(1, 'Budi Santoso', 'budi@example.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', '081234567890', '2025-12-03 14:36:32'),
(2, 'Siti Aminah', 'siti@example.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', '081298765432', '2025-12-03 14:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `data_layanan_rs`
--

CREATE TABLE `data_layanan_rs` (
  `id_layanan` int(11) NOT NULL,
  `id_rs` int(11) NOT NULL,
  `nama_layanan` varchar(150) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `ketersediaan_layanan` varchar(100) DEFAULT 'Tersedia',
  `create_by` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_rs_akun` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_layanan_rs`
--

INSERT INTO `data_layanan_rs` (`id_layanan`, `id_rs`, `nama_layanan`, `kategori`, `ketersediaan_layanan`, `create_by`, `id_admin`, `id_rs_akun`) VALUES
(1, 1, 'UGD 24 Jam', 'Gawat Darurat', 'Tersedia', NULL, 1, NULL),
(2, 1, 'Onkologi', 'Spesialis', 'Tersedia', NULL, 1, NULL),
(3, 2, 'Bedah Jantung', 'Bedah', 'Tersedia', NULL, 1, NULL),
(4, 2, 'Radiologi', 'Penunjang', 'Tersedia', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_penjadwalan`
--

CREATE TABLE `data_penjadwalan` (
  `id_penjadwalan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_rs` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `no_nik` varchar(50) DEFAULT NULL,
  `nama_pasien` varchar(150) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `status` enum('Menunggu','Dikonfirmasi','Dibatalkan','Selesai') DEFAULT 'Menunggu',
  `catatan` text DEFAULT NULL,
  `dibuat_pada` datetime DEFAULT current_timestamp(),
  `queue_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_penjadwalan`
--

INSERT INTO `data_penjadwalan` (`id_penjadwalan`, `id_user`, `id_rs`, `id_layanan`, `no_nik`, `nama_pasien`, `tanggal_kunjungan`, `status`, `catatan`, `dibuat_pada`, `queue_number`) VALUES
(1, 1, 1, 1, '1234567890123456', 'Budi Santoso', '2025-12-04', 'Menunggu', 'Catatan contoh', '2025-12-03 14:36:32', 'F-001');

-- --------------------------------------------------------

--
-- Table structure for table `data_rumah_sakit`
--

CREATE TABLE `data_rumah_sakit` (
  `id_rs` int(11) NOT NULL,
  `nama_rs` varchar(200) NOT NULL,
  `alamat` text DEFAULT NULL,
  `wilayah` varchar(100) DEFAULT NULL,
  `no_telpon` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `dibuat_pada` datetime DEFAULT current_timestamp(),
  `diperbarui_pada` datetime DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default_rs.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_rumah_sakit`
--

INSERT INTO `data_rumah_sakit` (`id_rs`, `nama_rs`, `alamat`, `wilayah`, `no_telpon`, `deskripsi`, `dibuat_pada`, `diperbarui_pada`, `create_by`, `id_admin`, `foto`) VALUES
(1, 'RSUP Fatmawati', 'Jl. RS Fatmawati No.1, Jakarta', 'Jakarta Selatan', '021-555-0123', 'Rumah Sakit Umum Daerah', '2025-12-03 14:36:32', NULL, NULL, 1, 'rsup_fatmawati.jpg'),
(2, 'RS Cipto Mangunkusumo (RSCM)', 'Jl. Diponegoro, Jakarta Pusat', 'Jakarta Pusat', '021-555-0456', 'Rumah Sakit Rujukan', '2025-12-03 14:36:32', NULL, NULL, 1, 'rs_cipto.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun_admin`
--
ALTER TABLE `akun_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `akun_rumah_sakit`
--
ALTER TABLE `akun_rumah_sakit`
  ADD PRIMARY KEY (`id_rs_akun`),
  ADD KEY `id_rs` (`id_rs`);

--
-- Indexes for table `akun_user`
--
ALTER TABLE `akun_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `data_layanan_rs`
--
ALTER TABLE `data_layanan_rs`
  ADD PRIMARY KEY (`id_layanan`),
  ADD KEY `id_rs` (`id_rs`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_rs_akun` (`id_rs_akun`);

--
-- Indexes for table `data_penjadwalan`
--
ALTER TABLE `data_penjadwalan`
  ADD PRIMARY KEY (`id_penjadwalan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_rs` (`id_rs`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indexes for table `data_rumah_sakit`
--
ALTER TABLE `data_rumah_sakit`
  ADD PRIMARY KEY (`id_rs`),
  ADD KEY `id_admin` (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun_admin`
--
ALTER TABLE `akun_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `akun_rumah_sakit`
--
ALTER TABLE `akun_rumah_sakit`
  MODIFY `id_rs_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `akun_user`
--
ALTER TABLE `akun_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_layanan_rs`
--
ALTER TABLE `data_layanan_rs`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_penjadwalan`
--
ALTER TABLE `data_penjadwalan`
  MODIFY `id_penjadwalan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `data_rumah_sakit`
--
ALTER TABLE `data_rumah_sakit`
  MODIFY `id_rs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun_rumah_sakit`
--
ALTER TABLE `akun_rumah_sakit`
  ADD CONSTRAINT `akun_rumah_sakit_ibfk_1` FOREIGN KEY (`id_rs`) REFERENCES `data_rumah_sakit` (`id_rs`) ON DELETE CASCADE;

--
-- Constraints for table `data_layanan_rs`
--
ALTER TABLE `data_layanan_rs`
  ADD CONSTRAINT `data_layanan_rs_ibfk_1` FOREIGN KEY (`id_rs`) REFERENCES `data_rumah_sakit` (`id_rs`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_layanan_rs_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `akun_admin` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `data_layanan_rs_ibfk_3` FOREIGN KEY (`id_rs_akun`) REFERENCES `akun_rumah_sakit` (`id_rs_akun`) ON DELETE SET NULL;

--
-- Constraints for table `data_penjadwalan`
--
ALTER TABLE `data_penjadwalan`
  ADD CONSTRAINT `data_penjadwalan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `akun_user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_penjadwalan_ibfk_2` FOREIGN KEY (`id_rs`) REFERENCES `data_rumah_sakit` (`id_rs`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_penjadwalan_ibfk_3` FOREIGN KEY (`id_layanan`) REFERENCES `data_layanan_rs` (`id_layanan`) ON DELETE CASCADE;

--
-- Constraints for table `data_rumah_sakit`
--
ALTER TABLE `data_rumah_sakit`
  ADD CONSTRAINT `data_rumah_sakit_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `akun_admin` (`id_admin`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
