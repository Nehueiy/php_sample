-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 06:46 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

-- CREATE TABLE `user` (
--   `id` int(11) NOT NULL,
--   `name` varchar(50) NOT NULL,
--   `email` varchar(100) NOT NULL,
--   `password` varchar(255) NOT NULL,
--   `role` int(11) NOT NULL DEFAULT 2 COMMENT '0=admin, 1=user, 2=customer',
--   `status` tinyint(1) NOT NULL DEFAULT 1,
--   `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
--   `phone` varchar(20) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --
-- -- Dumping data for table `users`
-- --

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `status`, `created_date`, `phone`) VALUES
(1, 'ramji', 'admin@gmail.com', '$2y$10$fSrCWXluB4BbbDxvP1NXUeuov.rFgii1w/WG/rC6idJMGkUUnEm8O', 0, 1, '2024-12-29 05:18:56', ''),
(3, 'ramji', 'admin1@gmail.com', '$2y$10$8hRAo8x9UCfBND5PzTCawOGgi4Cymd/PacqtUCJvZVewEslBgGBqy', 0, 1, '2024-12-29 05:45:58', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
