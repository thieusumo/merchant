-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2019 at 05:54 AM
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
-- Table structure for table `pos_sms_group_receivers`
--

CREATE TABLE `pos_sms_group_receivers` (
  `sms_group_receivers_id` int(11) NOT NULL,
  `sms_group_receivers_place_id` int(11) NOT NULL,
  `sms_group_receivers_group_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sms_group_receivers_status` int(11) NOT NULL DEFAULT '1',
  `sms_group_receivers_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pos_sms_group_receivers_detail`
--

CREATE TABLE `pos_sms_group_receivers_detail` (
  `sms_group_receivers_detail_id` int(11) NOT NULL,
  `sms_group_receivers_detail_place_id` int(11) NOT NULL,
  `sms_group_receivers_detail_name` varchar(64) NOT NULL,
  `sms_group_receivers_detail_phone` varchar(16) NOT NULL,
  `sms_group_receivers_detail_dob` date NOT NULL,
  `sms_group_receivers_detail_group_receivers_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_sms_group_receivers`
--
ALTER TABLE `pos_sms_group_receivers`
  ADD PRIMARY KEY (`sms_group_receivers_id`,`sms_group_receivers_place_id`);

--
-- Indexes for table `pos_sms_group_receivers_detail`
--
ALTER TABLE `pos_sms_group_receivers_detail`
  ADD PRIMARY KEY (`sms_group_receivers_detail_id`,`sms_group_receivers_detail_place_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `pos_message_template` ADD `remind_before` INT(3) NOT NULL AFTER `mt_description`;
