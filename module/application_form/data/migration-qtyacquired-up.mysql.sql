ALTER TABLE `applicationformitem` 
ADD COLUMN `qtyacquired` int(11) unsigned NOT NULL DEFAULT 0 
AFTER `qty` ;

ALTER TABLE `itemcategory` 
ADD COLUMN `groupflag` int(11) unsigned NOT NULL DEFAULT 0 ;
ALTER TABLE `itemcategory` 
ADD COLUMN `sort` int(11) unsigned NOT NULL DEFAULT 0 ;
