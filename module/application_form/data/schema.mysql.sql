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
-- Table structure for table `applicationform`
--

DROP TABLE IF EXISTS `applicationform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applicationform` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) unsigned NOT NULL,
  `apply_for` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_lab_perspective` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` TEXT COLLATE utf8mb4_unicode_ci,
  `submitted` int(11) unsigned NOT NULL,
  `submitted_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_applicationform_school` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `applicationformitem`
--

DROP TABLE IF EXISTS `applicationformitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applicationformitem` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `itemcategory_id` int(11) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL,
  `reasons` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicationform_id` int(11) unsigned NOT NULL,
  `lab_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_applicationformitem_itemcategory` (`itemcategory_id`),
  KEY `index_foreignkey_applicationformitem_applicationform` (`applicationform_id`),
  KEY `index_foreignkey_applicationformitem_lab` (`lab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

