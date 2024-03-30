-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2024 at 06:37 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jayrold`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `punch_date` date DEFAULT NULL,
  `punch_in` datetime DEFAULT NULL,
  `punch_out` datetime DEFAULT NULL,
  `status` enum('pending','present') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `punch_date`, `punch_in`, `punch_out`, `status`) VALUES
(1, 2, '2024-03-28', '2024-03-28 09:07:24', '2024-03-28 09:07:27', 'present'),
(2, 7, '2024-03-28', '2024-03-28 13:47:42', '2024-03-28 13:47:50', 'present'),
(3, 10, '2024-03-28', '2024-03-28 16:11:27', '2024-03-28 16:11:30', 'present'),
(4, 11, '2024-03-28', '2024-03-28 17:34:37', '2024-03-28 17:38:50', 'present'),
(5, 12, '2024-03-28', '2024-03-28 17:43:50', '2024-03-28 18:21:42', 'present'),
(6, 13, '2024-03-28', '2024-03-28 18:18:56', '2024-03-28 18:43:09', 'present'),
(7, 13, '2024-03-29', '2024-03-29 13:10:43', '2024-03-29 08:46:45', 'present'),
(8, 2, '2024-03-29', '2024-03-29 13:11:01', '2024-03-29 13:19:45', 'present'),
(9, 12, '2024-03-29', '2024-03-29 13:11:26', '2024-03-29 01:29:58', 'present'),
(10, 7, '2024-03-29', '2024-03-29 13:11:44', '2024-03-29 13:24:05', 'present'),
(11, 11, '2024-03-29', '2024-03-29 13:12:43', NULL, 'pending'),
(12, 2, '2024-03-29', '2024-03-29 20:22:00', NULL, 'pending'),
(13, 2, '2024-03-29', '2024-03-29 20:22:03', NULL, 'pending'),
(14, 10, '2024-03-29', '2024-03-29 08:24:50', '2024-03-29 13:25:00', 'present'),
(15, 14, '2024-03-29', '2024-03-29 08:30:37', '2024-03-29 01:30:45', 'present'),
(16, 15, '2024-03-29', '2024-03-29 08:39:25', '2024-03-29 08:39:29', 'present'),
(17, 16, '2024-03-29', '2024-03-29 08:41:19', '2024-03-29 08:41:22', 'present'),
(18, 2, '2024-03-30', '2024-03-30 01:15:05', '2024-03-30 01:15:11', 'present');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` enum('regular','admin') NOT NULL,
  `resume_status` enum('uploaded','not_uploaded') DEFAULT 'not_uploaded',
  `resume_file` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `account_type`, `resume_status`, `resume_file`) VALUES
(2, 'brime', 'lasting', 'brimelasting0@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'regular', 'uploaded', ''),
(7, 'test2', 'sample', 'jayr.tabalina.ui@phinmaed.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'regular', 'uploaded', ''),
(9, 'Admin', 'User', 'admin1@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'admin', 'not_uploaded', NULL),
(10, 'qwerty', 'uiop', 'qwerty@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'regular', 'not_uploaded', NULL),
(11, 'Yasmine', 'talaman', 'yasmine@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'regular', 'uploaded', ''),
(12, 'jayrold', 'tabalina', 'jayroldtabalina@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'regular', 'not_uploaded', NULL),
(13, 'jay', 'rold', 'jay@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'regular', 'not_uploaded', NULL),
(14, 'Yasmine', 'talaman', 'abc@yahoo.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'regular', 'not_uploaded', NULL),
(15, 'jay', 'jay', 'jay@yahoo.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'regular', 'not_uploaded', NULL),
(16, 'jayrold tabalina', 'tabalina', 'jay1@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'regular', 'not_uploaded', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
