/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `fluxocaixa` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;
USE `fluxocaixa`;

CREATE TABLE IF NOT EXISTS `acoes_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controlador` varchar(50) NOT NULL,
  `metodo` varchar(255) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `metodo` (`metodo`),
  KEY `FK_acoes_sistema_controladores` (`controlador`),
  CONSTRAINT `FK_acoes_sistema_controladores` FOREIGN KEY (`controlador`) REFERENCES `controladores` (`nome`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `caixas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `id_escritorio` int(11) NOT NULL,
  `id_usuario_abertura` int(11) NOT NULL,
  `id_usuario_fechamento` int(11) DEFAULT NULL,
  `data_abertura` datetime NOT NULL DEFAULT current_timestamp(),
  `data_fechamento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_caixas_escritorios` (`id_escritorio`),
  KEY `FK_caixas_usuarios` (`id_usuario_abertura`),
  KEY `FK_caixas_usuarios_2` (`id_usuario_fechamento`),
  CONSTRAINT `FK_caixas_escritorios` FOREIGN KEY (`id_escritorio`) REFERENCES `escritorios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_caixas_usuarios` FOREIGN KEY (`id_usuario_abertura`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_caixas_usuarios_2` FOREIGN KEY (`id_usuario_fechamento`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `controladores` (
  `nome` varchar(50) NOT NULL,
  `exibicao` varchar(50) DEFAULT NULL,
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `escritorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `permissoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `acao` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_permissoes_usuarios` (`id_usuario`),
  KEY `FK_permissoes_acoes_sistema` (`acao`),
  CONSTRAINT `FK_permissoes_acoes_sistema` FOREIGN KEY (`acao`) REFERENCES `acoes_sistema` (`metodo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_permissoes_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `id_escritorio` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `FK_usuarios_escritorios_2` (`id_escritorio`),
  CONSTRAINT `FK_usuarios_escritorios_2` FOREIGN KEY (`id_escritorio`) REFERENCES `escritorios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
