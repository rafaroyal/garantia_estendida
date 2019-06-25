<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

function formatar ($tipo = "", $string, $size = 12)
{
    $string = ereg_replace("[^0-9]", "", $string);
    
    switch ($tipo)
    {
        case 'fone':
            if($size === 10){
             $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 4) 
             . '-' . substr($tipo, 6);
         }else
         if($size === 11){
             $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 5) 
             . '-' . substr($tipo, 7);
         }
         break;
    }
    return $string;
}

echo formatar('43 984877846');

?>