-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: wawtravel
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `road_trip_id` int DEFAULT NULL,
  `checkpoint_id` int DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FFBF41406` (`road_trip_id`),
  KEY `IDX_C53D045FF27C615F` (`checkpoint_id`),
  CONSTRAINT `FK_C53D045FF27C615F` FOREIGN KEY (`checkpoint_id`) REFERENCES `checkpoint` (`id`),
  CONSTRAINT `FK_C53D045FFBF41406` FOREIGN KEY (`road_trip_id`) REFERENCES `road_trip` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES (11,19,NULL,'uploads/roadtrips/mountain-landing-67a79d990967d.webp','roadtrip'),(12,NULL,2,'uploads/checkpoints/67a7f726db749.webp','checkpoint'),(13,NULL,3,'uploads/checkpoints/67a7f8a520546.png','checkpoint'),(14,NULL,3,'uploads/checkpoints/67a7f8a521b76.png','checkpoint'),(15,NULL,3,'uploads/checkpoints/67a7f8a523ac4.png','checkpoint'),(16,20,NULL,'uploads/roadtrips/pexels-gaetanthurin-3163927-67ab592ebfe02.jpg','roadtrip'),(17,NULL,4,'uploads/checkpoints/67ab599f19646.jpg','checkpoint'),(18,NULL,5,'uploads/checkpoints/67ab59c0dc2b7.jpg','checkpoint'),(19,NULL,6,'uploads/checkpoints/67ab59f18a1bc.jpg','checkpoint'),(20,21,NULL,'uploads/roadtrips/pexels-olly-787452-67ab5a16167d7.jpg','roadtrip'),(21,NULL,7,'uploads/checkpoints/67ab5a3be5f74.jpg','checkpoint'),(22,NULL,8,'uploads/checkpoints/67ab5c261440a.jpg','checkpoint');
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checkpoint`
--

DROP TABLE IF EXISTS `checkpoint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checkpoint` (
  `id` int NOT NULL AUTO_INCREMENT,
  `road_trip_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `arrival_date` datetime NOT NULL,
  `departure_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F00F7BEFBF41406` (`road_trip_id`),
  CONSTRAINT `FK_F00F7BEFBF41406` FOREIGN KEY (`road_trip_id`) REFERENCES `road_trip` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checkpoint`
--

LOCK TABLES `checkpoint` WRITE;
/*!40000 ALTER TABLE `checkpoint` DISABLE KEYS */;
INSERT INTO `checkpoint` VALUES (1,19,'aze',56,33,'2025-02-09 01:25:00','2025-02-10 01:25:00'),(2,19,'Paris',42,26,'2025-02-11 01:30:00','2025-02-12 01:30:00'),(3,19,'aze2',-40,10,'2025-02-15 01:36:00','2025-02-02 01:36:00'),(4,20,'Pa Hin',40,40,'2025-02-11 15:07:00','2025-02-12 15:07:00'),(5,20,'Han Go',30,30,'2025-02-13 15:07:00','2025-02-14 15:07:00'),(6,20,'Ion Sua',20,20,'2025-02-15 15:08:00','2025-02-16 15:08:00'),(7,21,'Koualou',15,15,'2025-02-26 15:09:00','2025-02-27 15:10:00'),(8,21,'Kinita',10,10,'2025-02-28 15:18:00','2025-03-02 15:18:00');
/*!40000 ALTER TABLE `checkpoint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `road_trip`
--

DROP TABLE IF EXISTS `road_trip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `road_trip` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `visibility` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_626235CA7E3C61F9` (`owner_id`),
  CONSTRAINT `FK_626235CA7E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `road_trip`
--

LOCK TABLES `road_trip` WRITE;
/*!40000 ALTER TABLE `road_trip` DISABLE KEYS */;
INSERT INTO `road_trip` VALUES (19,1,'Congo','azeaze','public'),(20,2,'Thailande','Voyage en Thaïlande entre amis','public'),(21,2,'Congo','Voyage au congo','public');
/*!40000 ALTER TABLE `road_trip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle`
--

DROP TABLE IF EXISTS `vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1B80E4867E3C61F9` (`owner_id`),
  CONSTRAINT `FK_1B80E4867E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle`
--

LOCK TABLES `vehicle` WRITE;
/*!40000 ALTER TABLE `vehicle` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'emmanueljprime@gmail.com','[]','$2y$13$9bPAB5w46eZp63Rm8yFeYuKD3oNaGFkziLRhvTIAJiy618z.wBLhu','Emmanuel','Prime','Khowld'),(2,'test@test.fr','[]','$2y$13$EprIBFT3/OK5Z3mQeR62LOwTT/29FyQBnRrJ6lgeKCBLas/BHuv.2','Ophélie','Jion','Oracle');
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

-- Dump completed on 2025-02-11 15:23:54
