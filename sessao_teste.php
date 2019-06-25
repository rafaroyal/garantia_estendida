<?php
/* inicia a sessão */
session_start('trail_painel');

//Expire the session if user is inactive for 30
//minutes or more.
$expireAfter = 1;
 
//Check to see if our "last action" session
//variable has been set.
if(isset($_SESSION['last_action'])){
    
    //Figure out how many seconds have passed
    //since the user was last active.
    $secondsInactive = time() - $_SESSION['last_action'];
    
    //Convert our minutes into seconds.
    $expireAfterSeconds = $expireAfter * 60;
    
    //Check to see if they have been inactive for too long.
    if($secondsInactive >= $expireAfterSeconds){
        //User has been inactive for too long.
        //Kill their session.
        session_unset();
        session_destroy();
        header("Location: login.php");
	    exit;
    }
    
}
 
/*//Assign the current timestamp as the user's
//latest activity
$_SESSION['last_action'] = time();

//permissão para acessar a página
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
