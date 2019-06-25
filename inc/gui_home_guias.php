<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */



?>

<div class="row">
    <div class="col-md-3 col-sm-3">
        <div class="todo-sidebar">
                                            
        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption" data-toggle="collapse" data-target=".todo-project-list-content">
                    <span class="caption-subject font-green-sharp bold uppercase">CONTROLES </span>
                </div>
            </div>
            <div class="portlet-body todo-project-list-content" style="height: auto;">
                <div class="todo-project-list">
                    <ul class="nav nav-stacked">
                        <li>
                            <a href="gui_adicionar.php?item=gui_guias">
                                <span class="badge badge-success"> <i class="fa fa-plus"></i> </span> Nova Guia </a>
                        </li>
                        <li>
                            <a href="gui_listar.php?item=gui_local_atendimento">
                                <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Local Atendimento </a>
                        </li>
                        <?php
                        
                        if(in_array("9", $verifica_lista_permissoes_array_inc)){
                        ?>
                            <li>
                                <a href="gui_listar.php?item=gui_convenios">
                                    <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Convênios </a>
                            </li>
                        <?php
                        }
                        ?>
                            <li>
                            <a href="gui_listar.php?item=gui_procedimentos">
                                <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Procedimentos</a>
                        </li>
                        <?php
                        if(in_array("17", $verifica_lista_permissoes_array_inc)){
                        ?>
                        <li>
                            <a href="gui_listar.php?item=gui_profissionais">
                                <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Profissionais </a>
                        </li>
                        <?php
                        }

                        if(in_array("27", $verifica_lista_permissoes_array_inc)){
                        ?>
                        <li>
                            <a href="gui_listar.php?item=gui_pacientes">
                                <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Pacientes </a>
                        </li>
                        <?php
                        }

                        if(in_array("31", $verifica_lista_permissoes_array_inc)){
                        ?>
                        <li>
                            <a href="gui_listar.php?item=gui_guias">
                                <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Guias </a>
                        </li>
                        <?php
                        }
                        if(in_array("3", $verifica_lista_permissoes_array_inc)){
                        ?>
                        <li>
                            <a href="gui_listar.php?item=gui_pagamentos_guia">
                                <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Pagamentos </a>
                        </li>
                        <?php 
                        }
                        ?>
                        <li>
                            <a href="gui_listar.php?item=gui_busca_procedimentos">
                                <span class="badge badge-success"> <i class="fa fa-check"></i> </span> Busca Procedimentos </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-md-9 col-sm-9">
<!-- Begin: life time stats -->
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-share font-blue"></i>
                <span class="caption-subject font-blue bold uppercase">Visão Geral</span>
                <span class="caption-helper">10 últimos registros...</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#overview_1" data-toggle="tab"> Guias </a>
                    </li>
                    <li>
                        <a href="#overview_2" data-toggle="tab"> Pacientes </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="overview_1">
                        <div class="table-responsive">
                        <table class="table table-condensed table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th> Paciente </th>
                                    <th> Local Atendimento </th>
                                    <th> Status </th>
                                    <!--<th> Agendado para </th>-->
                                </tr>
                            </thead>
                                <tbody>
                        <?php
                        $where = '';

                        $usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
                        $usr_id          = base64_decode($_COOKIE["usr_id"]);
                        $nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
                        $nivel_status    = base64_decode($_COOKIE["nivel_status"]);

                        $where_id_parceiro = '';
                        $where_busca = '';
                        if($nivel_usuario != 'A'){
                            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
                        }else{
                            // nivel adm porem menos permições
                            if($nivel_status == '1'){
                                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
                            }
                        }
                        
                        $sql        = "SELECT gu.* FROM gui_guias gu
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $where_id_parceiro 
                    ORDER BY gu.id_guia DESC
                    LIMIT 0,10";
                    $query      = mysql_query($sql, $banco_painel);
                                    
                    if (mysql_num_rows($query)>0)
                    {
                    
                        while ($dados = mysql_fetch_array($query))
                        {
                            extract($dados);  
                            $status_list = array(
                            array("info" => "AGENDADO"),
                            array("warning" => "ABERTO"),
                            array("danger" => "PENDENTE"),
                            array("success" => "EMITIDO"),
                            array("danger" => "CANCEL")
                            );
                            $ativo = $status;
                            if($ativo == 'AGENDADO'){
                                $status = $status_list[0];
                            }elseif($ativo == 'ABERTO'){
                                $status = $status_list[1];
                            }elseif($ativo == 'PENDENTE'){
                                $status = $status_list[2];
                            }elseif($ativo == 'EMITIDO'){
                                $status = $status_list[3];
                            }elseif($ativo == 'CANCELADO'){
                                $status = $status_list[4];
                            }
                                
                            
                                $coluna_extra = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                            
                            
                            $sql_paciente   = "SELECT id_paciente, nome, data_nascimento, dados_completo FROM gui_pacientes
                                              WHERE id_paciente = $id_paciente";
                            $query_paciente = mysql_query($sql_paciente, $banco_painel) or die(mysql_error()." - 12185");
                            $id_paciente = '';
                            $nome_paciente  = '-';
                            $data_nascimento= '-';
                            $dados_completo = '-';
                            if (mysql_num_rows($query_paciente)>0)
                            {
                                $id_paciente        = mysql_result($query_paciente, 0, 'id_paciente');
                                $nome_paciente      = mysql_result($query_paciente, 0, 'nome');
                                $data_nascimento    = mysql_result($query_paciente, 0, 'data_nascimento');
                                $dados_completo     = mysql_result($query_paciente, 0, 'dados_completo');
                            }
            
                            $sql_local_atendimento  = "SELECT nome FROM gui_local_atendimento
                                              WHERE id_local_atendimento = $id_local_atendimento";
                            $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
                            $nome_local_atendimento = '-';
                            if (mysql_num_rows($query_local_atendimento)>0)
                            {
                                $nome_local_atendimento = mysql_result($query_local_atendimento, 0, 'nome');
                            }
            
                             $data_nascimento = converte_data($data_nascimento);
                             $idade = calcula_idade($data_nascimento);
                             $html_dados_completo = '';
                             if($dados_completo == 'N'){
                                $html_dados_completo = '<span class="label label-sm label-warning"> Dados incompletos </span><br/>';
                             }
                             
                             echo " <tr>
                                        <td>
                                            <a href=\"gui_editar.php?item=gui_guias_detalhes&id=$id_guia&tipo=gui_guias_detalhes\"> $nome_paciente </a>
                                        </td>
                                        <td> $nome_local_atendimento </td>
                                        <td> $coluna_extra </td>
                                        <!--<td>";
                                            $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                            $diasemana_numero = date('w', strtotime($data_agendamento));
                            //echo $diasemana[$diasemana_numero].", dia ".converte_data($data_agendamento).", as ".$hora_agendamento;
                                        echo "</td>-->
                                    </tr>";
                             
                             
                          }
                        }
                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="overview_2">
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th> Paciente </th>
                                        <th> Data Nasc. </th>
                                        <th> última guia </th>
                                        <th> Convenio </th>
                                        <th> Cadastrado desde </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $where = '';

                                $where_id_parceiro = '';
                                $where_busca = '';
                                if($nivel_usuario != 'A'){
                                    $where_id_parceiro = "AND id_parceiro = $usr_parceiro";
                                }else{
                                    // nivel adm porem menos permições
                                    if($nivel_status == '1'){
                                        $where_id_parceiro = "AND id_parceiro = $usr_parceiro";
                                    }
                                }
                                
                                $sql        = "SELECT * FROM gui_pacientes
                                            WHERE ativo = 'S' $where_id_parceiro 
                                            ORDER BY id_paciente DESC
                                            LIMIT 0,10";
                                $query      = mysql_query($sql, $banco_painel);
                                                
                                if (mysql_num_rows($query)>0)
                                {
                                
                                    while ($dados = mysql_fetch_array($query))
                                    {
                                        extract($dados);  
                                        
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
                                        
                                        if($nivel_usuario == 'A'){
                                            $sql_parceiro  = "SELECT nome FROM parceiros
                                                                WHERE id_parceiro = $id_parceiro AND del = 'N'";
                                            $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                                            $coluna_extra = 'Sem Parceiro';
                                            if (mysql_num_rows($query_parceiro)>0)
                                            {
                                                $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                                            }
                                        }else
                                        {
                                            $coluna_extra = $celular;
                                        }
                                        
                                    
                                        $sql_guia  = "SELECT data_agendamento FROM gui_guias
                                                          WHERE id_paciente = $id_paciente
                                                          ORDER BY id_paciente DESC
                                                          LIMIT 0,1";
                                        $query_guia = mysql_query($sql_guia, $banco_painel) or die(mysql_error()." - 185");
                                        $ultima_consulta = '-';
                                        if (mysql_num_rows($query_guia)>0)
                                        {
                                            $ultima_consulta = mysql_result($query_guia, 0, 'data_agendamento');
                                            $ultima_consulta = converte_data($ultima_consulta);
                                        }
                                        
                                        $sql_convenio  = "SELECT nome FROM gui_convenios
                                                          WHERE id_convenio = $id_convenio";
                                        $query_convenio = mysql_query($sql_convenio, $banco_painel) or die(mysql_error()." - 185");
                                        $nome_convenio = '-';
                                        if (mysql_num_rows($query_convenio)>0)
                                        {
                                            $nome_convenio = mysql_result($query_convenio, 0, 'nome');
                                        }
                                        
                                        $data_nascimento = converte_data($data_nascimento);
                                        $idade = calcula_idade($data_nascimento);
                                        $html_dados_completo = '';
                                         if($dados_completo == 'N'){
                                            $html_dados_completo = '<span class="label label-sm label-warning"> Pendente </span><br/>';
                                         }
                                         
                                        echo "<tr>
                                        <td>
                                            <a href=\"gui_editar.php?item=gui_pacientes&id=$id_paciente\"> $nome </a>
                                        </td>
                                        <td> $data_nascimento </td>
                                        <td> $ultima_consulta </td>
                                        <td> $nome_convenio </td>
                                        <td>".converte_data($data_cadastro)."</td>
                                    </tr>"; 
                                         
                                         
                                         
                                         
                                      }
                                  }   
        
                                ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- End: life time stats -->
    </div>
</div>
<div class="row">
<?php
if($nivel_usuario == 'A' AND in_array("52", $verifica_lista_permissoes_array_inc)){
?>

<div class="col-md-12">
    <!-- Begin: life time stats -->
    <!-- BEGIN PORTLET-->
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-globe font-red"></i>
                <span class="caption-subject font-red bold uppercase">MARGEM</span>
                <span class="caption-helper">Valor Cobrado - Custo</span>
            </div>
            <ul class="nav nav-tabs">
                <!--<li >
                    <a href="#portlet_faturamento_tab_1" data-toggle="tab"> Diário </a>
                </li>-->
                <li class="active">
                    <a href="#portlet_faturamento_tab_2" id="statistics_orders_tab" data-toggle="tab"> Semanal </a>
                </li>
                <li>
                    <a href="#portlet_faturamento_tab_3" id="statistics_orders_tab" data-toggle="tab"> Mensal </a>
                </li>
                <li>
                    <a href="#portlet_faturamento_tab_4" id="statistics_orders_tab" data-toggle="tab"> Anual </a>
                </li>
            </ul>
        </div>
        <div class="portlet-body">
            <div class="tab-content">
                <!--<div class="tab-pane " id="portlet_faturamento_tab_1">
                
                </div>-->
                <div class="tab-pane active" id="portlet_faturamento_tab_2">
                    <strong>PERÍODO: <?php
                    $primeiro_dia_semana =  date(
                      'Y-m-d', 
                      strtotime(
                        sprintf("-%s days",
                          date('w')
                        )
                      )
                    );
                    $input_data_inicio = str_replace("/", "-", $primeiro_dia_semana);
                    $proxima_data = date('d/m/Y', strtotime('+1 day', strtotime($input_data_inicio)));   
                    //$proxima_data = str_replace("/", "-", $proxima_data);
    
                    $week_day = date("w");
                    $weekend = 6; // sabado
                    $diff = $weekend - $week_day;
                    $weekend_day['saturday'] = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") + $diff), date("Y") ));
                    $ultimo_dia_semana = $weekend_day['saturday'];
                    
                    echo $proxima_data. " à ".$ultimo_dia_semana;
                    ?>
                    </strong>
                    <div id="chart_2" class="chart" style="height: 400px;"> </div>
                    <div class="well margin-top-20">
                <div class="row">
                    
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                        <span class="label btn blue-madison"> CONSULTAS</span>
                        <h5>
                        
                        <?php
                        $primeiro_dia_semana =  date(
                          'Y-m-d', 
                          strtotime(
                            sprintf("-%s days",
                              date('w')
                            )
                          )
                        );
    
                        $week_day = date("w");
                        $weekend = 6; // sabado
                        $diff = $weekend - $week_day;
                        $weekend_day['saturday'] = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") + $diff), date("Y") ));
                        $ultimo_dia_semana = converte_data($weekend_day['saturday']);
    
    
                        //$input_data_inicio = converte_data($primeiro_dia_semana); 
                        $input_data_inicio = str_replace("/", "-", $primeiro_dia_semana);
                        $proxima_data = date('d/m/Y', strtotime('+1 day', strtotime($input_data_inicio)));   
                        $proxima_data = str_replace("/", "-", $proxima_data);
                        $proxima_data = converte_data($proxima_data);

    //while(strtotime($proxima_data) <= strtotime($ultimo_dia_semana))
    //{
        
        $sql_guias        = "SELECT gu.id_guia, gu.id_convenio, gu.id_local_atendimento, gu.data_emissao FROM gui_guias gu
        JOIN gui_pacientes gui_pac ON gu.id_paciente = gui_pac.id_paciente
        WHERE gu.data_emissao BETWEEN '$proxima_data%' AND '$ultimo_dia_semana%' AND gu.status = 'EMITIDO'";
        $query_guias      = mysql_query($sql_guias);
        $soma_exame = 0;
        $soma_consulta = 0;
        
        if(mysql_num_rows($query_guias) > 0){
            
            
            while($dados_guias = mysql_fetch_array($query_guias))
            {
                extract($dados_guias);
                
                $sql_guias_procedimentos       = "SELECT id_procedimento, valor_cobrado FROM gui_procedimentos_guia
                WHERE id_guia = $id_guia";
                $query_procedimentos           = mysql_query($sql_guias_procedimentos);
                
                if(mysql_num_rows($query_procedimentos) > 0){
                    while($dados_guias_procedimentos = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados_guias_procedimentos);
                        
                        $sql_guias_local       = "SELECT preco_custo FROM gui_local_atendimento_procedimentos
WHERE id_procedimento = $id_procedimento AND id_local_atendimento = $id_local_atendimento AND id_convenio = $id_convenio";
                        $query_local           = mysql_query($sql_guias_local);
                        
                        if(mysql_num_rows($query_local) > 0){
                            while($dados_local = mysql_fetch_array($query_local))
                            {
                                extract($dados_local);
                                
                                
                                $sql_proced       = "SELECT gpro.nome FROM gui_procedimentos pro
                        JOIN gui_grupo_procedimentos gpro ON pro.id_grupo_procedimento = gpro.id_grupo_procedimento
                        WHERE id_procedimento = $id_procedimento";
                        $query_proced           = mysql_query($sql_proced);
                        $nome_grupo_proced = '';
                        if(mysql_num_rows($query_proced) > 0){
                            $nome = mysql_result($query_proced, 0, 'nome');
                        }
                                
                                if($nome == 'EXAME'){
                                    $soma_exame = $soma_exame + ($valor_cobrado - $preco_custo);
                                }
                                
                                if($nome == 'CONSULTA'){
                                    $soma_consulta = $soma_consulta + ($valor_cobrado - $preco_custo);
                                }  
                                
                                $soma_exame = moeda_db($soma_exame);
                                $soma_consulta = moeda_db($soma_consulta);
  
                            }
                        }
  
                    }
                }

            }
             
        }

    echo "<strong>".db_moeda($soma_consulta)."</strong>";
                        
                        ?>
                        
                        
                        </h5>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                    
                        <span class="label btn yellow-crusta">EXAMES</span>
                        <h5><?php
                        echo "<strong>".db_moeda($soma_exame)."</strong>";
                        
                        ?></h5>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat" style="text-align: right;">
                    
                        <span class="label label-default">TOTAL</span>
                        <h5><strong><?php
                        $soma_total = $soma_exame + $soma_consulta;
                        echo db_moeda($soma_total);
                        ?></strong></h5>
                    </div>
                </div>
            </div>
                </div>
                <div class="tab-pane" id="portlet_faturamento_tab_3">
                <strong><?php
                $data_ = date('Y-m-d');
                $array_proxima_data = explode("-", $data_);
                $array_dia_proxima_data = $array_proxima_data[0];
                $meses = array(
                    '01'=>'Janeiro',
                    '02'=>'Fevereiro',
                    '03'=>'Março',
                    '04'=>'Abril',
                    '05'=>'Maio',
                    '06'=>'Junho',
                    '07'=>'Julho',
                    '08'=>'Agosto',
                    '09'=>'Setembro',
                    '10'=>'Outubro',
                    '11'=>'Novembro',
                    '12'=>'Dezembro'
                );
                
                echo "PERÍODO: ".$meses[date('m')]." de ".$array_dia_proxima_data;
                
                


                ?></strong>
                    <div id="chart_3" class="chart" style="height: 400px;"> </div>
                    <div class="well margin-top-20">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                                <span class="label btn blue-madison"> CONSULTAS</span>
                                <h5><?php
                                
                                    $data_incio = mktime(0, 0, 0, date('m') , 1 , date('Y'));
    $data_fim = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
    $data_incio = date('Y-m-d',$data_incio);
    $data_fim =  date('Y-m-d',$data_fim);

    $proxima_data = $data_incio;

    //while(strtotime($proxima_data) <= strtotime($data_fim))
    //{
        $array_proxima_data = explode("-", $proxima_data);
        $array_dia_proxima_data = $array_proxima_data[2];
        $sql_guias        = "SELECT gu.id_guia, gu.id_convenio, gu.id_local_atendimento, gu.data_emissao FROM gui_guias gu
        JOIN gui_pacientes gui_pac ON gu.id_paciente = gui_pac.id_paciente
        WHERE gu.data_emissao BETWEEN '$data_incio%' AND '$data_fim%' AND gu.status = 'EMITIDO'";
        $query_guias      = mysql_query($sql_guias);
        $soma_exame = 0;
        $soma_consulta = 0;
        
        if(mysql_num_rows($query_guias) > 0){
            
            
            while($dados_guias = mysql_fetch_array($query_guias))
            {
                extract($dados_guias);
                
                $sql_guias_procedimentos       = "SELECT id_procedimento, valor_cobrado FROM gui_procedimentos_guia
                WHERE id_guia = $id_guia";
                $query_procedimentos           = mysql_query($sql_guias_procedimentos);
                
                if(mysql_num_rows($query_procedimentos) > 0){
                    while($dados_guias_procedimentos = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados_guias_procedimentos);
                        
                        $sql_guias_local       = "SELECT preco_custo FROM gui_local_atendimento_procedimentos
WHERE id_procedimento = $id_procedimento AND id_local_atendimento = $id_local_atendimento AND id_convenio = $id_convenio";
                        $query_local           = mysql_query($sql_guias_local);
                        
                        if(mysql_num_rows($query_local) > 0){
                           
                            while($dados_local = mysql_fetch_array($query_local))
                            {
                                extract($dados_local);
                                
                                
                                $sql_proced       = "SELECT gpro.nome FROM gui_procedimentos pro
                        JOIN gui_grupo_procedimentos gpro ON pro.id_grupo_procedimento = gpro.id_grupo_procedimento
                        WHERE id_procedimento = $id_procedimento";
                        $query_proced           = mysql_query($sql_proced);
                        $nome_grupo_proced = '';
                        if(mysql_num_rows($query_proced) > 0){
                            $nome = mysql_result($query_proced, 0, 'nome');
                        }
                                
                                if($nome == 'EXAME'){
                                    $soma_exame = $soma_exame + ($valor_cobrado - $preco_custo);
                                }
                                
                                if($nome == 'CONSULTA'){
                                    $soma_consulta = $soma_consulta + ($valor_cobrado - $preco_custo);
                                }  
                                
                                $soma_exame = moeda_db($soma_exame);
                                $soma_consulta = moeda_db($soma_consulta);
  
                            }
                        }
  
                    }
                }

            
             
        }

    }
 echo "<strong>".db_moeda($soma_consulta)."</strong>";
    
      
                                
                                ?></h5>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                                <span class="label btn yellow-crusta"> EXAMES</span>
                                <h5><?php
                                 echo "<strong>".db_moeda($soma_exame)."</strong>";
                                ?></h5>
                            </div>
                           <div class="col-md-4 col-sm-4 col-xs-12 text-stat" style="text-align: right;">
                    
                        <span class="label label-default">TOTAL</span>
                        <h5><strong><?php
                        $soma_total = $soma_exame + $soma_consulta;
                        echo db_moeda($soma_total);
                        ?></strong></h5>
                    </div>
                        </div>
                    </div>
                </div>
                 <div class="tab-pane" id="portlet_faturamento_tab_4">
                    
                </div>
            </div>
            
        </div>
    </div>
    <!-- End: life time stats -->
</div>


<div class="col-md-6">
    <!-- Begin: life time stats -->
    <!-- BEGIN PORTLET-->
    <div class="portlet light">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-globe font-red"></i>
                <span class="caption-subject font-red bold uppercase">GUIAS</span>
            </div>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#portlet_ecommerce_tab_1" data-toggle="tab"> Semana </a>
                </li>
                <li>
                    <a href="#portlet_ecommerce_tab_2" id="statistics_orders_tab" data-toggle="tab"> Mês atual </a>
                </li>
                 <li>
                    <a href="#portlet_ecommerce_tab_3" id="statistics_orders_tab" data-toggle="tab"> Último mês </a>
                </li>
            </ul>
        </div>
        <div class="portlet-body">
            <div class="tab-content">
                <div class="tab-pane active" id="portlet_ecommerce_tab_1">
                    <?php
                    $primeiro_dia_semana =  date(
                      'Y-m-d', 
                      strtotime(
                        sprintf("-%s days",
                          date('w')
                        )
                      )
                    );
                    
                    $week_day = date("w");
                    $weekend = 6; // sabado
                    $diff = $weekend - $week_day;
                    $weekend_day['saturday'] = date("d/m/Y", mktime(0, 0, 0, date("m"), (date("d") + $diff), date("Y") ));
                    $ultimo_dia_semana = $weekend_day['saturday'];
                    
                    echo "Entre os dias <strong>".converte_data($primeiro_dia_semana)."</strong> à <strong>".$ultimo_dia_semana."</strong>";
                    ?>
                    <div id="chart_8" class="chart" style="height: 300px;"> </div>
                </div>
                <div class="tab-pane" id="portlet_ecommerce_tab_2">
                <?php
                $data_incio     = mktime(0, 0, 0, date('m') , 1 , date('Y'));
                $data_fim       = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
                $data_incio_f   = date('d/m/Y',$data_incio);
                $data_fim_f     = date('d/m/Y',$data_fim);
                $soma_valores_total = 0;
                echo "Entre os dias <strong>$data_incio_f</strong> à <strong>$data_fim_f</strong>";
                ?>
                    <div id="chart_9" class="chart"> </div>
                </div>
                <div class="tab-pane" id="portlet_ecommerce_tab_3">
                    <div id="chart_10" class="chart"> </div>
                </div>
            </div>
            <div class="well margin-top-20">
                <h4>Total Geral</h4>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                        <?php
                        $sql_contar_guias        = "SELECT COUNT(*) FROM gui_pagamentos_guias pag
                        JOIN gui_guias gui ON pag.id_guia = gui.id_guia
                        JOIN gui_pacientes pac ON gui.id_paciente = pac.id_paciente
                        WHERE gui.del = 'N' AND pac.ativo = 'S' AND gui.id_convenio <> 5 ";
                        $query_contar_guias      = mysql_query($sql_contar_guias);
                        $soma_valores = mysql_result($query_contar_guias, 0,0);
                        $soma_valores_total = $soma_valores;
                        ?>
                        <span class="label label-success"> PLANO</span>
                        <h5><?php echo $soma_valores; ?> guia(s)</h5>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                     <?php
                        $sql_contar_guias        = "SELECT COUNT(*) FROM gui_pagamentos_guias pag
                        JOIN gui_guias gui ON pag.id_guia = gui.id_guia
                        JOIN gui_pacientes pac ON gui.id_paciente = pac.id_paciente
                        WHERE gui.del = 'N' AND pac.ativo = 'S' AND gui.id_convenio = 5 ";
                        $query_contar_guias      = mysql_query($sql_contar_guias);
                        $soma_valores = mysql_result($query_contar_guias, 0,0);
                        $soma_valores_total = $soma_valores_total + $soma_valores;
                        ?>
                        <span class="label label-info"> PARTICULAR </span>
                        <h5><?php echo $soma_valores; ?> guia(s)</h5>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat" style="text-align: right;">
                        <span class="label label-default"> TOTAL </span>
                        <h5><strong><?php echo $soma_valores_total; ?> guia(s)</strong></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End: life time stats -->
</div>

<div class="col-md-6">
    <!-- Begin: life time stats -->
    <!-- BEGIN PORTLET-->
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-globe font-red"></i>
                <span class="caption-subject font-red bold uppercase">PAGAMENTOS</span>
            </div>
            <!--<ul class="nav nav-tabs">
                <li class="active">
                    <a href="#portlet_ecommerce_tab_1" data-toggle="tab"> Amounts </a>
                </li>
                <li>
                    <a href="#portlet_ecommerce_tab_2" id="statistics_orders_tab" data-toggle="tab"> Orders </a>
                </li>
            </ul>-->
        </div>
        <div class="portlet-body">
            <div class="tab-content">
                <div class="tab-pane active" id="portlet_ecommerce_tab_1">
                <strong>Total de pagamentos</strong>
                    <div id="chart_7" class="chart" style="height: 300px;"> </div>
                </div>
                <div class="tab-pane" id="portlet_ecommerce_tab_2">
                    
                </div>
            </div>
            <div class="well margin-top-20">
                <h4>Total Geral</h4>
                <div class="row">
                    <?php
                    $sql_soma_valores        = "SELECT SUM(pag.valor_total)'valor_total' FROM gui_pagamentos_guias pag
                        JOIN gui_guias gui ON pag.id_guia = gui.id_guia
                        JOIN gui_pacientes pac ON gui.id_paciente = pac.id_paciente
                        WHERE gui.del = 'N' AND pac.ativo = 'S' AND pag.status = 'PAGO'
                        GROUP BY pag.local_pagamento";
                    $query_soma_valores      = mysql_query($sql_soma_valores);
                    $soma_valores = mysql_result($query_soma_valores, 0,'valor_total');
                    
                    
                    $sql_soma_valores        = "SELECT SUM(pag.valor_total)'valor_total' FROM gui_pagamentos_guias pag
                        JOIN gui_guias gui ON pag.id_guia = gui.id_guia
                        JOIN gui_pacientes pac ON gui.id_paciente = pac.id_paciente
                        WHERE gui.del = 'N' AND pac.ativo = 'S' AND pag.status = ''
                        GROUP BY pag.local_pagamento";
                    $query_soma_valores      = mysql_query($sql_soma_valores);
                    $soma_valores_nao_pago = mysql_result($query_soma_valores, 0,'valor_total');
                    $soma_valores_total = $soma_valores + $soma_valores_nao_pago;
                    ?>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                        <span class="label label-success"> PAGOS</span>
                        <h5><?php echo db_moeda($soma_valores); ?></h5>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat">
                    
                        <span class="label label-danger"> À PAGAR / L. ATEND.</span>
                        <h5><?php echo db_moeda($soma_valores_nao_pago); ?></h5>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-stat" style="text-align: right;">
                    
                        <span class="label label-default"> TOTAL</span>
                        <h5><strong><?php echo db_moeda($soma_valores_total); ?></strong></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End: life time stats -->
</div>
<?php
}
?>
</div>