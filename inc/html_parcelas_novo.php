<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

$parcelas            = (empty($_GET['parcelas'])) ? "" : verifica($_GET['parcelas']);  
$valor_parcela         = (empty($_GET['valor_parcela'])) ? "" : verifica($_GET['valor_parcela']);  

for($i=1;$i<=$parcelas;$i++){
    
    if($i == 1){
        $data_vencimento = 5;
    }else{
        if($i == 2){
            $data_vencimento = 30;
        }else{
            $n_i = $i - 1;
            $data_vencimento = 30 * $n_i; 
        }
        
    }
    
    $data = somar_datas( $data_vencimento, 'd'); // adiciona 3 meses a sua data
    $data_termino = date('d-m-Y', strtotime($data));

?>

<div class="col-xs-6">&nbsp;</div>
<div class="col-xs-2"><h4><?php echo $i; ?></h4></div>
<div class="col-xs-2"> 
    <div class="" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
        <input type="text" name="vencimentos[]" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="+0d" value="<?php echo $data_termino; ?>" readonly >
    </div>
</div>
<div class="col-xs-2">
    <h4 style="float: right;">R$ <?php echo $valor_parcela; ?></h4>
</div>

<?php
}
?>
<input type="hidden" id="valor_parcelas" value="<?php echo $valor_parcela; ?>" disabled=""/>