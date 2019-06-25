<?php
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
$width = 169;  
$height = 264; 
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
        $img_file = K_PATH_IMAGES.'bg.jpg';
        $this->Image($img_file, 0, 0, 0, 0, '', '', '', false, 0, '', false, false, 0);
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
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = <<<EOF
 <style type="text/css">
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
    <div class="div2">
    <table cellpadding="0px" cellspacing="0px" width="800px" align="center">
        <tbody><tr height="40px">
            <td align="right">
            
             </td>
        </tr>
        <tr>
            <td align="center"></td>
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
        <tr height="30px">
            <td style="font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;" align="center"><span id="txt3">Conferimos o presente certificado à</span>
                
            </td>
        </tr>
        <tr height="30px">
            <td style="font-size: xx-large; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;" align="center"><span id="txt4"></span></td>
        </tr>
        <tr height="30px">
            <td style="font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;" align="center"><span id="txt5">por ter concluído o curso de </span></td>
        </tr>
        <tr height="30px">
            <td style="font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;" align="center"><span id="txt1"> nesta data, com carga horária de </span></td>
        </tr>
        <tr height="30px">
            <td style="font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;" align="center"><span id="txt2">médio de  nas avaliações.</span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0px" cellspacing="0px" width="750px">
                    <tbody><tr height="30px">
                        <td style="font-size:medium; font-weight:bold; font-family: Arial, Helvetica, sans-serif; color: #333333;" width="750px" align="right">
                            <span id="lblCidade"></span><span id="lblData"></span>
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
                <div id="panelIdAluno">
	
                <table cellpadding="0px" cellspacing="0px" width="750px">
                    <tbody><tr>
                        <td align="right">
                        </td>
                    </tr>
                </tbody></table>
                
</div>
            </td>
        </tr>
    </tbody></table>
    </div>
    </form>
EOF;

$pdf->writeHTML($html, true, false, true, false, '');

// remove default header
$pdf->setPrintHeader(false);

// add a page
$pdf->AddPage();

// -- set new background ---

// get the current page break margin
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);
// set bacground image
$img_file = K_PATH_IMAGES.'bg2.jpg';
$pdf->Image($img_file, 0, 0, 0, 0, '', '', '', false, 0, '', false, false, 0);
// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();

$html = <<<EOF
fdd
EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
