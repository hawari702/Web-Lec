-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 05:06 PM
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
-- Database: `concert_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `concerts`
--

CREATE TABLE `concerts` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `location` text NOT NULL,
  `max_participants` int(11) DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concerts`
--

INSERT INTO `concerts` (`id`, `name`, `date`, `location`, `max_participants`, `image`, `description`) VALUES
(13, 'Arash Buana', '2024-10-14', 'UMN', 500, 'uploads/arash.jpg', 'Arash Buana bakal bawain lagu-lagu kesukaanmu nihh'),
(17, 'Lomba Sihir', '2024-10-15', 'UMN', 5100, 'uploads/lomba_sihir.jpg', 'Selamanya, Ribuan Memori'),
(19, 'Malam Puncak', '2024-11-19', 'UMN', 1000, 'uploads/malampuncak.jpg', 'Malam puncak UMN spesial event UMN'),
(35, 'Halloween Party', '2024-10-30', 'Lapangan Parkir UMN', 500, 'uploads/halowin.jpg', 'meriahkan halloween bersama teman-temanmu!'),
(51, 'Hivi', '2024-10-25', 'UMN', 2, 'uploads/hivi.jpg', 'konser buat yenca sama joey doang dah'),
(59, 'dikta', '2024-10-24', 'UMN', 22, 'uploads/dikta.jpeg', 'adasd');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `max_participants` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `concert_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `jumlah_tiket` int(11) NOT NULL,
  `nomor_hp` varchar(15) NOT NULL,
  `bukti_transfer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `concert_id`, `name`, `email`, `registration_date`, `jumlah_tiket`, `nomor_hp`, `bukti_transfer`) VALUES
(45, 51, 'Natan Adi Chandra', 'natan@example.com', '2024-10-24 13:21:07', 1, '123', 'uploads/taylor.jpeg'),
(46, 51, 'yenca stres', 'wakwaw@example.com', '2024-10-24 13:21:39', 1, '123', 'uploads/taylor.jpeg'),
(47, 17, 'Natan Adi Chandra', 'natan@example.com', '2024-10-24 13:24:22', 1, '123', 'uploads/taylor.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`) VALUES
(5, 'natan', '$2y$10$MbX9CiO7q4jhWeYlh010oO3qdOik002Dy..Jd3iF.MEAoBVTFpbOu', 'admin', '2024-10-14 04:53:19', 'admin@gmail.com'),
(9, 'ujang', '$2y$10$NCWiODNyGOLRsyHksYbq3.SXtyUK4TmSn2550n32LZnJi3RhfYq22', 'user', '2024-10-14 07:05:00', ''),
(11, 'marco', '$2y$10$z.Tfubj5MC4hg9oxTvH7tu0kFgyO5BuEOeKhKgYjg4S5oWlbg9vH6', 'user', '2024-10-14 10:36:43', ''),
(12, 'omen', '$2y$10$NedBFbxpuMr/dRYJwU/p8ut8lJZp5h2toYryP2a0DYGKLzXg1CvtW', 'user', '2024-10-20 15:56:29', ''),
(13, 'admin', '$2y$10$h6av5BP20ShclAxLr5heteeMGaL8nrnSn2bMUCylPMPKZPt5hxjqG', 'admin', '2024-10-21 17:29:46', ''),
(14, 'ubur ubur', '$2y$10$.w49Xn09yp8a4Xdc4fT4Xe3MNwuNVC/yldbAhLwEbCPsaevVvYWCS', 'user', '2024-10-21 17:32:01', ''),
(16, 'caca', '$2y$10$9Rl0yGJmqiAIocogzpNvkusTyLYBX2AtvNZOe3azuXW9Ynttuhaoy', 'user', '2024-10-22 15:55:07', ''),
(19, 'user', '$2y$10$6HSxo5n3Bs9mCY1BLyBpAOdQyikIiLAzNUfYXZdH6aCaNsGwHbYO6', 'user', '2024-10-24 13:42:31', 'user@example.com'),
(20, 'ubur ubur', '$2y$10$ZnYayCPhdPzqnjAdzpojZ.yu7MgxMZeK8dMa4N.SyY9k04eOdORIi', 'user', '2024-10-24 13:48:50', 'uburubur@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `user_concerts`
--

CREATE TABLE `user_concerts` (
  `user_id` int(11) NOT NULL,
  `concert_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `concerts`
--
ALTER TABLE `concerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `concert_id` (`concert_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_concerts`
--
ALTER TABLE `user_concerts`
  ADD PRIMARY KEY (`user_id`,`concert_id`),
  ADD KEY `concert_id` (`concert_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `concerts`
--
ALTER TABLE `concerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`concert_id`) REFERENCES `concerts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_concerts`
--
ALTER TABLE `user_concerts`
  ADD CONSTRAINT `user_concerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `registrations` (`id`),
  ADD CONSTRAINT `user_concerts_ibfk_2` FOREIGN KEY (`concert_id`) REFERENCES `concerts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
