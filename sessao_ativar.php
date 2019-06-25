<?php
/* define o limitador de cache para 'private' */

/* inicia a sessão */
session_start();
$_SESSION['last_action'] = time();
echo "OK";


?>
