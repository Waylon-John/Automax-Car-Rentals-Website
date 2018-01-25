-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2018 at 08:00 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `automaxr_rentaldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(30) NOT NULL,
  `Model` varchar(50) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Units` int(30) NOT NULL,
  `currentunits` int(128) NOT NULL,
  `PricePerDay` int(30) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `Model`, `Type`, `Units`, `currentunits`, `PricePerDay`, `image`) VALUES
(51, 'Hyundai Accent 2014', 'Sedan', 2, 2, 1500, 'car13.jpg'),
(50, 'Honda City 1.3 Vtec 2009', 'Sedan', 3, 3, 1500, 'car12.jpg'),
(49, 'Honda Crv 2.0L 4X2 AT 2013', 'SUV', 1, 1, 3000, 'car6.jpg'),
(48, 'Honda Accord 2.4 i-vtec', 'Sedan', 2, 2, 1500, 'car1.jpg'),
(47, 'Toyota Vios 2014 E', 'Sedan', 1, 1, 1500, 'car3.jpg'),
(46, 'Toyota Hiace Grandia GL 2013', 'SUv', 2, 2, 3000, 'car9.jpg'),
(45, 'Toyota Innova E 2.5', 'SUV', 2, 2, 3000, 'car8.jpg'),
(44, 'Mitsubishi Montero Sport 2014', 'SUV', 3, 1, 3000, 'car2.jpg'),
(52, 'Hyundai Grand Starex TCI 2011', 'SUV', 2, 2, 3000, 'car7.jpg'),
(53, 'Mazda BT50 4x2 2.2L MT DSL 2014', 'SUV', 1, 1, 3000, 'car4.jpg'),
(54, 'Chevrolet Captiva', 'SUV', 2, 2, 3000, 'car5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` varchar(2048) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `content`, `recipient_id`, `message_time`, `status`) VALUES
(84, 7, 'admin hey', 2, '2017-08-03 16:44:22', 'VIEWED'),
(85, 7, 'qwedqwedqwedqwefqwfeqwfqwe', 2, '2017-08-04 05:11:04', 'VIEWED'),
(86, 7, 'fqwefqweeqwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww', 2, '2017-08-04 05:11:08', 'VIEWED');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(30) NOT NULL,
  `Account_ID` varchar(30) NOT NULL,
  `Car_ID` int(30) NOT NULL,
  `DateApplied` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DateRent` varchar(50) NOT NULL,
  `DateReturn` varchar(50) NOT NULL,
  `RentPrice` int(30) NOT NULL,
  `Status` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `Account_ID`, `Car_ID`, `DateApplied`, `DateRent`, `DateReturn`, `RentPrice`, `Status`) VALUES
(244, '1', 44, '2018-01-25 07:24:47', '2018-01-28', '2018-01-31', 9000, 'NOT FINALIZED');

-- --------------------------------------------------------

--
-- Table structure for table `resproducts`
--

CREATE TABLE `resproducts` (
  `id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `product` varchar(200) NOT NULL,
  `images` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `ContactNumber` varchar(30) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `UserLevel` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `FirstName`, `LastName`, `ContactNumber`, `username`, `password`, `UserLevel`) VALUES
(1, 'Peter', 'Macasinag', '09153310623', 'peterm01', 'akosipeter', 'Customer'),
(2, 'Patrick', 'Martinez', '330847', 'admin', 'admin01', 'Admin'),
(3, 'Guy', 'A', '100', 'user1', 'user', 'Customer'),
(4, 'Guy', 'B', '200', 'user2', 'user', 'Customer'),
(6, 'test', 'test', '69', 'test', 'test', 'Customer'),
(7, 'user', 'customer', '1', 'user', 'user', 'Customer'),
(9, 'Mel', 'Candelario', '100', 'melons99', 'iammel', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resproducts`
--
ALTER TABLE `resproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;
--
-- AUTO_INCREMENT for table `resproducts`
--
ALTER TABLE `resproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
