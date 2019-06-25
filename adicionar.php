<?php
$origem = $_SERVER['HTTP_REFERER'];
if (empty($origem))
{
    header("Location: inicio.php");
}
require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
require_once('inc/permissoes.php');
$item = (empty($_GET['item'])) ? "" : $_GET['item'];
$expire = $_COOKIE["usr_time"];
$pasta = base64_decode($_COOKIE["pasta"]);
setcookie("item",    base64_encode($item),    $expire, "/".$pasta);
$nivel_usuario      = base64_decode($_COOKIE["usr_nivel"]);
$id_usuario_sessao  = base64_decode($_COOKIE["usr_id"]);
$id_parceiro        = base64_decode($_COOKIE["usr_parceiro"]);
$id_parceiro_sessao = $id_parceiro;
$id_filial          = base64_decode($_COOKIE["usr_filial"]);
 include ('inc/titulo.php');

?>

<html lang="pt_br">
<head>
        <meta charset="utf-8" />
        <title><?php echo titulo;?> | Listar</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
       <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
         <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
         <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        
         <link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <!--<link rel="stylesheet" href="assets/global/plugins/offline/themes/offline-theme-chrome.css" />
        <link rel="stylesheet" href="assets/global/plugins/offline/themes/offline-language-portuguese-brazil.css" />-->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> 
        
        </head>
<body class="page-container-bg-solid page-header-menu-fixed">
        <!-- BEGIN HEADER -->
        <div class="page-header">
            <!-- BEGIN HEADER TOP -->
            <div class="page-header-top">
                <div class="container-fluid">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="inicio.php">
                            <img src="assets/global/img/logo-default.jpg" alt="logo" class="logo-default"/>
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler"></a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <?php include('inc/topnav.php'); ?>
                </div>
            </div>
            <!-- END HEADER TOP -->
            <!-- BEGIN HEADER MENU -->
            <div class="page-header-menu">
                <div class="container-fluid">
                    <!-- BEGIN MEGA MENU -->
                    <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                    <div class="hor-menu  hor-menu-light">
                      <?php include('inc/menu.php'); ?> 
                    </div>
                    <!-- END MEGA MENU -->
                </div>
            </div>
            <!-- END HEADER MENU -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <!-- BEGIN PAGE HEAD-->
                <div class="page-head">
                    <div class="container-fluid">
                        <!-- BEGIN PAGE TITLE -->
                        <div class="page-title">
                            <h1><?php echo $titulo; ?>
                            </h1>
                        </div>
                        <!-- END PAGE TITLE -->
                        
                    </div>
                </div>
                <!-- END PAGE HEAD-->
                <!-- BEGIN PAGE CONTENT BODY -->
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- BEGIN PAGE BREADCRUMBS -->
                        <?php
                        include ('inc/breadcrumbs.php');
                        echo $bc;
                        ?>
                        <!-- END PAGE BREADCRUMBS -->
                        <!-- BEGIN PAGE CONTENT INNER -->
                        <div class="page-content-inner">
                        
                            <div class="row">
                                <div class="col-md-12">
                                   <?php
                    	               include ('inc/msg_status.php');
                    	           ?>
                                   <?php
                                   if ($item == 'parceiros' AND $nivel_usuario == 'A')
                                   {
                                    
                                    ?>
                                    <form role="form" name="form" id="form_adicionar" action="adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Parceiro</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            
                                            
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group form-md-radios">
                                                    <label>Tipo de Cadastro</label>
                                                    <div class="md-radio-inline">
                                                        <div class="md-radio">
                                                            <input type="radio" id="radio1" name="tipopessoa" class="md-radiobtn" onclick="return alterna();" value="PF"/>
                                                            <label for="radio1">
                                                                <span class="inc"></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Pessoa Física (PF) </label>
                                                        </div>
                                                        <div class="md-radio">
                                                            <input type="radio" id="radio2" name="tipopessoa" class="md-radiobtn" onclick="return alterna();" value="PJ"/>
                                                            <label for="radio2">
                                                                <span class="inc"></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Pessoa Jurídica (PJ) </label>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div id="form_PF_PJ"></div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                

                                        </div>
                                    </div>
                                    <div class="portlet light " id="box_pro" style="display: none;">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase">Produtos</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="row">
                                                    
                                                    <div class="col-md-3">
                                                    <h5>MÉTODO DE PAGAMENTO</h5>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="metodo_pagamento[]" value="MA" id="metodo_pagamento_1" class="md-check"/>
                                                                <label for="metodo_pagamento_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> MAQUINA DE CARTÃO </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="metodo_pagamento[]" value="ON" id="metodo_pagamento_2" class="md-check"/>
                                                                <label for="metodo_pagamento_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> ONLINE </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="metodo_pagamento[]" value="BO" id="metodo_pagamento_3" class="md-check"/>
                                                                <label for="metodo_pagamento_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> BOLETO </label>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <h5>EMISSÃO BOLETO</h5>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="emissao_boleto[]" value="LOJA" id="emissao_boleto_1" class="md-check"/>
                                                                <label for="emissao_boleto_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> LOJA </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="emissao_boleto[]" value="BANCO" id="emissao_boleto_2" class="md-check"/>
                                                                <label for="emissao_boleto_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> BANCO </label>
                                                            </div>
                                                           
                                                        </div>
                                                      
                                                        <h5>CONFIGURAÇÃO BOLETO</h5>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            
                                                                <div class="input-group-control">
                                                                    <select class="form-control" id="cobr_tipo_cobranca" name="cobr_tipo_cobranca">
                                                                        <option value=""></option>
                                                                        <option value="boleto_registrado">Boleto Registrado</option>
                                                                        <option value="boleto_arrecadacao">Arrecadação</option>
                                                                        <option value="carne_loja">Carnê Loja</option>
                                                                    </select>
                                                                    <label for="cobr_tipo_cobranca">Tipo Cobrança</label>
                                                                </div>
                                                            
                                                        </div>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            
                                                                <div class="input-group-control">
                                                                    <select class="form-control" id="cobr_banco" name="cobr_banco">
                                                                        <option value=""></option>
                                                                        <option value="caixa">CAIXA ECONÔMICA</option>
                                                                        <option value="santander">SANTANDER</option>
                                                                        <option value="bradesco">BRADESCO</option>
                                                                        <option value="loja">LOJA CARNÊ</option>
                                                                    </select>
                                                                    <label for="cobr_banco">Banco</label>
                                                                </div>
                                                            
                                                        </div>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" name="cobr_codigo_cliente" class="form-control" id="cobr_codigo_cliente" value="" />
                                                                <label for="cobr_codigo_cliente">Cod. Cliente</label>
                                                                <span class="help-block">Somente numeros...</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" name="cobr_agencia" class="form-control" id="cobr_agencia" value="" />
                                                                <label for="cobr_agencia">Agencia</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" name="cobr_conta" class="form-control" id="cobr_conta" value="" />
                                                                <label for="cobr_conta">Conta</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <hr />
                                                        <h5>PLANO ADICIONAL</h5>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="valor_plano" class="form-control" id="valor_plano" value="" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)"/>
                                                            <label for="valor_plano">Valor do Plano</label>
                                                            <span class="help-block">Somente números...</span>
                                                        </div>
                                                       
                                                        <h5>MÍNIMO DE ENTRADA</h5>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="porcentagem_entrada" class="form-control" id="porcentagem_entrada" value="" maxlength="3"/>
                                                            <label for="valor_plano">Em %</label>
                                                            <span class="help-block">Somente números...</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <h5>TIPO DE PAGAMENTO</h5>
                                                        <div class="md-checkbox-list">
                                                            <!--<div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="avista" id="tipo_pagamento_1" class="md-check"/>
                                                                <label for="tipo_pagamento_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> À vista </label>
                                                            </div>-->
                                                            <!--<div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="entrada_recorrente_cartao" id="tipo_pagamento_2" class="md-check"/>
                                                                <label for="tipo_pagamento_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Entrada + Recorrente Crédito </label>
                                                            </div>-->
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="parcelado_cartao" id="tipo_pagamento_3" class="md-check"/>
                                                                <label for="tipo_pagamento_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span>Parcelado Cartão</label>
                                                            </div>
                                                            <!--<div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="parcelado_cartao_recorrente" id="tipo_pagamento_4" class="md-check"/>
                                                                <label for="tipo_pagamento_4">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Parcelado Crédito Recorrente </label>
                                                            </div>-->
                                                            <!--<div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="recorrente_cartao" id="tipo_pagamento_5" class="md-check"/>
                                                                <label for="tipo_pagamento_5">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Recorrente Crédito </label>
                                                            </div>-->
                                                            <!--<div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="fidelidade" id="tipo_pagamento_6" class="md-check"/>
                                                                <label for="tipo_pagamento_6">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Cartão Fidelidade </label>
                                                            </div>-->
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="entrada_parcelado_boleto" id="tipo_pagamento_7" class="md-check"/>
                                                                <label for="tipo_pagamento_7">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span>Parcelado Boleto</label>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <h5>PERMITIR DESCONTO?</h5>
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio">
                                                                    <input type="radio" id="desconto1" name="desconto" class="md-radiobtn" value="S"/>
                                                                    <label for="desconto1">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> SIM! </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="desconto2" name="desconto" class="md-radiobtn" value="N"/>
                                                                    <label for="desconto2">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> NÃO! </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-3">
                                                    <h5>PARCELAS CARTÃO</h5>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="1" id="parcelas_cartao_1" class="md-check"/>
                                                                <label for="parcelas_cartao_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 1 vez </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="2" id="parcelas_cartao_2" class="md-check"/>
                                                                <label for="parcelas_cartao_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 2 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="3" id="parcelas_cartao_3" class="md-check"/>
                                                                <label for="parcelas_cartao_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 3 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="4" id="parcelas_cartao_4" class="md-check"/>
                                                                <label for="parcelas_cartao_4">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 4 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="5" id="parcelas_cartao_5" class="md-check"/>
                                                                <label for="parcelas_cartao_5">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 5 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="6" id="parcelas_cartao_6" class="md-check"/>
                                                                <label for="parcelas_cartao_6">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 6 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="7" id="parcelas_cartao_7" class="md-check"/>
                                                                <label for="parcelas_cartao_7">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 7 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="8" id="parcelas_cartao_8" class="md-check"/>
                                                                <label for="parcelas_cartao_8">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 8 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="9" id="parcelas_cartao_9" class="md-check"/>
                                                                <label for="parcelas_cartao_9">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 9 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="10" id="parcelas_cartao_10" class="md-check"/>
                                                                <label for="parcelas_cartao_10">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 10 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="11" id="parcelas_cartao_11" class="md-check"/>
                                                                <label for="parcelas_cartao_11">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 11 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="12" id="parcelas_cartao_12" class="md-check"/>
                                                                <label for="parcelas_cartao_12">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 12 vezes </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <h5>PARCELAS BOLETO</h5>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="1" id="parcelas_boleto_1" class="md-check"/>
                                                                <label for="parcelas_boleto_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 1 vez </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="2" id="parcelas_boleto_2" class="md-check"/>
                                                                <label for="parcelas_boleto_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 2 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="3" id="parcelas_boleto_3" class="md-check"/>
                                                                <label for="parcelas_boleto_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 3 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="4" id="parcelas_boleto_4" class="md-check"/>
                                                                <label for="parcelas_boleto_4">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 4 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="5" id="parcelas_boleto_5" class="md-check"/>
                                                                <label for="parcelas_boleto_5">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 5 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="6" id="parcelas_boleto_6" class="md-check"/>
                                                                <label for="parcelas_boleto_6">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 6 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="7" id="parcelas_boleto_7" class="md-check"/>
                                                                <label for="parcelas_boleto_7">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 7 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="8" id="parcelas_boleto_8" class="md-check"/>
                                                                <label for="parcelas_boleto_8">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 8 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="9" id="parcelas_boleto_9" class="md-check"/>
                                                                <label for="parcelas_boleto_9">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 9 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="10" id="parcelas_boleto_10" class="md-check"/>
                                                                <label for="parcelas_boleto_10">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 10 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="11" id="parcelas_boleto_11" class="md-check"/>
                                                                <label for="parcelas_boleto_11">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 11 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="12" id="parcelas_boleto_12" class="md-check"/>
                                                                <label for="parcelas_boleto_12">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 12 vezes </label>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <h5>PERMITIR ENTRADA?</h5>
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio">
                                                                    <input type="radio" id="entrada1" name="entrada" class="md-radiobtn" value="S"/>
                                                                    <label for="entrada1">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> SIM! </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="entrada2" name="entrada" class="md-radiobtn" value="N"/>
                                                                    <label for="entrada2">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> NÃO! </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <h5>VALOR ENTRADA AUTOMATICA?</h5>
                                                        <div class="form-group form-md-radios">
                                                            <div class="md-radio-list">
                                                                <div class="md-radio">
                                                                    <input type="radio" id="valor_entrada_automatica1" name="valor_entrada_automatica" class="md-radiobtn" value="S"/>
                                                                    <label for="valor_entrada_automatica1">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> SIM! </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="valor_entrada_automatica2" name="valor_entrada_automatica" class="md-radiobtn" value="N"/>
                                                                    <label for="valor_entrada_automatica2">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> NÃO! </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                <div class="col-md-3">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <div class="input-group input-group-sm">
                                                                <div class="input-group-control">
                                                                <?php
                                                                    $sql       = "SELECT id_produto, nome FROM produtos
                                                                                    WHERE ativo = 'S'";
                                                                    $query      = mysql_query($sql);
                                                                                    
                                                                    if (mysql_num_rows($query)>0)
                                                                    {
                                                                        echo "<select class=\"form-control\" id=\"selecionar_produtos\" name=\"selecionar_produtos\"><option value=\"\"></option>";
                                                                        
                                                                        while ($dados = mysql_fetch_array($query))
                                                                        {
                                                                            extract($dados);  
                                                                            
                                                                            echo "<option value=\"$id_produto\">$nome</option>";
                                                                        }
                                                                        echo "</select>";
                                                                    }
                                                                
                                                                ?>    
                                                                
                                                                <label for="selecionar_produtos">Produtos</label>
                                                                </div>
                                                                <span class="input-group-btn btn-right">
                                                                    <button class="btn green-haze" type="button" onclick="return add_produto_parceiro();"><i class="fa fa-plus "></i> Adicionar</button>
                                                                </span>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="form_add_produto"></div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>                     
                                            </div>
                                        </div>
                                                
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar Parceiro</button>
                                                    </div>
                                                </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                    <?php
                                   }elseif ($item == 'grupos_parceiros' AND $nivel_usuario == 'A'){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar" action="adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Grupo</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            
                                            <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                                <div class="form-body">
                                                    <div class="row">
                                                      <div class="col-md-12 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value=""/>
                                                            <label for="nome">Nome</label>
                                                            <span class="help-block">Como será chamado o grupo...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                

                                        </div>
                                    </div>
                                                   
                                            </div>
                                        </div>
                                                
                                            <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn blue">Salvar Grupo</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                    <?php
                                    }elseif ($item == 'clientes'){
                                        $tipo        = (empty($_GET['tipo'])) ? "" : $_GET['tipo'];
                                        
                                        if($tipo == 'produto')
                                        {
                                    ?>
                                    <form role="form" name="form" id="form_adicionar_cliente" action="adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <input type="hidden" name="exibe_form_cliente" id="exibe_form_cliente" value="nao"/>
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <span class="caption-subject bold uppercase">Selecione o Plano</span>
                                            </div>
                                            
                                        </div>
                                        <div class="portlet-body form">
                                           
                                                <div class="form-body">
                                                    <div class="row ">
                                                      
                                                      <div class="col-md-4 ">

                                                      <input type="hidden" name="tipo_get" id="tipo_get" value="<?php echo $tipo; ?>" />
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <?php 
                                                            
                                                            $sql_grupo_produto        = "SELECT gpro.id_grupo_produto, gpro.nome, gpro.info_pagamento, gpro.dependente_titular, gpro.dependente_adicional, bpro.slug FROM grupos_produtos gpro
                                                            JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
                                                            JOIN produtos pro ON prog.id_produto = pro.id_produto
                                                            JOIN parceiros_servicos pser ON gpro.id_grupo_produto = pser.id_grupo_produto
                                                            JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                                                            WHERE gpro.del = 'N' AND pser.id_parceiro = $id_parceiro 
                                                            GROUP BY gpro.id_grupo_produto";
                                                            $query_grupo_produto      = mysql_query($sql_grupo_produto);
                                                                            
                                                            if (mysql_num_rows($query_grupo_produto)>0)
                                                            {
                                                                
                                                                echo "<label class=\"control-label \">Plano</label>
                                                        <div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_grupo_produto\" name=\"select_grupo_produto[]\" ><option value=\"\" selected=\"\"></option>";
                                                                $novo_slug = '';
                                                                $novo_id_grupo_produto = '';
                                                                while($dados_grupo_produto = mysql_fetch_array($query_grupo_produto))
                                                                {
                                                                    extract($dados_grupo_produto);
                                                                    
                                                                    $sql_grupo_slug        = "SELECT bpro.slug FROM grupos_produtos gpro
                                                                    JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
                                                                    JOIN produtos pro ON prog.id_produto = pro.id_produto
                                                                    JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                                                    JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                                                                    WHERE gpro.del = 'N' AND pser.id_parceiro = $id_parceiro AND gpro.id_grupo_produto = $id_grupo_produto
                                                                    GROUP BY bpro.slug ORDER BY bpro.id_base_produto";
                                                                    $query_grupo_slug      = mysql_query($sql_grupo_slug);
                                                                                    
                                                                    if (mysql_num_rows($query_grupo_slug)>1)
                                                                    {
                                                                        
                                                                        while($dados_grupo_slug = mysql_fetch_array($query_grupo_slug))
                                                                        {
                                                                            
                                                                            extract($dados_grupo_slug);
                                                                            
                                                                            if($novo_slug == '')
                                                                            {
                                                                                $novo_slug = $slug;
                                                                            }
                                                                            else{
                                                                                $slug = $novo_slug."|".$slug;
                                                                            }

                                                                            
                                                                            
                                                                            
                                                                        }
                                                                        
                                                                    }
                                                                    
                                                                    
                                                                    
                                                                    echo "<option data=\"$slug\" ref=\"$info_pagamento\" dependente_titular=\"$dependente_titular\" dependente_adicional=\"$dependente_adicional\" value=\"$id_grupo_produto\">$nome</option>";
                                                                }
                                                                
                                                                echo "</select></div>";
                                                            }
                                                            
                                                             ?>
                                                         </div>
                                                         &nbsp;
                                                      </div>
                                                      <div class="col-md-4 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                         <div id="sel_produto_add"></div>
                                                         </div>
                                                         <span id="div_aguarde_sel_plano" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, carregando dados...</span>
                                                         &nbsp;
                                                      </div>
                                                    </div>
                                                    
                                                </div> 
                                        </div>
                                    </div>
                                            
                                   
                                    <!-- Begin: life time stats -->
                                    <div id="dados_cliente">
                                        
                                    </div>
                                    <?php
                                    
                                    if($nivel_usuario == 'P'){
                                    ?>
                                    <div id="dados_usuario_pedido" style="display: none;">
                                        <div class="portlet light ">
                                            <div class="portlet-title">
                                                <div class="caption font-green">
                                                    <span class="caption-subject bold uppercase">Víncular venda</span>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="portlet-body form">
                                           
                                                <div class="form-body">
                                                    <div class="row ">
                                                      
                                                      <div class="col-md-4 ">

                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                             
                                                            <?php 
                                                            // AND id_usuario <> $id_usuario_sessao
                                                            $sql_user_pedido        = "SELECT id_usuario, nome FROM usuarios
WHERE id_parceiro = $id_parceiro AND ativo = 'S' AND del = 'N'";
                                                            $query_user_pedido      = mysql_query($sql_user_pedido);
                                                                            
                                                            if(mysql_num_rows($query_user_pedido)>0)
                                                            {
                                                                echo "<label class=\"control-label \">Selecione o vendedor</label>
                                                                <div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_user_pedido\" name=\"select_user_pedido\" ><option value=\"\" selected=\"\"></option>";
                                                                
                                                                while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
                                                                {
                                                                    extract($dados_user_pedido);

                                                                    echo "<option value=\"$id_usuario\">$nome</option>";
                                                                }
                                                                
                                                                echo "</select></div>";
                                                            }
                                                            
                                                             ?>
                                                             
                                                          </div>
                                                      </div>
                                                      <div class="col-md-4 ">

                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                             
                                                           <?php 
                                                           $sel_parceiro = "WHERE pa.id_parceiro = $id_parceiro";


                                                           $sql_parceiros        = "SELECT fi.id_filial, fi.id_filial_integracao, fi.nome, fi.cidade FROM filiais fi
                                                           JOIN parceiros pa ON fi.id_parceiro = pa.id_parceiro
                                                           $sel_parceiro";
                                                           $query_parceiros      = mysql_query($sql_parceiros);
                                                                                
                                                               if (mysql_num_rows($query_parceiros)>0)
                                                               {
                                                                    
                                                                   echo "<label class=\"control-label \">Filial</label>
                                                            <div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_filial\" name=\"select_filial\" ><option value=\"\" selected=\"\"></option> ";
                                                                    
                                                                   while($dados_parceiros = mysql_fetch_array($query_parceiros))
                                                                   {
                                                                       extract($dados_parceiros);
                                                                        
                                                                       echo "<option value=\"$id_filial\" data-subtext=\"$cidade\">$nome</option>";
                                                            
                                                                   }
                                                                
                                                             echo "</select></div>";
    }
                                                             ?>
                                                             
                                                          </div>
                                                      </div>
                                                    </div>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                                    <?php  
                                    }
                                    ?>
                                    
                                    
                                    <div class="portlet light" id="info_add_produto_html" style="display: none;">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Informações de Pagamento</span>
                                            </div>
                                            
                                        </div>
                                        <div class="portlet-body form" >
                                                <div class="form-body">
                                                   <div class="row">
                                                        <div class="col-md-12">
                                                        <div id="sel_produto_info"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    
                                    <div id="inputs_hidden_sem_pagamentos">
                                        
                                    </div>
                                    
                                   
                                        </div>
                                            <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn blue" onclick="return bt_submit_action();" id="bt_add_submit">Registrar venda</button> <span class="div_aguarde" style="display: none;position: absolute;width: 100%;left: 0;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, registrando venda...</span>
                                                </div>
                                            </div>
                                            
                                             <?php        
                                                }
                                            ?>
                                                    </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                    <?php
                                    }elseif ($item == 'usuarios' AND ($nivel_usuario == 'A' OR $nivel_usuario == 'P') AND in_array("20", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar" action="adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Usuário</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            
                                            <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                                <div class="form-body">
                                                    <div class="row">
                                                    <div class="col-md-5 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                         <label class="control-label ">Parceiro</label>
                                                         <div class="">
                                                        
                                                        <?php  
                                                            
                                                            if($nivel_usuario == 'A'){
                                                                
                                                                $sql_parceiros        = "SELECT pa.id_parceiro, pa.nome, pa.cidade FROM parceiros pa
                                                                JOIN parceiros_grupos pg ON pa.id_parceiro = pg.id_parceiro
                                                                WHERE pa.del = 'N' ";
                                                                $query_parceiros      = mysql_query($sql_parceiros);
                                                                                
                                                                if (mysql_num_rows($query_parceiros)>0)
                                                                {
                                                                    
                                                                    echo "<select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_parceiro_user\" name=\"select_parceiro_user\" ><option value=\"\" selected=\"\"></option>";       
                                                                    
                                                                    while($dados_parceiros = mysql_fetch_array($query_parceiros))
                                                                    {
                                                                        extract($dados_parceiros);
                                                                        
                                                                        echo "<option value=\"$id_parceiro\" data-subtext=\"$cidade\">$nome</option>";
                                                                    }
                                                                    
                                                                    echo "</select>";
                                                                }
                                                                
                                                            }else{
                                                                
                                                                $sql_parceiros        = "SELECT nome FROM parceiros
                                                                WHERE del = 'N' AND id_parceiro = $id_parceiro_sessao";
                                                                $query_parceiros      = mysql_query($sql_parceiros);
                                                                                
                                                                if (mysql_num_rows($query_parceiros)>0)
                                                                {
                                                                    $nome        = mysql_result($query_parceiros, 0, 'nome');
                                                                    
                                                                }
                                                                
                                                            ?>
                                                            <p class="form-control-static"> <?php echo $nome; ?> </p>
                                                            <input type="hidden" class="form-control form-filter input-sm" name="select_parceiro_user" value="<?php echo $id_parceiro_sessao; ?>"/>
                                                            
                                                            
                                                            <?
                                                            }
                                                            

                                                            
                                                        ?>
                                                        
                                                        </div>
                                                        </div>
                                                        
                                                     </div>
                                                     <div class="col-md-4 ">
                                                     <?php
                                                     if($nivel_usuario == 'A'){
                                                     ?>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                        <div id="lista_filial"></div>
                                                        </div>
                                                        <?php
                                                        }
                                                        ?>
                                                        
                                                        <?php
                                                        if($nivel_usuario == 'P'){
                                                        ?>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                        <?php
                                                            $sel_parceiro = "WHERE pa.id_parceiro = $id_parceiro_sessao";

                                                            $sql_parceiros        = "SELECT fi.id_filial, fi.id_filial_integracao, fi.nome, fi.cidade FROM filiais fi
                                                        JOIN parceiros pa ON fi.id_parceiro = pa.id_parceiro
                                                        $sel_parceiro";
                                                            $query_parceiros      = mysql_query($sql_parceiros);
                                                                        
                                                            if (mysql_num_rows($query_parceiros)>0)
                                                            {
                                                                
                                                                echo "<label class=\"control-label \">Filial</label>
                                                        <div class=\"\"><select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" data-show-subtext=\"true\" id=\"select_filial\" name=\"select_filial\" ><option value=\"\" selected=\"\"></option> ";
                                                                
                                                                while($dados_parceiros = mysql_fetch_array($query_parceiros))
                                                                {
                                                                    extract($dados_parceiros);
                                                                    
                                                                    if($id_filial_integracao > 0){
                                                                        echo "<option value=\"$id_filial_integracao\" data-subtext=\"$cidade\">$nome</option>";
                                                                    }else{
                                                                        echo "<option value=\"$id_filial\" data-subtext=\"$cidade\">$nome</option>";
                                                                    }
                                                                    
                                                                }
                                                                
                                                                echo "</select></div>";
                                                            }
                                                            ?>
                                                            </div>
                                                            <?php   
                                                        }
                                                        ?>
                                                         
                                                     </div>
                                                      <div class="col-md-3 ">
                                                      &nbsp;
                                                          <div class="form-group form-md-line-input form-md-floating-label">
                                                          
                                                            <select class="form-control" id="nivel" name="nivel">
                                                                <option value=""></option>
                                                                <?php
                                                                    if($nivel_usuario == 'A'){
                                                                ?>
                                                                    <option value="A"> Administrador </option>
                                                                <?php
                                                                    }
                                                                ?>
                                                                <option value="P"> Parceiro </option>
                                                                <option value="U"> Usuário </option>
                                                                 
                                                            </select>
                                                            <label for="nivel">Nível de Acesso</label>
                                                          </div>
                                                         &nbsp;
                                                       </div>
                                                     </div>
                                                     <div class="row">
                                                           <div class="col-md-6">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" name="nome" class="form-control" id="nome" value=""/>
                                                                    <label for="nome">Nome</label>
                                                                    <span class="help-block">Como será chamado o usuário...</span>
                                                                </div>
                                                                &nbsp;
                                                           </div>
                                                           <div class="col-md-6">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" name="email" class="form-control" id="email" value=""/>
                                                                    <label for="email">E-mail</label>
                                                                    <span class="help-block">e-mail de contato...</span>
                                                              </div>
                                                              &nbsp;
                                                           </div>
                                                      </div>
                                                      <div class="row"> 
                                                          <div class="col-md-3">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" name="login" class="form-control" id="login" value="" onkeyup="this.value=this.value.replace(' ','')"/>
                                                                    <label for="login">Usuário</label>
                                                                    <span class="help-block">Min.4 / Max.20 caracteres...</span>
                                                              </div>
                                                              &nbsp;
                                                           </div>
                                                           <div class="col-md-9">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                                        <span>&nbsp;</span>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="row">
                                                           <div class="col-md-3">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="password" name="senha" class="form-control" id="senha" value=""/>
                                                                    <label for="senha">Senha</label>
                                                                    <span class="help-block">mínimo 6 cadacteres...</span>
                                                              </div>
                                                              &nbsp;
                                                           </div>
                                                           <div class="col-md-9">
                                                           <div class="form-group form-md-line-input form-md-floating-label">
                                                                        <span>&nbsp;</span>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="row">
                                                           <div class="col-md-3">
                                                              <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" id="status" name="status">
                                                                <option value=""></option>
                                                                <option value="N"> Ativo </option>
                                                                <option value="S"> Inativo </option>
                                                                 
                                                            </select>
                                                            <label for="status">Status</label>
                                                          </div>
                                                           </div>
                                                           &nbsp;
                                                           <div class="col-md-9">
                                                           <div class="form-group form-md-line-input form-md-floating-label">
                                                                        <span>&nbsp;</span>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       </div>
                                                       </div>
                                                

                                        </div>
                                         <?php
                                            if($nivel_usuario == 'A' AND $nivel_usuario == 0 AND in_array("25", $verifica_lista_permissoes_array_inc)){
                                         ?>
                                                      <div class="portlet light ">
                                            <div class="portlet-title">
                                                <div class="caption font-green">
                                                    <span class="caption-subject bold uppercase">PERMISSÕES</span>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="portlet-body form">
                                           
                                                <div class="form-body">
                                                    
                                                        <div class="row">
                                                       
                                                           <?php
                                                            
                                                            $sql_permissoes        = "SELECT id_grupo_permissao, nome'nome_grupo_permissao' FROM grupos_permissoes
                                                            WHERE del = 'N'
                                                            ORDER BY nome";
                                                            $query_permissoes      = mysql_query($sql_permissoes);
                                                                        
                                                            if (mysql_num_rows($query_permissoes)>0)
                                                            {
                                                               
                                                                $contar_perm = 1;
                                                                while($dados_permissoes = mysql_fetch_array($query_permissoes))
                                                                {
                                                                    extract($dados_permissoes);
                                                                    echo "<div class=\"col-md-12\"><div class=\"well\"><h4>$nome_grupo_permissao</h4></div>";
                                                                    
                                                                    $sql_lista_permissoes        = "SELECT id_permissao, nome'nome_permissao', obs FROM permissoes
                                                                    WHERE id_grupo_permissao = $id_grupo_permissao AND del = 'N' 
                                                                    ORDER BY nome";
                                                            $query_lista_permissoes      = mysql_query($sql_lista_permissoes);
                                                                        
                                                                    if (mysql_num_rows($query_lista_permissoes)>0)
                                                                    {
                                                                        echo "<div class=\"form-group form-md-checkboxes\">
                                                    
                                                                        <div class=\"md-checkbox-inline\">
                                                        
                                                        
                                                    ";
                                                    
                                                    
                                                                        while($dados_lista_permissoes = mysql_fetch_array($query_lista_permissoes))
                                                                        {
                                                                            extract($dados_lista_permissoes);
                                                                            
                                                                            echo "<div class=\"md-checkbox\" style=\"width: 23%;\">";
                                                                            $hide_input = "";
                                                                            if($id_permissao == 60){
                                                                                $hide_input = " style=\"display: none;\"";
                                                                            ?>
                                                                                    <style>
                                                                                    
                                                                                    .border_direita_col_local{
                                                                                        border-right: 1px solid;
                                                                                    }
                                                                                    </style>
                                                                                    <?php
                                                                                   $where_id_cidade = 'WHERE g_loc_ate.ativo = "S"
                                                                                                        GROUP BY g_loc_ate.id_local_atendimento';
                                                                                    if($nivel_usuario != 'A'){
                                                                                        $sql        = "SELECT id_cidade FROM parceiros
                                                                                            WHERE id_parceiro = $id_parceiro_sessao";
                                                                                        $query      = mysql_query($sql);
                                                                                                        
                                                                                        if (mysql_num_rows($query)>0)
                                                                                        {
                                                                                            $id_cidade = mysql_result($query, 0,0); 
                                                                                            $where_id_cidade = "WHERE g_cid_loc.loc_nu_sequencial = $id_cidade AND g_loc_ate.ativo = 'S'";
                                                                                        }
                                                                                    }
                                                                                    
                                                                                    $sql_contar        = "SELECT g_loc_ate.id_local_atendimento, g_loc_ate.nome'nome_local_atendimento', g_loc_ate.tipo, g_loc_ate.cidade, g_loc_ate.estado FROM gui_cidades_locais g_cid_loc
                                                                                    JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
                                                                                    $where_id_cidade ";
                                                                                    $query_contar      = mysql_query($sql_contar);
                                                                                    if (mysql_num_rows($query_contar)>0)
                                                                                    {
                                                                                        echo "<select class=\"bs-select form-control\" data-show-subtext=\"true\" data-live-search=\"true\" data-size=\"8\" name=\"select_local_guia\" id=\"select_local_guia\"><option data-content=\"Víncular Local de atendimento\" value=\"\">Víncular Local de atendimento</option>";
                                                                                        while ($dados = mysql_fetch_array($query_contar))
                                                                                        {
                                                                                            extract($dados); 
                                                                                            
                                                                                            echo "<option data-content=\"<div class='row'><div class='col-md-1 border_direita_col_local'>$id_local_atendimento</div><div class='col-md-5'>$nome_local_atendimento</div></div>\" value=\"$id_local_atendimento\">$nome_local_atendimento</option>";
                                                                                             
                                                                                        }
                                                                                        
                                                                                        echo '</select>';
                                                                                    }
                                                                                    ?>
                                                                                  
                                                                            
                                                                            <?php    
                                                                            }
                                                                            
                                                                            echo "<input type=\"checkbox\" id=\"separa_permissao_$contar_perm\" name=\"lista_permissoes[]\" class=\"md-check\" value=\"$id_permissao\" $hide_input>
                                                                            <label for=\"separa_permissao_$contar_perm\" $hide_input>
                                                                                <span></span>
                                                                                <span class=\"check\"></span>
                                                                                <span class=\"box\"></span> $nome_permissao </label>";
                                                                            
                                                                            
                                                                            
                                                                            echo "</div>";
                                                                            $contar_perm++;
                                                                        }
                                                                        
                                                                        echo "</div>
                                                                            </div>";
                                                                    }
                                                                    
                                                                    echo "</div>";
                                                                    
                                                                }
                                                             }
                                                                    
                                                        
                                                            ?>
                                                              
                                                           
                                                           
                                                       </div>
                                                       </div>
                                                       </div>
                                                       </div>
                                               <?php
                                                 }
                                                 ?>
                                                
                                    </div>
                                                   
                                            </div>
                                        </div>
                                                
                                            <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn blue">Salvar Usuário</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                    <?php
                                    }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE CONTENT INNER -->
<div class="modal fade" id="idle-timeout-dialog" data-backdrop="static">
                                <div class="modal-dialog modal-small">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Sua sessão está prestes a expirar.</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <i class="fa fa-warning font-red"></i> Sua sessão será bloqueada
                                                <span id="idle-timeout-counter"></span> segundos.</p>
                                            <p> Você quer continuar sua sessão? </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="idle-timeout-dialog-logout" type="button" class="btn dark btn-outline sbold uppercase">Não, Sair</button>
                                            <button id="idle-timeout-dialog-keepalive" type="button" class="btn green btn-outline sbold uppercase" data-dismiss="modal">Sim, Continue trabalhando</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
        <!-- BEGIN FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container-fluid"> 2018 &copy; Painel Trail Servicos.
            </div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimeout.js" type="text/javascript"></script>-->
        <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimer.js" type="text/javascript"></script>-->
       <!-- <script src="assets/global/plugins/offline/offline.min.js"></script>
        <script src="assets/global/plugins/offline/js/snake.js"></script>-->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/adicionar.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/form-validation-md.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
          <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>
        <!--<script src="assets/pages/scripts/ui-idletimeout.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
