<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$id_filial = (empty($_GET['id_filial'])) ? "" : verifica($_GET['id_filial']);  
$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);

$sql        = "SELECT * FROM filiais
            WHERE id_filial = '$id_filial'";

$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);
        
}

$sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = '$id_parceiro'";
$query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
$nome_parceiro = '';   
if (mysql_num_rows($query_parceiro)>0)
{
    $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
}

?>

<script>


function editar_filial_parceiro(id_filial){
{

  $("#bt_salva_pagamento").attr("disabled", true);  
  var vencimento        = $("#vencimento").val();
  var recebimento       = $("#recebimento").val();
  var valor_recebido    = $("#valor_recebido").val();
  var observacoes       = $("#observacoes").val();
  var tipo_recebimento  = $("input[name=tipo_recebimento]:checked").val();
  
  var id_cliente        = $("#id_cliente").val();  
  var id_usuario        = $("#id_usuario").val();
  var id_parceiro       = $("#id_parceiro").val();

    
    if($("#confirmar_pagamento").is(':checked')){
        var pagamento = 'S';
        recebimento = recebimento;
        valor_recebido = valor_recebido;
    }else{
        var pagamento = 'N';
        recebimento = '';
        valor_recebido = '';
    }
    
    if($("#alterar_todos_boletos").is(':checked')){
        var alterar_todos_boletos = 'S';
    }else{
        var alterar_todos_boletos = 'N';
    }
    $(".div_aguarde_2").hide(); 
    
    $.ajax({ 
     type: "POST",
     url:  "editar_db.php",
     data: {
        item: 'boletos_clientes',
        pagamento: pagamento,
        alterar_todos_boletos: alterar_todos_boletos,
        id_pagamento: id_pagamento,
        vencimento: vencimento,
        recebimento: recebimento,
        valor_recebido: valor_recebido,
        observacoes: observacoes,
        tipo_recebimento: tipo_recebimento,
        id_cliente: id_cliente,
        id_usuario: id_usuario,
        id_parceiro: id_parceiro,
        status_cliente: status_cliente,
        id_base: id_base
        },
     success: function(dados){
         if(dados == 'pago'){
            $('#bt_boleto_'+id_pagamento).removeClass('red').addClass('green').html('<i class="fa fa-check"></i>');
            $(".div_aguarde_2").hide(); 
            $('#pg_boleto_'+id_pagamento).removeClass('hide');
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else if(dados == 'nao_pago'){
            $('#bt_boleto_'+id_pagamento).removeClass('green').addClass('red').text('Receber');
            $(".div_aguarde_2").hide(); 
            $('#pg_boleto_'+id_pagamento).addClass('hide');
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else{
            alert('erro');
         }
         
         if(alterar_todos_boletos == 'S'){
            alert('Necessário atualizar a página para verificar novas datas.')
         }
         
        $("#bt_salva_pagamento").removeAttr("disabled");
        $('.modal-content').html('Aguarde...');
     } 
     });    
        
  //}
   
}};
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $nome_parceiro; ?> </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_s; ?>"/>
        <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro_s; ?>"/>
        <input type="hidden" name="id_filial" id="id_filial" value="<?php echo $id_filial; ?>"/>
            
            <h4> Informações </h4>
            <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>CNPJ/CPF:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="" /> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Nome:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="" /></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Cidade:</strong></label>
                        <p class="form-control-static"> <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="" /></p>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-1">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>UF:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="" /></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Telefone:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="" /></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
<span class="div_aguarde_2" style="display: none;position: relative;width: 100%;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    
    <a href="javascript:" onclick="return editar_filial_parceiro(<?php echo $id_filial;?>);" class="btn blue" id="bt_salva_pagamento">Atualizar Filial</a>
</div>