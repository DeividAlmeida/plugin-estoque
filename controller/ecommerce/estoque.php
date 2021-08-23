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
    
    
    #ML 
    
    if( file_exists('mercadolivre.php') && !empty($_GET['ML-id'])){
        
        $produto = DBRead('ecommerce','*',"WHERE id_ml = '{$_GET['ML-id']}'")[0];
        
        $img = ConfigPainel('base_url').'wa/ecommerce/uploads/'.DBRead('ecommerce_prod_imagens', '*', "WHERE id = {$produto['id_imagem_capa']}")[0]['uniq'];
        
         $atributo = DBRead('ecommerce_prod_termos',' MIN(id_atributo) AS id_atributo', "WHERE id_produto = {$produto['id']} GROUP BY id_atributo");
         if(is_array($atributo)){
                foreach($atributo as $Achave => $Avalor){
                    $att_name = DBRead('ecommerce_atributos', '*',  "WHERE id ={$Avalor['id_atributo']}")[0]['nome'];
                    $combinacao .= ' 
                    {  
                       "name":"'.$att_name.'",
                       "value_id":"52008",
                       "value_name":"'.DBRead('ecommerce_termos', '*', "WHERE id = {$_POST[$att_name]}")[0]['nome'].'"
                    },';
                }
            }
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mercadolibre.com/items/'.$_GET['ML-id'].'/variations',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>' {  
                 "attribute_combinations":[  
                    '.$combinacao.'
                 ],
                 "price":'.$produto['preco'].',
                 "available_quantity":'.$_POST['estoque'].',
                 "picture_ids":[  
                    "'.$img.'"
                 ]
              }',
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
                   'Authorization: Bearer '.$MLtoken['token'],
          ),
        ));
        
        $response = curl_exec($curl);
        $response = json_decode($response);
        $data['id_ml'] = $response[count($response)-1]->id;
        curl_close($curl);
    }
       
    $query = DBCreate('ecommerce_estoque', $data, true); 
    var_dump($response);
    if ($query != 0) {
        #Redireciona('?Estoque&sucesso');
    } else {
        #Redireciona('?Estoque&erro');
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
    if( file_exists('mercadolivre.php')){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mercadolibre.com/items/'.$_GET['ml'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_POSTFIELDS =>'{
            "available_quantity": '.$_GET['valor'].'
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
                   'Authorization: Bearer '.$MLtoken['token'],
          ),
        ));
        $response = curl_exec($curl);
        
        curl_close($curl);

    }
    $query = DBUpdate('ecommerce_estoque', ['estoque'=>$_GET['valor']], "id = '{$id}'");
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