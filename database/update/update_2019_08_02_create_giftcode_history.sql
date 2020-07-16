-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2019 at 10:32 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datae`
--

-- --------------------------------------------------------

--
-- Table structure for table `pos_giftcode_history`
--

CREATE TABLE `pos_giftcode_history` (
  `id` int(11) NOT NULL,
  `place_id` int(15) NOT NULL,
  `giftcode_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `giftcode_balance` int(11) NOT NULL,
  `giftcode_use` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `giftcode_type` int(1) NOT NULL COMMENT '1 is buy gictcard,2 is referral giftcard, 3 is payment giftcard',
  `giftcode_bonus_point` int(11) DEFAULT NULL,
  `gitcode_redemption` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pos_giftcode_history`
--

INSERT INTO `pos_giftcode_history` (`id`, `place_id`, `giftcode_code`, `giftcode_balance`, `giftcode_use`, `created_by`, `updated_by`, `created_at`, `updated_at`, `giftcode_type`, `giftcode_bonus_point`, `gitcode_redemption`) VALUES
(1, 15, 'retrwer', 0, 80, 10, 10, '2019-08-02 01:18:28', '2019-08-02 01:18:28', 1, NULL, NULL),
(2, 15, 'dgyi48aq5w46sj2vo', 0, 80, 10, 10, '2019-08-02 01:18:28', '2019-08-02 01:18:28', 1, NULL, NULL),
(3, 15, 'dgyi48aq5w46sj2vo', 0, 90, 10, 10, '2019-08-02 01:24:10', '2019-08-02 01:24:10', 3, NULL, NULL),
(4, 15, 'dgyi48aq5w46sj2vo', 0, 10, 10, 10, '2019-08-02 01:26:46', '2019-08-02 01:26:46', 3, NULL, NULL),
(5, 15, 'dgyi48aq5w46sj2vo', 0, 90, 10, 10, '2019-08-02 01:31:09', '2019-08-02 01:31:09', 1, NULL, NULL),
(6, 15, 'wewewewewe', 0, 9, 10, 10, '2019-08-02 01:31:39', '2019-08-02 01:31:39', 2, 0, NULL),
(7, 15, 'wewewewe', 0, 9, 10, 10, '2019-08-02 01:31:39', '2019-08-02 01:31:39', 2, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_giftcode_history`
--
ALTER TABLE `pos_giftcode_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pos_giftcode_history`
--
ALTER TABLE `pos_giftcode_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
