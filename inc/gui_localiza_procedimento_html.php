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

</script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$cod_procedimento_get    = (empty($_POST['gui_id_procedimento'])) ? "" : verifica($_POST['gui_id_procedimento']);

$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);

$sql        = "SELECT p.codigo, p.nome, p.id_grupo_procedimento, g.nome'nome_grupo' FROM gui_procedimentos p
            JOIN gui_grupo_procedimentos g ON p.id_grupo_procedimento = g.id_grupo_procedimento
            WHERE p.ativo = 'S' AND p.id_procedimento = $cod_procedimento_get";
$query      = mysql_query($sql, $banco_painel);
$codigo_procedimento = '';
$nome_procedimento = '';
$id_grupo_procedimento = '';

if (mysql_num_rows($query)>0)
{
    $codigo_procedimento = mysql_result($query, 0, 'codigo');
    $nome_procedimento = mysql_result($query, 0, 'nome');
    $nome_grupo = mysql_result($query, 0, 'nome_grupo');

     echo "<div class=\"portlet box green-meadow\" id=\"grupo_procedimento_$cod_procedimento_get\">
     <input type=\"hidden\" name=\"add_procedimento[]\" value=\"$cod_procedimento_get\">
        <div class=\"portlet-title\"><div class=\"caption\">$codigo_procedimento - $nome_procedimento</div> <div class=\"tools\"><a href=\"javascript:;\" class=\"collapse\" data-original-title=\"\" title=\"\"> </a> <a href=\"javascript:;\" onclick=\"return remove_procedimento_local('$cod_procedimento_get')\" class=\"remove\" data-original-title=\"\" title=\"\"></a></div></div>
        <div class=\"portlet-body\"><div class=\"row\">";
    $sql_convenio  = "SELECT id_convenio, nome'nome_convenio' FROM gui_convenios
                    WHERE ativo = 'S'";
    $query_convenio = mysql_query($sql_convenio) or die(mysql_error()." - 145");
    if (mysql_num_rows($query_convenio)>0)
    {
        $contar_convenios = 0;
        while ($dados = mysql_fetch_array($query_convenio))
        {
            extract($dados);  
            
            echo "<div class=\"col-md-2\">
            <strong>$nome_convenio</strong>
            <input type=\"hidden\" name=\"add_id_convenio[]\"  value=\"$id_convenio\">            
            <div class=\"form-group form-md-line-input form-md-floating-label\">
                <input type=\"text\" name=\"valor_custo[]\" class=\"form-control\" id=\"valor_custo_$id_convenio\" value=\"\" onkeydown=\"FormataMoeda(this,10,event)\" onkeypress=\"return maskKeyPress(event)\"/>
                <label for=\"valor_custo\">Valor de Custo</label>
                <span class=\"help-block\">Apenas números....</span>
             </div>
             <div class=\"form-group form-md-line-input form-md-floating-label\">
                <input type=\"text\" name=\"valor_final[]\" class=\"form-control\" id=\"valor_final_$id_convenio\" value=\"\" onkeydown=\"FormataMoeda(this,10,event)\" onkeypress=\"return maskKeyPress(event)\"/>
                <label for=\"valor_final\">Valor Final</label>
                <span class=\"help-block\">Apenas números....</span>
             </div>
            </div>";
            $contar_convenios++;
        }
        echo "<input type=\"hidden\" name=\"add_contar_convenios[]\" value=\"$contar_convenios\">";
    }
        
    echo "</div> </div>";
    
}else{
    echo "<strong>Sem resultado!</strong>";
}
               
?>
