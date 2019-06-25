
<script>
function retorna_resultado_campos(id_cliente){
{
    var id_cliente_importado = $("#id_cliente_importado_" + id_cliente).val();
    var nome_importado       = $("#nome_importado_" + id_cliente).val();
    var dt_nasc_importado    = $("#dt_nasc_importado_" + id_cliente).val();
    var cpf_importado        = $("#cpf_importado_" + id_cliente).val();
    var sexo_importado       = $("#sexo_importado_" + id_cliente).val();
    var telefone_importado   = $("#telefone_importado_" + id_cliente).val();
    var celular_importado    = $("#celular_importado_" + id_cliente).val();
    var cep_importado        = $("#cep_importado_" + id_cliente).val();
    var numero_importado     = $("#numero_importado_" + id_cliente).val();
    var complemento_importado= $("#complemento_importado_" + id_cliente).val();
    var id_convenio          = $("#id_convenio_" + id_cliente).val();
    
    $("#id_cliente").val(id_cliente_importado);
    $("#nome").val(nome_importado).focus().attr('readonly', 'true');
    $("#data_nasc").val(dt_nasc_importado).focus().attr('readonly', 'true');
    $("#cpf_paciente").val(cpf_importado).focus().attr('readonly', 'true');
    $('#convenio_paciente').val(id_convenio);
    $('.lista_id_imput_convenio').removeClass('exibe_convenio');
    $('.id_convenio_' + id_convenio).addClass('exibe_convenio');
    

    if(sexo_importado == 'H'){
        sexo_importado = 'M';
    }else{
        sexo_importado = 'F';
    }
    $("#sexo").val(sexo_importado).focus();
    
    $("#telefone").val(telefone_importado).focus();
    $("#celular").val(celular_importado).focus();
    $("#cep").val(cep_importado).keyup().focus();
    $("#numero").val(numero_importado).focus();
    $("#complemento").val(complemento_importado).focus(); 

    $('#ajax').modal('hide');
    $("#bt_cancela_importacao").show();
    
}};

</script>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$id_servico     = (empty($_POST['sel_servico'])) ? "" : verifica($_POST['sel_servico']); 
$busca          = (empty($_POST['nome'])) ? "" : verifica($_POST['nome']); 
$dt_nasc        = (empty($_POST['data_nasc'])) ? "" : verifica($_POST['data_nasc']); 
$cliente_chave  = (empty($_POST['cliente_chave'])) ? "" : verifica($_POST['cliente_chave']); 
$cliente_cpf    = (empty($_POST['cliente_cpf'])) ? "" : verifica($_POST['cliente_cpf']); 
$sel_dependente = (empty($_POST['sel_dependente'])) ? "" : verifica($_POST['sel_dependente']); 

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);


            $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
            JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
            JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
            JOIN servicos serv ON pro.id_servico = serv.id_servico
                                    WHERE serv.id_servico = $id_servico
            GROUP BY serv.id_servico ";

            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()."$sql_base");

            if (mysql_num_rows($query_base)>0)
            {
                $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                $banco_user             = mysql_result($query_base, 0, 'banco_user');
                $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                $banco_host             = mysql_result($query_base, 0, 'banco_host');
                $slug                   = mysql_result($query_base, 0, 'slug');
                $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                
                $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
            }
            
            
            $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);  
?>
<hr />
<h4>Resultado da pesquisa!</h4>
<!--<div class="portlet box green">-->
                                        <!--<div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-comments"></i>Lista de cliente(s)</div>
                                        </div>-->
                                        <div class="portlet-body">
                                            <div class="table-scrollable">
                                                <table class="table table-condensed table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th> # </th>
                                                            <th> Nome </th>
                                                            <th> Nascimento </th>
                                                            <th> CPF </th>
                                                            <th> Ação </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    
                                                    <?php
                                                    if($slug == 'europ'){
                                                        
                                                        $where_id_parceiro = '';
                                                        $where_busca = '';
                                                        $verifica = true;
                                                        
                                                        if($nivel_usuario != 'A'){
                                                            if(empty($dt_nasc)){
                                                                $verifica = false;
                                                            }else{
                                                                $where_busca = "AND nome LIKE '%$busca%' AND data_nascimento = '".converte_data($dt_nasc)."' "; 
                                                                $where_busca_dependente = "dep_cli.nome LIKE '%$busca%' AND dep_cli.data_nascimento = '".converte_data($dt_nasc)."' ";
                                                            }
                                                        
                                                    }else{
                                                        if(!empty($dt_nasc)){
                                                            $where_busca = "AND nome LIKE '%$busca%' AND data_nascimento = '".converte_data($dt_nasc)."' "; 
                                                            $where_busca_dependente = "dep_cli.nome LIKE '%$busca%' AND dep_cli.data_nascimento = '".converte_data($dt_nasc)."' ";
                                                        }else{
                                                            $where_busca = "AND nome LIKE '%$busca%'"; 
                                                            $where_busca_dependente = "dep_cli.nome LIKE '%$busca%'";
                                                        }

                                                    }
                                                    
                                                    if(!empty($cliente_chave) AND strlen($cliente_chave) == 20){
                                                        $where_busca = "AND chave = '$cliente_chave'";
                                                        $verifica = true;
                                                    }
                                                    
                                                    $cliente_cpf = limpa_caracteres($cliente_cpf);
                                                    if(!empty($cliente_cpf) AND strlen($cliente_cpf) == 11){
                                                        $where_busca = "AND cpf = '$cliente_cpf'";
                                                        $verifica = true;
                                                    }
                                                    
                                                    
                                                    
                                                    if($verifica == true){
                                                        
                                                        if($sel_dependente == 1){
                                                            $sql        = "SELECT c.*, dep_cli.id_dependente'id_dependente_cliente', dep_cli.tipo_dependente, dep_cli.nome'nome_dependente', dep_cli.data_nascimento'data_nasc_dependente' FROM dependentes_clientes dep_cli
                                                            JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                                                            JOIN clientes c ON v.id_cliente = c.id_cliente
                                                            WHERE dep_cli.status = 0 AND v.dependente = 'N' AND ($where_busca_dependente)
                                                                GROUP BY c.chave
                                                                ORDER BY c.id_cliente DESC";
                                                        }else{
                                                            $sql        = "SELECT * FROM clientes
                                                            WHERE status <> 1 $where_id_parceiro $where_busca
                                                            GROUP BY chave
                                                            ORDER BY id_cliente DESC";
                                                        }

                                                        
                                                        $query      = mysql_query($sql, $banco_produto);
                                                                        
                                                        if (mysql_num_rows($query)>0)
                                                        {
                                                            while ($dados = mysql_fetch_array($query))
                                                            {
                                                                extract($dados); 
                                                                $data_nascimento = converte_data($data_nascimento);
                                                                $idade = calcula_idade($data_nascimento);
                                                                $id_convenio_paciente = 5;
                                                                $status_list = array(
                                                                    array("success" => "Ativo"),
                                                                    array("danger" => "Inativo")
                                                                );
                                                                $agora 			= date("Y-m-d");
                                                                $vencido = '';
                                                                if((strtotime($data_termino) < strtotime($agora) AND $data_termino <> '0000-00-00') AND $tipo_movimento <> 'EX'){
                                                                    $vencido = '<span class="label label-sm label-warning">V</span>';
                                                                }
                                                                
                                                                $data_verif = somar_datas( 1, 'm'); // adiciona meses a sua data          
                                                                $data_restante = date('d/m/Y', strtotime($data_verif));
                                                                $data_restante = converte_data($data_restante);
                                                                if(strtotime($data_termino) <= strtotime($data_restante) AND $data_termino != '0000-00-00'){
                                                                    $vencido = '<span class="label label-sm label-warning">V</span>';
                                                                
                                                                }
                                                                
                                                                
                                                                
                                                                if((strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0))
                                                                {
                                                                    $status = $status_list[0];
                                                                    $id_convenio_paciente = 6;
                                                                }
                                                                elseif(strtotime($data_termino) == strtotime($agora) AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0))
                                                                {
                                                                    $status = $status_list[0];
                                                                    $id_convenio_paciente = 6;
                                                                }
                                                                else
                                                                {
                                                                    $status = $status_list[1];
                                                                }
                                                                                        
                                                                $depen = '';
                                                                if($id_cliente_principal > 0)
                                                                {
                                                                    $depen = '<span class="label label-sm label-info">A</span>';
                                                                } 
                                                                
                                                                $penden = '';
                                                                if($status_cliente == 3)
                                                                {
                                                                    $penden = '<span class="label label-sm label-danger">P</span>';
                                                                }  
                                                                
                                                                
                                                                if($status_cliente == 5)
                                                                {
                                                                    $vencido = '<span class="label label-sm label-warning">V</span>';
                                                                    $status = $status_list[0];
                                                                    $id_convenio_paciente = 6;
                                                                }
                                                                
                                                                
                                                                 if($sel_dependente == 1){
                                                                    $data_nasc_dependente = converte_data($data_nasc_dependente);
                                                                    $idade_dependente = calcula_idade($data_nasc_dependente);
                                                                    echo "<tr>
                                                                <td> $id_cliente </td>
                                                                <td> $nome_dependente<br/>Títular:<small> $nome</small> </td>
                                                                <td > $data_nasc_dependente <br/>($idade_dependente anos)</td>
                                                                <td style=\"font-size: 12px;\">&nbsp;</td>
                                                                <td>
                                                                    <input type=\"hidden\" id=\"id_convenio_$id_cliente\" value=\"$id_convenio_paciente\" />
                                                                    
                                                                    <input type=\"hidden\" id=\"id_cliente_importado_$id_cliente\" value=\"$id_cliente\" />
                                                                    <input type=\"hidden\" id=\"nome_importado_$id_cliente\" value=\"$nome_dependente\" />
                                                                    <input type=\"hidden\" id=\"dt_nasc_importado_$id_cliente\" value=\"$data_nasc_dependente\" />
                                                                    <input type=\"hidden\" id=\"cpf_importado_$id_cliente\" value=\"\" />
                                                                    <input type=\"hidden\" id=\"sexo_importado_$id_cliente\" value=\"\" />
                                                                    <input type=\"hidden\" id=\"telefone_importado_$id_cliente\" value=\"$telefone\" />
                                                                    <input type=\"hidden\" id=\"celular_importado_$id_cliente\" value=\"$celular\" />
                                                                    <input type=\"hidden\" id=\"cep_importado_$id_cliente\" value=\"$cep\" />   
                                                                    <input type=\"hidden\" id=\"numero_importado_$id_cliente\" value=\"$numero\" />   
                                                                    <input type=\"hidden\" id=\"complemento_importado_$id_cliente\" value=\"$complemento\" />   
                                                                    <a href='javascript:' onclick=\"return retorna_resultado_campos($id_cliente);\" class=\"btn purple btn-sm\"> Importar </a> <p><span class=\"label label-sm label-".(key($status))."\">($tipo_movimento) ".(current($status))."</span>$depen $penden $vencido</p>
                                                                </td>
                                                                </tr>"; 
                                                                 }else{

                                                                echo "<tr>
                                                                <td> $id_cliente </td>
                                                                <td> $nome_dependente <br/> $nome </td>
                                                                <td > $data_nascimento <br/>($idade anos)</td>
                                                                <td style=\"font-size: 12px;\">".mask_total($cpf, '###.###.###-##')."</td>
                                                                <td>
                                                                    <input type=\"hidden\" id=\"id_convenio_$id_cliente\" value=\"$id_convenio_paciente\" />
                                                                    
                                                                    <input type=\"hidden\" id=\"id_cliente_importado_$id_cliente\" value=\"$id_cliente\" />
                                                                    <input type=\"hidden\" id=\"nome_importado_$id_cliente\" value=\"$nome\" />
                                                                    <input type=\"hidden\" id=\"dt_nasc_importado_$id_cliente\" value=\"$data_nascimento\" />
                                                                    <input type=\"hidden\" id=\"cpf_importado_$id_cliente\" value=\"$cpf\" />
                                                                    <input type=\"hidden\" id=\"sexo_importado_$id_cliente\" value=\"$sexo\" />
                                                                    <input type=\"hidden\" id=\"telefone_importado_$id_cliente\" value=\"$telefone\" />
                                                                    <input type=\"hidden\" id=\"celular_importado_$id_cliente\" value=\"$celular\" />
                                                                    <input type=\"hidden\" id=\"cep_importado_$id_cliente\" value=\"$cep\" />   
                                                                    <input type=\"hidden\" id=\"numero_importado_$id_cliente\" value=\"$numero\" />   
                                                                    <input type=\"hidden\" id=\"complemento_importado_$id_cliente\" value=\"$complemento\" />   
                                                                    <a href='javascript:' onclick=\"return retorna_resultado_campos($id_cliente);\" class=\"btn purple btn-sm\"> Importar </a> <p><span class=\"label label-sm label-".(key($status))."\">($tipo_movimento) ".(current($status))."</span>$depen $penden $vencido</p>
                                                                </td>
                                                                </tr>"; 
                                                                }
                                                            }
                                                        }
                                                        }else{
                                                            echo "Sem resultado, faça outra pesquisa!";
                                                        }
                                                    }elseif($slug == 'sorteio_ead'){
                                                        
                                                        
                                                    }

                                                    ?>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <!--</div>-->