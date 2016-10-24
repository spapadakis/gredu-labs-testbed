ALTER TABLE `applicationform`
ADD COLUMN `received_document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
AFTER `received_by` ;
