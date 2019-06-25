<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');

$id_local   = (empty($_GET['id_local'])) ? "" : verifica($_GET['id_local']);
$periodo                = (empty($_GET['periodo'])) ? "" : verifica($_GET['periodo']);  
if($id_local != 'todos'){
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
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
       
        <div class="form-group">
         
            <?php 

            $sql_local      = "SELECT g_loc.nome, g_fat.periodo_inicio, g_fat.periodo_fim, g_fat.data_cadastro, g_fat.id_profissional, g_fat.id_usuario_baixa FROM gui_local_atendimento g_loc
                            JOIN gui_faturamentos_guias g_fat ON g_loc.id_local_atendimento = g_fat.id_local_atendimento
                            WHERE g_loc.id_local_atendimento = '$id_local' AND g_fat.mes_referencia = '' AND g_fat.ano_referencia = ''";
            $query_local      = mysql_query($sql_local);
            if (mysql_num_rows($query_local)>0)
            {
                echo "<label class=\"control-label\">Último faturamnto:</label>";

            }else{
                
                echo "<p>Sem histórico de Faturamento para o período selecionado <strong style=\"text-transform: uppercase;\">$mes_data1</strong>.</p>";
                
            }
            
             ?>

         </div>
         &nbsp;
<?php
}
?>