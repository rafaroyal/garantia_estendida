<?php

require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
require_once('inc/permissoes.php');
$banco_painel = $link;
$item                 = (empty($_GET['item'])) ? "" : $_GET['item'];

$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
$pasta = base64_decode($_COOKIE["pasta"]);
$usr_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);
$origem = $_SERVER['HTTP_REFERER'];
if (empty($origem))
{
    header("Location: inicio.php");
}

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
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!--<link rel="stylesheet" href="assets/global/plugins/offline/themes/offline-theme-chrome.css" />
        <link rel="stylesheet" href="assets/global/plugins/offline/themes/offline-language-portuguese-brazil.css" />-->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/pages/css/invoice.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
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
                                       
                                       if($item == 'est_movimentacoes'){
                                       ?>
                                   <div class="portlet light portlet-fit portlet-datatable ">
                                       <div class="portlet-title">
                                           <div class="actions">
                                               <div class="btn-group">
                                                   <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                                       <i class="fa fa-share"></i>
                                                       <span class="hidden-xs"> Ferramentas </span>
                                                       <i class="fa fa-angle-down"></i>
                                                   </a>
                                                   <ul class="dropdown-menu pull-right" id="datatable_ajax_tools">
                                                       <li>
                                                           <a href="javascript:;" data-action="0" class="tool-action salvar_excel"> Salvar para Excel </a>
                                                       </li>
                                                       <li>
                                                           <a href="javascript:;" data-action="1" class="tool-action salvar_pdf"> Salvar para  PDF</a>
                                                       </li>
                                                       <li class="divider"> </li>
                                                       <li>
                                                            <a href="javascript:;" data-action="2" class="tool-action salvar_imprimir"> Imprimir </a>
                                                       </li>
                                                   </ul> 
                                               </div>
                                               <?php
                                                   if(in_array("71", $verifica_lista_permissoes_array_inc)){
                                               ?>
                                                   <a href="est_adicionar.php?item=<?php echo $item; ?>"  class="btn sbold green">
                                                   <i class="fa fa-plus"></i> Adicionar </a>
                                               <?php
                                                   }
                                               ?>
                                           </div>
                                       </div>
                                       
                                           </div> <!-- portlet light -->
                                           <?php
                                       }
                                       ?>
                                       </div> <!-- col-12 -->
                                   </div> <!-- row -->
                                   <?php
                                   if($item == 'est_estoque'){
                                        
                                    ?>
                                        <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <div class="row">
                                    
                                                    </div>  
                                                </div>

                                                <!--<table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="25%"> NOME </th>
                                                            <th width="15%"> CPF </th>
                                                            <th width="10%"> NASC./IDADE </th>
                                                            <th width="20%"> PARCEIRO </th>
                                                            <th width="10%"> VALIDADE </th>
                                                            <th width="15%"> STATUS </th>
                                                            <th width="5%"> DETALHES </th>
                                                        </tr>
                                                        
                                                    </thead>
                                                <tbody> </tbody>
                                                </table>-->

                                                <div class="modal fade modal-scroll" id="ajax" role="basic" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                                                                <span> &nbsp;&nbsp;Aguarde... </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php    
                                    }elseif($item == 'est_movimentacoes'){
                                        
                                    ?>
                                        <div class="portlet-body">
                                            <div class="table-container">
                                            <div class="table-actions-wrapper">
                                                <div class="row">
                                                <div class="col-md-3" id="campo_data_filtro" style="display: none;">
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
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="todos_clientes_ativos" value="S" id="todos_clientes_ativos" class="md-check" />
                                                                <label for="todos_clientes_ativos">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Por todo período! <br /> <small> o período acima será anulado.</small></label>
                                                            </div>
                                                        </div>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="somente_clientes_ativos" value="S" id="somente_clientes_ativos" class="md-check" />
                                                                <label for="somente_clientes_ativos">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Apenas cliente ativos! <br /> <small> ocultar clientes cancelados.</small></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9" id="grupo_filtro_clientes">
                                                     <?php
                                                       if ($nivel_usuario == 'A' AND $id_serv_get == 2)
                                                        {
                                                       ?>
                                                        <a class="btn green" onclick="return exibir_filtro_clientes();" href="javascript:;" style="margin-top: 14px;"> Filtrar Clientes </a> <span class="div_aguarde" style="display: none;position: absolute;width: 100%;right: 150px;padding-left: 300px;padding-top: 20px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                        </div>
                                                        <hr />
                                                        <div class="row">
                                                        
                                                    
                                                       <div class="col-md-12">
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="busca_dependente" value="SIM" id="busca_dependente" class="form-control form-filter md-check"/>
                                                                <label for="busca_dependente" style="font-size: 12px;">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> DEPENDENTE </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <span>NOME | CPF | DATA DE NASC.(dd/mm/aaaa)</span>
                                                            <div class="input-group input-group-lg">
                                                                <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                                <input type="hidden" class="form-control form-filter input-sm" name="tipo_filtro" value="<?php echo $tipo_filtro; ?>"/>
                                                                
                                                                <input class="table-group-action-input form-control" name="teste" placeholder="Digite sua busca..."/>
                                                                <span class="input-group-btn">
                                                                <button class="btn green table-group-action-submit">
                                                                    <i class="fa fa-search"></i> Buscar</button>
                                                                
                                                                <button class="btn red btn-outline filter-cancel">
                                                                            <i class="fa fa-times"></i>&nbsp;</button>
                                                                </span>
                                                            </div>
                                                                            
                                                        </div>              
                                                        </div>
                                                        </div>
                                                     </div>

                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> #COD</th>
                                                            <th width="25%"> NOME </th>
                                                            <th width="15%"> Vr. </th>
                                                            <th width="10%"> ESTOQUE </th>
                                                            <th width="20%"> CADASTRO </th>
                                                            <th width="10%"> DESTALHES </th>
                                                        </tr>
                                                    </thead>
                                                <tbody> </tbody>
                                                </table>

                                                <div class="modal fade modal-scroll" id="ajax" role="basic" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                                                                <span> &nbsp;&nbsp;Aguarde... </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php    
                                    }
                                    ?>  
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
                                </div>
                            </div>
                <!-- END PAGE CONTENT BODY -->
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
          </div>  
        </div>
        <!-- END CONTAINER -->
        <?php
        if($item == 'est_estoque'){
        ?>
        <!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
        <div class="modal fade modal-scroll" id="ajax" role="basic" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                        <span> &nbsp;&nbsp;Aguarde... </span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <!-- /.modal -->
        <!-- BEGIN FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container-fluid"> 2019 &copy; Painel Trail Servicos.
            </div>
        </div>
        <div class="scroll-to-top hidden-print">
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
        <script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        
         <script src="assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
       <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
       <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimeout.js" type="text/javascript"></script>-->
       <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimer.js" type="text/javascript"></script>-->
        <!--<script src="assets/global/plugins/offline/offline.min.js"></script>
        <script src="assets/global/plugins/offline/js/snake.js"></script>-->
        <script src="assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/table-datatables-scroller.min.js" type="text/javascript"></script>
        <!--<script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>-->
        <script src="assets/pages/scripts/table-datatables-ajax-est.js" type="text/javascript"></script>   
         <script src="assets/pages/scripts/listar.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <!--<script src="assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>-->
        <!--<script src="assets/pages/scripts/ui-idletimeout.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
