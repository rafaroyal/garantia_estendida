<script>
jQuery(document).ready(function() {
    jQuery('#add_nascimento').focus();
});
</script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$add_paciente    = (empty($_POST['add_paciente'])) ? "" : verifica($_POST['add_paciente']); 
$add_nascimento  = (empty($_POST['add_nascimento'])) ? "" : verifica($_POST['add_nascimento']); 
$add_sexo        = (empty($_POST['add_sexo'])) ? "" : verifica($_POST['add_sexo']); 
$add_cidade      = (empty($_POST['add_cidade'])) ? "" : verifica($_POST['add_cidade']); 

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);


$nome_local = strtr(strtoupper($add_paciente),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
$add_nascimento = converte_data($add_nascimento);
//adiciona registro
$sql    = "INSERT INTO gui_pacientes (id_parceiro, id_usuario, id_convenio, nome, data_nascimento, sexo, cidade, data_cadastro, dados_completo)
VALUES ('$usr_parceiro', '$usr_id', '5', '$nome_local', '$add_nascimento', '$add_sexo', '$add_cidade', '".agora()."', 'N')";

$query  = mysql_query($sql);

if ($query)
{
    $id_novo_paciente = mysql_insert_id();
    echo $id_novo_paciente;
}
                   
?>
