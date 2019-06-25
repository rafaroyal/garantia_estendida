<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

require_once('functions.php');
require_once('conexao.php'); 
//$banco_painel = $link;

$id_parceiro         = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);  



?>

<link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />


<?php

if($id_parceiro == 'todos')
{
    $sel_parceiro = '';
}
else
{
    $sel_parceiro = "WHERE pa.id_parceiro = $id_parceiro";


    $sql_parceiros        = "SELECT fi.id_filial, fi.id_filial_integracao, fi.nome, fi.cidade FROM filiais fi
JOIN parceiros pa ON fi.id_parceiro = pa.id_parceiro
$sel_parceiro";
    $query_parceiros      = mysql_query($sql_parceiros);
                    
    if (mysql_num_rows($query_parceiros)>0)
    {
        
        echo "<label class=\"control-label \">Filial</label>
<div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_filial\" name=\"select_filial\" ><option value=\"\" selected=\"\">TODOS</option>";
        
        while($dados_parceiros = mysql_fetch_array($query_parceiros))
        {
            extract($dados_parceiros);
            
            echo "<option value=\"$id_filial\" data-subtext=\"$cidade\">$nome</option>";

        }
        
        echo "</select></div>";
    }
}
?>


<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>