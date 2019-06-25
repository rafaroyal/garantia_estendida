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

<div class="form-group form-md-floating-label">                                                             
<?php 
// AND id_usuario <> $id_usuario_sessao
$sql_user_pedido        = "SELECT id_usuario, nome FROM usuarios
WHERE id_parceiro = $id_parceiro AND ativo = 'S' AND del = 'N'";
$query_user_pedido      = mysql_query($sql_user_pedido);
                
if(mysql_num_rows($query_user_pedido)>0)
{
    echo "<label class=\"control-label \">Selecione o vendedor</label>
    <div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_usuario_filtro\" name=\"select_usuario_filtro\" ><option value=\"\" selected=\"\">TODOS</option>";
    
    while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
    {
        extract($dados_user_pedido);

        echo "<option value=\"$id_usuario\">$nome</option>";
    }
    
    echo "</select></div>";
}

 ?>
 
</div>


<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>