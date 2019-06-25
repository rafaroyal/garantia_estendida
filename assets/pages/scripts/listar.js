/**
Custom module for you to write your own javascript functions
**/
var Custom = function () {
    // private functions & variables
    var myFunc = function(text) {
        
    }

    // public functions
    return {

        //main function
        init: function () {
            //initialize here something.            
        },

        //some helper function
        doSomeStuff: function () {
            myFunc();
        }

    };

}();

jQuery(document).ready(function() {    
   Custom.init(); 
   
   $( document ).on( 'focus', ':input', function(){
        $( this ).attr( 'autocomplete', 'new-password' );
    });
});

/***
Usage
***/
//Custom.doSomeStuff();


$( "#grupo_parceiro" ).change(function() {
  var valor = $('#grupo_parceiro option:selected').val();
  if(valor.length > 0)
  {
    $.get('inc/sel_parceiro.php?id_grupo=' + valor ,function (dados) { $("#lista_parceiro").html(dados);});
    $('#passo_p3').addClass('done');  
  }
  else
  {
    $("#lista_parceiro").html(" ");
    $("#lista_filial").html(" ");
    $('#passo_p3').removeClass('done');  
  }

});

$('input[name=tipo_relatorio]:radio').click(function() {
  //var valor = $(this).val();
  $('#passo_p2').addClass('done');
  $('.md-radio-list').removeClass('has-error');
  
});


function exibir_filtro_clientes(){
{
    $(".div_aguarde").show();
    
    $.get('inc/sel_parceiro_filtro.php',function (dados) {$("#campo_data_filtro").show(); $("#grupo_filtro_clientes").html(dados); $("#campo_bt_busca").show(); $(".div_aguarde").hide();});
        
    
}};

function exibir_filtro_profissionais(){
{
    $(".div_aguarde").show();
    
    $.get('inc/sel_profissional_filtro.php',function (dados) {$("#grupo_filtro_clientes").html(dados); $("#campo_bt_busca").show(); $(".div_aguarde").hide();});
        
    
}};



function gerar_relatorio(){
{   
    var tipo                    = $("input[name=tipo_relatorio]").is(":checked");
    var grupo                   = $('#grupo_parceiro option:selected').val();
    var parceiro                = $('#select_parceiro option:selected').val();
    var filial                  = $('#select_filial option:selected').val();
    var grupo_produto           = $('#select_grupo_produto option:selected').val();
    var produto                 = $('#select_produto option:selected').val();
    var usuario_vendedor        = $('#select_usuario_vendedor option:selected').val();
    
    if($("#todos_clientes_ativos").is(':checked')){
        var todos_clientes_ativos = 'S';
    }else{
        var todos_clientes_ativos = 'N';
    }
    
    if($("#exibir_adicionais").is(':checked')){
        var exibir_adicionais = 'S';
    }else{
        var exibir_adicionais = 'N';
    }
    
    if($("#apenas_ativos").is(':checked')){
        var apenas_ativos = 'S';
    }else{
        var apenas_ativos = 'N';
    }
    
    if(tipo === false)
    {
        $('.md-radio-list').addClass('has-error');
    }
    
    if(grupo === '')
    {
        $("#lista_parceiro").html(" ");
        $('#passo_p3').removeClass('done');  
    }

    if(parceiro === 'todos')
    {
        usuario_vendedor        = $('#select_usuario_vendedor').val();
    }
    
    /*if(produto === 'undefined' &&  produto === '' )
    {
        
    }*/
    
    if(tipo === true && grupo !== '' && produto !== '' && produto !== 'undefined' && grupo_produto !== '' && grupo_produto !== 'undefined')
    {
        var periodo = $('#periodo').text();
        var pediodo_encode = encodeURI(periodo);
        var tipo        = $("input[name=tipo_relatorio]:checked").val();
        
        $(".div_aguarde").show();
        
        $.get('inc/gerar_relatorio.php?periodo=' + periodo + '&tipo=' + tipo + '&grupo=' + grupo + '&parceiro=' + parceiro + '&filial=' + filial + '&usuario_vendedor=' + usuario_vendedor + '&grupo_produto=' + grupo_produto + '&produto=' + produto + '&todos_clientes_ativos=' + todos_clientes_ativos + '&exibir_adicionais=' + exibir_adicionais + '&apenas_ativos=' + apenas_ativos,function (dados) { $("#resultado_relatorio").html(dados); $(".div_aguarde").hide();});
        
    }
}};

function gerar_fluxo_pagamento(){
{   
    //var parceiro            = $("input[name=tipo_relatorio]").is(":checked");
    
    if( $("input[name='lista_parceiros[]']").is(":checked") == true ){
        var lista_parceiros   = $("input[name='lista_parceiros[]']").serializeArray();
        //var lista_parceiros   = 'valor_variavel';
        alert('teste6');
        $.ajax({  
            type: "POST",
            url:  "inc/exportar_fluxo_pagamento.php",
            data: {
               lista_parceiros: lista_parceiros
            },
            success: function(dados){
               //alert(dados);
               window.open('http://YOUR_URL','_blank' );
            },
            erro: function()
            {
               alert('erro');
            } 
        });    
        
    }else{
        alert('Selecione no minimo um parceiro!');
    }
    
}};

function gerar_pagamento_cliente(){
{   
    var nome       = $('#nome').val();
    
    var cpf        = $('#cpf').val();
    var cpf_remove = cpf.replace(/[^\d]+/g,'');
    
    /*var cod_barras = $('#cod_barras').val();
    var cod_barras_remove = cod_barras.replace(/[^\d]+/g,'');*/
    
    var cod_aut = $('#cod_aut').val();
    //var cod_aut_remove = cod_aut.replace(/[^\d]+/g,'');
    
    var cod_baixa = $('#cod_baixa').val();
    //var cod_baixa_remove = cod_baixa.replace(/[^\d]+/g,'');
    
    var valiar = false;
    
    if(nome !== '')
    {
       if(nome.length > 2){
        valiar = true;
       } 
    }
    
    if(cpf_remove !== '')
    {
       if(cpf_remove.length === 11){
        valiar = true;
       }  
    }
    
    /*if(cod_barras_remove !== '')
    {
       if(cod_barras_remove.length === 20){
        valiar = true;
       } 
    }*/
    
    if(cod_aut !== '')
    {
       if(cod_aut.length > 2){
        valiar = true;
       } 
    }
    
    if(cod_baixa !== '')
    {
       if(cod_baixa.length > 2){
        valiar = true;
       } 
    }
    
    if($("#historico_pagamento").is(':checked')){
        var historico = 'S';
    }else{
        var historico = 'N';
    }
    
    if(valiar == true){
        $(".div_aguarde").show();
        $.get('inc/ver_pagamento_cliente.php?nome=' + nome + '&cpf=' + cpf + '&cod_aut=' + cod_aut + '&cod_baixa=' + cod_baixa + '&historico=' + historico,function (dados) { $("#conteudo_click_btn").html(dados); $(".div_aguarde").hide();});
    }
    
}};

$('#arquivo_retorno').change(function (event) {
        var file = this.files[0];

        if(file.name.length < 1) {
            alert("Por favor, selecionar o arquivo retorno!");
        }
        
    });

function atualizar_pagamentos(){
{   
    var validar_dados;
    var valida_tipo;
    var url;
    validar_dados   = false;
    valida_tipo     = false;
    $('#arquivo_retorno').change(function (event) {
        var file = this.files[0];

        if(file.name.length < 1) {
            alert("Por favor, selecionar o arquivo retorno!");
        }
        else{ 
            validar_dados = true;
        }
    
    });
    
    var x = document.getElementById("arquivo_retorno");
    var txt = "";
    if ('files' in x) {
        if (x.files.length == 0) {
             alert("Por favor, selecionar o arquivo retorno!");
             validar_dados = false;
        }else{
            validar_dados = true;
        }
    } 
 
    var tipo_arquivo = $("#tipo_retorno").val();
    
    if(tipo_arquivo.length == 0){
        alert('Selecione o tipo de retorno!');
        valida_tipo = false;
        
    }else{
        valida_tipo = true;
    }
    
    if(validar_dados == true && valida_tipo == true){
        var formData = new FormData();
        formData.append('arquivo_retorno', $('#arquivo_retorno')[0].files[0]);
        //var campos = $( "#form_dados" ).serialize();
        $(".div_aguarde").show();

        url = 'inc/atualizar_pagamentos_'+tipo_arquivo+'.php';

         $.ajax({
            url: url, // Url do lado server que vai receber o arquivo
            data:formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            type: 'POST',
            success: function (dados) {
                // utilizar o retorno
                $("#conteudo_click_btn").html(dados); $(".div_aguarde").hide();
            }
        });
        
    }
    
}};

$(document).keydown(function(e) {
    var nodeName = e.target.nodeName.toLowerCase();

    if (e.which === 8) {
        if ((nodeName === 'input' && e.target.type === 'text' || nodeName === 'input' && e.target.type === 'search' || nodeName === 'input' && e.target.type === 'password') ||
            nodeName === 'textarea' || nodeName === 'select') {
            // do nothing
        } else {
            e.preventDefault();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
  
  document.addEventListener('keydown', function(e){      
       var input = e.target;
       var val = input.value;
       var end = input.selectionEnd;
       if(e.keyCode == 32 && (val[end - 1] == " " || val[end] == " ")) {
         e.preventDefault();
         return false;
      }      
    });
});

$(function() {
    $('input').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});

jQuery(document).ready(function() {
    jQuery('#cod_barras').focus();
});

$(document).ready(function(){
    $('#home_tabela_procedimentos').DataTable();
});
        
        
$( "#select_local_guia" ).change(function() {
  var valor = $('#select_local_guia option:selected').val();
    $('#div_aguarde_2_dados_procedimento').show();
  if(valor.length > '')
  {
    $.get('inc/gui_html_busca_procedimento_buscar.php?id_local=' + valor ,function (dados) { $("#html_busca_procedmento_guia").html(dados); $('#div_aguarde_2_dados_procedimento').hide(); $("#html_tipo_procedimento").html(''); $("html, body").animate({ scrollTop: $('#html_busca_procedmento_guia').offset().top }, 1000);});
    $.get('inc/gui_html_busca_info_local_guia.php?id_local=' + valor ,function (dados) { $("#gui_html_busca_info_local_guia").html(dados); });
    
    
  }else{
    $("#html_busca_procedmento_guia").html('');
    $('#div_aguarde_2_dados_procedimento').hide();
  } 
});

$( "input[type=radio][name=tipo_busca]" ).change(function() {
  //alert(this.value);
  //var valor = $('#select_local_guia option:selected').val();
  $('#lista_por_local').hide();
    $('#div_aguarde_2_dados_procedimento').show();
  if(this.value == 'procedimento')
  {
    $.get('inc/gui_html_busca_procedimento_buscar_geral.php',function (dados) { $("#html_busca_procedmento_guia").html(dados); $('#div_aguarde_2_dados_procedimento').hide(); $("#html_tipo_procedimento").html(''); 
    $("#get_nome_procedimento_guia").focus();
    $("html, body").animate({ scrollTop: $('#div_campos_busca_procedimentos').offset().top - 100}, 1000);});
    //$.get('inc/gui_html_busca_info_local_guia.php?id_local=' + valor ,function (dados) { $("#gui_html_busca_info_local_guia").html(dados); });
    
    
  }else{
    $("#html_busca_procedmento_guia").html('');
    $('#lista_por_local').show();
    $('#div_aguarde_2_dados_procedimento').hide();
    $("html, body").animate({ scrollTop: $('#div_campos_busca_procedimentos').offset().top - 100 }, 1000);
  }
  
});

$( "input[type=radio][name=gui_tipo_busca_relatorio]" ).change(function() {
  var valor = this.value;
  $('#html_busca_relatorio').html('');  
  //$('#html_busca_relatorio').hide();
  $('#div_aguarde_1_relatorio').show();
  
  $.get('inc/gui_html_relatorio.php?valor='+valor,function (dados) { $("#html_busca_relatorio").html(dados); $('#div_aguarde_1_relatorio').hide(); $("html, body").animate({ scrollTop: $('#html_busca_relatorio').offset().top }, 1000);});
    //$.get('inc/gui_html_busca_info_local_guia.php?id_local=' + valor ,function (dados) { $("#gui_html_busca_info_local_guia").html(dados); });

});

$('#ajax').on('loaded.bs.modal', function (e) {
  $('#ajax').removeData();
});

$(document).ready(function(){
  $('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
  });
});

$(document).ready(function(){
  $('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
    $("#" + $(this).attr("id") + " .modal-content").empty();
    $("#" + $(this).attr("id") + " .modal-content").append("Aguarde...");
  });
});

function gui_filtro_relatorio_local_atendimento(){
{   
    var tipo                    = 'local_atendimento';
    var id_local_atendimento    = $('#select_local_guia_relatorio option:selected').val();
    var id_profissional         = $('#filtro_busca_profissional option:selected').val();
    var select_buscar_por       = $('#select_buscar_por option:selected').val();
    
    if($("#todos_guias_faturar").is(':checked')){
        var todos_guias_faturar = 'S';
    }else{
        var todos_guias_faturar = 'N';
    }
    
    if($("#apenas_emissao").is(':checked')){
        var apenas_emissao = 'S';
    }else{
        var apenas_emissao = 'N';
    }
    
    if(id_local_atendimento !== '')
    {
        var periodo = $('#periodo').text();
        var pediodo_encode = encodeURI(periodo);
        
        $(".div_aguarde_2").show();
        $.get('inc/gui_filtro_relatorio.php?periodo=' + periodo + '&tipo=' + tipo + '&id_local_atendimento=' + id_local_atendimento + '&apenas_emissao=' + apenas_emissao + '&id_profissional=' + id_profissional + '&select_buscar_por=' + select_buscar_por + '&todos_guias_faturar=' + todos_guias_faturar,function (dados) { $(".div_aguarde_2").hide(); $("#resultado_relatorio").html(dados);});
        
    }
}};

function gui_filtro_relatorio_faturamento(){
{   
    var tipo                    = 'local_atendimento';
    var id_local_atendimento    = $('#select_local_guia_relatorio_faturamento option:selected').val();
    var id_profissional         = $('#filtro_busca_profissional option:selected').val();
    
    if($("#todos_clientes_ativos").is(':checked')){
        var todos_clientes_ativos = 'S';
    }else{
        var todos_clientes_ativos = 'N';
    }
    
    if(id_local_atendimento !== '')
    {
        var periodo = $('#periodo').text();
        var pediodo_encode = encodeURI(periodo);
        
        $(".div_aguarde_2").show();
        $.get('inc/gui_filtro_relatorio_faturamento.php?periodo=' + periodo + '&tipo=' + tipo + '&id_local_atendimento=' + id_local_atendimento + '&id_profissional=' + id_profissional + '&todos_clientes_ativos=' + todos_clientes_ativos,function (dados) { $(".div_aguarde_2").hide(); $("#resultado_relatorio").html(dados);});
        
    }
}};

$( "#select_local_guia_relatorio" ).change(function() {
  var valor = $('#select_local_guia_relatorio option:selected').val();
    $('#div_aguarde_2_dados_procedimento').show();
   
  if(valor.length > '')
  {
    $.get('inc/gui_filtro_html_busca_profissional.php?id_local=' + valor ,function (dados) { $("#filtro_html_busca_profissional").html(dados); $('#div_aguarde_2_dados_procedimento').hide();});
    
  }else{
    $("#filtro_html_busca_profissional").html('');
    $('#div_aguarde_2_dados_procedimento').hide();

  }
  
  
});

$( "#select_local_guia_relatorio_faturamento" ).change(function() {
  var valor = $('#select_local_guia_relatorio_faturamento option:selected').val();
  var periodo = $('#periodo').text();
  var pediodo_encode = encodeURI(periodo);
    $('#div_aguarde_2_dados_procedimento').show();
   
  if(valor.length > '')
  {
     $.get('inc/gui_filtro_html_busca_profissional.php?id_local=' + valor ,function (dados) { $("#filtro_html_busca_profissional").html(dados); $('#div_aguarde_2_dados_procedimento').hide();});
     
    //$.get('inc/gui_filtro_html_busca_faruramento_parceiro.php?periodo=' + periodo + '&id_local=' + valor ,function (dados) { $("#filtro_html_busca_faruramento_parceiro").html(dados); $('#div_aguarde_2_dados_procedimento').hide();});
    
  }else{
    $("#filtro_html_busca_faruramento_parceiro").html('');
    $("#filtro_html_busca_profissional").html('');
    $('#div_aguarde_2_dados_procedimento').hide();

  }

});

$( "#select_parceiro_user_filtro" ).change(function() {
  var valor = $('#select_parceiro_user_filtro option:selected').val();
  $("#lista_filial").html('');  
  $("#lista_user").html('');
  if(valor > 0)
  {
    $.get('inc/sel_filial_filtro.php?id_parceiro=' + valor ,function (dados) { $("#lista_filial").html(dados);});
    $.get('inc/sel_user_filtro.php?id_parceiro=' + valor ,function (dados) { $("#lista_user").html(dados);});
  }
  
  
});

function click_nome_usuario_pagamentos(id_usuario_recebimento, nome){
{
    $(".div_aguarde").show();
    $("#sample_1_filter input[type=search]").val(id_usuario_recebimento+" "+nome).keyup();
    $("#bt_return_lista_user_"+id_usuario_recebimento).show();
    $.get('inc/ver_pagamentos_usuario.php?item=pagamentos_usuario&id_usuario_pagamento='+id_usuario_recebimento,function (dados) { $("#html_busca_pagamentos").html(dados); $('.div_aguarde').hide(); $("html, body").animate({ scrollTop: $('#html_busca_pagamentos').offset().top }, 1000);});
    
    
}};

function click_return_lista_user(id_usuario_recebimento){
{
    $("#sample_1_filter input[type=search]").val("").keyup();
    $("#bt_return_lista_user_"+id_usuario_recebimento).hide();
    $("#html_busca_pagamentos").html('');
}};
