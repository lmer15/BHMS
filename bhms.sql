-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2024 at 01:49 PM
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

--
-- Dumping data for table `aminities_services`
--

INSERT INTO `aminities_services` (`amiser_id`, `amiser_title`, `amiser_desc`, `amiser_status`) VALUES
(2, '24/7 Security', 'Round-the-clock security services to ensure the safety and peace of mind of all residents.', 'aminities'),
(3, 'Common Kitchen', ' A fully equipped kitchen for residents to prepare their own meals with shared cooking facilities.', 'aminities'),
(4, 'Hot and Cold Water Supply', 'Continuous supply of hot and cold water to all rooms and common areas for comfort and convenience.', 'aminities'),
(5, 'Air Conditioning', 'Air-conditioned rooms to provide a comfortable living environment during hot weather.', 'aminities'),
(6, 'Study Area', 'A quiet, well-lit area designed for students or professionals to focus on their studies or work.', 'aminities'),
(7, 'Trash Disposal', 'Regular waste collection services to maintain cleanliness and hygiene in the building.', 'aminities'),
(8, 'Room Cleaning Services', ' Regular cleaning of rooms and common areas to ensure a tidy and comfortable living space.', 'aminities'),
(9, 'Fire Safety Equipment', 'Fire extinguishers, alarms, and other safety measures to ensure the safety of all residents in case of emergency.', 'aminities');

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
(91, 123, 27, '2024-12-05', '2024-12-11', 'Booked'),
(92, 125, 36, '2024-12-12', '2024-12-12', 'Booked'),
(93, 126, 35, '2024-12-12', '2024-12-12', 'Booked');

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
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_name` varchar(255) DEFAULT NULL,
  `item_desc` text DEFAULT NULL,
  `status` enum('Pending','Done','Ongoing','Declined') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `tenant_id`, `date_requested`, `item_name`, `item_desc`, `status`) VALUES
(26, 123, '2024-12-12 06:33:36', 'Bathroom', 'The bathroom is clog.', 'Done'),
(29, 123, '2024-12-12 06:41:07', 'Table', 'The other leg of the table is broken', 'Ongoing'),
(36, 123, '2024-12-12 07:00:27', 'Ceiling', 'There\'s a leak in the ceiling. Need immediate repair.', 'Pending'),
(37, 123, '2024-12-12 07:05:25', 'Wifi', 'There\'s no internet connection.', 'Pending');

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

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`not_id`, `user`, `type`, `message`, `status`, `created_at`, `updated_at`) VALUES
(19, 123, 'maintenance', 'Elmer Rapon from RM208 submitted a new maintenance request to fix his/her Wifi.', '', '2024-12-12 15:05:25', '2024-12-12 15:05:25'),
(20, 123, 'maintenance', 'Elmer Rapon from RM208 deleted his request to fix his \'Clock\' that was submitted on 2024-12-12 14:41:32.', '', '2024-12-12 15:14:49', '2024-12-12 15:14:49');

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
  `payment_type` varchar(20) NOT NULL,
  `payment_status` enum('Paid','Pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`history_id`, `payment_id`, `tenant_id`, `payment_date`, `payment_amount`, `balance`, `payment_type`, `payment_status`) VALUES
(5, 11, 126, '2024-12-12', 6000.00, 0.00, 'deposit', '');

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

--
-- Dumping data for table `rental_payments`
--

INSERT INTO `rental_payments` (`payment_id`, `tenant_id`, `rent_period_start`, `rent_period_end`, `total_rent`, `amount_paid`, `payment_date`, `status`) VALUES
(11, 126, '2024-12-12', '2024-12-12', 6000.00, 6000.00, '2024-12-12', 'Paid');

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
(27, '674fd60ff3e9b_ROOM8.jpg', 'RM208', 'Double', 40, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 7500.00, 'Monthly', 8300.00, 'reserved'),
(31, '6759519ccf008_ROOM2.jpg', 'RM202', 'Single', 25, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 8000.00, 'Monthly', 10000.00, 'available'),
(32, '675951e548e5f_ROOM3.jfif', 'RM203', 'Double', 60, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 12000.00, 'Monthly', 13500.00, 'available'),
(33, '675952086def5_ROOM4.jfif', 'RM204', 'Double', 60, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 8000.00, 'Monthly', 10000.00, 'available'),
(34, '6759524492c1d_ROOM5.jfif', 'RM205', 'Family', 60, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 7000.00, 'Monthly', 8050.00, 'available'),
(35, '6759527057186_ROOM6.jfif', 'RM206', 'Double', 49, 'Bed, mattress, desk, chair, closet, shelving, mirror, curtains, trash bin, dining table, sofa, side table, wardrobe, wall hooks, bookshelf, bulletin board, rug, pillows, blanket.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 50000.00, 'Monthly', 6000.00, 'occupied'),
(36, '675952cb2a16b_ROOM9.jpg', 'RM207', 'Family', 90, 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 'Electric fan, air conditioner, lighting, power outlets, Wi-Fi, internet access, private/shared bathroom, laundry area access, water heater, refrigerator, microwave, stove, sink, exhaust fan, smoke detector, fire extinguisher.', 15000.00, 'Monthly', 18000.00, 'reserved');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_details`
--

CREATE TABLE `tenant_details` (
  `tc_id` int(10) NOT NULL,
  `id` int(20) NOT NULL,
  `profile` varchar(200) NOT NULL,
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

INSERT INTO `tenant_details` (`tc_id`, `id`, `profile`, `fname`, `lname`, `gender`, `number_of_occupants`, `email_address`, `contact_number`, `religion`, `nationality`, `occupation`) VALUES
(123, 93, 'profile_6759b75d33c2f5.18618288.png', 'Elmer', 'Rapon', 'male', 2, 'raponelmer15@gmail.com', '09068387448', 'Catholic', 'Filipino', 'Student'),
(125, 96, '', 'Heizl', 'Alucard', '', 5, 'lemonjuice@gmail.com', '09107998581', '', '', ''),
(126, 97, '', 'Josh', 'Almendras', '', 2, 'almedras@gmail.com', '09108770753', '', '', '');

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
(95, 'lmer14', '$2y$10$/0PMwvIFPHlcK1xCmXhn5OXrcgnUIGI/O7opE7K9odUhxTJrgJN/W', 'active', 'admin'),
(96, 'lemon', '$2y$10$9xjdP/rw9NwiilbGsz.qcebxlfUbqbR3s8ax8bfXpM6GzWyBq6KHK', 'pending', 'tenant'),
(97, 'josh123', '$2y$10$/lWofq82Nkaie9Oqi8pkCuH9x89Ra4a0hRWvmV8FeYzIK2pmtvXEq', 'approved', 'tenant');

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
  ADD PRIMARY KEY (`id`),
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
  MODIFY `amiser_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `business_profiles`
--
ALTER TABLE `business_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `not_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rental_payments`
--
ALTER TABLE `rental_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tenant_details`
--
ALTER TABLE `tenant_details`
  MODIFY `tc_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

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
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user`) REFERENCES `tenant_details` (`tc_id`) ON DELETE CASCADE;

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
