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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' 0=diabled, 1=Enabled ',
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT '0=Active, 1=Deleted',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `parent`, `disabled`, `is_deleted`, `deleted_at`) VALUES
(147, 'Electronics', 0, 1, 0, NULL),
(148, 'Computers', 0, 1, 0, NULL),
(149, 'Camera & Photo', 147, 0, 0, NULL),
(151, 'Audio & Radio', 147, 1, 0, NULL),
(152, 'Headphones', 147, 1, 0, NULL),
(153, 'Home Audio', 147, 1, 0, NULL),
(154, 'Office Electronics', 147, 1, 0, NULL),
(155, 'Computer Accessories & Periphe', 148, 1, 0, NULL),
(156, 'Computer Components', 148, 1, 0, NULL),
(157, 'Data Storage', 148, 1, 0, NULL),
(158, 'Computers & Tablets', 148, 1, 0, NULL),
(159, 'Laptop Accessories', 148, 1, 0, NULL),
(160, 'Monitors', 148, 1, 0, NULL),
(161, 'Smart Home', 0, 1, 0, NULL),
(162, 'Smart Home Lighting', 161, 1, 0, NULL),
(163, 'Security Cameras and Systems', 161, 1, 0, NULL),
(164, 'Smart Locks and Entry', 161, 1, 0, NULL),
(165, 'Voice Assistants and Hubs', 161, 1, 0, NULL),
(166, 'Automotive', 0, 1, 0, NULL),
(167, 'Car Care', 166, 1, 0, NULL),
(168, 'Oils & Fluids', 166, 1, 0, NULL),
(169, 'Car Electronics & Accessories', 166, 1, 0, NULL),
(170, 'Tires & Wheels', 166, 1, 0, NULL),
(171, 'Beauty and Personal Care', 0, 1, 0, NULL),
(172, 'Makeup', 171, 1, 0, NULL),
(173, 'Skin Care', 171, 1, 0, NULL),
(174, 'Hair Care', 171, 1, 0, NULL),
(175, 'Men\'s Fashion', 0, 1, 0, NULL),
(176, 'Clothing', 175, 1, 0, NULL),
(177, 'Shoes', 175, 1, 0, NULL),
(178, 'Watches', 175, 1, 0, NULL),
(179, 'Accessories', 175, 1, 0, NULL),
(180, 'Women\'s Fashion', 0, 1, 0, NULL),
(181, 'Jewelry', 180, 1, 0, NULL),
(182, 'Handbags', 180, 1, 0, NULL),
(183, 'Video Games', 0, 1, 0, NULL),
(184, 'Consoles & Accessories', 183, 1, 0, NULL),
(185, 'PlayStation 4', 183, 1, 0, NULL),
(186, 'PlayStation 5', 183, 1, 0, NULL),
(187, 'Xbox One', 183, 1, 0, NULL),
(188, 'Nintendo Switch', 183, 1, 0, NULL),
(189, 'PC', 183, 1, 0, NULL),
(190, 'Toys and Games', 0, 1, 0, NULL),
(191, 'Puzzles', 190, 1, 0, NULL),
(192, 'Sports & Outdoor Play', 190, 1, 0, NULL),
(193, 'Arts & Crafts', 190, 1, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `disabled` (`disabled`),
  ADD KEY `is_deleted` (`is_deleted`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
