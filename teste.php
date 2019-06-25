<?php
 
    $senha_padrao ='HQPfZ2xq1X';
    $chave ='chavecielo';
 
    function simple_encrypt($senha,$chave)
    {  
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $chave, $senha, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
 
    function simple_decrypt($senha,$chave)
    {  
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $chave, base64_decode($senha), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
    
    $senha = simple_encrypt($senha_padrao, $chave);
    //echo $senha.'<br/>';
    
    $sem_senha = simple_decrypt($senha, $chave);
    //echo $sem_senha;
    
    //$senha      = md5($senha_padrao);
    //echo $senha;
    $id_ = 2;
    $status_list = array(
    "Ativo" => "1",
    "Inativo" => "2"
    );
    
    //echo array_search($id_,$status_list);
    
    echo md5('cielo');
?>