<?php 
error_reporting(0);
require_once('../../../../includes/funcoes.php');
require_once('../../../../database/config.database.php');
require_once('../../../../database/config.php');

$tabela = [];
$data =  DBRead('ecommerce', '*', 'ORDER BY id DESC');
if(is_array($data)){
    foreach($data as $chave => $valor){ 
         $produto = DBRead('ecommerce_estoque','*',"WHERE ref = {$valor['id']}")[0];
        $atributo = DBRead('ecommerce_prod_termos',' MIN(id_atributo) AS id_atributo', "WHERE id_produto = {$valor['id']} GROUP BY id_atributo");
        if(!isset($_GET['search'])){
            if(is_array($atributo)){
                foreach($atributo as $Achave => $Avalor){
                    $tabela[$chave][$Achave]['atributo'] =  DBRead('ecommerce_atributos', '*',  "WHERE id ={$Avalor['id_atributo']}")[0]['nome'];  
                    $termo = DBRead('ecommerce_prod_termos ', '*', "WHERE id_produto = {$valor['id']} AND id_atributo = {$Avalor['id_atributo']}");
                    if(is_array($termo)){
                        foreach($termo as $Tchave => $Tvalor){
                          $tabela[$chave][$Achave][$Tchave]['nome'] =  DBRead('ecommerce_termos', '*', "WHERE id = {$Tvalor['id_termo']}")[0]['nome'];
                          $tabela[$chave][$Achave][$Tchave]['id'] =  DBRead('ecommerce_termos', '*', "WHERE id = {$Tvalor['id_termo']}")[0]['id'];
                        }
                    }
                }
            }
        }else{
            $produtos = DBRead('ecommerce_estoque','*');
            $tabela[$chave]['<div class="row" >
                <div class="hidden">'.$Pchave] = '</div>';
                if(is_array($produtos)){
                    foreach($produtos as $Pchave => $Pvalor){
                       if(strpos($Pvalor['ref'], $valor['id'].'-')===0){
                                $tabela[$chave]['<div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">'.$Pvalor['nome'].'</div>
                                        <div class="card-body"> Referência'] = $Pvalor['ref'];
                                            $tabela[$chave]['<div class="hidden">'.$Pchave.'</div>'.'Estoque'] = '<input onchange="estoque(this, null)" id="'.$Pvalor['id'].'" style="width:30%" type="number" value="'.$Pvalor['estoque'].'" >';
                                             $tabela[$chave]['<div class="hidden">'.$Pchave.'</div>'.'Limiar de Estoque Baixo']= ' <input id="'.$Pvalor['id'].'" ml="'.$valor['id_ml'].'" onchange="limiar(this)" type="number" style="width:25%" value="'.$Pvalor['min'].'">';
                                        $tabela[$chave]['<div class="hidden">'.$Pchave.'</div></div>
                                        <div class="card-footer "> 
                                            <div class="hidden">'] = '</div>';
                                            if( checkPermission($PERMISSION, $_SERVER['SCRIPT_NAME'],'estoque', 'deletar')){ 
                                                $tabela[$chave]['<a href="javascript:void(0)" id="DeletarVariacao" onclick="DeletarItem('.$Pvalor['id'].', this.id);" class="btn btn-primary">
                                                    <i class="icon icon-trash "></i> 
                                                Excluir</a><div class="hidden">']  = '</div>';
                                            }
                                        $tabela[$chave]['</div>
                                    </div>
                                </div>
                            <div class="hidden">'.$Pchave] ='</div>' ;
                        }else if(!is_array($atributo) && is_array($produto)){
                        $tabela[$chave]['<div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                '.$valor['nome'].'
                                </div>
                                <div class="card-body">Estoque'] = '
                                    <input id="'.$produto['id'].'" ml="'.$valor['id_ml'].'" onchange="estoque(this)" type="number" style="width:30%" value="'.$produto['estoque'].'">';
                                    $tabela[$chave]['Limiar de Estoque Baixo']= ' <input id="'.$produto['id'].'" ml="'.$valor['id_ml'].'" onchange="limiar(this)" type="number" style="width:25%" value="'.$produto['min'].'">';
                                $tabela[$chave]['</div>
                            </div>
                            <div class="hidden">'] = '</div>';
                       }
                    }
                }
            $tabela[$chave]['</div><div class="hidden">'] = ""; 
            $tabela[$chave]['id'] = $valor['id'];
            if(isset($valor['id_imagem_capa'])){
                $tabela[$chave]['imagem'] = "<img src='wa/ecommerce/uploads/".DBRead('ecommerce_prod_imagens', '*', "WHERE id = {$valor['id_imagem_capa']}")[0]['uniq']."' height='100'/>";
            }
            $tabela[$chave]['nome'] = $valor['nome'];
            if(is_array($atributo)){
                $tabela[$chave]['variacao'] = "<center><a onclick='lincar(".$chave.",".$valor['id'].",this)' ml='".$valor['id_ml']."' id='".$valor['nome']."' style='cursor:pointer' data-target='#Modal' data-toggle='modal' ><i class='text-center text-primary icon icon-plus-circle fa-3x' aria-hidden='true'></i></a></center>";
            }
        }
    }
}
echo json_encode($tabela);
