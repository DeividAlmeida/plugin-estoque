<?php
header('Access-Control-Allow-Origin: *');
require_once('../../../includes/funcoes.php');
require_once('../../../database/config.database.php');
require_once('../../../database/config.php');
$ref = $_GET['ref'];
$estoque = DBRead('ecommerce_estoque','*',"WHERE ref = '{$ref}'")[0];
if(is_array($estoque)){
    echo json_encode(['id'=>$estoque['id'],'estoque'=>$estoque['estoque']]);
}else{
   echo json_encode(['id'=>$estoque['id'],'estoque'=>0]); 
}