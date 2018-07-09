-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 09, 2018 at 07:14 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id6432065_door`
--

-- --------------------------------------------------------

--
-- Table structure for table `RFIDCARD`
--

CREATE TABLE `RFIDCARD` (
  `RFID` varchar(8) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `RFIDCARD`
--

INSERT INTO `RFIDCARD` (`RFID`) VALUES
('♀↨↑↓→');

-- --------------------------------------------------------

--
-- Table structure for table `TIMETEMPLATE`
--

CREATE TABLE `TIMETEMPLATE` (
  `IDTIME` varchar(10) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `TIMESTART` time NOT NULL,
  `TIMEEND` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USERINFO`
--

CREATE TABLE `USERINFO` (
  `MSSV` int(10) NOT NULL,
  `TEN` varchar(50) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `KHOA` varchar(50) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `GHICHU` varchar(200) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USERUSAGE`
--

CREATE TABLE `USERUSAGE` (
  `MSSV` int(10) NOT NULL,
  `IDTIME` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `RFID` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `GHICHU` varchar(200) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `TIMETEMPLATE`
--
ALTER TABLE `TIMETEMPLATE`
  ADD PRIMARY KEY (`IDTIME`);

--
-- Indexes for table `USERINFO`
--
ALTER TABLE `USERINFO`
  ADD PRIMARY KEY (`MSSV`);

--
-- Indexes for table `USERUSAGE`
--
ALTER TABLE `USERUSAGE`
  ADD PRIMARY KEY (`MSSV`,`IDTIME`,`RFID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
