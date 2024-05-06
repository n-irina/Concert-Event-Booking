CREATE DATABASE  IF NOT EXISTS `donkeyevent` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `donkeyevent`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: donkeyevent
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `artist`
--

DROP TABLE IF EXISTS `artist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `artist` (
  `idartist` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idartist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artist`
--

LOCK TABLES `artist` WRITE;
/*!40000 ALTER TABLE `artist` DISABLE KEYS */;
/*!40000 ALTER TABLE `artist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking` (
  `idbooking` int NOT NULL AUTO_INCREMENT,
  `quantity` int NOT NULL,
  `status` varchar(45) NOT NULL,
  `user_iduser` int NOT NULL,
  `event_idevent` int NOT NULL,
  `event_category_idcategory` int NOT NULL,
  `invoice_idinvoice` int NOT NULL,
  PRIMARY KEY (`idbooking`,`user_iduser`,`event_idevent`,`event_category_idcategory`,`invoice_idinvoice`),
  KEY `fk_booking_user1_idx` (`user_iduser`),
  KEY `fk_booking_event1_idx` (`event_idevent`,`event_category_idcategory`),
  KEY `fk_booking_invoice1_idx` (`invoice_idinvoice`),
  CONSTRAINT `fk_booking_event1` FOREIGN KEY (`event_idevent`, `event_category_idcategory`) REFERENCES `event` (`idevent`, `category_idcategory`),
  CONSTRAINT `fk_booking_invoice1` FOREIGN KEY (`invoice_idinvoice`) REFERENCES `invoice` (`idinvoice`),
  CONSTRAINT `fk_booking_user1` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `idcategory` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `date`
--

DROP TABLE IF EXISTS `date`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `date` (
  `iddate` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `event_idevent` int NOT NULL,
  `event_category_idcategory` int NOT NULL,
  PRIMARY KEY (`iddate`,`event_idevent`,`event_category_idcategory`),
  KEY `fk_date_event1_idx` (`event_idevent`,`event_category_idcategory`),
  CONSTRAINT `fk_date_event1` FOREIGN KEY (`event_idevent`, `event_category_idcategory`) REFERENCES `event` (`idevent`, `category_idcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `date`
--

LOCK TABLES `date` WRITE;
/*!40000 ALTER TABLE `date` DISABLE KEYS */;
/*!40000 ALTER TABLE `date` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `idevent` int NOT NULL AUTO_INCREMENT,
  `price` decimal(2,0) NOT NULL,
  `numberPlaces` int NOT NULL,
  `eventName` varchar(45) NOT NULL,
  `category_idcategory` int NOT NULL,
  PRIMARY KEY (`idevent`,`category_idcategory`),
  UNIQUE KEY `idevent_UNIQUE` (`idevent`),
  KEY `fk_event_category1_idx` (`category_idcategory`),
  CONSTRAINT `fk_event_category1` FOREIGN KEY (`category_idcategory`) REFERENCES `category` (`idcategory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_has_artist`
--

DROP TABLE IF EXISTS `event_has_artist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_has_artist` (
  `event_idevent` int NOT NULL,
  `artist_idartist` int NOT NULL,
  PRIMARY KEY (`event_idevent`,`artist_idartist`),
  KEY `fk_event_has_artist_artist1_idx` (`artist_idartist`),
  KEY `fk_event_has_artist_event_idx` (`event_idevent`),
  CONSTRAINT `fk_event_has_artist_artist1` FOREIGN KEY (`artist_idartist`) REFERENCES `artist` (`idartist`),
  CONSTRAINT `fk_event_has_artist_event` FOREIGN KEY (`event_idevent`) REFERENCES `event` (`idevent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_has_artist`
--

LOCK TABLES `event_has_artist` WRITE;
/*!40000 ALTER TABLE `event_has_artist` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_has_artist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice` (
  `idinvoice` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idinvoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
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

-- Dump completed on 2024-04-30 17:26:41
