-- MySQL dump 10.13  Distrib 5.7.30, for Linux (x86_64)
--
-- Host: localhost    Database: energy_matrix_template
-- ------------------------------------------------------
-- Server version	5.7.30-0ubuntu0.18.04.1

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
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL,
  `tag` varchar(48) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,'Flow Meter','flowMeter');
INSERT INTO `devices` VALUES (2,'Flow Meter','volumeMeter');
INSERT INTO `devices` VALUES (3,'Steam Flow Meter','steamFlowMeter');
INSERT INTO `devices` VALUES (4,'Deaerator','deaerator'); -- Feed Water
INSERT INTO `devices` VALUES (5,'Thermometer','thermometer');
INSERT INTO `devices` VALUES (6,'Ammeter','ammeter'); -- Current

INSERT INTO `devices` VALUES (7,'Relative Humidity Meter','flowMeter');
INSERT INTO `devices` VALUES (8,'Flow Meter','volumeMeter');
INSERT INTO `devices` VALUES (9,'Flow Meter','steamMeter');
INSERT INTO `devices` VALUES (10,'Flow Meter','feedWaterMeter');
INSERT INTO `devices` VALUES (11,'Thermometer','thermometer');
INSERT INTO `devices` VALUES (12,'Current Meter','currentMeter');
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_data`
--

DROP TABLE IF EXISTS `report_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL DEFAULT '0',
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flow_rate` decimal(7,3) NOT NULL DEFAULT '0.000',
  `total_volume` decimal(10,3) NOT NULL DEFAULT '0.000',
  `fahrenheit` decimal(7,3) NOT NULL DEFAULT '0.000',
  `current` decimal(7,3) NOT NULL DEFAULT '0.000',
  `relative_humidity` decimal(7,3) NOT NULL DEFAULT '0.000',
  `voltage_detected` int(1) NOT NULL DEFAULT '0',
  `error` int(4) NOT NULL DEFAULT '0',
  `velocity_reading` decimal(7,3) NOT NULL DEFAULT '0.000',
  `velocity_low_limit` decimal(7,3) NOT NULL DEFAULT '0.000',
  `velocity_high_limit` decimal(7,3) NOT NULL DEFAULT '0.000',
  `velocity_ma_custom` decimal(7,3) NOT NULL DEFAULT '0.000',
  `pressure_reading` decimal(7,3) NOT NULL DEFAULT '0.000',
  `pressure_low_limit` decimal(7,3) NOT NULL DEFAULT '0.000',
  `pressure_high_limit` decimal(7,3) NOT NULL DEFAULT '0.000',
  `pressure_ma_custom` decimal(7,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  KEY `FK_reportId` (`report_id`),
  CONSTRAINT `FK_reportId` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `device_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_userId` (`user_id`),
  CONSTRAINT `FK_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,2,2),(2,2,1),(3,2,3),(4,3,1),(5,2,1),(6,2,1);
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(48) NOT NULL,
  `username` varchar(24) NOT NULL,
  `password` varchar(128) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Kinergetics','kinergetics','$2y$10$eEk6LMUH1vNWf/2c7GHUCeVW1UL6WRn94QEBVU6a7NpAz2DtxmSHO',1),(2,'Test Company','test','$2y$10$rQBTjgcbEI8lOYSfhMRDDOt.YCPCqGfi05i0XAHq51Czlk2INLKp2',0),(3,'Test Company 2','test2','$2y$10$rQBTjgcbEI8lOYSfhMRDDOt.YCPCqGfi05i0XAHq51Czlk2INLKp2',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-08 12:52:21
