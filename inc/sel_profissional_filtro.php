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
<div class="col-md-3">
 <div class="form-group form-md-floating-label">
    <label class="control-label ">Profissão</label>    
    <?php  
            $sql        = "SELECT id_profissao, nome FROM gui_profissoes
                        WHERE ativo = 'S'";
            $query      = mysql_query($sql);
                            
            if (mysql_num_rows($query)>0)
            {
                echo "<select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_profissao_filtro\" name=\"select_profissao_filtro\" ><option value=\"\"selected=\"\">Não selecionado</option>";       
                
                while($dados = mysql_fetch_array($query))
                {
                    extract($dados);
                    echo "<option value=\"$id_profissao\">$nome</option>";
                }
                echo "</select>";
            }
    ?>
    </div>
</div>
<div class="col-md-3">
 <div class="form-group form-md-floating-label">
    <label class="control-label ">Especialidade</label>    
    <?php  
            $sql        = "SELECT id_especialidade, nome FROM gui_especialidades
                        WHERE ativo = 'S'";
            $query      = mysql_query($sql);
                            
            if (mysql_num_rows($query)>0)
            {
                echo "<select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_especialidade_filtro\" name=\"select_especialidade_filtro\" ><option value=\"\"selected=\"\">Não selecionado</option>";       
                
                while($dados = mysql_fetch_array($query))
                {
                    extract($dados);
                    echo "<option value=\"$id_especialidade\">$nome</option>";
                }
                echo "</select>";
            }
    ?>
    </div>
</div>
<div class="col-md-3">
 <div class="form-group form-md-floating-label">
    <label class="control-label ">Local de Atendimento</label>    
    <?php  
            $sql        = "SELECT id_local_atendimento, nome FROM gui_local_atendimento
                        WHERE tipo IN('CLINICA', 'CONSULTORIO') AND ativo = 'S'";
            $query      = mysql_query($sql);
                            
            if (mysql_num_rows($query)>0)
            {
                echo "<select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_local_atend_filtro\" name=\"select_local_atend_filtro\" ><option value=\"\"selected=\"\">Não selecionado</option>";       
                
                while($dados = mysql_fetch_array($query))
                {
                    extract($dados);
                    echo "<option value=\"$id_local_atendimento\">$nome</option>";
                }
                echo "</select>";
            }
    ?>
    </div>
</div>
<div class="col-md-3">
<div class="form-group form-md-floating-label">
    <label class="control-label ">Cidade</label>    
    <input class="form-control" name="cidade_filtro_profissional" id="cidade_filtro_profissional" placeholder="Digite a cidade..."/>
</div>
</div>



<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>