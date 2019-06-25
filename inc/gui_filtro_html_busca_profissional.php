<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');

$id_local   = (empty($_GET['id_local'])) ? "" : verifica($_GET['id_local']);

if($id_local != 'todos'){


?>
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
       
        <div class="form-group"> 
            <?php 

            $sql_local        = "SELECT pro_is.id_profissional, pro_is.nome FROM gui_profissionais pro_is
                                JOIN gui_local_atendimento_profissional loc_at_pro ON pro_is.id_profissional = loc_at_pro.id_profissional
                                WHERE loc_at_pro.id_local_atendimento = $id_local
                                GROUP BY pro_is.id_profissional";
            $query_local      = mysql_query($sql_local);
            if (mysql_num_rows($query_local)>0)
            {
                echo "<label class=\"control-label\">Profissional</label><select class=\"bs-select form-control filtro_busca_profissional\" data-show-subtext=\"true\" data-live-search=\"true\" data-size=\"8\" name=\"filtro_busca_profissional\" id=\"filtro_busca_profissional\" ><option data-content=\"TODOS OS PROFISSIONAIS\" value=\"todos\">TODOS OS PROFISSIONAIS</option>";
                while ($dados = mysql_fetch_array($query_local))
                {
                    extract($dados); 
                    
                    echo "<option data-content=\"<div class='row'><div class='col-md-12'>$nome</div></div>\" value=\"$id_profissional\">$nome</option>";
                     
                }

                echo '</select>';

                /*echo "<div class=\"\"><select class=\"bs-select form-control select_local\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" name=\"select_local[]\" ><option value=\"\"><-- locals --></option>";
                
                while($dados_local = mysql_fetch_array($query_local))
                {
                    extract($dados_local);
                    echo "<option value=\"$id_local_atendimento\">$nome</option>";
                }
                
                echo "</select></div>";*/
            }
            
             ?>

         </div>
         &nbsp;
<?php
}
?>