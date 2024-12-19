/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.6.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: airbnb
-- ------------------------------------------------------
-- Server version	11.6.2-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `Adress`
--

DROP TABLE IF EXISTS `Adress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Adress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `street` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Adress`
--

LOCK TABLES `Adress` WRITE;
/*!40000 ALTER TABLE `Adress` DISABLE KEYS */;
INSERT INTO `Adress` VALUES
(9,'1','1','1'),
(11,'Peprignant','France','7 rue des chazam'),
(12,'0','0','0'),
(13,'Perpignan','1','Pampres');
/*!40000 ALTER TABLE `Adress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AnnouncementEquipment`
--

DROP TABLE IF EXISTS `AnnouncementEquipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AnnouncementEquipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_announcement` int(11) DEFAULT NULL,
  `id_equipment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_announcement` (`id_announcement`),
  KEY `id_equipment` (`id_equipment`),
  CONSTRAINT `AnnouncementEquipment_ibfk_1` FOREIGN KEY (`id_announcement`) REFERENCES `announcement` (`id`),
  CONSTRAINT `AnnouncementEquipment_ibfk_2` FOREIGN KEY (`id_equipment`) REFERENCES `Equipment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AnnouncementEquipment`
--

LOCK TABLES `AnnouncementEquipment` WRITE;
/*!40000 ALTER TABLE `AnnouncementEquipment` DISABLE KEYS */;
/*!40000 ALTER TABLE `AnnouncementEquipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Equipment`
--

DROP TABLE IF EXISTS `Equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Equipment`
--

LOCK TABLES `Equipment` WRITE;
/*!40000 ALTER TABLE `Equipment` DISABLE KEYS */;
/*!40000 ALTER TABLE `Equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TypeAccommodation`
--

DROP TABLE IF EXISTS `TypeAccommodation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TypeAccommodation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `private_room` varchar(255) DEFAULT NULL,
  `public_room` varchar(255) DEFAULT NULL,
  `entire_dwelling` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TypeAccommodation`
--

LOCK TABLES `TypeAccommodation` WRITE;
/*!40000 ALTER TABLE `TypeAccommodation` DISABLE KEYS */;
/*!40000 ALTER TABLE `TypeAccommodation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT NULL,
  `adress_id` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sleeping` int(11) DEFAULT NULL,
  `accommodation_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accommodation_id` (`accommodation_id`),
  KEY `id_adress` (`adress_id`),
  KEY `id_owner` (`owner_id`),
  CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`accommodation_id`) REFERENCES `TypeAccommodation` (`id`),
  CONSTRAINT `announcement_ibfk_2` FOREIGN KEY (`adress_id`) REFERENCES `Adress` (`id`),
  CONSTRAINT `announcement_ibfk_3` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement`
--

LOCK TABLES `announcement` WRITE;
/*!40000 ALTER TABLE `announcement` DISABLE KEYS */;
INSERT INTO `announcement` VALUES
(1,1,9,1,1,'1',1,NULL,NULL),
(2,1,11,75,500,'Un petit studio est un espace de vie compact, souvent composé d\'une seule pièce principale regroupant salon, chambre et cuisine. Idéal pour les étudiants ou les jeunes professionnels, il offre une solution économique et pratique. Maximiser l’aménagement avec des meubles multifonctions est essentiel pour optimiser l’espace. Ce type de logement est souvent situé en centre-ville, proche des commodités.',5,NULL,NULL),
(3,1,12,0,0,'0',0,NULL,NULL),
(4,1,13,2,100,'zz',4,NULL,NULL);
/*!40000 ALTER TABLE `announcement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `role_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'arthur@gmail.com','6a9061e6effec3f73c6bf455401e793242b7b993615a3cd08193649805586103e64a8dc633b33d6c512e853e09953efb1415d5a72328ce27270ea9ce9c0bf8db','Arthur','Cazorla','0102030405','1'),
(2,'toto@toto.toto','ce0302ed35e8266060f6326b8121491dedd6862de36420636959b8b590d9ffa1a2ad25dec6af654a14bcae9f01bb9a8aa0adde5abc2bd50ac03cfd417ea558fa','Toto','Toto','toto','2'),
(3,'aaa@a.a','f804f7dc5553dab13cf935a2d2ae24be52ad074422b251b464af7b5fcf6db1b84ee455aa4523095d7ec57d4166dfb1724f700da22b73ed45d6b7a7a23a4dd22a','Arthur','Cazorla','0768836855','2');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2024-12-19 15:54:03
