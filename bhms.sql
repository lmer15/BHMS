-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 12:01 AM
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

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `tenant_id`, `room_id`, `booking_start_date`, `booking_end_date`, `status`) VALUES
(91, 123, 27, '2024-12-05', '2024-12-11', 'Booked');

-- --------------------------------------------------------

--
-- Table structure for table `business_profiles`
--

CREATE TABLE `business_profiles` (
  `id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `business_acronym` varchar(255) NOT NULL,
  `business_logo` varchar(255) NOT NULL,
  `business_email` varchar(255) NOT NULL,
  `business_phone` varchar(20) NOT NULL,
  `business_address` text NOT NULL,
  `business_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_profiles`
--

INSERT INTO `business_profiles` (`id`, `business_name`, `business_acronym`, `business_logo`, `business_email`, `business_phone`, `business_address`, `business_description`, `created_at`, `updated_at`) VALUES
(4, 'Boarding House Managemnet System', 'BHMS', '../../uploads/business_logos/6758ab71d56e1_GREEN_LOGO.png', 'bhmsbusiness@gmail.com', '9068387448', '6QVV+VQF, Natalio B. Bacalso S National Hwy, Minglanilla, Cebu', 'BHMS is a trusted provider of safe and comfortable accommodations for individuals seeking a home away from home. Established in decade, we cater to students, professionals, and travelers by offering well-maintained facilities, convenient amenities, and a welcoming environment. Our mission is to provide affordable housing solutions while ensuring a sense of community and security for all our residents. Whether you\'re looking for short-term stays or long-term arrangements, BHMS is committed to making your stay a pleasant and hassle-free experience.', '2024-12-10 20:58:25', '2024-12-10 20:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `request_id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `room_id` varchar(20) DEFAULT NULL,
  `issue_description` text DEFAULT NULL,
  `request_date` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  `assigned_staff_id` int(11) DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `completion_date` datetime DEFAULT NULL
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
  `payment_transactions` varchar(200) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `payment_type` varchar(20) NOT NULL,
  `payment_status` enum('Paid','Pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile_picture`
--

CREATE TABLE `profile_picture` (
  `pp_ID` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `image_path` varchar(200) NOT NULL,
  `date_uploaded` date NOT NULL
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
(20, '674fd453c2c10_ROOM1.jpg', 'RM201', 'Family', 50, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 10000.00, 'Monthly', 12000.00, 'available'),
(21, '674fd488a5ae4_ROOM2.jpg', 'RM202', 'Double', 25, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 5000.00, 'Monthly', 7000.00, 'available'),
(22, '674fd4ca469ce_ROOM3.jfif', 'RM203', 'Double', 50, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 8000.00, 'Monthly', 10000.00, 'available'),
(23, '674fd4f5837e4_ROOM4.jfif', 'RM204', 'Double', 60, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 8000.00, 'Monthly', 10000.00, 'available'),
(24, '674fd54a351d8_ROOM5.jfif', 'RM205', 'Family', 70, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 12000.00, 'Monthly', 15000.00, 'available'),
(25, '674fd5a376cd6_ROOM6.jfif', 'RM206', 'Single', 30, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 7000.00, 'Monthly', 7500.00, 'available'),
(26, '674fd5e1e2774_ROOM7.jpg', 'RM207', 'Single', 20, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 5000.00, 'Monthly', 6000.00, 'available'),
(27, '674fd60ff3e9b_ROOM8.jpg', 'RM208', 'Double', 40, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 7500.00, 'Monthly', 8300.00, 'reserved');

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

--
-- Dumping data for table `tenant_details`
--

INSERT INTO `tenant_details` (`tc_id`, `id`, `fname`, `lname`, `gender`, `number_of_occupants`, `email_address`, `contact_number`, `religion`, `nationality`, `occupation`) VALUES
(123, 93, 'Elmer', 'Solitario Rapon Gwapo', 'male', 2, 'raponelmer15@gmail.com', '09068387448', 'Catholic', 'Filipino', 'Student'),
(124, 95, 'Elmer', 'Rapon', '', 0, 'raponelmer14@gmail.com', '09068387448', '', '', '');

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
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `username`, `password`, `status`, `type`) VALUES
(93, 'lmer16', '$2y$10$DteacS/lwLkx7IIXg7fFFe2mcc7rrAIWZWjQMqFcpisG1qGd2xKe6', 'approved', 'tenant'),
(95, 'lmer14', '$2y$10$/0PMwvIFPHlcK1xCmXhn5OXrcgnUIGI/O7opE7K9odUhxTJrgJN/W', 'active', 'admin');

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
-- Indexes for table `business_profiles`
--
ALTER TABLE `business_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `business_email` (`business_email`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `tenant_id` (`tenant_id`);

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
-- Indexes for table `profile_picture`
--
ALTER TABLE `profile_picture`
  ADD PRIMARY KEY (`pp_ID`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `business_profiles`
--
ALTER TABLE `business_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `profile_picture`
--
ALTER TABLE `profile_picture`
  MODIFY `pp_ID` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rental_payments`
--
ALTER TABLE `rental_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tenant_details`
--
ALTER TABLE `tenant_details`
  MODIFY `tc_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

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
-- Constraints for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD CONSTRAINT `maintenance_requests_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenant_details` (`tc_id`);

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
-- Constraints for table `profile_picture`
--
ALTER TABLE `profile_picture`
  ADD CONSTRAINT `profile_picture_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tenant_details` (`tc_id`);

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
