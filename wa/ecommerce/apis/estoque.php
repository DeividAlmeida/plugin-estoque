<?php
header('Access-Control-Allow-Origin: *');
require_once('../../../includes/funcoes.php');
require_once('../../../database/config.database.php');
require_once('../../../database/config.php');
$valida = DBRead('ecommerce_estoque','*',"WHERE estoque = '{$email}' AND  senha = '{$senha}' ", "LIMIT 1")[0];