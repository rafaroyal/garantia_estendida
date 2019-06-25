<?php

require_once(__DIR__.'/../../../sessao.php');
require_once(__DIR__.'/../../../inc/functions.php');
require_once(__DIR__.'/../../../inc/conexao.php'); 
$banco_painel = $link;
$id_ordem_pedido = (empty($_GET['id_ordem_pedido'])) ? "" : verifica($_GET['id_ordem_pedido']);  
$id_boleto_get   = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']); 
$id_usuario_s    = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s   = base64_decode($_COOKIE["usr_parceiro"]);
$pasta = base64_decode($_COOKIE["pasta"]);
$diretorio_links = $pasta;
?>

<html lang="pt_br">

<head>
        <meta charset="utf-8" />
        <title>Boletos</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        
        <link rel="shortcut icon" href="<?php echo $diretorio_links; ?>/../../../../favicon.ico" /> 
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
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

@media print {
    .page_break_boleto {page-break-after: always; }
    strong{font-weight: bold!important;}
    body{
        font-family: Arial,sans-serif!important;
    }
}
@page{
margin-left: 0.8cm;
margin-right: 0cm;
margin-top: 1cm;
margin-bottom: 0.8px;
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
    letter-spacing: 0.04em;
}

#tabela_boleto span.grande_direita{
    width: 100%;
    float: left;
    text-align: right;
    font-size: 14px;
    font-weight: bold;
}

hr{
    margin-top: 2px;
    margin-bottom: 2px;
    border-style: dashed;
    border-color: #000;
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

.border_esquerda_none{
    border-left-color: #fff;
}

.border_direita_none{
    border-right-color: #fff;
}

.border_topo_none{
        border-top-color: #fff;
}

.border_base_none{
    border-bottom-color: #fff;
}

</style>
</head>
<body class="page-container-bg-solid page-header-menu-fixed">

<?php
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
                    
                    $sql_venda  = "SELECT c.id_cliente, c.id_parceiro, c.nome, c.cpf, c.endereco, c.numero, c.bairro, c.cidade, c.estado, c.cep FROM vendas v
                            JOIN clientes c ON v.id_cliente = c.id_cliente
                            WHERE v.id_venda = $ids_vendas[0]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente     = mysql_result($query_venda, 0, 'id_cliente');
                        $id_parceiro    = mysql_result($query_venda, 0, 'id_parceiro');
                        $nome_cliente   = mysql_result($query_venda, 0, 'nome');
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

$sql_opcoes  = "SELECT nome, logo, logo_proposta FROM parceiros
WHERE id_parceiro = $id_parceiro";
$query_opcoes = mysql_query($sql_opcoes, $banco_painel) or die(mysql_error()." - 2");
$nome_parceiro = '';
if (mysql_num_rows($query_opcoes)>0)
{
    $nome_parceiro = mysql_result($query_opcoes, 0,0);
    $logo          = mysql_result($query_opcoes, 0,1);
    $logo_parceiro = mysql_result($query_opcoes, 0,2);
}

$sql_opcoes  = "SELECT nome FROM usuarios
WHERE id_usuario = $id_usuario_s";
$query_opcoes = mysql_query($sql_opcoes, $banco_painel) or die(mysql_error()." - 2");
$nome_usuario = '';
if (mysql_num_rows($query_opcoes)>0)
{
    $nome_usuario = mysql_result($query_opcoes, 0,0);
}

$sql_opcoes  = "SELECT valor FROM opcoes
WHERE nome = 'porcento_multa_vencimento_boleto' OR nome = 'porcento_valor_diario_vencimento_boleto' OR nome = 'dias_nao_receber_atraso_boleto' OR nome = 'valor_desconto_pagamento_em_dia' ";
$query_opcoes = mysql_query($sql_opcoes, $banco_painel) or die(mysql_error()." - 2");

if (mysql_num_rows($query_opcoes)>0)
{
    $porcento_multa_vencimento_boleto          = mysql_result($query_opcoes, 0,0);
    $porcento_valor_diario_vencimento_boleto   = mysql_result($query_opcoes, 1,0);
    $dias_nao_receber_atraso_boleto            = mysql_result($query_opcoes, 2, 0);
    $valor_desconto_pagamento_em_dia           = mysql_result($query_opcoes, 3, 0);
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


$sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
                    VALUES ('$id_cliente', '$id_usuario_s', 'clientes', 'Ver boleto(s) de pagamento', '".agora()."')";   
$query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");


$verifica    = "SELECT * FROM controle_entregas
            WHERE id_entrega = '$id_controle_entregas'";
    
$query_verifica    = mysql_query($verifica, $banco_painel);
     
if (mysql_num_rows($query_verifica)>0)
{
    $id_entrega         = mysql_result($query_verifica, 0,0);
    $sel_tipo_entrega   = mysql_result($query_verifica, 0,2);
    $sql2    = "UPDATE controle_entregas SET imprimir = 'S' 
            WHERE id_entrega = $id_controle_entregas";
    $query2  = mysql_query($sql2, $banco_painel) OR DIE (mysql_error());
    
}

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
            
        $agora 			= date("Y-m-d H:i:s");
        $verifica           = "SELECT * FROM controle_impressao 
                            WHERE id_referencia = $id_boleto_get AND tipo_impressao = 'boleto_banco'";
        $query_verifica    = mysql_query($verifica, $banco_produto);
        $html_segunda_via = "";     
        if (mysql_num_rows($query_verifica)>0)
        {
            $sql2    = "UPDATE controle_impressao SET data_impressao = '$agora' WHERE id_referencia = $id_boleto_get AND tipo_impressao = 'boleto_banco'";
            $query2  = mysql_query($sql2, $banco_produto) OR DIE (mysql_error());
            $html_segunda_via = "2° via";
        }
        else
        {
            $sql3    = "INSERT INTO controle_impressao (tipo_impressao, id_referencia, data_impressao)
                    VALUES ('boleto_banco', '$id_boleto_get', '$agora')";
        
            $query3  = mysql_query($sql3, $banco_produto) or die(mysql_error());
            
        }
    }else{
        $sql        = "SELECT * FROM boletos_clientes
            WHERE id_ordem_pedido = $id_ordem_pedido AND pago = 'N' AND status_boleto = 0";
            
        $agora 			= date("Y-m-d H:i:s");
        $verifica           = "SELECT * FROM controle_impressao 
                            WHERE id_referencia = $id_ordem_pedido AND tipo_impressao = 'boleto_banco_carnet'";
        $query_verifica    = mysql_query($verifica, $banco_produto);
        $html_segunda_via = "";     
        if (mysql_num_rows($query_verifica)>0)
        {
            $sql2    = "UPDATE controle_impressao SET data_impressao = '$agora' WHERE id_referencia = $id_ordem_pedido AND tipo_impressao = 'boleto_banco_carnet'";
            $query2  = mysql_query($sql2, $banco_produto) OR DIE (mysql_error());
            $html_segunda_via = "2° via";
        }
        else
        {
            $sql3    = "INSERT INTO controle_impressao (tipo_impressao, id_referencia, data_impressao)
                    VALUES ('boleto_banco_carnet', '$id_ordem_pedido', '$agora')";
        
            $query3  = mysql_query($sql3, $banco_produto) or die(mysql_error());
            
        }
            
            
    }
    
    

    $query      = mysql_query($sql, $banco_painel);
                    
    if (mysql_num_rows($query)>0)
    {
        $i = 1;
        $contar_quebra = 1;
        $html_quebra_pagina = '';
        $i_r = 0;  
        $contar_linhas_while = mysql_num_rows($query); 
        while($dados = mysql_fetch_array($query)){
            extract($dados);
             
            $valor_parcela = $valor_parcela + $valor_desconto_pagamento_em_dia;
            $valor_parcela = number_format($valor_parcela, 2);
            $data_venc_dias = converte_data($data_vencimento);
            $data_venc_dias = str_replace('/', '-', $data_venc_dias);
            $data_venc_30_dias = date('d/m/Y', strtotime('30 days',strtotime($data_venc_dias)));
            $data_venc_30_dias = converte_data($data_venc_30_dias);
            $agora_para_venc   = date("Y-m-d");
            $verifica_atrasado = false;
            if(strtotime($data_venc_30_dias) < strtotime($agora_para_venc)){
                $agora_para_venc = converte_data($agora_para_venc);
                $agora_para_venc = str_replace('/', '-', $agora_para_venc);
                $agora  = date("Y-m-d");
                // Calcula a diferença em segundos entre as datas
                $diferenca = strtotime($agora) - strtotime($data_vencimento);
                //Calcula a diferença em dias
                $dias_de_atrado = floor($diferenca / (60 * 60 * 24));
                
                
                
                 $juros_de_atraso = porcentagem_juros_atraso($valor_parcela, $porcento_multa_vencimento_boleto);
                 $porcento_valor_diario_vencimento_boleto_calc = $porcento_valor_diario_vencimento_boleto / 30;                    
                 $juros_diario =  (($porcento_valor_diario_vencimento_boleto_calc * $dias_de_atrado));
                
                 $valor_atualizado = number_format($juros_de_atraso, 2, '.', '') + $valor_parcela + $juros_diario;
                 
                 //$valor_atualizado = number_format($valor_atualizado, 2, '.', '');
                  $valor_parcela = $valor_atualizado;
                  $valor_parcela = db_moeda2($valor_parcela);
                  $valor_parcela = str_replace(',', '.', $valor_parcela);
                
                $data_vencimento = date('d/m/Y', strtotime('5 days',strtotime($agora_para_venc)));
                $data_vencimento = converte_data($data_vencimento);
                
                $sql_nova_data    = "UPDATE boletos_clientes SET valor_parcela = '$valor_parcela', data_vencimento = '$data_vencimento' WHERE id_boleto = $id_boleto";
                $query_nova_data  = mysql_query($sql_nova_data, $banco_painel) OR DIE (mysql_error());
                $verifica_atrasado = true;
            }

if($i_r == 0){
?>
    <div style="height: 600px;" <?php echo $html_quebra_pagina;?>>
        <div style="width: 690px;height: 303px;border-bottom: 2px dashed #000000;" class="folha" >
        <div class="linha" style="margin-top: 10px;">
            <div style="width: 521px;text-decoration: underline;">
                <strong>RECIBO DE ENTREGA</strong>
            </div>
            <div style="width: 169px;text-align: right;text-decoration: underline;">
                <strong>Data: <?php echo converte_data(agora()); ?></strong>
            </div>
        </div>
        <div class="linha">
            <div style="width: 80px;">
                <div>
                    <label class="control-label "><strong># Pedido:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                    <?php echo $id_ordem_pedido;?>
                    </div>
                </div>
            </div>
            
            <div style="width: 472px;">
                <div >
                    <label class="control-label "><strong>Tipo Entrega:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                    <?php echo $sel_tipo_entrega;?>    
                    </div>
                </div>
            </div>
            <div style="width: 138px;text-align: right;">
                <div >
                    <label class="control-label "><strong>Vencimento 1° parcela:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                        <?php echo converte_data($data_vencimento); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="linha">
        <div style="width: 294px;">
            <div>
                <label class="control-label "><strong>Cliente:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                 <?php echo $nome_cliente; ?>
                </div>
            </div>
        </div>
        <div style="width: 295px;">
            <div>
                <label class="control-label "><strong>Endereço:</strong></label>
                <div class="col-md-12" style="width: 100%;padding: 0;">
                    <?php echo $endereco; ?>, <?php echo $numero_end; ?> <?php echo $bairro; ?> - <?php echo mask_total($cep, "#####-###"); ?> <?php echo $cidade; ?>-<?php echo $estado; ?>
                </div>
            </div>
        </div>
        <div style="width: 100px;text-align: right;">
            <div>
                <label class="control-label "><strong>Valor da Parcela:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                    <?php echo db_moeda2($valor_parcela); ?>
                </div>
            </div>
        </div>
        </div>
        <div class="linha">
            <div style="width: 294px;">
                <div >
                    <label class="control-label "><strong>Funcionário:</strong></label>
                    <div class="col-md-12"style="width: 100%;">
                    <?php echo $nome_usuario;?>
                    </div>
                    
                </div>
            </div>
            <div style="width: 295px;">
                <div>
                    <label class="control-label"><strong>Parceiro:</strong></label>
                    <div class="col-md-12" style="width: 100%;padding: 0;">
                       <?php echo $nome_parceiro;?>
                    </div>
                </div>
            </div>
            <div style="width: 100px;text-align: right;">
                <div>
                    <label class="control-label "><strong>Cod. Recibo:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                        <?php echo $id_entrega ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="linha">
            <div style="width: 690px;">
                <div>
                    <label class="control-label "><strong>Descrição:</strong></label>
                    <div class="col-md-12"style="width: 100%;">
                    Recebi da <strong>Trail Serviços Assistência Familiar</strong>, meu carnê de pagamento. E estou ciente das condições presentes neste carnê de pagamento e sua data de vencimento.
                    </div>
                </div>
            </div>
            
        </div>
        <div class="linha">
            <div style="width: 485px;">
                <div>
                    <label class="control-label "><strong>&nbsp;</strong></label>
                    <div class="col-md-12"style="width: 100%;">
                    Assinatura do Cliente/Responsável:_________________________________________
                    </div>
                </div>
            </div>
            <div style="width: 205px;text-align: right;">
                <div>
                    <div class="col-md-12" style="width: 100%;">
                         <img alt="" style="width: 150px;" src="<?php echo $diretorio_links; ?>/../../../../assets/pages/img/logos/<?php echo $logo; ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="width: 690px;height: 303px;" class="folha" >
        <div class="folha thumbnail" style="width: 300px; line-height: 1.8em;padding: 10px;margin: 20px; font-size: 12px;">
            
            <small> Destinatário </small> <br/>
            <strong><?php echo $nome_cliente; ?></strong> <br/>
            <?php echo $endereco; ?>, N° <?php echo $numero_end;?><br/>
            <?php $complemento = (!empty($complemento)) ? $complemento."<br/>" : ''; echo $complemento;?>
            <?php echo $bairro; ?><br/>
            <?php echo mask_total($cep, "#####-###"); ?> <?php echo $cidade;?> - <?php echo $estado;?><br/>                  
        </div>
        <div class="folha thumbnail" style="width: 300px; line-height: 1.8em;padding: 10px;margin: 20px; font-size: 12px;">
            
            <small> Remetente </small> <br/>
            <strong>REALIZA+SAÚDE ASSISTÊNCIA FAMILIAR</strong> <br/>
            Av. São Paulo, 132<br/>
            Centro<br />
            86010-060 Londrina-PR<br/>
                              
        </div>
    </div>
        </div>
        <div >&nbsp;</div>
        <table width="740" border="0" cellspacing="0" cellpadding="5" id="tabela_boleto" class="page_break_boleto">
        </table>
<?
}
?>
        <div style="height: 350px;margin: 5px 0;">
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
                        fbarcode($cod_boleto, $pasta); ?>
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
                        <?php fbarcode($id_cliente, $pasta); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
            <?php   
            if($i == 4)
            {
            ?>
            
            <?php
            $i = 1;
            }else{
                $i++;
            }
            ?>
            </div>
            <?php
        
            $i_r++; 
            if($contar_linhas_while != $i_r){
                //echo "<hr />";
            } 
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
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo $diretorio_links; ?>/../../../../assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
