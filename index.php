<?php
ob_start();
session_start();
//permiss�o para acessar a p�gina
$usr_id = base64_decode($_COOKIE["usr_id"]);
if (!isset($usr_id))
{
    header("Location: login.php");
    exit;
}
else
{
    header("Location: inicio.php");
    exit;
}