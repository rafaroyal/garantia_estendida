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
    // nivel adm porem menos permi��es
    if($nivel_status == '1'){
        $where_id_parceiro = "AND gui.id_parceiro = $usr_parceiro";
    }
}


if($filtro == 'semana'){
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
            
    $sql_user_pedido        = "SELECT gui.status, COUNT(*)'contar_linhas' FROM gui_pagamentos_guias pag
                            JOIN gui_guias gui ON pag.id_guia = gui.id_guia
                            JOIN gui_pacientes pac ON gui.id_paciente = pac.id_paciente
                            WHERE gui.del = 'N' AND pac.ativo = 'S' AND gui.data_agendamento BETWEEN '$primeiro_dia_semana' AND '$ultimo_dia_semana' $where_id_parceiro
                            GROUP BY gui.status";
    
}elseif($filtro == 'mes'){
   $mes = date("m");
   $ano = date("Y");
   $data_final = $ano.'-'.$mes;
   $sql_verifica_filtro = "AND gui.data_agendamento LIKE '%$data_final%'";        
   
$sql_user_pedido        = "SELECT gui.status, COUNT(*)'contar_linhas' FROM gui_pagamentos_guias pag
                        JOIN gui_guias gui ON pag.id_guia = gui.id_guia
                        JOIN gui_pacientes pac ON gui.id_paciente = pac.id_paciente
                        WHERE gui.del = 'N' AND pac.ativo = 'S' $sql_verifica_filtro $where_id_parceiro
                        GROUP BY gui.status";
}

$query_user_pedido      = mysql_query($sql_user_pedido);
// Print out rows
$prefix = '';
echo "[\n";
while ( $row = mysql_fetch_assoc( $query_user_pedido ) ) {
  echo $prefix . " {\n";
  echo '  "status": "' . $row['status'] . '",' . "\n";
  echo '  "contar_linhas": ' . $row['contar_linhas'] . '' . "\n";
  echo " }";
  $prefix = ",\n";
}
echo "\n]";

?>