<?php
$server_self = $_SERVER['PHP_SELF'];
$server_self_array = explode("/",$server_self);
$pasta_verifica = $server_self_array[1];
$pasta = $server_self_array[1];
/* inicia a sessão */
//session_name(md5('l7tec'.$_SERVER['REMOTE_ADDR'].'l7tec'.$_SERVER['HTTP_USER_AGENT'].'l7tec'));
/*$expireAfter = time() + 30 * 86400;
session_cache_expire($expireAfter);
session_start();*/
require_once('inc/conexao.php');
require_once('inc/functions.php');

$login          = verifica($_POST['login']);
$senha          = md5(verifica($_POST['senha']));
//$verifica = (isset(verifica($_POST['remember']))) ? verifica($_POST['remember']) : '';
$verifica   = (empty($_POST['remember'])) ? "" : verifica($_POST['remember']);

$tipo         = (empty($_POST['tipo'])) ? "" : verifica($_POST['tipo']); 



$sql        = "SELECT * FROM usuarios WHERE login = '$login' AND senha = '$senha' AND del = 'N'";
$query      = mysql_query($sql) or die(mysql_error());

if ($query)
{
    if (mysql_num_rows($query) == 1)
    {

        $usr_id         = mysql_result($query, 0, 'id_usuario');
        $usr_parceiro   = mysql_result($query, 0, 'id_parceiro');
        $usr_filial     = mysql_result($query, 0, 'id_filial');
        $usr_nome       = mysql_result($query, 0, 'nome');
        $usr_nivel      = mysql_result($query, 0, 'nivel');
        $ativo          = mysql_result($query, 0, 'ativo');
        $usr_del        = mysql_result($query, 0, 'del');
        $nivel_status   = mysql_result($query, 0, 'nivel_status');
        
        if ($usr_del == 'S' OR $ativo == 'N')
        {
            if($tipo == 'acesso_rapido'){
                echo 'erro';
                
            }else{
                header("Location: index.php?msg_status=login_erro");
            }
            
        }
        else
        {
            
            if($tipo == 'acesso_rapido'){
                $sql    = "UPDATE usuarios SET ult_acesso = '".agora()."' WHERE id_usuario = ".base64_decode($_COOKIE["usr_id"]);
                $query  = mysql_query($sql);
               
                
            }
            $usr_time                   = time() + 1800;
            if($verifica == 1){
                $usr_time                   = time() + 5 * 30 * 30 * 86400;
            }
            
            /*$_SESSION['usr_time']       = $usr_time;
            $_SESSION['usr_id']         = $usr_id;
            $_SESSION['usr_nome']       = $usr_nome;
            $_SESSION['usr_nivel']      = $usr_nivel;
            $_SESSION['usr_parceiro']   = $usr_parceiro;
            $_SESSION['usr_filial']     = $usr_filial;
            $_SESSION['nivel_status']   = $nivel_status;
            $_SESSION['pasta']          = $pasta;*/

            //$verifica_sessao = md5($pasta_verifica.$_SERVER['REMOTE_ADDR'].$pasta_verifica.$_SERVER['HTTP_USER_AGENT'].$pasta_verifica);

            $verifica_sessao = md5($pasta_verifica.$usr_time.$pasta_verifica.$usr_id.$pasta_verifica);

            $expire = $usr_time;
            setcookie("verifica_sessao",    $verifica_sessao,    $expire, "/".$pasta_verifica);
            setcookie("verifica",    base64_encode($verifica),    $expire, "/".$pasta_verifica);
            setcookie("usr_time",    $usr_time,    $expire, "/".$pasta_verifica);
            setcookie("usr_id",    base64_encode($usr_id),    $expire, "/".$pasta_verifica);

            setcookie("usr_nome",    base64_encode($usr_nome),    $expire, "/".$pasta_verifica);
            setcookie("usr_nivel",    base64_encode($usr_nivel),    $expire, "/".$pasta_verifica);
            setcookie("usr_parceiro",    base64_encode($usr_parceiro),    $expire, "/".$pasta_verifica);
            setcookie("usr_filial",    base64_encode($usr_filial),    $expire, "/".$pasta_verifica);
            setcookie("nivel_status",    base64_encode($nivel_status),    $expire, "/".$pasta_verifica);
            setcookie("pasta",    base64_encode($pasta),    $expire, "/".$pasta_verifica);

            if($tipo == 'acesso_rapido'){
                echo 'ok';
            }else{
                log_acesso($usr_id);
                Header("Location: inicio.php");
            }
            
            
        }
    }
    else
    {
         if($tipo == 'acesso_rapido'){
            echo 'erro';
         }else{
            //login e senha não conferem
            Header("Location: login.php?msg_status=login_erro");
         }
        
    }
}
else
{
    if($tipo == 'acesso_rapido'){
        echo 'erro';
    }else{
        //erro de conexao/query
        Header("Location: login.php?msg_status=login_erro");
    }
    
}
?>