-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2019 at 10:08 AM
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
-- Table structure for table `pos_sms_send_event`
--

CREATE TABLE `pos_sms_send_event` (
  `sms_send_event_id` int(11) NOT NULL,
  `sms_send_event_place_id` int(11) NOT NULL,
  `sms_send_event_title` varchar(255) NOT NULL,
  `sms_send_event_type` int(11) NOT NULL,
  `sms_send_event_template_id` int(11) NOT NULL,
  `sms_send_event_start_day` date NOT NULL,
  `sms_send_event_start_time` varchar(20) NOT NULL,
  `sms_send_event_end_date` date DEFAULT NULL,
  `sms_send_event_repeat_type` varchar(5) NOT NULL COMMENT 'no:Don''t repeat, w: Weekly, m: Monthly, y: Yearly',
  `sms_send_event_repeat_on` text,
  `send_before_days` int(5) DEFAULT NULL,
  `group_receiver_id` int(11) NOT NULL DEFAULT '0',
  `upload_list_receiver` text,
  `add_more_phone` varchar(255) NOT NULL DEFAULT '0',
  `sms_send_event_status` int(5) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_sms_send_event`
--
ALTER TABLE `pos_sms_send_event`
  ADD PRIMARY KEY (`sms_send_event_id`,`sms_send_event_place_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
