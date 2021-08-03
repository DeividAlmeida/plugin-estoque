<?php
error_reporting(0);
define('ROOT_PATH', dirname(__FILE__));


function atualizarMatrizesTodosProdutos(){
  $produtos   = DBRead('ecommerce', '*');

  foreach ($produtos as $produto) {
    atualizarMatrizProduto($produto['id']);
      $estoque = DBRead('ecommerce_estoque', '*', "WHERE ref = '{$produto['id']}' ");
      if(!is_array($estoque)){
           DBCreate('ecommerce_estoque',  ['ref'=>$produto['id'],'min'=>5]);
      }
  }
}


function atualizarMatrizProduto($id_produto){
  // Pega configuração
  $query = DBRead('ecommerce_config','*');

  $config = [];
  foreach ($query as $key => $row) {
    $config[$row['id']] = $row['valor'];
  }

  $query = DBRead('ecommerce', '*', "WHERE id = $id_produto");
  $produto = $query[0];

  // Pega arquivo da matriz criado pelo Web Acapella
  $matriz_base = file_get_contents($config['matriz_produto']);

  // Redefine matriz para a base da matriz que foi pega do arquivo
  $matriz = $matriz_base;

  // Buscando as categorias do produto
  $lista_ids_categorias = DBRead('ecommerce_prod_categorias', 'id_categoria', "WHERE id_produto = {$produto['id']}");

  // Varre todos os ID de categoria da lista, cria uma array, e transforma logo em seguida em uma string
  $id_categorias = array();
  foreach ($lista_ids_categorias as $linha) {
    array_push($id_categorias, $linha['id_categoria']);
  }
  $id_categorias   = implode(",", $id_categorias);
  $categorias      = DBRead('ecommerce_categorias', '*', "WHERE id IN ($id_categorias)");

    // Buscando as marcas do produto
  $lista_ids_marcas = DBRead('ecommerce_prod_marcas', 'id_marca', "WHERE id_produto = {$produto['id']}");

    // Varre todos os ID de marca da lista, cria uma array, e transforma logo em seguida em uma string
  $id_marcas = array();
  if (is_array($lista_ids_marcas)){ foreach ($lista_ids_marcas as $linha) {
    array_push($id_marcas, $linha['id_marca']);
  }
  $id_marcas   = implode(",", $id_marcas);
  $marcas      = DBRead('ecommerce_marcas', '*', "WHERE id IN ($id_marcas)");
}
    // Buscando as atributos do produto
  $lista_ids_atributos = DBRead('ecommerce_prod_termos', "DISTINCT id_atributo", "WHERE id_produto = {$produto['id']}");

    // Varre todos os ID de atributos da lista, cria uma array, e transforma logo em seguida em uma string
  $id_atributos = array();
  if(is_array($lista_ids_atributos)){foreach ($lista_ids_atributos as $linha) {
    array_push($id_atributos, $linha['id_atributo']);
  }

  $id_atributos   = implode(",", $id_atributos);
  $atributos     = DBRead('ecommerce_atributos', '*', "WHERE id IN ($id_atributos)");
}


  // URL do produto
  $nome_arquivo    =  $produto['url'].'-'.$produto['id'].".html";
  $url             = ConfigPainel('site_url').$nome_arquivo;

  // Carregando Fotos
  $fotos   = DBRead('ecommerce_prod_imagens','*', "WHERE id_produto = {$produto['id']}");

  // Busca pela foto de capa e salva em variavel
  foreach($fotos as $foto){
    if($foto['id'] == $produto['id_imagem_capa']){
      $foto_capa = $foto;
    }
  }

  // URL da imagem da capa
  $url_img_capa = RemoveHttpS(ConfigPainel('base_url'))."wa/ecommerce/uploads/".$foto_capa['uniq'];

  // TAGS - troca tags pelos seus conteudos
  require('tags/nome.php');                            // [WAC_ECOMMERCE_PROD_NOME]
  require('tags/descricao.php');                       // [WAC_ECOMMERCE_PROD_DESCRICAO]
  require('tags/cabecalho.php');                       // [WAC_ECOMMERCE_PROD_CABECALHO]
  require('tags/palavras_chave.php');                  // [WAC_ECOMMERCE_PROD_PALAVRAS_CHAVES]
  require('tags/resumo.php');                          // [WAC_ECOMMERCE_PROD_RESUMO]
  require('tags/url.php');                             // [WAC_ECOMMERCE_PROD_URL]
  require('tags/imagem_url.php');                      // [WAC_ECOMMERCE_PROD_IMAGEM_URL]
  require('tags/lista_prod_mais_vistos.php');          // [WAC_ECOMMERCE_LISTA_PROD_MAIS_VISTOS]
  require('tags/lista_prod_relacionados.php');         // [WAC_ECOMMERCE_LISTA_PROD_RELACIONADOS]
  // [WAC_ECOMMERCE_LISTA_PROD_MAIS_VENDIDOS]

  require('header.php');
  require('scripts.php');

  // Salvando HTML
  $caminhos_site_url = explode('/', ConfigPainel('site_url'));

  if($caminhos_site_url[3]){
    @unlink(ROOT_PATH."/../../../../".$caminhos_site_url[3].'/'.$nome_arquivo);

    $arquivo = fopen(ROOT_PATH."/../../../../".$caminhos_site_url[3].'/'.$nome_arquivo, "w");
    fwrite($arquivo, ''.$matriz.'');
    fclose($arquivo);
  } else {
    @unlink(ROOT_PATH."/../../../../".$nome_arquivo);

    $arquivo = fopen(ROOT_PATH."/../../../../".$nome_arquivo, "w");
    fwrite($arquivo, ''.$matriz.'');
    fclose($arquivo);
  }
}

function deletarMatrizProduto($id_produto){
  $query = DBRead('ecommerce', '*', "WHERE id = $id_produto");
  $produto = $query[0];

  $nome_arquivo    =  $produto['url'].'-'.$produto['id'].".html";

  // Salvando HTML
  $caminhos_site_url = explode('/', ConfigPainel('site_url'));

  if($caminhos_site_url[3]){
    @unlink(ROOT_PATH."/../../../../".$caminhos_site_url[3].'/'.$nome_arquivo);
  } else {
    @unlink(ROOT_PATH."/../../../../".$nome_arquivo);
  }
}
