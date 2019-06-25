<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

$valor = 2590;
$contar_carc = strlen($valor) - 2;
$valor_1 = substr($valor, -2, 2);
$valor_2 = substr($valor, 0, $contar_carc);

$novo_valor = $valor_2.",".$valor_1;
echo $novo_valor;
?>