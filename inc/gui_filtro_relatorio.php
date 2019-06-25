
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
$apenas_emissao         = (empty($_GET['apenas_emissao'])) ? "" : verifica($_GET['apenas_emissao']);  
$select_buscar_por      = (empty($_GET['select_buscar_por'])) ? "" : verifica($_GET['select_buscar_por']);  
$todos_guias_faturar    = (empty($_GET['todos_guias_faturar'])) ? "" : verifica($_GET['todos_guias_faturar']);  

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

?>

<?php

if($tipo == 'local_atendimento')
{
    
?>
<div class="invoice">
    <div class="row invoice-logo">
        <div class="col-xs-2">
           <a class="btn red btn-outline btn-circle" onclick="javascript:window.print();"> Imprimir
                <i class="fa fa-print"></i>
            </a>
            <div class="tools"> </div>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-xs-12">
            <!--<table class="table table-condensed table-hover">-->
            <!--<table class="table table-striped table-bordered table-hover" id="sample_1">-->
            <!--<table class="table table-condensed table-hover" id="sample_1">-->
            
<?php
$html_coluna_data = "";
$html_coluna_hora = "";
$hora_agendamento_agenda = "";
$sql_verifica_filtro = "";
if($select_buscar_por == 'data_agendamento'){
    $sql_verifica_filtro = "AND (gu.data_agendamento BETWEEN '$data1' AND '$data2')";
    $html_coluna_data = "AGENDADO PARA:";
    $html_coluna_hora = ", gu.hora_agendamento'hora_agendamento_agenda'";
}elseif($select_buscar_por == 'data_cadastro'){
    $sql_verifica_filtro = "AND (gu.data_cadastro BETWEEN '$data1' AND '$data2')";
    $html_coluna_data = "CADASTRADO EM:";
}elseif($select_buscar_por == 'data_emissao'){
    $sql_verifica_filtro = "AND (gu.data_emissao BETWEEN '$data1' AND '$data2')";
    $html_coluna_data = "DATA DE EMISSÃO:";
}elseif($select_buscar_por == 'faturamento_parceiro'){
    $select_buscar_por = 'data_agendamento';
    $html_coluna_data = "AGENDADO PARA:";
    $sql_verifica_filtro = "AND (gu.data_agendamento BETWEEN '$data1' AND '$data2')";
}


echo " <div class=\"portlet-body\">
        <div class=\"table-scrollable\">
            <table class=\"table table-hover\" id=\"sample_1\">";

                if($nivel_usuario == 'A'){
                
                echo "<thead>
                    <tr role=\"row\" class=\"heading\">
                        <th>PACIENTE</th>
                        <th>LOCAL</th>
                        <th>$html_coluna_data</th>
                        <th>PROFISSIONAL</th>
                        <th>CIDADE</th>
                        <th>PARCEIRO</th>
                        <th>STATUS</th>
                        <th>CUSTO</th>
                        <th>COBRADO</th>
                        <th>PAGAMENTO</th>
                    </tr>
                </thead>
                ";
                
            }else{

                echo "<thead>
                     <tr role=\"row\" class=\"heading\">
                        <th width=\"30%\"> PACIENTE </th>
                        <th width=\"20%\"> LOCAL </th>
                        <th width=\"20%\"> CIDADE </th>
                        <th width=\"20%\"> USUARIO </th>
                        <th width=\"10%\"> STATUS </th>
                    </tr>
                </thead>
                ";
            }
   
        echo "<tbody>";
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
        
        $where_apenas_emissao = '';
        if($apenas_emissao == 'S'){
            $where_apenas_emissao = "AND gu.status = 'EMITIDO'";
        }
        
        $where_guias_faturar = '';
        if($todos_guias_faturar == 'S'){
            $where_guias_faturar = "AND gupg.baixa_faturado = 'N'";
            $sql_verifica_filtro = '';
        }
        
        $where_id_local_atendimento = '';
        if($id_local_atendimento != 'todos'){
            $where_id_local_atendimento = "AND gu.id_local_atendimento = $id_local_atendimento";
        }
        
        $where_id_profissional = '';
        if($id_profissional != 'todos' AND $id_profissional != 'undefined'){
            $where_id_profissional = "AND gu.id_profissional = $id_profissional";
        }
        
        
        
            
        $sql        = "SELECT gu.*, gu.$select_buscar_por'html_coluna_data'$html_coluna_hora FROM gui_guias gu
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    JOIN gui_pagamentos_guias gupg ON gu.id_pagamento = gupg.id_pagamento
                    WHERE gu.del = 'N' $where_id_parceiro $sql_verifica_filtro $where_apenas_emissao $where_guias_faturar $where_id_local_atendimento $where_id_profissional
                    ORDER BY gu.$select_buscar_por DESC";
//echo $sql;
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
                }else
                {
                    $sql_parceiro  = "SELECT nome FROM usuarios
                                        WHERE id_usuario = $id_usuario";
                    $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                    $coluna_extra = 'Sem Usuario';
                    $coluna_extra_2 = '';
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        $coluna_extra_2 = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                    }
                    //$coluna_extra = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
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

                $sql_local_atendimento  = "SELECT nome, cidade FROM gui_local_atendimento
                                  WHERE id_local_atendimento = $id_local_atendimento";
                $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
                $nome_local_atendimento = '-';
                if (mysql_num_rows($query_local_atendimento)>0)
                {
                    $nome_local_atendimento = mysql_result($query_local_atendimento, 0, 'nome');
                    $cidade_local_atendimento = mysql_result($query_local_atendimento, 0, 'cidade');
                }
                
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
                 <td> $html_dados_completo $nome_paciente</td>
                 <td> $nome_local_atendimento</td>
                 <td> ".converte_data($html_coluna_data)." $html_coluna_hora</td>
                 <td> $nome_profissional</td>
                 <td> $cidade_local_atendimento</td>
                 <td> $coluna_extra</td>
                 <td> $coluna_extra_2";
                 echo ' <a href="inc/gui_ver_guia.php?id_guia='.$id_guia.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa" style="margin-top: 5px;"><i class="fa fa-search"></i></a>';
                 echo "</td>";
                 
                 
                 if($nivel_usuario == 'A'){
                    
                         /*$sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";*/
    
                     $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto, gui_pag_gui.baixa_recebimento FROM gui_procedimentos_guia gui_pro_gui
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
                                $valor_cobrado_exibe = db_moeda($valor_cobrado);
                                
                                $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                                $soma_valor_cobrado = $soma_valor_cobrado + $valor_cobrado;
      
                            }
                            
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
                            echo "<td>$soma_valor_cobrado</td>";
                            echo "<td>$html_bt_confirma</td>";
                            
                        }
                        else{
                            echo "<td> &nbsp; </td>";
                            echo "<td> &nbsp; </td>";
                            echo "<td> &nbsp; </td>";
                        }

                 }
                 
                 echo "</tr>"; 
                 
            }
            
        }
?>      
        </tbody>
    </table> 
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