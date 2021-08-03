<?php
    error_reporting(0);
  if(!checkPermission($PERMISSION, $_SERVER['SCRIPT_NAME'], 'produto', 'editar')){ Redireciona('./index.php'); }
?>
<?php
function Checked($query, $value = null){
  if ($query == $value) { echo "checked"; }
}

$id       = get('EditarProduto');

// Busca de categorias e produtos
$categorias = DBRead('ecommerce_categorias','*');
$produtos   = DBRead('ecommerce','*');
$marcas = DBRead('ecommerce_marcas','*');
$atributos = DBRead('ecommerce_atributos','*');
$query   = DBRead('ecommerce','*',"WHERE id = '{$id}'");
$dados   = $query[0];

$fotos   = DBRead('ecommerce_prod_imagens','*', "WHERE id_produto = {$id}");

// Busca pela foto de capa e salva em variavel
if(is_array($fotos)){
  foreach($fotos as $foto){
    if($foto['id'] == $dados['id_imagem_capa']){
      $foto_capa = $foto;
    }
  }
}

// Buscando as categorias do produto
$lista_ids_categorias = DBRead('ecommerce_prod_categorias', 'id_categoria', "WHERE id_produto = {$id}");

// Varre todos os ID de categoria da lista, cria uma array, e transforma logo em seguida em uma string
$ids_categorias = array();
foreach ($lista_ids_categorias as $linha) {
  array_push($ids_categorias, $linha['id_categoria']);
}
$string_ids_categorias = implode(",", $ids_categorias);



// Buscando as categorias do produto
$lista_ids_marcas = DBRead('ecommerce_prod_marcas', 'id_marca', "WHERE id_produto = {$id}");

// Varre todos os ID de categoria da lista, cria uma array, e transforma logo em seguida em uma string
$ids_marcas = array();
if(is_array($lista_ids_marcas)){
foreach ($lista_ids_marcas as $linha) {
  array_push($ids_marcas, $linha['id_marca']);
}}
$string_ids_marcas = implode(",", $ids_marcas);




// Buscando as categorias do produto
$lista_ids_prod_relacionado = DBRead('ecommerce_prod_relacionados', 'id_produto_relacionado', "WHERE id_produto = {$id}");

// Varre todos os ID de prod_relacionado d a lista, cria uma array, e transforma logo em seguida em uma string
$ids_prod_relacionado = array();
if(is_array($lista_ids_prod_relacionado)){
  foreach ($lista_ids_prod_relacionado as $linha) {
    array_push($ids_prod_relacionado, $linha['id_produto_relacionado']);
  }
}
$string_ids_prod_relacionado  = implode(",", $ids_prod_relacionado);

if (is_array($query)) { ?>
  <form method="post" action="?AtualizarProduto=<?php echo $id; ?>" enctype="multipart/form-data">
    <div class="card">
      <div class="card-header  white">
        <strong>Editar Produto</strong>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <!-- `nome` varchar(255) NOT NULL -->
            <div class="form-group">
              <label>Nome: </label>
              <input class="form-control produto-nome" name="nome" required value="<?php echo $dados['nome'];?>">
            </div>

            <!-- `descricao` text DEFAULT NULL -->
            <div class="form-group">
              <label>Descrição: </label>
              <textarea class="form-control tinymce" name="descricao"><?php echo $dados['descricao'];?></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <!-- `resumo` text DEFAULT NULL -->
            <div class="form-group">
              <label>Resumo: </label>
              <textarea class="form-control" name="resumo"><?php echo $dados['resumo'];?></textarea>
            </div>

            <!-- `codigo` varchar(255) NOT NULL -->
            <div class="form-group">
              <label>Código do Produto: </label>
              <input class="form-control" name="codigo" required value="<?php echo $dados['codigo'];?>">
            </div>

            <!-- `url` varchar(255) NOT NULL -->
            <div class="form-group">
              <label>URL amigável: </label>
              <input class="form-control produto-url" name="url" required value="<?php echo $dados['url'];?>">
            </div>

            <!-- `categorias` -->
            <div class="form-group">
              <label>Categorias: </label>
              <select class="form-control produto-categorias" name="categorias[]" multiple="multiple" required>
                <?php foreach($categorias as $categoria){ ?>
                  <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
                <?php } ?>
              </select>
            </div>

            <!-- `marcas` -->
          <div class="form-group">
            <label>Marca: </label>
            <select class="form-control produto-atributos" name="marcas[]" multiple="multiple" >
              <?php foreach($marcas as $marcas){ ?>
                <option value="<?php echo $marcas['id']; ?>"><?php echo $marcas['nome']; ?></option>
              <?php } ?>
            </select>
          </div>

          <!-- `atributos` -->
          <div class="form-group">            
            <a id="produto-add-atb" class="btn btn-primary" data-target="#Modal" data-toggle="modal" onclick="showDetails(this)">Adcionar atributo</a>             
          </div>

          <div class="form-group">
              <label>Produtos Relacionados: </label>
              <select class="form-control produto-prod_relacionados" name="produtos_relacionados[]" multiple="multiple">
                <?php foreach($produtos as $produtos){ ?>
                  <option value="<?php echo $produtos['id']; ?>"><?php echo $produtos['nome']; ?></option>
                <?php } ?>
              </select>
            </div>

            <!-- `preco` decimal(10,2) NOT NULL -->
            <div class="form-group">
              <label>Preço: </label>
              <input class="form-control" name="preco" required step="0.01" type="number" min="0" value="<?php echo $dados['preco'];?>">
            </div>

            <!-- `estoque` int(11) DEFAULT NULL-->
          <div class="form-group hidden">
            <label>Estoque:</label>            
              <input class="form-control" name="estoque" type="number">            
          </div>

          <!-- `estoque` int(11) DEFAULT NULL-->
          <div class="form-group">
            <label>Diminuir estoque:</label>            
              <select name="diminuir_est" required class="form-control custom-select">
              <option value="sim" <?php Selected($dados['diminuir_est'], "sim"); ?>>Sim</option>
                <option value="não" <?php Selected($dados['diminuir_est'], "não"); ?>>Não</option>
            </select>            
          </div>            
          </div>
        </div>
        <div class="row">        
        <div class="col-md-3" >
                <!-- `peso` varchar(255) DEFAULT NULL-->
            <div class="form-group">
                <label>Peso: <i class="icon icon-question-circle tooltips" data-tooltip="Encomenda com embalagem." ></i></label>
                <input class="form-control" name="peso" placeholder="Unidade de media kg" required value="<?php echo $dados['peso'];?>">          
            </div>
        </div>
        <div class="col-md-3">
            <!-- `comprimento` varchar(255) DEFAULT NULL-->
            <div class="form-group">
                <label>Comprimento: <i class="icon icon-question-circle tooltips" data-tooltip="Encomenda com embalagem." ></i></label>
                <input class="form-control" name="comprimento" placeholder="Unidade de media cm" required value="<?php echo $dados['comprimento'];?>">          
            </div>
        </div>
        <div class="col-md-3">
           <!-- `altura` varchar(255) DEFAULT NULL-->
           <div class="form-group">
                <label>Altura: <i class="icon icon-question-circle tooltips" data-tooltip="Encomenda com embalagem." ></i></label>
                <input class="form-control" name="altura" placeholder="Unidade de media cm" required value="<?php echo $dados['altura'];?>">          
            </div>
        </div>
        <div class="col-md-3">
            <!-- `largura` varchar(255) DEFAULT NULL-->
           <div class="form-group">
                <label>Largura: <i class="icon icon-question-circle tooltips" data-tooltip="Encomenda com embalagem." ></i></label>
                <input class="form-control" name="largura" placeholder="Unidade de media cm" required value="<?php echo $dados['largura'];?>">          
            </div> 
        </div>
    </div>    
        <div class="row">
          <div class="col-md-12">
            <!-- `palavras_chave` text NOT NULL -->
            <div class="form-group">
              <label>Palavras Chave: </label>
              <textarea class="form-control" name="palavras_chave"><?php echo $dados['palavras_chave'];?></textarea>
            </div>

            <!-- `etiqueta` varchar(255) DEFAULT NULL -->
            <div class="form-group">
              <label>Etiqueta: </label>
              <input class="form-control" name="etiqueta" value="<?php echo $dados['etiqueta'];?>">
            </div>

            <!-- `etiqueta_cor` varchar(255) DEFAULT NULL -->
            <div class="form-group">
              <label for="name">Cor da Etiqueta: </label>
              <div class="color-picker input-group colorpicker-element focused">
                <input type="text" class="form-control" name="etiqueta_cor" value="<?php echo $dados['etiqueta_cor'];?>">
                <span class="input-group-append">
                  <span class="input-group-text add-on white">
                    <i class="circle"></i>
                  </span>
                </span>
              </div>
            </div>
            
            <!-- `btn_texto` varchar(255) DEFAULT NULL -->
          <div class="form-group">            
            <input class="form-control" name="btn_texto" type="hidden" value="Comprar">
          </div>

            <!-- `ordem_manual` int(11) -->
            <div class="form-group">
              <label>Ordem Manual: </label>
              <input class="form-control" name="ordem_manual" type="number" value="<?php echo $dados['ordem_manual'];?>">
            </div>
          </div>
          <hr/>
          <div class="col-md-12">
            <h3>Fotos adicionadas</h3>

            <table id="fotos-adicionadas-wrapper" class="table mt-3 table-striped">
              <thead>
                <tr>
                  <th>Foto</th>
                  <th>Capa</th>
                  <th width="53px">Ações</th>
                </tr>
              </thead>

              <tbody>
                <?php if(is_array($fotos)){ foreach($fotos as $foto){ ?>
                  <tr id='foto-<?php echo $foto['id']; ?>'>
          					<td><img src="<?php echo RemoveHttpS(ConfigPainel('base_url'))."wa/ecommerce/uploads/".$foto['uniq']; ?>" height="100"/></td>
          					<td><input class='form-check-input' name='capa' type='radio' value='old-<?php echo $foto['id']; ?>' required  <?php Checked($dados['id_imagem_capa'], $foto['id']); ?>> Capa do Produto</td>
          					<td><button type='button' class='produto-rem-form btn btn-sm btn-danger float-right' onclick="ExcluirFotoProduto(<?php echo $foto['id']; ?>);">Excluir</button></td>
          				</tr>
                <?php } } ?>
              </tbody>
            </table>
          </div>
          <hr/>
          <div class="col-md-12">
            <h3>Adicionar novas fotos</h3> <a id="produto-add-foto" class="btn btn-primary">Adicionar foto</a>

            <table id="foto-wrapper" class="table mt-3 table-striped">
              <thead>
                <tr>
                  <th>Arquivo</th>
                  <th>Capa</th>
                  <th width="53px">Ações</th>
                </tr>
              </thead>

              <tbody></tbody>
            </table>

            <button class="btnSubmit btn btn-primary float-right" type="submit">Editar Produto</button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php } ?>

<div class="modal fade"  id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div  class="modal-dialog" role="document">
    <div  class="modal-content">
      <div class="modal-content b-0">
          <div class="modal-header r-0 bg-primary">
            <h6 class="modal-title text-white" id="exampleModalLabel">Adicionar Atibutos no Produto</h6>
            <a href="#" data-dismiss="modal" aria-label="Close" class="paper-nav-toggle paper-nav-white active"><i></i></a>
          </div>
          <div class="modal-body no-b" id="no-b">
          </div>
          <div class="modal-body no-b" id="no-c">           
          </div>
          <div class="modal-body no-b" id="no-d">           
          </div>          
        </div>
    </div>
  </div>
</div>
<script type="text/javascript"> function showDetails(z){$("#no-b").load('<?php echo ConfigPainel('base_url'); ?>/ecommerce/produtos/processa_attributos.php?radar=<?php echo $_GET['EditarProduto']; ?>');$("#no-d").load('<?php echo ConfigPainel('base_url'); ?>/ecommerce/produtos/processa_termos_listados.php?radar=<?php echo $_GET['EditarProduto']; ?>', )


}</script> 
