<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$data1                  = (empty($_GET['data_um'])) ? "" : verifica($_GET['data_um']);  
$data2                  = (empty($_GET['data_dois'])) ? "" : verifica($_GET['data_dois']);  
$id_local_atendimento   = (empty($_GET['id_local_atendimento'])) ? "" : verifica($_GET['id_local_atendimento']);  
$id_profissional        = (empty($_GET['id_profissional'])) ? "" : verifica($_GET['id_profissional']);  
$mes_referencia         = (empty($_GET['mes_referencia'])) ? "" : verifica($_GET['mes_referencia']);  
$ano_referencia         = (empty($_GET['ano_referencia'])) ? "" : verifica($_GET['ano_referencia']);  
$soma_preco_custo       = (empty($_GET['soma_preco_custo'])) ? "" : verifica($_GET['soma_preco_custo']);  

$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);




?>
<script>

function registrar_faturamento_guias(data1, data2, id_local_atendimento, id_profissional, mes_referencia, ano_referencia, soma_preco_custo){
{   
    $(".div_aguarde_2").show();
    $("#bt_registrar_faturamento_guias_confirmar").attr("disabled", true);
    var mes_referencia          = $('#mes_referencia').val();
    var ano_referencia          = $('#ano_referencia').val();
    var todos_clientes_ativos   = $('#todos_clientes_ativos').val();        
    
    var sel_guias_marcadas = new Array();
    var sel_valor_guias_marcadas = new Array();
    var valor_separado_para_soma = '';    
    var soma_preco_custo = 0;
    var total_custo = '';        
    $("input[type=checkbox][name='guias_marcadas[]']:checked").each(function(){
        sel_guias_marcadas.push($(this).val());
            
        var id_guia_sel = $(this).val();
        valor_separado_para_soma = $("#valor_custo_guias_" + id_guia_sel).val();
        total_custo = parseFloat(soma_preco_custo) + parseFloat(valor_separado_para_soma);
        soma_preco_custo = total_custo.toFixed(2);
        
        sel_valor_guias_marcadas.push(valor_separado_para_soma);                                         
    });
    //sel_valor_guias_marcadas_array = sel_valor_guias_marcadas.map(function(){return $(this).val();}).get();
    $.ajax({ 
     type: "POST",
     url:  "inc/gui_guias_faturadas.php",
     data: {
        id_local_atendimento: id_local_atendimento,
        data1: data1,
        data2: data2,
        id_profissional: id_profissional,
        mes_referencia: mes_referencia,
        ano_referencia: ano_referencia,
        soma_preco_custo: soma_preco_custo,
        'sel_guias_marcadas[]': sel_guias_marcadas,
        'sel_valor_guias_marcadas[]': sel_valor_guias_marcadas,
        todos_clientes_ativos: todos_clientes_ativos
        },
     success: function(dados){
     //$("#lista_cliente_faturado").html(dados); 
     $(".div_aguarde_2").hide(); 
     //alert('ok');
     //$('#campos_calculos_faturamento').remove(); 
     //$('#sel_mes_ref').html(texto_mes_referencia);
     $("#sample_1_wrapper").hide('fast');
     $("#bt_faturar_guia").hide();
     $("#info_historico_faturamento").hide();
     $("#html_imprimir_recibo_faturados").html('<div class="alert alert-success"><strong>Sucesso!</strong> Guias faturadas. </div> <a id="bt_imprimir_recibo_faturados" href="inc/guia_recibo_faturamento/?id_faturamento_guias=' + dados + '" class="btn green" target="_black"><i class="fa fa-file-o"></i> Imprimir Recibo </a>');
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
    <h4 class="modal-title"> Faturar Guias</h4>
</div>
<div class="modal-body"> Tem certeza que deseja faturar as guias listas abaixo de acordo com os Dados de Faturamento? <br />
A alteração não poderá ser revertida!<br />
</div>
<div class="modal-footer">
<span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
    <button type="button" class="btn default" data-dismiss="modal">Cancelar</button>
    <button type="button" id="bt_registrar_faturamento_guias_confirmar" onclick="return registrar_faturamento_guias('<?php echo $data1; ?>', '<?php echo $data2; ?>', '<?php echo $id_local_atendimento; ?>', '<?php echo $id_profissional; ?>', '<?php echo $mes_referencia; ?>', '<?php echo $ano_referencia; ?>', '<?php echo $soma_preco_custo; ?>');" class="btn green" >Sim, confirmar!</button>
</div>