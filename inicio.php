<?php

require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
require_once('inc/permissoes.php');
$item = (empty($_GET['item'])) ? "" : $_GET['item'];

$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
?>
<html lang="pt_br">

<head>
        <meta charset="utf-8" />
        <title><?php echo titulo;?> | Home</title>
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
                            <h1>Início
                                <small>Painel & Estatísticas</small>
                            </h1>
                        </div>
                        <!-- END PAGE TITLE -->
                        
                    </div>
                </div>
                <!-- END PAGE HEAD-->
                <!-- BEGIN PAGE CONTENT BODY -->
                
                <div class="page-content">
                    <div class="container">
                        <!-- BEGIN PAGE CONTENT INNER -->
                        <div class="page-content-inner">
                        <!--
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="dashboard-stat2 ">
                                        <div class="display">
                                            <div class="number">
                                                <h3 class="font-green-sharp">
                                                    <span data-counter="counterup" data-value="5">0</span>
                                                </h3>
                                                <small>PARCEIROS</small>
                                            </div>
                                            <div class="icon">
                                                <i class="icon-plus"></i>
                                            </div>
                                        </div>
                                        <a href="javascript:;" class="btn default btn-block"> Adicionar </a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="dashboard-stat2 ">
                                        <div class="display">
                                            <div class="number">
                                                <h3 class="font-green-sharp">
                                                    <span data-counter="counterup" data-value="5">0</span>
                                                </h3>
                                                <small>FILIAIS/FRANQUIAS</small>
                                            </div>
                                            <div class="icon">
                                                <i class="icon-plus"></i>
                                            </div>
                                        </div>
                                        <a href="javascript:;" class="btn default btn-block"> Adicionar </a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="dashboard-stat2 ">
                                        <div class="display">
                                            <div class="number">
                                                <h3 class="font-green-sharp">
                                                    <span data-counter="counterup" data-value="10"></span>
                                                </h3>
                                                <small>USUÁRIOS ATIVOS</small>
                                            </div>
                                            <div class="icon">
                                                <i class="icon-plus"></i>
                                            </div>
                                        </div>
                                        <a href="javascript:;" class="btn default btn-block"> Adicionar </a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="dashboard-stat2 ">
                                        <div class="display">
                                            <div class="number">
                                                <h3 class="font-red-haze">
                                                    <span data-counter="counterup" data-value="10"></span>
                                                </h3>
                                                <small>AVISOS</small>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-bell-o"></i>
                                            </div>
                                        </div>
                                       <a href="javascript:;" class="btn green-meadow btn-block"> VISUALIZAR </a>
                                    </div>
                                </div>
                            </div>
                            -->
                            
                            <?php
                            $id_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);
                            if($nivel_usuario == 'A'){
                                $sql        = "SELECT ser.id_servico, ser.nome'nome_servico' FROM servicos ser
                                JOIN produtos pro ON ser.id_servico = pro.id_servico
                                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                WHERE ser.ativo = 'S' AND pro.ativo = 'S' 
                                GROUP BY ser.id_servico ORDER BY ser.nome";
                            }else{
                                $sql        = "SELECT ser.id_servico, ser.nome'nome_servico' FROM servicos ser
                                JOIN produtos pro ON ser.id_servico = pro.id_servico
                                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                WHERE ser.ativo = 'S' AND pro.ativo = 'S' AND pser.id_parceiro = $id_parceiro_sessao
                                GROUP BY ser.id_servico ORDER BY ser.nome";
                                
                            }
                            
                            $query      = mysql_query($sql);
                            
                            if (mysql_num_rows($query)>0)
                            {
                                $contar_servico = 0;
                                while ($dados = mysql_fetch_array($query))
                                {
                                    extract($dados);   
                                    
                                ?>
                                <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption caption-md">
                                                <i class="fa fa-info-circle font-red"></i>
                                                <span class="caption-subject font-red bold uppercase"><?php echo strtoupper($nome_servico); ?></span>
                                            </div> 
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                <h4>Planos</h4>
                                                    <div class="panel-group accordion" id="accordion3">
                                                    
                                                        <?php
                                                        $sql_grupos        = "SELECT gpro.nome'nome_grupo', gpro.descricao FROM produtos pro
                                                        JOIN servicos ser ON pro.id_servico = ser.id_servico
                                                        JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
                                                        JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
                                                        WHERE pro.id_servico = $id_servico AND gpro.del = 'N'
                                                        GROUP BY gpro.nome
                                                        ORDER BY gpro.nome";
                                                        $query_grupos      = mysql_query($sql_grupos);
                                                        
                                                        if (mysql_num_rows($query_grupos)>0)
                                                        {
                                                            $contar_accordion = 0;
                                                            while ($dados_grupos = mysql_fetch_array($query_grupos))
                                                            {
                                                                extract($dados_grupos);  
                                                                 
                                                                
                                                            ?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_<?php echo $contar_servico.'_'.$contar_accordion; ?>"> <?php echo strtoupper($nome_grupo); ?> </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse_<?php echo $contar_servico.'_'.$contar_accordion; ?>" class="panel-collapse collapse">
                                                                <div class="panel-body util-btn-margin-bottom-5">
                                                                <?php echo $descricao; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    <?php
                                                    $contar_accordion++;
                                                    }
                                                }                                                   
                                                ?>
                                                        
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <h4>Produtos vínculados ao serviço: <?php echo $nome_servico; ?></h4>
                                                    <ul class="feeds">
                                                    
                                                    <?php
                                                        if($nivel_usuario == 'A'){
                                                            $sql_produtos_grupos        = "SELECT pro.nome'nome_produto_grupo', gpro.nome'nome_grupo_produto'  FROM produtos pro
                                                        JOIN servicos ser ON pro.id_servico = ser.id_servico
                                                        JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
                                                        JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
                                                        JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                                        WHERE pro.ativo = 'S' AND pro.id_servico = $id_servico
                                                        GROUP BY pro.nome";
                                                        }else{
                                                            $sql_produtos_grupos        = "SELECT pro.nome'nome_produto_grupo', gpro.nome'nome_grupo_produto'  FROM produtos pro
                                                        JOIN servicos ser ON pro.id_servico = ser.id_servico
                                                        JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
                                                        JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
                                                        JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                                        WHERE pro.ativo = 'S' AND pser.id_parceiro = $id_parceiro_sessao AND pro.id_servico = $id_servico
                                                        GROUP BY pro.nome";
                                                        }
                                                    
                                                        
                                                        $query_produtos_grupos      = mysql_query($sql_produtos_grupos);
                                                        
                                                        if (mysql_num_rows($query_produtos_grupos)>0)
                                                        {
                                                            
                                                            while ($dados_produtos_grupos = mysql_fetch_array($query_produtos_grupos))
                                                            {
                                                                extract($dados_produtos_grupos);  
                                                                 
                                                                
                                                            ?>
                                                        <li>
                                                            <div class="col1">
                                                                <div class="cont">
                                                                    <div class="cont-col1">
                                                                        <div class="label label-sm label-success">
                                                                            <i class="fa fa-object-group"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cont-col2">
                                                                        <div class="desc"> <?php echo $nome_produto_grupo; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col2">
                                                               <?php /*echo $nome_grupo_produto;*/ ?> 
                                                            </div>
                                                        </li>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                             <hr />
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    
                                <?php   
                                $contar_servico++;        
                                }
                            }
                    
                    
                            ?>
                            
                            
                            
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
                    </div>
                </div>
                <!-- END PAGE CONTENT BODY -->
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <div class="page-footer">
            <div class="container"> 2016 &copy; Painel Trail Servicos.
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
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimeout.js" type="text/javascript"></script>-->
        <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimer.js" type="text/javascript"></script>-->
        <!--<script src="assets/global/plugins/offline/offline.min.js"></script>
        <script src="assets/global/plugins/offline/js/snake.js"></script>-->
        
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!--<script src="assets/pages/scripts/ui-idletimeout.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
