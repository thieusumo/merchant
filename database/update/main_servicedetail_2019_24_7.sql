-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2019 at 06:00 AM
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
-- Table structure for table `main_servicedetail`
--

CREATE TABLE `main_servicedetail` (
  `servicedetail_id` int(32) NOT NULL,
  `servicedetail_name` varchar(128) NOT NULL,
  `servicedetail_price` double NOT NULL DEFAULT '0',
  `servicedetail_description` text NOT NULL,
  `servicedetail_slogan` varchar(128) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `servicedetail_status` tinyint(1) NOT NULL DEFAULT '1',
  `servicedetail_type` int(11) DEFAULT '0' COMMENT '1:sms_package_detail 0:null'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `main_servicedetail`
--

INSERT INTO `main_servicedetail` (`servicedetail_id`, `servicedetail_name`, `servicedetail_price`, `servicedetail_description`, `servicedetail_slogan`, `created_at`, `updated_at`, `created_by`, `updated_by`, `servicedetail_status`, `servicedetail_type`) VALUES
(1, 'Technical Support', 20, '', '', '2016-07-19 06:36:14', '2016-07-19 06:36:14', NULL, NULL, 1, NULL),
(2, 'Prior Year Comparison', 5, '', '', '2016-07-18 22:00:00', '2016-07-18 22:00:00', NULL, NULL, 1, NULL),
(3, 'Store & access your tax return for 3 years', 10, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(4, 'Income statement (W-2) -1040 Ez student', 10, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(5, 'ACA related Tax form (1095A)', 5, '', '', NULL, NULL, NULL, NULL, 0, NULL),
(6, 'Miscellaneous income & some related Expenses (1099MISC)', 2, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(7, 'ACA related Tax form (1095A)', 6, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(8, 'Iremized dedutions', 0, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(9, 'Mortgage/property tax deductions (schedule A)	', 9, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(10, 'Interest & ordinary income', 0, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(11, 'Sale of stocks and bonds (schedule D)', 0, '', '', NULL, NULL, NULL, NULL, 1, NULL),
(12, 'Sale Gift Card', 1, 'Sale Gift Card', 'Sale Gift Card', '2016-09-19 19:04:12', '2016-09-19 19:04:12', 15, NULL, 1, NULL),
(13, 'Special Pricing Offer', 1, 'Special Pricing Offer', 'Special Pricing Offer', '2016-09-19 19:04:25', '2016-09-19 19:04:25', 15, NULL, 1, NULL),
(14, 'Interchange + 0.1%', 1, 'Interchange + 0.1%', 'Interchange + 0.1%', '2016-09-19 19:04:34', '2016-09-19 19:04:34', 15, NULL, 1, NULL),
(15, 'Transaction 0.08 cents', 1, 'Transaction 0.08 cents', 'Transaction 0.08 cents', '2016-09-19 19:04:45', '2016-09-19 19:04:45', 15, NULL, 1, NULL),
(16, 'Free Terminal VX520', 1, 'Free Terminal VX520', 'Free Terminal VX520', '2016-09-19 19:04:53', '2016-09-19 19:04:53', 15, NULL, 1, NULL),
(17, 'Free Terminal paper', 1, 'Free Terminal paper', 'Free Terminal paper', '2016-09-19 19:05:00', '2016-09-19 19:05:00', 15, NULL, 1, NULL),
(18, 'Fast Approve', 1, 'Fast Approve', 'Fast Approve', '2016-09-19 19:05:08', '2016-09-19 19:05:08', 15, NULL, 1, NULL),
(19, 'No Application Fee', 1, 'No Application Fee', 'No Application Fee', '2016-09-19 19:05:16', '2016-09-19 19:05:16', 15, NULL, 1, NULL),
(20, 'Social network, yelp', 1, 'Social network, yelp', 'Social network, yelp', '2016-09-19 19:05:26', '2016-09-19 19:05:26', 15, NULL, 1, NULL),
(21, 'Facebook', 1, 'Facebook', 'Facebook', '2016-09-19 19:05:38', '2016-09-19 19:05:38', 15, NULL, 1, NULL),
(22, 'Manage rent station', 1, 'Manage rent station', 'Manage rent station', '2016-09-19 19:05:49', '2016-09-19 19:05:49', 15, NULL, 1, NULL),
(23, 'Manage Tickets', 1, 'Manage Tickets', 'Manage Tickets', '2016-09-19 19:05:59', '2016-09-19 19:05:59', 15, NULL, 1, NULL),
(24, 'Manage Customer Appointment', 1, 'Manage Customer Appointment', 'Manage Customer Appointment', '2016-09-19 19:06:10', '2016-09-19 19:06:10', 15, NULL, 1, NULL),
(25, 'Manage Customer by VIP Card', 1, 'Manage Customer by VIP Card', 'Manage Customer by VIP Card', '2016-09-19 19:06:19', '2016-09-19 19:06:19', 15, NULL, 1, NULL),
(26, 'Manage Customer # Visiting', 1, 'Manage Customer # Visiting', 'Manage Customer # Visiting', '2016-09-19 19:06:27', '2016-09-19 19:06:27', 15, NULL, 1, NULL),
(27, 'Manage Expense', 1, 'Manage Expense', 'Manage Expense', '2016-09-19 19:06:35', '2016-09-19 19:06:35', 15, NULL, 1, NULL),
(28, 'Detail Income Reports', 1, 'Detail Income Reports', 'Detail Income Reports', '2016-09-19 19:06:47', '2016-09-19 19:06:47', 15, NULL, 1, NULL),
(29, 'Profit Gain & Loss', 1, 'Profit Gain & Loss', 'Profit Gain & Loss', '2016-09-19 19:06:55', '2016-09-19 19:06:55', 15, NULL, 1, NULL),
(30, 'Product Inventory', 1, 'Product Inventory', 'Product Inventory', '2016-09-19 19:07:02', '2016-09-19 19:07:02', 15, NULL, 1, NULL),
(31, 'Detail Employee Services Reports', 1, 'Detail Employee Services Reports', 'Detail Employee Services Reports', '2016-09-19 19:07:12', '2016-09-19 19:07:12', 15, NULL, 1, NULL),
(32, 'Employee Clock in/out', 1, 'Employee Clock in/out', 'Employee Clock in/out', '2016-09-19 19:07:22', '2016-09-19 19:07:22', 15, NULL, 1, NULL),
(33, 'Complete Customer Service History', 1, 'Complete Customer Service History', 'Complete Customer Service History', '2016-09-19 19:07:31', '2016-09-19 19:07:31', 15, NULL, 1, NULL),
(34, 'Send Receipt by email and Signature', 1, 'Send Receipt by email and Signature', 'Send Receipt by email and Signature', '2016-09-19 19:07:45', '2016-09-19 19:07:45', 15, NULL, 1, NULL),
(35, 'Send Receipt by email and Signature', 1, 'Send Receipt by email and Signature', 'Send Receipt by email and Signature', '2016-09-19 19:07:57', '2016-09-19 19:07:57', 15, NULL, 1, NULL),
(36, 'Multiple languages, English and Vietnamese', 1, 'Multiple languages, English and Vietnamese', 'Multiple languages, English and Vietnamese', '2016-09-19 19:08:07', '2016-09-19 19:08:07', 15, NULL, 1, NULL),
(37, 'Form 1099 & 1096', 1, 'Form 1099 & 1096', 'Form 1099 & 1096', '2016-09-19 19:08:14', '2016-09-19 19:08:14', 15, NULL, 1, NULL),
(38, 'New Hiring', 1, 'New Hiring', 'New Hiring', '2016-09-19 19:09:24', '2016-09-19 19:09:24', 15, NULL, 1, NULL),
(39, 'Tax Filling', 1, 'Tax Filling', 'Tax Filling', '2016-09-19 19:09:36', '2016-09-19 19:09:36', 15, NULL, 1, NULL),
(40, 'W2 and W3', 1, 'W2 and W3', 'W2 and W3', '2016-09-19 19:10:06', '2016-09-19 19:10:06', 15, NULL, 1, NULL),
(41, 'Direct Deposit', 1, 'Direct Deposit', 'Direct Deposit', '2016-09-19 19:10:16', '2016-09-19 19:10:16', 15, NULL, 1, NULL),
(42, 'Payroll Delivery', 1, 'Payroll Delivery', 'Payroll Delivery', '2016-09-19 19:10:27', '2016-09-19 19:10:27', 15, NULL, 1, NULL),
(43, 'Electronic Reports', 1, 'Electronic Reports', 'Electronic Reports', '2016-09-19 19:10:35', '2016-09-19 19:10:35', 15, NULL, 1, NULL),
(44, 'New Hire Reporting', 1, 'New Hire Reporting', 'New Hire Reporting', '2016-09-19 19:10:45', '2016-09-19 19:10:45', 15, NULL, 1, NULL),
(45, 'Default service', 1, 'Default service', 'Default service', '2016-09-19 19:16:39', '2016-09-19 19:16:39', 15, NULL, 1, NULL),
(46, 'Form Professional Liability', 1, '1', 'Form Professional Liability', '2016-09-21 18:30:00', '2016-09-21 18:30:00', 15, NULL, 1, NULL),
(47, 'Website Nail Salon', 1, 'Website Nail Salon', 'Website Nail Salon', '2016-09-21 19:11:23', '2016-09-21 19:11:23', 15, NULL, 1, NULL),
(48, 'Merchant Service', 1, '1', '1', '2016-09-21 19:12:08', '2016-09-21 19:12:08', 15, NULL, 1, NULL),
(49, 'Insurance', 1, '1', '1', '2016-09-21 19:17:06', '2016-09-21 19:17:06', 15, NULL, 1, NULL),
(50, 'Form Worker Comp', 1, '1', '1', '2016-09-21 19:27:48', '2016-09-21 19:27:48', 15, NULL, 1, NULL),
(51, 'SMS mời khách viết review', 0, '', '', NULL, NULL, NULL, NULL, 1, 1),
(52, 'SMS thông báo booking thành công', 0, '', '', NULL, NULL, NULL, NULL, 1, 1),
(54, 'SMS thông báo về Giftcard khi khách hàng mua Giftcard', 0, '', '', NULL, NULL, NULL, NULL, 1, 1),
(56, 'SMS Coupon Happy Birthday, ngày lễ, sự kiện', 0, '', '', NULL, NULL, NULL, NULL, 1, 1),
(58, 'SMS nhắc nhở khách hàng đến tiệm làm dịch vụ sau 1 thời gian tùy theo chủ tiệm đặt hàng', 0, '', '', NULL, NULL, NULL, NULL, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `main_servicedetail`
--
ALTER TABLE `main_servicedetail`
  ADD PRIMARY KEY (`servicedetail_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `main_servicedetail`
--
ALTER TABLE `main_servicedetail`
  MODIFY `servicedetail_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
