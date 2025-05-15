-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 08:48 PM
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
-- Database: `kachabazardb`
--

-- --------------------------------------------------------

--
-- Table structure for table `contain`
--

CREATE TABLE `contain` (
  `GRitem_id` int(11) NOT NULL,
  `ORorder_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contain`
--

INSERT INTO `contain` (`GRitem_id`, `ORorder_id`, `quantity`) VALUES
(1, 4, 1),
(2, 2, 2),
(2, 5, 3),
(4, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `grocery_items`
--

CREATE TABLE `grocery_items` (
  `item_id` int(11) NOT NULL,
  `Groc_name` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `Groc_quantity` int(11) NOT NULL,
  `SEseller_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grocery_items`
--

INSERT INTO `grocery_items` (`item_id`, `Groc_name`, `status`, `category`, `description`, `price`, `Groc_quantity`, `SEseller_id`) VALUES
(1, 'Sprite', '', 'Beverages', 'Speeds up the process to diabetes. Recommended for heart patients.', 15.00, 199, 1),
(2, 'Carrot', '', 'Vegetables', 'Straight from Saint Martin. Fresh and healthy. ', 12.00, 78, 1),
(4, 'Burger Bun', '', 'Bakery', 'Made from 7 secret ingredients which guarantees you 4 kilograms of weight in 13 days.', 7.00, 255, 2);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `ORdate_time` datetime NOT NULL,
  `total_bill` decimal(10,2) NOT NULL,
  `ORpayment_status` varchar(50) NOT NULL,
  `UScustomer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `ORdate_time`, `total_bill`, `ORpayment_status`, `UScustomer_id`) VALUES
(2, '2025-05-15 00:26:52', 24.00, 'Paid', 3),
(3, '2025-05-15 00:27:53', 7.00, 'Paid', 2),
(4, '2025-05-15 00:38:27', 15.00, 'Paid', 3),
(5, '2025-05-15 00:39:13', 36.00, 'Paid', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ratings&review`
--

CREATE TABLE `ratings&review` (
  `review_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text DEFAULT NULL,
  `Rdate_time` datetime NOT NULL,
  `SEseller_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings&review`
--

INSERT INTO `ratings&review` (`review_id`, `rating`, `review`, `Rdate_time`, `SEseller_id`) VALUES
(1, 5, 'Eta khub fresh chilo! Just like my mom brings from bazar. Appreciate it!', '2025-05-15 00:27:45', 1),
(2, 4, 'Bhai! Ei bun diye egg sandwich banailam, next level chilo. Thanks Seller 2!', '2025-05-15 00:28:34', 2),
(3, 5, 'BRAC University midterms are brutal. Sprite from this seller kept me awake all night for study. Grateful!', '2025-05-15 00:39:04', 1),
(4, 2, 'I bought these carrots thinking I could impress my BRAC nutrition course group. Unfortunately, not crunchy enough.', '2025-05-15 00:39:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `seller_id` int(11) NOT NULL,
  `seller_name` varchar(100) NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `seller_email` varchar(100) NOT NULL,
  `seller_password` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `store_location` varchar(255) NOT NULL,
  `store_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`seller_id`, `seller_name`, `store_name`, `seller_email`, `seller_password`, `date`, `store_location`, `store_description`) VALUES
(1, 'Bajrangi Bhaijaan', 'Don no 1 grocery', 'seller@gmail.com', 'seller', '2025-05-14', 'Badda', 'Best or worst nothing in between. Order at your own risk. No refunds. Understood?'),
(2, 'Ben 10', 'Cartoon Network', 'seller2@gmail.com', 'seller', '2025-05-14', 'badda', 'Best store to relive childhood');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `customer_id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `customer` tinyint(1) NOT NULL DEFAULT 0,
  `Uname` varchar(100) NOT NULL,
  `PhoneNo` varchar(20) NOT NULL,
  `Upassword` varchar(255) NOT NULL,
  `Uemail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`customer_id`, `admin`, `customer`, `Uname`, `PhoneNo`, `Upassword`, `Uemail`) VALUES
(2, 1, 0, 'Big brother ', '01746614671', 'admin', 'admin@gmail.com'),
(3, 0, 1, 'Goku', '01985496950', 'customer', 'customer@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `UScustomer_id` int(11) DEFAULT NULL,
  `USadmin` tinyint(1) DEFAULT 0,
  `UScustomer` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contain`
--
ALTER TABLE `contain`
  ADD PRIMARY KEY (`GRitem_id`,`ORorder_id`),
  ADD KEY `ORorder_id` (`ORorder_id`);

--
-- Indexes for table `grocery_items`
--
ALTER TABLE `grocery_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `SEseller_id` (`SEseller_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `UScustomer_id` (`UScustomer_id`);

--
-- Indexes for table `ratings&review`
--
ALTER TABLE `ratings&review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `SEseller_id` (`SEseller_id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`seller_id`),
  ADD UNIQUE KEY `seller_email` (`seller_email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `Uemail` (`Uemail`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD KEY `UScustomer_id` (`UScustomer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grocery_items`
--
ALTER TABLE `grocery_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ratings&review`
--
ALTER TABLE `ratings&review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contain`
--
ALTER TABLE `contain`
  ADD CONSTRAINT `contain_ibfk_1` FOREIGN KEY (`GRitem_id`) REFERENCES `grocery_items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contain_ibfk_2` FOREIGN KEY (`ORorder_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `grocery_items`
--
ALTER TABLE `grocery_items`
  ADD CONSTRAINT `grocery_items_ibfk_1` FOREIGN KEY (`SEseller_id`) REFERENCES `seller` (`seller_id`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`UScustomer_id`) REFERENCES `user` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings&review`
--
ALTER TABLE `ratings&review`
  ADD CONSTRAINT `ratings&review_ibfk_1` FOREIGN KEY (`SEseller_id`) REFERENCES `seller` (`seller_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`UScustomer_id`) REFERENCES `user` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
