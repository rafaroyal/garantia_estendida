<html lang="pt_br">

<head>
        <meta charset="utf-8" />
        <title>Boletos</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        
        <link rel="shortcut icon" href="favicon.ico" /> 
         <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
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
.row_boleto div{
    float: left;
    position: relative; 
}
.linha{
    margin-bottom: 10px;
}
body{
    font-size: 11px;
}
@media print {
    .page_break_boleto {page-break-before: always;}
}
</style>
</head>
<body class="page-container-bg-solid page-header-menu-fixed">

<?php
require_once('sessao.php');
require_once('inc/functions.php');
require_once('inc/conexao.php'); 
$banco_painel = $link;
$id_ordem_pedido = (empty($_GET['id_ordem_pedido'])) ? "" : verifica($_GET['id_ordem_pedido']);  
$id_boleto_get   = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']); 
$id_usuario_s    = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s   = base64_decode($_COOKIE["usr_parceiro"]);


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
                    
                }elseif($slug == 'sorteio_ead'){
                    
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
                    
                
                }
            }
            
         }


$sql_opcoes  = "SELECT valor FROM opcoes
WHERE nome = 'porcento_multa_vencimento_boleto' OR nome = 'porcento_valor_diario_vencimento_boleto' OR nome = 'dias_nao_receber_atraso_boleto' ";
$query_opcoes = mysql_query($sql_opcoes, $banco_painel) or die(mysql_error()." - 2");

if (mysql_num_rows($query_opcoes)>0)
{
    $porcento_multa_vencimento_boleto          = mysql_result($query_opcoes, 0,0);
    $porcento_valor_diario_vencimento_boleto   = mysql_result($query_opcoes, 1,0);
    $dias_nao_receber_atraso_boleto            = mysql_result($query_opcoes, 2, 0);
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


<div class="modal-header">
    
    <button class="btn btn-primary" id="printButton">Imprimir</button>
    <button type="button" class="btn default" id="fechar_janela" >Fechar</button>
</div>
<div class="modal-body">
    <div class="row row_boleto" style="width: 700px;">
    
    <?php
    
    if(!empty($id_boleto_get) AND $id_boleto_get > 0){
        $sql        = "SELECT * FROM boletos_clientes
            WHERE id_ordem_pedido = $id_ordem_pedido AND pago = 'N' AND status_boleto = 0 AND id_boleto = $id_boleto_get";
    }else{
        $sql        = "SELECT * FROM boletos_clientes
            WHERE id_ordem_pedido = $id_ordem_pedido AND pago = 'N' AND status_boleto = 0";
    }
    
    

    $query      = mysql_query($sql, $banco_painel);
                    
    if (mysql_num_rows($query)>0)
    {
        $i = 1;
        while($dados = mysql_fetch_array($query)){
             extract($dados);
            
        ?>
        <div style="height: 350px;">
         <div style="width: 200px;height: 303px;border-bottom: 2px dashed #000000;border-right: 2px dashed #000000;margin-right: 10px;" class="folha" >
        <div class="linha" style="margin-top: 10px;">
            <div style="width: 200px;text-decoration: underline;text-align: center;">
                        <strong>Comprovante de Pagamento</strong>
            </div>
        </div>
    <div class="linha" style="padding-right: 10px;">
        <div style="width: 60px;">
            <div>
                <label class="control-label "><strong>Parcela:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                <?php echo $parcela." / ".$total_parcelas; ?></div>
            </div>
        </div>
        <div style="width: 125px;text-align: right;">
            <div>
                <label class="control-label "><strong>Valor da Parcela:</strong></label>
                <div class="col-md-12" style="border: 1px solid;padding: 5px 15px;width: 100%;">
                    <strong><?php echo db_moeda($valor_parcela); ?></strong>
                </div>
            </div>
        </div>
    </div>
     <div class="linha" style="padding-right: 10px; ">
            <div style="width: 180px;">
            <div>
                <label class="control-label "><strong>Cliente:</strong></label>
                <div class="col-md-12" style="width: 100%;font-size: 8px;">
                <?php echo $nome_cliente; ?></div>
            </div>
        </div>
      </div>
      <div class="linha" style="padding-right: 10px; ">
        <div style="width: 125px;text-align: left;">
            <div>
                <label class="control-label "><strong>Valor Pago:</strong></label>
                <div class="col-md-12" style="border: 1px solid;padding: 5px 15px;width: 100%;">
                    <strong>&nbsp;</strong>
                </div>
            </div>
        </div>
      
      </div>
      <div class="linha" style="padding-right: 10px; ">
        <div style="width: 150px;text-align: left;">
            <div>
                <label class="control-label "><strong>Data de Pagamento:</strong></label>
                <div class="col-md-12" style="border: 1px solid;padding: 5px 15px;width: 100%;">
                    <strong>&nbsp;</strong>
                </div>
            </div>
        </div>
      
      </div>
    </div>
    
        <div style="width: 490px;height: 303px;border-bottom: 2px dashed #000000;" class="folha" >
        <div class="linha" style="margin-top: 10px;">
            <div style="width: 316px;text-decoration: underline;">
                <strong>Carnê de Pagamento </strong>
            </div>
            <div style="width: 169px;text-align: right;text-decoration: underline;">
                <strong>emissão: <?php echo date("d/m/Y");?></strong>
            </div>
        </div>
        <div class="linha">
        <div style="width: 155px;">
            <div>
                <label class="control-label "><strong>Parcela:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                <?php echo $parcela." / ".$total_parcelas; ?></div>
            </div>
        </div>
        <div  style="width: 80px;">
            <div >
                <label class="control-label "><strong># Pedido:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                    <?php echo $id_ordem_pedido; ?>
                </div>
            </div>
        </div>
        <div  style="width: 112px;">
            <div >
                <label class="control-label "><strong>Info:</strong></label>
                <div class="col-md-12" style="width: 100%; font-size: 8px;padding: 0;">
                    Multa/atraso: <?php echo $porcento_multa_vencimento_boleto;?>% <br />
                    Multa/mês: <?php echo $porcento_valor_diario_vencimento_boleto;?>%<br />
                    Não receber: <?php echo $dias_nao_receber_atraso_boleto;?> dias/atraso.<br />
                </div>
            </div>
        </div>
        <div style="width: 138px;text-align: right;">
            <div >
                <label class="control-label "><strong>Vencimento em:</strong></label>
                <div class="col-md-12" style="border: 1px solid;padding: 5px 15px;width: 100%;">
                    <strong><?php echo converte_data($data_vencimento); ?></strong>
                </div>
            </div>
        </div>
        </div>
        <div class="linha">
        <div style="width: 347px;">
            <div >
                <label class="control-label "><strong>Cliente:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                    <?php echo $nome_cliente; ?>
                </div>
            </div>
        </div>
        <div style="width: 138px;text-align: right;">
            <div>
                <label class="control-label "><strong>Valor da Parcela:</strong></label>
                <div class="col-md-12" style="border: 1px solid;padding: 5px 15px;width: 100%;">
                    <strong><?php echo db_moeda($valor_parcela); ?></strong>
                </div>
            </div>
        </div>
        </div>
        <div class="linha">
            <div style="width: 219px;">
                <div >
                    <label class="control-label "><strong>Cod. Carnê:</strong></label>
                    <div class="col-md-12"style="width: 100%;">
                    <?php
                    
                    $valor_2 = "";
                    $seguencia_2 = str_pad($valor_2, 2, "0", STR_PAD_LEFT);
                    
                    $valor_numero = preg_replace("/[^0-9]/", "", $valor_parcela);
                    $valor_3 = $valor_numero;
                    $seguencia_3 = str_pad($valor_3, 7, "0", STR_PAD_LEFT);
                    
                    $valor_4 = $id_cliente;
                    $seguencia_4 = str_pad($valor_4, 6, "0", STR_PAD_LEFT);
                    
                    $valor_5 = $id_boleto;
                    $seguencia_5 = str_pad($valor_5, 5, "0", STR_PAD_LEFT);

                    $sequencias_exibe = $seguencia_2."-".$seguencia_3."-".$seguencia_4."-".$seguencia_5;
                    $sequencias_barra = $seguencia_2."".$seguencia_3."".$seguencia_4."".$seguencia_5;
                    $cod_boleto = str_pad($sequencias_barra, 20, "0", STR_PAD_LEFT);
                    echo $sequencias_exibe."<br />";
                        fbarcode($cod_boleto); ?>
                    </div>
                    
                </div>
            </div>
            <div style="width: 128px;text-align: right;">
                <div >
                    <label class="control-label"><strong>Total:</strong></label>
                    <div class="col-md-12" style="width: 100%;padding: 0;">
                       <?php echo db_moeda($valor_parcela); ?>
                    </div>
                </div>
            </div>
            <div style="width: 138px;text-align: right;">
                <div >
                    <label class="control-label "><strong>Cod. Cliente:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                        <?php echo $id_cliente; ?> <br />
                        <?php fbarcode($id_cliente); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
        <?php   
        if($i == 4)
        {
        ?>
        <div style=" page-break-after: always !important;"></div>
        <?php
        $i = 1;
        }else{
            $i++;
        }
        ?>
        </div>
        <?php
        }
     
    }
    
    ?>
    
    
    
    </div>     
        
</div>
<div class="modal-footer">
    
</div>

        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
       
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
    
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
