-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: quizcd5
-- ------------------------------------------------------
-- Server version	5.7.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20190607222404','2019-06-07 22:25:11'),('20190607224307','2019-06-07 22:43:33'),('20190608094936','2019-06-08 09:49:51'),('20190610085606','2019-06-10 08:56:16'),('20190610091803','2019-06-10 09:18:14'),('20190610092053','2019-06-10 09:20:58'),('20190610092308','2019-06-10 09:23:13'),('20190610093944','2019-06-10 09:41:58'),('20190612220225','2019-06-12 22:02:49');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,'simple','Qui est l\'entraineur du Betis Seville durant la saison 2018/2019 ?','a:1:{i:0;s:14:\"Quique Setién\";}'),(2,'simple','Qui est l\'entraineur de Cardiff durant la saison 2018/2019 ?','a:1:{i:0;s:12:\"Neil Warnock\";}'),(3,'simple','Dans quel club joue Mouctar Diakhaby durant la saison 2018/2019 ?','a:1:{i:0;s:10:\"Valence CF\";}'),(4,'simple','Dans quel club joue Aaron Lennon durant la saison 2018/2019 ?','a:1:{i:0;s:10:\"Burnley FC\";}'),(5,'simple','Quel numéro porte Raphaël Varane au Real Madrid ?','a:1:{i:0;s:1:\"5\";}'),(6,'simple','Quelle équipe joue contre Manchester City la finale de la Coupe d\'Angleterre en 2019 ?','a:1:{i:0;s:10:\"Watford FC\";}'),(7,'simple','Qui est le meilleur passeur de Ligue 1 durant la saison 2018/2019 ?','a:1:{i:0;s:14:\"Téji Savanier\";}'),(8,'simple','Quel club de Ligue 1 joue les barages durant la saison 2018/2019 ?','a:1:{i:0;s:9:\"Dijon FCO\";}'),(9,'simple','Combien de fois L\'Olympique Lyonnais a t-il été Champion de France ?','a:1:{i:0;s:1:\"7\";}'),(10,'simple','Quel jeune français est recruté par le FC Barcelone en provenance de Ligue 1 durant mercato 2018 ?','a:1:{i:0;s:17:\"Jean-Clair Todibo\";}'),(21,'simple','Quel numéro porte Moussa Sissoko à Tottenham ?','a:1:{i:0;s:2:\"17\";}'),(23,'simple','Quel numéro porte Valentin Rongier à Nantes ?','a:1:{i:0;s:2:\"28\";}'),(24,'simple','Qui a gagné la Ligue 1 en 2017/2018 ?','a:1:{i:0;s:9:\"AS Monaco\";}'),(25,'simple','Qui a gagné la Coupe du Monde en 2018 ?','a:1:{i:0;s:6:\"France\";}'),(26,'simple','Qui marque l\'unique but de la rencontre France-Brésil en 2006 ?','a:1:{i:0;s:13:\"Thierry Henry\";}');
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_quiz`
--

DROP TABLE IF EXISTS `question_quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `question_quiz` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  PRIMARY KEY (`question_id`,`quiz_id`),
  KEY `IDX_FAFC177D1E27F6BF` (`question_id`),
  KEY `IDX_FAFC177D853CD175` (`quiz_id`),
  CONSTRAINT `FK_FAFC177D1E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FAFC177D853CD175` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_quiz`
--

LOCK TABLES `question_quiz` WRITE;
/*!40000 ALTER TABLE `question_quiz` DISABLE KEYS */;
INSERT INTO `question_quiz` VALUES (1,7),(2,8),(3,7),(4,8),(5,7),(6,8),(7,9),(8,9),(9,9),(10,7),(21,8),(23,9),(24,9),(25,7),(25,11),(26,11);
/*!40000 ALTER TABLE `question_quiz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz`
--

LOCK TABLES `quiz` WRITE;
/*!40000 ALTER TABLE `quiz` DISABLE KEYS */;
INSERT INTO `quiz` VALUES (7,'Quiz Liga'),(8,'Quiz PL'),(9,'Quiz Ligue 1'),(10,'Quiz Série A'),(11,'Quiz Coupe du Monde');
/*!40000 ALTER TABLE `quiz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resultat` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_136AC113A76ED395` (`user_id`),
  KEY `IDX_136AC113853CD175` (`quiz_id`),
  CONSTRAINT `FK_136AC113853CD175` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`),
  CONSTRAINT `FK_136AC113A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `result`
--

LOCK TABLES `result` WRITE;
/*!40000 ALTER TABLE `result` DISABLE KEYS */;
INSERT INTO `result` VALUES (10,0,2,11),(11,3,3,8),(13,3,3,9);
/*!40000 ALTER TABLE `result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `first_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'test@test.com','$argon2i$v=19$m=65536,t=6,p=1$OTVDbFdGeEVWUkM0ejg0Mg$AVtT7fV+5alESdxiM3J0eiufrCGE/Ac/COReABJUaJQ','[]','TestPrénom','TESTNOM',762887107,'2019-06-10 10:23:49','adresse test'),(3,'adsy@adsy.com','$argon2i$v=19$m=65536,t=6,p=1$TkM1czBUU0sxQmV4VTVBag$tNURGLUdy3AruNXnDoiJqsd0fAAWLMDXjIcQDQg3UM0','[]','Adrien','SIMON',762887107,'2019-06-10 10:23:49','34 rue Lucien Voilin, 92800 Puteaux'),(6,'wu@wu.com','$argon2i$v=19$m=65536,t=6,p=1$WS5RUWxXc3dpN1AuMHB4Rg$NG6q/e71dXxJ948fhCoYKEk5N/qaA9PxTPD8NHH7Zvw','[]','Céline','WU',123456789,'2019-06-10 10:23:49','1 rue des wu');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-20 22:07:57
