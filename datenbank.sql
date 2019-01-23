-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: localhost    Database: dch_noten_demodb
-- ------------------------------------------------------
-- Server version	5.7.24-0ubuntu0.16.04.1

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
-- Current Database: `dch_noten_demodb`
--


--
-- Table structure for table `archiv`
--

DROP TABLE IF EXISTS `archiv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archiv` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Komponist` varchar(255) DEFAULT NULL,
  `Werk` longtext,
  `Kategorie` int(11) NOT NULL DEFAULT '1',
  `Raum` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archiv`
--

LOCK TABLES `archiv` WRITE;
/*!40000 ALTER TABLE `archiv` DISABLE KEYS */;
/*!40000 ALTER TABLE `archiv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `globalSettings`
--

DROP TABLE IF EXISTS `globalSettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `globalSettings` (
  `attribute` varchar(15) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`attribute`),
  UNIQUE KEY `globalSettings_attribute_uindex` (`attribute`),
  UNIQUE KEY `globalSettings_value_uindex` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `globalSettings`
--

LOCK TABLES `globalSettings` WRITE;
/*!40000 ALTER TABLE `globalSettings` DISABLE KEYS */;
INSERT INTO `globalSettings` VALUES ('maintenanceMode',0);
/*!40000 ALTER TABLE `globalSettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategorie`
--

DROP TABLE IF EXISTS `kategorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategorie` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Kategorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Kategorie` (`Kategorie`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategorie`
--

LOCK TABLES `kategorie` WRITE;
/*!40000 ALTER TABLE `kategorie` DISABLE KEYS */;
INSERT INTO `kategorie` VALUES (2,'Messen'),(1,'Motetten');
/*!40000 ALTER TABLE `kategorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_token`
--

DROP TABLE IF EXISTS `login_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` varchar(30) NOT NULL,
  `token` varchar(30) NOT NULL,
  `date` datetime NOT NULL,
  `isPermanent` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_token_token_uindex` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_token`
--

LOCK TABLES `login_token` WRITE;
/*!40000 ALTER TABLE `login_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Komponist` varchar(255) DEFAULT NULL,
  `Werk` longtext,
  `Kategorie` int(11) NOT NULL DEFAULT '0',
  `Raum` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Raum` (`Raum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note`
--

LOCK TABLES `note` WRITE;
/*!40000 ALTER TABLE `note` DISABLE KEYS */;
/*!40000 ALTER TABLE `note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `standorte`
--

DROP TABLE IF EXISTS `standorte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `standorte` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Raum` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Raum` (`Raum`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `standorte`
--

LOCK TABLES `standorte` WRITE;
/*!40000 ALTER TABLE `standorte` DISABLE KEYS */;
INSERT INTO `standorte` VALUES (0,'archiv'),(12,'messen'),(4,'motteten');
/*!40000 ALTER TABLE `standorte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(30) DEFAULT NULL,
  `prename` varchar(30) DEFAULT NULL,
  `login` varchar(30) DEFAULT NULL,
  `password` varchar(30) NOT NULL,
  `readOnly` tinyint(1) NOT NULL DEFAULT '1',
  `isDev` tinyint(1) NOT NULL DEFAULT '0',
  `isInArchiveMode` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_id_uindex` (`id`),
  UNIQUE KEY `users_login_uindex` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=10005 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (10002,'Demo','Demo Benutzer','demo-view','demo',1,0,0),(10003,'Demo','Demo Verwalter','demo-edit','demo',0,0,0),(10004,'Demo','Demo Admin','demo-admin','demo',0,1,0);
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

-- Dump completed on 2019-01-23  1:40:58

