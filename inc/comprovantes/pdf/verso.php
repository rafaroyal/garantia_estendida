<?php

    require_once('../wp-content/themes/forte/scripts/conexao.php');
    require_once('../wp-content/themes/forte/scripts/functions.php');

?>

 <?php 
    
    $origem = $_SERVER['HTTP_REFERER'];
    if (empty($origem))
    {
        header("Location: http://www.fixou.com.br");
    }
    
    $id_aluno = (empty($_GET['aluno'])) ? "" : $_GET['aluno'];
    $id_aluno = verifica($id_aluno);
    $id_curso = (empty($_GET['curso'])) ? "" : $_GET['curso'];
    $id_curso = verifica($id_curso);
    
    
    $sql        = "SELECT uc.users_LOGIN, uc.active, uc.from_timestamp, uc.completed, uc.score, 
                    uc.to_timestamp, u.id, u.login, u.email, u.`name`, u.surname, c.`name`'nome_curso', c.active'curso_ativo', c.carga_horaria FROM users_to_courses uc
                    JOIN users u ON u.login = uc.users_LOGIN
                    JOIN courses c ON c.id = uc.courses_ID
                    WHERE u.id = $id_aluno AND uc.courses_ID = $id_curso AND uc.completed = 1";
    
    $query      = mysql_query($sql);
    
    if(mysql_numrows($query) > 0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);
 
        $periodo_de = date('d/m/Y', $from_timestamp);
        $periodo_ate = date('d/m/Y', $to_timestamp);
 ?>

   
   
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-BR" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8" />
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="lolkittens" />

	<title>Certificado de <?php echo $name." ".$surname; ?></title>
</head>

<body>
 
 
 <style type="text/css">
body{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #333333;
	margin-left: 20px;
	margin-top: 0px;
	margin-right: 20px;
	margin-bottom: 30px;
    overflow: hidden;
} 
.div_imprimir{
            background: rgba(102,102,102,0.97);
              right: 0;
              position: fixed;
              top: 100px;
              z-index: 999;
              display: block;
        }
@media print 
{
    .naoprint
    {
        display:none;
    }
}

        @page {  
 size: 280mm 180mm;  
 margin-left: 0px;  
 margin-right: 0px;  
 margin-top: 0px;  
 margin-bottom: 0px;  
} 
</style>

 <div class="div_imprimir naoprint"><a href="#" title="Imprimir Certificado"  onclick="window.print();" style="  position: relative;
  float: left;
  display: block;
  width: 100%;
  overflow: hidden;padding: 10px;"><img id="imgCert" src="images/certificados/print.png" style="border-width:0px;"/></a></div>
<form name="form1" method="post" action="Verso.aspx?IdAluno=345502&amp;Curso=Assistente+Administrativo.%3a+Introdu%u00e7%u00e3o&amp;tipo=3309" id="form1">
<div>
</div>

    <table cellpadding="0px" cellspacing="0px" width="850px" align="center">
        <tbody><tr height="40px"><td align="right" width="850px">
            <a href="frente.php?aluno=<?php echo $id_aluno; ?>&curso=<?php echo $id_curso; ?>" id="lnkFrente"><img class="naoprint" height="40px" src="images/btFrente.png" alt="" border="0"/></a>
        </td></tr>
        <tr><td align="left" width="850px">
            <b>Instituição certificadora:</b> FIXOU CURSOS ON LINE, inscrita sob o <b>CNPJ:</b> 07.788.735/0001-70<br/><br/><b>Nome do aluno:</b> <?php echo $name." ".$surname; ?><br/><br/><b>Título do curso:</b> <?php echo $nome_curso; ?><br/><br/><b>Carga horária:</b> <?php echo $carga_horaria; ?> horas.<br/><br/><b>Período do Curso:</b> <?php echo $periodo_de;?> à <?php echo $periodo_ate;?>.<br/><br/><br/><span style="font-size: 18px;"><b>Aproveitamento/Média final:</b> <?php echo $score; ?>% nas avaliações.</span>
        </td></tr>
        </tbody>
    </table>
    </form>
    
    </body>
   </html>
   
<?php

}
else
{
    
}