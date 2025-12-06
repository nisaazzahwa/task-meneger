-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 07:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `habit`
--

-- --------------------------------------------------------

--
-- Table structure for table `kebiasaan`
--

CREATE TABLE `kebiasaan` (
  `id_kebiasaan` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `nama_kebiasaan` varchar(50) NOT NULL,
  `deskripsi_kebiasaan` varchar(200) NOT NULL,
  `frequensi` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu','setiap hari') NOT NULL,
  `waktu` time NOT NULL,
  `status` enum('belum','selesai') NOT NULL DEFAULT 'belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kebiasaan`
--

INSERT INTO `kebiasaan` (`id_kebiasaan`, `user_id`, `nama_kebiasaan`, `deskripsi_kebiasaan`, `frequensi`, `waktu`, `status`) VALUES
(1, 1, 'water', 'drink', 'sabtu', '12:27:00', 'selesai'),
(2, 1, 'read', 'read', 'setiap hari', '12:42:00', 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_kebiasaan`
--

CREATE TABLE `riwayat_kebiasaan` (
  `id` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `id_kebiasaan` int(12) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_kebiasaan`
--

INSERT INTO `riwayat_kebiasaan` (`id`, `user_id`, `id_kebiasaan`, `tanggal`) VALUES
(1, 1, 2, '2025-12-06'),
(2, 1, 1, '2025-12-06'),
(3, 1, 1, '2025-12-06'),
(4, 1, 2, '2025-12-06'),
(5, 1, 1, '2025-12-05'),
(6, 1, 1, '2025-12-04'),
(7, 1, 2, '2025-12-04'),
(8, 1, 1, '2025-12-03'),
(9, 1, 1, '2025-12-02'),
(10, 1, 1, '2025-12-01'),
(11, 1, 1, '2025-12-06'),
(12, 1, 2, '2025-12-06'),
(13, 1, 1, '2025-12-05'),
(14, 1, 1, '2025-12-04'),
(15, 1, 2, '2025-12-04'),
(16, 1, 1, '2025-12-03'),
(17, 1, 1, '2025-12-02'),
(18, 1, 1, '2025-12-01'),
(19, 1, 1, '2025-11-06'),
(20, 1, 1, '2025-11-05'),
(21, 1, 1, '2025-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `nama_tugas` varchar(50) NOT NULL,
  `deadline` datetime NOT NULL,
  `prioritas` enum('rendah','sedang','tinggi') NOT NULL,
  `status` enum('belum','selesai') NOT NULL,
  `ekstimasi_waktu` time NOT NULL,
  `deskripsi` varchar(200) NOT NULL,
  `tanggal_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `user_id`, `nama_tugas`, `deadline`, `prioritas`, `status`, `ekstimasi_waktu`, `deskripsi`, `tanggal_selesai`) VALUES
(2, 1, 'web', '2025-12-06 23:11:00', 'sedang', 'selesai', '14:22:00', 'build a web', '2025-12-06'),
(3, 1, 'reading', '2025-12-06 13:48:00', 'rendah', 'selesai', '14:50:00', 'w', '2025-12-06'),
(4, 1, 'web', '2025-12-05 12:50:00', 'tinggi', 'selesai', '12:50:00', 'w', '2025-12-06'),
(5, 1, 'web', '2025-12-07 12:51:00', 'tinggi', 'selesai', '12:51:00', 'ww', '2025-12-06'),
(6, 1, 'Study PHP', '2025-12-06 13:50:59', 'tinggi', 'selesai', '02:00:00', 'Learning charts', '2025-12-06'),
(7, 1, 'Fix CSS', '2025-12-06 13:50:59', 'sedang', 'selesai', '01:30:00', 'Styling dashboard', '2025-12-05'),
(8, 1, 'Database Design', '2025-12-06 13:50:59', 'tinggi', 'selesai', '04:00:00', 'ERD Diagram', '2025-12-04'),
(9, 1, 'Meeting', '2025-12-06 13:50:59', 'rendah', 'selesai', '00:45:00', 'Client meeting', '2025-12-04'),
(10, 1, 'Write Report', '2025-12-06 13:50:59', 'sedang', 'selesai', '03:00:00', 'Weekly report', '2025-12-02'),
(11, 1, 'Study PHP', '2025-12-06 13:51:34', 'tinggi', 'selesai', '02:00:00', 'Learning charts', '2025-12-06'),
(12, 1, 'Fix CSS', '2025-12-06 13:51:34', 'sedang', 'selesai', '01:30:00', 'Styling dashboard', '2025-12-05'),
(13, 1, 'Database Design', '2025-12-06 13:51:34', 'tinggi', 'selesai', '04:00:00', 'ERD Diagram', '2025-12-04'),
(14, 1, 'Meeting', '2025-12-06 13:51:34', 'rendah', 'selesai', '00:45:00', 'Client meeting', '2025-12-04'),
(15, 1, 'Write Report', '2025-12-06 13:51:34', 'sedang', 'selesai', '03:00:00', 'Weekly report', '2025-12-02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(12) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@example.com', '21232f297a57a5a743894a0e4a801fc3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kebiasaan`
--
ALTER TABLE `kebiasaan`
  ADD PRIMARY KEY (`id_kebiasaan`);

--
-- Indexes for table `riwayat_kebiasaan`
--
ALTER TABLE `riwayat_kebiasaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kebiasaan`
--
ALTER TABLE `kebiasaan`
  MODIFY `id_kebiasaan` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `riwayat_kebiasaan`
--
ALTER TABLE `riwayat_kebiasaan`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `riwayat_kebiasaan`
--
ALTER TABLE `riwayat_kebiasaan`
  ADD CONSTRAINT `riwayat_kebiasaan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;
