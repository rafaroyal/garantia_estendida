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
    font-family: Arial,sans-serif!important;
}

.modal-body{
    padding: 0 0 0 30px;
}
@media print {
    .page_break_boleto {page-break-before: always;}
    strong{font-weight: bold!important;}
    body{
        font-family: Arial,sans-serif!important;
    }
}

#tabela_boleto{
    font-size: 8px;
    line-height: 1.7em;
}

#tabela_boleto, tr, td{
    border: 1px solid;
}

td, th {
    padding: 1px 4px;
}

#tabela_boleto span.normal{
    width: 100%;
    float: left;
    text-align: center;
    font-size: 12px;
}

#tabela_boleto span.normal_esquerda{
    width: 100%;
    float: left;
    text-align: left;
    font-size: 12px;
}

#tabela_boleto span.menor_direita{
    width: 100%;
    float: left;
    text-align: right;
    font-size: 10px;
}

#tabela_boleto span.menor_esquerda{
    width: 100%;
    float: left;
    text-align: left;
    font-size: 10px;
}

#tabela_boleto span.normal_bold{
    width: 100%;
    float: left;
    text-align: center;
    font-size: 12px;
    font-weight: bold;
}

#tabela_boleto span.normal_bold_direita{
    width: 100%;
    float: left;
    text-align: right;
    font-size: 12px;
    font-weight: bold;
}

#tabela_boleto span.grande{
    width: 100%;
    float: left;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
}

#tabela_boleto span.grande_direita{
    width: 100%;
    float: left;
    text-align: right;
    font-size: 14px;
    font-weight: bold;
}

hr{
    margin-top: 3px;
    margin-bottom: 9px;
    border-top: 1px dashed #000000;
}

.box_linha_geral{
    width: 100%;
    margin-bottom: 5px;
}

.box_linha_digitavel{
    width: 102px;
    margin: 0px 1.3px;
}

.margin_barra{
    margin-left: 40px;
    margin-bottom: 5px;
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
                    
                    $sql_venda  = "SELECT c.id_cliente, c.nome, c.cpf, c.endereco, c.numero, c.bairro, c.cidade, c.estado, c.cep FROM vendas v
                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                    WHERE v.id_venda = $ids_vendas[0]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente     = mysql_result($query_venda, 0, 'id_cliente');
                        $nome_cliente   = mysql_result($query_venda, 0, 'nome');
                        $cpf            = mysql_result($query_venda, 0, 'cpf');
                        $endereco       = mysql_result($query_venda, 0, 'endereco');
                        $numero_end     = mysql_result($query_venda, 0, 'numero');
                        $bairro         = mysql_result($query_venda, 0, 'bairro');
                        $cidade         = mysql_result($query_venda, 0, 'cidade');
                        $estado         = mysql_result($query_venda, 0, 'estado');
                        $cep            = mysql_result($query_venda, 0, 'cep');
                    }
                    
                }elseif($slug == 'sorteio_ead'){
                    
                    $sql_venda   = "SELECT c.id_venda, c.nome, c.cpf, c.endereco, c.numero, c.bairro, c.cidade, c.estado, c.cep FROM vendas_painel v
                                    JOIN vendas c ON v.id_venda = c.id_venda
                                    JOIN titulos t ON c.id_titulo = t.id_titulo
                                    WHERE v.id_venda_painel = $ids_vendas[0]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 5");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente      = mysql_result($query_venda, 0, 'id_venda');
                        $nome_cliente    = mysql_result($query_venda, 0, 'nome');
                        $cpf            = mysql_result($query_venda, 0, 'cpf');
                        $endereco       = mysql_result($query_venda, 0, 'endereco');
                        $numero_end     = mysql_result($query_venda, 0, 'numero');
                        $bairro         = mysql_result($query_venda, 0, 'bairro');
                        $cidade         = mysql_result($query_venda, 0, 'cidade');
                        $estado         = mysql_result($query_venda, 0, 'estado');
                        $cep            = mysql_result($query_venda, 0, 'cep');
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
    <div class="row row_boleto" style="width: 740px;">
    
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
                    
            $id_produto = 8; // identificar arrecadação
            $id_segmento = 9; //Carnes e Assemelhados ou demais Empresas / Órgãos que serão identificadas através do CNPJ.
            $id_valor_efetivo = 6; //ser reajustado por um índice
            $valor_numero = preg_replace("/[^0-9]/", "", $valor_parcela);
            $valor_3 = $valor_numero;
            $seguencia_3 = str_pad($valor_3, 11, "0", STR_PAD_LEFT);
            $valor_barra = $seguencia_3;
            //$numero_empresa = 11459541; // primerios 8 digitos do cnpj
            $numero_empresa = 3673; // primerios 8 digitos do cnpj
            $data_vencimento_convert = converte_data($data_vencimento);
            $venci = preg_replace("/[^0-9]/", "", $data_vencimento_convert);
            //$venci = str_pad($venci, 21, "0", STR_PAD_LEFT); //Identificação da Empresa/Órgão
            $id_boleto_barra = str_pad($id_boleto, 11, "0", STR_PAD_LEFT); //id boleto
            $cod_boleto_sem_dv = $id_produto.$id_segmento.$id_valor_efetivo.$valor_barra.$numero_empresa.$venci.$id_boleto_barra.$mes_referencia.$ano_referencia;
            
            $digito_veriv_geral = modulo_10($cod_boleto_sem_dv);
            $cod_boleto_com_dv = $id_produto.$id_segmento.$id_valor_efetivo.$digito_veriv_geral.$valor_barra.$numero_empresa.$venci.$id_boleto_barra.$mes_referencia.$ano_referencia;
            
           $id_boleto_barra = substr($id_boleto_barra, 3, 8);
    
    ?>
        
        
<table width="740" border="0" cellspacing="0" cellpadding="5" id="tabela_boleto">
  <tr>
    <!--<td rowspan="12" width="20">&nbsp;</td>-->
    <td width="120" rowspan="2"> <!--RECIBO <br />-->
    <!--<img src="assets/pages/img/logo_boleto.jpg" style="width: 75px;margin-left: 10px;" />-->
    </td>
    <td width="118" style="border-bottom: 2px solid;"><span class="grande">ARRECADAÇÃO</span></td>
    <td colspan="6" style="border-bottom: 2px solid;"><span class="grande"><?php echo monta_linha_digitavel($cod_boleto_com_dv);?></span></td>
  </tr>
  <tr>
    <td colspan="6">Local de pagamento <br />
    <span class="grande">PAGAR SOMENTE EM LOTÉRICAS E AFILIADAS</span>
    </td>
    <td width="139">Vencimento <br />
    <span class="grande_direita"><?php echo converte_data($data_vencimento); ?></span>
    </td>
  </tr>
  <tr>
    <td>Número do Documento <br />
    <span class="normal_bold"><?php echo $id_boleto_barra."-".$mes_referencia."/".$ano_referencia;?></span>
    </td>
    <td colspan="4">Cedente <br />
    <span class="normal">BERTOLINO & OLIVEIRA LTDA - ME</span>
    </td>
    <td colspan="2">CNPJ <br />
    <span class="normal">11.459.541/0001-07</span>
    </td>
    <td>Via <br />
    <span class="normal_bold_direita">Empresa</span>
    </td>
  </tr>
  <tr>
    <td>Data de Vencimento <br />
    <span class="normal_bold"><?php echo converte_data($data_vencimento); ?></span>
    </td>
    <td>Data do Documento <br />
    <span class="normal"><?php echo date("d/m/Y");?></span>
    </td>
    <td colspan="2">Número do Documento <br />
    <span class="normal_bold_direita"><?php echo $id_boleto_barra."-".$mes_referencia."/".$ano_referencia;?></span>
    </td>
    <td width="60">Espécie Doc. <br />
    <span class="normal">CARNET</span>
    </td>
    <td width="39">Aceite <br />
    <span class="normal">NÃO</span>
    </td>
    <td width="90">Processamento <br />
    <span class="normal"><?php echo date("d/m/Y");?></span>
    </td>
    <td>Nosso número <br />
    <span class="normal_bold_direita"><?php echo $id_boleto_barra."-".$mes_referencia."/".$ano_referencia;?></span>
    </td>
  </tr>
  <tr>
    <td>Via <br />
    <span class="normal_bold">Cliente</span>
    </td>
    <td>Reservado <br />
    <span class="normal">&nbsp;</span>
    </td>
    <td >Carteira <br />
    <span class="normal_bold">TRAIL</span>
    </td>
    <td>Espécie <br />
    <span class="normal_bold_direita">R$</span>
    </td>
    <td>Quantidade <br />
    <span class="normal">&nbsp;</span>
    </td>
    <td colspan="2">Valor <br />
    <span class="normal">&nbsp;</span>
    </td>
    <td>(=) Valor do documento <br />
    <span class="normal_bold_direita"><?php echo db_moeda2($valor_parcela); ?></span>
    </td>
  </tr>
  <tr>
    <td>Nosso número <br />
    <span class="normal_bold"><?php echo $id_boleto_barra."-".$mes_referencia."/".$ano_referencia;?></span>
    </td>
    <td colspan="6" rowspan="3">Instruções de responsabilidade do Cedente <br /> <br />
    <span class="grande" style="line-height: 1.22em;">
    >> NÃO RECEBER APÓS O VENCIMENTO << <br />
    <!--RECEBER ATÉ <?php echo $dias_nao_receber_atraso_boleto;?> DIAS DO VENCIMENTO--> &nbsp; <br />
    <!-- NÃO DISPENSAR MULTA E JUROS DE MORA!--> <br />
    <!--APÓS O VENCIMENTO COBRAR MULTA DE <?php echo $porcento_multa_vencimento_boleto;?>%, 
    JUROS DE <?php echo $porcento_valor_diario_vencimento_boleto;?>% AO MÊS--> 
    </span>
    </td>
    <td>(+) Multa <!-- de <?php echo $porcento_multa_vencimento_boleto;?>% --><br />
    <span class="normal_bold">&nbsp;</span>
    </td>
  </tr>
  <tr>
    <td>(=) Valor Documento <br />
    <span class="normal_bold"><?php echo db_moeda2($valor_parcela); ?></span>
    </td>
    <td>(+) Juros <!-- de <?php echo $porcento_valor_diario_vencimento_boleto;?>% ao mês --><br />
    <span class="normal_bold">&nbsp;</span>
    </td>
  </tr>
  <tr>
    <td>N° Parcela <br />
    <span class="normal_bold"><?php echo $parcela." de ".$total_parcelas; ?></span>
    </td>
    <td>(=) Valor cobrado <br />
    <span class="normal_bold">&nbsp;</span>
    </td>
  </tr>
  <tr>
    <td>(+) Multa <!-- de <?php echo $porcento_multa_vencimento_boleto;?>% --> <br />
    <span class="normal_bold">&nbsp;</span>
    </td>
    <td colspan="7" rowspan="2">Sacado <br />
    <span class="normal_esquerda"><?php echo $nome_cliente; ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; CPF <?php echo mask_total($cpf, '###.###.###-##'); ?><br />
    <?php echo $endereco; ?>, <?php echo $numero_end; ?> <?php echo $bairro; ?> - <?php echo mask_total($cep, "#####-###"); ?> <?php echo $cidade; ?></span>
    <span class="normal_esquerda">Código de baixa: <strong><?php echo $id_boleto;?></strong></span>
    </td>
  </tr>
  <tr>
    <td>(+) Juros <!-- de <?php echo $porcento_valor_diario_vencimento_boleto;?>% ao mês --><br />
    <span class="normal_bold">&nbsp;</span>
    </td>
  </tr>
  <tr>
    <td>(=) Valor Cobrado <br />
    <span class="normal_bold">&nbsp;</span>
    </td>
    <td colspan="7" rowspan="2"> <span class="menor_direita">Autenticação mecânica</span>
    <span class="normal_esquerda margin_barra">
    <div class="box_linha_geral">
    <div class="box_linha_digitavel"><?php echo monta_linha_digitavel_p1($cod_boleto_com_dv); ?></div>
    <div class="box_linha_digitavel"><?php echo monta_linha_digitavel_p2($cod_boleto_com_dv); ?></div>
    <div class="box_linha_digitavel"><?php echo monta_linha_digitavel_p3($cod_boleto_com_dv); ?></div>
    <div class="box_linha_digitavel"><?php echo monta_linha_digitavel_p4($cod_boleto_com_dv); ?></div>
    </div>
    <?php
    
    //echo monta_linha_digitavel($cod_boleto_com_dv)."<br/>";
            fbarcode($cod_boleto_com_dv); 
    
    ?></span>
    </td>
  </tr>
  <tr>
    <td>Sacado <br />
    <span class="menor_esquerda"><?php echo mask_total($cpf, '###.###.###-##'); ?></span>
    </td>
  </tr>
</table>
<hr />
        
        <?php
        }
     
    }
    
    ?>
    
    
    
    </div>     
        
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
