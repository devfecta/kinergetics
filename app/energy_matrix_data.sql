-- MySQL dump 10.13  Distrib 5.7.32, for Linux (x86_64)
--
-- Host: localhost    Database: energy_matrix
-- ------------------------------------------------------
-- Server version	5.7.32-0ubuntu0.18.04.1

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
-- Table structure for table `data_points`
--

DROP TABLE IF EXISTS `data_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `sensor_id` int(11) NOT NULL DEFAULT '0',
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_point` longtext,
  `custom_field` tinyint(1) NOT NULL DEFAULT '0',
  `custom_value` decimal(7,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`),
  KEY `FK_dataPoint_userId` (`user_id`),
  CONSTRAINT `FK_dataPoint_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_points`
--

LOCK TABLES `data_points` WRITE;
/*!40000 ALTER TABLE `data_points` DISABLE KEYS */;
INSERT INTO `data_points` VALUES (14,2,528911,'2020-12-04 23:40:00','{\"state\": \"16\", \"rawData\": \"23.3\", \"voltage\": \"3.19\", \"dataType\": \"TemperatureData\", \"sensorID\": \"528911\", \"dataValue\": \"23.3\", \"networkID\": \"3\", \"plotLabels\": \"Fahrenheit\", \"plotValues\": \"73.94\", \"sensorName\": \"2 | Test Company | Temperature\", \"messageDate\": \"2020-12-04 17:40:00\", \"batteryLevel\": \"100\", \"applicationID\": \"2\", \"pendingChange\": \"True\", \"signalStrength\": \"0\", \"dataMessageGUID\": \"4c5da9b7-7e0d-46fd-88cb-cac46176cd5f\"}',0,0.000),(15,2,528879,'2020-12-04 02:42:14','{\"state\": \"2\", \"rawData\": \"0\", \"voltage\": \"2.5\", \"dataType\": \"MilliAmps\", \"sensorID\": \"528879\", \"dataValue\": \"0\", \"networkID\": \"3\", \"plotLabels\": \"mA\", \"plotValues\": \"0\", \"sensorName\": \"2 | Test Company | 0-20mA Current\", \"messageDate\": \"2020-09-03 20:42:14\", \"batteryLevel\": \"100\", \"applicationID\": \"22\", \"pendingChange\": \"True\", \"signalStrength\": \"0\", \"dataMessageGUID\": \"7ba0b460-f815-48aa-8ef0-eb254bb61649\"}',0,0.000),(16,2,528911,'2020-12-05 20:53:42','{\"state\": \"1\", \"rawData\": \"22.6\", \"voltage\": \"2.54\", \"dataType\": \"TemperatureData\", \"sensorID\": \"528911\", \"dataValue\": \"22.6\", \"networkID\": \"3\", \"plotLabels\": \"Fahrenheit\", \"plotValues\": \"72.68\", \"sensorName\": \"2 | Test Company | Thermocouple\", \"messageDate\": \"2020-12-05 14:53:42\", \"batteryLevel\": \"100\", \"applicationID\": \"86\", \"pendingChange\": \"True\", \"signalStrength\": \"100\", \"dataMessageGUID\": \"57f2f4d6-3152-4e34-8d38-cf9c0b967f99\"}',0,0.000),(17,2,528889,'2020-12-29 03:52:35','{\"state\": \"0\", \"rawData\": \"0%2c0%2c0%2c0\", \"voltage\": \"3\", \"dataType\": \"AmpHours|Amps|Amps|Amps\", \"sensorID\": \"528889\", \"dataValue\": \"0|0|0|0\", \"networkID\": \"3\", \"plotLabels\": \"Amp Hours|AvgCurrent|MaxCurrent|MinCurrent\", \"plotValues\": \"0|0|0|0\", \"sensorName\": \"2 | Test Company | Current Meter 20 Amp\", \"messageDate\": \"2020-12-28 21:52:35\", \"batteryLevel\": \"100\", \"applicationID\": \"93\", \"pendingChange\": \"True\", \"signalStrength\": \"0\", \"dataMessageGUID\": \"e026e9ec-f193-44f1-abdd-b71817631cdd\"}',0,0.000),(18,2,528911,'2020-11-04 23:40:00','{\"sensorID\":\"528911\",\"sensorName\":\"2 | Test Company | Temperature\",\"applicationID\":\"2\",\"networkID\":\"3\",\"dataMessageGUID\":\"4c5da9b7-7e0d-46fd-88cb-cac46176cd5f\",\"state\":\"16\",\"messageDate\":\"2020-11-04 17:40:00\",\"rawData\":\"23.3\",\"dataType\":\"TemperatureData\",\"dataValue\":\"23.3\",\"plotValues\":\"75.84\",\"plotLabels\":\"Fahrenheit\",\"batteryLevel\":\"100\",\"signalStrength\":\"0\",\"pendingChange\":\"True\",\"voltage\":\"3.19\"}',0,0.000),(19,2,528879,'2020-09-04 01:42:14','{\"sensorID\":\"528879\",\"sensorName\":\"2 | Test Company | 0-20mA Current\",\"applicationID\":\"22\",\"networkID\":\"3\",\"dataMessageGUID\":\"7ba0b460-f815-48aa-8ef0-eb254bb61649\",\"state\":\"2\",\"messageDate\":\"2020-09-03 20:42:14\",\"rawData\":\"0\",\"dataType\":\"MilliAmps\",\"dataValue\":\"0.5\",\"plotValues\":\"0.5\",\"plotLabels\":\"mA\",\"batteryLevel\":\"100\",\"signalStrength\":\"0\",\"pendingChange\":\"True\",\"voltage\":\"2.5\"}',0,0.000),(20,2,528876,'2020-11-05 20:53:42','{\"sensorID\":\"528876\",\"sensorName\":\"2 | Test Company | Thermocouple\",\"applicationID\":\"86\",\"networkID\":\"3\",\"dataMessageGUID\":\"57f2f4d6-3152-4e34-8d38-cf9c0b967f99\",\"state\":\"1\",\"messageDate\":\"2020-11-05 14:53:42\",\"rawData\":\"22.6\",\"dataType\":\"TemperatureData\",\"dataValue\":\"22.6\",\"plotValues\":\"74.38\",\"plotLabels\":\"Fahrenheit\",\"batteryLevel\":\"100\",\"signalStrength\":\"100\",\"pendingChange\":\"True\",\"voltage\":\"2.54\"}',0,0.000),(21,2,528889,'2020-09-29 02:52:35','{\"sensorID\":\"528889\",\"sensorName\":\"2 | Test Company | Current Meter 20 Amp\",\"applicationID\":\"93\",\"networkID\":\"3\",\"dataMessageGUID\":\"e026e9ec-f193-44f1-abdd-b71817631cdd\",\"state\":\"0\",\"messageDate\":\"2020-09-28 21:52:35\",\"rawData\":\"0%2c0%2c0%2c0\",\"dataType\":\"AmpHours|Amps|Amps|Amps\",\"dataValue\":\"0|0|0|0\",\"plotValues\":\"0|3|0|0\",\"plotLabels\":\"Amp Hours|AvgCurrent|MaxCurrent|MinCurrent\",\"batteryLevel\":\"100\",\"signalStrength\":\"0\",\"pendingChange\":\"True\",\"voltage\":\"3\"}',0,0.000);
/*!40000 ALTER TABLE `data_points` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,'Flow Meter','flowMeter'),(2,'Volume Meter','volumeMeter'),(3,'Steam Flow Meter','steamFlowMeter'),(4,'Deaerator','deaerator'),(5,'Thermometer','thermometer'),(6,'Ammeter','ammeter'),(7,'Relative Humidity Meter','flowMeter'),(8,'Flow Meter','volumeMeter'),(9,'Flow Meter','steamMeter'),(10,'Flow Meter','feedWaterMeter'),(11,'Thermometer','thermometer'),(12,'Current Meter','currentMeter');
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_data`
--

LOCK TABLES `report_data` WRITE;
/*!40000 ALTER TABLE `report_data` DISABLE KEYS */;
INSERT INTO `report_data` VALUES (6,5,'2020-06-14 20:06:00',1.020,96.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(7,5,'2020-06-15 18:33:00',1.500,86.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(8,5,'2020-06-13 14:16:00',1.200,75.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(9,5,'2020-06-18 20:06:00',1.010,90.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(10,7,'2020-06-26 21:34:44',0.000,0.000,95.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(12,5,'2020-06-30 01:06:00',1.300,90.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(13,5,'2020-06-29 01:05:00',1.700,77.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(14,6,'2020-06-28 01:06:00',2.000,92.000,0.000,0.000,0.000,0,1,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(19,7,'2020-11-04 23:40:00',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(20,7,'2020-09-04 01:42:14',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(21,7,'2020-11-05 20:53:42',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(22,7,'2020-09-29 02:52:35',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(23,7,'2020-11-04 23:40:00',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(24,7,'2020-09-04 01:42:14',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(25,7,'2020-11-05 20:53:42',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000),(26,7,'2020-09-29 02:52:35',0.000,0.000,0.000,0.000,0.000,0,0,0.000,0.000,0.000,0.000,0.000,0.000,0.000,0.000);
/*!40000 ALTER TABLE `report_data` ENABLE KEYS */;
UNLOCK TABLES;

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
  `form_fields` longtext,
  PRIMARY KEY (`id`),
  KEY `FK_userId` (`user_id`),
  CONSTRAINT `FK_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (5,2,1,'[\"flow_rate\", \"total_volume\", \"error\"]'),(6,2,1,'[\"flow_rate\", \"total_volume\", \"error\"]'),(7,2,5,'[\"fahrenheit\"]');
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

-- Dump completed on 2020-11-19 11:38:45
