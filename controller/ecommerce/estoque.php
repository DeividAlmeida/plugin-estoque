<?php
error_reporting(0);
if( file_exists('mercadolivre.php')){
    $MLtoken = DBRead('ecommerce_mercadolivre', '*')[0];
}
if(isset($_GET['addVariacao'])){
    
    $data= [
        'estoque'=>$_POST['estoque'],
        'min'=>$_POST['min'],
        'nome'=>$_POST['nome'],
        'ref'=>$_POST['ref']
        ];
           
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
           
    if ($query != 0) {
       Redireciona('?Estoque&sucesso');
    } else {
        Redireciona('?Estoque&erro');
  }
}

if(isset($_GET['Limiar'])){
    $id = $_GET['Limiar'];
    $query = DBUpdate('ecommerce_estoque', ['min'=>$_GET['valor']], "id = '{$id}'");
}

if(isset($_GET['Estorno'])){
$id = $_GET['Estorno'];
$valor = json_decode(DBRead('ecommerce_vendas', '*', "WHERE id = $id ")[0]['estorno']);
    if(is_object($valor)){
        foreach($valor as $key => $value){
           $real =  DBRead('ecommerce_estoque', '*', "WHERE id = $key ")[0]['estoque'] + $value;
           DBUpdate('ecommerce_estoque', ['estoque'=>$real], "id = '{$key}'");
        }  
        DBUpdate('ecommerce_vendas', ['estorno'=> null], "id = '{$id }'");
    }
}