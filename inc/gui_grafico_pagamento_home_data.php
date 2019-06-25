<?php

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

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
        
$sql_user_pedido        = "SELECT pag.*, COUNT(*)'contar_linhas' FROM gui_pagamentos_guias pag
                        JOIN gui_guias gui ON pag.id_guia = gui.id_guia
                        JOIN gui_pacientes pac ON gui.id_paciente = pac.id_paciente
                        WHERE gui.del = 'N' AND pac.ativo = 'S' $where_id_parceiro
                        GROUP BY pag.local_pagamento";
$query_user_pedido      = mysql_query($sql_user_pedido);

// Print out rows
$prefix = '';
echo "[\n";
while ( $row = mysql_fetch_assoc( $query_user_pedido ) ) {
  echo $prefix . " {\n";
  echo '  "local_pagamento": "' . $row['local_pagamento'] . '",' . "\n";
  echo '  "status": "' . $row['status'] . '",' . "\n";
  echo '  "valor_total": ' . $row['contar_linhas'] . '' . "\n";
  echo " }";
  $prefix = ",\n";
}
echo "\n]";

?>