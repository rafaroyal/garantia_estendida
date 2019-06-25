<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php');
require_once('permissoes.php'); 
$banco_painel = $link;
$id_guia = (empty($_GET['id_guia'])) ? "" : verifica($_GET['id_guia']);  
$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);

$sql        = "SELECT gui_pro_gui.*, gui_pag_gui.local_pagamento, gui_pag_gui.baixa_recebimento, gui_pag_gui.usuario_baixa, gui_pag_gui.data_baixa FROM gui_guias gui_pro_gui
            JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
            WHERE gui_pro_gui.id_guia = $id_guia";

$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{

    $dados = mysql_fetch_array($query);
    extract($dados);
        
}


if($id_usuario_emissao == ''){
    $id_usuario_emissao = 0;
    $id_usuario_html = $id_usuario_s;
}else{
    $id_usuario_html = '';
    if($usuario_baixa > 0){
        $id_usuario_html = $usuario_baixa;
    }
}

$sql_parceiro        = "SELECT nome'nome_usuario' FROM usuarios
                                         WHERE id_usuario = $id_usuario_emissao";
$query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
$nome_usuario = '';   
if (mysql_num_rows($query_parceiro)>0)
{
    $nome_usuario        = mysql_result($query_parceiro, 0,'nome_usuario');
}else{
    
    $sql_parceiro        = "SELECT nome'nome_usuario' FROM usuarios
                                         WHERE id_usuario = $id_usuario_s";
    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
    $nome_usuario = '';   
    if (mysql_num_rows($query_parceiro)>0)
    {
        $nome_usuario        = mysql_result($query_parceiro, 0,'nome_usuario');
    }
    
}

$sql_parceiro        = "SELECT nome'nome_usuario' FROM usuarios
                                         WHERE id_usuario = '$id_usuario_html'";
$query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
$nome_usuario_sessao = 'Não Confirmado';   
if (mysql_num_rows($query_parceiro)>0)
{
    $nome_usuario_sessao        = mysql_result($query_parceiro, 0,'nome_usuario');
}

$sql_venda  = "SELECT p.id_paciente, p.nome, p.data_nascimento FROM gui_pacientes p
                JOIN gui_guias g ON p.id_paciente = g.id_paciente
                WHERE g.id_guia = $id_guia";
$query_venda = mysql_query($sql_venda, $banco_painel) or die(mysql_error()." - 2");

if (mysql_num_rows($query_venda)>0)
{
    $id_cliente     = mysql_result($query_venda, 0, 'id_paciente');
    $nome_cliente   = mysql_result($query_venda, 0, 'nome');
    $data_nascimento   = mysql_result($query_venda, 0, 'data_nascimento');
}

$nome_parceiro = 'Sem Parceiro';
$sql   = "SELECT nome FROM parceiros
        WHERE id_parceiro = $id_parceiro AND del = 'N'";
$query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 4");

if (mysql_num_rows($query)>0)
{
    $nome_parceiro = mysql_result($query, 0, 'nome');
}

$sql   = "SELECT nome, tipo, endereco, numero, complemento, bairro, cidade, estado FROM gui_local_atendimento
WHERE id_local_atendimento = $id_local_atendimento AND ativo = 'S'";
$query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 3");
$nome_local_atendimento = '-';
$tipo_local_atendimento         = '';
$endereco_local_atendimento     = '';
$numero_local_atendimento       = '';
$complemento_local_atendimento  = '';
$bairro_local_atendimento       = '';
$cidade_local_atendimento       = '';
$estado_local_atendimento       = '';

if (mysql_num_rows($query)>0)
{
    $nome_local_atendimento         = mysql_result($query, 0, 'nome');
    $tipo_local_atendimento         = mysql_result($query, 0, 'tipo');
    $endereco_local_atendimento     = mysql_result($query, 0, 'endereco');
    $numero_local_atendimento       = mysql_result($query, 0, 'numero');
    $complemento_local_atendimento  = mysql_result($query, 0, 'complemento');
    $bairro_local_atendimento       = mysql_result($query, 0, 'bairro');
    $cidade_local_atendimento       = mysql_result($query, 0, 'cidade');
    $estado_local_atendimento       = mysql_result($query, 0, 'estado');
}

$status_list = array(
array("info" => "AGENDADO"),
array("warning" => "ABERTO"),
array("danger" => "PENDENTE"),
array("success" => "EMITIDO"),
array("danger" => "CANCELADO")
);
$ativo = $status;
if($ativo == 'AGENDADO'){
    $status_ = $status_list[0];
}elseif($ativo == 'ABERTO'){
    $status_ = $status_list[1];
}elseif($ativo == 'PENDENTE'){
    $status_ = $status_list[2];
}elseif($ativo == 'EMITIDO'){
    $status_ = $status_list[3];
}elseif($ativo == 'CANCELADO'){
    $status_ = $status_list[4];
}

if($local_pagamento == 'LOCAL'){
    $html_local_pagamento =  "NA EMISSÃO DESTA GUIA";
}else{
    $html_local_pagamento = "NO LOCAL DE ATENDIMENTO";
}
                        
                        

?>

<script>

function confirma_pagamento(id_pagamento){
{

  $("#bt_confirma_pagamento").attr("disabled", true);
  var id_usuario            = $("#id_usuario").val();
  var id_parceiro           = $("#id_parceiro").val();
  var id_cliente            = $("#id_cliente").val();
  var valor_confirma        = $("#valor_confirma").val();  

    $(".div_aguarde_2").hide(); 
    
    $.ajax({ 
     type: "POST",
     url:  "gui_editar_db.php",
     data: {
        item: 'confirmar_pagamento_guia',
        id_pagamento: id_pagamento,
        id_usuario: id_usuario,
        id_parceiro: id_parceiro,
        id_cliente: id_cliente,
        valor_confirma: valor_confirma
        },
     success: function(dados){
         if(dados == 'confirmado'){
            
             $('#bt_confirmar_'+id_pagamento).removeClass('btn-info').addClass('btn-danger').text('Cancelar');
             $(".div_aguarde_2").hide();
             //$('#pg_boleto_'+id_pagamento).removeClass('hide');
             $('#ajax').hide();
             $('.modal-backdrop').remove();
             $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else if(dados == 'nao_confirmado'){
            $('#bt_confirmar_'+id_pagamento).removeClass('btn-danger').addClass('btn-info').text('Confirmar');
            $(".div_aguarde_2").hide(); 
            //$('#pg_boleto_'+id_pagamento).addClass('hide');
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else{
            alert('erro');
         }
        $("#bt_confirma_pagamento").removeAttr("disabled");
     } 
     });    
        
  //}
   
}};
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $nome_cliente; ?> - Ns: <?php echo converte_data($data_nascimento) ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
       
        <input type="hidden" name="valor_confirma" id="valor_confirma" value="<?php echo $baixa_recebimento; ?>"/>
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_s; ?>"/>
        <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro_s; ?>"/>
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>"/>
            <h4>Informações</h4>
            <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Código da Guia:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> #<?php echo $id_guia; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Status:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"><span class="label label-sm label-<?php echo key($status_); ?>"><?php echo $ativo; ?></span></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Agendado para:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                        $diasemana_numero = date('w', strtotime($data_agendamento));
                        echo $diasemana[$diasemana_numero].", dia ".converte_data($data_agendamento).", as ".$hora_agendamento;
                         ?></p>
                    </div>
                </div>
            </div>
            </div>
            <!--/span-->
            <!--/span-->
             <div class="row">
             
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Local de atendimento:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $id_local_atendimento." - ".$tipo_local_atendimento." - ".$nome_local_atendimento; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>PAGAMENTO:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $html_local_pagamento; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
            <!--/span-->
            <div class="row">
            <div class="col-md-12">
            <table class="table table-hover table-light">
            <thead>
                <tr class="uppercase">
                    <th> # </th>
                    <th> Descrição </th>
                    <?php
                        if(in_array("42", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <th> Custo </th>
                    <?php
                        }
                    ?>
                    <th> Valor </th>
                </tr>
            </thead>
            <tbody>
                
           
                <?php
                    /*$sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";*/
    $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";
                    $query_procedimentos      = mysql_query($sql_procedimentos);
                                
                if (mysql_num_rows($query_procedimentos)>0)
                {  
                    $soma_preco_custo = 0;
                    $soma_valor_cobrado = 0;
                    while ($dados = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados); 
                        $preco_custo_calc = moeda_db($preco_custo);
                        $valor_cobrado_exibe = db_moeda($valor_cobrado);
                        
                        $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                        $soma_valor_cobrado = $soma_valor_cobrado + $valor_cobrado;
                        
                        echo "<tr>
                                <td> $codigo_procedimnto </td>
                                <td> $nome_procedimento </td>";
                        if(in_array("42", $verifica_lista_permissoes_array_inc)){
                            echo "<td> R$ $preco_custo </td>";
                        }
                                echo "<td> $valor_cobrado_exibe </td>
                            </tr>";
                    }
                }
            ?> 
            </tbody>
        </table>
            </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: right;">
                <?php
                    if(in_array("42", $verifica_lista_permissoes_array_inc)){
                ?>
                <div>Total de Custos: <strong><?php echo db_moeda($soma_preco_custo);?></strong></div> 
                <?php
                }?>
                <div>Total: <strong><?php echo db_moeda($soma_valor_cobrado);?></strong></div>
                <?php
                if($desconto > 0){
                ?>
                    <div>Desconto de: <strong><?php echo $html_desconto = ($tipo_desconto == 'por') ? $desconto."%" : db_moeda($desconto);?></strong></div>
                    <div>Novo valor total cobrado com desconto: <strong><?php echo db_moeda($valor_total_desconto);?></strong></div>
    
                <?php
                } 
                ?>
                </div>
            </div>
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Responsável pela emissão da guia:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $nome_usuario; ?></p>
                    </div>
                </div>
            </div>
            </div>
            <!--/span-->
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Responsável pela confirmação do recebimento:</strong> <?php echo converte_data($data_baixa); ?></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $nome_usuario_sessao; ?></p>
                    </div>
                </div>
            </div>
            </div>
      
            
    </div>
</div>
<div class="modal-footer">
<span class="div_aguarde_2" style="display: none;position: relative;width: 100%;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    <?php 
    if($baixa_recebimento == 'S'){
        $html_bt_confirmacao = "Cancelar Confirmação";
        $hml_cor_bt = 'red';
    }else{
        $html_bt_confirmacao = "Confirmar Pagamento";
         $hml_cor_bt = 'blue';
    }
    ?>
    <a href="javascript:" onclick="return confirma_pagamento(<?php echo $id_guia;?>);" class="btn <?php echo $hml_cor_bt ?>" id="bt_confirma_pagamento">
    <?php echo $html_bt_confirmacao ?>
    </a>
</div>