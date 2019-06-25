<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
<script>

jQuery(document).ready(function() {   
jQuery("#cidade").change(function() {
        
        if (jQuery(this).val().length >= '1') {
        	var id_cidade = jQuery("#cidade option:selected").attr('rel');
            //$("#cidade").find('option').removeAttr("selected");
            //var cidade = jQuery("#cidade").val();
            //$('#cidade option[rel=' + id_cidade + ']').attr('selected','selected');
            
            $("#id_cidade").val(id_cidade);
             }
     });   
});
</script>
<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 

$estado   = (empty($_POST['estado'])) ? "" : verifica($_POST['estado']);  
        $sql_user_pedido        = "SELECT loc_nu_sequencial, loc_nosub, ufe_sg FROM log_localidade
                                WHERE ufe_sg = '$estado'";
        $query_user_pedido      = mysql_query($sql_user_pedido);
                        
        if(mysql_num_rows($query_user_pedido)>0)
        {
            echo "
            <div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"cidade\" name=\"cidade\" ><option value=\"\"></option>";
            
            while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
            {
                extract($dados_user_pedido);

                echo "<option value=\"$loc_nosub\" rel=\"$loc_nu_sequencial\">$loc_nosub</option>";
            }
            
            echo "</select></div>";
        }
        
         ?> 
         
