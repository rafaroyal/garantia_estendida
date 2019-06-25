<?php

//extract data from the post
//set POST variables
$url = 'http://trailservicos.com.br/painel_trail/retorno_teste_curl_post.php';
$fields = array(
	'id_boleto' => '121'
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

if($result){
    print_r($result);
}else{
    echo 'error';
}

?>