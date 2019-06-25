<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$id_cliente         = (empty($_POST['q'])) ? "" : verifica($_GET['q']);

// FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
    


$row = array();
$return_arr = array();
$row_array = array();

if((isset($_GET['q']) && strlen($_GET['q']) > 0) || (isset($_GET['id']) && is_numeric($_GET['id'])))
{

    /*if(isset($_GET['q']))
    {
        $getVar = $db->real_escape_string($_GET['q']);
        $whereClause =  " label LIKE '%" . $getVar ."%' ";
    }
    elseif(isset($_GET['id']))
    {
        $whereClause =  " categoryId = $getVar ";
    }*/
    /* limit with page_limit get */

    $limit = intval($_GET['page_limit']);
    $search = strip_tags(trim($_GET['q']));
    
    $sql_base   = "SELECT * FROM gui_procedimentos
    WHERE nome LIKE '%$search%'";
    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");

    $rows = array();
     if (mysql_num_rows($query_base)>0)
     {    
        while ($dados = mysql_fetch_array($query_base))
        {
            
            $data[] = array('items' => array('codigo' => $dados['codigo'], 'text' => $dados['nome']));
            
            //$data[] = array('nome' => $dados['codigo'], 'text' => $dados['nome']);
            //$row_array['codigo'] = $codigo;
            //$row_array['nome'] = $nome;
            //array_push($return_arr,$row_array);
        } 
    }else{
        $data[] = array('items' => array('sem_resultado' => 'Sem resultados...'));
    }
}
/*else
{
    $row_array['id'] = 0;
    $row_array['text'] = utf8_encode('Start Typing....');
    array_push($return_arr,$row_array);
}*/

//$ret = array();
/* this is the return for a single result needed by select2 for initSelection */
/*if(isset($_GET['id']))
{
    $ret = $row_array;
}*/
/* this is the return for a multiple results needed by select2
* Your results in select2 options needs to be data.result
*/
/*else
{
    $ret['results'] = $return_arr;
}*/

header('Content-Type: application/json');
echo json_encode($data);


?>