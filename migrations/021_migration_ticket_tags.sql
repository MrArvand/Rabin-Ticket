-- Per-user ticket tags (برچسب‌های تیکت) for ticket list
-- Run against the main database (requestr_rahbarian)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

CREATE TABLE IF NOT EXISTS `ticket_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_code_p` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'User code of tag creator',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#6366f1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_owner_tag_title` (`owner_code_p`, `title`),
  KEY `idx_owner` (`owner_code_p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ticket_tag_assignments` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tag_id` int NOT NULL,
  `ticket_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `owner_code_p` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'User who assigned the tag',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_tag_ticket` (`tag_id`, `ticket_code`),
  KEY `idx_owner_ticket` (`owner_code_p`, `ticket_code`),
  KEY `idx_ticket_code` (`ticket_code`),
  CONSTRAINT `fk_ticket_tag_assignments_tag`
    FOREIGN KEY (`tag_id`) REFERENCES `ticket_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
