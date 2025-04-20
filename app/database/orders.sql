-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 03:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eshopper_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_url` varchar(60) NOT NULL,
  `session_id` varchar(30) NOT NULL,
  `address` varchar(1024) DEFAULT NULL,
  `address_2` varchar(100) DEFAULT NULL COMMENT 'Optional',
  `country` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT 0.00,
  `shipping` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date` datetime NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('paid','unpaid','failed') DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_url`, `session_id`, `address`, `address_2`, `country`, `state`, `zip`, `phone`, `note`, `tax`, `shipping`, `total`, `date`, `status`, `payment_status`) VALUES
(52, '', 'k23i69eeh2u4o9plel4h1isfco', 'Shurrab Street', '', 'Palestine', 'Gaza', '9030', '0599135587', '', 30.00, 0.00, 329.98, '2025-04-07 09:16:59', 'processing', 'unpaid'),
(53, 'wduzhkvq7bkz', '4tive2lpbcn9td2ru1foppeqt2', 'Khan Younis', 'Al Jalal Street', 'Palestine', 'Gaza', '9060', '2432432', '', 20.00, 0.00, 219.99, '2025-04-07 09:19:26', 'shipped', 'paid'),
(54, 'wduzhkvq7bkz', '4tive2lpbcn9td2ru1foppeqt2', 'Khan Younis', 'Al Jalal Street', 'Palestine', 'Gaza', '9060', '12234214324', '', 35.00, 0.00, 384.99, '2025-04-07 09:19:50', 'delivered', 'paid'),
(55, 'uflegth0mjgb9rtag83fyiggntijlcgkot1t4ocb7af0kvilxcn2kg4ec5eh', 'pclp4vhjjf5cnat06f5v1rguh3', 'dfww', 'fdwfwer', 'Kuwait', 'Kuwait City', '3060', '3212432423412', '', 35.80, 0.00, 393.75, '2025-04-07 09:20:23', 'pending', 'unpaid'),
(62, '', '4anc8dr1urcd1m1lbata327pai', 'eeeeeeee', 'ddddddddd', 'Libya', 'Sabha', '3060', '13124234', '', 5.99, 0.00, 65.93, '2025-04-08 12:18:35', 'cancelled', 'failed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date` (`date`),
  ADD KEY `idx_user_date` (`user_url`,`date`),
  ADD KEY `idx_country_state` (`country`,`state`),
  ADD KEY `idx_user_status` (`user_url`,`status`),
  ADD KEY `user_url` (`user_url`),
  ADD KEY `session_id` (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
