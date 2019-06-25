<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$numero_mes_referencia  = (empty($_GET['numero_mes_referencia'])) ? "" : verifica($_GET['numero_mes_referencia']);  
$grupo_produtos         = (empty($_GET['grupo_produtos'])) ? "" : verifica($_GET['grupo_produtos']);  
$id_servico             = (empty($_GET['id_servico'])) ? "" : verifica($_GET['id_servico']);  
$periodo                = (empty($_GET['periodo'])) ? "" : verifica($_GET['periodo']);  
$data1                  = (empty($_GET['data1'])) ? "" : verifica($_GET['data1']);  
$id_parceiro            = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);  
$id_filial              = (empty($_GET['id_filial'])) ? "" : verifica($_GET['id_filial']);  
$id_produto_plano       = (empty($_GET['id_produto_plano'])) ? "" : verifica($_GET['id_produto_plano']); 
if($numero_mes_referencia == '1'){
    $mes_referencia = 'Janeiro';
}elseif($numero_mes_referencia == '2'){
    $mes_referencia = 'Fevereiro';
}elseif($numero_mes_referencia == '3'){
    $mes_referencia = 'Marco';
}elseif($numero_mes_referencia == '4'){
    $mes_referencia = 'Abril';
}elseif($numero_mes_referencia == '5'){
    $mes_referencia = 'Maio';
}elseif($numero_mes_referencia == '6'){
    $mes_referencia = 'Junho';
}elseif($numero_mes_referencia == '7'){
    $mes_referencia = 'Julho';
}elseif($numero_mes_referencia == '8'){
    $mes_referencia = 'Agosto';
}elseif($numero_mes_referencia == '9'){
    $mes_referencia = 'Setembro';
}elseif($numero_mes_referencia == '10'){
    $mes_referencia = 'Outubro';
}elseif($numero_mes_referencia == '11'){
    $mes_referencia = 'Novembro';
}elseif($numero_mes_referencia == '12'){
    $mes_referencia = 'Dezembro';
}

if($periodo == 'todos'){
    $exibe_pediodo = "Todos os períodos";
    $se_prazo = '';
}elseif($periodo == 0){
    $exibe_pediodo = "Recorrente";
    $periodo = 0;
    $se_prazo = "AND prazo = $periodo";
}else{
    $exibe_pediodo = $periodo." meses";
    $se_prazo = "AND prazo = $periodo";
}

if($periodo == 'INDIVIDUAL'){
    $exibe_pediodo = "FATURAMENTO INDIVIDUAL";
    $se_prazo = '';
}

if($periodo == 'FAMILIAR'){
    $exibe_pediodo = "FATURAMENTO FAMILIAR";
    $se_prazo = '';
}

$sql_sel_filial = '';
if($id_filial > 0){
    $sql_sel_filial = 'AND id_filial = '.$id_filial;
}

$cortar_data1 = substr($data1, 0, 4);
$sql_verifica_faturamento = "SELECT id_faturamento, quantidade, valor, quantidade_adicional, valor_adicional, quantidade_total, parcelas, data_cadastro FROM faturamentos
WHERE id_parceiro = $id_parceiro $sql_sel_filial AND id_grupo_produto = $grupo_produtos AND id_produto_grupo = $id_produto_plano AND id_servico = $id_servico $se_prazo AND mes_referencia = $numero_mes_referencia AND ano_referencia LIKE '$cortar_data1%' 
ORDER BY data_cadastro DESC";
//echo $sql_verifica_faturamento;
    $query_verifica_faturamento      = mysql_query($sql_verifica_faturamento, $banco_painel) or die(mysql_error()." - 7");
    $id_faturamento_atual = '';
    if (mysql_num_rows($query_verifica_faturamento)>0)
    { 
        $id_faturamento_atual = mysql_result($query_verifica_faturamento, 0, 'id_faturamento');
        $quantidade           = mysql_result($query_verifica_faturamento, 0, 'quantidade');
        $valor                = mysql_result($query_verifica_faturamento, 0, 'valor');
        $quantidade_adicional = mysql_result($query_verifica_faturamento, 0, 'quantidade_adicional');
        $valor_adicional      = mysql_result($query_verifica_faturamento, 0, 'valor_adicional');
        $quantidade_total     = mysql_result($query_verifica_faturamento, 0, 'quantidade_total');
        $parcelas = mysql_result($query_verifica_faturamento, 0, 'parcelas');
        $data_cadastro = mysql_result($query_verifica_faturamento, 0, 'data_cadastro');
        $data_cadastro = converte_data($data_cadastro);
        echo "<div class=\"col-xs-12\"><h5 style='color: red;'> Período de <strong>$mes_referencia</strong> já faturado em $data_cadastro. Os registros abaixo serão substituido:</h5>
        <p>Vendas: <strong>$quantidade</strong></p>
        <p>Valor: <strong>$valor</strong></p>
        <p>Adicional: <strong>$quantidade_adicional</strong></p>
        <p>Valor Adicional: <strong>$valor_adicional</strong></p>
        <p>Quant. Total: <strong>$quantidade_total</strong></p>
        <p>Parcelas: <strong>$parcelas</strong></p></div>
        ";
    }
echo "<input type=\"hidden\" name=\"id_faturamento_atual\" id=\"id_faturamento_atual\" value=\"$id_faturamento_atual\" />";