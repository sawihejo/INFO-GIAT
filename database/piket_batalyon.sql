-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 04:36 AM
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
-- Database: `piket_batalyon`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apel`
--

CREATE TABLE `tbl_apel` (
  `id` int(11) NOT NULL,
  `kadet_id` int(11) NOT NULL,
  `batalyon` enum('I','II','III','IV') NOT NULL,
  `jenis_apel` varchar(50) NOT NULL,
  `tanggal_apel` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `nama_nama` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_apel`
--

INSERT INTO `tbl_apel` (`id`, `kadet_id`, `batalyon`, `jenis_apel`, `tanggal_apel`, `keterangan`, `jumlah`, `nama_nama`, `created_at`) VALUES
(1, 3, 'III', 'Malam', '2025-10-19', 'Ibadah', 45, 'Terlampir', '2025-10-19 12:07:29'),
(2, 6, 'II', 'Pagi', '2025-10-20', 'Tanpa Keterangan', 1, 'Damas', '2025-10-20 01:04:05'),
(3, 6, 'II', 'Pagi', '2025-10-20', 'Dinas Luar', 25, 'Terlampir', '2025-10-20 01:04:24'),
(4, 3, 'III', 'Malam', '2025-10-20', 'Orkestra', 14, 'Terlampir', '2025-10-20 15:28:36'),
(5, 3, 'III', 'Pagi', '2025-12-04', 'Sakit', 1, 'Ruvian', '2025-12-04 08:25:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_batalyon`
--

CREATE TABLE `tbl_batalyon` (
  `id` int(11) NOT NULL,
  `nama_batalyon` enum('I','II','III','IV') NOT NULL,
  `jumlah_total_personil` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_batalyon`
--

INSERT INTO `tbl_batalyon` (`id`, `nama_batalyon`, `jumlah_total_personil`) VALUES
(1, 'I', 300),
(2, 'II', 300),
(3, 'III', 300),
(4, 'IV', 652);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dokumentasi`
--

CREATE TABLE `tbl_dokumentasi` (
  `id` int(11) NOT NULL,
  `kadet_id` int(11) NOT NULL,
  `batalyon` enum('I','II','III','IV') NOT NULL,
  `kategori` enum('makan_pagi','makan_siang','makan_malam','apel_pagi','apel_malam','lainnya') NOT NULL,
  `keterangan_lainnya` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_status` enum('available','archived') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dokumentasi`
--

INSERT INTO `tbl_dokumentasi` (`id`, `kadet_id`, `batalyon`, `kategori`, `keterangan_lainnya`, `file_path`, `file_status`, `created_at`) VALUES
(1, 3, 'III', 'apel_malam', NULL, 'uploads/2025-10-19_20-45-07_Batalyon-III_36ab60.jpg', 'available', '2025-10-19 13:45:07'),
(2, 6, 'II', 'apel_pagi', NULL, 'uploads/2025-10-20_08-05-54_Batalyon-II_b16e6f.jpg', 'available', '2025-10-20 01:05:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_piket_jaga`
--

CREATE TABLE `tbl_piket_jaga` (
  `id` int(11) NOT NULL,
  `tanggal_piket` date NOT NULL,
  `batalyon` enum('I','II','III','IV') NOT NULL,
  `nama_piket` text DEFAULT NULL,
  `kontak_piket` text DEFAULT NULL,
  `user_input_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_piket_jaga`
--

INSERT INTO `tbl_piket_jaga` (`id`, `tanggal_piket`, `batalyon`, `nama_piket`, `kontak_piket`, `user_input_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2025-10-19', 'III', 'Satrio Wibowo', '081220800303', 1, NULL, '2025-10-19 12:06:42', NULL),
(2, '2025-10-19', 'I', 'Nurdin', '', 1, NULL, '2025-10-19 13:52:47', NULL),
(3, '2025-10-20', 'I', 'Nurdin', '', 1, NULL, '2025-10-20 01:01:09', NULL),
(4, '2025-10-20', 'II', 'Alya', '', 1, NULL, '2025-10-20 01:03:21', NULL),
(5, '2025-10-20', 'III', 'Satrio Wibowo', '081220800303', 1, NULL, '2025-10-20 15:25:53', NULL),
(6, '2025-11-21', 'III', 'Satrio Wibowo', '081220800303', 1, NULL, '2025-11-21 02:32:05', NULL),
(7, '2025-11-28', 'III', 'Satrio Wibowo', '0811203535', 1, NULL, '2025-11-28 02:24:00', NULL),
(8, '2025-12-04', 'III', 'Satrio Wibowo', '081220800303', 1, NULL, '2025-12-04 06:57:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rencana`
--

CREATE TABLE `tbl_rencana` (
  `id` int(11) NOT NULL,
  `kadet_id` int(11) NOT NULL,
  `batalyon` enum('I','II','III','IV') NOT NULL,
  `tanggal_kegiatan` date NOT NULL,
  `isi_rencana` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_rencana`
--

INSERT INTO `tbl_rencana` (`id`, `kadet_id`, `batalyon`, `tanggal_kegiatan`, `isi_rencana`, `created_at`) VALUES
(1, 3, 'III', '2025-10-19', 'RENCANA KEGIATAN\r\nBATALYON MADYA TK III/ NARA ARKASENA\r\nSenin, 20 Oktober 2025\r\n\r\n04.00 - 05.00    Bangun Pagi Ibadah\r\n06.00 - 07.00    Makan Pagi Terpimpin\r\n07.00 - 07.30    Apel Menkad\r\n07.30 - 12.00    Perkuliahan\r\n12.00 - 13.00    ISHOMA\r\n13.00 - 15.30    Perkuliahan \r\n15.30 - 18.00    Pembersihan \r\n18.00 - 19.00    ISHOMA\r\n20.00 - 20.30    Apel Malam\r\n21.30 - 04.00    Istirahat malam\r\n\r\nNote :\r\n1. Dc PDL linting baret\r\n2. Maksimalkan Belajar \r\n3. Maksimalkan PUDD\r\n4. Kendali waktu\r\n5. Serpas terpimpin & bernyanyi dimonitor piket yon \r\n6. Melaksanakan Ibadah Salat Berjamaah ', '2025-10-19 13:55:36'),
(2, 6, 'II', '2025-10-20', 'RENCANA KEGIATAN\r\nBATALYON MADYA TK III/ NARA ARKASENA\r\nSenin, 20 Oktober 2025\r\n\r\n04.00 - 05.00    Bangun Pagi, ibadah dan olahraga pagi\r\n06.00 - 07.00    Makan Pagi Terpimpin\r\n07.00 - 07.30    Apel Menkad\r\n07.30 - 12.00    Perkuliahan\r\n12.00 - 13.00    ISHOMA\r\n13.00 - 15.30    Perkuliahan \r\n15.30 - 18.00    Pembersihan \r\n18.00 - 19.00    ISHOMA\r\n20.00 - 20.30    Apel Malam\r\n21.30 - 04.00    Istirahat malam\r\n\r\nNote :\r\n1. Dc PDL linting baret\r\n2. Maksimalkan Belajar \r\n3. Maksimalkan PUDD\r\n4. Kendali waktu\r\n5. Serpas terpimpin & bernyanyi dimonitor piket yon \r\n6. Melaksanakan Ibadah Salat Berjamaah \r\n', '2025-10-20 01:06:07'),
(3, 3, 'III', '2025-12-04', 'RENGIAT RESIMEN KORPS KADET MAHASISWA\r\nBATALYON I, II, III, IV\r\nKamis, 04 Desember 2025 \r\n\r\n04.00 - 04.20 Bangun Pagi dan Ibadah\r\n04.20 - 05.00 Senam + Penguatan\r\n05.00 - 05.30 Pembersihan\r\n05.30 - 06.00 Persiapan Makan Pagi\r\n06.00 - 07.00 Makan Pagi di Rukan\r\n07.00 - 08.00 Apel Menkor\r\n08.00 - 11.40 Perkuliahan\r\n11.40 - 12.00 Ibadah\r\n12.00 - 12.30 Persiapan Makan Siang\r\n12.30 - 13.00 Makan Siang di Rukan \r\n13.00 - 16.00 Perkuliahan\r\n16.00 - 17.30 UKM (Orkestra,Tari,Dansa,Oraum)\r\n17.30 - 18.00 Pembersihan\r\n18.00 - 18.30 Persiapan Makan Malam\r\n18.30 - 19.00 Makan Malam di Rukan \r\n19.00 - 19.30 Kajian dan Ibadah Malam \r\n20.00 - 20.30 Apel Malam\r\n20.30 - 22.00 Pembersihan, Belajar Mandiri\r\n22.00 - 04.00 Istirahat Malam \r\n\r\nDC:PDH + Baret       \r\n\r\n*Catatan Wajib :\r\n1. Tidak ada keterlambatan\r\n2. Pakaian sesuai ketentuan (*Wajib Still)\r\n3. Masing-masing personel dinas luar tidak ada pelanggaran dan teguran\r\n4. Pergerakan berkelompok, berlari, dan bernyanyi\r\n5  Ikut kajian bagi kadet muslim\r\n7. English Day\r\n8. Piket divisi jaga harian, polkad, dan pokdo bertanggungjawab              ', '2025-12-04 08:27:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('kadet','organik','admin') NOT NULL,
  `batalyon` enum('I','II','III','IV') DEFAULT NULL,
  `telegram_id` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `token` varchar(10) DEFAULT NULL,
  `token_expire` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `password`, `role`, `batalyon`, `telegram_id`, `phone`, `deleted_at`, `token`, `token_expire`, `created_at`, `updated_at`) VALUES
(1, 'admin_utama', '$2y$10$aGgCzRZbWwGzvLS5coKIJOuHWwulRXNAi6bBOg273BZjLwIBwrCp6', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-19 08:19:10', NULL),
(3, 'Satrio Wibowo', '$2y$10$inHUtb2Vtn2/qS2OeH6im.yqPlY7Hko.p8RkG2YGvponOVyS9xWKa', 'kadet', 'III', '8251298191', '081220800303', NULL, NULL, NULL, '2025-10-19 09:03:10', NULL),
(4, 'Agung', '$2y$10$rVfLpAGtMF3rA487lGpeTuESILQWl8R3gGgXiMruglfB3XwjSC91.', 'organik', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-19 09:30:18', NULL),
(5, 'Nurdin', '$2y$10$EfKw9fqvYFzOk7aYRD.6T.tKOOHZ1TdzBC5iKL3pE3KxhZ..292SO', 'kadet', 'I', '6118599017', NULL, NULL, '744262', '2025-10-20 08:06:22', '2025-10-19 09:32:02', NULL),
(6, 'Alya', '$2y$10$pLvF891ezd/oNZsVoWf42uUVxpQ0WNj1dya2cdnFgXu7nUl4Wrxpe', 'kadet', 'II', '1905739865', NULL, NULL, NULL, NULL, '2025-10-20 01:03:14', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_apel`
--
ALTER TABLE `tbl_apel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kadet_id` (`kadet_id`),
  ADD KEY `idx_tanggal` (`tanggal_apel`),
  ADD KEY `idx_batalyon` (`batalyon`),
  ADD KEY `idx_jenis` (`jenis_apel`);

--
-- Indexes for table `tbl_batalyon`
--
ALTER TABLE `tbl_batalyon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_batalyon_unique` (`nama_batalyon`);

--
-- Indexes for table `tbl_dokumentasi`
--
ALTER TABLE `tbl_dokumentasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kadet_id` (`kadet_id`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `tbl_piket_jaga`
--
ALTER TABLE `tbl_piket_jaga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tanggal_batalyon_unik` (`tanggal_piket`,`batalyon`),
  ADD KEY `user_input_id` (`user_input_id`),
  ADD KEY `idx_tanggal_jaga` (`tanggal_piket`),
  ADD KEY `idx_batalyon_jaga` (`batalyon`);

--
-- Indexes for table `tbl_rencana`
--
ALTER TABLE `tbl_rencana`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kadet_id` (`kadet_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_id_unique` (`telegram_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_apel`
--
ALTER TABLE `tbl_apel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_batalyon`
--
ALTER TABLE `tbl_batalyon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_dokumentasi`
--
ALTER TABLE `tbl_dokumentasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_piket_jaga`
--
ALTER TABLE `tbl_piket_jaga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_rencana`
--
ALTER TABLE `tbl_rencana`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_apel`
--
ALTER TABLE `tbl_apel`
  ADD CONSTRAINT `tbl_apel_ibfk_1` FOREIGN KEY (`kadet_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_dokumentasi`
--
ALTER TABLE `tbl_dokumentasi`
  ADD CONSTRAINT `tbl_dokumentasi_ibfk_1` FOREIGN KEY (`kadet_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_piket_jaga`
--
ALTER TABLE `tbl_piket_jaga`
  ADD CONSTRAINT `tbl_piket_jaga_ibfk_1` FOREIGN KEY (`user_input_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_rencana`
--
ALTER TABLE `tbl_rencana`
  ADD CONSTRAINT `tbl_rencana_ibfk_1` FOREIGN KEY (`kadet_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
