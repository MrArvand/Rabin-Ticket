-- Migration: connect users to departments (many-to-many)
-- A user can be connected to more than one department. Through the department
-- the user inherits the department's company (sherkatha) and division (moavenat).
-- Run once against database `rahbaria_requestr_rahbarian`.

CREATE TABLE IF NOT EXISTS `department_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` VARCHAR(255) NOT NULL,
  `user_code` VARCHAR(64) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_department_user` (`department_id`, `user_code`),
  KEY `idx_department_id` (`department_id`),
  KEY `idx_user_code` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
