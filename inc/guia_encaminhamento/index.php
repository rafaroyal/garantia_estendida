<?php
    require_once('../../sessao.php');
    require_once('../conexao.php');
    require_once('../functions.php');
    require_once('../permissoes.php');
    $banco_painel = $link;
    $usr_id       = base64_decode($_COOKIE["usr_id"]);
    $nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);

    $id_guia_get = (empty($_GET['id_guia'])) ? "" : verifica($_GET['id_guia']);

    $sql        = "SELECT * FROM gui_guias 
                WHERE id_guia = $id_guia_get";
    $query      = mysql_query($sql);
                
    if (mysql_num_rows($query)>0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);  
        $agora = agora();
        $html_reimpressao_guia = 'Impresso em:';
        $verifica_data_agendamento = false;
        $completo_data_agendamento = $data_agendamento." ".$hora_agendamento;
        if((strtotime($completo_data_agendamento) > strtotime($agora)) OR ($nivel_usuario == 'A' AND in_array("63", $verifica_lista_permissoes_array_inc))){
            $verifica_data_agendamento = true;
            if(strtotime($completo_data_agendamento) < strtotime($agora)){
                $html_reimpressao_guia = 'Reimpresso em:';
            }
        } 
    }
    
    if($verifica_data_agendamento == true){
    $sql    = "UPDATE gui_guias SET id_usuario_emissao = $usr_id, data_impressao = '".agora()."'
    WHERE id_guia = $id_guia";
    $query  = mysql_query($sql) OR DIE (mysql_error());
    
    $sql        = "SELECT data_impressao FROM gui_guias 
                WHERE id_guia = $id_guia";
    $query      = mysql_query($sql);
                
    if (mysql_num_rows($query)>0)
    {
        $data_impressao = mysql_result($query, 0, 'data_impressao');
    }
    
    $sql   = "SELECT p.id_paciente, p.id_cliente, p.nome, p.data_nascimento, c.nome'nome_convenio' FROM gui_pacientes p
            JOIN gui_convenios c ON p.id_convenio = c.id_convenio
            WHERE id_paciente = $id_paciente";
    $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 2");
    $id_paciente = '-';
    $nome_paciente  = '-';
    $data_nascimento= '-';
    $id_cliente = '-';
    if (mysql_num_rows($query)>0)
    {
        $gui_id_paciente                = mysql_result($query, 0, 'id_paciente');
        $gui_id_cliente                 = mysql_result($query, 0, 'id_cliente');
        $gui_nome_paciente              = mysql_result($query, 0, 'nome');
        $gui_data_nascimento            = mysql_result($query, 0, 'data_nascimento');
        $gui_nome_convenio_paciente     = mysql_result($query, 0, 'nome_convenio');
    }
    
    $sql   = "SELECT nome, tipo, telefone, telefone_alt, celular, endereco, numero, complemento, bairro, cidade, estado, obs, local_pagamento FROM gui_local_atendimento
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
    $obs_local_atendimento          = '';
    
    if (mysql_num_rows($query)>0)
    {
        $nome_local_atendimento         = mysql_result($query, 0, 'nome');
        $tipo_local_atendimento         = mysql_result($query, 0, 'tipo');
        $telefone_local_atendimento     = mysql_result($query, 0, 'telefone');
        $telefone_alt_local_atendimento = mysql_result($query, 0, 'telefone_alt');
        $celular_local_atendimento      = mysql_result($query, 0, 'celular');
        $endereco_local_atendimento     = mysql_result($query, 0, 'endereco');
        $numero_local_atendimento       = mysql_result($query, 0, 'numero');
        $complemento_local_atendimento  = mysql_result($query, 0, 'complemento');
        $bairro_local_atendimento       = mysql_result($query, 0, 'bairro');
        $cidade_local_atendimento       = mysql_result($query, 0, 'cidade');
        $estado_local_atendimento       = mysql_result($query, 0, 'estado');
        $obs_local_atendimento          = mysql_result($query, 0, 'obs');
        $local_pagamento                = mysql_result($query, 0, 'local_pagamento');
    }
    
    $sql   = "SELECT nome_fantasia, cep, endereco, numero, bairro, cidade, estado, tel_com, tel_cel, logo FROM parceiros
                        WHERE id_parceiro = $id_parceiro AND del = 'N'";
    $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 4");
    $nome_parceiro = 'Sem Parceiro';
    if (mysql_num_rows($query)>0)
    {
        $nome_parceiro          = mysql_result($query, 0, 'nome_fantasia');
        $cep_parceiro           = mysql_result($query, 0, 'cep');
        $endereco_parceiro      = mysql_result($query, 0, 'endereco');
        $numero_parceiro        = mysql_result($query, 0, 'numero');
        $bairro_parceiro        = mysql_result($query, 0, 'bairro');
        $cidade_parceiro        = mysql_result($query, 0, 'cidade');
        $estado_parceiro        = mysql_result($query, 0, 'estado');
        $tel_com_parceiro       = mysql_result($query, 0, 'tel_com');
        $tel_cel_parceiro       = mysql_result($query, 0, 'tel_cel');
        $logo_parceiro          = mysql_result($query, 0, 'logo');
        
	}
	
	$sql_opcoes   = "SELECT valor FROM opcoes
	WHERE nome IN ('dados_painel_nome','dados_painel_endereco','dados_painel_fones')";
	$query_opcoes = mysql_query($sql_opcoes, $banco_painel);
	$dados_painel_nome 		= '';    
	$dados_painel_endereco 	= '';    
	$dados_painel_fones 	= '';         
	if (mysql_num_rows($query_opcoes)>0)
	{
		$dados_painel_nome 		= mysql_result($query_opcoes, 0,0);    
		$dados_painel_endereco 	= mysql_result($query_opcoes, 1,0);    
		$dados_painel_fones 	= mysql_result($query_opcoes, 2,0);    
	}

    $sql   = "SELECT nome FROM usuarios
                        WHERE id_usuario = $usr_id AND del = 'N'";
    $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 5");
    $nome_user_cadastro = 'Sem Usuario';
    if (mysql_num_rows($query)>0)
    {
        $nome_user_cadastro = mysql_result($query, 0, 'nome');
    }
    
    $sql   = "SELECT nome FROM gui_convenios
            WHERE id_convenio = $id_convenio AND ativo = 'S'";
    $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 6");
    $nome_convenio = 'Sem Convenio';
    if (mysql_num_rows($query)>0)
    {
        $nome_convenio = mysql_result($query, 0, 'nome');
    }
    
    $sql   = "SELECT nome, tratamento, conselho, registro FROM gui_profissionais
                      WHERE id_profissional = $id_profissional";
    $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 2");
    $nome_profissonal        = '-';
    $tratamento_profissional = '-';
    if (mysql_num_rows($query)>0)
    {
        $nome_profissonal           = mysql_result($query, 0, 'nome');
        $tratamento_profissional    = mysql_result($query, 0, 'tratamento');
        $conselho                   = mysql_result($query, 0, 'conselho');
        $registro                   = mysql_result($query, 0, 'registro');
    }
?>


<HTML>
<!-- saved from url=(0020)http://www.corel.com -->
<HEAD>
	<TITLE>GUIA DE ENCAMINHAMENTO</TITLE>
	<META http-equiv="Content-Type" Content="text/html; charset=utf-8">
	<META NAME="Generator" CONTENT="CorelDRAW X8">
	<META NAME="Date" CONTENT="12/29/2016">
<style type="text/css">
@page{
margin-left: 0cm;
margin-right: 0cm;
margin-top: 0cm;
margin-bottom: 0cm;
}

body{
    font-family: Arial, Helvetica, sans-serif;
}

table {
 font-size: 10px!important;   
}
.bt_imprimir{
    display: block;
    overflow: hidden;
    position: absolute;
    margin: 10px 40px;
    float: left;
    top: 0px;
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
.page_break_boleto {page-break-after: always; page-break-inside:auto }
.margin_top_tabela{
    margin-top: 50px;
}
@media print {
	.bt_imprimir{
	   display: none;
	}
    body{
    position: inherit;
    margin-top: 0;
    }
    .page_break_boleto {page-break-after: always; page-break-inside:auto }
}
</style>
</HEAD>
<BODY BGCOLOR="#FFFFFF">
<div class="bt_imprimir" onclick="javascript:window.print();"><button>Imprimir</button></div>
<TABLE BORDER=0 WIDTH=795 CELLSPACING=0 CELLPADDING=0>
<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=69 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=6 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=6 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=10 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=10 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=16 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=16 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=47 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=47 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=6 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=6 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=70 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=70 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=5 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=5 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=3 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=3 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=6 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=6 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=3 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=3 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=5 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=5 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=5 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=5 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=10 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=10 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=43 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=43 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=34 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=34 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=13 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=13 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=24 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=24 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=11 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=11 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=73 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=73 HEIGHT=1></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=50></TD>
	<TD COLSPAN=84 ROWSPAN=1 WIDTH=794 HEIGHT=50></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=4></TD>
	<TD COLSPAN=10 ROWSPAN=1 WIDTH=234 HEIGHT=4></TD>
	<TD COLSPAN=49 ROWSPAN=2 WIDTH=303 HEIGHT=17 style="font-size: 12px;"><?php echo $dados_painel_nome;?></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=12 HEIGHT=4></TD>
	<TD COLSPAN=3 ROWSPAN=2 WIDTH=3 HEIGHT=17></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=77 HEIGHT=4></TD>
	<TD COLSPAN=17 ROWSPAN=1 WIDTH=165 HEIGHT=4></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=13></TD>
	<TD COLSPAN=2 ROWSPAN=16 WIDTH=71 HEIGHT=206></TD>
	<TD COLSPAN=7 ROWSPAN=5 WIDTH=156 HEIGHT=54><!--<IMG SRC="images/hex4.jpg" ALT="hex4.jpg" BORDER=0 HEIGHT=53 WIDTH=155 ALIGN=TOP>-->
</TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=13></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=12 HEIGHT=13></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=78 HEIGHT=13 style="font-size: 12px;"><strong>PARCEIRO</strong></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=164 HEIGHT=13></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=6></TD>
	<TD COLSPAN=1 ROWSPAN=4 WIDTH=7 HEIGHT=41></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=6></TD>
	<TD COLSPAN=3 ROWSPAN=4 WIDTH=12 HEIGHT=41></TD>
    <TD COLSPAN=12 ROWSPAN=5 WIDTH=121 HEIGHT=42><IMG SRC="<?php echo "../../assets/pages/img/logos/$logo_parceiro"; ?>" ALT="hex4.jpg" BORDER=0 WIDTH=120 ALIGN=TOP>
	<TD COLSPAN=3 ROWSPAN=5 WIDTH=3 HEIGHT=42></TD>
	<TD COLSPAN=1 ROWSPAN=4 WIDTH=43 HEIGHT=41></TD>
	
</TD>
	<TD COLSPAN=6 ROWSPAN=4 WIDTH=78 HEIGHT=41></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=14></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=14 style="font-size: 12px;"><?php echo $dados_painel_endereco; ?></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=12 style="font-size: 12px;">Fone: <?php echo $dados_painel_fones; ?></TD>
</TR>


<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=30></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=30></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=16></TD>
	<TD COLSPAN=68 ROWSPAN=1 WIDTH=571 HEIGHT=16 style="font-size: 18px;font-weight: bold;">GUIA DE ENCAMINHAMENTO - VIA PACIENTE</TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=16></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=13 HEIGHT=16><IMG SRC="images/hex44.jpg" HEIGHT=16 WIDTH=13 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=8 ROWSPAN=1 WIDTH=56 HEIGHT=16 style="font-size: 16px;"> <?php echo "<strong>$id_guia</strong>"; ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=16></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=22></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=22></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=59 ROWSPAN=1 WIDTH=468 HEIGHT=11><strong>Agendado para:</strong></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=11 HEIGHT=11></TD>
	<TD COLSPAN=17 ROWSPAN=1 WIDTH=168 HEIGHT=11><strong><?php echo $html_reimpressao_guia;?></strong></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=59 ROWSPAN=1 WIDTH=468 HEIGHT=11 style="font-size: 14px;font-weight: bold;"><?php $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                            $diasemana_numero = date('w', strtotime($data_agendamento));
                            echo $diasemana[$diasemana_numero].", dia ".converte_data($data_agendamento)." às ".$hora_agendamento;
                             ?></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=12 HEIGHT=11></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=167 HEIGHT=11><?php echo converte_data($data_impressao); ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex50.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=21></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=21></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=3 WIDTH=6 HEIGHT=13><strong>#</strong></TD>
	<TD COLSPAN=4 ROWSPAN=3 WIDTH=79 HEIGHT=13></TD>
	<TD COLSPAN=53 ROWSPAN=3 WIDTH=382 HEIGHT=13><strong>Paciente <?php echo $gui_nome_convenio_paciente; ?>:</strong></TD>
	<TD COLSPAN=3 ROWSPAN=3 WIDTH=12 HEIGHT=13></TD>
	<TD COLSPAN=17 ROWSPAN=3 WIDTH=168 HEIGHT=13><strong>Data de Nascimento:</strong></TD>
	<TD COLSPAN=4 ROWSPAN=3 WIDTH=76 HEIGHT=13></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=2 ROWSPAN=7 WIDTH=71 HEIGHT=55></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=6></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=6></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=79 HEIGHT=11><?php echo $gui_id_paciente; ?></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=7 HEIGHT=11></TD>
	<TD COLSPAN=52 ROWSPAN=1 WIDTH=381 HEIGHT=11><?php echo $gui_nome_paciente; ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=13 HEIGHT=11></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=167 HEIGHT=11><?php echo converte_data($gui_data_nascimento); ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex58.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=24></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=24></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=33 ROWSPAN=3 WIDTH=320 HEIGHT=11><strong>Local de atendimento:</strong></TD>
	<TD COLSPAN=4 ROWSPAN=3 WIDTH=11 HEIGHT=11></TD>
	<TD COLSPAN=40 ROWSPAN=3 WIDTH=315 HEIGHT=11><strong>Endereço:</strong></TD>
	<TD COLSPAN=5 ROWSPAN=3 WIDTH=77 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=2 ROWSPAN=7 WIDTH=71 HEIGHT=56></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=35 ROWSPAN=1 WIDTH=322 HEIGHT=11><?php echo $nome_local_atendimento;?></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=10 HEIGHT=11></TD>
	<TD COLSPAN=39 ROWSPAN=1 WIDTH=314 HEIGHT=11><?php echo $endereco_local_atendimento.", ".$numero_local_atendimento."  ".$complemento_local_atendimento." ".$bairro_local_atendimento."<br/>".$cidade_local_atendimento." - ".$estado_local_atendimento;  ?></TD>
	<TD COLSPAN=5 ROWSPAN=1 WIDTH=77 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex64.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=25></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=25></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=15 ROWSPAN=3 WIDTH=200 HEIGHT=11><strong>Conselho / Registro:</strong></TD>
	<TD COLSPAN=3 ROWSPAN=3 WIDTH=15 HEIGHT=11></TD>
	<TD COLSPAN=57 ROWSPAN=3 WIDTH=429 HEIGHT=11><strong>Profissional:</strong></TD>
	<TD COLSPAN=7 ROWSPAN=3 WIDTH=79 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=2 ROWSPAN=22 WIDTH=71 HEIGHT=681></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=202 HEIGHT=11><?php echo $conselho.": ".$registro ?></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=13 HEIGHT=11></TD>
	<TD COLSPAN=56 ROWSPAN=1 WIDTH=428 HEIGHT=11><?php echo $tratamento_profissional." ".$nome_profissonal; ?></TD>
	<TD COLSPAN=8 ROWSPAN=1 WIDTH=80 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex70.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=290></TD>
	<TD COLSPAN=79 ROWSPAN=1 WIDTH=648 HEIGHT=290 style="border: 1px solid;padding: 5px;">
    <table class="table table-hover table-light" style="width: 100%;">
            <thead>
                <tr class="uppercase">
                    <th width=90 style="font-size: 14px;text-align: left;"> Código </th>
                    <th  style="font-size: 14px;text-align: left;"> Descrição </th>
                    
                </tr>
            </thead>
            <tbody>
                
           
                <?php
                    $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_pro.obs'obs_procedimento', gui_pro_gui.valor_cobrado FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    WHERE gui_pro_gui.id_guia = $id_guia";
                    $query_procedimentos      = mysql_query($sql_procedimentos, $banco_painel);
                                
                if (mysql_num_rows($query_procedimentos)>0)
                {  
                    $soma_procedimentos = 0;
                    while ($dados = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados); 
                        //$soma_procedimentos = $soma_procedimentos + $valor_cobrado;
                        $html_obs_prtocedimento = '';
                        /*if(!empty($obs_procedimento)){
                            $html_obs_prtocedimento = "<br/><span style=\"font-size: 12px;color: #848484;\">$obs_procedimento</span>";
                        }*/
                         
                        echo "<tr>
                                <td style=\"border-bottom: 1px solid #c3c3c3;\"> $codigo_procedimnto </td>
                                <td style=\"border-bottom: 1px solid #c3c3c3;\"> $nome_procedimento $html_obs_prtocedimento</td>
                              </tr>";
                    }
                    
                }
            ?> 
            </tbody>
        </table></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=75 HEIGHT=290></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=19></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=19></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=12 ROWSPAN=1 WIDTH=182 HEIGHT=12 style="font-size: 13px;"><strong>Informações de pagamento:</strong></TD>
	<TD COLSPAN=32 ROWSPAN=1 WIDTH=197 HEIGHT=12 style="font-size: 13px;"><?php if($local_pagamento == 'LOCAL'){
                            echo "NA EMISSÃO DESTA GUIA";
                        }else{
                            echo "NO LOCAL DE ATENDIMENTO";
                        }?></TD>
	<TD COLSPAN=38 ROWSPAN=1 WIDTH=344 HEIGHT=12></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=21></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=21></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=48 ROWSPAN=1 WIDTH=403 HEIGHT=3></TD>
	<TD COLSPAN=33 ROWSPAN=1 WIDTH=247 HEIGHT=3>
    <?php if($local_pagamento == 'LOCAL'){
                            echo " <IMG SRC=\"images/hex74.jpg\" HEIGHT=3 WIDTH=247 ALIGN=TOP BORDER=0></TD>";
                        }else{
                            echo "&nbsp;";
                        }
    ?>
   
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=73 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=12></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=54 ROWSPAN=1 WIDTH=453 HEIGHT=11></TD>
	<TD COLSPAN=17 ROWSPAN=1 WIDTH=147 HEIGHT=11 style="font-size: 12px;text-align: center;">
    <?php if($local_pagamento == 'LOCAL'){
                            echo "<strong>Carimbo de pagamento</strong>";
                        }else{
                            echo "&nbsp;";
                        }
    ?>
    </TD>
	<TD COLSPAN=11 ROWSPAN=1 WIDTH=123 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=50></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=50></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=24 ROWSPAN=1 WIDTH=249 HEIGHT=3><IMG SRC="images/hex76.jpg" HEIGHT=3 WIDTH=249 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=24 ROWSPAN=1 WIDTH=154 HEIGHT=3></TD>
	<TD COLSPAN=33 ROWSPAN=1 WIDTH=247 HEIGHT=3><IMG SRC="images/hex77.jpg" HEIGHT=3 WIDTH=247 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=73 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=32 HEIGHT=12></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=187 HEIGHT=12 style="text-align: center;"><?php echo $nome_user_cadastro;?></TD>
	<TD COLSPAN=33 ROWSPAN=1 WIDTH=218 HEIGHT=12></TD>
	<TD COLSPAN=20 ROWSPAN=1 WIDTH=187 HEIGHT=12 style="text-align: center;"><?php echo $gui_nome_paciente;?></TD>
	<TD COLSPAN=10 ROWSPAN=1 WIDTH=99 HEIGHT=12></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=25 ROWSPAN=1 WIDTH=221 HEIGHT=11 style="text-align: center;font-size: 12px;"><strong>Assinatura do responsável pela liberação</strong></TD>
	<TD COLSPAN=24 ROWSPAN=1 WIDTH=191 HEIGHT=11></TD>
	<TD COLSPAN=29 ROWSPAN=1 WIDTH=207 HEIGHT=11 style="text-align: center;font-size: 12px;"><strong>Assinatura do paciente ou responsável</strong></TD>
	<TD COLSPAN=9 ROWSPAN=1 WIDTH=88 HEIGHT=11></TD>
</TR>
</TABLE>

<TABLE BORDER=0 WIDTH=795 CELLSPACING=0 CELLPADDING=0 class="page_break_boleto margin_top_tabela">
<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=69 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=6 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=6 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=10 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=10 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=16 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=16 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=47 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=47 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=6 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=6 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=70 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=70 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=5 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=5 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=3 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=3 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=6 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=6 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=3 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=3 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=5 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=5 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=5 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=5 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=2 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=10 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=10 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=43 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=43 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=34 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=34 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=4 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=4 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=7 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=13 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=13 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=9 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=9 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=24 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=24 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=11 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=11 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=8 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=8 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=73 HEIGHT=1><IMG SRC="images/hex0.gif" WIDTH=73 HEIGHT=1></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=50></TD>
	<TD COLSPAN=84 ROWSPAN=1 WIDTH=794 HEIGHT=50></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=4></TD>
	<TD COLSPAN=10 ROWSPAN=1 WIDTH=234 HEIGHT=4></TD>
	<TD COLSPAN=49 ROWSPAN=2 WIDTH=303 HEIGHT=17 style="font-size: 12px;"><?php echo $dados_painel_nome; ?> </TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=12 HEIGHT=4></TD>
	<TD COLSPAN=3 ROWSPAN=2 WIDTH=3 HEIGHT=17></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=77 HEIGHT=4></TD>
	<TD COLSPAN=17 ROWSPAN=1 WIDTH=165 HEIGHT=4></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=13></TD>
	<TD COLSPAN=2 ROWSPAN=16 WIDTH=71 HEIGHT=206></TD>
	<TD COLSPAN=7 ROWSPAN=5 WIDTH=156 HEIGHT=54><!--<IMG SRC="images/hex4.jpg" ALT="hex4.jpg" BORDER=0 HEIGHT=53 WIDTH=155 ALIGN=TOP>-->
</TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=13></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=12 HEIGHT=13></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=78 HEIGHT=13 style="font-size: 12px;"><strong>PARCEIRO</strong></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=164 HEIGHT=13></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=6></TD>
	<TD COLSPAN=1 ROWSPAN=4 WIDTH=7 HEIGHT=41></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=6></TD>
	<TD COLSPAN=3 ROWSPAN=4 WIDTH=12 HEIGHT=41></TD>
    <TD COLSPAN=12 ROWSPAN=5 WIDTH=121 HEIGHT=42><IMG SRC="<?php echo "../../assets/pages/img/logos/$logo_parceiro"; ?>" ALT="hex4.jpg" BORDER=0 WIDTH=120 ALIGN=TOP>
	<TD COLSPAN=3 ROWSPAN=5 WIDTH=3 HEIGHT=42></TD>
	<TD COLSPAN=1 ROWSPAN=4 WIDTH=43 HEIGHT=41></TD>
	
</TD>
	<TD COLSPAN=6 ROWSPAN=4 WIDTH=78 HEIGHT=41></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=14></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=14 style="font-size: 12px;"><?php echo $dados_painel_endereco; ?></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=49 ROWSPAN=1 WIDTH=303 HEIGHT=12 style="font-size: 12px;">Fone: <?php echo $dados_painel_fones; ?></TD>
</TR>


<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=30></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=30></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=16></TD>
	<TD COLSPAN=68 ROWSPAN=1 WIDTH=571 HEIGHT=16 style="font-size: 18px;font-weight: bold;">GUIA DE ENCAMINHAMENTO - VIA PARCEIRO</TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=7 HEIGHT=16></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=13 HEIGHT=16><IMG SRC="images/hex44.jpg" HEIGHT=16 WIDTH=13 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=8 ROWSPAN=1 WIDTH=56 HEIGHT=16 style="font-size: 16px;"> <?php echo "<strong>$id_guia</strong>"; ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=16></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=22></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=22></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=59 ROWSPAN=1 WIDTH=468 HEIGHT=11><strong>Agendado para:</strong></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=11 HEIGHT=11></TD>
	<TD COLSPAN=17 ROWSPAN=1 WIDTH=168 HEIGHT=11><strong><?php echo $html_reimpressao_guia;?></strong></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=59 ROWSPAN=1 WIDTH=468 HEIGHT=11 style="font-size: 14px;font-weight: bold;"><?php $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                            $diasemana_numero = date('w', strtotime($data_agendamento));
                            echo $diasemana[$diasemana_numero].", dia ".converte_data($data_agendamento)." às ".$hora_agendamento;
                             ?></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=12 HEIGHT=11></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=167 HEIGHT=11><?php echo converte_data($data_impressao); ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex50.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=21></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=21></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=1 ROWSPAN=3 WIDTH=6 HEIGHT=13><strong>#</strong></TD>
	<TD COLSPAN=4 ROWSPAN=3 WIDTH=79 HEIGHT=13></TD>
	<TD COLSPAN=53 ROWSPAN=3 WIDTH=382 HEIGHT=13><strong>Paciente <?php echo $gui_nome_convenio_paciente; ?>:</strong></TD>
	<TD COLSPAN=3 ROWSPAN=3 WIDTH=12 HEIGHT=13></TD>
	<TD COLSPAN=17 ROWSPAN=3 WIDTH=168 HEIGHT=13><strong>Data de Nascimento:</strong></TD>
	<TD COLSPAN=4 ROWSPAN=3 WIDTH=76 HEIGHT=13></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=2 ROWSPAN=7 WIDTH=71 HEIGHT=55></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=6></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=6></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=79 HEIGHT=11><?php echo $gui_id_paciente; ?></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=7 HEIGHT=11></TD>
	<TD COLSPAN=52 ROWSPAN=1 WIDTH=381 HEIGHT=11><?php echo $gui_nome_paciente; ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=13 HEIGHT=11></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=167 HEIGHT=11><?php echo converte_data($gui_data_nascimento); ?></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex58.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=24></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=24></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=33 ROWSPAN=3 WIDTH=320 HEIGHT=11><strong>Local de atendimento:</strong></TD>
	<TD COLSPAN=4 ROWSPAN=3 WIDTH=11 HEIGHT=11></TD>
	<TD COLSPAN=40 ROWSPAN=3 WIDTH=315 HEIGHT=11><strong>Endereço:</strong></TD>
	<TD COLSPAN=5 ROWSPAN=3 WIDTH=77 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=2 ROWSPAN=7 WIDTH=71 HEIGHT=56></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=35 ROWSPAN=1 WIDTH=322 HEIGHT=11><?php echo $nome_local_atendimento;?></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=10 HEIGHT=11></TD>
	<TD COLSPAN=39 ROWSPAN=1 WIDTH=314 HEIGHT=11><?php echo $endereco_local_atendimento.", ".$numero_local_atendimento."  ".$complemento_local_atendimento." ".$bairro_local_atendimento."<br/>".$cidade_local_atendimento." - ".$estado_local_atendimento;  ?></TD>
	<TD COLSPAN=5 ROWSPAN=1 WIDTH=77 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex64.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=25></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=25></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=15 ROWSPAN=3 WIDTH=200 HEIGHT=11><strong>Conselho / Registro:</strong></TD>
	<TD COLSPAN=3 ROWSPAN=3 WIDTH=15 HEIGHT=11></TD>
	<TD COLSPAN=57 ROWSPAN=3 WIDTH=429 HEIGHT=11><strong>Profissional:</strong></TD>
	<TD COLSPAN=7 ROWSPAN=3 WIDTH=79 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=69 HEIGHT=9></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=2 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=1></TD>
	<TD COLSPAN=2 ROWSPAN=22 WIDTH=71 HEIGHT=681></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=7></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=7></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=202 HEIGHT=11><?php echo $conselho.": ".$registro ?></TD>
	<TD COLSPAN=2 ROWSPAN=1 WIDTH=13 HEIGHT=11></TD>
	<TD COLSPAN=56 ROWSPAN=1 WIDTH=428 HEIGHT=11><?php echo $tratamento_profissional." ".$nome_profissonal; ?></TD>
	<TD COLSPAN=8 ROWSPAN=1 WIDTH=80 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=78 ROWSPAN=1 WIDTH=647 HEIGHT=3><IMG SRC="images/hex70.jpg" HEIGHT=3 WIDTH=647 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=4 ROWSPAN=1 WIDTH=76 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=290></TD>
	<TD COLSPAN=79 ROWSPAN=1 WIDTH=648 HEIGHT=290 style="border: 1px solid;padding: 5px;">
    <table class="table table-hover table-light" style="width: 100%;">
            <thead>
                <tr class="uppercase">
                    <th width=90 style="font-size: 14px;text-align: left;"> Código </th>
                    <th  style="font-size: 14px;text-align: left;"> Descrição </th>
                    
                </tr>
            </thead>
            <tbody>
                
           
                <?php
                    $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_pro.obs'obs_procedimento', gui_pro_gui.valor_cobrado FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    WHERE gui_pro_gui.id_guia = $id_guia";
                    $query_procedimentos      = mysql_query($sql_procedimentos, $banco_painel);
                                
                if (mysql_num_rows($query_procedimentos)>0)
                {  
                    $soma_procedimentos = 0;
                    while ($dados = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados); 
                        //$soma_procedimentos = $soma_procedimentos + $valor_cobrado;
                        $html_obs_prtocedimento = '';
                        /*if(!empty($obs_procedimento)){
                            $html_obs_prtocedimento = "<br/><span style=\"font-size: 12px;color: #848484;\">$obs_procedimento</span>";
                        }*/
                         
                        echo "<tr>
                                <td style=\"border-bottom: 1px solid #c3c3c3;\"> $codigo_procedimnto </td>
                                <td style=\"border-bottom: 1px solid #c3c3c3;\"> $nome_procedimento $html_obs_prtocedimento</td>
                              </tr>";
                    }
                    
                }
            ?> 
            </tbody>
        </table></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=75 HEIGHT=290></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=19></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=19></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=12 ROWSPAN=1 WIDTH=182 HEIGHT=12 style="font-size: 13px;"><strong>Informações de pagamento:</strong></TD>
	<TD COLSPAN=32 ROWSPAN=1 WIDTH=197 HEIGHT=12 style="font-size: 13px;"><?php if($local_pagamento == 'LOCAL'){
                            echo "NA EMISSÃO DESTA GUIA";
                        }else{
                            echo "NO LOCAL DE ATENDIMENTO";
                        }?></TD>
	<TD COLSPAN=38 ROWSPAN=1 WIDTH=344 HEIGHT=12></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=21></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=21></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=48 ROWSPAN=1 WIDTH=403 HEIGHT=3></TD>
	<TD COLSPAN=33 ROWSPAN=1 WIDTH=247 HEIGHT=3>
    <?php if($local_pagamento == 'LOCAL'){
                            echo " <IMG SRC=\"images/hex74.jpg\" HEIGHT=3 WIDTH=247 ALIGN=TOP BORDER=0></TD>";
                        }else{
                            echo "&nbsp;";
                        }
    ?>
   
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=73 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=12></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=54 ROWSPAN=1 WIDTH=453 HEIGHT=11></TD>
	<TD COLSPAN=17 ROWSPAN=1 WIDTH=147 HEIGHT=11 style="font-size: 12px;text-align: center;">
    <?php if($local_pagamento == 'LOCAL'){
                            echo "<strong>Carimbo de pagamento</strong>";
                        }else{
                            echo "&nbsp;";
                        }
    ?>
    </TD>
	<TD COLSPAN=11 ROWSPAN=1 WIDTH=123 HEIGHT=11></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=50></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=50></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=3></TD>
	<TD COLSPAN=24 ROWSPAN=1 WIDTH=249 HEIGHT=3><IMG SRC="images/hex76.jpg" HEIGHT=3 WIDTH=249 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=24 ROWSPAN=1 WIDTH=154 HEIGHT=3></TD>
	<TD COLSPAN=33 ROWSPAN=1 WIDTH=247 HEIGHT=3><IMG SRC="images/hex77.jpg" HEIGHT=3 WIDTH=247 ALIGN=TOP BORDER=0></TD>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=73 HEIGHT=3></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=8></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=8></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=12></TD>
	<TD COLSPAN=3 ROWSPAN=1 WIDTH=32 HEIGHT=12></TD>
	<TD COLSPAN=16 ROWSPAN=1 WIDTH=187 HEIGHT=12 style="text-align: center;"><?php echo $nome_user_cadastro;?></TD>
	<TD COLSPAN=33 ROWSPAN=1 WIDTH=218 HEIGHT=12></TD>
	<TD COLSPAN=20 ROWSPAN=1 WIDTH=187 HEIGHT=12 style="text-align: center;"><?php echo $gui_nome_paciente;?></TD>
	<TD COLSPAN=10 ROWSPAN=1 WIDTH=99 HEIGHT=12></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=9></TD>
	<TD COLSPAN=82 ROWSPAN=1 WIDTH=723 HEIGHT=9></TD>
</TR>

<TR ALIGN=LEFT VALIGN=TOP>
	<TD COLSPAN=1 ROWSPAN=1 WIDTH=1 HEIGHT=11></TD>
	<TD COLSPAN=25 ROWSPAN=1 WIDTH=221 HEIGHT=11 style="text-align: center;font-size: 12px;"><strong>Assinatura do responsável pela liberação</strong></TD>
	<TD COLSPAN=24 ROWSPAN=1 WIDTH=191 HEIGHT=11></TD>
	<TD COLSPAN=29 ROWSPAN=1 WIDTH=207 HEIGHT=11 style="text-align: center;font-size: 12px;"><strong>Assinatura do paciente ou responsável</strong></TD>
	<TD COLSPAN=9 ROWSPAN=1 WIDTH=88 HEIGHT=11></TD>
</TR>
</TABLE>
</BODY>
</HTML>
<?php
}else{
    echo "Ops! Têm algo errado, por favor, verifique as informações as seguintes informações: Data de agendamento vencida, cliente com pendência ou guia cancelada.".agora();
}
?>