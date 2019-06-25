<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$contar_especialidade         = (empty($_GET['contar_especialidade'])) ? "" : verifica($_GET['contar_especialidade']);
$contar_especialidade = $contar_especialidade + 1;
?>
<div class="row" id="linha_especialidade_<?php echo $contar_especialidade;?>">
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
    <div class="col-md-12" >
        <div class="col-md-8">
        <div class="form-group form-md-line-input form-md-floating-label">
            <?php 
            // AND id_usuario <> $id_usuario_sessao
            $sql_especialidade        = "SELECT id_especialidade, nome FROM gui_especialidades
WHERE ativo = 'S'";
            $query_especialidade      = mysql_query($sql_especialidade);
                            
            if(mysql_num_rows($query_especialidade)>0)
            {
                echo "<div class=\"\"><select class=\"bs-select form-control select_especialidade\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" name=\"select_especialidade[]\" ><option value=\"\"><-- Especialidades --></option>";
                
                while($dados_especialidade = mysql_fetch_array($query_especialidade))
                {
                    extract($dados_especialidade);

                    echo "<option value=\"$id_especialidade\">$nome</option>";
                }
                
                echo "</select></div>";
            }
            
             ?>

         </div>
         &nbsp;
        </div>
        <div class="col-md-2">
         <div class="form-group form-md-line-input form-md-floating-label">
            <input type="text" name="rqe[]" class="form-control rqe" value="" maxlength="11" />
            <label for="rqe">RQE</label>
            <span class="help-block">números...</span>
         </div>
         &nbsp;
        </div>
        <div class="col-md-2 ">
         <div class="form-actions noborder">
         <a href="javascript:" class="btn btn-sm red btn-outline sbold principal_bt_remove_especialidade" data="<?php echo $contar_especialidade; ?>">
            <i class="fa fa-times"></i> Excluir</a>
         </div>
         &nbsp;
        </div>
    </div>
    </div>