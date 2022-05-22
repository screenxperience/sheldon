-- MySQL dump 10.19  Distrib 10.3.34-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sheldon
-- ------------------------------------------------------
-- Server version	10.3.34-MariaDB-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `sheldon`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `sheldon` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `sheldon`;

--
-- Table structure for table `asset`
--

DROP TABLE IF EXISTS `asset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset` (
  `asset_id` int(255) NOT NULL AUTO_INCREMENT,
  `asset_type_id` int(255) NOT NULL,
  `asset_vendor_id` int(255) NOT NULL,
  `asset_model_id` int(255) NOT NULL,
  `asset_building_id` int(255) NOT NULL DEFAULT 1,
  `asset_floor_id` int(255) NOT NULL DEFAULT 1,
  `asset_room_id` int(255) NOT NULL DEFAULT 1,
  `asset_serial` varchar(200) NOT NULL,
  `asset_cis` varchar(200) NOT NULL,
  PRIMARY KEY (`asset_id`),
  UNIQUE KEY `asset_serial` (`asset_serial`),
  KEY `asset_type_id` (`asset_type_id`),
  KEY `asset_vendor_id` (`asset_vendor_id`),
  KEY `asset_model_id` (`asset_model_id`),
  KEY `asset_building_id` (`asset_building_id`),
  KEY `asset_floor_id` (`asset_floor_id`),
  KEY `asset_room_id` (`asset_room_id`),
  CONSTRAINT `asset_ibfk_1` FOREIGN KEY (`asset_type_id`) REFERENCES `type` (`type_id`),
  CONSTRAINT `asset_ibfk_2` FOREIGN KEY (`asset_vendor_id`) REFERENCES `vendor` (`vendor_id`),
  CONSTRAINT `asset_ibfk_3` FOREIGN KEY (`asset_model_id`) REFERENCES `model` (`model_id`),
  CONSTRAINT `asset_ibfk_4` FOREIGN KEY (`asset_building_id`) REFERENCES `building` (`building_id`),
  CONSTRAINT `asset_ibfk_5` FOREIGN KEY (`asset_floor_id`) REFERENCES `floor` (`floor_id`),
  CONSTRAINT `asset_ibfk_6` FOREIGN KEY (`asset_room_id`) REFERENCES `room` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset`
--

LOCK TABLES `asset` WRITE;
/*!40000 ALTER TABLE `asset` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `building`
--

DROP TABLE IF EXISTS `building`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `building` (
  `building_id` int(11) NOT NULL AUTO_INCREMENT,
  `building_name` varchar(200) NOT NULL,
  PRIMARY KEY (`building_id`),
  UNIQUE KEY `building_name` (`building_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `building`
--

LOCK TABLES `building` WRITE;
/*!40000 ALTER TABLE `building` DISABLE KEYS */;
INSERT INTO `building` VALUES (1,'Geb. 31');
/*!40000 ALTER TABLE `building` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci`
--

DROP TABLE IF EXISTS `ci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci` (
  `ci_id` int(255) NOT NULL AUTO_INCREMENT,
  `ci_name` varchar(200) NOT NULL,
  `ci_type` enum('string','select','url','list') NOT NULL,
  `ci_regex` varchar(200) NOT NULL,
  PRIMARY KEY (`ci_id`),
  UNIQUE KEY `ci_name` (`ci_name`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci`
--

LOCK TABLES `ci` WRITE;
/*!40000 ALTER TABLE `ci` DISABLE KEYS */;
INSERT INTO `ci` VALUES (28,'Hostname','string','A-Z0-9'),(29,'MAC-Adresse','string','A-F0-9\:');
/*!40000 ALTER TABLE `ci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `floor`
--

DROP TABLE IF EXISTS `floor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `floor` (
  `floor_id` int(11) NOT NULL AUTO_INCREMENT,
  `floor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`floor_id`),
  UNIQUE KEY `floor_name` (`floor_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `floor`
--

LOCK TABLES `floor` WRITE;
/*!40000 ALTER TABLE `floor` DISABLE KEYS */;
INSERT INTO `floor` VALUES (1,'OG01');
/*!40000 ALTER TABLE `floor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lend`
--

DROP TABLE IF EXISTS `lend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lend` (
  `lend_id` int(255) NOT NULL AUTO_INCREMENT,
  `lend_creator_id` int(255) NOT NULL,
  `lend_user_id` int(255) NOT NULL,
  `lend_assets` longtext NOT NULL,
  `lend_archived_assets` longtext NOT NULL,
  `lend_description` varchar(200) NOT NULL,
  `lend_last_seen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lend_start` date NOT NULL,
  `lend_end` date NOT NULL,
  `lend_archived` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`lend_id`),
  KEY `lend_creator_id` (`lend_creator_id`),
  KEY `lend_user_id` (`lend_user_id`),
  CONSTRAINT `lend_ibfk_1` FOREIGN KEY (`lend_creator_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `lend_ibfk_2` FOREIGN KEY (`lend_user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lend`
--

LOCK TABLES `lend` WRITE;
/*!40000 ALTER TABLE `lend` DISABLE KEYS */;
/*!40000 ALTER TABLE `lend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model`
--

DROP TABLE IF EXISTS `model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `model_name` varchar(200) NOT NULL,
  PRIMARY KEY (`model_id`),
  UNIQUE KEY `model_name` (`model_name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model`
--

LOCK TABLES `model` WRITE;
/*!40000 ALTER TABLE `model` DISABLE KEYS */;
INSERT INTO `model` VALUES (22,'EliteBook 840 G7'),(21,'EliteBook 840 G8'),(23,'Probook 640 G2');
/*!40000 ALTER TABLE `model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rank`
--

DROP TABLE IF EXISTS `rank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rank` (
  `rank_id` int(255) NOT NULL AUTO_INCREMENT,
  `rank_name_long` varchar(200) NOT NULL,
  `rank_name_short` varchar(200) NOT NULL,
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rank`
--

LOCK TABLES `rank` WRITE;
/*!40000 ALTER TABLE `rank` DISABLE KEYS */;
INSERT INTO `rank` VALUES (1,'Gefreiter','Gefr'),(2,'Obergefreiter','OGefr'),(3,'Hauptgefreiter','HptGefr'),(4,'Stabsgefreiter','StGefr'),(5,'Oberstabsgefreiter','OStGefr'),(6,'Korporal','Korporal'),(7,'Stabskorporal','StKorporal'),(8,'Maat','Maat'),(9,'Unteroffizier','Uffz'),(10,'Seekadett','SKad'),(11,'Fahnenjunker','Fhj'),(12,'Obermaat','OMaat'),(13,'Stabsunteroffizier','StUffz'),(14,'Bootsmann','Btsm'),(15,'Feldwebel','Fw'),(16,'Fähnrich zur See','Fähnr zS'),(17,'Fähnrich','Fähnr'),(18,'Oberbootsmann','OBtsm'),(19,'Oberfeldwebel','OFw'),(20,'Hauptbootsmann','HptBtsm'),(21,'Hauptfeldwebel','HptFw'),(22,'Oberfähnrich zur See','OFähnr zS'),(23,'Oberfähnrich','OFähnr'),(24,'Stabsbootsmann','StBtsm'),(25,'Stabsfeldwebel','StFw'),(26,'Oberstabsbootsmann','OStBtsm'),(27,'Oberstabsfeldwebel','OStFw'),(28,'Leutnant zur See','Lt zS'),(29,'Leutnant','Lt'),(30,'Oberleutnant zur See','OLt zS'),(31,'Oberleutnant','OLt'),(32,'Kapitänleutnant','KptLt'),(33,'Hauptmann','Hptm'),(34,'Stabskapitänleutnant','StKptLt'),(35,'Stabshauptmann','StHptm'),(36,'Korvettenkapitän','KKpt'),(37,'Major','Maj'),(38,'Fregattenkapitän','FKpt'),(39,'Oberstleutnant','Oberstlt'),(40,'Kapitän zur See','Kpt zS'),(41,'Oberst','Oberst'),(42,'Flottillenadmiral','FltlAdm'),(43,'Brigadegeneral','BrigGen'),(44,'Konteradmiral','KAdm'),(45,'Generalmajor','GenMaj'),(46,'Vizeadmiral','VAdm'),(47,'Generalleutnant','GenLt'),(48,'Admiral','Adm'),(49,'General','Gen'),(50,'Stabsarzt','StArzt'),(51,'Stabsapotheker','StAp'),(52,'Stabsveterinär','StVet'),(53,'Oberstabsarzt','OStArzt'),(54,'Oberstabsapotheker','OStAp'),(55,'Oberstabsveterinär','OStVet'),(56,'Flottillenarzt','FltlArzt'),(57,'Flottillenapotheker','FltlAp'),(58,'Oberfeldarzt','OFArzt'),(59,'Oberfeldapotheker','OFAp'),(60,'Oberfeldveterinär','OFVet'),(61,'Flottenarzt','FlArzt'),(62,'Oberstarzt','OberstArzt'),(63,'Oberstapotheker','OberstAp'),(64,'Oberstveterinär','OberstVet'),(65,'Admiralarzt','AdmArzt'),(66,'Generalarzt','GenArzt'),(67,'Generalapotheker','GenAp'),(68,'Admiralstabsarzt','AdmStArzt'),(69,'Generalstabsarzt','GenStArzt'),(70,'Admiraloberstabsarzt','AdmOStArzt'),(71,'Generaloberstabsarzt','GenOStArzt');
/*!40000 ALTER TABLE `rank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(200) NOT NULL,
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_name` (`room_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (1,'R. 1.33');
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(200) NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type`
--

LOCK TABLES `type` WRITE;
/*!40000 ALTER TABLE `type` DISABLE KEYS */;
INSERT INTO `type` VALUES (14,'Laptop'),(15,'Webcam');
/*!40000 ALTER TABLE `type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(8) NOT NULL,
  `user_rank_id` int(2) NOT NULL,
  `user_vname` varchar(200) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_building_id` int(255) NOT NULL DEFAULT 1,
  `user_floor_id` int(255) NOT NULL DEFAULT 1,
  `user_room_id` int(255) NOT NULL DEFAULT 1,
  `user_password` varchar(200) NOT NULL,
  `user_salt` varchar(10) NOT NULL,
  `user_active` enum('0','1') NOT NULL DEFAULT '0',
  `user_admin` enum('0','1') NOT NULL DEFAULT '0',
  `user_failed_login` enum('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`),
  KEY `user_building_id` (`user_building_id`),
  KEY `user_floor_id` (`user_floor_id`),
  KEY `user_room_id` (`user_room_id`),
  KEY `user_rank_id` (`user_rank_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_building_id`) REFERENCES `building` (`building_id`),
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`user_floor_id`) REFERENCES `floor` (`floor_id`),
  CONSTRAINT `user_ibfk_3` FOREIGN KEY (`user_room_id`) REFERENCES `room` (`room_id`),
  CONSTRAINT `user_ibfk_4` FOREIGN KEY (`user_rank_id`) REFERENCES `rank` (`rank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (11549851,12,'Alexander','Brosch','alexanderbrosch@bundeswehr.org',1,1,1,'78be7d99d95e79e9807476f6964f97c8560ee703c9aff44d8d94f1f8e8b81b56','j2GVy94PPP','1','1','0');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(200) NOT NULL,
  PRIMARY KEY (`vendor_id`),
  UNIQUE KEY `vendor_name` (`vendor_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (20,'HP');
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-22 19:43:30
