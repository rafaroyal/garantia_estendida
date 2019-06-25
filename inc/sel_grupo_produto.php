<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

require_once('functions.php');
require_once('conexao.php'); 
//$banco_painel = $link;

$id_parceiro         = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);  
$id_grupo_parceiro   = (empty($_GET['id_grupo_parceiro'])) ? "" : verifica($_GET['id_grupo_parceiro']);  


?>

<link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

<script>

$( "#select_grupo_produto" ).change(function() {
  var valor = $('#select_grupo_produto option:selected').val();

  if(valor.length > '')
  {
    $('#passo_p4').addClass('done');  
    $.get('inc/sel_produto.php?id_grupo_produto=' + valor ,function (dados) { $("#lista_produto").html(dados);});
  }
  else
  {
    $('#lista_produto').html(' ');  
    $('#passo_p4').removeClass('done');  
  }
  
  
});

var valor = 'todos';
    
    $.get('inc/sel_produto.php?id_grupo_produto=' + valor ,function (dados) { $("#lista_produto").html(dados);});

</script>

<?php


if($id_parceiro == 'todos')
{
    $sel_parceiro = "";
}
else
{
    $sel_parceiro = " AND pser.id_parceiro = $id_parceiro ";
}

if($id_grupo_parceiro == 'todos'){
    $sel_grupo_parceiro = '';
}else{
    $sel_grupo_parceiro = " AND pagr.id_grupo_parceiro = $id_grupo_parceiro";
}

    //AND gpro.tipo_grupo = 'IND'
    $sql_grupo_produto        = "SELECT gpro.id_grupo_produto, gpro.nome FROM grupos_produtos gpro
    JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
    JOIN produtos pro ON prog.id_produto = pro.id_produto
    JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
    JOIN parceiros_grupos pagr ON pser.id_parceiro = pagr.id_parceiro
    WHERE gpro.del = 'N' $sel_grupo_parceiro $sel_parceiro
    GROUP BY gpro.id_grupo_produto";
    $query_grupo_produto      = mysql_query($sql_grupo_produto);
                    
    if (mysql_num_rows($query_grupo_produto)>0)
    {
        
        echo "<label class=\"control-label \">Plano</label>
<div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_grupo_produto\" name=\"select_grupo_produto\" >
<option value=\"todos\" selected=\"\">TODOS</option>";
        
        while($dados_grupo_produto = mysql_fetch_array($query_grupo_produto))
        {
            extract($dados_grupo_produto);
            
            echo "<option value=\"$id_grupo_produto\">$nome</option>";
        }
        
        echo "</select></div>";
    }
?>


<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>