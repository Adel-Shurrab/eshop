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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `url_address` varchar(60) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `rank` enum('admin','customer') NOT NULL,
  `gender` enum('male','female','unknown') DEFAULT 'unknown',
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Active=1, InActive=0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `url_address`, `name`, `email`, `password`, `date`, `last_login`, `avatar`, `rank`, `gender`, `phone`, `address`, `country`, `state`, `zip`, `status`, `is_deleted`, `deleted_at`) VALUES
(12, 'wduzhkvq7bkz', 'Admin', 'admin@example.com', '$2y$10$Fy/U3qddcDZblOxTMmNEf.5cnm/.ZGWXzHvJaL8SvFr3L2dgBknaW', '2025-01-20 09:18:46', '2025-04-20 08:50:40', 'uploads/avatars/avatar_12_68010d7200990.jpg', 'admin', 'male', '0567371500', 'Shurrab Street', 13, 61, '9050', 1, 0, NULL),
(13, 'uflegth0mjgb9rtag83fyiggntijlcgkot1t4ocb7af0kvilxcn2kg4ec5eh', 'Adel Shurrab', 'a3@gmail.com', '$2y$10$U3Rg0WL7vgAjCe/vBjeYjurCuRAnJiv6eAJG8kdnMasRxgKUk7RHC', '2025-01-22 10:29:33', '2025-04-08 11:36:31', 'uploads/avatars/avatar_13_6800f3d8a94c2.png', 'customer', 'male', '0567371501', 'Shurrab Street', 5, 24, '9030', 1, 1, '2025-04-20 15:27:58'),
(14, 'hahlgg6myattqgl7qmeomy', 'Samya Ahmed', 'samya@gmail.com', '$2y$10$V.uIa02RQWN0/y.x3Kk0M.Z8JIE2fsljniRIQ6EQ2JxxPKu8eE7.e', '2025-04-15 11:13:50', NULL, NULL, 'customer', 'male', '96522658714', 'dddddddddddddddddddddd', 9, 43, '20011', 0, 0, NULL),
(15, 'hwshc7akrrn63lnuvoh7bnm7iwbauoct', 'Lara Smith', 'lara.smith@example.com', '$2y$10$SK5rdeW1wPjEMA.sLAbHee33YUQTTEbqtuh2sTFgslRlFJtSR6gG.', '2025-04-16 07:05:29', NULL, NULL, 'customer', 'female', '1234567890', '123 Maple Street', 5, 25, '10001', 1, 0, NULL),
(17, 'o7ov', 'Emma Wilson', 'emma.wilson@example.com', '$2y$10$8G.a3.EM5ev6kLz.jol/JuPCrLJ39dBqx4r/1QWSsePiVwgQNd/q2', '2025-04-16 07:09:44', NULL, NULL, 'customer', 'male', '5551234595', '789 Pine Road', 9, 41, '30303', 0, 1, '2025-04-20 11:37:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `url_address` (`url_address`) USING BTREE,
  ADD KEY `country` (`country`),
  ADD KEY `state` (`state`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`country`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`state`) REFERENCES `states` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
