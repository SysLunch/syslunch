-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.3.10-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando dados para a tabela syslunch_ok.financeiro: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `financeiro` DISABLE KEYS */;
/*!40000 ALTER TABLE `financeiro` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.grupo: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `grupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `grupo` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.itenspedido: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `itenspedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `itenspedido` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.local: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `local` DISABLE KEYS */;
/*!40000 ALTER TABLE `local` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.localpedido: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `localpedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `localpedido` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.log: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.meiopagamento: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `meiopagamento` DISABLE KEYS */;
INSERT INTO `meiopagamento` (`idMeio`, `meioPagamento`, `actionPagamento`, `dataCadastro`, `dataEdicao`) VALUES
	(1, 'Dinheiro', '', '2016-02-01 13:57:37', NULL),
	(2, 'MoIP', NULL, '2016-04-15 15:29:56', NULL);
/*!40000 ALTER TABLE `meiopagamento` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.movimentacaocreditos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `movimentacaocreditos` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimentacaocreditos` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.pedido: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.permissoesusuario: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `permissoesusuario` DISABLE KEYS */;
INSERT INTO `permissoesusuario` (`idPermissao`, `nomePermissao`, `nivelPermissao`, `restritoSuper`) VALUES
	(1, 'Usuario', 1, 0),
	(2, 'Vendas', 2, 0),
	(3, 'Administrador', 3, 0),
	(4, 'Super Administrador', 4, 0);
/*!40000 ALTER TABLE `permissoesusuario` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.pessoa: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.reservacodigos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `reservacodigos` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservacodigos` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.reservaletras: ~9 rows (aproximadamente)
/*!40000 ALTER TABLE `reservaletras` DISABLE KEYS */;
INSERT INTO `reservaletras` (`idLetra`, `letraReserva`, `idtipoReserva`, `tipoGrupo`, `textoTipo`, `texto`) VALUES
	(1, 'F', 3, 1, 'Funcionário', 'FUNCIONÁRIO - INCUBADAS'),
	(2, 'G', 4, 1, 'Funcionário', 'FUNCIONÁRIO GRATUITO'),
	(3, 'T', 1, 1, 'Ticket', 'TICKET'),
	(4, 'J', 2, 1, 'Ticket', 'TICKET GRATUITO'),
	(5, 'C', 5, 1, 'CRT', 'CRT'),
	(6, 'E', 6, 2, 'Participante', 'PARTICIPANTE PAGO'),
	(7, 'V', 7, 2, 'Participante', 'PARTICIPANTE GRATUITO'),
	(8, 'N', 8, 2, 'Ticket Evento', 'TICKET EVENTO - PAGO'),
	(9, 'O', 9, 2, 'Ticket Evento', 'TICKET EVENTO - GRATUITO');
/*!40000 ALTER TABLE `reservaletras` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.situacao: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `situacao` DISABLE KEYS */;
INSERT INTO `situacao` (`idSituacao`, `nomeSituacao`, `isFinal`, `tipoTag`, `classesIcon`) VALUES
	(1, 'Nova', 0, 'secondary ', 'fi-burst-new'),
	(2, 'Pedido Efetuado', 0, 'warning', NULL),
	(3, 'Pendente(Cheque)', 0, 'warning', NULL),
	(4, 'Pendente(Cartão)', 0, 'warning', NULL),
	(5, 'Pagamento Concluído', 0, '', NULL),
	(6, 'Finalizado', 1, 'success', 'fi-check'),
	(7, 'Cancelado pelo Sistema', 1, 'alert', 'fi-x'),
	(8, 'Cancelado pelo Usuário', 1, 'alert', 'fi-x');
/*!40000 ALTER TABLE `situacao` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.tickets: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.tipoproduto: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `tipoproduto` DISABLE KEYS */;
INSERT INTO `tipoproduto` (`idTipoProduto`, `descricaoProduto`, `valorUnitario`, `tipoTransacao`, `toEmpresa`, `isCartao`, `isFree`, `idLetra`) VALUES
	(1, 'Ticket', 15, 1, 0, 0, 0, 3),
	(2, 'Recarga', 10, 2, 1, 1, 0, NULL),
	(3, 'Ticket Gratuito', 0, 1, 0, 0, 1, 4),
	(4, 'Recarga Gratuita', 0, 2, 1, 1, 1, NULL),
	(5, 'Recarga Evento', 20, 1, 1, 1, 0, 6);
/*!40000 ALTER TABLE `tipoproduto` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.tiporeserva: ~9 rows (aproximadamente)
/*!40000 ALTER TABLE `tiporeserva` DISABLE KEYS */;
INSERT INTO `tiporeserva` (`idtipoReserva`, `descricaoTReserva`, `isGratuito`, `tipoUso`) VALUES
	(1, 'Ticket', 0, 2),
	(2, 'Ticket', 1, 2),
	(3, 'Cartão', 0, 1),
	(4, 'Cartão', 1, 1),
	(5, 'CRT', 0, 3),
	(6, 'Evento', 0, 1),
	(7, 'Evento', 1, 1),
	(8, 'Evento', 0, 2),
	(9, 'Evento', 1, 2);
/*!40000 ALTER TABLE `tiporeserva` ENABLE KEYS */;

-- Copiando dados para a tabela syslunch_ok.usuario: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
