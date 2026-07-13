-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2026 at 04:17 AM
-- Server version: 8.4.7
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `depedmis_clmd`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(12, 2, 'document_submit', 'Doc #6', '127.0.0.1', '2026-06-30 19:51:49'),
(13, 1, 'document_review', 'Doc #6 -> Approved', '127.0.0.1', '2026-06-30 19:52:13'),
(14, 2, 'document_submit', 'Doc #7', '127.0.0.1', '2026-06-30 21:24:20'),
(15, 1, 'document_review', 'Doc #7 -> Approved', '127.0.0.1', '2026-06-30 21:24:47'),
(16, 2, 'document_submit', 'Cert #8', '127.0.0.1', '2026-07-08 14:17:43'),
(17, 2, 'document_submit', 'Endorse #9', '127.0.0.1', '2026-07-08 14:17:43'),
(18, 2, 'document_submit', 'Cert #10', '127.0.0.1', '2026-07-08 14:19:37'),
(19, 2, 'document_submit', 'Endorse #11', '127.0.0.1', '2026-07-08 14:19:37'),
(20, 1, 'document_review', 'Doc #10 -> Approved', '127.0.0.1', '2026-07-08 14:49:47'),
(21, 1, 'document_review', 'Doc #11 -> Approved', '127.0.0.1', '2026-07-08 14:50:00'),
(22, 2, 'document_submit', 'Cert #12', '127.0.0.1', '2026-07-13 08:34:57'),
(23, 2, 'document_submit', 'Endorse #13', '127.0.0.1', '2026-07-13 08:34:57'),
(24, 1, 'document_review', 'Doc #12 -> Approved', '127.0.0.1', '2026-07-13 08:35:47'),
(25, 1, 'document_review', 'Doc #13 -> Approved', '127.0.0.1', '2026-07-13 08:35:54'),
(26, 2, 'user_update', 'Updated user chiko@deped.gov.ph', '127.0.0.1', '2026-07-13 08:38:58'),
(27, 1, 'user_update', 'Updated user chiko@deped.gov.ph', '127.0.0.1', '2026-07-13 08:43:59');

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `division_id` int UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`division_id`, `code`, `name`, `address`, `contact`, `is_active`, `created_at`) VALUES
(1, 'SDO-DVO', 'Schools Division Office of Davao del Norte', 'Tagum City', NULL, 1, '2026-05-15 15:54:37'),
(2, 'SDO-DVS', 'Schools Division Office of Davao del Sur', 'Digos City', NULL, 1, '2026-05-15 15:54:37'),
(3, 'SDO-DVE', 'Schools Division Office of Davao Oriental', 'Mati City', NULL, 1, '2026-05-15 15:54:37'),
(4, 'SDO-DVOC', 'Schools Division Office of Davao Occidental', 'Malita, Davao Occidental', NULL, 1, '2026-05-15 15:54:37'),
(5, 'SDO-DGN', 'Schools Division Office of Davao de Oro', 'Nabunturan', NULL, 1, '2026-05-15 15:54:37'),
(6, 'SDO-DVC', 'Schools Division Office of Davao City', 'Davao City', NULL, 1, '2026-05-15 15:54:37'),
(7, 'SDO-IGCT', 'Schools Division Office of Island Garden City of Samal', 'IGACOS', NULL, 1, '2026-05-15 15:54:37'),
(8, 'SDO-PNB', 'Schools Division Office of Panabo City', 'Panabo City', NULL, 1, '2026-05-15 15:54:37'),
(9, 'SDO-TGM', 'Schools Division Office of Tagum City', 'Tagum City', NULL, 1, '2026-05-15 15:54:37'),
(10, 'SDO-DGS', 'Schools Division Office of Digos City', 'Digos City', NULL, 1, '2026-05-15 15:54:37'),
(11, 'SDO-MTI', 'Schools Division Office of Mati City', 'Mati City', NULL, 1, '2026-05-15 15:54:37');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int UNSIGNED NOT NULL,
  `control_no` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` int UNSIGNED NOT NULL,
  `division_id` int UNSIGNED NOT NULL,
  `document_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `current_track` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_strand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_specializations` text COLLATE utf8mb4_unicode_ci,
  `strengthened_track` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strengthened_strand` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strengthened_specializations` text COLLATE utf8mb4_unicode_ci,
  `status` enum('For Approval','Approved','Rejected','Revised') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'For Approval',
  `submitted_by` int UNSIGNED NOT NULL,
  `reviewed_by` int UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_unicode_ci,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `control_no`, `school_id`, `division_id`, `document_title`, `document_type`, `file_path`, `remarks`, `current_track`, `current_strand`, `current_specializations`, `strengthened_track`, `strengthened_strand`, `strengthened_specializations`, `status`, `submitted_by`, `reviewed_by`, `reviewed_at`, `review_notes`, `approved_at`, `created_at`, `updated_at`) VALUES
(10, 'CLMD-RXI-2026-0001', 3, 3, 'Certification of Compliance', 'Certification of Compliance to DepEd Order No. 54, s. 2022', 'uploads/documents/6044adfa7b69e2c764f8215002f2704b.pdf', NULL, 'TVL Track', 'I.A. Strand', 'aaa\r\nbbb', 'TechPro Track', 'Industrial Technologies', 'bbb\r\nccc', 'Approved', 2, 1, '2026-07-08 14:49:47', NULL, '2026-07-08 14:49:47', '2026-07-08 14:19:37', '2026-07-08 14:49:47'),
(11, 'CLMD-RXI-2026-0002', 3, 3, 'Endorsement', 'Endorsement', 'uploads/documents/1af42420299e82d59b41708a8c870bbc.pdf', NULL, 'TVL Track', 'I.A. Strand', 'aaa\r\nbbb', 'TechPro Track', 'Industrial Technologies', 'bbb\r\nccc', 'Approved', 2, 1, '2026-07-08 14:50:00', NULL, '2026-07-08 14:50:00', '2026-07-08 14:19:37', '2026-07-08 14:50:00'),
(12, 'CLMD-RXI-2026-0003', 4, 3, 'Certification of Compliance', 'Certification of Compliance to DepEd Order No. 54, s. 2022', 'uploads/documents/cddf2c7048ff0f2546a8dec7c2a7fdbc.pdf', NULL, 'TVL Track', 'I.A. Strand', 'Specialization 1\r\nSpecialization 2\r\nSpecialization 3', 'TechPro Track', 'ICT Support and Computer Programming Technologies', 'Contact Center Services NC II\r\nComputer Systems Servicing NC II', 'Approved', 2, 1, '2026-07-13 08:35:47', NULL, '2026-07-13 08:35:47', '2026-07-13 08:34:57', '2026-07-13 08:35:47'),
(13, 'CLMD-RXI-2026-0004', 4, 3, 'Endorsement', 'Endorsement', 'uploads/documents/6205e5b45e5fbd505fa4d68e4b9a4530.pdf', NULL, 'TVL Track', 'I.A. Strand', 'Specialization 1\r\nSpecialization 2\r\nSpecialization 3', 'TechPro Track', 'ICT Support and Computer Programming Technologies', 'Contact Center Services NC II\r\nComputer Systems Servicing NC II', 'Approved', 2, 1, '2026-07-13 08:35:54', NULL, '2026-07-13 08:35:54', '2026-07-13 08:34:57', '2026-07-13 08:35:54');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notif_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `school_id` int UNSIGNED NOT NULL,
  `school_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_type` enum('Public','Private') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Private',
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `province` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `barangay` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `division_id` int UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`school_id`, `school_code`, `school_name`, `school_type`, `email`, `province`, `city`, `barangay`, `division_id`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(3, '6666', 'LUZ PARON SCHOOL OF TOMORROW', 'Private', '', '', '', '', 3, 1, 2, '2026-05-15 16:21:50', '2026-05-15 16:21:50'),
(4, NULL, 'Nanny Go Wang ES', 'Private', 'nannygowang@gmail.com', 'Davao Oriental', 'Manay', 'Mariano', 3, 1, 2, '2026-06-30 23:01:13', '2026-07-13 08:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int UNSIGNED NOT NULL,
  `chief_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chief_position` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT 'Chief Education Supervisor, CLMD',
  `letterhead_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letterhead_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Republic of the Philippines\nDepartment of Education\nRegion XI - Davao Region',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `chief_name`, `chief_position`, `letterhead_path`, `signature_path`, `letterhead_text`, `updated_at`) VALUES
(1, 'Mary Jeanne B. Aldeguer', 'Chief Education Supervisor, CLMD', 'uploads/letterhead/b04e0c661a6468193acd0a4cda48c585.png', 'uploads/signature/009007cb72fed197926fe15409f9e477.png', 'Republic of the Philippines\r\nDepartment of Education\r\nRegion XI - Davao Region', '2026-07-13 09:30:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('regional','division') COLLATE utf8mb4_unicode_ci NOT NULL,
  `division_id` int UNSIGNED DEFAULT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `role`, `division_id`, `position`, `is_active`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$ijH2lc8xdKzSyhi0qtGla.5Va0lsY8/Wv3lkoJs2AsO9apWvT83KS', 'CLMD Regional Administrator', 'clmd@region11.deped.gov.ph', 'regional', NULL, 'CLMD Chief', 1, '2026-07-13 09:54:52', '2026-05-15 15:54:37'),
(2, 'chiko@deped.gov.ph', '$2y$10$CRwn8m6kfYa0ZdwwRvaHx.YKh2yH.vcWqdfOuho7BEQ2evbH0iX96', 'Chiko Bolero', 'chiko@deped.gov.ph', 'division', 3, 'Division Focal Person', 1, '2026-07-13 09:55:05', '2026-05-15 16:07:56'),
(3, 'norman.tomas@deped.gov.ph', '$2y$10$WU1q5K8fatExAWvNB4/oR.ZyyU0vL3pvkgHury440U8Q.gwct0c/K', 'Norman Tomas', 'norman.tomas@deped.gov.ph', 'division', 3, NULL, 1, '2026-05-16 19:30:25', '2026-05-16 11:30:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_log_user` (`user_id`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`division_id`),
  ADD UNIQUE KEY `uq_div_code` (`code`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD UNIQUE KEY `uq_control_no` (`control_no`),
  ADD KEY `fk_docs_school` (`school_id`),
  ADD KEY `fk_docs_div` (`division_id`),
  ADD KEY `fk_docs_submitter` (`submitted_by`),
  ADD KEY `fk_docs_reviewer` (`reviewed_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `fk_notif_user` (`user_id`),
  ADD KEY `idx_notif_unread` (`user_id`,`is_read`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`school_id`),
  ADD KEY `fk_schools_div` (`division_id`),
  ADD KEY `fk_schools_user` (`created_by`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_username` (`username`),
  ADD KEY `fk_users_division` (`division_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `division_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notif_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `school_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_docs_div` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docs_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docs_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`school_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docs_submitter` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schools`
--
ALTER TABLE `schools`
  ADD CONSTRAINT `fk_schools_div` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_schools_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
