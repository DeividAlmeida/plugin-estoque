(function($) {
  'use strict';

  // Atualizar carrinho
  $('.cart_qtd').change(function(){
    var parent = $(this).parent();
    var btn_id = parent.attr('id');

    var id = parseInt(btn_id.split("cart_qtd_")[1]);
    var qtd = parseInt(parent.find('input').val());

    var  pdt = parent.attr("pdt");
    var  vlf = parent.attr("vlf");
    
    var ref = parent.attr("ref");

    estoque(ref).then(res=>{
        if(parseInt(res.estoque) >= parseInt(qtd)){
            $.ajax({
              type: 	"GET",
              cache: 	false,
              url: 		UrlPainel+'wa/ecommerce/carrinho/?UpdateQtd='+id+'&qtd='+qtd+'&pdt='+pdt+'&vlf='+vlf+"&ref="+res.id+"&refs="+ref,
        
              success: function () {
               new EcommerceBtnCarrinho();
               new  EcommerceCarrinho();
               Swal.fire({
                  icon: 'success',
                  title: 'Atualizado',
                  html: '<p style="font-size:15px">Carrinho atualizado com sucesso.</p>',
                  showConfirmButton: false,
                  showCloseButton: true,
                });
        
              }
            });
        }else{  
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              html: '<p style="font-size:15px">Infelizmente não temos estoque suficiente para suprir essa demanda. Nós temos '+ res.estoque +' unidade(s) desse produto em estoque.</p>',
              showConfirmButton: false,
              showCloseButton: true,
            });
        }
    })
})
      


  // Remove item do carrinho
  $('.cart_qtd_delete').click(function(){
    var parent = $(this).parent();
    var btn_id = parent.attr('id');
    var id_produto = parseInt(btn_id.split("cart_qtd_")[1]);

    $.ajax({
      type: 	"GET",
      cache: 	false,
      url: 		UrlPainel+'wa/ecommerce/carrinho/?RemItem='+id_produto,
      success: function () {
      new EcommerceBtnCarrinho();
      new  EcommerceCarrinho();
      }
    });
  })

  $('#formCarrinho').submit(function (e) {
		// Para de enviar o formulario
		e.preventDefault();

		// Faz solicitação via AJAX
		$.ajax({
			type: 				'POST',
			cache: 				false,
			url: 					UrlPainel+'wa/ecommerce/carrinho/?EnviarEmail',
			data: 				$(this).serialize(),
      beforeSend: function (data){
        $("#formCarrinho .btnSubmit").attr("disabled", true).html("Enviando...");
      },
      success: function () {
        $('#formCarrinho').empty();
        $('#formCarrinho').html('');
        $('#formCarrinhoSucesso').attr('style', '');
      },
      error: function () {
        $("#formCarrinho .btnSubmit").attr("disabled", true).html("Erro interno. Tente novamente mais tarde.");
      }
		})
	});
})(jQuery);
