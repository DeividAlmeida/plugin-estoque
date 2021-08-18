CREATE TABLE IF NOT EXISTS `ecommerce_estoque` ( 
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`ref` text DEFAULT NULL,
`min` int(11)  DEFAULT NULL,
`estoque` int(11)  DEFAULT NULL,
`nome` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `ecommerce_plugins` (`id`, `titulo`, `nome`, `tipo`, `path`, `img`, `status`) VALUES (5, 'Estoque', 'estoque', 'estoque', 'ecommerce/plugins/estoque/estoque', '', 'checked');
UPDATE modulos SET acao = "{\"pedidos\":[\"notificar\",\"editar\",\"deletar\"],\"listagem\":[\"adicionar\",\"editar\",\"deletar\"],\"categoria\":[\"adicionar\",\"editar\",\"deletar\"],\"marca\":[\"adicionar\",\"editar\",\"deletar\"],\"atributo\":[\"adicionar\",\"editar\",\"deletar\"],\"termo\":[\"adicionar\",\"editar\",\"deletar\"],\"produto\":[\"adicionar\",\"editar\",\"deletar\"],\"codigo\":[\"acessar\"],\"estoque\":[\"adicionar\",\"editar\",\"deletar\"],\"configuracao\":[\"acessar\"]}" WHERE nome = "Ecommerce";
ALTER TABLE `ecommerce_vendas` ADD `estorno` TEXT NULL AFTER `view`;