-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2019 at 05:56 AM
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
-- Table structure for table `main_service`
--

CREATE TABLE `main_service` (
  `service_id` int(64) NOT NULL,
  `service_name` varchar(128) NOT NULL,
  `service_index` int(11) DEFAULT NULL,
  `service_image` varchar(255) DEFAULT NULL,
  `service_parent_id` int(32) DEFAULT NULL,
  `service_description` text NOT NULL,
  `service_price` double NOT NULL DEFAULT '0',
  `service_listservicedetail_id` varchar(256) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `service_status` tinyint(1) NOT NULL DEFAULT '1',
  `service_url` text NOT NULL,
  `service_total_sms` int(11) DEFAULT '0',
  `service_bonus_sms` int(11) DEFAULT '0',
  `service_type` tinyint(4) DEFAULT '0' COMMENT '1:sms_package 0:null'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `main_service`
--

INSERT INTO `main_service` (`service_id`, `service_name`, `service_index`, `service_image`, `service_parent_id`, `service_description`, `service_price`, `service_listservicedetail_id`, `created_at`, `updated_at`, `created_by`, `updated_by`, `service_status`, `service_url`, `service_total_sms`, `service_bonus_sms`, `service_type`) VALUES
(1, 'Income Tax', NULL, 'images/service/percentage.png', NULL, 'Income Tax Parent', 1, '45', '2016-09-19 19:18:14', '2016-09-19 19:18:14', NULL, NULL, 0, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(2, 'Pos Nails', NULL, 'images/service/pos-nails.png', NULL, 'Pos Nails Parent', 1, '45', '2016-09-19 19:17:19', '2016-09-19 19:17:19', NULL, NULL, 1, 'https://pos.dataeglobal.com/', NULL, NULL, NULL),
(3, 'Payroll / Bookkeeper', NULL, 'images/service/payroll.png', NULL, 'Payroll Parents', 1, '45', '2016-09-19 19:20:08', '2016-09-21 14:24:57', NULL, NULL, 0, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(4, 'Phone Telecom', NULL, 'images/service/pinless-phone.png', NULL, 'Phone VN Parent', 1, '', '2016-09-19 19:18:38', '2017-01-18 01:35:43', NULL, NULL, 0, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(5, 'Merchant Service', NULL, 'images/service/merchant.png', NULL, 'Terminal = Merchant', 1, '48', '2016-09-19 19:17:45', '2016-09-21 19:12:54', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(6, 'Website Nails Salon', NULL, 'images/service/website-builder.png', NULL, 'Main service', 0, '47', '2016-09-19 19:16:04', '2016-09-21 19:11:38', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(7, 'Shopping online', NULL, 'images/service/bookeeping.png', NULL, 'Form Professional Liability Parent', 1, '38', '2016-09-19 19:19:22', '2016-09-19 19:56:29', NULL, NULL, 0, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(8, 'Insurance', NULL, 'images/service/handshake.jpg', NULL, 'Form Worker Comp Parents', 1, '', '2016-09-19 19:19:40', '2016-09-21 19:19:27', NULL, NULL, 0, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(10, 'Basic Packet tax', NULL, NULL, 1, 'website basic packet', 1, '10;11;12;13', '2016-09-19 19:24:32', '2016-09-21 14:47:55', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(11, 'Full system POS', NULL, NULL, 2, 'Basic Packet', 199.95, '10;11;12;13;20;21;22;23;24;25;26;27;28;29;30', '2016-09-19 19:26:51', '2017-01-19 19:21:37', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(12, 'Basic Packet payroll', NULL, NULL, 3, 'Basic Packet', 1, '14;15;16;17;18;19', '2016-09-19 19:46:51', '2016-09-21 14:48:18', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(13, 'Basic Packet telephone', NULL, NULL, 4, 'Basic Packet', 1, '39', '2016-09-19 20:05:43', '2016-09-21 14:48:28', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(14, 'Basic packet shopping', NULL, NULL, NULL, '', 1, '38', '2016-09-19 20:06:09', '2016-09-21 21:42:30', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(15, 'Basic Packet nail', NULL, NULL, 6, 'Basic Packet', 1, '47', '2016-09-19 20:06:41', '2016-09-21 19:16:21', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(16, 'Basic Packet merchant', NULL, NULL, 5, 'Basic Packet', 1, '48', '2016-09-19 20:07:27', '2016-09-21 19:18:44', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(17, 'Basic Packet insurance', NULL, NULL, 8, 'Basic Packet', 1, '49', '2016-09-19 20:12:10', '2016-09-21 19:19:57', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(18, 'Bacsic Packet', NULL, NULL, 9, 'Payroll', 1, '43;42;41', '2016-09-19 20:12:51', '2016-09-19 20:12:51', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(19, 'Form 1099 & 1096', NULL, NULL, 20, 'Form 1099 & 1096 Parent', 1, '45', '2016-09-19 19:19:03', '2016-09-21 19:05:16', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(20, 'Form 1099 & 1096', NULL, NULL, NULL, 'Form 1099 & form 1096', 1, '', '2016-09-21 14:45:14', '2016-09-21 20:17:31', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(21, 'Merchant Service', NULL, NULL, NULL, 'terminal', 1, '', '2016-09-21 14:47:19', '2016-09-21 19:12:44', NULL, NULL, 0, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(22, 'basic packet termial', NULL, NULL, 21, '', 1, '48', '2016-09-21 14:50:04', '2016-09-21 19:14:38', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(23, 'Form Professional Liability', NULL, NULL, NULL, 'Form Professional Liability', 1, '', '2016-09-21 18:28:27', '2016-09-21 18:28:27', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(24, 'Form Professional Liability', NULL, NULL, 23, 'Form Professional Liability', 1, '38', '2016-09-21 18:30:25', '2016-09-21 20:10:37', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(25, 'Form Worker Comp', NULL, NULL, NULL, '1', 1, '', '2016-09-21 19:27:58', '2016-09-21 19:27:58', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(26, 'Form Worker Comp', NULL, NULL, 25, '1', 1, '50', '2016-09-21 19:29:45', '2016-09-21 19:29:45', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(27, 'Basic packet form 1099', NULL, NULL, 20, '1', 1, '37', '2016-09-21 20:18:00', '2016-09-21 20:18:13', NULL, NULL, 1, 'https://merchant.dataeglobal.com/', NULL, NULL, NULL),
(28, 'Regular system pos', NULL, NULL, 2, '', 119, '10;12;21;22;23;24;27;29;30', '2017-01-18 01:42:22', '2017-01-19 19:22:48', NULL, NULL, 1, '', NULL, NULL, NULL),
(29, 'Basic system Pos', NULL, NULL, 2, '', 69, '12;20;21;24;26', '2017-01-18 01:44:07', '2017-01-19 19:21:56', NULL, NULL, 1, '', NULL, NULL, NULL),
(30, 'sms_pk1', NULL, NULL, NULL, '', 10, '51;52;54;56;58', NULL, NULL, NULL, NULL, 1, '', 4, 2, 1),
(31, 'sms_pk2', NULL, NULL, NULL, '', 20, '51;52;54;56', NULL, NULL, NULL, NULL, 1, '', 5, 4, 1),
(32, 'sms_pk3', NULL, NULL, NULL, '', 30, '51;52;54', NULL, NULL, NULL, NULL, 1, '', 6, 6, 1),
(33, 'sms_pk4', NULL, NULL, NULL, '', 40, '51;52', NULL, NULL, NULL, NULL, 1, '', 7, 8, 1),
(34, 'sms_pk5', NULL, NULL, NULL, '', 50, '51', NULL, NULL, NULL, NULL, 1, '', 8, 10, 1),
(35, 'try', NULL, NULL, NULL, '', 66, '52;54', NULL, NULL, NULL, NULL, 1, '', 1, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `main_service`
--
ALTER TABLE `main_service`
  ADD PRIMARY KEY (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `main_service`
--
ALTER TABLE `main_service`
  MODIFY `service_id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
