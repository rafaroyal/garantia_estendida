<script src="assets/pages/scripts/table-datatables-scroller_2.min.js" type="text/javascript"></script>
<script>

function confirma_pagamento_direto(id_pagamento, id_usuario, id_parceiro, id_cliente, valor_confirma){
{

  $("#bt_confirma_pagamento_"+id_pagamento).attr("disabled", true);
  //var id_usuario       = $("#id_usuario").val();
  //var id_parceiro      = $("#id_parceiro").val();
  //var valor_confirma   = $("#valor_confirma").val();
  //var id_cliente       = $("#id_cliente").val();
    
    
    //$(".div_aguarde_2").hide(); 
    
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
            $('#bt_confirmar_'+id_pagamento).html('<a href="inc/ver_confirmar_pagamento.php?id_boleto='+id_pagamento+'" id="bt_confirmar_'+id_pagamento+'" data-target="#ajax" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>');
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
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;

$id_usuario_pagamento       = (empty($_GET['id_usuario_pagamento'])) ? "" : verifica($_GET['id_usuario_pagamento']);  
$where = '';

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$data_inicio = '2018-11-01';
$agora 			= date("Y-m-d");

$sql_user_nome        = "SELECT u.nome'nome_usuario', p.nome'nome_parceiro' FROM usuarios u
JOIN parceiros p ON u.id_parceiro = p.id_parceiro 
WHERE id_usuario = $id_usuario_pagamento";
$query_user_nome      = mysql_query($sql_user_nome, $banco_painel);
$nome_user = '-';
$nome_parc = '-';
if(mysql_num_rows($query_user_nome)>0)
{
    $nome_user = mysql_result($query_user_nome, 0,'nome_usuario');
    $nome_parc = mysql_result($query_user_nome, 0,'nome_parceiro');  
}
?>  
<div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase">Lista de Pagamentos | <?php echo $nome_user." | ".$nome_parc; ?> </span> 
        </div>
        <div class="tools"> </div>
        
    </div>
    <div class="portlet-body table-both-scroll">
        <!--<div class="col-md-6">&nbsp;</div>-->
        <?php
        /*$sql = "SELECT SUM(valor_recebido) FROM boletos_clientes
        WHERE pago = 'S' AND baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2) AND id_usuario_recebimento = $id_usuario_pagamento AND tipo_recebimento IN ('AV', 'BO', 'CA')";
        $query          = mysql_query($sql);
        $valor_a_confirmar = "";                
        if (mysql_num_rows($query)>0)
        {
           $valor_a_confirmar = mysql_result($query, 0,0);
        }*/
        
        ?>
        <!--<div class="col-md-3">À confirmar: <label id="html_valor_a_confrmar"><strong><?php echo db_moeda($valor_a_confirmar); ?></strong></label></div>-->
        <?php
        /*$sql = "SELECT SUM(valor_recebido) FROM boletos_clientes
        WHERE pago = 'S' AND baixa_recebimento = 'S' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2) AND data_baixa = '$agora' AND id_usuario_recebimento = $id_usuario_pagamento AND tipo_recebimento IN ('AV', 'BO', 'CA')";
        $query          = mysql_query($sql);
        $valor_confirmado = "";                
        if (mysql_num_rows($query)>0)
        {
           $valor_confirmado = mysql_result($query, 0,0);
        }*/
        
        ?>
        <!--<div class="col-md-3">Confirmado hoje: <label id="html_valor_confrmado"><strong><?php echo db_moeda($valor_confirmado); ?> </strong></label></div>-->
        
        
        <?php

            $status_list = array(
                array("green" => "Pago"),
                array("red" => "Receber")
            );
            
            // status_boleto = 0 (ativo)
            // status_boleto = 1 (concluido)
            // status_boleto = 2 (cancelado)
            $sql        = "SELECT * FROM boletos_clientes
            WHERE pago = 'S' AND ((baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2)) OR (baixa_recebimento = 'S' AND status_boleto IN (0,1,2) AND data_baixa = '$agora')) AND id_usuario_recebimento = $id_usuario_pagamento AND tipo_recebimento IN ('AV', 'BO', 'CA')
                        ORDER BY data_pagamento DESC";
            $query          = mysql_query($sql, $banco_painel);
            //$query_contar   = mysql_query($sql, $banco_painel); 
            //echo $sql;               
            if (mysql_num_rows($query)>0)
            {
              echo "<table class=\"table table-striped table-bordered table-hover order-column\" id=\"sample_11\">
                                                <thead>
                                                    <tr>
                                                            <th> # </th>
                                                            <th> Cliente </th>
                                                            <th> Parceiro </th>
                                                            <th> Parcela </th>
                                                            <th> Tipo </th>
                                                            <th> R$ Pago </th>
                                                            <th> Data pag.</th>
                                                            <th width='10%'> info </th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados); 
               
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
  
                    
                $sql_cliente_ordem        = "SELECT ordem_pedido FROM ordem_pedidos
                                             WHERE id_ordem_pedido = $id_ordem_pedido";
                $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                   
                if (mysql_num_rows($query_cliente_ordem)>0)
                {
                    $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                }        
       
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                $pegar_nome_ok = false;
                $nome_cliente = '';
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
                    }
                    $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
                    
                    if($slug == 'europ'){
                        
                        if($pegar_nome_ok == false){
                            
                            $sql_venda  = "SELECT v.metodo_pagamento, c.id_cliente, c.nome FROM vendas v
                                        JOIN clientes c ON v.id_cliente = c.id_cliente
                                        WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                            
                            if (mysql_num_rows($query_venda)>0)
                            {
                                $id_cliente         = mysql_result($query_venda, 0, 'id_cliente');
                                $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $pegar_nome_ok = true;
                            }
                        
                        }
                    
                    }
                    
                }
                    
                    $html_exibe_bt_confirma = '';
                    if($nivel_usuario == 'A' AND in_array("41", $verifica_lista_permissoes_array_inc)){
                        
                        if($baixa_recebimento == 'N'){
                            $html_texto_bt_confirma = "<i class=\"fa fa-thumbs-up\"></i>";
                            $html_class_cor = "btn-danger";
                            $valor_confirma = 'S';
                            //$html_bt_confirmacao = "Cancelar Confirmação";
                            //$hml_cor_bt = 'red';    
                            $html_exibe_bt_confirma = "<span id=\"bt_confirmar_$id_boleto\"><a href=\"javascript:\" onclick=\"return confirma_pagamento_direto('$id_boleto','$usr_id','$usr_parceiro','$id_cliente','$valor_confirma');\" class=\"btn $html_class_cor\" id=\"bt_confirma_pagamento_$id_boleto\">$html_texto_bt_confirma</a></span>";
                        }else{
                            $html_texto_bt_confirma = "<i class=\"fa fa-check\"></i>";
                            $html_class_cor = "btn-success";
                            $valor_confirma = 'N';
                            //$html_bt_confirmacao = "Confirmar Pagamento";
                            //$hml_cor_bt = 'blue'; 
                             $html_exibe_bt_confirma = '<span id="bt_confirmar_'.$id_boleto.'"><a href="inc/ver_confirmar_pagamento.php?id_boleto='.$id_boleto.'" id="bt_confirmar_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a></span>';
                        }
                        
                      

                    
                        
                    }
   
                    $status_list = array(
                        array("green" => "<i class=\"fa fa-money\"></i>"),
                        array("red" => "Receber")
                    );
                    
                    if($pago == 'N')
                    {
                        $status = $status_list[1];
                    }
                    else
                    {
                        $status = $status_list[0];
                    }
                    
                    $pg_html_exibe = 'hide';
                    if($pago == 'S'){
                        $pg_html_exibe = '';
                    }
                    
                    if($metodo_pagamento == 'BO'){
                        $html_metodo_pagamento = 'BOLETO';
                    }elseif($metodo_pagamento == 'MA'){
                        $html_metodo_pagamento = 'MAQUINA';
                    }elseif($metodo_pagamento = 'ON'){
                        $html_metodo_pagamento = 'ON-LINE';
                    }
                    
                    $html_tipo = '';
                    if($entrada == 'S'){
                        $html_tipo = "ENTRADA-".$tipo_recebimento;
                    }else{
                        $html_tipo = $tipo_recebimento.' '.$html_metodo_pagamento;
                    }
                    
                    echo "<tr>
                     <td>$id_boleto</td>
                     <td><a href=\"inc/ver_cliente.php?id_cliente=$id_cliente&id_produto=6&tipo=produto\" data-target=\"#ajax\" data-toggle=\"modal\" >$nome_cliente </a></td>
                     <td>$nome_parceiro</td>
                     <td>$parcela / $total_parcelas</td>
                     <td>$html_tipo</td>
                     <td>$valor_recebido</td>
                     <td>".converte_data($data_pagamento)."</td>
                     <td>
                     $html_exibe_bt_confirma <a href=\"inc/ver_comprovante_cliente.php?id_boleto=$id_boleto\" id=\"pg_boleto_$id_boleto\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn btn-sm purple$pg_html_exibe\"><i class=\"fa fa-barcode\"></i></a></td></tr>";

                        }
                        echo "</tbody>
                                                </table>";
                        }
                        ?>
                                        </div>
                <!--<div class="modal fade modal-scroll" id="ajax" role="basic" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                        <span> &nbsp;&nbsp;Aguarde... </span>
                    </div>
                </div>
            </div>
        </div>-->