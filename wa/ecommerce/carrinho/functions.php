<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
error_reporting(0);

function CarrinhoAddQtd($id, $qtd, $vlf, $att, $ref, $refs){
  $_SESSION["car"][$att] = [$id, $qtd, $vlf, $ref, $refs];

  $query         = DBRead('ecommerce', "WHERE id = {$id}");
  $contagem_cart = $query[0]['count_add_cart'];

  DBUpdate('ecommerce', array('count_add_cart' => $contagem_cart + 1), "id = {$id}");
}

function CarrinhoRemItem($id){
  unset($_SESSION["car"][$id]);
}

function CarrinhoUpdate($id, $ptd, $qtd, $vlf, $ref, $refs){
  $_SESSION["car"][$id] = [$vlf, $ptd, $qtd, $ref, $refs];

}
