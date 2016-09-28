SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
DROP TABLE IF EXISTS `user_urole`;
DROP TABLE IF EXISTS `urole_upermission`;
DROP TABLE IF EXISTS `urole`;
DROP TABLE IF EXISTS `upermission`;

CREATE TABLE `urole` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `upermission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `permkey` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `urole_upermission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `urole_id` int(11) unsigned NOT NULL,
  `upermission_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_urole_upermission_composite` (`urole_id`, `upermission_id`),
  KEY `ix_fk_urole_upermission_urole` (`urole_id`),
  KEY `ix_fk_urole_upermission_upermission` (`upermission_id`),
  CONSTRAINT `c_fk_urole_upermission_urole` FOREIGN KEY (`urole_id`) REFERENCES `urole` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `c_fk_urole_upermission_upermission` FOREIGN KEY (`upermission_id`) REFERENCES `upermission` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_urole` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `urole_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_user_urole_composite` (`user_id`, `urole_id`),
  KEY `ix_fk_user_urole_user` (`user_id`),
  KEY `ix_fk_user_urole_urole` (`urole_id`),
  CONSTRAINT `c_fk_user_urole_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `c_fk_user_urole_urole` FOREIGN KEY (`urole_id`) REFERENCES `urole` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_eduadmin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `eduadmin_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `un_user_eduadmin_composite` (`user_id`, `eduadmin_id`),
  KEY `ix_fk_user_eduadmin_user` (`user_id`),
  KEY `ix_fk_user_eduadmin_eduadmin` (`eduadmin_id`),
  CONSTRAINT `c_fk_user_eduadmin_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `c_fk_user_eduadmin_eduadmin` FOREIGN KEY (`eduadmin_id`) REFERENCES `eduadmin` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


LOCK TABLES `urole` WRITE;
INSERT INTO `urole` (`id`,`title`) VALUES (1,'user_admin');
INSERT INTO `urole` (`id`,`title`) VALUES (2,'application_admin');
INSERT INTO `urole` (`id`,`title`) VALUES (3,'application_viewer');
INSERT INTO `urole` (`id`,`title`) VALUES (4,'school_admin');
INSERT INTO `urole` (`id`,`title`) VALUES (5,'audit_admin');
INSERT INTO `urole` (`id`,`title`) VALUES (6,'guest');
UNLOCK TABLES;

LOCK TABLES `upermission` WRITE;
INSERT INTO `upermission` (`id`,`permkey`) VALUES (1,'r_applicationform');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (2,'r_applicationformitem');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (3,'r_branch');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (4,'r_eduadmin');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (5,'r_educationlevel');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (6,'r_itemcategory');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (7,'r_lab');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (8,'r_lab_lesson');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (9,'r_lesson');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (10,'r_prefecture');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (11,'r_regioneduadmin');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (12,'r_school');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (13,'r_schoolasset');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (14,'r_schooltype');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (15,'r_software');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (16,'r_softwarecategory');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (17,'r_teacher');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (18,'r_tpesurvey');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (19,'r_user');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (20,'r_urole');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (21,'r_upermission');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (22,'r_urole_upermission');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (23,'r_user_urole');

INSERT INTO `upermission` (`id`,`permkey`) VALUES (101,'w_applicationform');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (102,'w_applicationformitem');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (103,'w_branch');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (104,'w_eduadmin');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (105,'w_educationlevel');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (106,'w_itemcategory');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (107,'w_lab');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (108,'w_lab_lesson');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (109,'w_lesson');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (110,'w_prefecture');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (111,'w_regioneduadmin');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (112,'w_school');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (113,'w_schoolasset');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (114,'w_schooltype');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (115,'w_software');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (116,'w_softwarecategory');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (117,'w_teacher');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (118,'w_tpesurvey');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (119,'w_user');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (120,'w_urole');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (121,'w_upermission');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (122,'w_urole_upermission');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (123,'w_user_urole');

INSERT INTO `upermission` (`id`,`permkey`) VALUES (201,'d_applicationform');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (202,'d_applicationformitem');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (203,'d_branch');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (204,'d_eduadmin');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (205,'d_educationlevel');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (206,'d_itemcategory');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (207,'d_lab');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (208,'d_lab_lesson');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (209,'d_lesson');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (210,'d_prefecture');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (211,'d_regioneduadmin');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (212,'d_school');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (213,'d_schoolasset');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (214,'d_schooltype');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (215,'d_software');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (216,'d_softwarecategory');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (217,'d_teacher');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (218,'d_tpesurvey');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (219,'d_user');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (220,'d_urole');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (221,'d_upermission');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (222,'d_urole_upermission');
INSERT INTO `upermission` (`id`,`permkey`) VALUES (223,'d_user_urole');
UNLOCK TABLES;

LOCK TABLES `urole_upermission` WRITE;
/* read permissions for user_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1019,1,19);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1020,1,20);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1021,1,21);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1022,1,22);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1023,1,23);
/* write permissions for user_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1119,1,119);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1120,1,120);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1121,1,121);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1122,1,122);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1123,1,123);
/* delete permissions for user_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1219,1,219);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1220,1,220);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1221,1,221);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1222,1,222);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1223,1,223);

/* read permissions for application_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1001,2,1);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1002,2,2);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1006,2,6);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1007,2,7);
/* write permissions for application_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1101,2,101);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1102,2,102);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1106,2,106);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1107,2,107);
/* delete permissions for application_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1201,2,201);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1202,2,202);

/* read permissions for application_viewer. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1001,3,1);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1002,3,2);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1006,3,6);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1007,3,7);

/* read permissions for school_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1012,4,12);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1013,4,13);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1014,4,14);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1015,4,15);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1016,4,16);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1017,4,17);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1018,4,18);

/* write permissions for school_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1113,4,113);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1115,4,115);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1117,4,117);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1118,4,118);

/* delete permissions for school_admin. Only what he needs. */
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1213,4,213);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1215,4,215);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1217,4,217);
INSERT INTO `urole_upermission` (`id`,`urole_id`,`upermission_id`) VALUES (1218,4,218);

UNLOCK TABLES;

LOCK TABLES `user_eduadmin` WRITE;
INSERT INTO `user_eduadmin` (`user_id`,`eduadmin_id`) VALUES (1,1,131);
UNLOCK TABLES;