ALTER TABLE `applicationformitem`
ADD COLUMN `qtyreceived` int(11) unsigned NOT NULL DEFAULT 0
AFTER `qtyacquired` ;

ALTER TABLE `applicationform`
ADD COLUMN `received_ts` timestamp NULL
AFTER `submitted_by` ;

ALTER TABLE `applicationform`
ADD COLUMN `received_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
AFTER `received_ts` ;

CREATE INDEX `index_applicationform_submitted` ON `applicationform`(`submitted`);
CREATE INDEX `index_applicationform_received_ts` ON `applicationform`(`received_ts`);
