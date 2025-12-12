-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 12, 2025 at 11:29 AM
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
(1, 'Budi Santoso', 'budi@example.com', '$2y$10$M1uVJz0bf5EYY0Np0KZ/uOqDzhY1KQMrsfZst3VmosT/CeS1wkjBW', '0812345671212', '2025-12-03 14:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `data_jadwal_layanan`
--

CREATE TABLE `data_jadwal_layanan` (
  `id_jadwal` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_buka_praktek` time NOT NULL DEFAULT '08:00:00',
  `jam_tutup_praktek` time NOT NULL DEFAULT '16:00:00',
  `kuota_per_sesi` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_jadwal_layanan`
--

INSERT INTO `data_jadwal_layanan` (`id_jadwal`, `id_layanan`, `hari`, `jam_buka_praktek`, `jam_tutup_praktek`, `kuota_per_sesi`) VALUES
(1, 1, 'Senin', '00:00:00', '23:59:59', 20),
(2, 1, 'Selasa', '00:00:00', '23:59:59', 20),
(3, 1, 'Rabu', '00:00:00', '23:59:59', 20),
(4, 1, 'Kamis', '00:00:00', '23:59:59', 20),
(5, 1, 'Jumat', '00:00:00', '23:59:59', 20),
(6, 1, 'Sabtu', '00:00:00', '23:59:59', 20),
(7, 1, 'Minggu', '00:00:00', '23:59:59', 20),
(8, 2, 'Senin', '08:00:00', '14:00:00', 20),
(9, 2, 'Selasa', '08:00:00', '14:00:00', 20),
(10, 2, 'Rabu', '08:00:00', '14:00:00', 20),
(11, 2, 'Kamis', '08:00:00', '14:00:00', 20),
(12, 2, 'Jumat', '08:00:00', '11:00:00', 15),
(13, 2, 'Sabtu', '09:00:00', '12:00:00', 10);

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
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `status` enum('Menunggu','Dikonfirmasi','Dibatalkan','Selesai') DEFAULT 'Menunggu',
  `catatan` text DEFAULT NULL,
  `dibuat_pada` datetime DEFAULT current_timestamp(),
  `queue_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_penjadwalan`
--

INSERT INTO `data_penjadwalan` (`id_penjadwalan`, `id_user`, `id_rs`, `id_layanan`, `no_nik`, `nama_pasien`, `tanggal_kunjungan`, `jam_mulai`, `jam_selesai`, `status`, `catatan`, `dibuat_pada`, `queue_number`) VALUES
(1, 1, 1, 1, '1234567890123456', 'Budi Santoso', '2025-12-04', NULL, NULL, 'Dikonfirmasi', 'Catatan contoh', '2025-12-03 14:36:32', 'F-001'),
(4, 1, 1, 2, '3173051234567890', 'Budi Santoso', '2025-12-20', NULL, NULL, 'Menunggu', 'Pemeriksaan lanjutan onkologi', '2025-12-10 11:19:37', 'F-004');

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
-- Indexes for table `data_jadwal_layanan`
--
ALTER TABLE `data_jadwal_layanan`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_layanan` (`id_layanan`);

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
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_jadwal_layanan`
--
ALTER TABLE `data_jadwal_layanan`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `data_layanan_rs`
--
ALTER TABLE `data_layanan_rs`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_penjadwalan`
--
ALTER TABLE `data_penjadwalan`
  MODIFY `id_penjadwalan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `data_jadwal_layanan`
--
ALTER TABLE `data_jadwal_layanan`
  ADD CONSTRAINT `data_jadwal_layanan_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `data_layanan_rs` (`id_layanan`) ON DELETE CASCADE;

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
