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
    
            var gui_nome_procedimento           = '__xxtodosxx__';
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
    			url: 'inc/gui_localiza_procedimento_buscar.php',
    			data: {gui_nome_procedimento: gui_nome_procedimento, sel_id_local_atendimento: sel_id_local_atendimento, verifica_grupo_procedimento: verifica_grupo_procedimento, verifica_grupo_convenio: verifica_grupo_convenio, sel_procedimentos_sel: sel_procedimentos_sel, id_convenio_paciente_sel: id_convenio_paciente_sel},
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
    
    jQuery("#get_nome_procedimento_guia").keyup(function() {
        if (jQuery(this).val().length >= '3') {
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
    			url: 'inc/gui_localiza_procedimento_buscar.php',
    			data: {gui_nome_procedimento: gui_nome_procedimento, sel_id_local_atendimento: sel_id_local_atendimento, verifica_grupo_procedimento: verifica_grupo_procedimento, verifica_grupo_convenio: verifica_grupo_convenio, sel_procedimentos_sel: sel_procedimentos_sel, id_convenio_paciente_sel: id_convenio_paciente_sel},
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
            if (jQuery(this).val().length == '0') {
            var gui_nome_procedimento           = '__xxtodosxx__';
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
    			url: 'inc/gui_localiza_procedimento_buscar.php',
    			data: {gui_nome_procedimento: gui_nome_procedimento, sel_id_local_atendimento: sel_id_local_atendimento, verifica_grupo_procedimento: verifica_grupo_procedimento, verifica_grupo_convenio: verifica_grupo_convenio, sel_procedimentos_sel: sel_procedimentos_sel, id_convenio_paciente_sel: id_convenio_paciente_sel},
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
            }
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
    			url: 'inc/gui_localiza_procedimento_buscar.php',
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
});

</script>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$id_local    = (empty($_GET['id_local'])) ? "" : verifica($_GET['id_local']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);
               
?>
<input type="hidden" name="sel_id_local_atendimento" id="sel_id_local_atendimento" value="<?php echo $id_local; ?>" />
<div class="portlet-body form">
    <div id="click_campo_gui_nome_procedimento"></div>
    <div class="form-body">
        <div class="row note note-info">
            <h4 class="block">Procurar por código ou nome do procedimento &nbsp; <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span></h4> 
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
</div>