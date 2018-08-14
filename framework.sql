-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 14, 2018 at 12:47 PM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `framework`
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `USERNAME` varchar(20) COLLATE utf8_vietnamese_ci NOT NULL,
  `PASSWORD` varchar(200) COLLATE utf8_vietnamese_ci NOT NULL,
  `FULLNAME` varchar(70) COLLATE utf8_vietnamese_ci NOT NULL,
  `BIRTHDAY` date NOT NULL,
  `GENDER` varchar(10) COLLATE utf8_vietnamese_ci NOT NULL,
  `ADDRESS` varchar(200) COLLATE utf8_vietnamese_ci NOT NULL,
  `EMAIL` varchar(80) COLLATE utf8_vietnamese_ci NOT NULL,
  `PHONE` varchar(15) COLLATE utf8_vietnamese_ci NOT NULL,
  `ROLE` varchar(100) COLLATE utf8_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USERUSAGE`
--

CREATE TABLE `USERUSAGE` (
  `MSSV` int(10) NOT NULL,
  `TIME` time(6) NOT NULL,
  `DAY` date NOT NULL,
  `RFID` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `GHICHU` varchar(200) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `USERUSAGE`
--

INSERT INTO `USERUSAGE` (`MSSV`, `TIME`, `DAY`, `RFID`, `GHICHU`) VALUES
(1520364, '00:00:00.000000', '2018-07-17', 'ABCD', 'đang test'),
(1928811, '00:00:00.000000', '0000-00-00', 'BBBB', 'dang test too'),
(12334521, '03:00:00.000000', '0000-00-00', 'BBGF', 'qwerty'),
(14520022, '18:00:00.000000', '0000-00-00', 'GHFE', '12345'),
(14520031, '16:18:33.000000', '0000-00-00', 'QWER', 'dang test'),
(15520054, '03:00:00.000000', '0000-00-00', 'AGGG', 'qwer'),
(15520331, '16:18:33.000000', '0000-00-00', 'WEQR', 'dang test'),
(15520345, '06:00:00.000000', '0000-00-00', 'WEQQ', 'asdf'),
(15520366, '00:00:00.000000', '0000-00-00', 'ABBB', 'Đang test'),
(15520456, '17:00:00.000000', '0000-00-00', 'RTER', 'dang test'),
(15526789, '00:00:00.000000', '0000-00-00', 'QQQQ', '123'),
(16599281, '15:00:00.000000', '0000-00-00', 'RRRR', '1234');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `USERUSAGE`
--
ALTER TABLE `USERUSAGE`
  ADD PRIMARY KEY (`MSSV`,`RFID`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
