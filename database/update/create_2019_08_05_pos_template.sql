-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2019 at 12:15 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `deg_merchant`
--

-- --------------------------------------------------------

--
-- Table structure for table `pos_template`
--

CREATE TABLE `pos_template` (
  `template_id` int(10) NOT NULL,
  `template_place_id` int(10) NOT NULL,
  `template_title` varchar(255) NOT NULL,
  `template_discount` int(11) NOT NULL,
  `template_type` tinyint(4) NOT NULL COMMENT 'value 0 is percent, value 1 is amount',
  `template_linkimage` varchar(128) NOT NULL,
  `template_list_service` varchar(128) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `template_status` int(1) NOT NULL DEFAULT '1',
  `template_type_id` int(10) NOT NULL,
  `template_table_type` int(2) DEFAULT NULL COMMENT '1: coupon_template; 2:promotion_template'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos_template`
--

INSERT INTO `pos_template` (`template_id`, `template_place_id`, `template_title`, `template_discount`, `template_type`, `template_linkimage`, `template_list_service`, `created_at`, `updated_at`, `template_status`, `template_type_id`, `template_table_type`) VALUES
(1, 15, 'title coupon 1', 1, 0, 'ewrerw', '1;2', NULL, NULL, 1, 1, 1),
(2, 15, 'title coupon 2', 2, 1, '2121321', '13;14', NULL, NULL, 1, 2, 1),
(3, 15, 'title promotion 1', 1, 1, '123123', '13;12', NULL, NULL, 1, 3, 2),
(4, 15, 'title promotion 2', 2, 1, '213', '1;2', NULL, NULL, 1, 4, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_template`
--
ALTER TABLE `pos_template`
  ADD PRIMARY KEY (`template_id`,`template_place_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
