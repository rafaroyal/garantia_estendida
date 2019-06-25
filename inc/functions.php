<?php

/**
 * @project GED
 * @author Lucas V. Leati
 * @created 28/4/2011
 * @lastmodify 05/11/2012
 */

//constantes
DEFINE('titulo','Painel Trail Serviços');
DEFINE('descricao','FIXOU Cursos OnLine');

//converte data BD --> normal
function converte_data($data)
{
    
    if ($data == "")
    {
        $data_nova = "";
    }
    elseif (substr($data, 2, 1) == '/' || substr($data, 2, 1) == '-' )
    {
        //dd/mm/yyyy --> tem que converter data pra banco de dados
        $data_nova = substr($data, 6,4)."-".substr($data, 3,2)."-".substr($data, 0,2);
        
    }
    else
    {
        //yyyy-mm-dd 11:11
        if (strlen($data) > 12)
        {
            //tem data e hora
            $data_nova = substr($data, 8,2)."/".substr($data, 5,2)."/".substr($data, 0,4)." - ".substr($data, 11,5);
        }        
        else
        {
            //só data
            $data_nova = substr($data, 8,2)."/".substr($data, 5,2)."/".substr($data, 0,4);
        }
    }
    
    return $data_nova;
}

function converte_data_barra($data_db)
{
    $data_nova = substr($data_db, 0,2)."/".substr($data_db, 2,2)."/".substr($data_db, 4,4);
    return $data_nova;
}



//data atual
function agora()
{
    $data = date('Y-m-d H:i:s');
    
    return $data;
}

//codifica
function encode($var)
{
    $enc = base64_encode($var.substr(agora(), 11).mt_rand(0, 9));
    
    return $enc;
}
function decode ($var)
{
    $dec = base64_decode($var);
    $dec = substr($dec, 0, -9); //2010-22-22 00:00:00
    
    return $dec;
}


//busca nome do usuario
function nome_usuario($id_usuario)
{
    $sql = "SELECT nome FROM usuarios WHERE id_usuario = $id_usuario";
    $query = mysql_query($sql);
    
    if ($query && mysql_num_rows($query) > 0)
    {
        $nome_usuario = mysql_result($query, 0, 0);
        return $nome_usuario;
    }
    else
    {
        return "";
    }
}

//anti sql-injection
function verifica($var, $html = false)	
{
    if (is_array($var))
    {
        for ($i=0; $i<count($var); $i++)
        {
            $var[$i] = preg_replace("/(from|select|insert|delete|where|drop table|show tables|\*|--|\\\\)/i","",$var[$i]);  
            $var[$i] = trim($var[$i]);//limpa espaços vazio 
            if ($html == false) $var[$i] = strip_tags($var[$i]);//tira tags html e php  
            if(!get_magic_quotes_gpc()) 
            $var[$i] = addslashes($var[$i]);//Adiciona barras invertidas a uma string   
            
        }
        return $var;
    }
    else 
    {
        // remove palavras que contenham sintaxe sql    
        $var = preg_replace("/(from|select|insert|delete|where|drop table|show tables|\*|--|\\\\)/i","",$var);  
        $var = trim($var);//limpa espaços vazio 
        if ($html == false) $var = strip_tags($var);//tira tags html e php  
        if(!get_magic_quotes_gpc()) 
        $var = addslashes($var);//Adiciona barras invertidas a uma string   
        return $var;
    }    	
    	
}	

function exibe($dados)
{
    if (empty($dados) || $dados == '0000-00-00')
    {
        return "-";
    }
    elseif (substr($dados, 4,1) == '-' && substr($dados, 7,1) == '-')
    {
        return converte_data($dados);
    }
    elseif ($dados == 'S')
    {
        return "Sim";
    }
    elseif ($dados == 'N')
    {
        return "Não";
    }
    else
    {
        return $dados;
    }
}

//tooltip
function tooltip($msg)
{
    echo "<img src=\"./img/interrogacao.gif\" style=\"cursor: help;width:12px;height:12px;float:inherit\" title=\"$msg\" />";
}

//redimensiona imagem
function redimensiona($imagem_post, $name, $largura, $altura, $pasta){
    $img = imagecreatefromjpeg($imagem_post['tmp_name']);
    $x   = imagesx($img);
    $y   = imagesy($img);
    //$altura = ($largura * $y)/$x;
    $nova = imagecreatetruecolor($largura, $altura);
    imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);
    imagejpeg($nova, "$pasta/$name");
    imagedestroy($img);
    imagedestroy($nova);
    return $name;
}

//url_amigável --> remove acentos, espaços e etc
function normaliza($str){
    $str = strtolower(utf8_decode($str)); $i=1;
    $str = strtr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
    $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
    while($i>0) $str = str_replace('--','-',$str,$i);
    if (substr($str, -1) == '-') $str = substr($str, 0, -1);
    return $str;
}

function resumo($frase, $qtde_letras = 60)
{
    $frase = strip_tags($frase);
    $p = explode(' ', $frase);
    $c = 0;
    $cortada = '';
    
    foreach($p as $p1){
    if ($c<$qtde_letras && ($c+strlen($p1) <= $qtde_letras)){
         $cortada .= ' '.$p1;
         $c += strlen($p1)+1;
    }else{
        break;
    }
    }
    
    return strlen($cortada) < $qtde_letras ? $cortada.'...' : $cortada;
}

function getIDyoutube($ytURL) {
 
	$ytvIDlen = 11;	// This is the length of YouTube's video IDs

	// The ID string starts after "v=", which is usually right after 
	// "youtube.com/watch?" in the URL
	$idStarts = strpos($ytURL, "?v=");

	// In case the "v=" is NOT right after the "?" (not likely, but I like to keep my 
	// bases covered), it will be after an "&":
	if($idStarts === FALSE)
		$idStarts = strpos($ytURL, "&v=");
	// If still FALSE, URL doesn't have a vid ID
	if($idStarts === FALSE)
		die("");

	// Offset the start location to match the beginning of the ID string
	$idStarts +=3;

	// Get the ID string and return it
	$ytvID = substr($ytURL, $idStarts, $ytvIDlen);
	
	return $ytvID;
 
}


function auto_increment($tabela)
{
    $sql = "SHOW TABLE STATUS LIKE '$tabela'";
    $res = mysql_query($sql)or die(mysql_error());
    $status = mysql_fetch_array($res);
     
    return $status['Auto_increment']; 
}

/*function verifica_acesso($id_pagina)
{
    if ($_SESSION['usr_nivel'] == 2)
    {
        if (!in_array($id_pagina, explode(" ", $_SESSION['paginas'])))
        {
            header("Location: inicio.php");   
        }
    }
    
}*/

function verifica_link($link)
{
    if (!empty($link))
    {
        $verif = substr($link, 0, 4);
        if ($verif != 'http')
        {
            $link = "http://".$link;
        }
        
        return $link;
    }
}
//obtem apenas um resultado
function result($sql)
{
	$query = mysql_query($sql);
	if ($query && mysql_num_rows($query)>0)
	{
		$result = mysql_result($query, 0, 0);
		return $result;
	}
}
function paginacao($tabela, $pag_atual)
{
	$sql		= "SELECT COUNT(*) FROM $tabela";
	$query		= mysql_query($sql);
	
	$total		= mysql_result($query,0,0);
	
	$limite		= 10;
	
	$pags		= ceil($total / $limite);
	$anterior	= $pag_atual -1;
	$proxima	= $pag_atual +1;		

	if ($pags > 1)
	{
		$paginacao 	= "<ul class=\"pagination hor-list\">";
		for ($i=1; $i<=$pags; $i++)
		{
			if ($i == 1)
			{
				if ($pag_atual == $i)	
				{
					$paginacao.= "<li class='disabled'><a href='#'>Primeira</a></li>
			                    <li class='disabled'><a href='#'>Anterior</a></li>
			                    <li class='active'><a href='#'>$i</a></li>";
				}
				else 
				{
					$paginacao.= "<li><a href='#' rel='$tabela-1'>Primeira</a></li>
			                    <li><a href='#' rel='$tabela-$anterior'>Anterior</a></li>
			                    <li><a href='#' rel='$tabela-$i'>$i</a></li>";
				}
				
			}
			elseif ($i == $pags)
			{
				if ($pag_atual == $i)
				{
					$paginacao.= "<li class='active' rel='$tabela-$i'><a href='#'>$i</a></li>";	
					$paginacao.= "<li class='disabled'><a href='#'>Próxima</a></li>
                    				<li class='disabled'><a href='#'>Última</a></li>";
				}	
				else 
				{
					$paginacao.= "<li><a href='#' rel='$tabela-$i'>$i</a></li>";	
					$paginacao.= "<li><a href='#' rel='$tabela-$proxima'>Próxima</a></li>
                	    			<li><a href='#' rel='$tabela-$pags'>Última</a></li>";
				}
				
			}
			else 
			{
				if ($pag_atual == $i)
				{
					$paginacao.= "<li class='active'><a href='#'>$i</a></li>";
				}	
				else 
				{
					$paginacao.= "<li><a href='#'>$i</a></li>";
				}
				
			}
		}
	}
	
	
	return $paginacao;
}

//link_video
function link_video($id, $provedor)
{
	if ($provedor == 'youtube')
	{
		$link = result("SELECT link_youtube FROM config WHERE id_config = 1");
		$link = str_replace('%id_video%', $id, $link);
		
		return $link;
	}
	elseif ($provedor == 'vimeo') 
	{
		$link = result("SELECT link_vimeo FROM config WHERE id_config = 1");
		$link = str_replace('%id_video%', $id, $link);
		
		return $link;
	}
	else 
	{
	
	}

}

//msg_status
function status_ok($txt)
{
	$span = "<div class=\"alert alert-success fade in\">
                    <button class=\"close\" data-close=\"alert\"></button>
                    <i class=\"fa-lg fa fa-check\"></i>
                    <span> $txt </span>
                </div>";	
	return $span;
}
function status_erro($txt)
{
	$span = "<div class=\"alert alert-danger fade in\">
                    <button class=\"close\" data-close=\"alert\"></button>
                    <i class=\"fa fa-close\"></i>
                    <span> $txt </span>
                </div>";	
	return $span;
}
function status_info($txt)
{
	$span = "<div class=\"alert alert-warning fade in\">
                    <button class=\"close\" data-close=\"alert\"></button>
                    <i class=\"fa fa-info\"></i>
                    <span> $txt </span>
                </div>";	
	return $span;
}

//BUSCA CONFIGURAÇÃO
function config($campo)
{
    $sql    = "SELECT $campo FROM config WHERE id_config = 1";
    $query  = mysql_query($sql);
    
    $result = mysql_result($query, 0,0);
    
    return $result;
}

//calcula idade
function calcula_idade($data_nascimento) {
   
    // Separa em dia, mês e ano
    list($dia, $mes, $ano) = explode('/', $data_nascimento);
   
    // Descobre que dia é hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    // Descobre a unix timestamp da data de nascimento do fulano
    $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
   
    // Depois apenas fazemos o cálculo já citado :)
    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
    return $idade;
    
}

function moeda_db($str)
{
    $str = str_replace(".", "", $str);
    $str = str_replace(",", ".", $str);
    
    return $str;
    
}

function db_moeda($str)
{
    if(strlen($str) > 0){
        $str = str_replace(",", ".", $str);
        $str = "R$ ".number_format($str, 2, ',', '.');
    }else{
        $str = '-';
    }
    
    
    return $str;
}
function db_moeda2($valor)
{
    if(strlen($valor) > 0){
    $valor = number_format($valor, 2, ',', '.');
    }else{
        $valor = '';
    }
    return $valor;
}

function db_moeda3($valor)
{
    if(strlen($valor) > 0){
    $valor = str_replace(",", ".", $valor);
    $valor = number_format($valor, 2, ',', '.');
    }else{
        $valor = '';
    }
    return $valor;
}

function db_moeda4($valor)
{
    if(strlen($valor) > 0){
    $valor = str_replace(",", ".", $valor);
    $valor = number_format($valor, 2, '.', '.');
    }else{
        $valor = '';
    }
    return $valor;
}

function moeda_db4($str)
{
    $str = str_replace(".", ",", $str);
    return $str;
    
}

function moeda_db5($str)
{
    $str = str_replace(".", "", $str);
    return $str;
    
}

function mask_total($val, $mask)
{
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++)
 {
 if($mask[$i] == '#')
 {
 if(isset($val[$k]))
 $maskared .= $val[$k++];
 }
 else
 {
 if(isset($mask[$i]))
 $maskared .= $mask[$i];
 }
 }
 return $maskared;
}

function remove_acento($palavra)
{
		$palavra = preg_replace('/(\'|")/', '', $palavra);
        //$palavra = ereg_replace("[^a-zA-Z0-9]", "", strtr($palavra, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC "));
		//preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($palavra));
		$array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" 
, "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç", "º" );
		$array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C", "" );
		return str_replace( $array1, $array2, $palavra); 
		
		return $palavra;
}

function get_ip()
{
    $ip						= $_SERVER["REMOTE_ADDR"];
    //$ip2long				= sprintf("%u\n", ip2long($ip));
    //return $ip2long;
    return $ip;
}

function log_acesso($id_user)
{
    $ip = get_ip();
    $dt_acesso = agora();

    $sql    = "UPDATE log_acesso SET ativo = 'N' WHERE id_usuario = '$id_user' ORDER BY id_log_acesso DESC LIMIT 1";
    $query  = mysql_query($sql);

    $sql    = "INSERT INTO log_acesso (id_usuario, dt_acesso, ip, ativo) VALUES ($id_user, '$dt_acesso', '$ip', 'S')";
    $query  = mysql_query($sql);
}

function log_acesso_sair($id_user)
{
    $sql    = "UPDATE log_acesso SET ativo = 'N' WHERE id_usuario = '$id_user' ORDER BY id_log_acesso DESC LIMIT 1";
    $query  = mysql_query($sql);
}

function primeiro_nome($nome)
{
    $primeiro_nome = explode(" ", $nome);
    return $primeiro_nome[0];
}

function conexaoDB($host_DB, $user, $senha, $nome_DB)
{
    /*$nome_DB = mysql_connect($host_DB, $user, $senha) or die (mysql_error());
    $nome_DB = mysql_select_db($nome_DB, $nome_DB);*/
    
    $nome_conexao = mysql_connect($host_DB, $user, $senha) or die (mysql_error());
    mysql_select_db($nome_DB, $nome_conexao);
    mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
    return $nome_conexao;
}

function simple_encrypt($senha,$chave)
    {  
        //return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $chave, $senha, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        $secret_key = $chave;
        $secret_iv = $chave;
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        
        $output = base64_encode( openssl_encrypt( $senha, $encrypt_method, $key, 0, $iv ) );

        return $output;
    }
 
    function simple_decrypt($senha,$chave)
    {  
        //return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $chave, base64_decode($senha), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        $secret_key = $chave;
        $secret_iv = $chave;
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        
        $output = openssl_decrypt( base64_decode( $senha ), $encrypt_method, $key, 0, $iv );

        return $output;
    }
    
function limpa_caracteres($valor)
    {
    $valor = preg_replace('/[^a-zA-Z0-9]/', '', $valor);
       return $valor;
    } 

function limpa_aspas($valor)
    {
    $valor = preg_replace('/(\'|")/', '', $valor);
       return $valor;
    } 


function limpar_texto($str){
      return preg_replace("/[^0-9]/", "", $str);
    }

function createToken($id_cliente, $id_parceiro){

        $salt = "0123456789";  // salt to select chars from
        srand((double)microtime()*1000000); // start the random generator
        $token = $id_cliente.$id_parceiro; // set the inital variable
        $contar = 20 - strlen($token);
        
        for ($i = 0; $i < $contar; $i++) {  // loop and create password
			$token = $token . substr ($salt, rand() % strlen($salt), 1);
		}
        return $token;
    }
    
function gerar_codigos($quantidade){

        $salt = "0123456789";  // salt to select chars from
        srand((double)microtime()*1000000); // start the random generator
        //$token = $id_cliente.$id_parceiro; // set the inital variable
        $contar = $quantidade;
        
        for ($i = 0; $i < $contar; $i++) {  // loop and create password
			$token = substr ($salt, rand() % strlen($salt), 1);
		}
        return $token;
    }

function somar_datas( $numero, $tipo ){
  switch ($tipo) {
    case 'd':
    	$tipo = ' day';
    	break;
    case 'm':
    	$tipo = ' month';
    	break;
    case 'y':
    	$tipo = ' year';
    	break;
    }	
    return "+".$numero.$tipo;
}

function porcentagem_juros_atraso ( $valor, $porcentagem ) {
    return ( $valor * $porcentagem ) / 100;
}

function convdata($dataform, $tipo){
	if ($tipo == 0) {
		$datatrans = explode ("/", $dataform);
		$data = "$datatrans[2]-$datatrans[1]-$datatrans[0]";
	} elseif ($tipo == 1) {
		$datatrans = explode ("-", $dataform);
		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
	}elseif ($tipo == 2) {
		$datatrans = explode ("-", $dataform);
		$data = "$datatrans[1]/$datatrans[2]/$datatrans[0]";
	} elseif ($tipo == 3) {
		$datatrans = explode ("/", $dataform);
		$data = "$datatrans[2]-$datatrans[1]-$datatrans[0]";
	}
 
	return $data;
};

function diasEntreData($date_ini, $date_end){
	$data_ini = strtotime( convdata(convdata($date_ini,3),2)   ); //data inicial '29 de julho de 2003'
	$hoje = convdata($date_end,3);//date("m/d/Y"); // data atual
	$foo = strtotime($hoje); // transforma data atual em segundos (eu acho)
	$dias = ($foo - $data_ini)/86400; //calcula intervalo
	return $dias;
};

/* codigo de barras */

function fbarcode($valor, $pasta=''){

$fino = 1 ;
$largo = 3 ;
$altura = 40 ;

  $barcodes[0] = "00110" ;
  $barcodes[1] = "10001" ;
  $barcodes[2] = "01001" ;
  $barcodes[3] = "11000" ;
  $barcodes[4] = "00101" ;
  $barcodes[5] = "10100" ;
  $barcodes[6] = "01100" ;
  $barcodes[7] = "00011" ;
  $barcodes[8] = "10010" ;
  $barcodes[9] = "01010" ;
  for($f1=9;$f1>=0;$f1--){ 
    for($f2=9;$f2>=0;$f2--){  
      $f = ($f1 * 10) + $f2 ;
      $texto = "" ;
      for($i=1;$i<6;$i++){ 
        $texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
      }
      $barcodes[$f] = $texto;
    }
  }


//Desenho da barra


//Guarda inicial
?><img src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/p.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/b.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/p.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/b.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
<?php
$texto = $valor ;
if((strlen($texto) % 2) <> 0){
	$texto = "0" . $texto;
}

// Draw dos dados
while (strlen($texto) > 0) {
  $i = round(esquerda($texto,2));
  $texto = direita($texto,strlen($texto)-2);
  $f = $barcodes[$i];
  for($i=1;$i<11;$i+=2){
    if (substr($f,($i-1),1) == "0") {
      $f1 = $fino ;
    }else{
      $f1 = $largo ;
    }
?>
    src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/p.png' width=<?php echo $f1?> height=<?php echo $altura?> border=0><img 
<?php
    if (substr($f,$i,1) == "0") {
      $f2 = $fino ;
    }else{
      $f2 = $largo ;
    }
?>
    src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/b.png' width=<?php echo $f2?> height=<?php echo $altura?> border=0><img 
<?php
  }
}

// Draw guarda final
?>
src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/p.png' width=<?php echo $largo?> height=<?php echo $altura?> border=0><img 
src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/b.png' width=<?php echo $fino?> height=<?php echo $altura?> border=0><img 
src='<?php echo "https://".$_SERVER['HTTP_HOST']."/".$pasta;?>/inc/img_carne/p.png' width=<?php echo 1?> height=<?php echo $altura?> border=0> 
  <?php
} //Fim da função

function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

/* fim cod de barras */

function modulo_10($num) {
		$numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo (falor 10)
            $temp = $numeros[$i] * $fator;
            $temp0=0;
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }

        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }

        return $digito;

}

function monta_linha_digitavel($codigo) {

        $p1 = substr($codigo, 0, 11);
        $p2 = substr($codigo, 11, 11);
        $p3 = substr($codigo, 22, 11);
        $p4 = substr($codigo, 33, 11);
        

        $p1_dv = modulo_10($p1);
        $p2_dv = modulo_10($p2);
        $p3_dv = modulo_10($p3);
        $p4_dv = modulo_10($p4);

        return "$p1-$p1_dv &nbsp; $p2-$p2_dv &nbsp; $p3-$p3_dv &nbsp; $p4-$p4_dv";
    
}

function monta_linha_digitavel_p1($codigo) {

        $p1 = substr($codigo, 0, 11);
        
        $p1_dv = modulo_10($p1);

        return "$p1-$p1_dv";
    
}

function monta_linha_digitavel_p2($codigo) {

        $p2 = substr($codigo, 11, 11);

        $p2_dv = modulo_10($p2);

        return "$p2-$p2_dv";
    
}

function monta_linha_digitavel_p3($codigo) {

        $p3 = substr($codigo, 22, 11);

        $p3_dv = modulo_10($p3);

        return "$p3-$p3_dv";
    
}

function monta_linha_digitavel_p4($codigo) {

        $p4 = substr($codigo, 33, 11);

        $p4_dv = modulo_10($p4);

        return "$p4-$p4_dv";
    
}

function reduzirNome( $texto, $tamanho )
{
    // Se o nome for maior que o permitido
    if( strlen( $texto ) > ( $tamanho - 2 ) )
    {
        $texto = strip_tags( $texto );

        // Pego o primeiro nome
        $palavas    = explode( ' ', $texto );
        $nome       = $palavas[0];

        // Pego o ultimo nome
        $palavas    = explode( ' ', $texto );
        $sobrenome  = trim( $palavas[count( $palavas ) - 1]);

        // Vejo qual e a posicao do ultimo nome
        $ult_posicao= count( $palavas ) - 1;

        // Crio uma variavel para receber os nomes do meio abreviados
        $meio = '';

        // Listo todos os nomes do meios e abrevio eles
        for( $a = 1; $a < $ult_posicao; $a++ ):

            // Enquanto o tamanho do nome nao atingir o limite de caracteres
            // completo com o nomes do meio abreviado
            if( strlen( $nome.' '.$meio.' '.$sobrenome )<=$tamanho ):
                $meio .= ' '.strtoupper( substr( $palavas[$a], 0,1 ) );
            endif;
        endfor;

    }else{
       $nome       = $texto;
       $meio       = '';
       $sobrenome  = '';
    }

    return trim( $nome.$meio.' '.$sobrenome );
}

function ValidaData($dat){
	$data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como referência
	$d = $data[0];
	$m = $data[1];
	$y = $data[2];
 
	// verifica se a data é válida!
	// 1 = true (válida)
	// 0 = false (inválida)
	$res = checkdate($m,$d,$y);
	if ($res == 1){
	   return "ok";
	} else {
	   return "erro";
	}
}


?>