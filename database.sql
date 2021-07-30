CREATE TABLE IF NOT EXISTS `ecommerce_estoque` ( 
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`ref` int(11)  DEFAULT NULL,
`min` int(11)  DEFAULT NULL,
`estoque` int(11)  DEFAULT NULL,
`nome` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `ecommerce_plugins` (`id`, `titulo`, `nome`, `tipo`, `path`, `img`, `status`) VALUES (5, 'Estoque', 'estoque', 'estoque', 'ecommerce/plugins/estoque/estoque', '', 'checked');

