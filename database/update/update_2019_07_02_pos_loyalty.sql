-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2019 at 10:34 AM
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
-- Table structure for table `pos_loyalty`
--

CREATE TABLE `pos_loyalty` (
  `loyalty_id` int(11) NOT NULL,
  `loyalty_place_id` int(11) NOT NULL,
  `loyalty_paying_by_cash` int(11) NOT NULL,
  `loyalty_return_in_a_month` text NOT NULL,
  `loyalty_referral_gift_card` int(11) NOT NULL,
  `loyalty_buying_gift_card` int(11) NOT NULL,
  `loyalty_new_customer` int(11) NOT NULL,
  `loyalty_vip_customer` int(11) NOT NULL,
  `loyalty_for_normal` int(11) NOT NULL,
  `loyalty_for_siver` int(11) NOT NULL,
  `loyalty_for_golden` int(11) NOT NULL,
  `loyalty_for_dimond` int(11) NOT NULL,
  `loyalty_vip_point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_loyalty`
--
ALTER TABLE `pos_loyalty`
  ADD PRIMARY KEY (`loyalty_id`,`loyalty_place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pos_loyalty`
--
ALTER TABLE `pos_loyalty`
  MODIFY `loyalty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
