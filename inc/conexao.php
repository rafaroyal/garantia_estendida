<?php

//conexao com banco local
/*DEFINE('host_DB','localhost');
DEFINE('user_DB','root');
DEFINE('senha_DB','');
DEFINE('nome_DB','trailserv_api');*/

/*$server =   'localhost';
$user   =   'root';
$senha  =   '';
$db     =   'trailserv_api';*/

$server =   '198.12.152.215';
$user   =   'trailservicos_grlpainel';
$senha  =   'ElmclWp$Y6FP';
$db     =   'trailservicos_garantia_estendida';

$link = mysql_connect($server, $user, $senha);
if (!$link) {
    die('Erro de conexão com Banco de Dados ' . mysql_error());
}

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');

$db_selected = mysql_select_db($db, $link);

?>