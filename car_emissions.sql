-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2023 at 01:39 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_emissions`
--
CREATE DATABASE IF NOT EXISTS `car_emissions` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `car_emissions`;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

DROP TABLE IF EXISTS `car`;
CREATE TABLE `car` (
  `CarID` int(9) NOT NULL,
  `Make` varchar(32) NOT NULL,
  `Model` varchar(64) NOT NULL,
  `Year` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`CarID`, `Make`, `Model`, `Year`) VALUES
(1, 'Testoyota', 'Testamry', 2009),
(2, 'Testissan', 'Testeaf', 2016);

-- --------------------------------------------------------

--
-- Table structure for table `electricity_consumption`
--

DROP TABLE IF EXISTS `electricity_consumption`;
CREATE TABLE `electricity_consumption` (
  `CarID` int(9) NOT NULL,
  `Econsumption_City` decimal(4,2) NOT NULL,
  `Econsumption_Hwy` decimal(4,2) NOT NULL,
  `VehicleRange` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `electricity_consumption`
--

INSERT INTO `electricity_consumption` (`CarID`, `Econsumption_City`, `Econsumption_Hwy`, `VehicleRange`) VALUES
(2, '2.40', '1.40', 999);

-- --------------------------------------------------------

--
-- Table structure for table `emissions`
--

DROP TABLE IF EXISTS `emissions`;
CREATE TABLE `emissions` (
  `CarID` int(9) NOT NULL,
  `gas_emissions` int(3) NOT NULL,
  `CO2_Index` int(1) NOT NULL,
  `Smog_Index` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `emissions`
--

INSERT INTO `emissions` (`CarID`, `gas_emissions`, `CO2_Index`, `Smog_Index`) VALUES
(1, 200, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `engine`
--

DROP TABLE IF EXISTS `engine`;
CREATE TABLE `engine` (
  `CarID` int(9) NOT NULL,
  `engine_size` int(3) NOT NULL,
  `HP` int(3) NOT NULL,
  `Transmission` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `engine`
--

INSERT INTO `engine` (`CarID`, `engine_size`, `HP`, `Transmission`) VALUES
(1, 200, 1, 'TEST'),
(2, 100, 2, '123A');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_consumption`
--

DROP TABLE IF EXISTS `fuel_consumption`;
CREATE TABLE `fuel_consumption` (
  `CarID` int(9) NOT NULL,
  `Consumption_City` decimal(4,2) NOT NULL,
  `Consumption_Hwy` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_consumption`
--

INSERT INTO `fuel_consumption` (`CarID`, `Consumption_City`, `Consumption_Hwy`) VALUES
(1, '1.20', '10.99');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `CarID` int(9) NOT NULL,
  `IsElectric` tinyint(1) DEFAULT NULL,
  `IsGas` tinyint(1) DEFAULT NULL,
  `size` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`CarID`, `IsElectric`, `IsGas`, `size`) VALUES
(1, 0, 1, 'Big'),
(2, 1, 0, 'Tiny');

-- --------------------------------------------------------

--
-- Table structure for table `value`
--

DROP TABLE IF EXISTS `value`;
CREATE TABLE `value` (
  `CarID` int(9) NOT NULL,
  `Price` decimal(9,2) NOT NULL,
  `Tax` decimal(5,2) NOT NULL,
  `Incentives` decimal(7,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `value`
--

INSERT INTO `value` (`CarID`, `Price`, `Tax`, `Incentives`) VALUES
(1, '12000.95', '25.00', NULL),
(2, '25000.99', '30.00', '5000.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`CarID`);

--
-- Indexes for table `electricity_consumption`
--
ALTER TABLE `electricity_consumption`
  ADD UNIQUE KEY `CarID` (`CarID`);

--
-- Indexes for table `emissions`
--
ALTER TABLE `emissions`
  ADD UNIQUE KEY `CarID` (`CarID`);

--
-- Indexes for table `engine`
--
ALTER TABLE `engine`
  ADD UNIQUE KEY `CarID` (`CarID`);

--
-- Indexes for table `fuel_consumption`
--
ALTER TABLE `fuel_consumption`
  ADD UNIQUE KEY `CarID` (`CarID`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD UNIQUE KEY `CarID` (`CarID`);

--
-- Indexes for table `value`
--
ALTER TABLE `value`
  ADD UNIQUE KEY `CarID` (`CarID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `CarID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `electricity_consumption`
--
ALTER TABLE `electricity_consumption`
  ADD CONSTRAINT `electric_FK` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`);

--
-- Constraints for table `emissions`
--
ALTER TABLE `emissions`
  ADD CONSTRAINT `emissions_FK` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`);

--
-- Constraints for table `engine`
--
ALTER TABLE `engine`
  ADD CONSTRAINT `engine_FK` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`);

--
-- Constraints for table `fuel_consumption`
--
ALTER TABLE `fuel_consumption`
  ADD CONSTRAINT `fuel_FK` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`);

--
-- Constraints for table `type`
--
ALTER TABLE `type`
  ADD CONSTRAINT `type_FK` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`);

--
-- Constraints for table `value`
--
ALTER TABLE `value`
  ADD CONSTRAINT `value_FK` FOREIGN KEY (`CarID`) REFERENCES `car` (`CarID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
