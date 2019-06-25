var item                = Cookies.get('item');
var id                  = Cookies.get('id');
var tipo                = Cookies.get('tipo');
var id_grupo_parceiro   = Cookies.get('id_grupo_parceiro');
var id_serv_get         = Cookies.get('id_serv_get');
var tipo_filtro         = Cookies.get('tipo_filtro');
var id_usuario_pagamento= Cookies.get('id_usuario_pagamento');
var TableDatatablesAjax = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }

    var handleRecords = function () {

        var grid = new Datatable();
        
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Aguarde...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                
                "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.

                "lengthMenu": [
                    [10, 20, 50, 100, 200, 500, 1000, 2000, 3000, 4000, 5000],
                    [10, 20, 50, 100, 200, 500, 1000, 2000, 3000, 4000, 5000] // change per page values here
                ],
                "pageLength": 20, // default record count per page
                "ajax": {
                    "url": "inc/table_ajax.php?item=" + item + "&id=" + id + '&tipo=' + tipo + '&id_grupo_parceiro=' + id_grupo_parceiro + '&id_serv_get=' + id_serv_get + '&tipo_filtro=' + tipo_filtro + '&id_usuario_pagamento=' + id_usuario_pagamento, // ajax source
                    "type": "POST" // request type                    
                },
                "order": false,
                 buttons: [
                    { extend: 'excel', className: 'salvar_excel' },
                    { extend: 'pdf', className: 'salvar_pdf' },
                    { extend: 'print', className: 'salvar_imprimir' }
                ],
            }
        });
        

        // handle datatable custom tools
        $('#datatable_ajax_tools > li > a.tool-action').on('click', function() {
            var action = $(this).attr('data-action');
            grid.getDataTable().button(action).trigger();
        });
        
        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action                      = $(".table-group-action-input", grid.getTableWrapper());
            var item                        = $("input.form-filter[name='item']", grid.getTableWrapper());
            var busca_dependente            = $("input.form-filter[name='busca_dependente']:checked", grid.getTableWrapper());
            var dt_nasc                     = $("input.form-filter[name='dt_nasc']", grid.getTableWrapper());
            var tipo_filtro                 = $("input.form-filter[name='tipo_filtro']", grid.getTableWrapper());
            var parceiro_user_filtro        = $("#select_parceiro_user_filtro option:selected", grid.getTableWrapper());
            var filial_user_filtro          = $("#select_filial option:selected", grid.getTableWrapper());
            var usuario_filtro              = $("#select_usuario_filtro option:selected", grid.getTableWrapper());
            var periodo                     = $("#periodo", grid.getTableWrapper());
            
            var select_profissao_filtro     = $("#select_profissao_filtro option:selected", grid.getTableWrapper());
            var select_especialidade_filtro = $("#select_especialidade_filtro option:selected", grid.getTableWrapper());
            var select_local_atend_filtro   = $("#select_local_atend_filtro option:selected", grid.getTableWrapper());
            var cidade_filtro_profissional  = $("input[name='cidade_filtro_profissional']", grid.getTableWrapper());
            
            if($("#todos_clientes_ativos").is(':checked')){
                var todos_clientes_ativos = 'S';
            }else{
                var todos_clientes_ativos = 'N';
            }
            
            if($("#somente_clientes_ativos").is(':checked')){
                var somente_clientes_ativos = 'S';
            }else{
                var somente_clientes_ativos = 'N';
            }
            
            if ((action.val() != "" || parceiro_user_filtro.val() != "" || filial_user_filtro.val() != "" || usuario_filtro.val() != "") && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionItem", item.val());
                grid.setAjaxParam("customActionItem_dep", busca_dependente.val());
                grid.setAjaxParam("customActionItem_dt_nasc", dt_nasc.val());
                grid.setAjaxParam("campofiltro", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.setAjaxParam("tipo_filtro", tipo_filtro.val());
                grid.setAjaxParam("parceiro_user_filtro", parceiro_user_filtro.val());
                grid.setAjaxParam("filial_user_filtro", filial_user_filtro.val());
                grid.setAjaxParam("usuario_filtro", usuario_filtro.val());
                grid.setAjaxParam("periodo", periodo.text());
                grid.setAjaxParam("select_profissao_filtro", select_profissao_filtro.val());
                grid.setAjaxParam("select_especialidade_filtro", select_especialidade_filtro.val());
                grid.setAjaxParam("select_local_atend_filtro", select_local_atend_filtro.val());
                grid.setAjaxParam("cidade_filtro_profissional", cidade_filtro_profissional.val());
                grid.setAjaxParam("todos_clientes_ativos", todos_clientes_ativos);
                grid.setAjaxParam("somente_clientes_ativos", somente_clientes_ativos);
                grid.getDataTable().ajax.reload();
                //grid.clearAjaxParams();
            } else if (action.val() == "") {
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Por favor, digite sua busca',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Sem registros',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });
        
        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.filter-cancel', function (e) {
            e.preventDefault();
            
            var action = $(".table-group-action-input").val('');
            
            grid.setAjaxParam("botaolimpar", "limpar");
            
            grid.setAjaxParam("id", grid.getSelectedRows());
            grid.getDataTable().ajax.reload();
            grid.clearAjaxParams();
            
        });
                
        
        grid.setAjaxParam("customActionType", "group_action");
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
        
    }

    return {

        //main function to initiate the module
        init: function () {
            
            initPickers();
            handleRecords();
        }

    };

}();


jQuery(document).ready(function() {
    TableDatatablesAjax.init();
    

});