-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 05:45 AM
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
(45, 4, 'White Rice', 2.5, '../addon_images/addon_66ee381857bf21.26205509.jpg'),
(46, 4, 'Soft Drink', 2, '../addon_images/addon_66ee3818584773.80044182.jpg'),
(47, 6, 'Rice', 2.5, '../addon_images/addon_66ee39456333b8.26114688.jpg'),
(50, 10, 'White Rice', 2.5, '../addon_images/addon_66ee3a7c989b70.78165208.jpg'),
(51, 9, 'White Rice', 2.5, '../addon_images/addon_66ee3a2c5e7c97.53318925.jpg'),
(52, 9, 'Soft Drink', 2, '../addon_images/addon_66ee3a2c5ebf08.49522927.jpg'),
(53, 11, 'White rice', 2, '../addon_images/addon_66ee69b49f1511.96444275.jpg'),
(54, 12, 'white rice', 2, '../addon_images/addon_66ee6a27abb6b4.22167262.jpg'),
(57, 13, 'White rice', 2, '../addon_images/addon_66ee6a7fce5399.41643677.jpg'),
(58, 13, 'Soft Drink', 2, '../addon_images/addon_66ee6a7fce8853.40410646.jpg');

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
  `country` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `Cust_ID`, `line1`, `line2`, `city`, `state`, `postal_code`, `country`, `latitude`, `longitude`) VALUES
(2, 'C00001', 'no, 18 , jalan setia imapian', 'taman setia', 'Shah Alam', 'Selangor', '40170', 'Malaysia', NULL, NULL),
(3, 'C00002', '70, jalan beruang, Kawasan rumah hijau', '', 'Taiping', 'Perak', '34000', 'Malaysia', NULL, NULL),
(4, 'C00004', 'addressline1', 'addressline2', 'taiping', 'perak', '40000', 'Malaysia', NULL, NULL),
(5, 'C00005', 'address line 1', 'address line 2', 'puchong ', 'selangor ', '47120', 'malaysia', NULL, NULL),
(7, 'C00001', '98321', 'Jalan Setia Impian U13/6, Setia Alam, Shah Alam, Selangor, Malaysia', 'Shah Alam', 'Selangor', '40170', 'Malaysia', 3.10473150, 101.44760730);

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
('C00001', 'Darren goh', 'M', '012-321 6728', 'U00001', ''),
('C00002', 'Yeoh Yao Wen', 'M', '012-625 8236', 'U00002', ''),
('C00003', 'Song Mern Shen', 'M', '012-371 0928', 'U00003', ''),
('C00004', 'new customer  sa', 'M', '0183895447', 'U00010', ''),
('C00005', 'new customer puchong ', 'M', '0183895447', 'U00011', '');

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
('202409210004', '4', 'S00001', '2', 'C00001', '2024-09-21', 'on delivery', 0.00000000, 0.00000000),
('202409210006', '6', 'S00001', '2', 'C00001', '2024-09-21', 'order accepted', NULL, NULL),
('202409210008', '8', 'S00006', '3', 'C00002', '2024-09-21', 'order accepted', NULL, NULL),
('202409210009', '9', 'S00001', '3', 'C00002', '2024-09-21', 'done delivery', 3.21574586, 101.72624509),
('202409210010', '10', 'S00001', '2', 'C00001', '2024-09-21', 'done delivery', 3.21574586, 101.72624509),
('202409210014', '14', 'S00007', '2', 'C00001', '2024-09-21', 'food preparing', 0.00000000, 0.00000000),
('202409240004', '4', 'S00001', '2', 'C00001', '2024-09-24', 'order accepted', NULL, NULL),
('202409240006', '6', 'S00001', '2', 'C00001', '2024-09-24', 'order accepted', NULL, NULL),
('202409240008', '8', 'S00006', '3', 'C00002', '2024-09-24', 'order accepted', NULL, NULL),
('202409240009', '9', 'S00001', '3', 'C00002', '2024-09-24', 'order accepted', NULL, NULL),
('202409240010', '10', 'S00001', '2', 'C00001', '2024-09-24', 'order accepted', NULL, NULL),
('202409250004', '4', 'S00001', '2', 'C00001', '2024-09-25', 'order accepted', NULL, NULL),
('202409250005', '5', 'S00001', '2', 'C00001', '2024-09-25', 'order accepted', NULL, NULL),
('202409250006', '6', 'S00001', '2', 'C00001', '2024-09-25', 'order accepted', NULL, NULL),
('202409250008', '8', 'S00006', '3', 'C00002', '2024-09-25', 'order accepted', NULL, NULL),
('202409250009', '9', 'S00001', '3', 'C00002', '2024-09-25', 'order accepted', NULL, NULL),
('202409250010', '10', 'S00001', '2', 'C00001', '2024-09-25', 'order accepted', NULL, NULL),
('202409260004', '4', 'S00001', '2', 'C00001', '2024-09-26', 'order accepted', NULL, NULL),
('202409260005', '5', 'S00001', '2', 'C00001', '2024-09-26', 'order accepted', NULL, NULL),
('202409260008', '8', 'S00006', '3', 'C00002', '2024-09-26', 'order accepted', NULL, NULL),
('202409260009', '9', 'S00001', '3', 'C00002', '2024-09-26', 'order accepted', NULL, NULL),
('202409260010', '10', 'S00001', '2', 'C00001', '2024-09-26', 'order accepted', NULL, NULL),
('202409270004', '4', 'S00001', '2', 'C00001', '2024-09-27', 'order accepted', NULL, NULL),
('202409270005', '5', 'S00001', '2', 'C00001', '2024-09-27', 'order accepted', NULL, NULL),
('202409270008', '8', 'S00006', '3', 'C00002', '2024-09-27', 'order accepted', NULL, NULL),
('202409270009', '9', 'S00001', '3', 'C00002', '2024-09-27', 'order accepted', NULL, NULL),
('202409270010', '10', 'S00001', '2', 'C00001', '2024-09-27', 'order accepted', NULL, NULL);

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
(4, 'C00002', '8', 'good ah', '4', '2024-09-21'),
(5, 'C00001', '4', 'it was amazing!', '5', '2024-09-21'),
(6, 'C00001', '6', 'it was bad', '1', '2024-09-21'),
(7, 'C00001', '10', 'absolutely terrible', '1', '2024-09-21');

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

--
-- Dumping data for table `link_requests`
--

INSERT INTO `link_requests` (`id`, `user_id`, `seller_id`, `status`, `linked_date`) VALUES
(1, 'U00005', 'S00005', 'accepted', '2024-09-21'),
(2, 'U00012', 'S00008', 'accepted', '2024-09-21'),
(3, 'U00012', 'S00007', 'accepted', '2024-09-21');

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
(19, 'S00001', 'U00001', 'where is my food'),
(20, 'U00010', 'S00008', 'hi');

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
(3, 6, 45, 3),
(4, 6, 46, 2),
(5, 8, 51, 2),
(6, 8, 52, 1),
(7, 9, 45, 2);

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
(4, '2024-09-21', 100.00, 'Active', 'Lunch', 10, '2024-09-21', '2024-09-30', 1, 'C00001', '4', 2, '不要香菜'),
(5, '2024-09-21', 216.00, 'Active', 'Dinner', 6, '2024-09-25', '2024-09-30', 2, 'C00001', '5', 2, ''),
(6, '2024-09-21', 100.00, 'Finished', 'Dinner', 5, '2024-09-21', '2024-09-25', 2, 'C00001', '4', 2, ''),
(7, '2024-09-21', 420.00, 'Finished', 'Lunch', 10, '2024-09-01', '2024-09-10', 3, 'C00001', '7', 2, ''),
(8, '2024-09-21', 200.00, 'Active', 'Lunch', 20, '2024-09-21', '2024-10-10', 1, 'C00002', '9', 3, ''),
(9, '2024-09-21', 180.00, 'Active', 'Lunch', 9, '2024-09-21', '2024-09-29', 2, 'C00002', '4', 3, ''),
(10, '2024-09-21', 60.00, 'Active', 'Lunch', 6, '2024-09-20', '2024-09-28', 1, 'C00001', '4', 2, ''),
(14, '2024-09-21', 23.00, 'Finished', 'Lunch', 2, '2024-09-21', '2024-09-23', 1, 'C00001', '12', 2, '');

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
(4, 100.00, '2024-09-21', '4'),
(5, 216.00, '2024-09-21', '5'),
(6, 100.00, '2024-09-21', '6'),
(7, 420.00, '2024-09-21', '7'),
(8, 200.00, '2024-09-21', '8'),
(9, 180.00, '2024-09-21', '9'),
(10, 60.00, '2024-09-21', '10'),
(11, 744.00, '2024-09-21', '12'),
(12, 744.00, '2024-09-21', '13');

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `id` int(6) UNSIGNED NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `description` varchar(2000) NOT NULL,
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
(4, 'Package A (1pax)', 'Package A, get As wherever you go in your life! It is bento style, with an accurate portion for 1 person at an affordable price. If you prefer ‘1 person 1 set’ , definitely should go for this package.  📌 Package A includes: 1 bowl of rice, 1 vegetarian dish, 1 meat or fish, and 1 random dish of egg, tofu or Aunty Lau’s handmade. 📌 You could pick delivery dates up to 5 and 20 days. Just click on the “delivery date” panel and choose the dates you want to eat Aunty Lau’s home-cooked food. And on that day, all you have to do is sit back, relax and wait to eat~', 10, '2024-09-21', '2024-10-31', 'Lunch,Dinner', 'active', 'S00001', '../plan/66ee3818569020.07025696.jpg,../plan/66ee381856bde1.98211520.jpg,../plan/66ee381856e252.61798891.jpg,../plan/66ee38185745e9.66986940.jpg,../plan/66ee38185776c4.97671843.jpg', 1),
(5, 'Package B (2pax)', 'Package B has a large serving size and it is suitable for families! The portion for this Package is just like how usually mummy prepared the dishes, and like how it served in Chinese restaurants, share together! This package is suitable for families.  📌 Package B includes 1 vegetarian dish, 1 meat or fish, and 1 random dish of egg, tofu, or Aunty Lau’s handmade. Excludes rice but it comes with Aunty Lau’s Traditional Chinese Soups to warm your soul (Once a week). 📌 You could pick delivery dates up to 5, 20 days, and 40 days. Just click on the “delivery date” panel and choose the dates you want to eat Aunty Lau’s home-cooked food. And on that day, cooking is not a matter for busy moms, just leave it to Aunty Lau!', 18, '2024-09-21', '2024-11-08', 'Dinner', 'active', 'S00001', '../plan/66ee38753ef269.92367747.jpg,../plan/66ee38753f1706.90960836.jpg,../plan/66ee38753f3660.74009623.jpg,../plan/66ee38753f51a0.04593024.jpg,../plan/66ee38753f6a92.79962246.jpg,../plan/66ee38753f80f1.48561743.jpg', 0),
(6, 'Soup Package', 'Each meal serve with white rice and random vege.  Soup is the song of home that warms your soul. If you are looking for soups that’s packed with nutritious goodness, this package is for you!  This 5 days Subscriptions of Soup Series include several selections of soup: Cordyceps Flower Snow Fungus, White Radish Goji Berry Soup, Siwu Black Bean Soup, Double-Dates Lotus Root Stewed Pork Ribs, Angelica Egg Soup, and Lotus Seed Egg Soup.', 12, '2024-09-21', '2024-10-26', 'Dinner', 'active', 'S00001', '../plan/66ee394561f680.61363603.jpg,../plan/66ee3945621ba8.21621171.jpg,../plan/66ee3945623d55.27213407.jpg,../plan/66ee3945625ae3.85944185.jpg,../plan/66ee3945627354.02786297.jpg,../plan/66ee39456289d9.22732940.jpg,../plan/66ee394562ae71.97905064.jpg,../', 1),
(7, '(Set) Hakka Lei Cha Brown Rice', 'Brown rice is topped with various vegetables and toppings and served with tea soup made of tea leaves, nuts, seeds, and herbs.', 14, '2024-09-21', '2024-10-24', 'Lunch,Dinner', 'active', 'S00005', '../plan/66ee399702bc67.83094903.jpg', 0),
(8, '(Set) Hakka Lei Cha Brown Rice (Vegetarian)', 'This vegetarian set is come with brown rice is topped with various vegetables and toppings without any dried shrimps, garlic, onion together with tea soup made of tea leaves, nuts, seeds, and herbs.', 13, '2024-09-21', '2024-10-31', 'Lunch', 'active', 'S00005', '../plan/66ee39c384bdf7.07642657.jpg', 0),
(9, 'Package A (1 pax)', 'Package A, get As wherever you go in your life! It is bento style, with an accurate portion for 1 person at an affordable price. If you prefer ‘1 person 1 set’ , definitely should go for this package.  📌 Package A includes: 1 bowl of rice, 1 vegetarian dish, 1 meat or fish, and 1 random dish of egg, tofu or Aunty Lau’s handmade. 📌 You could pick delivery dates up to 5 and 20 days. Just click on the “delivery date” panel and choose the dates you want to eat Aunty Lau’s home-cooked food. And on that day, all you have to do is sit back, relax and wait to eat~', 10, '2024-09-21', '2024-11-07', 'Lunch,Dinner', 'active', 'S00006', '../plan/66ee3a2c5d98d6.74847937.jpg,../plan/66ee3a2c5dbac9.15516204.jpg,../plan/66ee3a2c5ddc27.19494093.jpg,../plan/66ee3a2c5dfd55.44506012.jpg,../plan/66ee3a2c5e22b9.11230431.jpg', 1),
(10, 'Soup Package', 'Each meal serve with white rice and random vege.  Soup is the song of home that warms your soul. If you are looking for soups that’s packed with nutritious goodness, this package is for you!  This 5 days Subscriptions of Soup Series include several selections of soup: Cordyceps Flower Snow Fungus, White Radish Goji Berry Soup, Siwu Black Bean Soup, Double-Dates Lotus Root Stewed Pork Ribs, Angelica Egg Soup, and Lotus Seed Egg Soup.  With this 5 days Soup Subscriptions Package, you will be getting 1 different soup daily for 5 days with FREE delivery.', 12, '2024-09-21', '2024-11-21', 'Dinner', 'active', 'S00006', '../plan/66ee3a7c977756.08690356.jpg,../plan/66ee3a7c97a308.37842999.jpg,../plan/66ee3a7c97c0a3.08878033.jpg,../plan/66ee3a7c97de55.52889773.jpg,../plan/66ee3a7c97f876.41717387.jpg,../plan/66ee3a7c981649.66078738.jpg,../plan/66ee3a7c983137.21692126.jpg,../', 1),
(12, 'restaurant 1 plan 1 (package A) (1 pax)', 'this is restaurnt 1 plan 1', 12, '2024-09-21', '2024-10-24', 'Lunch,Dinner', 'active', 'S00007', '../plan/66ee6a27aae014.95330015.jpg,../plan/66ee6a27ab0cb7.34089296.jpg,../plan/66ee6a27ab30a7.11471453.jpg,../plan/66ee6a27ab4ce6.88160345.jpg,../plan/66ee6a27ab79d9.70609133.jpg', 1);

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
  `unit_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `postcode` int(7) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `bank_company` varchar(255) DEFAULT NULL,
  `bank_account` varchar(20) DEFAULT NULL,
  `access` enum('verify','pending','inactive','rejected','linked','unknown') DEFAULT 'inactive',
  `user_id` varchar(11) NOT NULL,
  `image_urls` varchar(255) NOT NULL,
  `requests_open` tinyint(1) DEFAULT 0,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`id`, `name`, `profile_pic`, `detail`, `contact_number`, `unit_number`, `address`, `postcode`, `city`, `state`, `bank_company`, `bank_account`, `access`, `user_id`, `image_urls`, `requests_open`, `latitude`, `longitude`) VALUES
('S00001', 'Lau Home Cook (HQ)', '../seller_profile_pic/66ee31da3c04c1.72918906.png', '每个家庭都有代代相传的食谱和再熟悉不过的好味道，那是经年累月的时光烙在了我们味蕾上的记忆。刘妈妈是在16岁时从妈妈的手上接下烹饪技巧，一开始是为了家中的7个兄弟姊妹，后来则是为了在吉隆坡工作的儿子，确保孩子在人生打拼阶段也能吃的营养健康，刘妈妈毅然决然离开家乡跟着搬到吉隆坡，便创办了开放人人都可以点购的刘进厨房。如果你想念一份简单的暖心饭菜，也希望让家人吃的更健康、营养，欢迎体验刘妈家常菜传递简单温暖的幸福感。', '016-221 4667', NULL, '29-1a, Jalan Bandar 3, Pusat Bandar Puchong', 47100, 'Puchong', 'Selangor', 'Public Bank Berhad', '5028518306', 'verify', 'U00004', '../document/66ee31da3c3ed3.06458565.png,../document/66ee31da3c6629.75430058.jpg,../document/66ee31da3c89a8.96294065.jpg,../document/66ee31da3cadb4.48664648.jpg', 0, NULL, NULL),
('S00002', 'Ming De Zai', '../seller_profile_pic/66ee3533063333.98144201.png', 'Refreshing Vegetarian Meals', '019 622 5912', NULL, '32a, Jalan Anggerik Eria At 31/At, Kota Kemuning', 40460, 'Shah Alam', 'Selangor', 'Hong Leong Islamic Bank', '3728361832', 'linked', 'U00005', '../document/66ee3533065d91.62188255.jpg,../document/66ee3533068018.94021022.jpg', 0, NULL, NULL),
('S00003', NULL, '', NULL, NULL, NULL, NULL, 0, '', '', NULL, NULL, 'unknown', 'U00006', '', 0, NULL, NULL),
('S00004', NULL, '', NULL, NULL, NULL, NULL, 0, '', '', NULL, NULL, 'unknown', 'U00007', '', 0, NULL, NULL),
('S00005', 'Mei • Hakka Lei Cha', '../seller_profile_pic/66ee331da9a2b4.45427089.png', 'Chinese, Non-halal, Vegetarian', '011 5913 5912', NULL, 'Jln Sentul, Sentul Pasar', 51000, 'Kuala Lumpur', 'Wilayah Persekutuan', 'Maybank Berhad', '3827928193', 'verify', 'U00004', '../document/66ee331da9c4a8.48663909.png,../document/66ee331da9e133.03755946.jpg', 1, NULL, NULL),
('S00006', 'Lau Home Cook (Klang branch)', '../seller_profile_pic/66ee338e1a1544.70088415.png', '\r\n每个家庭都有代代相传的食谱和再熟悉不过的好味道，那是经年累月的时光烙在了我们味蕾上的记忆。刘妈妈是在16岁时从妈妈的手上接下烹饪技巧，一开始是为了家中的7个兄弟姊妹，后来则是为了在吉隆坡工作的儿子，确保孩子在人生打拼阶段也能吃的营养健康，刘妈妈毅然决然离开家乡跟着搬到吉隆坡，便创办了开放人人都可以点购的刘进厨房。如果你想念一份简单的暖心饭菜，也希望让家人吃的更健康、营养，欢迎体验刘妈家常菜传递简单温暖的幸福感。', '012 393 3099', NULL, 'Jalan Stesen, Kawasan 1', 41000, 'Klang', 'Selangor', 'Hong Leong Bank Berhad', '37281983271', 'verify', 'U00004', '../document/66ee338e1b5843.37847335.png,../document/66ee338e1b8a24.88338311.jpg,../document/66ee338e1bace0.21267995.jpg,../document/66ee338e1bca01.02675388.jpg', 0, NULL, NULL),
('S00007', 'restaurant1', '../seller_profile_pic/66ee67eb1b4906.41542263.png', 'this is restaurant 1', '019 512 5912', NULL, '18 jalan setia', 41000, 'Klang', 'Selangor', 'Public Bank Berhad', '12369546581', 'verify', 'U00009', '../document/66ee67eb1b6913.45467849.jpg,../document/66ee67eb1b8af2.06922394.jpg,../document/66ee67eb1ba950.17124908.jpg', 1, NULL, NULL),
('S00008', 'restaurant 1 new branch', '../seller_profile_pic/66ee6873e0f9f2.88856947.png', 'this is restuarnt 1 branch', '012 548 1266', NULL, '17, jalan taman ', 47140, 'Puchong', 'Selangor', 'Affin Bank', '15459235254', 'verify', 'U00009', '../document/66ee6873e12475.97996434.jpg,../document/66ee6873e14718.29786713.jpg,../document/66ee6873e16a35.57275564.jpg', 1, NULL, NULL),
('S00009', NULL, '', NULL, NULL, NULL, NULL, 0, '', '', NULL, NULL, 'linked', 'U00012', '', 0, NULL, NULL),
('S00010', 'dsadsadadas', '../seller_profile_pic/66f416dc39ea85.24754317.jpg', 'dsadasdhasjdashdjkasd', '01622154', '13', 'Jalan Setia Impian U13/2, Setia Alam, Shah Alam, Selangor, Malaysia', 40170, 'Shah Alam', 'Selangor', 'Affin Bank', '45645', 'pending', 'U00013', '../document/66f416dc3a0e47.77855907.jpg,../document/66f416dc3a2e14.96811900.jpg', 0, 3.10462320, 101.45055120);

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
('S00001', '90f3f1919ec596927968040214d42e2b', 0.00000000, 0.00000000, 'close', '2024-09-25 02:40:49'),
('S00007', 'bb99a4c1bf0d4ee52a39e81d32abc985', 0.00000000, 0.00000000, 'close', '2024-09-21 07:46:16');

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
(15, 'Successful', 356.00, 334.64, 'Withdraw', '2024-09-21 14:16:41', 'S00001'),
(16, 'Successful', 200.00, 188.00, 'Withdraw', '2024-09-21 15:12:02', 'S00008');

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
('U00001', 'Darren goh', 'gohdarren317@gmail.com', '$2y$10$P9snE8027pVOoBG7pPvaWOJ4SRxxfL1Iz9Dsno.kmu4qZFaNYFR22', 'customer', '2024-09-21 09:44:41', 'Online', '../uploads/default.jpg', 'What city were you born in?', 'Klang', 'What was your first pet\'s name?', 'Coco', NULL, NULL),
('U00002', 'Yeoh Yao Wen', 'yeohyw@gmail.com', '$2y$10$krTaV1qARPu/3kj5pFmeXuMgU81GTfclEY43XOR2QryJrUU/ShVI6', 'customer', '2024-09-21 09:46:42', 'Offline now', '../uploads/default.jpg', 'What city were you born in?', 'Setapak', 'What was your first pet\'s name?', 'Ali', NULL, NULL),
('U00003', 'Song Mern Shen', 'songms@gmail.com', '$2y$10$F2Z0zvhCn1AgjWt97zSwdOrJkph4CxEPvi1X63./byDQNhY9e4LHW', 'customer', '2024-09-21 09:48:51', NULL, '../uploads/default.jpg', 'What is your favorite food?', 'KFC', 'What is your mother\'s maiden name?', 'Siti', NULL, NULL),
('U00004', 'Gery Ng', 'darrengey-wp20@student.tarc.edu.my', '$2y$10$yqDHsx/tuAKhlIPzaAQ/ZuKsqG4zRb.YYVjOVWrkUByzIcSJ5gHmG', 'seller', '2024-09-21 09:51:15', 'Online', '../uploads/66ee3495d0dc24.18038872.jpeg', 'What city were you born in?', 'Narnia', 'What was your first pet\'s name?', 'Bat', NULL, NULL),
('U00005', 'Dora Goh', 'gohdarren238@gmail.com', '$2y$10$WfVE9U5hrJNfvvypL5rSTe16B23XRkHhBzcB0CKPYMFPrqLo05qXW', 'seller', '2024-09-21 09:51:58', 'Offline now', '../uploads/default.jpg', 'What city were you born in?', 'Taiping', 'What was your first pet\'s name?', 'Jerry', NULL, NULL),
('U00006', 'Choong Kah Chay', 'choongkc123@gmail.com', '$2y$10$bLFVwVrmmPPbzVGBdJwTzu6ZOyGmZTzbeN5pXN1BVAOt1acVwtYCG', 'seller', '2024-09-21 10:02:58', NULL, '../uploads/default.jpg', 'What city were you born in?', 'Semenyih', 'What was your first pet\'s name?', 'Plague', NULL, NULL),
('U00007', 'Tan Li Hao', 'tanlh123@gmail.com', '$2y$10$ZyvRX1yzkbeg0oXQraqK.uXFEGV/LZ8Y7sqVZO.4i.RIp5S.gIM5e', 'seller', '2024-09-21 10:03:29', NULL, '../uploads/default.jpg', 'What city were you born in?', 'Johor Bahru', 'What was your first pet\'s name?', 'KittyMeowMeow', NULL, NULL),
('U00008', 'Admin', 'darrenggoh123@gmail.com', '$2y$10$d43/SWub/qw.PUduXS9G4.K.Y31Y5UJRirS2.7uH/hN6yLrA0KpnC', 'admin', '2024-09-21 10:04:30', 'Offline now', '../uploads/default.jpg', 'What city were you born in?', 'Narnia', 'What was your first pet\'s name?', 'Bat', NULL, NULL),
('U00009', 'testseller1', 'geryng0102@gmail.com', '$2y$10$vz4OTHVvf2ofVfqKY.P7lOOiC8K.SXlvc6asENmoypcw25TedNQce', 'seller', '2024-09-21 14:26:45', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'coco', 'What city were you born in?', 'setia alam', NULL, NULL),
('U00010', 'new customer sa', 'sanewcustomer@gmail.com', '$2y$10$nwcgq.shQ6wu9ojhOG74xOBeGlp4K6C.BjGGlEyE9Q4ltEKToZwWe', 'customer', '2024-09-21 14:43:19', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'coco', 'What is your favorite food?', 'nasi lemak', NULL, NULL),
('U00011', 'new customer puchong', 'newcustomerpuchong@gmail.com', '$2y$10$Pka9oOEWkcevi5aLzEu5F.lY09CaJHxCsqpnpXU60Jm.B1xYp8ibu', 'customer', '2024-09-21 14:44:18', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'pretty', 'What is your favorite food?', 'nasi lemak', NULL, NULL),
('U00012', 'testseller2', 'ngyx-wm22@student.tarc.edu.my', '$2y$10$dqveyNGt2lU/0m/MyYEToupAx.NKxdt1DSOYPNh0vYuVDoVU/lqHq', 'seller', '2024-09-21 15:17:33', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'lihao', 'What is your mother\'s maiden name?', 'lihao', NULL, NULL),
('U00013', 'testseller3', 'hello180102@gmail.com', '$2y$10$E.bxeTU3x5mOySv8zGZziO6ike6fbr2Z1WojBRnMbtO0ymX0Eth3u', 'seller', '2024-09-25 18:47:39', 'Offline now', '../uploads/default.jpg', 'What was your first pet\'s name?', 'lihao', 'What is your mother\'s maiden name?', 'lihao', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `revenue` decimal(10,2) NOT NULL,
  `seller_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `balance`, `revenue`, `seller_id`) VALUES
(3, -34.64, 656.00, 'S00001'),
(4, 0.00, 0.00, 'S00002'),
(5, 0.00, 0.00, 'S00003'),
(6, 0.00, 0.00, 'S00004'),
(7, 420.00, 420.00, 'S00005'),
(8, 200.00, 200.00, 'S00006'),
(9, 0.00, 0.00, 'S00007'),
(10, 1288.00, 1488.00, 'S00008');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `Feedback_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `link_requests`
--
ALTER TABLE `link_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_addon`
--
ALTER TABLE `order_addon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_cust`
--
ALTER TABLE `order_cust`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
