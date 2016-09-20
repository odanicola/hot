-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 20 Sep 2016 pada 03.17
-- Versi Server: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epus_hot`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `app_users_profile`
--

CREATE TABLE IF NOT EXISTS `app_users_profile` (
  `username` varchar(30) NOT NULL,
  `code` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `id_grup` int(11) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `bpjs` varchar(20) DEFAULT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat` text,
  `tb` double(10,2) DEFAULT NULL,
  `bb` double(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `app_users_profile`
--

INSERT INTO `app_users_profile` (`username`, `code`, `nama`, `phone_number`, `id_grup`, `email`, `status`, `bpjs`, `jk`, `tgl_lahir`, `alamat`, `tb`, `bb`) VALUES
('admin', '3172040201', 'administrator', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('kecamatan', '3172040201', 'KEC. MAKASAR', NULL, 2, NULL, 1, NULL, NULL, '2016-08-30', NULL, 170.00, 66.00),
('cipinangmelayu', '3172040202', 'KEL. CIPINANG MELAYU', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('halim1', '3172040203', 'KEL. HALIM P.KUSUMA I', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('halim2', '3172040204', 'KEL. HALIM P.KUSUMA II', '081291427592', 2, 'Kartikaistanidewi92@gmail.com', 1, NULL, NULL, NULL, NULL, NULL, NULL),
('kebonpala', '3172040205', 'KEL. KEBON PALA', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('pinangranti', '3172040206', 'KEL. PINANG RANTI', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('makasar', '3172040207', 'KEL. MAKASAR', NULL, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL),
('3273013011840002', '3172040201', 'VIKTOR TUNGGUL', '081220922911', 2, '-', 1, '0001523636559', 'L', '1984-01-30', '-', 170.00, 60.00),
('1231231231231231', '3172040201', 'MUHAMMAD RIZKI SETIAWAN', '', 1, '', 1, '0001256033755', 'L', '1998-01-13', '', NULL, NULL),
('1231231231231232', '3172040201', 'MUHAMMAD RIZKI SETIAWAN', '08999319661', 1, '1', 1, '0001256033755', 'L', '1998-01-13', '1', 0.00, 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_users_profile`
--
ALTER TABLE `app_users_profile`
  ADD PRIMARY KEY (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
