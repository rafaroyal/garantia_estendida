<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

function simple_encrypt_antigo($senha,$chave)
    {  
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $chave, $senha, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
 
    function simple_decrypt_antigo($senha,$chave)
    {  
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $chave, base64_decode($senha), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    function simple_encrypt_novo($senha,$chave)
    {  
        $secret_key = $chave;
        $secret_iv = $chave;
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        
        $output = base64_encode( openssl_encrypt( $senha, $encrypt_method, $key, 0, $iv ) );

        return $output;

    }
 
    function simple_decrypt_novo($senha,$chave)
    {  
        $secret_key = $chave;
        $secret_iv = $chave;
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        
        $output = openssl_decrypt( base64_decode( $senha ), $encrypt_method, $key, 0, $iv );

        return $output;
    }



   /* function my_simple_crypt( $string, $action = 'e' ) {
        // you may change these values to your own
        $secret_key = 'my_simple_secret_key';
        $secret_iv = 'my_simple_secret_iv';
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
     
        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
     
        return $output;
    }*/

    $senha_banco = '';
    $simple_encrypt_antigo = simple_encrypt_antigo($senha_banco, 'senhal7tec');
    $simple_decrypt_antigo = simple_decrypt_antigo($simple_encrypt_antigo, 'senhal7tec');
    $simple_encrypt_novo = simple_encrypt_novo($senha_banco, 'senhal7tec');
    $simple_decrypt_novo = simple_decrypt_novo($simple_encrypt_novo, 'senhal7tec');
    echo "simple_encrypt_sorteio_antigo: ".$simple_encrypt_antigo."<br/>";
    echo "simple_decrypt_sorteio_antigo: ".$simple_decrypt_antigo."<br/><br/>";
    echo "simple_encrypt_sorteio_novo: ".$simple_encrypt_novo."<br/>";
    echo "simple_decrypt_sorteio_novo: ".$simple_decrypt_novo."<br/><br/>";

    $senha_banco = '';
    $simple_encrypt_antigo = simple_encrypt_antigo($senha_banco, 'senhal7tec');
    $simple_decrypt_antigo = simple_decrypt_antigo($simple_encrypt_antigo, 'senhal7tec');
    $simple_encrypt_novo = simple_encrypt_novo($senha_banco, 'senhal7tec');
    $simple_decrypt_novo = simple_decrypt_novo($simple_encrypt_novo, 'senhal7tec');
    echo "simple_encrypt_dados_trail_antigo: ".$simple_encrypt_antigo."<br/>";
    echo "simple_decrypt_dados_trail_antigo: ".$simple_decrypt_antigo."<br/><br/>";
    echo "simple_encrypt_dados_trail_novo: ".$simple_encrypt_novo."<br/>";
    echo "simple_decrypt_dados_trail_novo: ".$simple_decrypt_novo."<br/><br/>";

    $senha_banco = '';
    $simple_encrypt_antigo = simple_encrypt_antigo($senha_banco, 'senhal7tec');
    $simple_decrypt_antigo = simple_decrypt_antigo($simple_encrypt_antigo, 'senhal7tec');
    $simple_encrypt_novo = simple_encrypt_novo($senha_banco, 'senhal7tec');
    $simple_decrypt_novo = simple_decrypt_novo($simple_encrypt_novo, 'senhal7tec');
    echo "simple_encrypt_dados_qualipax_antigo: ".$simple_encrypt_antigo."<br/>";
    echo "simple_decrypt_dados_qualipax_antigo: ".$simple_decrypt_antigo."<br/><br/>";
    echo "simple_encrypt_dados_qualipax_novo: ".$simple_encrypt_novo."<br/>";
    echo "simple_decrypt_dados_qualipax_novo: ".$simple_decrypt_novo."<br/><br/>";
    

    $senha_banco = '';
    $simple_encrypt_antigo = simple_encrypt_antigo($senha_banco, 'senhal7tec');
    $simple_decrypt_antigo = simple_decrypt_antigo($simple_encrypt_antigo, 'senhal7tec');
    $simple_encrypt_novo = simple_encrypt_novo($senha_banco, 'senhal7tec');
    $simple_decrypt_novo = simple_decrypt_novo($simple_encrypt_novo, 'senhal7tec');
    echo "simple_encrypt_dados_gestar_antigo: ".$simple_encrypt_antigo."<br/>";
    echo "simple_decrypt_dados_gestar_antigo: ".$simple_decrypt_antigo."<br/><br/>";
    echo "simple_encrypt_dados_gestar_novo: ".$simple_encrypt_novo."<br/>";
    echo "simple_decrypt_dados_gestar_novo: ".$simple_decrypt_novo."<br/><br/>";
    

    

?>