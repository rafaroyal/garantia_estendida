<script>
function retorno_click_nome_procedimento(id_procedimento, id_grupo_procedimento, nome_grupo_procedimento, id_convenio, preco_venda, preco_custo){
{
    $(".div_aguarde_2").show();
    var gui_id_procedimento             = id_procedimento;
    var gui_id_grupo_procedimento       = id_grupo_procedimento;
    //var gui_id_grupo_convenio       = id_convenio;
    var gui_nome_grupo_procedimento     = nome_grupo_procedimento;
    var gui_id_convenio                 = id_convenio;
    var gui_preco_venda                 = preco_venda;
    var gui_preco_custo                 = preco_custo;
    var gui_id_convenio_paciente_sel    = $("#id_convenio_paciente_sel").val();
    
    jQuery.ajax({
		type: "POST",
		url: 'inc/gui_localiza_procedimento_html_guia.php',
		data: {gui_id_procedimento: gui_id_procedimento, gui_id_grupo_procedimento: gui_id_grupo_procedimento, gui_id_convenio: gui_id_convenio, gui_preco_venda: gui_preco_venda, gui_preco_custo: gui_preco_custo},
        dataType : 'html',
		success: function(dados)
		{
		    jQuery('#get_nome_procedimento_guia').focus();
            $("#click_campo_gui_nome_procedimento").append(dados);
            jQuery("#get_id_procedimento").val('');
            jQuery("#get_nome_procedimento_guia").val('');
            $(".div_aguarde_2").hide();
            $(".html_info_novo_valor").hide();
            $("#desconto").val('');
            $("#resultado_campo_gui_nome_procedimento_gui").html('');
            var contar_procedimentos_sel = $('#contar_procedimentos_sel').val();
            if(contar_procedimentos_sel == 0){
                $("#html_tipo_procedimento").html('<div class="row note note-info"><h4 class="block">Tipo de agendamento da guia: <strong>' + gui_nome_grupo_procedimento + '</strong></h4></div>');
                $("#verifica_grupo_procedimento").val(gui_id_grupo_procedimento);
                $("#verifica_grupo_convenio").val(id_convenio);
            }
            
            soma_contar_procedimentos_sel = parseInt(contar_procedimentos_sel) + 1;
            $('#contar_procedimentos_sel').val(soma_contar_procedimentos_sel);
            
            if(soma_contar_procedimentos_sel == 25){
                $("#get_id_procedimento").hide();
                $("#get_nome_procedimento_guia").hide();
            }else{
                $("#get_id_procedimento").show();
                $("#get_nome_procedimento_guia").show();
            }
            
            if(gui_id_convenio == gui_id_convenio_paciente_sel){
                $("#html_tipo_convenio_procedimento_paciente").html('');
            }else{
                if(gui_id_convenio == 5){
                      $("#html_tipo_convenio_procedimento_paciente").html('');
                }else{
                    $("#html_tipo_convenio_procedimento_paciente").html('<div class="row note note-info"><div class="col-md-6"> <span style="color:red;">Campo obrigatório.</span><h4 class="block">Observações e motivos para seleção de procedimentos do PLANO ASISTENCIA para paciente PARTICULAR:</h4></div> <div class="col-md-6 form-group"><textarea name="tipo_convenio_procedimento_paciente" id="tipo_convenio_procedimento_paciente" rows="6" style="width: 100%; resize: none;"></textarea> </div></div>');
                }
                
            }
            
            var quant = document.getElementsByName("add_preco_venda[]");

            var soma = [].reduce.call(quant, function (somatorio, el) {
                return somatorio + parseFloat(el.value, 10) || 0;
            }, 0);
            
            soma = soma.toFixed(2);
            $("#valor_total_guia").val(soma);
            var soma_html = soma.replace(".", ",");
            document.getElementById("exibe_valor_total_guia").innerHTML = "R$ " + soma_html;
            
            var quant_custo = document.getElementsByName("add_preco_custo[]");

            var soma_custo = [].reduce.call(quant_custo, function (somatorio, el) {
                return somatorio + parseFloat(el.value, 10) || 0;
            }, 0);
            
            soma_custo = soma_custo.toFixed(2);
            $("#valor_total_guia_custo").val(soma_custo);
            var soma_html_custo = soma_custo.replace(".", ",");
            document.getElementById("exibe_valor_total_guia_custo").innerHTML = "R$ " + soma_html_custo;
            
            
            
            $("html, body").animate({ scrollTop: $('#html_tipo_convenio_procedimento_paciente').offset().top - 100 }, 1000);
		},
        error: function(){
            //$("#click_campo_gui_nome_procedimento").html(dados);
            $("#resultado_campo_gui_nome_procedimento_gui").html('');
            alert('Erro, tente novamente!');
            $(".div_aguarde_2").hide();
        }
        
    });
    /*$("#id_paciente").val(id_cliente).focus();
    $("#gui_nome_paciente").val(nome).focus();
    $("#data_nascimento").val(data_nascimento).focus();
    $("#resultado_campo_gui_nome_paciente").html('');
    $("html, body").animate({ scrollTop: $('#portlet_procedimentos').offset().top }, 1000);*/
    
}};

</script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$busca                      = (empty($_POST['gui_nome_procedimento'])) ? "" : verifica($_POST['gui_nome_procedimento']); 
$cod_procedimento_get       = (empty($_POST['get_id_procedimento'])) ? "" : verifica($_POST['get_id_procedimento']);
$sel_id_local_atendimento   = (empty($_POST['sel_id_local_atendimento'])) ? "" : verifica($_POST['sel_id_local_atendimento']);
$verifica_grupo_procedimento= (empty($_POST['verifica_grupo_procedimento'])) ? "" : verifica($_POST['verifica_grupo_procedimento']);
$verifica_grupo_convenio    = (empty($_POST['verifica_grupo_convenio'])) ? "" : verifica($_POST['verifica_grupo_convenio']);
$sel_procedimentos_sel      = (empty($_POST['sel_procedimentos_sel'])) ? "" : verifica($_POST['sel_procedimentos_sel']);
$id_convenio_paciente_sel   = (empty($_POST['id_convenio_paciente_sel'])) ? "" : verifica($_POST['id_convenio_paciente_sel']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);

?>
<div class="portlet box green">
<div class="portlet-title">
    <div class="caption">
        <i class="fa fa-heartbeat"></i>SELECIONE O PROCEDIMENTO</div>
</div>
<div class="portlet-body">
    <div class="table-scrollable" style="height: 400px;overflow-y: visible;">
<?php

if(!empty($cod_procedimento_get) AND $cod_procedimento_get > 0){
    $busca = $cod_procedimento_get;
}
$where_grupo_procedimento = '';
if(!empty($verifica_grupo_procedimento) AND $verifica_grupo_procedimento > 0){
    $where_grupo_procedimento = "AND pro.id_grupo_procedimento = $verifica_grupo_procedimento";
}
$where_grupo_convenio = '';
if(!empty($verifica_grupo_convenio) AND $verifica_grupo_convenio > 0){
    $where_grupo_convenio = "AND loc.id_convenio = $verifica_grupo_convenio";
}
$where_sel_procedimento = '';
/*if(!empty($sel_procedimentos_sel)){
    $array_sel_procedimentos_sel = implode(",", $sel_procedimentos_sel);
    $where_sel_procedimento = "AND pro.id_procedimento NOT IN ($array_sel_procedimentos_sel)";  
}*/

$where_id_convenio_paciente_sel = '';
/*if(empty($id_convenio_paciente_sel) OR $id_convenio_paciente_sel == 5){
    $where_id_convenio_paciente_sel = "AND loc.id_convenio = 5";
}*/


if(is_numeric($busca) === FALSE) {
    $busca_array = explode(' ', $busca);
    $array_v_busca = array();
    
    if(count($busca_array) > 1){
        foreach($busca_array as $v_busca){
            $array_v_busca[] = $v_busca."%";
        }
        $array_v_busca_sql = implode("", $array_v_busca);
        $where_busca = "AND (pro.nome LIKE '$array_v_busca_sql') "; 

    }else{
        $where_busca = "AND (pro.nome LIKE '%$busca_array[0]%') "; 
    }  
}else{
    /*if($busca == '__xxtodosxx__'){
    $where_busca = '';
    }else{*/
        $where_busca = "AND (pro.codigo = '$busca') "; 
    //}
}

$sql        = "SELECT pro.*, loc.id_convenio, loc.preco_custo, loc.preco_venda FROM gui_procedimentos pro
JOIN gui_local_atendimento_procedimentos loc ON pro.id_procedimento = loc.id_procedimento
WHERE pro.ativo = 'S' AND loc.preco_venda <> '' AND loc.id_local_atendimento = $sel_id_local_atendimento $where_busca $where_grupo_procedimento $where_sel_procedimento $where_id_convenio_paciente_sel $where_grupo_convenio
ORDER BY pro.nome DESC";

$query      = mysql_query($sql, $banco_painel);
$contar_linha = mysql_num_rows($query);                
if ($contar_linha == 0 AND count($busca_array) > 1){

    $where_busca = "AND (pro.nome LIKE '%$array_v_busca_sql') "; 
    
    $sql        = "SELECT pro.*, loc.id_convenio, loc.preco_custo, loc.preco_venda FROM gui_procedimentos pro
JOIN gui_local_atendimento_procedimentos loc ON pro.id_procedimento = loc.id_procedimento
WHERE pro.ativo = 'S' AND loc.preco_venda <> '' AND loc.id_local_atendimento = $sel_id_local_atendimento $where_busca $where_grupo_procedimento $where_sel_procedimento $where_id_convenio_paciente_sel $where_grupo_convenio
ORDER BY pro.nome DESC";

$query      = mysql_query($sql, $banco_painel);
    
}

//$where_busca = "AND (nome LIKE '%$busca%' OR codigo = '$busca') "; 
    /* $sql   = "SELECT pro.*, loc.id_convenio, loc.preco_venda  FROM gui_procedimentos pro
JOIN gui_local_atendimento_procedimentos loc ON pro.id_procedimento = loc.id_procedimento
WHERE pro.ativo = 'S' AND loc.preco_venda <> '' AND loc.id_local_atendimento = $sel_id_local_atendimento $where_busca $where_grupo_procedimento $where_sel_procedimento $where_id_convenio_paciente_sel $where_grupo_convenio
ORDER BY pro.id_procedimento DESC";*/

$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{
     echo "<table class=\"table table-hover table-light\">
            <thead>
                <tr>
                    <th width=\"5%\"> COD </th>
                    <th width=\"45%\"> PROCEDIMENTO </th>
                    <th width=\"10%\"> GRUPO </th>
                    <th width=\"15%\"> CONVÊNIO </th>";
                    if(in_array("42", $verifica_lista_permissoes_array_inc)){
                    echo "<th width=\"10%\"> CUSTO </th>";
                    }
                    echo "<th width=\"10%\"> VALOR </th>
                </tr>
            </thead>
            <tbody>";
    while ($dados = mysql_fetch_array($query))
    {
        extract($dados);  
        $sql_grupo  = "SELECT g.id_grupo_procedimento, g.nome FROM gui_grupo_procedimentos g
                                JOIN gui_procedimentos p ON p.id_grupo_procedimento = g.id_grupo_procedimento
                                WHERE p.id_procedimento = $id_procedimento";
        $query_grupo = mysql_query($sql_grupo) or die(mysql_error()." - 145");
        $nome_grupo = 'Sem grupo';
        $id_grupo_procedimento = '';
        if (mysql_num_rows($query_grupo)>0)
        {
            $id_grupo_procedimento = mysql_result($query_grupo, 0, 'id_grupo_procedimento');
            $nome_grupo            = mysql_result($query_grupo, 0, 'nome');
        }
        
        $sql_grupo  = "SELECT nome FROM gui_convenios
                        WHERE id_convenio = $id_convenio";
        $query_grupo = mysql_query($sql_grupo) or die(mysql_error()." - 145");
        $nome_convenio = 'Sem convênio';
        if (mysql_num_rows($query_grupo)>0)
        {
            $nome_convenio = mysql_result($query_grupo, 0, 'nome');
        }
            echo "<tr>
                <td>$codigo</td>
                <td><a href=\"javascript:\" onclick=\"return retorno_click_nome_procedimento('$id_procedimento', '$id_grupo_procedimento', '$nome_grupo', '$id_convenio', '".moeda_db($preco_venda)."', '".moeda_db($preco_custo)."')\" id=\"retorno_click_nome_paciente\">$nome</a></td>
                <td>$nome_grupo</td>
                <td>$nome_convenio</td>";
                if(in_array("42", $verifica_lista_permissoes_array_inc)){
                    echo "<td>".db_moeda($preco_custo)."</td>";
                }
                echo "<td>".db_moeda($preco_venda)."</td>
            </tr>";
    }
    
    echo " </tbody>
                </table>";
    
}else{
    echo "<strong>Sem resultado!</strong>";
}
               


?>

            </div>
        </div>
    </div>



                        
                    