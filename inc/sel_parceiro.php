<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
//$banco_painel = $link;

$id_grupo           = (empty($_GET['id_grupo'])) ? "" : verifica($_GET['id_grupo']);  
$nivel_usuario      = base64_decode($_COOKIE["usr_nivel"]);
$id_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);

?>

<?php

if($id_grupo == 'todos')
{
    $sel_grupo = '';
?>

<script>
    var valor = 'todos';
    
    $.get('inc/sel_grupo_produto.php?id_parceiro=' + valor + '&id_grupo_parceiro=todos',function (dados) { $("#lista_grupo_produto").html(dados);});


</script>

<?php
}
else
{
?>

<script>

$( "#select_parceiro" ).change(function() {
  var valor             = $('#select_parceiro option:selected').val();
  var id_grupo_parceiro = $('#grupo_parceiro option:selected').val();

  if(valor.length > '')
  {
    $('#passo_p3').addClass('done');  
    $.get('inc/sel_filial.php?id_parceiro=' + valor ,function (dados) { $("#lista_filial").html(dados);});
    $.get('inc/sel_usuario_vendedor.php?id_parceiro=' + valor ,function (dados) { $("#lista_usuario").html(dados);});
    $.get('inc/sel_grupo_produto.php?id_parceiro=' + valor + '&id_grupo_parceiro=' + id_grupo_parceiro,function (dados) { $("#lista_grupo_produto").html(dados);});
    $('#lista_produto').html(' ');  
  }
  else
  {
    $('#passo_p3').removeClass('done');  
  }
  
  
});

</script>
<link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

<label class="control-label ">Parceiro</label>
<div class="">

<?php  
    $sel_grupo = "AND pg.id_grupo_parceiro = $id_grupo";
    
    if($nivel_usuario == 'P' OR $nivel_usuario == 'U')
    {
        $sel_grupo.= " AND pa.id_parceiro = $id_parceiro_sessao";
    }
    
    $sql_parceiros        = "SELECT pa.id_parceiro, pa.nome, pa.cidade FROM parceiros pa
JOIN parceiros_grupos pg ON pa.id_parceiro = pg.id_parceiro
WHERE pa.del = 'N' $sel_grupo";
    $query_parceiros      = mysql_query($sql_parceiros) or die(mysql_error()." - 145");
                    
    if (mysql_num_rows($query_parceiros)>0)
    {
        
        echo "<select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_parceiro\" name=\"select_parceiro\" ><option value=\"\" selected=\"\"></option>";       
        if($nivel_usuario == 'A')
        {
            echo "<option value=\"todos\" >TODOS</option>";
        }
        
        
        while($dados_parceiros = mysql_fetch_array($query_parceiros))
        {
            extract($dados_parceiros);
            
            echo "<option value=\"$id_parceiro\" data-subtext=\"$cidade\">$nome</option>";
        }
        
        echo "</select>";
    }
?>

</div>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>

<?php

}
?>