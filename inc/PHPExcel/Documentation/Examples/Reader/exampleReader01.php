<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>PHPExcel Reader Example #01</title>

</head>
<body>

<h1>PHPExcel Reader Example #01</h1>
<h2>Simple File Reader using PHPExcel_IOFactory::load()</h2>
<?php

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');

/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';

//$inputFileType = 'CSV';
$inputFileName = './sampleData/lancamentos.xls';
echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
//$objReader = PHPExcel_IOFactory::createReader($inputFileType);
//$objPHPExcel = $objReader->load($inputFileName);
echo '<hr />';
/*
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
var_dump($sheetData);
*/

$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

echo 'getHighestColumn() =  [' . $highestColumm . ']<br/>';
echo 'getHighestRow() =  [' . $highestRow . ']<br/>';

//echo '<table border="1">';
for($linha = 4;$linha <= $highestRow;$linha++) {
    
    $cell_data_pagamento = "B".$linha;
    $cell_doc_nsu = "F".$linha;
    $cell_cod_aut = "G".$linha;
    $cell_valor = "H".$linha;
    $cell_rejeitado = "I".$linha;
    
    $valor_data_pagamento = $objPHPExcel->getActiveSheet()->getCell($cell_data_pagamento)->getValue();
    $valor_data_pagamento = date($format = "d-m-Y", PHPExcel_Shared_Date::ExcelToPHP($valor_data_pagamento));
    echo $valor_data_pagamento;    
}
//echo '</table>';

?>
<body>
</html>