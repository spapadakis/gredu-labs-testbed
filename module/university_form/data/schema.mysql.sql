-- Table structure for table `univ`
--

DROP TABLE IF EXISTS `univ`;
--
-- Table structure for table `univ`
--

CREATE TABLE IF NOT EXISTS `univ` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idrima` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sxolh` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmhma` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `person` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telef` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

