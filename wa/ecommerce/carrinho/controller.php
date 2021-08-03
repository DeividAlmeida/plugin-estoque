<?php
require_once('functions.php');

if(isset($_GET['UpdateQtd'])){
	$id = get('UpdateQtd');
	$qtd = get('qtd');
	$vlf = get('vlf');
	$pdt = get('pdt');
	$ref = get('ref');
    $refs =  get('refs');
  CarrinhoUpdate($id, $qtd, $vlf, $pdt, $ref, $refs);
  exit();
}

if(isset($_GET['AddItem'])){
  $id = get('AddItem');
  $qtd = get('qtd');
  $vlf = get('vlf');
  $att = get('att');
  $ref = get('ref');
  $refs =  get('refs');
  CarrinhoAddQtd($id, $qtd, $vlf, $att, $ref, $refs);
  exit();
}

if(isset($_GET['RemItem'])){
  $id = get('RemItem');
  CarrinhoRemItem($id, 1);
  exit();
}


