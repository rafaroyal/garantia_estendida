<?php
/* inicia a sessão */
require_once('inc/conexao.php');
require_once('inc/functions.php');
//session_name(md5('l7tec'.$_SERVER['REMOTE_ADDR'].'l7tec'.$_SERVER['HTTP_USER_AGENT'].'l7tec'));
/*$expireAfter = time() + 30 * 86400;
session_cache_expire($expireAfter);
session_start();*/
$server_self = $_SERVER['PHP_SELF'];
$server_self_array = explode("/",$server_self);
$pasta_verifica = $server_self_array[1];
$verifica_sessao              = $_COOKIE["verifica_sessao"];
$usr_time                     = $_COOKIE["usr_time"];
$usr_id                       = base64_decode($_COOKIE["usr_id"]);
$verifica                     = (isset($_COOKIE['verifica'])) ? base64_decode($_COOKIE['verifica']) : '';

//$token_user = md5($pasta_verifica.$_SERVER['REMOTE_ADDR'].$pasta_verifica.$_SERVER['HTTP_USER_AGENT'].$pasta_verifica);
$token_user = md5($pasta_verifica.$usr_time.$pasta_verifica.$usr_id.$pasta_verifica);
if($verifica_sessao != $token_user){
    //User has been inactive for too long.
    //Kill their session.
    
    //error_log("verifica_sessao: ".$verifica_sessao." | token_user: ".$token_user." | original: ".$pasta_verifica.$usr_time.$pasta_verifica.$usr_id.$pasta_verifica);
    $verifica_sessao = '';
    $expire = 0;
    log_acesso_sair($usr_id);
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


    //session_unset();
    //session_destroy();
    
    header("Location: login.php");
    exit;
}else{
    $expire = 0;
    setcookie("verifica_sessao",    '',    $expire, "/");
    setcookie("usr_time",    '',    $expire, "/");
    setcookie("usr_id",    '',    $expire, "/");

    setcookie("usr_nome",    '',    $expire, "/");
    setcookie("usr_nivel",    '',    $expire, "/");
    setcookie("usr_parceiro",    '',    $expire, "/");
    setcookie("usr_filial",    '',    $expire, "/");
    setcookie("nivel_status",    '',    $expire, "/");
    setcookie("pasta",    '',    $expire, "/");

    setcookie("cpf",    '',    $expire, "/");
    setcookie("id",    '',    $expire, "/");
    setcookie("id_banco",    '',    $expire, "/");
    setcookie("id_ordem_pedido",    '',    $expire, "/");
    setcookie("id_serv_get",    '',    $expire, "/");
    setcookie("item",    '',    $expire, "/");
    setcookie("slug",    '',    $expire, "/");
    setcookie("tipo",    '',    $expire, "/");
}



//variable has been set.
if($verifica == ''){
    
    //Expire the session if user is inactive for 30
    //minutes or more.
    $expireAfter        = time() + 1800;
    $expireAfter_ver    = time();
    //Check to see if our "last action" session

    //Figure out how many seconds have passed
    //since the user was last active.
    //$secondsInactive = $expireAfter_ver - $usr_time;
    
    //Convert our minutes into seconds.
    //$expireAfterSeconds = $expireAfter * 60;
    
    //Check to see if they have been inactive for too long.
    if($$expireAfter_ver >= $usr_time){
        //User has been inactive for too long.
        //Kill their session.
        $verifica_sessao = '';
        $expire = 0;
        log_acesso_sair($usr_id);
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
    
    
        //session_unset();
        //session_destroy();
        
        header("Location: login.php");
        exit;
    }else{
        //Assign the current timestamp as the user's
        //latest activity

        $verifica_sessao = md5($pasta_verifica.$expireAfter.$pasta_verifica.$usr_id.$pasta_verifica);

        $expire = $expireAfter;
        setcookie("verifica_sessao",    $verifica_sessao,    $expire, "/".$pasta_verifica);

        setcookie("usr_time",    $expireAfter,    $expireAfter, "/".$pasta_verifica);
    }
    
}


/*//permissão para acessar a página
if (!isset($_SESSION["usr_id"])) 
{
    
}
else
{
    $id_usuario = $_SESSION['usr_id'];
}

//bugfix sessao
if ($_SESSION["usr_id"] < 1)
{
    Header("Location: login.php");
	exit;
}*/
?>
