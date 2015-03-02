-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2014 at 06:01 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `teamb`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE IF NOT EXISTS `administrators` (
`userID` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`userID`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`catID` int(11) NOT NULL,
  `catName` varchar(30) DEFAULT NULL,
  `catDescription` varchar(200) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`catID`, `catName`, `catDescription`) VALUES
(1, 'Category one (edited)', 'Description of category one (edited)');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
`customerId` int(11) NOT NULL,
  `customerFirstName` varchar(20) DEFAULT NULL,
  `customerLastName` varchar(30) DEFAULT NULL,
  `customerAddress` varchar(100) DEFAULT NULL,
  `customerCity` varchar(20) DEFAULT NULL,
  `customerPostCode` varchar(4) DEFAULT NULL,
  `customerEmail` varchar(50) DEFAULT NULL,
  `isBusinessAccount` varchar(5) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerId`, `customerFirstName`, `customerLastName`, `customerAddress`, `customerCity`, `customerPostCode`, `customerEmail`, `isBusinessAccount`) VALUES
(1, 'Jane', 'Doe', '123 Main Street', 'Toytown', '1234', 'JaneDoe@toys.com', 'yes'),
(2, 'chandan', 'ram', '4-11', 'onslow', '8014', 'chandan249@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderproducts`
--

CREATE TABLE IF NOT EXISTS `orderproducts` (
`orderProductId` int(11) NOT NULL,
  `productQuantity` int(11) DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `orderproducts`
--

INSERT INTO `orderproducts` (`orderProductId`, `productQuantity`, `orderId`, `productId`) VALUES
(1, 2, 1, 1),
(2, 3, 2, 2),
(3, 2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
`orderId` int(11) NOT NULL,
  `orderIsSuccess` tinyint(1) DEFAULT NULL,
  `orderDate` date DEFAULT NULL,
  `customerId` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `orderIsSuccess`, `orderDate`, `customerId`) VALUES
(1, 1, '2014-11-03', 1),
(2, 0, '2014-11-03', 2);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
`paymentId` int(11) NOT NULL,
  `custId` int(11) DEFAULT NULL,
  `amount` decimal(6,2) DEFAULT NULL,
  `paymentDate` date DEFAULT NULL,
  `lastUpdate` date DEFAULT NULL,
  `paymentType` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
`productID` int(11) NOT NULL,
  `productName` varchar(30) DEFAULT NULL,
  `productDescription` varchar(300) DEFAULT NULL,
  `productPrice` decimal(6,2) DEFAULT NULL,
  `productPic` varchar(200) DEFAULT NULL,
  `catID` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `productName`, `productDescription`, `productPrice`, `productPic`, `catID`) VALUES
(1, 'Product one', 'Description of product one', '123.45', NULL, 1),
(2, 'chan', 'chan', '12.00', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`userID` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `pwCheck` varchar(75) DEFAULT NULL,
  `dateCreated` datetime DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `pwCheck`, `dateCreated`, `lastLogin`) VALUES
(1, 'Mike Lopez', 'mike.lopez@cpit.ac.nz', '5000$1AGp0JbYCJWcH9ng$oKpRQEO9vkPVkPQTiOuF9DhV0aUaPjopKPcXo6Ic0d/', '2014-08-01 00:00:00', '2014-11-03 21:45:39'),
(2, 'Mike Lance', 'lancem@cpit.ac.nz', '5000$w0dIUsUZWFB5eJ5f$B/FN5Z2Rwx5MKVuB0LkM29KM6F8LqsrlXJTg61sEtE8', '2014-08-02 00:00:00', '2014-11-03 21:45:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
 ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`catID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
 ADD PRIMARY KEY (`customerId`);

--
-- Indexes for table `orderproducts`
--
ALTER TABLE `orderproducts`
 ADD PRIMARY KEY (`orderProductId`), ADD KEY `productId` (`productId`), ADD KEY `orderId` (`orderId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
 ADD PRIMARY KEY (`orderId`), ADD KEY `customerId` (`customerId`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
 ADD PRIMARY KEY (`paymentId`), ADD KEY `custId` (`custId`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
 ADD PRIMARY KEY (`productID`), ADD KEY `catID` (`catID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`userID`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `catID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
MODIFY `customerId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `orderproducts`
--
ALTER TABLE `orderproducts`
MODIFY `orderProductId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderproducts`
--
ALTER TABLE `orderproducts`
ADD CONSTRAINT `orderproducts_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `products` (`productID`),
ADD CONSTRAINT `orderproducts_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerId`) REFERENCES `customers` (`customerId`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`custId`) REFERENCES `customers` (`customerId`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`catID`) REFERENCES `categories` (`catID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
