CREATE TABLE IF NOT EXISTS `ecommerce_estoque` ( 
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`ref` int(11)  DEFAULT NULL,
`min` int(11)  DEFAULT NULL,
`estoque` int(11)  DEFAULT NULL,
`nome` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


