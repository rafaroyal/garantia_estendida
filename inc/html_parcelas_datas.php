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
$soma_total_parcela            = (empty($_GET['soma_total_parcela'])) ? "" : verifica($_GET['soma_total_parcela']);  
$valor_entrada            = (empty($_GET['valor_entrada'])) ? "" : verifica($_GET['valor_entrada']);  
$parcela_menos_um         = (empty($_GET['parcela_menos_um'])) ? "" : verifica($_GET['parcela_menos_um']);  
$agora = date('d-m-Y');
$data_soma_script = somar_datas( 1, 'm'); // adiciona 1 mes a sua data                       
$data_script = date('d-m-Y', strtotime($data_soma_script));
//$data_script = converte_data($data_script);

if($valor_entrada == 0 OR $valor_entrada == ''){
    $valor_entrada = $soma_total_parcela;
}
?>
<div class="col-md-6"><div class="form-group" style="text-align: right;"><label>Valor Entrada:</label><div class="input-icon" style="margin-top: 6px;font-size: 18px;"><strong>R$ <?php echo $valor_entrada;?></strong></div></div></div><div class="col-md-6"><div class="form-group"><label>Vencimento Entrada:</label><div class="" data-date-format="dd-mm-yyyy" data-date-start-date="-30d" data-date-end-date="+30d"><input type="text" name="vencimento_entrada" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="-30d" data-date-end-date="+30d" value="<?php echo $agora;?>" readonly ></div></div></div><div class="col-md-6" style="text-align: right;"><div class="form-group"><label>&nbsp;</label><div class="input-icon" style="margin-top: 6px;">+ <?php echo $parcela_menos_um;?> parcelas de <strong style="font-size: 18px;">R$ <?php echo $soma_total_parcela;?></strong></div></div></div><div class="col-md-6"><div class="form-group"><label>Vencimento 1° parcela:</label><div class="" data-date-format="dd-mm-yyyy" data-date-start-date="-90d" data-date-end-date="+90d"><input type="text" name="vencimento_primeira" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="-90d" data-date-end-date="+90d" value="<?php echo $data_script;?>" readonly ></div></div></div>