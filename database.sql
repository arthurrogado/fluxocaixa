/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `caixas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `observacoes` varchar(512) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `caixas` (`id`, `nome`, `observacoes`, `id_escritorio`, `id_usuario_abertura`, `id_usuario_fechamento`, `data_abertura`, `data_fechamento`) VALUES
	(30, 'Materiais', '', 2, 34, NULL, '2024-01-23 00:25:49', NULL),
	(32, 'Teste primeiro caixa', 'teste', 3, 33, NULL, '2024-02-05 16:54:24', NULL);

CREATE TABLE IF NOT EXISTS `carteiras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `observacoes` varchar(255) NOT NULL,
  `ativa` enum('1','0') NOT NULL DEFAULT '1',
  `id_escritorio` int(11) DEFAULT NULL,
  `entrada` enum('1','0') NOT NULL DEFAULT '1' COMMENT 'Se podem depositar nessa carteira',
  `saida` enum('1','0') NOT NULL DEFAULT '1' COMMENT 'Se podem sacar dessa carteira',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_formas_recebimento_escritorios` (`id_escritorio`) USING BTREE,
  CONSTRAINT `FK_formas_recebimento_escritorios` FOREIGN KEY (`id_escritorio`) REFERENCES `escritorios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Basicamente a forma de recebimento ou de pagamento. \r\nSe o valor entrada estiver 1 (true), pode adicionar entradas nessa carteira.\r\nSe o valor saída estiver 1 (true), pode retirar dessa carteira.\r\n\r\nExemplos:\r\n- No escritório tem o caixa normal, de dinheiro físico (cédulas), então é dito que essa carteira permite saída e entrada, já que os pagamentos dos clientes em dinheiro vai para a mesma, e quando o escritório necessita de comprar algo pode usar essa carteira;\r\n- Porém o escritório dispõe de uma maquininha de cartão de crédito/débito, que pode apenas receber. Logo essa carteira tem como true apenas o parâmetro entrada;\r\n- E o mesmo escritório dispõe de um cartão de crédito, que neste caso só pode ter saídas, logo nessa carteira o atributo entrada é false (0).';

INSERT INTO `carteiras` (`id`, `nome`, `observacoes`, `ativa`, `id_escritorio`, `entrada`, `saida`) VALUES
	(1, 'DINHEIRO', 'Cédulas ou moedas', '1', NULL, '1', '1'),
	(2, 'PIX', 'Recebimento PIX', '1', NULL, '1', '0'),
	(3, 'Cartão crédito', 'Cartão de crédito', '1', NULL, '1', '0'),
	(4, 'Cartão débito', 'Cartão de débito', '1', NULL, '1', '0'),
	(5, 'placeholder1', '', '0', NULL, '1', '0'),
	(6, 'placeholder2', '', '0', NULL, '1', '0'),
	(7, 'placeholder3', '', '0', NULL, '1', '0'),
	(8, 'placeholder4', '', '0', NULL, '1', '0'),
	(9, 'placeholder5', '', '0', NULL, '1', '0'),
	(10, 'placeholder6', '', '0', NULL, '1', '0'),
	(30, 'Boleto Bradesco', 'Gerado pelo Net Empresa', '1', 2, '1', '0');

CREATE TABLE IF NOT EXISTS `controladores` (
  `nome` varchar(50) NOT NULL,
  `exibicao` varchar(50) DEFAULT NULL,
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `controladores` (`nome`, `exibicao`) VALUES
	('CaixasController', 'Caixas'),
	('CarteirasController', 'Carteiras'),
	('EscritoriosController', NULL),
	('OperacoesController', NULL),
	('PermissaoController', NULL),
	('UsuariosController', NULL);

CREATE TABLE IF NOT EXISTS `escritorios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `escritorios` (`id`, `nome`, `cnpj`, `observacoes`, `senha`) VALUES
	(1, '[ADMIN OFFICE]', '11111111111111', 'Escritório do admin master blaster', ''),
	(2, 'CFC Jaguar', '09524929000194', 'Autoescola Jaguar de Uruaçu. A melhor!', '$2y$10$Mkwbpy93Udy6BONH5YlvhOzlKyUkrdS3XnzDsbVg85mUBV1i5ovBC'),
	(3, 'CFC Jaguariúna 2', '45839641000172', 'A melhor autoescola do sul de Goiás!', '$2y$10$DQJN5F7WXwTi3OqT8jBh.eru1Pvg/vWb7R1GsQr8IJa7qK8E1BsqG');

CREATE TABLE IF NOT EXISTS `metodos_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(50) DEFAULT NULL,
  `metodo` varchar(255) NOT NULL,
  `controlador` varchar(50) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `metodo` (`metodo`),
  KEY `FK_acoes_sistema_controladores` (`controlador`),
  CONSTRAINT `FK_acoes_sistema_controladores` FOREIGN KEY (`controlador`) REFERENCES `controladores` (`nome`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `metodos_sistema` (`id`, `titulo`, `metodo`, `controlador`, `descricao`) VALUES
	(1, NULL, 'abrirCaixa', 'CaixasController', 'Fazer a abertura de um novo caixa.'),
	(2, NULL, 'listarCaixas', 'CaixasController', 'Pegar todos os caixas abertos no escritório do usuário'),
	(3, NULL, 'editarCaixa', 'CaixasController', NULL),
	(4, NULL, 'visualizarCaixa', 'CaixasController', NULL),
	(5, NULL, 'excluirCaixa', 'CaixasController', NULL),
	(11, 'Ver as operações do caixa', 'getOperacoesCaixa', 'OperacoesController', 'O usuário vai poder visualizar as operações dentro do caixa, assim como os totais.'),
	(12, NULL, 'criarOperacao', 'OperacoesController', NULL),
	(13, NULL, 'editarOperacao', 'OperacoesController', NULL),
	(14, NULL, 'excluirOperacao', 'OperacoesController', NULL);

CREATE TABLE IF NOT EXISTS `operacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `observacoes` varchar(255) DEFAULT NULL,
  `valor` float NOT NULL,
  `id_caixa` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `data` date NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo` enum('e','s') DEFAULT NULL,
  `id_carteira` int(11) NOT NULL,
  `excluido` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK__usuarios` (`id_usuario`),
  KEY `FK_operacao_forma_pagamento` (`id_carteira`) USING BTREE,
  KEY `FK_operacoes_caixas` (`id_caixa`),
  CONSTRAINT `FK__usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `FK_operacoes_caixas` FOREIGN KEY (`id_caixa`) REFERENCES `caixas` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `FK_operacoes_carteira` FOREIGN KEY (`id_carteira`) REFERENCES `carteiras` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `operacoes` (`id`, `nome`, `observacoes`, `valor`, `id_caixa`, `id_usuario`, `data`, `data_criacao`, `tipo`, `id_carteira`, `excluido`) VALUES
	(1, 'Curso teórico', '', 502, NULL, 34, '2024-01-19', '2024-01-22 22:35:51', 'e', 2, '0'),
	(2, 'Abastecimento moto', 'Lá no posto tal', 30, NULL, 34, '2024-01-22', '2024-01-22 22:51:20', 's', 1, '0'),
	(4, '', '', 0, NULL, 34, '2024-01-23', '2024-01-22 23:00:17', 'e', 3, '1'),
	(5, 'teste', '', 12, NULL, 34, '2024-01-23', '2024-01-22 23:14:17', 'e', 3, '1'),
	(6, 'KIT Completo', 'José dos Anzóis', 50, 30, 34, '2024-01-23', '2024-01-23 00:26:38', 'e', 1, '0'),
	(7, 'Abastecimento moto', '', 30, 30, 34, '2024-01-23', '2024-01-23 00:27:14', 's', 1, '0'),
	(8, 'Livrinho', 'Da dona Maria', 20, 30, 34, '2024-01-22', '2024-01-23 00:27:55', 'e', 2, '0'),
	(9, 'Teste', '', 100, NULL, 34, '2024-01-23', '2024-01-23 00:41:09', 'e', 1, '1'),
	(10, 'teste', '', 80, NULL, 34, '2024-01-23', '2024-01-23 01:33:44', 'e', 1, '1'),
	(11, '', '', 0, NULL, 34, '2024-01-23', '2024-01-23 02:03:13', 's', 4, '1'),
	(12, 'teste', '', 123, NULL, 34, '2024-01-23', '2024-01-23 02:03:40', 's', 1, '1'),
	(13, '', '', 0, NULL, 34, '2024-01-23', '2024-01-23 02:06:28', 's', 2, '1'),
	(14, '', '', 0, NULL, 34, '2024-01-23', '2024-01-23 02:06:37', 'e', 4, '1'),
	(15, '', '', 0, NULL, 34, '2024-01-23', '2024-01-23 11:49:11', 'e', 30, '1'),
	(16, 'teste', '', 123, NULL, 34, '2024-01-29', '2024-01-29 17:49:46', 'e', 1, '1'),
	(17, 'teste', '123123123123', 10, NULL, 33, '2024-02-02', '2024-02-05 16:11:27', 'e', 1, '1'),
	(18, 'teste', '', 12, NULL, 33, '2024-02-05', '2024-02-05 16:26:24', 'e', 1, '1'),
	(19, 'arthur', '', 12, NULL, 34, '2024-02-05', '2024-02-05 16:26:59', 'e', 1, '1'),
	(20, 'teste', '', 2, NULL, 34, '2024-02-05', '2024-02-05 16:54:08', 'e', 1, '0'),
	(21, 'jaguariuna2', '0', 1, 32, 34, '2024-02-05', '2024-02-05 17:20:27', 'e', 2, '0'),
	(22, 'teste', '', 12, 32, 34, '2024-02-11', '2024-02-11 00:19:34', 's', 1, '0'),
	(23, '', '', 0, 32, 34, '2024-02-11', '2024-02-11 00:22:41', 'e', 1, '1'),
	(24, '', '', 0, 32, 34, '2024-02-11', '2024-02-11 00:22:51', 'e', 2, '1'),
	(25, '', '', 0, 32, 34, '2024-02-11', '2024-02-11 00:24:14', 's', 1, '1'),
	(26, 'teste', '', 1, 32, 34, '2024-02-11', '2024-02-11 19:08:02', 'e', 1, '0'),
	(27, '212asdfas', 'asdf ', 1231, 32, 34, '2024-02-12', '2024-02-12 00:09:58', 'e', 1, '1'),
	(28, 'Grande', '', 12, 32, 34, '2024-02-12', '2024-02-12 00:15:18', 's', 1, '0'),
	(29, 'teste cc', '', 11, 32, 34, '2024-02-12', '2024-02-12 00:22:55', 'e', 3, '0'),
	(30, 'parcela 1/5', 'ele vei om', 517, 30, 33, '2024-02-13', '2024-02-14 20:37:24', 'e', 1, '0');

CREATE TABLE IF NOT EXISTS `permissoes` (
  `id_usuario` int(11) NOT NULL,
  `id_metodo` int(11) NOT NULL,
  KEY `FK_permissoes_usuarios` (`id_usuario`),
  KEY `FK_permissoes_acoes_sistema` (`id_metodo`),
  CONSTRAINT `FK_permissoes_acoes_sistema` FOREIGN KEY (`id_metodo`) REFERENCES `metodos_sistema` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_permissoes_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `permissoes` (`id_usuario`, `id_metodo`) VALUES
	(34, 1),
	(34, 2),
	(34, 3),
	(34, 4),
	(34, 11),
	(33, 2),
	(33, 4),
	(33, 11);

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `id_escritorio` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `FK_usuarios_escritorios_2` (`id_escritorio`),
  CONSTRAINT `FK_usuarios_escritorios_2` FOREIGN KEY (`id_escritorio`) REFERENCES `escritorios` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`, `id_escritorio`) VALUES
	(1, 'ADMIN', 'admin', '$2y$10$XOzZr6CEkUicXO1koRCu2eHp7Xnw3YhzN3RSsNlgETaFUrUXN9GsO', 1),
	(33, 'Thiago Serra', 'thiagoserra', '$2y$10$zk16kGbVIkVmIPaJOo1P6u18lu9eRQAiksdkAFj.L39ZOUjnA03P6', 2),
	(34, 'Arthur Rogado Reis', 'arthurrogado', '$2y$10$QAwHNHSDaSC5CUec5VagFOY309NHnF6slO8bBXHuLfqWAoRW/oTeG', 3),
	(35, 'Carlos', 'carlos', '$2y$10$R.sbYhoLszlTrCG4TFGfMOZVXYaC/j/R6YIXk6V14kxKctRi3vzVO', 3);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
