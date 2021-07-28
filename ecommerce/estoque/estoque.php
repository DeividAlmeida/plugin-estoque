<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.17.1/dist/bootstrap-table.min.css">
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <div class="card">        
        <div class="card-body">                
            <table class="table  table-striped BootstrapTable" id="BootstrapTable"  data-id-field="id" data-maintain-meta-data="true"  data-show-refresh="true"  data-show-pagination-switch="true" data-detail-view="true"   data-detail-formatter="detailFormatter"  data-url="ecommerce/estoque/database.php" data-toggle="table" data-pagination="true" data-locale="pt-BR" data-cache="false" data-search="true" data-show-export="true" data-export-data-type="all" data-export-types="['csv', 'excel', 'pdf']" data-mobile-responsive="true" data-click-to-select="true" data-toolbar="#toolbar" data-show-columns="true" >       
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
                            <form id="editarCliente" action="?editarCliente&Clientes" method="POST"> 
                                <div class="modal-body no-b" id="no-b">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nome: </label>
                                                <input id="nome" class="form-control" name="nome">
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="savet" class="btn btn-primary" type="submit">
                                        <i class="icon icon-floppy-o"></i>Salvar Mudanças
                                    </button>  
                                </div>
                            </form>
                        </div>          
                    </div>            
                </div>
            </div>
        </div>
    </div>
