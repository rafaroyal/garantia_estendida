<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script>
function retorno_click_nome_procedimento(id_cliente, nome, data_nascimento){
{
    /*$("#id_paciente").val(id_cliente).focus();
    $("#gui_nome_paciente").val(nome).focus();
    $("#data_nascimento").val(data_nascimento).focus();
    $("#resultado_campo_gui_nome_paciente").html('');
    $("html, body").animate({ scrollTop: $('#portlet_procedimentos').offset().top }, 1000);*/
    
}};
function remove_procedimento_local_guia(id){
{   
    $('#grupo_procedimento_' + id).remove();
    var contar_procedimentos_sel = $('#contar_procedimentos_sel').val();
    soma_contar_procedimentos_sel = parseInt(contar_procedimentos_sel) - 1;
    if(soma_contar_procedimentos_sel == 0){
        $("#html_tipo_procedimento").html('');
        $("#verifica_grupo_procedimento").val('');
        $("#verifica_grupo_convenio").val('');
        $("#portlet_profissional").hide();
        $("#bt_submit_guia").hide();
        $("#bt_avancar_passo4_guia").show();
        $("#html_tipo_convenio_procedimento_paciente").html('');
    }
    
    if(soma_contar_procedimentos_sel < 25){
        $("#get_id_procedimento").show();
        $("#get_nome_procedimento_guia").show();
    }
    $(".html_info_novo_valor").hide();
    $("#desconto").val('');
    $('#contar_procedimentos_sel').val(soma_contar_procedimentos_sel);
    
    var quant = document.getElementsByName("add_preco_venda[]");

    var soma = [].reduce.call(quant, function (somatorio, el) {
        return somatorio + parseFloat(el.value, 10) || 0;
    }, 0);
    
    soma = soma.toFixed(2);
    $("#valor_total_guia").val(soma);
    var soma_html = soma.replace(".", ",");
    document.getElementById("exibe_valor_total_guia").innerHTML = "R$ " + soma_html;
    
    
    
    
    
    var quant = document.getElementsByName("add_preco_custo[]");

    var soma = [].reduce.call(quant, function (somatorio, el) {
        return somatorio + parseFloat(el.value, 10) || 0;
    }, 0);
    
    soma = soma.toFixed(2);
    $("#valor_total_guia_custo").val(soma);
    var soma_html = soma.replace(".", ",");
    document.getElementById("exibe_valor_total_guia_custo").innerHTML = "R$ " + soma_html;

}};
</script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$cod_procedimento_get         = (empty($_POST['gui_id_procedimento'])) ? "" : verifica($_POST['gui_id_procedimento']);
$gui_id_grupo_procedimento    = (empty($_POST['gui_id_grupo_procedimento'])) ? "" : verifica($_POST['gui_id_grupo_procedimento']);
$gui_id_convenio              = (empty($_POST['gui_id_convenio'])) ? "" : verifica($_POST['gui_id_convenio']);
$gui_preco_venda              = (empty($_POST['gui_preco_venda'])) ? "" : verifica($_POST['gui_preco_venda']);
$gui_preco_custo              = (empty($_POST['gui_preco_custo'])) ? "" : verifica($_POST['gui_preco_custo']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);

$sql        = "SELECT p.codigo, p.nome, p.id_grupo_procedimento, g.nome'nome_grupo', p.obs FROM gui_procedimentos p
            JOIN gui_grupo_procedimentos g ON p.id_grupo_procedimento = g.id_grupo_procedimento
            WHERE p.ativo = 'S' AND p.id_procedimento = $cod_procedimento_get";
$query      = mysql_query($sql, $banco_painel);
$codigo_procedimento = '';
$nome_procedimento = '';
$id_grupo_procedimento = '';

if (mysql_num_rows($query)>0)
{
    $codigo_procedimento = mysql_result($query, 0, 'codigo');
    $nome_procedimento   = mysql_result($query, 0, 'nome');
    $nome_grupo          = mysql_result($query, 0, 'nome_grupo');
    $obs                 = mysql_result($query, 0, 'obs');
    $id_time_stamp_div   = time();
     echo "<div class=\"portlet box green-meadow\" id=\"grupo_procedimento_$id_time_stamp_div\">
     <input type=\"hidden\" name=\"add_procedimento[]\" value=\"$cod_procedimento_get\">
        <div class=\"portlet-title\"><div class=\"caption\">$codigo_procedimento - $nome_procedimento</div> <div class=\"tools\"><a href=\"javascript:;\" class=\"collapse\" data-original-title=\"\" title=\"\"> </a> <a href=\"javascript:;\" onclick=\"return remove_procedimento_local_guia('$id_time_stamp_div')\" class=\"remove\" data-original-title=\"\" title=\"\"></a></div></div>
        <div class=\"portlet-body\"><div class=\"row\">";
    $sql_convenio  = "SELECT nome'nome_convenio' FROM gui_convenios
                    WHERE ativo = 'S' AND id_convenio = $gui_id_convenio";
    $query_convenio = mysql_query($sql_convenio) or die(mysql_error()." - 145");
    $nome_convenio = 'sem convenio';
    if (mysql_num_rows($query_convenio)>0)
    {
       $nome_convenio = mysql_result($query_convenio, 0, 'nome_convenio');
    }
            echo "
            <input type=\"hidden\" name=\"add_id_convenio[]\"  value=\"$gui_id_convenio\"> 
            <input type=\"hidden\" name=\"add_id_grupo_procedimento[]\" value=\"$gui_id_grupo_procedimento\">
            <input type=\"hidden\" name=\"add_preco_venda[]\" value=\"$gui_preco_venda\">
            <div class=\"col-md-12\">
            Convênio selecionado:  <strong>$nome_grupo $nome_convenio</strong> <br/>";
            if(in_array("42", $verifica_lista_permissoes_array_inc)){
                
                echo "<input type=\"hidden\" name=\"add_preco_custo[]\" value=\"$gui_preco_custo\">
                Valor custo: <strong>".db_moeda($gui_preco_custo)."</strong> <br/>";
            }
            
            echo "Valor cobrado: <strong>".db_moeda($gui_preco_venda)."</strong> <br/> <br/>
            <strong>Observações:</strong> $obs <br/>  
            </div>";
        
    echo "</div> </div>";
    
}else{
    echo "<strong>Sem resultado!</strong>";
}
               
?>
