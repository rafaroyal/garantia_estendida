<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script>
function retorno_click_nome_paciente(id_cliente, nome, data_nascimento, nome_convenio, id_convenio){
{

    $("#id_paciente").val(id_cliente).focus();
    $("#gui_nome_paciente").val(nome).focus();
    $("#data_nascimento").val(data_nascimento).focus();
    $("#html_tipo_convenio_paciente").html('Convênio do paciente: ' + nome_convenio);
    $("#id_convenio_paciente_sel").val(id_convenio);
    $("#resultado_campo_gui_nome_paciente").html('');
    //$("html, body").animate({ scrollTop: $('#portlet_procedimentos').offset().top }, 1000);
    $("#portlet_procedimentos").show();
    $("html, body").animate({ scrollTop: $('#portlet_procedimentos').offset().top }, 1000);
    $("#bt_avancar_passo3_guia").hide();
    
    
}};

</script>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$busca              = (empty($_POST['gui_nome_paciente'])) ? "" : verifica($_POST['gui_nome_paciente']); 
$id_paciente_get    = (empty($_POST['id_paciente'])) ? "" : verifica($_POST['id_paciente']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);

?>

                    
                    <?php
                        $where_id_parceiro = '';
                        $verifica = false;
                        
                        if(strlen($id_paciente_get) >= 3){
                            $verifica = true;
                            $where_busca = "AND id_paciente = $id_paciente_get";
                        }
                        
                        if(strlen($busca) >= 3){
                            $verifica = true;
                            $where_busca = "AND nome LIKE '%$busca%'";
                        }
                       
                    if($verifica == true){

                        $sql        = "SELECT * FROM gui_pacientes
                                    WHERE ativo = 'S' $where_id_parceiro $where_busca
                                    ";
                        $query      = mysql_query($sql, $banco_painel);
                                        
                        if (mysql_num_rows($query)>0)
                        {
                            echo "<div class=\"portlet box green\">
        <div class=\"portlet-title\">
            <div class=\"caption\">
                <i class=\"fa fa-user\"></i>Selecione o cliente!</div>
        </div>
        <div class=\"portlet-body\">
            <div class=\"table-scrollable\" style=\"height: 300px;overflow-y: visible;\">
                <table class=\"table table-hover table-light\">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nome </th>
                            <th> Nascimento </th>
                            <th> Sexo </th>
                            <th> Cidade </th>
                            <th> Convênio </th>
                        </tr>
                    </thead>
                    <tbody>";
                            
                            while ($dados = mysql_fetch_array($query))
                            {
                                extract($dados); 
                                
                                $sql_conv        = "SELECT nome FROM gui_convenios
                                    WHERE ativo = 'S' AND id_convenio = $id_convenio
                                    ";
                                $query_conv      = mysql_query($sql_conv, $banco_painel);
                                $nome_convenio = 'Sem Convênio';                
                                if (mysql_num_rows($query_conv)>0)
                                {
                                    $nome_convenio = mysql_result($query_conv, 0, 'nome');
                                }
                                
                                
                                $data_nascimento = converte_data($data_nascimento);
                                $idade = calcula_idade($data_nascimento);
                                echo "<tr>
                                <td> $id_paciente</td>
                                <td> <a href=\"javascript:\" onclick=\"return retorno_click_nome_paciente('$id_paciente', '$nome', '$data_nascimento', '$nome_convenio', '$id_convenio')\" id=\"retorno_click_nome_paciente\">$nome</a></td>
                                <td> $data_nascimento <br/>($idade anos)</td>
                                <td style=\"font-size: 12px;\">$sexo</td>
                                <td>$cidade</td>
                                <td>$nome_convenio</td>
                                </tr>"; 
                            }
                            
                            echo "</tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>";
                        }else{
                        ?>
                        
                        <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-user"></i>Cadastro rápido!</div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-scrollable">
                                <table class="table table-hover table-light">
                                    <thead>
                                        <tr>
                                            <th width="1%"> # </th>
                                            <th width="30%"> PACIENTE </th>
                                            <th width="10%"> NASCIMENTO </th>
                                            <th width="25%"> SEXO </th>
                                            <th width="25%"> CIDADE </th>
                                            <th width="5%"> AÇÃO </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><input type="text" class="form-control input-sm" id="add_paciente" name="add_paciente" value="<?php echo $busca;?>" /></td>
                                        <td><input type="text" class="form-control input-sm data_nasc" id="add_nascimento" name="add_nascimento" /></td>
                                        <td><select class="form-control input-sm" id="add_sexo" name="add_sexo">
                                            <option value=""></option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                        </select></td>
                                        <td><input type="text" class="form-control input-sm" id="add_cidade" name="add_cidade" /></td>
                                        <td><a href="javascript:" id="bt_add_paciente_rapido" class="btn btn-sm green"> Salvar</a></td>
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
                        
                    