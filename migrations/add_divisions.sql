-- Migration: Add divisions (معاونت) layer between company and department
-- Hierarchy: company (sherkatha) => division (moavenat) => department (departman)
-- Run once against database `rahbaria_requestr_rahbarian`.

CREATE TABLE IF NOT EXISTS `moavenat` (
  `id` VARCHAR(100) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `company_code` VARCHAR(100) NOT NULL,
  `company_name` VARCHAR(255) NOT NULL DEFAULT '',
  `vaziat` CHAR(1) NOT NULL DEFAULT 'y',
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_company_code` (`company_code`),
  KEY `idx_vaziat` (`vaziat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Link departments to a division (denormalized, same style as default_company_*)
ALTER TABLE `departman`
  ADD COLUMN `division_code` VARCHAR(100) NULL DEFAULT NULL AFTER `default_company_name`,
  ADD COLUMN `division_name` VARCHAR(255) NULL DEFAULT NULL AFTER `division_code`,
  ADD KEY `idx_division_code` (`division_code`);
