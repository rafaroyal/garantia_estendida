<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$id_local    = (empty($_GET['id_local'])) ? "" : verifica($_GET['id_local']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);


$sql_contar        = "SELECT * FROM gui_local_atendimento
                    WHERE id_local_atendimento = $id_local";
$query_contar      = mysql_query($sql_contar);
if (mysql_num_rows($query_contar)>0)
{
    $dados = mysql_fetch_array($query_contar);
    extract($dados); 
    
    echo "Conveniado: $conveniado / Tipo: $tipo / Email: $email <br/>";
    echo "CEP: $cep - $endereco, $numero $complemento $bairro, $cidade-$estado <br/>";
    echo "<strong>Observações:</strong> $obs";
}
               
?>

