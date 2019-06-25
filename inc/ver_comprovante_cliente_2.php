<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
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
$html_ver_copr_boleto = 'N. Comprovante: <br/>';
if($comprovante == ''){
    $html_ver_copr_boleto = 'Cod. boleto: <br/>';
    $comprovante = $id_boleto;
}


$sql        = "SELECT nome'nome_parceiro' FROM parceiros
            WHERE id_parceiro = $id_parceiro_recebimento";
$query      = mysql_query($sql, $banco_painel);
$nome_parceiro = '';                
if (mysql_num_rows($query)>0)
{
    $nome_parceiro = mysql_result($query, 0);    
}

$sql        = "SELECT nome'nome_usuario' FROM usuarios
            WHERE id_usuario = $id_usuario_recebimento";
$query      = mysql_query($sql, $banco_painel);
$nome_usuario_receb = '';                
if (mysql_num_rows($query)>0)
{
    $nome_usuario_receb = mysql_result($query, 0);    
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
                
                // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
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
                        //$id_cliente      = mysql_result($query_venda, 0, 'id_venda');
                        //$nome_cliente    = mysql_result($query_venda, 0, 'nome');
                    }
                    
                
                }*/
            }
            
         }

if($mes_referencia == '1'){
    $numero_mes_referencia = 'Janeiro';
}elseif($mes_referencia == '2'){
    $numero_mes_referencia = 'Fevereiro';
}elseif($mes_referencia == '3'){
    $numero_mes_referencia = 'Mar�o';
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

$sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
VALUES ('$id_cliente', '$id_usuario_s', 'clientes', 'Exibir comprovante pagamento id: $id_boleto', '".agora()."')";   
$query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");
?>
<script>


jQuery(document).ready(function() {    
   
   jQuery('#printButton').on('click', function () {
    $(".modal-header").hide();
    window.print();
    $(".modal-header").show();
});

jQuery('#fechar_janela').on('click', function () {
    window.close();
});
   
});


</script>
<style>
@media print {
    .hidden-print {display: none; }
}
@page{
margin-left: 0cm;
margin-right: 0cm;
margin-top: 0.3cm;
margin-bottom: 0cm;
}

.modal-body{
    width: 260px;
}

.label_comprovante_lc{
    text-align: left;
}

.label_comprovante_rc{
    text-align: right;
}

</style>

<div class="modal-body" style='font-family: "Arial",sans-serif;text-transform: uppercase;'>
<button class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();"> Imprimir comprovante
</button>
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="text-align: center;line-height: 1.15em;font-size: 12px;font-family: monospace;">
        
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_s; ?>"/>
        <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro_s; ?>"/>
            <div style="font-weight: bold;">TRAIL ASSIST�NCIA TOTAL</div>
            <div >COMPROVANTE DE PAGAMENTO</div>
            <div >N�O � DOCUMENTO FISCAL</div>

             <div class="row">
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">Data impress�o:</label>      
                </div>
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><?php echo converte_data(agora());?> </label>
                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row" style="border-top: 1px dashed #000000;border-bottom: 1px dashed #000000;">
                <div class="col-md-12">
                        <label class="control-label col-md-12" style="font-weight: bold;"><?php echo $nome_cliente;?> </label>
                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                        <label class="control-label col-md-12 label_comprovante_lc" style="float: left;"><?php echo $html_ver_copr_boleto?> </label>

                </div>
                <div class="col-md-6">
                        <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><?php echo $comprovante;?> </label>

                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">Parcela: </label>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><?php echo $parcela." de ".$total_parcelas; ?></label>
                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">Vencimento: </label> 
                </div>
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><?php echo converte_data($data_vencimento); ?></label>
                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">Data Pagamento: </label>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><?php echo converte_data($data_pagamento); ?> �s <?php echo $hora_pagamento;?></label>
                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">Valor do Documento: </label>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><?php echo db_moeda($valor_recebido); ?></label>
                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row" style="border-top: 1px dashed #000000;">
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">OPERADOR:</label>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><? echo $nome_parceiro; ?></label>
                </div>
                <!--/span-->
            </div>
            <br />
            <div class="row" style="border-bottom: 1px dashed #000000;">
                <div class="col-md-6">
                        <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">USU�RIO:</label>
                </div>
                <div class="col-md-6">
                        <label class="control-label col-md-12 label_comprovante_rc" style="float: right;"><? echo $id_usuario_recebimento." - ".$nome_usuario_receb; ?></label>
                </div>
                <!--/span-->
            </div>
            <br />
             <div class="row" style="border-bottom: 1px dashed #000000;">
             <div class="col-md-12">
                        <label class="control-label col-md-12 label_comprovante_lc" style="float: left;">Mantenha suas parcelas em dia e aproveite todos os benef�cios da sua Assist�ncia Familiar.</label>
                </div>
             </div>
        </div>
    </div>
    
</div>
