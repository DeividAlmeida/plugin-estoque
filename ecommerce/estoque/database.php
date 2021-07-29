<?php 
#error_reporting(0);
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
        if(!isset($_GET['search'])){
            $atributo = ler("SELECT MIN(id_atributo) AS id_atributo FROM ecommerce_prod_termos WHERE id_produto = {$valor['id']} GROUP BY id_atributo");
            if(is_array($atributo)){
                foreach($atributo as $Achave => $Avalor){
                    $tabela[$chave][$Achave]['atributo'] =  ler("SELECT * FROM ecommerce_atributos WHERE id ={$Avalor['id_atributo']}")[0]['nome'];  
                    $termo = ler("SELECT * FROM ecommerce_prod_termos WHERE id_produto = {$valor['id']} AND id_atributo = {$Avalor['id_atributo']}");
                    if(is_array($termo)){
                        foreach($termo as $Tchave => $Tvalor){
                          $tabela[$chave][$Achave][$Tchave]['nome'] =  ler("SELECT * FROM ecommerce_termos WHERE id = {$Tvalor['id_termo']}")[0]['nome'];
                          $tabela[$chave][$Achave][$Tchave]['id'] =  ler("SELECT * FROM ecommerce_termos WHERE id = {$Tvalor['id_termo']}")[0]['id'];
                        }
                    }
                }
            }
        }else{
            $tabela[$chave]['<div class="hidden">'] = ""; 
            $tabela[$chave]['id'] = $valor['id'];
            $tabela[$chave]['imagem'] = "<img src='wa/ecommerce/uploads/".ler("SELECT uniq FROM ecommerce_prod_imagens WHERE id = {$valor['id_imagem_capa']}")[0]['uniq']."' height='100'/>";
            $tabela[$chave]['nome'] = $valor['nome'];
            $tabela[$chave]['variacao'] = "<center><a onclick='lincar(".$chave.",".$valor['id'].",this.id)' id='".$valor['nome']."' style='cursor:pointer' data-target='#Modal' data-toggle='modal' ><i class='text-center text-primary icon icon-plus-circle fa-3x' aria-hidden='true'></i></a></center>";
        }
    }
}
echo json_encode($tabela);
