-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 06:28 AM
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
-- Database: `elective4`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic`
--

CREATE TABLE `academic` (
  `id` int(11) NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `semester` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=Start, 2=Closed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic`
--

INSERT INTO `academic` (`id`, `school_year`, `semester`, `status`, `created_at`, `updated_at`) VALUES
(1, '2024-2025', 1, 2, '2024-11-09 23:46:36', '2024-11-14 23:50:37'),
(2, '2024-2025', 2, 2, '2024-11-09 23:47:07', '2024-11-14 23:50:37'),
(3, '2025-2026', 1, 2, '2024-11-09 23:47:42', '2024-11-14 23:50:37'),
(4, '2025-2026', 2, 1, '2024-11-14 23:06:44', '2024-11-14 23:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `verification_token` varchar(64) DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `passwordHash`, `verification_token`, `email_verified`, `created_at`, `updated_at`) VALUES
(5, 'Janelle Toquero', 'elective4.project@gmail.com', '$2y$10$Ufs3nP0G3gj7HYJp3F3O0eGuSazrJheB2J.uF1JG/yZe.Wxxj8f2G', NULL, 1, '2024-11-04 03:57:26', '2024-11-04 03:57:38');

-- --------------------------------------------------------

--
-- Table structure for table `college`
--

CREATE TABLE `college` (
  `college_id` int(11) NOT NULL,
  `college_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college`
--

INSERT INTO `college` (`college_id`, `college_name`, `created_at`, `updated_at`) VALUES
(1, 'College of Engineering and Technology', '2024-11-09 08:47:16', '2024-11-09 08:47:16'),
(15, 'College of Arts and Science', '2024-11-09 09:30:17', '2024-11-09 09:30:17'),
(16, 'College of Agriculture', '2024-11-09 09:32:14', '2024-11-09 09:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'COMMITMENT', '2024-11-10 09:44:11', '2024-11-10 09:44:11'),
(2, 'KNOWLEDGE OF SUBJECT MATTER', '2024-11-10 09:46:25', '2024-11-10 09:46:25'),
(3, 'TEACHING FOR INDEPENDENT LEARNING', '2024-11-10 09:46:47', '2024-11-10 09:46:47'),
(4, 'MANAGEMENT OF LEARNING', '2024-11-10 09:48:48', '2024-11-10 09:48:48');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `college_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `college_id`, `created_at`, `updated_at`) VALUES
(1, 'BSIT', 1, '2024-11-09 10:20:04', '2024-11-09 10:20:04'),
(4, 'BFPT', 1, '2024-11-09 10:21:10', '2024-11-09 10:21:10'),
(10, 'BSA', 16, '2024-11-09 10:27:19', '2024-11-09 10:27:19'),
(12, 'BSSW', 15, '2024-11-09 10:44:46', '2024-11-09 10:44:46'),
(13, 'BSENE', 1, '2024-11-09 11:16:53', '2024-11-09 11:16:53'),
(14, 'BSABE', 1, '2024-11-11 01:37:32', '2024-11-11 01:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int(11) NOT NULL,
  `student_id` int(20) NOT NULL,
  `faculty_id` int(20) NOT NULL,
  `academic_id` int(20) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation`
--

INSERT INTO `evaluation` (`id`, `student_id`, `faculty_id`, `academic_id`, `comment`, `created_at`, `updated_at`) VALUES
(9, 1, 3, 1, 'She\'s a good instructor who help her student to gain new knowledge.  ', '2024-11-10 19:33:02', '2024-11-10 19:33:02'),
(10, 1, 3, 2, 'She\'s a good instructor who help her student to gain new knowledge.  ', '2024-11-10 19:34:08', '2024-11-10 19:34:08'),
(11, 3, 4, 1, 'bad teacher, he always give assignment. tsk he should be remove as a faculty. ', '2024-11-11 02:05:53', '2024-11-11 02:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answer`
--

CREATE TABLE `evaluation_answer` (
  `evaluation_id` int(20) NOT NULL,
  `evaluation_question_id` int(20) NOT NULL,
  `rating_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_answer`
--

INSERT INTO `evaluation_answer` (`evaluation_id`, `evaluation_question_id`, `rating_id`) VALUES
(9, 2, 1),
(9, 3, 1),
(9, 4, 1),
(9, 5, 1),
(9, 6, 1),
(9, 7, 1),
(9, 8, 1),
(9, 9, 1),
(9, 10, 1),
(9, 11, 1),
(9, 14, 1),
(10, 6, 1),
(10, 14, 1),
(9, 12, 2),
(9, 13, 2),
(10, 2, 2),
(10, 10, 2),
(10, 11, 2),
(9, 1, 4),
(10, 1, 4),
(10, 3, 4),
(10, 5, 4),
(10, 7, 4),
(10, 8, 4),
(10, 13, 4),
(10, 4, 5),
(10, 9, 5),
(10, 12, 5),
(11, 1, 7),
(11, 2, 7),
(11, 3, 7),
(11, 4, 7),
(11, 5, 7),
(11, 6, 7),
(11, 7, 7),
(11, 8, 7),
(11, 9, 7),
(11, 10, 7),
(11, 11, 7),
(11, 12, 7),
(11, 13, 7),
(11, 14, 7);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_dates`
--

CREATE TABLE `evaluation_dates` (
  `id` int(11) NOT NULL,
  `open_datetime` datetime NOT NULL,
  `close_datetime` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_dates`
--

INSERT INTO `evaluation_dates` (`id`, `open_datetime`, `close_datetime`, `created_at`, `updated_at`) VALUES
(28, '2024-12-04 11:10:00', '2024-12-06 11:10:00', '2024-12-04 11:10:04', '2024-12-04 11:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_question`
--

CREATE TABLE `evaluation_question` (
  `id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_question`
--

INSERT INTO `evaluation_question` (`id`, `criteria_id`, `question_text`, `created_at`, `updated_at`) VALUES
(1, 1, 'Demonstrates sensitivity to students\' ability to absorb content information', '2024-11-10 12:07:46', '2024-11-10 12:07:46'),
(2, 1, 'Makes self-available to students beyond official time.', '2024-11-10 12:14:43', '2024-11-10 12:14:43'),
(3, 1, 'Keeps accurate records of students\' performance and prompt submission of same', '2024-11-10 12:15:05', '2024-11-10 12:15:05'),
(4, 2, 'Demonstrates mastery of the subject matter.', '2024-11-10 12:17:20', '2024-11-10 12:17:20'),
(5, 2, 'Draws and shares information on the state-of-the-art theory and practice in their discipline', '2024-11-10 12:18:01', '2024-11-10 12:18:01'),
(6, 2, 'Integrates subject to practical circumstances and learning intents of students.', '2024-11-10 12:18:19', '2024-11-10 12:18:19'),
(7, 2, 'Demonstrates up-to-date knowledge and/or awareness on current trends and issues of the subject.', '2024-11-10 12:18:44', '2024-11-10 12:18:44'),
(8, 3, 'Creates teaching strategies that allow students to practice using concepts they need to understand (interactive discussion).', '2024-11-10 12:20:17', '2024-11-10 12:20:17'),
(9, 3, 'Enhances student self-esteem and/or gives due recognition to students\' performance/potentials.', '2024-11-10 12:20:42', '2024-11-10 12:20:42'),
(10, 3, 'Allows students to create their own course objectives and realistically define student-professor roles and make them accountable for their performance.', '2024-11-10 12:21:24', '2024-11-10 12:21:24'),
(11, 3, 'Allows students to think independently and make their own decisions and holding them accountable for their performance based largely on their success in executing decisions.', '2024-11-10 12:22:15', '2024-11-10 12:22:15'),
(12, 3, 'Encourages students to learn beyond what is required and help/guide the students how to apply the concepts.', '2024-11-10 12:22:32', '2024-11-10 12:22:32'),
(13, 4, 'Designs and implements learning conditions and experience that promotes healthy exchange and/or confrontations.', '2024-11-10 12:23:11', '2024-11-10 12:23:11'),
(14, 4, 'Structures/restructures teaching and learning context to enhance attainment of collective learning objectives.', '2024-11-10 12:23:28', '2024-11-10 12:23:28');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(11) NOT NULL,
  `faculty_id` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phoneNumber` varchar(15) DEFAULT NULL,
  `gender` enum('Female','Male') NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `college` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_created_at` timestamp NULL DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `faculty_id`, `full_name`, `phoneNumber`, `gender`, `email`, `passwordHash`, `college`, `department`, `is_active`, `reset_token`, `token_created_at`, `email_verified`, `verification_token`, `created_at`, `updated_at`) VALUES
(3, '2021307847', 'Janelle Toquero', '09755509795', 'Female', 'toquerojanelle2@gmail.com', '$2y$10$gDSlz5/6PmojF5vo5mlfZeotDbeHaYP7wixd4lVF5cEAFIEoe8K.y', 1, 1, 1, NULL, NULL, 1, NULL, '2024-11-10 02:17:03', '2024-11-10 09:50:20'),
(4, '2021304104', 'Joshua Mervin V. Batiancila', '09120742671', 'Male', 'batiancilajoshuamervin777@gmail.com', '$2y$10$30fkmEcDaW7HHB1J5FRA/OHltWHR12POAc3UbuWP/YWcXM0kIXL8a', 1, 1, 1, NULL, NULL, 0, '6e2e41070cd4df6538952f4a60e16f439b971a12a70af3c51a05de0641bcde61', '2024-11-11 01:44:33', '2024-11-11 01:48:23');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `rate` tinyint(4) NOT NULL CHECK (`rate` between 1 and 5),
  `descriptive_rating` varchar(50) NOT NULL,
  `qualitative_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `rate`, `descriptive_rating`, `qualitative_description`, `created_at`, `updated_at`) VALUES
(1, 5, 'Outstanding', 'The Performance almost always exceeds the job requirements. The faculty is an exceptional role model', '2024-11-10 08:13:48', '2024-11-10 08:13:48'),
(2, 4, 'Very Satisfactory', 'The performance meets and often exceeds the job requirements', '2024-11-10 08:19:13', '2024-11-10 08:19:13'),
(4, 3, 'Satisfactory', 'The performance meets job requirements', '2024-11-10 09:38:26', '2024-11-10 09:38:26'),
(5, 2, 'Fair', 'The performance needs some development to meet job requirements', '2024-11-10 09:39:05', '2024-11-10 09:39:05'),
(7, 1, 'Poor', 'The faculty fails to meet job requirements.', '2024-11-10 15:05:41', '2024-11-10 15:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phoneNumber` varchar(15) DEFAULT NULL,
  `gender` enum('Female','Male') NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_created_at` timestamp NULL DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `student_id`, `full_name`, `phoneNumber`, `gender`, `email`, `passwordHash`, `reset_token`, `token_created_at`, `email_verified`, `verification_token`, `created_at`, `updated_at`) VALUES
(1, '2021307847', 'Janelle Toquero', '09755509795', 'Female', 'toquerojanelle2@gmail.com', '$2y$10$S3Ab1QRIn49DLMc/aHn/MO2EJyQSLrn4Lys2v3h1jlhIodTkD/Oq.', NULL, NULL, 1, NULL, '2024-11-10 12:57:15', '2024-11-10 12:59:16'),
(3, '12345678', 'Junna C. Mapalo', '09123456789', 'Female', 'mjunnamitchelle@gmail.com', '$2y$10$XIRDELT4tWntCWizpUyUAOprg9BHm.0VXEUQ0/E2n03WUJEEiaSdq', NULL, NULL, 1, NULL, '2024-11-11 02:01:39', '2024-11-11 02:03:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic`
--
ALTER TABLE `academic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`college_id`),
  ADD UNIQUE KEY `college_name` (`college_name`);

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `academic_id` (`academic_id`);

--
-- Indexes for table `evaluation_answer`
--
ALTER TABLE `evaluation_answer`
  ADD PRIMARY KEY (`evaluation_id`,`evaluation_question_id`),
  ADD KEY `evaluation_question_id` (`evaluation_question_id`),
  ADD KEY `rating_id` (`rating_id`);

--
-- Indexes for table `evaluation_dates`
--
ALTER TABLE `evaluation_dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_question`
--
ALTER TABLE `evaluation_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculty_id` (`faculty_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `college` (`college`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rate` (`rate`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic`
--
ALTER TABLE `academic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `college`
--
ALTER TABLE `college`
  MODIFY `college_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `evaluation_dates`
--
ALTER TABLE `evaluation_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `evaluation_question`
--
ALTER TABLE `evaluation_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `college` (`college_id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `evaluation_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_list` (`id`),
  ADD CONSTRAINT `evaluation_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_list` (`id`),
  ADD CONSTRAINT `evaluation_ibfk_3` FOREIGN KEY (`academic_id`) REFERENCES `academic` (`id`);

--
-- Constraints for table `evaluation_answer`
--
ALTER TABLE `evaluation_answer`
  ADD CONSTRAINT `evaluation_answer_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`),
  ADD CONSTRAINT `evaluation_answer_ibfk_2` FOREIGN KEY (`evaluation_question_id`) REFERENCES `evaluation_question` (`id`),
  ADD CONSTRAINT `evaluation_answer_ibfk_3` FOREIGN KEY (`rating_id`) REFERENCES `rating` (`id`);

--
-- Constraints for table `evaluation_question`
--
ALTER TABLE `evaluation_question`
  ADD CONSTRAINT `evaluation_question_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD CONSTRAINT `faculty_list_ibfk_1` FOREIGN KEY (`college`) REFERENCES `college` (`college_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `faculty_list_ibfk_2` FOREIGN KEY (`department`) REFERENCES `department` (`department_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
