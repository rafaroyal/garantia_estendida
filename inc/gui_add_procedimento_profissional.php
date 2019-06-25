<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$id_local         = (empty($_GET['id_local'])) ? "" : verifica($_GET['id_local']);

$sql        = "SELECT pro.*, loc.id_convenio, loc.preco_venda, loc_ate.nome'nome_local_atendimento', loc_ate.cidade'cidade_local_atendimento' FROM gui_procedimentos pro
JOIN gui_local_atendimento_procedimentos loc ON pro.id_procedimento = loc.id_procedimento
JOIN gui_local_atendimento loc_ate ON loc.id_local_atendimento = loc_ate.id_local_atendimento
WHERE pro.ativo = 'S' AND loc.preco_venda <> '' AND loc.id_local_atendimento = $id_local
ORDER BY pro.id_procedimento DESC";

$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{
    echo "<select class=\"bs-select form-control select_procedimento_prof\" data-show-subtext=\"true\" data-live-search=\"true\" data-size=\"8\" name=\"select_procedimento_prof[]\" ><option data-content=\"&nbsp;\" value=\"\">&nbsp;</option>";
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
            

        echo "<option data-content=\"<div class='row'><div class='col-md-1 border_direita_col_local'>$codigo</div><div class='col-md-5 border_direita_col_local'>$nome</div><div class='col-md-3 border_direita_col_local'>$nome_convenio</div><div class='col-md-3'>R$ $preco_venda</div></div>\" value=\"$id_procedimento\">$nome</option>";
                
                
    }
    echo '</select>';

}else{
    echo "<strong>Sem resultado!</strong>";
}
               


?>

