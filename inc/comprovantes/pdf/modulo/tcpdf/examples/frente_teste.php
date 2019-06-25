   <?php

    require_once('/home/fixoucursos/public_html/wp-content/themes/forte/scripts/conexao.php');
    require_once('/home/fixoucursos/public_html/wp-content/themes/forte/scripts/functions.php');

?>
      
    <?php 
    
    /*$origem = $_SERVER['HTTP_REFERER'];
    if (empty($origem))
    {
        header("Location: http://www.fixou.com.br");
    }*/
    
    $id_aluno = (empty($_GET['aluno'])) ? "" : $_GET['aluno'];
    $id_aluno = verifica($id_aluno);
    $id_curso = (empty($_GET['curso'])) ? "" : $_GET['curso'];
    $id_curso = verifica($id_curso);
    
    
    $sql        = "SELECT uc.users_LOGIN, uc.active, uc.from_timestamp, uc.completed, uc.score, 
                    uc.to_timestamp, u.id, u.login, u.email, u.`name`, u.surname, c.`name`'nome_curso', c.active'curso_ativo', c.carga_horaria FROM users_to_courses uc
                    JOIN users u ON u.login = uc.users_LOGIN
                    JOIN courses c ON c.id = uc.courses_ID
                    WHERE u.id = $id_aluno AND uc.courses_ID = $id_curso AND uc.completed = 1";
    
    $query      = mysql_query($sql);
    
    if(mysql_numrows($query) > 0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);
    
    
        $time = $from_timestamp;                // you have 1299446702 in time
        $year = $time/31556926 % 12;  // to get year
        $week = $time / 604800 % 52;  // to get weeks
        $hour = $time / 3600 % 24;    // to get hours
        $minute = $time / 60 % 60;    // to get minutes
        $second = $time % 60;    
        
        $time_ = $to_timestamp;                // you have 1299446702 in time
        $year_ = $time_ /31556926 % 12;  // to get year
        $week_ = $time_ / 604800 % 52;  // to get weeks
        $hour_ = $time_ / 3600 % 24;    // to get hours
        $minute_ = $time_ / 60 % 60;    // to get minutes
        $second_ = $time_ % 60;    
        
        


    $data = gmdate("Y-m-d", $to_timestamp);
 
    $mydate = strtotime($data);
    //echo date('F jS Y', $mydate);
    
    //date('d w Y', $mydate);
    $dia    =  date('d', $mydate);
    $semana =  date('w', $mydate);
    $mes    =  date('n', $mydate);
    $ano    =  date('Y', $mydate);
    
    if($semana == '0'){
       $semana = 'Domingo'; 
    }elseif($semana == '1'){
        $semana = 'Segunda-feira';
    }elseif($semana == '2'){
        $semana = 'Terça-feira';
    }elseif($semana == '3'){
        $semana = 'Quarta-feira';
    }elseif($semana == '4'){
        $semana = 'Quinta-feira';
    }elseif($semana == '5'){
        $semana = 'Sexta-feira';
    }elseif($semana == '6'){
        $semana = 'Sábado';
    }
    
    if($mes == '1'){
       $mes = 'Janeiro'; 
    }elseif($mes == '2'){
        $mes = 'Fevereiro';
    }elseif($mes == '3'){
        $mes = 'Março';
    }elseif($mes == '4'){
        $mes = 'Abril';
    }elseif($mes == '5'){
        $mes = 'Maio';
    }elseif($mes == '6'){
        $mes = 'Junho';
    }elseif($mes == '7'){
        $mes = 'Julho';
    }elseif($mes == '8'){
        $mes = 'Agosto';
    }elseif($mes == '9'){
        $mes = 'Setembro';
    }elseif($mes == '10'){
        $mes = 'Outubro';
    }elseif($mes == '11'){
        $mes = 'Novembro';
    }elseif($mes == '12'){
        $mes = 'Dezembro';
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

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content

    ?>

<?php 
   
   $html = "<style type=\"text/css\">
        body{
            overflow: hidden;
        }    
        
        .div1
        {
            position: absolute;
            width: 100%;
            margin: 0px auto 0px auto;
            top: 20px;
            z-index:1;
        }
        .div2
        {
            position: absolute;
            margin: 0px auto 0px auto;
            width: 100%;
            top: 20px;
            z-index: 2;
        }
        #cabecalho
        {
            visibility: hidden;
            display: none;
        }
        .div_imprimir{
            background: rgba(102,102,102,0.97);
              right: 0;
              position: fixed;
              top: 100px;
              z-index: 999;
              display: block;
        }
        
        @media print
        {
            .naoprint
            {
                display: none;
            }
        }
        
        @page {  
 size: 280mm 180mm;  
 margin-left: 0px;  
 margin-right: 0px;  
 margin-top: 0px;  
 margin-bottom: 0px;  
} 
    </style>
    <div class=\"div_imprimir naoprint\"><a href=\"#\" title=\"Imprimir Certificado\"  onclick=\"window.print();\" style=\"  position: relative;
  float: left;
  display: block;
  width: 100%;
  overflow: hidden;padding: 10px;\"><img id=\"imgCert\" src=\"/home/fixoucursos/public_html/certificado/images/certificados/print.png\" style=\"border-width:0px;\"/></a></div>
  
  

    <div class=\"div1\">
        <table cellpadding=\"0px\" cellspacing=\"0px\" width=\"850px\" align=\"center\">
            <tbody>
                <tr> 
                    <td align=\"center\">
                        <img id=\"imgCert\" src=\"/home/fixoucursos/public_html/certificado/images/certificados/bg.jpg\" style=\"border-width:0px;\"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class=\"div2\">
    <table cellpadding=\"0px\" cellspacing=\"0px\" width=\"800px\" align=\"center\">
        <tbody><tr height=\"40px\">
            <td align=\"right\">
            
             </td>
        </tr>
        <tr>
            <td align=\"center\"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr height=\"30px\">
            <td style=\"font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;\" align=\"center\"><span id=\"txt3\">Conferimos o presente certificado à</span>
                
            </td>
        </tr>
        <tr height=\"30px\">
            <td style=\"font-size: xx-large; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;\" align=\"center\"><span id=\"txt4\">$name $surname </span></td>
        </tr>
        <tr height=\"30px\">
            <td style=\"font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;\" align=\"center\"><span id=\"txt5\">por ter concluído o curso de $nome_curso</span></td>
        </tr>
        <tr height=\"30px\">
            <td style=\"font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;\" align=\"center\"><span id=\"txt1\"> nesta data, com carga horária de $carga_horaria horas e aproveitamento </span></td>
        </tr>
        <tr height=\"30px\">
            <td style=\"font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;\" align=\"center\"><span id=\"txt2\">médio de $score % nas avaliações.</span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table cellpadding=\"0px\" cellspacing=\"0px\" width=\"750px\">
                    <tbody><tr height=\"30px\">
                        <td style=\"font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;\" width=\"750px\" align=\"right\">
                            <span id=\"lblCidade\"></span><span id=\"lblData\">$semana, $dia de $mes de $ano</span>
                        </td>
                    </tr>
                </tbody></table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <div id=\"panelIdAluno\">
	
                <table cellpadding=\"0px\" cellspacing=\"0px\" width=\"750px\">
                    <tbody><tr>
                        <td align=\"right\">
                        </td>
                    </tr>
                </tbody></table>
                
</div>
            </td>
        </tr>
    </tbody></table>
    </div>
    </form>";



// output the HTML content
$pdf->writeHTML($html);
// reset pointer to the last page
$pdf->lastPage();

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');


}
else
{
    echo "erro";
}
?>