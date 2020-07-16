-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2019 at 08:42 AM
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
-- Table structure for table `main_sms_package`
--

CREATE TABLE `main_sms_package` (
  `id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `package_price` float NOT NULL,
  `package_total_sms` double NOT NULL,
  `package_bonus_sms` varchar(255) DEFAULT NULL,
  `package_permission` varchar(255) NOT NULL COMMENT '1;2;3;4;5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `main_sms_package`
--

INSERT INTO `main_sms_package` (`id`, `package_name`, `package_price`, `package_total_sms`, `package_bonus_sms`, `package_permission`) VALUES
(1, 'abc', 123456, 12.4332, 'sdfg werg werwr werw wer ', '1'),
(2, 'abcd', 1111, 12.4332, 'sdfg werg werwr werw wer ', '1;2;3;4;5'),
(3, 'abcde', 2222, 12.4332, 'sdfg werg werwr werw wer ', '2;3;4'),
(4, 'abcdef', 1112, 12.4332, 'sdfg werg werwr werw wer ', '1;2;3;4;5'),
(5, 'tri', 123, 123, '123123123123', '1;2;3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `main_sms_package`
--
ALTER TABLE `main_sms_package`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `main_sms_package`
--
ALTER TABLE `main_sms_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
