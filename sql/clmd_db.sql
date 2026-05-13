-- =====================================================================
-- Curriculum Learning Management Division (CLMD) - DepEd Region XI
-- Database schema
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `clmd_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `clmd_db`;

-- ---------------------------------------------------------------------
-- Divisions (DepEd Region XI Schools Division Offices)
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
-- Users (2 roles: regional, division)
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

-- Default admin user is created by install.php (password generated via password_hash).

-- ---------------------------------------------------------------------
-- Curriculum (uploaded/maintained at the regional level)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `curriculum`;
CREATE TABLE `curriculum` (
  `curriculum_id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`          VARCHAR(255) NOT NULL,
  `grade_level`    VARCHAR(30)  NOT NULL,
  `subject`        VARCHAR(100) NOT NULL,
  `description`    TEXT,
  `file_path`      VARCHAR(255) DEFAULT NULL,
  `school_year`    VARCHAR(20)  DEFAULT NULL,
  `created_by`     INT UNSIGNED NOT NULL,
  `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`curriculum_id`),
  KEY `fk_cur_user` (`created_by`),
  CONSTRAINT `fk_cur_user` FOREIGN KEY (`created_by`)
    REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Certifications (Private School DO 54 Compliance)
-- Submitted by Division (uploads Certification + Endorsement),
-- approved by Regional, printable to be issued to applying schools.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `certifications`;
CREATE TABLE `certifications` (
  `certification_id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `control_no`         VARCHAR(40)  DEFAULT NULL,
  `school_name`        VARCHAR(255) NOT NULL,
  `school_address`     VARCHAR(255) DEFAULT NULL,
  `school_head`        VARCHAR(150) DEFAULT NULL,
  `school_level`       VARCHAR(100) DEFAULT NULL,
  `school_year`        VARCHAR(20)  DEFAULT NULL,
  `division_id`        INT UNSIGNED DEFAULT NULL,
  `certification_file` VARCHAR(255) DEFAULT NULL,
  `endorsement_file`   VARCHAR(255) DEFAULT NULL,
  `purpose`            VARCHAR(255) DEFAULT NULL,
  `status`             ENUM('Pending','Approved','Rejected','Revised') NOT NULL DEFAULT 'Pending',
  `remarks`            TEXT,
  `submitted_by`       INT UNSIGNED NOT NULL,
  `reviewed_by`        INT UNSIGNED DEFAULT NULL,
  `reviewed_at`        DATETIME     DEFAULT NULL,
  `date_issued`        DATE         DEFAULT NULL,
  `created_at`         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`certification_id`),
  UNIQUE KEY `uq_control_no` (`control_no`),
  KEY `fk_cert_div` (`division_id`),
  KEY `fk_cert_submitter` (`submitted_by`),
  KEY `fk_cert_reviewer` (`reviewed_by`),
  CONSTRAINT `fk_cert_div` FOREIGN KEY (`division_id`)
    REFERENCES `divisions` (`division_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_cert_submitter` FOREIGN KEY (`submitted_by`)
    REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cert_reviewer` FOREIGN KEY (`reviewed_by`)
    REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
