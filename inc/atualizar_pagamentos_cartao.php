<script>
$(document).ready(function () {

    function exportTableToCSV($table, filename) {

        var $rows = $table.find('tr:has(td)'),

            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character

            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',

            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');

                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();

                    return text.replace(/"/g, '""'); // escape double quotes

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"';

				// Deliberate 'false', see comment below
        if (false && window.navigator.msSaveBlob) {

						var blob = new Blob([decodeURIComponent(csv)], {
	              type: 'text/csv;charset=utf8'
            });
            
            // Crashes in IE 10, IE 11 and Microsoft Edge
            // See MS Edge Issue #10396033: https://goo.gl/AEiSjJ
            // Hence, the deliberate 'false'
            // This is here just for completeness
            // Remove the 'false' at your own risk
            window.navigator.msSaveBlob(blob, filename);
            
        } else if (window.Blob && window.URL) {
						// HTML5 Blob        
            var blob = new Blob([csv], { type: 'text/csv;charset=utf8' });
            var csvUrl = URL.createObjectURL(blob);

            $(this)
            		.attr({
                		'download': filename,
                		'href': csvUrl
		            });
				} else {
            // Data URI
            var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

						$(this)
                .attr({
               		  'download': filename,
                    'href': csvData,
                    'target': '_blank'
            		});
        }
    }

    // This must be a hyperlink
    $("#bt_export_xls").on('click', function (event) {
        // CSV
        var args = [$('#conteudo_bt_export_xls>table'), 'export.csv'];
        
        exportTableToCSV.apply(this, args);
        
        // If CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });
});

</script>
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
$where_parceiro = '';
if($nivel_usuario == "A"){
    $sql        = "SELECT * FROM bases_produtos 
                WHERE ativo = 'S'";  
}
$query      = mysql_query($sql, $banco_painel);

if (mysql_num_rows($query)>0)
{
    
    while($dados = mysql_fetch_array($query)){
        extract($dados);  

        // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
        $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                    JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                    WHERE bpro.id_base_produto = $id_base_produto";
        
        $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
        
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
                


        if($slug == 'europ')
        {
            $valida_dados = false;
            $whele_cod_barras = '';
            $array_clientes = false;
            
            $nome_cliente_db_array    = array();
            $id_ordem_pedido_array    = array();
            



//foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); } 

//$arquivo            = (empty($_POST['arquivo_retorno'])) ? "" : verifica($_POST['arquivo_retorno']);  
//$tipo_retorno       = (empty($_POST['tipo_retorno'])) ? "" : verifica($_POST['tipo_retorno']);

/*$fileName = $_FILES['arquivo_retorno']['name'];
$fileType = $_FILES['arquivo_retorno']['type'];
$fileError = $_FILES['arquivo_retorno']['error'];
$fileContent = file_get_contents($_FILES['arquivo_retorno']['tmp_name']);
*/
$horario = date('His');
    
    $nome_arquivo_sem_extencao = pathinfo($_FILES['arquivo_retorno']['name'], PATHINFO_FILENAME);
    $extensao_arquivo = $extensao = pathinfo($_FILES['arquivo_retorno']['name'], PATHINFO_EXTENSION);  


    $uploaddir      = '../arquivos/retorno/cartao/';
    $uploadfile     = $uploaddir . $nome_arquivo_sem_extencao."_".$horario.".".$extensao_arquivo;
    $nome_arquivo   = $nome_arquivo_sem_extencao."_".$horario.".".$extensao_arquivo;


    if (move_uploaded_file($_FILES['arquivo_retorno']['tmp_name'], $uploaddir . $nome_arquivo))
    {
        //upload com sucesso
        //verifica o número de títulos
        //$arquivo = file($uploadfile);

        /** Include path **/
        set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel/Classes/');
        
        /** PHPExcel_IOFactory */
        include 'PHPExcel/IOFactory.php';
        
        //$inputFileType = 'CSV';
        $inputFileName = './sampleData/lancamentos.xls';
        
        $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
        
        
        $highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataRow();
        //echo $highestRow;
        //echo '<table border="1">';
        
        echo "<a href=\"javascript:;\" class=\"btn btn-sm blue\" id=\"bt_export_xls\"> <i class=\"fa fa-upload\"></i> Exportar para Excel </a>";
        
        $sql_a      = "INSERT INTO arquivos_cartao (nome_arquivo, data_cadastro, processado, registro_total)
                                                VALUES ('$nome_arquivo', '".agora()."', 'N', 0)";
        $query_a    = mysql_query($sql_a, $banco_painel);
        $id_arquivo = mysql_insert_id($banco_painel);
        $contar_linhas = 0;
        $contar_processados = 0;
        $contar_ja_processados = 0;
        $contar_erros = 0;
        //processa e gera as chaves
        echo " <div class=\"portlet-body\">
                <div class=\"table-scrollable\" id=\"conteudo_bt_export_xls\">
            <table class=\"table table-hover\">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Cliente </th>
                                <th> Parcelas </th>
                                <th> Data Pagamento </th>
                                <th> Valor Parcela </th>
                                <th> Vencimento </th>
                                <th> Pago </th>
                            </tr>
                        </thead>
                        <tbody>";   
        
        for($linha = 4;$linha <= $highestRow;$linha++) {
            $cell_data_venda = "B".$linha;
            $cell_data_pagamento = "A".$linha;
            $cell_doc_nsu = "G".$linha;
            $cell_cod_aut = "H".$linha;
            $cell_valor = "I".$linha;
            $cell_rejeitado = "J".$linha;
            $data_pagamento_convertido = '';
            $id_ordem_pedido_array = '';
            $nome_cliente_db_array = '';
            
            $data_venda = $objPHPExcel->getActiveSheet()->getCell($cell_data_venda)->getValue();
            $data_pagamento = $objPHPExcel->getActiveSheet()->getCell($cell_data_pagamento)->getValue();
            if(!empty($data_pagamento)){
                $data_venda_convertido     = date("d-m-Y", PHPExcel_Shared_Date::ExcelToPHP($data_venda));
                $data_pagamento_convertido = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($data_pagamento));
            
            
                $data_pagamento_convertido_array = explode("-", $data_pagamento_convertido);
                
               
                
                $comprovante_doc_nsu     = $objPHPExcel->getActiveSheet()->getCell($cell_doc_nsu)->getValue();
                $comprovante_maquina_aut = $objPHPExcel->getActiveSheet()->getCell($cell_cod_aut)->getValue();
                $valor_pago              = $objPHPExcel->getActiveSheet()->getCell($cell_valor)->getValue();
                $rejeitado               = $objPHPExcel->getActiveSheet()->getCell($cell_rejeitado)->getValue(); 
                //echo "-".$rejeitado."-";
                
                if (strpos($rejeitado, 'Não') !== false) {
                    $pago_novo = 'S';
                }else{
                    $pago_novo = 'N';
                }

                /*if($rejeitado === "Não"){
                    
                }else{
                    
                }*/
                
                
                $sql        = "SELECT bcli.*, op.ordem_pedido FROM boletos_clientes bcli
                            JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                            WHERE comprovante_maquina = $comprovante_maquina_aut AND comprovante_doc = $comprovante_doc_nsu AND mes_referencia = $data_pagamento_convertido_array[1] AND ano_referencia = $data_pagamento_convertido_array[0]";
                            //echo $sql;
                $query      = mysql_query($sql, $banco_painel);
                                
                if (mysql_num_rows($query)>0)
                {
                    $dados = mysql_fetch_array($query);
                    extract($dados); 
                    $id_ordem_pedido_array[] = $id_ordem_pedido;  
                    $html_status_linha = 'class="success"';
                    if($pago == 'S'){
                        $contar_ja_processados++;
                        $html_status_linha = 'class="warning"';
                    }
                    
                    $sql_u      = "UPDATE boletos_clientes SET pago = '$pago_novo', data_pagamento = '$data_pagamento_convertido', valor_recebido = '$valor_pago', id_usuario_recebimento = 138, id_parceiro_recebimento = 17, tipo_recebimento = 'CA'
                    WHERE comprovante_maquina = $comprovante_maquina_aut AND comprovante_doc = $comprovante_doc_nsu AND mes_referencia = $data_pagamento_convertido_array[1] AND ano_referencia = $data_pagamento_convertido_array[0]";
                    $query_u    = mysql_query($sql_u, $banco_painel);
                    
                    $contar_processados++;
        
                    
                    $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                                
                    $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                    for($ib=0;$contar_array_id_base_ids_vendas>=$ib;$ib++)
                    {
                        $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$ib]);
                        $id_base = $array_ids_base_vendas[0];
                        $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                        
                        if($id_base == $id_base_produto){
                            
                            $sql_venda  = "SELECT c.id_cliente, c.nome FROM vendas v
                                                JOIN clientes c ON v.id_cliente = c.id_cliente
                                                WHERE v.id_venda = $ids_vendas[0]";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                            
                            if (mysql_num_rows($query_venda)>0)
                            {
                                $id_cliente_db   = mysql_result($query_venda, 0, 'id_cliente');
                                $nome_cliente_db_array[] = mysql_result($query_venda, 0, 'nome');
                            }
                            
                        }
                    }
                    
                    
                    $contar_array = 0;
                    //for($ic=0;$ic<=$contar_array;$ic++){
                
                        $sql        = "SELECT id_boleto, entrada, parcela, total_parcelas, valor_parcela, pago, data_vencimento FROM boletos_clientes
                                       WHERE id_ordem_pedido = $id_ordem_pedido_array[0] AND status_boleto = 0 AND id_boleto = $id_boleto";
                                    
                        $query      = mysql_query($sql, $banco_painel);
                                    
                        if (mysql_num_rows($query)>0)
                        {
    
                            while($dados = mysql_fetch_array($query))
                            {
                               extract($dados);
                                
                                $status_list = array(
                                    array("green" => "Pago"),
                                    array("red" => "Receber")
                                );
                                
                                if($pago == 'N')
                                {
                                    $status = $status_list[1];
                                }
                                else
                                {
                                    $status = $status_list[0];
                                }
                                $html_tipo = '';
                                if($entrada == 'S'){
                                    $html_tipo = "ENTRADA";
                                }
                                $pg_html_exibe = 'hide';
                                if($pago == 'S'){
                                    $pg_html_exibe = '';
                                }
                                
                                echo '<tr '.$html_status_linha.'>
                                    <td> '.$id_boleto.' </td>
                                    <td> '.$nome_cliente_db_array[0].' </td>
                                    <td> '.$parcela.' /'.$total_parcelas.' </td>
                                    <td> '.converte_data($data_pagamento_convertido).' </td>
                                    <td> '.db_moeda($valor_parcela).' </td>
                                    <td> '.converte_data($data_vencimento).' </td>';
                                
                                    echo '<td> <a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a> </td>';
  
                                echo '</tr>';
                                
                            }
                            
                            
                            
                        }else{
                           $contar_erros++;
                            echo '<tr class="danger">
                                    <td colspan="7">A'.$linha.'-'.$data_venda_convertido.' | B'.$linha.'-'.converte_data($data_pagamento_convertido).' | F'.$linha.'-'.$comprovante_doc_nsu.' | G'.$linha.'-'.$comprovante_maquina_aut.' | H'.$linha.'-'.$valor_pago.' | I'.$linha.'-'.$rejeitado.'</td>';
                                echo '</tr>';
                        }
                    
                    //}
  
                }else{
                    $contar_erros++;
                            echo '<tr class="danger">
                                    <td colspan="7">A'.$linha.'-'.$data_venda_convertido.' | B'.$linha.'-'.converte_data($data_pagamento_convertido).' | F'.$linha.'-'.$comprovante_doc_nsu.' | G'.$linha.'-'.$comprovante_maquina_aut.' | H'.$linha.'-'.$valor_pago.' | I'.$linha.'-'.$rejeitado.'</td>';
                                echo '</tr>';
                }
              $contar_linhas++;
            }
        }
        
        echo "</tbody>
                </table>
            </div>";
            //$num_titulos = $num_titulos - 2;
        echo "Total de registros: ".$contar_linhas." / Processados: ".$contar_processados." / Já Registrados: ".$contar_ja_processados." / Erros: ".$contar_erros;
        echo "</div>";
        
        if($contar_processados > 0){
            
            $sql_u_a      = "UPDATE arquivos_cartao SET processado = 'S', registro_total = $contar_linhas, registro_processado = $contar_processados, registro_erro = $contar_erros
            WHERE id_arquivo = $id_arquivo";
            $query_u_a    = mysql_query($sql_u_a, $banco_painel);                    
        }

    }
    else
    {
        if ($_FILES["arquivo_retorno"]["error"] > 0)
        {
            echo "Error: " . $_FILES["arquivo_retorno"]["error"] . "<br>";
        }
        echo "Erro ao processar requisição. Tentar novamente.";
    }
      
}
    }
}

?>
