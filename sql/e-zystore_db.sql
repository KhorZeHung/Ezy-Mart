-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Dec 13, 2023 at 03:40 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-zystore_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `address_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `address_name`, `address`, `user_id`) VALUES
(3, 'college', 'ypc international college', 2),
(5, 'home', '5, Jalan 1, Bandar 1. ', 2),
(6, 'Office', 'Velocity', 3),
(13, 'default', 'ypc international college', 4),
(14, 'assas', '1', 4),
(15, 'default', 'ypc international college', 5);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) DEFAULT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `admin_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$K2rYce.meHTVR5RPjJjLlerNPOkus3VRzo140agC5U2DYei.Rm7O6');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `cart_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`user_id`, `product_id`, `cart_quantity`) VALUES
(3, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` varchar(6) NOT NULL,
  `cat_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`) VALUES
('BEV001', 'Beverages'),
('CF002', 'Convenience Foods'),
('FRO005', 'Frozen Foods'),
('HOU004', 'Household Supplies'),
('SNA003', 'Snacks');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(12) DEFAULT NULL,
  `contact_subject` text DEFAULT NULL,
  `contact_message` text DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `contact_name`, `contact_email`, `contact_phone`, `contact_subject`, `contact_message`, `admin_id`) VALUES
(1, 'khor', 'zehung.k@ypccollege.edu.my', '011-10790813', 'new user discount', 'Is there any discount or promotion for new user or first purchase ', 1),
(2, 'khor', 'zehung.k@ypccollege.edu.my', '011-10790813', 'user discount', 'is there any user discount?', 1),
(3, 'khor', 'zehung.k@ypccollege.edu.my', '011-10790813', 'new user discount', 'i want new user discount', 1),
(4, 'khor', 'zehung.k@ypccollege.edu.my', '011-10790812', 'new user discount', 'i want new user discount', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `payment_datetime` datetime DEFAULT current_timestamp(),
  `address_id` int(11) NOT NULL,
  `deliver_date` datetime DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL CHECK (`status` between 0 and 3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `payment_datetime`, `address_id`, `deliver_date`, `total_price`, `payment_id`, `admin_id`, `status`) VALUES
(1, 2, '2023-12-08 07:20:09', 3, '2023-12-11 14:51:44', '41.80', 1, 1, 3),
(2, 2, '2023-12-11 06:55:20', 5, '2023-12-11 21:18:22', '23.98', 2, 1, 3),
(3, 3, '2023-12-11 07:40:02', 6, NULL, '8.35', 2, NULL, NULL),
(4, 4, '2023-12-12 04:51:53', 14, NULL, '27.19', 2, 1, 1),
(5, 2, '2023-12-12 05:32:23', 3, NULL, '15.20', 3, 1, 2),
(6, 2, '2023-12-12 06:41:06', 5, NULL, '23.70', 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

CREATE TABLE `orderlist` (
  `Olist_id` int(11) NOT NULL,
  `Olist_quantity` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orderlist`
--

INSERT INTO `orderlist` (`Olist_id`, `Olist_quantity`, `product_id`, `order_id`, `unit_price`) VALUES
(1, 2, 2, 1, '20.90'),
(2, 2, 14, 2, '11.99'),
(3, 1, 1, 3, '8.35'),
(4, 1, 5, 4, '15.20'),
(5, 1, 14, 4, '11.99'),
(6, 2, 4, 5, '7.60'),
(7, 6, 9, 6, '3.95');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `payment_option` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_option`) VALUES
(1, 'Cash On Delivery (COD)'),
(2, 'Online Banking'),
(3, 'Card Payment');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_detail` varchar(500) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `front_imageurl` varchar(100) NOT NULL,
  `back_imageurl` varchar(100) DEFAULT NULL,
  `add_imageurl` varchar(100) DEFAULT NULL,
  `cat_id` varchar(6) DEFAULT NULL,
  `activate` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_detail`, `product_price`, `front_imageurl`, `back_imageurl`, `add_imageurl`, `cat_id`, `activate`) VALUES
(1, 'Kawan Sweet Potato Balls', 'Pack Size: 30 x 10g<br />\r\n<br />\r\nNice to try', '8.35', 'a659a1588206aef1014575e2d7a2c7e9.jpg', '923a39fe1c78ffdb3b69eefb221f9bbb.jpg', '859d4047a6cfe7f99e37b095e0ec9adc.jpg', 'FRO005', 1),
(2, 'Dr. Oetker Ristorante - Pizza Chicken Hawaii', 'Pack Size: 355g  <br />\r\n <br />\r\nExpiry: 6 to 9 months  <br />\r\n', '20.90', 'dc6993d801aaee908296402b3dfadb16.jpg', 'cb6012ded7af7788b933cf86f9f850f1.jpg', NULL, 'FRO005', 0),
(4, 'Ramly - Chicken Nuggets', 'Pack Size: 500g', '7.60', '7121d3b1cace8e2277b6c8a4d7adf03e.jpg', 'f9db928476bb78d65ab2ebfaf3b65bee.jpg', NULL, 'FRO005', 1),
(5, 'Ramly - Shoestring French Fries', 'Pack Size: 1kg', '15.20', '67a7a436c1b3830d437e0c6d381c3c3e.png', 'f36b77bda1c578b0b63590a5b412b862.png', NULL, 'FRO005', 1),
(6, 'Farm Fresh Strawberry Yogurt Drink', 'Drink down the probiotics for your gut health with our Yogurt Drink, which undergo fermentation for easier digestion!   <br />\r\n <br />\r\nShelf life: 2-3 weeks', '6.99', 'f8dc3b1de71ff8f200b8d4103c4abf0c.png', '170b2e0003ab1acd4e3db0149ec52c87.png', '09c457425179bfdd00f2af300dbb0bf0.png', 'BEV001', 1),
(7, 'Marigold HL Low Fat Milk 1L', 'High in Calcium and Protein Enriched with 3 Vitamins (A, B1 and D) <br />\r\nFormulated with unique BonePlus, Vitamin D & Ca for better absorption of calcium.', '6.49', 'b95ea3ef12f6dc1d7380e20d35b8fcef.png', 'b48356e76dca752f435c5f3ddce8079e.png', 'd4e88d38617459e55707cb83f07d24e3.png', 'BEV001', 1),
(8, 'Ayam brand tuna mayonnaise 160g', 'Weight : 160g  <br />\r\n      <br />\r\nA convenient and flavorful product that combines the goodness of tuna with the creamy richness of mayonnaise', '5.99', 'c8fbabb218a5adf90f6f8ca3044134af.jpeg', '8845035fd2d7ddf0c206ea3b4e612616.png', '8c9e14315d0e3edaae605a575ba27700.jpg', 'CF002', 1),
(9, 'Maggi Asam Laksa Instant Noodle 78GX5', 'package size : 78GX5 <br />\r\n<br />\r\nIndulge in the rich and authentic taste of Malaysia with Maggi Laksa Noodles.', '3.95', 'b19663003ef2a69d259adf35dff1fae5.gif', '80749814e620269697b3790d3ae1d41d.jpg', 'fcf5f2dbae13134f75efa34d415ba200.jpg', 'CF002', 1),
(10, 'Quaker instant oatmeal 800g', 'Pack size : 800g <br />\r\n<br />\r\nHappiness and nutrition come in a warm bowl of Quaker Instant Oatmeal, and now with Quaker Instant Oatmeal you can enjoy them no matter how busy your day is. ', '10.99', '8666fbfaab9e3b4bc6f94c6fda52c6e2.jpg', '3a4c8f544ba8ca9551793065b5ff83d8.jpg', 'ff81f28a150ba8e3e0cbad07bd499886.jpg', 'CF002', 1),
(11, 'Nestle Milo Activ-Go Chocolate Malt Powder Softpack (2kg)', 'Natural goodness of cocoa, milk and malt to fuel you to take on your day <br />\r\n', '16.99', '8ba6110b43b1d5e7223271e8981fd803.jpg', '7f941d6da8629c0bcfe36ba8cd8ac309.jpg', 'b1d30884e5bd1e546cc17701842d5e39.jpg', 'BEV001', 1),
(12, 'Nescafe Classic Refill 200g', 'The original NESCAFE coffee with unmistakable taste you know and love', '17.50', '3274bdc1ed323fd96eeaaf0c05c3b695.jpg', '58de47d37867de1c4bb97d9176e5256b.jpg', '2adbcaca191fbfd5b7af976cc1cd71db.jpg', 'BEV001', 1),
(13, 'GLO Concentrated Dish Washing Liquid 800ml - Lime', 'GLO Concentrated Dish Washing Liquid - Lime<br />\r\n<br /><br />\r\n100% effective in grease removal!', '5.35', '12a8065a418b68c8669961af82679307.jpg', '70c6cd51c3bd7a355670bdcfde6d919d.jpg', NULL, 'HOU004', 1),
(14, 'Cheetos Crunchy Cheese Flavored 215g', 'pack size : 215g  <br />\r\n<br />\r\nBring a cheesy, delicious crunch to snack time with a bag of Cheetos Crunchy Cheese-Flavored Snacks.', '11.99', '7497cea49e24782dcd1bd7504bedd5af.jpg', '5d73abc6591f32e18d68589517421c55.jpg', '0e8ae9e9a931e388fda5278ff58968fb.jpg', 'SNA003', 1),
(15, 'Cadbury Dairy Milk - Handheld Chocolate Bar, 110g', 'pack size : 110g<br />\r\n<br />\r\nCadbury goodness starts with the chocolate. Not just the milk & cocoa goodness in the chocolate, but the inherent goodness in chocolate made by Cadbury.', '6.59', '28c9d02b37b55dc1c58b8b2885140871.jpg', '933823b1206a6fe21238f03159b18765.jpg', NULL, 'SNA003', 1),
(16, 'Lexus Choco Coated Chocolate Biscuits 360G', 'pack size : 360g  <br />\r\n<br />\r\nLexus, The Chocolate Biscuit-Packed With Smooth And Creamy Indulgent Fillings, Coated In A Layer Of Chocolate.', '10.90', '8918edd235b46ea6f4c2c8264912c195.jpg', '52126b5278093bce860b18a152953d6c.jpg', '1e3bd781a1f4b40340941149b1a5e5e4.jpg', 'SNA003', 1),
(17, 'sponge', 'sponge', '1.99', '3d5bb0cb8005beb8da2f8a27a51571e8.jpg', '06387eaade4da41eb37f8cbcb75fd366.jpg', NULL, 'HOU004', 1);

-- --------------------------------------------------------

--
-- Table structure for table `slider_content`
--

CREATE TABLE `slider_content` (
  `contentID` int(11) NOT NULL,
  `contentLocation` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `slider_content`
--

INSERT INTO `slider_content` (`contentID`, `contentLocation`) VALUES
(7, 'd10ce51a2ec954eaa10132005aef914a.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_email`, `user_phone`) VALUES
(1, 'khor', '$2y$10$0.i4wX/ZlRR6DIvoC8m2re4yH2nrmGH9z6PRed6844v8JMoeAgvYC', 'zehung.k@ypccollege.edu.com', '011-1079 0813'),
(2, 'khor', '$2y$10$GL2Dj7/tlPOAaUriK/TzPu5cEUC09qO7h4GKvgnbyKW6AKbkrfmRG', 'zehung.k@ypccollege.edu.my', '011-1071 1111'),
(3, 'jying', '$2y$10$1Z4A9Vl5VRTgqoLHKc6xwOahBwqxAmSw/QhHbi9mVexzD.wIzXTXC', 'khorzehung@gmail.com', '011-111 1111'),
(4, 'Lee Jian Zhang', '$2y$10$TOy8HdOIOoptgUtaLPgkC.rrKdTHpSRHaCmRNsGQApjl0l2AveuSa', 'daveleejz@hotmail.com', '011-11341525'),
(5, 'jying', '$2y$10$DFOYohH14Z7oV061CdsZv.Br2PBhgc.evBgnnuDhkmgEaPln2SzOa', 'jying@gmail.com', '019-112 9182');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `foreign key` (`user_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD UNIQUE KEY `unique_fk_combination` (`user_id`,`product_id`,`cart_quantity`),
  ADD KEY `product_id` (`product_id`) USING BTREE,
  ADD KEY `cart_ibfk_1` (`product_id`) USING BTREE;

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `contact_ibfk_1` (`admin_id`) USING BTREE;

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_ibfk_2` (`user_id`),
  ADD KEY `payment_id` (`payment_id`) USING BTREE,
  ADD KEY `fk_address_id` (`address_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD PRIMARY KEY (`Olist_id`),
  ADD KEY `orderlist_ibfk_1` (`product_id`),
  ADD KEY `orderlist_ibfk_2` (`order_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_ibfk_1` (`cat_id`);

--
-- Indexes for table `slider_content`
--
ALTER TABLE `slider_content`
  ADD PRIMARY KEY (`contentID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orderlist`
--
ALTER TABLE `orderlist`
  MODIFY `Olist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `slider_content`
--
ALTER TABLE `slider_content`
  MODIFY `contentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `foreign key` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_productid` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `fk_userid` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`),
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`),
  ADD CONSTRAINT `fk_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD CONSTRAINT `fk_ol_order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  ADD CONSTRAINT `fk_ol_orderid` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  ADD CONSTRAINT `fk_ol_productid` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `orderlist_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
