-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2019 at 12:14 PM
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
-- Table structure for table `pos_template_type`
--

CREATE TABLE `pos_template_type` (
  `template_type_id` int(10) NOT NULL,
  `template_type_name` varchar(128) NOT NULL,
  `template_type_status` int(1) NOT NULL DEFAULT '1',
  `template_type_table_type` int(4) DEFAULT NULL COMMENT '1: coupon_template; 2:promotion_template'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos_template_type`
--

INSERT INTO `pos_template_type` (`template_type_id`, `template_type_name`, `template_type_status`, `template_type_table_type`) VALUES
(1, 'template coupon 1', 1, 1),
(2, 'template coupon 2', 1, 1),
(3, 'promotion template 1', 1, 2),
(4, 'promotion template 2', 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_template_type`
--
ALTER TABLE `pos_template_type`
  ADD PRIMARY KEY (`template_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pos_template_type`
--
ALTER TABLE `pos_template_type`
  MODIFY `template_type_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
