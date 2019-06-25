
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$id_cliente_historico   = (empty($_GET['id_cliente'])) ? "" : verifica($_GET['id_cliente']); 
$tipo_sms               = (empty($_GET['tipo_sms'])) ? "" : verifica($_GET['tipo_sms']); 
$nome_cliente           = (empty($_GET['nome'])) ? "" : verifica($_GET['nome']);  
$celular                = (empty($_GET['celular'])) ? "" : verifica($_GET['celular']); 
$celular_principal   = str_replace(" ", "", $celular);
//$celular_principal   = '43984877846';
$msg = '';

$id_usuario_s   = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s  = base64_decode($_COOKIE["usr_parceiro"]);

$agora_com_hora  = date("Y-m-d H:i:s");

$sql_user   = "SELECT valor FROM opcoes
            WHERE nome = 'msg_sms_contato'";
$query_user = mysql_query($sql_user, $banco_painel);
$msg_sms_contato = '';                
if (mysql_num_rows($query_user)>0)
{
    $msg_sms_contato = mysql_result($query_user, 0);    
}


if($tipo_sms == 'cobranca'){
    $msg = "$nome_cliente, $msg_sms_contato";
}


if(in_array("57", $verifica_lista_permissoes_array_inc)){
$msg_encode = urlencode($msg);
$url = "http://209.133.205.2/shortcode/api.ashx?action=sendsms&lgn=43984877846&pwd=16061987&msg=$msg_encode&numbers=$celular_principal";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);

if($content = trim(curl_exec($curl))) 
{
    $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
VALUES ('$id_cliente_historico', '$id_usuario_s', 'clientes', 'Envio de SMS/COBRANÇA: $msg', '$agora_com_hora')";   
$query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");
    
    echo 'ok';
}

curl_close($curl);
}else{
    echo 'erro';
}
