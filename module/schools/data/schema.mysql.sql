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
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branch` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (139,'ΔΕ01.01-ΗΛΕΚΤΡΟΤΕΧΝΙΤΕΣ'),(140,'ΔΕ01.02-ΜΗΧΑΝΟΤΕΧΝΙΤΕΣ'),(141,'ΔΕ01.04-ΗΛΕΚΤΡΟΝΙΚΟΙ'),(142,'ΔΕ01.05-ΟΙΚΟΔΟΜΟΙ'),(143,'ΔΕ01.06-ΕΜΠΕΙΡ. ΜΗΧΑΝΟΛΟΓΟΙ'),(144,'ΔΕ01.07-ΕΜΠΕΙΡ. ΗΛΕΚΤΡΟΛΟΓΟΙ'),(145,'ΔΕ01.08-ΗΛΕΚΤΡΟΣΥΓΚΟΛΛΗΤΕΣ'),(146,'ΔΕ01.09-ΒΟΗΘΟΙ ΧΗΜΙΚΟΥ'),(147,'ΔΕ01.10-ΤΕΧΝΙΤΕΣ ΑΥΤΟΚΙΝΗΤΟΥ'),(148,'ΔΕ01.11-ΤΕΧΝΙΤΕΣ ΨΥΞΕΩΝ (ΨΥΚΤΙΚΟΙ)'),(149,'ΔΕ01.12-ΥΔΡΑΥΛΙΚΟΙ'),(150,'ΔΕ01.13-ΞΥΛΟΥΡΓΟΙ'),(151,'ΔΕ01.14-ΚΟΠΤΙΚΗΣ-ΡΑΠΤΙΚΗΣ'),(152,'ΔΕ01.15-ΑΡΓΥΡΟΧΡΥΣΟΧΟΙΪΑΣ'),(153,'ΔΕ01.16-ΤΕΧΝ. ΑΜΑΞΩΜΑΤΩΝ'),(154,'ΔΕ01.17-ΚΟΜΜΩΤΙΚΗΣ'),(155,'ΔΕ01.18-ΑΙΣΘΗΤΙΚΗΣ'),(172,'ΔΕ1-ΔIOIKHTIKOI'),(156,'ΔΕ1-ΕΙΔΙΚΟΥ ΕΚΠ/ΙΚΟΥ ΠΡΟΣΩΠΙΚΟΥ'),(157,'ΔΕ2-ΓΡΑΜΜΑΤΕΩΝ'),(1,'ΠΕ01-ΘΕΟΛΟΓΟΙ'),(2,'ΠΕ02-ΦΙΛΟΛΟΓΟΙ'),(3,'ΠΕ03-ΜΑΘΗΜΑΤΙΚΟΙ'),(4,'ΠΕ04.01-ΦΥΣΙΚΟΙ'),(5,'ΠΕ04.02-ΧΗΜΙΚΟΙ'),(6,'ΠΕ04.03-ΦΥΣΙΟΓΝΩΣΤΕΣ'),(7,'ΠΕ04.04-ΒΙΟΛΟΓΟΙ'),(8,'ΠΕ04.05-ΓΕΩΛΟΓΟΙ'),(9,'ΠΕ05-ΓΑΛΛΙΚΗΣ ΦΙΛΟΛΟΓΙΑΣ'),(10,'ΠΕ06-ΑΓΓΛΙΚΗΣ ΦΙΛΟΛΟΓΙΑΣ'),(11,'ΠΕ07-ΓΕΡΜΑΝΙΚΗΣ ΦΙΛΟΛΟΓΙΑΣ'),(12,'ΠΕ08-ΚΑΛΩΝ ΤΕΧΝΩΝ'),(13,'ΠΕ09-ΟΙΚΟΝΟΜΟΛΟΓΟΙ'),(170,'ΠΕ1-ΔIOIKHTIKOI'),(14,'ΠΕ10-ΚΟΙΝΩΝΙΟΛΟΓΟΙ'),(15,'ΠΕ11-ΦΥΣΙΚΗΣ ΑΓΩΓΗΣ'),(164,'ΠΕ12-ΜΗΧΑΝΙΚΟΙ'),(16,'ΠΕ12.01-ΠΟΛΙΤΙΚΟΙ ΜΗΧΑΝΙΚΟΙ'),(17,'ΠΕ12.02-ΑΡΧΙΤΕΚΤΟΝΕΣ'),(18,'ΠΕ12.03-ΤΟΠΟΓΡΑΦΟΙ'),(19,'ΠΕ12.04-ΜΗΧΑΝΟΛΟΓΟΙ'),(20,'ΠΕ12.05-ΗΛΕΚΤΡΟΛΟΓΟΙ'),(21,'ΠΕ12.06-ΗΛΕΚΤΡ. ΜΗΧΑΝΙΚΟΙ'),(22,'ΠΕ12.07-ΜΗΧΑΝΙΚΟΙ ΠΑΡΑΓ. & ΔΙΟΙΚΗΣΗΣ'),(23,'ΠΕ12.08-ΧΗΜ. ΜΗΧΑΝΙΚΟΙ'),(24,'ΠΕ12.09-ΜΕΤΑΛΛΕΙΟΛΟΓΟΙ'),(25,'ΠΕ12.10-ΦΥΣ. ΡΑΔΙΟΗΛΕΚΤΡΟΛΟΓΟΙ'),(26,'ΠΕ12.11-ΝΑΥΠΗΓΟΙ'),(27,'ΠΕ12.12-ΜΗΧ. ΚΛΩΣΤΟΫΦΑΝΤΟΥΡΓΙΑΣ'),(28,'ΠΕ12.13-ΠΕΡΙΒΑΛΛΟΝΤΟΛΟΓΟΙ'),(29,'ΠΕ13-ΝΟΜΙΚΟΙ-ΠΟΛ. ΕΠΙΣΤΗΜΩΝ'),(30,'ΠΕ14.01-ΙΑΤΡΟΙ'),(31,'ΠΕ14.02-ΟΔΟΝΤΙΑΤΡΟΙ'),(32,'ΠΕ14.03-ΦΑΡΜΑΚΟΠΟΙΟΙ'),(33,'ΠΕ14.04-ΓΕΩΠΟΝΟΙ'),(34,'ΠΕ14.05-ΔΑΣΟΛΟΓΙΑΣ & ΦΥΣ.ΠΕΡ/ΝΤΟΣ'),(35,'ΠΕ14.06-ΝΟΣΗΛΕΥΤΕΣ'),(36,'ΠΕ15.01-ΟΙΚΙΑΚΗΣ ΟΙΚΟΝΟΜΙΑΣ'),(166,'ΠΕ16-ΜΟΥΣΙΚΗΣ'),(37,'ΠΕ16.01-ΜΟΥΣΙΚΗΣ'),(38,'ΠΕ16.02-ΜΟΥΣΙΚΗΣ'),(165,'ΠΕ17-ΤΕΧΝΟΛΟΓΟΙ MHXANIKOI'),(39,'ΠΕ17.01-ΠΟΛΙΤΙΚΟΙ ΑΣΕΤΕΜ'),(40,'ΠΕ17.02-ΜΗΧΑΝΟΛΟΓΟΙ ΑΣΕΤΕΜ'),(41,'ΠΕ17.03-ΗΛΕΚΤΡΟΛΟΓΟΙ ΑΣΕΤΕΜ'),(42,'ΠΕ17.04-ΗΛΕΚΤΡΟΝΙΚΟΙ ΑΣΕΤΕΜ'),(43,'ΠΕ17.05-ΤΟΠΟΓΡΑΦΟΙ ΑΣΕΤΕΜ'),(44,'ΠΕ17.06-ΝΑΥΤ.ΕΜΠ.Ν.ΑΣΕΤΕΜ-ΤΕΙ-ΚΑΤΕΕ'),(45,'ΠΕ17.07-ΜΗΧΑΝΟΥΡΓΟΙ - ΗΛΕΚΤΡΟΥΡΓΟΙ'),(46,'ΠΕ17.08-ΗΛΕΚΤΡΟΝΙΚΟΙ ΤΕΙ - ΚΑΤΕΕ'),(47,'ΠΕ17.09-ΤΕΧΝΙΚΟΙ ΙΑΤΡΙΚΩΝ ΟΡΓΑΝΩΝ'),(48,'ΠΕ17.10-ΤΕΧΝΟΛΟΓΟΙ ΕΝΕΡΓΕΙΑΚΗΣ ΤΕΧΝ.'),(49,'ΠΕ17.11-ΤΟΠΟΓΡΑΦΟΙ ΤΕΙ - ΚΑΤΕΕ'),(50,'ΠΕ18.01-ΓΡΑΦΙΚΩΝ ΤΕΧΝΩΝ'),(51,'ΠΕ18.02-ΔΙΟΙΚΗΣΗΣ ΕΠΙΧΕΙΡΗΣΕΩΝ'),(52,'ΠΕ18.03-ΛΟΓΙΣΤΙΚΗΣ'),(53,'ΠΕ18.04-ΑΙΣΘΗΤΙΚΗΣ'),(54,'ΠΕ18.05-ΚΟΜΜΩΤΙΚΗΣ'),(55,'ΠΕ18.06-ΚΟΠΤΙΚΗΣ- ΡΑΠΤΙΚΗΣ'),(56,'ΠΕ18.07-ΙΑΤΡΙΚΩΝ ΕΡΓΑΣΤΗΡΙΩΝ'),(57,'ΠΕ18.08-ΟΔΟΝΤΟΤΕΧΝΙΚΗΣ'),(58,'ΠΕ18.09-ΚΟΙΝΩΝΙΚΗΣ ΕΡΓΑΣΙΑΣ'),(59,'ΠΕ18.10-ΝΟΣΗΛΕΥΤΙΚΗ'),(60,'ΠΕ18.11-ΜΑΙΕΥΤΙΚΗ'),(61,'ΠΕ18.12-ΦΥΤΙΚΗΣ ΠΑΡΑΓΩΓΗΣ'),(62,'ΠΕ18.13-ΖΩΙΚΗΣ ΠΑΡΑΓΩΓΗΣ'),(63,'ΠΕ18.14-ΙΧΘΥΟΚΟΜΙΑΣ - ΑΛΙΕΙΑΣ'),(64,'ΠΕ18.15-ΓΕΩΡΓ. ΜΗΧΑΝ. & ΑΡΔΕΥΣΕΩΝ'),(65,'ΠΕ18.16-ΔΑΣΟΠΟΝΙΑΣ'),(66,'ΠΕ18.17-ΔΙΟΙΚΗΣΗΣ ΓΕΩΡΓ. ΕΚΜΕΤΑΛ.'),(67,'ΠΕ18.18-ΟΧΗΜΑΤΩΝ ΤΕΙ'),(68,'ΠΕ18.19-ΣΤΑΤΙΣΤΙΚΗΣ'),(69,'ΠΕ18.20-ΚΛΩΣΤΟΥΦΑΝΤΟΥΡΓΙΑΣ'),(70,'ΠΕ18.21-ΡΑΔΙΟΛΟΓΙΑ - ΑΚΤΙΝΟΛΟΓΙΑ'),(71,'ΠΕ18.22-ΜΕΤΑΛΛΕΙΟΛΟΓΟΙ'),(72,'ΠΕ18.23-ΝΑΥΤ. ΜΑΘ. (ΠΛΟΙΑΡΧΟΙ)'),(73,'ΠΕ18.24-ΕΡΓΑΣΙΟΘΕΡΑΠΕΙΑ'),(74,'ΠΕ18.25-ΦΥΣΙΟΘΕΡΑΠΕΙΑ'),(75,'ΠΕ18.26-ΓΡΑΦΙΣΤΙΚΗΣ'),(76,'ΠΕ18.27-ΔΙΑΚΟΣΜΗΤΙΚΗΣ'),(77,'ΠΕ18.28-ΣΥΝΤΗΡΗΤΕΣ ΕΡΓΩΝ ΤΕΧΝΗΣ & ΑΡΧ.  ΕΥΡΗΜΑΤΩΝ'),(78,'ΠΕ18.29-ΦΩΤΟΓΡΑΦΙΑΣ'),(79,'ΠΕ18.30-ΘΕΡΜΟΚΗΠ.ΚΑΛΛΙΕΡΓΕΙΩΝ & ΑΝΘ/ΜΙΑΣ'),(80,'ΠΕ18.31-ΜΗΧΑΝ. ΕΜΠΟΡ. ΝΑΥΤΙΚΟΥ'),(81,'ΠΕ18.32-ΜΗΧΑΝΟΣΥΝΘ. ΑΕΡΟΣΚΑΦΩΝ'),(82,'ΠΕ18.33-ΒΡΕΦΟΝΗΠΙΟΚΟΜΟΙ'),(83,'ΠΕ18.34-ΑΡΓΥΡΟΧΡΥΣΟΧΟΪΑΣ'),(84,'ΠΕ18.35-ΤΟΥΡΙΣΤΙΚΩΝ ΕΠΙΧΕΙΡΗΣΕΩΝ'),(85,'ΠΕ18.36-ΤΕΧΝΟΛΟΓΟΙ ΤΡΟΦ.& ΔΙΑΤΡΟΦ.'),(86,'ΠΕ18.37-ΔΗΜΟΣΙΑΣ ΥΓΙΕΙΝΗΣ'),(87,'ΠΕ18.38-ΚΕΡΑΜΙΚΗΣ'),(88,'ΠΕ18.39-ΕΠΙΣΚΕΠΤΕΣ ΥΓΕΙΑΣ'),(89,'ΠΕ18.40-ΕΜΠΟΡΙΑΣ & ΔΙΑΦΗΜΙΣΗΣ (MARKETING)'),(90,'ΠΕ18.41-ΔΡΑΜΑΤΙΚΗΣ ΤΕΧΝΗΣ'),(91,'ΠΕ19-ΠΛΗΡΟΦΟΡΙΚΗΣ ΑΕΙ'),(92,'ΠΕ20-ΠΛΗΡΟΦΟΡΙΚΗΣ ΤΕΙ'),(93,'ΠΕ21-ΘΕΡΑΠΕΥΤΕΣ ΛΟΓΟΥ ΑΕΙ'),(94,'ΠΕ22-ΕΠΑΓΓΕΛΜΑΤΙΚΩΝ ΣΥΜΒΟΥΛΩΝ'),(95,'ΠΕ23-ΨΥΧΟΛΟΓΟΙ'),(96,'ΠΕ24-ΠΑΙΔΟΨΥΧΙΑΤΡΟΙ'),(97,'ΠΕ25-ΕΠΙΜΕΛΗΤΩΝ ΥΓΕΙΑΣ'),(98,'ΠΕ26-ΘΕΡΑΠΕΥΤΕΣ ΛΟΓΟΥ ΤΕΙ'),(99,'ΠΕ27-ΕΠΑΓΓΕΛΜΑΤΙΚΩΝ ΣΥΜΒΟΥΛΩΝ'),(100,'ΠΕ28-ΦΥΣΙΚΟΘΕΡΑΠΕΥΤΕΣ'),(101,'ΠΕ29-ΕΡΓΑΣΙΟΘΕΡΑΠΕΥΤΕΣ'),(102,'ΠΕ30-ΚΟΙΝ. ΛΕΙΤΟΥΡΓΟΙ'),(103,'ΠΕ31-ΕΙΔΙΚΟΥ ΕΚΠ/ΙΚΟΥ ΠΡΟΣΩΠΙΚΟΥ'),(104,'ΠΕ32.01-ΘΕΑΤΡΙΚΩΝ ΣΠΟΥΔΩΝ'),(105,'ΠΕ33.01-ΜΕΘΟΔΟΛΟΓΙΑΣ ΙΣΤΟΡΙΑΣ (ΜΙΘΕ)'),(162,'ΠΕ34-ΙΤΑΛΙΚΗΣ'),(163,'ΠΕ40-ΙΣΠΑΝΙΚΗΣ'),(160,'ΠΕ60-ΝΗΠΙΑΓΩΓΟΙ'),(167,'ΠΕ61-ΝΗΠΙΑΓΩΓΟΙ ΕΙΔΙΚΗΣ ΑΓΩΓΗΣ'),(159,'ΠΕ70-ΔΑΣΚΑΛΟΙ'),(161,'ΠΕ71-ΔΑΣΚΑΛΟΙ ΕΙΔΙΚΗΣ ΑΓΩΓΗΣ'),(106,'ΤΕ01.01-ΣΧΕΔΙΑΣΤΕΣ'),(107,'ΤΕ01.02-ΜΗΧΑΝΟΛΟΓΟΙ'),(108,'ΤΕ01.03-ΜΗΧΑΝ. ΑΥΤΟΚΙΝΗΤΩΝ'),(109,'ΤΕ01.04-ΨΥΚΤΙΚΟΙ'),(110,'ΤΕ01.05-ΔΟΜΙΚΟΙ'),(111,'ΤΕ01.06-ΗΛΕΚΤΡΟΛΟΓΟΙ'),(112,'ΤΕ01.07-ΗΛΕΚΤΡΟΝΙΚΟΙ'),(113,'ΤΕ01.08-ΧΗΜΙΚΟΙ ΕΡΓΑΣΤΗΡΙΩΝ'),(114,'ΤΕ01.09-ΜΗΧΑΝ. ΕΜΠΟΡ. ΝΑΥΤΙΚΟΥ'),(115,'ΤΕ01.10-ΥΠΑΛΛΗΛΟΙ ΓΡΑΦΕΙΟΥ'),(116,'ΤΕ01.11-ΥΠΑΛΛΗΛΟΙ ΛΟΓΙΣΤΗΡΙΟΥ'),(117,'ΤΕ01.12-ΔΙΑΚΟΣΜΗΤΙΚΗΣ'),(118,'ΤΕ01.13-ΠΡΟΓΡΑΜΜΑΤΙΣΤΕΣ Υ/Η'),(119,'ΤΕ01.14-ΓΡΑΦΙΚΩΝ ΤΕΧΝΩΝ'),(120,'ΤΕ01.15-ΨΗΦΙΔΟΓΡΑΦΟΙ-ΥΑΛΟΓΡΑΦΟΙ'),(121,'ΤΕ01.17-ΣΥΝΤΗΡΗΤΕΣ ΕΡΓΩΝ ΤΕΧΝΗΣ'),(122,'ΤΕ01.19-ΚΟΜΜΩΤΙΚΗΣ'),(123,'ΤΕ01.20-ΑΙΣΘΗΤΙΚΗΣ'),(124,'ΤΕ01.22-ΚΟΠΤΙΚΗΣ-ΡΑΠΤΙΚΗΣ'),(125,'ΤΕ01.23-ΜΕΤΑΛΛΕΙΟΛΟΓΟΙ'),(126,'ΤΕ01.24-ΩΡΟΛΟΓΟΠΟΙΪΑΣ'),(127,'ΤΕ01.25-ΑΡΓΥΡΟΧΡΥΣΟΧΟΪΑΣ'),(128,'ΤΕ01.26-ΟΔΟΝΤΟΤΕΧΝΙΚΗΣ'),(129,'ΤΕ01.27-ΚΛΩΣΤΟΫΦΑΝΤΟΥΡΓΙΑΣ'),(130,'ΤΕ01.28-ΜΗΧΑΝΟΣΥΝΘΕΤΕΣ ΑΕΡΟΣΚΑΦΩΝ'),(131,'ΤΕ01.30-ΒΟΗΘ.ΠΑΙΔΟΚΟΜΟΙ-ΒΡΕΦΟΚΟΜΟΙ'),(132,'ΤΕ01.31-ΧΕΙΡΙΣΤΕΣ ΙΑΤΡΙΚΩΝ ΣΥΣΚΕΥΩΝ'),(133,'ΤΕ01.32-ΑΝΘΟΚΟΜΙΑΣ & ΚΗΠΟΤΕΧΝΙΑΣ'),(134,'ΤΕ01.33-ΦΥΤΙΚΗΣ ΠΑΡΑΓΩΓΗΣ'),(135,'ΤΕ01.34-ΖΩΙΚΗΣ ΠΑΡΑΓΩΓΗΣ'),(136,'ΤΕ01.35-ΓΕΩΡΓΙΚΩΝ ΜΗΧΑΝΗΜΑΤΩΝ'),(137,'ΤΕ01.36-ΑΓΡΟΤΙΚΩΝ ΣΥΝΕΤΑΙΡΙΣΜΩΝ & ΕΚΜΕΤΑΛΛΕΥΣΕΩΝ'),(171,'ΤΕ1-ΔIOIKHTIKOI'),(169,'ΤΕ10.29-ΒΟΗΘ. ΙΑΤΡ.&ΒΙΟΛΟΓ.  ΕΡΓΑΣΤΗΡΙΩΝ'),(168,'ΤΕ16-ΠΤΥΧΙΟΥΧΟΙ ΩΔΕΙΟΥ'),(138,'ΤΕΟ1.06-ΗΛΕΚΤΡΟΛΟΓΟΙ'),(158,'ΥΕ1-ΚΛΗΤΗΡΩΝ');
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eduadmin`
--

DROP TABLE IF EXISTS `eduadmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eduadmin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_edu_admin` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `c_fk_eduadmin_regioneduadmin_id_idx` (`region_edu_admin`),
  CONSTRAINT `c_fk_eduadmin_regioneduadmin_id` FOREIGN KEY (`region_edu_admin`) REFERENCES `regioneduadmin` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eduadmin`
--

LOCK TABLES `eduadmin` WRITE;
/*!40000 ALTER TABLE `eduadmin` DISABLE KEYS */;
INSERT INTO `eduadmin` VALUES (1,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΑΙΤΩΛΟΑΚΑΡΝΑΝΙΑΣ',1),(3,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΑΡΓΟΛΙΔΑΣ',2),(7,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΑΝΑΤΟΛΙΚΗΣ ΑΤΤΙΚΗΣ',3),(9,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΠΕΙΡΑΙΑ',3),(10,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΑΧΑΙΑΣ',1),(11,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΔΩΔΕΚΑΝΗΣΟΥ',4),(12,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΔΩΔΕΚΑΝΗΣΟΥ',4),(13,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΕΥΒΟΙΑΣ',5),(15,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΗΡΑΚΛΕΙΟΥ',6),(16,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΑΡΔΙΤΣΑΣ',7),(18,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΥΚΛΑΔΩΝ',4),(19,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΛΑΡΙΣΑΣ',7),(20,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΛΕΣΒΟΥ',8),(21,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΜΑΓΝΗΣΙΑΣ',7),(22,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΠΙΕΡΙΑΣ',9),(23,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΣΑΜΟΥ',8),(24,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΣΕΡΡΩΝ',9),(25,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΧΑΝΙΩΝ',6),(26,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΧΙΟΥ',8),(27,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΦΛΩΡΙΝΑΣ',10),(29,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΙΛΚΙΣ',9),(30,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΑΡΓΟΛΙΔΑΣ',2),(31,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΑΡΚΑΔΙΑΣ',2),(32,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΑΡΤΑΣ',11),(36,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΑΧΑΙΑΣ',1),(37,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΓΡΕΒΕΝΩΝ',10),(38,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΔΡΑΜΑΣ',12),(39,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΕΥΒΟΙΑΣ',5),(40,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΗΜΑΘΙΑΣ',9),(41,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΗΡΑΚΛΕΙΟΥ',6),(42,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΘΕΣΠΡΩΤΙΑΣ',11),(43,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΙΩΑΝΝΙΝΩΝ',11),(44,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΑΒΑΛΑΣ',12),(45,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΑΡΔΙΤΣΑΣ',7),(46,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΕΡΚΥΡΑΣ',13),(47,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΕΦΑΛΛΗΝΙΑΣ',13),(48,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΟΖΑΝΗΣ',10),(49,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΥΚΛΑΔΩΝ',4),(50,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΑΚΩΝΙΑΣ',2),(51,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΑΡΙΣΑΣ',7),(52,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΕΣΒΟΥ',8),(53,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΜΑΓΝΗΣΙΑΣ',7),(54,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΜΕΣΣΗΝΙΑΣ',2),(55,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΞΑΝΘΗΣ',12),(56,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΠΕΛΛΑΣ',9),(57,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΠΙΕΡΙΑΣ',9),(58,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΤΡΙΚΑΛΩΝ',7),(59,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΦΘΙΩΤΙΔΑΣ',5),(60,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΧΑΝΙΩΝ',6),(61,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΧΙΟΥ',8),(62,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΑΝΑΤΟΛΙΚΗΣ ΑΤΤΙΚΗΣ',3),(63,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΔΥΤΙΚΗΣ ΑΤΤΙΚΗΣ',3),(64,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΠΕΙΡΑΙΑ',3),(65,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΒΟΙΩΤΙΑΣ',5),(66,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΗΛΕΙΑΣ',1),(67,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΕΒΡΟΥ',12),(68,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΡΟΔΟΠΗΣ',12),(69,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΕΥΡΥΤΑΝΙΑΣ',5),(70,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΖΑΚΥΝΘΟΥ',13),(71,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΑΣΤΟΡΙΑΣ',10),(72,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΑΣΙΘΙΟΥ',6),(73,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΕΥΚΑΔΑΣ',13),(74,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΠΡΕΒΕΖΑΣ',11),(75,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΡΕΘΥΜΝΟΥ',6),(76,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΣΑΜΟΥ',8),(77,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΣΕΡΡΩΝ',9),(78,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΦΩΚΙΔΑΣ',5),(79,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΧΑΛΚΙΔΙΚΗΣ',9),(80,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΤΡΙΚΑΛΩΝ',7),(81,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΡΟΔΟΠΗΣ',12),(82,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΕΡΚΥΡΑΣ',13),(83,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΑΡΚΑΔΙΑΣ',2),(84,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΑΡΤΑΣ',11),(85,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΔΥΤΙΚΗΣ ΑΤΤΙΚΗΣ',3),(86,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΒΟΙΩΤΙΑΣ',5),(87,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΓΡΕΒΕΝΩΝ',10),(88,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΔΡΑΜΑΣ',12),(89,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΕΒΡΟΥ',12),(90,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΗΛΕΙΑΣ',1),(91,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΗΜΑΘΙΑΣ',9),(92,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΘΕΣΠΡΩΤΙΑΣ',11),(93,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΙΩΑΝΝΙΝΩΝ',11),(94,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΟΖΑΝΗΣ',10),(95,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΛΑΣΙΘΙΟΥ',6),(96,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΛΕΥΚΑΔΑΣ',13),(97,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΜΕΣΣΗΝΙΑΣ',2),(98,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΠΕΛΛΑΣ',9),(99,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΠΡΕΒΕΖΑΣ',11),(100,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΡΕΘΥΜΝΟΥ',6),(101,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΦΘΙΩΤΙΔΑΣ',5),(102,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΧΑΛΚΙΔΙΚΗΣ',9),(103,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΞΑΝΘΗΣ',12),(104,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΛΑΚΩΝΙΑΣ',2),(105,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΙΛΚΙΣ',9),(106,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΦΛΩΡΙΝΑΣ',10),(107,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΑΒΑΛΑΣ',12),(112,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΑΣΤΟΡΙΑΣ',10),(113,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΖΑΚΥΝΘΟΥ',13),(114,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΕΥΡΥΤΑΝΙΑΣ',5),(115,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΕΦΑΛΛΗΝΙΑΣ',13),(116,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΦΩΚΙΔΑΣ',5),(117,'ΔΙΕΥΘΥΝΣΗ Π.Ε. Α΄ ΑΘΗΝΑΣ',3),(118,'ΔΙΕΥΘΥΝΣΗ Π.Ε. Δ΄ ΑΘΗΝΑΣ',3),(119,'ΔΙΕΥΘΥΝΣΗ Π.Ε. Β΄ ΑΘΗΝΑΣ',3),(120,'ΔΙΕΥΘΥΝΣΗ Π.Ε. Γ΄ ΑΘΗΝΑΣ',3),(121,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΑΝΑΤ. ΘΕΣ/ΝΙΚΗΣ',9),(122,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΔΥΤ. ΘΕΣ/ΝΙΚΗΣ',9),(123,'ΔΙΕΥΘΥΝΣΗ Π.Ε. ΚΟΡΙΝΘΙΑΣ',2),(124,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. Δ΄ ΑΘΗΝΑΣ',3),(126,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. Β΄ ΑΘΗΝΑΣ',3),(127,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΚΟΡΙΝΘΙΑΣ',2),(128,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. Γ΄ ΑΘΗΝΑΣ',3),(129,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΑΙΤΩΛΟΑΚΑΡΝΑΝΙΑΣ',1),(130,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΑΝΑΤ. ΘΕΣ/ΝΙΚΗΣ',9),(131,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΔΥΤ. ΘΕΣ/ΝΙΚΗΣ',9),(132,'ΔΙΕΥΘΥΝΣΗ ΘΡΗΣΚΕΥΤΙΚΗΣ ΕΚΠΑΙΔΕΥΣΗΣ',29),(133,'ΔΙΕΥΘΥΝΣΗ Δ.Ε. Α΄ ΑΘΗΝΑΣ',3),(134,'ΓΡΑΦΕΙΟ ΜΕΙΟΝΟΤΙΚΗΣ ΕΚΠΑΙΔΕΥΣΗΣ',NULL),(154,'ΣΙΒΙΤΑΝΙΔΕΙΟΣ',NULL);
/*!40000 ALTER TABLE `eduadmin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `educationlevel`
--

DROP TABLE IF EXISTS `educationlevel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `educationlevel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `educationlevel`
--

LOCK TABLES `educationlevel` WRITE;
/*!40000 ALTER TABLE `educationlevel` DISABLE KEYS */;
INSERT INTO `educationlevel` VALUES (4,'ΑΝΕΞ. ΒΑΘΜΙΔΑΣ'),(2,'ΔΕΥΤΕΡΟΒΑΘΜΙΑ'),(3,'ΜΕΤΑΔΕΥΤΕΡΟΒΑΘΜΙΑ'),(1,'ΠΡΩΤΟΒΑΘΜΙΑ');
/*!40000 ALTER TABLE `educationlevel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prefecture`
--

DROP TABLE IF EXISTS `prefecture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prefecture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prefecture`
--

LOCK TABLES `prefecture` WRITE;
/*!40000 ALTER TABLE `prefecture` DISABLE KEYS */;
INSERT INTO `prefecture` VALUES (1,'ΑΙΤΩΛΟΑΚΑΡΝΑΝΙΑΣ'),(66,'ΑΝΑΤΟΛΙΚΗΣ ΑΤΤΙΚΗΣ'),(75,'ΑΝΔΡΟΥ'),(21,'ΑΡΚΑΔΙΑΣ'),(22,'ΑΡΤΑΣ'),(4,'ΑΧΑΪΑΣ'),(38,'ΒΟΙΩΤΙΑΣ'),(54,'ΒΟΡΕΙΟΥ ΤΟΜΕΑ ΑΘΗΝΩΝ'),(23,'ΓΡΕΒΕΝΩΝ'),(24,'ΔΡΑΜΑΣ'),(70,'ΔΥΤΙΚΗΣ ΑΤΤΙΚΗΣ'),(56,'ΔΥΤΙΚΟΥ ΤΟΜΕΑ ΑΘΗΝΩΝ'),(40,'ΕΒΡΟΥ'),(6,'ΕΥΒΟΙΑΣ'),(42,'ΕΥΡΥΤΑΝΙΑΣ'),(43,'ΖΑΚΥΝΘΟΥ'),(39,'ΗΛΕΙΑΣ'),(25,'ΗΜΑΘΙΑΣ'),(8,'ΗΡΑΚΛΕΙΟΥ'),(61,'ΘΑΣΟΥ'),(26,'ΘΕΣΠΡΩΤΙΑΣ'),(44,'ΘΕΣΣΑΛΟΝΙΚΗΣ'),(74,'ΘΗΡΑΣ'),(62,'ΙΘΑΚΗΣ'),(63,'ΙΚΑΡΙΑΣ'),(27,'ΙΩΑΝΝΙΝΩΝ'),(28,'ΚΑΒΑΛΑΣ'),(58,'ΚΑΛΥΜΝΟΥ'),(9,'ΚΑΡΔΙΤΣΑΣ'),(60,'ΚΑΡΠΑΘΟΥ'),(45,'ΚΑΣΤΟΡΙΑΣ'),(73,'ΚΕΑΣ-ΚΥΘΝΟΥ'),(52,'ΚΕΝΤΡΙΚΟΥ ΤΟΜΕΑ ΑΘΗΝΩΝ'),(29,'ΚΕΡΚΥΡΑΣ'),(30,'ΚΕΦΑΛΛΗΝΙΑΣ'),(20,'ΚΙΛΚΙΣ'),(31,'ΚΟΖΑΝΗΣ'),(7,'ΚΟΡΙΝΘΙΑΣ'),(59,'ΚΩ'),(32,'ΛΑΚΩΝΙΑΣ'),(11,'ΛΑΡΙΣΑΣ'),(46,'ΛΑΣΙΘΙΟΥ'),(12,'ΛΕΣΒΟΥ'),(47,'ΛΕΥΚΑΔΑΣ'),(69,'ΛΗΜΝΟΥ'),(13,'ΜΑΓΝΗΣΙΑΣ'),(33,'ΜΕΣΣΗΝΙΑΣ'),(71,'ΜΗΛΟΥ'),(76,'ΜΥΚΟΝΟΥ'),(67,'ΝΑΞΟΥ'),(65,'ΝΗΣΩΝ'),(53,'ΝΟΤΙΟΥ ΤΟΜΕΑ ΑΘΗΝΩΝ'),(34,'ΞΑΝΘΗΣ'),(77,'ΠΑΡΟΥ'),(64,'ΠΕΙΡΑΙΩΣ'),(35,'ΠΕΛΛΑΣ'),(14,'ΠΙΕΡΙΑΣ'),(48,'ΠΡΕΒΕΖΑΣ'),(49,'ΡΕΘΥΜΝΟΥ'),(41,'ΡΟΔΟΠΗΣ'),(57,'ΡΟΔΟΥ'),(15,'ΣΑΜΟΥ'),(16,'ΣΕΡΡΩΝ'),(78,'ΣΠΟΡΑΔΩΝ'),(68,'ΣΥΡΟΥ'),(72,'ΤΗΝΟΥ'),(36,'ΤΡΙΚΑΛΩΝ'),(37,'ΦΘΙΩΤΙΔΑΣ'),(19,'ΦΛΩΡΙΝΑΣ'),(50,'ΦΩΚΙΔΑΣ'),(51,'ΧΑΛΚΙΔΙΚΗΣ'),(17,'ΧΑΝΙΩΝ'),(18,'ΧΙΟΥ');
/*!40000 ALTER TABLE `prefecture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regioneduadmin`
--

DROP TABLE IF EXISTS `regioneduadmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regioneduadmin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regioneduadmin`
--

LOCK TABLES `regioneduadmin` WRITE;
/*!40000 ALTER TABLE `regioneduadmin` DISABLE KEYS */;
INSERT INTO `regioneduadmin` VALUES (29,'ΓΕΝΙΚΗ ΓΡΑΜΜΑΤΕΙΑ ΘΡΗΣΚΕΥΜΑΤΩΝ'),(12,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΑΝΑΤΟΛΙΚΗΣ ΜΑΚΕΔΟΝΙΑΣ ΚΑΙ ΘΡΑΚΗΣ'),(3,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΑΤΤΙΚΗΣ'),(8,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΒΟΡΕΙΟΥ ΑΙΓΑΙΟΥ'),(1,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΔΥΤΙΚΗΣ ΕΛΛΑΔΑΣ'),(10,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΔΥΤΙΚΗΣ ΜΑΚΕΔΟΝΙΑΣ'),(11,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΗΠΕΙΡΟΥ'),(7,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΘΕΣΣΑΛΙΑΣ'),(13,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΙΟΝΙΩΝ ΝΗΣΩΝ'),(9,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΚΕΝΤΡΙΚΗΣ ΜΑΚΕΔΟΝΙΑΣ'),(6,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΚΡΗΤΗΣ'),(4,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΝΟΤΙΟΥ ΑΙΓΑΙΟΥ'),(2,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΠΕΛΟΠΟΝΝΗΣΟΥ'),(5,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΣΤΕΡΕΑΣ ΕΛΛΑΔΑΣ');
/*!40000 ALTER TABLE `regioneduadmin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schooltype`
--

DROP TABLE IF EXISTS `schooltype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schooltype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `educationlevel_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `index_foreignkey_schooltype_educationlevel` (`educationlevel_id`),
  CONSTRAINT `c_fk_schooltype_educationlevel_id` FOREIGN KEY (`educationlevel_id`) REFERENCES `educationlevel` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schooltype`
--

LOCK TABLES `schooltype` WRITE;
/*!40000 ALTER TABLE `schooltype` DISABLE KEYS */;
INSERT INTO `schooltype` VALUES (1,'ΝΗΠΙΑΓΩΓΕΙΟ',1),(2,'ΔΗΜΟΤΙΚΟ',1),(3,'ΓΥΜΝΑΣΙΟ',2),(4,'ΓΕΝΙΚΟ ΛΥΚΕΙΟ',2),(5,'ΕΠΑΓΓΕΛΜΑΤΙΚΟ ΛΥΚΕΙΟ',2),(6,'ΕΠΑΓΓΕΛΜΑΤΙΚΗ ΣΧΟΛΗ',2),(7,'ΤΕΧΝΙΚΟ ΕΠΑΓΓΕΛΜΑΤΙΚΟ ΕΚΠΑΙΔΕΥΤΗΡΙΟ',2),(8,'ΕΡΓΑΣΤΗΡΙΑΚΟ ΚΕΝΤΡΟ',2),(9,'ΕΠΑΓΓΕΛΜΑΤΙΚΟ ΓΥΜΝΑΣΙΟ ',2),(10,'ΓΕΝΙΚΟ ΑΡΧΕΙΟ ΚΡΑΤΟΥΣ',4),(11,'ΕΙΔΙΚΟ ΕΡΓΑΣΤΗΡΙΟ ΕΠΑΓΓΕΛΜΑΤΙΚΗΣ ΕΚΠΑΙΔΕΥΣΗΣ ΚΑΙ ΚΑΤΑΡΤΙΣΗΣ',2),(12,'ΣΧΟΛΙΚΗ ΕΠΙΤΡΟΠΗ ΠΡΩΤΟΒΑΘΜΙΑΣ',4),(13,'ΣΧΟΛΙΚΗ ΕΠΙΤΡΟΠΗ ΔΕΥΤΕΡΟΒΑΘΜΙΑΣ',4),(14,'ΔΙΕΥΘΥΝΣΗ ΔΕΥΤΕΡΟΒΑΘΜΙΑΣ ΕΚΠΑΙΔΕΥΣΗΣ',4),(15,'ΔΙΕΥΘΥΝΣΗ ΠΡΩΤΟΒΑΘΜΙΑΣ ΕΚΠΑΙΔΕΥΣΗΣ',4),(16,'ΠΕΡΙΦΕΡΕΙΑΚΗ ΔΙΕΥΘΥΝΣΗ ΕΚΠΑΙΔΕΥΣΗΣ',4),(17,'ΓΡΑΦΕΙΟ ΠΡΩΤΟΒΑΘΜΙΑΣ ΕΚΠΑΙΔΕΥΣΗΣ',4),(18,'ΓΡΑΦΕΙΟ ΔΕΥΤΕΡΟΒΑΘΜΙΑΣ ΕΚΠΑΙΔΕΥΣΗΣ',4),(19,'ΓΡΑΦΕΙΟ ΕΠΑΓΓΕΛΜΑΤΙΚΗΣ ΕΚΠΑΙΔΕΥΣΗΣ',4),(20,'ΚΕΣΥΠ',4),(21,'ΓΡΑΣΕΠ',4),(22,'ΣΣΝ',4),(23,'ΚΕΔΔΥ',4),(24,'ΚΕΠΛΗΝΕΤ',4),(25,'ΕΚΦΕ',4),(26,'ΚΠΕ',4),(27,'ΠΕΚ',4),(28,'ΣΕΠΕΗΥ',4),(29,'ΕΡΓΑΣΤΗΡΙΑ ΦΥΣΙΚΩΝ ΕΠΙΣΤΗΜΩΝ',4),(30,'ΣΧΟΛΙΚΕΣ ΒΙΒΛΙΟΘΗΚΕΣ',4),(31,'ΔΗΜΟΣΙΕΣ ΒΙΒΛΙΟΘΗΚΕΣ',4),(32,'ΚΟΜΒΟΣ ΠΣΔ',4),(33,'ΥΠΟΥΡΓΕΙΟ ΠΑΙΔΕΙΑΣ',4),(34,'ΦΟΡΕΑΣ ΕΞΩΤΕΡΙΚΟΥ',NULL),(35,'ΕΚΚΛΗΣΙΑΣΤΙΚΟ ΣΧΟΛΕΙΟ',2),(36,'ΙΝΣΤΙΤΟΥΤΟ ΕΠΑΓΓΕΛΜΑΤΙΚΗΣ ΚΑΤΑΡΤΙΣΗΣ',3),(37,'ΣΧΟΛΗ ΕΠΑΓΓΕΛΜΑΤΙΚΗΣ ΚΑΤΑΡΤΙΣΗΣ',3),(38,'ΣΧΟΛΕΙΟ ΔΕΥΤΕΡΗΣ ΕΥΚΑΙΡΙΑΣ',2),(39,'HELPDESK ΦΟΡΕΩΝ ΥΛΟΠΟΙΗΣΗΣ ΤΟΥ ΠΣΔ',4),(40,'ΟΜΟΣΠΟΝΔΙΑ',4),(41,'ΕΛΜΕ',4),(42,'ΜΟΝΑΔΕΣ ΑΛΛΩΝ ΥΠΟΥΡΓΕΙΩΝ',4);
/*!40000 ALTER TABLE `schooltype` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teacher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` int(11) unsigned NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `is_principle` tinyint(1) unsigned DEFAULT '0',
  `is_responsible` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_teacher_school` (`school_id`),
  KEY `index_foreignkey_teacher_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `itemcategory`
--

DROP TABLE IF EXISTS `itemcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemcategory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemcategory`
--

LOCK TABLES `itemcategory` WRITE;
/*!40000 ALTER TABLE `itemcategory` DISABLE KEYS */;
INSERT INTO `itemcategory` VALUES (8,'ACCESS POINT'),(26,'LAPTOP'),(7,'MEDIA CONVERTER'),(6,'MODEM / ROUTER '),(25,'MOTHERBOARD'),(5,'PATCH PANEL'),(4,'POWERLINE PLC'),(14,'PRINTER'),(3,'RACK'),(13,'SCANNER'),(24,'SERVER'),(12,'SPLITTER'),(2,'SWITCH/ HUB'),(23,'TABLET'),(1,'VOIP ADAPTER'),(11,'WEBCAM'),(22,'WORKSTATION'),(41,'ΒΙΝΤΕΟΠΡΟΒΟΛΕΑΣ'),(40,'ΔΙΑΔΡΑΣΤΙΚΟ ΣΥΣΤΗΜΑ'),(39,'ΔΙΑΔΡΑΣΤΙΚΟΣ ΠΙΝΑΚΑΣ'),(21,'ΕΞΩΤΕΡΙΚΟ ΜΕΣΟ ΑΠΟΘΗΚΕΥΣΗΣ'),(38,'ΕΠΕΞΕΡΓΑΣΤΗΣ (CPU)'),(20,'ΗΧΕΙΑ'),(10,'ΚΑΡΤΑ WIRELESS'),(37,'ΚΑΡΤΑ ΓΡΑΦΙΚΩΝ'),(36,'ΚΑΡΤΑ ΔΙΚΤΥΟΥ'),(35,'ΚΑΡΤΑ ΗΧΟΥ'),(9,'ΚΕΡΑΙΑ WIFI'),(34,'ΚΙΝΗΤΟ ΕΡΓΑΣΤΗΡΙΟ'),(33,'ΚΟΥΤΙ ΥΠΟΛΟΓΙΣΤΗ'),(19,'ΜΙΚΡΟΦΩΝΟ MULTIMEDIA'),(32,'ΜΝΗΜΗ RAM'),(31,'ΟΔΗΓΟΙ ΟΠΤΙΚΩΝ ΜΕΣΩΝ'),(30,'ΟΘΟΝΗ'),(18,'ΠΛΗΚΤΡΟΛΟΓΙΟ'),(17,'ΠΟΝΤΙΚΙ'),(29,'ΣΚΛΗΡΟΣ ΔΙΣΚΟΣ'),(16,'ΣΤΑΘΕΡΟΠΟΙΗΤΕΣ ΤΑΣΗΣ & U.P.S'),(28,'ΣΥΣΤΗΜΑ ΤΗΛΕΔΙΑΣΚΕΨΗΣ'),(27,'ΤΡΟΦΟΔΟΤΙΚΟ'),(15,'ΨΗΦ. ΦΩΤΟΓΡΑΦΙΚΗ/ΒΙΝΤΕΟΚΑΜΕΡΑ');
/*!40000 ALTER TABLE `itemcategory` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `lab`
--

DROP TABLE IF EXISTS `lab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lab` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `schoolasset`
--

DROP TABLE IF EXISTS `schoolasset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schoolasset` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `itemcategory_id` int(11) unsigned NOT NULL,
  `school_id` int(11) unsigned NOT NULL,
  `qty` int(11) unsigned NOT NULL,
  `lab_id` int(11) unsigned NOT NULL,
  `acquisition_year` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `index_foreignkey_schoolasset_itemcategory` (`itemcategory_id`),
  KEY `index_foreignkey_schoolasset_school` (`school_id`),
  KEY `index_foreignkey_schoolasset_lab` (`lab_id`)
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
