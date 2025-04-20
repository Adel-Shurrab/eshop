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
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state` varchar(30) NOT NULL,
  `disabled` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `state`, `disabled`) VALUES
(1, 1, 'Riyadh', 0),
(2, 1, 'Mecca', 0),
(3, 1, 'Medina', 0),
(4, 1, 'Jeddah', 0),
(5, 1, 'Dammam', 0),
(6, 2, 'Dubai', 0),
(7, 2, 'Abu Dhabi', 0),
(8, 2, 'Sharjah', 0),
(9, 2, 'Ajman', 0),
(10, 2, 'Ras Al Khaimah', 0),
(11, 3, 'Doha', 0),
(12, 3, 'Al Rayyan', 0),
(13, 3, 'Umm Salal', 0),
(14, 3, 'Al Wakrah', 0),
(15, 3, 'Al Khor', 0),
(16, 4, 'Kuwait City', 0),
(17, 4, 'Hawalli', 0),
(18, 4, 'Farwaniya', 0),
(19, 4, 'Mubarak Al-Kabeer', 0),
(20, 4, 'Ahmadi', 0),
(21, 5, 'Muscat', 0),
(22, 5, 'Salalah', 0),
(23, 5, 'Sohar', 0),
(24, 5, 'Nizwa', 0),
(25, 5, 'Sur', 0),
(26, 6, 'Manama', 0),
(27, 6, 'Muharraq', 0),
(28, 6, 'Riffa', 0),
(29, 6, 'Hamad Town', 0),
(30, 6, 'Isa Town', 0),
(31, 7, 'Baghdad', 0),
(32, 7, 'Basra', 0),
(33, 7, 'Erbil', 0),
(34, 7, 'Mosul', 0),
(35, 7, 'Najaf', 0),
(36, 8, 'Amman', 0),
(37, 8, 'Zarqa', 0),
(38, 8, 'Irbid', 0),
(39, 8, 'Aqaba', 0),
(40, 8, 'Madaba', 0),
(41, 9, 'Beirut', 0),
(42, 9, 'Tripoli', 0),
(43, 9, 'Sidon', 0),
(44, 9, 'Tyre', 0),
(45, 9, 'Byblos', 0),
(46, 10, 'Damascus', 0),
(47, 10, 'Aleppo', 0),
(48, 10, 'Homs', 0),
(49, 10, 'Latakia', 0),
(50, 10, 'Hama', 0),
(51, 11, 'Sana\'a', 0),
(52, 11, 'Aden', 0),
(53, 11, 'Taiz', 0),
(54, 11, 'Al Hudaydah', 0),
(55, 11, 'Ibb', 0),
(56, 13, 'Jerusalem', 0),
(61, 13, 'Gaza', 0),
(62, 13, 'Ramallah', 0),
(63, 13, 'Hebron', 0),
(64, 13, 'Nablus', 0),
(65, 13, 'Bethlehem', 0),
(66, 14, 'Cairo', 0),
(67, 14, 'Alexandria', 0),
(68, 14, 'Giza', 0),
(69, 14, 'Luxor', 0),
(70, 14, 'Aswan', 0),
(71, 15, 'Istanbul', 0),
(72, 15, 'Ankara', 0),
(73, 15, 'Izmir', 0),
(74, 15, 'Bursa', 0),
(75, 15, 'Antalya', 0),
(76, 16, 'Tehran', 0),
(77, 16, 'Mashhad', 0),
(78, 16, 'Isfahan', 0),
(79, 16, 'Tabriz', 0),
(80, 16, 'Shiraz', 0),
(81, 17, 'Kabul', 0),
(82, 17, 'Kandahar', 0),
(83, 17, 'Herat', 0),
(84, 17, 'Mazar-i-Sharif', 0),
(85, 17, 'Jalalabad', 0),
(86, 18, 'Islamabad', 0),
(87, 18, 'Karachi', 0),
(88, 18, 'Lahore', 0),
(89, 18, 'Peshawar', 0),
(90, 18, 'Quetta', 0),
(91, 19, 'Tripoli', 0),
(92, 19, 'Benghazi', 0),
(93, 19, 'Misrata', 0),
(94, 19, 'Zawiya', 0),
(95, 19, 'Sabha', 0),
(96, 20, 'Khartoum', 0),
(97, 20, 'Omdurman', 0),
(98, 20, 'Port Sudan', 0),
(99, 20, 'Kassala', 0),
(100, 20, 'Nyala', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disabled` (`disabled`),
  ADD KEY `country_id` (`country_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
