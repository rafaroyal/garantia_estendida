<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */
 
 /*
 
 $url = "http://209.133.196.250/painel/api.ashx?action=sendsms&lgn=4384877846&pwd=16061987&msg=oil&numbers=4384877846";
  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $a = curl_exec($ch);
  $resultado = json_decode($a);
  //curl_close($ch);
  
  $status = $resultado->status;
  echo $status;
  */
  $msg = 'olá tudo bem! número da sorte: 12345';
  $msg = urlencode($msg);
  $homepage = file_get_contents("http://209.133.196.250/painel/api.ashx?action=sendsms&lgn=4384877846&pwd=16061987&msg=$msg&numbers=4384877846");
  $resultado = json_decode($homepage);
  $status = $resultado->status;
  echo $status;
?>