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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `slag` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(500) NOT NULL,
  `image2` varchar(500) DEFAULT NULL,
  `image3` varchar(500) DEFAULT NULL,
  `image4` varchar(500) DEFAULT NULL,
  `date` datetime NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT '0=Active, 1=Deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `slag`, `description`, `category_id`, `price`, `quantity`, `image`, `image2`, `image3`, `image4`, `date`, `deleted_at`, `is_deleted`) VALUES
(232, 'canon-eos-rebel-dslr-camera', 'Canon EOS Rebel DSLR Camera', 149, 598.99, 2, '1739696663_Canon_XsQjYI6TEf_w6XpYbOCgS.jpg', '1739696696_canon2_EnFesbHHjG.jpg', '2025-04-17_095615_44Paeu1EIm.png', '', '2025-02-15 13:10:06', NULL, 0),
(233, 'nikon-d3500-digital-slr-camera', 'Nikon D3500 Digital SLR Camera', 149, 499.99, 0, '1739696808_NikonD3500_1_xqlgK6jM3b.jpg', '1739696818_Nikon_2__9mtAdl0kEU.jpg', '1739696818_Nikon_3_8qhn8Es7Kp.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(234, 'sony-portable-amfm-radio', 'Sony Portable AM/FM Radio', 151, 39.99, 100, '1739697397_sony_gprcJ5Mz6w.jpg', '1739697397_sony_2_4sSTjJF96V.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(235, 'sony-wh-1000xm4-wireless-headphones', 'Sony WH-1000XM4 Wireless Headphones', 152, 349.99, 30, '1739697487_SonyWH-1000XM4_1_HoyZAXZOML.jpg', '1739697487_SonyWH-1000XM4_2_UDJBEKFfhy.jpg', '1739697487_SonyWH-1000XM4_3_IWzd3Py8k6.jpg', NULL, '2025-02-15 13:10:06', NULL, 0),
(236, 'bose-quietcomfort-45-wireless-headphones', 'Bose QuietComfort 45 Wireless Headphones', 152, 329.99, 25, '1739697547_BoseQuietComfort_aRYavlYtyS.jpg', '', '', '', '2025-02-15 13:10:06', NULL, 0),
(237, 'sonos-play5-wireless-speaker', 'Sonos Play:5 Wireless Speaker', 153, 499, 20, '1739697718_81N2obT55UL._AC_SL1300__PdXqk6dcAw.jpg', '', '', '', '2025-02-15 13:10:06', NULL, 0),
(238, 'hp-officejet-pro-9015e-all-in-one-printer', 'HP OfficeJet Pro 9015e All-in-One Printer', 154, 299.99, 40, '1739697743_61CwOSIirpL._AC_SL1500__HSnmS6e3ln.jpg', '', '', '', '2025-02-15 13:10:06', NULL, 0),
(239, 'logitech-mk270-wireless-keyboard-and-mouse-combo', 'Logitech MK270 Wireless Keyboard And Mouse Combo', 155, 24.99, 100, '1739697784_61y7o65wHvL._AC_SL1500_.jpg', '', '', '', '2025-02-15 13:10:06', NULL, 0),
(240, 'razer-deathadadder-essential-gaming-mouse', 'Razer DeathAdadder Essential Gaming Mouse', 155, 29.99, 80, '1739697831_8189uwDnMkL._AC_SL1500__V1i6tXRtpR.jpg', '', '', '', '2025-02-15 13:10:06', NULL, 0),
(241, 'nvidia-geforce-rtx-3080-graphics-card', 'NVIDIA GeForce RTX 3080 Graphics Card', 156, 799.99, 10, '1739697892_71UStULnUyS._AC_SL1500_1.jpg', '1739697892_71XgLgo9r9S._AC_SL1500_2.jpg', '1739697892_71BjgQuLG2S._AC_SL1500_3.jpg', '1739697892_61JtbHel13S._AC_SL1500_4.jpg', '2025-02-15 13:10:06', NULL, 0),
(242, 'amd-ryzen-9-5900x-processor', 'AMD Ryzen 9 5900X Processor', 156, 549.99, 15, '1739698141_618hq612iS._AC_SL1500_.jpg', '1739698141_71AKMKHsHL._AC_SL1500_.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(243, 'samsung-970-evo', 'Samsung 970 EVO 1TB NVMe SSD', 157, 129.99, 50, 'storage1.jpg', NULL, NULL, NULL, '2025-02-15 13:10:06', NULL, 0),
(244, 'seagate-barracuda-2tb-hdd', 'Seagate Barracuda 2TB HDD', 157, 64.99, 60, 'storage2.jpg', '1739862948_71fSzbp5FML._AC_SL1000_.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(245, 'apple-macbook-pro-16-inch-laptop', 'Apple MacBook Pro 16-inch Laptop', 158, 2399.99, 10, 'computer1.jpg', '1739863167_images.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(246, 'microsoft-surface-pro-9', 'Microsoft Surface Pro 9', 158, 999.99, 20, 'computer2.jpg', '1739863354_51rC0TeCnSL._SL1500_.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(247, 'targus-citygear-laptop-backpack', 'Targus CityGear Laptop Backpack', 159, 49.99, 70, 'laptopacc1.jpg', '1739863501_61QXYjZ6P0L._AC_SL1200_.jpg', '1739863501_61WiZRyv7yL._AC_SL1200_.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(248, 'anker-7-in-1-usb-c-hub', 'Anker 7-in-1 USB-C Hub', 159, 35.99, 85, 'laptopacc2.jpg', '1739863617_61KYtBCqyfL._AC_SL1500_.jpg', '1739863636_61-L8xbQ6kL._AC_SL1500_.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(249, 'dell-ultrasharp-27-4k-monitor', 'Dell UltraSharp 27 4K Monitor', 160, 699.99, 25, 'monitor1.jpg', '1739863767_41c14evXIL._AC_SL1200_.jpg', '1739863767_41pcK127vRL._AC_SL1200_.jpg', '1739863767_41yTE2iH09L._AC_SL1200_.jpg', '2025-02-15 13:10:06', NULL, 0),
(250, 'lg-27gn800-b-gaming-monitor', 'LG 27GN800-B Gaming Monitor', 160, 349.99, 30, 'monitor2.jpg', '1739864081_71whNUxcmzL._AC_SL1500_.jpg', '1739864081_91yRjRg0uUL._AC_SL1500_.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(251, 'philips-hue-white-and-color-ambiance-starter-kit', 'Philips Hue White And Color Ambiance Starter Kit', 162, 199.99, 40, 'lighting1.jpg', '1739864222_61VKVCJRQoL._AC_SL1400_.jpg', '1739864222_71vbbjtWa4L._AC_SL1500_.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(252, 'lytworx-smart-led-bulb-4-pack', 'Lytworx Smart LED Bulb, 4-Pack', 162, 29.99, 100, 'lighting2.jpg', '', '', '', '2025-02-15 13:10:06', NULL, 0),
(253, 'ring-stick-up-cam-battery-weather-resistant-outdoor-camera-live-view-color-night-vision-two-way-talk', 'Ring Stick Up Cam Battery | Weather-Resistant Outdoor Camera, Live View, Color Night Vision, Two-way Talk, Motion Alerts, Works With Alexa | White', 163, 99.99, 50, 'security1.jpg', '1739864493_61ktgGAXj-L._SL1000_.jpg', '1739864493_61YB1QUE1WL._SL1000_.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(254, 'arlo-pro-4-spotlight-camera-3-pack-wireless-security-2k-video-hdr-color-night-vision-2-way-audio-wir', 'Arlo Pro 4 Spotlight Camera - 3 Pack - Wireless Security, 2K Video & HDR, Color Night Vision, 2 Way Audio, Wire-Free, Direct To WiFi No Hub Needed, White - VMC4350P', 163, 199.99, 30, 'security2.jpg', '1739864731_71v4gaxl08L._AC_SL1500_.jpg', '1739864731_81JwCX944eL._AC_SL1500_.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(255, 'august-home-aug-sl05-m01-g01-august-wi-fi-4th-generation-smart-lock-matte-black', 'August Home AUG-SL05-M01-G01 August Wi-Fi, (4th Generation) Smart Lock, Matte Black', 164, 249.99, 25, 'lock1.jpg', '1739864948_61kljVhwy9L._AC_SL1500_.jpg', '1739864948_615dXny89YL._AC_SL1500_.jpg', '', '2025-02-15 13:10:06', NULL, 0),
(256, 'echo-dot-4th-generation-smart-speaker-with-clock-and-alexa-arabic-or-english-blue', 'Echo Dot (4th Generation) | Smart Speaker With Clock And Alexa (Arabic Or English) | Blue', 165, 49.99, 150, 'voice1.jpg', '1739865267_61EgrBbiM8L._AC_SL1000_.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(258, 'mobil-1-advanced-full-synthetic-motor-oil-5w-30-5-quart', 'Mobil 1 Advanced Full Synthetic Motor Oil 5W-30, 5 Quart', 168, 34.99, 100, 'oil1.jpg', '1739865398_71wjlNexWwL._AC_SL1500_.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(259, 'pioneer-dmh-1770nex-68inch-digital-multimediaapple-carplay-androidbuiltin-bluetoothwith-1-bullet-sty', 'PIONEER DMH-1770NEX 6.8inch Digital Multimedia,Apple CarPlay & Android,Builtin Bluetooth,with (1) Bullet Style Back-up Camera', 169, 299.99, 25, 'carelectronics1.jpg', '', '', '', '2025-02-15 13:10:06', NULL, 0),
(260, 'michelin-defender-ltx-ms-all-season-car-tire-for-light-trucks-suvs-and-crossovers-25555r18xl-109h', 'MICHELIN Defender LTX M/S All Season Car Tire For Light Trucks, SUVs And Crossovers - 255/55R18/XL 109H', 170, 189.99, 40, 'tire1.jpg', '1739865927_713rkY5FuSL._AC_SL1500_.jpg', '', '', '2025-02-15 13:10:06', NULL, 0),
(261, 'maybelline-super-stay-matte-ink-liquid-lipstick-makeup-long-lasting-high-impact-color-up-to-16h-wear', 'Maybelline Super Stay Matte Ink Liquid Lipstick Makeup, Long Lasting High Impact Color, Up To 16H Wear, Lover, Mauve Neutral, 1 Count', 172, 9.99, 300, 'makeup1.jpg', '1739865728_818lVAsYmqL._SL1500_.jpg', '', '', '2025-02-15 13:10:06', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slag` (`slag`),
  ADD KEY `date` (`date`),
  ADD KEY `quantity` (`quantity`),
  ADD KEY `price` (`price`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `description` (`description`),
  ADD KEY `is_deleted` (`is_deleted`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
