-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 05:57 AM
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
-- Table structure for table `tenant_accounts`
--

CREATE TABLE `tenant_accounts` (
  `tc_id` int(10) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `email_address` varchar(100) NOT NULL,
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

INSERT INTO `tenant_accounts` (`tc_id`, `fname`, `lname`, `gender`, `email_address`, `contact_number`, `password`, `religion`, `nationality`, `occupation`, `status`) VALUES
(23, 'Elmer', 'Rapon', '', 'raponelmer15@gmail.com', '09107998581', '$2y$10$p4C8KqdAW7lji294FV1WROqWd4wCOe1Zd5DsLIK2LXfCcF1fhsMsm', '', '', '', 'reservee'),
(24, 'SANDRIA', 'CADUSALE', '', 'sandriacadusale@gmail.com', '09438284163', '$2y$10$CAa6e539JzGQ7ZHpQvgdNeu5n.4zrhWRPB9Wii9SoRw2G7or7SNLq', '', '', '', 'reservee');

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
-- AUTO_INCREMENT for table `tenant_accounts`
--
ALTER TABLE `tenant_accounts`
  MODIFY `tc_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
