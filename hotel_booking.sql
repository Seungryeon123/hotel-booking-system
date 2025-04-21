-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2025 at 05:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `name`) VALUES
(1, 'admin', 'admin123', 'Admin Utama');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `nights` int(3) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Dalam Proses',
  `room_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `hotel_id`, `check_in`, `check_out`, `nights`, `status`, `room_id`) VALUES
(1, 5, 10, '2025-04-14', '2025-04-16', NULL, 'Selesai', 0),
(2, 1, 1, '2025-04-15', '2025-04-16', NULL, 'Selesai', 0),
(3, 1, 6, '2025-04-16', '2025-04-23', NULL, 'Dalam Proses', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `hotel_name`, `price`, `room_type`, `location`, `rating`, `image`, `review`) VALUES
(1, 'Putra Regency Hotel', 120.00, 'Deluxe', 'Kangar, Perlis', 0.0, NULL, 'Hotel ini sangat selesa dan bersih.'),
(2, 'Hotel Seri Malaysia', 100.00, 'Standard', 'Kangar, Perlis', 0.0, NULL, NULL),
(3, 'T Hotel', 90.00, 'Single', 'Kangar, Perlis', 0.0, NULL, NULL),
(5, 'Sri Garden Hotel', 150.00, 'Standard', 'Kangar', 0.0, NULL, NULL),
(6, 'Hotel Seri Malaysia', 180.00, 'Suite', 'Kangar', 0.0, NULL, NULL),
(7, 'Hotel Noor', 120.00, 'Standard', 'Kangar', 0.0, NULL, NULL),
(8, 'Hotel Seberang Jaya', 100.00, 'Budget', 'Kangar', 0.0, NULL, NULL),
(9, 'T Hotel', 130.00, 'Standard', 'Kangar', 0.0, NULL, NULL),
(10, 'Ants Hotel', 170.00, 'Deluxe', 'Kangar', 0.0, NULL, NULL),
(11, 'Savana Hotel & Serviced Apartments', 220.00, 'Suite', 'Kangar', 0.0, NULL, NULL),
(12, 'De Mesra Villa', 190.00, 'Deluxe', 'Kangar', 0.0, NULL, NULL),
(13, 'Metro Hotel', 160.00, 'Standard', 'Kangar', 0.0, NULL, NULL),
(14, 'Putra Regency Hotel', 200.00, 'Deluxe', 'Kangar', 0.0, NULL, NULL),
(15, 'Sri Garden Hotel', 150.00, 'Standard', 'Kangar', 0.0, NULL, NULL),
(16, 'Hotel Seri Malaysia', 180.00, 'Suite', 'Kangar', 0.0, NULL, NULL),
(17, 'Hotel Noor', 120.00, 'Standard', 'Kangar', 0.0, NULL, NULL),
(18, 'Hotel Seberang Jaya', 100.00, 'Budget', 'Kangar', 0.0, NULL, NULL),
(19, 'T Hotel', 130.00, 'Standard', 'Kangar', 0.0, NULL, NULL),
(20, 'Ants Hotel', 170.00, 'Deluxe', 'Kangar', 0.0, NULL, NULL),
(21, 'Savana Hotel & Serviced Apartments', 220.00, 'Suite', 'Kangar', 0.0, NULL, NULL),
(22, 'De Mesra Villa', 190.00, 'Deluxe', 'Kangar', 0.0, NULL, NULL),
(23, 'Metro Hotel', 160.00, 'Standard', 'Kangar', 0.0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `review` text NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_type` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `hotel_id`, `room_type`, `price`) VALUES
(1, 1, 'Single Room', 100.00),
(2, 1, 'Double Room', 150.00),
(3, 2, 'Deluxe Room', 200.00),
(4, 2, 'Suite', 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`) VALUES
(1, 'aiman haikal', 'aiman', 'aimanhaikal468@gmail.com', '1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
