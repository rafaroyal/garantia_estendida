<?php


require_once('conexao.php');
require_once('functions.php');


$cep       = verifica($_POST['cep']);
$cep = str_replace('-', '', $cep);

        $sql = "SELECT em.ufe_sg'estado', em.log_nome'endereco', cd.loc_nosub'cidade', ba.bai_no'bairro' FROM log_logradouro em
JOIN log_localidade cd ON cd.loc_nu_sequencial = em.loc_nu_sequencial
JOIN log_bairro ba ON ba.bai_nu_sequencial = em.bai_nu_sequencial_ini
WHERE em.cep = '$cep'"; 

        $query = mysql_query($sql);
    
    if (mysql_num_rows($query)>0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);
        
        $vetor = array($endereco, $bairro, $cidade, $estado);
        echo implode('%-%', $vetor);
        
    }
    else
    {
        // placa não existe
        $vetor = array(1, 2);
        echo implode('%-%', $vetor);
    }


?>