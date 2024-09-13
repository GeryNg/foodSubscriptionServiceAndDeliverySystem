-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2024 at 07:09 PM
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
-- Database: `makan_apa`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(11) UNSIGNED NOT NULL,
  `plan_id` int(11) UNSIGNED NOT NULL,
  `addon_name` varchar(255) NOT NULL,
  `addon_price` double NOT NULL,
  `addon_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `plan_id`, `addon_name`, `addon_price`, `addon_image`) VALUES
(25, 3, 'brown rice', 7, NULL),
(26, 3, 'soft-drink', 4, NULL),
(35, 2, 'rice', 6, '../addon_images/addon_66e3dbb2121a60.67292572.jpg'),
(36, 2, 'soft-drink', 3, '../addon_images/addon_66e3dbb212c946.91696126.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `Cust_ID` varchar(11) NOT NULL,
  `line1` varchar(255) NOT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `Cust_ID`, `line1`, `line2`, `city`, `state`, `postal_code`, `country`) VALUES
(1, 'C00001', '18, jalan setia impian', ' Taman setia', 'shah alam', 'selangor', '40170', 'malaysia');

-- --------------------------------------------------------

--
-- Table structure for table `address_book`
--

CREATE TABLE `address_book` (
  `postcode` varchar(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address_book`
--

INSERT INTO `address_book` (`postcode`, `city`, `state`, `country`) VALUES
('40000', 'Shah Alam', 'Selangor', 'Malaysia'),
('40100', 'Shah Alam', 'Selangor', 'Malaysia'),
('40150', 'Shah Alam', 'Selangor', 'Malaysia'),
('40160', 'Shah Alam', 'Selangor', 'Malaysia'),
('40170', 'Shah Alam', 'Selangor', 'Malaysia'),
('40200', 'Shah Alam', 'Selangor', 'Malaysia'),
('40400', 'Shah Alam', 'Selangor', 'Malaysia'),
('40450', 'Shah Alam', 'Selangor', 'Malaysia'),
('40460', 'Shah Alam', 'Selangor', 'Malaysia'),
('40470', 'Shah Alam', 'Selangor', 'Malaysia'),
('40500', 'Shah Alam', 'Selangor', 'Malaysia'),
('41000', 'Klang', 'Selangor', 'Malaysia'),
('41050', 'Klang', 'Selangor', 'Malaysia'),
('41100', 'Klang', 'Selangor', 'Malaysia'),
('41150', 'Klang', 'Selangor', 'Malaysia'),
('41200', 'Klang', 'Selangor', 'Malaysia'),
('41250', 'Klang', 'Selangor', 'Malaysia'),
('41300', 'Klang', 'Selangor', 'Malaysia'),
('41400', 'Klang', 'Selangor', 'Malaysia'),
('42000', 'Pelabuhan Klang', 'Selangor', 'Malaysia'),
('42100', 'Klang', 'Selangor', 'Malaysia'),
('42200', 'Kapar', 'Selangor', 'Malaysia'),
('42300', 'Bandar Puncak Alam', 'Selangor', 'Malaysia'),
('42500', 'Telok Panglima Garang', 'Selangor', 'Malaysia'),
('42600', 'Jenjarom', 'Selangor', 'Malaysia'),
('42700', 'Banting', 'Selangor', 'Malaysia'),
('43000', 'Kajang', 'Selangor', 'Malaysia'),
('43100', 'Hulu Langat', 'Selangor', 'Malaysia'),
('43200', 'Cheras', 'Selangor', 'Malaysia'),
('43300', 'Seri Kembangan', 'Selangor', 'Malaysia'),
('43500', 'Semenyih', 'Selangor', 'Malaysia'),
('43600', 'Bandar Baru Bangi', 'Selangor', 'Malaysia'),
('43650', 'Bandar Baru Bangi', 'Selangor', 'Malaysia'),
('46000', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('46050', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('46100', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('46200', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('46300', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('46350', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('46400', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47100', 'Puchong', 'Selangor', 'Malaysia'),
('47110', 'Puchong', 'Selangor', 'Malaysia'),
('47120', 'Puchong', 'Selangor', 'Malaysia'),
('47130', 'Puchong', 'Selangor', 'Malaysia'),
('47140', 'Puchong', 'Selangor', 'Malaysia'),
('47150', 'Puchong', 'Selangor', 'Malaysia'),
('47160', 'Puchong', 'Selangor', 'Malaysia'),
('47170', 'Puchong', 'Selangor', 'Malaysia'),
('47180', 'Puchong', 'Selangor', 'Malaysia'),
('47190', 'Puchong', 'Selangor', 'Malaysia'),
('47200', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47300', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47301', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47400', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47410', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47500', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47600', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47610', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47620', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47630', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47640', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47650', 'Subang Jaya', 'Selangor', 'Malaysia'),
('47800', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47810', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47820', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('47830', 'Petaling Jaya', 'Selangor', 'Malaysia'),
('48000', 'Rawang', 'Selangor', 'Malaysia'),
('48010', 'Rawang', 'Selangor', 'Malaysia'),
('48020', 'Rawang', 'Selangor', 'Malaysia'),
('48050', 'Rawang', 'Selangor', 'Malaysia'),
('48300', 'Rawang', 'Selangor', 'Malaysia'),
('50000', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50100', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50200', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50300', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50400', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50500', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50600', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50700', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50800', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('50900', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51000', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51100', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51200', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51300', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51400', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51500', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51600', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51700', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51800', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('51900', 'Kuala Lumpur', 'Wilayah Persekutuan', 'Malaysia'),
('63000', 'Cyberjaya', 'Selangor', 'Malaysia'),
('63300', 'Cyberjaya', 'Selangor', 'Malaysia'),
('68000', 'Ampang', 'Selangor', 'Malaysia'),
('68100', 'Batu Caves', 'Selangor', 'Malaysia');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `user_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Cust_ID` varchar(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Gender` enum('M','F','O') DEFAULT NULL,
  `Phone_num` varchar(20) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `address_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Cust_ID`, `Name`, `Gender`, `Phone_num`, `user_id`, `address_id`) VALUES
('C00001', 'testcustomer1.0', 'M', '01111111113', 'U00002', '');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` varchar(20) NOT NULL,
  `order_id` varchar(11) NOT NULL,
  `seller_id` varchar(11) NOT NULL,
  `address_id` varchar(11) NOT NULL,
  `cust_id` varchar(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `status` enum('order accepted','food preparing','on delivery','done delivery') NOT NULL DEFAULT 'order accepted',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`delivery_id`, `order_id`, `seller_id`, `address_id`, `cust_id`, `delivery_date`, `status`, `latitude`, `longitude`) VALUES
('202409120001', '1', 'S00001', '1', 'C00001', '2024-09-12', 'food preparing', NULL, NULL),
('202409130001', '1', 'S00001', '1', 'C00001', '2024-09-13', 'order accepted', NULL, NULL),
('202409130002', '2', 'S00001', '1', 'C00001', '2024-09-13', 'order accepted', NULL, NULL),
('202409140001', '1', 'S00001', '1', 'C00001', '2024-09-14', 'order accepted', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `Feedback_ID` int(10) NOT NULL,
  `Cust_ID` varchar(10) NOT NULL,
  `Order_ID` varchar(20) DEFAULT NULL,
  `Comment` varchar(255) NOT NULL,
  `Rating` enum('1','2','3','4','5') NOT NULL,
  `FeedbackDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`Feedback_ID`, `Cust_ID`, `Order_ID`, `Comment`, `Rating`, `FeedbackDate`) VALUES
(1, '0', '1', 'good ah', '4', '2024-09-13'),
(2, '0', '2', 'good', '3', '2024-09-13');

-- --------------------------------------------------------

--
-- Table structure for table `link_requests`
--

CREATE TABLE `link_requests` (
  `id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `seller_id` varchar(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `linked_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` varchar(11) NOT NULL,
  `outgoing_msg_id` varchar(11) NOT NULL,
  `msg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`) VALUES
(4, 'S00001', 'U00002', 'test1'),
(8, 'U00002', 'S00001', 'test2'),
(16, 'U00002', 'S00001', 'test3'),
(17, 'S00001', 'U00002', 'test4'),
(18, 'S00001', 'U00002', 'test5');

-- --------------------------------------------------------

--
-- Table structure for table `order_addon`
--

CREATE TABLE `order_addon` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `addon_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_addon`
--

INSERT INTO `order_addon` (`id`, `order_id`, `addon_id`, `addon_quantity`) VALUES
(1, 2, 35, 1),
(2, 2, 36, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_cust`
--

CREATE TABLE `order_cust` (
  `Order_ID` int(11) NOT NULL,
  `OrderDate` date NOT NULL,
  `GrandTotal` decimal(8,2) NOT NULL,
  `Status` enum('Active','Finished','Cancelled','Inactive') NOT NULL,
  `Meal` enum('Lunch','Dinner') NOT NULL DEFAULT 'Lunch',
  `Duration` int(30) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Cust_ID` varchar(11) NOT NULL,
  `Plan_ID` varchar(11) NOT NULL,
  `delivery_address_id` int(11) DEFAULT NULL,
  `instructions` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_cust`
--

INSERT INTO `order_cust` (`Order_ID`, `OrderDate`, `GrandTotal`, `Status`, `Meal`, `Duration`, `StartDate`, `EndDate`, `Quantity`, `Cust_ID`, `Plan_ID`, `delivery_address_id`, `instructions`) VALUES
(1, '2024-09-11', 527.78, 'Active', 'Lunch', 22, '2024-09-12', '2024-10-04', 1, 'C00001', '1', 1, 'no'),
(2, '2024-09-13', 69.00, 'Finished', 'Lunch', 1, '2024-09-13', '2024-09-13', 1, 'C00001', '2', 1, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(10) NOT NULL,
  `PaymentAmount` decimal(8,2) NOT NULL,
  `PaymentDate` date NOT NULL,
  `Order_ID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_ID`, `PaymentAmount`, `PaymentDate`, `Order_ID`) VALUES
(1, 527.78, '2024-09-11', '1'),
(2, 69.00, '2024-09-13', '2');

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `id` int(6) UNSIGNED NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `section` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `seller_id` varchar(11) NOT NULL,
  `image_urls` varchar(255) NOT NULL,
  `has_addons` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`id`, `plan_name`, `description`, `price`, `date_from`, `date_to`, `section`, `status`, `seller_id`, `image_urls`, `has_addons`) VALUES
(1, 'seller1.restaurant1.plan1', 'seller1.restaurant1.plan1', 23.99, '2024-09-09', '2024-09-16', 'Lunch,Dinner', 'active', 'S00001', '../plan/66df0db0c14d80.56367489.jpeg,../plan/66df0db0c16915.57106371.jpg,../plan/66df0db0c18229.81265907.jpg', 0),
(2, 'seller1.restaurant1.plan2', 'seller1.restaurant1.plan2', 69, '2024-09-13', '2024-10-12', 'Lunch,Dinner', 'active', 'S00001', '../plan/66e3a1f36cf3e3.13939582.jpg,../plan/66e3a1f36d1374.18881589.jpg,../plan/66e3a1f36d3096.16568722.jpg,../plan/66e3a1f36d4504.01494704.jpg,../plan/66e3a1f3730956.76109283.jpg', 1),
(3, 'seller1.restaurant1.plan3.0', 'seller1.restaurant1.plan3', 42, '2024-09-13', '2024-10-09', 'Dinner', 'active', 'S00001', '../plan/66e3a6716870a7.33003355.jpeg,../plan/66e3a671688af1.92716723.jpg,../plan/66e3a67168a328.39936776.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `id` varchar(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `postcode` int(7) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `bank_company` varchar(255) DEFAULT NULL,
  `bank_account` varchar(20) DEFAULT NULL,
  `access` enum('verify','pending','inactive','rejected','linked','unknown') DEFAULT 'inactive',
  `user_id` varchar(11) NOT NULL,
  `image_urls` varchar(255) NOT NULL,
  `requests_open` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`id`, `name`, `profile_pic`, `detail`, `contact_number`, `address`, `postcode`, `city`, `state`, `bank_company`, `bank_account`, `access`, `user_id`, `image_urls`, `requests_open`) VALUES
('S00001', 'testseller1.restaurant1', '../seller_profile_pic/66df0b63ec63a4.58526112.jpeg', 'testseller1.restaurant1', '0196225912', '18,jalan setia impian', 40170, 'Shah Alam', 'Selangor', 'HSBC Bank Malaysia', '123456754312', 'verify', 'U00001', '../document/66df0b63ec7f68.66180784.png,../document/66df0b63ec9227.24299956.png,../document/66df0b63ecab03.96275883.png,../document/66df0b63ecc2e9.89826429.png', 0),
('S00002', 'testseller1.restaurant2', '../seller_profile_pic/66df0d41745c93.99894103.jpg', 'testseller1.restaurant2', '0196225912', '18,jalan setia impian', 40100, 'Shah Alam', 'Selangor', 'Public Islamic Bank', '123456754312', 'verify', 'U00001', '../document/66df0d41747374.11572228.png,../document/66df0d417485a2.56885246.png,../document/66df0d41749802.93982248.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `seller_location`
--

CREATE TABLE `seller_location` (
  `seller_id` varchar(11) NOT NULL,
  `fingerprint` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `status` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_location`
--

INSERT INTO `seller_location` (`seller_id`, `fingerprint`, `latitude`, `longitude`, `status`, `timestamp`) VALUES
('S00001', '', 3.09749017, 101.45375450, 'close', '2024-09-12 16:31:55');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amountProcessed` decimal(10,2) NOT NULL,
  `transactionType` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `seller_id` varchar(11) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `status`, `amount`, `amountProcessed`, `transactionType`, `datetime`, `seller_id`) VALUES
(1, 'Successful', 200.00, 12.00, 'Withdraw', '2024-09-10 00:00:00', 'S00001'),
(2, 'Pending', 100.00, 6.00, 'Deposit', '2024-09-10 00:00:00', 'S00001'),
(3, 'Successful', 300.00, 18.00, 'Deposit', '2024-09-10 00:00:00', 'S00001'),
(4, 'Pending', 400.00, 24.00, 'Withdraw', '2024-09-10 00:00:00', 'S00001'),
(5, 'Successful', 150.00, 9.00, 'Withdraw', '2024-09-10 18:42:39', 'S00001'),
(6, 'Pending', 20.00, 18.80, 'Withdraw', '2024-09-10 19:18:04', 'S00001'),
(7, 'Successful', 223.00, 209.62, 'Withdraw', '2024-09-10 19:20:19', 'S00001'),
(8, 'Successful', 12.00, 11.28, 'Withdraw', '2024-09-10 19:21:23', 'S00001'),
(9, 'Pending', 11.00, 10.34, 'Withdraw', '2024-09-10 19:21:47', 'S00001'),
(10, 'Pending', 6.00, 5.64, 'Withdraw', '2024-09-10 19:22:04', 'S00001'),
(11, 'Pending', 7.00, 6.58, 'Withdraw', '2024-09-10 19:22:28', 'S00001'),
(12, 'Pending', 8.00, 7.52, 'Withdraw', '2024-09-10 19:25:40', 'S00001'),
(13, 'Pending', 9.00, 8.46, 'Withdraw', '2024-09-10 19:26:50', 'S00001'),
(14, 'Pending', 6.00, 5.64, 'Withdraw', '2024-09-10 19:54:53', 'S00001');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','seller','admin') NOT NULL,
  `join_date` datetime DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT NULL COMMENT 'Offline now, Online',
  `avatar` varchar(255) NOT NULL DEFAULT '../uploads/default.jpg',
  `security_question1` varchar(255) NOT NULL,
  `security_answer1` varchar(255) NOT NULL,
  `security_question2` varchar(255) NOT NULL,
  `security_answer2` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `join_date`, `status`, `avatar`, `security_question1`, `security_answer1`, `security_question2`, `security_answer2`, `reset_token_hash`, `reset_token_expires_at`) VALUES
('U00001', 'testseller1', 'geryng0102@gmail.com', '$2y$10$yD4rMw/kvekHFM6Pl1w7JOlBvOEnfhZTZdYIR0NCrPlkJoZjS1ww.', 'seller', '2024-09-09 22:30:57', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'lihao', 'What is your mother\'s maiden name?', 'lihao', NULL, NULL),
('U00002', 'testcustomer1', 'ngyx-wm22@student.tarc.edu.my', '$2y$10$9Sr6jk6D5YRLlWx3ieDHuugSKg5IrKuL0EZGHzwvYhJa8YbjX9sKm', 'customer', '2024-09-09 23:15:48', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'lihao', 'What is your mother\'s maiden name?', 'lihao', NULL, NULL),
('U00003', 'testadmin1', 'hello180102@gmail.com', '$2y$10$2uKhct036G31ubRuqEfaPeSX9LpK63cZedlHCpREt.7zD/16AKNlW', 'admin', '2024-09-10 21:50:33', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'lihao', 'What is your mother\'s maiden name?', 'lihao', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` varchar(11) CHARACTER SET armscii8 COLLATE armscii8_general_ci NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `revenue` decimal(10,2) NOT NULL,
  `seller_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `balance`, `revenue`, `seller_id`) VALUES
('', 1488.38, 3000.00, 'S00001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `Cust_ID` (`Cust_ID`);

--
-- Indexes for table `address_book`
--
ALTER TABLE `address_book`
  ADD PRIMARY KEY (`postcode`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Cust_ID`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `customer_fk_user_id` (`user_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`Feedback_ID`),
  ADD KEY `Order_ID` (`Order_ID`);

--
-- Indexes for table `link_requests`
--
ALTER TABLE `link_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `order_addon`
--
ALTER TABLE `order_addon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addon_id` (`addon_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_cust`
--
ALTER TABLE `order_cust`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `Cust_ID` (`Cust_ID`),
  ADD KEY `Plan_ID` (`Plan_ID`),
  ADD KEY `fk_order_address` (`delivery_address_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `Order_ID` (`Order_ID`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postcode` (`postcode`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `seller_location`
--
ALTER TABLE `seller_location`
  ADD PRIMARY KEY (`seller_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `Feedback_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `link_requests`
--
ALTER TABLE `link_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_addon`
--
ALTER TABLE `order_addon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_cust`
--
ALTER TABLE `order_cust`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_cust`
--
ALTER TABLE `order_cust`
  ADD CONSTRAINT `fk_order_address` FOREIGN KEY (`delivery_address_id`) REFERENCES `address` (`address_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
