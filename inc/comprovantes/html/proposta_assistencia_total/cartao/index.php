<?php

    require_once('../../../../conexao.php');
    require_once('../../../../functions.php');
    require_once('../../../../../sessao.php');
    require_once('../../../../permissoes.php');
    $banco_painel = $link;
    
    $origem = $_SERVER['HTTP_REFERER'];
    if (empty($origem))
    {
        $pasta = base64_decode($_COOKIE["pasta"]);
        header('Location: http://www.trailservicos.com.br/'.$pasta);
    }
    
    if(in_array("47", $verifica_lista_permissoes_array_inc)){
        
    $cert = (empty($_GET['cert'])) ? "" : verifica($_GET['cert']);
    $tipo_plano = (empty($_GET['tipo_plano'])) ? "" : verifica($_GET['tipo_plano']);
    
        
    
    // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
    $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                WHERE bpro.id_base_produto = 3";
    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 2");
    
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
    
    $sql        = "SELECT nome'nome_plano' FROM grupos_produtos 
                WHERE id_grupo_produto = $tipo_plano";
    $query      = mysql_query($sql, $banco_painel);
                
    if (mysql_num_rows($query)>0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);  
    }
    
    $sql        = "SELECT * FROM clientes 
                WHERE chave = '$cert'";
    $query      = mysql_query($sql, $banco_produto);
                
    if (mysql_num_rows($query)>0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);  
        
        $data_inicio = converte_data($data_inicio);
        $data_emissao = converte_data($data_emissao);
        $data_termino = converte_data($data_termino);
        $data_nascimento = converte_data($data_nascimento);
        
        
        $agora 			= date("Y-m-d H:i:s");
        $verifica           = "SELECT * FROM controle_impressao 
                            WHERE id_referencia = $id_cliente AND tipo_impressao = 'cartao'";
        $query_verifica    = mysql_query($verifica, $banco_produto);
             
        if (mysql_num_rows($query_verifica)>0)
        {
            $sql2    = "UPDATE controle_impressao SET data_impressao = '$agora' WHERE id_referencia = $id_cliente AND tipo_impressao = 'cartao'";
            $query2  = mysql_query($sql2, $banco_produto) OR DIE (mysql_error());
            
        }
        else
        {
            $sql3    = "INSERT INTO controle_impressao (tipo_impressao, id_referencia, data_impressao)
                    VALUES ('cartao', '$id_cliente', '$agora')";
        
            $query3  = mysql_query($sql3, $banco_produto) or die(mysql_error());
        }
        
        $id_usuario_s    = base64_decode($_COOKIE["usr_id"]);
        $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
    VALUES ('$id_cliente', '$id_usuario_s', 'clientes', 'Exibir cart�o para impressao - id_cliente: $id_cliente - cert: $cert', '$agora')";   
        $query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");

    }
        
        if($data_termino == '00/00/0000'){
            $data_termino = 'Recorrente';
        }
        
        $sql_vendas        = "SELECT tipo_pagamento, desconto, valor_parcela, valor_parcela_total, prazo FROM vendas 
                            WHERE id_cliente = $id_cliente";
                           
    $query_vendas      = mysql_query($sql_vendas, $banco_produto);
                
    if (mysql_num_rows($query_vendas)>0)
    {
        $dados_vendas = mysql_fetch_array($query_vendas);
        extract($dados_vendas);  
        
        $valor_parcela_total = db_moeda($valor_parcela_total);
    }
    
    
    $partes_chave_1 = substr($chave, 0, 4);
    $partes_chave_2 = substr($chave, 4, 4);
    $partes_chave_3 = substr($chave, 8, 4);
    $partes_chave_4 = substr($chave, 12, 4);
    $partes_chave_5 = substr($chave, 16, 4);
    
    $partes_chave_total = $partes_chave_1." ".$partes_chave_2." ".$partes_chave_3." ".$partes_chave_4." ".$partes_chave_5;

?>

<HTML>
<!-- saved from url=(0020)http://www.corel.com -->
<HEAD>
	<TITLE>TRAIL</TITLE>
	<META http-equiv="Content-Type" Content="text/html; charset=utf-8">
	<META NAME="Generator" CONTENT="CorelDRAW X7">
	<META NAME="Date" CONTENT="02/23/2016">

<style type="text/css">
#apDiv1 {
	position: absolute;
	left: 28px;
    top: 195px;
    width: 324px;
    height: 20px;
    z-index: 1;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 16px;
    line-height: 20px;
    font-weight: bold;
    text-transform: uppercase;
}
#apDiv2 {
	position: absolute;
	left: 28px;
    top: 267px;
    width: 285px;
    height: 20px;
    z-index: 2;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 22px;
    font-weight: bold;
	line-height: 20px;
    text-transform: uppercase;
}
#apDiv3 {
	position: absolute;
	left: 348px;
    top: 195px;
	width: 117px;
	height: 20px;
	z-index: 3;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
	line-height: 20px;
	text-align: left;
    text-transform: uppercase;
}
#apDiv4 {
	position: absolute;
	left: 28px;
    top: 128px;
    width: 334px;
	height: 20px;
	z-index: 4;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;
	line-height: 20px;
	text-align: left;
    text-transform: uppercase;
}
#apDiv5 {
	position: absolute;
	left: 366px;
    top: 257px;
    width: 104px;
    height: 20px;
    z-index: 5;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 16px;
    font-weight: bold;
	line-height: 20px;
    text-transform: uppercase;
}
#apDiv6 {
	position: absolute;
	left: 28px;
    top: 216px;
    width: 100px;
    height: 20px;
    z-index: 3;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: bold;
    line-height: 20px;
    text-align: center;
    text-transform: uppercase;
    background: #000000;
    color: #ffffff;
    padding: 2px 0px;
}
.bt_imprimir{
    display: block;
    overflow: hidden;
    position: absolute;
    margin: 10px 40px;
    float: left;
    top: -50px;
    left: 260px;
}
.bt_imprimir button{
    background-image: none;
    display: inline-block;
    margin-bottom: 0;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    touch-action: manipulation;
    cursor: pointer;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: #FF5722;
    color: #ffffff;
    font-size: 20px;
}

body{
    position: relative;
    margin-top: 60px;
    color: #000000;
}

@media print {
	.bt_imprimir{
	   display: none;
	}
    body{
    position: inherit;
    margin-top: 0;
}
}
</style>
</HEAD>
<BODY BGCOLOR="#FFFFFF">
<div class="bt_imprimir" onclick="javascript:window.print();"><button>Imprimir</button></div>
<?php

echo "<div id=\"apDiv1\">".reduzirNome($nome, 30)."</div>
<div id=\"apDiv2\">$partes_chave_total</div>
<div id=\"apDiv3\">$data_nascimento</div>
<div id=\"apDiv4\">$nome_plano</div>
<div id=\"apDiv6\">TITULAR</div>";

?>
<STYLE type="text/css">
<!--

-->
</STYLE>
<DIV STYLE="position: absolute; left: 0px; top:0px; width: 746px; height: 273px;">
</DIV>
</DIV>
</BODY>
</HTML>
<?php
}else{
    echo "Voc� n�o tem permiss�o para imprimir o cart�o!";
}
?>