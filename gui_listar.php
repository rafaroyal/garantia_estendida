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
//$expire = time() + 30 * 86400;
$expire = $_COOKIE["usr_time"];
$pasta = base64_decode($_COOKIE["pasta"]);
setcookie("item",    base64_encode($item),    $expire, "/".$pasta);
setcookie("id",    base64_encode($id),    $expire, "/".$pasta);
setcookie("tipo",    base64_encode($tipo),    $expire, "/".$pasta);
setcookie("id_grupo_parceiro",    base64_encode($id_grupo_parceiro),    $expire, "/".$pasta);
setcookie("id_serv_get",    base64_encode($id_serv_get),    $expire, "/".$pasta);
setcookie("tipo_filtro",    base64_encode($tipo_filtro),    $expire, "/".$pasta);
$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
$usr_parceiro = base64_decode($_COOKIE["usr_parceiro"]);

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
        <!-- <link rel="stylesheet" href="assets/global/plugins/offline/themes/offline-theme-chrome.css" />
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
                <div class="container">
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
                <div class="container">
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
                    <div class="container">
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
                                        if($item == 'gui_local_atendimento' OR $item == 'gui_convenios' OR $item == 'gui_procedimentos' OR $item == 'gui_grupo_procedimentos' OR $item == 'gui_profissionais' OR $item == 'gui_pacientes'  OR $item == 'gui_guias' OR $item == 'gui_pagamentos_guia' OR $item == 'gui_pagamentos_guia_pagos' OR $item == 'gui_pagamentos_guia_avencer' OR $item == 'gui_pagamentos_guia_vencidos' OR $item == 'gui_pagamentos_guia_todos'){
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
                                                    if((in_array("10", $verifica_lista_permissoes_array_inc) OR in_array("11", $verifica_lista_permissoes_array_inc) OR in_array("14", $verifica_lista_permissoes_array_inc) OR in_array("18", $verifica_lista_permissoes_array_inc) OR in_array("28", $verifica_lista_permissoes_array_inc) OR in_array("38", $verifica_lista_permissoes_array_inc)) AND $item <> 'gui_pagamentos_guia' AND $item <> 'gui_pagamentos_guia_pagos' AND $item <> 'gui_pagamentos_guia_avencer' AND $item <> 'gui_pagamentos_guia_vencidos' AND $item <> 'gui_pagamentos_guia_todos'){
                                                ?>
                                                    <a href="gui_adicionar.php?item=<?php echo $item; ?>"  class="btn sbold green">
                                                    <i class="fa fa-plus"></i> Adicionar </a>
                                                    
                                                    <?php
                                                    if($item == 'gui_procedimentos'){
                                                    ?>
                                                    <a href="gui_listar.php?item=gui_grupo_procedimentos"  class="btn sbold green">
                                                    <i class="fa fa-search"></i> Grupos </a>
                                                    <?php
                                                    }
                                                    ?>
                                                <?php
                                                        
                                                    }
                                                
                                                    if($item == 'gui_pagamentos_guia' OR $item == 'gui_pagamentos_guia_pagos' OR $item == 'gui_pagamentos_guia_avencer' OR $item == 'gui_pagamentos_guia_vencidos' OR $item == 'gui_pagamentos_guia_todos'){
                                                    ?>

                                                        <div class="btn-group">
                                                <button class="btn btn-sm green-haze dropdown-toggle" type="button" data-toggle="dropdown"> Pagos
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_pagos&tipo=cliente&tipo_filtro=hoje"><i class="fa fa-list-ul"></i> Hoje </a>
                                                    </li>
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_pagos&tipo=cliente&tipo_filtro=ontem"><i class="fa fa-list-ul"></i> Ontem</a>
                                                    </li>
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_pagos&tipo=cliente&tipo_filtro=ultimos_7"><i class="fa fa-list-ul"></i> Útimos 7 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_pagos&tipo=cliente&tipo_filtro=ultimos_30"><i class="fa fa-list-ul"></i> Útimos 30 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_pagos&tipo=cliente&tipo_filtro=neste_mes"><i class="fa fa-list-ul"></i> Neste mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_pagos&tipo=cliente&tipo_filtro=ultimo_mes" ><i class="fa fa-list-ul"></i> Útimo mês</a>
                                                    </li>
                                                     <li class="divider"> </li>
                                                    
                                                    
                                                </ul>
                                            </div>
<!--<div class="btn-group">
                                                <button class="btn btn-sm yellow-mint dropdown-toggle" type="button" data-toggle="dropdown"> À vencer
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_avencer&tipo=cliente&tipo_filtro=hoje"><i class="fa fa-list-ul"></i> Hoje </a>
                                                    </li>
                                                    <li>
                                                        <a href="#"><i class="fa fa-list-ul"></i> Amanhã</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"><i class="fa fa-list-ul"></i> Próximos 7 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"><i class="fa fa-list-ul"></i> Próximos 30 dias</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" ><i class="fa fa-list-ul"></i> Neste mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" ><i class="fa fa-list-ul"></i> Próximo mês</a>
                                                    </li>
                                                     <li class="divider"> </li>
                                                </ul>
                                            </div>-->
                                            
                                            
                                            <div class="btn-group">
                                                <button class="btn btn-sm red-mint dropdown-toggle" type="button" data-toggle="dropdown"> NÃO PAGOS / L. ATEND.
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_vencidos&tipo=cliente&tipo_filtro=todos"><i class="fa fa-list-ul"></i> Todos </a>
                                                    </li>
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_vencidos&tipo=cliente&tipo_filtro=neste_mes" ><i class="fa fa-list-ul"></i> Neste mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_vencidos&tipo=cliente&tipo_filtro=ultimo_mes" ><i class="fa fa-list-ul"></i> Útimo mês</a>
                                                    </li>
                                                    <li class="divider"> </li>
                                                </ul>
                                            </div>
                                            
                                            <!--<div class="btn-group">
                                                <button class="btn btn-sm blue-hoki dropdown-toggle" type="button" data-toggle="dropdown"> Todos
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="gui_listar.php?item=gui_pagamentos_guia_todos&tipo=cliente&tipo_filtro=todos"><i class="fa fa-list-ul"></i> Todos </a>
                                                    </li>      
                                                    <li>
                                                        <a href="#" ><i class="fa fa-list-ul"></i> Neste mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" ><i class="fa fa-list-ul"></i> Próximo mês</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" ><i class="fa fa-list-ul"></i> Útimo mês</a>
                                                    </li>
                                                    <li class="divider"> </li>
                                                </ul>
                                            </div>-->
                                            
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
                                    if($item == 'guias' AND in_array("6", $verifica_lista_permissoes_array_inc))                                    {
                                    ?>
                                    <?php include('inc/gui_home_guias.php'); ?>
                                        <!--<div class="portlet-body">
                                        </div>-->
                                    
                                     <?php
                                     }elseif($item == 'gui_local_atendimento'){
                                     ?>
                                     
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <span> </span>
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste" placeholder="por Nome..."/>
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
                                                            <th width="20%"> TIPO </th>
                                                            <th width="20%"> LOCAL </th>
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
                                     }elseif($item == 'gui_busca_procedimentos'){
                                      ?> 
                                        <div class="portlet-title">
                                        <div class="actions">
                                        <a href="gui_listar.php?item=gui_local_atendimento" class="btn btn-lg btn-icon-only green"><i class="fa fa-home"></i></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row row-centered" id="grupo_bts" style="margin-bottom: 40px;">
                                            <div class="col-md-12 col-centered">
                                                <div class="portlet-body form">
                                                    <div class="form-body">
                                                        <div class="row note note-info">
                                                            <h4>Selecione o tipo de busca: <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span></h4>
                                                            <div class="btn-group" data-toggle="buttons">
                                                                <label class="btn btn-default">
                                                                    <input type="radio" class="toggle" value="procedimento" name="tipo_busca" id="por_proced_busca_geral" /> POR PROCEDIMENTOS </label>
                                                                
                                                            </div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="portlet-body" >
                                             <div class="row row-centered" id="grupo_bts" style="margin-bottom: 40px;">
                                                <div class="col-md-12 col-centered" >
                                                    <div class="portlet-body form" style="display: none;" id="lista_por_local">
                                        <div class="form-body">
                                            <div class="row note note-info">
                                                <h4 class="block">Selecione um local de atendimento</h4>
                                                  <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-2">Local de atendimento</label>
                                                        <div class="col-md-10">
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
                                                                WHERE id_parceiro = $usr_parceiro";
                                                            $query      = mysql_query($sql);
                                                                            
                                                            if (mysql_num_rows($query)>0)
                                                            {
                                                                $id_cidade = mysql_result($query, 0,0); 
                                                                $where_id_cidade = "WHERE g_cid_loc.loc_nu_sequencial = $id_cidade AND g_loc_ate.ativo = 'S'";
                                                            }
                                                        }
                                                        
                                                        $sql_contar        = "SELECT g_loc_ate.id_local_atendimento, g_loc_ate.nome, g_loc_ate.tipo, g_loc_ate.cidade, g_loc_ate.estado FROM gui_cidades_locais g_cid_loc
                                                        JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
                                                        $where_id_cidade ";
                                                        $query_contar      = mysql_query($sql_contar);
                                                        if (mysql_num_rows($query_contar)>0)
                                                        {
                                                            echo "<select class=\"bs-select form-control\" data-show-subtext=\"true\" data-live-search=\"true\" data-size=\"8\" name=\"select_local_guia\" id=\"select_local_guia\"><option data-content=\"&nbsp;\" value=\"\">&nbsp;</option>";
                                                            while ($dados = mysql_fetch_array($query_contar))
                                                            {
                                                                extract($dados); 
                                                                
                                                                echo "<option data-content=\"<div class='row'><div class='col-md-1 border_direita_col_local'>$id_local_atendimento</div><div class='col-md-5 border_direita_col_local'>$nome</div><div class='col-md-3 border_direita_col_local'>$tipo</div><div class='col-md-3'>$cidade-$estado</div></div>\" value=\"$id_local_atendimento\">$nome</option>";
                                                                 
                                                            }
                                                            
                                                            echo '</select>';
                                                        }
                                                        ?>
                                                            
                                                        </div>
                                                    </div>
                                                     &nbsp;
                                                  </div>
                                                  <div class="col-md-12">
                                                  <input type="hidden" name="verifica_grupo_procedimento" id="verifica_grupo_procedimento" value="" />
                                                  <input type="hidden" name="verifica_grupo_convenio" id="verifica_grupo_convenio" value="" />
                                                  <input type="hidden" name="contar_procedimentos_sel" id="contar_procedimentos_sel" value="0" />
                                                    <div id="gui_html_busca_info_local_guia"></div>
                                                  </div>
                                             </div> 
                                             <div id="html_tipo_procedimento"></div>  
                                        </div>
                                    </div>
                                    
                                    <div id="html_busca_procedmento_guia">
                                    
                                    </div>  
                                                </div>
                                                
                                             </div>

                                        </div>
                                     
                                    
                                        
                                     <?php   
                                     }elseif($item == 'gui_convenios' AND in_array("9", $verifica_lista_permissoes_array_inc)){
                                     ?>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="100"> NOME </th>
                                                            <th width="20%"> DATA CADASTRO </th>
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
                                     }elseif($item == 'gui_procedimentos'){
                                     ?>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <span> </span>
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste" placeholder="por Nome ou codigo..."/>
                                                    <button class="btn btn-sm green table-group-action-submit">
                                                        <i class="fa fa-check"></i> Buscar</button>
                                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                                                    <i class="fa fa-times"></i> Limpar</button>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%"> #COD</th>
                                                            <th width="55%"> NOME </th>
                                                            <th width="20%"> GRUPO </th>
                                                            <th width="5%"> STATUS</th>
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
                                     }elseif($item == 'gui_grupo_procedimentos'){
                                     ?>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="100"> NOME </th>
                                                            <th width="20%"> STATUS</th>
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
                                     }elseif($item == 'gui_profissionais' AND in_array("17", $verifica_lista_permissoes_array_inc)){
                                     ?>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                    <div class="row">
                                                     <div class="col-md-9" id="campo_data_filtro" style="display: none;">
                                                     
                                                     
                                                     </div>
                                                    <div class="col-md-12" id="grupo_filtro_clientes">
                                                        <a class="btn green" onclick="return exibir_filtro_profissionais();" href="javascript:;" style="margin-top: 14px;"> Filtrar Profissionais </a> <span class="div_aguarde" style="display: none;position: absolute;width: 100%;right: 125px;padding-left: 300px;padding-top: 20px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>

                                                    </div>
                                                        <div class="col-md-3 pull-right">
                                                    
                                                            <span> </span>
                                                            <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                            <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste" placeholder="Nome..."/>
                                                            <button class="btn btn-sm green table-group-action-submit">
                                                            <i class="fa fa-check"></i> Buscar</button>
                                                            <button class="btn btn-sm red btn-outline filter-cancel">
                                                                    <i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%"> ID</th>
                                                            <th width="30%"> NOME </th>
                                                            <th width="15%"> Cidade </th>
                                                            <th width="10%"> PROFISSÃO</th>
                                                            <th width="25%"> ESPECIALIDADE</th>
                                                            <th width="5%"> STATUS</th>
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
                                     }elseif($item == 'gui_pacientes' AND in_array("27", $verifica_lista_permissoes_array_inc)){
                                     ?>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                 
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste" placeholder="Nome..."/> &nbsp; &nbsp;  <input class="table-group-action-input form-control input-inline input-small input-sm form-filter" name="dt_nasc" id="data_nasc" placeholder="Data nascimento"/>
                                              
                                                    <div style="position: relative;margin: 10px 0 0 77px;">
                                                        <button class="btn btn-sm green table-group-action-submit">
                                                            <i class="fa fa-check"></i> Buscar</button>
                                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                                            <i class="fa fa-times"></i> Limpar</button>
                                                    </div>                
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                <?php
                                                
                                                    if($nivel_usuario == 'A'){
                                                        
                                                    
                                                    ?>
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%">ID</th>
                                                            <th width="35%"> NOME </th>
                                                            <th width="20%"> PARCEIRO</th>
                                                            <th width="20%"> ULTIMA GUIA</th>
                                                            <th width="10%"> DT. NASC.</th>
                                                            <th width="10%"> CONVENIO</th>
                                                            <th width="10%"> DETALHES </th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                    }else{
                                                    ?>
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="5%">ID</th>
                                                            <th width="35%"> NOME </th>
                                                            <th width="20%"> TELEFONE</th>
                                                            <th width="20%"> ULTIMA GUIA</th>
                                                            <th width="10%"> DT. NASC.</th>
                                                            <th width="10%"> CONVENIO</th>
                                                            <th width="10%"> DETALHES </th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                    }
                                                    ?>
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
                                     }elseif($item == 'gui_guias' AND in_array("31", $verifica_lista_permissoes_array_inc)){
                                     ?>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                                <div class="table-actions-wrapper">
                                                 
                                                    <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                                    <input class="table-group-action-input form-control input-inline input-small input-sm" name="teste" placeholder="Nome ou ##..."/> 
                                                    
                                                        <button class="btn btn-sm green table-group-action-submit">
                                                            <i class="fa fa-check"></i> Buscar</button>
                                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                                            <i class="fa fa-times"></i> Limpar</button>
                                                                    
                                                </div>
                                                <table class="table table-striped table-bordered table-hover table-checkable table-condensed" id="datatable_ajax">
                                                    <?php
                                                    if($nivel_usuario == 'A'){
                                                    ?>
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%">##</th>
                                                            <th width="25%"> PACIENTE </th>
                                                            <th width="15%"> AGENDADO </th>
                                                            <th width="15%"> HORA </th>
                                                            <th width="20%"> PARCEIRO </th>
                                                            <th width="20%"> LOCAL </th>
                                                            <th width="10%"> DETALHES </th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                    }else{
                                                    ?>
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%">##</th>
                                                            <th width="30%"> PACIENTE </th>
                                                            <th width="15%"> AGENDADO </th>
                                                            <th width="15%"> HORA </th>
                                                            <th width="10%"> STATUS </th>
                                                            <th width="20%"> LOCAL </th>
                                                            <th width="10%"> DETALHES </th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                    }
                                                    ?>
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
                                     }elseif($item == 'gui_pagamentos_guia' AND in_array("3", $verifica_lista_permissoes_array_inc)){
                                     ?>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%"> COD. GUIA </th>
                                                            <th width="30%"> NOME</th>
                                                            <th width="20%"> LOCAL PAG.</th>
                                                            <th width="10%"> DATA PAG.</th>
                                                            <th width="10%"> STATUS</th>
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
                                     }elseif($item == 'gui_pagamentos_guia_pagos' AND in_array("3", $verifica_lista_permissoes_array_inc)){
                                        
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
                                    }
                                    
                                    if($item == 'gui_pagamentos_guia_todos'){
                                        $html_lista_titulo = "Todos";
                                    }elseif($item == 'gui_pagamentos_guia_vencidos'){
                                        $html_lista_titulo = "Vencidos";
                                    }elseif($item == 'gui_pagamentos_guia_avencer'){
                                        $html_lista_titulo = "À vencer";
                                    }elseif($item == 'gui_pagamentos_guia_pagos'){
                                        $html_lista_titulo = "Pagos";
                                    }
                                    
                                    ?>
                                    <div class="col-md-12"><h1><?php echo $html_lista_titulo." ".$html_titulo;?></h1></div>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%"> COD. GUIA </th>
                                                            <th width="30%"> NOME</th>
                                                            <th width="20%"> LOCAL PAG.</th>
                                                            <th width="10%"> DATA PAG.</th>
                                                            <th width="10%"> STATUS</th>
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
                                     }elseif($item == 'gui_pagamentos_guia_avencer' AND in_array("3", $verifica_lista_permissoes_array_inc)){
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
                                    }
                                    
                                    if($item == 'gui_pagamentos_guia_todos'){
                                        $html_lista_titulo = "Todos";
                                    }elseif($item == 'gui_pagamentos_guia_vencidos'){
                                        $html_lista_titulo = "Vencidos";
                                    }elseif($item == 'gui_pagamentos_guia_avencer'){
                                        $html_lista_titulo = "À vencer";
                                    }elseif($item == 'gui_pagamentos_guia_pagos'){
                                        $html_lista_titulo = "Pagos";
                                    }
                                    
                                    ?>
                                    <div class="col-md-12"><h1><?php echo $html_lista_titulo." ".$html_titulo;?></h1></div>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%"> COD. GUIA </th>
                                                            <th width="30%"> NOME</th>
                                                            <th width="20%"> LOCAL PAG.</th>
                                                            <th width="10%"> DATA PAG.</th>
                                                            <th width="10%"> STATUS</th>
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
                                     }elseif($item == 'gui_pagamentos_guia_vencidos' AND in_array("3", $verifica_lista_permissoes_array_inc)){
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
                                    }
                                    
                                    if($item == 'gui_pagamentos_guia_todos'){
                                        $html_lista_titulo = "Todos";
                                    }elseif($item == 'gui_pagamentos_guia_vencidos'){
                                        $html_lista_titulo = "Vencidos";
                                    }elseif($item == 'gui_pagamentos_guia_avencer'){
                                        $html_lista_titulo = "À vencer";
                                    }elseif($item == 'gui_pagamentos_guia_pagos'){
                                        $html_lista_titulo = "Pagos";
                                    }
                                    
                                    ?>
                                    <div class="col-md-12"><h1><?php echo $html_lista_titulo." ".$html_titulo;?></h1></div>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%"> COD. GUIA </th>
                                                            <th width="30%"> NOME</th>
                                                            <th width="20%"> LOCAL PAG.</th>
                                                            <th width="10%"> DATA PAG.</th>
                                                            <th width="10%"> STATUS</th>
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
                                     }elseif($item == 'gui_pagamentos_guia_todos' AND in_array("3", $verifica_lista_permissoes_array_inc)){
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
                                    }
                                    
                                   if($item == 'gui_pagamentos_guia_todos'){
                                        $html_lista_titulo = "Todos";
                                    }elseif($item == 'gui_pagamentos_guia_vencidos'){
                                        $html_lista_titulo = "Vencidos";
                                    }elseif($item == 'gui_pagamentos_guia_avencer'){
                                        $html_lista_titulo = "À vencer";
                                    }elseif($item == 'gui_pagamentos_guia_pagos'){
                                        $html_lista_titulo = "Pagos";
                                    }
                                    
                                    ?>
                                    <div class="col-md-12"><h1><?php echo $html_lista_titulo." ".$html_titulo;?></h1></div>
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body">
                                            <div class="table-container">
                                
                                                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="10%"> COD. GUIA </th>
                                                            <th width="30%"> NOME</th>
                                                            <th width="20%"> LOCAL PAG.</th>
                                                            <th width="10%"> DATA PAG.</th>
                                                            <th width="10%"> STATUS</th>
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
                                     }elseif($item == 'gui_relatorios'){
                                      ?> 
                                        <div class="portlet-title">
                                        <div class="actions">
                                        <a href="gui_listar.php?item=gui_local_atendimento" class="btn btn-lg btn-icon-only green"><i class="fa fa-home"></i></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row row-centered" id="grupo_bts" style="margin-bottom: 40px;">
                                            <div class="col-md-12">
                                                <div class="portlet-body form">
                                                    <div class="form-body">
                                                        <div class="row note note-info">
                                                            <h4>Relatório Gerencial <span class="div_aguarde_2" id="div_aguarde_1_relatorio" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span></h4>
                                                            <ul class="list-group">
                                                                <li class="list-group-item"> <a href="gui_listar.php?item=gui_relatorio_local_atendimento" > Agendamentos / Emitidos </a> </li>
                                                                <?php
                                                                
                                                                if(in_array("64", $verifica_lista_permissoes_array_inc)){
                                                                ?>
                                                                    <li class="list-group-item"> <a href="gui_listar.php?item=gui_relatorio_faturamentos" > Faturamentos </a> </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                     <div class="portlet-body" >
                                         <div class="row row-centered" id="grupo_bts" style="margin-bottom: 40px;">
                                            <div class="col-md-12 col-centered" >  
                                                <div id="html_busca_relatorio"></div>  
                                            </div>
                                         </div>
                                     </div>
                                     
                                    
                                        
                                     <?php
                                     }elseif($item == 'gui_relatorio_local_atendimento'){
                                      ?> 
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body" >
                                        <div class="row note note-info">
    <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif" pagespeed_url_hash="3869186112"/> Aguarde ...</span>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label ">Data / Período</label>
                                                    <div class="">
                                                        <div id="reportrange" class="btn default">
                                                            <!--<i class="fa fa-calendar"></i> &nbsp;-->
                                                            <span style="font-size: 0.8em;" id="periodo"> </span>
                                                            <b class="fa fa-angle-down"></b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                              
                                              
                                                
                                                
                                                <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label col-md-12">Buscar por:</label>
                                                    <!--<div class="col-md-12">-->
                                                        <select class="bs-select form-control" data-show-subtext="true" data-live-search="true" data-size="8" name="select_buscar_por" id="select_buscar_por">
                                                        <option data-content="Data de Agendamento" value="data_agendamento" selected="">Data de Agendamento</option>
                                                        <option data-content="Data de Cadastro" value="data_cadastro">Data de Cadastro</option>
                                                        <option data-content="Data de Emissão" value="data_emissao">Data de Emissão</option>
                                                        </select>
                                                    <!--</div>-->
                                                </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-12">Local de atendimento / Parceiro</label>
                                                        <div class="col-md-12">
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
                                                                WHERE id_parceiro = $usr_parceiro";
                                                            $query      = mysql_query($sql);
                                                                            
                                                            if (mysql_num_rows($query)>0)
                                                            {
                                                                $id_cidade = mysql_result($query, 0,0); 
                                                                $where_id_cidade = "WHERE g_cid_loc.loc_nu_sequencial = $id_cidade AND g_loc_ate.ativo = 'S'";
                                                            }
                                                        }
                                                        
                                                        $sql_contar        = "SELECT g_loc_ate.id_local_atendimento, g_loc_ate.nome, g_loc_ate.tipo, g_loc_ate.cidade, g_loc_ate.estado FROM gui_cidades_locais g_cid_loc
                                                        JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
                                                        $where_id_cidade ";
                                                        $query_contar      = mysql_query($sql_contar);
                                                        if (mysql_num_rows($query_contar)>0)
                                                        {
                                                            echo "<select class=\"bs-select form-control\" data-show-subtext=\"true\" data-live-search=\"true\" data-size=\"8\" name=\"select_local_guia_relatorio\" id=\"select_local_guia_relatorio\"><option data-content=\"TODOS OS LOCAIS\" value=\"todos\">TODOS OS LOCAIS</option>";
                                                            while ($dados = mysql_fetch_array($query_contar))
                                                            {
                                                                extract($dados); 
                                                                
                                                                echo "<option data-content=\"<div class='row'><div class='col-md-1 border_direita_col_local'>$id_local_atendimento</div><div class='col-md-11 border_direita_col_local'>$nome, $cidade-$estado</div></div>\" value=\"$id_local_atendimento\">$nome</option>";
                                                                 
                                                            }
                                                            
                                                            echo '</select>';
                                                        }
                                                        ?>
                                                            
                                                        </div>
                                                    </div>
                                                     &nbsp;
                                                  
                                                </div>
                                                <div class="col-md-3" id="filtro_html_busca_profissional">
                                                
                                                </div>
                                                <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label col-md-12">&nbsp;</label>
                                                    <div class="table-actions-wrapper">

                                                        <button onclick="return gui_filtro_relatorio_local_atendimento();" class="btn btn-sm green table-group-action-submit">
                                                            <i class="fa fa-check"></i> Aplicar</button>
                                                       
                                                    </div>
                                                </div>
                                                </div>
                                                <div class="row" style="margin-bottom: 10px;">
                                                 <div class="col-md-12">
                                                     <div class="md-checkbox-list">
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" name="apenas_emissao" value="S" id="apenas_emissao" class="md-check" />
                                                            <label for="apenas_emissao">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Mostrar apenas Guias Emitidas </label>                    
                                                        </div>
                                                     </div>
                                                 </div>
                                                 
                                             </div>
                                        </div>
                                     </div>
                                     <div class="portlet-body" id="resultado_relatorio">
                                           
                                        </div>
                                    
                                        
                                    <?php
                                     }elseif($item == 'gui_relatorio_faturamentos'){
                                      ?> 
                                     <input type="hidden" class="form-control form-filter input-sm" name="item" value="<?php echo $item; ?>"/>
                                     <div class="portlet-body" >
                                        <div class="row note note-info">
    <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif" pagespeed_url_hash="3869186112"/> Aguarde ...</span>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label col-md-12">Período de Atendimento</label>
                                                    <div class="col-md-12">
                                                        <div id="reportrange" class="btn default">
                                                            <!--<i class="fa fa-calendar"></i> &nbsp;-->
                                                            <span style="font-size: 0.8em;" id="periodo"> </span>
                                                            <b class="fa fa-angle-down"></b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                            <div class="form-group">
                                            <label class="control-label ">&nbsp;</label>
                                                <div class="md-checkbox-list">
                                                    <div class="md-checkbox">
                                                        <input type="checkbox" name="todos_clientes_ativos" value="S" id="todos_clientes_ativos" class="md-check" />
                                                        <label for="todos_clientes_ativos">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Listar todas Guias pendentes! <br /> <small> o período de atendimento será anulado.</small></label>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-12">Local de atendimento / Parceiro</label>
                                                        <div class="col-md-12">
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
                                                                WHERE id_parceiro = $usr_parceiro";
                                                            $query      = mysql_query($sql);
                                                                            
                                                            if (mysql_num_rows($query)>0)
                                                            {
                                                                $id_cidade = mysql_result($query, 0,0); 
                                                                $where_id_cidade = "WHERE g_cid_loc.loc_nu_sequencial = $id_cidade AND g_loc_ate.ativo = 'S'";
                                                            }
                                                        }
                                                        
                                                        $sql_contar        = "SELECT g_loc_ate.id_local_atendimento, g_loc_ate.nome, g_loc_ate.tipo, g_loc_ate.cidade, g_loc_ate.estado FROM gui_cidades_locais g_cid_loc
                                                        JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
                                                        $where_id_cidade ";
                                                        $query_contar      = mysql_query($sql_contar);
                                                        if (mysql_num_rows($query_contar)>0)
                                                        {
                                                            echo "<select class=\"bs-select form-control\" data-show-subtext=\"true\" data-live-search=\"true\" data-size=\"8\" name=\"select_local_guia_relatorio_faturamento\" id=\"select_local_guia_relatorio_faturamento\"><option data-content=\"&nbsp;\" value=\"\">&nbsp;</option>";
                                                            while ($dados = mysql_fetch_array($query_contar))
                                                            {
                                                                extract($dados); 
                                                                
                                                                echo "<option data-content=\"<div class='row'><div class='col-md-1 border_direita_col_local'>$id_local_atendimento</div><div class='col-md-11 border_direita_col_local'>$nome, $cidade-$estado</div></div>\" value=\"$id_local_atendimento\">$nome</option>";
                                                                 
                                                            }
                                                            
                                                            echo '</select>';
                                                        }
                                                        ?>
                                                        <span id="filtro_html_busca_faruramento_parceiro">
                                                
                                                        </span>    
                                                        </div>
                                                    </div>
                                                     &nbsp;
                                                  
                                                </div>
                                                <div class="col-md-4" id="filtro_html_busca_profissional">
                                                
                                                </div>
                                                <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label col-md-12">&nbsp;</label>
                                                    <div class="table-actions-wrapper">

                                                        <button onclick="return gui_filtro_relatorio_faturamento();" class="btn btn-sm green table-group-action-submit">
                                                            <i class="fa fa-check"></i> Gerar Relatório</button>
                                                       
                                                    </div>
                                                </div>
                                                </div>
                                                
                                        </div>
                                     </div>
                                     <div class="portlet-body" id="resultado_relatorio">
                                           
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
        if($item == 'pagamentos_clientes' OR $item == 'boletos_clientes' OR $item == 'boletos_vencidos_clientes' OR $item == 'boletos_avencermes_clientes' OR $item == 'boletos_pagosmes_clientes' OR $item == 'boletos_mes_clientes' OR $item == 'atualizar_pagamentos' OR $item == 'gui_busca_procedimentos'){
        ?>
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
        <?php
        }
        ?>
        <!-- /.modal -->
        <!-- BEGIN FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container"> 2016 &copy; Painel Trail Servicos.
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
        <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimeout.js" type="text/javascript"></script>-->
        <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimer.js" type="text/javascript"></script>-->
         <script src="assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
       <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <!--<script src="assets/global/plugins/offline/offline.min.js"></script>
        <script src="assets/global/plugins/offline/js/snake.js"></script>-->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
        
        <?php
        if(!empty($tipo_filtro)){
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
        <script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
        <!--<script src="assets/pages/scripts/ui-idletimeout.min.js" type="text/javascript"></script>-->
        <?php
        if($item == 'guias' AND in_array("52", $verifica_lista_permissoes_array_inc)){
       ?>
        <script src="assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/charts-amcharts_listar.js" type="text/javascript"></script>
        <?php
        }
        ?>

        
        
                <!--<script src="assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
