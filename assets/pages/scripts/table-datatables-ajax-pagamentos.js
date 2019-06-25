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
                
                //"bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.

                "lengthMenu": [
                    [-1],
                    ["Todos"] // change per page values here
                ],
                "pageLength": -1, // default record count per page
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
            var action = $(".table-group-action-input", grid.getTableWrapper());
            var item = $("input.form-filter[name='item']", grid.getTableWrapper());

            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionItem", item.val());
                grid.setAjaxParam("campofiltro", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
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