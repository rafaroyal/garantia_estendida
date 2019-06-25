<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
//$banco_painel = $link;

$id_parceiro        = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);  
$nivel_usuario      = base64_decode($_COOKIE["usr_nivel"]);
$id_usuario_sessao  = base64_decode($_COOKIE["usr_id"]);

?>

<link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />


<?php
$sel_todos = '<option value="todos" selected="">TODOS</option>';
$sel_user = '';
if($nivel_usuario == 'U')
{
    $sel_user = "AND fi.id_usuario = $id_usuario_sessao";
    $sel_todos = '';
}

if($id_parceiro == 'todos')
{
    $sel_parceiro = '';
    echo "<input type=\"hidden\" name=\"select_usuario_vendedor\" id=\"select_usuario_vendedor\" value=\"todos\">";
}
else
{
    $sel_parceiro = "AND pa.id_parceiro = $id_parceiro";


    $sql_parceiros        = "SELECT fi.id_usuario, fi.nome'nome_vendedor', pa.nome'nome_parceiro' FROM usuarios fi
                            JOIN parceiros pa ON fi.id_parceiro = pa.id_parceiro
                            WHERE fi.ativo = 'S' AND fi.del = 'N' $sel_parceiro $sel_user";
    $query_parceiros      = mysql_query($sql_parceiros);
                    
    if (mysql_num_rows($query_parceiros)>0)
    {
        
        echo "<label class=\"control-label \">Usuário</label>
<div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_usuario_vendedor\" name=\"select_usuario_vendedor\" >$sel_todos";
        
        while($dados_parceiros = mysql_fetch_array($query_parceiros))
        {
            extract($dados_parceiros);
            
            echo "<option value=\"$id_usuario\" data-subtext=\"$nome_parceiro\">$nome_vendedor</option>";
            
        }
        
        echo "</select></div>";
    }
}
?>


<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>