<?php
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$id_guia_get = (empty($_GET['id_guia'])) ? "" : verifica($_GET['id_guia']);  


$sql        = "SELECT gui.*, pag_gui.obs_pagamento FROM gui_guias gui
	JOIN gui_pagamentos_guias pag_gui ON gui.id_guia = pag_gui.id_guia
	WHERE gui.id_guia = $id_guia_get";
$query      = mysql_query($sql);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);
        
}

?>
<script>
function exibe_add_comentario(verifica){
{
if(verifica == 'true'){
    $('#form_add_comentario').show('fast').focus();
    $('#exibe_bt_add_comentario').hide('fast');
}else{
    $('#form_add_comentario').hide('fast');
    $('#exibe_bt_add_comentario').show('fast');
}
}};

function confirma_add_comentario(){
{
    var id_cliente        = $("#id_guia_hidden").val();
    var comentario        = $("#campo_add_comentario").val();
    $("#bt_enviar_comentario").attr("disabled", true);
    
    if(comentario.length > 2){
        $.ajax({ 
         type: "POST",
         url:  "gui_editar_db.php",
         data: {
            item: 'comentarios_atividades',
            id_cliente: id_cliente,
            comentario: comentario
            },
         success: function(dados){
            data = dados.split('%-%');
            if(data[0] != 1)
            {
                var data_criacao    = data[3];
                var id_criado_por   = data[1];
                var nome_criado_por = data[0];
                var comentario      = data[2];
                $('#tabela_lista_comentarios').append('<tr><td>' + data_criacao + '</td><td>' + id_criado_por + ' - ' + nome_criado_por + '</td><td>' + comentario + '</td></tr>');
                $('#form_add_comentario').hide('fast');
                $('#exibe_bt_add_comentario').show('fast');
                $("#bt_enviar_comentario").removeAttr("disabled");
                $("#campo_add_comentario").val('');
            }else{
                $("#bt_enviar_comentario").removeAttr("disabled");
            }
         } 
        });
    }
    
        
    
//}

}};
</script>
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
    <h4 class="modal-title">GUIA DE ENCAMINHAMENTO <strong>#<?php echo $id_guia; ?></strong></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            
        <?php
                $status_list = array(
                array("info" => "AGENDADO"),
                array("warning" => "ABERTO"),
                array("danger" => "PENDENTE"),
                array("success" => "EMITIDO"),
                array("danger" => "CANCELADO")
                );
                $ativo = $status;
                if($ativo == 'AGENDADO'){
                    $status_ = $status_list[0];
                }elseif($ativo == 'ABERTO'){
                    $status_ = $status_list[1];
                }elseif($ativo == 'PENDENTE'){
                    $status_ = $status_list[2];
                }elseif($ativo == 'EMITIDO'){
                    $status_ = $status_list[3];
                }elseif($ativo == 'CANCELADO'){
                    $status_ = $status_list[4];
                }
                
                $sql   = "SELECT id_paciente, nome, data_nascimento FROM gui_pacientes
                                  WHERE id_paciente = $id_paciente";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 2");
                $nome_paciente  = '-';
                $data_nascimento= '-';
                if (mysql_num_rows($query)>0)
                {
                    $id_paciente        = mysql_result($query, 0, 'id_paciente');
                    $nome_paciente      = mysql_result($query, 0, 'nome');
                    $data_nascimento    = mysql_result($query, 0, 'data_nascimento');
                }
                
                $sql   = "SELECT nome, tipo, endereco, numero, complemento, bairro, cidade, estado FROM gui_local_atendimento
                WHERE id_local_atendimento = $id_local_atendimento AND ativo = 'S'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 3");
                $nome_local_atendimento = '-';
                $tipo_local_atendimento         = '';
                $endereco_local_atendimento     = '';
                $numero_local_atendimento       = '';
                $complemento_local_atendimento  = '';
                $bairro_local_atendimento       = '';
                $cidade_local_atendimento       = '';
                $estado_local_atendimento       = '';
                
                if (mysql_num_rows($query)>0)
                {
                    $nome_local_atendimento         = mysql_result($query, 0, 'nome');
                    $tipo_local_atendimento         = mysql_result($query, 0, 'tipo');
                    $endereco_local_atendimento     = mysql_result($query, 0, 'endereco');
                    $numero_local_atendimento       = mysql_result($query, 0, 'numero');
                    $complemento_local_atendimento  = mysql_result($query, 0, 'complemento');
                    $bairro_local_atendimento       = mysql_result($query, 0, 'bairro');
                    $cidade_local_atendimento       = mysql_result($query, 0, 'cidade');
                    $estado_local_atendimento       = mysql_result($query, 0, 'estado');
                }
                
                $sql   = "SELECT nome FROM parceiros
                                    WHERE id_parceiro = $id_parceiro AND del = 'N'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 4");
                $nome_parceiro = 'Sem Parceiro';
                if (mysql_num_rows($query)>0)
                {
                    $nome_parceiro = mysql_result($query, 0, 'nome');
                }
                
                $sql   = "SELECT nome FROM usuarios
                                    WHERE id_usuario = $id_usuario AND del = 'N'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 5");
                $nome_user_cadastro = 'Sem Usuario';
                if (mysql_num_rows($query)>0)
                {
                    $nome_user_cadastro = mysql_result($query, 0, 'nome');
                }
                
                $sql   = "SELECT nome FROM gui_convenios
                        WHERE id_convenio = $id_convenio AND ativo = 'S'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 6");
                $nome_convenio = 'Sem Convenio';
                if (mysql_num_rows($query)>0)
                {
                    $nome_convenio = mysql_result($query, 0, 'nome');
                }
                
                $sql   = "SELECT nome, tratamento FROM gui_profissionais
                                  WHERE id_profissional = $id_profissional";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 2");
                $nome_profissonal        = '-';
                $tratamento_profissional = '-';
                if (mysql_num_rows($query)>0)
                {
                    $nome_profissonal           = mysql_result($query, 0, 'nome');
                    $tratamento_profissional    = mysql_result($query, 0, 'tratamento');
                }
            ?>
                <input type="hidden" name="id_guia_hidden" id="id_guia_hidden" value="<?php echo $id_guia_get; ?>" />
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Parceiro:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_parceiro; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Usuário:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_user_cadastro; ?> </p>
                            
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <hr />
                <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Agendado para:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                            $diasemana_numero = date('w', strtotime($data_agendamento));
                            echo $diasemana[$diasemana_numero].", dia ".converte_data($data_agendamento).", as ".$hora_agendamento;
                             ?> <br /><br /><span class="label label-sm label-<?php echo key($status_); ?>"><?php echo $ativo; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Cadastro:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo converte_data($data_cadastro) ?></p>
                        </div>
                    </div>
                </div>
                <?php
                if(!empty($data_emissao)){
                    echo "<div class=\"col-md-4\">
                    <div class=\"form-group\">
                        <label class=\"control-label col-md-12\"><strong>Data emissão:</strong></label>
                        <div class=\"col-md-12\">
                            <p class=\"form-control-static\">".converte_data($data_emissao)."</p>
                        </div>
                    </div>
                </div>";
                }
                ?>
                
            </div>
            <hr />
                <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>ID:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $id_paciente; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Paciente:</strong></label>
                        <div class="col-md-9">
                            <p class="form-control-static"> <strong><?php echo $nome_paciente; ?> </strong></p>
                        </div>
                        <div class="col-md-3">
                            <a href="gui_editar.php?item=gui_pacientes&id=<?php echo $id_paciente; ?>" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nasc.:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"><?php echo converte_data($data_nascimento) ?></p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <hr />
                <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Convênio:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_convenio; ?> </p>
                        </div>
                    </div>
                </div>
                
                <!--/span-->
                </div>
                 <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Local de atendimento:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $tipo_local_atendimento." - ".$nome_local_atendimento; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Endereço:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php 
                            echo $endereco_local_atendimento.", ".$numero_local_atendimento." - ".$complemento_local_atendimento.", ".$bairro_local_atendimento.", ".$cidade_local_atendimento." - ".$estado_local_atendimento   ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
           <hr />
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Profissional:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $tratamento_profissional." ".$nome_profissonal; ?> </p>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Observações:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $obs_guia; ?> </p>
                        <p class="form-control-static"> <?php echo $obs_pagamento; ?> </p>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-12">
            <table class="table table-hover table-light">
            <thead>
                <tr class="uppercase">
                    <th> # </th>
                    <th> Descrição </th>
                    <?php
                        if(in_array("42", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <th> Custo </th>
                    <?php
                        }
                    ?>
                    <th> Valor </th>
                </tr>
            </thead>
            <tbody>
                
           
                <?php
                    /*$sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";*/
    $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";
                    $query_procedimentos      = mysql_query($sql_procedimentos);
                                
                if (mysql_num_rows($query_procedimentos)>0)
                {  
                    $soma_preco_custo = 0;
                    $soma_valor_cobrado = 0;
                    while ($dados = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados); 
                        $preco_custo_calc = moeda_db($preco_custo);
                        $valor_cobrado_exibe = db_moeda($valor_cobrado);
                        
                        $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                        $soma_valor_cobrado = $soma_valor_cobrado + $valor_cobrado;
                        
                        echo "<tr>
                                <td> $codigo_procedimnto </td>
                                <td> $nome_procedimento </td>";
                        if(in_array("42", $verifica_lista_permissoes_array_inc)){
                            echo "<td> R$ $preco_custo </td>";
                        }
                                echo "<td> $valor_cobrado_exibe </td>
                            </tr>";
                    }
                }
            ?> 
            </tbody>
        </table>
            </div>
            <div class="col-md-12" style="text-align: right;">
            <?php
                if(in_array("42", $verifica_lista_permissoes_array_inc)){
            ?>
            <div>Total de Custos: <strong><?php echo db_moeda($soma_preco_custo);?></strong></div> 
            <?php
            }?>
            <div>Total: <strong><?php echo db_moeda($soma_valor_cobrado);?></strong></div>
            <?php
            if($desconto > 0){
            ?>
                <div>Desconto de: <strong><?php echo $html_desconto = ($tipo_desconto == 'por') ? $desconto."%" : db_moeda($desconto);?></strong></div>
                <div>Novo valor total cobrado com desconto: <strong><?php echo db_moeda($valor_total_desconto);?></strong></div>

            <?php
            } 
            ?>
            </div>
            </div>
            <hr/>
            <div class="row"> 
            <div class="col-md-12">
                    <div class="panel-group accordion" id="accordion3">
                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_0">COMENTÁRIOS <label class="btn btn-transparent red btn-outline btn-circle btn-sm active">
                                        <?php
                                            $sql    = "SELECT COUNT(*) FROM comentarios_atividades
                                                    WHERE id_referencia = $id_guia_get AND tipo_historico = 'guias_pacientes'";
                                        
                                            $query      = mysql_query($sql, $banco_painel);
                                            $contar_historico = '0';                
                                            if (mysql_num_rows($query)>0)
                                            {
                                                $contar_historico = mysql_result($query, 0,0);
                                                
                                            }
                                            echo $contar_historico;
                                        ?>
                                        </label></a>
                                    </h4>
                                </div>
                                <div id="collapse_3_0" class="panel-collapse collapse">
                                    <div class="portlet-body bg-grey-cararra">
                                        <div class="table-scrollable table-scrollable-borderless">
                                                <table class="table table-hover table-light" id="tabela_lista_comentarios">
                                                    <thead>
                                                        <tr class="uppercase">
                                                            <th width='20%'> Data </th>
                                                            <th width='30%'> Criado por </th>
                                                            <th width='50%'> Descrição </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $sql    = "SELECT * FROM comentarios_atividades
                                                                WHERE id_referencia = $id_guia_get AND tipo_historico = 'guias_pacientes'";
                                                    
                                                        $query      = mysql_query($sql, $banco_painel);
                                                                        
                                                        if (mysql_num_rows($query)>0)
                                                        {
                                                            while($dados_historico = mysql_fetch_array($query)){
                                                                extract($dados_historico);
                                                                
                                                                $sql_user   = "SELECT us.nome'nome_usuario' FROM usuarios us
                                                                            WHERE us.id_usuario = $id_usuario";
                                                                $query_user = mysql_query($sql_user, $banco_painel);
                                                                $nome_usuario = '';                
                                                                if (mysql_num_rows($query_user)>0)
                                                                {
                                                                    $nome_usuario = mysql_result($query_user, 0);    
                                                                }
                                                              
                                                              echo "<tr>
                                                                    <td> ".converte_data($data_alteracao)." </td>
                                                                    <td> $id_usuario - $nome_usuario </td>
                                                                    <td> $descricao </td>
                                                                </tr>";
                                                            }
                                                        }
                                                        
                                                    ?>

                                                    </tbody>
                                                </table>
                                                
                                            </div>  
                                            
                                        <div class="form-horizontal form-bordered" style="display: none;" id="form_add_comentario">
                                            <hr />
                                            <div class="form-body">
                                                <div class="form-group last">
                                                    <div class="col-md-12">
                                                        <textarea class="col-md-12" rows="4" cols="4" name="texto_comentario" style="resize: none;" id="campo_add_comentario"> </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn green" onclick="return confirma_add_comentario();" id="bt_enviar_comentario">
                                                            <i class="fa fa-check"></i> Salvar</button>
                                                        <button type="button" class="btn default" onclick="return exibe_add_comentario('false');">Cancelar</button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-actions" id="exibe_bt_add_comentario">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a href="javascript:;" id="bt_add_comentario" class="btn green" onclick="return exibe_add_comentario('true');"> Adicionar Comentário <i class="fa fa-plus"></i></a>
                                                        <?php
                                                        if(in_array("57", $verifica_lista_permissoes_array_inc)){
                                                            //$nome_primeiro_nome = explode(" ", $nome);
                                                            
                                                            $celular_sms_verificar = substr($celular_sms, 3,1);
                                                            if($celular_sms_verificar == 9 OR $celular_sms_verificar == 8){
                                                                
                                                        ?>
                                                                <span id="bt_enviar_sms_txt"><a data-toggle="modal" href="javascript:" class="btn blue" onclick="return enviar_sms_html('true');" id="btn_enviar_sms_html">
                                                                <i class="fa fa-send"></i> Enviar SMS para contato</a> <span id="div_aguarde_envia_sms" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguardando resposta...</span></span>
                                                                <?php
                                                                $verifica   = "SELECT data_alteracao FROM historicos_atividades 
                                                                            WHERE id_referencia = $id_cliente AND descricao LIKE '%a REALIZA + SAUDE Assistencia Familiar precisa falar com voce%'
                                                                            ORDER BY id_historico DESC
                                                                            LIMIT 0,1";
                                                                $query_verifica    = mysql_query($verifica, $banco_painel);
                                                                     
                                                                if (mysql_num_rows($query_verifica)>0)
                                                                {
                                                                    $data_impressao = mysql_result($query_verifica, 0,0);
                                                                    
                                                                    echo "<span> Data do último SMS enviado: <strong>".converte_data($data_impressao)."</strong></span>";
                                                                }
                                                            }else{
                                                                //echo '<span id="bt_enviar_sms_txt"> Sem celular cadastrado para endio de SMS.';
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    <?php
    if(in_array("35", $verifica_lista_permissoes_array_inc)){
    ?>
    <a href="gui_editar.php?item=gui_guias_detalhes&id=<?php echo $id_guia; ?>&tipo=gui_guias_detalhes" class="btn blue">Detalhes</a>
    <?php
    }
    ?>
    
</div>