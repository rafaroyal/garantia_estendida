<?php
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$id_local_atendimento = (empty($_GET['id_local_atendimento'])) ? "" : verifica($_GET['id_local_atendimento']);  


$sql        = "SELECT * FROM gui_local_atendimento
            WHERE id_local_atendimento = $id_local_atendimento";
$query      = mysql_query($sql);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);
        
}

?>
<style>
.sel_cid_sel{
    margin: 5px 10px;
    background: #eee;
    padding: 6px 10px;
    float: left;
    position: relative;
}
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $id_local_atendimento." - ".$nome; ?> <?php echo "($tipo)"; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            
        <?php
        
                
                $status_list = array(
                array("success" => "Ativo"),
                array("danger" => "Inativo")
                );
                if($ativo == 'S')
                {
                    $status = $status_list[0];
                }
                else
                {
                    $status = $status_list[1];
                }
            ?>
                <h4>Dados</h4>
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
                        <label class="control-label col-md-12"><strong>Conveniado?</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php $sel_conveniado = ($conveniado == 'S') ? 'SIM' : 'NÃO';  echo $sel_conveniado; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>E-mail:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $email; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>CNES:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $cnes; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
            <?php
            
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
                    <label class="control-label col-md-12"><strong>Telefone Comercial:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $telefone; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Telefone Alternativo:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $telefone_alt; ?></p>
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
                        <p class="form-control-static"> <?php echo $celular; ?> </p>
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
                        <p class="form-control-static"> <?php echo converte_data($data_cadastro); ?> </p>
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
            <h4>Cidades de Atendimento</h4>
            
            <?php
                $sql_par_cidade        = "SELECT cid_loc.loc_nosub FROM gui_cidades_locais g_cid_loc
JOIN log_localidade cid_loc ON g_cid_loc.loc_nu_sequencial = cid_loc.loc_nu_sequencial
WHERE g_cid_loc.id_local_atendimento = $id_local_atendimento";
                $query_par_cidade      = mysql_query($sql_par_cidade);
                            
            if (mysql_num_rows($query_par_cidade)>0)
            {
                echo "<div class=\"row\">
                        <div class=\"col-md-12\">";
                while ($dados = mysql_fetch_array($query_par_cidade))
                {
                    extract($dados); 
                    
                    echo "
                            <span class=\"sel_cid_sel\"> $loc_nosub </span>";
 
                }
                
                echo "</div>
                        </div>";
                
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
    <?php
    if(in_array("8", $verifica_lista_permissoes_array_inc)){
    ?>
    <a href="gui_editar.php?item=gui_local_atendimento&id=<?php echo $id_local_atendimento; ?>&tipo=local_atendimento" class="btn blue">Editar</a>
    <?php
    }
    ?>
    
</div>