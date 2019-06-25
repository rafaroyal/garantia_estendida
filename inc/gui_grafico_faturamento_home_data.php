<?php

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$filtro = (empty($_GET['filtro'])) ? "" : verifica($_GET['filtro']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);

$where_id_parceiro = '';
if($nivel_usuario != 'A'){
    $where_id_parceiro = "AND gui.id_parceiro = $usr_parceiro";
}else{
    // nivel adm porem menos permições
    if($nivel_status == '1'){
        $where_id_parceiro = "AND gui.id_parceiro = $usr_parceiro";
    }
}

if($filtro == 'semanal'){
    $primeiro_dia_semana =  date(
      'Y-m-d', 
      strtotime(
        sprintf("-%s days",
          date('w')
        )
      )
    );
    
    $week_day = date("w");
    $weekend = 6; // sabado
    $diff = $weekend - $week_day;
    $weekend_day['saturday'] = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") + $diff), date("Y") ));
    $ultimo_dia_semana = converte_data($weekend_day['saturday']);
    
    
    //$input_data_inicio = converte_data($primeiro_dia_semana); 
    $input_data_inicio = str_replace("/", "-", $primeiro_dia_semana);
    $proxima_data = date('d/m/Y', strtotime('+1 day', strtotime($input_data_inicio)));   
    $proxima_data = str_replace("/", "-", $proxima_data);
    $proxima_data = converte_data($proxima_data);
    $prefix = '';
            echo "[\n";
    while(strtotime($proxima_data) <= strtotime($ultimo_dia_semana))
    {
        
        $sql_guias        = "SELECT gu.id_guia, gu.id_convenio, gu.id_local_atendimento, gu.data_emissao FROM gui_guias gu
        JOIN gui_pacientes gui_pac ON gu.id_paciente = gui_pac.id_paciente
        WHERE gu.data_emissao LIKE '$proxima_data%' AND gu.status = 'EMITIDO'";
        $query_guias      = mysql_query($sql_guias);
        $soma_exame = 0;
        $soma_consulta = 0;
        
        if(mysql_num_rows($query_guias) > 0){
            
            
            while($dados_guias = mysql_fetch_array($query_guias))
            {
                extract($dados_guias);
                
                $sql_guias_procedimentos       = "SELECT id_procedimento, valor_cobrado FROM gui_procedimentos_guia
                WHERE id_guia = $id_guia";
                $query_procedimentos           = mysql_query($sql_guias_procedimentos);
                
                if(mysql_num_rows($query_procedimentos) > 0){
                    while($dados_guias_procedimentos = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados_guias_procedimentos);
                        
                        $sql_guias_local       = "SELECT preco_custo FROM gui_local_atendimento_procedimentos
WHERE id_procedimento = $id_procedimento AND id_local_atendimento = $id_local_atendimento AND id_convenio = $id_convenio";
                        $query_local           = mysql_query($sql_guias_local);
                        
                        if(mysql_num_rows($query_local) > 0){
                            while($dados_local = mysql_fetch_array($query_local))
                            {
                                extract($dados_local);
                                
                                
                                $sql_proced       = "SELECT gpro.nome FROM gui_procedimentos pro
                        JOIN gui_grupo_procedimentos gpro ON pro.id_grupo_procedimento = gpro.id_grupo_procedimento
                        WHERE id_procedimento = $id_procedimento";
                        $query_proced           = mysql_query($sql_proced);
                        $nome_grupo_proced = '';
                        if(mysql_num_rows($query_proced) > 0){
                            $nome = mysql_result($query_proced, 0, 'nome');
                        }
                                
                                if($nome == 'EXAME'){
                                    $soma_exame = $soma_exame + ($valor_cobrado - $preco_custo);
                                }
                                
                                if($nome == 'CONSULTA'){
                                    $soma_consulta = $soma_consulta + ($valor_cobrado - $preco_custo);
                                }  
                                
                                $soma_exame = moeda_db($soma_exame);
                                $soma_consulta = moeda_db($soma_consulta);
  
                            }
                        }
  
                    }
                }

            }
             
        }
        
        
        
        $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                                $diasemana_numero = date('w', strtotime($proxima_data));
                                $array_dia_semana = $diasemana[$diasemana_numero];
                                $despesa = 0;
        //while ( $row = mysql_fetch_assoc( $query_user_pedido ) ) {
                                  echo $prefix . " {\n";
                                  echo '  "periodo": "' . $array_dia_semana . '",' . "\n";
                                  echo '  "consulta": "' . $soma_consulta . '",' . "\n";
                                  echo '  "exame": "' . $soma_exame . '",' . "\n";
                                  echo '  "despesa": "' . $despesa . '"' . "\n";
                                  echo " }";
                                  $prefix = ",\n";
                                //}
        
        
        
        
        $proxima_data = date('d/m/Y', strtotime('+1 day', strtotime($proxima_data)));
        $proxima_data = str_replace("/", "-", $proxima_data);
        $proxima_data = converte_data($proxima_data);
    }
    echo "\n]";
    
        
}elseif($filtro == 'mensal'){
    $data_incio = mktime(0, 0, 0, date('m') , 1 , date('Y'));
    $data_fim = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
    $data_incio = date('Y-m-d',$data_incio);
    $data_fim =  date('Y-m-d',$data_fim);

    $proxima_data = $data_incio;
    
    $prefix = '';
            echo "[\n";
    while(strtotime($proxima_data) <= strtotime($data_fim))
    {
        $array_proxima_data = explode("-", $proxima_data);
        $array_dia_proxima_data = $array_proxima_data[2];
        $sql_guias        = "SELECT gu.id_guia, gu.id_convenio, gu.id_local_atendimento, gu.data_emissao FROM gui_guias gu
        JOIN gui_pacientes gui_pac ON gu.id_paciente = gui_pac.id_paciente
        WHERE gu.data_emissao LIKE '$proxima_data%' AND gu.status = 'EMITIDO'";
        $query_guias      = mysql_query($sql_guias);
        $soma_exame = 0;
        $soma_consulta = 0;
        
        if(mysql_num_rows($query_guias) > 0){
            
            
            while($dados_guias = mysql_fetch_array($query_guias))
            {
                extract($dados_guias);
                
                $sql_guias_procedimentos       = "SELECT id_procedimento, valor_cobrado FROM gui_procedimentos_guia
                WHERE id_guia = $id_guia";
                $query_procedimentos           = mysql_query($sql_guias_procedimentos);
                
                if(mysql_num_rows($query_procedimentos) > 0){
                    while($dados_guias_procedimentos = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados_guias_procedimentos);
                        
                        $sql_guias_local       = "SELECT preco_custo FROM gui_local_atendimento_procedimentos
WHERE id_procedimento = $id_procedimento AND id_local_atendimento = $id_local_atendimento AND id_convenio = $id_convenio";
                        $query_local           = mysql_query($sql_guias_local);
                        
                        if(mysql_num_rows($query_local) > 0){
                           
                            while($dados_local = mysql_fetch_array($query_local))
                            {
                                extract($dados_local);
                                
                                
                                $sql_proced       = "SELECT gpro.nome FROM gui_procedimentos pro
                        JOIN gui_grupo_procedimentos gpro ON pro.id_grupo_procedimento = gpro.id_grupo_procedimento
                        WHERE id_procedimento = $id_procedimento";
                        $query_proced           = mysql_query($sql_proced);
                        $nome_grupo_proced = '';
                        if(mysql_num_rows($query_proced) > 0){
                            $nome = mysql_result($query_proced, 0, 'nome');
                        }
                                
                                if($nome == 'EXAME'){
                                    $soma_exame = $soma_exame + ($valor_cobrado - $preco_custo);
                                }
                                
                                if($nome == 'CONSULTA'){
                                    $soma_consulta = $soma_consulta + ($valor_cobrado - $preco_custo);
                                }  
                                
                                $soma_exame = moeda_db($soma_exame);
                                $soma_consulta = moeda_db($soma_consulta);
  
                            }
                        }
  
                    }
                }

            }
             
        }
        
        
        
                                /*$diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                                $diasemana_numero = date('w', strtotime($proxima_data));
                                $array_dia_semana = $diasemana[$diasemana_numero];*/
                                $despesa = 0;
        //while ( $row = mysql_fetch_assoc( $query_user_pedido ) ) {
                                  echo $prefix . " {\n";
                                  echo '  "periodo": "' . $array_dia_proxima_data . '",' . "\n";
                                  echo '  "consulta": "' . $soma_consulta . '",' . "\n";
                                  echo '  "exame": "' . $soma_exame . '",' . "\n";
                                  echo '  "despesa": "' . $despesa . '"' . "\n";
                                  echo " }";
                                  $prefix = ",\n";
                                //}
        
        
        
        
        $proxima_data = date('d/m/Y', strtotime('+1 day', strtotime($proxima_data)));
        $proxima_data = str_replace("/", "-", $proxima_data);
        $proxima_data = converte_data($proxima_data);
    }
    echo "\n]";
    
      
}


?>