<?php
if( file_exists('mercadolivre.php')){
    $MLtoken = DBRead('ecommerce_mercadolivre', '*')[0];
}
if(isset($_GET['addVariacao'])){
    $data= [];
    foreach($_POST as $key => $valor){
        $data[$key]=$valor;
    };
    
    #ML
    
    if( file_exists('mercadolivreS.php')){
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mercadolibre.com/items/MLB1986951425/variations',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>' {  
                 "attribute_combinations":[  
                    {  
                       "name":"Tamanho",
                       "value_id":"52005",
                       "value_name":"34"
                    },
                     {  
                       "name":"Color",
                       "value_id":"52005",
                       "value_name":"Marrón"
                    }
                 ],
                 "price":100,
                 "available_quantity":4,
                 "sold_quantity":0,
                 "picture_ids":[  
                    "https://static.riachuelo.com.br/RCHLO/14008190/portrait/5f50fe1c48380d479e457b721cb04884c653fc95.jpg"
                 ]
              }',
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
                   'Authorization: Bearer '.$MLtoken['token'],
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;

    }
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
    }
}