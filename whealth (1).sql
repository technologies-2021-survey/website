-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2021 at 07:43 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whealth`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins_tbl`
--

CREATE TABLE `admins_tbl` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_username` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_emailaddress` varchar(255) NOT NULL,
  `admin_phonenumber` text NOT NULL,
  `admin_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `doctors_tbl`
--

CREATE TABLE `doctors_tbl` (
  `doctor_id` int(11) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `doctor_username` varchar(255) NOT NULL,
  `doctor_password` varchar(255) NOT NULL,
  `doctor_emailaddress` varchar(255) NOT NULL,
  `doctor_phonenumber` text NOT NULL,
  `doctor_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors_tbl`
--

INSERT INTO `doctors_tbl` (`doctor_id`, `doctor_name`, `doctor_username`, `doctor_password`, `doctor_emailaddress`, `doctor_phonenumber`, `doctor_code`) VALUES
(1, 'Harvey Arboleda', 'harvs', 'b5d1f1248f0a4ac1d9d023f73b4860ba', 'arboledaharvs@gmail.com', '09668429327', '');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries_tbl`
--

CREATE TABLE `inquiries_tbl` (
  `Inquiries_ID` int(11) NOT NULL,
  `Inquiries_title` varchar(255) NOT NULL,
  `Inquiries_body` varchar(255) NOT NULL,
  `Inquiries_Name` varchar(255) NOT NULL,
  `Inquiries_EmailAddress` varchar(255) NOT NULL,
  `Inquiries_PhoneNumber` varchar(255) NOT NULL,
  `Inquiries_DateTime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inquiries_tbl`
--

INSERT INTO `inquiries_tbl` (`Inquiries_ID`, `Inquiries_title`, `Inquiries_body`, `Inquiries_Name`, `Inquiries_EmailAddress`, `Inquiries_PhoneNumber`, `Inquiries_DateTime`) VALUES
(1, '123', '123', 'Harvey Tagalog Arboleda', 'arboledaharvs@gmail.com', '09668429327', 1630908704),
(2, 'title', 'message', 'Harvey Tagalog Arboleda', 'arboledaharvs@gmail.com', '09668429327', 1630908725);

-- --------------------------------------------------------

--
-- Table structure for table `parents_tbl`
--

CREATE TABLE `parents_tbl` (
  `parent_id` int(11) NOT NULL,
  `parent_name` varchar(255) NOT NULL,
  `parent_username` varchar(255) NOT NULL,
  `parent_password` varchar(255) NOT NULL,
  `parent_emailaddress` varchar(255) NOT NULL,
  `parent_phonenumber` text NOT NULL,
  `parent_code` text NOT NULL,
  `login_timeout` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receptionists_tbl`
--

CREATE TABLE `receptionists_tbl` (
  `receptionist_id` int(11) NOT NULL,
  `receptionist_name` varchar(255) NOT NULL,
  `receptionist_username` varchar(255) NOT NULL,
  `receptionist_password` varchar(255) NOT NULL,
  `receptionist_emailaddress` varchar(255) NOT NULL,
  `receptionist_phonenumber` text NOT NULL,
  `receptionist_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctors_tbl`
--
ALTER TABLE `doctors_tbl`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `inquiries_tbl`
--
ALTER TABLE `inquiries_tbl`
  ADD PRIMARY KEY (`Inquiries_ID`);

--
-- Indexes for table `parents_tbl`
--
ALTER TABLE `parents_tbl`
  ADD PRIMARY KEY (`parent_id`);

--
-- Indexes for table `receptionists_tbl`
--
ALTER TABLE `receptionists_tbl`
  ADD PRIMARY KEY (`receptionist_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors_tbl`
--
ALTER TABLE `doctors_tbl`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inquiries_tbl`
--
ALTER TABLE `inquiries_tbl`
  MODIFY `Inquiries_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parents_tbl`
--
ALTER TABLE `parents_tbl`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receptionists_tbl`
--
ALTER TABLE `receptionists_tbl`
  MODIFY `receptionist_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
