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

INSERT INTO `acoes_sistema` (`id`, `controlador`, `metodo`, `descricao`) VALUES
	(1, 'CaixasController', 'abrirCaixa', 'Fazer a abertura de um novo caixa.'),
	(2, 'CaixasController', 'listarCaixas', 'Pegar todos os caixas abertos no escritório do usu');

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `caixas` (`id`, `nome`, `observacoes`, `id_escritorio`, `id_usuario_abertura`, `id_usuario_fechamento`, `data_abertura`, `data_fechamento`) VALUES
	(25, 'Caixa principal', '', 2, 34, NULL, '2024-01-18 15:27:46', NULL),
	(26, 'Juju ', '', 2, 34, NULL, '2024-01-21 19:51:08', NULL);

CREATE TABLE IF NOT EXISTS `controladores` (
  `nome` varchar(50) NOT NULL,
  `exibicao` varchar(50) DEFAULT NULL,
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `controladores` (`nome`, `exibicao`) VALUES
	('CaixasController', 'Caixas');

CREATE TABLE IF NOT EXISTS `escritorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `escritorios` (`id`, `nome`, `cnpj`, `observacoes`) VALUES
	(1, '[ADMIN OFFICE]', '11111111111111', 'Escritório do admin master blaster'),
	(2, 'CFC Jaguar', '09524929000194', 'Autoescola Jaguar de Uruaçu'),
	(5, 'Jaguariúna', '22222222222222', '');

CREATE TABLE IF NOT EXISTS `formas_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `observacoes` varchar(255) NOT NULL,
  `ativa` tinyint(1) NOT NULL DEFAULT 1,
  `id_escritorio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_forma_pagamento_escritorios` (`id_escritorio`),
  CONSTRAINT `FK_forma_pagamento_escritorios` FOREIGN KEY (`id_escritorio`) REFERENCES `escritorios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `formas_pagamento` (`id`, `nome`, `observacoes`, `ativa`, `id_escritorio`) VALUES
	(1, 'Dinheiro', 'Dinheiro em cédulas, que contam no valor a transportar do caixa (dinheiro físico para o fechamento)', 1, NULL),
	(2, 'PIX', 'PIX', 1, NULL),
	(3, 'Crédito', 'Cartão de crédito', 1, NULL),
	(4, 'Débito', 'Cartão de débito', 1, NULL);

CREATE TABLE IF NOT EXISTS `operacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `valor` float NOT NULL,
  `id_caixa` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data` date NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo_entrada` tinyint(1) NOT NULL,
  `id_forma_pagamento` int(11) NOT NULL,
  `excluido` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK__caixas` (`id_caixa`),
  KEY `FK__usuarios` (`id_usuario`),
  KEY `FK_operacao_forma_pagamento` (`id_forma_pagamento`),
  CONSTRAINT `FK__caixas` FOREIGN KEY (`id_caixa`) REFERENCES `caixas` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `FK__usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `FK_operacao_forma_pagamento` FOREIGN KEY (`id_forma_pagamento`) REFERENCES `formas_pagamento` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `operacoes` (`id`, `nome`, `observacoes`, `valor`, `id_caixa`, `id_usuario`, `data`, `data_criacao`, `tipo_entrada`, `id_forma_pagamento`, `excluido`) VALUES
	(7, 'Abastecimento moto', '', 30, 25, 34, '2024-01-19', '2024-01-21 18:02:07', 0, 1, '0'),
	(8, 'Curso teórico', 'Zé da manga', 502, 25, 34, '2024-01-21', '2024-01-21 18:03:11', 1, 1, '0'),
	(9, 'Tutu', 'Jujurrr', 100000, 26, 34, '2024-01-19', '2024-01-21 19:52:06', 1, 1, '0'),
	(10, 'asdf', '', 12, 26, 34, '2024-01-21', '2024-01-21 19:52:17', 0, 1, '0'),
	(11, 'Teste', '', 123, 25, 34, '2024-01-19', '2024-01-21 23:49:50', 1, 1, '1'),
	(12, 'Apenas um teste levemente mais grande (kkkk maior ', 'Bem, acho que posso escrever algo mais aqui né? Vamos tentar então. Sou Jake Peralta e sou um detetive da polícia de Nova York. Você precisa que eu resolva um caso? Então é só me chamar, estou no canal Netflix hehe :D', 10, 25, 34, '2024-01-22', '2024-01-22 00:17:41', 1, 1, '0'),
	(13, 'opa kk', '', 5, 25, 34, '2024-01-22', '2024-01-22 00:53:02', 1, 1, '1'),
	(14, 'Teste apenas', '', 12, 25, 34, '2024-01-22', '2024-01-22 00:59:34', 0, 1, '0'),
	(15, 'asd', '', 1, 25, 34, '2024-01-22', '2024-01-22 01:00:01', 0, 1, '1');

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

INSERT INTO `permissoes` (`id`, `id_usuario`, `acao`) VALUES
	(2, 34, 'listarCaixas');

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

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`, `id_escritorio`) VALUES
	(1, 'ADMIN', 'admin', '$2y$10$XOzZr6CEkUicXO1koRCu2eHp7Xnw3YhzN3RSsNlgETaFUrUXN9GsO', 1),
	(33, 'Thiago Serra', 'thiagoserra', '$2y$10$Nw6sFyVb/G4/eAYwlYFNReHIbROCVStLFItn5t.y5pL0qx.8xJmXC', 2),
	(34, 'Arthur Rogado Reis', 'arthurrogado', '$2y$10$QAwHNHSDaSC5CUec5VagFOY309NHnF6slO8bBXHuLfqWAoRW/oTeG', 2);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
