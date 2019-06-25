<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
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

    $sql_parcelas_parceiro        = "SELECT parcelas_cartao FROM parceiros
    WHERE id_parceiro = $id_parceiro_login";
    $query_parcelas_parceiro      = mysql_query($sql_parcelas_parceiro);        
    if (mysql_num_rows($query_parcelas_parceiro)>0)
    {
        echo "<strong>Máquina de Cartão</strong>. Antes de registrar venda, confirme o pagamento na máquina de cartão.<div class='col-md-3'><div class='form-group form-md-line-input form-md-floating-label'><div class='input-group input-group-sm'><div class='input-group-control'><input type='text' name='comprovante_doc' class='form-control' id='comprovante_doc' value=''/><label for='comprovante_doc'>DOC:</label><span class='help-block'>Somente números..</span></div></div></div></div><div class='col-md-3'><div class='form-group form-md-line-input form-md-floating-label'><div class='input-group input-group-sm'><div class='input-group-control'><input type='text' name='comprovante_maquina' class='form-control' id='comprovante_maquina' value=''/><label for='comprovante_maquina'>AUT:</label><span class='help-block'>Somente números..</span></div><span class='input-group-btn btn-right'><a class='btn red btn-outline sbold' data-toggle='modal' href='#responsive'><i class='fa fa-info'></i></a></span></div></div></div><input type=\"hidden\" name=\"valor_parcela_boleto\" id=\"valor_parcela_boleto\" value=\"\"/><div class='col-md-3' id=\"div_parcela_parcelas_boleto\"><div class='form-group form-md-line-input form-md-floating-label'><select class='form-control' id='parcela_parcelas_boleto' name='parcela_parcelas_boleto'><option value=''></option>";
        
        $parcelas_boleto = mysql_result($query_parcelas_parceiro, 0, 'parcelas_cartao');
        $parcelas_boleto_array = explode("|", $parcelas_boleto);
        $contar_array = count($parcelas_boleto_array) - 1;
        
        for($i = 0; $i<=$contar_array;$i++){
            $txt_parc = 'vezes';
            if($parcelas_boleto_array[$i] == 1){
                $txt_parc = 'vez';
            }
            echo "<option value='$parcelas_boleto_array[$i]'>$parcelas_boleto_array[$i] $txt_parc</option>";
            
        }
        
        echo "</select><label for='parcela_parcelas_boleto'>Parcelar em:</label></div></div><div class='col-md-9 txt_parcela_boleto' style='margin-top: 30px;display: block!important;'></div>";
    }
?>