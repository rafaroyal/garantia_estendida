<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script>
function retorno_click_nome_procedimento(id_procedimento){
{
    $("#div_aguarde_2_dados_procedimento").show();
    var gui_id_procedimento = id_procedimento;
    
    jQuery.ajax({
		type: "POST",
		url: 'inc/gui_localiza_procedimento_html.php',
		data: {gui_id_procedimento: gui_id_procedimento},
        dataType : 'html',
		success: function(dados)
		{
            $("#click_campo_gui_nome_procedimento").append(dados);
            jQuery("#get_id_procedimento").val('');
            jQuery("#get_nome_procedimento").val('');
            $("#div_aguarde_2_dados_procedimento").hide();
            $("#resultado_campo_gui_nome_procedimento").html('');
		},
        error: function(){
            //$("#click_campo_gui_nome_procedimento").html(dados);
            $("#resultado_campo_gui_nome_procedimento").html('');
            alert('Erro, tente novamente!');
            $("#div_aguarde_2_dados_procedimento").hide();
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
$banco_painel = $link;
$busca                   = (empty($_POST['gui_nome_procedimento'])) ? "" : verifica($_POST['gui_nome_procedimento']); 
$cod_procedimento_get    = (empty($_POST['get_id_procedimento'])) ? "" : verifica($_POST['get_id_procedimento']);

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

$where_busca = "AND nome LIKE '%$busca%' OR codigo = '$busca' "; 
$sql        = "SELECT * FROM gui_procedimentos
            WHERE ativo = 'S' $where_busca
            ORDER BY id_procedimento DESC";
$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{
     echo "<table class=\"table table-hover table-light\">
            <thead>
                <tr>
                    <th width=\"10%\"> COD </th>
                    <th width=\"65%\"> PROCEDIMENTO </th>
                    <th width=\"25%\"> GRUPO </th>
                </tr>
            </thead>
            <tbody>";
    while ($dados = mysql_fetch_array($query))
    {
        extract($dados);  
        $sql_grupo  = "SELECT g.nome FROM gui_grupo_procedimentos g
                                JOIN gui_procedimentos p ON p.id_grupo_procedimento = g.id_grupo_procedimento
                                WHERE p.id_procedimento = $id_procedimento";
        $query_grupo = mysql_query($sql_grupo) or die(mysql_error()." - 145");
        $nome_grupo = 'Sem grupo';
        if (mysql_num_rows($query_grupo)>0)
        {
            $nome_grupo = mysql_result($query_grupo, 0, 'nome');
        }
            echo "<tr>
                <td>$codigo</td>
                <td><a href=\"javascript:\" onclick=\"return retorno_click_nome_procedimento('$id_procedimento')\" id=\"retorno_click_nome_paciente\">$nome</a></td>
                <td>$nome_grupo</td>
                
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



                        
                    