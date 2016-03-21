/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tpesurvey`
--

DROP TABLE IF EXISTS `tpesurvey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tpesurvey` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) unsigned NOT NULL,
  `already_using_tpe` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `knowledge_level` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assets_in_use` text COLLATE utf8mb4_unicode_ci,
  `sw_web2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sw_packages` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sw_digitalschool` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sw_other` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uc_eduprograms` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uc_digitaldesign` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uc_asyncedu` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uc_other` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edu_fields_current` text COLLATE utf8mb4_unicode_ci,
  `edu_fields_future` text COLLATE utf8mb4_unicode_ci,
  `edu_fields_future_sync_type` tinyint(1) unsigned DEFAULT 0,
  `edu_fields_future_async_type` tinyint(1) unsigned DEFAULT 0,
  `extra_needs` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_id_UNIQUE` (`teacher_id`),
  KEY `index_foreignkey_tpesurvey_teacher` (`teacher_id`),
  CONSTRAINT `c_fk_tpesurvey_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tpesurvey`
--

LOCK TABLES `tpesurvey` WRITE;
/*!40000 ALTER TABLE `tpesurvey` DISABLE KEYS */;
/*!40000 ALTER TABLE `tpesurvey` ENABLE KEYS */;
UNLOCK TABLES;


ALTER TABLE `school` 
ADD COLUMN `teachers_count` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `registry_no`;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
