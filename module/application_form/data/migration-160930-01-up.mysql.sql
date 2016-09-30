ALTER TABLE `applicationformitem` 
ADD COLUMN `qtyacquired` int(11) unsigned NOT NULL DEFAULT 0 
AFTER `qty` ;

ALTER TABLE `itemcategory` 
ADD COLUMN `groupflag` int(11) unsigned NOT NULL DEFAULT 0 ;
ALTER TABLE `itemcategory` 
ADD COLUMN `sort` int(11) unsigned NOT NULL DEFAULT 0 ;

LOCK TABLES `itemcategory` WRITE;
/*!40000 ALTER TABLE `itemcategory` DISABLE KEYS */;
INSERT INTO `itemcategory` (`id`,`name`,`groupflag`,`sort`) VALUES 
(101,'ΣΤΑΘΕΡΟΣ ΗΛΕΚΤΡΟΝΙΚΟΣ ΥΠΟΛΟΓΙΣΤΗΣ (DESKTOP)', 1,1),
(102,'ΦΟΡΗΤΟΣ ΗΛΕΚΤΡΟΝΙΚΟΣ ΥΠΟΛΟΓΙΣΤΗΣ (LAPTOP)', 1,2),
(103,'ΕΠΙΤΡΑΠΕΖΙΟΣ ΒΙΝΤΕΟΠΡΟΒΟΛΕΑΣ (SHORT THROW PROJECTOR)', 1,3),
(104,'ΕΠΙΤΟΙΧΟΣ ΒΙΝΤΕΟΠΡΟΒΟΛΕΑΣ (ULTRA SHORT THROW WIFI PROJECTOR)', 1,4),
(105,'ΕΓΧΡΩΜΟΣ ΕΚΤΥΠΩΤΗΣ Α4', 1,5),
(106,'ΑΣΠΡΟΜΑΥΡΟΣ ΕΚΤΥΠΩΤΗΣ LASER Α4', 1,6),
(107,'ΔΙΑΔΙΚΤΥΑΚΗ ΚΑΜΕΡΑ (WEB CAMERA)', 1,7),
(108,'ΕΞΩΤΕΡΙΚΟΣ ΣΚΛΗΡΟΣ ΔΙΣΚΟΣ', 1,8),
(109,'ΑΣΠΡΟΜΑΥΡΟ ΠΟΛΥΜΗΧΑΝΗΜΑ Α4', 1,9),
(110,'ΑΣΠΡΟΜΑΥΡΟ ΠΟΛΥΜΗΧΑΝΗΜΑ Α3', 1,10),
(111,'ΑΣΥΡΜΑΤΟ ΣΗΜΕΙΟ ΠΡΟΣΒΑΣΗΣ (ACCESS POINT)', 1,11),
(112,'ΕΞΥΠΗΡΕΤΗΤΗΣ (HIGH-END WORKSTATION FOR SERVER FUNCTION)', 1,12),
(113,'ΥΠΟΛΟΓΙΣΤΙΚΗ ΜΟΝΑΔΑ ΧΑΜΗΛΟΥ ΟΓΚΟΥ/ΚΑΤΑΝΑΛΩΣΗΣ (SINGLE BOARD COMPUTER)', 1,13),
(114,'ΣΤΑΘΕΡΟΣ ΗΛΕΚΤΡΟΝΙΚΟΣ ΥΠΟΛΟΓΙΣΤΗΣ CLIENT (FAT CLIENT)', 1,14),
(115,'ΑΚΟΥΣΤΙΚΑ (HEADSET)', 1,15),
(116,'ΜΕΤΑΓΩΓΕΑΣ (SWITCH)', 1,16),
(117,'ΤΡΙΣΔΙΑΣΤΑΤΟΣ ΕΚΤΥΠΩΤΗΣ (3D PRINTER)', 1,17),
(118,'ΤΡΙΣΔΙΑΣΤΑΤΟΣ ΣΑΡΩΤΗΣ (3D SCANNER)', 1,18),
(119,'ΣΕΤ ΡΟΜΠΟΤΙΚΗΣ ΝΗΠΙΑΓΩΓΕΙΟΥ', 1,19),
(120,'ΣΕΤ ΡΟΜΠΟΤΙΚΗΣ ΔΗΜΟΤΙΚΟΥ', 1,20),
(121,'ΣΕΤ ΡΟΜΠΟΤΙΚΗΣ ΓΥΜΝΑΣΙΟΥ', 1,21),
(122,'ΣΕΤ ΡΟΜΠΟΤΙΚΗΣ ΛΥΚΕΙΟΥ', 1,22),
(123,'ΔΙΑΔΡΑΣΤΙΚΟ ΣΥΣΤΗΜΑ (INTERACTIVE SET)', 1,23);
/*!40000 ALTER TABLE `itemcategory` ENABLE KEYS */;
UNLOCK TABLES;

ALTER TABLE `itemcategory` AUTO_INCREMENT = 200;
