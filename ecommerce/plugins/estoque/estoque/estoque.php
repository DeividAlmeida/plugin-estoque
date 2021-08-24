<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.17.1/dist/bootstrap-table.min.css">
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<div class="card">        
    <div class="card-body">                
        <table class="table  table-striped BootstrapTable" id="BootstrapTable"  data-id-field="id" data-maintain-meta-data="true"  data-show-refresh="true"  data-show-pagination-switch="true" data-detail-view="true"   data-detail-formatter="detailFormatter"  data-url="ecommerce/plugins/estoque/estoque/database.php" data-toggle="table" data-pagination="true" data-locale="pt-BR" data-cache="false" data-search="true" data-show-export="true" data-export-data-type="all" data-export-types="['csv', 'excel', 'pdf']" data-mobile-responsive="true" data-click-to-select="true" data-toolbar="#toolbar" data-show-columns="true" >       
            <thead >
                <tr >                        
                    <th scope="col" data-field="id" data-sortable="true" > <span style="font-weight: bold; font-size:16px;">ID<span></th>
                    <th scope="col" data-field="imagem" data-sortable="true" > <span style="font-weight: bold; font-size:16px;">Imagem<span></th>
                    <th scope="col" data-field="nome" data-sortable="true" ><span style="font-weight: bold; font-size:16px;">Nome<span></th>
                    <th scope="col" data-field="variacao" data-sortable="true" ><span style="font-weight: bold; font-size:16px; whidth:50px">Variação <span></th>
                </tr>
            </thead>
        </table>
        <div class="modal fade"  id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div  class="modal-dialog  modal-lg" role="document">
                <div  class="modal-content">
                    <div class="modal-content b-0">
                        <div class="modal-header r-0 bg-primary">
                            <h3 class="modal-title text-white text-white" id="exampleModalLabel">Adicionar variação do produto </h3>
                            <a href="#" data-dismiss="modal" aria-label="Close" class="paper-nav-toggle paper-nav-white active"><i></i></a>
                        </div>
                        <form id="addvariacao" method="POST" :action="'?addVariacao&ML-id='+ml"> 
                            <div class="modal-body no-b" id="no-b">
                                <div class="row" v-if="main.length>0">
                                    <div class="col-md-6" v-for="att ,i of main">
                                        <div class="form-group">
                                            <label>{{att.atributo}}: </label>
                                            <select class="custom-select termo " :name="att.atributo" required @change="tageando($event.target.innerText)">
                                                <option hidden selected value="">Escolha um termo</option>
                                                <option :value="termo.id" v-for="termo, id of main[i]"  v-show="id != 'atributo'">{{termo.nome}}</option>
                                             </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Estoque: </label>
                                            <input id="estoque" class="form-control variacao" name="estoque" type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Limiar de estoque baixo: </label>
                                            <input id="min" class="form-control variacao" name="min" type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ref: </label>
                                            <input id="ref" class="form-control variacao" :value="ref" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nome: </label>
                                            <input id="nome" class="form-control variacao" :value="nome" disabled>
                                        </div>
                                    </div>
                                    <input type="hidden" name="nome" :value="nome">
                                    <input type="hidden" name="ref" :value="ref">
                                </div>
                                <div v-else class="row justify-content-center">
                                    <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                                </div>
                            </div>
                            <div class="modal-footer" v-if="main.length>0">
                                <button id="savet" class="btn btn-primary" type="submit">
                                    <i class="icon icon-floppy-o"></i>Salvar
                                </button>  
                            </div>
                        </form>
                    </div>          
                </div>            
            </div>
        </div>
    </div>
</div>
<script>
let id
let nome
const vue  = new Vue({
    el: '#Modal',
    data:{
        main:[],
        ref:null,
        nome:null,
        ml:null
    },
    methods:{
        tageando: function(){
           let termo = document.getElementsByClassName('termo')
           let montarref
           let montarnome
           for(let i = 0; i< termo.length; i++){
             montarref += "-"+termo[i].value
             montarnome += " / "+termo[i].options[termo[i].selectedIndex].text;
           }
           this.ref= String(id+montarref).replaceAll('undefined','')
           this.nome= String(nome+montarnome).replaceAll('undefined','')
        }
    }
})
function lincar(i,j,w){
    vue.ml=w.getAttribute('ml')
    id = j
    nome = w.id
    vue.main=[]
    vue.ref = null
    vue.nome = null
    fetch('ecommerce/plugins/estoque/estoque/database.php').then(a=>a.json()).then(b=>{
        f = [b]
        f.filter(c=>{
            vue.main = c[i]
        }).then(c=>{
            new showDetails(700)
        })
    })
}

function estoque(e){
    fetch('?AtualizarVariacao='+e.id+'&valor='+e.value+'&ml='+e.getAttribute('ml')).then(a=>{
        swal("Salvo!", "Estoque atualizado com sucesso !", "success");
    })
}
function limiar(e){
    fetch('?Limiar='+e.id+'&valor='+e.value).then(a=>{
        swal("Salvo!", "Limiar atualizada com sucesso !", "success");
    })
}
</script>
