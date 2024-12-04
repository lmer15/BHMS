-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 01:15 AM
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
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `room_id` int(20) NOT NULL,
  `booking_start_date` date NOT NULL,
  `booking_end_date` date NOT NULL,
  `status` enum('Booked','Completed','Cancelled') DEFAULT 'Booked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `not_id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `history_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('Paid','Pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rental_payments`
--

CREATE TABLE `rental_payments` (
  `payment_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `rent_period_start` date NOT NULL,
  `rent_period_end` date NOT NULL,
  `total_rent` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `balance` decimal(10,2) GENERATED ALWAYS AS (`total_rent` - `amount_paid`) STORED,
  `payment_date` date DEFAULT NULL,
  `status` enum('Pending','Partially Paid','Paid','Overdue') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `room_payfre` varchar(50) NOT NULL DEFAULT 'Monthly',
  `room_deporate` decimal(50,2) NOT NULL,
  `room_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_image`, `room_number`, `room_type`, `room_size`, `room_aminities`, `room_utilities`, `rental_rates`, `room_payfre`, `room_deporate`, `room_status`) VALUES
(12, '674ce834f3a5d_RM2.jpg', 'RM201', 'Double', 25, 'fgdfthfyj', 'uigyuify', 6000.00, 'Monthly', 50000.00, 'available'),
(13, '674ceace684df_RM3.jpg', 'RM202', 'Single', 25, 'RJKHSDGRBJKLNHJ', 'RGJGSO;ERIGJTHKLM', 25600.00, 'Monthly', 5000.00, 'available'),
(14, '674ced6871dcb_RM4.jpg', 'RM203', 'Double', 25, 'RTHFTIT8YLO', '8TYOTUILHJLG', 3566.00, 'Monthly', 5676.00, 'available'),
(15, '674cedfc5626a_RM5.jpg', 'RM204', 'Single', 25, 'RGDTRYGFUJFRYU', 'UIOIYHIYUILJIL', 5667.00, 'Monthly', 476577.00, 'available'),
(16, '674cf979ab697_RM5.jpg', 'RM205', 'Double', 25, 'TRHFGKGHOLTUG', 'FYIJKYHJIOLYUIOLIHO', 3454.00, 'Monthly', 5676.00, 'available'),
(17, '674cfe664a85f_RM6.jpg', 'RM206', 'Single', 40, 't5tydtryftikygukhjlho', 'fykfiljfhdfm/lgh/', 5000.00, 'Monthly', 5666.00, 'available'),
(18, '674d5b4aa5723_RM7.jpg', 'RM207', 'Single', 80, 'KJHSRGKHIOYRTUYHUYT7IKKYLTYDYTAJKDRHTNEK;EARGIMDKLFHMG;LDRPJIORHYJMF;TUKT;LKRYUK[', 'RTYOKHTRYHLR[PIORPYKRE[\'PTKOOWEUJWEOINHDFKLGMDYJM;DYHRTHF;KGEW', 5000.00, 'Monthly', 6000.00, 'available'),
(19, '674dc241c887d_RM8.jpg', 'RM208', 'Suite', 25, 'euhsdffzxczxcvxcvxcvnmxcvnmdfcdjksdfjksdfjkdfjifioeuiowehioiotuweruwrejiohjklerjklgtrlgtr', 'rghleoprweriopweriorj;jklgrjklgtrjkltrjio;iujoptopttykkpk', 50000.00, 'Monthly', 700000.00, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_details`
--

CREATE TABLE `tenant_details` (
  `tc_id` int(10) NOT NULL,
  `id` int(20) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `number_of_occupants` int(20) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `occupation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` int(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `status` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL
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
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`not_id`),
  ADD KEY `id` (`user`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `rental_payments`
--
ALTER TABLE `rental_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `tenant_details`
--
ALTER TABLE `tenant_details`
  ADD PRIMARY KEY (`tc_id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aminities_services`
--
ALTER TABLE `aminities_services`
  MODIFY `amiser_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `not_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rental_payments`
--
ALTER TABLE `rental_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tenant_details`
--
ALTER TABLE `tenant_details`
  MODIFY `tc_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenant_details` (`tc_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `rental_payments` (`payment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_history_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenant_details` (`tc_id`) ON DELETE CASCADE;

--
-- Constraints for table `rental_payments`
--
ALTER TABLE `rental_payments`
  ADD CONSTRAINT `rental_payments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenant_details` (`tc_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenant_details`
--
ALTER TABLE `tenant_details`
  ADD CONSTRAINT `tenant_details_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user_accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
