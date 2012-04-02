-- MySQL dump 10.13  Distrib 5.5.16, for osx10.6 (i386)
--
-- Host: localhost    Database: project
-- ------------------------------------------------------
-- Server version	5.5.16

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
-- Table structure for table `Appointment`
--

DROP TABLE IF EXISTS `Appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Appointment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `therapyId` int(11) NOT NULL,
  `therapistId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `invoiceId` int(11) DEFAULT NULL,
  `duration` float DEFAULT NULL,
  `date` date DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Appointment`
--

LOCK TABLES `Appointment` WRITE;
/*!40000 ALTER TABLE `Appointment` DISABLE KEYS */;
INSERT INTO `Appointment` VALUES (10,24,43,3,7,0.5,'2012-03-19'),(11,24,48,2,6,0.5,'2012-03-14'),(12,24,48,2,NULL,1.5,'2012-03-31');
/*!40000 ALTER TABLE `Appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BusinessRules`
--

DROP TABLE IF EXISTS `BusinessRules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BusinessRules` (
  `key` varchar(32) NOT NULL DEFAULT '',
  `value` varchar(32) DEFAULT NULL,
  `fromDate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`key`,`fromDate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BusinessRules`
--

LOCK TABLES `BusinessRules` WRITE;
/*!40000 ALTER TABLE `BusinessRules` DISABLE KEYS */;
INSERT INTO `BusinessRules` VALUES ('betaaltermijn','30','2011-01-01'),('btwhoog','19','2012-01-01'),('kvknummer','A269-0912','2011-01-01'),('rekeningnummer','Abn Amro 568308976','2011-01-01'),('telefoonnummer','030 - 2719944','2011-01-01');
/*!40000 ALTER TABLE `BusinessRules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Customer`
--

DROP TABLE IF EXISTS `Customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Customer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postalCode` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Customer`
--

LOCK TABLES `Customer` WRITE;
/*!40000 ALTER TABLE `Customer` DISABLE KEYS */;
INSERT INTO `Customer` VALUES (2,'Danny van der Sluijs','Weegschaal 98','3721WR','Bilthoven','0619170221',NULL),(3,'Timo van der Sluijs','Weegschaal 98','3721WR','Bilthoven','+31619170221',NULL),(4,'Vincent Vermeulen','Dorpsstraat 12','3421 AG','Zeist','+31302234567',NULL);
/*!40000 ALTER TABLE `Customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CustomerInsurance`
--

DROP TABLE IF EXISTS `CustomerInsurance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CustomerInsurance` (
  `customerId` int(11) NOT NULL DEFAULT '0',
  `insuranceAgencyId` int(11) NOT NULL DEFAULT '0',
  `polisNumber` varchar(255) DEFAULT NULL,
  `fromDate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`customerId`,`insuranceAgencyId`,`fromDate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CustomerInsurance`
--

LOCK TABLES `CustomerInsurance` WRITE;
/*!40000 ALTER TABLE `CustomerInsurance` DISABLE KEYS */;
INSERT INTO `CustomerInsurance` VALUES (2,2,'123456','2012-03-01'),(2,2,'1234567','2012-03-19'),(2,3,'123456777777','2012-03-31');
/*!40000 ALTER TABLE `CustomerInsurance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `InsuranceAgency`
--

DROP TABLE IF EXISTS `InsuranceAgency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `InsuranceAgency` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postalCode` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `InsuranceAgency`
--

LOCK TABLES `InsuranceAgency` WRITE;
/*!40000 ALTER TABLE `InsuranceAgency` DISABLE KEYS */;
INSERT INTO `InsuranceAgency` VALUES (1,'CZ groep Zorgverzekeringen','Postbus 90152','5000 LD','Tilburg',1),(2,'Agis','Postbus 4200','3800 EE','Utrecht',1),(3,'Besured','Spuiboulevard 202','3311 GR','Dordrecht',1);
/*!40000 ALTER TABLE `InsuranceAgency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Invoice`
--

DROP TABLE IF EXISTS `Invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invoice` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `paid` tinyint(1) DEFAULT '0',
  `reminder` int(11) DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Invoice`
--

LOCK TABLES `Invoice` WRITE;
/*!40000 ALTER TABLE `Invoice` DISABLE KEYS */;
INSERT INTO `Invoice` VALUES (6,'2012-02-12',0,0),(7,'2012-03-27',0,0);
/*!40000 ALTER TABLE `Invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `InvoiceRule`
--

DROP TABLE IF EXISTS `InvoiceRule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `InvoiceRule` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `InvoiceId` int(11) DEFAULT NULL,
  `SessionId` int(11) DEFAULT NULL,
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `InvoiceRule`
--

LOCK TABLES `InvoiceRule` WRITE;
/*!40000 ALTER TABLE `InvoiceRule` DISABLE KEYS */;
/*!40000 ALTER TABLE `InvoiceRule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Session`
--

DROP TABLE IF EXISTS `Session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Session` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `TherapyId` int(11) DEFAULT NULL,
  `TherapistId` int(11) DEFAULT NULL,
  `Time` float DEFAULT NULL,
  `Date` date DEFAULT NULL,
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Session`
--

LOCK TABLES `Session` WRITE;
/*!40000 ALTER TABLE `Session` DISABLE KEYS */;
/*!40000 ALTER TABLE `Session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Therapy`
--

DROP TABLE IF EXISTS `Therapy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Therapy` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Therapy`
--

LOCK TABLES `Therapy` WRITE;
/*!40000 ALTER TABLE `Therapy` DISABLE KEYS */;
INSERT INTO `Therapy` VALUES (24,'bioresonantie',1),(25,'fysiotherapie',1),(26,'acupunctuur',1);
/*!40000 ALTER TABLE `Therapy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TherapyPrice`
--

DROP TABLE IF EXISTS `TherapyPrice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TherapyPrice` (
  `therapyId` int(11) NOT NULL DEFAULT '0',
  `pricePerHour` float DEFAULT NULL,
  `fromDate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`therapyId`,`fromDate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TherapyPrice`
--

LOCK TABLES `TherapyPrice` WRITE;
/*!40000 ALTER TABLE `TherapyPrice` DISABLE KEYS */;
INSERT INTO `TherapyPrice` VALUES (24,34,'2012-02-01'),(25,23.88,'2012-02-01'),(25,23,'2012-03-01'),(25,45.78,'2012-03-04'),(26,15,'2012-03-27');
/*!40000 ALTER TABLE `TherapyPrice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `therapist`
--

DROP TABLE IF EXISTS `therapist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `therapist` (
  `Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `therapist`
--

LOCK TABLES `therapist` WRITE;
/*!40000 ALTER TABLE `therapist` DISABLE KEYS */;
INSERT INTO `therapist` VALUES (41,'Wim Willems',1),(42,'Bernard Boulevard',1),(43,'Henk Hansen',1),(45,'Arjan Alias',1),(47,'Peter Prinse',1),(48,'Jan de Jong',1);
/*!40000 ALTER TABLE `therapist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-03-29 19:39:20
