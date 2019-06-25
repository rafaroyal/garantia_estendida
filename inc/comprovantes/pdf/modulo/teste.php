<?php
// just require TCPDF instead of FPDF
require_once('tcpdf/tcpdf.php');
require_once('fpdi/fpdi.php');

class PDF extends FPDI
{
    /**
     * "Remembers" the template id of the imported page
     */
    var $_tplIdx;
    
    /**
     * Draw an imported PDF logo on every page
     */
    function Header()
    {
        
    }
    
    function Footer()
    {
        // emtpy method body
    }
}

// initiate PDF
$pdf = new PDF();
$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(true, 40);
$pdf->setFontSubsetting(false);

// add a page
$pdf->AddPage();

// get external file content
$utf8text = file_get_contents('tcpdf/examples/data/utf8test.txt', true);

$pdf->SetFont('freeserif', '', 12);
// now write some text above the imported page
$pdf->Write(5, $utf8text);

$pdf->Output();