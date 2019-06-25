<?php
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$id = (empty($_GET['id'])) ? "" : verifica($_GET['id']);  


$sql        = "SELECT * FROM gui_profissionais
            WHERE id_profissional = $id";
$query      = mysql_query($sql);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);   
}

$sql_profissao  = "SELECT nome FROM gui_profissoes
WHERE id_profissao = $id_profissao AND ativo = 'S'";
$query_profissao = mysql_query($sql_profissao, $banco_painel) or die(mysql_error()." - 145");
$nome_profissao = 'Sem Profissão';
if (mysql_num_rows($query_profissao)>0)
{
    $nome_profissao = mysql_result($query_profissao, 0, 'nome');
}

$sql_especialidade  = "SELECT gu_esp.nome'nome_especialidade' FROM gui_especialidades gu_esp
JOIN gui_especialidades_profissional gu_esp_p ON gu_esp.id_especialidade = gu_esp_p.id_especialidade
WHERE gu_esp_p.id_profissional = $id_profissional";
$query_especialidade = mysql_query($sql_especialidade, $banco_painel) or die(mysql_error()." - 145");
$nome_especialidade = '';
if (mysql_num_rows($query_especialidade)>0)
{
    $nome_especialidade_array = array();
    while($dados_especialidade = mysql_fetch_array($query_especialidade))
    {
        extract($dados_especialidade);
        if(!in_array($nome_especialidade, $nome_especialidade_array)){
            $nome_especialidade_array[] = $nome_especialidade;
        }
        
    }
    
    $nome_especialidade = implode(', ', $nome_especialidade_array);
    
    
}
$nome_convenios_array = array();
$nome_convenios = '';
$ids_convenios_array = explode("|", $ids_convenios);

$ids_convenios_array_contar = count($ids_convenios_array) -1;

for($i=0;$ids_convenios_array_contar>=$i;$i++){
    
    $sql_convenios  = "SELECT nome FROM gui_convenios

    WHERE id_convenio = $ids_convenios_array[$i]";
    $query_convenios = mysql_query($sql_convenios, $banco_painel) or die(mysql_error()." - 145");
    if (mysql_num_rows($query_convenios)>0)
    {
        $nome_convenios = mysql_result($query_convenios, 0,0);
        $nome_convenios_array[] = $nome_convenios;
    }
    
    
}
$nome_convenios = implode(', ', $nome_convenios_array);


$sql_local  = "SELECT gu_at.cidade'cidade_local' FROM gui_local_atendimento gu_at
JOIN gui_local_atendimento_profissional gu_at_p ON gu_at.id_local_atendimento = gu_at_p.id_local_atendimento
WHERE gu_at_p.id_profissional = $id_profissional";
$query_local = mysql_query($sql_local, $banco_painel) or die(mysql_error()." - 145");
$cidade_local = '';
if (mysql_num_rows($query_local)>0)
{
    $cidade_local_array = array();
    while($dados_local = mysql_fetch_array($query_local))
    {
        extract($dados_local);
        if(!in_array($cidade_local, $cidade_local_array)){
            $cidade_local_array[] = $cidade_local;
        }
        
    }
    $cidade_local = implode(', ', $cidade_local_array);
    
}


?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $tratamento." ".$nome; ?> <?php echo "($nome_profissao)"; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

        <?php
            $tipopessoa = 'Pessoa Fisica';
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            $status = $status_list[0];
            if($ativo == 'N')
            {
                $status = $status_list[1];
            }
        ?>
            <h4>Dados do profissional <small>(<?php echo '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>' ?>)</small></h4>
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
                    <label class="control-label col-md-12"><strong>Data de Nascimento:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $data_nascimento; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Telefones:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $telefone." | ".$celular; ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>E-mail:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $email; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>

            <h4>Info Profissão</h4>
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Especialidades:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $nome_especialidade; ?> </p>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Conselho:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $conselho; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Registro:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $registro; ?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>

            <h4><?php echo ( $conveniado == "S" ? "Conveniado" : "Não conveniado" ); ?></h4>
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Nome(s):</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $nome_convenios; ?> </p>
                    </div>
                </div>
            </div>
            
            </div>
            
            <h4>Locais de atendimento</h4>
            <div class="row">
            <div class="col-md-12">
                <?php
                $sql_loc_ate        = "SELECT g_loc_ate.nome'nome_local_atendimento' FROM gui_local_atendimento_profissional g_loc_pro
JOIN gui_local_atendimento g_loc_ate ON g_loc_pro.id_local_atendimento = g_loc_ate.id_local_atendimento
WHERE g_loc_pro.id_profissional = $id";
                $query_loc_ate      = mysql_query($sql_loc_ate);
                            
            if (mysql_num_rows($query_loc_ate)>0)
            {
                
                while ($dados = mysql_fetch_array($query_loc_ate))
                {
                    extract($dados); 
                    
                    echo "<span class=\"sel_cid_sel\"> $nome_local_atendimento </span>";
 
                }
               
            }
            ?>
            </div>
            
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    <?php
    if(in_array("19", $verifica_lista_permissoes_array_inc)){
    ?>
    <a href="gui_editar.php?item=gui_profissionais&id=<?php echo $id; ?>" class="btn blue">Editar</a>
    <?php
    }
    ?>
    
</div>