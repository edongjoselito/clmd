-- =====================================================================
-- CLMD - DepEd Region XI
-- Document Submission & Approval System
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `clmd_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `clmd_db`;

-- ---------------------------------------------------------------------
-- Divisions
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `divisions`;
CREATE TABLE `divisions` (
  `division_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code`          VARCHAR(20)  NOT NULL,
  `name`          VARCHAR(150) NOT NULL,
  `address`       VARCHAR(255) DEFAULT NULL,
  `contact`       VARCHAR(50)  DEFAULT NULL,
  `is_active`     TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`division_id`),
  UNIQUE KEY `uq_div_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `divisions` (`code`,`name`,`address`) VALUES
('SDO-DVO',  'Schools Division Office of Davao del Norte',     'Tagum City'),
('SDO-DVS',  'Schools Division Office of Davao del Sur',       'Digos City'),
('SDO-DVE',  'Schools Division Office of Davao Oriental',      'Mati City'),
('SDO-DVOC', 'Schools Division Office of Davao Occidental',    'Malita, Davao Occidental'),
('SDO-DGN',  'Schools Division Office of Davao de Oro',        'Nabunturan'),
('SDO-DVC',  'Schools Division Office of Davao City',          'Davao City'),
('SDO-IGCT', 'Schools Division Office of Island Garden City of Samal', 'IGACOS'),
('SDO-PNB',  'Schools Division Office of Panabo City',         'Panabo City'),
('SDO-TGM',  'Schools Division Office of Tagum City',          'Tagum City'),
('SDO-DGS',  'Schools Division Office of Digos City',          'Digos City'),
('SDO-MTI',  'Schools Division Office of Mati City',           'Mati City');

-- ---------------------------------------------------------------------
-- Users (regional | division)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`     VARCHAR(50)  NOT NULL,
  `password`     VARCHAR(255) NOT NULL,
  `full_name`    VARCHAR(150) NOT NULL,
  `email`        VARCHAR(150) DEFAULT NULL,
  `role`         ENUM('regional','division') NOT NULL,
  `division_id`  INT UNSIGNED DEFAULT NULL,
  `position`     VARCHAR(100) DEFAULT NULL,
  `is_active`    TINYINT(1)   NOT NULL DEFAULT 1,
  `last_login`   DATETIME     DEFAULT NULL,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_username` (`username`),
  KEY `fk_users_division` (`division_id`),
  CONSTRAINT `fk_users_division` FOREIGN KEY (`division_id`)
    REFERENCES `divisions` (`division_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin is created by install.php

-- ---------------------------------------------------------------------
-- Schools (managed per Division)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools` (
  `school_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_code`   VARCHAR(40)  DEFAULT NULL,
  `school_name`   VARCHAR(255) NOT NULL,
  `school_type`   ENUM('Public','Private') NOT NULL DEFAULT 'Private',
  `address`       VARCHAR(255) DEFAULT NULL,
  `municipality`  VARCHAR(120) DEFAULT NULL,
  `division_id`   INT UNSIGNED NOT NULL,
  `is_active`     TINYINT(1)   NOT NULL DEFAULT 1,
  `created_by`    INT UNSIGNED DEFAULT NULL,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`school_id`),
  KEY `fk_schools_div` (`division_id`),
  KEY `fk_schools_user` (`created_by`),
  CONSTRAINT `fk_schools_div` FOREIGN KEY (`division_id`)
    REFERENCES `divisions` (`division_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_schools_user` FOREIGN KEY (`created_by`)
    REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Documents (submitted by Division, approved by Regional)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `document_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `control_no`      VARCHAR(40)  DEFAULT NULL,
  `school_id`       INT UNSIGNED NOT NULL,
  `division_id`     INT UNSIGNED NOT NULL,
  `document_title`  VARCHAR(255) NOT NULL,
  `document_type`   VARCHAR(120) NOT NULL,
  `file_path`       VARCHAR(255) DEFAULT NULL,
  `remarks`         TEXT,
  `status`          ENUM('For Approval','Approved','Rejected','Revised') NOT NULL DEFAULT 'For Approval',
  `submitted_by`    INT UNSIGNED NOT NULL,
  `reviewed_by`     INT UNSIGNED DEFAULT NULL,
  `reviewed_at`     DATETIME     DEFAULT NULL,
  `review_notes`    TEXT,
  `approved_at`     DATETIME     DEFAULT NULL,
  `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`document_id`),
  UNIQUE KEY `uq_control_no` (`control_no`),
  KEY `fk_docs_school` (`school_id`),
  KEY `fk_docs_div` (`division_id`),
  KEY `fk_docs_submitter` (`submitted_by`),
  KEY `fk_docs_reviewer` (`reviewed_by`),
  CONSTRAINT `fk_docs_school` FOREIGN KEY (`school_id`)
    REFERENCES `schools` (`school_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_docs_div` FOREIGN KEY (`division_id`)
    REFERENCES `divisions` (`division_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_docs_submitter` FOREIGN KEY (`submitted_by`)
    REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_docs_reviewer` FOREIGN KEY (`reviewed_by`)
    REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Notifications
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `notif_id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NOT NULL,
  `title`       VARCHAR(150) NOT NULL,
  `message`     VARCHAR(500) NOT NULL,
  `link_url`    VARCHAR(255) DEFAULT NULL,
  `is_read`     TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notif_id`),
  KEY `fk_notif_user` (`user_id`),
  KEY `idx_notif_unread` (`user_id`,`is_read`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Settings (CLMD Chief signatory / signature image)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `chief_name`      VARCHAR(150) DEFAULT NULL,
  `chief_position`  VARCHAR(150) DEFAULT 'Chief Education Supervisor, CLMD',
  `signature_path`  VARCHAR(255) DEFAULT NULL,
  `letterhead_text` VARCHAR(255) DEFAULT 'Republic of the Philippines\nDepartment of Education\nRegion XI - Davao Region',
  `updated_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_id`,`chief_name`,`chief_position`)
VALUES (1, 'Maria L. Dela Cruz, Ph.D.', 'Chief Education Supervisor, CLMD');

-- ---------------------------------------------------------------------
-- Activity Log
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `log_id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED DEFAULT NULL,
  `action`     VARCHAR(100) NOT NULL,
  `details`    TEXT,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `fk_log_user` (`user_id`),
  CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
