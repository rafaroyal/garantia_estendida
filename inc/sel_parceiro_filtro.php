<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
//$banco_painel = $link;

?>
<script>


$( "#select_parceiro_user_filtro" ).change(function() {
  var valor = $('#select_parceiro_user_filtro option:selected').val();
  $("#lista_filial").html('');  
  $("#lista_user").html('');
  if(valor > 0)
  {
    $.get('inc/sel_filial_filtro.php?id_parceiro=' + valor ,function (dados) { $("#lista_filial").html(dados);});
    $.get('inc/sel_user_filtro.php?id_parceiro=' + valor ,function (dados) { $("#lista_user").html(dados);});
  }
  
  
});

</script>
<link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
<div class="col-md-4">
 <div class="form-group form-md-floating-label">
     <label class="control-label ">Parceiro</label>

    
    <?php  
        $id_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);
        if(base64_decode($_COOKIE["usr_nivel"]) == 'A'){
            
            $sql_parceiros        = "SELECT pa.id_parceiro, pa.nome, pa.cidade FROM parceiros pa
            JOIN parceiros_grupos pg ON pa.id_parceiro = pg.id_parceiro
            WHERE pa.del = 'N' ";
            $query_parceiros      = mysql_query($sql_parceiros);
                            
            if (mysql_num_rows($query_parceiros)>0)
            {
                
                echo "<select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_parceiro_user_filtro\" name=\"select_parceiro_user_filtro\" ><option value=\"\"selected=\"\"></option><option value=\"todos\">TODOS</option>";       
                
                while($dados_parceiros = mysql_fetch_array($query_parceiros))
                {
                    extract($dados_parceiros);
                    
                    echo "<option value=\"$id_parceiro\" data-subtext=\"$cidade\">$nome</option>";
                }
                
                echo "</select>";
            }
            
        }else{
            
            $sql_parceiros        = "SELECT nome FROM parceiros
            WHERE del = 'N' AND id_parceiro = $id_parceiro_sessao";
            $query_parceiros      = mysql_query($sql_parceiros);
                            
            if (mysql_num_rows($query_parceiros)>0)
            {
                $nome        = mysql_result($query_parceiros, 0, 'nome');
                
            }
            
        ?>
        <p class="form-control-static"> <?php echo $nome; ?> </p>
        <input type="hidden" class="form-control form-filter input-sm" name="select_parceiro_user" value="<?php echo $id_parceiro_sessao; ?>"/>
        
        
        <?
        }

    ?>
    
 
    </div>
    
</div>
<div class="col-md-4">
 <?php
 if(base64_decode($_COOKIE["usr_nivel"]) == 'A'){
 ?>
    <div class="form-group form-md-floating-label">
    <div id="lista_filial"></div>
    </div>
    <?php
    }
    ?>
    
    <?php
    if(base64_decode($_COOKIE["usr_nivel"]) == 'P'){
    ?>
    <div class="form-group form-md-floating-label">
    <?php
        $sel_parceiro = "WHERE pa.id_parceiro = $id_parceiro_sessao";

        $sql_parceiros        = "SELECT fi.id_filial, fi.id_filial_integracao, fi.nome, fi.cidade FROM filiais fi
    JOIN parceiros pa ON fi.id_parceiro = pa.id_parceiro
    $sel_parceiro";
        $query_parceiros      = mysql_query($sql_parceiros);
                    
        if (mysql_num_rows($query_parceiros)>0)
        {
            
            echo "<label class=\"control-label \">Filial</label>
    <div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_filial\" name=\"select_filial\" ><option value=\"\" selected=\"\"></option> ";
            
            while($dados_parceiros = mysql_fetch_array($query_parceiros))
            {
                extract($dados_parceiros);
                
                if($id_filial_integracao > 0){
                    echo "<option value=\"$id_filial_integracao\" data-subtext=\"$cidade\">$nome</option>";
                }else{
                    echo "<option value=\"$id_filial\" data-subtext=\"$cidade\">$nome</option>";
                }
                
            }
            
            echo "</select></div>";
        }
        ?>
        </div>
        <?php   
    }
    ?>
     
</div>
<div class="col-md-4">
    <div id="lista_user"></div>
</div>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>