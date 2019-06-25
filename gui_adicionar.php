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
//$expire = time() + 30 * 86400;
$expire = $_COOKIE["usr_time"];
$pasta = base64_decode($_COOKIE["pasta"]);
setcookie("item",    base64_encode($item),    $expire, "/".$pasta);
$nivel_usuario      = base64_decode($_COOKIE["usr_nivel"]);
$id_usuario_sessao  = base64_decode($_COOKIE["usr_id"]);
$usr_parceiro = base64_decode($_COOKIE["usr_parceiro"]);
 include ('inc/titulo.php');
 
 
?>

<html lang="pt_br">

<head>
        <meta charset="utf-8" />
        <title><?php echo titulo;?> | Adicionar</title> 
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
        
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
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
                            <h1><?php echo $titulo; ?>
                            </h1>
                        </div>
                        <!-- END PAGE TITLE -->
                        
                    </div>
                </div>
                <!-- END PAGE HEAD-->
                <!-- BEGIN PAGE CONTENT BODY -->
                <div class="page-content">
                    <div class="container">
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
                                   if($item == 'gui_local_atendimento' AND $nivel_usuario == 'A'){
                                   ?>
                                    <form role="form" name="form" id="form_adicionar" action="gui_adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Local Atendimento</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="" style="text-transform: uppercase;" maxlength="40"/>
                                                            <label for="nome">Nome Completo do Local</label>
                                                            <span class="help-block">Digite o nome completo do local de atendimento...</span>
                                                         </div>
                                                         &nbsp;
                                                   </div>
                                                   <div class="col-md-3 ">
                                                             <div class="form-group form-md-radios">
                                                                <label>Conveniado</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_sim" name="conveniado" class="md-radiobtn" value="S" />
                                                                        <label for="conveniado_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_nao" name="conveniado" class="md-radiobtn" value="N" />
                                                                        <label for="conveniado_nao">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Não </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             &nbsp;
                                                        </div>
                                                </div>
                                                    <div class="row">
                                                        <div class="col-md-3 ">
                                                             <div class="form-group form-md-radios">
                                                                <label>Local de pagamento</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="ver_local_pagamento_l" name="ver_local_pagamento" class="md-radiobtn" value="LOCAL" checked="" />
                                                                        <label for="ver_local_pagamento_l">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> NA EMISSÃO </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="ver_local_pagamento" name="ver_local_pagamento" class="md-radiobtn" value="CLINICA/CONSULTORIO" />
                                                                        <label for="ver_local_pagamento">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> LOCAL ATENDIMENTO </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             &nbsp;
                                                        </div>
                                                      <div class="col-md-3 ">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" id="tipo_local_atendimento" name="tipo_local_atendimento">
                                                                <option value=""></option>
                                                                <option value="CLINICA">Clinica</option>
                                                                <option value="CONSULTORIO">Consultório</option>
                                                                <option value="LABORATORIO_CLINICO">Laboratório Clínico</option>
                                                                <option value="LABORATORIO_IMAGEM">Laboratório de Imagem</option>
                                                            </select>
                                                            <label for="tipo">Tipo</label>
                                                        </div>
                                                         &nbsp;
                                                      </div>
                                                        <div class="col-md-4 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="email" class="form-control" id="email" value=""/>
                                                            <label for="email">E-mail</label>
                                                            <span class="help-block">Digite o e-mail corretamente...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-2 ">
                                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="cnes" class="form-control" id="cnes" value="" style="text-transform: uppercase;" maxlength="10"/>
                                                                <label for="cnes">CNES</label>
                                                                <span class="help-block">Cadastro Nacional de Estabelecimentos de Saúde</span>
                                                             </div>
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-4">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="telefone_com" class="form-control" id="telefone_com" value="" />
                                                            <label for="telefone_com">Telefone (Comercial)</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-4">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="telefone_alt" class="form-control" id="telefone_alt" value=""/>
                                                            <label for="celular">Telefone (Alternativo)</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                       </div>
                                                       <div class="col-md-4">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="celular" class="form-control" id="celular" value=""/>
                                                            <label for="celular">Telefone (Celular)</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                       </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-3 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="cep" class="form-control" id="cep" value="" style="text-transform: uppercase;"/>
                                                            <label for="cep">CEP</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-7 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="endereco" class="form-control" id="endereco" value="" maxlength="40"/>
                                                            <label for="endereco">Endereço</label>
                                                            <span class="help-block">Digite o endereço completo...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-2 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="numero" class="form-control" id="numero" value="" maxlength="4"/>
                                                            <label for="numero">Número</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                    </div>
                                                     <div class="row">
                                                      <div class="col-md-3 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="complemento" class="form-control" id="complemento" value="" style="text-transform: uppercase;"/>
                                                            <label for="complemento">Complemento</label>
                                                            <span class="help-block">Ex.: ap. 526</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="bairro" class="form-control" id="bairro" value="" maxlength="20"/>
                                                            <label for="bairro">Bairro</label>
                                                             <span class="help-block">LIMITE DE 20 CARACTERES...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-4 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="cidade" class="form-control" id="cidade" value="" style="text-transform: uppercase;" maxlength="30"/>
                                                            <label for="cidade">Cidade</label>
                                                            
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-2 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="estado" class="form-control" id="estado" value=""/>
                                                            <label for="estado">Estado</label>
                                                            <span class="help-block">Campo com 2 dígitos</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                    <h4>Observações</h4>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <textarea class="form-control" rows="3" id="observacoes_local" name="observacoes_local" ></textarea>     
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                                

                                        </div>
                                    </div> <!-- FIM BLOCO -->
                                    
                                    <div class="portlet light" id="hash_cidades">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Cidade(s) de Atendimento</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                    <?php
                                                $sql_user_pedido        = "SELECT ufe_sg FROM log_localidade
                                        GROUP BY ufe_sg
                                                ";
                                                $query_user_pedido      = mysql_query($sql_user_pedido);
                                                                
                                                if(mysql_num_rows($query_user_pedido)>0)
                                                {
                                                    echo "
                                                    <select class=\"form-control\" data-size=\"8\" id=\"estado_atendimento\" name=\"estado_atendimento\" ><option value=\"\"></option>";
                                                    
                                                    while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
                                                    {
                                                        extract($dados_user_pedido);
                                                        $html_select = '';
                                                        /*if($estado == $ufe_sg){
                                                            $html_select = 'selected';
                                                        }*/
                                                        
                                                        echo "<option value=\"$ufe_sg\" $html_select>$ufe_sg</option>";
                                                    }
                                                    
                                                    echo "</select>";
                                                }
                                                
                                            ?>
                                            <label for="estado_atendimento">Estado</label>
                                            </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                       <div class="form-actions noborder">
                                                       <span class="div_aguarde" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, buscando cidades...</span>
                                                    </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                    <div id="listas_cidades_local">
                                                    
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="portlet light ">
                                        <div class="row">
                                                <div class="col-md-12">
                                                    <div id="click_campo_gui_nome_procedimento"></div>
                                                </div>
                                            </div>
                                            <div class="portlet-title">
                                                <div class="caption font-green">
                                                    <i class="fa fa-plus font-green"></i>
                                                    <span class="caption-subject bold uppercase"> Adicionar Procedimentos&nbsp; <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span></span>
                                                </div>
                                            </div>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                            
                                                <div class="row note-info">
                                                        <div class="col-md-2">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="get_id_procedimento" class="form-control id_procedimento_mask" id="get_id_procedimento" value="" />
                                                            <label for="get_id_procedimento">Código</label>
                                                         </div>
                                                         &nbsp;
                                                          </div>
                                                          <div class="col-md-10">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="get_nome_procedimento" class="form-control" id="get_nome_procedimento" value="" autocomplete="off" />
                                                                <label for="get_nome_procedimento">Nome do procedimento</label>
                                                                <span class="help-block">Digite o nome do procedimento...</span>
                                                             </div>
                                                             &nbsp;
                                                          </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div id="resultado_campo_gui_nome_procedimento"></div>
                                                        </div>
                                                    </div>
                                                    
                                            </div>
                                        </div>
                                        </div>           
                                    </div>
                                </div>
                                                
                                            <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn btn-lg blue">Salvar</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                    <?php
                                    }elseif ($item == 'gui_convenios' AND in_array("9", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar" action="gui_adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Convenio</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            
                                            <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                                <div class="form-body">
                                                    <div class="row">
                                                    
                                                      <div class="col-md-12">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value=""/>
                                                            <label for="nome">Nome</label>
                                                            <span class="help-block">Como será chamado o convenio...</span>
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
                                                    <button type="submit" class="btn blue">Salvar</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                    <?php
                                    }elseif ($item == 'gui_grupo_procedimentos' AND in_array("14", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar" action="gui_adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Grupo de Procedimentos</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>">
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
                                                    <button type="submit" class="btn blue">Salvar</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                     <?php
                                    }elseif ($item == 'gui_procedimentos' AND in_array("14", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar" action="gui_adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Procedimento</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            
                                            <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                                <div class="form-body">
                                                    <div class="row">
                                                    
                                                    <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="codigo_procedimento" class="form-control" id="codigo_procedimento" value=""/>
                                                            <label for="codigo_procedimento">#Codigo</label>
                                                            <span class="help-block">Código do procedimento...</span>
                                                         </div>
                                                         &nbsp;
                                                    </div>
                                                    <div class="col-md-6">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value=""/>
                                                            <label for="nome">Nome</label>
                                                            <span class="help-block">Como será chamado o procedimento...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                    <?php
                                                $sql_grupo_proc        = "SELECT id_grupo_procedimento, nome FROM gui_grupo_procedimentos
                                                                        WHERE ativo = 'S'";
                                                $query_grupo_proc      = mysql_query($sql_grupo_proc);
                                                                
                                                if(mysql_num_rows($query_grupo_proc)>0)
                                                {
                                                    echo "
                                                    <select class=\"form-control\" data-size=\"8\" id=\"grupo_procedimento\" name=\"grupo_procedimento\" ><option value=\"\"></option>";
                                                    
                                                    while($dados_grupo_proc = mysql_fetch_array($query_grupo_proc))
                                                    {
                                                        extract($dados_grupo_proc);
                                                        
                                                        echo "<option value=\"$id_grupo_procedimento\">$nome</option>";
                                                    }
                                                    
                                                    echo "</select>";
                                                }
                                                
                                            ?>
                                            <label for="grupo_procedimento">Grupo de Procedimentos</label>
                                            </div>
                                                    </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                    <h4>Observações</h4>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <textarea class="form-control" rows="2" id="observacoes_procedimentos" name="observacoes_procedimentos" maxlength="100"></textarea>     
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                

                                        </div>
                                    </div>
                                                   
                                            </div>
                                        </div>
                                                
                                            <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn blue">Salvar</button> &nbsp;
                                                    <button type="submit" value="1" name="bt_salvar_mais" class="btn grey">Salvar e Adicionar outro</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                     <?php
                                    }elseif ($item == 'gui_profissionais' AND in_array("18", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar" action="gui_adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Profissional</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            
                                            <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                                <div class="form-body">
                                                    <div class="row">
                                                    
                                                    <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" data-size="8" id="tratamento_profissional" name="tratamento_profissional" >
                                                                <option value=""></option>
                                                                <option value="Dr.">Dr.</option>
                                                                <option value="Dra.">Dra.</option>
                                                                <option value="Sr.">Sr.</option>
                                                                <option value="Sra.">Sra.</option>
                                                            </select>
                                                            <label for="tratamento_profissional">Tratamento</label>
                                                            <span class="help-block">do profissional...</span>
                                                         </div>
                                                         &nbsp;
                                                    </div>
                                                    <div class="col-md-6">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value=""/>
                                                            <label for="nome">Nome</label>
                                                            <span class="help-block">Nome do profissional...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="dt_nasc_profissional" class="form-control" id="dt_nasc_profissional" value=""/>
                                                            <label for="nome">Data de Nascimento</label>
                                                            <span class="help-block">somente números...</span>
                                                         </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="telefone_com" class="form-control" id="telefone_com" value="" />
                                                            <label for="telefone_com">Telefone</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                         <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="celular" class="form-control" id="celular" value=""/>
                                                            <label for="celular">Telefone (Celular)</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                       </div>
                                                       <div class="col-md-6">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="email" class="form-control" id="email" value=""/>
                                                            <label for="email">E-mail</label>
                                                            <span class="help-block">Digite o e-mail corretamente...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3 ">
                                                             &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">

                                                    <?php
                                                $sql_convenio        = "SELECT id_profissao, nome FROM gui_profissoes
                                                                        WHERE ativo = 'S'";
                                                $query_convenio      = mysql_query($sql_convenio);
                                                                
                                                if(mysql_num_rows($query_convenio)>0)
                                                {
                                                    echo "
                                                    <select class=\"form-control\" data-size=\"8\" id=\"profissao\" name=\"profissao\" ><option value=\"\"></option>";
                                                    
                                                    while($dados_convenio = mysql_fetch_array($query_convenio))
                                                    {
                                                        extract($dados_convenio);
                                                        
                                                        echo "<option value=\"$id_profissao\">$nome</option>";
                                                    }
                                                    
                                                    echo "</select> ";
                                                }
                                                
                                            ?>
                                            <label for="convenio">Profissão</label>

                                            </div>
                                                    </div>
                                                   <div class="col-md-9">
                                                   <input type="hidden" name="principal_contar_especialidade_atual" id="principal_contar_especialidade_atual" value="0"/>
                                                   <input type="hidden" name="principal_contar_especialidade" id="principal_contar_especialidade" value="0"/>
                                                   <div id="inserir_mais_especialidades"></div>
                                                   <div class="row" style="background: #eeeeee;"><div class="col-md-6">
                                                        <div class="form-actions noborder">
                                                          <a href="javascript:" class="btn green" id="principal_bt_add_especialidade"><i class="fa fa-plus"></i> Especialidade</a> <span class="div_aguarde_especialidade" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde...</span>
                                                        </div>
                                                        &nbsp;
                                                   </div></div>
                                                             
                                                   </div>
                                                      
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" data-size="8" id="conselho" name="conselho" >
                                                                <option value=""></option>
                                                                <option value="CRAS">CRAS</option>
                                                                <option value="CRBM">CRBM</option>
                                                                <option value="CREFITO">CREFITO</option>
                                                                <option value="COREM">COREM</option>
                                                                <option value="CRF">CRF</option>
                                                                <option value="CRFA">CRFA</option>
                                                                <option value="CRM">CRM</option>
                                                                <option value="CRN">CRN</option>
                                                                <option value="CRO">CRO</option>
                                                                <option value="CRP">CRP</option>
                                                                <option value="CRT">CRT</option>
                                                                <option value="CRNT">CRNT</option>
                                                            </select>
                                                            <label for="conselho">Conselho</label>
                                                            <span class="help-block">...</span>
                                                         </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="registro" class="form-control" id="registro" value=""/>
                                                            <label for="registro">Registro</label>
                                                            <span class="help-block">número de registro do conselho...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3 ">
                                                             <div class="form-group form-md-radios">
                                                                <label>Ativo</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="ativo_sim" name="ativo" class="md-radiobtn" value="S" checked=""/>
                                                                        <label for="ativo_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="ativo_nao" name="ativo" class="md-radiobtn" value="N" />
                                                                        <label for="ativo_nao">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Não </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             &nbsp;
                                                        </div>
                                                        <div class="col-md-3 ">
                                                             <div class="form-group form-md-radios">
                                                                <label>Conveniado</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_sim" name="conveniado" class="md-radiobtn" value="S" />
                                                                        <label for="conveniado_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_nao" name="conveniado" class="md-radiobtn" value="N" />
                                                                        <label for="conveniado_nao">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Não </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             &nbsp;
                                                        </div>
                                                    </div>
                                                    
                                                    <hr />
                                                    <div class="row">
                                                    <h4> Convenios</h4>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <?php
                                                                
                                                                    $sql_lista_convenio        = "SELECT id_convenio, nome FROM gui_convenios
                                                                    WHERE ativo = 'S' 
                                                                    ORDER BY nome";
                                                                    $query_lista_convenio      = mysql_query($sql_lista_convenio);
                                                                        
                                                                    if (mysql_num_rows($query_lista_convenio)>0)
                                                                    {
                                                                        echo "<div class=\"form-group form-md-checkboxes\">
                                                    
                                                                        <div class=\"md-checkbox-inline\">";
                                                                        while($dados_lista_convenio = mysql_fetch_array($query_lista_convenio))
                                                                        {
                                                                            extract($dados_lista_convenio);
                                                                            
                                                                            echo "<div class=\"md-checkbox\" style=\"    width: 23%;\">
                                                                            <input type=\"checkbox\" id=\"separa_permissao_$id_convenio\" name=\"lista_convenios[]\" class=\"md-check\" value=\"$id_convenio\">
                                                                            <label for=\"separa_permissao_$id_convenio\">
                                                                                <span></span>
                                                                                <span class=\"check\"></span>
                                                                                <span class=\"box\"></span> $nome </label>
                                                                            </div>";
                                                                            
                                                                        }
                                                                        
                                                                        echo "</div>
                                                                            </div>";
                                                                    }else{
                                                                        echo "Sem convenio cadastrado!";
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                    <h4> Locais de Atendimento</h4>
                                                        <div class="col-md-12">
                                                           <input type="hidden" name="principal_contar_local_atual" id="principal_contar_local_atual" value="0"/>
                                                           <input type="hidden" name="principal_contar_local" id="principal_contar_local" value="0"/>
                                                           <div id="inserir_mais_locals"></div>
                                                           <div class="row" style="background: #eeeeee;">
                                                                <div class="col-md-6">
                                                                <div class="form-actions noborder">
                                                                  <a href="javascript:" class="btn green" id="principal_bt_add_local"><i class="fa fa-plus"></i> Local de Atendimento</a> <span class="div_aguarde_local" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde...</span>
                                                                </div>
                                                                &nbsp;
                                                                </div></div>
                                                        
                                                        
                                                        
                                                        
                                                            <!--<div class="form-group">
                                                            <?php
                                                            /*
                                                                    $where_id_cidade = '';
                                                                    if($nivel_usuario != 'A'){
                                                                        $sql        = "SELECT id_cidade FROM parceiros
                                                                            WHERE id_parceiro = $usr_parceiro";
                                                                        $query      = mysql_query($sql, $banco_painel);
                                                                                        
                                                                        if (mysql_num_rows($query)>0)
                                                                        {
                                                                            $id_cidade = mysql_result($query, 0,0); 
                                                                            $where_id_cidade = "g_cid_loc.loc_nu_sequencial = $id_cidade AND";
                                                                        }
                                                                    }
                                                                    
                                                                    $sql_local        = "SELECT g_loc_ate.* FROM gui_cidades_locais g_cid_loc
                                                            JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
                                                            WHERE $where_id_cidade g_loc_ate.ativo = 'S'
                                                            GROUP BY g_loc_ate.id_local_atendimento";
                                                                    $query_local      = mysql_query($sql_local, $banco_painel);
                                                                    if (mysql_num_rows($query_local)>0)
                                                                    {
                                                                        echo "<div class=\"form-group form-md-checkboxes\">
                                                    
                                                                        <div class=\"md-checkbox-inline\">";
                                                                        while($dados_local = mysql_fetch_array($query_local))
                                                                        {
                                                                            extract($dados_local);
                                                                            
                                                                            echo "<div class=\"md-checkbox\" style=\"    width: 23%;\">
                                                                            <input type=\"checkbox\" id=\"separa_permissao_$id_local_atendimento\" name=\"lista_local_atendimento[]\" class=\"md-check\" value=\"$id_local_atendimento\">
                                                                            <label for=\"separa_permissao_$id_local_atendimento\">
                                                                                <span></span>
                                                                                <span class=\"check\"></span>
                                                                                <span class=\"box\"></span> $nome </label>
                                                                            </div>";
                                                                            
                                                                        }
                                                                        
                                                                        echo "</div>
                                                                            </div>";
                                                                    }else{
                                                                        echo 'Sem local de atendimento!';
                                                                    }
                                                                */?>
                                                            </div>-->
                                                        </div>

                                                    </div>
                                                </div>
                                                

                                        </div>
                                    </div>
                                                   
                                            </div>
                                        </div>
                                                
                                            <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn blue">Salvar</button> &nbsp;
                                                    <button type="submit" value="1" name="bt_salvar_mais" class="btn grey">Salvar e Adicionar outro</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                   <?php
                                    }elseif ($item == 'gui_pacientes' AND in_array("28", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar_cliente" action="gui_adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <input type="hidden" name="id_cliente" id="id_cliente" value="0"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Adicionar Paciente | <a href="inc/gui_importar_cliente_paciente.php" id="" data-target="#ajax" data-toggle="modal" class="btn btn-sm green"> Importar cliente</a> &nbsp; <a href="javascript:" class="btn btn-sm red btn-outline sbold" id="bt_cancela_importacao" onclick="return gui_cancela_importacao();" style="display: none;">
                    <i class="fa fa-times"></i> Cancelar Importação</a></span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                                <div class="form-body">
                                                    <div class="row">
                                                      <div class="col-md-9">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="" />
                                                            <label for="nome">Nome do paciente</label>
                                                            <span class="help-block">Nome completo...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="data_nasc" class="form-control" id="data_nasc" value="" />
                                                            <label for="data_nasc">Data de Nascimento</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        </div>
                                                    <div class="row">
                                                    <div class="col-md-3">
                                                      <div class="form-group form-md-line-input form-md-floating-label">
                                                           <input type="hidden" name="convenio_paciente" id="convenio_paciente" value="5"/>
                                                           <style>
                                                           .exibe_convenio{
                                                            display: block!important;
                                                           }
                                                           .lista_id_imput_convenio{
                                                            display: none;
                                                           }
                                                           </style>
                                                            <?php 
                                                            // AND id_usuario <> $id_usuario_sessao
                                                            $sql_lista_convenio        = "SELECT id_convenio, nome FROM gui_convenios
                                                                    WHERE ativo = 'S' 
                                                                    ORDER BY nome";
                                                            $query_lista_convenio      = mysql_query($sql_lista_convenio);
                                                                            
                                                            if(mysql_num_rows($query_lista_convenio)>0)
                                                            {
                                                                while($dados_lista_convenio = mysql_fetch_array($query_lista_convenio))
                                                                {
                                                                    extract($dados_lista_convenio);
                                                                    $class_ = '';
                                                                    if($id_convenio == 5){
                                                                        $class_ = 'exibe_convenio';
                                                                    }
                                                                    echo "<input type=\"text\" name=\"nome_do_convenio\" class=\"form-control lista_id_imput_convenio id_convenio_$id_convenio $class_\" value=\"$nome\" readonly=\"\"/>";
                                                                   
                                                                } 
                                                            }
                                                            
                                                             ?>
                                                             <label for="nome">Convenio</label>
                                                   </div>
                                                </div>
                                                    <div class="col-md-3">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input  type="text" name="cpf_paciente" class="form-control cpf" id="cpf_paciente" value="" onkeyup="return verificarCPF(this.value)"/>
                                                        <label for="cpf_paciente">CPF</label>
                                                        <span class="help-block">Somente números..</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-3">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <select class="form-control" id="sexo" name="sexo">
                                                            <option value=""></option>
                                                            <option value="M">Masculino</option>
                                                            <option value="F">Feminino</option>
                                                        </select>
                                                        <label for="sexo">Sexo</label>
                                                    </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-3">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="email" class="form-control" id="email" value=""/>
                                                        <label for="email">E-mail</label>
                                                        <span class="help-block">Digite o e-mail corretamente...</span>
                                                     </div>
                                                     &nbsp;
                                                   </div>
                                                </div>
                                               <div class="row">
                                                  <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="telefone" class="form-control" id="telefone" value="" />
                                                        <label for="telefone">Telefone (fixo)</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="celular" class="form-control" id="celular" value=""/>
                                                        <label for="celular">Celular</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                   </div>
                                                   <div class="col-md-3">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="comercial" class="form-control" id="comercial" value=""/>
                                                        <label for="celular">Comercial</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                   </div>
                                                 </div>  
                                                <div class="row">
                                                  <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="cep_paciente" class="form-control" id="cep" value="" style="text-transform: uppercase;"/>
                                                        <label for="cep_paciente">CEP</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-7 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="endereco_paciente" class="form-control" id="endereco" value="" maxlength="50"/>
                                                        <label for="endereco">Endereço</label>
                                                        <span class="help-block">Digite o endereço completo...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-2 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="numero_paciente" class="form-control" id="numero" value="" maxlength="4"/>
                                                        <label for="numero">Número</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="complemento_paciente" class="form-control" id="complemento" value="" style="text-transform: uppercase;" maxlength="30"/>
                                                        <label for="complemento">Complemento</label>
                                                        <span class="help-block">Ex.: ap. 526</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="bairro_paciente" class="form-control" id="bairro" value="" maxlength="40"/>
                                                        <label for="bairro">Bairro</label>
                                                         <span class="help-block">LIMITE DE 40 CARACTERES...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-4 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="cidade_paciente" class="form-control" id="cidade" value="" style="text-transform: uppercase;" maxlength="40"/>
                                                        <label for="cidade">Cidade</label>
                                                        
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-2 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="estado_paciente" class="form-control" id="estado" value=""/>
                                                        <label for="estado">Estado</label>
                                                        <span class="help-block">Campo com 2 dígitos</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                               <!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
                                            <div class="modal fade modal-scroll" id="ajax" role="basic" aria-hidden="true">
                                                <div class="modal-dialog">
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
                                                
                                            <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn blue">Salvar</button> &nbsp;
                                                    <button type="submit" value="1" name="bt_salvar_mais" class="btn grey">Salvar e Adicionar outro</button>
                                                </div>
                                            </div>
                                                
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
                                    
                                   <?php
                                    }elseif ($item == 'gui_guias' AND in_array("38", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                    <form role="form" name="form" id="form_adicionar_guia" action="gui_adicionar_db.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                    <input type="hidden" name="id_cliente" id="id_cliente" value="0"/>
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> AGENDAR PARA... </span> 
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                                <div class="form-body">
                                                    <div class="row">
                                                       
                                                          <div class="col-md-6">
                                                             <div class="form-group">
                                                        <label class="control-label col-md-3">Data de agendamento</label>
                                                        <div class="col-md-3">
                                                            <div class="input-group input-medium date date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
                                                                <input type="text" class="form-control" value="<?php echo date('d-m-Y'); ?>" name="data_agendamento" id="data_agendamento" readonly  />
                                                                <span class="input-group-btn">
                                                                    <button class="btn default" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            <!-- /input-group -->
                                                            <!--<span class="help-block"> Selecione </span>-->
                                                        </div>
                                                    </div>
                                                             &nbsp;
                                                          </div>
                                                          <div class="col-md-6">
                                                             <div class="form-group">
                                                        <label class="control-label col-md-3">Horário de agendamento</label>
                                                        <div class="col-md-6">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control timepicker timepicker-24" name="horario_agendamento" id="horario_agendamento"/>
                                                                <span class="input-group-btn">
                                                                    <button class="btn default" type="button">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                             &nbsp;
                                                          </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                    <div class="col-md-12" >
                                                        <div class="form-actions noborder">
                                                            <a href="javascript:;" class="btn default green-stripe" id="bt_avancar_passo2_guia" style="float: right;"> Selecionar o Paciente <i class="fa fa-angle-right"></i> </a>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    
                                                </div>
                                        </div>
                                        </div>
                                    <div class="portlet light" id="portlet_paciente" style="display: none;">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> DADOS DO PACIENTE </span> &nbsp; <span class="div_aguarde_2" id="div_aguarde_2_dados_paciente" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                                            </div>
                                            
                                        </div>
                                        <div class="portlet-body form">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="id_paciente" class="form-control id_paciente_mask" id="id_paciente" value="" />
                                                            <label for="nome">Código</label>
                                                         </div>
                                                         &nbsp;
                                                          </div>
                                                          <div class="col-md-8">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="gui_nome_paciente" class="form-control" id="gui_nome_paciente" value="" autocomplete="off" />
                                                                <label for="gui_nome_paciente">Nome do paciente</label>
                                                                <span class="help-block">Nome completo...</span>
                                                             </div>
                                                             &nbsp;
                                                          </div>
                                                          <div class="col-md-2">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="data_nascimento" class="form-control" id="data_nascimento" value="" readonly="" />
                                                                <label for="nome">Data de Nascimento</label>
                                                             </div>
                                                             &nbsp;
                                                          </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">&nbsp;</div>
                                                        <div class="col-md-8">
                                                            <span class="div_aguarde_2" id="div_aguarde_2_dados_paciente_2" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                                                            <div id="resultado_campo_gui_nome_paciente"></div>
                                                            <input type="hidden" name="id_convenio_paciente_sel" id="id_convenio_paciente_sel" value="" />
                                                            <div id="html_tipo_convenio_paciente"></div>
                                                        </div>
                                                        <div class="col-md-2">&nbsp;</div>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                    <div class="col-md-12" >
                                                        <div class="form-actions noborder">
                                                            <a href="javascript:;" class="btn default green-stripe" id="bt_avancar_passo3_guia" style="float: right;"> Selecionar Procedimento(s) <i class="fa fa-angle-right"></i> </a>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                        <div class="portlet light" id="portlet_procedimentos" style="display: none;">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> PROCEDIMENTO (S) / LOCAL ATENDIMENTO</span> &nbsp; <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                                            </div>
                                            
                                        </div>
                                        
                                    <div class="portlet-body form">
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
                                                  <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento_2" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                                                    <div id="gui_html_busca_info_local_guia"></div>
                                                  </div>
                                             </div> 
                                             <div id="html_tipo_procedimento"></div>  
                                        </div>
                                    </div>
                                    <div id="html_busca_procedmento_guia"></div>
                                                <hr />
                                                    <div class="row">
                                                    <div class="col-md-12" >
                                                        <div class="form-actions noborder">
                                                            <a href="javascript:;" class="btn default green-stripe" id="bt_avancar_passo4_guia" style="float: right;"> Selecionar o Profissional <i class="fa fa-angle-right"></i> </a>
                                                        </div>
                                                    </div>
                                                    </div> 
                                            </div>
                                            <div class="portlet light" id="portlet_profissional" style="display: none;">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> PROFISSIONAL RESPONSÁVEL</span>  &nbsp; <span id="div_aguarde_2_dados_profissional" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span> 
                                            </div>
                                            
                                        </div>
                                        <div class="portlet-body form">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="registro_profissional" class="form-control registro_profissional" id="registro_profissional" value="" />
                                                            <label for="registro_profissional">Registro</label>
                                                         </div>
                                                         &nbsp;
                                                          </div>
                                                          <div class="col-md-10">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="nome_profissional" class="form-control" id="nome_profissional" value="" autocomplete="off" />
                                                                <label for="nome_profissional">Nome do Profissional</label>
                                                                <span class="help-block">Começe a digitar o nome...</span>
                                                             </div>
                                                             &nbsp;
                                                          </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">&nbsp;</div>
                                                        <div class="col-md-10">
                                                         <input type="hidden" name="id_profissional_sel" id="id_profissional_sel" value=""/>
                                                            <div id="resultado_campo_gui_nome_profissional"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                        <?php
                                        if(in_array("43", $verifica_lista_permissoes_array_inc)){
                                            
                                       ?>
                                        <div class="portlet light" id="portlet_profissional">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> VÍNCULAR PARCEIRO</span>  &nbsp; <span id="div_aguarde_2_dados_profissional" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span> 
                                            </div>
                                            
                                        </div>
                                        <div class="portlet-body form">
                                                <div class="form-body">
                                                     <div class="row ">
                                                        <div class="col-md-4">
                                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                         <label class="control-label ">Parceiro</label>
                                                         <div class="">
                                                        <?php  
                                                            $id_parceiro_sessao = $usr_parceiro;
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
                                                    </div>
                                                </div>
                                        </div>
                                        </div>
                                        <?php
                                         }
                                        ?>
                                         <!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
                                            <div class="modal fade modal-scroll" id="ajax" role="basic" aria-hidden="true">
                                                <div class="modal-dialog">
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
                                                
                                            <div class="col-md-12" id="bt_submit_guia" style="display: none;">
                                                <div class="form-actions noborder">
                                                    <button type="submit" onclick="return bt_submit_action();" id="bt_add_submit" class="btn btn-lg blue">Agendar</button> &nbsp; <span class="div_aguarde" style="display: none;position: absolute;width: 100%;left: 0;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, registrando...</span>
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
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
       <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
       <script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
       <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimeout.js" type="text/javascript"></script>-->
       <!--<script src="assets/global/plugins/jquery-idle-timeout/jquery.idletimer.js" type="text/javascript"></script>-->
        <!--<script src="assets/global/plugins/offline/offline.min.js"></script>
        <script src="assets/global/plugins/offline/js/snake.js"></script>-->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
        
        <script src="assets/pages/scripts/form-validation-md.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
          <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/adicionar.js" type="text/javascript"></script>
        <!--<script src="assets/pages/scripts/ui-idletimeout.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
