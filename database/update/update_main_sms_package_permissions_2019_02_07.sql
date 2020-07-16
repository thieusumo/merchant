-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2019 at 08:40 AM
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
-- Table structure for table `main_sms_package_permissions`
--

CREATE TABLE `main_sms_package_permissions` (
  `sms_package_permission_id` int(11) NOT NULL,
  `sms_package_permission_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `main_sms_package_permissions`
--

INSERT INTO `main_sms_package_permissions` (`sms_package_permission_id`, `sms_package_permission_name`) VALUES
(1, 'SMS mời khách viết review'),
(2, 'SMS thông báo booking thành công'),
(3, 'SMS thông báo về Giftcard khi khách hàng mua Giftcard'),
(4, 'SMS Coupon Happy Birthday, ngày lễ, sự kiện'),
(5, 'SMS nhắc nhở khách hàng đến tiệm làm dịch vụ sau 1 thời gian tùy theo chủ tiệm đặt hàng');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `main_sms_package_permissions`
--
ALTER TABLE `main_sms_package_permissions`
  ADD PRIMARY KEY (`sms_package_permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `main_sms_package_permissions`
--
ALTER TABLE `main_sms_package_permissions`
  MODIFY `sms_package_permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
