<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$add_paciente               = (empty($_POST['add_profissional'])) ? "" : verifica($_POST['add_profissional']); 
$add_conselho               = (empty($_POST['add_conselho'])) ? "" : verifica($_POST['add_conselho']); 
$add_registro_profissional  = (empty($_POST['add_registro_profissional'])) ? "" : verifica($_POST['add_registro_profissional']); 
$add_tratamento_profissional= (empty($_POST['add_tratamento_profissional'])) ? "" : verifica($_POST['add_tratamento_profissional']); 
$add_convenio               = (empty($_POST['add_convenio'])) ? "" : verifica($_POST['add_convenio']); 
$add_local_atendimento      = (empty($_POST['add_local_atendimento'])) ? "" : verifica($_POST['add_local_atendimento']); 

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);


$nome_local = strtr(strtoupper($add_paciente),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
$add_nascimento = converte_data($add_nascimento);
//adiciona registro
$sql    = "INSERT INTO gui_profissionais (nome, ids_convenios, conveniado, tratamento, conselho, registro, data_cadastro, ativo)
VALUES ('$nome_local', '$add_convenio', 'N', '$add_tratamento_profissional', '$add_conselho', '$add_registro_profissional', '".agora()."', 'S')";

$query  = mysql_query($sql);

if ($query)
{
    $id_novo_paciente = mysql_insert_id();
    $sql    = "INSERT INTO gui_local_atendimento_profissional (id_profissional, id_local_atendimento)
    VALUES ('$id_novo_paciente', '$add_local_atendimento')";
    
    $query  = mysql_query($sql);

    echo $id_novo_paciente.'%-%'.$add_registro_profissional;
}
                   
?>
