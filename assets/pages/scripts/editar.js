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
                        jQuery('#lista_cidades').html('<input type="text" class="form-control" id="cidade" name="cidade" readonly=""/><label for="cidade">Cidade</label>');
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
   
   jQuery("#estado").change(function() {
        if (jQuery(this).val().length >= '1') {
        	
            var estado = jQuery("#estado").val();
            //$("#estado").find('option').removeAttr("selected");
            //$('#estado option[value=' + estado + ']').attr('selected','selected');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/localiza_cidade.php',
    			data: {estado: estado},
                dataType : 'html',
    			success: function( result )
    			{
                    $("#lista_cidades").html(result);
    			},
                erro: function()
                {
                    alert('erro');
                }
                    
     		});
            
 			
             }
     });    
     
    /*jQuery("#cidade").change(function() {
        
        if (jQuery(this).val().length >= '1') {
        	var id_cidade = jQuery("#cidade option:selected").attr('rel');
            $("#cidade").find('option').removeAttr("selected");
            
            $('#cidade option[rel=' + id_cidade + ']').attr('selected');
            
            $("#id_cidade").val(id_cidade);
             }
     }); */
     
     
        function retira_acentos(palavra) { 
        com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ'; 
        sem_acento = 'aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC'; 
        nova=''; 
        for(i=0;i<palavra.length;i++) { 
            if (com_acento.search(palavra.substr(i,1))>=0) { 
                nova+=sem_acento.substr(com_acento.search(palavra.substr(i,1)),1); 
            } 
            else { 
                nova+=palavra.substr(i,1); 
            } 
        } 
        return nova; 
        }
        
        
        $('#buscar_nome_cidade').keyup(function(){
            buscar = $(this).val();
                    var buscar_sem_acento = retira_acentos(buscar);
                     $(this).val(buscar_sem_acento);
                      //novo_buscar = $(this).val();
                     buscar_hash = $(this).val().toLowerCase();
                     buscar_hash_tratado = buscar_hash.replace(" ", "_");
                     $('#tabela_lista_cidades .md-checkbox').removeClass('resaltar');
                     if(jQuery.trim(buscar) != ''){
                       $("#tabela_lista_cidades #" + buscar_hash_tratado + "").addClass('resaltar');
                       $("#bt_buscar_realce_cidades").attr("href", "#" + buscar_hash_tratado)
                       
                     }
            });
  
  var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();          
                
    jQuery("#get_nome_procedimento").keyup(function() {
        if (jQuery(this).val().length >= '4') {
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
    
    jQuery("#nome_profissional").keyup(function() {
        if (jQuery(this).val().length >= '3') {
        	$("#div_aguarde_2_dados_profissional").show();
  delay(function(){
            var gui_nome_profissional           = jQuery("#nome_profissional").val();
            var sel_id_local_atendimento        = jQuery("#sel_id_local_atendimento").val();
            var verifica_grupo_procedimento     = 0;
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
    
  jQuery("#registro_profissional").keyup(function() {
        if (jQuery(this).val().length >= '3') {
        	$("#div_aguarde_2_dados_profissional").show();
  delay(function(){
            var registro_profissional       = jQuery("#registro_profissional").val();
            var sel_id_local_atendimento    = jQuery("#sel_id_local_atendimento").val();
            var verifica_grupo_procedimento = 0;
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
    
    $( document ).on( 'focus', ':input', function(){
        $( this ).attr( 'autocomplete', 'new-password' );
    });
});

//sel_metodo_pagamento
function sel_metodo_pagamento(){
{   
    $("#html_emissao_boleto").hide();
    var id_parceiro     = $('#id_parceiro').val();
    if ($('input[name=metodo_pagamento]:checked').val() == 'MA')
    {
        $.get('inc/html_parcelas_cartao.php?id_parceiro='+id_parceiro,function (dados) { $('.txt_metodo_pagamento').html(dados);});
        //$('.txt_metodo_pagamento').html("<strong>Máquina de Cartão</strong>. Antes de registrar venda, confirme o pagamento na máquina de cartão.<div class='col-md-5'><div class='form-group form-md-line-input form-md-floating-label'><div class='input-group input-group-sm'><div class='input-group-control'><input type='text' name='comprovante_maquina' class='form-control' id='comprovante_maquina' value=''/><label for='comprovante_maquina'>AUT. Comprovante</label><span class='help-block'>Somente números..</span></div><span class='input-group-btn btn-right'><a class='btn red btn-outline sbold' data-toggle='modal' href='#responsive'><i class='fa fa-info'></i></a></span></div></div></div>");
        
    }
    
    if ($('input[name=metodo_pagamento]:checked').val() == 'ON')
    {
        $('.txt_metodo_pagamento').html('<strong>Checkout Online</strong>. Você será redirecionado para página de Checkout da CIELO.');
        
    }
    
    if ($('input[name=metodo_pagamento]:checked').val() == 'BO')
    {
        $.get('inc/html_parcelas_boleto.php?id_parceiro='+id_parceiro,function (dados) { $('.txt_metodo_pagamento').html(dados);});
        
        /*$('.txt_metodo_pagamento').html("<strong>Boleto Parcelado</strong>. Após Finalizar cadastro, solicitar boleto(s) no Banco emissor.<input type=\"hidden\" name=\"valor_parcela_boleto\" id=\"valor_parcela_boleto\" value=\"\"/><div class='col-md-5' id=\"div_parcela_parcelas_boleto\"><div class='form-group form-md-line-input form-md-floating-label'><select class='form-control' id='parcela_parcelas_boleto' name='parcela_parcelas_boleto'><option value=''></option><option value='2'>2x</option><option value='3'>3x</option><option value='4'>4x</option><option value='5'>5x</option><option value='6'>6x</option><option value='7'>7x</option><option value='8'>8x</option><option value='9'>9x</option><option value='10'>10x</option><option value='11'>11x</option><option value='12'>12x</option></select><label for='parcela_parcelas_boleto'>Parcelar em:</label></div></div><div class='col-md-5 txt_parcela_boleto' style='margin-top: 30px;'></div>");*/
        //$("#parcela_parcelas_boleto").focus();
        $("#html_emissao_boleto").show();
    }
    
    $('#box_pro').show();
    
    $("#bt_add_submit").show();
  
}};

function f_permitir_entrada(){
{   
    if ($('input[name=permitir_entrada]:checked').val() == 'S')
    {
        $('#bloco_entrada').show();
        $('#input_status_entrada').val('S');
    }
   
    if ($('input[name=permitir_entrada]:checked').val() == 'N')
    {
         $('#bloco_entrada').hide(); 
         $('#input_status_entrada').val('N');
    } 
    
}}; 

/*$( document ).on( "change", "#parcela_parcelas_boleto", function() {

  var prazo                       = $('#prazo option:selected').val();
  var parcela_parcelas_boleto     = $('#parcela_parcelas_boleto option:selected').val();
  var total_geral_assistencia     = $('#total_geral_assistencia').val();
  
  if(prazo != '' && typeof(prazo) !== 'undefined' && parcela_parcelas_boleto != '' && typeof(parcela_parcelas_boleto) !== 'undefined')
  {
    var soma_total_parcela = parseFloat(total_geral_assistencia) / parseFloat(parcela_parcelas_boleto);
    soma_total_parcela = soma_total_parcela.toFixed(2);
    
    $('#valor_parcela_boleto').val(soma_total_parcela);
    soma_total_parcela = soma_total_parcela.replace(".", ",");
    $('.txt_parcela_boleto').html(' de R$ ' + soma_total_parcela);
    
    $('.exibe_total_parcela').html('-');
    
  }
  else
  {
    $('#valor_parcela_boleto').val('');
    $('.txt_parcela_boleto').html(' de R$ -');
  }

});*/

$( document ).on( "change", "#parcela_parcelas_boleto", function() {

  var prazo                       = $('#prazo option:selected').val();
  var parcela_parcelas_boleto     = $('#parcela_parcelas_boleto option:selected').val();
  //var valor_parcela               = $('#soma_produto').val();
  var total_geral_assistencia     = $('#total_geral_assistencia').val();
  
  
  if(prazo != '' && typeof(prazo) !== 'undefined' && parcela_parcelas_boleto != '' && typeof(parcela_parcelas_boleto) !== 'undefined')
  {
    if ($('#input_status_entrada').val() == 'S')
    {
        var soma_total_parcela = parseFloat(total_geral_assistencia) / parseFloat(parcela_parcelas_boleto);
        soma_total_parcela = soma_total_parcela.toFixed(2);
        
        $('#valor_parcela_boleto').val(soma_total_parcela);
        
        soma_total_parcela = soma_total_parcela.replace(".", ",");
        var valor_entrada = $('#valor_entrada').val();
        var tipo_valor_entrada = $('#tipo_calculo_entrada').val();
        
        $(".div_aguarde_2").show();
        $("#bt_add_submit").hide();
        if(tipo_valor_entrada == 'auto'){
            var parcela_menos_um =  parseFloat(parcela_parcelas_boleto) - 1;
            var valor_entrada_auto        = $("#valor_entrada_auto").val();
            valor_entrada_auto = valor_entrada_auto.replace(".", ",");
            var soma_total_parcela_calc   = soma_total_parcela.replace(",", ".");
            var soma_valor_entrada        = parseFloat(soma_total_parcela_calc) + parseFloat(valor_entrada_auto);
            soma_valor_entrada            = soma_valor_entrada.toFixed(2);
            soma_valor_entrada            = soma_valor_entrada.replace(".", ",");
            //var valor_entrada_auto = $('#valor_entrada_auto').val();
            $.get('inc/html_parcelas_datas.php?valor_entrada='+soma_valor_entrada+'&soma_total_parcela='+soma_total_parcela+'&parcela_menos_um='+parcela_menos_um,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 

          
          //valor_entrada_verifica = valor_entrada_verifica.replace(",", ".");
          //var valor_entrada             = parseFloat($("#valor_entrada").val());
          //$("#valor_entrada").val(valor_entrada_verifica);
          $("#valor_entrada").val(soma_valor_entrada);
        }else if(tipo_valor_entrada == 'manual'){

            var parcela_menos_um =  parseFloat(parcela_parcelas_boleto);
             $.get('inc/html_parcelas_datas.php?valor_entrada='+valor_entrada+'&soma_total_parcela='+soma_total_parcela+'&parcela_menos_um='+parcela_menos_um,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 
        
        }else{

            $.get('inc/html_parcelas_uma_data.php?soma_total_parcela='+soma_total_parcela,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 
        }

        $('.exibe_total_parcela').html('-');
        
    }else{

        $(".div_aguarde_2").show();
        $("#bt_add_submit").hide();
        //var permite_desconto = $('input[name=permitir_entrada]:checked').val();
        var soma_total_parcela = parseFloat(total_geral_assistencia) / parseFloat(parcela_parcelas_boleto);
        soma_total_parcela = soma_total_parcela.toFixed(2);
        
        $('#valor_parcela_boleto').val(soma_total_parcela);
        soma_total_parcela = soma_total_parcela.replace(".", ",");
         $.get('inc/html_parcelas_uma_data.php?soma_total_parcela='+soma_total_parcela,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 
        
        $('.exibe_total_parcela').html('-');
        
    }
    
    
  }
  else
  {
    //$('.exibe_soma_total').html('-');
    //$('#total_geral_assistencia').val('');
    $('#valor_parcela_boleto').val('');
    $('.txt_parcela_boleto').html(' de R$ -');
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
            jQuery("#cpf").val("");
            alert('CPF inválido, digite novamente!');
        }
        
        for (i = 0; i < 9; i++){
            d1 += c.charAt(i)*(10-i);
        }
        if (d1 == 0){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery("#cpf").val("");
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
            jQuery("#cpf").val("");
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
            jQuery("#cpf").val("");
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

//VALIDADORES
function verificarIDADE(d){ 

    var contar = d.replace(/[^0-9]/g,'');
    var contar_int = contar.length;
    if(contar_int == 8)
    {
        var nasc_dia = contar.substr(0,2);
        var nasc_mes = contar.substr(2,2);
        var nasc_ano = contar.substr(4,4);
    
    //variaveis do dia/mes/ano de hj	
    	data = new Date();
    	dia = data.getDate();
    	mes = data.getMonth()+1;
    	ano = data.getFullYear();
    	
    	totalDias = dia - nasc_dia; 
    	resMes = mes - nasc_mes;
    	totalMes = resMes * 30; 
    	resDiasDoMes = totalMes - totalDias;
    	resAno = ano - nasc_ano;
    	totalAno = resAno * 365;
    	total = resDiasDoMes + totalAno;
        
        idade = total / 365;
        idade = Math.floor(idade);
    	
    	if(idade > 69 || idade < 18)
        {
            $("#data_nasc").val(" ");
            alert('Erro! Idade do cliente superior à 70 anos ou inferior à 18 anos');             
        }
    
    }
    
}

function bt_submit_action(){
{   
    /*$("#bt_add_submit").attr('disabled', 'true');*/
    $('.div_aguarde').show();
    
}};
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

function add_filial_parceiro(){
{   
    var cnpj_filial     = $('#cnpj_filial_add_campo').val();
    var nome_filial     = $('#nome_filial_add_campo').val();
    var cidade_filial   = $('#cidade_filial_add_campo').val();
    var estado_filial   = $('#estado_filial_add_campo').val();
    var fone_filial     = $('#fone_filial_add_campo').val();
    var id_box          = $('#contar_filiais').val();
    var id_parceiro     = $('input[name=id]').val();
    
    if ($('#nome_filial_add_campo').val() != '' && $('#cidade_filial_add_campo').val() != '' && $('#estado_filial__add_campo').val() != '')
    {
        var cnpj_encode     = encodeURI(cnpj_filial);
        var nome_encode     = encodeURI(nome_filial);
        var cidade_encode   = encodeURI(cidade_filial);
        var estado_encode   = encodeURI(estado_filial);
        var fone_encode     = encodeURI(fone_filial);
        /*$('#form_add_produto').append($('#form_add_produto').load('inc/form_add_produto.php?id_produto=' + selecao + '&nome=' + nome_encode));*/
        $.get('inc/form_add_filial.php?cnpj_filial='+cnpj_encode+'&nome_filial=' + nome_encode + '&cidade_filial=' + cidade_encode + '&estado_filial=' + estado_encode + '&fone_filial='+fone_encode+'&id_box=' + id_box + '&id_parceiro=' + id_parceiro,function (dados) { $("#box_filial").append(dados);
            $('#cnpj_filial_add_campo').val('');
            $('#nome_filial_add_campo').val('');
            $('#cidade_filial_add_campo').val('');
            $('#estado_filial_add_campo').val('');
            $('#fone_filial_add_campo').val('');
            
            var soma_filial = parseInt(id_box) + 1;
            $('#contar_filiais').val(soma_filial);
        });
        
        
        
    }

}};

function remove_filial_parceiro(id_box, id_filial, id_parceiro){
{   
    $.get('excluir.php?item=parceiros_filiais&id_filial='+id_filial+'&id_parceiro='+id_parceiro,function (dados) {
        if(dados == 'ok'){
            $('#div_add_filial_' + id_box).remove();
            $('#remove_filiais').append('<input type="hidden" name="remove_filial[]" id="remove_filial" value="' + id_filial + '"/>');
        }
    });
    
    
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

function remove_produto_parceiro(id, nome){
{   
    $('#box_remove_produto_' + id).remove();
    
    $('#remove_produtos').append('<input type="hidden" name="remove_produto[]" id="remove_produto" value="' + id + '"/>')
    
    /*$('#selecionar_produtos').append($('<option>', {
    value: id,
    text: nome
    }));*/
    
}};

$(document).keydown(function(e) {
    var nodeName = e.target.nodeName.toLowerCase();

    if (e.which === 8) {
        if ((nodeName === 'input' && e.target.type === 'text') ||
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

$( "#forma_pagamento" ).change(function() {
  $("#bt_add_submit").hide();  
  $('#bt_calcular_pagamento').hide();
  $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
  var valor         = $('#forma_pagamento option:selected').val();
  $('#select_prazo').html('');
  
  $('.html_info_pagamento').show();
  $('.html_forma_pagamento').hide();
  //$('#valor_entrada').val('');
  $('#desconto').val('');
  $('#prazo').val('');
  $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
  $("#desconto").removeAttr('readonly');  
  $("#tipo_desconto").removeAttr('readonly');
    $("#valor_entrada").removeAttr('readonly');  
  if(valor == 'recorrente_cartao' || valor == 'fidelidade' || valor == 'entrada_recorrente_cartao') 
  {
    
    $('.exibe_soma_total').html('-');
    $('#total_geral_assistencia').val('');
    $('.exibe_prazo').html('-');
    $('.prazo_periodo').hide();
    $('.exibe_inicio').html(' ');
    $('.exibe_termino').html(' ');
    
    $("#radioMA").prop( "checked", false );
    $("#radioON").prop( "checked", false );
    $("#radioBO").prop( "checked", false );
    
    $("#div_parcela_parcelas_boleto").hide();
    
    
    $("#div_radioMA").show();
    $("#div_radioON").show();
    $("#div_radioBO").hide();
    $('#bt_calcular_pagamento').show();
    $("#bt_calcular_pagamento").removeAttr('disabled');  
        
  }
  else
  {
    $("#radioMA").prop( "checked", false );
    $("#radioON").prop( "checked", false );
    $("#radioBO").prop( "checked", false );
    $("#div_parcela_parcelas_boleto").hide();
    
    if(valor == 'entrada_parcelado_boleto') 
    {
        $("#div_radioMA").hide();
        $("#div_radioON").hide();
        $("#div_radioBO").show();
    }else{
        $("#div_radioMA").show();
        $("#div_radioON").show();
        if(valor == 'avista') 
        {
            $("#div_radioBO").show();
        }else{
            $("#div_radioBO").hide();
        }
    }
    
    $('.exibe_soma_total').html('-');
    $('#total_geral_assistencia').val('');
    $('.exibe_prazo').html('-');
    $('.prazo_periodo').hide();
    $('.exibe_inicio').html(' ');
    $('.exibe_termino').html(' ');
    $('#select_prazo').html("<select class='form-control' id='prazo' name='prazo'><option value=''></option><option value='12'>12 (Doze)</option></select><label for='estado_civil'>Prazo Vigência</label><span class='help-block'>Vigência em meses...</span>");
    $('#select_prazo option[value=""]').attr('selected','selected');
    
    //$('.prazo_periodo').show();
    
    $('#forma_pagamento').focus();
  }
  
});

$( document ).on( "change", "#prazo", function() {
//$( "#prazo" ).change(function() {
  $("#radioMA").prop( "checked", false );
  $("#radioON").prop( "checked", false );
  $("#radioBO").prop( "checked", false );  
  $("#div_parcela_parcelas_boleto").hide();
  $(".txt_parcela_boleto").hide();
  var prazo             = $('#prazo option:selected').val();
  var valor_parcela     = $('#soma_produto').val();
  
  var valor_entrada_auto_parceiro   = $("#valor_entrada_auto_parceiro").val();
  var divide_entrada_prazo = parseFloat(valor_entrada_auto_parceiro) / parseFloat(prazo);
  divide_entrada_prazo = divide_entrada_prazo.toFixed(2);
  var subtrai_valor_parcela_entrada = parseFloat(valor_parcela) - parseFloat(divide_entrada_prazo);
  subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.toFixed(2);
  subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.replace(".", ",");
  
  var msg_status     = $('#msg_status').val();
  
  if(prazo != '' && typeof(prazo) !== 'undefined')
  {
    var soma_total = parseInt(prazo) * parseFloat(valor_parcela);
    if(msg_status == 'renovar_venda' || msg_status == 'finalizar_venda'){
        var valor_renovacao     = $('#valor_renovacao').val();
        soma_total_somar = soma_total;
        soma_total = parseFloat(soma_total_somar) - parseFloat(valor_renovacao);
    }
    
    soma_total = soma_total.toFixed(2);
    $('#total_geral_assistencia').val(soma_total);
    soma_total = soma_total.replace(".", ",");
    $('.exibe_soma_total').html(' R$ ' + soma_total);
    $('.exibe_soma_valor_total_parcela').html(' R$ ' + subtrai_valor_parcela_entrada);
    var prazo_selecionado;
    //var 
    if(prazo == 1){
        prazo_selecionado = '1 (um) mês de contratacão';
    }else if(prazo == 6){
        prazo_selecionado = '6 (Seis) meses de contratacão';
    }else if(prazo == 12){
        prazo_selecionado = '12 (Doze) meses de contratacão';
    }else if(prazo == 24){
        prazo_selecionado = '24 (Vinte e quatro) meses de contratacão';
    }else if(prazo == 36){
        prazo_selecionado = '36 (Trinta e seis) meses de contratacão';
    }else if(prazo == 48){
        prazo_selecionado = '48 (Quarenta e oito) meses de contratacão';
    }
    
   var data      = new Date(); // instancia um objeto da classe Date com a data do sistema.
   var data_hoje = new Date(); // instancia um objeto da classe Date com a data do sistema.
    data.setMonth(data.getMonth() + parseInt(prazo) + 1); 
    
    data_hoje.setMonth(data_hoje.getMonth() + 1);

	//alert(data_hoje);
    
    $('.exibe_prazo').html(prazo_selecionado);
    
    //var dt = new Date();
    var month = data.getMonth();
    var day = data.getDate();
    var year = data.getFullYear();
    
    function setDateZero(date){
        return date < 10 ? '0' + date : date;
    }
    
    if(month == 0)
    {
        month = 1;
    }
    
    var mes_atual = data_hoje.getMonth();
    
    if(data_hoje.getMonth() == 0)
    {
        mes_atual = 1;
    }
    
    $('.exibe_termino').html(setDateZero(day) + '-' + setDateZero(month) + '-' + year);
    
    $('.exibe_inicio').html(setDateZero(data_hoje.getDate()) + '-' + setDateZero(mes_atual) + '-' + data_hoje.getFullYear());
    
    $('#bt_calcular_pagamento').show();
    $("#bt_calcular_pagamento").removeAttr('disabled');  
  }
  else
  {
    $('#bt_calcular_pagamento').hide();
    $("#bt_calcular_pagamento").attr('disabled', 'true');
    $('.exibe_soma_total').html('-');
    $('#total_geral_assistencia').val('');
  }
  
  
});

//sel_metodo_pagamento
function soma_valor_adicional(){
{   
    var soma_produto              = parseFloat($("#soma_produto_atual").val());
    var valor_adicional           = $("#valor_adicional").val();
    valor_adicional               = valor_adicional.replace(",", ".");
    
    
    
      $("#bt_add_submit").hide();  
      $('#bt_calcular_pagamento').hide();
      $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
      $('#prazo').val('');
      $('#forma_pagamento').val('');
      $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
      
    
    
    
    
    if(valor_adicional.length > 0){
        
        var soma_novo_valor_produto = parseFloat(soma_produto) + parseFloat(valor_adicional);
        var soma_novo_valor_produto_html = soma_novo_valor_produto.toFixed(2);
        
        $("#soma_produto").val(soma_novo_valor_produto_html);
        
        soma_novo_valor_produto = soma_novo_valor_produto_html.replace(".", ",");
        
        $("#exibe_soma_produto_atual").html("R$ " + soma_novo_valor_produto);
        $(".exibe_soma_produto").html("R$ " + soma_novo_valor_produto);
    }else{
        var soma_produto_atual    = parseFloat($("#soma_produto_atual").val());
        $("#soma_produto").val(soma_produto_atual);
        var soma_novo_valor_produto_html = soma_produto_atual.toFixed(2);
        soma_novo_valor_produto = soma_novo_valor_produto_html.replace(".", ",");
        $("#exibe_soma_produto_atual").html("R$ " + soma_novo_valor_produto);
        $(".exibe_soma_produto").html("R$ " + soma_novo_valor_produto);
    }
    
}};

$( document ).on( "keyup", "#valor_entrada", function() {
    var valor_entrada_verifica  = $("#valor_entrada").val();
    valor_entrada_verifica = valor_entrada_verifica.replace(",", ".");

    var valor_minimo_entrada    = $("#valor_entrada").attr('data');
    var total_geral_assistencia = $('#total_geral_assistencia').val();
    
    var calcular_minimo = parseFloat((total_geral_assistencia * valor_minimo_entrada)/100);
    //calcular_minimo = calcular_minimo.toFixed(2);
    
    if(valor_entrada_verifica.length > 0){
        if(calcular_minimo > valor_entrada_verifica){
        $("#valor_entrada").parent('div').addClass('has-error');
        $('#bt_calcular_pagamento').hide();
        }else{
            var valor_forma_pagamento = $('#forma_pagamento option:selected').val();
            
            if(valor_forma_pagamento.length > 1){
                $('#bt_calcular_pagamento').show(); 
            }
            
        }
    }else{
        if(valor_forma_pagamento.length > 1){
        $('#bt_calcular_pagamento').show(); 
        }
    }
    
    
});

$( document ).on( "click", "#bt_calcular_pagamento", function() {

  var valor                 = $('#forma_pagamento option:selected').val();
  var desconto_verifica     = 0;
  
  if(document.getElementById('desconto')){
    var desconto_verifica_input  = $("#desconto").val();
    desconto_verifica            = desconto_verifica_input.replace(",", ".");
    var desconto                 = parseFloat(desconto_verifica);
    var tipo_desconto            = $("#tipo_desconto").val();
  }  
  
  /*if(document.getElementById('desconto')){
    var desconto_verifica         = $("#desconto").val();
  } */ 
  
  //var desconto                  = parseFloat($("#desconto").val());
  
  //var valor_entrada             = parseFloat($("#valor_entrada").val());
  /*var valor_adicional           = $("#valor_adicional").val();
  valor_adicional               = valor_adicional.replace(",", ".");*/

  if(valor == 'recorrente_cartao' || valor == 'fidelidade' || valor == 'entrada_recorrente_cartao') 
  {

    if(desconto_verifica.length < 7 && desconto_verifica.length > 0)
    {
        var valor_parcela     = parseFloat($('#soma_produto').val());
        if(tipo_desconto == '%'){
            var calcular_desconto = parseFloat((valor_parcela * desconto)/100);
        }else{
            var calcular_desconto = desconto;
        }
        //var calcular_desconto = parseFloat((valor_parcela * desconto)/100);
        calcular_desconto = calcular_desconto.toFixed(2);
        var calcular_desconto_input = parseFloat(valor_parcela - calcular_desconto);
        calcular_desconto_input = calcular_desconto_input.toFixed(2);
        var calcular_desconto_html;
        calcular_desconto_html = calcular_desconto_input.replace(".", ",");
       
        $('.exibe_desconto').html(' - ' + desconto + '%');
        $('.exibe_total_parcela').html(': R$ ' + calcular_desconto_html);
        
        $('#soma_produto_com_desconto').val(calcular_desconto_input);
        
        var valor_parcela_atual     = parseFloat($('#soma_produto_com_desconto').val());
        
        //valor_adicional
        /*if(valor_adicional.length > 0)
        {
            var calcular_com_valor_adicional = parseFloat(valor_parcela) + parseFloat(valor_adicional);
            valor_parcela = calcular_com_valor_adicional;
        }*/
        $('#exibe_novo_valor_total_geral_assistencia').show();
      
    }
    else{
        $('.exibe_desconto').html(' ');
        $('.exibe_total_parcela').html('');  
        $('#exibe_novo_valor_total_geral_assistencia').hide();
    }

  }else{
    
    var valor_parcela_atual     = parseFloat($('#soma_produto').val());
    valor_parcela_atual = valor_parcela_atual.toFixed(2);
    //valor_adicional
    /*if(valor_adicional.length > 0)
    {
        var calcular_com_valor_adicional = parseFloat(valor_parcela_atual) + parseFloat(valor_adicional);
        valor_parcela_atual = calcular_com_valor_adicional;
    }*/
    
    
    
    
    if(desconto_verifica.length < 7 && desconto_verifica.length > 0)
    {
        var valor_parcela     = parseFloat($('#soma_produto').val());
        if(tipo_desconto == '%'){
            var calcular_desconto = parseFloat((valor_parcela * desconto)/100);
        }else{
            var calcular_desconto = desconto;
        }
        //var calcular_desconto = parseFloat((valor_parcela * desconto)/100);
        calcular_desconto = calcular_desconto.toFixed(2);
        var calcular_desconto_input = parseFloat(valor_parcela - calcular_desconto);
        calcular_desconto_input = calcular_desconto_input.toFixed(2);
        var calcular_desconto_html;
        calcular_desconto_html = calcular_desconto_input.replace(".", ",");
        
        $('.exibe_desconto').html(' - ' + desconto + '%');
        $('.exibe_total_parcela').html(': R$ ' + calcular_desconto_html);
        
        $('#soma_produto_com_desconto').val(calcular_desconto_input);
        
        var valor_parcela_atual     = parseFloat($('#soma_produto_com_desconto').val());
        //valor_adicional
        /*if(valor_adicional.length > 0)
        {
            var calcular_com_valor_adicional = parseFloat(valor_parcela_atual) + parseFloat(valor_adicional);
            valor_parcela_atual = calcular_com_valor_adicional;
        }*/
        $('#exibe_novo_valor_total_geral_assistencia').show();
    }
    else{
        $('.exibe_desconto').html(' ');
        $('.exibe_total_parcela').html(''); 
        $('#exibe_novo_valor_total_geral_assistencia').hide(); 
    }
    
    var prazo             = $('#prazo option:selected').val();
    var soma_total = parseInt(prazo) * parseFloat(valor_parcela_atual);
    var novo_valor_com_desconto = soma_total;

    novo_valor_com_desconto = novo_valor_com_desconto.toFixed(2);
    novo_valor_com_desconto = novo_valor_com_desconto.replace(".", ",");
    
    var valor_entrada_verifica    = $("#valor_entrada").val();
    valor_entrada_verifica        = valor_entrada_verifica.replace(",", ".");

    if($("#tipo_desconto_entrada").is(':checked')){
        var tipo_desconto_entrada = 'S';
    }else{
        var tipo_desconto_entrada = 'N';
    }

    if ($('#input_status_entrada').val() == 'N')
    {
         valor_entrada_verifica = '';
    } 
    
    if(valor_entrada_verifica.length > 0 && tipo_desconto_entrada == 'N') 
    {
        var calcular_com_entrada = parseFloat(soma_total) - parseFloat(valor_entrada_verifica);
        soma_total = calcular_com_entrada;
    }
    var msg_status     = $('#msg_status').val();
    if(msg_status == 'renovar_venda' || msg_status == 'finalizar_venda'){
       var valor_renovacao     = $('#valor_renovacao').val();
       soma_total_somar = soma_total;
       soma_total = parseFloat(soma_total_somar) - parseFloat(valor_renovacao);
    }
    var novo_valor_com_desconto = soma_total;
    novo_valor_com_desconto = novo_valor_com_desconto.toFixed(2);
    novo_valor_com_desconto = novo_valor_com_desconto.replace(".", ",");
    
    soma_total = soma_total.toFixed(2);
    $('#total_geral_assistencia').val(soma_total);
    
    
    $('#novo_valor_total_geral_assistencia').html("R$ " + novo_valor_com_desconto);
    
    var valor_entrada_auto_parceiro   = $("#valor_entrada_auto_parceiro").val();
    var divide_entrada_prazo = parseFloat(valor_entrada_auto_parceiro) / parseFloat(prazo);
    divide_entrada_prazo = divide_entrada_prazo.toFixed(2);
    var subtrai_valor_parcela_entrada = parseFloat(valor_parcela_atual) - parseFloat(divide_entrada_prazo);
    subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.toFixed(2);
    subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.replace(".", ",");
    
    $('#novo_valor_parcela_com_desconto').html("R$ " + subtrai_valor_parcela_entrada);
    
    /*if ($('#input_msg_status').val() == 'finalizar_venda' && $('#input_status_entrada').val() == 'N')
    {
        valor_parcela_atual_desconta_entrada = parseFloat(valor_parcela_atual) - parseFloat(divide_entrada_prazo);
        valor_parcela_atual_desconta_entrada = valor_parcela_atual_desconta_entrada.toFixed(2);
        $("#soma_produto_atual").val(valor_parcela_atual_desconta_entrada);
        
        //valor_entrada_auto_parceiro = valor_entrada_auto_parceiro.toFixed(2);
        soma_total_desconta_estrada = parseFloat(soma_total) - parseFloat(valor_entrada_auto_parceiro);
        soma_total_desconta_estrada = soma_total_desconta_estrada.toFixed(2);
        $('#total_geral_assistencia').val(soma_total_desconta_estrada);
        
    }*/
    //soma_total = soma_total.replace(".", ",");
    //$('.exibe_soma_total').html(' R$ ' + novo_valor_com_desconto);        
  }
  
  $("#bt_calcular_pagamento").attr('disabled', 'true');
  $("#bt_cancelar_calcular_pagamento").removeAttr('disabled');  
  
  $('.html_info_pagamento').show();
  $('.html_forma_pagamento').show();
  
  $("#desconto").attr('readonly', 'true');
  $("#tipo_desconto").attr('readonly', 'true');
  $("#bloco_verifica_entrada").hide();
  $("#valor_entrada").attr('readonly', 'true');
  $("#valor_adicional").attr('readonly', 'true');
  
  $("#forma_pagamento").attr('readonly', 'true');
  $("#prazo").attr('readonly', 'true');
    
});

$( document ).on( "click", "#bt_cancelar_calcular_pagamento", function() {
    
    //$('.html_info_pagamento').hide();
    $('#parcela_parcelas_boleto').val('');
    $('.txt_parcela_boleto').html('');
    $('.html_forma_pagamento').hide();
    $("#bt_add_submit").hide();
    /*$('#valor_entrada').val('');
    $('#desconto').val('');
    $('#forma_pagamento').val('');
    $('#prazo').val('');*/
    $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
    $("#desconto").removeAttr('readonly');  
    $("#tipo_desconto").removeAttr('readonly');  
    $("#bloco_verifica_entrada").show();
    $("#valor_entrada").removeAttr('readonly');
    $("#valor_adicional").removeAttr('readonly');
    
    $("#bt_calcular_pagamento").removeAttr('disabled');
    //$('#bt_calcular_pagamento').hide(); 
    $("#forma_pagamento").removeAttr('readonly');
    $("#prazo").removeAttr('readonly');
});   

//$( document ).on( "click", "#bt_remove_dependente", function() {
function acao_remove_dependente(id_dependente, valor_dependente){
{
    //var id_dependente = $("#bt_remove_dependente").attr("data");
    //var valor_dependente = $("#bt_remove_dependente").attr("data_valor");
    $("#div_lista_dependente").append('<input type="hidden" id="excluir_id_dependente" name="excluir_id_dependente[]" value="' + id_dependente + '"/>');
    
    $("#box_lista_depen_" + id_dependente).remove();
    
    var soma_produto = $("#soma_produto").val();
    
    var soma_novo_valor_produto = parseFloat(soma_produto) - parseFloat(valor_dependente);
    var soma_novo_valor_produto_html = soma_novo_valor_produto.toFixed(2);
        
    $("#soma_produto").val(soma_novo_valor_produto_html);
    $("#soma_produto_atual").val(soma_novo_valor_produto_html);
    
    soma_novo_valor_produto = soma_novo_valor_produto_html.replace(".", ",");
    
    $("#exibe_soma_produto_atual").html("R$ " + soma_novo_valor_produto);
    $(".exibe_soma_produto").html("R$ " + soma_novo_valor_produto);
    
    $("#valor_adicional").val("");
    $("#forma_pagamento").val("");
    
    $(".div_informa_user_excluir_dep").show();
    
}};

$( document ).on("click", "#principal_bt_add_dependente", function() {
{       
        $("#div_aguarde_sel_plano_add_depe").show();
        $("#principal_bt_add_dependente").hide();
        var contar_dependente_atual = $("#principal_contar_dependente_atual").val();
        var contar_dependente       = $("#principal_contar_dependente").val();
        var tipo_depen  = $("#linha_dependente_" + contar_dependente_atual + " .tipo_dependente").val();
        var nome_depen  = $("#linha_dependente_" + contar_dependente_atual + " .nome_dependente").val();
        var data_nasc   = $("#linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").val();
        var ver_ordem_pedido = $("#verifica_id_ordem_pedido_dependentes").val();
        var id_produto_get = $("#id_produto_get").val();
        if(data_nasc != '' && nome_depen != ''){
            
           //alert(data_nasc);
            $.get('inc/add_form_dependente_editar.php?contar_dependente=' + contar_dependente_atual + '&ver_ordem_pedido=' + ver_ordem_pedido + '&id_produto_get=' + id_produto_get,function (dados) {
                $("#linha_dependente_" + contar_dependente_atual + " .tipo_dependente").attr('readonly', true);
                $("#linha_dependente_" + contar_dependente_atual + " .nome_dependente").attr('readonly', true);
                $("#linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").attr('readonly', true);
                //$("#linha_dependente_" + contar_dependente_atual + " #principal_bt_add_dependente").hide();
                $("#inserir_mais_dependentes").append(dados);
                contar_dependente_atual = parseInt(contar_dependente_atual) + 1;
                contar_dependente       = parseInt(contar_dependente) + 1;
                $("#principal_contar_dependente_atual").val(contar_dependente_atual);
                $("#principal_contar_dependente").val(contar_dependente);
                $("#div_aguarde_sel_plano_add_depe").hide();
                $("#principal_bt_add_dependente").show();
                
        });
        
    }else{
        $("#div_aguarde_sel_plano_add_depe").hide();
        $("#principal_bt_add_dependente").show();
    }
}

});

$( document ).on("click", ".principal_bt_remove_dependente", function() {
{       
        var contar_dependente_atual = $("#principal_contar_dependente_atual").val();
        var contar_dependente       = $("#principal_contar_dependente").val();
        var tipo_depen  = $("#linha_dependente_" + contar_dependente_atual + " .tipo_dependente").val();
        var nome_depen  = $("#linha_dependente_" + contar_dependente_atual + " .nome_dependente").val();
        var data_nasc   = $("#linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").val();
        var id_dependente = $( this ).attr("data");
        $("#linha_dependente_" + id_dependente).remove();
        
}

});

function acao_remove_dependente_ativo(id_dependente){
{
   
    $("#div_lista_dependente_ativo").append('<input type="hidden" id="principal_excluir_id_dependente" name="principal_excluir_id_dependente[]" value="' + id_dependente + '"/>');
    
    $("#box_lista_depen_ativo_" + id_dependente).remove();

    
}};

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


    $('input[name="lista_cidades_local[]"]').click(function() {
        var pegar_nome_selecao          = $(this).attr("data");
        var pegar_nome_selecao_completo = $(this).attr("nome");
        if ($(this).is(':checked')) {
            $("#lista_cidades_selecionada").append("<span class='sel_cid_sel sel_" + pegar_nome_selecao + "'>" + pegar_nome_selecao_completo + "</span>");
        }else{
            $("#lista_cidades_selecionada").find(".sel_" + pegar_nome_selecao).remove();
        }
        
        $(window).trigger("hash_cidades");
    });
    
$('input[name="check_editar_cidades"]').click(function() {
        if ($(this).is(':checked')) {
            $("#editar_sel_cidades").show();
        }else{
            $("#editar_sel_cidades").hide();
        }
    });
    
   //function to change hash
function change_hash(aux){
 location.hash = '#pages='+aux;
 document.title = 'Work '+aux;
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

function remove_procedimento_local(id){
{   
    $('#remove_procedimentos').append('<input type="hidden" name="remove_procedimento_local[]" value="' + id + '"/>')

}};

function editar_data_agendamento(id_guia){
{
  var alterar_id_guia                 = id_guia;
  var alterar_data_agendamento        = $("#alterar_data_agendamento").val();
  var alterar_horario_agendamento     = $("#alterar_horario_agendamento").val();
    
    $.ajax({ 
     type: "POST",
     url:  "gui_editar_db.php",
     data: {
        item: 'alterar_horario_agendamento',
        alterar_id_guia: alterar_id_guia,
        alterar_horario_agendamento: alterar_horario_agendamento,
        alterar_data_agendamento: alterar_data_agendamento
        },
     success: function(dados){
         if(dados != 'erro'){
            $("#dia_da_semana").html(dados);
            $("#dia_do_novo_agendamento").html(alterar_data_agendamento);
            $("#hora_do_novo_agendamento").html(alterar_horario_agendamento);
            $('#editar_data_agendamento').modal('hide');
            location.reload();
         }else{
            alert('erro');
         }
   
     } 
     });    
        
  //}
   
}};

function editar_profissional_guia(id_guia){
{
  var alterar_id_guia               = id_guia;
  var id_profissional_sel         = $("#id_profissional_sel").val();
  //var nome_profissional             = $("#nome_profissional").val();
  $("#div_aguarde_2_dados_profissional").show();
  $("#bt_confirmar_alterar_profissional_guia").attr('disabled', 'true');  
    $.ajax({ 
     type: "POST",
     url:  "gui_editar_db.php",
     data: {
        item: 'alterar_profissional_guia',
        alterar_id_guia: alterar_id_guia,
        id_profissional_sel: id_profissional_sel
        },
     success: function(dados){
         if(dados != 'erro'){
            /*$("#dia_da_semana").html(dados);
            $("#dia_do_novo_agendamento").html(alterar_data_agendamento);
            $("#hora_do_novo_agendamento").html(alterar_horario_agendamento);*/
            $('#editar_data_agendamento').modal('hide');
            location.reload();
         }else{
            alert('erro');
         }
   
     } 
     });    
        
  //}
   
}};



$( "#bt_add_dependente" ).click(function() {
{   
        $("#bt_add_dependente").hide();
        $("#div_aguarde_sel_plano_add").show();
        $("#info_add_produto_html").hide();
        $("#forma_pagamento").val("").change();
        $("#radioMA").prop( "checked", false );
        $("#radioON").prop( "checked", false );
        $("#radioBO").prop( "checked", false );
        
        $("#div_parcela_parcelas_boleto").hide();
        $(".txt_parcela_boleto").hide();
        
        var contar_dependente       = parseFloat($('#contar_dependente').val());
        var contar_dependente_atual = parseFloat($('#contar_dependente_atual').val());
        contar_dependente_atual     = parseFloat(contar_dependente_atual + 1);
        $('#contar_dependente_atual').val(contar_dependente_atual);
        var valor_dependente        = parseFloat($('#valor_dependente').val());
        var id_parceiro             = $('#id_parceiro').val();
        
        $.get('inc/form_add_dependente_editar.php?contar_dependente=' + contar_dependente_atual + '&id_parceiro=' + id_parceiro,function (dados) { $("#form_add_depenente").append(dados);
        var quantidade_depenente    = parseFloat($('#contar_dependente').val());
        contar_dependente = parseFloat(contar_dependente + 1);
        $('#contar_dependente').val(contar_dependente);
        
        var tel_telefone        = $("#telefone").val();
        var tel_celular         = $("#celular").val();
        var tel_cep             = $("#cep").val();
        var tel_endereco        = $("#endereco").val();
        var tel_numero          = $("#numero").val();
        var tel_complemento     = $("#complemento").val();
        var tel_bairro          = $("#bairro").val();
        var tel_cidade          = $("#cidade").val();
        var tel_estado          = $("#estado").val();
        
        $("#dados_dependente_"+contar_dependente_atual+" #telefone").val(tel_telefone).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #celular").val(tel_celular).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #cep").val(tel_cep).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #endereco").val(tel_endereco).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #numero").val(tel_numero).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #complemento").val(tel_complemento).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #bairro").val(tel_bairro).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #cidade").val(tel_cidade).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #estado").val(tel_estado).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #nome").focus();
        
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
        
        if(prazo != '' && typeof(prazo) !== 'undefined')
        {
            $('#total_geral_assistencia').val(calcular_soma_total);
            $('.exibe_soma_total').html(" + R$ " + calcular_soma_total_html);
        }
        
        $("#div_aguarde_sel_plano_add").hide();
        $("#bt_add_dependente").show();
        });
        
    }

});
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
    $("#bt_add_submit").show();
    $("#bt_add_dependente").show();
    $("#info_add_produto_html").show();
}
});

function cancelar_cliente(slug_get, id_grupo_produto, id, id_ordem_pedido, chave){
{   
    //$(".div_aguarde").show();
    $("#bt_cancelar_cliente").attr("disabled", true);
    var tipo_cancel = $('input[name=tipo_cancelamento]:checked').val();
    $.get('excluir.php?item=cliente&slug_produto='+slug_get+'&id_grupo_produto='+id_grupo_produto+'&id_cliente_get='+id+'&order_number='+id_ordem_pedido+'&chave_cliente='+chave+'&tipo_cancel='+tipo_cancel,function (dados) {window.location.replace("listar.php?item=clientes&id_serv=2&id=todos&tipo=produto");});
    
}};

function cancelar_parceiro(id){
{   
    //$(".div_aguarde").show();
    $("#bt_cancelar_cliente").attr("disabled", true);
    
    $.get('excluir.php?item=parceiros&id='+id,function (dados) {window.location.replace("listar.php?item=parceiros");});
    
}};

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


function acao_remove_especialidade_profissional_ativo(id_especialidade_profissional){
{
   
    $("#linha_especialidade_ativo").append('<input type="hidden" id="principal_excluir_id_especialidade_profissional" name="principal_excluir_id_especialidade_profissional[]" value="' + id_especialidade_profissional + '"/>');
    
    $("#linha_especialidade_ativo_" + id_especialidade_profissional).remove();

    
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
        //alert(data_nasc);
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
/*window.onbeforeunload = function() {
   var Ans = confirm("Sair da página?");
   if(Ans==true)
       return true;
   else
       return false;
};*/