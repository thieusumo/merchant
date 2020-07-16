-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2019 at 06:35 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dataeglobal`
--

-- --------------------------------------------------------

--
-- Table structure for table `pos_notification`
--

CREATE TABLE `pos_notification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `notification_place_id` int(8) NOT NULL,
  `notification_type` varchar(500) NOT NULL,
  `notification_message` varchar(500) NOT NULL,
  `notification_link` varchar(500) NOT NULL,
  `notification_readed` int(1) NOT NULL COMMENT '0:unreaded 1:readed',
  `notification_receiver_place_id` varchar(200) NOT NULL COMMENT 'place_id: 1,2,3,...etc all_places:0',
  `notification_user_phone` varchar(16) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos_notification`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_notification`
--
ALTER TABLE `pos_notification`
  ADD PRIMARY KEY (`id`,`notification_place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pos_notification`
--
ALTER TABLE `pos_notification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
