-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 30, 2025 at 07:39 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebook_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `created_at`) VALUES
(4, 'fiction', 'cat_683750cc4c3d90.39527273.jpg', '2025-05-28 18:07:08'),
(5, 'romance', 'cat_683750e5b06120.87385151.jpg', '2025-05-28 18:07:33'),
(6, 'crime', 'cat_683750ec57a598.96860301.jpg', '2025-05-28 18:07:40'),
(7, 'classics', 'cat_68375131877470.58107791.jpg', '2025-05-28 18:08:49'),
(8, 'comedy', 'cat_683751777ff510.36928202.jpg', '2025-05-28 18:09:59'),
(9, 'history', 'cat_6839edd3b794c6.27758354.png', '2025-05-30 17:41:39'),
(11, 'frensh', 'cat_6839f1a2769a65.85903219.png', '2025-05-30 17:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `title`, `category`, `description`, `price`, `cover_image`, `file_path`, `file_name`, `created_at`) VALUES
(19, 'A Confederate surgeon\'s letters to his wife by Spencer Glasgow Welch', 'history', '\"A Confederate Surgeon’s Letters to His Wife\" by Spencer Glasgow Welch is a compelling collection of personal letters written by Dr. Welch, a Confederate army surgeon, to his wife during the American Civil War. Spanning from 1861 to 1865, these letters offer a vivid and intimate glimpse into the daily life, struggles, and moral reflections of a Southern doctor serving on the front lines.\r\n\r\nWith a tone that is both affectionate and honest, Welch recounts his experiences in field hospitals, the conditions of wounded soldiers, and his observations on military life, all while expressing deep love and longing for his family. More than just a war diary, the book captures the emotional toll of conflict, providing insight into the human side of one of America’s most turbulent periods.\r\n\r\nIdeal for readers interested in Civil War history, military medicine, or personal wartime narratives, this volume stands as a valuable firsthand account of duty, sacrifice, and the enduring bond of marriage in a time of national crisis.', 25.00, 'pg76190.cover.medium.jpg', '0', NULL, '2025-05-30 17:50:23'),
(20, 'Benjamine : roman by Jean Aicard', 'frensh', '\"Benjamine : roman\" by Jean Aicard is a poignant French novel that delves into themes of love, identity, and the complexities of womanhood in a changing society. Written by the celebrated 19th-century poet and novelist Jean Aicard, this richly emotional story follows the life of Benjamine, a young woman navigating personal desires and societal expectations in provincial France.\r\n\r\nThrough elegant prose and sensitive character development, Aicard paints a vivid portrait of a heroine torn between duty and independence, tradition and transformation. Set against the backdrop of rural life, the novel explores the moral and emotional struggles faced by women seeking their place in a patriarchal world.\r\n\r\nIdeal for lovers of classic French literature, \"Benjamine\" is a heartfelt exploration of feminine strength, resilience, and the human spirit, written by one of Provence’s most beloved literary voices.', 20.00, 'cover.jpg', '0', NULL, '2025-05-30 17:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `ebook_id` int NOT NULL,
  `paypal_order_id` varchar(64) NOT NULL,
  `status` enum('pending','paid','rejected') NOT NULL DEFAULT 'pending',
  `email` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `customer_email` varchar(255) DEFAULT NULL,
  `payment_status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `amount_paid` decimal(10,2) DEFAULT '0.00',
  `payment_method` varchar(50) DEFAULT 'paypal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `ebook_id`, `paypal_order_id`, `status`, `email`, `price`, `customer_email`, `payment_status`, `created_at`, `amount_paid`, `payment_method`) VALUES
(1, 3, '31G81601754787223', 'rejected', NULL, 0.00, '', 'rejected', '2025-05-27 19:40:00', 0.00, 'paypal'),
(2, 3, '5GS023402N179631A', 'rejected', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:18:16', 0.00, 'paypal'),
(3, 3, '6V4937462J903452A', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:21:34', 0.00, 'paypal'),
(4, 3, '55078617JY011451W', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:29:26', 0.00, 'paypal'),
(5, 3, '82424759H62299803', 'rejected', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:36:15', 0.00, 'paypal'),
(6, 3, '24760797B25858245', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:39:40', 0.00, 'paypal'),
(7, 3, '1GV61809RP976643T', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'approved', '2025-05-27 20:49:42', 0.00, 'paypal'),
(8, 3, '6K516693HA4847142', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:51:43', 0.00, 'paypal'),
(9, 3, '88Y81546VB118964Y', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:55:08', 0.00, 'paypal'),
(10, 3, '3G900157JG837612W', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 20:58:06', 0.00, 'paypal'),
(11, 3, '4B79476829073435J', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 21:02:14', 0.00, 'paypal'),
(12, 3, '56A509585X2189448', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'pending', '2025-05-27 21:05:57', 0.00, 'paypal'),
(13, 3, '3DD15453JW867064R', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'pending', '2025-05-27 21:09:50', 0.00, 'paypal'),
(14, 3, '8DX17734WB5173425', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'approved', '2025-05-27 21:10:08', 0.00, 'paypal'),
(15, 4, '0BY99889WF380280H', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 21:11:27', 0.00, 'paypal'),
(16, 4, '2VG76663C7621321U', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 21:12:49', 0.00, 'paypal'),
(17, 4, '4WU68896WK593272M', 'pending', NULL, 60.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-27 21:16:50', 60.00, 'card'),
(18, 4, '86X22419PM2021459', 'pending', NULL, 60.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-27 21:22:36', 60.00, 'card'),
(19, 4, '1S472644L0736860G', 'pending', NULL, 60.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-27 21:27:56', 60.00, 'card'),
(20, 4, '5S1481710K172484G', 'pending', NULL, 0.00, 'lammminnne2004@gmail.com', 'rejected', '2025-05-27 21:32:42', 0.00, 'paypal'),
(21, 4, '77C95034P1522590M', 'pending', NULL, 0.00, NULL, 'rejected', '2025-05-27 21:34:04', 0.00, 'paypal'),
(22, 4, '0MC18826AA8358637', 'pending', NULL, 60.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-27 21:34:12', 60.00, 'card'),
(23, 4, '39985442N8501332H', 'pending', NULL, 60.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-27 21:38:24', 60.00, 'card'),
(24, 4, '3MK96947FV352511C', 'pending', NULL, 60.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-27 21:39:29', 60.00, 'card'),
(25, 4, '0HY39482DJ429035L', 'pending', NULL, 60.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-28 16:43:06', 60.00, 'card'),
(26, 4, '50V51859CV8770352', 'pending', NULL, 0.00, NULL, 'pending', '2025-05-28 17:38:21', 0.00, 'paypal'),
(27, 16, '70D100128U466474U', 'pending', NULL, 0.00, NULL, 'rejected', '2025-05-28 19:16:03', 0.00, 'paypal'),
(28, 16, '45U6218322116001Y', 'pending', NULL, 50.00, 'lammminnne2004@gmail.com', 'paid', '2025-05-28 19:19:32', 50.00, 'card'),
(29, 18, '00G34157PA2508536', 'pending', NULL, 0.00, NULL, 'pending', '2025-05-29 18:03:12', 0.00, 'paypal'),
(30, 18, '6L77778292421623X', 'pending', NULL, 0.00, NULL, 'pending', '2025-05-29 18:03:29', 0.00, 'paypal'),
(31, 18, '1P309426J8037251E', 'pending', NULL, 0.00, NULL, 'pending', '2025-05-29 18:03:59', 0.00, 'paypal'),
(32, 18, '9Y335363DW607000M', 'pending', NULL, 0.00, NULL, 'pending', '2025-05-29 19:12:56', 0.00, 'paypal'),
(33, 19, '3JW28064FF4919529', 'pending', NULL, 0.00, NULL, 'approved', '2025-05-30 19:00:53', 0.00, 'paypal');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_requests`
--

CREATE TABLE `password_reset_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `created_at`, `profile_pic`) VALUES
(6, NULL, 'maxie', 'lammminnne2004@gmail.com', '$2y$10$7gp14xuChmsoT5RFMSh2v.7I/ittihCQatYWU/9545HKWaWc63d2y', '2025-05-29 20:22:14', 'uploads/pfp_6.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  ADD CONSTRAINT `password_reset_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
