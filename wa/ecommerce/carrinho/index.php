<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
error_reporting(0);
header('Access-Control-Allow-Origin: *');
require_once('../../../includes/funcoes.php');
require_once('../../../database/config.database.php');
require_once('../../../database/config.php');
require_once('controller.php');
if(isset($_SESSION['E-Wacontrol'])){
    $id_cliente = $_SESSION['E-Wacontrol'][0];
}
else if(isset($_COOKIE['E-Wacontroltoken'])){
    $id_cliente =  $_COOKIE['E-Wacontrolid'];
}else{
    $id_cliente = null;
}
$usuario = DBRead('ecommerce_usuario','*',"WHERE id = '{$id_cliente}'")[0];

$query = DBRead('ecommerce_config','*');

$config = [];
foreach ($query as $key => $row) {
  $config[$row['id']] = $row['valor'];
}
$usuario = DBRead('ecommerce_usuario','*',"WHERE id = '{$id_cliente}'")[0];
$total_carrinho = 0;
?>
<style>
.shop--cart .btn, #formCarrinhoSucesso .btn{
  border: 0;
  background-color: <?php echo $config['carrinho_cor_btns']; ?> !important;
}
#cartCheckout{
	background-color: <?php echo $config['carrinho_cor_btn_finalizar']; ?> !important;
}
.swal2-popup{
    padding:50px !important;
    width:40em !important;
}
</style>

<div class="shop--cart">

<?php
if(isset($_SESSION["car"]) && is_array($_SESSION["car"]) && count($_SESSION["car"]) > 0){
?>
	<meta charset="UTF-8">
	<div class="shop--cart__block"></div>
	<div class="table-responsive-sm">
	<table id="shop--cart--table" class="shop--cart--table table m-0 table-striped">
	  <tr>
	    <th>Imagem</th>
	    <th>Produto</th>
	    <th>Quantidade</th>
	    <th>Preço</th>
	    <th>Total</th>
	  </tr>

	  <?php foreach($_SESSION["car"] as $id => $qtd ){
	    $query = DBRead('ecommerce', '*', "WHERE id = $qtd[0]");
	    $produto = $query[0];

	    // Carregando Fotos do produto
	    $fotos  = DBRead('ecommerce_prod_imagens','*', "WHERE id_produto = {$produto['id']}");

	    // Busca pela foto de capa e salva em variavel
	    foreach($fotos as $foto){
	      if($foto['id'] == $produto['id_imagem_capa']){
	        $foto_capa = $foto;
	      }
	    }

	    // URL da imagem da capa
	    $url_img_capa = RemoveHttpS(ConfigPainel('base_url'))."wa/ecommerce/uploads/".$foto_capa['uniq'];
	    
	    if ($produto['a_consultar'] <> 'S') {
	    	$total_carrinho += floatval(str_replace(",", ".", $qtd[2])) * floatval(str_replace(",", ".", $qtd[1]));
	    }
	    
	  ?>

	    <tr>
	      <td><img src="<?php echo $url_img_capa ?>" alt="Foto Produto <?php echo $produto['nome']; ?>" width="100"></td>
		  <td><?php echo $produto['nome']; ?><br><span style="word-wrap: normal" id="trm<?php echo $id ?>">
		  <script> 
		  const a = document.getElementById("trm<?php echo $id ?>");
		  const b = sessionStorage.getItem("<?php echo $id ?>");
		  let c = a.innerHTML = b;
		   </script></span></td>
	        <td class="produtos" id="cart_qtd_<?php echo $id; ?>" pdt="<?php echo $qtd[0]; ?>" vlf="<?php echo $qtd[2]; ?>" ref="<?php echo $qtd[4]; ?>" style="white-space: nowrap;">
				<input class="cart_qtd" type="number" min="1" style="width:50px;" value="<?php echo $qtd[1]; ?>"/>
				<button class="cart_qtd_delete btn btn-sm btn-primary"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
			</td>
		<?php if ($produto['a_consultar'] <> 'S') { ?>
	      <td><?php echo $config['moeda'].' '.str_replace(".",",",$qtd[2]); ?></td>
	      <td><?php echo $config['moeda'].' '.number_format(floatval(str_replace(",", ".", $qtd[2])) * floatval(str_replace(",", ".", $qtd[1])), 2, ",", "."); ?></td>
	  	<?php } else { ?>
	  		<td>A Consultar</td>
	      	<td>A Consultar</td>
	  	<?php } ?>
	    </tr>
	  <?php } ?>
	  <tr>
			<td colspan="3"></td>
			<td><strong>Desconto</strong></td>
			<td><span id="desconto"><?php echo $config['moeda'].' '; ?>0,00</span></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td><strong>Total</strong></td>
			<td id="total"><?php echo $config['moeda'].' '.number_format ($total_carrinho, 2, ",", ".") ?></td>
		</tr>
	</table>
	</div>


	<div class="row ">
		<div class="col-xs-6 text-left">
			<div class="col-xs-6 hidden" >
			    <input type="text" id="cupom">
			</div>
			<div class="col-xs-6 hidden">   
			    <a id="cartCheckout" onclick="Cupom(document.getElementById('cupom').value)" class="btn btn-primary" >Adicionar cupom</a>
			</div>
		</div>
		<div class="col-xs-6 text-right">
			<a id="cartCheckout" class="btn btn-primary" onclick="conta()">Finalizar Pedido</a>			
		</div>

	</div>

<? } else {?>
	<span>Seu carrinho está vazio!</span>
<? } ?>

</div>
<link rel="stylesheet" href="<?php echo RemoveHttpS(ConfigPainel('base_url')); ?>epack/css/elements/form.css">
<link rel="stylesheet" href="<?php echo RemoveHttpS(ConfigPainel('base_url')); ?>epack/css/elements/animate.css">
<link rel="stylesheet" href="<?php echo RemoveHttpS(ConfigPainel('base_url')); ?>epack/css/elements/modal.css">
<link rel="stylesheet" href="<?php echo RemoveHttpS(ConfigPainel('base_url')); ?>wa/ecommerce/assets/css/carrinho.css">
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
<?php if(!is_array($usuario)){ ?>
    function conta(){ 
        Swal.fire({
            title: 'Como você deseja comprar?',
            showConfirmButton: false,
            html:
                '<b>Quero comprar como visitante:</b><br><br>'+
                '<button id="cartCheckout" onclick="logout()" type="button" class="swal2-confirm swal2-styled" aria-label="" style="display: line-block;">Comprar como visitante</button><br><br>'+
                '<b>Já tenho uma conta, quero fazer login:</b>'+
                '<input id="swal-input1" class="swal2-input" placeholder="E-mail" type="email">' +
                '<input id="swal-input2" class="swal2-input" placeholder="Senha" type="password">'+
                '<button id="cartCheckout" type="button" onclick="login()" class="swal2-confirm swal2-styled" aria-label="" style="display: line-block;">ENTRAR</button><br><br>'
        })
     }
<?php }else{ ?>
    function conta(){
        window.location.href ="<?php echo $config['pagina_checkout']; ?>"
    }
<?php } ?>
function logout(){
  fetch('<?php echo  ConfigPainel('base_url'); ?>wa/ecommerce/apis/logout.php?token=<?php echo md5(session_id()) ?>').then(window.location.href ="<?php echo $config['pagina_checkout']; ?>")
}
function login(){
    fetch('<?php echo  ConfigPainel('base_url'); ?>wa/ecommerce/apis/logout.php?token=<?php echo md5(session_id()) ?>').then(next=>{
        fetch('<?php echo  ConfigPainel('base_url'); ?>wa/ecommerce/apis/autentica.php',{
                method: 'POST',
                headers:{
                    'Authorization': 'Basic '+btoa(document.getElementById('swal-input1').value+':'+document.getElementById('swal-input2').value),
                    'Content-Type': 'application/json',
                }
            }).then(aa=>aa.text()).then(aa=>{
                if(aa == 1){
                    Swal.fire({
                      icon: 'success',
                      title: "Pode comprar!!",
                      text: "Login realizado com sucesso"
                    }).then(window.location.href ="<?php echo $config['pagina_checkout']; ?>")              
                }else{ 
                    Swal.fire({
                      icon: 'error',
                      title: "ERRO!",
                      text: aa
                    })
                }
            })
    })
}
let existe = sessionStorage.getItem('cuponsUsados');
if(sessionStorage.getItem('cuponsUsados') == null){ sessionStorage.setItem('cuponsUsados', '')};

function Cupom(get){ 
    let hoje = new Date();
    let desconto = document.getElementById('desconto');
    desconto.innerHTML =  "<i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i>";
    let frete = document.getElementById('vl_frete');
    let total = document.getElementById('valor');
    let geral = document.getElementById('valor_geral');
    let cupons = sessionStorage.getItem("cuponsUsados").split(',');
    let qtd = 0;
    let vlf = 0;
    let quantidade = document.getElementsByClassName('cart_qtd');
    var form = new FormData();
    let itens = document.getElementsByClassName('produtos');
    let v_total = document.getElementById('total');
    let abate = <?php echo $total_carrinho; ?>;
    for(i=0; i< itens.length; i++){
       form.append(itens[i].getAttribute("pdt"), parseInt(document.getElementsByClassName('cart_qtd')[i].value));
    }
    
    fetch(UrlPainel+'wa/ecommerce/apis/cupons.php?id='+get, {
        method: "POST",
        body: form
    }).then( (res) => { res.json().then(data =>{
        let expira = new Date(data.expira.split('-').join('-'));
        expira.setSeconds(expira.getSeconds() + 97199);
        let min =  parseFloat(data.min.replace(",", "."));
        let max = parseFloat(data.max.replace(",", "."));
        let descontoReal;
        switch(data.tipo){
            case '1':
                descontoReal = (data.fixo/100)* abate;
                break;
            case '2':
                descontoReal = data.fixo;
                break;
            case '3': 
                descontoReal = data.desconto;
                break;
        }
        
            if(data.frete == 'on' && descontoReal > 0 && expira > hoje && abate >= min && abate <= max){
                sessionStorage.setItem("totalDesconto", "document.getElementById('vl_frete').value")
                desconto.innerHTML = "Frete Grátis";
            }
            else if(data.acumular != "on" && expira > hoje && abate >= min && abate <= max){
                    cupons.push(get);
                    sessionStorage.setItem("cuponsUsados", cupons);
                    sessionStorage.setItem("cupom"+get, descontoReal);
                    let unique = [...new Set(cupons)];
                    for(a=1; a<unique.length;a++){
                        vlf += parseFloat(sessionStorage.getItem("cupom"+unique[a]));
                    }
                    sessionStorage.setItem("totalDesconto", vlf);
                    let totalDesconto = sessionStorage.getItem('totalDesconto');
                    desconto.innerHTML = "<?php echo $config['moeda']?> "+ parseFloat(totalDesconto).toFixed(2).toString().replace(".", ",");
                    v_total.innerHTML = "<?php echo $config['moeda']?> "+ parseFloat( abate - totalDesconto).toFixed(2).toString().replace(".", ",");
                }
            else if(data.acumular == "on" && expira > hoje && abate >= min && abate <= max){
                    sessionStorage.setItem("totalDesconto", descontoReal);
                        let totalDesconto = sessionStorage.getItem('totalDesconto');
                        desconto.innerHTML = "<?php echo $config['moeda']?> "+ parseFloat(totalDesconto).toFixed(2).toString().replace(".", ",");
                        v_total.innerHTML = "<?php echo $config['moeda']?> "+ parseFloat( abate - totalDesconto).toFixed(2).toString().replace(".", ",");
                }
            else{
                sessionStorage.setItem("totalDesconto", '0.00')
                desconto.innerHTML = "<?php echo $config['moeda']?> "+ "0,00";
                v_total.innerHTML = "<?php echo $config['moeda']?> "+ parseFloat(abate).toFixed(2).toString().replace(".", ",");
                Swal.fire({
                          icon: 'error',
                          title: 'Cupom inválido!!'
                        })
            }
        });
    });
}

</script>
<script src="<?php echo RemoveHttpS(ConfigPainel('base_url')); ?>wa/ecommerce/assets/js/carrinho.js"></script>