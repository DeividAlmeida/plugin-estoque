<?php
header('Access-Control-Allow-Origin: *');
require_once('../../../includes/funcoes.php');
require_once('../../../database/config.database.php');
require_once('../../../database/config.php');
$ref = $_GET['ref'];
$estoque = DBRead('ecommerce_estoque','*',"WHERE estoque LIKE '%{$ref}%'")[0]['estoque'];
echo $estoque;