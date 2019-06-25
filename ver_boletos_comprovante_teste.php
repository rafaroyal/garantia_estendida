<html lang="pt_br">

<head>
        <meta charset="utf-8" />
        <title>Boletos</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        
        <link rel="shortcut icon" href="favicon.ico" /> 
         <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script>

jQuery(document).ready(function() {    
   
   jQuery('#printButton').on('click', function () {
    $(".modal-header").hide();
    window.print();
    $(".modal-header").show();
});

jQuery('#fechar_janela').on('click', function () {
    window.close();
});
   
});


</script>
<style>
.row_boleto div{
    float: left;
    position: relative; 
}
.linha{
    margin-bottom: 10px;
}
body{
    font-size: 11px;
    font-family: Arial,sans-serif!important;
}

.modal-body{
    padding: 0 0 0 30px;
}
@media print {
    .page_break_boleto {page-break-after: always; }
    strong{font-weight: bold!important;}
    body{
        font-family: Arial,sans-serif!important;
    }
}

#tabela_boleto{
    font-size: 8px;
    line-height: 1.7em;
}

#tabela_boleto, tr, td{
    border: 1px solid;
}

td, th {
    padding: 1px 4px;
}

#tabela_boleto span.normal{
    width: 100%;
    float: left;
    text-align: center;
    font-size: 12px;
}

#tabela_boleto span.normal_esquerda{
    width: 100%;
    float: left;
    text-align: left;
    font-size: 12px;
}

#tabela_boleto span.menor_direita{
    width: 100%;
    float: left;
    text-align: right;
    font-size: 10px;
}

#tabela_boleto span.menor_esquerda{
    width: 100%;
    float: left;
    text-align: left;
    font-size: 10px;
}

#tabela_boleto span.normal_bold{
    width: 100%;
    float: left;
    text-align: center;
    font-size: 12px;
    font-weight: bold;
}

#tabela_boleto span.normal_bold_direita{
    width: 100%;
    float: left;
    text-align: right;
    font-size: 12px;
    font-weight: bold;
}

#tabela_boleto span.grande{
    width: 100%;
    float: left;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    letter-spacing: 0.04em;
}

#tabela_boleto span.grande_direita{
    width: 100%;
    float: left;
    text-align: right;
    font-size: 14px;
    font-weight: bold;
}

hr{
    margin-top: 2px;
    margin-bottom: 2px;
    border-style: dashed;
    border-color: #000;
}

.box_linha_geral{
    width: 100%;
    margin-bottom: 5px;
}

.box_linha_digitavel{
    width: 102px;
    margin: 0px 1.3px;
}

.margin_barra{
    margin-left: 40px;
    margin-bottom: 5px;
}

.border_esquerda_none{
    border-left-color: #fff;
}

.border_direita_none{
    border-right-color: #fff;
}

.border_topo_none{
        border-top-color: #fff;
}

.border_base_none{
    border-bottom-color: #fff;
}

</style>
</head>
<body class="page-container-bg-solid page-header-menu-fixed">

<?php
require_once('sessao.php');
require_once('inc/functions.php');
require_once('inc/conexao.php'); 

?>


<div class="modal-header">
    <button class="btn btn-primary" id="printButton">Imprimir</button>
    <button type="button" class="btn default" id="fechar_janela" >Fechar</button>
</div>
<div class="modal-body">
    <div class="row row_boleto" style="width: 740px;">
    <div style="height: 350px;">
         
    
        <div style="width: 690px;height: 303px;border-bottom: 2px dashed #000000;" class="folha" >
        <div class="linha" style="margin-top: 10px;">
            <div style="width: 521px;text-decoration: underline;">
                <strong>RECIBO DE ENTREGA</strong>
            </div>
            <div style="width: 169px;text-align: right;text-decoration: underline;">
                <strong>data: <?php echo agora(); ?></strong>
            </div>
        </div>
        <div class="linha">
            <div style="width: 80px;">
                <div>
                    <label class="control-label "><strong># Pedido:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                    10012
                    </div>
                </div>
            </div>
            
            <div style="width: 472px;">
                <div >
                    <label class="control-label "><strong>Tipo Entrega:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                    entrega_em_maos    
                    </div>
                </div>
            </div>
            <div style="width: 138px;text-align: right;">
                <div >
                    <label class="control-label "><strong>Vencimento 1° parcela:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                        <strong>01/01/2011</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="linha">
        <div style="width: 552px;">
            <div>
                <label class="control-label "><strong>Cliente:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                fulano de tal    
                </div>
            </div>
        </div>
        <div style="width: 138px;text-align: right;">
            <div>
                <label class="control-label "><strong>Valor da Parcela:</strong></label>
                <div class="col-md-12" style="width: 100%;">
                    <strong>R$ 100,00</strong>
                </div>
            </div>
        </div>
        </div>
        <div class="linha">
            <div style="width: 294px;">
                <div >
                    <label class="control-label "><strong>Funcionário:</strong></label>
                    <div class="col-md-12"style="width: 100%;">
                    nomafsdd sf
                    </div>
                    
                </div>
            </div>
            <div style="width: 295px;text-align: right;">
                <div >
                    <label class="control-label"><strong>Parceiro:</strong></label>
                    <div class="col-md-12" style="width: 100%;padding: 0;">
                       f dffd sdsf
                    </div>
                </div>
            </div>
            <div style="width: 100px;text-align: right;">
                <div>
                    <label class="control-label "><strong>Cod. Recibo:</strong></label>
                    <div class="col-md-12" style="width: 100%;">
                        3423
                    </div>
                </div>
            </div>
        </div>
        <div class="linha">
            <div style="width: 690px;">
                <div>
                    <label class="control-label "><strong>Descrição:</strong></label>
                    <div class="col-md-12"style="width: 100%;">
                    Recebi da <strong>Trail Serviços Assistência Familiar</strong>, meu carnê de pagamento. E estou ciente das condições presentes neste carnê de pagamento e sua data de vencimento.
                    </div>
                </div>
            </div>
            
        </div>
        <div class="linha">
            <div style="width: 485px;">
                <div>
                    <label class="control-label "><strong>&nbsp;</strong></label>
                    <div class="col-md-12"style="width: 100%;">
                    Assinatura do Cliente/Responsável:_________________________________________
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
   

        <div style=" page-break-after: always !important;"></div>

        </div>
    </div>     
        
</div>


        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
       
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
    
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
