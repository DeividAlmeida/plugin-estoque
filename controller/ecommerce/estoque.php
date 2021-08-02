<?php
if(isset($_GET['addVariacao'])){
    $data= [];
    foreach($_POST as $key => $valor){
        $data[$key]=$valor;
    };
    $query = DBCreate('ecommerce_estoque', $data, true); 
    if ($query != 0) {
        Redireciona('?Estoque&sucesso');
    } else {
        Redireciona('?Estoque&erro');
  }
}
if(isset($_GET['DeletarVariacao'])){
    $id     = get('DeletarVariacao');
    $query  = DBDelete('ecommerce_estoque',"id = '{$id}'");
    if ($query != 0) {
        Redireciona('?Estoque&sucesso');
    } else {
        Redireciona('?Estoque&erro');
  }
}
if(isset($_GET['AtualizarVariacao'])){
    $id = $_GET['AtualizarVariacao'];
    $query = DBUpdate('ecommerce_estoque', ['estoque'=>$_GET['valor']], "id = '{$id}'");
}
