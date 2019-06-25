<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {
    
    var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
    
    
    jQuery("#get_nome_procedimento_guia").keyup(function() {
        if (jQuery(this).val().length >= '2') {
        	$(".div_aguarde_2").show();
  delay(function(){
            var gui_nome_procedimento           = jQuery("#get_nome_procedimento_guia").val();
            var sel_id_local_atendimento        = jQuery("#sel_id_local_atendimento").val();
            var verifica_grupo_procedimento     = jQuery("#verifica_grupo_procedimento").val();
            var verifica_grupo_convenio         = jQuery("#verifica_grupo_convenio").val();
            var sel_procedimentos_sel = '';
            var contar_procedimentos_sel = $('#contar_procedimentos_sel').val();
            var id_convenio_paciente_sel = $('#id_convenio_paciente_sel').val();
            if(contar_procedimentos_sel >0){
            sel_procedimentos_sel = $("input[name='add_procedimento[]']").map(function(){return $(this).val();}).get();
            }
            jQuery("#get_id_procedimento").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_procedimento_guia.php',
    			data: {gui_nome_procedimento: gui_nome_procedimento, sel_id_local_atendimento: sel_id_local_atendimento, verifica_grupo_procedimento: verifica_grupo_procedimento, verifica_grupo_convenio: verifica_grupo_convenio, sel_procedimentos_sel: sel_procedimentos_sel, id_convenio_paciente_sel: id_convenio_paciente_sel},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_procedimento_gui").html(dados);
                    $(".div_aguarde_2").hide();
                    $("html, body").animate({ scrollTop: $('#html_busca_procedmento_guia .note-info').offset().top - 100 }, 1000);
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_procedimento_gui").html(dados);
                    $(".div_aguarde_2").hide();
                }
                
 		    });
            }, 1000);
        
        }else{
            $("#resultado_campo_gui_nome_procedimento_gui").html('');
            $(".div_aguarde_2").hide();
        }  
    });
    
        jQuery("#get_id_procedimento").keyup(function() {
        if (jQuery(this).val().length >= '8') {
        	$(".div_aguarde_2").show();
  delay(function(){
            var get_id_procedimento       = jQuery("#get_id_procedimento").val();
            var sel_id_local_atendimento    = jQuery("#sel_id_local_atendimento").val();
            var verifica_grupo_procedimento     = jQuery("#verifica_grupo_procedimento").val();
            var sel_procedimentos_sel = '';
            var contar_procedimentos_sel = $('#contar_procedimentos_sel').val();
            var id_convenio_paciente_sel = $('#id_convenio_paciente_sel').val();
            if(contar_procedimentos_sel >0){
            sel_procedimentos_sel = $("input[name='add_procedimento[]']").map(function(){return $(this).val();}).get();
            }
            jQuery("#get_nome_procedimento_guia").val('');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/gui_localiza_procedimento_guia.php',
    			data: {get_id_procedimento: get_id_procedimento, sel_id_local_atendimento: sel_id_local_atendimento, verifica_grupo_procedimento: verifica_grupo_procedimento, sel_procedimentos_sel: sel_procedimentos_sel, id_convenio_paciente_sel: id_convenio_paciente_sel},
                dataType : 'html',
    			success: function(dados)
    			{        
                    $("#resultado_campo_gui_nome_procedimento_gui").html(dados);
                    $(".div_aguarde_2").hide();
                    //$("html, body").animate({ scrollTop: $('#resultado_campo_gui_nome_procedimento_gui').offset().top }, 1000);
    			},
                error: function(){
                    $("#resultado_campo_gui_nome_procedimento_gui").html(dados);
                    $(".div_aguarde_2").hide();
                }
                
 		    });
            }, 1000);
        
        }else{
            $("#resultado_campo_gui_nome_procedimento_gui").html('');
            $(".div_aguarde_2").hide();
        }  
    });
    
    jQuery("#desconto").keyup(function() {
        if (jQuery(this).val().length >= '1') {
    	$("#div_aguarde_3_desconto").show();
        delay(function(){

            var desconto_verifica_input  = $("#desconto").val();
            if (desconto_verifica_input.length >= '1') {
            desconto_verifica            = desconto_verifica_input.replace(",", ".");
            var desconto                 = parseFloat(desconto_verifica);
            var tipo_desconto            = $("#tipo_desconto").val();
            var valor_total_guia            = $("#valor_total_guia").val();
            
            var desconto_html = desconto.toFixed(2);
            
            if(tipo_desconto == 'por'){
            var calcular_desconto = parseFloat((valor_total_guia * desconto)/100);
            $('#html_exibe_valor_desconto').html(' - ' + desconto_html + '%');
            }else{
                var calcular_desconto = desconto;
                var desconto_html_exibe = desconto_html.replace(".", ",");
                $('#html_exibe_valor_desconto').html(' - R$ ' + desconto_html_exibe);
            }
            
            calcular_desconto = calcular_desconto.toFixed(2);
            var calcular_desconto_input = parseFloat(valor_total_guia - calcular_desconto);
            calcular_desconto_input = calcular_desconto_input.toFixed(2);
            var calcular_desconto_html;
            calcular_desconto_html = calcular_desconto_input.replace(".", ",");
           
            
            $('#html_exibe_valor_desconto_guia').html(': R$ ' + calcular_desconto_html);
            
            $(".html_info_novo_valor").show();

            $("#div_aguarde_3_desconto").hide();
            }else{
                $("#div_aguarde_3_desconto").hide();
                $(".html_info_novo_valor").hide();
                $("#desconto").val('');
                $("#desconto").focus();
            }
            
        }, 2000);
        }else{
            $(".html_info_novo_valor").hide();
            $("#desconto").val('');
            $("#desconto").focus();
        }
    });
    
    $( "#tipo_desconto" ).change(function() {
        
    $(".html_info_novo_valor").hide();
    $("#desconto").val('');
    $("#desconto").focus();
    });
});

</script>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$id_local    = (empty($_GET['id_local'])) ? "" : verifica($_GET['id_local']);

$sql_contar        = "SELECT local_pagamento FROM gui_local_atendimento
                    WHERE id_local_atendimento = $id_local";
$query_contar      = mysql_query($sql_contar);
$local_pagamento = '';
if (mysql_num_rows($query_contar)>0)
{
    $dados = mysql_fetch_array($query_contar);
    extract($dados);
}

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);
          
          
               
?>
<input type="hidden" name="sel_id_local_atendimento" id="sel_id_local_atendimento" value="<?php echo $id_local; ?>" />
<input type="hidden" name="valor_total_guia" id="valor_total_guia" value="0" />
<input type="hidden" name="valor_total_guia_custo" id="valor_total_guia_custo" value="0" />
<div class="portlet-body form">
    <div id="click_campo_gui_nome_procedimento"></div>
    <div id="html_tipo_convenio_procedimento_paciente"><input type="hidden" name="tipo_convenio_procedimento_paciente" id="tipo_convenio_procedimento_paciente" value="" /></div>
    <div class="form-body">
        <div class="row note note-info">
            <h4 class="block">Selecione até 25 procedimentos &nbsp; <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span></h4> 
                <div class="col-md-2">
                 <div class="form-group form-md-line-input form-md-floating-label">
                    <input type="text" name="get_id_procedimento" class="form-control id_procedimento_mask" id="get_id_procedimento" value="" />
                    <label for="get_id_procedimento">Código</label>
                 </div>
                 &nbsp;
                  </div>
                  <div class="col-md-10">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="get_nome_procedimento_guia" class="form-control" id="get_nome_procedimento_guia" value="" autocomplete="off" />
                        <label for="get_nome_procedimento">Nome do procedimento</label>
                        <span class="help-block">Digite o nome do procedimento...</span>
                     </div>
                     &nbsp;
                  </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="resultado_campo_gui_nome_procedimento"></div>
                </div>
            </div>    
    </div>
    <div id="resultado_campo_gui_nome_procedimento_gui"></div>
    <div id="gui_info_guia_pagamento">
    <div class="form-body note well">
        <div class="row note well">
        <h4 class="block">Informações de pagamento:</h4> 
        <p><strong>Local de pagamento: </strong> <?php if($local_pagamento == 'LOCAL'){
                            echo "NA EMISSÃO DESTA GUIA";
                        }else{
                            echo "NO LOCAL DE ATENDIMENTO";
                        } ?></p>
        <p><strong>Valor à cobrar:</strong> <span id="exibe_valor_total_guia"></span></p>
        <?php
         if(in_array("42", $verifica_lista_permissoes_array_inc)){
            echo '<p><strong>Valor Custo:</strong> <span id="exibe_valor_total_guia_custo"></span></p>';
        }
        ?>
        <div class="html_info_novo_valor" style="display: none;">
        <p><strong>Desconto de:</strong> <span id="html_exibe_valor_desconto"></span> </p>
        <p><strong>Valor da Guia com desconto à cobrar:</strong> <span id="html_exibe_valor_desconto_guia"></span></p>
        </div>
        <div class="col-md-12"><h4 class="block">Observações complementares de pagamento:</h4></div> <div class="col-md-6 form-group"><textarea name="info_obs_pagamento" id="info_obs_pagamento" rows="6" style="width: 100%; resize: none;"></textarea> </div>
        </div>
        <?php
        if (in_array("51", $verifica_lista_permissoes_array_inc)){
        ?>
        
        <div class="row note well">
        <h4 class="block">Desconto:</h4> 
            <div class="col-md-1">
               <div class="form-group form-md-line-input form-md-floating-label">
                   <input type="text" name="desconto" class="form-control" id="desconto" value="" onkeyup="this.value=this.value.replace('.',',')" maxlength="5" autocomplete="off"/>
                   <label for="nome">Valor</label>
                </div>
            </div>
            <div class="col-md-1">
               <div class="form-group form-md-line-input form-md-floating-label">
                   <select class="form-control" id="tipo_desconto" name="tipo_desconto">
                       <option value="real" selected="">R$</option>
                       <option value="por">%</option>
                       
                   </select>
                   <label for="tipo_desconto">&nbsp;</label>
               </div>
            </div>
            <div class="col-md-4">
                <span class="div_aguarde_3" id="div_aguarde_3_desconto" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> calculando ...</span>
            </div>
        </div>
        
        <?php } ?>
        
    </div>
    </div>
</div>