-- Migration: Add default_user field to departman table
-- This allows each department to have a default assigned user for new tickets

ALTER TABLE `departman` 
ADD COLUMN `default_user_code` VARCHAR(50) NOT NULL DEFAULT '' AFTER `modir`,
ADD COLUMN `default_user_name` VARCHAR(255) NOT NULL DEFAULT '' AFTER `default_user_code`;

-- Add index for faster lookups
ALTER TABLE `departman` 
ADD INDEX `idx_default_user` (`default_user_code`);

