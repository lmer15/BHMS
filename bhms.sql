-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 11:41 PM
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
-- Database: `bhms`
--

-- --------------------------------------------------------

--
-- Table structure for table `aminities_services`
--

CREATE TABLE `aminities_services` (
  `amiser_id` int(20) NOT NULL,
  `amiser_title` varchar(50) NOT NULL,
  `amiser_desc` text NOT NULL,
  `amiser_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `management_account`
--

CREATE TABLE `management_account` (
  `manage_ID` int(20) NOT NULL,
  `manage_email` varchar(50) NOT NULL,
  `manage_password` varchar(200) NOT NULL,
  `manage_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `management_account`
--

INSERT INTO `management_account` (`manage_ID`, `manage_email`, `manage_password`, `manage_status`) VALUES
(1, 'management@gmail.com', 'management', 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(20) NOT NULL,
  `room_image` varchar(200) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `room_type` varchar(20) NOT NULL,
  `room_size` int(100) NOT NULL,
  `room_aminities` text NOT NULL,
  `room_utilities` text NOT NULL,
  `rental_rates` decimal(50,2) NOT NULL,
  `room_payfre` varchar(100) NOT NULL,
  `room_deporate` decimal(50,2) NOT NULL,
  `room_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_accounts`
--

CREATE TABLE `tenant_accounts` (
  `tc_id` int(10) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_accounts`
--

INSERT INTO `tenant_accounts` (`tc_id`, `fname`, `lname`, `gender`, `email_address`, `username`, `contact_number`, `password`, `religion`, `nationality`, `occupation`, `status`) VALUES
(31, 'SANDRIA', 'CADUSALE', '', 'sandriacadusale@gmail.com', 'lmer15', '09438284163', '$2y$10$2nUMkDSGLDgpl627UDqQYeDd7VoA/6AKWA3KW21s7uDizniysxEaa', '', '', '', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_profile`
--

CREATE TABLE `tenant_profile` (
  `t_ID` int(11) NOT NULL,
  `tc_ID` int(11) NOT NULL,
  `t_img` varchar(200) NOT NULL,
  `t_dateUplaod` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aminities_services`
--
ALTER TABLE `aminities_services`
  ADD PRIMARY KEY (`amiser_id`);

--
-- Indexes for table `management_account`
--
ALTER TABLE `management_account`
  ADD PRIMARY KEY (`manage_ID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `tenant_accounts`
--
ALTER TABLE `tenant_accounts`
  ADD PRIMARY KEY (`tc_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `tenant_profile`
--
ALTER TABLE `tenant_profile`
  ADD PRIMARY KEY (`t_ID`),
  ADD KEY `tc_id` (`tc_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aminities_services`
--
ALTER TABLE `aminities_services`
  MODIFY `amiser_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `management_account`
--
ALTER TABLE `management_account`
  MODIFY `manage_ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_accounts`
--
ALTER TABLE `tenant_accounts`
  MODIFY `tc_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tenant_profile`
--
ALTER TABLE `tenant_profile`
  MODIFY `t_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tenant_profile`
--
ALTER TABLE `tenant_profile`
  ADD CONSTRAINT `tc_id` FOREIGN KEY (`tc_ID`) REFERENCES `tenant_profile` (`t_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
