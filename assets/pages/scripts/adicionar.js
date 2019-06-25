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
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
jQuery(document).ready(function() {    
   Custom.init(); 
   
   jQuery("#cep").keyup(function() {
    if (jQuery(this).val().length >= '8') {
    	
        var cep = jQuery("#cep").val();
        //jQuery('.alerta_cep').html('Aguarde...').addClass('txt_verde');
        jQuery.ajax({
			type: "POST",
			url: 'inc/verifica_cep.php',
			data: {cep: cep},
            dataType : 'html',
			success: function( result )
			{
                data = result.split('%-%');
                if(data[0] != 1)
                {
                    jQuery('#endereco').val(data[0]);
                    jQuery('#endereco').focus();
                    jQuery('#bairro').val(data[1]);
                    jQuery('#bairro').focus();
                    jQuery('#cidade').val(data[2]);
                    jQuery('#cidade').focus();
                    jQuery('#estado').val(data[3]);
                    jQuery('#estado').focus();
                    jQuery('#numero').focus();
                    //jQuery('.alerta_cep').html('').addClass('txt_verde');
                }
                else
                {
                    jQuery('#endereco').focus();
                    //jQuery('.alerta_cep').html('').addClass('txt_verde');
                }
			},
            erro: function()
            {
                alert('erro');
            }
                
 		});
        
		
         }
    });  

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

    jQuery("#gui_nome_paciente").keyup(function() {
        if (jQuery(this).val().length >= '3') {
        	$("#div_aguarde_2_dados_paciente").show();
            $("#div_aguarde_2_dados_paciente_2").show();
  delay(function(){
            var gui_nome_paciente = jQuery("#gui_nome_paciente").val();
            jQuery("#id_paciente").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_paciente.php',
    			data: {gui_nome_paciente: gui_nome_paciente},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_paciente").html(dados);
                    $("#div_aguarde_2_dados_paciente").hide();
                    $("#div_aguarde_2_dados_paciente_2").hide();
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_paciente").html(dados);
                    $("#div_aguarde_2_dados_paciente").hide();
                    $("#div_aguarde_2_dados_paciente_2").hide();
                }
                
 		    });
            $("html, body").animate({ scrollTop: $('#portlet_paciente').offset().top - 100 }, 1000);
            }, 1000);
        
        }  
    });
    
        jQuery("#id_paciente").keyup(function() {
        if (jQuery(this).val().length >= '3') {
        	$("#div_aguarde_2_dados_paciente").show();
  delay(function(){
            var id_paciente = jQuery("#id_paciente").val();
            jQuery("#gui_nome_paciente").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_paciente.php',
    			data: {id_paciente: id_paciente},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_paciente").html(dados);
                    $("#div_aguarde_2_dados_paciente").hide();
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_paciente").html(dados);
                    $("#div_aguarde_2_dados_paciente").hide();
                }
                
 		    });
            }, 1500);
        
        }  
    });
    
    jQuery("#nome_profissional").keyup(function() {
        if (jQuery(this).val().length >= '3') {
        	$("#div_aguarde_2_dados_profissional").show();
  delay(function(){
            var gui_nome_profissional = jQuery("#nome_profissional").val();
            var sel_id_local_atendimento        = jQuery("#sel_id_local_atendimento").val();
            var verifica_grupo_procedimento     = jQuery("#verifica_grupo_procedimento").val();
            jQuery("#registro_profissional").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_profissional_guia.php',
    			data: {gui_nome_profissional: gui_nome_profissional, sel_id_local_atendimento: sel_id_local_atendimento, verifica_grupo_procedimento: verifica_grupo_procedimento},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_profissional").html(dados);
                    $("#div_aguarde_2_dados_profissional").hide();
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_profissional").html(dados);
                    $("#div_aguarde_2_dados_profissional").hide();
                }
                
 		    });
            }, 1000);
        
        }  
    });
    
    jQuery("#get_nome_procedimento").keyup(function() {
        if (jQuery(this).val().length >= '3') {
        	$("#div_aguarde_2_dados_procedimento").show();
  delay(function(){
            var gui_nome_procedimento = jQuery("#get_nome_procedimento").val();
            jQuery("#get_id_procedimento").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_procedimento.php',
    			data: {gui_nome_procedimento: gui_nome_procedimento},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_procedimento").html(dados);
                    $("#div_aguarde_2_dados_procedimento").hide();
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_procedimento").html(dados);
                    $("#div_aguarde_2_dados_procedimento").hide();
                }
                
 		    });
            }, 1000);
        
        }  
    });

        jQuery("#registro_profissional").keyup(function() {
        if (jQuery(this).val().length >= '3') {
        	$("#div_aguarde_2_dados_profissional").show();
  delay(function(){
            var registro_profissional       = jQuery("#registro_profissional").val();
            var sel_id_local_atendimento    = jQuery("#sel_id_local_atendimento").val();
            var verifica_grupo_procedimento = jQuery("#verifica_grupo_procedimento").val();
            jQuery("#nome_profissional").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_profissional_guia.php',
    			data: {registro_profissional: registro_profissional, sel_id_local_atendimento: sel_id_local_atendimento, verifica_grupo_procedimento: verifica_grupo_procedimento},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_profissional").html(dados);
                    $("#div_aguarde_2_dados_profissional").hide();
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_profissional").html(dados);
                    $("#div_aguarde_2_dados_profissional").hide();
                }
                
 		    });
            }, 1000);
        
        }  
    });
    
    
    jQuery("#get_id_procedimento").keyup(function() {
        if (jQuery(this).val().length >= '8') {
        	$("#div_aguarde_2_dados_procedimento").show();
  delay(function(){
            var get_id_procedimento = jQuery("#get_id_procedimento").val();
            jQuery("#get_nome_procedimento").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_procedimento.php',
    			data: {get_id_procedimento: get_id_procedimento},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_procedimento").html(dados);
                    $("#div_aguarde_2_dados_procedimento").hide();
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_procedimento").html(dados);
                    $("#div_aguarde_2_dados_procedimento").hide();
                }
                
 		    });
            }, 1000);
        
        }  
    });
    
    $( document ).on( 'focus', ':input', function(){
        $( this ).attr( 'autocomplete', 'new-password' );
    });

});

/***
Usage
***/
//Custom.doSomeStuff();

function alterna(){
{   
    if ($('input[name=tipopessoa]:checked').val() == 'PF')
    {
        $('#form_PF_PJ').load('inc/parceiro_PF.php');
        
    }
    //if($(this).val()==="PJ")
    if ($('input[name=tipopessoa]:checked').val() == 'PJ')
    {
         $('#form_PF_PJ').load('inc/parceiro_PJ.php'); 
    } 
    
    $('#box_pro').show();
}};



function add_produto_parceiro(){
{   
    if ($('#selecionar_produtos option:selected').val() != '')
    {
        var selecao = $('#selecionar_produtos option:selected').val();
        var selecao_nome = $('#selecionar_produtos option:selected').text();
        //$("#selecionar_produtos option[value='" + selecao + "']").remove();
        var nome_encode = encodeURI(selecao_nome);
        /*$('#form_add_produto').append($('#form_add_produto').load('inc/form_add_produto.php?id_produto=' + selecao + '&nome=' + nome_encode));*/
        $.get('inc/form_add_produto.php?id_produto=' + selecao + '&nome=' + nome_encode + '',function (dados) { $("#form_add_produto").append(dados);});

    }

}};

$( "#select_grupo_produto" ).change(function() {
    
  var valor                 = $('#select_grupo_produto option:selected').val();
  var slug                  = $('#select_grupo_produto option:selected').attr('data');
  var tipo_pagamento        = $('#select_grupo_produto option:selected').attr('ref');

  var dependente_titular    = $('#select_grupo_produto option:selected').attr('dependente_titular');
  var dependente_adicional  = $('#select_grupo_produto option:selected').attr('dependente_adicional');
  
  $("#div_aguarde_sel_plano").show();
  if(valor.length > '')
  {
    if(tipo_pagamento == 'S'){
        $('#dados_usuario_pedido').show();
        $('#select_user_pedido').removeAttr('disabled');
        $('#passo_p4').addClass('done');
        $('#inputs_hidden_sem_pagamentos').html('');  
        $.get('inc/sel_produto_info.php?id_grupo_produto=' + valor + '&data=' + slug,function (dados) { $("#sel_produto_info").html(dados);$('#info_add_produto_html').show();});
        
    }else{
        $('#exibe_form_cliente').val('nao');
        $('#sel_produto_info').html(' '); 
        $('#info_add_produto_html').hide();  
        $('#dados_usuario_pedido').hide();
        $('#select_user_pedido').attr('disabled', 'true');
        $('#inputs_hidden_sem_pagamentos').html('<input type="hidden" name="forma_pagamento" value="parcelado_cartao"/><input type="hidden" name="slug_produto[]" value="'+slug+'"/><input type="hidden" name="soma_produto" id="soma_produto" value="0.00"/><input type="hidden" name="total_geral_assistencia" value="0.00"/><input type="hidden" name="soma_produto_atual" value="0.00"/><input type="hidden" name="prazo" value="1"/><input type="hidden" name="metodo_pagamento" value="ON"/><input type="hidden" name="info_pagamento" value="N"/>');  
    }
     $.get('inc/sel_produto_add.php?id_grupo_produto=' + valor,function (dados) { $("#sel_produto_add").html(dados);});
    
    var exibe_form_cliente    = $('#exibe_form_cliente').val();
    
    if(exibe_form_cliente == 'nao'){
        $.get('inc/add_dados_cliente.php?info_pagamento='+tipo_pagamento+'&data='+slug+'&dependente_titular='+dependente_titular+'&dependente_adicional='+dependente_adicional,function (dados) { $("#dados_cliente").html(dados); 
        delay(function(){
            $("html, body").animate({ scrollTop: $('#dados_cliente').offset().top - 100 }, 1000);
            $("#nome").focus(); $("#div_aguarde_sel_plano").hide();  
        }, 1000);
        
        if(tipo_pagamento == 'S'){
        $('#exibe_form_cliente').val('sim');
        }
        });   
    }else{

        if(dependente_titular == 'S'){
            $("#principal_bt_add_dependente").show();
        }

        if(dependente_adicional == 'S'){
            $("#dependente_adicional").val('S');
        }else{
            $("#dependente_adicional").val('N');
        }

        delay(function(){
        $("html, body").animate({ scrollTop: $('#dados_cliente').offset().top - 100 }, 1000); $("#nome").focus();
        $("#div_aguarde_sel_plano").hide();
        }, 1000);
    }
    

  }
  else
  {
    $('#sel_produto_add').html(' ');  
    $('#dados_cliente').html(' ');  
    $('#sel_produto_info').html(' '); 
    $('#info_add_produto_html').hide();  
    $('#dados_usuario_pedido').hide();
    $("#div_aguarde_sel_plano").hide();
    
  }
  $("#bt_add_submit").removeAttr('disabled');
  $("#principal_contar_dependente_atual").val(0);
  $("#principal_contar_dependente").val(0);
});

$( "#select_parceiro_user" ).change(function() {
  var valor = $('#select_parceiro_user option:selected').val();

  if(valor.length > '')
  {
    $.get('inc/sel_filial_user.php?id_parceiro=' + valor ,function (dados) { $("#lista_filial").html(dados);});
  }
  
  
});

$( "#select_local_guia" ).change(function() {
  var valor = $('#select_local_guia option:selected').val();
    $('#div_aguarde_2_dados_procedimento').show();
    $('#div_aguarde_2_dados_procedimento_2').show();
  if(valor.length > '')
  {
    $.get('inc/gui_html_busca_procedimento_guia.php?id_local=' + valor ,function (dados) { $("#html_busca_procedmento_guia").html(dados); $('#div_aguarde_2_dados_procedimento').hide(); $('#div_aguarde_2_dados_procedimento_2').hide(); $("#html_tipo_procedimento").html(''); $("#portlet_profissional").hide(); $("#bt_submit_guia").hide(); $("#bt_avancar_passo4_guia").show(); $("#verifica_grupo_convenio").val(''); $("#contar_procedimentos_sel").val(0); $("#html_tipo_convenio_procedimento_paciente").html(''); $("html, body").animate({ scrollTop: $('#html_busca_procedmento_guia').offset().top }, 1000);});
    $.get('inc/gui_html_busca_info_local_guia.php?id_local=' + valor ,function (dados) { $("#gui_html_busca_info_local_guia").html(dados); });
    
    
  }else{
    $("#html_busca_procedmento_guia").html('');
    $('#div_aguarde_2_dados_procedimento').hide();
    $('#div_aguarde_2_dados_procedimento_2').hide();
  }
  
  
});

function remove_produto_parceiro(id, nome){
{   
    $('#box_add_produto_' + id).remove();
    
    /*$('#selecionar_produtos').append($('<option>', {
    value: id,
    text: nome
    }));*/
    
}};

function bt_submit_action(){
{   
    /*$("#bt_add_submit").attr('disabled', 'true');*/
    $('.div_aguarde').show();
    
}};
$( document ).on( "click", "#bt_remove_dependente", function() {
/*$( "#bt_remove_dependente" ).click(function() {*/
{   
    $("#radioMA").prop( "checked", false );
    $("#radioON").prop( "checked", false );
    $("#radioBO").prop( "checked", false );
    
    $("#div_parcela_parcelas_boleto").hide();
    $(".txt_parcela_boleto").hide();
    
    var id_dependente = $( this ).attr("data");
    $("#dados_dependente_" + id_dependente).remove();
    
    var contar_dependente = parseFloat($('#contar_dependente').val());
    var valor_dependente = parseFloat($('#valor_dependente').val());
    var quantidade_depenente    = parseFloat($('#contar_dependente').val());
    contar_dependente = parseFloat(contar_dependente - 1);
    $('#contar_dependente').val(contar_dependente);
    
    
    var valor_parcela           = parseFloat($('#soma_produto_atual').val());
    var soma_dependente_html    = parseFloat(valor_dependente * contar_dependente);
    soma_dependente_html = soma_dependente_html.toFixed(2);
    soma_dependente_html = soma_dependente_html.replace(".", ",");
    var calcular_dependente     = parseFloat((valor_dependente * contar_dependente) + valor_parcela);
    
    
    var prazo                   = $('#prazo option:selected').val();
    var calcular_soma_total     = parseInt(prazo) * parseFloat(calcular_dependente);

    calcular_soma_total = calcular_soma_total.toFixed(2);
    calcular_dependente = calcular_dependente.toFixed(2);
 
    
    //var calcular_desconto_input = parseFloat(valor_parcela - calcular_desconto);
    
    //calcular_desconto_input = calcular_desconto_input.toFixed(2);
    var calcular_desconto_html;
    calcular_desconto_html = calcular_dependente.replace(".", ",");
    var calcular_desconto_html  = calcular_dependente.replace(".", ",");
    
    var calcular_soma_total_html;
    calcular_soma_total_html = calcular_soma_total.replace(".", ",");
    var calcular_soma_total_html  = calcular_soma_total.replace(".", ",");
    
    //calcular_desconto = calcular_desconto_novo.replace(".", ",");
    
    $('.exibe_total_parcela').html(': R$ ' + calcular_desconto_html);
    
    $('#soma_produto').val(calcular_dependente);
    $('.exibe_dependente').html(" + R$ " + soma_dependente_html);
    $('#total_geral_assistencia').val(calcular_soma_total);
    $('.exibe_soma_total').html(" + R$ " + calcular_soma_total_html);
    $("#bt_add_submit").removeAttr('disabled');
    $("#bt_add_dependente").show();
    $("#info_add_produto_html").show();
}
});


//
/*$(document).keyup(function(e) {
    
    e = nodeName.replace(/\s{2,}/g, ' ');    

});*/

$(document).keydown(function(e) {
    var nodeName = e.target.nodeName.toLowerCase();
    e = nodeName.replace(/\s{2,}/g, ' ');    
    if (e.which === 8) {
        if ((nodeName === 'input' || e.target.type === 'text' || e.target.type === 'password') ||
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

/*$(function() {
    $('input').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});*/

jQuery("#estado_atendimento").change(function() {
    $('.div_aguarde').show();
if (jQuery(this).val().length >= '1') {
	
    var estado = jQuery("#estado_atendimento").val();
    //$("#estado").find('option').removeAttr("selected");
    //$('#estado option[value=' + estado + ']').attr('selected','selected');
    jQuery.ajax({
		type: "POST",
		url: 'inc/gui_lista_cidades_local.php',
		data: {estado: estado},
        dataType : 'html',
		success: function( result )
		{
            $("#listas_cidades_local").html(result);
            $('.div_aguarde').hide();
		},
        erro: function()
        {
            alert('erro');
            $('.div_aguarde').hide();
        }
            
	});
     }else{
        $('.div_aguarde').hide();
     }
});    

//VALIDADORES
function verificarCPF(c){ 
    var contar = c.length;
    var i;
    s = c;
    var c = s.substr(0,9);
    var dv = s.substr(9,2);
    var d1 = 0;
    var v = false;
   
    
    if(contar == 11)
    {
        if(s == '11111111111' || s == '22222222222' || s == '33333333333' || s == '44444444444' || s == '55555555555' || s == '66666666666' || s == '77777777777' || s == '88888888888' || s == '99999999999')
        {
            jQuery(".cpf").val("");
            alert('CPF inválido, digite novamente!');
        }
        
        for (i = 0; i < 9; i++){
            d1 += c.charAt(i)*(10-i);
        }
        if (d1 == 0){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery(".cpf").val("");
            alert('CPF inválido, digite novamente!');
            v = true;
            return false;
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9) d1 = 0;
        if (dv.charAt(0) != d1){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery(".cpf").val("");
            alert('CPF inválido, digite novamente!');
            v = true;
            return false;
        }
     
        d1 *= 2;
        for (i = 0; i < 9; i++){
            d1 += c.charAt(i)*(11-i);
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9) d1 = 0;
        if (dv.charAt(1) != d1){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery(".cpf").val("");
            alert('CPF inválido, digite novamente!');
            v = true;
            return false;
        }
        if (!v) {
            //alert(c + "nCPF Válido")
            jQuery(".msg_info_matricula").html('');
            jQuery(".msg_info_matricula").fadeOut('fast');
            
        }
    
    }
    
}

function gui_cancela_importacao(){
{   
    $("#id_cliente").val(0);
    $("#nome").removeAttr('readonly');
    $("#data_nasc").removeAttr('readonly');
    $("#cpf_paciente").removeAttr('readonly');
    $("#bt_cancela_importacao").hide();
    $('#convenio_paciente').val(5);
    $('.lista_id_imput_convenio').removeClass('exibe_convenio');
    $('.id_convenio_5').addClass('exibe_convenio');
}};


$( document ).on( "click", "#bt_add_paciente_rapido", function() {
{ 
    $("#div_aguarde_2_dados_paciente").show();
    var add_paciente    = jQuery("#add_paciente").val();
    var add_nascimento  = jQuery("#add_nascimento").val();
    var add_sexo        = jQuery("#add_sexo").val();
    var add_cidade       = jQuery("#add_cidade").val();
    var verifica = false;
    if(add_paciente.length >= 4 && add_nascimento.length >= 10 && add_sexo.length == 1)
    {
       verifica = true; 
    }else{
        $("#div_aguarde_2_dados_paciente").hide();
    }
    if(verifica == true){
        jQuery.ajax({
    		type: "POST",
    		url: 'inc/gui_add_paciente_rapido.php',
    		data: {
    		  add_paciente: add_paciente,
              add_nascimento: add_nascimento,
              add_sexo: add_sexo,
              add_cidade: add_cidade},
            dataType : 'html',
    		success: function(dados)
    		{
                if(dados.length >= 2){
                    $("#id_paciente").val(dados);
                    jQuery('#id_paciente').focus();
                    $("#gui_nome_paciente").val(add_paciente);
                    $("#data_nascimento").val(add_nascimento);
                    jQuery('#add_nascimento').focus();
                    $("#html_tipo_convenio_paciente").html('Convênio do paciente: PARTICULAR');
                    $("#id_convenio_paciente_sel").val('5');
                    $("#resultado_campo_gui_nome_paciente").html('');
                    $("#div_aguarde_2_dados_paciente").hide();
                }else{
                    alert("erro ao adicionar, tente novamente!")
                }
                
    		},
            error: function(){
                $("#resultado_campo_gui_nome_paciente").html(dados);
                $("#div_aguarde_2_dados_paciente").hide();
            }
            
        });
    }
}})

$( document ).on( "click", "#bt_add_profissional_rapido", function() {
{ 
    $("#div_aguarde_2_dados_profissional").show();
    var add_profissional            = jQuery("#add_nome_profissional").val();
    var add_conselho                = jQuery("#conselho").val();
    var add_registro_profissional   = jQuery("#add_registro_profissional").val();
    var add_tratamento_profissional = jQuery("#tratamento_profissional").val();
    var add_convenio                = jQuery("#verifica_grupo_convenio").val();
    var add_local_atendimento       = jQuery("#sel_id_local_atendimento").val();
    var verifica = false;
    if(add_profissional.length >= 4 && add_registro_profissional.length >= 2 && add_tratamento_profissional.length > 1 && add_conselho.length > 1)
    {
       verifica = true; 
    }else{
        $("#div_aguarde_2_dados_profissional").hide();
    }
    if(verifica == true){
        jQuery.ajax({
    		type: "POST",
    		url: 'inc/gui_add_profissional_rapido.php',
    		data: {
    		  add_profissional: add_profissional,
              add_conselho: add_conselho,
              add_registro_profissional: add_registro_profissional,
              add_tratamento_profissional: add_tratamento_profissional,
              add_convenio: add_convenio,
              add_local_atendimento: add_local_atendimento},
            dataType : 'html',
    		success: function(result)
    		{
    		    data = result.split('%-%');
                if(data[0] != 1)
                {  
                    $("#registro_profissional").val(data[1]);
                    jQuery('#registro_profissional').focus();
                    $("#nome_profissional").val(add_profissional);
                    $("#id_profissional_sel").val(data[0]);
                    $("#resultado_campo_gui_nome_profissional").html('');
                    $("#div_aguarde_2_dados_profissional").hide();
                }else{
                    alert("erro ao adicionar, tente novamente!")
                }
                
    		},
            error: function(){
                //$("#resultado_campo_gui_nome_paciente").html(dados);
                alert("erro ao adicionar, tente novamente!")
                $("#div_aguarde_2_dados_profissional").hide();
            }
            
        });
    }
}})

$( document ).on( "click", "#bt_avancar_passo2_guia", function() {
{ 
    $(this).hide();
    $("#portlet_paciente").show();
    jQuery('#gui_nome_paciente').focus();
    $("html, body").animate({ scrollTop: $('#portlet_paciente').offset().top - 100 }, 1000);
    
}});

$( document ).on( "click", "#bt_avancar_passo3_guia", function() {
{ 
    
    var id_paciente        = jQuery("#id_paciente").val();
    var gui_nome_paciente  = jQuery("#gui_nome_paciente").val();
    var data_nascimento    = jQuery("#data_nascimento").val();

    if(id_paciente.length >= 3 && gui_nome_paciente.length >= 4 && data_nascimento.length > 1)
    {
        $(this).hide();
        $("#portlet_procedimentos").show();
        $("html, body").animate({ scrollTop: $('#portlet_procedimentos').offset().top }, 1000);
    }else{
        alert('Selecione o paciente!');
    }
    
}});

$( document ).on( "click", "#bt_avancar_passo4_guia", function() {
{ 
    var select_local_guia        = jQuery("#select_local_guia").val();
    var add_procedimento        = jQuery("input[name='add_procedimento[]']").val();
    if(select_local_guia.length >= 1 && add_procedimento.length >= 1)
    {
        $(this).hide();
        $("#portlet_profissional").show();
        $("#bt_submit_guia").show();
        $("html, body").animate({ scrollTop: $('#portlet_profissional').offset().top }, 1000);
    }
}});

function remove_procedimento_local(id){
{   
    $('#grupo_procedimento_' + id).remove();
    
}};
$( document ).on( "click", "#principal_bt_add_especialidade", function() {
//$("#principal_bt_add_especialidade").click(function() {
{       
        $(".div_aguarde_especialidade").show();
        $("#principal_bt_add_especialidade").attr('disabled', 'true');
        var contar_especialidade_atual = $("#principal_contar_especialidade_atual").val();
        var contar_especialidade       = $("#principal_contar_especialidade").val();
        var select_especialidade       = $("#linha_especialidade_" + contar_especialidade_atual + " .select_especialidade option:selected").val();
        var rqe                        = $("#linha_especialidade_" + contar_especialidade_atual + " .rqe").val();
        
        if(select_especialidade == 'undefined' || select_especialidade != ''){
            
           //alert(data_nasc);
            $.get('inc/gui_add_especialidade_profissional.php?contar_especialidade=' + contar_especialidade_atual,function (dados) {
                $("#linha_especialidade_" + contar_especialidade_atual + " .select_especialidade").attr('readonly', true);
                $("#linha_especialidade_" + contar_especialidade_atual + " .rqe").attr('readonly', true);
                //$("#linha_especialidade_" + contar_especialidade_atual + " #principal_bt_add_especialidade").hide();
                $("#inserir_mais_especialidades").append(dados);
                contar_especialidade_atual = parseInt(contar_especialidade_atual) + 1;
                contar_especialidade       = parseInt(contar_especialidade) + 1;
                $("#principal_contar_especialidade_atual").val(contar_especialidade_atual);
                $("#principal_contar_especialidade").val(contar_especialidade);
                $(".div_aguarde_especialidade").hide();
                $("#principal_bt_add_especialidade").removeAttr('disabled');  
        });
        //$(".div_aguarde_especialidade").hide();
    }else{
        alert("Selecione a especialidade ou exclua a linha vazia!");
        $(".div_aguarde_especialidade").hide();
        $("#principal_bt_add_especialidade").removeAttr('disabled');
    }
}

});

$( document ).on("click", ".principal_bt_remove_especialidade", function() {
{       
        var contar_especialidade_atual = $("#principal_contar_especialidade_atual").val();
        var contar_especialidade       = $("#principal_contar_especialidade").val();
        var tipo_depen  = $("#linha_especialidade_" + contar_especialidade_atual + " .tipo_especialidade").val();
        var nome_depen  = $("#linha_especialidade_" + contar_especialidade_atual + " .nome_especialidade").val();
        var data_nasc   = $("#linha_especialidade_" + contar_especialidade_atual + " .data_nasc_especialidade").val();
        var id_especialidade = $( this ).attr("data");
        $("#linha_especialidade_" + id_especialidade).remove();
        
}

});


//inserir local atendimento profissional
//inserir local atendimento profissional
$( document ).on( "click", "#principal_bt_add_local", function() {
{       
        $(".div_aguarde_local").show();
        $("#principal_bt_add_local").attr('disabled', 'true');
        var contar_local_atual = $("#principal_contar_local_atual").val();
        var contar_local       = $("#principal_contar_local").val();
        var select_local       = $("#linha_local_" + contar_local_atual + " .select_local option:selected").val();
        var procedimento       = $("#linha_local_" + contar_local_atual + " .procedimento").val();
        
        if(select_local == 'undefined' || select_local != ''){
            
           //alert(data_nasc);
            $.get('inc/gui_add_local_profissional.php?contar_local=' + contar_local_atual,function (dados) {
                $("#linha_local_" + contar_local_atual + " .select_local").attr('readonly', true);
                $("#linha_local_" + contar_local_atual + " .rqe").attr('readonly', true);
                //$("#linha_local_" + contar_local_atual + " #principal_bt_add_local").hide();
                $("#inserir_mais_locals").append(dados);
                contar_local_atual = parseInt(contar_local_atual) + 1;
                contar_local       = parseInt(contar_local) + 1;
                $("#principal_contar_local_atual").val(contar_local_atual);
                $("#principal_contar_local").val(contar_local);
                $(".div_aguarde_local").hide();
                $("#principal_bt_add_local").removeAttr('disabled');  
        });
        //$(".div_aguarde_local").hide();
    }else{
        alert("Selecione a local ou exclua a linha vazia!");
        $(".div_aguarde_local").hide();
        $("#principal_bt_add_local").removeAttr('disabled');
    }
}

});

$( document ).on("click", ".principal_bt_remove_local", function() {
{       
        var contar_local_atual = $("#principal_contar_local_atual").val();
        var contar_local       = $("#principal_contar_local").val();
        var tipo_depen  = $("#linha_local_" + contar_local_atual + " .tipo_local").val();
        var nome_depen  = $("#linha_local_" + contar_local_atual + " .nome_local").val();
        var data_nasc   = $("#linha_local_" + contar_local_atual + " .data_nasc_local").val();
        var id_local = $( this ).attr("data");
        $("#linha_local_" + id_local).remove();
        
}
});

$( document ).on("change", ".select_local", function() {
    alert('1');
//$( ".select_local" ).change(function() {
    
    var contar_local_atual = $("#principal_contar_local_atual").val();
    var contar_local       = $("#principal_contar_local").val();
    var valor = $("#linha_local_" + contar_local_atual + " .select_local option:selected").val();
    $('.div_aguarde_local').show();
    if(valor.length > '')
    {
        $.get('inc/gui_add_procedimento_profissional.php?id_local=' + valor ,function (dados) { $("#linha_local_procedimento_" + contar_local_atual).html(dados); $('div_aguarde_local').hide();});
    //$.get('inc/gui_html_busca_info_local_guia.php?id_local=' + valor ,function (dados) { $("#gui_html_busca_info_local_guia").html(dados); });

    }else{
        $("#html_busca_procedmento_guia").html('');
        $('#div_aguarde_2_dados_procedimento').hide();
    }
  
  
});

$("form").keypress(function(e) {
    //Enter key
    var nodeName = e.target.nodeName.toLowerCase();
    if (e.which == 13) {
        if (nodeName === 'textarea') {
            // do nothing
        } else {
            //e.preventDefault();
            return false;
        }
      
    }
  });
/*var areYouReallySure = false;
function areYouSure() {
    if(allowPrompt){
        if (!areYouReallySure && true) {
            areYouReallySure = true;
            var confMessage = "Sair da p�gina?";
            return confMessage;
        }
    }else{
        allowPrompt = true;
    }
}

var allowPrompt = true;
window.onbeforeunload = areYouSure;*/

/*window.onbeforeunload = function() {
   var Ans = confirm("Sair da p�gina?");
   if(Ans==true)
       return true;
   else
       return false;
};*/

/*$(function (){
    $(window).on('beforeunload', function()
    {
        return '';
    })
    
});*/

