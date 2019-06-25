<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<?php
require_once('../sessao.php');
require_once('functions.php');
$data_inicio            = (empty($_GET['data_inicio'])) ? "" : verifica($_GET['data_inicio']);  

?>
<div class="col-md-6"><div class="form-group"><label>Data de início:</label><div class="" data-date-format="dd-mm-yyyy" data-date-start-date="-30d"><input type="text" name="vencimento_primeira" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="-30d" value="<?php echo converte_data($data_inicio);?>" readonly ></div></div></div>