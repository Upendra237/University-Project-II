-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 26, 2025 at 10:12 AM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 8.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u290660616_pustak`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_info`
--

CREATE TABLE `log_info` (
  `logid` int(11) NOT NULL,
  `number_plate` varchar(30) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repo_projects`
--

CREATE TABLE `repo_projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `project_type` varchar(50) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `department` varchar(100) NOT NULL,
  `team_members` text NOT NULL,
  `supervisor` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `full_description` text DEFAULT NULL,
  `preview_image` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `demo_url` varchar(255) DEFAULT NULL,
  `report_url` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `downloads` int(11) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `technologies_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technologies_used`)),
  `project_stats` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`project_stats`)),
  `architecture_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`architecture_details`)),
  `key_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`key_features`)),
  `project_outcomes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`project_outcomes`)),
  `challenges_faced` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`challenges_faced`)),
  `implementation_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`implementation_details`)),
  `team_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`team_roles`)),
  `project_timeline` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`project_timeline`)),
  `awards_recognition` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`awards_recognition`)),
  `media_gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`media_gallery`)),
  `status` enum('draft','pending','approved','rejected') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `submitted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repo_projects`
--

INSERT INTO `repo_projects` (`id`, `title`, `description`, `project_type`, `semester`, `department`, `team_members`, `supervisor`, `year`, `full_description`, `preview_image`, `github_url`, `demo_url`, `report_url`, `views`, `downloads`, `rating`, `technologies_used`, `project_stats`, `architecture_details`, `key_features`, `project_outcomes`, `challenges_faced`, `implementation_details`, `team_roles`, `project_timeline`, `awards_recognition`, `media_gallery`, `status`, `created_at`, `updated_at`, `submitted_by`) VALUES
(1, 'Title', 'Description', 'Project I', '', 'Computer Engineering', 'sanjib, cyndi', 'supervisor 1', 2025, NULL, NULL, '', '', NULL, 0, 0, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-01-21 14:52:17', '2025-01-21 14:52:17', 4),
(2, 'Title', 'Description', 'Project I', '', 'Computer Engineering', 'sanjib, cyndi', 'supervisor 1', 2025, NULL, NULL, '', '', NULL, 0, 0, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', '2025-01-21 14:52:22', '2025-01-21 14:52:22', 4),
(3, 'Title', 'Description', 'Project I', '', 'Computer Engineering', 'sanjib, cyndi', 'supervisor 1', 2025, NULL, NULL, '', '', NULL, 0, 0, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-01-21 14:52:28', '2025-01-21 14:52:28', 4),
(4, 'Title', 'Description', 'Project I', '', 'Computer Engineering', 'ohoooooooooo', 'supervisor 1', 2025, NULL, NULL, '', '', NULL, 0, 0, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', '2025-01-21 14:53:06', '2025-01-21 14:53:06', 4),
(5, 'Project Title', 'This is the project description with the consitao a', 'Project I', '', 'Computer Engineering', 'hjk', 'asd', 2025, NULL, NULL, '', '', NULL, 0, 0, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'draft', '2025-01-22 14:22:20', '2025-01-22 14:22:20', 4),
(6, 'OOOOOOOO', 'This is the project description with the consitao a', 'Project I', '', 'Computer Engineering', 'hjk', 'asd', 2025, NULL, NULL, '', '', NULL, 0, 0, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-01-22 14:23:08', '2025-01-22 14:23:08', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `user_type` enum('admin','department','faculty','student') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `department` varchar(100) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `user_type`, `status`, `created_at`, `updated_at`, `department`, `semester`) VALUES
(2, 'dept_head', '$2y$10$cFSBXMDIJZrLp2q6dNgak.jrOc5HcdZXwRdu3lShaGjD4ViCQlFNu', 'department@example.com', 'Department Head', 'department', 'active', '2025-01-19 11:37:47', '2025-01-19 12:12:02', NULL, NULL),
(3, 'faculty1', '$2y$10$cFSBXMDIJZrLp2q6dNgak.jrOc5HcdZXwRdu3lShaGjD4ViCQlFNu', 'faculty@example.com', 'Faculty Member', 'faculty', 'active', '2025-01-19 11:37:47', '2025-01-19 12:11:58', NULL, NULL),
(4, 'Upendra237', '$2y$10$cFSBXMDIJZrLp2q6dNgak.jrOc5HcdZXwRdu3lShaGjD4ViCQlFNu', 'shahiupendra237@gmail.com', 'Upendra Shahi', 'student', 'active', '2025-01-19 11:37:47', '2025-01-22 15:20:57', 'Computer Engnineering', '6'),
(5, 'admin', '$2y$10$cFSBXMDIJZrLp2q6dNgak.jrOc5HcdZXwRdu3lShaGjD4ViCQlFNu', 'admin@example.com', 'Admin User', 'admin', 'active', '2025-01-19 11:53:57', '2025-01-21 13:28:02', NULL, NULL),
(6, 'student_hxcwe', '$2y$10$cFSBXMDIJZrLp2q6dNgak.jrOc5HcdZXwRdu3lShaGjD4ViCQlFNu', 'student_hxcwe@example.com', 'Student Hxcwe', 'student', 'active', '2025-01-19 12:04:44', '2025-01-19 12:04:44', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_info`
--
ALTER TABLE `log_info`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `repo_projects`
--
ALTER TABLE `repo_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submitted_by` (`submitted_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_info`
--
ALTER TABLE `log_info`
  MODIFY `logid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repo_projects`
--
ALTER TABLE `repo_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `repo_projects`
--
ALTER TABLE `repo_projects`
  ADD CONSTRAINT `repo_projects_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
