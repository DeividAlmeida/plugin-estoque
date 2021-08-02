<?php
ob_start();
?>
<style>
  .shop--modal-add-product__btn{
    border: 0 !important;
    margin-top:10px !important;
    background-color: <?php echo $config['carrinho_cor_btns']; ?> !important;
  }
  .shop--product-page--header__name{
    color: <?php echo $config['produto_cor_titulo']; ?> !important;
  }
  .shop--product-page--header__price{
    color: <?php echo $config['produto_cor_preco']; ?> !important;
  }
  .shop--product-page--header__button{
    background-color: <?php echo $config['produto_cor_botao']; ?> !important;
    color: <?php echo $config['produto_cor_texto_botao']; ?> !important;
  }
  .shop--product-page--header__button:hover{
    background-color: <?php echo $config['produto_cor_hover_botao']; ?> !important;
  }
  .shop--product-page--header__categories li{
    background-color: <?php echo $config['produto_cor_tag_categoria']; ?> !important;
    color: <?php echo $config['produto_cor_texto_tag_categoria']; ?> !important;
  }
  .shop--product-page--header--main-photo__tag{
    background-color: <?php echo $produto['etiqueta_cor']; ?> !important;
  }
  @media print {
    .hidden-print {
      display: none !important;
    }
  }
  
.input-number {
  width: 40px;
  padding: 0 12px;
  text-align: center;
  outline: none;
}

.input-number,
.input-number-decrement,
.input-number-increment {
  border: 1px solid #ccc;
  height: 40px;
  user-select: none;
}

.input-number-decrement,
.input-number-increment {
  display: inline-block;
  width: 30px;
  line-height: 38px;
  background: #f1f1f1;
  color: #444;
  text-align: center;
  font-weight: bold;
  cursor: pointer;
}
.input-number-decrement:active,
.input-number-increment:active {
  background: #ddd;
}

.input-number-decrement {
  border-right: none;
  border-radius: 4px 0 0 4px;
}

.input-number-increment {
  border-left: none;
  border-radius: 0 4px 4px 0;
}
#frajola{
	opacity: 0.5;
}
</style>
<div class="shop--product-page--header row">
  <div class="col-md-6">
    <div class="shop--product-page--header--main-photo__wrapper">
      <img class="shop--product-page--header--main-photo__photo" src="<?php echo $url_img_capa; ?>" alt="Foto do produto <?php echo $produto['nome']; ?>" data-zoom-image="<?php echo $url_img_capa; ?>" width="100%"/>

      <?php if(isset($produto['etiqueta']) && !empty($produto['etiqueta'])){ ?>
        <span class="shop--product-page--header--main-photo__tag"><?php echo $produto['etiqueta']; ?></span>
      <?php } ?>
    </div>


    <div id="gallery" class="shop--product-page--header--gallery">
      <?php foreach($fotos as $foto){
        $url_img = RemoveHttpS(ConfigPainel('base_url'))."wa/ecommerce/uploads/".$foto['uniq'];
      ?>
        <a class="shop--product-page--header--gallery__img" data-src="<?php echo $url_img; ?>" data-image="<?php echo $url_img; ?>" data-zoom-image="<?php echo $url_img; ?>">
          <img src="<?php echo $url_img; ?>" alt="Foto do produto <?php echo $produto['nome']; ?>">
        </a>
      <?php } ?>
    </div>
  </div>
  <div class="col-md-6">
    <h4 class="shop--product-page--header__name"><?php echo $produto['nome']; ?></h4>
    <div class="shop--product-page--header__price" id="valor" <?php if(isset($atributos)){ foreach($atributos as $atributo){ print_r("data-".$atributo['id']."='0'"); print_r(" id-".$atributo['id']."='0'"); print_r(" index-".$atributo['id']."='0'");}} ?> data-valor="<?php echo floatval($produto['preco']); ?>" valor-final="<?php echo floatval($produto['preco']); ?>" quantidade="1" id-final="0" index-final="" >
      <?php if($produto['a_consultar'] == 'S') {?>
        A consultar
      <?php } else { ?>
        <?= $config['moeda'] ?> <?php echo str_replace(".",",",$produto['preco']); ?>
      <?php } ?>
    </div>

    <hr class="shop--product-page--header__divider"/>

    <div class="shop--product-page--header__categories">
      <ul>
        <?php foreach($categorias as $categoria){ ?>
          <li><?php echo $categoria['nome']; ?></li><br>
        <?php } ?>        
      </ul><br>
          <?php if(isset($marcas)){ foreach($marcas as $marca){ ?>
          <p><b>Marca:</b>&nbsp;<?php echo $marca['nome']; ?></p><br>
        <?php }} ?>

        <?php if(isset($atributos)){ foreach($atributos as $atributo){        	
        		$id = $atributo['id'];
        		$termos = DBRead('ecommerce_prod_termos', '*', "WHERE id_atributo = {$id} AND id_produto = {$produto['id']}");        	
        	?>
        	<div class="form-group">
        	<label> <?php print_r($atributo['nome'].":&nbsp;"); ?></label>
        	<select class="form-control produto-categorias" id="mySelect<?php print_r($atributo['id']); ?>" style="width: auto" onchange="change<?php print_r($atributo['id']); ?>()">
        		<option value="" default>Escolha uma opção</option> 
        		<?php foreach ($termos as $termo) {
        			$nome = DBRead('ecommerce_termos', 'nome,id', "WHERE id = {$termo['id_termo']}");?>
        		<option value="<?php echo $termo['valor']; ?>" data-id="<?php echo $nome[0]['id']; ?>"><?php print_r($nome[0]['nome']); ?></option>
        		<?php }  ?>                         
          </select> 
          
        
        </div>
		  <script>
            function change<?php print_r($atributo['id']); ?>(){
        		var h = "<?php echo $config['moeda'];?>";
				    var x = document.getElementById("mySelect<?php print_r($atributo['id']); ?>").value;
				    var e = document.getElementById("mySelect<?php print_r($atributo['id']); ?>").selectedIndex;
            var k = document.getElementById("mySelect<?php print_r($atributo['id']); ?>").options;
            var v = "<?php print_r($atributo['nome']); ?>: " + k[e].text;
            var z =	document.getElementById("quantidade").value;
            var y = document.getElementById("valor").setAttribute("id-<?php print_r($atributo['id']); ?>", v);				    
				    var w = document.getElementById("valor").setAttribute("data-<?php print_r($atributo['id']); ?>", x);
				    var j = document.getElementById("valor").setAttribute("index-<?php print_r($atributo['id']); ?>", e);
				    var i = document.getElementById("valor").getAttribute("data-<?php print_r($atributo['id']); ?>");
				    var a = document.getElementById("valor").getAttribute("data-valor");
				    var b = document.getElementById("valor").getAttribute("quantidade");				    
				    var c = <?php  foreach($atributos as $atributo){ print_r("(+document.getElementById('valor').getAttribute('data-".$atributo['id']."')) * b + ");  } ?> a * b;
				    var g = <?php  foreach($atributos as $atributo){ print_r("document.getElementById('valor').getAttribute('id-".$atributo['id']."') +'<br>'+");  } ?> "" ;
				    var m = <?php  foreach($atributos as $atributo){ print_r("document.getElementById('valor').getAttribute('index-".$atributo['id']."')+");  } echo $produto['id']; ?> ;
            var t = c.toFixed(2).replace(".", ",");
            var d = document.getElementById('valor').innerHTML = h +" "+ t;
            var l = document.getElementById("valor").setAttribute("id-final", g);
            var n = document.getElementById("valor").setAttribute("index-final", m);
            var o = c / b;
            var p = o.toFixed(2);
            var f = document.getElementById("valor").setAttribute("valor-final", p);
					if(<?php  foreach($atributos as $atributo){ print_r("document.getElementById('mySelect".$atributo['id']."').value != '' &&");  }; ?> z > 0){document.getElementById("piupiu").style.display = "inline";document.getElementById("frajola").style.display = "none";}
					else{document.getElementById("piupiu").style.display = "none";document.getElementById("frajola").style.display = "inline";}
				}         
        	</script>
        <?php } } ?> 

        
<span class="input-number-decrement">–</span><input class="input-number" type="text" id="quantidade" value="1" min="1" max="999"><span class="input-number-increment" style="margin-right: 30px;">+</span> <a <?php if(isset($atributos)){ echo "style='display:none;'";}else{echo "style='display:inline;'";} ?>  id="piupiu" class="shop--product-page--header__button btn btn-lg" 
<?php echo (!empty($produto['link_venda'])) ? "href='{$produto["link_venda"]}' target='{$produto["target_link"]}'" : 'onclick="CarrinhoAdd('.$produto["id"].', '."'{$config["pagina_carrinho"]}'".', document.getElementById('."'quantidade'".').value,'.' document.getElementById('."'valor'".').getAttribute('."'valor-final'".')'; if(isset($atributos)){ echo ', document.getElementById('."'valor'".').getAttribute('."'index-final'".')';}else{echo " ";} echo ', sessionStorage.setItem(document.getElementById('."'valor'".').getAttribute('."'index-final'".'), document.getElementById('."'valor'".').getAttribute('."'id-final'".')))"'; ?>>
      <?php echo $produto['btn_texto']; ?>
    </a><a class="shop--product-page--header__button btn btn-lg" <?php if(isset($atributos)){ echo "style='display:inline;'";}else{echo "style='display:none;'";} ?>  id="frajola" onclick="alerta()" >
      <?php echo $produto['btn_texto']; ?>
    </a>
    </div>


    <p class="shop--product-page--header__resume"><?php echo $produto['resumo']; ?></p>

    

    <hr class="shop--product-page--header__divider"/>

    <h5>Compartilhar</h5>
    <div class="shop--product-page__header--share__wrapper hidden-print">
      <!-- Sharingbutton Facebook -->
      <a class="shop--product-page__header--share__link" href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode($url); ?>" target="_blank" rel="noopener" aria-label="Facebook" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
        <div class="shop--product-page__header--share shop--product-page__header--share--facebook shop--product-page__header--share--medium"><div aria-hidden="true" class="shop--product-page__header--share__icon shop--product-page__header--share__icon--solid">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg></div>Facebook</div>
      </a>

      <!-- Sharingbutton Twitter -->
      <a class="shop--product-page__header--share__link" href="https://twitter.com/intent/tweet/?text=<?php echo urlencode($produto['nome']); ?>&amp;url=<?php echo urlencode($url); ?>" target="_blank" rel="noopener" aria-label="Twitter" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
        <div class="shop--product-page__header--share shop--product-page__header--share--twitter shop--product-page__header--share--medium"><div aria-hidden="true" class="shop--product-page__header--share__icon shop--product-page__header--share__icon--solid">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg></div>Twitter</div>
      </a>

      <!-- Sharingbutton Pinterest -->
      <a class="shop--product-page__header--share__link" href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode($url); ?>&amp;media=<?php echo urlencode($url); ?>&amp;description=<?php echo urlencode($produto['nome']); ?>" target="_blank" rel="noopener" aria-label="Pinterest" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
        <div class="shop--product-page__header--share shop--product-page__header--share--pinterest shop--product-page__header--share--medium"><div aria-hidden="true" class="shop--product-page__header--share__icon shop--product-page__header--share__icon--solid">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path d="M12.14.5C5.86.5 2.7 5 2.7 8.75c0 2.27.86 4.3 2.7 5.05.3.12.57 0 .66-.33l.27-1.06c.1-.32.06-.44-.2-.73-.52-.62-.86-1.44-.86-2.6 0-3.33 2.5-6.32 6.5-6.32 3.55 0 5.5 2.17 5.5 5.07 0 3.8-1.7 7.02-4.2 7.02-1.37 0-2.4-1.14-2.07-2.54.4-1.68 1.16-3.48 1.16-4.7 0-1.07-.58-1.98-1.78-1.98-1.4 0-2.55 1.47-2.55 3.42 0 1.25.43 2.1.43 2.1l-1.7 7.2c-.5 2.13-.08 4.75-.04 5 .02.17.22.2.3.1.14-.18 1.82-2.26 2.4-4.33.16-.58.93-3.63.93-3.63.45.88 1.8 1.65 3.22 1.65 4.25 0 7.13-3.87 7.13-9.05C20.5 4.15 17.18.5 12.14.5z"/></svg></div>Pinterest</div>
      </a>

      <!-- Sharingbutton WhatsApp -->
      <a class="shop--product-page__header--share__link" href="whatsapp://send?text=<?php echo urlencode($produto['nome']); ?>%20<?php echo urlencode($url); ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
        <div class="shop--product-page__header--share shop--product-page__header--share--whatsapp shop--product-page__header--share--medium"><div aria-hidden="true" class="shop--product-page__header--share__icon shop--product-page__header--share__icon--solid" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2 .5 3.9 1.5 5.6L.6 23.4l6-1.6c1.6.9 3.5 1.3 5.4 1.3 6.3 0 11.4-5.1 11.4-11.4-.1-2.8-1.2-5.7-3.3-7.8zM12 21.4c-1.7 0-3.3-.5-4.8-1.3l-.4-.2-3.5 1 1-3.4L4 17c-1-1.5-1.4-3.2-1.4-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.4-9.5 9.4zm5.1-7.1c-.3-.1-1.7-.9-1.9-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1s-1.2-.5-2.3-1.4c-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6s.3-.3.4-.5c.2-.1.3-.3.4-.5.1-.2 0-.4 0-.5C10 9 9.3 7.6 9 7c-.1-.4-.4-.3-.5-.3h-.6s-.4.1-.7.3c-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.3-.3-.4-.6-.5z"/></svg></div>WhatsApp</div>
      </a>

      <a class="shop--product-page__header--share__link"  aria-label="Imprimir" id="printBtn" href="javascript:window.print()">
        <div class="shop--product-page__header--share shop--product-page__header--share--email shop--product-page__header--share--medium">Imprimir</div>
      </a>
    </div>
  </div>
</div>

<script type="text/javascript">

	(function() {
 
  window.inputNumber = function(el) {

    var min = el.attr('min') || false;
    var max = el.attr('max') || false;

    var els = {};

    els.dec = el.prev();
    els.inc = el.next();

    el.each(function() {
      init($(this));
    });

    function init(el) {

      els.dec.on('click', decrement);
      els.inc.on('click', increment);

      function decrement() {
        var value = el[0].value;
        value--;
        if(!min || value >= min) {
          el[0].value = value;
          document.getElementById("valor").setAttribute("quantidade", value);
          var h = "<?php echo $config['moeda'];?>";
          var a = document.getElementById("valor").getAttribute("data-valor");
          var b = document.getElementById("valor").getAttribute("quantidade");      
		      var c = <?php if(isset($atributos)){ foreach($atributos as $atributo){ print_r("(+document.getElementById('valor').getAttribute('data-".$atributo['id']."')) * b + ");  }} ?> a * b;
          var t = c.toFixed(2).replace(".", ",");
          var d = document.getElementById('valor').innerHTML = h +" "+ t;
          var v = c / b;
          var z = v.toFixed(2).replace(".", ",");
          var f = document.getElementById("valor").setAttribute("valor-final", z);
        }
      }

      function increment() {
        var value = el[0].value;
        value++;
        if(!max || value <= max) {
          el[0].value = value;
          document.getElementById("valor").setAttribute("quantidade", value);
          var h = "<?php echo $config['moeda'];?>";
          var a = document.getElementById("valor").getAttribute("data-valor");
          var b = document.getElementById("valor").getAttribute("quantidade");
		      var c = <?php if(isset($atributos)){ foreach($atributos as $atributo){ print_r("(+document.getElementById('valor').getAttribute('data-".$atributo['id']."')) * b + ");  }} ?> a * b;
          var t = c.toFixed(2).replace(".", ",");
          var v = c / b;
          var z = v.toFixed(2).replace(".", ",");
          var d = document.getElementById('valor').innerHTML = h +" "+ t;
          var f = document.getElementById("valor").setAttribute("valor-final", z);

        }
      }
    }
  }
})();

inputNumber($('.input-number'));

	          
</script>
<?php
$cabecalho  = ob_get_clean();
$matriz     = str_replace('[WAC_ECOMMERCE_PROD_CABECALHO]', $cabecalho, $matriz);
