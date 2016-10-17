ALTER TABLE `applicationformitem`
DROP COLUMN `qtyreceived` ;

ALTER TABLE `applicationform`
DROP COLUMN `received_ts` ;

ALTER TABLE `applicationform`
DROP COLUMN `received_by` ;

ALTER TABLE applicationform DROP INDEX `index_applicationform_submitted`;
