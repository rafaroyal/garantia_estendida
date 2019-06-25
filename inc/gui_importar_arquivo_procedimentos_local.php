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

    $valida_dados = false;
    $whele_cod_barras = '';
    $array_clientes = false;
    
    $nome_cliente_db_array    = array();
    $id_ordem_pedido_array    = array();
            

    $horario = date('His');
    
    $nome_arquivo_sem_extencao = pathinfo($_FILES['arquivo_retorno']['name'], PATHINFO_FILENAME);
    $extensao_arquivo = $extensao = pathinfo($_FILES['arquivo_retorno']['name'], PATHINFO_EXTENSION);  
    $id_local_atendimento_get    = (empty($_POST['id_local_atendimento'])) ? "" : verifica($_POST['id_local_atendimento']);

    $uploaddir      = '../arquivos/importar_procedimentos/';
    $uploadfile     = $uploaddir . $nome_arquivo_sem_extencao."_".$horario.".".$extensao_arquivo;
    $nome_arquivo   = $nome_arquivo_sem_extencao."_".$horario.".".$extensao_arquivo;


    if (move_uploaded_file($_FILES['arquivo_retorno']['tmp_name'], $uploaddir . $nome_arquivo))
    {
        //upload com sucesso
        //verifica o número de títulos
        //$arquivo = file($uploadfile);
         echo " <div class=\"portlet-body\">
                <p> <a href=\"javascript:window.location.reload(true)\">Clique aqui</a> e atualize a página para visualizar todos os procedimentos.</p>
                <div class=\"table-scrollable\">
            <table class=\"table table-hover\">
                        <thead>
                            <tr>
                                <th> COD </th>
                                <th> PROCEDIMENTO </th>
                                <th> NOVO </th>
                            </tr>
                        </thead>
                        <tbody>";   
        /** Include path **/
        set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel/Classes/');
        
        /** PHPExcel_IOFactory */
        include 'PHPExcel/IOFactory.php';
        
        //$inputFileType = 'CSV';
        $inputFileName = './sampleData/lancamentos.xls';
        
        $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
        
        
        $highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
        $highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataRow();
        echo $highestRow;
        //echo '<table border="1">';
        
        $sql_a      = "INSERT INTO arquivos_importar_procedimentos (nome_arquivo, data_cadastro, processado, registro_total)
                                                VALUES ('$nome_arquivo', '".agora()."', 'N', 0)";
        $query_a    = mysql_query($sql_a, $banco_painel);
        $id_arquivo = mysql_insert_id($banco_painel);
        $contar_linhas = 0;
        $contar_processados = 0;
        $contar_ja_processados = 0;
        $contar_erros = 0;
        $novos = 0;
        for($linha = 2;$linha <= $highestRow;$linha++) {
            $cell_cod           = "A".$linha;
            $cell_procedimento  = "B".$linha;
            $cell_id_convenio   = "C".$linha;
            $cell_preco_custo   = "D".$linha;
            $cell_preco_venda   = "E".$linha;
            
            $cell_cod_ativo             = $objPHPExcel->getActiveSheet()->getCell($cell_cod)->getValue();
            $cell_cod_ativo             = limpar_texto($cell_cod_ativo);
            $cell_procedimento_ativo    = $objPHPExcel->getActiveSheet()->getCell($cell_procedimento)->getValue();
            $cell_id_convenio_ativo     = $objPHPExcel->getActiveSheet()->getCell($cell_id_convenio)->getValue();
            $cell_preco_custo_ativo     = $objPHPExcel->getActiveSheet()->getCell($cell_preco_custo)->getValue();
            $cell_preco_venda_ativo     = $objPHPExcel->getActiveSheet()->getCell($cell_preco_venda)->getValue();   
            
            if(!empty($cell_procedimento_ativo) AND !empty($cell_id_convenio_ativo))
            {
                if(!empty($cell_preco_custo_ativo)){
                    $cell_preco_custo_ativo = moeda_db4($cell_preco_custo_ativo);
                }
                
                if(!empty($cell_preco_venda_ativo)){
                    $cell_preco_venda_ativo = moeda_db4($cell_preco_venda_ativo);
                }
                
                
                if(strlen($cell_cod_ativo) < 7){
                    $cell_cod_ativo = gerar_codigos(10);
                }
                
                $sql        = "SELECT id_procedimento FROM gui_procedimentos
                               WHERE codigo = '$cell_cod_ativo'";
    
                $query      = mysql_query($sql, $banco_painel);
                                
                if (mysql_num_rows($query)>0)
                {
                    $dados = mysql_fetch_array($query);
                    extract($dados); 
                    
                    $sql_proced        = "SELECT id_local_procedimento FROM gui_local_atendimento_procedimentos
                                        WHERE id_procedimento = '$id_procedimento' AND id_local_atendimento = '$id_local_atendimento_get' AND id_convenio = '$cell_id_convenio_ativo'";
    
                    $query_proced      = mysql_query($sql_proced, $banco_painel);
                                    
                    if (mysql_num_rows($query_proced)>0)
                    {
                        $sql_u_a      = "UPDATE gui_local_atendimento_procedimentos SET preco_custo = '$cell_preco_custo_ativo', preco_venda = '$cell_preco_venda_ativo'
            WHERE id_procedimento = '$id_procedimento' AND id_local_atendimento = '$id_local_atendimento_get' AND id_convenio = '$cell_id_convenio_ativo'";
            $query_u_a    = mysql_query($sql_u_a, $banco_painel);        
                    }else{
                        $sql_a      = "INSERT INTO gui_local_atendimento_procedimentos (id_procedimento, id_local_atendimento, id_convenio, preco_custo, preco_venda)
                                                VALUES ('$id_procedimento', '$id_local_atendimento_get', '$cell_id_convenio_ativo', '$cell_preco_custo_ativo', '$cell_preco_venda_ativo')";
                        $query_a    = mysql_query($sql_a, $banco_painel);
                    }
                    
                    $contar_processados++;
                    
                    $contar_linhas++;
                     echo '<tr>
                        <td> '.$cell_cod_ativo.' </td>
                        <td> '.$cell_procedimento_ativo.' </td>
                        <td> NÃO </td>
                    </tr>';
            
                }else{
                    
                    $sql_a      = "INSERT INTO gui_procedimentos (id_grupo_procedimento, codigo, nome)
                                                VALUES ('2', '$cell_cod_ativo', '$cell_procedimento_ativo')";
                    $query_a    = mysql_query($sql_a, $banco_painel);
                    
                    $id_procedimento = mysql_insert_id($banco_painel);
                    
                    $sql_a      = "INSERT INTO gui_local_atendimento_procedimentos (id_procedimento, id_local_atendimento, id_convenio, preco_custo, preco_venda)
                                                VALUES ('$id_procedimento', '$id_local_atendimento_get', '$cell_id_convenio_ativo', '$cell_preco_custo_ativo', '$cell_preco_venda_ativo')";
                    $query_a    = mysql_query($sql_a, $banco_painel);
                    
                    
                    echo '<tr>
                        <td> '.$cell_cod_ativo.' </td>
                        <td> '.$cell_procedimento_ativo.' </td>
                        <td> SIM </td>
                    </tr>';
                    $novos++;
                }
            
            }else{
                echo "erro procedimento ativo ou convenio ativo!";
            }
        }
        echo '<tr>
            <td> - </td>
            <td> - </td>
            <td> - </td>
        </tr>';
       
         
        
        echo '<tr >
            <td colspan="3"> Processados:'.$contar_processados.' / novos:'.$novos.'</td>
        </tr>';
        echo "</tbody>
                </table>
            </div>";
        echo "</div>";
        
        if($contar_processados > 0){
            
            $sql_u_a      = "UPDATE arquivos_importar_procedimentos SET processado = 'S', registro_total = $contar_linhas, registro_processado = $contar_processados, novos = $novos
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
      

    


?>
