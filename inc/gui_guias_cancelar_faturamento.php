
<?php

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$id_faturamento     = (empty($_POST['id_faturamento'])) ? "" : verifica($_POST['id_faturamento']);
$observacoes        = (empty($_POST['observacoes'])) ? "" : verifica($_POST['observacoes']); 

$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);

   
        $sql            = "SELECT id_pagamento FROM gui_pagamentos_guias
                        WHERE id_faturamento = '$id_faturamento'";
        $query_count    = mysql_query($sql, $banco_painel);
        
        if (mysql_num_rows($query_count)>0)
        {
            while ($dados = mysql_fetch_array($query_count))
            {
                extract($dados);
          
                $sql2    = "UPDATE gui_pagamentos_guias SET baixa_faturado = 'N', id_faturamento = ''
                WHERE id_pagamento = $id_pagamento";
                $query2  = mysql_query($sql2, $banco_painel) OR DIE (mysql_error());
            }
            
            $sql2    = "UPDATE gui_faturamentos_guias SET cancelar = 'S', data_cancelamento = '".agora()."', id_usuario_cancelamento = '$id_usuario_s', obs = '$observacoes'
            WHERE id_faturamento = $id_faturamento";
            $query2  = mysql_query($sql2, $banco_painel) OR DIE (mysql_error());
            
            echo $id_faturamento;
        }
            
                
                
     
    
?>




