<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script>
function retorno_click_nome_profissional(id_profissional, registro, nome){
{

    $("#registro_profissional").val(registro).focus();
    $("#nome_profissional").val(nome).focus();
    $("#id_profissional_sel").val(id_profissional);
    $("#resultado_campo_gui_nome_profissional").html('');
    //$("html, body").animate({ scrollTop: $('#portlet_procedimentos').offset().top }, 1000);
    
}};

</script>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$busca                      = (empty($_POST['gui_nome_profissional'])) ? "" : verifica($_POST['gui_nome_profissional']); 
$registro                   = (empty($_POST['registro_profissional'])) ? "" : verifica($_POST['registro_profissional']);
$sel_id_local_atendimento   = (empty($_POST['sel_id_local_atendimento'])) ? "" : verifica($_POST['sel_id_local_atendimento']); 
$verifica_grupo_procedimento= (empty($_POST['verifica_grupo_procedimento'])) ? "" : verifica($_POST['verifica_grupo_procedimento']); 

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);

                        $where_id_parceiro = '';
                        $verifica = false;
                        if(strlen($registro) >= 3){
                            $verifica = true;
                            $where_busca = "AND registro = $registro";
                        }
                        
                        if(strlen($busca) >= 3){
                            $verifica = true;
                            $where_busca = "AND nome LIKE '%$busca%'";
                        }
                        
                        $where_local_atendimento = '';
                        if($verifica_grupo_procedimento == 1){
                            $where_local_atendimento = "AND alpro.id_local_atendimento = '$sel_id_local_atendimento'";
                        }
                       
                    if($verifica == true){

                        $sql        = "SELECT prof.* FROM gui_profissionais prof
                                    JOIN gui_local_atendimento_profissional alpro ON prof.id_profissional = alpro.id_profissional
                                    WHERE prof.ativo = 'S' $where_id_parceiro $where_busca $where_local_atendimento
                                    GROUP BY prof.id_profissional";
                        $query      = mysql_query($sql, $banco_painel);
                                        
                        if (mysql_num_rows($query)>0)
                        {
                            echo "<div class=\"portlet box green\">
        <div class=\"portlet-title\">
            <div class=\"caption\">
                <i class=\"fa fa-user\"></i>Selecione o profissional!</div>
        </div>
        <div class=\"portlet-body\">
            <div class=\"table-scrollable\" style=\"height: 300px;overflow-y: visible;\">
                <table class=\"table table-hover table-light\">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Conselho </th>
                            <th> Registro </th>
                            <th> Tratamento </th>
                            <th> Nome </th>
                        </tr>
                    </thead>
                    <tbody>";
                            
                            while ($dados = mysql_fetch_array($query))
                            {
                                extract($dados); 
                                
                                echo "<tr>
                                <td> $id_profissional</td>
                                <td> $conselho</td>
                                <td> $registro</td>
                                <td> $tratamento</td>
                                <td> <a href=\"javascript:\" onclick=\"return retorno_click_nome_profissional('$id_profissional', '$registro', '$nome')\" id=\"retorno_click_nome_profissional\">$nome</a></td>
                                </tr>"; 
                            }
                            
                            echo "</tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>";
                        }else{
                        ?>
                       
                        <div class="portlet box green" id="html_add_campo_profissional_rapido">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-user"></i>Cadastro rápido do profissional!</div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-scrollable">
                                <table class="table table-hover table-light">
                                    <thead>
                                        <tr>
                                            <th width="1%"> # </th>
                                            <th width="10%"> CONSELHO </th>
                                            <th width="15%"> REGISTRO </th>
                                            <th width="10%"> TRATAMENTO </th>
                                            <th width="45%"> NOME </th>
                                            <th width="5%"> AÇÃO </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><select class="form-control" data-size="8" id="conselho" name="conselho" >
                                            <option value=""></option>
                                            <option value="CRAS">CRAS</option>
                                            <option value="CRBM">CRBM</option>
                                            <option value="CREFITO">CREFITO</option>
                                            <option value="COREM">COREM</option>
                                            <option value="CRF">CRF</option>
                                            <option value="CRFA">CRFA</option>
                                            <option value="CRM">CRM</option>
                                            <option value="CRN">CRN</option>
                                            <option value="CRO">CRO</option>
                                            <option value="CRP">CRP</option>
                                            <option value="CRT">CRT</option>
                                            <option value="CRNT">CRNT</option>
                                        </select></td>
                                        <td><input type="text" class="form-control registro_profissional" id="add_registro_profissional" name="add_registro_profissional" /></td>
                                        <td> <select class="form-control" data-size="8" id="tratamento_profissional" name="tratamento_profissional" >
                                            <option value=""></option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Dra.">Dra.</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="Sra.">Sra.</option>
                                        </select></td>
                                        <td><input type="text" class="form-control input-sm" id="add_nome_profissional" name="add_nome_profissional" value="<?php echo $busca;?>" /></td>
                                        <td><a href="javascript:" id="bt_add_profissional_rapido" class="btn btn-sm green"> Salvar</a></td>
                                    </tr>
                                    </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                        <?php
                        }
                        }
                    ?>
                        
                    