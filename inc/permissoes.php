<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$verifica_lista_permissoes = '';
$id_usuario = base64_decode($_COOKIE["usr_id"]);

if ($id_usuario < 1)
{
    header("Location: login.php");
	exit;
}else{
    $sql        = "SELECT lista_permissoes FROM usuarios
               WHERE id_usuario = $id_usuario";
    $query      = mysql_query($sql, $banco_painel);
                
    if (mysql_num_rows($query)>0)
    {
        $verifica_lista_permissoes = mysql_result($query, 0,0);
        $verifica_lista_permissoes_array_inc = explode("|", $verifica_lista_permissoes);
    }
}

?>