-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: almacen
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

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
-- Current Database: `almacen`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `almacen` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `almacen`;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (5,'Clavos'),(1,'Repuestos'),(2,'Tornillos'),(3,'Tuercas');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incomes`
--

DROP TABLE IF EXISTS `incomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `incomes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` decimal(25,2) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incomes`
--

LOCK TABLES `incomes` WRITE;
/*!40000 ALTER TABLE `incomes` DISABLE KEYS */;
/*!40000 ALTER TABLE `incomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'filter.jpg','image/jpeg'),(2,'tornillo1.jpg','image/jpeg'),(3,'tornillo2.jpg','image/jpeg'),(17,'tuerca.png','image/png');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `partNo` varchar(60) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT 0.00,
  `sale_price` decimal(25,2) DEFAULT 0.00,
  `categorie_id` int(10) unsigned NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partNo` (`partNo`),
  KEY `categorie_id` (`categorie_id`),
  KEY `media_id` (`media_id`),
  CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Filtro de gasolina','FILT_AB0F01',93,15.00,25.00,1,'X1',1,'2017-06-16 07:03:16'),(2,'Tornillo hexagonal 10mm x 50mm','TOR_HEX_001',30,2.00,3.40,2,'A1',2,'2019-03-01 07:03:16'),(3,'Tornillo hexagonal 8mm x 45mm','TOR_HEX_002',22,2.00,3.00,2,'A2',2,'2019-03-01 07:03:16'),(4,'Tornillo hexagonal 8mm x 60mm','TOR_HEX_003',94,2.00,3.00,2,'X2',2,'2019-03-01 07:03:16'),(5,'Tornillo Phillips1 70mm','TOR_PHI_170',89,2.50,5.10,2,'A1',3,'2019-03-02 07:05:23'),(6,'Tornillo Phillips1 80mm','TOR_PHI_180',90,2.50,4.50,2,'A2',3,'2019-03-02 07:05:34'),(7,'Tornillo Phillips1 90mm','TOR_PHI_190',81,2.50,4.50,2,'X2',3,'2019-03-02 07:06:02'),(8,'Tornillo Phillips2 70mm','TOR_PHI_270',88,2.50,4.50,2,'X4',3,'2019-03-02 07:06:10'),(9,'Tornillo Phillips2 80mm','TOR_PHI_280',85,2.50,4.50,2,'X4',3,'2019-03-02 07:06:15'),(10,'Tornillo Phillips2 90mm','TOR_PHI_290',100,2.50,4.50,2,'X4',3,'2019-03-02 07:06:21'),(11,'Tornillo Phillips3 80mm','TOR_PHI_380',80,3.00,5.20,2,'A1',3,'2020-06-05 17:04:14'),(14,'Tornillo Phillips1 80mm','TOR_PHI_180_2',50,2.90,5.30,2,'A1',3,'2020-06-11 14:20:26');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `buy_price` decimal(25,2) DEFAULT 0.00,
  `sale_price` decimal(25,2) DEFAULT 0.00,
  `total_sale` decimal(25,2) DEFAULT 0.00,
  `profit` decimal(25,2) DEFAULT 0.00,
  `destination` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,7,5,2.50,4.50,21.50,9.00,'Pedro','2020-06-10 00:00:00'),(30,3,4,2.00,3.00,11.00,3.00,'Pedro','2020-06-11 02:26:05'),(32,3,7,2.00,3.00,21.00,7.00,'Juan','2020-06-04 00:00:00'),(33,3,5,2.00,3.00,15.00,5.00,'Almacen La Felicidad','2020-06-08 00:00:00'),(34,2,2,2.00,3.00,5.00,1.00,'Juan P.','2020-06-09 00:00:00'),(35,2,2,2.00,3.00,6.00,2.00,'Jose Martinez','2020-06-03 00:00:00'),(37,2,2,2.00,3.00,6.00,2.00,'Pedro Perez','2020-06-01 00:00:00'),(38,1,10,15.00,25.00,250.00,100.00,'Almacen La Felicidad','2020-06-11 00:00:00'),(39,3,1,2.00,3.00,3.00,1.00,'Almacen La Felicidad','2020-06-08 00:00:00'),(40,7,2,2.50,4.50,9.00,4.00,'Jesus','2020-05-27 00:00:00'),(41,6,10,2.50,4.50,45.00,20.00,'Pedro Perez','2020-05-28 00:00:00'),(45,9,15,2.50,4.20,63.00,25.50,'Pedro Perez','2020-06-08 00:00:00'),(46,5,10,2.50,5.30,53.00,28.00,'Juan Hernandez','2020-06-11 00:00:00');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES (1,'Admin',1,1),(2,'Special',2,1),(3,'User',3,1);
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `user_level` (`user_level`),
  CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','Admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,'hombre-avatar.jpg',1,'2020-06-11 14:37:18'),(2,'Juan Hernandez','jhernandez','b49a5780a99ea81284fc0746a78f84a30e4d5c73',2,'no_image.jpg',1,'2017-06-16 07:11:26'),(3,'Default User','User','12dea96fec20593566ab75692c9949596833adc9',3,'no_image.jpg',1,'2017-06-16 07:11:03'),(13,'Pedro Perez','pperez','a9993e364706816aba3e25717850c26c9cd0d89d',3,'no_image.jpg',1,'2020-06-11 14:36:33');
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

-- Dump completed on 2020-06-11 14:42:14
