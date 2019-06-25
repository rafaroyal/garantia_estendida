<script>
//jQuery(document).ready(function() {
    
    var contador = function() {
        var n = $("input[type=checkbox][name='guias_marcadas[]']:checked").length;
        $("#total_guias_sel").text( n + (n === 1 || n === 0 ? " é" : " são") + " selecionado(s)" );
        
        if(n == 0){
            $("#bt_faturar_guia").hide();
        }else{
            $("#bt_faturar_guia").show();
        }
    };
    contador(); 
    $("input[type=checkbox][name='guias_marcadas[]']:checked").on( "click", contador );
    
    
    var contador_todos = function() {
        var n = $("input[type=checkbox][name='guias_marcadas[]']:checked").length;
        if(n > 0){
            n = 0;
            //$("input[type=checkbox][name='guias_todas_marcadas']").prop('checked', false); 
            $("#total_guias_sel").text( n + (n === 1 || n === 0 ? " é" : " são") + " selecionado(s)" );
            $("#bt_faturar_guia").hide();
        }else{
            var n = $("input[type=checkbox][name='guias_marcadas[]']").length;
            //$("input[type=checkbox][name='guias_todas_marcadas']").prop('checked', true); 
            $("#total_guias_sel").text( n + (n === 1 || n === 0 ? " é" : " são") + " selecionado(s)" );
            $("#bt_faturar_guia").show();
        }
        
    };
    //contador_todos(); 
    $("input[type=checkbox][name='guias_todas_marcadas']:checked").on( "click", contador_todos );
    
    //});

</script>
<style>
.dt-buttons{
    position: absolute;
    right: 0;
    top: -90px;
}
#sample_1_filter, #sample_1_info{
    display: none;
}
.table-scrollable{
    overflow: visible;
}

@media print {
    .btn-outline {display: none; }
}
</style>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;

$periodo                = (empty($_GET['periodo'])) ? "" : verifica($_GET['periodo']);  
$tipo                   = (empty($_GET['tipo'])) ? "" : verifica($_GET['tipo']);  
$id_local_atendimento   = (empty($_GET['id_local_atendimento'])) ? "" : verifica($_GET['id_local_atendimento']);  
$id_profissional        = (empty($_GET['id_profissional'])) ? "" : verifica($_GET['id_profissional']); 
$todos_clientes_ativos  = (empty($_GET['todos_clientes_ativos'])) ? "" : verifica($_GET['todos_clientes_ativos']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);

// converter string periodo em data
// exemplo: 06 Janeiro, 2016 - 04 Fevereiro, 2016

$datas = explode(" - ", $periodo);

$data1 = explode(" ", $datas[0]);
$dia_data1 = $data1[0];
$mes_data1 = substr($data1[1], 0, -1);
$ano_data1 = $data1[2];

if($mes_data1 == "Janeiro"){
$mes_num_data1 = "01";
}elseif($mes_data1 == "Fevereiro"){
$mes_num_data1 = "02";
}elseif($mes_data1 == "Marco"){
$mes_num_data1 = "03";
}elseif($mes_data1 == "Abril"){
$mes_num_data1 = "04";
}elseif($mes_data1 == "Maio"){
$mes_num_data1 = "05";
}elseif($mes_data1 == "Junho"){
$mes_num_data1 = "06";
}elseif($mes_data1 == "Julho"){
$mes_num_data1 = "07";
}elseif($mes_data1 == "Agosto"){
$mes_num_data1 = "08";
}elseif($mes_data1 == "Setembro"){
$mes_num_data1 = "09";
}elseif($mes_data1 == "Outubro"){
$mes_num_data1 = "10";
}elseif($mes_data1 == "Novembro"){
$mes_num_data1 = "11";
}else{
$mes_num_data1 = "12";
}

$data1 = $ano_data1.'/'.$mes_num_data1.'/'.$dia_data1;
$data1_convert = str_replace("/", "-", $data1);

$data2 = explode(" ", $datas[1]);
$dia_data2 = $data2[0];
$mes_data2 = substr($data2[1], 0, -1);
$ano_data2 = $data2[2];

if($mes_data2 == "Janeiro"){
$mes_num_data2 = "01";
}elseif($mes_data2 == "Fevereiro"){
$mes_num_data2 = "02";
}elseif($mes_data2 == "Marco"){
$mes_num_data2 = "03";
}elseif($mes_data2 == "Abril"){
$mes_num_data2 = "04";
}elseif($mes_data2 == "Maio"){
$mes_num_data2 = "05";
}elseif($mes_data2 == "Junho"){
$mes_num_data2 = "06";
}elseif($mes_data2 == "Julho"){
$mes_num_data2 = "07";
}elseif($mes_data2 == "Agosto"){
$mes_num_data2 = "08";
}elseif($mes_data2 == "Setembro"){
$mes_num_data2 = "09";
}elseif($mes_data2 == "Outubro"){
$mes_num_data2 = "10";
}elseif($mes_data2 == "Novembro"){
$mes_num_data2 = "11";
}else{
$mes_num_data2 = "12";
}

$data2 = $ano_data2.'/'.$mes_num_data2.'/'.$dia_data2;
$data2_convert = str_replace("/", "-", $data2);


if($tipo == 'local_atendimento')
{
  echo "<div class=\"portlet light\" id=\"info_historico_faturamento\">
        <div class=\"portlet-title\">
        <div class=\"caption\">
            <i class=\"icon-share font-green-sharp\"></i>
            <span class=\"caption-subject font-green-sharp bold uppercase\">Histório de faturamento do período:</span>
        </div>
    </div>";
        
        echo "<div class=\"portlet-body\">
        <h4 class=\"block\">Últimos faturamentos:</h4>
        <ul class=\"list-group\">";
 
        $sql_local      = "SELECT g_loc.nome, g_fat.id_faturamento, g_fat.periodo_inicio, g_fat.periodo_fim, g_fat.quantidade_total, g_fat.data_cadastro, g_fat.id_profissional'id_profissional_faturado', g_fat.id_usuario_baixa, g_fat.cancelar, g_fat.guias_pendentes FROM gui_local_atendimento g_loc
                            JOIN gui_faturamentos_guias g_fat ON g_loc.id_local_atendimento = g_fat.id_local_atendimento
                            WHERE g_loc.id_local_atendimento = '$id_local_atendimento' AND g_fat.mes_referencia = '$mes_num_data1' AND g_fat.ano_referencia = '$ano_data1'";
            $query_local      = mysql_query($sql_local);
            if (mysql_num_rows($query_local)>0)
            {
                
                while ($dados = mysql_fetch_array($query_local))
                {
                    extract($dados);
                    ?>
                    <li class="list-group-item <?php echo ($cancelar == 'S') ? 'list-group-item-danger' : ''; ?>" id="id_faturamento_guias_<?php echo $id_faturamento; ?>">
                        <?php 
                        if($cancelar == 'S'){
                        ?>
                            Faturamento: <strong style="text-transform: uppercase;"><?php echo $mes_data1; ?>-<?php echo $ano_data1;?></strong>
                             | Período de Faturamento: 
                             <?php
                             if($guias_pendentes == 'S'){
                             ?>
                                <strong>Todo período/Guias pendentes</strong> 
                             <?php  
                             }else{
                             ?>
                                <strong><?php echo converte_data($periodo_inicio)." à ".converte_data($periodo_fim); ?></strong> 
                             <?php
                             }
                             ?>

                             | Quantidade de Guias: <strong><?php echo $quantidade_total; ?></strong>  <span class="badge badge-danger">CANCELADO</span>
                        <?php    
                        }else{
                        ?>
                            <a href="inc/guia_recibo_faturamento/?id_faturamento_guias=<?php echo $id_faturamento; ?>" target="_black"> 
                                Faturamento: <strong style="text-transform: uppercase;"><?php echo $mes_data1; ?>-<?php echo $ano_data1;?></strong>
                                 | Período de Faturamento: 
                                 <?php
                                 if($guias_pendentes == 'S'){
                                 ?>
                                    <strong>Todo período/Guias pendentes</strong> 
                                 <?php  
                                 }else{
                                 ?>
                                    <strong><?php echo converte_data($periodo_inicio)." à ".converte_data($periodo_fim); ?></strong> 
                                 <?php
                                 }
                                 ?>
                                 | Quantidade de Guias: <strong><?php echo $quantidade_total; ?></strong> 
                            </a> 
                        <?php
                        }
                        ?>
                        
                        
                        
                            <?php
                            if($nivel_usuario == 'A' AND in_array("66", $verifica_lista_permissoes_array_inc) AND $cancelar == 'N'){
                            ?>
                            <a data-target="#ajax" data-toggle="modal" href="inc/gui_cancelar_faturamento_guias.php?id_faturamento=<?php echo $id_faturamento; ?>" class="badge badge-danger" id="bt_cancel_id_faturamento_guias_<?php echo $id_faturamento; ?>">
                            <span>
                                <i class="fa fa-times"></i> Cancelar
                            </span></a>
                            <?php
                            }
                            ?>
                        
                    </li>
                    <?php
                }

            }else{
                
                echo "<li class=\"list-group-item\">Sem histórico de Faturamento para o período selecionado <strong style=\"text-transform: uppercase;\">$mes_data1</strong>.</li>";
                
            }
         echo "</ul>
    </div>
</div>";
  
?>

<div class="invoice">
    <div class="row invoice-logo">
        <!--<div class="col-xs-2">
           <a class="btn red btn-outline btn-circle" onclick="javascript:window.print();"> Imprimir
                <i class="fa fa-print"></i>
            </a>
            <div class="tools"> </div>
        </div>-->
        <div class="col-xs-12">
            <div class="tools"> </div>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-xs-12">
        <div class="portlet light "> 
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase">Lista de Guias para conferência:</span>
            </div>
            <div class="tools"> </div>
        </div>

    <?php
    $hora_agendamento_agenda = "";
    $select_buscar_por = 'data_agendamento';
    $sql_verifica_filtro = "AND (gu.data_agendamento BETWEEN '$data1' AND '$data2')";
    $html_coluna_data = "AGENDADO PARA:";
    $html_coluna_hora = ", gu.hora_agendamento'hora_agendamento_agenda'";
    $html_periodo_fatu = $periodo;
    $html_todos_clientes_ativos = "<input type=\"hidden\" name=\"todos_clientes_ativos\" id=\"todos_clientes_ativos\" value=\"N\">";
    if($todos_clientes_ativos == 'S'){
        $sql_verifica_filtro='';
        $html_periodo_fatu = 'Todo período/Guias pendentes';
        $html_todos_clientes_ativos = "<input type=\"hidden\" name=\"todos_clientes_ativos\" id=\"todos_clientes_ativos\" value=\"S\">";
    }
  
    echo " <div class=\"portlet-body\">
        <div class=\"table-scrollable\">
            <table class=\"table table-hover table-checkable\" id=\"sample_1\">";

                if($nivel_usuario == 'A'){
                
                echo "<thead>
                    <tr role=\"row\" class=\"heading\">
                        <th><input type=\"checkbox\" name=\"guias_todas_marcadas\" class=\"group-checkable\" data-set=\"#sample_1 .checkboxes\" checked/> </th>
                        <th>#GUIA</th>
                        <th>PACIENTE</th>
                        <th>$html_coluna_data</th>
                        <th>PROFISSIONAL</th>
                        <th>PARCEIRO</th>
                        <th>CUSTO</th>
                    </tr>
                </thead>
                ";
                
            }
   
        echo "<tbody>";
        
        $sql_local_atendimento  = "SELECT nome, cidade, forma_faturamento FROM gui_local_atendimento
                                  WHERE id_local_atendimento = $id_local_atendimento";
        $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
        $nome_local_atendimento = '-';
        $cidade_local_atendimento = '-';
        $forma_faturamento_atendimento = '-';
        if (mysql_num_rows($query_local_atendimento)>0)
        {
            $nome_local_atendimento         = mysql_result($query_local_atendimento, 0, 'nome');
            $cidade_local_atendimento       = mysql_result($query_local_atendimento, 0, 'cidade');
            $forma_faturamento_atendimento  = mysql_result($query_local_atendimento, 0, 'forma_faturamento');
        }
        
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
            if(in_array("44", $verifica_lista_permissoes_array_inc)){
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }

        $where_apenas_emissao = "AND gu.status = 'EMITIDO'";

        $where_guias_faturar = "AND gupg.baixa_faturado = 'N'";
        
        $where_id_local_atendimento = '';
        if($id_local_atendimento != 'todos'){
            $where_id_local_atendimento = "AND gu.id_local_atendimento = $id_local_atendimento";
        }
        
        $where_id_profissional = '';
        if($id_profissional != 'todos' AND $id_profissional != 'undefined'){
            $where_id_profissional = "AND gu.id_profissional = $id_profissional";
        }
        
        
        $sql        = "SELECT gu.id_guia FROM gui_guias gu
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    JOIN gui_pagamentos_guias gupg ON gu.id_pagamento = gupg.id_pagamento
                    WHERE gu.del = 'N' $where_id_parceiro $sql_verifica_filtro $where_apenas_emissao $where_guias_faturar $where_id_local_atendimento $where_id_profissional
                    ORDER BY gu.$select_buscar_por DESC";
                    //echo $sql;
        $query_count      = mysql_query($sql, $banco_painel);
        $quantade_total_guias = '';
        $custo_total_guias  = '';   
        $soma_preco_custo = 0;
        $soma_valor_cobrado = 0;             
        if (mysql_num_rows($query_count)>0)
        {
            $quantade_total_guias = mysql_num_rows($query_count);
            
            while ($dados = mysql_fetch_array($query_count))
            {
                extract($dados);
                
                 $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimento', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto, gui_pag_gui.baixa_recebimento FROM gui_procedimentos_guia gui_pro_gui
                    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
                    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
                    JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
                    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";
                        $query_procedimentos      = mysql_query($sql_procedimentos, $banco_painel);
                                
                        if (mysql_num_rows($query_procedimentos)>0)
                        {  

                            while ($dados_valor = mysql_fetch_array($query_procedimentos))
                            {
                                extract($dados_valor); 
                                //echo $preco_custo;
                                $preco_custo_calc = moeda_db($preco_custo);
                                $valor_cobrado_exibe = $valor_cobrado;
                                
                                if($forma_faturamento_atendimento == 'PRECO_CUSTO'){
                                    $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                                    //echo " (".$soma_preco_custo.") ";
                                }elseif($forma_faturamento_atendimento == 'PRECO_FINAL'){
                                    $soma_preco_custo = $soma_preco_custo + $valor_cobrado_exibe;
                                }elseif($forma_faturamento_atendimento == 'REPASSE_FINAL_NENOS_CUSTO'){
                                    $soma_preco_custo = $soma_preco_custo + ($preco_custo_calc - $valor_cobrado_exibe);
                                }

                            }
                            
                            //$soma_preco_custo = db_moeda4($soma_preco_custo);
                            
                            if($soma_preco_custo == 0.00){
                                $soma_preco_custo = 0;
                            }
  
                        }

            }
            //$quantade_total_guias   = mysql_result($query_count, 0, 'contar_guia');
            //$custo_total_guias      = mysql_result($query_count, 0, 'preco_custo_soma');
            
        }
        if($quantade_total_guias > 0){
            echo "<div class=\"row note note-info\">
                <h4 class=\"block\">Info! Dados para Faturamento.</h4>
                ";
                ?>
                <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label ">Mês</label>
                    <select class="bs-select form-control" data-live-search="true" data-size="8" id="mes_referencia" name="mes_referencia">
                        <option value="1" <?php $mes_referencia_sel = ($mes_data1 == 'Janeiro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Janeiro</option>
                        <option value="2" <?php $mes_referencia_sel = ($mes_data1 == 'Fevereiro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Fevereiro</option>
                        <option value="3" <?php $mes_referencia_sel = ($mes_data1 == 'Marco') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Março</option>
                        <option value="4" <?php $mes_referencia_sel = ($mes_data1 == 'Abril') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Abril</option>
                        <option value="5" <?php $mes_referencia_sel = ($mes_data1 == 'Maio') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Maio</option>
                        <option value="6" <?php $mes_referencia_sel = ($mes_data1 == 'Junho') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Junho</option>
                        <option value="7" <?php $mes_referencia_sel = ($mes_data1 == 'Julho') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Julho</option>
                        <option value="8" <?php $mes_referencia_sel = ($mes_data1 == 'Agosto') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Agosto</option>
                        <option value="9" <?php $mes_referencia_sel = ($mes_data1 == 'Setembro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Setembro</option>
                        <option value="10" <?php $mes_referencia_sel = ($mes_data1 == 'Outubro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Outubro</option>
                        <option value="11" <?php $mes_referencia_sel = ($mes_data1 == 'Novembro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Novembro</option>
                        <option value="12" <?php $mes_referencia_sel = ($mes_data1 == 'Dezembro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Dezembro</option>
                    </select>
                </div>
                </div>
                <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label ">Ano</label>
                    <select class="bs-select form-control" data-live-search="true" data-size="8" id="ano_referencia" name="ano_referencia">
                        <?php
                        $agora_normal   = date('Y');
                        $final_atual = date('Y', strtotime('+1 year', strtotime($agora_normal)));
                        $ano_anterior = date('Y', strtotime('-1 year', strtotime($agora_normal)));
                        for($i = $ano_anterior; $i <= $final_atual; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>" <?php $ano_referencia_sel = ($ano_data1 == $i) ? 'selected=""' : ''; echo $ano_referencia_sel; ?>><?php echo $i; ?></option>
                        <?php
                        }
                        ?>
                        </select>
                </div>
                </div>
                <?php
                //<strong style=\"text-transform: uppercase;\">$mes_data1</strong></p>
                
                echo "<div class=\"col-md-8\">
                <p> Período de Faturamento: $html_periodo_fatu $html_todos_clientes_ativos</p>
                <p> Quantidade de Guias: $quantade_total_guias</p>
                <p> Total Guias selecionadas: <strong id=\"total_guias_sel\">$quantade_total_guias</strong></p>
                <!--<p> Custo Total: <strong>".db_moeda($soma_preco_custo)."</strong></p>-->
                <p>&nbsp;</p>
                <p><a data-toggle=\"modal\" id=\"bt_faturar_guia\" href=\"inc/gui_confirmar_faturamento_guias.php?data_um=$data1_convert&data_dois=$data2_convert&id_local_atendimento=$id_local_atendimento&id_profissional=$id_profissional&mes_referencia=$mes_num_data1&ano_referencia=$ano_data1&soma_preco_custo=$soma_preco_custo\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn blue\">
                <i class=\"fa fa-file-o\"></i> Faturar Guias </a> <span id=\"html_imprimir_recibo_faturados\"></span></p>
                </div>
            </div>";
        }else{
            echo "<div class=\"note note-warning\">
                <h4 class=\"block\">Info! Dados para Faturamento.</h4>
                <!--<p> Mês de Faturamento: <strong style=\"text-transform: uppercase;\">$mes_data1</strong></p>-->
                <p> Período de Faturamento: $html_periodo_fatu</p>
                <p> <strong>Sem guias para faturamento no período selecionado!</strong>";
        }
        
        
            
        $sql        = "SELECT gu.*, gu.$select_buscar_por'html_coluna_data'$html_coluna_hora FROM gui_guias gu
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    JOIN gui_pagamentos_guias gupg ON gu.id_pagamento = gupg.id_pagamento
                    WHERE gu.del = 'N' $where_id_parceiro $sql_verifica_filtro $where_apenas_emissao $where_guias_faturar $where_id_local_atendimento $where_id_profissional
                    ORDER BY gu.$select_buscar_por DESC";
        //echo $sql;
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query_count)>0)
        {
            $i = 1;
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
                    
                if($nivel_usuario == 'A'){
                    $sql_parceiro  = "SELECT nome FROM parceiros
                                        WHERE id_parceiro = $id_parceiro AND del = 'N'";
                    $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                    $coluna_extra = 'Sem Parceiro';
                    $coluna_extra_2 = '';
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        $coluna_extra_2 = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                    }
                }
                
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

                /*$sql_local_atendimento  = "SELECT nome, cidade FROM gui_local_atendimento
                                  WHERE id_local_atendimento = $id_local_atendimento";
                $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
                $nome_local_atendimento = '-';
                if (mysql_num_rows($query_local_atendimento)>0)
                {
                    $nome_local_atendimento = mysql_result($query_local_atendimento, 0, 'nome');
                    $cidade_local_atendimento = mysql_result($query_local_atendimento, 0, 'cidade');
                }*/
                
                $sql_profissional  = "SELECT nome FROM gui_profissionais
                                  WHERE id_profissional = $id_profissional";
                $query_profissional= mysql_query($sql_profissional, $banco_painel) or die(mysql_error()." - 186");
                $nome_profissional = '-';
                if (mysql_num_rows($query_profissional)>0)
                {
                    $nome_profissional = mysql_result($query_profissional, 0, 'nome'); 
                }

                 $data_nascimento = converte_data($data_nascimento);
                 //$idade = calcula_idade($data_nascimento);
                 $html_dados_completo = '';
                 if($dados_completo == 'N'){
                    $html_dados_completo = '<span class="label label-sm label-warning"> Dados incompletos </span><br/>';
                 }
                 
                 
                 $html_coluna_hora = $hora_agendamento_agenda;
                 

                 echo "<tr>
                 <td><input type=\"checkbox\" name=\"guias_marcadas[]\" id=\"guias_marcadas_$id_guia\" class=\"checkboxes\" value=\"$id_guia\" checked/> </td>
                 <td> $i-$id_guia</td>
                 <td> $nome_paciente</td>
                 <td> ".converte_data($html_coluna_data)." $html_coluna_hora</td>
                 <td> $nome_profissional</td>
                 <td> $coluna_extra</td>";
                 
                 
                 if($nivel_usuario == 'A'){
                    
                         /*$sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";*/
    
                     $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimento', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto, gui_pag_gui.baixa_recebimento FROM gui_procedimentos_guia gui_pro_gui
                    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
                    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
                    JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
                    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";
                        $query_procedimentos      = mysql_query($sql_procedimentos, $banco_painel);
                                
                        if (mysql_num_rows($query_procedimentos)>0)
                        {  
                            $soma_preco_custo = 0;
                            $soma_valor_cobrado = 0;
                            
                            $status_list_confirma = array(
                               array("info" => "Confirmar"),
                               array("danger" => "Cancelar")
                            );
            
                            $html_bt_confirma = '&nbsp;';
                            
                            while ($dados_valor = mysql_fetch_array($query_procedimentos))
                            {
                                extract($dados_valor); 
                                
                                if($baixa_recebimento == 'S'){
                                    $status_conf = $status_list_confirma[1];
                                }
                                else
                                {
                                    $status_conf = $status_list_confirma[0];
                                }
                                
                                if($ativo == 'EMITIDO'){
                                    $html_bt_confirma = '<a href="inc/gui_ver_confirmar_pagamento_guia.php?id_guia='.$id_guia.'" id="bt_confirmar_'.$id_guia.'" data-target="#ajax" data-toggle="modal" class="btn btn-'.(key($status_conf)).' btn-block btn-sm" style="margin-top: 5px;">'.(current($status_conf)).'</a>';
                                }
                                
                                
                                $preco_custo_calc = moeda_db($preco_custo);
                                $valor_cobrado_exibe = $valor_cobrado;
                                
                                if($forma_faturamento_atendimento == 'PRECO_CUSTO'){
                                    $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                                }elseif($forma_faturamento_atendimento == 'PRECO_FINAL'){
                                    $soma_preco_custo = $soma_preco_custo + $valor_cobrado_exibe;
                                }elseif($forma_faturamento_atendimento == 'REPASSE_FINAL_NENOS_CUSTO'){
                                    $soma_preco_custo = $soma_preco_custo + ($preco_custo_calc - $valor_cobrado_exibe);
                                }
                                
                                //$soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                                //$soma_valor_cobrado = $soma_valor_cobrado + $valor_cobrado;
      
                            }
                            
                            /*if($forma_faturamento_atendimento == 'REPASSE_FINAL_NENOS_CUSTO'){
                                $soma_preco_custo = $soma_preco_custo - $soma_valor_cobrado;
                            }*/
                            
                            $soma_preco_custo = db_moeda4($soma_preco_custo);
                            
                            if($soma_preco_custo == 0.00){
                                $soma_preco_custo = 0;
                            }
                            
                            if(in_array("42", $verifica_lista_permissoes_array_inc)){
                                echo "<td>$soma_preco_custo</td>";
                            }else{
                                echo "<td> &nbsp; </td>";
                            }
                            
                            if($desconto > 0){
                            $soma_valor_cobrado = $valor_total_desconto;
                            }
                            
                            $soma_valor_cobrado = db_moeda4($soma_valor_cobrado);
                            if($soma_valor_cobrado == 0.00){
                                $soma_valor_cobrado = 0;
                            }
   
                        }
                        else{
                            echo "<td> &nbsp; </td>";
                        }

                 }
                 echo "<input type=\"hidden\" name=\"valor_custo_guias[]\" id=\"valor_custo_guias_$id_guia\" value=\"$soma_preco_custo\">";
                 echo "</tr>"; 
                 $i++;
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
                

<?php    
}
?>
                                   
    <div class="row">
        <div class="col-xs-8 invoice-block">
            
        </div>
        <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                        <span> &nbsp;&nbsp;Aguarde... </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>
        <!--<script src="assets/pages/scripts/table-datatables-editable.min.js" type="text/javascript"></script>-->