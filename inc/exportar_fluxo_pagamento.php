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
//date_default_timezone_set('Europe/London');
$banco_painel = $link;

$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
$usr_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);

$horario = date('His');

$lista_parceiros         = $_POST['lista_parceiros']; 

if($nivel_usuario == "A"){
    $sql        = "SELECT * FROM bases_produtos 
                WHERE ativo = 'S'";  
}
$query      = mysql_query($sql, $banco_painel);
//echo "1";
if (mysql_num_rows($query)>0)
{
    while($dados = mysql_fetch_array($query)){
        extract($dados);  
        //echo "3";
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
              
        /** Include path **/
        set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel/Classes/');
        
        /** PHPExcel_IOFactory */
        include 'PHPExcel/IOFactory.php';
        
        $objPHPExcel = new PHPExcel();
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID CLIENTE');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'DATA VENDA');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NOME');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'DATA NASCIMENTO');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'TELEFONE');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CELULAR');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'COLABORADOR');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'PARCEIRO');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'PLANO');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'FORMA PAGAMENTO');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'VENC PARCELAS');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'TOTAL');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'PARCELAS');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'ENTRADA');
        
        
        
        $sharedStyle2 = new PHPExcel_Style();
        
            $contar_linhas = 2;
            $sql_info_geral  = "SELECT id_ordem_pedido'ordem_pedido_verifica', data_vencimento FROM boletos_clientes
            WHERE entrada = 'N' AND status_boleto = 0
            GROUP BY id_ordem_pedido
            ORDER BY data_vencimento ASC";
            $query_info_geral = mysql_query($sql_info_geral, $banco_painel) or die(mysql_error()." - 145");
            if (mysql_num_rows($query_info_geral)>0)
            {
                while ($dados_info_geral = mysql_fetch_array($query_info_geral))
                {
                    extract($dados_info_geral);  
                    
            $lista_parceiros_implode = implode(",", array_values($lista_parceiros));
            //error_log($lista_parceiros_implode, 0);
            //$teste_array = $lista_parceiros[0]['value'];
            //$ultimo_dia_mes = date("Y-m-t");
            $ultimo_dia_mes = date("Y-m");
            $ultimo_dia_mes = $ultimo_dia_mes.'-01';
                                                 
            $sql_cliente        = "SELECT c.id_cliente, c.id_parceiro, c.id_usuario, c.nome, c.id_produto, c.data_nascimento, c.telefone, c.celular, v.id_ordem_pedido, v.valor_total, v.metodo_pagamento FROM clientes c
            JOIN vendas v ON c.id_cliente = v.id_cliente
            WHERE c.id_parceiro IN ($lista_parceiros_implode) AND (c.id_cliente_principal = 0 OR c.id_cliente_principal = '') AND c.tipo_movimento IN('IN', 'AL') AND c.data_termino >=  '$ultimo_dia_mes' AND (c.status = 99 OR c.status = 0) AND v.metodo_pagamento IN('BO', 'MA') AND v.id_ordem_pedido = $ordem_pedido_verifica
            GROUP BY c.chave";
            //error_log($sql_cliente);
            $query_cliente      = mysql_query($sql_cliente, $banco_produto);
            
            if (mysql_num_rows($query_cliente)>0)
            {
                               
                while ($dados_clinte = mysql_fetch_array($query_cliente))
                {
                    extract($dados_clinte);  
                    
                    $sql_info  = "SELECT nome FROM grupos_produtos
                        WHERE id_grupo_produto = $id_produto";    
                    $query_info = mysql_query($sql_info, $banco_painel) or die(mysql_error()." - 145");
                    $nome_plano = '';
                    if (mysql_num_rows($query_info)>0)
                    {
                        $nome_plano = mysql_result($query_info, 0, 'nome');
                    }
                    
                    $sql_info  = "SELECT nome FROM usuarios
                        WHERE id_usuario = $id_usuario";    
                    $query_info = mysql_query($sql_info, $banco_painel) or die(mysql_error()." - 145");
                    $nome_colaborador = '';
                    if (mysql_num_rows($query_info)>0)
                    {
                        $nome_colaborador = mysql_result($query_info, 0, 'nome');
                    }
                    
                    $sql_info  = "SELECT nome FROM parceiros
                        WHERE id_parceiro = $id_parceiro";    
                    $query_info = mysql_query($sql_info, $banco_painel) or die(mysql_error()." - 145");
                    $nome_colaborador_parceiro = '';
                    if (mysql_num_rows($query_info)>0)
                    {
                        $nome_colaborador_parceiro = mysql_result($query_info, 0, 'nome');
                    }
                    
                    if($metodo_pagamento == 'BO'){
                        $metodo_pagamento = 'BOLETO';
                    }else{
                        $metodo_pagamento = 'CARTÃO';
                    }
                    
                    $sql_info  = "SELECT mes_referencia, ano_referencia, parcela, total_parcelas, valor_parcela, data_cadastro, data_vencimento FROM boletos_clientes
                        WHERE id_ordem_pedido = $id_ordem_pedido AND entrada = 'N' AND status_boleto = 0
                        ORDER BY data_vencimento ASC";
                    $query_info = mysql_query($sql_info, $banco_painel) or die(mysql_error()." - 145");
                    if (mysql_num_rows($query_info)>0)
                    {
                        $sql_info_dados  = "SELECT total_parcelas, data_cadastro, data_vencimento FROM boletos_clientes
                        WHERE id_ordem_pedido = $id_ordem_pedido AND entrada = 'N' AND status_boleto = 0
                        ORDER BY data_vencimento ASC";
                        $query_info_dados = mysql_query($sql_info_dados, $banco_painel) or die(mysql_error()." - 145");
                        $data_cadastro = '';
                        $data_vencimento = '';
                        $data_vencimento_array = '';
                        $total_parcelas = '';
                        if (mysql_num_rows($query_info_dados)>0)
                        {
                        $data_cadastro = mysql_result($query_info_dados, 0, 'data_cadastro');
                        $data_cadastro = converte_data($data_cadastro);
                        
                        $data_vencimento = mysql_result($query_info_dados, 0, 'data_vencimento');
                        $data_vencimento_array = explode('-', $data_vencimento);
                        
                        $total_parcelas = mysql_result($query_info_dados, 0, 'total_parcelas');
                            
                        }
                        
                        
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$contar_linhas, $id_cliente);
                        $objPHPExcel->getActiveSheet()->setCellValue('B'.$contar_linhas, $data_cadastro);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.$contar_linhas, $nome);
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.$contar_linhas, converte_data($data_nascimento));
                        $objPHPExcel->getActiveSheet()->setCellValue('E'.$contar_linhas, $telefone);
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.$contar_linhas, $celular);
                        $objPHPExcel->getActiveSheet()->setCellValue('G'.$contar_linhas, $nome_colaborador);
                        $objPHPExcel->getActiveSheet()->setCellValue('H'.$contar_linhas, $nome_colaborador_parceiro);
                        $objPHPExcel->getActiveSheet()->setCellValue('I'.$contar_linhas, $nome_plano);
                        $objPHPExcel->getActiveSheet()->setCellValue('J'.$contar_linhas, $metodo_pagamento);
                        $objPHPExcel->getActiveSheet()->setCellValue('K'.$contar_linhas, $data_vencimento_array[2]);
                        $objPHPExcel->getActiveSheet()->setCellValue('L'.$contar_linhas, db_moeda3($valor_total));
                        $objPHPExcel->getActiveSheet()->setCellValue('M'.$contar_linhas, $total_parcelas);
                        
                        $sql_info_dados  = "SELECT valor_parcela FROM boletos_clientes
                        WHERE id_ordem_pedido = $id_ordem_pedido AND entrada = 'S' AND status_boleto = 0";
                        $query_info_dados = mysql_query($sql_info_dados, $banco_painel) or die(mysql_error()." - 14125");
                        $valor_entrada = '';
                        if (mysql_num_rows($query_info_dados)>0)
                        {
                            $valor_entrada = mysql_result($query_info_dados, 0, 'valor_parcela');
                            $objPHPExcel->getActiveSheet()->setCellValue('N'.$contar_linhas, db_moeda3($valor_entrada));
                            
                        }
                        
                        $col = 'O';
                        $add_coluna = 'O';
                       /* while ($dados_info = mysql_fetch_array($query_info))
                        {
                            extract($dados_info);
                            
                            if($ano_referencia == $ano_atual){
                                
                            }else{
                                
                            }
                            
                            if($mes_referencia == '1'){
                               $mes_referencia = 'JANEIRO'; 
                               
                            }elseif($mes_referencia == '2'){
                                $mes_referencia = 'FEVEREIRO';
                                
                            }elseif($mes_referencia == '3'){
                                $mes_referencia = 'MARÇO';
                                
                            }elseif($mes_referencia == '4'){
                                $mes_referencia = 'ABRIL';
                                
                            }elseif($mes_referencia == '5'){
                                $mes_referencia = 'MAIO';
                                
                            }elseif($mes_referencia == '6'){
                                $mes_referencia = 'JUNHO';
                                
                            }elseif($mes_referencia == '7'){
                                $mes_referencia = 'JULHO';
                                
                            }elseif($mes_referencia == '8'){
                                $mes_referencia = 'AGOSTO';
                                
                            }elseif($mes_referencia == '9'){
                                $mes_referencia = 'SETEMBRO';
                                
                            }elseif($mes_referencia == '10'){
                                $mes_referencia = 'OUTUBRO';
                                
                            }elseif($mes_referencia == '11'){
                                $mes_referencia = 'NOVEMBRO';
                                
                            }elseif($mes_referencia == '12'){
                                $mes_referencia = 'DEZEMBRO';
                                
                            }
                            
                            
                            $objPHPExcel->getActiveSheet()->setCellValue($add_coluna.'1', $mes_referencia.'-'.$ano_referencia);
                            $objPHPExcel->getActiveSheet()->setCellValue($add_coluna.$contar_linhas, db_moeda3($valor_parcela));

                            $add_coluna = $col++;
                            $ano_atual = $ano_referencia;
                        }
                        */
                        /*$objPHPExcel->getActiveSheet()->setCellValue($add_coluna.$contar_linhas, db_moeda3($valor_parcela));*/
                    }
                    $contar_linhas++;
                }
            }   
   
                }

            } 
                $sharedStyle1 = new PHPExcel_Style();
                $sharedStyle1->applyFromArray(
                array('fill' 	=> array(
                						'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                						'color'		=> array('argb' => '70ad11')
                					)
                 ));
                
                //$add_coluna = $add_coluna - 1;
                $total_colunas = 'A1:'.$add_coluna.'1';
                $objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, $total_colunas);
                $objPHPExcel->getActiveSheet()->getStyle($total_colunas)->getFont()->getColor()->setARGB('ffffff');
                $col = 'A';
                while(true){
                    $tempCol = $col++;
                    $objPHPExcel->getActiveSheet()->getColumnDimension($tempCol)->setAutoSize(true);
                    if($tempCol == $objPHPExcel->getActiveSheet()->getHighestDataColumn()){
                        break;
                    }
                }
                
                $objPHPExcel->getActiveSheet()->setTitle('teste_procedimentos_expotados');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="teste_procedimentos_expotados.xlsx"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                 
        
        
        }
    }
}


?>
