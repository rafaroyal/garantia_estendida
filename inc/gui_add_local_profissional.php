<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$nivel_usuario  = base64_decode($_COOKIE["usr_nivel"]);
$usr_parceiro   = base64_decode($_COOKIE["usr_parceiro"]);
$contar_local   = (empty($_GET['contar_local'])) ? "" : verifica($_GET['contar_local']);
$contar_local   = $contar_local + 1;
?>
<div class="row" id="linha_local_<?php echo $contar_local;?>">
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
    <div class="col-md-12">
        <div class="col-md-4">
        <div class="form-group form-md-line-input form-md-floating-label">
            <?php 
            // AND id_usuario <> $id_usuario_sessao
            $where_id_cidade = '';
            if($nivel_usuario != 'A'){
                $sql        = "SELECT id_cidade FROM parceiros
                    WHERE id_parceiro = $usr_parceiro";
                $query      = mysql_query($sql);
                                
                if (mysql_num_rows($query)>0)
                {
                    $id_cidade = mysql_result($query, 0,0); 
                    $where_id_cidade = "g_cid_loc.loc_nu_sequencial = $id_cidade AND";
                }
            }
            
            $sql_local        = "SELECT g_loc_ate.* FROM gui_cidades_locais g_cid_loc
    JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
    WHERE $where_id_cidade g_loc_ate.ativo = 'S'
    GROUP BY g_loc_ate.id_local_atendimento";
            $query_local      = mysql_query($sql_local);
            if (mysql_num_rows($query_local)>0)
            {
                echo "<select class=\"bs-select form-control select_local\" data-show-subtext=\"true\" data-live-search=\"true\" data-size=\"8\" name=\"select_local[]\" ><option data-content=\"&nbsp;\" value=\"\">&nbsp;</option>";
                while ($dados = mysql_fetch_array($query_local))
                {
                    extract($dados); 
                    
                    echo "<option data-content=\"<div class='row'><div class='col-md-8 border_direita_col_local'>$nome</div><!--<div class='col-md-4'>$cidade-$estado</div>--></div>\" value=\"$id_local_atendimento\">$nome</option>";
                     
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
        </div>
        <div class="col-md-6">
        <div id="linha_local_procedimento_<?php echo $contar_local;?>">
            
        </div>
         &nbsp;
        </div>
        <div class="col-md-2 ">
         <div class="form-actions noborder">
         <a href="javascript:" class="btn btn-sm red btn-outline sbold principal_bt_remove_local" data="<?php echo $contar_local; ?>">
            <i class="fa fa-times"></i> Excluir</a>
         </div>
         &nbsp;
        </div>
    </div>
    </div>