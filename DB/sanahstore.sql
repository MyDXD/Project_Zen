-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 12, 2024 at 09:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sanahstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_img` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_type`, `product_detail`, `product_img`) VALUES
(19, 'Leather Long Wallet', '75.00', 'ตากแห้ง', 'Hand-stitched wallet made from premium leather.', 'product_pic/ทาโร่.jpg'),
(20, 'Classic White T-Shirt', '20.00', 'อบกรอบ', 'A classic white t-shirt made from 100% cotton.', 'product_pic/น้ำเก.jpg'),
(21, 'Running Shoes', '120.00', 'ตากแห้ง', 'Lightweight running shoes with breathable fabric.', 'product_pic/6706c03899af9Screenshot 2024-04-22 173346.png'),
(22, 'กาแฟดำ', '150.00', 'ชา/กาแฟ', 'แดกเยอะก็จะนอนไม่หลับ', 'product_pic/6706c158edb70228b0d0eddf640c2ab83aad6b14887d3.jpg'),
(30, 'ขนม', '200', 'ตากแห้ง', 'อร่อย', 'product_pic/6706bb3428de6Screenshot 2024-04-22 173346.png'),
(31, 'ขนมจีบ', '40', 'ขนมนำเข้า', 'บูดแล้ว', 'product_pic/6706caaf3c7305c98ab2aba064e70b8d2f2504f4c5a19.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE `userdata` (
  `user_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `title_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `bank_account_id` int NOT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`user_id`, `email`, `password`, `first_name`, `last_name`, `title_name`, `address`, `phone_number`, `bank_account_id`, `role`) VALUES
(27, 'niraname@gmail.com', '123', 'นิรนาม', 'ไม่ทราบชื่อ', '', '', '', 0, 'user'),
(28, 'user@user.com', '1234', 'user', 'user', 'tseestes', 'sdfsdfsdfsd', '0946645521', 123132132, 'user'),
(29, 'admin@admin.com', '1234', 'admin', 'admin', 'asdsadsa', 'dsadas', '0845451212', 1212, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `userdata`
--
ALTER TABLE `userdata`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
