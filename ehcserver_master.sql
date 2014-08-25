-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ehcserver_ehome
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `jobaevent`
--

DROP TABLE IF EXISTS `jobaevent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobaevent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` varchar(11) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `done` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobaevent`
--

LOCK TABLES `jobaevent` WRITE;
/*!40000 ALTER TABLE `jobaevent` DISABLE KEYS */;
INSERT INTO `jobaevent` VALUES (2,'Blutzuckermessung','180','health','2013-10-03 16:43:10','2013-11-20 19:00:00',1),(4,'Gewichtsmessung','75','health','2013-10-14 06:00:00','0000-00-00 00:00:00',1),(5,'Blutzuckermessung','175','health','2013-10-17 19:01:27','0000-00-00 00:00:00',1),(6,'Blutzuckermessung','175','health','2013-10-22 19:01:31','0000-00-00 00:00:00',1),(7,'Gewichtsmessung','83','health','2013-10-25 19:02:52','0000-00-00 00:00:00',0),(8,'Gewichtsmessung','82.5','health','2013-10-27 20:02:52','0000-00-00 00:00:00',1),(9,'Gewichtsmessung','83','health','2013-10-27 20:03:17','0000-00-00 00:00:00',1),(10,'Gewichtsmessung','82.5','health','2013-10-28 20:03:17','0000-00-00 00:00:00',1),(11,'Gewichtsmessung','83','health','2013-10-22 19:03:20','0000-00-00 00:00:00',1),(12,'Gewichtsmessung','82.5','health','2013-10-29 20:03:20','0000-00-00 00:00:00',1),(13,'Pulsmessung','65','health','2013-10-31 20:04:40','0000-00-00 00:00:00',1),(14,'Pulsmessung','68','health','2013-11-02 20:05:14','0000-00-00 00:00:00',1),(15,'Blutzuckermessung','154','health','2013-11-05 20:06:13','0000-00-00 00:00:00',1),(16,'Pulsmessung','159','health','2013-11-13 20:06:44','0000-00-00 00:00:00',1),(278,'Protokoll','Alle Systemnachrichten wurden gelöscht.','message','2014-05-14 15:13:41','2014-05-14 15:13:41',0),(279,'Protokoll','Licht Nummer Zwei im Raum \'Besprechungsraum\' ausgeschaltet.','message','2014-06-17 10:52:36','2014-06-17 10:52:36',0),(280,'Protokoll','Licht Nummer Eins im Raum \'Besprechungsraum\' eingeschaltet.','message','2014-06-17 10:52:41','2014-06-17 10:52:41',0),(281,'Protokoll','Licht Nummer Zwei im Raum \'Besprechungsraum\' ausgeschaltet.','message','2014-07-03 15:06:17','2014-07-03 15:06:17',0),(282,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 13:34:13','2014-07-07 13:34:13',0),(283,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 13:55:10','2014-07-07 13:55:10',0),(284,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 14:03:30','2014-07-07 14:03:30',0),(285,'Warnung','eine warnung','message','2014-07-07 14:11:01','0000-00-00 00:00:00',0),(286,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-07 14:19:51','2014-07-07 14:19:51',0),(287,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-28 13:47:10','2014-07-28 13:47:10',0),(288,'Protokoll','Raum \'Infotainment\' konfiguriert.','message','2014-07-28 13:47:18','2014-07-28 13:47:18',0),(293,'Amperemessung','5','energy','2014-01-15 14:30:51','0000-00-00 00:00:00',1),(294,'Voltmessung','230','energy','2014-01-15 14:31:01','0000-00-00 00:00:00',1),(295,'Amperemessung','4.9','energy','2014-02-15 14:29:34','0000-00-00 00:00:00',1),(296,'Voltmessung','231','energy','2014-02-15 14:30:41','0000-00-00 00:00:00',1),(297,'Amperemessung','5.1','energy','2014-03-15 14:28:50','0000-00-00 00:00:00',1),(298,'Voltmessung','232','energy','2014-03-15 14:30:11','0000-00-00 00:00:00',1),(299,'Amperemessung','5','energy','2014-04-15 13:31:02','0000-00-00 00:00:00',1),(300,'Voltmessung','231','energy','2014-04-15 13:30:21','0000-00-00 00:00:00',1),(301,'Amperemessung','5.2','energy','2014-05-15 13:30:52','0000-00-00 00:00:00',1),(302,'Voltmessung','234','energy','2014-05-15 13:31:21','0000-00-00 00:00:00',1),(303,'Amperemessung','4.8','energy','2014-06-15 13:28:42','0000-00-00 00:00:00',1),(304,'Voltmessung','229','energy','2014-06-15 13:32:31','0000-00-00 00:00:00',1),(305,'Amperemessung','4.7','energy','2014-07-15 13:32:12','0000-00-00 00:00:00',1),(306,'Voltmessung','233','energy','2014-07-15 13:34:10','0000-00-00 00:00:00',1),(308,'Amperemessung','5.2','energy','2014-08-15 13:20:57','0000-00-00 00:00:00',1),(309,'Voltmessung','230','energy','2014-08-15 14:00:18','0000-00-00 00:00:00',1),(310,'Protokoll','Die Tabelle und die Graphik \"Energiedaten\" wurden hinzugefügt.','message','2014-08-08 10:45:40','0000-00-00 00:00:00',0),(311,'Amperemessung','5','energy','2014-09-15 13:30:02','0000-00-00 00:00:00',1),(312,'Voltmessung','232','energy','2014-09-15 13:33:02','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `jobaevent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `humidity` int(11) NOT NULL,
  `temperature` int(11) NOT NULL,
  `switch` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (1,'Besprechungsraum',20,22,0),(2,'Energie',56,23,0),(3,'Geschäftsführung',95,18,0),(4,'Hiwiraum',60,20,0),(5,'Infotainment',61,21,0),(6,'LivingLab',62,22,0);
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,NULL,'info@jochen-bauer.net',NULL,'$2y$14$c78PtObYNRub2vnGiSivxeGPZfwGXnAyJXz8tufTggHzhweSo9Ppm',NULL),(2,NULL,'thomas.braun@faps.uni-erlangen.de',NULL,'$2y$14$w/YSg8LlStEocMDKjWAlE.7Wi7aKtOK6NVgceZ3gd8tKn.VVemPbq',NULL),(3,NULL,'jochen.bauer@gmail.com',NULL,'$2y$14$dIlVE7x7ysWlsxKTupFyHe88/6mit2HDw/A2BUXidTi86mbi26cFq',NULL),(4,NULL,'guest@jochen-bauer.net',NULL,'$2y$14$g3NucbIMcGNMgxvEYHJ0y.OsVH048OQCpVBMDcx4THbSZ9UZ8hiaq',NULL);
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

-- Dump completed on 2014-08-12 15:05:10