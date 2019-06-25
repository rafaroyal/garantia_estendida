<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$id_faturamento     = (empty($_GET['id_faturamento'])) ? "" : verifica($_GET['id_faturamento']);

$id_usuario_s   = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s  = base64_decode($_COOKIE["usr_parceiro"]);

?>
<script>

function cancelar_faturamento_guias(id_faturamento){
{   
    $(".div_aguarde_2").show();
    $("#bt_registrar_faturamento_guias_confirmar").attr("disabled", true);
    var observacoes       = $("#observacoes").val();
     $.ajax({ 
     type: "POST",
     url:  "inc/gui_guias_cancelar_faturamento.php",
     data: {
        id_faturamento: id_faturamento,
        observacoes: observacoes
        },
     success: function(dados){
     //$("#lista_cliente_faturado").html(dados); 
     $(".div_aguarde_2").hide(); 
     //alert('ok');
     //$('#campos_calculos_faturamento').remove(); 
     //$('#sel_mes_ref').html(texto_mes_referencia);
     $("#sample_1_wrapper").hide('fast');
     //$("#bt_faturar_guia").hide();
     //$("#info_historico_faturamento").hide();

     $("#id_faturamento_guias_" + id_faturamento).addClass('list-group-item-danger');
     $("#id_faturamento_guias_" + id_faturamento + " a").attr('href', '#');
     $("#bt_cancel_id_faturamento_guias_" + id_faturamento).html('');
     $('#ajax').hide();
     $('.modal-backdrop').remove();
     $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
    },
    erro: function()
    {
       alert('erro');
       $(".div_aguarde_2").hide(); 
       $('#ajax').hide();
       $('.modal-backdrop').remove();
       $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
       
    }  
     });    

}};

</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"> Cancelar faturamento</h4>
</div>
<div class="modal-body"> Tem certeza que deseja cancelar o faturamento selecionado, as guias vinculadas ficarão disponíveis para novo faturamento? <br />
A alteração não poderá ser revertida!<br />
<h6>Observações</h6>
<div class="row">
<div class="col-md-12">
    <div class="form-group">
        <textarea class="form-control" rows="3" id="observacoes" ></textarea>     
    </div>
</div>
</div>
</div>
<div class="modal-footer">
<span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
    <button type="button" class="btn default" data-dismiss="modal">Cancelar</button>
    <button type="button" id="bt_registrar_faturamento_guias_confirmar" onclick="return cancelar_faturamento_guias('<?php echo $id_faturamento; ?>');" class="btn green" >Sim, confirmar!</button>
</div>