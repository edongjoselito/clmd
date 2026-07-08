-- Add curriculum fields to documents table
ALTER TABLE `documents` ADD COLUMN `current_track` VARCHAR(100) DEFAULT NULL AFTER `remarks`;
ALTER TABLE `documents` ADD COLUMN `current_strand` VARCHAR(100) DEFAULT NULL AFTER `current_track`;
ALTER TABLE `documents` ADD COLUMN `current_specializations` TEXT DEFAULT NULL AFTER `current_strand`;
ALTER TABLE `documents` ADD COLUMN `strengthened_track` VARCHAR(100) DEFAULT NULL AFTER `current_specializations`;
ALTER TABLE `documents` ADD COLUMN `strengthened_strand` VARCHAR(150) DEFAULT NULL AFTER `strengthened_track`;
ALTER TABLE `documents` ADD COLUMN `strengthened_specializations` TEXT DEFAULT NULL AFTER `strengthened_strand`;
