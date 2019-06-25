
<?php

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$id_local_atendimento       = (empty($_POST['id_local_atendimento'])) ? "" : verifica($_POST['id_local_atendimento']);  
$data1                      = (empty($_POST['data1'])) ? "" : verifica($_POST['data1']); 
$data2                      = (empty($_POST['data2'])) ? "" : verifica($_POST['data2']); 
$id_profissional            = (empty($_POST['id_profissional'])) ? "" : verifica($_POST['id_profissional']); 
$mes_referencia             = (empty($_POST['mes_referencia'])) ? "" : verifica($_POST['mes_referencia']); 
$ano_referencia             = (empty($_POST['ano_referencia'])) ? "" : verifica($_POST['ano_referencia']); 
$soma_preco_custo           = (empty($_POST['soma_preco_custo'])) ? "" : verifica($_POST['soma_preco_custo']); 
$sel_guias_marcadas         = (empty($_POST['sel_guias_marcadas'])) ? "" : verifica($_POST['sel_guias_marcadas']); 
$sel_valor_guias_marcadas   = (empty($_POST['sel_valor_guias_marcadas'])) ? "" : verifica($_POST['sel_valor_guias_marcadas']); 
$todos_clientes_ativos      = (empty($_POST['todos_clientes_ativos'])) ? "" : verifica($_POST['todos_clientes_ativos']); 

$data1_convert = str_replace("-", "/", $data1);
$data2_convert = str_replace("-", "/", $data2);

$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);

$where_id_profissional = '';
if($id_profissional != 'todos' AND $id_profissional != 'undefined'){
    $where_id_profissional = "AND gu.id_profissional = $id_profissional";
}else{
    $id_profissional = 0;
}

    $sql_local_atendimento  = "SELECT nome, cidade, forma_faturamento FROM gui_local_atendimento
                                  WHERE id_local_atendimento = $id_local_atendimento";
    $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
    $nome_local_atendimento = '-';
    $cidade_local_atendimento = '-';
    $forma_faturamento_atendimento = '-';
    if (mysql_num_rows($query_local_atendimento)>0)
    {
        $nome_local_atendimento         = mysql_result($query_local_atendimento, 0, 'nome');
        $cidade_local_atendimento       = mysql_result($query_local_atendimento, 0, 'cidade');
        $forma_faturamento_atendimento  = mysql_result($query_local_atendimento, 0, 'forma_faturamento');
    }
        $nr_colunas = count($sel_guias_marcadas);
        $quantade_total_guias = $nr_colunas;
        $sql3    = "INSERT INTO gui_faturamentos_guias (id_local_atendimento, mes_referencia, ano_referencia, periodo_inicio, periodo_fim, quantidade_total, soma_total, data_cadastro, id_profissional, id_usuario_baixa, guias_pendentes)
                            VALUES ('$id_local_atendimento', '$mes_referencia', '$ano_referencia', '$data1_convert', '$data2_convert', '$quantade_total_guias', '$soma_preco_custo', '".agora()."', '$id_profissional', '$id_usuario_s', '$todos_clientes_ativos')";       
        $query3  = mysql_query($sql3, $banco_painel) or die(mysql_error());
        
        $id_faturamento = mysql_insert_id($banco_painel);
        
        
        for ($i = 0; $i<$nr_colunas; $i++)
        {
            $id_guia_post       = $sel_guias_marcadas[$i];
            $valor_custo_post   = $sel_valor_guias_marcadas[$i];

            $sql_pagamento        = "SELECT id_pagamento FROM gui_pagamentos_guias
                                    WHERE id_guia = $id_guia_post";
            $query_pagamento      = mysql_query($sql_pagamento, $banco_painel);
                    
            if (mysql_num_rows($query_pagamento)>0)
            {  
                $id_pagamento = mysql_result($query_pagamento, 0,'id_pagamento');
                
                $sql2    = "UPDATE gui_pagamentos_guias SET baixa_faturado = 'S', id_faturamento = '$id_faturamento', valor_custo = '$valor_custo_post'
                            WHERE id_pagamento = $id_pagamento";
                $query2  = mysql_query($sql2, $banco_painel) OR DIE (mysql_error());
                
                 
            }
        
        
        /*$sql            = "SELECT gu.id_guia FROM gui_guias gu 
                        JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente 
                        JOIN gui_pagamentos_guias gupg ON gu.id_pagamento = gupg.id_pagamento 
                        WHERE gu.del = 'N' AND (gu.data_agendamento BETWEEN '$data1_convert' AND '$data2_convert') AND gu.status = 'EMITIDO' AND gupg.baixa_faturado = 'N' AND gu.id_local_atendimento = '$id_local_atendimento' $where_id_profissional
                        ORDER BY gu.data_agendamento DESC";
        
        $query_count    = mysql_query($sql, $banco_painel);
        $quantade_total_guias = '';
           
        if (mysql_num_rows($query_count)>0)
        {
            $quantade_total_guias = mysql_num_rows($query_count);
            $sql3    = "INSERT INTO gui_faturamentos_guias (id_local_atendimento, mes_referencia, ano_referencia, periodo_inicio, periodo_fim, quantidade_total, soma_total, data_cadastro, id_profissional, id_usuario_baixa)
                                VALUES ('$id_local_atendimento', '$mes_referencia', '$ano_referencia', '$data1_convert', '$data2_convert', '$quantade_total_guias', '$soma_preco_custo', '".agora()."', '$id_profissional', '$id_usuario_s')";       
            $query3  = mysql_query($sql3, $banco_painel) or die(mysql_error());
            
            $id_faturamento = mysql_insert_id($banco_painel);
            
            while ($dados = mysql_fetch_array($query_count))
            {
                extract($dados);

                $sql_pagamento        = "SELECT id_pagamento FROM gui_pagamentos_guias
                    WHERE id_guia = $id_guia";
                        $query_pagamento      = mysql_query($sql_pagamento, $banco_painel);
                                
                        if (mysql_num_rows($query_pagamento)>0)
                        {  
                            $id_pagamento = mysql_result($query_pagamento, 0,'id_pagamento');
                            $soma_preco_custo = 0;
                            
                            $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimento', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto, gui_pag_gui.baixa_recebimento FROM gui_procedimentos_guia gui_pro_gui
                    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
                    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
                    JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
                    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";
                            $query_procedimentos      = mysql_query($sql_procedimentos, $banco_painel);
                                    
                            if (mysql_num_rows($query_procedimentos)>0)
                            {  
    
                                while ($dados_valor = mysql_fetch_array($query_procedimentos))
                                {
                                    extract($dados_valor); 
                                    //echo $preco_custo;
                                    $preco_custo_calc = moeda_db($preco_custo);
                                    $valor_cobrado_exibe = $valor_cobrado;
                                    
                                    if($forma_faturamento_atendimento == 'PRECO_CUSTO'){
                                        $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                                        //echo " (".$soma_preco_custo.") ";
                                    }elseif($forma_faturamento_atendimento == 'PRECO_FINAL'){
                                        $soma_preco_custo = $soma_preco_custo + $valor_cobrado_exibe;
                                    }elseif($forma_faturamento_atendimento == 'REPASSE_FINAL_NENOS_CUSTO'){
                                        $soma_preco_custo = $soma_preco_custo + ($preco_custo_calc - $valor_cobrado_exibe);
                                    }
    
                                }
                                
                                //$soma_preco_custo = db_moeda4($soma_preco_custo);
                                
                                if($soma_preco_custo == 0.00){
                                    $soma_preco_custo = 0;
                                }
      
                            }

                            
                            $sql2    = "UPDATE gui_pagamentos_guias SET baixa_faturado = 'S', id_faturamento = '$id_faturamento', valor_custo = '$soma_preco_custo'
                            WHERE id_pagamento = $id_pagamento";
                            $query2  = mysql_query($sql2, $banco_painel) OR DIE (mysql_error());
                            
                        }

            
            }
            
            echo $id_faturamento;
        }*/
        
        }
        echo $id_faturamento;
    
?>




