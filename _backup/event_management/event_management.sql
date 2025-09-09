-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 08:41 PM
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
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `max_participants` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `location`, `max_participants`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'php Session by Akshay kumar', 'a insightful session on php and sql and xampp\r\ndetailed 2 hour session\r\nby mr Akshay kumar', '2024-11-22', 'lpu 32-701', 50, 2, '2024-11-19 17:17:10', '2024-11-19 19:40:31'),
(3, 'Mountain Climbing Coures', 'Join us for an exhilarating Mountain Climbing Course designed for both beginners and experienced climbers. This course will provide you with the essential skills and knowledge needed to safely and confidently climb mountains. Our experienced instructors will guide you through various climbing techniques, safety protocols, and equipment usage.', '2024-11-30', 'Lima Block , Jalandhar cant (near officers.Mess)', 62, 2, '2024-11-19 19:23:31', '2024-11-19 19:23:31'),
(4, ' Rock Climbing Workshop', 'Join our Rock Climbing Workshop for an exciting and challenging experience. This workshop is perfect for climbers of all levels who want to improve their skills and learn new techniques. Our expert instructors will provide hands-on training and guidance to help you conquer various rock formations.', '2024-11-29', 'Red Rock Canyon, Himachal', 100, 2, '2024-11-19 19:26:05', '2024-11-19 19:26:05'),
(5, 'Freshman Orientation', 'Welcome to the university! Join us for Freshman Orientation, an event designed to help new students transition smoothly into university life. This orientation will provide you with essential information about campus resources, academic programs, and student life. Meet your fellow classmates, faculty, and staff, and get ready for an exciting journey ahead', '2024-12-01', 'University Main Auditorium', 263, 2, '2024-11-19 19:27:55', '2024-11-19 19:27:55'),
(6, 'Career Fair', 'Join us for the annual Career Fair, where you can connect with top employers and explore various career opportunities. This event is designed to help students and alumni network with potential employers, learn about job openings, and gain valuable insights into different industries. Bring your resume and be prepared to make a great impression!', '2025-01-02', 'University Sports Complex', 800, 2, '2024-11-19 19:28:38', '2024-11-19 19:28:38'),
(7, 'Research Symposium', 'join us for the annual Research Symposium, where students and faculty showcase their research projects and findings. This event provides a platform for researchers to present their work, exchange ideas, and collaborate with others. Attend the symposium to learn about the latest research developments and innovations in various fields.', '2025-01-05', 'University Conference Center', 500, 2, '2024-11-19 19:29:20', '2024-11-19 19:29:20');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`id`, `user_id`, `event_id`, `registration_date`) VALUES
(1, 1, 2, '2024-11-19 17:54:55'),
(5, 4, 2, '2024-11-19 19:31:18'),
(6, 4, 4, '2024-11-19 19:31:20'),
(7, 4, 7, '2024-11-19 19:31:21'),
(8, 4, 6, '2024-11-19 19:31:23'),
(9, 4, 3, '2024-11-19 19:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`, `last_login`, `deleted_at`) VALUES
(1, 'ankit', 'ankit@gmail.com', '$2y$10$ZsPrCGyma.1pTe/cnr8ykeZjnOPIAWyBa2joOGJ7AdaWDIgWo6I/K', 'user', '2024-11-19 16:28:11', '2024-11-19 18:34:07', '2024-11-19 18:34:07', NULL),
(2, 'admin', 'admin@gmail.com', '$2y$10$K1nsF1fYyjbWy7CdvdrT4euJdT61dUydZGI7Q9bgoh3jhT9VJkkRW', 'admin', '2024-11-19 16:35:45', '2024-11-19 19:39:45', '2024-11-19 19:39:45', NULL),
(4, 'abc', 'abc@gmail.com', '$2y$10$w.QplNPwnYZ9psSJ106RXu.aiFSX1ao8XV0JBjVfEvXN28fDkNnPS', 'user', '2024-11-19 18:41:19', '2024-11-19 19:31:13', '2024-11-19 19:31:13', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `participants_ibfk_1` (`user_id`);

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
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
