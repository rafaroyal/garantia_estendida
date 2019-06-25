<?php

    require_once('../../../conexao.php');
    require_once('../../../functions.php');
    
    $banco_painel = $link;
    
    /*$origem = $_SERVER['HTTP_REFERER'];
    if (empty($origem))
    {
        header("Location: http://www.trailservicos.com.br/painel_trail/");
    }*/
    
    
    $cert = (empty($_GET['cert'])) ? "" : verifica($_GET['cert']);
    
    // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
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

    }
        
        $sql_vendas        = "SELECT tipo_pagamento, desconto, valor_entrada, valor_parcela, valor_parcela_total, parcelas, prazo, metodo_pagamento FROM vendas 
                            WHERE id_cliente = $id_cliente";
                           
    $query_vendas      = mysql_query($sql_vendas, $banco_produto);
                
    if (mysql_num_rows($query_vendas)>0)
    {
        $dados_vendas = mysql_fetch_array($query_vendas);
        extract($dados_vendas);  
    }
    
    // ver se existe dependente
    $sql_dep = "SELECT chave FROM clientes
    WHERE id_cliente_principal = $id_cliente";
    $query_dep      = mysql_query($sql_dep, $banco_produto);
    $array_cert_dep = array();    
    $html_dep = '';   
    if (mysql_num_rows($query_dep)>0)
    {        
        while($dados_dep = mysql_fetch_array($query_dep))
        {  
           $array_cert_dep[] = $dados_dep['chave'];
        }
        
        $dep_cert = implode(" - " , $array_cert_dep);
        $html_dep = "Certificado(s) outro(s) cliente(s):<br/>".$dep_cert;
    }
    
    if($desconto > 0)
    {
        //$desconto = db_moeda($desconto);
        $desconto_exibe = "Desconto: -".$desconto."%";
        function porcentagem_xn ( $porcentagem, $total ) {
        	return ( $porcentagem / 100 ) * $total;
        }
        
        $calculo_com_desconto = porcentagem_xn ( $desconto, $valor_parcela );
        $novo_valor_parcela = $valor_parcela - $calculo_com_desconto; 
        // SOMA TOTAL
        $calculo_soma_total = $novo_valor_parcela * $prazo;
    }
    else
    {
        $desconto_exibe = '';
        // SOMA TOTAL
        $calculo_soma_total = $valor_parcela * $prazo;
    }
    
    $exibe_calculo_soma_total = "Total do serviço: ".db_moeda($calculo_soma_total);
    
    if($valor_entrada > 0)
    {
        $valor_entrada = db_moeda($valor_entrada);
        $valor_entrada_exibe = "Entrada de: ".$valor_entrada;
    }
    else
    {
        $valor_entrada_exibe = '';
    }
    
    $valor_parcela_total = db_moeda($valor_parcela_total);
    
    if($metodo_pagamento == 'BO'){
        $exibe_parcelas_boleto = $parcelas."x de ";
    }else{
        $exibe_parcelas_boleto = '';
    }
    
    if($prazo == 0)
    {
        $prazo_exibe = "Recorrente";
    }
    else
    {
        $prazo_exibe = $prazo." meses";
    }
    
    if(empty($id_cliente_principal)){
        $id_cliente_principal = 0;
    }
    // ver dados do cliente principal
    $sql_pr        = "SELECT nome FROM clientes 
                WHERE id_cliente = $id_cliente_principal";
    $query_pr      = mysql_query($sql_pr, $banco_produto);
                
    if (mysql_num_rows($query_pr)>0)
    {
        $nome_principal = mysql_result($query_pr, 0, 'nome');
        $html_nome_principal = "1° Títular: ".$nome_principal;
        $valor_parcela_total = '';
    }
    
    $sql_img_parc        = "SELECT logo, logo_proposta, obs_proposta FROM parceiros 
                            WHERE id_parceiro = $id_parceiro";
                           
    $query_img_parc      = mysql_query($sql_img_parc, $banco_painel);
    $img_logo_proposta = '';   
    $obs_proposta = '';        
    if (mysql_num_rows($query_img_parc)>0)
    {
        $img_logo_parceiro = mysql_result($query_img_parc, 0,0);
        $img_logo_proposta = mysql_result($query_img_parc, 0,1);
        $obs_proposta = mysql_result($query_img_parc, 0,2);
        
        if(!empty($obs_proposta)){
            $obs_proposta = $obs_proposta.'<br/>';
        }
       
    }
    
?>

<HTML>
<HEAD>
	<TITLE>TRAIL</TITLE>
	<META http-equiv="Content-Type" Content="text/html; charset=utf-8">

<style type="text/css">
#apDiv1 {
	position: absolute;
	left: 84px;
	top: 247px;
	width: 420px;
	height: 20px;
	z-index: 1;
	line-height: 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
#apDiv2 {
	position: absolute;
	left: 550px;
	top: 247px;
	width: 199px;
	height: 20px;
	z-index: 2;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv3 {
	position: absolute;
	left: 334px;
	top: 277px;
	width: 305px;
	height: 20px;
	z-index: 3;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv4 {
	position: absolute;
	left: 674px;
	top: 277px;
	width: 85px;
	height: 20px;
	z-index: 4;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv5 {
	position: absolute;
	left: 130px;
	top: 307px;
	width: 371px;
	height: 20px;
	z-index: 5;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv6 {
	position: absolute;
	left: 308px;
	top: 307px;
	width: 200px;
	height: 20px;
	z-index: 6;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv7 {
	position: absolute;
    left: 567px;
    top: 307px;
    width: 195px;
	height: 20px;
	z-index: 7;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv16 {
	position: absolute;
    left: 100px;
    top: 338px;
    width: 195px;
	height: 20px;
	z-index: 7;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv17 {
	position: absolute;
    left: 326px;
    top: 338px;
    width: 195px;
	height: 20px;
	z-index: 7;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv8 {
	position: absolute;
	left: 564px;
	top: 338px;
	width: 207px;
	height: 20px;
	z-index: 8;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv9 {
	position: absolute;
	left: 38px;
	top: 417px;
	width: 232px;
	height: 20px;
	z-index: 9;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv10 {
	position: absolute;
	left: 357px;
	top: 417px;
	width: 146px;
	height: 20px;
	z-index: 10;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv11 {
	position: absolute;
	left: 620px;
	top: 415px;
	width: 28px;
	height: 20px;
	z-index: 11;
}
#apDiv12 {
	position: absolute;
	left: 624px;
	top: 419px;
	width: 134px;
	height: 20px;
	z-index: 11;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv13 {
	position: absolute;
	left: 40px;
	top: 790px;
	width: 301px;
	height: 219px;
	z-index: 12;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv14 {
	position: absolute;
	left: 396px;
	top: 900px;
	width: 147px;
	height: 17px;
	z-index: 13;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	line-height: 20px;
	font-weight: bold;
}
#apDiv15 {
	position: absolute;
	left: 113px;
    top: 276px;
	width: 147px;
	height: 17px;
	z-index: 13;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	line-height: 20px;
	font-weight: bold;
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
echo "<div id=\"apdiv1\">$nome</div>
<div id=\"apdiv2\">$cpf</div>
<div id=\"apdiv15\">".converte_data($data_nascimento)."</div>
<div id=\"apdiv3\">$endereco</div>
<div id=\"apdiv4\">$numero</div>
<div id=\"apdiv5\">$complemento</div>
<div id=\"apdiv6\">".mask_total($cep, '#####-###')."</div>
<div id=\"apdiv7\">$cidade</div>
<div id=\"apdiv16\">$telefone</div>
<div id=\"apdiv17\">$celular</div>
<div id=\"apdiv8\">$estado</div>
<div id=\"apdiv9\">Assistência Funeral Familiar</div>
<div id=\"apdiv10\">$prazo_exibe</div>
<div id=\"apdiv12\">$data_emissao</div>
<div id=\"apdiv13\">
$obs_proposta
Tipo de Pagamento: $tipo_pagamento <br/>
$html_nome_principal<br/>";
if($id_cliente_principal == 0){
    echo "$desconto_exibe <br/>
$valor_entrada_exibe <br/>
$exibe_parcelas_boleto $valor_parcela_total <br/>
$exibe_calculo_soma_total <br/>
Prazo de Vigência: $prazo_exibe<br/>
$html_dep
";
}
echo "</div><div id=\"apdiv14\">$data_emissao</div>"

?>

<DIV STYLE="position: absolute; left: 598px; top:21px; width: 5px; height: 97px;">
<IMG SRC="images/hex21.jpg" HEIGHT=97 WIDTH=5 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 379px; top:26px; width: 209px; height: 22px;">
<IMG SRC="images/hex22.jpg" HEIGHT=22 WIDTH=209 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 41px; top:27px; width: 157px; height: 63px;">
<IMG SRC="../../../../assets/pages/img/logos/<?php echo $img_logo_proposta; ?> " ALT="<?php echo $img_logo_proposta; ?>" BORDER=0 WIDTH=155 ALIGN=TOP></DIV>
<DIV STYLE="position: absolute; left: 615px; top:31px; width: 56px; height: 13px;">
<IMG SRC="images/hex24.jpg" HEIGHT=13 WIDTH=56 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 379px; top:55px; width: 209px; height: 63px;">
<IMG SRC="images/hex25.jpg" HEIGHT=63 WIDTH=209 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 41px; top:97px; width: 157px; height: 12px;">
<!--<IMG SRC="images/hex4.gif" HEIGHT=12 WIDTH=157 ALIGN=TOP BORDER=0>-->&nbsp;</DIV>
<DIV STYLE="position: absolute; left: 227px; top:127px; width: 156px; height: 20px;">
<IMG SRC="images/hex27.jpg" HEIGHT=20 WIDTH=156 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 383px; top:127px; width: 185px; height: 20px;">
<IMG SRC="images/hex28.jpg" HEIGHT=20 WIDTH=185 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 17px; top:154px; width: 760px; height: 48px;">
<IMG SRC="images/hex29.jpg" HEIGHT=48 WIDTH=760 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 17px; top:211px; width: 750px; height: 243px;">
<IMG SRC="images/hex30.jpg" HEIGHT=243 WIDTH=750 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 17px; top:471px; width: 750px; height: 260px;">
<IMG SRC="images/hex50.jpg" HEIGHT=260 WIDTH=750 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 17px; top:742px; width: 334px; height: 267px;">
<IMG SRC="images/hex51.jpg" HEIGHT=267 WIDTH=334 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 363px; top:759px; width: 373px; height: 36px;">
<IMG SRC="images/hex52.jpg" HEIGHT=36 WIDTH=373 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 363px; top:810px; width: 373px; height: 25px;">
<IMG SRC="images/hex53.jpg" HEIGHT=25 WIDTH=373 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 363px; top:849px; width: 373px; height: 38px;">
<IMG SRC="images/hex54.jpg" HEIGHT=38 WIDTH=373 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 363px; top:908px; width: 373px; height: 13px;">
<IMG SRC="images/hex55.jpg" HEIGHT=13 WIDTH=373 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 363px; top:984px; width: 373px; height: 3px;">
<IMG SRC="images/hex56.jpg" HEIGHT=3 WIDTH=373 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 363px; top:998px; width: 373px; height: 11px;">
<IMG SRC="images/hex57.jpg" HEIGHT=11 WIDTH=373 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 37px; top:1027px; width: 721px; height: 52px;">
<IMG SRC="images/hex58.jpg" HEIGHT=52 WIDTH=721 ALIGN=TOP BORDER=0></DIV>
<DIV STYLE="position: absolute; left: 615px; top:56px; width: 155px; height: 50px;">
<IMG SRC="../../../../assets/pages/img/logos/<?php echo $img_logo_parceiro; ?> " ALT="<?php echo $img_logo_parceiro; ?>" BORDER=0 WIDTH=155 ALIGN=TOP>
</DIV>
</BODY>
</HTML>
