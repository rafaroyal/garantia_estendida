<?php
session_start();
$token = "1634d91bed2b72946786a7820acdcf67";
$senhaAcesso = 'senhal7tec';
$tokenValido = false;
if($_REQUEST['token'] == $token AND $_REQUEST['senhaAcesso'] == $senhaAcesso )
    $tokenValido = true;

if($_REQUEST['senhaAcesso'] == $senhaAcesso && empty($_SESSION['usuarioValido']))
    $_SESSION['usuarioValido'] = true;

if($_REQUEST['sair'])
    unset($_SESSION['usuarioValido']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8"/>
<title>Usando GIT para atualizar arquivos no servidor de hospedagem</title>
</head>
<body>
<pre>
    <?php
        if($tokenValido == true) {
            $exec = shell_exec("git pull origin master 2>&1");
            echo $exec;

            $textoLog = PHP_EOL."Data: ".date(d."/".m."/".Y." - ".H.":".i.":".s);
            $textoLog .= PHP_EOL.$exec;

            $arquivoLog = fopen('log.txt', 'a+');
            fwrite($arquivoLog, $textoLog);
            fclose($arquivoLog);
        } /*else if($_SESSION['usuarioValido']) {
        ?>
        <!--<form action="index.php" method="post">
            <input type="hidden" name="token" value="1634d91bed2b72946786a7820acdcf67">
            <input type="submit" value="Atualizar">
        </form>-->
        <?php
        if($_SESSION['usuarioValido'])
            echo '<p><a href="index.php?sair=true">Sair</a></p>';
            $texto = file('log.txt');
            foreach ($texto as $linha) {
                echo $linha;
            }
        } else {
        ?>
        <!--<form action="index.php" method="post">
            <div>
                <input type="text" placeholder="Senha" name="senhaAcesso"> 
            </div>
            <input type="submit" value="Acessar Sistema">
        </form>-->
        <?php
        }*/
    ?>
</pre>
</body>
</html>