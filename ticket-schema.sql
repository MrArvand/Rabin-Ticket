-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 30, 2025 at 12:37 AM
-- Server version: 10.6.24-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `requestr_rahbarian`
--

-- --------------------------------------------------------

--
-- Table structure for table `daste_mohtava`
--

CREATE TABLE `daste_mohtava` (
  `name_daste` varchar(200) NOT NULL,
  `id_daste` varchar(100) NOT NULL,
  `name_f_daste` varchar(200) NOT NULL,
  `id_f_daste` varchar(100) NOT NULL,
  `fader` varchar(1) NOT NULL,
  `i_daste` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departman`
--

CREATE TABLE `departman` (
  `name` varchar(255) NOT NULL,
  `id` varchar(255) NOT NULL,
  `modir` varchar(100) NOT NULL,
  `vaziat` varchar(1) NOT NULL,
  `i_dep` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_pasokh`
--

CREATE TABLE `file_pasokh` (
  `code_ticket` varchar(100) NOT NULL,
  `code_pasokh` varchar(100) NOT NULL,
  `code_file` varchar(100) NOT NULL,
  `titr` varchar(255) NOT NULL,
  `kind` varchar(10) NOT NULL,
  `hajm` varchar(10) NOT NULL,
  `vaziat` varchar(1) NOT NULL,
  `i_file` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karbar`
--

CREATE TABLE `karbar` (
  `name` varchar(250) NOT NULL,
  `code_p` varchar(100) NOT NULL,
  `kind` varchar(100) NOT NULL,
  `code_karbar` varchar(100) NOT NULL,
  `semat` varchar(200) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `vaziat` varchar(1) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `name_sherkat` varchar(200) NOT NULL,
  `code_sherkat` varchar(100) NOT NULL,
  `kind_daste` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `i_karbar` int(10) NOT NULL,
  `let` varchar(255) NOT NULL,
  `gozaresh` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karkerd`
--

CREATE TABLE `karkerd` (
  `code_p` varchar(50) NOT NULL,
  `name_karbar` varchar(250) NOT NULL,
  `tarikh_s` varchar(20) NOT NULL,
  `saat_s` varchar(6) NOT NULL,
  `tarikh_e` varchar(20) NOT NULL,
  `saat_e` varchar(6) NOT NULL,
  `daste` varchar(200) NOT NULL,
  `matn` mediumtext NOT NULL,
  `zaman` varchar(20) NOT NULL,
  `faal` varchar(1) NOT NULL,
  `mortabet` varchar(200) NOT NULL,
  `vaziat` varchar(1) NOT NULL,
  `code` varchar(50) NOT NULL,
  `i_karkerd` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mohtava`
--

CREATE TABLE `mohtava` (
  `titr` varchar(255) NOT NULL,
  `kind` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `name_file` varchar(255) NOT NULL,
  `kind_file` varchar(6) NOT NULL,
  `sherkat` varchar(100) NOT NULL,
  `daste` varchar(100) NOT NULL,
  `name_daste` varchar(255) NOT NULL,
  `cat1` varchar(255) NOT NULL,
  `name_cat1` varchar(255) NOT NULL,
  `cat2` varchar(255) NOT NULL,
  `name_cat2` varchar(255) NOT NULL,
  `vaziat` varchar(1) NOT NULL,
  `code` varchar(100) NOT NULL,
  `matn` mediumtext NOT NULL,
  `tarikh_sabt` varchar(15) NOT NULL,
  `nevisande` varchar(255) NOT NULL,
  `poster` varchar(255) NOT NULL,
  `i_mohtava` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pasokh`
--

CREATE TABLE `pasokh` (
  `code` varchar(100) NOT NULL,
  `code_ticket` varchar(100) NOT NULL,
  `code_karbar_sabt` varchar(50) NOT NULL,
  `name_karbar_sabt` varchar(200) NOT NULL,
  `code_karbar2` varchar(50) NOT NULL,
  `name_karbar2` varchar(200) NOT NULL,
  `matn` mediumtext NOT NULL,
  `tarikh_sabt` varchar(20) NOT NULL,
  `saat_sabt` varchar(9) NOT NULL,
  `vaziat` varchar(1) NOT NULL,
  `kind` varchar(10) NOT NULL DEFAULT '',
  `oksee` varchar(1) NOT NULL,
  `tarikh_see` varchar(20) NOT NULL,
  `saat_see` varchar(6) NOT NULL,
  `i_pasokh` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sherkatha`
--

CREATE TABLE `sherkatha` (
  `name` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `logo_sherkat` varchar(255) NOT NULL,
  `i_sherkat` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `titr` varchar(255) NOT NULL,
  `olaviat` varchar(1) NOT NULL,
  `matn` mediumtext NOT NULL,
  `code` varchar(20) NOT NULL,
  `code_p_karbar` varchar(20) NOT NULL,
  `name_karbar` varchar(255) NOT NULL,
  `tel_karbar` varchar(30) NOT NULL,
  `tarikh_sabt` varchar(15) NOT NULL,
  `saat_sabt` varchar(6) NOT NULL,
  `vaziat` varchar(1) NOT NULL,
  `daste` varchar(100) NOT NULL,
  `name_daste` varchar(200) NOT NULL,
  `name_sherkat` varchar(255) NOT NULL,
  `code_sherkat` varchar(100) NOT NULL,
  `code_p_karbar_anjam` varchar(50) NOT NULL,
  `name_karbar_anjam` varchar(200) NOT NULL,
  `tarikh_anjam` varchar(20) NOT NULL,
  `saat_anjam` varchar(6) NOT NULL,
  `log_txt` mediumtext NOT NULL,
  `priority_status` enum('n','y') NOT NULL DEFAULT 'n',
  `priority_order` int(11) DEFAULT NULL,
  `i_ticket` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daste_mohtava`
--
ALTER TABLE `daste_mohtava`
  ADD PRIMARY KEY (`i_daste`);

--
-- Indexes for table `departman`
--
ALTER TABLE `departman`
  ADD PRIMARY KEY (`i_dep`),
  ADD KEY `idx_departman_vaziat` (`vaziat`);

--
-- Indexes for table `file_pasokh`
--
ALTER TABLE `file_pasokh`
  ADD PRIMARY KEY (`i_file`),
  ADD KEY `code_file` (`code_file`),
  ADD KEY `idx_file_pasokh_code_ticket` (`code_ticket`),
  ADD KEY `idx_file_pasokh_code_pasokh` (`code_pasokh`),
  ADD KEY `idx_file_pasokh_ticket_pasokh` (`code_ticket`,`code_pasokh`);

--
-- Indexes for table `karbar`
--
ALTER TABLE `karbar`
  ADD PRIMARY KEY (`i_karbar`),
  ADD KEY `code_p` (`code_p`),
  ADD KEY `idx_karbar_code_p` (`code_p`),
  ADD KEY `idx_karbar_vaziat` (`vaziat`);

--
-- Indexes for table `karkerd`
--
ALTER TABLE `karkerd`
  ADD PRIMARY KEY (`i_karkerd`),
  ADD KEY `idx_karkerd_code_p` (`code_p`),
  ADD KEY `idx_karkerd_tarikh` (`tarikh_s`);

--
-- Indexes for table `mohtava`
--
ALTER TABLE `mohtava`
  ADD PRIMARY KEY (`i_mohtava`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `pasokh`
--
ALTER TABLE `pasokh`
  ADD PRIMARY KEY (`i_pasokh`),
  ADD KEY `code` (`code`),
  ADD KEY `idx_pasokh_code_ticket` (`code_ticket`),
  ADD KEY `idx_pasokh_oksee` (`oksee`),
  ADD KEY `idx_pasokh_ticket_unread` (`code_ticket`,`oksee`),
  ADD KEY `idx_pasokh_code_karbar2` (`code_karbar2`),
  ADD KEY `idx_pasokh_notification` (`oksee`,`code_karbar2`,`code_ticket`),
  ADD KEY `idx_pasokh_code_karbar_sabt` (`code_karbar_sabt`),
  ADD KEY `idx_pasokh_i_pasokh` (`i_pasokh`),
  ADD KEY `idx_pasokh_ticket_date` (`code_ticket`,`tarikh_sabt`,`saat_sabt`);

--
-- Indexes for table `sherkatha`
--
ALTER TABLE `sherkatha`
  ADD PRIMARY KEY (`i_sherkat`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`i_ticket`),
  ADD UNIQUE KEY `priority_status` (`priority_status`,`priority_order`),
  ADD KEY `code` (`code`),
  ADD KEY `idx_ticket_vaziat` (`vaziat`),
  ADD KEY `idx_ticket_daste` (`daste`),
  ADD KEY `idx_ticket_code_sherkat` (`code_sherkat`),
  ADD KEY `idx_ticket_code_p_karbar_anjam` (`code_p_karbar_anjam`),
  ADD KEY `idx_ticket_code_p_karbar` (`code_p_karbar`),
  ADD KEY `idx_ticket_vaziat_anjam` (`vaziat`,`code_p_karbar_anjam`),
  ADD KEY `idx_ticket_tarikh_sabt` (`tarikh_sabt`),
  ADD KEY `idx_ticket_code` (`code`),
  ADD KEY `idx_ticket_priority` (`priority_status`,`priority_order`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daste_mohtava`
--
ALTER TABLE `daste_mohtava`
  MODIFY `i_daste` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departman`
--
ALTER TABLE `departman`
  MODIFY `i_dep` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_pasokh`
--
ALTER TABLE `file_pasokh`
  MODIFY `i_file` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `karbar`
--
ALTER TABLE `karbar`
  MODIFY `i_karbar` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `karkerd`
--
ALTER TABLE `karkerd`
  MODIFY `i_karkerd` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mohtava`
--
ALTER TABLE `mohtava`
  MODIFY `i_mohtava` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pasokh`
--
ALTER TABLE `pasokh`
  MODIFY `i_pasokh` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sherkatha`
--
ALTER TABLE `sherkatha`
  MODIFY `i_sherkat` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `i_ticket` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
