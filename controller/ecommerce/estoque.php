if(isset($_GET['?addVariacao'])){
    $data = [];
    foreach($_POST as $key => $valor){
        $data[$key]=$valor;
    };
    $query = DBCreate('ecommerce_estoque', $data, true); 
}