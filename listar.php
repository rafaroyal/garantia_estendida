<?php

require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
require_once('inc/permissoes.php');
$banco_painel = $link;
$item                 = (empty($_GET['item'])) ? "" : $_GET['item'];
$id                   = (empty($_GET['id'])) ? "" : $_GET['id'];
$tipo                 = (empty($_GET['tipo'])) ? "" : $_GET['tipo'];
$id_grupo_parceiro    = (empty($_GET['id_grupo_parceiro'])) ? "" : $_GET['id_grupo_parceiro'];
$id_serv_get          = (empty($_GET['id_serv'])) ? "" : $_GET['id_serv'];
$tipo_filtro          = (empty($_GET['tipo_filtro'])) ? "" : $_GET['tipo_filtro'];
$id_usuario_pagamento = (empty($_GET['id_usuario_pagamento'])) ? "" : $_GET['id_usuario_pagamento'];

$expire = $_COOKIE["usr_time"];
$pasta = base64_decode($_COOKIE["pasta"]);
setcookie("item",    base64_encode($item),    $expire, "/".$pasta);
setcookie("id",    base64_encode($id),    $expire, "/".$pasta);
setcookie("tipo",    base64_encode($tipo),    $expire, "/".$pasta);
setcookie("id_grupo_parceiro",    base64_encode($id_grupo_parceiro),    $expire, "/".$pasta);
setcookie("id_serv_get",    base64_encode($id_serv_get),    $expire, "/".$pasta);
setcookie("tipo_filtro",    base64_encode($tipo_filtro),    $expire, "/".$pasta);
setcookie("id_usuario_pagamento",    base64_encode($id_usuario_pagamento),    $expire, "/".$pasta);

$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
$pasta = base64_decode($_COOKIE["pasta"]);
$usr_id = base64_decode($_COOKIE["usr_id"]);
$nivel_status  = base64_decode($_COOKIE["nivel_status"]);
$usr_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);
$id_parceiro_s = $usr_parceiro_sessao;
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
                    	           ?>
                                   <!-- Begin: life time stats -->
                                   <?php
                                   if ($item == 'clientes')
                                   {
                                   ?>
                                   <div class="col-md-3"><p><span class="label label-sm label-danger">EX</span> Inativo / <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=cancelado">Cancelado</a></p><p><span class="label label-sm label-success">IN</span> <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=ativos">Ativo / Inclusão </a></p><p><span class="label label-sm label-success">AL</span> <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=ativos"> Ativo / Alterado </a>  / <span class="label label-sm bg-purple">RE</span> <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=renovado">Renovado</a></p></div>
                                   <div class="col-md-3"><p><span class="label label-sm label-info">A</span> <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=dependente">Adicional</a></p><p><span class="label label-sm label-danger">P</span> <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=pendente">Pendente</a></p><p><span class="label label-sm label-warning">V</span> <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=vencido">Vencido </a> / <a href="listar.php?item=clientes&id_serv=<?php echo $id_serv_get; ?>&id=<?php echo $id;?>&tipo=<?php echo $tipo;?>&tipo_filtro=avencer">À vencer</a> </p></div>
                                   <div class="col-md-3"><p><span class="label label-sm label-default"><i class="fa fa-credit-card"></i></span> <a href="#">Cartão Pendente de Impressão</a></p></div>
                                   <?php
                                   }
                                   if ($item <> 'personalizar_pagamentos')
                                    {
                                   ?>
                                    <div class="portlet light portlet-fit portlet-datatable ">
                                     <?php
                                    }
                                   if ($item <> 'relatorios' AND $item <> 'comprovante' AND $item <> 'atualizar_pagamentos' AND $item <> 'guias' AND $item <> 'personalizar_pagamentos')
                                    {
                                        if($item == 'boletos_pagosmes_clientes' OR $item == 'boletos_vencidos_clientes' OR $item == 'boletos_clientes' OR $item == 'boletos_avencermes_clientes' OR $item == 'boletos_mes_clientes' OR $item == 'pagamentos_clientes' OR $item == 'fluxo_pagamento' ){
                                        ?>
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
                                            <div class="btn-group">
                                                <button class="btn btn-sm green-haze dropdown-toggle" type="button" data-toggle="dropdown"> Pagos
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente&tipo_filtro=personalizar_boletos_pagos"><i class="fa fa-list-ul"></i> Buscar pagamentos</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente&tipo_filtro=hoje"><i class="fa fa-list-ul"></i> Hoje </a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente&tipo_filtro=ontem"><i class="fa fa-list-ul"></i> Ontem</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente&tipo_filtro=ultimos_7"><i class="fa fa-list-ul"></i> Útimos 7 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente&tipo_filtro=ultimos_30"><i class="fa fa-list-ul"></i> Útimos 30 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente&tipo_filtro=neste_mes"><i class="fa fa-list-ul"></i> Neste mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente&tipo_filtro=ultimo_mes" ><i class="fa fa-list-ul"></i> Útimo mês</a>
                                                    </li>
                                                     <li class="divider"> </li>
                                                    <?php
                                                        if(in_array("41", $verifica_lista_permissoes_array_inc))
                                                        {
                                                    ?>
                                                            <li>
                                                                <a href="listar.php?item=personalizar_pagamentos&tipo=cliente" ><i class="fa fa-list-ul"></i> Confirmar recebimentos </a>
                                                            </li>
                                                    <?php
                                                        }
                                                    ?>
                                                    
                                                    <li>
                                                            <a href="listar.php?item=pagamentos_usuario&id_usuario_pagamento=<?php echo $usr_id; ?>" ><i class="fa fa-list-ul"></i>Meus Pagamentos Pendenes de Aprovação </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            
                                            
                                            <div class="btn-group">
                                                <button class="btn btn-sm yellow-mint dropdown-toggle" type="button" data-toggle="dropdown"> À vencer
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente&tipo_filtro=hoje"><i class="fa fa-list-ul"></i> Hoje </a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente&tipo_filtro=amanha"><i class="fa fa-list-ul"></i> Amanhã</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente&tipo_filtro=proximos_7"><i class="fa fa-list-ul"></i> Próximos 7 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente&tipo_filtro=proximos_30"><i class="fa fa-list-ul"></i> Próximos 30 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente&tipo_filtro=neste_mes" ><i class="fa fa-list-ul"></i> Neste mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente&tipo_filtro=proximo_mes" ><i class="fa fa-list-ul"></i> Próximo mês</a>
                                                    </li>
                                                     <li class="divider"> </li>
                                                </ul>
                                            </div>
                                            
                                            
                                            <div class="btn-group">
                                                <button class="btn btn-sm red-mint dropdown-toggle" type="button" data-toggle="dropdown"> Vencidos
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="listar.php?item=boletos_vencidos_clientes&tipo=cliente&tipo_filtro=todos"><i class="fa fa-list-ul"></i> Todos </a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_vencidos_clientes&tipo=cliente&tipo_filtro=neste_mes" ><i class="fa fa-list-ul"></i> Neste mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_vencidos_clientes&tipo=cliente&tipo_filtro=ultimo_mes" ><i class="fa fa-list-ul"></i> Útimo mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="listar.php?item=boletos_vencidos_clientes&tipo=cliente&tipo_filtro=mais_tres_meses" ><i class="fa fa-list-ul"></i> 3 meses vencidos</a>
                                                    </li>
                                                    
                                                    <li class="divider"> </li>
                                                </ul>
                                            </div>

                                             <?php
                                        
                                            if(in_array("68", $verifica_lista_permissoes_array_inc))
                                            {
                                         ?>
                                         
                                            <div class="btn-group">
                                                <button class="btn btn-sm blue-hoki dropdown-toggle" type="button" data-toggle="dropdown"> Fluxo de Pagamento <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                         
                                                    <li>
                                                        <a href="listar.php?item=fluxo_pagamento&tipo=cliente" ><i class="fa fa-list-ul"></i> Lista Clientes (parceiros) </a>
                                                    </li>
                                                    <li class="divider"> </li>
                                                </ul>
                                            </div>
                                            <?php
                                          }

                                          if(in_array("5", $verifica_lista_permissoes_array_inc))
                                          {
                                          ?>   
                                            <a href="listar.php?item=atualizar_pagamentos&tipo=cliente" class="btn btn-sm blue-hoki"><i class="fa fa-list-ul"></i> Atualizar pagamentos</a>
                                         <?php
                                          }
                                          
                                         if($item <> 'pagamentos_clientes'){
                                         ?>
                                            <a href="listar.php?item=pagamentos_clientes&tipo=cliente" class="btn btn-lg btn-icon-only green"><i class="fa fa-home"></i></a>
                                         <?php
                                         }   
                                         ?>
                                        </div>
                                    </div>
                                        <?php
                                        }else{
                                    ?>
                                        <div class="portlet-title">
                                        
                                            <div class="actions">
                                                <div class="btn-group">
                                                    <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                                        <i class="fa fa-share"></i>
                                                        <span class="hidden-xs"> Ferramentas </span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right" id="datatable_ajax_tools">
                                                    <?php
                                                        if($item == 'parceiros' AND $nivel_usuario == 'A')
                                                        {
                                                    ?>
                                                             <li>
                                                                <a href="listar.php?item=grupos_parceiros"> Listar GRUPOS </a>
                                                             </li>
                                                        
                                                    <?php
                                                        }
                                                    ?>
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
                                                    if($item <> 'grupo_parceiro' AND $item <> 'faturamentos' AND $item <> 'pagamentos' AND $item <> 'boletos_clientes')
                                                    {
                                                       if($item == 'clientes')
                                                       {
                                                        $id_serv              = (empty($_GET['id_serv'])) ? "" : $_GET['id_serv'];
                                                        if($nivel_usuario != 'A'){
                                                            
                                                        ?>
                                                    <a href="adicionar.php?item=<?php echo $item; ?>&id_serv=<?php echo $id_serv; ?>&id=<?php echo $id; ?>&tipo=produto"  class="btn sbold green">
                                                    <i class="fa fa-plus"></i> Adicionar </a>
                                                <?php
                                                        }
                                                       }elseif($item == 'usuarios' AND in_array("20", $verifica_lista_permissoes_array_inc)){
                                                        
                                                        ?>
                                                    <a href="adicionar.php?item=<?php echo $item; ?>"  class="btn sbold green">
                                                    <i class="fa fa-plus"></i> Adicionar </a>
                                                <?php
                                                        
                                                       }elseif($item == 'parceiros' AND in_array("39", $verifica_lista_permissoes_array_inc)){
                                                        
                                                        ?>
                                                    <a href="adicionar.php?item=<?php echo $item; ?>"  class="btn sbold green">
                                                    <i class="fa fa-plus"></i> Adicionar </a>
                                                <?php
                                                        
                                                       }
                                                
                                                    }
                                                ?>
                                            </div>
                                            
                                            <?php
                                            if($item == 'pagamentos_usuario' AND $nivel_usuario == 'A'){
                                            ?>
                                                <a href="listar.php?item=personalizar_pagamentos&tipo=cliente" class="btn btn-lg btn-icon-only green"><i class="fa fa-home"></i></a>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                         <?php
                                         }
                                        }
                                    ?>
                                   <?php
                                   if ($item == 'parceiros' AND $nivel_usuario == 'A')
                                    {
                                    
                                    ?>
                                        <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <span> </span>
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste"/>
                                                    <button class="btn btn-sm green table-group-action-submit">
                                                        <i class="fa fa-check"></i> Buscar</button>
                                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                                                    <i class="fa fa-times"></i> Limpar</button>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="100"> NOME </th>
                                                            <th width="20%"> CNPJ/CPF </th>
                                                            <th width="13%"> STATUS </th>
                                                            <th width="10%"> DETALHES </th>
                                                        </tr>
                                                        
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                                <!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
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
                                            <!-- /.modal -->
                                            </div>
                                        </div>
                                    <?php
                                    }elseif($item == 'clientes'){
                                       if(!empty($tipo_filtro)){
                                        if($tipo_filtro == 'avencer'){
                                            $html_tipo_filtro = 'À vencer';
                                        }elseif($tipo_filtro == 'vencido'){
                                            $html_tipo_filtro = 'Vencido(s)';
                                        }elseif($tipo_filtro == 'pendente'){
                                            $html_tipo_filtro = 'Pendente(s)';
                                        }elseif($tipo_filtro == 'dependente'){
                                            $html_tipo_filtro = 'Dependente(s)';
                                        }elseif($tipo_filtro == 'renovado'){
                                            $html_tipo_filtro = 'Renovados';
                                        }else{
                                            $html_tipo_filtro = '';
                                        }
                                        echo "<div class=\"col-md-12\"><h1>$html_tipo_filtro</h1><span><a href=\"listar.php?item=clientes&id_serv=$id_serv_get&id=$id&tipo=$tipo\">Remover filtro</a></span></div>";
                                       }

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
                                                
                                                <?php
                                                    if(!empty($tipo) AND $tipo == 'produto')
                                                    {
                                                        
                                                        if($id == 'todos'){
                                                            $sql_base   = "SELECT bpro.slug FROM bases_produtos bpro
                                                            JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                                            JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                                            JOIN servicos serv ON pro.id_servico = serv.id_servico
                                                            WHERE serv.id_servico = $id_serv_get
                                                            GROUP BY serv.id_servico ";
                                                        }else{
                                                            $sql_base   = "SELECT bpro.slug FROM bases_produtos bpro
                                                                    JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                                                    WHERE pro.id_produto = $id";
                                                        }
                                                        
                                                        
                                                        $query_base = mysql_query($sql_base) or die(mysql_error()." - 1");   
                                                        $slug       = mysql_result($query_base, 0, 'slug');
                                                    }
        
                                                    if(isset($slug) AND $slug == 'europ')
                                                    {
                                                        if($nivel_usuario == 'A')
                                                        {
                                                            
                                                            ?>
                                                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
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
                                                            </table>
                                                            <?php
                                                            
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
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
                                                            </table>
                                                            <?php
                                                        }
                                                    
                                                    }
                                                    elseif(isset($slug) AND $slug == 'sorteio_ead')
                                                    {
                                                    ?>    
                                                    <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%"> ID</th>
                                                            <th width="30%"> NOME </th>
                                                            <th width="15%"> CNPJ/CPF </th>
                                                            <th width="15%"> N. SORTEIO </th>
                                                            <th width="15%"> Dt. SORTEIO </th>
                                                            <th width="20%"> PARCEIRO </th>
                                                            <th width="13%"> STATUS </th>
                                                            <th width="10%"> DETALHES </th>
                                                        </tr>
                                                        
                                                    </thead>
                                                    <tbody> </tbody>
                                                    </table>
                                                    <?php    
                                                    }
                                                ?>
                                                
                                                
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
                                        
                                        ?>
                                    
                                        
                                    <?php    
                                        
                                    }elseif($item == 'grupos_parceiros'){
                                        
                                    ?>
                                        <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <span> </span>
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste"/>
                                                    <button class="btn btn-sm green table-group-action-submit">
                                                        <i class="fa fa-check"></i> Buscar</button>
                                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                                                    <i class="fa fa-times"></i> Limpar</button>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="100"> NOME DO GRUPO </th>
                                                            <th width="10%"> TOTAL PARCEIROS </th>
                                                            <th width="13%"> STATUS </th>
                                                            <th width="10%"> DETALHES </th>
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
                                    }elseif($item == 'grupo_parceiro'){
                                        
                                    ?>
                                        <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <span> </span>
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste"/>
                                                    <button class="btn btn-sm green table-group-action-submit">
                                                        <i class="fa fa-check"></i> Buscar</button>
                                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                                                    <i class="fa fa-times"></i> Limpar</button>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="100"> NOME </th>
                                                            <th width="20%"> CPF/CNPJ </th>
                                                            <th width="20%"> CIDADE/UF </th>
                                                            <th width="10%"> DETALHES </th>
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
                                    }elseif($item == 'relatorios'){
                                        
                                       
                                        
                                        //if($nivel_usuario == 'A')
                                        //{
                                    ?>  
                                        <div class="portlet-body">
                                            <div class="mt-element-step">
                                                <div class="row step-thin">
                                                    <div class="form-body">
                                                    <div class="row form">
                                                    <div class="col-md-3" style="padding: 0 5px;">
                                                    <div class="col-md-12 bg-grey mt-step-col done" style="margin-bottom: 20px;" id="passo_p1">
                                                        <div class="mt-step-number bg-white font-grey">1</div>
                                                        <div class="mt-step-title uppercase font-grey-cascade">Período</div>
                                                        <div class="mt-step-content font-grey-cascade">Data da venda</div>
                                                     </div>
                                                        <div class="portlet-body" id="bloco_p4" >
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
                                                       </div>
                                                    </div>
                                                    <div class="col-md-3 " style="padding: 0 5px;">
                                                    <div class="col-md-12 bg-grey mt-step-col" style="margin-bottom: 20px;" id="passo_p2">
                                                        <div class="mt-step-number bg-white font-grey">2</div>
                                                        <div class="mt-step-title uppercase font-grey-cascade">Tipo</div>
                                                        <div class="mt-step-content font-grey-cascade">Tipo de relatório</div>
                                                     </div>
                                                        <div class="portlet-body">
                                                            <div class="col-md-12 " style="margin-bottom: 20px;">
                                                                <div class="md-radio-list">
                                                                <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_1" name="tipo_relatorio" class="md-radiobtn" value="vendas" />
                                                                    <label for="checkbox1_1">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Vendas </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_2" name="tipo_relatorio" class="md-radiobtn" value="clientes" />
                                                                    <label for="checkbox1_2">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Clientes </label>
                                                                </div>
                                                                <div class="md-checkbox-list" style="margin-left: 30px;">
                                                                    <div class="md-checkbox small">
                                                                        <input type="checkbox" name="exibir_adicionais" value="S" id="exibir_adicionais" class="md-check" />
                                                                        <label for="exibir_adicionais">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Exibir Adicionais</label>
                                                                    </div>
                                                                    <div class="md-checkbox small">
                                                                        <input type="checkbox" name="apenas_ativos" value="S" id="apenas_ativos" class="md-check" />
                                                                        <label for="apenas_ativos">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Apenas ativos </label>
                                                                    </div>
                                                                            
                                                                </div>
                                                                <?php
                                                                
                                                                if($nivel_usuario == 'A' AND $nivel_status == 0){
                                                                ?>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_3" name="tipo_relatorio" class="md-radiobtn" value="faturamento"/>
                                                                    <label for="checkbox1_3">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Faturamento
                                                                    </label>
                                                                </div>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                           </div>
                                                          
                                                       </div>
                                                    </div>
                                                    <div class="col-md-3" style="padding: 0 5px;">
                                                        <div class="col-md-12 bg-grey mt-step-col" style="margin-bottom: 20px;" id="passo_p3">
                                                            <div class="mt-step-number bg-white font-grey">3</div>
                                                            <div class="mt-step-title uppercase font-grey-cascade">Parceiro</div>
                                                            <div class="mt-step-content font-grey-cascade">Selecione o parceiro</div>
                                                        </div>
                                                        <div class="portlet-body" id="bloco_p2" >
                                                        
                                                            <div class="form-group">
                                                                <label class="control-label ">Grupos</label>
                                                                    <div class="">
                                                                    <?php
                                                                        
                                                                        if($nivel_usuario == 'A')
                                                                        {
                                                                            $sql_parceiros        = "SELECT id_grupo_parceiro, nome FROM grupos_parceiros
WHERE del = 'N'";
                                                                        }else{
                                                                            $sql_parceiros = "SELECT gpar.id_grupo_parceiro, gpar.nome FROM grupos_parceiros gpar
                                                                            JOIN parceiros_grupos pgru ON gpar.id_grupo_parceiro = pgru.id_grupo_parceiro
                                                                            WHERE gpar.del = 'N' AND pgru.id_parceiro = $id_parceiro_s";
                                                                        }
                                                                        
                                                                        $query_parceiros      = mysql_query($sql_parceiros);
                                                                                        
                                                                        if (mysql_num_rows($query_parceiros)>0)
                                                                        {
                                                                            
                                                                            echo "<select class=\"bs-select form-control\" data-live-search=\"true\" data-size=\"8\" id=\"grupo_parceiro\" name=\"grupo_parceiro\"><option value=\"\" selected=\"\">"; 
                                                                            if($nivel_usuario == 'A'){
                                                                            echo "</option> <option value=\"todos\" >TODOS</option>";
                                                                            }
                                                                        
                                                                            
                                                                            
                                                                            while($dados_parceiros = mysql_fetch_array($query_parceiros))
                                                                            {
                                                                                extract($dados_parceiros);
                                                                                
                                                                                
                                                                                echo "<option value=\"$id_grupo_parceiro\">$nome</option>";
                                                                            }
                                                                            
                                                                            echo "</select>";
                                                                        }
                                                                    ?>
                                                                    
                                                                        
                                                                    </div>
                                                            </div>
                                                            
                                                            <div class="form-group" id="lista_parceiro">
                                                                
                                                            </div>
                                                            <div class="form-group" id="lista_filial">
                                                            
                                                            </div>
                                                            <div class="form-group" id="lista_usuario">
                                                            
                                                            </div>
                                                       </div>
                                                    </div>
                                                    <div class="col-md-3" style="padding: 0 5px;">
                                                    <div class="col-md-12 bg-grey mt-step-col" style="margin-bottom: 20px;" id="passo_p4">
                                                        <div class="mt-step-number bg-white font-grey">4</div>
                                                        <div class="mt-step-title uppercase font-grey-cascade">PRODUTO</div>
                                                        <div class="mt-step-content font-grey-cascade">Tipo de produto</div>
                                                     </div>
                                                        <div class="portlet-body" id="bloco_p3" >
                                                            <div class="form-group" id="lista_grupo_produto">
                                                            
                                                            </div>
                                                           <div class="form-group" id="lista_produto">
                                                           </div>
                                                           
                                                       </div>
                                                    </div>
                                                     
                                                    <div class="col-md-12 form-actions right" id="proximo_p1">
                                                        <span class="div_aguarde" style="display: none;position: absolute;width: 100%;right: 150px;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span> <button onclick="return gerar_relatorio();" class="btn green">Gerar relatório</button> 
                                                    </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-container" id="resultado_relatorio">
                                            
                                                
                                                
                                            </div>
                                        </div>
                                        <?php
                                        //}
                                        ?>
                                    <?php
                                    }elseif($item == 'comprovante'){
                                        ?>
                                        
                                        <div class="portlet-body">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="portlet light ">
                                                
                                        <?php
                                        
                                        $url_referencia = (empty($_GET['url_referencia'])) ? "" : $_GET['url_referencia'];
                                        /* URL RETORNO CIELO*/
                                        //http://www.trailservicos.com.br/painel_trail/listar.php?item=comprovante&url_referencia=online
                                        //if($url_referencia == 'online'){
                                            
                                        $slug              = base64_decode($_COOKIE["slug"]);
                                        $id_banco          = base64_decode($_COOKIE["id_banco"]);
                                        $id_banco          = explode("-", $id_banco);
                                        //$cert              = $_COOKIE["cert"];
                                        //$erro_depen        = $_COOKIE["erro_depen"];
                                        //$id_venda_sort_ead = $_COOKIE["id_venda_sort_ead"];
                                        $cpf               = base64_decode($_COOKIE["cpf"]);
                                        $id_ordem_pedido   = base64_decode($_COOKIE["id_ordem_pedido"]);
                                        
                                        $sql_ordem   = "SELECT ordem_pedido, metodo_pagamento FROM ordem_pedidos
                                                        WHERE id_ordem_pedido = $id_ordem_pedido";

                                        $query_ordem = mysql_query($sql_ordem, $banco_painel) or die(mysql_error()." - 0");
                                        
                                        if (mysql_num_rows($query_ordem)>0)
                                        {
                                            $ordem_pedido               = mysql_result($query_ordem, 0, 'ordem_pedido');
                                            $metodo_pagamento_ordem     = mysql_result($query_ordem, 0, 'metodo_pagamento');
                                        }
                                        
                                        /*}else{
                                            
                                            $slug              = (empty($_GET['slug'])) ? "" : $_GET['slug'];
                                            $id_banco          = (empty($_GET['id_banco'])) ? "" : $_GET['id_banco'];
                                            $id_banco          = explode("-", $id_banco);
                                            $cert              = (empty($_GET['cert'])) ? "" : $_GET['cert'];
                                            $erro_depen        = (empty($_GET['erro_depen'])) ? "" : $_GET['erro_depen'];
                                            $id_venda_sort_ead = (empty($_GET['id_venda_sort_ead'])) ? "" : $_GET['id_venda_sort_ead'];
                                            $cpf               = (empty($_GET['cpf'])) ? "" : $_GET['cpf'];
                                            
                                        }*/
                                        
                                        echo "<h5>Método de pagamento: $url_referencia</h5>";
                                        
                                        if($metodo_pagamento_ordem == 'BO' OR $metodo_pagamento_ordem == 'MA'){
                                            
                                            
                                            
                                            
                                            ?>
                                                <div class="portlet-title">
                                                    <div class="caption font-green">
                                                        <span class="caption-subject bold uppercase">BOLETOS / MAQUINA</span> <span class="caption-helper"> </span>
                                                    </div>
                                                </div>

                                            <?php
                                            
                                            $agora = date('d-m-Y');
                                            $partes_data = explode("-", $agora);
                                            //$dia = $partes[0];
                                            $mes_referencia = $partes_data[1];
                                            $ano_referencia = $partes_data[2];
                                            //AND data_cadastro LIKE '%$ano_referencia%'
                                            $sql        = "SELECT id_boleto, entrada, parcela, total_parcelas, valor_parcela, pago, data_vencimento, tipo_boleto FROM boletos_clientes
                                                           WHERE id_ordem_pedido = $id_ordem_pedido AND status_boleto = 0";
                                                        $query      = mysql_query($sql, $banco_painel);
                                                                    
                                                        if (mysql_num_rows($query)>0)
                                                        {
                                                            
                                                            
                                                            echo " <div class=\"portlet-body\">
                                            <div class=\"table-scrollable\">
                                                <table class=\"table table-hover\">
                                                    <thead>
                                                        <tr>
                                                            <th> # </th>
                                                            <th> Parcelas </th>
                                                            <th> Tipo </th>
                                                            <th> Valor Parcela </th>
                                                            <th> Vencimento </th>
                                                            <th> Pago </th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>";   
                                                    $verificar_entrada = false;
                                                            while($dados = mysql_fetch_array($query))
                                                            {
                                                               extract($dados);
                                                                
                                                                if($tipo_boleto == 'LOJA'){
                                                                    $link_boletos = 'ver_boletos_cliente_loja.php';
                                                                }elseif($tipo_boleto == 'BANCO'){
                                                                    $link_boletos = 'ver_boletos_cliente.php';
                                                                }
                                                                
                                                                $status_list = array(
                                                                    array("green" => "Pago"),
                                                                    array("red" => "Receber")
                                                                );
                                                                
                                                                $status_list_status = array(
                                                                array("success" => "Ativo"),
                                                                array("danger" => "Inativo")
                                                                );
                                                                
                                                                if($pago == 'N')
                                                                {
                                                                    $status = $status_list[1];
                                                                }
                                                                else
                                                                {
                                                                    $status = $status_list[0];
                                                                }
                                                                $html_tipo = '';
                                                                if($entrada == 'S'){
                                                                    $html_tipo = "ENTRADA";
                                                                    $verificar_entrada = true;
                                                                    $id_boleto_entrada = $id_boleto;
                                                                }
                                                                $pg_html_exibe = 'hide';
                                                                if($pago == 'S'){
                                                                    $pg_html_exibe = '';
                                                                }
                                                                
                                                                echo '<tr>
                                                                    <td> '.$id_boleto.' </td>
                                                                    <td> '.$parcela.' /'.$total_parcelas.' </td>
                                                                    <td> '.$html_tipo.' </td>
                                                                    <td> '.db_moeda($valor_parcela).' </td>
                                                                    <td> '.converte_data($data_vencimento).' </td>';
                                                                     if($nivel_usuario == "A" OR ($nivel_usuario == "P" AND $metodo_pagamento == "BO" AND ($tipo_boleto == 'LOJA' OR $entrada == 'S'))){
                                                                        echo '<td> <a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a> </td>';
                                                                    }else{
                                                                        echo '<td> &nbsp; </td>';
                                                                    }
                                                                echo '</tr>';
                                                                
                                                            }
                                                            
                                                            echo "</tbody>
                                                                </table>
                                                            </div>";
                                                        echo "<div class=\"form-actions right\" style=\"float: right;width: 100%;background: #eee;\">";
                                                      if($metodo_pagamento_ordem == 'BO'){  
                                                        /*if($verificar_entrada == true){
                                                                    echo "<a href=\"ver_boletos_cliente_entrada.php?id_ordem_pedido=$id_ordem_pedido&id_boleto=$id_boleto_entrada\" class=\"btn btn-warning\" style=\"float: right;margin-left: 10px;\" target=\"_blank\">Entrada</a>";
                                                                }*/
                                                        
                                                        echo "<a href=\"ver_boletos_cliente_confirmacao.php?id_ordem_pedido=$id_ordem_pedido\" class=\"btn green\" style=\"float: right;\" target=\"_blank\">Imprimir Boletos (carnê)</a>";
                                                    }
                                                echo "</div>";    
                                                        echo "</div>";
                                                            
                                                        }

                                        }
                                        
                                        $slug = explode("-", $slug);
                                        $contar_slug = count($slug) - 1;
                                        for($i=0;$contar_slug>=$i;$i++)
                                        {
                                            
                                            $array_id_base_ids_vendas = explode("|", $ordem_pedido);
            
                                            $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                                            $id_base = $array_ids_base_vendas[0];
                                            $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                                            $id_venda = $ids_vendas[0];    
                                            
                                        if($slug[$i] == 'europ'){
                                            
                                            // FAZ A CONEXAO COM BANCO DE DADOS DO PRODUTO
                                            $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                                        JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                                        WHERE bpro.id_base_produto = $id_banco[$i]";
                                            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 2");
                                            
                                            if (mysql_num_rows($query_base)>0)
                                            {
                                                
                                                $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                                                $banco_user             = mysql_result($query_base, 0, 'banco_user');
                                                $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                                                $banco_host             = mysql_result($query_base, 0, 'banco_host');
                                                //$slug                   = mysql_result($query_base, 0, 'slug');
                                                $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                                                
                                                $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                                            }
                                            
                                            $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
                                            
                                            
                                            
                                            $sql        = "SELECT cl.id_cliente, cl.chave, cl.id_produto'id_tipo_plano', cl.nome FROM clientes cl
                                                        JOIN vendas ve ON cl.id_cliente = ve.id_cliente
                                                        WHERE ve.id_venda = $id_venda";
                                            $query      = mysql_query($sql, $banco_produto);
                                                        
                                            if (mysql_num_rows($query)>0)
                                            {
                                                $dados = mysql_fetch_array($query);
                                                extract($dados);
                                                
                                                $cert = $chave;
                                            ?>
                                            <div class="portlet-title">
                                                <div class="caption font-green">
                                                    <span class="caption-subject bold uppercase"><?php echo $nome." - $cert"; ?></span> <span class="caption-helper"> TRAIL ASSISTENCIA TOTAL</span> <?php  echo '<a href="inc/ver_cliente.php?id_cliente='.$id_cliente.'&id_produto=6&tipo=produto" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver cliente</a>'; ?>
                                                </div>
                                            </div>
                                            <?php    
                                                $sql_vendas        = "SELECT metodo_pagamento, status_pedido FROM vendas 
                                                                   WHERE id_cliente = $id_cliente";
                                                $query_vendas      = mysql_query($sql_vendas, $banco_produto);
                                                            
                                                if (mysql_num_rows($query_vendas)>0)
                                                {
                                                    $dados_vendas = mysql_fetch_array($query_vendas);
                                                    extract($dados_vendas);
                                                    
                                                    if($status_pedido === 'Pendente' OR $status_pedido === 'Nao_Finalizado'){   
                                                    ?>
                                                    
                                                        <div class="portlet-body form">
                                                            <div class="form-body">
                                                                <div class="row ">
                                                                  <div class="col-md-12">
                                                                  <h5>Recebemos seu pedido com sucesso!</h5>
                                                                  <p>Status da Transação: <strong><?php echo $status_pedido; ?></strong></p>
                                                                  <p>Em breve você receberá mais informações da aprovação de crédito.<br /> E caso o meio de pagamamento for <strong>Boleto Bancário</strong>, a venda será confirmada apenas após pagamento da 1° parcela.</p>
                                                                  </div>
                                                                </div>
                                                                
                                                            </div> 
                                                        </div> 
                                                                                                                                                                   <?php     
                                                    }elseif($status_pedido === 'Negado' OR $status_pedido === 'Expirado' OR $status_pedido == 'Cancelado' OR $status_pedido == 'Chargeback'){
                                                    ?>
                                                    
                                                    <div class="portlet-body form">
                                                        <div class="form-body">
                                                            <div class="row ">
                                                              <div class="col-md-12">
                                                              <h5>Ops! Recebemos seu pedido, mas houve algum problema no pagamento!</h5>
                                                              <p>Status da Transação: <strong><?php echo $status_pedido; ?></strong></p>
                                                              <p><a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base; ?>&slug=europ&id_produto=3&id=<?php echo $id_cliente;?>&id_grupo_produto=7&plano_adicional=nao&msg_status=finalizar_venda" class="btn green" target="_blank">Outro pagamento</a></p>
                                                              </div>
                                                            </div>
                                                            
                                                        </div> 
                                                    </div> 
                                                    
                                                    <?php    
                                                     }elseif($status_pedido === 'Pago' OR $status_pedido === 'Autorizado'){
                                                        ?>
                                        
                                                        <div class="portlet-body form">
                                                            <div class="form-body">
                                                                <div class="row ">
                                                                <div class="col-md-12"><strong>Pedido realizado com sucesso!<br />Status: <?php echo $status_pedido; ?></strong></div>
                                                                  <div class="col-md-4 ">
                                                                  <h5>Imprimir Formulário 2 vias</h5>
                                                                  <p><a href="inc/comprovantes/html/proposta_assistencia_total/?cert=<?php echo $cert; ?>" class="btn green" target="_blank">LOJA / CLIENTE</a></p>
                                                                  </div>
                                                                  <div class="col-md-4 ">
                                                                    <h5>Imprimir Certificado do produto</h5>
                                                                    <a href="http://www.trailservicos.com.br/<?php echo $pasta; ?>/condicoes_gerais/trail_assistencia_total.pdf" class="btn green" target="_blank">CLIENTE</a>
                                                                  </div>
                                                                  <div class="col-md-4 ">
                                                                    <h5>Imprimir Cartão Medicamentos</h5>
                                                                    <a href="inc/comprovantes/html/proposta_assistencia_total/cartao/?cert=<?php echo $cert; ?>&tipo_plano=<?php echo $id_tipo_plano;?>" class="btn green" target="_blank">CLIENTE</a>
                                                                  </div>
                                                                </div>
                                                                
                                                            </div> 
                                                        </div> 
                                                     <?php
                                                        if($erro_depen == 'sim'){
                                                        ?>
                                                        <div class="portlet-body form">
                                                            <div class="form-body">
                                                                <div class="row ">
                                                                  <div class="col-md-4 ">
                                                                  Plano Adicional inválido! Idade superior à  70 anos.
                                                                  </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    <?php
                                                    // verifica se exite depenente
                                                    $sql        = "SELECT v.id_cliente'id_cliente_venda' FROM vendas v
                                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                                    WHERE c.chave = '$cert'
                                                    GROUP BY c.cpf";
                                                    $query      = mysql_query($sql, $banco_produto);
                                                                    
                                                        if (mysql_num_rows($query)>0)
                                                        {
                                                            $id_cliente_venda = mysql_result($query, 0,0);
            
                                                                
                                                                $sql_dep = "SELECT * FROM clientes
                                                                WHERE id_cliente_principal = $id_cliente_venda AND tipo_movimento IN ('IN','AL')";
                                                                $query_dep      = mysql_query($sql_dep, $banco_produto);
                                                                
                                                                if (mysql_num_rows($query_dep)>0)
                                                                {
                                                                ?>
                                                                    <div class="portlet light ">
                                                                    <div class="portlet-title">
                                                                        <div class="caption font-green">
                                                                            <span class="caption-subject bold uppercase">Plano Adicional</span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                <?php    
                                                                    while($dados = mysql_fetch_array($query_dep))
                                                                    {
                                                                        extract($dados);
                                                                    ?>    
                                                                        <div class="portlet-body form">
                                                                        <div class="form-body">
                                                                            <div class="row ">
                                                                              <div class="col-md-8">
                                                                              <h5><?php echo $nome." - ".$chave; ?></h5>
                                                                              <?php
                                                                              if($status == '0')
                                                                              {
                                                                                $html_status = "<span class=\"label label-sm label-success\">ATIVO</span>";
                                                                                $obs_info = '';
                                                                              }elseif($status == '3'){
                                                                                $html_status = "<span class=\"label label-warning\"> PENDENTE </span>";
                                                                                $obs_info = 'Para ativar o cliente, necessário preencher todos os campos de cadastro.';
                                                                              }
                                                                              ?>
                                                                              <p><strong>Status do cliente: <?php echo $html_status.'<br/>'.$obs_info; ?> </strong></p>
                                                                              </div>
                                                                              
                                                                              <div class="col-md-4 ">
                                                                                <h5>Imprimir Cartão Medicamentos</h5>
                                                                                <a href="http://www.trailservicos.com.br/painel_trail/inc/comprovantes/html/proposta_assistencia_total/cartao/?cert=<?php echo $chave; ?>&tipo_plano=<?php echo $id_produto;?>" class="btn green" target="_blank">CLIENTE</a>
                                                                              </div>
                                                                            </div>
                                                                            
                                                                        </div> 
                                                                    </div> 
                                                                       
                                                                    <?php    
                                                                    }
                                                                    
                                                                    ?>
                                                                     </div>
                                                                <?php
                                                                }
                                                        }
                                                     
                                                     }
                                                }
                                        }
                                            
                                        
                                    ?>
                                    </div> <!-- fim portlet light -->
                                    
                                    <?php
                                        
                                        }
                                        elseif($slug[$i] == "sorteio_ead")
                                        {
                                            ?>
                                            <div class="portlet-body">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="portlet light ">
                                                            <div class="portlet-title">
                                                                <div class="caption font-green">
                                                                    <span class="caption-subject bold uppercase">CPF: <?php echo $cpf; ?></span> <span class="caption-helper"> SORTEIOS EAD</span>
                                                                </div>
                                                            </div>
                                            <?php
                                            
                                            // FAZ A CONEXAO COM BANCO DE DADOS DO PRODUTO
                                            $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                                        JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                                        WHERE bpro.id_base_produto = $id_banco[$i]";
                                            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 2");
                                            
                                            if (mysql_num_rows($query_base)>0)
                                            {
                                                
                                                $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                                                $banco_user             = mysql_result($query_base, 0, 'banco_user');
                                                $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                                                $banco_host             = mysql_result($query_base, 0, 'banco_host');
                                                //$slug                   = mysql_result($query_base, 0, 'slug');
                                                $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                                                
                                                $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                                            }
                                            
                                            $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
                                            
                                            $sql_vendas        = "SELECT id_venda'id_venda_cliente', metodo_pagamento, status_pedido FROM vendas_painel 
                                                                   WHERE id_venda_painel = $id_venda";
                                            $query_vendas      = mysql_query($sql_vendas, $banco_produto);
                                                            
                                                if (mysql_num_rows($query_vendas)>0)
                                                {
                                                    $dados_vendas = mysql_fetch_array($query_vendas);
                                                    extract($dados_vendas);
                                                    
                                                    if($status_pedido === 'Pendente' OR $status_pedido === 'Nao_Finalizado'){   
                                                    ?>
                                                    
                                                        <div class="portlet-body form">
                                                            <div class="form-body">
                                                                <div class="row ">
                                                                  <div class="col-md-12">
                                                                  <h5>Recebemos seu pedido com sucesso!</h5>
                                                                  <p>Status da Transação: <strong><?php echo $status_pedido; ?></strong></p>
                                                                  <p>Em breve você receberá mais informações da aprovação de crédito.<br /> E caso o meio de pagamamento for <strong>Boleto Bancário</strong>, a venda será confirmada apenas após pagamento da 1° parcela.</p>
                                                                  </div>
                                                                </div>
                                                                
                                                            </div> 
                                                        </div> 
                                                                                                                                                                   <?php     
                                                    }elseif($status_pedido === 'Negado' OR $status_pedido === 'Expirado' OR $status_pedido == 'Cancelado' OR $status_pedido == 'Chargeback'){
                                                    ?>
                                                    
                                                    <div class="portlet-body form">
                                                        <div class="form-body">
                                                            <div class="row ">
                                                              <div class="col-md-12">
                                                              <h5>Ops! Recebemos seu pedido, mas houve algum problema no pagamento!</h5>
                                                              <p>Status da Transação: <strong><?php echo $status_pedido; ?></strong></p>
                                                              <p></p>
                                                              </div>
                                                            </div>
                                                            
                                                        </div> 
                                                    </div> 
                                                    
                                                    <?php    
                                                     }elseif($status_pedido === 'Pago' OR $status_pedido === 'Autorizado'){
                                                        ?>
                                                                        <div class="portlet-body form">
                                                                            <div class="form-body">
                                                                                <div class="row ">
                                                                                  <div class="col-md-4 ">
                                                                                  <h5>Imprimir Comprovante Sorteio EAD</h5>
                                                                                  <p><a href="http://fixou.com.br/painel_cursos/comprovante.php?id=<?php echo md5($id_venda_cliente); ?>&exibe_painel=ok" class="btn green" target="_blank">COMPROVANTE</a></p>
                                                                                  </div>
                
                                                                                </div>
                                                                                
                                                                            </div> 
                                                                        </div> 
                                                                    
                                                        <?php   
                                                        
                                                     }
                                                }else{
                                                    echo "erro";
                                                }
                                                ?>
                                                
                                                </div>
                                                                </div>
                                                            </div>
                                                        </div>   
                                                <?php
                                                
                                        
                                        }
                                    }
                                    ?> 
                                    
                                    
                                    </div>
                                                 
                                    </div>
                                    </div>
                                    <?php
                                    
                                    }elseif($item == 'usuarios' AND in_array("21", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <span> </span>
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste"/>
                                                    <button class="btn btn-sm green table-group-action-submit">
                                                        <i class="fa fa-check"></i> Buscar</button>
                                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                                                    <i class="fa fa-times"></i> Limpar</button>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="100"> NOME </th>
                                                            <th width="20%"> PARCEIRO </th>
                                                            <th width="20%"> FILIAL </th>
                                                            <th width="13%"> NÍVEL </th>
                                                            <th width="13%"> STATUS </th>
                                                            <th width="10%"> DETALHES </th>
                                                        </tr>
                                                        
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                                <!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
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
                                            <!-- /.modal -->
                                            </div>
                                        </div>
                                    
                                   <?php
                                    
                                    }elseif($item == 'faturamentos'){
                                    ?>
                                    
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="100"> PARCEIRO </th>
                                                            <th width="20%"> PLANO </th>
                                                            <th width="20%"> PERÍODO </th>
                                                            <th width="100"> MÊS </th>
                                                            <th width="100"> QTDE. </th>
                                                            <th width="13%"> VALOR TOTAL </th>
                                                            <th width="100"> PARC. </th>
                                                            <th width="10%"> DT FATU. </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                                <!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
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
                                            <!-- /.modal -->
                                            </div>
                                        </div>
                                    
                                    <?php
                                    
                                    }elseif($item == 'pagamentos'){
                                    ?>
                                    
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="30%"> PARCEIRO </th>
                                                            <th width="100"> PLANO </th>
                                                            <th width="20%"> VENCIMENTO </th>
                                                            <th width="100"> PARCELA </th>
                                                            <th width="13%"> VALOR PARCELA </th>
                                                            <th width="10%"> PAGO </th>
                                                            <th width="10%"> DATA CADAS. </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                                <!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
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
                                            <!-- /.modal -->
                                            </div>
                                        </div>
                                    
                                    <?php
                                    
                                    }elseif($item == 'boletos_clientes' OR $item == 'boletos_vencidos_clientes' OR $item == 'boletos_avencermes_clientes' OR $item == 'boletos_pagosmes_clientes' OR $item == 'boletos_mes_clientes'){
                                    if($tipo_filtro == 'hoje'){
                                        $html_titulo = "Hoje";
                                    }elseif($tipo_filtro == 'ontem'){
                                        $html_titulo = "Ontem";
                                    }elseif($tipo_filtro == 'ultimos_7'){
                                        $html_titulo = "Útimos 7 dias";
                                    }elseif($tipo_filtro == 'ultimos_30'){
                                        $html_titulo = "Útimos 30 dias";
                                    }elseif($tipo_filtro == 'neste_mes'){
                                        $html_titulo = "Neste mês";
                                    }elseif($tipo_filtro == 'ultimo_mes'){
                                        $html_titulo = "Útimo mês";
                                    }elseif($tipo_filtro == 'amanha'){
                                        $html_titulo = "Amanhã";
                                    }elseif($tipo_filtro == 'proximos_7'){
                                        $html_titulo = "Próximos 7 dias";
                                    }elseif($tipo_filtro == 'proximos_30'){
                                        $html_titulo = "Próximos 30 dias";
                                    }elseif($tipo_filtro == 'proximo_mes'){
                                        $html_titulo = "Próximo mês";
                                    }elseif($tipo_filtro == 'personalizar_boletos_pagos'){
                                        $html_titulo = " - Buscar pagamentos";
                                    }
                                    
                                    if($item == 'boletos_clientes'){
                                        $html_lista_titulo = "Todos";
                                    }elseif($item == 'boletos_vencidos_clientes'){
                                        $html_lista_titulo = "Vencidos";
                                    }elseif($item == 'boletos_avencermes_clientes'){
                                        $html_lista_titulo = "À vencer";
                                    }elseif($item == 'boletos_pagosmes_clientes'){
                                        $html_lista_titulo = "Pagos";
                                    }
                                    
                                    ?>
                                    <div class="col-md-12"><h1><?php echo $html_lista_titulo." ".$html_titulo;?></h1></div>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                            <div class="table-actions-wrapper" >
                                                <div class="row" >
                                                    <?php
                                                    if($tipo_filtro == 'personalizar_boletos_pagos'){
                                                        ?>
                                                        <div class="col-md-3" id="campo_data_filtro" style="display: none;">
                                                            <div class="form-group">
                                                            <label class="control-label ">Data pagamento</label>
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
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="col-md-9" id="grupo_filtro_clientes">
                                                     <?php
                                                       if ($nivel_usuario == 'A' AND $item == 'boletos_pagosmes_clientes')
                                                        {
                                                       ?>
                                                        <a class="btn green" onclick="return exibir_filtro_clientes();" href="javascript:;" style="margin-top: 14px;"> Filtrar Clientes </a> <span class="div_aguarde" style="display: none;position: absolute;width: 100%;right: 150px;padding-left: 300px;padding-top: 20px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                     <?php
                                                    if($tipo_filtro != 'personalizar_boletos_pagos'){
                                                    ?>
                                                        <div class="col-md-3">&nbsp;</div>
                                                    <?php
                                                    }
                                                    ?>
                                                       <div class="col-md-3 pull-right" id="campo_bt_busca" style="display: none;">
                                                        
                                                            <span> </span>
                                                            <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                            <input type="hidden" class="form-control form-filter input-sm" name="tipo_filtro" value="<?php echo $tipo_filtro; ?>"/>
                                                            
                                                            <!--<input class="table-group-action-input form-control input-inline input-small input-sm" name="teste"/>-->
                                                            
                                                            <button class="btn btn-sm green table-group-action-submit">
                                                                <i class="fa fa-check"></i> Buscar</button>
                                                                
                                                                <button class="btn btn-sm red btn-outline filter-cancel">
                                                                            <i class="fa fa-times"></i>&nbsp;</button>
                                                        </div>
                                                        </div>
                                                     </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <?php
                                                        
                                                        if($item == 'boletos_vencidos_clientes'){
                                                        ?>
                                                        <tr role="row" class="heading">
                                                            <th> # </th>
                                                            <th> Cliente </th>
                                                            <th> Par. </th>
                                                            <th> Fone </th>
                                                            <th> Ende. </th>
                                                            <th> Bairro </th>
                                                            <th> Cid. </th>
                                                            <th> CEP </th>
                                                            <th> Parc. </th>
                                                            <th> Tipo </th>
                                                            <th> R$ Parc. </th>
                                                            <th> Venc./Pag. </th>
                                                            <th> Plano </th>
                                                        </tr>
                                                        <?php
                                                        }else{
                                                        ?>
                                                        <tr role="row" class="heading">
                                                            <th> # </th>
                                                            <th> Cliente </th>
                                                            <th> info. </th>
                                                            <th> Parc. </th>
                                                            <th> Tipo </th>
                                                            <th> V. Parc. </th>
                                                            <th> V. Pago </th>
                                                            <th> Pag. </th>
                                                            <th> Venc. </th>
                                                            <th> Pago </th>
                                                        </tr>
                                                        <?php    
                                                        }
                                                        ?>
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    
                                    <?php
                                    
                                    }elseif($item == 'pagamentos_clientes'){
                                    ?>
                                    
                                     <div class="portlet-body">

                                             <div class="row row-centered" id="grupo_bts" style="margin-bottom: 10px;">
                                                <div class="col-md-4 col-centered">
                                                        <div class="form-group">
                                                            <label>Nome do cliente</label>
                                                            <div class="input-group input-group-lg">
                                                                <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-user"></i></span>
                                                                <input type="text" id="nome" class="form-control" placeholder="Nome do cliente" aria-describedby="sizing-addon1" /> </div>
                                                        </div>
                                                        
                                                </div>
                                                <div class="col-md-4 col-centered">
                                                        <div class="form-group">
                                                            <label>CPF</label>
                                                            <div class="input-group input-group-lg">
                                                                <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-user"></i></span>
                                                                <input type="text" id="cpf" class="form-control" placeholder="CPF" aria-describedby="sizing-addon1" /> </div>
                                                        </div>
                                                </div>
                                                <!--<div class="col-md-2 col-centered">
                                                    <div class="form-group">
                                                        <label>Cod. Carnê</label>
                                                        <div class="input-group input-group-lg">
                                                            <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-user"></i></span>
                                                            <input type="text" id="cod_barras" class="form-control" placeholder="00-0000000-000000-00000" aria-describedby="sizing-addon1" /> </div>
                                                    </div>
                                                </div>-->
                                                <div class="col-md-2 col-centered">
                                                    <div class="form-group">
                                                        <label>COD AUT.</label>
                                                        <div class="input-group input-group-lg">
                                                            <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-user"></i></span>
                                                            <input type="text" id="cod_aut" class="form-control" placeholder="000000" aria-describedby="sizing-addon1" /> </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-centered">
                                                    <div class="form-group">
                                                        <label>COD. BAIXA</label>
                                                        <div class="input-group input-group-lg">
                                                            <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-user"></i></span>
                                                            <input type="text" id="cod_baixa" class="form-control" placeholder="000000" aria-describedby="sizing-addon1" /> </div>
                                                    </div>
                                                </div>
                                             </div>
                                             <div class="row" style="margin-bottom: 10px;">
                                                 <div class="col-md-12">
                                                     <div class="md-checkbox-list">
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" name="historico_pagamento" value="S" id="historico_pagamento" class="md-check" />
                                                            <label for="historico_pagamento">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Mostrar pagamentos anteriores ou cancelados (EX) </label>                    
                                                        </div>
                                                     </div>
                                                 </div>
                                             </div>
                                             
                                             <div class="row row-centered" style="margin-bottom: 10px;">
                                                <div class="col-xs-6 col-centered col-fixed">
                                                    <a href="javascript:;" onclick="return gerar_pagamento_cliente();" class="btn btn-lg blue btn-block" id="bt_pesquisar_cliente"> <i class="fa fa-search"></i> Pesquisar </a> <span class="div_aguarde" style="position: absolute;width: 90%;padding-top: 10px;height: 100%;text-align: center;display: none;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
                                                </div>
                                             </div>
                                            <div class="row " id="conteudo_click_btn" style="margin-bottom: 10px;">
                                            
                                            </div>
                                           
                                        </div>
                                    
                                    <?php
                                    
                                    }elseif($item == 'atualizar_pagamentos' AND in_array("5", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <div class="portlet-title">
                                        <div class="actions">
                                       
                                        <a href="listar.php?item=pagamentos_clientes&tipo=cliente" class="btn btn-lg btn-icon-only green"><i class="fa fa-home"></i></a>
                                        </div>
                                    </div>
                                     <div class="portlet-body">
                                     <form  id="form_dados" action="#" enctype="multipart/form-data">
                                             <div class="row row-centered" id="grupo_bts" style="margin-bottom: 40px;">
                                                <div class="col-md-5 col-centered">
                                                   <div class="form-group">
                                                        
                                                        <div class="col-md-3">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <label for="arquivo_retorno">Formatos permitidos: .TXT | .RET | .XLSX</label>
                                                                <div class="input-group input-large">
                                                                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                                        <span class="fileinput-filename"> </span>
                                                                    </div>
                                                                    <span class="input-group-addon btn default btn-file">
                                                                        <span class="fileinput-new"> Selecionar o arquivo </span>
                                                                        <span class="fileinput-exists"> Alterar </span>
                                                                        <input type="file" name="arquivo_retorno" id="arquivo_retorno"/> </span>
                                                                    <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>     
                                                </div>
                                                <div class="col-md-3 col-centered">
                                               
                                                    <div class="form-group" style="top: -12px;position: relative;">
                                                    <label for="tipo_retorno">Tipo de Retorno (Banco)</label>
                                                        <select class="form-control" id="tipo_retorno" name="tipo_retorno">
                                                            <option value="">--> Selecionar <--</option>
                                                            <option value="arrecadacao">ARRECADAÇÃO CAIXA</option>
                                                            <option value="caixa">CAIXA ECONÔMICA</option>
                                                            <option value="santander">SANTANDER</option>
                                                            <option value="bradesco">BRADESCO</option>
                                                            <option value="cartao">MÁQUINA CIELO</option>
                                                            <option value="loja">LOJA LOCAL</option>
                                                        </select>
                                                        
                                                    </div>
                                                </div>
                                             </div>
                                             </form>
                                             <div class="row row-centered" style="margin-bottom: 10px;">
                                                <div class="col-xs-6 col-centered col-fixed">
                                                    <a href="javascript:;" onclick="return atualizar_pagamentos();" class="btn btn-lg blue btn-block" id="bt_pesquisar_cliente"> <i class="fa fa-upload"></i> Enviar </a> <span class="div_aguarde" style="position: absolute;width: 90%;padding-top: 10px;height: 100%;text-align: center;display: none;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
                                                </div>
                                             </div>
                                            <div class="row " id="conteudo_click_btn" style="margin-bottom: 10px;">
                                            
                                            <?php
                                            $sql_arquivo        = "SELECT id_retorno, nsa, data_cadastro, data_retorno, data_credito, total_titulos, titulos_emitidos, titulos_erros, titulos_recusados, emitido, id_usuario, nome_arquivo, banco FROM controle_retorno
                                            ORDER BY id_retorno DESC LIMIT 20";
                                    
                                            $query_arquivo      = mysql_query($sql_arquivo, $banco_painel);
                                                            
                                            if(mysql_num_rows($query_arquivo)>0)
                                            {
                                                
                                            ?>    
                                            <div class="col-md-12">
                                                
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-social-dribbble font-green"></i>
                                                            <span class="caption-subject font-green bold uppercase">ÚLTIMOS 20 ARQUIVOS RETORNO</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-scrollable">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th> # </th>
                                                                        <th> NSA</th>
                                                                        <th> Banco</th>
                                                                        <th> Cadastro </th>
                                                                        <th> Retorno </th>
                                                                        <th> Títulos </th>
                                                                        <th> Processados </th>
                                                                        <th> Erros </th>
                                                                        <th> Recusados </th>
                                                                        <th> Emitido </th>
                                                                        <th> Usuário </th>
                                                                        <th> Arquivo </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    while($dados = mysql_fetch_array($query_arquivo))
                                                                    {
                                                                        extract($dados); 
                                                                        $html_emitido = ($emitido == 'S')? "<span class=\"label label-sm label-success\"> Sim </span>" : "<span class=\"label label-sm label-danger\">Não</span>";
                                                                        
                                                                        $caminho_pasta_arquivo = ($banco == 'cartao')?'cartao':'boleto';
                                                                        $html_nome_arquivo = "/$pasta/arquivos/retorno/$caminho_pasta_arquivo/$nome_arquivo";

                                                                        echo "
                                                                        <tr>
                                                                        <td> $id_retorno </td>
                                                                        <td> $nsa </td>
                                                                        <td> $banco </td>
                                                                        <td> ".converte_data($data_cadastro)." </td>
                                                                        <td> ".converte_data($data_retorno)." </td>
                                                                        <td> $total_titulos </td>
                                                                        <td> $titulos_emitidos </td>
                                                                        <td> $titulos_erros </td>
                                                                        <td> $titulos_recusados </td>
                                                                        <td> $html_emitido  </td>
                                                                        <td> $id_usuario </td>
                                                                        <td> <a href=\"$html_nome_arquivo\" target=\"_blank\" class=\"btn dark btn-sm btn-outline sbold uppercase\"><i class=\"fa fa-share\"></i> Ver </a></td>
                                                                        </tr>";

                                                                    }

                                                                    ?>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                             
                                            </div>
                                                
                                            <?php     
                                            }
                                            ?>
                                            
                                            </div>
                                           
                                        </div>
                                     
                                    
                                    <?php
                                    
                                    }elseif($item == 'fluxo_pagamento' AND $nivel_usuario == 'A'){
                                        
                                        if($tipo == 'cliente'){

                                    ?>
                                    
                                     <div class="portlet-body">
                                        <form id="form_dados" action="inc/exportar_fluxo_pagamento.php" method="post" enctype="multipart/form-data">
                                             <div class="row row-centered" id="grupo_bts" style="margin-bottom: 40px;">
                                                <div class="col-md-12 col-centered">
                                                   <div class="form-group form-md-checkboxes">
<label>Parceiro(s)</label>
<div class="md-checkbox-list" id="tabela_lista_cidades">

    <?php
        
        $sql_user_pedido        = "SELECT id_parceiro, nome FROM parceiros
WHERE del = 'N'";
        $query_user_pedido      = mysql_query($sql_user_pedido);
        
        $contar_linhas = mysql_num_rows($query_user_pedido);
        if($contar_linhas>0)
        {
            $divisao_colunas = $contar_linhas / 3;
            $resultado_divisao = ceil($divisao_colunas);
            $i = 0;
            while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
            {
                extract($dados_user_pedido);
                
                if($i > 0){
                    //if($resultado_divisao < $i){
                        $i++; 
                    //
                }

                if($i == 0){
                    echo "<div class=\"col-md-4\">";
                    $i++; 
                }
                
                echo " <div class=\"md-checkbox\">
                <input type=\"checkbox\" name=\"lista_parceiros[]\" value=\"$id_parceiro\" id=\"$id_parceiro\" class=\"md-check\"/>
                <label for=\"$id_parceiro\">
                    <span></span>
                    <span class=\"check\"></span>
                    <span class=\"box\"></span> $nome </label>
                </div>";
                
                
                
                if($i > $resultado_divisao){
                    $i = 0;
                     echo "</div>";
                }/*else{
                    $i++;
                }*/
                
               /* if($resultado_divisao < $i){
                   
                }*/
                
            }
            
            echo "</div>";
        }
        
    ?>
    
    
    
       
    </div>
</div>     
                                                </div>
                                                
                                             </div>
                                             <div class="row row-centered" style="margin-bottom: 10px;">
                                                <div class="col-xs-6 col-centered col-fixed">
                                                    <button class="btn btn-lg blue btn-block"> <i class="fa fa-upload"></i> Gerar Planilha </button> <span class="div_aguarde" style="position: absolute;width: 90%;padding-top: 10px;height: 100%;text-align: center;display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                                                </div>
                                             </div>
                                             </form>
                                             

                                        </div>

                                    <?php
                                    }
                                    
                                    if($tipo == 'resultado'){
                                        
                                    ?>    
                                      <div class="portlet-body">
                                      
                                      
                                      </div>
                                    <?php  
                                    }
                                    
                                    }elseif($item == 'personalizar_pagamentos' AND in_array("41", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <div class="portlet light portlet-fit portlet-datatable ">
                                    <div class="portlet-title">
                                            <div class="caption font-dark">
                                                <i class="icon-settings font-dark"></i>
                                                <span class="caption-subject bold uppercase">Lista de Usuários</span> <span class="div_aguarde" style="position: absolute;width: 90%;padding-top: 50px;height: 100%;text-align: center;display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                                            </div>
                                            <div class="tools"> </div>
                                            
                                        </div>
                                        
                                    <div class="portlet-body table-both-scroll">
                                    
                                    <?php
                                    
                                     
                                    if($nivel_usuario == 'A'){

                                        $data_inicio = '2018-11-01';
                                        $agora 			= date("Y-m-d");
                                        $sql_user_baixas        = "SELECT id_usuario_recebimento FROM boletos_clientes 
                                        WHERE pago = 'S' AND ((baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2)) OR (baixa_recebimento = 'S' AND status_boleto IN (0,1,2) AND data_baixa = '$agora'))
                                        GROUP BY id_usuario_recebimento
                                        ORDER BY id_usuario_recebimento ASC";
                                       
                                        $query_user_baixas      = mysql_query($sql_user_baixas, $banco_painel);
                                        
                                        if(mysql_num_rows($query_user_baixas)>0)
                                        {
                                            echo "<table class=\"table table-striped table-bordered table-hover order-column\" id=\"sample_1\">
                                                <thead>
                                                    <tr>
                                                        <th>ID_NOME</th>
                                                        <th>PARCEIRO</th>
                                                        <th>ÚTIMA BAIXA</th>
                                                        <th>BOLETO</th>
                                                        <th>CARTAO</th>
                                                        <th>QUANT. PAG.</th>
                                                    </tr>
                                                </thead>
                                                <tbody>";
                                                
                                                while($dados_user_baixas = mysql_fetch_array($query_user_baixas))
                                                {
                                                    extract($dados_user_baixas);
                                                    $sql_user_nome        = "SELECT u.nome'nome_usuario', p.nome'nome_parceiro' FROM usuarios u
                                                    JOIN parceiros p ON u.id_parceiro = p.id_parceiro 
                                                    WHERE id_usuario = $id_usuario_recebimento";
                                                    $query_user_nome      = mysql_query($sql_user_nome, $banco_painel);
                                                    $nome_user = '-';
                                                    $nome_parc = '-';
                                                    if(mysql_num_rows($query_user_nome)>0)
                                                    {
                                                        $nome_user = mysql_result($query_user_nome, 0,'nome_usuario');
                                                        $nome_parc = mysql_result($query_user_nome, 0,'nome_parceiro');  
                                                    }
                                                    
                                                    $sql_data_baixas        = "SELECT data_baixa FROM boletos_clientes
                                                    WHERE id_usuario_recebimento = $id_usuario_recebimento
                                                    ORDER BY data_baixa DESC
                                                    LIMIT 0,1";
                                                    
                                                    $query_data_baixas      = mysql_query($sql_data_baixas, $banco_painel);
                                                    $data_ultima_baixa = '';
                                                    if(mysql_num_rows($query_data_baixas)>0)
                                                    {
                                                        $data_ultima_baixa = mysql_result($query_data_baixas, 0,0);
                                                    }
                                                    
                                                    
                                                    $sql_user_baixas_user        = "SELECT SUM(valor_recebido)'soma_valor_recebido', COUNT(*)'contar_boletos' FROM boletos_clientes
                                                    WHERE pago = 'S' AND baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2) AND id_usuario_recebimento = $id_usuario_recebimento AND tipo_recebimento IN ('AV', 'BO')";
                                                    
                                                    $query_user_baixas_user      = mysql_query($sql_user_baixas_user, $banco_painel);
                                                    $html_baixa_boletos = "R$ 0,00";
                                                    $contar_boletos = 0;
                                                    if(mysql_num_rows($query_user_baixas_user)>0)
                                                    {
                                                        $soma_valor_recebido_boleto = mysql_result($query_user_baixas_user, 0,0);
                                                        $contar_boletos = mysql_result($query_user_baixas_user, 0,'contar_boletos');
                                                        
                                                        $html_baixa_boletos = db_moeda($soma_valor_recebido_boleto);
                                                    }
                                                    
                                                    $sql_user_baixas_user        = "SELECT SUM(valor_recebido)'soma_valor_recebido', COUNT(*)'contar_cartao' FROM boletos_clientes
                                                    WHERE pago = 'S' AND baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2) AND id_usuario_recebimento = $id_usuario_recebimento AND tipo_recebimento = 'CA'";
                                                    
                                                    $query_user_baixas_user      = mysql_query($sql_user_baixas_user, $banco_painel);
                                                    $html_baixa_cartao = "R$ 0,00";
                                                    $contar_cartao = 0;
                                                    if(mysql_num_rows($query_user_baixas_user)>0)
                                                    {
                                                        $soma_valor_recebido_boleto = mysql_result($query_user_baixas_user, 0,0);
                                                        $contar_cartao = mysql_result($query_user_baixas_user, 0,'contar_cartao');
                                                        
                                                        $html_baixa_cartao = db_moeda($soma_valor_recebido_boleto);
                                                    }
                                                    $soma_pagamentos = $contar_boletos + $contar_cartao;
                                                    
                                                    echo "<tr>
                                                        <td><a href=\"javascrip:;\" onclick=\"return click_return_lista_user('$id_usuario_recebimento');\" id=\"bt_return_lista_user_$id_usuario_recebimento\" style=\"display:none;\" class=\"font-red-mint\"><i class=\"fa fa-toggle-left font-red-mint\"></i> voltar </a>  <a href=\"javascript:;\" onclick=\"return click_nome_usuario_pagamentos('$id_usuario_recebimento','$nome_user');\" >($id_usuario_recebimento) $nome_user</a></td>
                                                        <td>$nome_parc</td>
                                                        <td>".converte_data($data_ultima_baixa)."</td>
                                                        <td>$html_baixa_boletos</td>
                                                        <td>$html_baixa_cartao</td>
                                                        <td>$soma_pagamentos pagamentos</td>
                                                    </tr>";
                                                }
                                                echo "</tbody>
                                            </table>";
                                        }
                                        
                                    }
                                    ?>
                                                                        
                                      </div>  
                                      
                                      <span class="div_aguarde" style="position: absolute;width: 90%;padding-top: 10px;height: 100%;text-align: center;display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                                      </div>
                                      
                                    <div class="portlet light portlet-fit portlet-datatable " id="html_busca_pagamentos">
                                    
                                    </div>
         
                                        <?php
                                    }elseif($item == 'pagamentos_usuario'){
                                    $data_inicio = '2018-11-01';
                                    $agora 			= date("Y-m-d");
                                    ?>  
                                        <div class="portlet-body">
                                            <div class="col-md-6">&nbsp;</div>
                                            <?php
                                            $sql = "SELECT SUM(valor_recebido) FROM boletos_clientes
                                            WHERE pago = 'S' AND baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2) AND id_usuario_recebimento = $id_usuario_pagamento AND tipo_recebimento IN ('AV', 'BO', 'CA')";
                                            $query          = mysql_query($sql);
                                            $valor_a_confirmar = "";                
                                            if (mysql_num_rows($query)>0)
                                            {
                                               $valor_a_confirmar = mysql_result($query, 0,0);
                                            }
                                            
                                            ?>
                                            <div class="col-md-3">À confirmar: <label id="html_valor_a_confrmar"><strong><?php echo db_moeda($valor_a_confirmar); ?></strong></label></div>
                                            <?php
                                            $sql = "SELECT SUM(valor_recebido) FROM boletos_clientes
                                            WHERE pago = 'S' AND baixa_recebimento = 'S' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2) AND data_baixa = '$agora' AND id_usuario_recebimento = $id_usuario_pagamento AND tipo_recebimento IN ('AV', 'BO', 'CA')";
                                            $query          = mysql_query($sql);
                                            $valor_confirmado = "";                
                                            if (mysql_num_rows($query)>0)
                                            {
                                               $valor_confirmado = mysql_result($query, 0,0);
                                            }
                                            
                                            ?>
                                            <div class="col-md-3">Confirmado hoje: <label id="html_valor_confrmado"><strong><?php echo db_moeda($valor_confirmado); ?> </strong></label></div>
                                            <div class="table-container">
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        
                                                        <tr role="row" class="heading">
                                                            <th> # </th>
                                                            <th> Cliente </th>
                                                            <th> info. </th>
                                                            <th> Parc. </th>
                                                            <th> Tipo </th>
                                                            <th> R$ Parc. </th>
                                                            <th> R$ Pag. </th>
                                                            <th> Data Pag. </th>
                                                            <th> Venc. </th>
                                                            <th> Pago </th>
                                                        </tr>
                                                        
                                                    </thead>
                                                    <tbody> </tbody>
                                                </table>
                                                
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    if ($item <> 'personalizar_pagamentos')
                                    {
                                    ?>
                                            </div> <!-- portlet light -->
                                    <?php
                                    }
                                    ?>
                                        </div> <!-- col-12 -->
                                    </div> <!-- row -->
                                    <?php
                                    if($item == 'guias' AND in_array("6", $verifica_lista_permissoes_array_inc))                                    {
                                    ?>
                                    <?php include('inc/home_guias.php'); ?>
                                        <!--<div class="portlet-body">
                                        </div>-->
                                    
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
        if($item == 'pagamentos_clientes' OR $item == 'boletos_clientes' OR $item == 'boletos_vencidos_clientes' OR $item == 'boletos_avencermes_clientes' OR $item == 'boletos_pagosmes_clientes' OR $item == 'boletos_mes_clientes' OR $item == 'atualizar_pagamentos' OR $item == 'comprovante' OR $item == 'pagamentos_usuario' OR $item == 'personalizar_pagamentos'){
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
            <div class="container-fluid"> 2016 &copy; Painel Trail Servicos.
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
        <?php
        if(!empty($id_usuario_pagamento)){
        ?>
            <script src="assets/pages/scripts/table-datatables-ajax-pagamentos.js" type="text/javascript"></script>
        <?php
        }else{
        ?>
            <script src="assets/pages/scripts/table-datatables-ajax.js" type="text/javascript"></script>    
        <?php
        }
        ?>
        
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
