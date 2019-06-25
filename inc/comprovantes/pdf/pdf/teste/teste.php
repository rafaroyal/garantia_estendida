<?php
include("/home/vipassistenciaco/public_html/pdf/mpdf.php");
// Saves file on the server as 'filename.pdf'
$mpdf=new mPDF();
$mpdf->WriteHTML('<p>Hallo World</p>');
$mpdf->Output('filename.pdf','F');

?>