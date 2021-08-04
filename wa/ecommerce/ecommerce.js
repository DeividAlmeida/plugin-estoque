async function estoque(a){
  let req = await fetch(UrlPainel+'wa/ecommerce/apis/estoque.php?ref='+a)
  let res = await req.json()
  return res
}
function EcommerceListagem(id, pag){
$.ajax({
type: "GET",
cache: false,
url: UrlPainel+'wa/ecommerce/listagem?id='+id+'&pag='+pag,
beforeSend: function (data){
//$("#SimpleSlideWA"+id).html("<center><br><img src=¥""+UrlPainel+"wa/css_js/loading.gif¥"><br>Carregando...<br></center>");
},
success: function (data) {
jQuery('#EcommerceListagem'+id).html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceListagem(id, pag); }, 5000);
},
});
}
function EcommerceCarrinho(){
$.ajax({
type:    "GET",
cache:   false,
url:     UrlPainel+'wa/ecommerce/carrinho',
success: function (data) {
jQuery('#EcommerceCarrinho').html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceCarrinho(); }, 5000);
},
});
}

function EcommerceCheckout(){
$.ajax({
type:    "GET",
cache:   false,
url:     UrlPainel+'wa/ecommerce/checkout',
success: function (data) {
jQuery('#EcommerceCheckout').html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceCheckout(); }, 5000);
},
});
}
function EcommerceCheckoutEu(){
$.ajax({
type:    "GET",
cache:   false,
url:     UrlPainel+'wa/ecommerce/checkout/eu.php',
success: function (data) {
jQuery('#EcommerceCheckoutEu').html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceCheckoutEu(); }, 5000);
},
});
}
function EcommerceBtnCarrinho(){
$.ajax({
type:    "GET",
cache:   false,
url:     UrlPainel+'wa/ecommerce/btn_carrinho',
success: function (data) {
jQuery('#EcommerceBtnCarrinho').html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceBtnCarrinho(); }, 5000);
},
});
}

function EcommerceBuscaResultado(pag){
const urlParams = new URLSearchParams(window.location.search);
const busca     = urlParams.get('b');

$.ajax({
type:    "GET",
cache:   false,
url:     UrlPainel+'wa/ecommerce/busca/resultado.php?b='+busca+'&pag='+pag,
success: function (data) {
jQuery('#EcommerceBuscaResultado').html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceBuscaResultado(); }, 5000);
},
});
}
function EcommerceBuscador(){
$.ajax({
type:    "GET",
cache:   false,
url:     UrlPainel+'wa/ecommerce/busca/buscador.php',
success: function (data) {
jQuery('#EcommerceBuscador').html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceBuscador(); }, 5000);
},
});
}
function EcommerceSlider(id){
$.ajax({
type: "GET",
cache: false,
url: UrlPainel+'wa/ecommerce/slider?id='+id,
beforeSend: function (data){
//$("#SimpleSlideWA"+id).html("<center><br><img src=¥""+UrlPainel+"wa/css_js/loading.gif¥"><br>Carregando...<br></center>");
},
success: function (data) {
jQuery('#EcommerceSlider'+id).html(data);
},
error: function (data) {
setTimeout(function(){ EcommerceSlider(id); }, 5000);
},
});
}
function shopUpdateListView(idList, isGrid, columnClass){
if(isGrid){
$("#shop--list"+idList+" .shop--list__content").removeClass('is-list');
$("#shop--list"+idList+" .shop--list__content").addClass('is-grid');
$("#shop--list"+idList+" .shop--list--bar__view-grid").addClass('is-active');
$("#shop--list"+idList+" .shop--list--bar__view-list").removeClass('is-active');
}
else{
$("#shop--list"+idList+" .shop--list__content").removeClass('is-grid');
$("#shop--list"+idList+" .shop--list__content").addClass('is-list');
$("#shop--list"+idList+" .shop--list--bar__view-grid").removeClass('is-active');
$("#shop--list"+idList+" .shop--list--bar__view-list").addClass('is-active');
}

$("#shop--list"+idList+" .shop--list__content .shop--product").each(function() {
$(this).attr('class', 'shop--product '+columnClass);
});
}
function CarrinhoAdd(id, carrinho_url, qtd, vlf, att){  
let atributo = document.getElementsByClassName('produto-categorias')
let ids = id
for(let i = 0;i<atributo.length;i++){
 ids +=  "-"+atributo[i].options[atributo[i].selectedIndex].getAttribute('data-id')
}
estoque(ids).then(res=>{
if( parseInt(res.estoque) >= parseInt(qtd)){
  if(att === void(0)){ att = id;}
    $.ajax({
  type: 	"GET",
  cache: 	false,
  url: 		UrlPainel+'wa/ecommerce/carrinho?AddItem='+id+"&qtd="+qtd+"&vlf="+vlf+"&att="+att+"&ref="+res.id+"&refs="+ids,
  beforeSend: function (data){
    $('.shop--product-page--header__button').html("Adicionando ao carrinho...");
  },
  success: function () {
    EcommerceBtnCarrinho();
    $('.shop--product-page--header__button').html("Comprar");
    Swal.fire({
      type:  'success',
              title: "Item adicionado no carrinho",
      showConfirmButton: false,
      showCloseButton: true,
      html: '<p>Clique no botão abaixo para ir para o carrinho ou clique no X para continuar comprando</p><a class="btn btn-primary shop--modal-add-product__btn" href="'+carrinho_url+'">Ver carrinho</a>'
          });
  }
});
}else{
      Swal.fire({
        type: 'error',
        title: 'Oops...',
        text: 'Infelizmente não temos estoque suficiente para suprir essa demanda. Nós temos '+ res.estoque +' unidade(s) desse produto em estoque.',
        showConfirmButton: false,
        showCloseButton: true,
      });
}
})
}
function alerta(){

Swal.fire({
    type: 'error',
    title: 'Oops...',
    text: 'Por favor preencha todos os campos antes de efetuar a compra!',
    showConfirmButton: false,
    showCloseButton: true,
  });

} 
function height(i){
let a = document.getElementById('Eframe')
a.style.height= '0px';
a.style.height = (i+200) + 'px';
}
Eframe = (a) => {
a.style.height= '0px';
a.style.height = (a.contentWindow.document.documentElement.scrollHeight+200) + 'px';
let border = document.createAttribute('frameborder');
let scroll = document.createAttribute('scrolling');
let widths = document.createAttribute('width');
border.value = '0';
scroll.value ='no';
widths.value = '100%';
a.setAttributeNode(border);
a.setAttributeNode(scroll);
a.setAttributeNode(widths);
$.getScript( "https://cdn.jsdelivr.net/npm/vue-swal@1/dist/vue-swal.min.js" )
}