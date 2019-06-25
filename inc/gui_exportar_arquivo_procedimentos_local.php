<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 

ini_set("max_execution_time", 300);
set_time_limit(300);

//tamanho de arquivo
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');

//exibir erro e log
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
$banco_painel = $link;

$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
$usr_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);

$horario = date('His');

$id    = (empty($_GET['id_local_atendimento'])) ? "" : verifica($_GET['id_local_atendimento']);

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel/Classes/');

/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'COD');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'PROCEDIMENTO');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'ID CONVENIO');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'PREÇO DE CUSTO');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'PREÇO VENDA');


$sharedStyle1 = new PHPExcel_Style();
$sharedStyle2 = new PHPExcel_Style();

$sharedStyle1->applyFromArray(
array('fill' 	=> array(
						'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'		=> array('argb' => '70ad11')
					)
 ));
$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "A1:E1");
$objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->getColor()->setARGB('ffffff');
$col = 'A';
while(true){
    $tempCol = $col++;
    $objPHPExcel->getActiveSheet()->getColumnDimension($tempCol)->setAutoSize(true);
    if($tempCol == $objPHPExcel->getActiveSheet()->getHighestDataColumn()){
        break;
    }
}
        
    $contar_linhas = 2;                                     
    $sql        = "SELECT ap.id_local_procedimento, p.id_procedimento, p.codigo, p.nome'nome_procedimento' FROM gui_local_atendimento_procedimentos ap
    JOIN gui_procedimentos p ON ap.id_procedimento = p.id_procedimento
                WHERE ap.id_local_atendimento = '$id'
                GROUP BY ap.id_procedimento
                ORDER BY p.id_procedimento ASC";
    $query      = mysql_query($sql, $banco_painel);
    
    if (mysql_num_rows($query)>0)
    {
        
        while ($dados = mysql_fetch_array($query))
        {
        extract($dados);  
        
        $sql_convenio  = "SELECT c.id_convenio, c.nome'nome_convenio', ap.preco_custo, ap.preco_venda FROM gui_convenios c
        JOIN gui_local_atendimento_procedimentos ap ON ap.id_convenio = c.id_convenio
                        WHERE c.ativo = 'S' AND ap.id_procedimento = $id_procedimento AND ap.id_local_atendimento = $id";
        $query_convenio = mysql_query($sql_convenio) or die(mysql_error()." - 145");
        if (mysql_num_rows($query_convenio)>0)
        {
            
            while ($dados_conv = mysql_fetch_array($query_convenio))
            {
                extract($dados_conv);  
                
                /*if(!empty($preco_custo) AND count($preco_custo) > 0){
                    $preco_custo = $preco_custo;
                }else{
                    $preco_custo = 0;
                }
                
                if(!empty($preco_venda) AND count($preco_custo) > 0){
                    $preco_venda = $preco_venda;
                }else{
                    $preco_venda = 0;
                }*/
                
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$contar_linhas, $codigo);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$contar_linhas, $nome_procedimento);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$contar_linhas, $id_convenio);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$contar_linhas, $preco_custo);
                //$objPHPExcel->getActiveSheet()->getStyle('D'.$contar_linhas)->getNumberFormat()->setFormatCode('0');
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$contar_linhas, $preco_venda);
                //$objPHPExcel->getActiveSheet()->getStyle('E'.$contar_linhas)->getNumberFormat()->setFormatCode('0');
                $contar_linhas++;
            }
        }
        
        }
    }    
        
        
        $objPHPExcel->getActiveSheet()->setTitle('procedimentos_expotados');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$id.'_procedimentos_expotados.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');


?>
