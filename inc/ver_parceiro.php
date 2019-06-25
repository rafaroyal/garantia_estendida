<?php
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$id_parceiro = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);  


$sql        = "SELECT * FROM parceiros
            WHERE id_parceiro = $id_parceiro";
$query      = mysql_query($sql);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);
        
}

$sql        = "SELECT gp.nome'nome_grupo' FROM grupos_parceiros gp
JOIN parceiros_grupos pg ON gp.id_grupo_parceiro = pg.id_grupo_parceiro
            WHERE id_parceiro = $id_parceiro";
$query      = mysql_query($sql);
$nome_grupo = '';                
if (mysql_num_rows($query)>0)
{
    $nome_grupo = mysql_result($query, 0);    
}


?>

<script>

function lista_filial(){
{   
    $('#lista_filiais').show();
}};

function remove_lista_filial(){
{   
    $('#lista_filiais').hide();
}};
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $nome; ?> <?php echo "($nome_grupo)"; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div id="lista_filiais" style="display: none;">
            <h4>Filiais do Parceiro  <button class="btn btn-sm red btn-outline filter-cancel" type="button" onclick="return remove_lista_filial();"><i class="fa fa-times"></i> Fechar</button></h4>
            
                <div class="portlet-body">
                    <div class="table-scrollable">
                        
                            <?php
                                $sql_filiais        = "SELECT id_filial, nome'nome_filial', cidade'cidade_filial', estado'estado_filial', del'del_filial' FROM filiais
                                            WHERE id_parceiro = $id_parceiro";
                                $query_filiais      = mysql_query($sql_filiais);
                                                
                                if (mysql_num_rows($query_filiais)>0)
                                {
                                    echo "<table class=\"table table-hover\">
                                            <thead>
                                                <tr>
                                                    <th> ID </th>
                                                    <th> Nome </th>
                                                    <th> Cidade/UF </th>
                                                    <th> Status </th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                                    while($dados_filiais = mysql_fetch_array($query_filiais))
                                    {
                                        extract($dados_filiais);
                                        $status_list = array(
                                        array("success" => "Ativo"),
                                        array("danger" => "Inativo")
                                        );
                                         if($del == 'N')
                                        {
                                            $status = $status_list[0];
                                        }
                                        else
                                        {
                                            $status = $status_list[1];
                                        }
                                        
                                        echo "<tr>
                                            <td> $id_filial </td>
                                            <td> $nome_filial </td>
                                            <td> $cidade_filial/$estado_filial </td>
                                            <td>";
                                       echo '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                                       echo "</td>
                                        </tr>";
                                
                                        
                                    }
                                    
                                     echo "</tbody>
                                </table>";
                                }
                                else
                                {
                                    echo "Sem filiais cadastradas!";
                                }
                                
                               
                            
                            ?>

                    </div>
                </div>
                <hr />
            </div>
        <?php
        if($tipo == 'PF')
        {
            $tipopessoa = 'Pessoa Fisica';
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
             if($del == 'N')
            {
                $status = $status_list[0];
            }
            else
            {
                $status = $status_list[1];
            }
        ?>
            <h4>Dados pessoais <small>(<?php echo $tipopessoa; ?>)</small></h4>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Nome:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $nome; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>CPF:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $cpf; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>RG:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $rg; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            
            <?php
            }
            else
            {
                $tipopessoa = 'Pessoa Jurídica';
                $status_list = array(
                array("success" => "Ativo"),
                array("danger" => "Inativo")
                );
                 if($del == 'N')
                {
                    $status = $status_list[0];
                }
                else
                {
                    $status = $status_list[1];
                }
            ?>
                <h4>Dados da empresa <small>(<?php echo $tipopessoa; ?>)</small></h4>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>CNPJ:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $cnpj; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Razão Social:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $razao_social; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Fantasia:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_fantasia; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
            <?php
            }
            ?>
            <h4>Endereço/Contato</small></h4>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>CEP:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $cep; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Endereço:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $endereco; ?>, N° <?php echo $numero;?>, Bairro <?php echo $bairro;?><?php $complemento = (!empty($complemento)) ? ', '.$complemento : ''; echo $complemento;?>,  <?php echo $cidade;?> - <?php echo $estado;?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Telefone Residencial:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $tel_res; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Telefone Comercial:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $tel_com; ?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Telefone Celular:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $tel_cel; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>E-mail:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $email; ?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Ramo Atividade:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $ramo_atividade; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Modalidade:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $modalidade; ?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Data cadastro:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo converte_data($dt_cadastro); ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Status:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>' ?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <h4>Serviços / Produtos</small></h4>
            
            <?php
                $sql_par_produto        = "SELECT se.nome'nome_servico', pro.nome'nome_produto', ps.preco_custo, ps.preco_venda FROM parceiros_servicos ps
JOIN produtos pro ON ps.id_produto = pro.id_produto
JOIN servicos se ON pro.id_servico = se.id_servico
WHERE ps.id_parceiro = $id_parceiro AND pro.ativo = 'S' AND se.ativo = 'S'";
                $query_par_produto      = mysql_query($sql_par_produto);
                            
            if (mysql_num_rows($query_par_produto)>0)
            {
            
                while ($dados = mysql_fetch_array($query_par_produto))
                {
                    extract($dados); 
                    
                    echo "<div class=\"row\">
                        <div class=\"col-md-6\">
                            <div class=\"form-group\">
                                <label class=\"control-label col-md-12\"><strong>Nome do Serviço:</strong></label>
                                <div class=\"col-md-12\">
                                    <p class=\"form-control-static\"> $nome_servico </p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class=\"col-md-6\">
                            <div class=\"form-group\">
                                <label class=\"control-label col-md-12\"><strong>Nome do Produto:</strong></label>
                                <div class=\"col-md-12\">
                                    <p class=\"form-control-static\"> $nome_produto <br/> Preço Custo: $preco_custo <br/> Preço Venda: $preco_venda </p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        </div>";
 
                }
                
            }
            ?>
                       
        <?php                
   
        ?>                                
                    
            <p></p>
            
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    <button type="button" class="btn green default" onclick="return lista_filial();"><i class="fa fa-users"></i> Filiais</button>
    <?php
    if(in_array("2", $verifica_lista_permissoes_array_inc)){
    ?>
    <a href="editar.php?item=parceiros&id=<?php echo $id_parceiro; ?>&tipo=parceiro" class="btn blue">Editar</a>
    <?php
    }
    ?>
    
</div>