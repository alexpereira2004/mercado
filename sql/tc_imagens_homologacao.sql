/*
SQLyog Community v10.3 
MySQL - 5.5.23-55 : Database - msabores_prod
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`msabores_prod` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `msabores_prod`;

/*Table structure for table `tc_imagens` */

DROP TABLE IF EXISTS `tc_imagens`;

CREATE TABLE `tc_imagens` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `tx_link` varchar(255) DEFAULT NULL,
  `nm_imagem` varchar(255) DEFAULT NULL,
  `cd_tipo` varchar(5) DEFAULT NULL,
  `cd_status` varchar(5) DEFAULT NULL,
  `cd_extensao` varchar(5) DEFAULT NULL,
  `de_breve` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=latin1;

/*Data for the table `tc_imagens` */

insert  into `tc_imagens`(`id`,`tx_link`,`nm_imagem`,`cd_tipo`,`cd_status`,`cd_extensao`,`de_breve`) values (40,'','Mercado-dos-Sabores-234-1354573305.png','DP','A','',NULL),(66,'','Mercado-dos-Sabores-Luiggi-1354840520.jpg','DP','A','',NULL),(68,'','Luiggi-1354841163.jpg','DP','A','',NULL),(84,'','Amarílis-Cebolas-Frutíferas-Ralado-1355003619.jpg','DP','A','',NULL),(85,'','Amarílis-Cebolas-Frutíferas-Ralado-1355003623.jpg','DP','A','',NULL),(86,'','Amarílis-Cebolas-Frutíferas-Ralado-1355003630.jpg','DP','A','',NULL),(87,'','Amarílis-Lírios-dfgsd-1355004177.jpg','DP','A','',NULL),(103,'','Hortaliças-Caládios-Pimenta-Simples-1357178807.jpeg','DP','A','',NULL),(104,'','Chap%E9u%20de%20bispo-Ralador-de-Pimenta-1357178902.jpg','PR','A','',''),(107,'','Amarílis-Mini-Pimenta-1357181539.jpg','PR','A','',NULL),(108,'','Caládios-Pimenta-Caturra-1357181636.jpg','DP','A','',NULL),(109,'','Pimenta%20de%20Cayenne-Temperadas-Mix-de-Tempero-1357420948.jpg','PR','A','',''),(112,'','Cebolas-Frutíferas-Caládios-Mix-Pimentas-Amarelas-1357421342.jpg','PR','A','',NULL),(115,'','Abóboras-Cebolas-Lírios-Pimenta-Branca-1357853748.jpg','PR','A','',NULL),(116,'','Chap%E9u%20de%20bispo-Frut%EDferas-Salsinha-Verde-1357853816.jpg','PR','A','',''),(117,'','Pimenta%20Boyra%20Habanero%20Vermelha-Pimentas%20Amarelas-Tempero%20Verde-Pimenta-Rosa-1357853888.jpg','PR','A','',''),(118,'','Caládios-Temperadas-Tomates-Salvia-1357853965.jpg','PR','A','',NULL),(119,'','Amar%EDlis-Piment%E3o-Tempero%20Verde-Tempero-Misto-1357854030.jpg','PR','A','',''),(120,'','Ab%F3boras-Pimenta%20Boyra%20Habanero%20Vermelha-Tempero%20Concentrado-Tomates-Kit-de-Temperos-1357854207.jpg','PR','A','',''),(121,'','Caládios-Dálias-Manjericão-Pimenta-Light-1357854403.jpg','PR','A','',NULL),(122,'','Ab%F3boras-Cactos%20e%20Suculentas-Chap%E9u%20de%20bispo-Tempero%20Verde-Molho-Mexicano-1357854480.jpeg','PR','A','',''),(123,'','Amar%EDlis-Chap%E9u%20de%20bispo-Hortali%E7as-Tempero%20Concentrado-Pimenta-Malagueta-1357854705.jpg','PR','A','',''),(124,'','Amarílis-Cebolas-Hortaliças-Pimenta-Albina-1357856005.jpg','PR','A','',NULL),(125,'','Cebolas-Pimenta%20de%20Cayenne-Tempero%20Verde-Pimentas%20Amarelas-Tabasco-em-Pote-1357856096.jpg','PR','A','',''),(126,'','Cebolas-Pimenta%20de%20Cayenne-Pimentas%20Amarelas-Tempero%20Verde-Tabasco-em-Pote-Cru-1357856111.jpg','PR','A','',''),(128,'','Cactos%20e%20Suculentas-Chap%E9u%20de%20bispo-Mostarda-P%F3-Pimenta-Grosso-1357856254.jpg','PR','A','',''),(129,'','Cactos%20e%20Suculentas-Chap%E9u%20de%20bispo-Mostarda-P%F3-Pimenta-Muklti-1357856271.jpg','PR','A','',''),(130,'','Cactos%20e%20Suculentas-Chap%E9u%20de%20bispo-Mostarda-P%F3-Pimenta-Verde-1357856283.png','PR','A','',''),(131,'','Cactos%20e%20Suculentas-D%E1lias-Mix-de-Temperos-Verdes-1357862651.jpg','PR','A','',''),(132,'','Amar%EDlis-Hortali%E7as-Pimenta%20Boyra%20Habanero%20Vermelha-Tempero%20Mix-Tomates-Tempero-Tradicional-1357862690.jpg','PR','A','',''),(133,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1357862749.jpg','PR','A','',''),(134,'','Cactos%20e%20Suculentas-Salsinha-Amarela-1357863017.jpg','PR','A','',''),(135,'','Cactos%20e%20Suculentas-Salsinha-Amarela-1357863027.jpg','DP','A','',''),(136,'','Cactos%20e%20Suculentas-Salsinha-Amarela-1357863032.jpg','DP','A','',''),(137,'','Cactos%20e%20Suculentas-Chap%E9u%20de%20bispo-Mostarda-P%F3-Pimenta-Fino-1357863856.jpg','PR','A','',''),(139,'','Caládios-Hortaliças-Amarílis-Pimenta-Fraca-1357943593.jpg','PR','A','',NULL),(140,'','Frut%EDferas-Pimentas%20Amarelas-Tempero%20Concentrado-Potes-Salgados-1357958441.jpg','PR','A','',''),(141,'','Cactos%20e%20Suculentas-D%E1lias-Mix-de-Temperos-Verdes-1357959260.jpg','DP','A','',''),(142,'','Cactos%20e%20Suculentas-D%E1lias-Mix-de-Temperos-Verdes-1357959288.jpg','DP','A','',''),(143,'','Cactos%20e%20Suculentas-D%E1lias-Mix-de-Temperos-Verdes-1357959298.jpg','DP','A','',''),(144,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1358017622.jpg','DP','A','',''),(145,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1358017681.jpg','DP','A','','Criação de sites empresariais '),(146,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1358017705.jpg','DP','A','','O belo da vida ainda esta para nascer'),(147,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1358017716.jpg','DP','A','','Olá, você tem mal gosto'),(148,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1358017730.jpg','DP','A','','Teste de mensagem pequena'),(149,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1358017741.jpg','DP','A','','Querida nunca diga!'),(150,'','Cactos%20e%20Suculentas-Cal%E1dios-D%E1lias-Mix-de-Temperos-1358018767.png','DP','A','','asdasdasd'),(151,'','Chap%E9u%20de%20bispo-Piment%E3o-Pimentas%20Amarelas-Onda-Reggae-1358030961.jpg','DP','A','','ertyer yert ye rt'),(158,'','1360820954.jpg','DP','A','','Vegetal'),(159,'','1360821017.jpg','PR','A','','Molho Verde'),(161,'','1360821109.jpg','PR','A','','Semente Verde'),(162,'','1360821183.jpg','PR','A','','Pimenta Temperada'),(163,'','1360821258.jpg','PR','A','','Salvia Quente'),(164,'','1360821349.jpg','PR','A','','Pimenta Amarela'),(165,'','1360821403.jpg','PR','A','','Vegetal'),(166,'','1360821510.jpg','PR','A','','Erva Doce'),(167,'','1360821640.jpg','PR','A','','Tempero Amarelo'),(168,'','1360821948.jpg','PR','A','','Ardida'),(169,'','1360822063.jpg','PR','A','','Manjericão Verde Forte'),(170,'','1360822126.jpg','PR','A','','Pimentinha Holandesa'),(171,'','1360822135.jpg','DP','A','','Pimentinha Holandesa'),(172,'','1360822193.jpg','PR','A','','Pimenta Mexicana'),(173,'','1360822261.jpg','PR','A','','Pimenta àrabe'),(174,'','1360822394.jpg','PR','A','','Alho de mesa');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
