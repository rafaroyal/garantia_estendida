<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

require_once('functions.php');
require_once('conexao.php'); 
//$banco_painel = $link;

$id_grupo_produto         = (empty($_GET['id_grupo_produto'])) ? "" : verifica($_GET['id_grupo_produto']);  



?>

<link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

<script>

$( "#select_produto" ).change(function() {
  var valor = $('#select_produto option:selected').val();
 
  if(valor.length > '')
  {
    $('#passo_p4').addClass('done');  
  }
  else
  {
    $('#passo_p4').removeClass('done');  
  }
});

</script>

<?php

    
if($id_grupo_produto == 'todos'){
  echo "<label class=\"control-label \">Produto</label>
<div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_produto\" name=\"select_produto\" ><option value=\"\" ></option> <option value=\"todos\" selected=\"\">TODOS</option>";
      echo "</select></div>";
}else{

  $sql_produtos        = "SELECT pro.id_produto, pro.nome, bpro.slug FROM produtos pro
JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
WHERE pro.ativo = 'S' AND prog.id_grupo_produto = $id_grupo_produto AND pro.id_base_produto <> 1";
  $query_produtos      = mysql_query($sql_produtos);
                  
  if (mysql_num_rows($query_produtos)>0)
  {
      echo "<label class=\"control-label \">Produto</label>
<div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_produto\" name=\"select_produto\" ><option value=\"\" ></option> <option value=\"todos\" selected=\"\">TODOS</option>";
      
      while($dados_produtos = mysql_fetch_array($query_produtos))
      {
          extract($dados_produtos);
          
          echo "<option value=\"$id_produto\" data-subtext=\"$slug\">$nome</option>";
      }
      
      echo "</select></div>";
  }
}
?>


<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>