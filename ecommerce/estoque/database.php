<?php 
error_reporting(0);
require_once('../../database/config.php');
function ler($i){
      $mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
      $mysqli -> set_charset(DB_CHARSET);
      if ($mysqli -> connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
          exit();
        }
      $result = $mysqli -> query($i);
      $data = $result -> fetch_all(MYSQLI_ASSOC); 
      $result -> free_result();
      $mysqli -> close();
        return $data;
  }

$tabela = [];
$data = ler("SELECT * FROM ecommerce ORDER BY id DESC");
if(is_array($data)){
    foreach($data as $chave => $valor){        
       
        $tabela[$chave]['<div class="hidden">'] = ""; 
        $tabela[$chave]['id'] = $valor['id'];
        $tabela[$chave]['imagem'] = "<img src='wa/ecommerce/uploads/".ler("SELECT uniq FROM ecommerce_prod_imagens WHERE id = {$valor['id_imagem_capa']}")[0]['uniq']."' height='100'/>";
        $tabela[$chave]['nome'] = $valor['nome'];
    }
}
echo json_encode($tabela);
