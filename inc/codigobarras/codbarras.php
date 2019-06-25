<?php

//Feito por Aziz Vicentini - azizvc@yahoo.com.br - http://portalfacil.zgames.com.br
//Impressão de codigo de barras Intercalado 2 de 5 (para boletos bancarios)
//em conformidade com a regulamentação da FEBRABAN 
//exemplo de uso: <img src="codbarras.php?cod=1234&altura=50&espmin=1">
//cod = código numerico para representação das barras (o numero de digitos deve ser par)
//altura = altura das parras em pixels
//espmin = espessura minima da barra (deve ser maior que 0)

header("Content-type: image/jpeg");
pintarbarras(codificar("$cod"), $altura, $espmin);
exit();


function codificar($codigo) {
  $cbinicio = "NNNN";
  $cbfinal = "WNN";
  $cbnumeros = array("NNWWN", "WNNNW", "NWNNW", "WWNNN", "NNWNW", "WNWNN", "NWWNN", "NNNWW", "WNNWN", "NWNWN");

  if (is_numeric($codigo)&(!(strlen($codigo)&1))) {

    for($i = 0; $i < strlen($codigo); $i = $i+2) {	  
	    
	  $cbvar1 = $cbnumeros[$codigo[$i]];
	  $cbvar2 = $cbnumeros[$codigo[$i+1]]; 
	  
      for ($j = 0; $j <= 4; $j++) {
	    $cbresult .= $cbvar1[$j].$cbvar2[$j];
      }
      
    }
    return $cbinicio.$cbresult.$cbfinal;
  } else return '';
  
}


function pintarbarras($mapaI25, $altura, $espmin) {
  if (!extension_loaded('gd')) { dl('php_gd2.dll'); }
  
  $espmin--;
  if($espmin < 0){ $espmin = 0; }	
  if($altura < 5){ $altura = 5; }	
  
  $largura = (strlen($mapaI25)/5*((($espmin+1)*3)+(($espmin+3)*2)))+20;
  
  $im = imagecreate($largura, $altura);
  imagecolorallocate($im, 255, 255, 255);
  
  $spH = 10;
  for($k = 0; $k < strlen($mapaI25); $k++) {
  	
    if (!($k&1)) { $corbarra = ImageColorAllocate($im,0,0,0); }
    else { $corbarra = ImageColorAllocate($im,255,255,255); }
  	
    if ($mapaI25[$k] == 'N'){
  	  ImageFilledRectangle($im, $spH, $altura-3, $spH+$espmin, 2, $corbarra);
  	  $spH = $spH+$espmin+1;	 
    } else {
  	  ImageFilledRectangle($im, $spH, $altura-3, $spH+$espmin+2, 2, $corbarra);
  	  $spH = $spH+$espmin+3;	 
    }
  	
  }
  
  imagejpeg($im);
  imagedestroy($im);
  
}

?>