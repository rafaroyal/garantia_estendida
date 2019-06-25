<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$id_boleto = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']);  
$id_boleto = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']);
$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);

$sql        = "SELECT * FROM boletos_clientes
            WHERE id_boleto = $id_boleto";

$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);
        
}

if($id_usuario_recebimento == ''){
    $id_usuario_recebimento = 0;
    $id_usuario_html = $id_usuario_s;
}else{
    $id_usuario_html = '';
    if($usuario_baixa > 0){
        $id_usuario_html = $usuario_baixa;
    }
}

$sql_parceiro        = "SELECT nome'nome_usuario' FROM usuarios
                                         WHERE id_usuario = $id_usuario_recebimento";
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
$nome_usuario_sessao = '';   
if (mysql_num_rows($query_parceiro)>0)
{
    $nome_usuario_sessao        = mysql_result($query_parceiro, 0,'nome_usuario');
}

$sql_ordem   = "SELECT ordem_pedido FROM ordem_pedidos
                        WHERE id_ordem_pedido = $id_ordem_pedido";

        $query_ordem = mysql_query($sql_ordem, $banco_painel) or die(mysql_error()." - 0");
        
        if (mysql_num_rows($query_ordem)>0)
        {
            $ordem_pedido       = mysql_result($query_ordem, 0, 'ordem_pedido');
            //$status_recorrencia = mysql_result($query_ordem, 0, 'status_recorrencia');
    
            $array_id_base_ids_vendas = explode("|", $ordem_pedido);
            
            $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
            for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
            {
                $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                $id_base = $array_ids_base_vendas[0];
                $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                
                // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
                $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                            JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                            WHERE bpro.id_base_produto = $id_base";

                $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                
                if (mysql_num_rows($query_base)>0)
                {
                    $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                    $banco_user             = mysql_result($query_base, 0, 'banco_user');
                    $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                    $banco_host             = mysql_result($query_base, 0, 'banco_host');
                    $slug                   = mysql_result($query_base, 0, 'slug');
                    $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                    
                    $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                    //$array_slug_base_produto[]  = $slug;
                }
                $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
                
                if($slug == 'europ'){
                    
                    $sql_venda  = "SELECT c.id_cliente, c.nome FROM vendas v
                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                    WHERE v.id_venda = $ids_vendas[0]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente     = mysql_result($query_venda, 0, 'id_cliente');
                        $nome_cliente   = mysql_result($query_venda, 0, 'nome');
                    }
                    
                }/*elseif($slug == 'sorteio_ead'){
                    
                    $sql_venda   = "SELECT c.id_venda, c.nome FROM vendas_painel v
                                    JOIN vendas c ON v.id_venda = c.id_venda
                                    JOIN titulos t ON c.id_titulo = t.id_titulo
                                    WHERE v.id_venda_painel = $ids_vendas[0]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 5");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente      = mysql_result($query_venda, 0, 'id_venda');
                        $nome_cliente    = mysql_result($query_venda, 0, 'nome');
                    }
                    
                
                }*/
            }
            
         }





if($mes_referencia == '1'){
    $numero_mes_referencia = 'Janeiro';
}elseif($mes_referencia == '2'){
    $numero_mes_referencia = 'Fevereiro';
}elseif($mes_referencia == '3'){
    $numero_mes_referencia = 'Março';
}elseif($mes_referencia == '4'){
    $numero_mes_referencia = 'Abril';
}elseif($mes_referencia == '5'){
    $numero_mes_referencia = 'Maio';
}elseif($mes_referencia == '6'){
    $numero_mes_referencia = 'Junho';
}elseif($mes_referencia == '7'){
    $numero_mes_referencia = 'Julho';
}elseif($mes_referencia == '8'){
    $numero_mes_referencia = 'Agosto';
}elseif($mes_referencia == '9'){
    $numero_mes_referencia = 'Setembro';
}elseif($mes_referencia == '10'){
    $numero_mes_referencia = 'Outubro';
}elseif($mes_referencia == '11'){
    $numero_mes_referencia = 'Novembro';
}elseif($mes_referencia == '12'){
    $numero_mes_referencia = 'Dezembro';
}

$cortar_periodo_inicio_ano = substr($periodo_inicio, 0, 4);

?>

<script>

function confirma_pagamento(id_pagamento, id_usuario, id_parceiro, id_cliente, valor_confirma){
{
  $("#bt_confirma_pagamento_"+id_pagamento).attr("disabled", true);
  var id_usuario       = $("#id_usuario").val();
  var id_parceiro      = $("#id_parceiro").val();
  var valor_confirma   = $("#valor_confirma").val();
  var id_cliente       = $("#id_cliente").val();
    
    if($("#confirmar_pagamento").is(':checked')){
        var pagamento = 'S';
        recebimento = recebimento;
        valor_recebido = valor_recebido;
    }else{
        var pagamento = 'N';
        recebimento = '';
        valor_recebido = '';
    }

    $(".div_aguarde_2").hide(); 
    
    $.ajax({ 
     type: "POST",
     url:  "editar_db.php",
     data: {
        item: 'confirmar_pagamentos',
        id_pagamento: id_pagamento,
        id_usuario: id_usuario,
        id_parceiro: id_parceiro,
        id_cliente: id_cliente,
        valor_confirma: valor_confirma
        },
     success: function(dados){
         if(dados == 'confirmado'){
            
             $('#bt_confirmar_'+id_pagamento).removeClass('btn-danger').addClass('btn-success').html('<i class="fa fa-check"></i>');
             $(".div_aguarde_2").hide();
             //$('#pg_boleto_'+id_pagamento).removeClass('hide');
             $('#ajax').hide();
             $('.modal-backdrop').remove();
             $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else if(dados == 'nao_confirmado'){
            valor_confirma = 'S';
            $('#bt_confirmar_'+id_pagamento).html("<a href=\"javascript:\" onclick=\"return confirma_pagamento_direto('"+id_pagamento+"','"+id_usuario+"','"+id_parceiro+"','"+id_cliente+"','"+valor_confirma+"');\" class=\"btn btn-danger\" id=\"bt_confirma_pagamento_"+id_pagamento+"\"><i class=\"fa fa-thumbs-up\"></i></a>");
            $(".div_aguarde_2").hide(); 
            //$('#pg_boleto_'+id_pagamento).addClass('hide');
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else{
            alert('erro');
         }
        $("#bt_confirma_pagamento_"+id_pagamento).removeAttr("disabled");
     } 
     });    
        
  //}
   
}};
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $nome_cliente; ?> </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
        
        <?php
        
        if($usuario_baixa > 0){
            $valor_confirma = 'N';
        }else{
            $valor_confirma = 'S';
        }
        
        ?>
         <input type="hidden" name="valor_confirma" id="valor_confirma" value="<?php echo $valor_confirma; ?>"/>
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_s; ?>"/>
        <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro_s; ?>"/>
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>"/>
            <h4>Informações</h4>
            <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Parcela:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $parcela." de ".$total_parcelas; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Referência:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $numero_mes_referencia." de ".$ano_referencia; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Vencimento:</strong></label>
<input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="<?php echo converte_data($data_vencimento); ?>" readonly >

                </div>
            </div>
            <!--/span-->
            
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Valor parcela:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo db_moeda($valor_parcela)?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Valor Recebido:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo db_moeda($valor_recebido)?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Responsável pelo recebimento:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $nome_usuario; ?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Responsável pela confirmação do recebimento:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $nome_usuario_sessao; ?> - <?php echo converte_data($data_baixa); ?></p>
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
    if($valor_confirma == 'N'){
        $html_bt_confirmacao = "Cancelar Confirmação";
        $hml_cor_bt = 'red';
    }else{
        $html_bt_confirmacao = "Confirmar Pagamento";
         $hml_cor_bt = 'blue';
    }
    ?>
    <a href="javascript:" onclick="return confirma_pagamento(<?php echo $id_boleto;?>);" class="btn <?php echo $hml_cor_bt ?>" id="bt_confirma_pagamento">
    <?php echo $html_bt_confirmacao ?>
    </a>
</div>