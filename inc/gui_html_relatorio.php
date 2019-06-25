<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>


<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$valor    = (empty($_GET['valor'])) ? "" : verifica($_GET['valor']);



if($valor == 'profissional'){
    
    
    
}elseif($valor == 'local_atendimento'){
?>
<div class="portlet-body form">
    <div class="form-body">
        <div class="row note note-info">
            <h4 class="block">Relatório relativo ao período &nbsp; <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span></h4> 
                <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label ">Período</label>
                    <div class="">
                        <div id="reportrange" class="btn default">
                            <!--<i class="fa fa-calendar"></i> &nbsp;-->
                            <span style="font-size: 0.8em;" id="periodo"> </span>
                            <b class="fa fa-angle-down"></b>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>
<?php    
}elseif($valor == 'guia'){
    
    
    
}elseif($valor == 'paciente'){
    
    
    
}
?>