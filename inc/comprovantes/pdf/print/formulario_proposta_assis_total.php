<?php

    require_once('../../conexao.php');
    require_once('../../functions.php');
    
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
        
        $sql_vendas        = "SELECT tipo_pagamento, desconto, valor_parcela, valor_parcela_total, prazo FROM vendas 
                            WHERE id_cliente = $id_cliente";
                           
    $query_vendas      = mysql_query($sql_vendas, $banco_produto);
                
    if (mysql_num_rows($query_vendas)>0)
    {
        $dados_vendas = mysql_fetch_array($query_vendas);
        extract($dados_vendas);  
    }
    
    

//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */


// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
$width = 1000;  
$height = 1000; 
// create new PDF document
$pagelayout = array($height, $width);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        //$img_file = '/home/fixoucursos/public_html/certificado/images/certificados/bg.jpg';
        //$this->Image($img_file, 0, 0, 0, 0, '', '', '', false, 0, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, $pagelayout, true, 'UTF-8', false);

//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pagelayout, true, 'UTF-8', false);
//$pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
//Add a custom size  


// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = <<<EOF
<html>
<body>
<style type="text/css">
body{
            overflow: hidden;
            line-height: 1.65em;
        } 
        html {
    display: block;
}

#apdiv1 {
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
#apdiv2 {
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
#apdiv3 {
	position: absolute;
	left: 100px;
	top: 277px;
	width: 404px;
	height: 20px;
	z-index: 3;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apdiv4 {
	position: absolute;
	left: 545px;
	top: 277px;
	width: 205px;
	height: 20px;
	z-index: 4;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apdiv5 {
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
#apdiv6 {
	position: absolute;
	left: 549px;
	top: 307px;
	width: 200px;
	height: 20px;
	z-index: 6;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apdiv7 {
	position: absolute;
	left: 89px;
	top: 338px;
	width: 413px;
	height: 20px;
	z-index: 7;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apdiv8 {
	position: absolute;
	left: 543px;
	top: 338px;
	width: 207px;
	height: 20px;
	z-index: 8;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apdiv9 {
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
#apdiv10 {
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
#apdiv11 {
	position: absolute;
	left: 620px;
	top: 415px;
	width: 28px;
	height: 20px;
	z-index: 11;
}
#apdiv12 {
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
#apdiv13 {
	position: absolute;
	left: 35px;
	top: 729px;
	width: 301px;
	height: 219px;
	z-index: 12;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	line-height: 20px;
	font-weight: bold;
}
#apdiv14 {
	position: absolute;
	left: 396px;
	top: 858px;
	width: 147px;
	height: 17px;
	z-index: 13;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	line-height: 20px;
	font-weight: bold;
}
</style>

<div id="apdiv1">$nome</div>
<div id="apdiv2">$cpf</div>
<div id="apdiv3">$endereco</div>
<div id="apdiv4">$numero</div>
<div id="apdiv5">$complemento</div>
<div id="apdiv6">$cep</div>
<div id="apdiv7">$cidade</div>
<div id="apdiv8">$estado</div>
<div id="apdiv9">Assistência Funeral Familiar</div>
<div id="apdiv10">$prazo meses</div>
<div id="apdiv12">$data_emissao</div>
<div id="apdiv13">
Tipo de Pagamento: $tipo_pagamento <br/>
Desconto: $desconto <br/>
Parcelas de: $valor_parcela_total <br/>
Prazo de: $prazo meses
</div>
<div id="apdiv14">$data_emissao</div>

<div STYLE="position: absolute; left: 598px; top:21px; width: 5px; height: 97px;">
<IMG SRC="img_proposta_assis_total/hex21.jpg" HEIGHT=97 WIDTH=5 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 379px; top:26px; width: 209px; height: 22px;">
<IMG SRC="img_proposta_assis_total/hex22.jpg" HEIGHT=22 WIDTH=209 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 41px; top:27px; width: 157px; height: 63px;">
<IMG SRC="img_proposta_assis_total/hex2.gif" HEIGHT=63 WIDTH=157 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 615px; top:31px; width: 56px; height: 13px;">
<IMG SRC="img_proposta_assis_total/hex24.jpg" HEIGHT=13 WIDTH=56 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 379px; top:55px; width: 209px; height: 63px;">
<IMG SRC="img_proposta_assis_total/hex25.jpg" HEIGHT=63 WIDTH=209 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 41px; top:97px; width: 157px; height: 12px;">
<IMG SRC="img_proposta_assis_total/hex4.gif" HEIGHT=12 WIDTH=157 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 227px; top:127px; width: 156px; height: 20px;">
<IMG SRC="img_proposta_assis_total/hex27.jpg" HEIGHT=20 WIDTH=156 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 383px; top:127px; width: 185px; height: 20px;">
<IMG SRC="img_proposta_assis_total/hex28.jpg" HEIGHT=20 WIDTH=185 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 17px; top:154px; width: 760px; height: 48px;">
<IMG SRC="img_proposta_assis_total/hex29.jpg" HEIGHT=48 WIDTH=760 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 17px; top:211px; width: 750px; height: 243px;">
<IMG SRC="img_proposta_assis_total/hex30.jpg" HEIGHT=243 WIDTH=750 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 17px; top:471px; width: 750px; height: 210px;">
<IMG SRC="img_proposta_assis_total/hex31.jpg" HEIGHT=210 WIDTH=750 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 17px; top:697px; width: 334px; height: 267px;">
<IMG SRC="img_proposta_assis_total/hex32.jpg" HEIGHT=267 WIDTH=334 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 362px; top:713px; width: 374px; height: 37px;">
<IMG SRC="img_proposta_assis_total/hex33.jpg" HEIGHT=37 WIDTH=374 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 362px; top:764px; width: 374px; height: 26px;">
<IMG SRC="img_proposta_assis_total/hex34.jpg" HEIGHT=26 WIDTH=374 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 362px; top:803px; width: 374px; height: 39px;">
<IMG SRC="img_proposta_assis_total/hex35.jpg" HEIGHT=39 WIDTH=374 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 362px; top:863px; width: 374px; height: 13px;">
<IMG SRC="img_proposta_assis_total/hex36.jpg" HEIGHT=13 WIDTH=374 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 362px; top:938px; width: 374px; height: 3px;">
<IMG SRC="img_proposta_assis_total/hex37.jpg" HEIGHT=3 WIDTH=374 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 362px; top:951px; width: 374px; height: 13px;">
<IMG SRC="img_proposta_assis_total/hex38.jpg" HEIGHT=13 WIDTH=374 ALIGN=TOP BORDER=0></div>
<div STYLE="position: absolute; left: 36px; top:981px; width: 722px; height: 52px;">
<IMG SRC="img_proposta_assis_total/hex39.jpg" HEIGHT=52 WIDTH=722 ALIGN=TOP BORDER=0></div>

    
    </body>
   </html>
EOF;


// output the HTML content
$pdf->writeHTML($html, true, true, true, true, '');
// remove default header
$pdf->setPrintHeader(true);
$nome_pdf = $name.'_'.$surname.'.pdf';
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($nome_pdf, 'I');

//============================================================+
// END OF FILE
//============================================================+
