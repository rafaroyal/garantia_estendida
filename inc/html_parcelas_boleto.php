<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
/**
 * @author [$Rafael]
 * @copyright [$2014]
 */


$id_parceiro_login         = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);

if($id_parceiro_login == ''){
    $id_parceiro_login = base64_decode($_COOKIE["usr_parceiro"]);
}

 
$sql_parcelas_parceiro        = "SELECT parcelas_boleto FROM parceiros
    WHERE id_parceiro = $id_parceiro_login";
    $query_parcelas_parceiro      = mysql_query($sql_parcelas_parceiro);
                    
    if (mysql_num_rows($query_parcelas_parceiro)>0)
    {

        echo "<input type=\"hidden\" name=\"valor_parcela_boleto\" id=\"valor_parcela_boleto\" value=\"\"/><div class='col-md-3' id=\"div_parcela_parcelas_boleto\"><div class='form-group form-md-line-input form-md-floating-label'><select class='form-control' id='parcela_parcelas_boleto' name='parcela_parcelas_boleto'><option value=''></option>";
        
        $parcelas_boleto = mysql_result($query_parcelas_parceiro, 0, 'parcelas_boleto');
        $parcelas_boleto_array = explode("|", $parcelas_boleto);
        $contar_array = count($parcelas_boleto_array) - 1;
        
        for($i = 0; $i<=$contar_array;$i++){
            $txt_parc = 'vezes';
            if($parcelas_boleto_array[$i] == 1){
                $txt_parc = 'vez';
            }
            echo "<option value='$parcelas_boleto_array[$i]'>$parcelas_boleto_array[$i] $txt_parc</option>";
            
        }
        
        echo "</select><label for='parcela_parcelas_boleto'>Parcelar em:</label></div></div><div class='col-md-8 txt_parcela_boleto' style='margin-top: 30px;'></div>";
    }
?>