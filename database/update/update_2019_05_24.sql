-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2019 at 06:05 AM
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
-- Table structure for table `pos_expense_template`
--

CREATE TABLE `pos_expense_template` (
  `ex_template_id` int(64) UNSIGNED NOT NULL,
  `ex_template_place_id` int(64) NOT NULL,
  `ex_template_name` varchar(200) NOT NULL,
  `ex_template_cost` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos_expense_template`
--

INSERT INTO `pos_expense_template` (`ex_template_id`, `ex_template_place_id`, `ex_template_name`, `ex_template_cost`, `created_at`, `updated_at`) VALUES
(14, 15, 'SUPPLY OFFICE', NULL, '2019-05-23 21:02:13', '2019-05-23 21:02:13'),
(15, 15, 'ACCOUNT', NULL, '2019-05-23 21:02:20', '2019-05-23 21:02:20'),
(16, 15, 'GAS FOR BUSINESS', NULL, '2019-05-23 21:02:37', '2019-05-23 21:02:37'),
(17, 15, 'LICENSE', NULL, '2019-05-23 21:02:46', '2019-05-23 21:02:46'),
(18, 15, 'MERCHANT SERVICE', NULL, '2019-05-23 21:02:55', '2019-05-23 21:02:55'),
(19, 15, 'DONATION', NULL, '2019-05-23 21:03:02', '2019-05-23 21:03:02'),
(20, 15, 'BUSINESS INS', NULL, '2019-05-23 21:03:12', '2019-05-23 21:03:12'),
(21, 15, 'LEGAL SERVICE', NULL, '2019-05-23 21:03:19', '2019-05-23 21:03:19'),
(22, 15, 'TRAVEL', NULL, '2019-05-23 21:03:27', '2019-05-23 21:03:27'),
(23, 15, 'MEAL/PARTY', NULL, '2019-05-23 21:03:36', '2019-05-23 21:03:36'),
(24, 15, 'SHOW NAILS', NULL, '2019-05-23 21:03:45', '2019-05-23 21:03:45'),
(25, 15, 'BUSINESS CARD', NULL, '2019-05-23 21:03:53', '2019-05-23 21:03:53'),
(26, 15, 'HEALTH INS', NULL, '2019-05-23 21:04:00', '2019-05-23 21:04:00'),
(27, 15, 'ELECTRONIC', NULL, '2019-05-23 21:04:09', '2019-05-23 21:04:09'),
(28, 15, 'REPAIR BUSINESS', NULL, '2019-05-23 21:04:18', '2019-05-23 21:04:18'),
(29, 15, 'BUSINESS PHONE', NULL, '2019-05-23 21:04:26', '2019-05-23 21:04:26'),
(30, 15, 'SAMPLE PRODUCT', NULL, '2019-05-23 21:04:35', '2019-05-23 21:04:35'),
(32, 15, 'BROCHURSE', NULL, '2019-05-23 21:04:54', '2019-05-23 21:04:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_expense_template`
--
ALTER TABLE `pos_expense_template`
  ADD PRIMARY KEY (`ex_template_id`,`ex_template_place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pos_expense_template`
--
ALTER TABLE `pos_expense_template`
  MODIFY `ex_template_id` int(64) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
