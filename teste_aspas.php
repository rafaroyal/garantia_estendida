<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */
 
 function remove_acento($palavra)
{
		$palavra = preg_replace('/(\'|")/', '', $palavra);
        //$palavra = ereg_replace("[^a-zA-Z0-9]", "", strtr($palavra, "�������������������������� ", "aaaaeeiooouucAAAAEEIOOOUUC "));
		//preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($palavra));
		$array1 = array( "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�" 
, "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�" );
		$array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C", "" );
		return str_replace( $array1, $array2, $palavra); 
		
		return $palavra;
}

$texto = "RAFAEL NOGU�IRA TEST'1D RUA O'SWALDO NUNES RUA O'SWALDO NUNES, N� 1, Bairro MARIA LUCIA' DAS C, DSRDS 'DF', LONDRINA - PR";


//$texto = preg_replace('/(\'|")/', '', $texto);

echo remove_acento($texto);
?>