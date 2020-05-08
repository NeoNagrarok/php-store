-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: localhost    Database: ecfPhp
-- ------------------------------------------------------
-- Server version	5.7.29-0ubuntu0.18.04.1

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
-- Table structure for table `ce_cart`
--

DROP TABLE IF EXISTS `ce_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_cart` (
  `id_user` int(10) unsigned NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(10) unsigned DEFAULT NULL,
  KEY `id_user` (`id_user`),
  KEY `id_product` (`id_product`),
  CONSTRAINT `ce_cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `ce_user` (`id`),
  CONSTRAINT `ce_cart_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `ce_product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ce_cart`
--

LOCK TABLES `ce_cart` WRITE;
/*!40000 ALTER TABLE `ce_cart` DISABLE KEYS */;
INSERT INTO `ce_cart` VALUES (1,3,4),(1,6,2);
/*!40000 ALTER TABLE `ce_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ce_category`
--

DROP TABLE IF EXISTS `ce_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `description` text,
  `_actif` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ce_category`
--

LOCK TABLES `ce_category` WRITE;
/*!40000 ALTER TABLE `ce_category` DISABLE KEYS */;
INSERT INTO `ce_category` VALUES (1,'spells','spells.png','A collection of spells, for every magician, wizard, necromencer etc.','1'),(2,'potions','potions.png','A set of potion for everyone who need to be more ... What you want !','1'),(3,'scrolls','scrolls.jpg','Some scrolls for who doesn\'t have any magic skill','0');
/*!40000 ALTER TABLE `ce_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ce_order`
--

DROP TABLE IF EXISTS `ce_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `address` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `ce_order_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `ce_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ce_order`
--

LOCK TABLES `ce_order` WRITE;
/*!40000 ALTER TABLE `ce_order` DISABLE KEYS */;
INSERT INTO `ce_order` VALUES (29,1,'2020-04-30 14:36:43','{\"country_code\": \"FR\"}'),(30,1,'2020-05-01 19:49:11','{\"country_code\": \"FR\"}'),(31,1,'2020-05-01 19:52:16','{\"country_code\": \"FR\"}');
/*!40000 ALTER TABLE `ce_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ce_order_product`
--

DROP TABLE IF EXISTS `ce_order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_order_product` (
  `id_order` int(10) unsigned NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(10) unsigned DEFAULT NULL,
  KEY `id_product` (`id_product`),
  KEY `id_order` (`id_order`),
  CONSTRAINT `ce_order_product_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `ce_product` (`id`),
  CONSTRAINT `ce_order_product_ibfk_2` FOREIGN KEY (`id_order`) REFERENCES `ce_order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ce_order_product`
--

LOCK TABLES `ce_order_product` WRITE;
/*!40000 ALTER TABLE `ce_order_product` DISABLE KEYS */;
INSERT INTO `ce_order_product` VALUES (29,1,6),(29,2,2),(30,5,1),(31,5,80000);
/*!40000 ALTER TABLE `ce_order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ce_product`
--

DROP TABLE IF EXISTS `ce_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  `description` text,
  `price` float(11,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `_actif` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ce_product`
--

LOCK TABLES `ce_product` WRITE;
/*!40000 ALTER TABLE `ce_product` DISABLE KEYS */;
INSERT INTO `ce_product` VALUES (1,'fireball','fireball.png','With this spell you will launch a terrible fireball on your opponent !',3.00,30,'1'),(2,'frost nova','frostnova.png','Trap your ennemies with a frost spell around you !',3.00,70,'1'),(3,'spark','spark.png','Shine like never with this magnificent spell of thunder family !',3.00,116,'1'),(4,'Soul capture','soulCapture.jpg','A powerful spell for who want to practice enchantment or some obscure ritual ...',50.00,2,'0'),(5,'Health potion','healthPotion.png','Restaure your health for 30 points',1.00,205455,'1'),(6,'Mana potion','manaPotion.png','Restaure your mana for 20 points',2.00,566,'1');
/*!40000 ALTER TABLE `ce_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ce_product_category`
--

DROP TABLE IF EXISTS `ce_product_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_product_category` (
  `id_product` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  KEY `id_product` (`id_product`),
  KEY `id_category` (`id_category`),
  CONSTRAINT `ce_product_category_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `ce_product` (`id`),
  CONSTRAINT `ce_product_category_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `ce_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ce_product_category`
--

LOCK TABLES `ce_product_category` WRITE;
/*!40000 ALTER TABLE `ce_product_category` DISABLE KEYS */;
INSERT INTO `ce_product_category` VALUES (1,1),(2,1),(3,1),(4,1),(5,2),(6,2);
/*!40000 ALTER TABLE `ce_product_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ce_user`
--

DROP TABLE IF EXISTS `ce_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ce_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `_actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ce_user`
--

LOCK TABLES `ce_user` WRITE;
/*!40000 ALTER TABLE `ce_user` DISABLE KEYS */;
INSERT INTO `ce_user` VALUES (1,'Test','test.test@test.test',1),(2,'Toto','toto.toto@toto.toto',1);
/*!40000 ALTER TABLE `ce_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-02  1:14:59
