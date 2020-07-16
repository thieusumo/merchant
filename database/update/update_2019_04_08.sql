-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2019 at 10:00 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

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
-- Table structure for table `pos_merchant_menus`
--
DROP TABLE IF EXISTS pos_merchant_menus;
CREATE TABLE `pos_merchant_menus` (
  `mer_menu_id` INT(10) NOT NULL,
  `mer_menu_parent_id` INT(10) NOT NULL DEFAULT '0',
  `mer_menu_index` INT(10) NOT NULL,
  `mer_menu_text` VARCHAR(100) NOT NULL,
  `mer_menu_class` VARCHAR(100) NOT NULL,
  `mer_menu_url` VARCHAR(255) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos_merchant_menus`
--

INSERT INTO `pos_merchant_menus` (`mer_menu_id`, `mer_menu_parent_id`, `mer_menu_index`, `mer_menu_text`, `mer_menu_class`, `mer_menu_url`) VALUES
(1, 0, 0, 'Dashboard', 'fa-dashboard', 'dashboard'),
(2, 0, 1, 'Statistic', 'fa-bar-chart-o', 'statistic'),
(3, 0, 2, 'Sale & Finances', 'fa-shopping-bag', 'salefinances'),
(4, 0, 3, 'Clients', 'fa-user-circle-o', 'clients'),
(5, 0, 4, 'Management', 'fa-th-large', 'management'),
(6, 0, 5, 'Marketing', 'fa-star-o', 'marketing'),
(7, 0, 6, 'Webbuilder', 'fa-cogs', 'webbuilder'),
(8, 0, 7, 'Users', 'fa-users', 'users'),
(9, 0, 8, 'Setting', 'fa-cog', 'setting'),
(10, 3, 0, 'Schedule', '', 'schedule'),
(11, 3, 1, 'Booking', '', 'booking'),
(12, 3, 2, 'Payment', '', 'payment'),
(13, 3, 3, 'Order History', '', 'order-history'),
(14, 3, 4, 'Expenses', '', 'expenses'),
(15, 4, 1, 'Groups', '', 'groups'),
(16, 4, 2, 'Import', '', 'import'),
(17, 4, 0, 'Clients', '', 'clients'),
(18, 5, 1, 'Rent Stations', '', 'staffs'),
(19, 5, 3, 'Tax Forms', '', 'taxforms'),
(21, 6, 1, 'Reviews', '', 'reviews'),
(22, 6, 2, 'SMS/Email', '', 'sms'),
(23, 6, 3, 'Coupons', '', 'coupons'),
(24, 6, 4, 'Promotions', '', 'promotions'),
(25, 6, 5, 'Gift cards', '', 'giftcards'),
(26, 6, 6, 'Image Templates', '', 'contenttemplates'),
(27, 7, 1, 'Service Categories', '', 'cateservices'),
(28, 7, 2, 'Services', '', 'services'),
(29, 7, 3, 'Menus', '', 'menus'),
(30, 7, 4, 'Banners', '', 'banners'),
(31, 7, 5, 'Themes', '', 'themes'),
(32, 7, 6, 'Contacts', '', 'contacts'),
(33, 7, 7, 'Social Network', '', 'social-network'),
(34, 8, 1, 'Users', '', 'users'),
(35, 8, 2, 'Roles', '', 'roles'),
(36, 8, 3, 'Permission Setting', '', 'permissions'),
(37, 9, 1, 'Business Store', '', 'bstore'),
(38, 9, 2, 'System', '', 'system'),
(39, 5, 2, 'Attendances', '', 'staff-attendances');

-- --------------------------------------------------------

--
-- Table structure for table `pos_merchant_permission`
--
DROP TABLE IF EXISTS pos_merchant_permission;
CREATE TABLE `pos_merchant_permission` (
  `mp_id` INT(11) NOT NULL,
  `mer_menu_id` INT(10) NOT NULL,
  `mp_name` VARCHAR(50) NOT NULL,
  `mp_display_name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(10) NOT NULL,
  `updated_by` INT(10) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pos_merchant_permission`
--

INSERT INTO `pos_merchant_permission` (`mp_id`, `mer_menu_id`, `mp_name`, `mp_display_name`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 17, 'create-clients', 'Create Clients', '2019-03-26 08:47:15', '0000-00-00 00:00:00', 0, 0),
(2, 17, 'read-clients', 'View Clients', '2019-03-26 08:47:17', '0000-00-00 00:00:00', 0, 0),
(3, 17, 'update-clients', 'Update Clients', '2019-03-26 08:47:19', '0000-00-00 00:00:00', 0, 0),
(4, 17, 'delete-clients', 'Delete Clients', '2019-03-26 08:47:21', '0000-00-00 00:00:00', 0, 0),
(5, 15, 'create-client-group', 'Create Client Group', '2019-03-26 08:26:57', '0000-00-00 00:00:00', 0, 0),
(6, 15, 'update-client-group', 'Update Client Group', '2019-03-26 08:26:57', '0000-00-00 00:00:00', 0, 0),
(7, 15, 'read-client-group', 'View Client Group', '2019-03-26 08:28:05', '0000-00-00 00:00:00', 0, 0),
(8, 15, 'delete-client-group', 'Delete Client Group', '2019-03-26 08:28:05', '0000-00-00 00:00:00', 0, 0),
(9, 10, 'create-schedule', 'Create Schedule', '2019-03-27 03:55:09', '0000-00-00 00:00:00', 0, 0),
(10, 10, 'update-schedule', 'Update Schedule', '2019-03-27 03:55:09', '0000-00-00 00:00:00', 0, 0),
(11, 10, 'read-schedule', 'View Schedule', '2019-03-27 03:55:09', '0000-00-00 00:00:00', 0, 0),
(12, 10, 'delete-schedule', 'Delete Schedule', '2019-03-27 03:55:09', '0000-00-00 00:00:00', 0, 0),
(13, 11, 'create-tickets', 'Create Tickets', '2019-03-27 03:57:42', '0000-00-00 00:00:00', 0, 0),
(14, 11, 'read-tickets', 'View Tickets', '2019-03-27 03:57:42', '0000-00-00 00:00:00', 0, 0),
(15, 11, 'update-tickets', 'Update Tickets', '2019-03-27 03:57:42', '0000-00-00 00:00:00', 0, 0),
(16, 11, 'delete-tickets', 'Delete Tickets', '2019-03-27 03:57:42', '0000-00-00 00:00:00', 0, 0),
(17, 12, 'create-payment', 'Create Payment', '2019-03-27 03:59:20', '0000-00-00 00:00:00', 0, 0),
(18, 12, 'read-payment', 'View Payment', '2019-03-27 03:59:20', '0000-00-00 00:00:00', 0, 0),
(19, 12, 'update-payment', 'Update Payment', '2019-03-27 03:59:20', '0000-00-00 00:00:00', 0, 0),
(20, 12, 'delete-payment', 'Delete Payment', '2019-03-27 03:59:20', '0000-00-00 00:00:00', 0, 0),
(21, 13, 'create-order-history', 'Create Order History', '2019-03-27 04:04:36', '0000-00-00 00:00:00', 0, 0),
(22, 13, 'read-order-history', 'View Order History', '2019-03-27 04:04:36', '0000-00-00 00:00:00', 0, 0),
(23, 13, 'update-order-history', 'Update Order History', '2019-03-27 04:04:36', '0000-00-00 00:00:00', 0, 0),
(24, 13, 'delete-order-payment', 'Delete Order Payment', '2019-03-27 04:04:36', '0000-00-00 00:00:00', 0, 0),
(25, 14, 'create-expenses', 'Create Expenses', '2019-03-27 04:10:13', '0000-00-00 00:00:00', 0, 0),
(26, 14, 'read-expense', 'View Expense', '2019-03-27 04:10:13', '0000-00-00 00:00:00', 0, 0),
(27, 14, 'update-expense', 'Update Expense', '2019-03-27 04:10:13', '0000-00-00 00:00:00', 0, 0),
(28, 14, 'delete-expense', 'Delete Expense', '2019-03-27 04:10:13', '0000-00-00 00:00:00', 0, 0),
(29, 16, 'create-client-import', 'Create Client Import', '2019-03-27 04:14:03', '0000-00-00 00:00:00', 0, 0),
(30, 16, 'read-client-import', 'View Client Import', '2019-03-27 04:14:03', '0000-00-00 00:00:00', 0, 0),
(31, 16, 'update-client-import', 'Update Client Import', '2019-03-27 04:14:03', '0000-00-00 00:00:00', 0, 0),
(32, 16, 'delete-client-import', 'Delete Client Import', '2019-03-27 04:14:03', '0000-00-00 00:00:00', 0, 0),
(33, 18, 'create-rent-stations', 'Create Rent Stations', '2019-03-27 04:32:45', '0000-00-00 00:00:00', 0, 0),
(34, 18, 'read-rent-stations', 'View Rent Stations', '2019-03-27 04:32:45', '0000-00-00 00:00:00', 0, 0),
(35, 18, 'update-rent-stations', 'Update Rent Stations', '2019-03-27 04:32:45', '0000-00-00 00:00:00', 0, 0),
(36, 18, 'delete-rent-stations', 'Delete Rent Stations', '2019-03-27 04:32:45', '0000-00-00 00:00:00', 0, 0),
(37, 19, 'create-tax-forms', 'Tax Forms', '2019-03-27 04:34:17', '0000-00-00 00:00:00', 0, 0),
(38, 19, 'read-tax-forms', 'View Tax Forms', '2019-03-27 04:34:17', '0000-00-00 00:00:00', 0, 0),
(39, 19, 'update-tax-forms', 'Update Tax Forms', '2019-03-27 04:34:17', '0000-00-00 00:00:00', 0, 0),
(40, 19, 'delete-tax-forms', 'Delete Tax Forms', '2019-03-27 04:34:17', '0000-00-00 00:00:00', 0, 0),
(45, 21, 'create-reviews', 'Create Reviews', '2019-03-27 04:39:38', '0000-00-00 00:00:00', 0, 0),
(46, 21, 'read-reviews', 'View Reviews', '2019-03-27 04:39:38', '0000-00-00 00:00:00', 0, 0),
(47, 21, 'update-reviews', 'Update Reviews', '2019-03-27 04:39:38', '0000-00-00 00:00:00', 0, 0),
(48, 21, 'delete-reviews', 'Delete Reviews', '2019-03-27 04:39:38', '0000-00-00 00:00:00', 0, 0),
(49, 22, 'create-sms', 'Create SMS', '2019-03-27 04:40:55', '0000-00-00 00:00:00', 0, 0),
(50, 22, 'read-sms', 'View SMS', '2019-03-27 04:40:55', '0000-00-00 00:00:00', 0, 0),
(51, 22, 'update-sms', 'Update SMS', '2019-03-27 04:40:55', '0000-00-00 00:00:00', 0, 0),
(52, 22, 'delete-sms', 'Delete SMS', '2019-03-27 04:40:55', '0000-00-00 00:00:00', 0, 0),
(53, 23, 'create-coupons', 'Create Coupons', '2019-03-27 04:42:23', '0000-00-00 00:00:00', 0, 0),
(54, 23, 'read-coupons', 'View Coupons', '2019-03-27 04:42:23', '0000-00-00 00:00:00', 0, 0),
(55, 23, 'update-coupons', 'Update Coupons', '2019-03-27 04:42:23', '0000-00-00 00:00:00', 0, 0),
(56, 23, 'delete-coupons', 'Delete Coupons', '2019-03-27 04:42:23', '0000-00-00 00:00:00', 0, 0),
(57, 24, 'create-promotions', 'Create Promotions', '2019-03-27 04:44:09', '0000-00-00 00:00:00', 0, 0),
(58, 24, 'read-promotions', 'View Promotions', '2019-03-27 04:44:09', '0000-00-00 00:00:00', 0, 0),
(59, 24, 'update-promotions', 'Update Promotions', '2019-03-27 04:44:09', '0000-00-00 00:00:00', 0, 0),
(60, 24, 'delete-promotions', 'Delete Promotions', '2019-03-27 04:44:09', '0000-00-00 00:00:00', 0, 0),
(61, 25, 'create-gift-cards', 'Gift cards', '2019-03-27 04:45:34', '0000-00-00 00:00:00', 0, 0),
(62, 25, 'read-gift-cards', 'View Gift Cards', '2019-03-27 04:45:34', '0000-00-00 00:00:00', 0, 0),
(63, 25, 'update-gift-cards', 'Update Gift Cards', '2019-03-27 04:45:34', '0000-00-00 00:00:00', 0, 0),
(64, 25, 'delete-gift-cards', 'Delete Gift Cards', '2019-03-27 04:45:34', '0000-00-00 00:00:00', 0, 0),
(65, 26, 'create-image-templates', 'Create Image Templates', '2019-03-27 04:47:09', '0000-00-00 00:00:00', 0, 0),
(66, 26, 'read-image-templates', 'View Image Templates', '2019-03-27 04:47:09', '0000-00-00 00:00:00', 0, 0),
(67, 26, 'update-image-templates', 'Update Image Templates', '2019-03-27 04:47:09', '0000-00-00 00:00:00', 0, 0),
(68, 26, 'delete-image-templates', 'Delete Image Templates', '2019-03-27 04:47:09', '0000-00-00 00:00:00', 0, 0),
(69, 27, 'create-service-categories', 'Create Service Categories', '2019-03-27 04:48:42', '0000-00-00 00:00:00', 0, 0),
(70, 27, 'read-service-categories', 'View Service Categories', '2019-03-27 04:48:42', '0000-00-00 00:00:00', 0, 0),
(71, 27, 'update-service-categories', 'Update Service Categories', '2019-03-27 04:48:42', '0000-00-00 00:00:00', 0, 0),
(72, 27, 'delete-service-categories', 'Delete Service Categories', '2019-03-27 04:48:42', '0000-00-00 00:00:00', 0, 0),
(73, 28, 'create-services', 'Create Services', '2019-03-27 04:49:55', '0000-00-00 00:00:00', 0, 0),
(74, 28, 'read-services', 'View Services', '2019-03-27 04:49:55', '0000-00-00 00:00:00', 0, 0),
(75, 28, 'update-services', 'Update Services', '2019-03-27 04:49:55', '0000-00-00 00:00:00', 0, 0),
(76, 28, 'delete-services', 'Delete Services', '2019-03-27 04:49:55', '0000-00-00 00:00:00', 0, 0),
(77, 29, 'create-menus', 'Create Menus', '2019-03-27 04:51:15', '0000-00-00 00:00:00', 0, 0),
(78, 29, 'read--menus', 'View Menus', '2019-03-27 04:51:38', '0000-00-00 00:00:00', 0, 0),
(79, 29, 'update-menus', 'Update Menus', '2019-03-27 04:51:41', '0000-00-00 00:00:00', 0, 0),
(80, 29, 'delete-menus', 'Delete Menus', '2019-03-27 04:51:43', '0000-00-00 00:00:00', 0, 0),
(81, 30, 'create-banners', 'Banners', '2019-03-27 04:55:38', '0000-00-00 00:00:00', 0, 0),
(82, 30, 'read-banners', 'View Banners', '2019-03-27 04:55:38', '0000-00-00 00:00:00', 0, 0),
(83, 30, 'update-banners', 'Update Banners', '2019-03-27 04:55:38', '0000-00-00 00:00:00', 0, 0),
(84, 30, 'delete-banners', 'Delete Banners', '2019-03-27 04:55:38', '0000-00-00 00:00:00', 0, 0),
(85, 31, 'create-themes', 'Create Themes', '2019-03-27 04:57:49', '0000-00-00 00:00:00', 0, 0),
(86, 31, 'read-themes', 'View Themes', '2019-03-27 04:57:49', '0000-00-00 00:00:00', 0, 0),
(87, 31, 'update-themes', 'Update Themes', '2019-03-27 04:57:49', '0000-00-00 00:00:00', 0, 0),
(88, 31, 'delete-themes', 'Delete Themes', '2019-03-27 04:57:49', '0000-00-00 00:00:00', 0, 0),
(89, 34, 'create-users', 'Create Users', '2019-03-27 04:59:08', '0000-00-00 00:00:00', 0, 0),
(90, 34, 'read-users', 'View Users', '2019-03-27 04:59:08', '0000-00-00 00:00:00', 0, 0),
(91, 34, 'update-users', 'Update Users', '2019-03-27 04:59:08', '0000-00-00 00:00:00', 0, 0),
(92, 34, 'delete-users', 'Delete Users', '2019-03-27 04:59:08', '0000-00-00 00:00:00', 0, 0),
(93, 35, 'create-roles', 'Create Roles', '2019-03-27 05:00:38', '0000-00-00 00:00:00', 0, 0),
(94, 35, 'read-roles', 'View Roles', '2019-03-27 05:00:38', '0000-00-00 00:00:00', 0, 0),
(95, 34, 'update-roles', 'Update Roles', '2019-03-27 05:00:38', '0000-00-00 00:00:00', 0, 0),
(96, 35, 'delete-roles', 'Delete Roles', '2019-03-27 05:00:38', '0000-00-00 00:00:00', 0, 0),
(97, 36, 'create-permission-setting', 'Permission Setting\r\n', '2019-03-27 05:01:58', '0000-00-00 00:00:00', 0, 0),
(98, 36, 'read-permission-setting', 'View Permission Setting', '2019-03-27 05:01:58', '0000-00-00 00:00:00', 0, 0),
(99, 36, 'update-permission-setting', 'Update Permission Setting', '2019-03-27 05:01:58', '0000-00-00 00:00:00', 0, 0),
(100, 36, 'delete-permission-setting', 'Delete Permission Setting', '2019-03-27 05:01:58', '0000-00-00 00:00:00', 0, 0),
(101, 37, 'create-business-store', 'Business Store', '2019-03-27 05:15:27', '0000-00-00 00:00:00', 0, 0),
(102, 37, 'read--business-store', 'View Business Store', '2019-03-27 05:15:27', '0000-00-00 00:00:00', 0, 0),
(103, 37, 'update-business-store', 'Update Business Store', '2019-03-27 05:15:27', '0000-00-00 00:00:00', 0, 0),
(104, 37, 'delete-business-store', 'Delete Business Store', '2019-03-27 05:15:27', '0000-00-00 00:00:00', 0, 0),
(105, 38, 'create-system', 'Create System', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(106, 38, 'read-system', 'View System', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(107, 38, 'update-system', 'Update System', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(108, 38, 'delete-system', 'Delete System', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(109, 32, 'read-contact', 'View Contact', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(110, 32, 'delete-contact', 'Delete Contact', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(111, 33, 'read-social-network', 'View Social Network', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(112, 33, 'update-social-network', 'Update Social Network', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0),
(113, 39, 'staff-attendance', 'View Staff Attendances', '2019-03-27 05:16:25', '0000-00-00 00:00:00', 0, 0);


-- --------------------------------------------------------

--
-- Table structure for table `pos_merchant_per_user_group`
--
DROP TABLE IF EXISTS pos_merchant_per_user_group;
CREATE TABLE `pos_merchant_per_user_group` (
  `mp_id` TEXT NOT NULL COMMENT 'table:pos_merchant_permission   id',
  `ug_id` INT(11) NOT NULL COMMENT 'table: pos_user_group ug_id',
  `mpug_place_id` INT(10) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_merchant_menus`
--
ALTER TABLE `pos_merchant_menus`
  ADD PRIMARY KEY (`mer_menu_id`);

--
-- Indexes for table `pos_merchant_permission`
--
ALTER TABLE `pos_merchant_permission`
  ADD PRIMARY KEY (`mp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pos_merchant_menus`
--
ALTER TABLE `pos_merchant_menus`
  MODIFY `mer_menu_id` INT(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `pos_merchant_permission`
--
ALTER TABLE `pos_merchant_permission`
  MODIFY `mp_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
