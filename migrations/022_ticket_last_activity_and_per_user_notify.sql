-- Migration 022: per-user notification correctness + last_activity ordering
-- Database: main Rabin-Ticket DB (e.g. requestr_rahbarian)
-- Run once via phpMyAdmin or: mysql -u USER -p DB_NAME < migrations/022_ticket_last_activity_and_per_user_notify.sql
--
-- If an ALTER fails with "Duplicate column/key name", that step is already applied — continue.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- ---------------------------------------------------------------------------
-- 1) Ticket activity column used by list ordering (newest activity first)
-- ---------------------------------------------------------------------------
ALTER TABLE `ticket`
  ADD COLUMN `last_activity` VARCHAR(32) NOT NULL DEFAULT '' AFTER `log_txt`;

-- Backfill from latest pasokh timestamp
UPDATE `ticket` t
INNER JOIN (
  SELECT `code_ticket`, MAX(CONCAT(`tarikh_sabt`, ' ', `saat_sabt`)) AS `last_ts`
  FROM `pasokh`
  GROUP BY `code_ticket`
) p ON p.`code_ticket` = t.`code`
SET t.`last_activity` = p.`last_ts`
WHERE t.`last_activity` = '';

-- Fallback: tickets with no pasokh rows
UPDATE `ticket`
SET `last_activity` = TRIM(CONCAT(`tarikh_sabt`, ' ', `saat_sabt`))
WHERE `last_activity` = '';

-- ---------------------------------------------------------------------------
-- 2) Indexes for unread / recipient lookups
-- ---------------------------------------------------------------------------
ALTER TABLE `pasokh`
  ADD INDEX `idx_pasokh_unread_recipient` (`oksee`, `code_karbar2`, `code_ticket`);

ALTER TABLE `ticket`
  ADD INDEX `idx_ticket_last_activity` (`last_activity`);

-- ---------------------------------------------------------------------------
-- 3) Widen kind for 'referral' / 'assign'
-- ---------------------------------------------------------------------------
ALTER TABLE `pasokh`
  MODIFY COLUMN `kind` VARCHAR(16) NOT NULL DEFAULT '';

-- ---------------------------------------------------------------------------
-- 4) Normalize referral system rows (excluded from badges)
-- ---------------------------------------------------------------------------
UPDATE `pasokh`
SET
  `kind` = 'referral',
  `oksee` = 'y'
WHERE (`kind` IS NULL OR `kind` = '')
  AND `matn` LIKE '%مسئول پاسخگویی به%';

-- ---------------------------------------------------------------------------
-- 5) Point empty-recipient unread issuer replies at current assignee
-- ---------------------------------------------------------------------------
UPDATE `pasokh` p
INNER JOIN `ticket` t ON t.`code` = p.`code_ticket`
SET
  p.`code_karbar2` = t.`code_p_karbar_anjam`,
  p.`name_karbar2` = t.`name_karbar_anjam`
WHERE p.`oksee` = 'n'
  AND (p.`code_karbar2` IS NULL OR p.`code_karbar2` = '')
  AND p.`code_karbar_sabt` = t.`code_p_karbar`
  AND t.`code_p_karbar_anjam` IS NOT NULL
  AND t.`code_p_karbar_anjam` != ''
  AND t.`code_p_karbar_anjam` != '0'
  AND p.`matn` NOT LIKE '%مسئول پاسخگویی به%'
  AND (p.`kind` IS NULL OR p.`kind` = '' OR p.`kind` NOT IN ('referral'));

-- ---------------------------------------------------------------------------
-- 6) Point empty-recipient unread support replies at ticket creator
-- ---------------------------------------------------------------------------
UPDATE `pasokh` p
INNER JOIN `ticket` t ON t.`code` = p.`code_ticket`
SET
  p.`code_karbar2` = t.`code_p_karbar`,
  p.`name_karbar2` = t.`name_karbar`
WHERE p.`oksee` = 'n'
  AND (p.`code_karbar2` IS NULL OR p.`code_karbar2` = '')
  AND p.`code_karbar_sabt` = t.`code_p_karbar_anjam`
  AND t.`code_p_karbar` IS NOT NULL
  AND t.`code_p_karbar` != ''
  AND p.`matn` NOT LIKE '%مسئول پاسخگویی به%'
  AND (p.`kind` IS NULL OR p.`kind` = '' OR p.`kind` NOT IN ('referral'));

COMMIT;
