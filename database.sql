/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `miniframework` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;
USE `miniframework`;

CREATE TABLE IF NOT EXISTS `tb_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tb_info` (`id`, `titulo`, `descricao`) VALUES
	(1, 'Missão', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
	(2, 'Visão', 'Morbi faucibus elit nec nibh elementum, a ultrices ante condimentum.'),
	(3, 'Valores', 'Pellentesque faucibus egestas justo sed malesuada. Etiam convallis tortor diam, scelerisque sodales nibh consequat quis. Nullam sodales nunc neque, eu placerat ex ultrices a. Nulla sed sapien eu orci egestas imperdiet fringilla ut massa.');

CREATE TABLE IF NOT EXISTS `tb_produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(200) NOT NULL,
  `preco` float(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tb_produtos` (`id`, `descricao`, `preco`) VALUES
	(1, 'Sofá', 1250.75),
	(2, 'Cadeira', 378.99),
	(3, 'Cama', 870.75),
	(4, 'Notebook', 1752.49),
	(5, 'Smartphone', 999.99);

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`) VALUES
	(1, 'ADMIN', 'admin', '$2y$10$lmbhaBEUY2Y6Ysyu2zz6iOc4/pzJJEWDax01N114CdD56MwM4JT/a'),
	(26, 'Carlos', 'carlos', '$2y$10$XYeG0PxErQBLORx2gF/E0Oxgc9qQZi1LP8m125I5MdXBoz1kXLK9G'),
	(28, 'Outro', 'outro', '$2y$10$nbMQaUo4fGYT278HQdci8.rnjeUKVWTVSHkPgKOvunvHy.vC.MYji');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
