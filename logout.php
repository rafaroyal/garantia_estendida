<?php
/* inicia a sessão */
session_name(md5('l7tec'.$_SERVER['REMOTE_ADDR'].'l7tec'.$_SERVER['HTTP_USER_AGENT'].'l7tec'));
session_start();
//agora verifico se ele possui permissão para acessar a página

require ('./inc/conexao.php');
require ('./inc/functions.php');
$server_self = $_SERVER['PHP_SELF'];
$server_self_array = explode("/",$server_self);
$pasta_verifica = $server_self_array[1];
//registra saída
$sql    = "UPDATE usuarios SET ult_acesso = '".agora()."' WHERE id_usuario = ".base64_decode($_COOKIE["usr_id"]);
$query  = mysql_query($sql);

$usr_id = base64_decode($_COOKIE["usr_id"]);
log_acesso_sair($usr_id);
$verifica_sessao = '';
$expire = 0;
setcookie("verifica_sessao",    '',    $expire, "/".$pasta_verifica);
    setcookie("usr_time",    '',    $expire, "/".$pasta_verifica);
    setcookie("usr_id",    '',    $expire, "/".$pasta_verifica);

    setcookie("usr_nome",    '',    $expire, "/".$pasta_verifica);
    setcookie("usr_nivel",    '',    $expire, "/".$pasta_verifica);
    setcookie("usr_parceiro",    '',    $expire, "/".$pasta_verifica);
    setcookie("usr_filial",    '',    $expire, "/".$pasta_verifica);
    setcookie("nivel_status",    '',    $expire, "/".$pasta_verifica);
    setcookie("pasta",    '',    $expire, "/".$pasta_verifica);

    setcookie("cpf",    '',    $expire, "/".$pasta_verifica);
    setcookie("id",    '',    $expire, "/".$pasta_verifica);
    setcookie("id_banco",    '',    $expire, "/".$pasta_verifica);
    setcookie("id_ordem_pedido",    '',    $expire, "/".$pasta_verifica);
    setcookie("id_serv_get",    '',    $expire, "/".$pasta_verifica);
    setcookie("item",    '',    $expire, "/".$pasta_verifica);
    setcookie("slug",    '',    $expire, "/".$pasta_verifica);
    setcookie("tipo",    '',    $expire, "/".$pasta_verifica);
session_destroy();
session_write_close(); 

mysql_close($link);
ob_flush();
//die();
//exit(0);

//REDIRECIONA PARA A TELA DE LOGIN 
header("Location: index.php"); 

?>


