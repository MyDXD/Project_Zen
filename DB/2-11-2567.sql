-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sanahstore
CREATE DATABASE IF NOT EXISTS `sanahstore` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sanahstore`;

-- Dumping structure for table sanahstore.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `added_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userdata` (`user_id`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sanahstore.cart: ~0 rows (approximately)

-- Dumping structure for table sanahstore.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(10,2) NOT NULL,
  `order_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `delivery_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_slip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userdata` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sanahstore.orders: ~2 rows (approximately)
INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_price`, `order_status`, `delivery_address`, `phone_number`, `payment_method`, `transaction_id`, `payment_slip`) VALUES
	(145, 32, '2024-10-28 13:47:01', 675.00, 'waiting', 'ไม่บอกเขิน', NULL, NULL, NULL, 'payment_slip/671f9c3aef700Screenshot 2024-10-23 211515.png'),
	(146, 32, '2024-10-28 14:15:53', 2750.00, 'waiting', 'ไม่บอกเขิน', NULL, NULL, NULL, 'payment_slip/671f9cafa6d33w1.png');

-- Dumping structure for table sanahstore.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sanahstore.order_items: ~4 rows (approximately)
INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
	(18, 145, 19, 3, 75.00),
	(19, 145, 22, 3, 150.00),
	(20, 146, 19, 10, 75.00),
	(21, 146, 33, 4, 500.00);

-- Dumping structure for table sanahstore.products
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_img` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sanahstore.products: ~7 rows (approximately)
INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_type`, `product_detail`, `product_img`, `stock`) VALUES
	(19, 'Leather Long Wallet', '75.00', 'ตากแห้ง', 'Hand-stitched wallet made from premium leather.', 'product_pic/ทาโร่.jpg', 52),
	(20, 'Classic White T-Shirt', '20.00', 'อบกรอบ', 'A classic white t-shirt made from 100% cotton.', 'product_pic/น้ำเก.jpg', 37),
	(21, 'Running Shoes', '120.00', 'ตากแห้ง', 'Lightweight running shoes with breathable fabric.', 'product_pic/6706c03899af9Screenshot 2024-04-22 173346.png', 47),
	(22, 'กาแฟดำ', '150.00', 'ชา/กาแฟ', 'แดกเยอะก็จะนอนไม่หลับ', 'product_pic/6706c158edb70228b0d0eddf640c2ab83aad6b14887d3.jpg', 58),
	(30, 'ขนม', '200', 'ตากแห้ง', 'อร่อย', 'product_pic/6706bb3428de6Screenshot 2024-04-22 173346.png', 99),
	(31, 'ขนมจีบ', '40', 'ขนมนำเข้า', 'บูดแล้ว', 'product_pic/6706caaf3c7305c98ab2aba064e70b8d2f2504f4c5a19.jpg', 99),
	(33, 'test ครับน้อง', '500', 'อบกรอบ', 'test ครับน้องtest ครับน้องtest ครับน้อง', 'product_pic/670e7d4a6e1741.jpg', 6),
	(34, 'ซิกเนเจอร์ เฮอร์บัล รีเฟรช', '80', 'ชา/กาแฟ', 'กาแฟสเปเชียลตี้ที่ให้ความ เปรี้ยว ซ่า สดชื่น สไตล์ไทย ด้วยความหอมจากของกลิ่นสมุนไพรและเปปเปอร์มิ้นต์', 'product_pic/6718f1b1ef63229a70607ae2e4679845754b7e2133c06.jpg', 50);

-- Dumping structure for table sanahstore.userdata
CREATE TABLE IF NOT EXISTS `userdata` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `title_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table sanahstore.userdata: ~3 rows (approximately)
INSERT INTO `userdata` (`user_id`, `email`, `password`, `first_name`, `last_name`, `title_name`, `address`, `phone_number`, `role`) VALUES
	(27, 'niraname@gmail.com', '123', 'นิรนาม', 'ไม่ทราบชื่อ', '', '', '', 'user'),
	(28, 'user@user.com', '123456', 'userFirst', 'userLastname', 'tseestes', '284 O\'Reilly Fords, Port Marcellfurt, ND 20587', '0946645521', 'user'),
	(29, 'admin@admin.com', '1234', 'admin', 'admin', 'asdsadsa', 'Suite 549 61706 Wolff Pass, Laruechester, AL 18413-1354', '0974412234', 'admin'),
	(32, 'dxd@dxd.com', '1234', 'D', 'XD', 'title_name', 'ไม่บอกเขิน', '0984445512', 'user');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
