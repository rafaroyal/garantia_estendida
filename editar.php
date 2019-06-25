<?php

require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
require_once('inc/permissoes.php');
$banco_painel = $link;
if (empty($_GET['item']) && empty($_GET['id']))
{
    header("Location: inicio.php");
}
else 
{
    $origem = $_SERVER['HTTP_REFERER'];
    if (empty($origem))
    {
        header("Location: inicio.php");
    }
    $item   = verifica($_GET['item']);
    $id     = verifica($_GET['id']);
    
    include ('inc/titulo.php');
    
    $tipo_get           = (empty($_GET['tipo'])) ? "" : $_GET['tipo'];
    $status_renovacao   = (empty($_GET['status_renovacao'])) ? "" : $_GET['status_renovacao'];
    
    if($tipo_get <> 'produto')
    {
        
        if($item == 'usuarios'){
            $id_bd = "MD5(id_usuario)";
        }
        
        $sql        = "SELECT * FROM $item WHERE $id_bd = '$id'";
        $query      = mysql_query($sql);

        if ($query && mysql_num_rows($query)>0)
        {
            $dados = mysql_fetch_array($query);
            extract($dados);
            
        }
        else
        {
            // header("Location: listar.php?item=$item&msg_status=id");
        }
    }
    
}
//$expire = time() + 30 * 86400;
$expire = $_COOKIE["usr_time"];
$pasta = base64_decode($_COOKIE["pasta"]);
setcookie("item",    base64_encode($item),    $expire, "/".$pasta);
$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
$id_usuario = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);
$nivel_status  = base64_decode($_COOKIE["nivel_status"]);
?>

<html lang="pt_br">

<head>
        <meta charset="utf-8" />
        <title><?php echo titulo;?> | Editar</title>
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
        
<style>
.html_info_pagamento, .html_forma_pagamento, #bt_calcular_pagamento{
    display: none;
}
</style>
       
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
                        <?php
                    	               include ('inc/msg_status.php');
                                       
                                       if($item == 'cliente'){
                                        ?>
                                         <form role="form" name="form" id="form_editar_cliente" action="editar_db.php" method="post" enctype="multipart/form-data">
                                       <?php 
                                       }else{
                                        ?>
                                         <form role="form" name="form" id="form_adicionar" action="editar_db.php" method="post" enctype="multipart/form-data">
                                       <?php
                                       }
                                       
                    	           ?>
                        
                       
                            
                                   
                                   <?php
                                   if ($item == 'parceiros' AND ($nivel_usuario == 'A' OR $nivel_usuario == 'P'))
                                    {
                                        
                                    ?> 
                                    <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Parceiro</span>
                                            </div>
                                            <div class="actions">
            <?php
                    
            if($nivel_usuario == 'A' AND in_array("54", $verifica_lista_permissoes_array_inc)){
            ?>
            <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Excluir Parceiro</h4>
                        </div>
                        <div class="modal-body"> Tem certeza que deseja excluir o parceiro? A alteração não poderá ser revertida! <br />
                        Todos as informações vínculadas ao parceiro serão perdidas!<br />
                        
                        </div>
                       <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="bt_cancelar_cliente" onclick="return cancelar_parceiro('<?php echo $id_parceiro; ?>');"  class="btn green" >Sim, confirmar!</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            
             <?php
                }else{
             ?>

            <div class="modal fade modal-scroll" id="excluir_p" tabindex="-1" role="excluir_p" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Excluir Parceiro</h4>
                        </div>
                        <div class="modal-body"> Tem certeza que deseja excluir o Parceiro? A alteração não poderá ser revertida! <br />
                        Você não tem permissão para excluir o parceiro, entrar em contato com o Administrador ou encaminhe um e-mail para <strong>contato@trailservicos.com.br</strong> com as seguintes informações abaixo: <br />
                        
                       <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Fechar</button>
                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
                </div>
            </div>
            <?php
                }
            ?>
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <?php
                    
                    if($nivel_usuario == 'A' AND in_array("54", $verifica_lista_permissoes_array_inc)){
                    ?>
                    
                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                    <i class="fa fa-times"></i> Excluir</a>
                    
                   <?php
                    }else{
                    ?>
                    <a data-toggle="modal" href="#excluir_p" class="btn btn-sm red btn-outline sbold">
                    <i class="fa fa-times"></i> Excluir</a>
                    
                    <?php
                    }
                    ?>
                </div>
            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            
                                            <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                                <div class="form-body">
                                                    <div class="row">
                                                        <?php 
                                                            if($tipo == 'PF')
                                                            {
                                                        ?>
                                                        <input type="hidden" name="tipopessoa" value="<?php echo $tipo; ?>"/>
                                                            <div class="col-md-6 ">
                                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>"/>
                                                                <label for="nome">Nome (<?php echo $tipo;?>)</label>
                                                                <span class="help-block">Como será chamado o parceiro...</span>
                                                             </div>
                                                             &nbsp;
                                                            </div>
                                                            <div class="col-md-6 ">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <select class="form-control edited" id="grupo" name="grupo">
                                                                        <option value=""></option>
                                                                        
                                                                        <?php
                                                                            $sql_grupo = "SELECT id_grupo_parceiro FROM parceiros_grupos
                                                                            WHERE id_parceiro = $id_parceiro";
                                                                            $query_grupo      = mysql_query($sql_grupo);     
                                                                            if (mysql_num_rows($query_grupo)>0)
                                                                            {
                                                                              $id_grupo = mysql_result($query_grupo, 0,0);  
                                                                            }
                                                                            
                                                                            
                                                                            $sql_grupo = "SELECT id_grupo_parceiro, nome'nome_grupo' FROM grupos_parceiros
                                                                                    WHERE del = 'N'";
                                                                            $query_grupo      = mysql_query($sql_grupo);
                                                                                     
                                                                            if (mysql_num_rows($query_grupo)>0)
                                                                            {
                                                                                while ($dados_grupo = mysql_fetch_array($query_grupo))
                                                                                {
                                                                                    extract($dados_grupo);  
                                                                                    $selecao = ($id_grupo == $id_grupo_parceiro) ? 'selected' : ''; 
                                                                                    
                                                                                    echo "<option value=\"$id_grupo_parceiro\" $selecao>$nome_grupo</option>";
                                                                                }
                                                                                
                                                                            }
                                                                            
                                                                        ?> 
                                                                    </select>
                                                                    <label for="grupo">Nome do grupo</label>
                                                                </div>
                                                             &nbsp;
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" disabled id="cpf" name="cpf" value="<?php echo $cpf; ?>"/>
                                                                    <input type="hidden" name="cpf" value="<?php echo $cpf; ?>"/>
                                                                    <label for="cpf">CPF</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="cep" name="cep" value="<?php echo $cep; ?>"/>
                                                                    <label for="cep">CEP</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="numero" name="numero" value="<?php echo $numero; ?>"/>
                                                                    <label for="numero">Número</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $bairro; ?>"/>
                                                                    <label for="bairro">Bairro</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="tel_res" name="tel_res" value="<?php echo $tel_res; ?>"/>
                                                                    <label for="tel_res">Telefone residencial</label>
                                                                    <span class="help-block">Ex.: 43 33333333</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="tel_cel" name="tel_cel" accept="<?php echo $tel_cel; ?>"/>
                                                                    <label for="tel_cel">Celular</label>
                                                                    <span class="help-block">Ex.: 43 99999999</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="ramo_atividade" name="ramo_atividade" value="<?php echo $ramo_atividade; ?>"/>
                                                                    <label for="ramo_atividade">Ramo Atividade</label>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <select class="form-control edited" id="modalidade" name="modalidade">
                                                                        <option value="" selected ></option>
                                                                        <option value="golden" <?php $modalidade_sel = ($modalidade == "Golden") ? 'selected=""' : ""; echo $modalidade_sel;?>>Golden</option>
                                                                        <option value="premium"  <?php $modalidade_sel = ($modalidade == "Premium") ? 'selected=""' : ""; echo $modalidade_sel;?>>Premium</option>
                                                                    </select>
                                                                    <label for="modalidade">Modalidade</label>
                                                                </div>
                                                            
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" disabled id="rg" name="rg" value="<?php echo $rg; ?>"/>
                                                                    <label for="rg">RG</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $endereco; ?>"/>
                                                                    <label for="endereco">Endereço</label>
                                                                    
                                                                </div>
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="complemento" name="complemento" accept="<?php echo $complemento; ?>"/>
                                                                    <label for="complemento">Complemento</label>
                                                                    <span class="help-block">Ex.: Sala 01</span>
                                                                </div>
                                                                
                                                                 <div class="form-group form-md-line-input form-md-floating-label">
        <?php
        
        
        
        $sql_user_pedido        = "SELECT ufe_sg FROM log_localidade
GROUP BY ufe_sg
        ";
        $query_user_pedido      = mysql_query($sql_user_pedido);
                        
        if(mysql_num_rows($query_user_pedido)>0)
        {
            echo "
            <div class=\"\"><select class=\"form-control\" data-size=\"8\" id=\"estado\" name=\"estado\" ><option value=\"\"></option>";
            
            while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
            {
                extract($dados_user_pedido);
                $html_select = '';
                if($estado == $ufe_sg){
                    $html_select = 'selected';
                }
                
                echo "<option value=\"$ufe_sg\" $html_select>$ufe_sg</option>";
            }
            
            echo "</select></div>";
        }
        
        ?>
        
    </div>
    <div class="form-group form-md-line-input form-md-floating-label">
    <input type="hidden" class="form-control" id="id_cidade" name="id_cidade" value="<?php echo $id_cidade; ?>"/>
        <div id="lista_cidades">
        <input type="text" readonly="" class="form-control" id="cidade" name="cidade" value="<?php echo $cidade; ?>"/>
            
        </div>                                                               
    </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="tel_com" name="tel_com" value="<?php echo $tel_com; ?>"/>
                                                                    <label for="tel_com">Telefone comercial</label>
                                                                    <span class="help-block">Ex.: 43 33333333</span>
                                                                </div>
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>"/>
                                                                    <label for="email">E-mail</label>
                                                                </div>
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <div class="fileinput fileinput-new col-md-6" data-provides="fileinput">
                                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 50px; height: 50px;"> <img src="assets/pages/img/logos/<?php echo $logo; ?>" /> </div>
                                                                        <div style="display: inline-block;">
                                                                            <span class="btn red btn-outline btn-file">
                                                                                <span class="fileinput-new"> LOGO PARCEIRO (500 x 161) px </span>
                                                                                <span class="fileinput-exists"> Selecione </span>
                                                                                <input type="file" name="logo1"/> </span>
                                                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
                                                                        </div>
                                                                    </div>
                                                                   <div class="fileinput fileinput-new col-md-6" data-provides="fileinput">
                                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 50px; height: 50px;"> <img src="assets/pages/img/logos/<?php echo $logo_proposta; ?>" /> </div>
                                                                        <div style="display: inline-block;">
                                                                            <span class="btn red btn-outline btn-file">
                                                                                <span class="fileinput-new"> LOGO PROPOSTA (500 x 161) px </span>
                                                                                <span class="fileinput-exists"> Selecione </span>
                                                                                <input type="file" name="logo2"/> </span>
                                                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

    
                                                        
                                                        <?php
                                                            }
                                                            else
                                                            {
                                                        ?>
                                                        <input type="hidden" name="tipopessoa" value="<?php echo $tipo; ?>"/>
                                                            <div class="col-md-3 ">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>"/>
                                                                <label for="nome">Nome (<?php echo $tipo;?>)</label>
                                                                <span class="help-block">Como será chamado o parceiro...</span>
                                                             </div>
                                                             &nbsp;
                                                            </div>
                                                            <div class="col-md-3 ">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <select class="form-control edited" id="grupo" name="grupo">
                                                                        <option value=""></option>
                                                                        
                                                                        <?php
                                                                            $sql_grupo = "SELECT id_grupo_parceiro FROM parceiros_grupos
                                                                            WHERE id_parceiro = $id_parceiro";
                                                                            $query_grupo      = mysql_query($sql_grupo);     
                                                                            if (mysql_num_rows($query_grupo)>0)
                                                                            {
                                                                              $id_grupo = mysql_result($query_grupo, 0,0);  
                                                                            }
                                                                            
                                                                            
                                                                            $sql_grupo = "SELECT id_grupo_parceiro, nome'nome_grupo' FROM grupos_parceiros
                                                                                    WHERE del = 'N'";
                                                                            $query_grupo      = mysql_query($sql_grupo);
                                                                                     
                                                                            if (mysql_num_rows($query_grupo)>0)
                                                                            {
                                                                                while ($dados_grupo = mysql_fetch_array($query_grupo))
                                                                                {
                                                                                    extract($dados_grupo);  
                                                                                    $selecao = ($id_grupo == $id_grupo_parceiro) ? 'selected' : ''; 
                                                                                    
                                                                                    echo "<option value=\"$id_grupo_parceiro\" $selecao>$nome_grupo</option>";
                                                                                }
                                                                                
                                                                            }
                                                                            
                                                                        ?> 
                                                                    </select>
                                                                    <label for="grupo">Nome do grupo</label>
                                                                </div>
                                                             &nbsp;
                                                            </div>
                                                            <div class="col-md-6 ">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" disabled id="cnpj" name="cnpj" value="<?php echo $cnpj; ?>"/>
                                                                    <input type="hidden" name="cnpj" value="<?php echo $cnpj; ?>"/>
                                                                    <label for="cnpj">CNPJ</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                             </div>
                                                             &nbsp;
                                                            </div>
                                                            <div class="col-md-6 ">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="razao" disabled class="form-control" id="razao" value="<?php echo $razao_social; ?>"/>
                                                                <label for="razao">Razão Social</label>
                                                             </div>
                                                             &nbsp;
                                                            </div>
                                                            <div class="col-md-6 ">
                                                             <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="fantasia" class="form-control" id="fantasia" value="<?php echo $nome_fantasia; ?>"/>
                                                                <label for="fantasia">Nome Fantasia</label>
                                                             </div>
                                                             &nbsp;
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="cep" name="cep" value="<?php echo $cep; ?>"/>
                                                                    <label for="cep">CEP</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="numero" name="numero" value="<?php echo $numero; ?>"/>
                                                                    <label for="numero">Número</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $bairro; ?>"/>
                                                                    <label for="bairro">Bairro</label>
                                                                    <span class="help-block">Apenas números...</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="ramo_atividade" name="ramo_atividade" value="<?php echo $ramo_atividade; ?>"/>
                                                                    <label for="ramo_atividade">Ramo Atividade</label>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <select class="form-control edited" id="modalidade" name="modalidade">
                                                                        <option value="" selected ></option>
                                                                        <option value="golden" <?php $modalidade_sel = ($modalidade == "Golden") ? 'selected=""' : ""; echo $modalidade_sel;?>>Golden</option>
                                                                        <option value="premium" <?php $modalidade_sel = ($modalidade == "Premium") ? 'selected=""' : ""; echo $modalidade_sel;?>>Premium</option>
                                                                    </select>
                                                                    <label for="modalidade">Modalidade</label>
                                                                </div>
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>"/>
                                                                    <label for="email">E-mail</label>
                                                                </div>
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <textarea class="form-control" id="obs_proposta" name="obs_proposta" rows="4" style="resize: none;" ><?php echo $obs_proposta; ?></textarea>
                                                                    <label for="obs_proposta">Observações na proposta</label>
                                                                    
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $endereco; ?>"/>
                                                                    <label for="endereco">Endereço</label>
                                                                    
                                                                </div>
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="complemento" name="complemento" value="<?php echo $complemento; ?>"/>
                                                                    <label for="complemento">Complemento</label>
                                                                    <span class="help-block">Ex.: Sala 01</span>
                                                                </div>
                                                                    <div class="form-group form-md-line-input form-md-floating-label">
        <?php
        
        
        
        $sql_user_pedido        = "SELECT ufe_sg FROM log_localidade
GROUP BY ufe_sg
        ";
        $query_user_pedido      = mysql_query($sql_user_pedido);
                        
        if(mysql_num_rows($query_user_pedido)>0)
        {
            echo "
            <div class=\"\"><select class=\"form-control\" data-size=\"8\" id=\"estado\" name=\"estado\" ><option value=\"\"></option>";
            
            while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
            {
                extract($dados_user_pedido);
                $html_select = '';
                if($estado == $ufe_sg){
                    $html_select = 'selected';
                }
                
                echo "<option value=\"$ufe_sg\" $html_select>$ufe_sg</option>";
            }
            
            echo "</select></div>";
        }
        
        ?>
        
    </div>
    <div class="form-group form-md-line-input form-md-floating-label">
    <input type="hidden" class="form-control" id="id_cidade" name="id_cidade" value="<?php echo $id_cidade; ?>"/>
        <div id="lista_cidades">

        <input type="text" readonly="" class="form-control" id="cidade" name="cidade" value="<?php echo $cidade; ?>"/>

        </div>                                                               
    </div>
    
    
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" class="form-control" id="tel_com" name="tel_com" value="<?php echo $tel_com; ?>" />
                                                                    <label for="tel_com">Telefone comercial</label>
                                                                    <span class="help-block">Ex.: 43 33333333</span>
                                                                </div>
                                                                
                                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <div class="fileinput fileinput-new col-md-6" data-provides="fileinput">
                                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 50px; height: 50px;"> <img src="assets/pages/img/logos/<?php echo $logo; ?>" /> </div>
                                                                        <div style="display: inline-block;">
                                                                            <span class="btn red btn-outline btn-file">
                                                                                <span class="fileinput-new"> LOGO PARCEIRO (500 x 161) px </span>
                                                                                <span class="fileinput-exists"> Selecione </span>
                                                                                <input type="file" name="logo1"/> </span>
                                                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="fileinput fileinput-new col-md-6" data-provides="fileinput">
                                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 50px; height: 50px;"> <img src="assets/pages/img/logos/<?php echo $logo_proposta; ?>" /> </div>
                                                                        <div style="display: inline-block;">
                                                                            <span class="btn red btn-outline btn-file">
                                                                                <span class="fileinput-new"> LOGO PROPOSTA (500 x 161) px </span>
                                                                                <span class="fileinput-exists"> Selecione </span>
                                                                                <input type="file" name="logo2"/> </span>
                                                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
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
                                    </div>
                                    <div class="portlet light " >
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase">Filiais</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form" id="box_filial">
                                            <div class="form-body" id="div_add_filial_add_campo">
                                            <?php
                                                $verifica = result("SELECT COUNT(*) FROM filiais WHERE id_parceiro = $id_parceiro");
                                                
                                                
                                            ?>
                                            
                                            <input type="hidden" class="form-control" id="contar_filiais" name="contar_filiais" value="<?php echo $verifica; ?>"/>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" class="form-control" id="cnpj_filial_add_campo" name="cnpj_filial_add_campo" value=""/>
                                                            <label for="cnpj_filial_add_campo">CNPJ/CPF</label>
                                                            <span class="help-block">* Campo obrigatório</span>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" class="form-control" id="nome_filial_add_campo" name="nome_filial_add_campo" value=""/>
                                                            <label for="nome_filial_add_campo">Nome</label>
                                                            <span class="help-block">* Campo obrigatório</span>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" class="form-control" id="cidade_filial_add_campo" name="cidade_filial_add_campo" value=""/>
                                                            <label for="cidade_filial_add_campo">Cidade</label>
                                                            <span class="help-block">* Campo obrigatório</span>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" class="form-control" id="estado_filial_add_campo" name="estado_filial_add_campo" value="" maxlength="2" style="text-transform: uppercase;" autocomplete="off" />
                                                            <label for="estado_filial_add_campo">UF</label>
                                                            <span class="help-block">* Campo obrigatório</span>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" class="form-control" id="fone_filial_add_campo" name="fone_filial_add_campo" value=""/>
                                                            <label for="fone_filial_add_campo">Telefone</label>
                                                            <span class="help-block">* Campo obrigatório</span>
                                                         </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <span class="input-group-btn btn-right">
                                                                    <button class="btn green-haze" type="button" onclick="return add_filial_parceiro(0);"><i class="fa fa-plus "></i></button>
                                                        </span>
                                                    </div>
                                                </div>                                            
                                           <?php
                                                $sql_filiais        = "SELECT  id_filial, nome'nome_filial', cnpj, cidade'cidade_filial', estado'estado_filial', tel_com, del'del_filial' FROM filiais
                                                            WHERE id_parceiro = $id_parceiro";
                                                $query_filiais      = mysql_query($sql_filiais);
                                                                
                                                if (mysql_num_rows($query_filiais)>0)
                                                {
                                          
                                                    $i = 1;
                                            while($dados_filiais = mysql_fetch_array($query_filiais))
                                                    {
                                                        extract($dados_filiais);
                                                    ?>    
                                                        <div class="form-body" id="div_add_filial_<?php echo $i; ?>">
    <div class="row">
        <div class="col-md-2">
             <div class="form-group form-md-floating-label">
                       
                        <label for="cnpj_filial"><?php echo $cnpj; ?></label>
             </div>
        </div>
        <div class="col-md-3">
             <div class="form-group form-md-floating-label">
                       
                        <label for="nome_filial"><strong>(<?php echo $id_filial; ?>)</strong> <?php echo $nome_filial; ?></label>
             </div>
        </div>
        <div class="col-md-3">
             <div class="form-group form-md-floating-label">
                       
                        <label for="cidade_filial"><?php echo $cidade_filial; ?></label>
             </div>
        </div>
        <div class="col-md-1">
             <div class="form-group form-md-floating-label">
                        
                        <label for="estado_filial"><?php echo $estado_filial; ?></label>
             </div>
        </div>
        <div class="col-md-2">
             <div class="form-group form-md-floating-label">
                        
                        <label for="fone_filial"><?php echo $tel_com; ?></label>
             </div>
        </div>
        <div class="col-md-1">
            <span class="input-group-btn btn-right">
            <a href="inc/ver_editar_filial_parceiro.php?id_filial=<?php echo $id_filial; ?>" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline green"><i class="fa fa-edit"></i></a>
            </span>
            <span class="input-group-btn btn-right">
                        <a data-toggle="modal" href="#excluir_filial_<?php echo $id_filial; ?>" class="btn btn-sm red btn-outline sbold">
                    <i class="fa fa-times"></i></a>
            </span>
        </div>
    </div>  
    <div class="modal fade modal-scroll" id="excluir_filial_<?php echo $id_filial; ?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Excluir Filial</h4>
            </div>
            <div class="modal-body"> Tem certeza que deseja excluir a filial? A alteração não poderá ser revertida! <br />
            Todas as informações vínculadas serão perdidas!<br />
            </div>
           <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                <button type="button" id="bt_cancelar_cliente" onclick="return remove_filial_parceiro('<?php echo $i; ?>','<?php echo $id_filial; ?>','<?php echo $id_parceiro; ?>');" data-dismiss="modal" class="btn green" >Sim, confirmar!</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>                                            
</div>
                                                
                                                    <?php  
                                                    $i++;
                                                    }
                                                   
                                                }  
                            
                            ?>
    
                                            </div>
                                            <div id="remove_filiais"></div>
                                        </div>
                                    </div>
                                    <div class="portlet light " id="box_pro">
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
                                                    <?php
                                                        $metodo_pagamento_array = explode("|", $metodo_pagamento);
                                                    ?>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="metodo_pagamento[]" value="MA" id="metodo_pagamento_1" class="md-check"  <?php if (in_array("MA", $metodo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="metodo_pagamento_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> MAQUINA DE CARTÃO </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="metodo_pagamento[]" value="ON" id="metodo_pagamento_2" class="md-check" <?php if (in_array("ON", $metodo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="metodo_pagamento_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> ONLINE </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="metodo_pagamento[]" value="BO" id="metodo_pagamento_3" class="md-check" <?php if (in_array("BO", $metodo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="metodo_pagamento_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> BOLETO </label>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <hr />
                                                        <h5>EMISSÃO BOLETO</h5>
                                                    <?php
                                                        $emissao_boleto_array = explode("|", $emissao_boleto);
                                                    ?>
                                                        <div class="md-checkbox-list">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="emissao_boleto[]" value="LOJA" id="emissao_boleto_1" class="md-check"  <?php if (in_array("LOJA", $emissao_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="emissao_boleto_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> LOJA </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="emissao_boleto[]" value="BANCO" id="emissao_boleto_2" class="md-check" <?php if (in_array("BANCO", $emissao_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="emissao_boleto_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> BANCO </label>
                                                            </div>
                                                            
                                                        </div>
                                                        <hr />
                                                        <h5>PLANO ADICIONAL</h5>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="valor_plano" class="form-control" id="valor_plano" value="<?php echo db_moeda2($valor_plano_adicional); ?>" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)"/>
                                                            <label for="valor_plano">Valor do Plano</label>
                                                            <span class="help-block">Somente números...</span>
                                                        </div>
                                                       <hr />
                                                        <h5>Mínimo de entrada</h5>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="porcentagem_entrada" class="form-control" id="porcentagem_entrada" value="<?php echo $porcentagem_entrada; ?>" maxlength="3"/>
                                                            <label for="valor_plano">Em %</label>
                                                            <span class="help-block">Somente números...</span>
                                                        </div>
                                                        <hr />
                                                        <h5>Desconto Renovação</h5>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="valor_desconto_renova" class="form-control" id="valor_desconto_renova" value="<?php echo db_moeda3($valor_desconto_renovacao); ?>" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)"/>
                                                            <label for="valor_desconto_renova">Valor do Desconto</label>
                                                            <span class="help-block">Somente números...</span>
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                    <div class="col-md-3">
                                                    <h5>TIPO DE PAGAMENTO</h5>
                                                    <?php
                                                        $tipo_pagamento_array = explode("|", $tipo_pagamento);
                                                    ?>
                                                        <div class="md-checkbox-list">
                                                            <!--<div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="avista" id="tipo_pagamento_1" class="md-check" <?php if (in_array("avista", $tipo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="tipo_pagamento_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> À vista </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="entrada_recorrente_cartao" id="tipo_pagamento_2" class="md-check" <?php if (in_array("entrada_recorrente_cartao", $tipo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="tipo_pagamento_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Entrada + Recorrente Crédito </label>
                                                            </div>-->
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="parcelado_cartao" id="tipo_pagamento_3" class="md-check" <?php if (in_array("parcelado_cartao", $tipo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="tipo_pagamento_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span>Parcelado Cartão</label>
                                                            </div>
                                                            <!--<div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="parcelado_cartao_recorrente" id="tipo_pagamento_4" class="md-check" <?php if (in_array("parcelado_cartao_recorrente", $tipo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="tipo_pagamento_4">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Parcelado Crédito Recorrente </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="recorrente_cartao" id="tipo_pagamento_5" class="md-check" <?php if (in_array("recorrente_cartao", $tipo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="tipo_pagamento_5">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Recorrente Crédito </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="fidelidade" id="tipo_pagamento_6" class="md-check" <?php if (in_array("fidelidade", $tipo_pagamento_array)) echo "checked='checked'"; ?>/>
                                                                <label for="tipo_pagamento_6">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Cartão Fidelidade </label>
                                                            </div>-->
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="tipo_pagamento[]" value="entrada_parcelado_boleto" id="tipo_pagamento_7" class="md-check" <?php if (in_array("entrada_parcelado_boleto", $tipo_pagamento_array)) echo "checked='checked'"; ?>/>
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
                                                                    <input type="radio" id="desconto1" name="desconto" class="md-radiobtn" value="S" <?php $sel_desconto = ($desconto == 'S') ? "checked='checked'" : ""; echo $sel_desconto; ?>/>
                                                                    <label for="desconto1">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> SIM! </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="desconto2" name="desconto" class="md-radiobtn" value="N" <?php $sel_desconto = ($desconto == 'N') ? "checked='checked'" : ""; echo $sel_desconto; ?>/>
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
                                                     <?php
                                                        $parcelas_cartao_array = explode("|", $parcelas_cartao);
                                                    ?>
                                                        <div class="md-checkbox-list">
                                                             <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="1" id="parcelas_cartao_1" class="md-check" <?php if (in_array("1", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 1 vez </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="2" id="parcelas_cartao_2" class="md-check" <?php if (in_array("2", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 2 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="3" id="parcelas_cartao_3" class="md-check" <?php if (in_array("3", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 3 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="4" id="parcelas_cartao_4" class="md-check" <?php if (in_array("4", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_4">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 4 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="5" id="parcelas_cartao_5" class="md-check" <?php if (in_array("5", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_5">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 5 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="6" id="parcelas_cartao_6" class="md-check" <?php if (in_array("6", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_6">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 6 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="7" id="parcelas_cartao_7" class="md-check" <?php if (in_array("7", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_7">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 7 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="8" id="parcelas_cartao_8" class="md-check" <?php if (in_array("8", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_8">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 8 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="9" id="parcelas_cartao_9" class="md-check" <?php if (in_array("9", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_9">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 9 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="10" id="parcelas_cartao_10" class="md-check" <?php if (in_array("10", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_10">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 10 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="11" id="parcelas_cartao_11" class="md-check" <?php if (in_array("11", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_11">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 11 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_cartao[]" value="12" id="parcelas_cartao_12" class="md-check" <?php if (in_array("12", $parcelas_cartao_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_cartao_12">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 12 vezes </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <h5>PARCELAS BOLETO</h5>
                                                     <?php
                                                        $parcelas_boleto_array = explode("|", $parcelas_boleto);
                                                    ?>
                                                        <div class="md-checkbox-list">
                                                             <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="1" id="parcelas_boleto_1" class="md-check" <?php if (in_array("1", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 1 vez </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="2" id="parcelas_boleto_2" class="md-check" <?php if (in_array("2", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_2">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 2 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="3" id="parcelas_boleto_3" class="md-check" <?php if (in_array("3", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_3">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 3 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="4" id="parcelas_boleto_4" class="md-check" <?php if (in_array("4", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_4">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 4 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="5" id="parcelas_boleto_5" class="md-check" <?php if (in_array("5", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_5">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 5 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="6" id="parcelas_boleto_6" class="md-check" <?php if (in_array("6", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_6">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 6 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="7" id="parcelas_boleto_7" class="md-check" <?php if (in_array("7", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_7">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 7 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="8" id="parcelas_boleto_8" class="md-check" <?php if (in_array("8", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_8">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 8 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="9" id="parcelas_boleto_9" class="md-check" <?php if (in_array("9", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_9">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 9 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="10" id="parcelas_boleto_10" class="md-check" <?php if (in_array("10", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_10">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 10 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="11" id="parcelas_boleto_11" class="md-check" <?php if (in_array("11", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
                                                                <label for="parcelas_boleto_11">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> 11 vezes </label>
                                                            </div>
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" name="parcelas_boleto[]" value="12" id="parcelas_boleto_12" class="md-check" <?php if (in_array("12", $parcelas_boleto_array)) echo "checked='checked'"; ?>/>
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
                                                                    <input type="radio" id="entrada1" name="entrada" class="md-radiobtn" value="S" <?php $sel_entrada = ($entrada == 'S') ? "checked='checked'" : ""; echo $sel_entrada; ?>/>
                                                                    <label for="entrada1">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> SIM! </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="entrada2" name="entrada" class="md-radiobtn" value="N" <?php $sel_entrada = ($entrada == 'N') ? "checked='checked'" : ""; echo $sel_entrada; ?>/>
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
                                                                    <input type="radio" id="valor_entrada_automatica1" name="valor_entrada_automatica" class="md-radiobtn" value="S" <?php $sel_valor_entrada = ($valor_entrada_automatica == 'S') ? "checked='checked'" : ""; echo $sel_valor_entrada; ?>/>
                                                                    <label for="valor_entrada_automatica1">
                                                                        <span class="inc"></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> SIM! </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="valor_entrada_automatica2" name="valor_entrada_automatica" class="md-radiobtn" value="N" <?php $sel_valor_entrada = ($valor_entrada_automatica == 'N') ? "checked='checked'" : ""; echo $sel_valor_entrada; ?>/>
                                                                    <label for="valor_entrada_automatica2">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> NÃO! </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h5>Valor em R$ da entrada automatica</h5>
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="valor_entrada_auto" class="form-control" id="valor_entrada_auto" value="<?php echo db_moeda3($valor_entrada_auto); ?>" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)"/>
                                                            <label for="valor_entrada_auto">Valor da Entrada</label>
                                                            <span class="help-block">Somente números...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-3">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <div class="input-group input-group-sm">
                                                                <div class="input-group-control">
                                                                
                                                                <?php
                                                                    /*$sql = "SELECT pro.id_produto, pro.nome'nome_produto' FROM produtos pro
                                                                    WHERE pro.ativo = 'S' AND pro.id_produto NOT IN (
                                                                        SELECT pro.id_produto FROM parceiros_servicos ps
                                                                    JOIN produtos pro ON ps.id_produto = pro.id_produto
                                                                    JOIN servicos se ON pro.id_servico = se.id_servico
                                                                    WHERE ps.id_parceiro = $id_parceiro AND pro.ativo = 'S' AND se.ativo = 'S')";*/
                                                                    $sql = "SELECT id_produto, nome'nome_produto' FROM produtos
                                                                                    WHERE ativo = 'S'";
                                                                    $query      = mysql_query($sql);
                                                                       echo "<select class=\"form-control edited\" id=\"selecionar_produtos\" name=\"selecionar_produtos\"><option selected ></option>";             
                                                                    if (mysql_num_rows($query)>0)
                                                                    {
                                                                        
                                                                        while ($dados = mysql_fetch_array($query))
                                                                        {
                                                                            extract($dados);  
                                                                            
                                                                            echo "<option value=\"$id_produto\">$nome_produto</option>";
                                                                        }
                                                                        
                                                                    }
                                                                    echo "</select>";
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
                                                    <div id="form_add_produto">
                                                    
                                                    <?php
                                                    $sql_par_produto        = "SELECT pro.id_produto, se.nome'nome_servico', pro.nome'nome_produto', ps.preco_custo, ps.preco_venda, ps.id_grupo_produto, ps.id_parceiro_servico FROM parceiros_servicos ps
                                                    JOIN produtos pro ON ps.id_produto = pro.id_produto
                                                    JOIN servicos se ON pro.id_servico = se.id_servico
                                                    WHERE ps.id_parceiro = $id_parceiro AND pro.ativo = 'S' AND se.ativo = 'S'";
                                                    $query_par_produto      = mysql_query($sql_par_produto);
                                                                                
                                                    if (mysql_num_rows($query_par_produto)>0)
                                                    {
                                                    
                                                        while ($dados = mysql_fetch_array($query_par_produto))
                                                        {
                                                            extract($dados); 
                                                            
                                                            echo "<div id=\"box_remove_produto_$id_parceiro_servico\">
                                                            <div class=\"col-md-3\" id=\"box_add_produto_$id_parceiro_servico\">
<input type=\"hidden\" name=\"add_produto[]\" id=\"add_produto\" value=\"$id_produto\"/>
<input type=\"hidden\" name=\"add_parceiro_servico[]\" id=\"add_parceiro_servico\" value=\"$id_parceiro_servico\"/>
<div class=\"portlet light\">
    <div class=\"portlet-title\">
        <div class=\"col-md-9\">
            <div class=\"caption font-green\">
                <h5 >$nome_produto</h5>
            </div>
        </div>
        <div class=\"col-md-3\">
            <div class=\"actions\">
                <div class=\"btn-group\">
                 <button class=\"btn btn-sm red btn-outline filter-cancel\" type=\"button\" onclick=\"return remove_produto_parceiro($id_parceiro_servico, '$nome_produto');\"><i class=\"fa fa-times\"></i></button>  
                </div>
            </div>
        </div>
    </div>
    <div class=\"portlet-body form\">
        <div class=\"form-body\">
            <div class=\"row\">
                <div class=\"col-md-12\">

                            <div class=\"form-group form-md-line-input form-md-floating-label\">
                                <input type=\"text\" name=\"preco_custo[]\" class=\"form-control\" id=\"preco_custo\" onkeydown=\"FormataMoeda(this,10,event)\" onkeypress=\"return maskKeyPress(event)\" value=\"$preco_custo\"/>
                                <label for=\"preco_custo\">Preço de custo</label>
                                <span class=\"help-block\">Somente números...</span>
                            </div>
                            <div class=\"form-group form-md-line-input form-md-floating-label\">
                                <input type=\"text\" name=\"preco_venda[]\" class=\"form-control\" id=\"preco_venda\" onkeydown=\"FormataMoeda(this,10,event)\" onkeypress=\"return maskKeyPress(event)\" value=\"$preco_venda\"/>
                                <label for=\"preco_venda\">Preço de Venda</label>
                                <span class=\"help-block\">Somente números...</span>
                            </div>";
                            
                    ?>        
                    
                        
                        <?php
                            /*$sql_grupo = "SELECT gp.nome'nome_do_grupo' FROM produtos_grupos pg
JOIN grupos_produtos gp ON pg.id_grupo_produto = gp.id_grupo_produto
WHERE pg.id_produto = $id_produto AND pg.id_grupo_produto = $id_grupo_produto";
                            $query_grupo      = mysql_query($sql_grupo);     
                            if (mysql_num_rows($query_grupo)>0)
                            {
                              $nome_do_grupo = mysql_result($query_grupo, 0,0);

                            }*/
                            

                            $sql_grupo = "SELECT gp.id_grupo_produto'id_grupo_produto_lista', gp.nome'nome_do_grupo' FROM produtos_grupos pg
JOIN grupos_produtos gp ON pg.id_grupo_produto = gp.id_grupo_produto
WHERE pg.id_produto = $id_produto";
                            $query_grupo      = mysql_query($sql_grupo); 
                            if (mysql_num_rows($query_grupo)>0)
                            {
                                echo "<div class=\"form-group form-md-line-input form-md-floating-label\">
                    <select class=\"form-control edited\" id=\"grupo_produto\" name=\"grupo_produto[]\">
                        <option value=\"\"></option>";
                                
                                while ($dados_grupo = mysql_fetch_array($query_grupo))
                                {
                                    extract($dados_grupo);  
                                    $selecao = ($id_grupo_produto == $id_grupo_produto_lista) ? 'selected' : ''; 
                                    
                                    echo "<option value=\"$id_grupo_produto_lista\" $selecao>$nome_do_grupo</option>";
                                }
                                
                                echo " </select>
                                    <label for=\"grupo\">Nome do grupo</label>
                                </div>";
                            }
                            else
                            {
                            ?>
                            <input type="hidden" name="grupo_produto[]" id="grupo_produto" value="0"/>
                            <?php
                            }
                            
                            
                        ?> 
                        
                <?php       
                echo "</div>
            </div>
        </div>  
    </div>
</div>
</div>
</div>";
                                         
                                                        }
                                                        
                                                    }
                                                    
                                                    ?>    

                                                    
                                                    </div>
                                                    <div id="remove_produtos"></div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>                     
                                            </div>
                                        </div>
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
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar Parceiro</button>
                                                    </div>
                                                </div>
                                            
                                    
                                    <?php
                                    }elseif ($item == 'grupos_parceiros' AND $nivel_usuario == 'A'){
                                    ?>
                                   <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Grupo</span>
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
                                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="col-md-12 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>"/>
                                                            <label for="nome">Nome</label>
                                                            <span class="help-block">Como será chamado o grupo...</span>
                                                         </div>
                                                         &nbsp;
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
                                   
                                        
                                    <?php
                                    }elseif ($item == 'cliente'){
                                        
                                        if($tipo_get == 'produto'){
                                            
                                            $id_produto                 = (empty($_GET['id_produto'])) ? "" : verifica($_GET['id_produto']);  
                                            $id_grupo_produto           = (empty($_GET['id_grupo_produto'])) ? "" : verifica($_GET['id_grupo_produto']);  
                                            $ativar_plano_cancelado     = (empty($_GET['ativar_plano_cancelado'])) ? "" : verifica($_GET['ativar_plano_cancelado']);  
                                            
                                            $id_produto_get     = $id_produto;  
                                            $id_parceiro = $id_parceiro_s;
                                            // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
                                            $sql_base   = "SELECT bpro.id_base_produto, bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                                        JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                                        WHERE pro.id_produto = $id_produto";
                                            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                                        
                                            if (mysql_num_rows($query_base)>0)
                                            {
                                                $id_base_produto        = mysql_result($query_base, 0, 'id_base_produto');
                                                $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                                                $banco_user             = mysql_result($query_base, 0, 'banco_user');
                                                $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                                                $banco_host             = mysql_result($query_base, 0, 'banco_host');
                                                $slug                   = mysql_result($query_base, 0, 'slug');
                                                $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                                                
                                                $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                                            }
                                            $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
                                            
                                            ?>
                                             <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo_get; ?>" />
                                             <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id; ?>" />
                                             <input type="hidden" name="item" id="item" value="<?php echo $item; ?>" />
                                             <?php 
                                             if(isset($_GET['alterar_pagamento']) AND !empty($_GET['alterar_pagamento']) AND $_GET['alterar_pagamento'] == 'true'){
                                             ?>
                                             <input type="hidden" name="alterar_pagamento" id="alterar_pagamento" value="<?php echo $_GET['alterar_pagamento']; ?>" />
                                             <?php
                                             }
                                             ?>
                                             
                                             
                                             
                                            <?php
                                            if($nivel_usuario == 'A'){
                                                $sql_grupo_slug = "SELECT bpro.slug'slug_produtos' FROM grupos_produtos gpro
                                                JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
                                                JOIN produtos pro ON prog.id_produto = pro.id_produto
                                                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                                JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                                                WHERE gpro.del = 'N' AND gpro.id_grupo_produto = $id_grupo_produto
                                                GROUP BY bpro.slug ORDER BY bpro.id_base_produto";
                                            }else{
                                                $sql_grupo_slug = "SELECT bpro.slug'slug_produtos' FROM grupos_produtos gpro
                                                JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
                                                JOIN produtos pro ON prog.id_produto = pro.id_produto
                                                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                                JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                                                WHERE gpro.del = 'N' AND pser.id_parceiro = $id_parceiro AND gpro.id_grupo_produto = $id_grupo_produto
                                                GROUP BY bpro.slug ORDER BY bpro.id_base_produto";
                                            }
                                            

                                            $query_grupo_slug      = mysql_query($sql_grupo_slug, $banco_painel);
                                                           
                                            if (mysql_num_rows($query_grupo_slug)>1)
                                            {
                                                
                                                while($dados_grupo_slug = mysql_fetch_array($query_grupo_slug))
                                                {
                                                    
                                                    extract($dados_grupo_slug);
                                                    
                                                    if($novo_slug == '')
                                                    {
                                                        $novo_slug = $slug_produtos;
                                                    }
                                                    else{
                                                        $slug_produtos = $novo_slug."|".$slug_produtos;
                                                    }
                                                }
                                             }else{
                                                $slug_produtos = mysql_result($query_grupo_slug, 0, 'slug_produtos');
                                             }   
                                            ?>
                                            <input type="hidden" name="slug_produto" id="slug_produto" value="<?php echo $slug_produtos; ?>" />
            
                                            <?php    
                                                                                  
                                            if($slug == 'europ')
                                            {
                                                
                                                $sql        = "SELECT cl.*, ve.id_ordem_pedido, ve.tipo_pagamento, ve.desconto, ve.valor_entrada, ve.valor_dependente, ve.valor_parcela, ve.valor_parcela_total, ve.valor_total, ve.parcelas, ve.prazo, ve.data_venda, ve.metodo_pagamento'metodo_pagamento_atual_cliente', ve.tipo_desconto FROM clientes cl
                                                            JOIN vendas ve ON cl.id_cliente = ve.id_cliente
                                                            WHERE cl.id_cliente = $id";
                                                $query      = mysql_query($sql, $banco_produto);
                                                                
                                                if (mysql_num_rows($query)>0)
                                                {
                                                    $dados = mysql_fetch_array($query);
                                                    extract($dados); 
                                                    
                                                    $tipo_pagamento_atual_cliente       = $tipo_pagamento;
                                                    $parcela_atual_cliente              = $parcelas;
                                                    $valor_parcela_total_atual_cliente  = $valor_parcela_total;  
                                                    $sql_dependentes = "AND tipo_movimento <> 'EX'";
                                                    $sql_adicionais_se = "AND cl.tipo_movimento IN ('IN', 'AL')";
                                                    if($ativar_plano_cancelado == 'ok'){
                                                        $tipo_movimento_cliente_get = $tipo_movimento;
                                                        $data_cancelamento_cliente_get = $data_cancelamento;
                                                        $sql_dependentes = "AND data_cancelamento = '$data_cancelamento_cliente_get'";
                                                        $sql_adicionais_se = "AND cl.data_cancelamento = '$data_cancelamento_cliente_get'";
                                                    }
                                                    
                                                }
                                                $disativar_campo = '';
                                                if($status <> 3)
                                                {
                                                    // cliente penente
                                                    $disativar_campo = 'disabled=""';
                                                }

                                                ?>

    <div class="portlet light " id="dados_cliente">
        <div class="portlet-title">
            <div class="caption font-green">
                <i class="fa fa-plus font-green"></i> 
                <span class="caption-subject bold uppercase"> Dados pessoais - <?php echo $chave; ?>
                <?php
                $plano_adicional         = (empty($_GET['plano_adicional'])) ? "" : verifica($_GET['plano_adicional']);  
                if($plano_adicional == 'sim'){
                    echo '<small>PLANO ADICIONAL</small>';
                }
                ?>
                </span>
            </div>
            
            
            <?php
            
            if($plano_adicional == 'nao'){
                $slug_get = str_replace("|", "-", $slug_produtos);
            ?>
            <div class="actions">
            <?php
                    
            if($nivel_usuario == 'A' AND in_array("7", $verifica_lista_permissoes_array_inc)){
            ?>
            <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Excluir Cliente</h4>
                        </div>
                        <div class="modal-body"> Tem certeza que deseja excluir o cliente? A alteração não poderá ser revertida! <br />
                        Todos as informações vínculadas ao cliente serão perdidas!<br />
                        <strong>Tem certeza que deseja confirmar? Selecione o motivo do cancelamento:</strong>
                        <div class="row">
                        <div class="col-md-12">
                        <div class="form-group form-md-radios">
                        <div class="md-radio-list">
                            <div class="md-radio">
                                <input type="radio" id="checkbox1_1" name="tipo_cancelamento" class="md-radiobtn" value="Financeiro" checked="" />
                                <label for="checkbox1_1">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>1-Financeiro</label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox1_2" name="tipo_cancelamento" class="md-radiobtn" value="Nao_tem_Interesse" />
                                <label for="checkbox1_2">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>2-Nao tem Interesse</label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox1_3" name="tipo_cancelamento" class="md-radiobtn" value="Falecimento"/>
                                <label for="checkbox1_3">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>3-Falecimento</label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox1_4" name="tipo_cancelamento" class="md-radiobtn" value="Nao_usa_o_plano"/>
                                <label for="checkbox1_4">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span>4-Nao usa o plano</label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="checkbox1_5" name="tipo_cancelamento" class="md-radiobtn" value="Outros"/>
                                <label for="checkbox1_5">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>5-Outros</label>
                            </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                       <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="bt_cancelar_cliente" onclick="return cancelar_cliente('<?php echo $slug_get; ?>','<?php echo $id_grupo_produto; ?>','<?php echo $id; ?>','<?php echo $id_ordem_pedido; ?>','<?php echo $chave; ?>');"  class="btn green" >Sim, confirmar!</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            
             <?php
                }else{
             ?>
            
            
            <div class="modal fade modal-scroll" id="excluir_p" tabindex="-1" role="excluir_p" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Excluir Cliente</h4>
                        </div>
                        <div class="modal-body"> Tem certeza que deseja excluir o cliente? A alteração não poderá ser revertida! <br />
                        Encaminhe um e-mail para <strong>contato@trailservicos.com.br</strong> com as seguintes informações abaixo: <br />
                        ID_PEDIDO: <?php echo $id_ordem_pedido; ?><br />
                        ID_CLIENTE: <?php echo $id; ?><br />
                        SLUG_PRODUTO: <?php echo $slug_get;?><br />
                        ID_GRUPO PRODUTO: <?php echo $id_grupo_produto; ?><br />
                        ID_PARCEIRO: <?php echo $id_parceiro;?> <br />
                        ID_USUÁRIO: <?php echo $id_usuario; ?></div>
                       <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Fechar</button>
                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <?php
                }
            ?>
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <?php
                    
                    if($nivel_usuario == 'A' AND in_array("7", $verifica_lista_permissoes_array_inc)){
                    ?>
                    
                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                    <i class="fa fa-times"></i> Excluir</a>
                    
                   <?php
                    }else{
                    ?>
                    <a data-toggle="modal" href="#excluir_p" class="btn btn-sm red btn-outline sbold">
                    <i class="fa fa-times"></i> Excluir</a>
                    
                    <?php
                    }
                    ?>
                </div>
            </div>
            
            <?php
            }
            ?>
            <input type="hidden" name="chave_cliente" id="chave_cliente" value="<?php echo $chave; ?>" />
            <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro; ?>" />
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>" />
            
            <?php
            
            if($msg_status == 'renovar_venda'){
                
            ?>
            <input type="hidden" name="hist_renova_id_ordem_pedido" id="hist_renova_id_ordem_pedido" value="<?php echo $id_ordem_pedido; ?>" />
            <input type="hidden" name="hist_renova_tipo_pagamento" id="hist_renova_tipo_pagamento" value="<?php echo $tipo_pagamento; ?>" />
            <input type="hidden" name="hist_renova_desconto" id="hist_renova_desconto" value="<?php echo $desconto; ?>" />
            <input type="hidden" name="hist_renova_valor_entrada" id="hist_renova_valor_entrada" value="<?php echo $valor_entrada; ?>" />
            <input type="hidden" name="hist_renova_valor_adicional" id="hist_renova_valor_adicional" value="<?php echo $valor_dependente; ?>" />
            <input type="hidden" name="hist_renova_valor_parcela" id="hist_renova_valor_parcela" value="<?php echo $valor_parcela; ?>" />
            <input type="hidden" name="hist_renova_valor_parcela_total" id="hist_renova_valor_parcela_total" value="<?php echo $valor_parcela_total; ?>" />
            <input type="hidden" name="hist_renova_valor_total" id="hist_renova_valor_total" value="<?php echo $valor_total; ?>" />
            <input type="hidden" name="hist_renova_parcelas" id="hist_renova_parcelas" value="<?php echo $parcelas; ?>" />
            <input type="hidden" name="hist_renova_prazo" id="hist_renova_prazo" value="<?php echo $prazo; ?>" />
            <input type="hidden" name="hist_renova_data_venda" id="hist_renova_data_venda" value="<?php echo $data_venda; ?>" />
            <?php
            }
            ?>
            
            <!--<div class="actions">
                <div class="btn-group">
                    <a class="btn default" href="">
                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                    
                </div>
            </div>-->
        </div>
        <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                      <div class="col-md-12 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>" style="text-transform: uppercase;" maxlength="40" <?php //echo $disativar_campo; ?> />
                            <?php
                            if($plano_adicional == 'nao'){
                            ?>
                            <!--<input type="hidden" name="nome" value="<?php echo $nome; ?>"/>-->
                            <?php
                            }
                            ?>
                            <label for="nome">Nome Completo</label>
                            <span class="help-block">Digite o nome completo do cliente...</span>
                         </div>
                         &nbsp;
                      </div>
                    </div>
                    <div class="row">
                    <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="cpf" class="form-control" id="cpf" value="<?php echo $cpf; ?>" onkeyup="return verificarCPF(this.value)" <?php //echo $disativar_campo; ?> />
                            <?php
                            if($plano_adicional == 'nao'){
                            ?>
                            <!--<input type="hidden" name="cpf" value="<?php echo $cpf; ?>" />-->
                            <?php
                            }
                            ?>
                            <label for="cpf">CPF</label>
                            <span class="help-block">Somente números..</span>
                         </div>
                         &nbsp;
                        </div>
                      <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="data_nasc" class="form-control" id="data_nasc" value="<?php echo converte_data($data_nascimento); ?>" 
                            <?php
                            if(in_array("49", $verifica_lista_permissoes_array_inc)){
                            }else{
                             $verifica_plano_adicional = ($plano_adicional == 'nao') ? 'onkeyup="return verificarIDADE(this.value)"' : 'onkeyup="return verificarIDADE(this.value)"'; echo $verifica_plano_adicional; 
                            }
                            ?>   
                            />
                            <label for="data_nasc">Data de Nascimento</label>
                            <span class="help-block">Somente números...</span>
                         </div>
                         &nbsp;
                        </div>
                        <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <select class="form-control" id="sexo" name="sexo">
                                <option value=""></option>
                                <option value="H" <?php $sexo_sel = ($sexo == 'H') ? 'selected=""' : ''; echo $sexo_sel; ?>>Masculino</option>
                                <option value="M" <?php $sexo_sel = ($sexo == 'M') ? 'selected=""' : ''; echo $sexo_sel; ?>>Feminino</option>
                            </select>
                            <label for="sexo">Sexo</label>
                        </div>
                         &nbsp;
                        </div>
                        <div class="col-md-3 ">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select class="form-control" id="estado_civil" name="estado_civil">
                                <option value=""  ></option>
                                <option value="S" <?php $estado_civil_sel = ($estado_civil == 'S') ? 'selected=""' : ''; echo $estado_civil_sel; ?>>Solteiro(a)</option>
                                <option value="C" <?php $estado_civil_sel = ($estado_civil == 'C') ? 'selected=""' : ''; echo $estado_civil_sel; ?>>Casado(a)</option>
                                <option value="D" <?php $estado_civil_sel = ($estado_civil == 'D') ? 'selected=""' : ''; echo $estado_civil_sel; ?>>Divorciado(a)</option>
                                <option value="V" <?php $estado_civil_sel = ($estado_civil == 'V') ? 'selected=""' : ''; echo $estado_civil_sel; ?>>Viuvo(a)</option>
                            </select>
                            <label for="estado_civil">Estado Civil</label>
                        </div>
                         &nbsp;
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="telefone" class="form-control" id="telefone" value="<?php echo $telefone; ?>" />
                            <label for="telefone">Telefone (fixo)</label>
                            <span class="help-block">Somente números...</span>
                         </div>
                         &nbsp;
                        </div>
                        <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="celular" class="form-control" id="celular" value="<?php echo $celular; ?>"/>
                            <label for="celular">Celular</label>
                            <span class="help-block">Somente números...</span>
                         </div>
                         &nbsp;
                       </div>
                       <div class="col-md-6">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="email" class="form-control" id="email" value="<?php echo $email; ?>"/>
                            <label for="email">E-mail</label>
                            <span class="help-block">Digite o e-mail corretamente...</span>
                         </div>
                         &nbsp;
                       </div>
                    </div>
                     <div class="row">
                      <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="cep" class="form-control" id="cep" value="<?php echo $cep; ?>" style="text-transform: uppercase;"/>
                            <label for="cep">CEP</label>
                            <span class="help-block">Somente números...</span>
                         </div>
                         &nbsp;
                        </div>
                        <div class="col-md-7 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="endereco" class="form-control" id="endereco" value="<?php echo $endereco; ?>" style="text-transform: uppercase;" maxlength="40"/>
                            <label for="endereco">Endereço</label>
                            <span class="help-block">Digite o endereço completo...</span>
                         </div>
                         &nbsp;
                        </div>
                        <div class="col-md-2 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="numero" class="form-control" id="numero" value="<?php echo $numero; ?>" maxlength="4"/>
                            <label for="numero">Número</label>
                            <span class="help-block">Somente números...</span>
                         </div>
                         &nbsp;
                        </div>
                    </div>
                     <div class="row">
                      <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="complemento" class="form-control" id="complemento" value="<?php echo $complemento; ?>" style="text-transform: uppercase;" maxlength="10"/>
                            <label for="complemento">Complemento</label>
                            <span class="help-block">Ex.: ap. 526</span>
                         </div>
                         &nbsp;
                        </div>
                        <div class="col-md-3 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="bairro" class="form-control" id="bairro" value="<?php echo $bairro; ?>" style="text-transform: uppercase;" maxlength="20"/>
                            <label for="bairro">Bairro</label>
                            
                         </div>
                         &nbsp;
                        </div>
                        <div class="col-md-4 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="cidade" class="form-control" id="cidade" value="<?php echo $cidade; ?>" style="text-transform: uppercase;" maxlength="30"/>
                            <label for="cidade">Cidade</label>
                            
                         </div>
                         &nbsp;
                        </div>
                        <div class="col-md-2 ">
                         <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="estado" class="form-control" id="estado" value="<?php echo $estado; ?>" style="text-transform: uppercase;"/>
                            <label for="estado">Estado</label>
                            <span class="help-block">Campo com 2 dígitos</span>
                         </div>
                         &nbsp;
                        </div>
                        </div>
                        <div id="div_lista_dependente_ativo"></div>
                        <input type="hidden" id="verifica_id_ordem_pedido_dependentes" value="<?php echo $id_ordem_pedido; ?>" />
                        <input type="hidden" name="verifica_id_ordem_pedido_principal" id="verifica_id_ordem_pedido_principal" value="<?php echo $id_ordem_pedido; ?>" />
                        <?php
                        
                    $sql_adicional        = "SELECT dep_cli.id_dependente'id_cliente_dependente_ativo', dep_cli.tipo_dependente, dep_cli.nome'nome_dependente', dep_cli.data_nascimento'data_nasc_dependente', dep_cli.id_cliente'id_cliente_dep_cliente' FROM dependentes_clientes dep_cli
                    JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                    WHERE dep_cli.id_cliente = $id AND dep_cli.status = 0
                    GROUP BY dep_cli.id_dependente";
                    $query_adicional      = mysql_query($sql_adicional, $banco_produto);
                                    
                    if (mysql_num_rows($query_adicional)>0)
                    {
                        
                        while($dados_adicional = mysql_fetch_array($query_adicional)){
                            extract($dados_adicional); 
                        ?>
                        <div class="row" id="box_lista_depen_ativo_<?php echo $id_cliente_dependente_ativo; ?>">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Cliente:</strong></label>
                                    <div class="col-md-12">
                                        <p class="form-control-static"> <?php 
                                        
                                        $sql_cliente_dep_cliente        = "SELECT nome FROM clientes
                                        WHERE id_cliente = $id_cliente_dep_cliente $sql_dependentes";
                                        $query_cliente_dep_cliente      = mysql_query($sql_cliente_dep_cliente, $banco_produto);
                                        $nome_cliente_vinculo_dep = 'sem cliente';
                                        if (mysql_num_rows($query_cliente_dep_cliente)>0)
                                        {
                                            $nome_cliente_vinculo_dep = mysql_result($query_cliente_dep_cliente, 0, 'nome');
                                        }
                                        echo $nome_cliente_vinculo_dep;
                                         ?> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Tipo:</strong></label>
                                    <div class="col-md-12">
                                        <p class="form-control-static"> <?php echo $tipo_dependente; ?> </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Nome:</strong></label>
                                    <div class="col-md-12">
                                        <p class="form-control-static"> <?php echo $nome_dependente; ?> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Nasc.:</strong></label>
                                    <div class="col-md-12">
                                        <p class="form-control-static"> <?php echo converte_data($data_nasc_dependente); ?> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <a class="btn btn-sm red btn-outline filter-cancel" href="javascript:" onclick="return acao_remove_dependente_ativo('<?php echo $id_cliente_dependente_ativo; ?>');" style="    margin-top: 15px;"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                            
                            <!--/span-->
                        </div>
                        
                        <?php
                        }
                    
                    }else{
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Sem dependente</strong></label>
                                    
                                </div>
                            </div>
                        </div>
                        <?php
                    }  
                        ?>
                        <div style="background: #f7f7f7;padding: 4px;">
                        <input type="hidden" name="principal_contar_dependente_atual" id="principal_contar_dependente_atual" value="0"/>
                        <input type="hidden" name="principal_contar_dependente" id="principal_contar_dependente" value="0"/>
                        <div id="inserir_mais_dependentes"></div>
                        <div class="row"><div class="col-md-6">
                             <div class="form-actions noborder">
                               <a href="javascript:" class="btn green" id="principal_bt_add_dependente">Adicionar <i class="fa fa-plus"></i> Dependente </a> <span id="div_aguarde_sel_plano_add_depe" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, carregando dados...</span>
                             </div>
                             &nbsp;
                            </div></div>
                        </div>
                        <hr />
                        <div class="row">
                         <div class="col-md-12" >
                            <div id="form_add_depenente"></div>
                         </div>
                        </div>
                        <?php
                            $valor_plano_adicional_c = 0;
                            if($msg_status != 'ativar_venda'){
                                
                            
                            $sql_dep = "SELECT cl.id_cliente'id_cliente_dependente', cl.nome'nome_dependente', cl.chave'chave_dependente', cl.data_nascimento'data_nascimento_dependente', cl.`status`'status_dependente', v.valor_dependente FROM clientes ci
                            JOIN clientes cl ON ci.id_cliente = cl.id_cliente_principal
                            JOIN vendas v ON cl.id_cliente = v.id_cliente
                            WHERE ci.chave = '$chave' $sql_adicionais_se
                            GROUP BY cl.chave";
                            $query_dep      = mysql_query($sql_dep, $banco_produto);
                            
                            if (mysql_num_rows($query_dep)>0)
                            {
                                echo "<hr/><div class=\"row\" id=\"div_lista_dependente\"><h5> PLANO ADICIONAL</h5>";
                                $contar_dep = 0;
                                
                                $sql_valor_dep        = "SELECT valor_plano_adicional FROM parceiros
                                                            WHERE id_parceiro = $id_parceiro";
                                                      
                                $query_valor_dep      = mysql_query($sql_valor_dep, $banco_painel);

                                if(mysql_num_rows($query_valor_dep)>0)
                                {
                                    $valor_plano_adicional = mysql_result($query_valor_dep, 0, 'valor_plano_adicional');
                                    $valor_plano_adicional_c = str_replace(',', '.', $valor_plano_adicional);
                                }
                                while($dados = mysql_fetch_array($query_dep))
                                {
                                    extract($dados);
                                    $get_mgs_status = 'editar_cliente';
                                    if($nome_dependente == ''){
                                        $nome_dependente = 'Sem nome...';
                                    }
                                    
                                    if($status_dependente == 3){
                                        $get_mgs_status = 'finalizar_cadastro';
                                    }
                                ?>
                                 
                                <div class="portlet box green" id="box_lista_depen_<?php echo $id_cliente_dependente; ?>">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-plus"></i>
                                            <span class="caption-subject bold uppercase"> <?php echo $nome_dependente; ?> </span>
                                            <span class="caption-helper" style="color: #ffffff;">certificado:<? echo $chave_dependente; ?> &nbsp; <?php if($ativar_plano_cancelado <> 'ok'){?><a class="btn btn-sm white btn-outline filter-cancel" href="editar.php?item=cliente&tipo=produto&id_base=3&slug=europ&id_produto=<?php echo $id_produto_get;?>&id=<?php echo $id_cliente_dependente; ?>&id_grupo_produto=<?php echo $id_grupo_produto; ?>&plano_adicional=sim&msg_status=<?php echo $get_mgs_status; ?>"><i class="fa fa-edit"></i> EDITAR</a> <?php } ?> </span>
                                        </div>
                                        <div class="actions">
                                            <div class="btn-group">
                                                <?php if($ativar_plano_cancelado <> 'ok'){?><a class="btn btn-sm white btn-outline filter-cancel" href="javascript:" onclick="return acao_remove_dependente('<?php echo $id_cliente_dependente; ?>','<?php echo $valor_plano_adicional_c;?>');"><i class="fa fa-times"></i> Remover</a>  <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php  
                                $contar_dep++;  
                                }
                                echo "<div class=\"div_informa_user_excluir_dep\" style=\"display: none;\"><strong>Será confirmada a exclusão do dependente, apenas quando finalizar o cadastro abaixo! E para desfazer a ação, atualize a página.</strong></div></div>";
                            }
                            }
                        ?>
                        
                        <?php
                        
                            $sql_dep = "SELECT id_cliente'id_cliente_dependente', nome'nome_dependente', chave'chave_dependente', data_nascimento'data_nascimento_dependente', `status`'status_dependente' FROM clientes
                            
                            WHERE id_cliente = '$id_cliente_principal'
                            GROUP BY chave";
                            $query_dep      = mysql_query($sql_dep, $banco_produto);
                            
                            if (mysql_num_rows($query_dep)>0)
                            {
                                echo "<hr/><div class=\"row\"><h5> CLIENTE PRINCIPAL</h5>";
                                
                                while($dados = mysql_fetch_array($query_dep))
                                {
                                    extract($dados);
                                    
                                    if($nome_dependente == ''){
                                        $nome_dependente = 'Sem nome...';
                                    }
                                ?>
                                <div class="portlet box green">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-plus"></i>
                                            <span class="caption-subject bold uppercase"> <?php echo $nome_dependente; ?> </span>
                                            <span class="caption-helper" style="color: #ffffff;">certificado:<? echo $chave; ?> &nbsp; <!--<a class="btn btn-sm white btn-outline filter-cancel" href="editar.php?item=cliente&tipo=produto&id_base=3&slug=europ&id_produto=<?php echo $id_produto_get;?>&id=<?php echo $id_cliente_dependente; ?>&id_grupo_produto=<?php echo $id_grupo_produto; ?>&plano_adicional=sim&msg_status=finalizar_cadastro" data="<?php echo $id_cliente_dependente; ?>" id="bt_remove_dependente"><i class="fa fa-edit"></i> EDITAR</a>--> </span>
                                        </div>
                                        <!--<div class="actions">
                                            <div class="btn-group">
                                                <a class="btn btn-sm white btn-outline filter-cancel" href="javascript:" data="<?php echo $id_cliente_dependente; ?>" id="bt_remove_dependente"><i class="fa fa-times"></i> Remover</a>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                                <?php 
                                   
                                }
                                echo "</div>";
                            }
                        ?>
                        <hr />
                        <div class="row">
     <div class="col-md-12" >
        <div class="form-actions noborder">
            <input type="hidden" name="contar_dependente_atual" id="contar_dependente_atual" value="0"/>
            <input type="hidden" name="contar_dependente" id="contar_dependente" value="0"/>
            
            <?php
            if($msg_status == 'finalizar_venda' OR $msg_status == 'renovar_venda'){
                    $sql_valor_dep        = "SELECT valor_plano_adicional FROM parceiros
        WHERE id_parceiro = $id_parceiro";
                    $query_valor_dep      = mysql_query($sql_valor_dep, $banco_painel);
                                    
                    if (mysql_num_rows($query_valor_dep)>0)
                    {
                        $valor_plano_adicional = mysql_result($query_valor_dep, 0, 'valor_plano_adicional');
                        $valor_plano_adicional_c = str_replace(',', '.', $valor_plano_adicional);
                    }
                
                if($valor_plano_adicional > 0 AND !empty($valor_plano_adicional)){
    
                    ?>
                    <input type="hidden" name="valor_dependente" id="valor_dependente" value="<?php echo $valor_plano_adicional_c;?>"/>
                    
                    <?php              
                    if($ativar_plano_cancelado != 'ok'){
                    ?>
                    <a href="javascript:" class="btn green" id="bt_add_dependente"><i class="fa fa-plus"></i> Plano Adicional</a> <span id="div_aguarde_sel_plano_add" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, carregando dados...</span> ** Cada Plano adicional para <strong>Assistência Total</strong>, valor de <?php echo db_moeda($valor_plano_adicional);?> por parcela.
                    <?php
                    }
                }
            }
            ?>
        </div>
     </div>
    </div>
    <hr />
                        <div class="row" style="display: none;">
                        <div class="col-md-12" style="margin-bottom: 20px;" >
                        
                        <h4>Serviços / Produtos</small></h4>
                <?php

                $sql_par_produto        = "SELECT gpro.nome'nome_grupo', pro.nome'nome_produto' FROM grupos_produtos gpro
JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
JOIN produtos pro ON prog.id_produto = pro.id_produto
WHERE gpro.id_grupo_produto = $id_grupo_produto";
                    $query_par_produto      = mysql_query($sql_par_produto, $banco_painel);
                                
                if (mysql_num_rows($query_par_produto)>0)
                {
                    $contar_produto = 0;
                    while ($dados = mysql_fetch_array($query_par_produto))
                    {
                        extract($dados); 
                        
                        echo "<div class=\"row\">
                            <div class=\"col-md-6\">
                                <div class=\"form-group\">
                                    <label class=\"control-label col-md-12\"><strong>Nome do Grupo:</strong></label>
                                    <div class=\"col-md-12\">
                                        <p class=\"form-control-static\"> $nome_grupo </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class=\"col-md-6\">
                                <div class=\"form-group\">
                                    <label class=\"control-label col-md-12\"><strong>Nome do Produto:</strong></label>
                                    <div class=\"col-md-12\">
                                        <p class=\"form-control-static\"> $nome_produto </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            </div>";
                        $contar_produto++;
                    }
                    
                }
                ?>

                        </div>
                        
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="mt-element-ribbon bg-grey-steel">
                                <div class="ribbon ribbon-color-default uppercase">Opções da venda atual. </div>
                                <p class="ribbon-content">
                                    <strong>Tipo Pagamento: </strong><?php echo $metodo_pagamento_atual_cliente; ?> <br />
                                    <strong>Prazo(meses): </strong><?php echo $prazo; ?> <br />
                                    <strong>Desconto em <?php echo $tipo_desconto; ?>: </strong><?php $ver_desconto = ($desconto > 0 ? $desconto : "sem desconto"); echo $ver_desconto; ?> <br />
                                    <strong>Entrada/Valor: </strong><?php echo db_moeda($valor_entrada); ?> <br />
                                    <input type="hidden" id="valor_entrada_atual" name="valor_entrada_atual" value="<?php echo $valor_entrada;?>"/>
                                    <strong>Forma de Pagamento:</strong> <?php echo $tipo_pagamento_atual_cliente; ?> <br />
                                    <strong>Parcelas:</strong> <?php echo $parcela_atual_cliente; ?> <br />
                                    <strong>Valor parcela:</strong> <?php echo db_moeda($valor_parcela_total_atual_cliente); ?><br />
                                    <strong>Valor Total:</strong> <?php echo db_moeda($valor_total); ?> <br />
                                    <strong>Último vencimento:</strong> 
                                    <?php
                                    
                                    $sql_dt_venc    = "SELECT data_vencimento FROM boletos_clientes
                                                    WHERE id_ordem_pedido = $id_ordem_pedido AND status_boleto = 0
                                                    ORDER BY data_vencimento DESC
                                                    LIMIT 0,1";
                                    //echo $sql_dt_venc;
                                    $query_dt_venc  = mysql_query($sql_dt_venc, $banco_painel);
                                    
                                    $dia_parcela_array = '-';                
                                    if (mysql_num_rows($query_dt_venc)>0)
                                    {
                                        $dia_parcela        = mysql_result($query_dt_venc, 0,0);
                                        $dia_parcela_array  = explode("-", $dia_parcela);
                                        echo converte_data($dia_parcela);
                                    }
                                    
                                    ?>
                                </p>
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-element-ribbon bg-grey-steel">
                                    <div class="ribbon ribbon-color-default uppercase">Serviços / Produtos</div>
                                    <p class="ribbon-content">
                                    <?php

                                        $sql_par_produto        = "SELECT gpro.nome'nome_grupo', pro.nome'nome_produto' FROM grupos_produtos gpro
                        JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
                        JOIN produtos pro ON prog.id_produto = pro.id_produto
                        WHERE gpro.id_grupo_produto = $id_grupo_produto";
                                            $query_par_produto      = mysql_query($sql_par_produto, $banco_painel);
                                        $nome_grupo = 'Sem nome';        
                                        if (mysql_num_rows($query_par_produto)>0)
                                        {
                                            $nome_grupo = mysql_result($query_par_produto, 0,'nome_grupo');
                                        }
                                        echo $nome_grupo;
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="info_add_produto_html" class=" panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMAÇÕES DE PAGAMENTO</h3>
                        </div>
                        <div class="panel-body">
                        <div class="row">
                        <?php
                        if($msg_status == 'finalizar_venda' OR $msg_status == 'renovar_venda' OR $msg_status == 'reativar_cliente'){
                            
                            //if($msg_status == 'renovar_venda'){
                                
                                $sql_grupo_produto        = "SELECT pser.preco_venda FROM grupos_produtos gpro
                                JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
                                JOIN produtos pro ON prog.id_produto = pro.id_produto
                                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                WHERE gpro.del = 'N' AND pser.id_parceiro = $id_parceiro AND gpro.id_grupo_produto = $id_grupo_produto AND pser.id_grupo_produto = $id_grupo_produto";
                                $query_grupo_produto      = mysql_query($sql_grupo_produto, $banco_painel);
                                                
                                if (mysql_num_rows($query_grupo_produto)>0)
                                {

                                    $soma_produto = 0;
                                    $somar_dependentes = 0;
                                        if($contar_dep > 0){
                                            
                                            
                                            
                                            $somar_dependentes = $contar_dep * $valor_plano_adicional_c;
                                        }
                                    while($dados_grupo_produto = mysql_fetch_array($query_grupo_produto))
                                    {
                                        extract($dados_grupo_produto);

                                        $soma_produto = $soma_produto + moeda_db($preco_venda);
                            
                                    }
                                    $soma_produto = $soma_produto + $somar_dependentes;
                                    
                                    
                                   
                                   
                                }

                          
                        ?>
                       <div class="col-md-3" style="display: none;">
                       <?php $soma_valor_prazo_parcela = $soma_produto * $prazo;?>
                       
                       <?php
                            $soma_produto_decimal = explode(".", $soma_valor_prazo_parcela);
                            if(strlen($soma_produto_decimal[1]) == 1)
                            {
                                $soma_produto_decimal[1] = $soma_produto_decimal[1].'0';
                            }elseif(strlen($soma_produto_decimal[1]) == 0)
                            {
                                $soma_produto_decimal[1] = '00';
                            }
                            $soma_valor_prazo_parcela = $soma_produto_decimal[0].".".$soma_produto_decimal[1];
                            ?>
                       
                       <!--<input type="hidden" id="total_geral_assistencia" name="total_geral_assistencia" value="<?php /*echo $soma_valor_prazo_parcela;*/?>"/>-->
                       
                       <?php
                       if($status == '4'){
                            
                           if($prazo > 0)
                            {
                                $data_termino = converte_data($data_termino);
                                $data = somar_datas( $prazo, 'm'); // adiciona 3 meses a sua data
                                $data_termino = str_replace('/', '-', $data_termino);
                                $data_termino = date('d/m/Y', strtotime('-'.$prazo.' month',strtotime($data_termino)));
                                $data_termino = converte_data($data_termino);
                            }  
                       }
                       ?>
                       
                       <input type="hidden" id="data_termino_atual" name="data_termino_atual" value="<?php echo $data_termino;?>"/>
                       <!--<input type="hidden" name="soma_produto" id="soma_produto" value="<?php /*echo $soma_produto; */?>"/>-->
                       <!--<p style="    margin-top: 28px;">Valor : <strong id="exibe_soma_produto_atual"><?php echo db_moeda($soma_produto); ?></strong> </p>-->
                       </div>
                       <div class="col-md-3">
                       
                           <?php
                           if($ativar_plano_cancelado != 'ok'){
                            
                            ?>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="valor_adicional" class="form-control" id="valor_adicional" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" value="" onkeyup="return soma_valor_adicional();"/>
                                <label for="nome">Somar (+) valor por parcela</label>
                           </div>
                            <?php
                           }
                           ?>
                       
                       </div>
                       <div class="col-md-3">
                        <div class="form-group form-md-line-input form-md-floating-label" style="display: block;">
                        <small>Campo NÃO obrigatório.</small>
                        </div>
                       </div>
                       </div>
                    <hr />   
                    <div class="row">
                    <div class="col-md-3">
                 <div class="form-group form-md-line-input form-md-floating-label">
                    <?php
                    
                    if($ativar_plano_cancelado != 'ok'){
                    ?>
                    <select class="form-control" id="forma_pagamento" name="forma_pagamento">
                        <option value=""></option>
                        
                         <?php
                            $sql_tipo_pagamnto        = "SELECT tipo_pagamento, desconto, valor_entrada_automatica, entrada, porcentagem_entrada, valor_entrada_auto FROM parceiros
            WHERE id_parceiro = $id_parceiro";
                            $query_tipo_pagamnto      = mysql_query($sql_tipo_pagamnto, $banco_painel);
                                            
                            if (mysql_num_rows($query_tipo_pagamnto)>0)
                            {
                                $tipo_pagamento = mysql_result($query_tipo_pagamnto, 0, 'tipo_pagamento');
                                
                                $permitir_desconto = mysql_result($query_tipo_pagamnto, 0, 'desconto');
                                
                                $verifica_valor_entrada_automatica = mysql_result($query_tipo_pagamnto, 0, 'valor_entrada_automatica');
                                
                                $verifica_entrada = mysql_result($query_tipo_pagamnto, 0, 'entrada');
                                
                                $verifica_porcentagem_entrada = mysql_result($query_tipo_pagamnto, 0, 'porcentagem_entrada');
                                $valor_entrada_auto = mysql_result($query_tipo_pagamnto, 0, 'valor_entrada_auto');
                                if(empty($verifica_porcentagem_entrada)){
                                    $verifica_porcentagem_entrada = 0;
                                }
                                
                                $tipo_pagamento_array = explode("|", $tipo_pagamento);
                                
                                $tipo_pagamento_contar = count($tipo_pagamento_array) - 1;
                                
                                for($i=0; $i<=$tipo_pagamento_contar; $i++)
                                {
                                    if($tipo_pagamento_array[$i] == 'avista'){
                                        $nome_tipo = "À vista";
                                    }elseif($tipo_pagamento_array[$i] == 'entrada_recorrente_cartao'){
                                        $nome_tipo = "Entrada + Recorrente Crédito";
                                    }elseif($tipo_pagamento_array[$i] == 'parcelado_cartao'){
                                        $nome_tipo = "Cartão";
                                    }elseif($tipo_pagamento_array[$i] == 'parcelado_cartao_recorrente'){
                                        $nome_tipo = "Parcelado Crédito Recorrente";
                                    }elseif($tipo_pagamento_array[$i] == 'recorrente_cartao'){
                                        $nome_tipo = "Recorrente Crédito";
                                    }elseif($tipo_pagamento_array[$i] == 'fidelidade'){
                                        $nome_tipo = "Cartão Fidelidade";
                                    }elseif($tipo_pagamento_array[$i] == 'entrada_parcelado_boleto'){
                                        $nome_tipo = "Boleto";
                                    }
                                    
                                    echo "<option value=\"$tipo_pagamento_array[$i]\">$nome_tipo</option>";
                                }
                                
                                
                            }
                
                        ?>
                    </select>
                    <label for="forma_pagamento">Tipo pagamento</label>
                    <?php
                    }else{
                    ?>
                    <input type="hidden" name="forma_pagamento" id="forma_pagamento" value="<?php echo $tipo_pagamento_atual_cliente;?>"/>
                    <?php
                    }
                    ?>
                </div>
              </div>
              <div class="col-md-6">
                 <div class="form-group form-md-line-input form-md-floating-label html_info_pagamento">
                    <strong>Valor Total da Assistência:</strong><span class="exibe_soma_total">-</span> <br />
                    <strong>Valor por parcela sem desconto:</strong><span class="exibe_soma_valor_total_parcela">-</span> <br /> <br />
                    <?php
                    $valor_renovacao = 0;
                     if($msg_status == 'renovar_venda' OR $msg_status == 'finalizar_venda'){
                                        
                            $sql_valor_renova   = "SELECT valor_desconto_renovacao FROM parceiros
                                                WHERE id_parceiro = $id_parceiro";
                                          
                            $query_valor_renova = mysql_query($sql_valor_renova, $banco_painel);
                            
                            if(mysql_num_rows($query_valor_renova)>0)
                            {
                                $valor_renovacao = mysql_result($query_valor_renova, 0, 'valor_desconto_renovacao');
                            }
                             
                    }
                    $soma_produto_decimal = explode(".", $soma_produto);
                    if(strlen($soma_produto_decimal[1]) == 1)
                    {
                        $soma_produto_decimal[1] = $soma_produto_decimal[1].'0';
                    }elseif(strlen($soma_produto_decimal[1]) == 0)
                    {
                        $soma_produto_decimal[1] = '00';
                    }
                    $soma_produto_decimal_correto = $soma_produto_decimal[0].".".$soma_produto_decimal[1];
                    
                    
                    
                    ?>
                     <input type="hidden" name="soma_produto" id="soma_produto" value="<?php echo $soma_produto_decimal_correto; ?>"/>
                     <input type="hidden" name="soma_produto_com_desconto" id="soma_produto_com_desconto" value=""/>
                     <input type="hidden" name="valor_renovacao" id="valor_renovacao" value="<?php echo $valor_renovacao?>"/>
                     <input type="hidden" name="total_geral_assistencia" id="total_geral_assistencia" value=""/>
                     <input type="hidden" name="soma_produto_atual" id="soma_produto_atual" value="<?php echo $soma_produto; ?>"/>
                     <div id="exibe_novo_valor_total_geral_assistencia" style="display: none;"><strong>TOTAL com desconto: </strong> <span id="novo_valor_total_geral_assistencia" class="bg-green-jungle bg-font-green-jungle" style="font-size: 20px;padding: 5px;"></span> <br />
                    <strong>PARCELA com desconto: </strong> <span id="novo_valor_parcela_com_desconto" class="bg-green-jungle bg-font-green-jungle" style="font-size: 20px;padding: 5px;"></span> </div> 
                    <!--<strong>Valor produto: </strong><span class="exibe_soma_produto"> <?php echo db_moeda($soma_produto); ?> </span> <span class="exibe_desconto"></span> <span class="exibe_dependente"></span> <strong><span class="exibe_total_parcela"></span></strong> <br />-->

                </div>
              </div>    
              </div>
              
              <div class="row">
              <div class="col-md-3">
                 <div class="form-group form-md-line-input form-md-floating-label" id="select_prazo">
                    
                </div>
                &nbsp;
              </div>
              <div class="col-md-6" >
                 <div class="form-group form-md-line-input form-md-floating-label prazo_periodo html_info_pagamento">
                    <strong>Prazo selecionado:</strong> <span class="exibe_prazo">-</span> <br />
                    <div style="display: none;"><strong>Início vigência:</strong> <span class="exibe_inicio">-</span> / <strong> Termino Vigência:</strong> <span class="exibe_termino">-</span></div>
                </div>
              </div>
            </div>
            <?php
            if($permitir_desconto == 'S' AND $ativar_plano_cancelado != 'ok'){
            ?>
            <div class="row">
                <div class="col-md-2 note note-info">
                   <div class="form-group form-md-line-input form-md-floating-label">
                       <input type="text" name="desconto" class="form-control" id="desconto" value="" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" maxlength="6"/>
                       <label for="nome">Desconto</label>
                       <span class="help-block">Por Parcela</span>
                    </div>
                </div>
                <div class="col-md-1 note note-info">
                   <div class="form-group form-md-line-input form-md-floating-label">
                       <select class="form-control" id="tipo_desconto" name="tipo_desconto">
                           <option value="%" selected="">%</option>
                           <option value="R$">R$</option>
                       </select>
                       <label for="tipo_desconto">&nbsp;</label>
                   </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-md-line-input form-md-floating-label" style="display: block;">
                        <small>Campo NÃO obrigatório.</small>
                    </div>
               </div>
            </div> 
            <?php
            }
            ?>
            <?php 
            
            if($ativar_plano_cancelado != 'ok'){
                
                $status_verifica_entrada = false;
                $check_entrada = "";
                $valor_se_entrada = 'N';
                $css_display_entrada = 'style="display: none;"';
                if($verifica_entrada == 'S' ){
                    if($verifica_valor_entrada_automatica == 'S' ){
                        $status_verifica_entrada = true;
                        $check_entrada = 'checked=""';
                        $valor_se_entrada = 'S';
                        $css_display_entrada = '';
                    }
                }
                
                if($msg_status == 'finalizar_venda'){
                    
            ?>
            <div class="row">
            <input type="hidden" value="<?php echo $msg_status;?>" id="input_msg_status" />
            <div class="note note-info col-md-3" id="bloco_verifica_entrada">
            
                    <input type="hidden" value="<?php echo $valor_se_entrada;?>" id="input_status_entrada" name="input_status_entrada" />
            <h5>TAXA ADESÃO?</h5>
                <div class="form-group form-md-radios">
                    <div class="md-radio-list">
                        <div class="md-radio">
                            <input type="radio" id="entrada1" name="permitir_entrada" class="md-radiobtn" onclick="return f_permitir_entrada();" value="S" <?php echo $check_entrada;?>/>
                            <label for="entrada1">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span> SIM! </label>
                        </div>
                        <?php
                        if($status_verifica_entrada == false){
                            
                        ?>
                        <div class="md-radio">
                            <input type="radio" id="entrada2" name="permitir_entrada" class="md-radiobtn" onclick="return f_permitir_entrada();" value="N" checked=""/>
                            <label for="entrada2">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> NÃO! </label>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            
            </div>
            <div class="col-md-3">
                    <div class="form-group form-md-line-input form-md-floating-label" style="display: block;">
                        <small>Campo NÃO obrigatório.</small>
                    </div>
               </div>
           </div>
           <?php
           }else{
           ?>
            <input type="hidden" value="<?php echo $valor_se_entrada;?>" id="input_status_entrada" name="input_status_entrada" />
            <input type="hidden" value="<?php echo $msg_status;?>" id="input_msg_status" />
           <?php
           }
           ?>
           
           <div id="bloco_entrada" <?php echo $css_display_entrada;?>>
               <?php
                if($verifica_entrada == 'S' ){
                    if($verifica_valor_entrada_automatica == 'N' ){
                    ?>
                    <div class="row">
                       <div class="col-md-2 ">
                        <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="hidden" name="tipo_calculo_entrada" id="tipo_calculo_entrada" value="manual"/>
                        <input type="hidden" name="valor_entrada_auto_parceiro" id="valor_entrada_auto_parceiro" value="<?php echo moeda_db($valor_entrada_auto); ?>"/>
                            <input type="text" name="valor_entrada" class="form-control" id="valor_entrada" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" value="<?php echo $valor_entrada_auto; ?>" data="<?php echo $verifica_porcentagem_entrada; ?>"/>
                            
                            <label for="nome">Valor de adesão</label>
                            
                        </div>
                       </div>
                       <div class="col-md-3">
                                <div class="md-checkbox-list">
                                    <div class="md-checkbox">
                                        <input type="checkbox" name="tipo_desconto_entrada" value="ok" id="tipo_desconto_entrada" class="md-check"/>
                                        <label for="tipo_desconto_entrada">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Cobrar entrada individual.</label>
                                            <span class="help-block" style="color: #00BCD4;">&nbsp; <br />
                                            </span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                       <div class="col-md-6">
                            <span class='help-block' style="color: #00BCD4;">** Valor mínimo de <strong><?php echo $verifica_porcentagem_entrada; ?>%</strong> no Valor Total da Assistência.</span>
                       </div>
                    </div>
                    <?php
                        }else{
                            ?>
                    <div class="row">
                       <div class="col-md-12">
                           <input type="hidden" name="valor_entrada" id="valor_entrada" value="0"/>
                           <input type="hidden" name="tipo_calculo_entrada" id="tipo_calculo_entrada" value="auto"/>
                           <input type="hidden" name="valor_entrada_auto" id="valor_entrada_auto" value="<?php echo moeda_db($valor_entrada_auto); ?>"/>
                           <p><br/> Valor da Adesão será calculada automaticamente. Valor da adesão R$ <?php echo $valor_entrada_auto; ?></p>
                       </div>
                    </div>
                    <?php
                        }
                    ?>
                    <h5>FORMA DE PAGAMENTO DA ADESÃO!</h5>
                    <div class="form-group form-md-radios">
                        <div class="md-radio-list">
                            <div class="md-radio">
                                <input type="radio" id="tipo_recebimento_entrada1" name="tipo_recebimento_entrada" class="md-radiobtn" value="BO" checked=""/>
                                <label for="tipo_recebimento_entrada1">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Boleto / à vista </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="tipo_recebimento_entrada2" name="tipo_recebimento_entrada" class="md-radiobtn" value="CA" />
                                <label for="tipo_recebimento_entrada2">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Cartão crédito / débito </label>
                            </div>
                        </div>
                    </div>
                <?php                        
                }
                else{
                ?>
                <p><br/>Você não tem permissão para vender com valor de Entrada.</p>
                 <input type="hidden" name="valor_entrada" id="valor_entrada" value="0"/>
                 <input type="hidden" name="tipo_calculo_entrada" id="tipo_calculo_entrada" value="sem_permissao"/>
                <?php
                }
                ?>
            </div>
            
            <div class="row">
            <hr />
                <div class="col-md-12 " >
                    <button type="button" class="btn btn-info" id="bt_calcular_pagamento"><i class="fa fa-calculator"></i> Calcular</button>
                    <button type="button" disabled="" class="btn btn-danger" id="bt_cancelar_calcular_pagamento"><i class="fa fa-close"></i> Cancelar</button>
                </div>
            <hr />
            </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-8 html_forma_pagamento">     
                <div class="form-group form-md-radios">
                    <h4>Forma de Pagamento</h4>
                    <span class="div_aguarde_2" style="position: absolute;width: 90%;padding-top: 10px;height: 100%;text-align: center;display: none;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
                        <div class="md-radio-inline">
            <?php
            
                $sql_forma_pagamnto        = "SELECT metodo_pagamento FROM parceiros
WHERE id_parceiro = $id_parceiro";
                $query_forma_pagamnto      = mysql_query($sql_forma_pagamnto, $banco_painel);
                                
                if (mysql_num_rows($query_forma_pagamnto)>0)
                {
                    $metodo_pagamento = mysql_result($query_forma_pagamnto, 0, 'metodo_pagamento');
                    
                    $metodo_pagamento_array = explode("|", $metodo_pagamento);
                    
                    $metodo_pagamento_contar = count($metodo_pagamento_array) - 1;
                    
                    if($metodo_pagamento_contar >= 1)
                    {
                        for($i = 0; $i<=$metodo_pagamento_contar;$i++){
                            
                            if($metodo_pagamento_array[$i] == 'MA'){
                                $nome_forma_pagamento = "Máquina de Cartão";
                            }elseif($metodo_pagamento_array[$i] == 'ON'){
                                $nome_forma_pagamento = "Checkout Online";
                            }elseif($metodo_pagamento_array[$i] == 'BO'){
                                $nome_forma_pagamento = "Boleto Parcelado";
                            }
                            

                            echo "<div class=\"md-radio\" id=\"div_radio$metodo_pagamento_array[$i]\">
                            <input type=\"radio\" id=\"radio$metodo_pagamento_array[$i]\" name=\"metodo_pagamento\" class=\"md-radiobtn\" onclick=\"return sel_metodo_pagamento();\" value=\"$metodo_pagamento_array[$i]\"/>
                            <label for=\"radio$metodo_pagamento_array[$i]\">
                                <span class=\"inc\"></span>
                                <span class=\"check\"></span>
                                <span class=\"box\"></span> $nome_forma_pagamento </label>
                        </div>"; 
                        }
                       
                        echo "<div class='txt_metodo_pagamento'></div>";
                    }
                    else
                    {
                        if($metodo_pagamento_array[0] == 'MA')
                        {
                            echo "<strong>Máquina de Cartão</strong>. Antes de registrar venda, confirme o pagamento na máquina de cartão.
                            <input type=\"hidden\" name=\"metodo_pagamento\" value=\"MA\"/><div class='col-md-5'><div class='form-group form-md-line-input form-md-floating-label'><div class='input-group input-group-sm'><div class='input-group-control'><input type='text' name='comprovante_maquina' class='form-control' id='comprovante_maquina' value=''/><label for='comprovante_maquina'>AUT. Comprovante</label><span class='help-block'>Somente números..</span></div><span class='input-group-btn btn-right'><a class='btn red btn-outline sbold' data-toggle='modal' href='#responsive'><i class='fa fa-info'></i></a></span></div></div></div>";
                        }
                        elseif($metodo_pagamento_array[0] == 'ON')
                        {
                            echo "<strong>Checkout Online</strong>. Você será redirecionado para página de Checkout da CIELO.
                            <input type=\"hidden\" name=\"metodo_pagamento\" value=\"ON\"/>";
                        }elseif($metodo_pagamento_array[0] == 'BO')
                        {
                            echo "<input type=\"hidden\" name=\"metodo_pagamento\" value=\"BO\"/>";
                            
                            $id_parceiro_login = $id_parceiro_s;
                            if($nivel_usuario == 'A'){
                                $id_parceiro_login = $id_parceiro;
                            }
                            
                            $sql_parcelas_parceiro        = "SELECT parcelas_boleto FROM parceiros
                            WHERE id_parceiro = $id_parceiro_login";
                            $query_parcelas_parceiro      = mysql_query($sql_parcelas_parceiro, $banco_painel);
                                            
                            if (mysql_num_rows($query_parcelas_parceiro)>0)
                            {
                                echo "<div class=\"col-md-12\"><strong>Boleto Parcelado</strong>. Após Finalizar cadastro, solicitar boleto(s) no Banco emissor.</div><input type=\"hidden\" name=\"valor_parcela_boleto\" id=\"valor_parcela_boleto\" value=\"\"/><div class='col-md-5' id=\"div_parcela_parcelas_boleto_2\" style=\"display: block;\"><div class='form-group form-md-line-input form-md-floating-label'><select class='form-control' id='parcela_parcelas_boleto' name='parcela_parcelas_boleto'><option value=''></option>";
                                
                                $parcelas_boleto = mysql_result($query_parcelas_parceiro, 0, 'parcelas_boleto');
                                $parcelas_boleto_array = explode("|", $parcelas_boleto);
                                $contar_array = count($parcelas_boleto_array) - 1;
                                
                                for($i = 0; $i<=$contar_array;$i++){
                                    $txt_parc = 'vezes';
                                    if($parcelas_boleto_array[$i] == 1){
                                        $txt_parc = 'vez';
                                    }
                                    echo "<option value='$parcelas_boleto_array[$i]'>$parcelas_boleto_array[$i] $txt_parc</option>";
                                    
                                }
                                
                                echo "</select><label for='parcela_parcelas_boleto'>Parcelar em:</label></div></div><div class='col-md-5 txt_parcela_boleto' style='margin-top: 30px;display: block!important;'></div>";
                            }
                        }
                        
                        
                    }
                }
            ?>
                
            <div id="responsive" class="modal fade modal-scroll" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Modelo de Comprovante Estabelecimento</h4>
                        </div>
                        <div class="modal-body">
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; "><div class="scroller" style="text-align: center; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                                <img src="assets/pages/img/comprovante_cielo_aut.png"/>
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn dark btn-outline">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
                      <div class="col-md-12" id="html_emissao_boleto" style="display: none;">
                
                <?php
                $id_parceiro_login = $id_parceiro_s;
                    $sql_forma_pagamnto        = "SELECT emissao_boleto FROM parceiros
    WHERE id_parceiro = $id_parceiro_login";
                    $query_forma_pagamnto      = mysql_query($sql_forma_pagamnto, $banco_painel);
                                    
                    if (mysql_num_rows($query_forma_pagamnto)>0)
                    {
                        ?>
                        <div class="form-group form-md-radios">
                        <label>Emissão Boleto</label>
                        <div class="md-radio-inline">
                        
                        <?php
                        
                        $emissao_boleto = mysql_result($query_forma_pagamnto, 0, 'emissao_boleto');
                        
                        $emissao_boleto_array = explode("|", $emissao_boleto);
                        
                        $emissao_boleto_contar = count($emissao_boleto_array) - 1;
                        
                        if(!empty($emissao_boleto))
                        {
                            for($i = 0; $i<=$emissao_boleto_contar;$i++){
                                
                                if($i == 0){
                                    $checked = 'checked="checked"';
                                }
                                echo "<div class=\"md-radio\" >
                                <input type=\"radio\" id=\"radio_$emissao_boleto_array[$i]\" name=\"emissao_boleto\" class=\"md-radiobtn\" value=\"$emissao_boleto_array[$i]\" $checked/>
                                <label for=\"radio_$emissao_boleto_array[$i]\">
                                    <span class=\"inc\"></span>
                                    <span class=\"check\"></span>
                                    <span class=\"box\"></span> $emissao_boleto_array[$i] </label>
                            </div>";
                                
                            }
                        }
                        ?>
                            </div>
                        </div>
                    <?php 
                    }
                ?>
     
                </div> 
                
                <?php
                }else
                {
                ?>
                <input type="hidden" name="valor_entrada" id="valor_entrada" value="<?php echo $entrada;?>"/>
                <input type="hidden" name="valor_entrada_verificar" id="valor_entrada" value="<?php echo $valor_entrada;?>"/>
                <input type="hidden" name="tipo_calculo_entrada" id="tipo_calculo_entrada" value="manual"/>
                <input type="hidden" name="metodo_pagamento" id="metodo_pagamento" value="<?php echo $metodo_pagamento_atual_cliente;?>"/>
                <input type="hidden" name="valor_parcela_boleto" id="valor_parcela_boleto" value="<?php echo $valor_parcela_total_atual_cliente;?>"/>
                <input type="hidden" name="emissao_boleto" id="emissao_boleto" value="BANCO"/>
                <input type="hidden" name="prazo" id="prazo" value="<?php echo $prazo; ?>" />
                <input type="hidden" name="ativar_plano_cancelado" id="ativar_plano_cancelado" value="ok" />
                <input type="hidden" name="manter_vigencia" id="manter_vigencia" value="ok" />
                <input type="hidden" name="parcela_parcelas_boleto" id="parcela_parcelas_boleto" value="<?php echo $parcela_atual_cliente;?>" />
                
                <?php
                }
                ?>
                        <?php
                        
                        if(isset($_GET['alterar_pagamento']) AND !empty($_GET['alterar_pagamento']) AND $_GET['alterar_pagamento'] == 'true' AND $ativar_plano_cancelado != 'ok'){
                        ?>
                        <hr />
                        <div>
                            <div class="col-md-12">
                                <div class="md-checkbox-list">
                                    <div class="md-checkbox">
                                        <input type="checkbox" name="manter_vigencia" value="ok" id="manter_vigencia" class="md-check"/>
                                        <label for="manter_vigencia">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Manter data de Término de vigência! Término em: <strong><?php echo converte_data($data_termino);?></strong> </label>
                                            <span class="help-block" style="color: #00BCD4;">** Os DADOS PESSOAIS do cliente, Dependente(s) e Plano(s) Adicional(s) serão salvos, assim como o valor NOVO VALOR das parcelas. Porém as Datas de vencimentos são ANULADAS. <br />
                                            <!--<strong>Tipo Pagamento: </strong><?php echo $metodo_pagamento_atual_cliente; ?> <br />
                                            <strong>Prazo(meses): </strong><?php echo $prazo; ?> <br />
                                            <strong>Desconto em %: </strong><?php echo $desconto; ?> <br />
                                            <strong>Entrada/Valor: </strong><?php echo db_moeda($valor_entrada); ?> <br />
                                            <strong>Forma de Pagamento:</strong> <?php echo $tipo_pagamento_atual_cliente; ?> <br />
                                            <strong>Parcelas:</strong> <?php echo $parcela_atual_cliente; ?> <br />
                                            <strong>Valor parcela:</strong> <?php echo db_moeda($valor_parcela_total_atual_cliente); ?><br />
                                            <strong>Valor Total:</strong> <?php echo db_moeda($valor_total); ?>-->
                                            </span>
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
                       }elseif($msg_status == 'ativar_venda' AND $nivel_usuario == 'A'){
                        
                        $sql_boleto_ver     = "SELECT * FROM boletos_clientes
                                           WHERE id_ordem_pedido = $id_ordem_pedido AND entrada = 'S' AND status_boleto = 0";
                        
                        $query_boleto_ver   = mysql_query($sql_boleto_ver, $banco_painel);
                                        
                        if (mysql_num_rows($query_boleto_ver)>0)
                        {
                  
                            // buscar dada de pagamento do boleto ou avisar que não foi pago
                            $sql_boleto     = "SELECT valor_parcela, pago, data_vencimento, data_pagamento FROM boletos_clientes
                                                WHERE id_ordem_pedido = $id_ordem_pedido AND entrada = 'S' AND  status_boleto = 0";
                    
                            $query_boleto   = mysql_query($sql_boleto, $banco_painel);
                            
                            $html_tipo_pagamento = 'ENTRADA';
                            
                        }else{
                            // buscar dada de pagamento do boleto ou avisar que não foi pago
                            $sql_boleto     = "SELECT valor_parcela, pago, data_vencimento, data_pagamento FROM boletos_clientes
                                                WHERE id_ordem_pedido = $id_ordem_pedido AND entrada = 'N' AND parcela = 1 AND status_boleto = 0";
                    
                            $query_boleto   = mysql_query($sql_boleto, $banco_painel);
                            $html_tipo_pagamento = '1° parcela';
                        }
                        
                        
                                        
                        if (mysql_num_rows($query_boleto)>0)
                        {
                            
                            $dados = mysql_fetch_array($query_boleto);
                            extract($dados); 
                            $input_data_inicio = '';
                           if($pago == 'S'){
                            $input_data_inicio = converte_data($data_pagamento);
                            $input_data_inicio = str_replace("/", "-", $input_data_inicio);
                            $proxima_data = date('d/m/Y', strtotime('+1 day', strtotime($input_data_inicio)));
                            $proxima_data = str_replace("/", "-", $proxima_data);
                            echo "<div class=\"note note-success\"><h4>PAGAMENTO EFETUADO COM SUCESSO!</h4><p> Pagamento da $html_tipo_pagamento efetuado em: <strong>".converte_data($data_pagamento)."</strong>, com vencimento dia ".converte_data($data_vencimento)." e início de vigência dia ".$proxima_data." - Prazo de $prazo meses.</p></div>";

                            }else{
                                echo "<div class=\"note note-danger\"><h4> ATENÇÃO! </h4><p> Pagamento não confirmado, com vencimento em ".converte_data($data_vencimento)." - Prazo de $prazo meses.</p></div>";
                                $input_data_inicio = converte_data($data_vencimento); 
                                $input_data_inicio = str_replace("/", "-", $input_data_inicio);
                                $proxima_data = date('d/m/Y', strtotime('+1 day', strtotime($input_data_inicio)));   
                                $proxima_data = str_replace("/", "-", $proxima_data);
                            }
                            $data_venda_campo = converte_data($data_inicio);
                            
                            /*if($status_renovacao == 'S'){
                                $data_venda_campo = str_replace("/", "-", $data_venda_campo);
                                $data_venda_campo = date('d/m/Y', strtotime('+12 month', strtotime($data_venda_campo)));
                            }*/
                            
                            
                            $data_venda_campo = str_replace("/", "-", $data_venda_campo);
                         ?>
                             <input type="hidden" name="prazo" id="prazo" value="<?php echo $prazo; ?>" />
                             <input type="hidden" name="soma_produto" id="soma_produto" value="1" />
                             <input type="hidden" name="forma_pagamento" id="forma_pagamento" value="AT" />
                             <input type="hidden" name="valor_parcela_boleto" id="valor_parcela_boleto" value="1" />
                             <div class="note note-success">Data da Venda: <?php echo converte_data($data_venda);?></div>
                            <div class="col-md-3"><div class="form-group"><label><strong>Início de Vigência:</strong></label><div class="" data-date-format="dd-mm-yyyy" data-date-start-date="-180d"><input type="text" name="imput_dt_inicio_vigencia" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="-180d" value="<?php echo $data_venda_campo;?>" readonly ></div></div></div>
                            <? 
                            
                            
                        }
                        
                        
                       }
                       
                       ?>
                       
                        <div class="col-md-12 " <?php $html_margin = ($msg_status == 'ativar_venda') ? "" : '" style="margin-bottom: 20px;"'; echo $html_margin;?>>
                            <input type="hidden" name="acao_editar" id="acao_editar" value="<?php echo $msg_status; ?>" />
                            <input type="hidden" name="id_grupo_produto" id="id_grupo_produto" value="<?php echo $id_grupo_produto; ?>" />
                            <input type="hidden" name="id_produto_get" id="id_produto_get" value="<?php echo $id_produto_get; ?>" />

                            <?php

                            if($status == 3 AND ($tipo_movimento == 'IN' OR $tipo_movimento == 'AL')) { // é dependente
                                $msg_status = 'finalizar_cadastro';
                            }
                            ?>
                            <input type="hidden" name="msg_status" id="msg_status" value="<?php echo $msg_status; ?>" />

                            <input type="hidden" name="status_renovacao" id="status_renovacao" value="<?php echo $status_renovacao; ?>" />
                            
                            <?php
                            $soma_produto_decimal = explode(".", $soma_produto);
                            if(strlen($soma_produto_decimal[1]) == 1)
                            {
                                $soma_produto_decimal[1] = $soma_produto_decimal[1].'0';
                            }elseif(strlen($soma_produto_decimal[1]) == 0)
                            {
                                $soma_produto_decimal[1] = '00';
                            }
                            $soma_produto_decimal_correto = $soma_produto_decimal[0].".".$soma_produto_decimal[1];
                            ?>
                            
                             <input type="hidden" name="valor_parcela_total" id="valor_parcela_total" value="<?php echo $soma_produto_decimal_correto; ?>" />
                            
                           
                            <?php
                            }
                            
                            if($contar_produto > 1){
                            ?>
                             <input type="hidden" name="select_produto" id="select_produto" value="todos" />
                            <?php
                            }else{
                            ?>
                            <input type="hidden" name="select_produto" id="select_produto" value="<?php echo $id_produto_get; ?>" />
                            
                            <?php
                            }
                            
                            ?>
                            
                            <strong><?php
                            
                            if($msg_status == 'editar_cliente' OR $msg_status == 'finalizar_cadastro'){
                                echo "<h5>Ação após salvar o registro:</h5>
                                Apenas atualizar dados pessoais do cliente";
                            }
                            ?></strong>
                       </div>
                    
                    
                 
                </div>
        </div>
    </div>
                                                
                                                <div class="col-md-12" >
                                                <div class="form-actions noborder">
                                                    <button type="submit" class="btn btn-lg blue" onclick="return bt_submit_action();" id="bt_add_submit"> <i class="fa fa-check"></i> <?php
                            if($msg_status == 'finalizar_venda'){
                                if($tipo_movimento == 'EX'){
                                    echo "Reativar venda";
                                }else{
                                    echo "Finalizar venda";
                                }
                                
                            }elseif($msg_status == 'editar_cliente' OR $msg_status == 'finalizar_cadastro'){
                                echo "Atualizar cliente";
                            }elseif($msg_status == 'renovar_venda'){
                                echo "Renovar cliente";
                            }elseif($msg_status == 'reativar_cliente'){
                                echo "Reativar cliente";
                            }elseif($msg_status == 'ativar_venda'){
                                echo "Ativar Venda";
                            }
                                                    ?></button> <span class="div_aguarde" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, atualizando venda...</span>
                                                </div>
                                            </div>
                                                <?php  
            
                                        }
                                    }elseif ($item == 'usuarios' AND in_array("22", $verifica_lista_permissoes_array_inc)){
                                    ?>
                                   <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Usuário</span>
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
                                            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                            <div class="form-body">
                                                 <div class="row">
                                                    <div class="col-md-5 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                         <label class="control-label ">Parceiro</label>
                                                         <div class="">
                                                        
                                                        <?php  
   
                                                            $sql_parceiros        = "SELECT nome FROM parceiros
                                                            WHERE del = 'N' AND id_parceiro = $id_parceiro";
                                                            $query_parceiros      = mysql_query($sql_parceiros);              
                                                            if (mysql_num_rows($query_parceiros)>0)
                                                            {
                                                                $nome_parceiro = mysql_result($query_parceiros, 0, 'nome');
                                                            }
                                                                
                                                        ?>
                                                            <p class="form-control-static"> <strong><?php echo $nome_parceiro; ?></strong> </p>
                                                            <input type="hidden" class="form-control form-filter input-sm" name="select_parceiro_user" value="<?php echo $id_parceiro; ?>"/>
                                                            
                                                            
                                                            <?
                                                            //}

                                                        ?>
                                                        
                                                        </div>
                                                        </div>
                                                        
                                                     </div>
                                                     <div class="col-md-4 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <?php  
   
                                                            $sql_filial        = "SELECT nome FROM filiais
                                                            WHERE del = 'N' AND id_filial = $id_filial";
                                                            $query_filial      = mysql_query($sql_filial);              
                                                            if (mysql_num_rows($query_filial)>0)
                                                            {
                                                            ?>
                                                            <label class="control-label ">Filial</label>
                                                            <?php
                                                                $nome_filial = mysql_result($query_filial, 0, 'nome');
                                                            }
                                                                
                                                        ?>
                                                            <p class="form-control-static"> <strong><?php echo $nome_filial; ?></strong> </p>
                                                            <input type="hidden" class="form-control form-filter input-sm" name="select_parceiro_user" value="<?php echo $id_filial; ?>"/>
                                                            
                                                            
                                                            <?
                                                            //}

                                                        ?>
                                                        
                                                        
                                                        </div>
                                                     </div>
                                                      <div class="col-md-3 ">
                                                          <div class="form-group form-md-line-input form-md-floating-label">
                                                          
                                                            <select class="form-control" id="nivel" name="nivel">
                                                                <option value=""></option>
                                                                <?php
                                                                    if($nivel_usuario == 'A'){
                                                                ?>
                                                                    <option value="A" <?php $sel_nivel = ($nivel == 'A') ? 'selected=""' : ''; echo $sel_nivel; ?>> Administrador </option>
                                                                    <option  value="P" <?php $sel_nivel = ($nivel == 'P') ? 'selected=""' : ''; echo $sel_nivel; ?>> Parceiro </option>
                                                                <?php
                                                                    }
                                                                ?>
                                                                <?php 
                                                                if($nivel_usuario == 'P'){
                                                                ?>
                                                                <option  value="P" <?php $sel_nivel = ($nivel == 'P') ? 'selected=""' : ''; echo $sel_nivel; ?>> Parceiro </option>
                                                                <?php
                                                                }
                                                                ?>
                                                                
                                                                <option value="U" <?php $sel_nivel = ($nivel == 'U') ? 'selected=""' : ''; echo $sel_nivel; ?>> Usuário </option>
                                                                 
                                                            </select>
                                                            <label for="nivel" >Nível de Acesso</label>
                                                          </div>
                                                       </div>
                                                     </div>
                                                     <div class="row">
                                                           <div class="col-md-6">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" name="nome" class="form-control" id="nome" value="<? echo $nome; ?>"/>
                                                                    <label for="nome">Nome</label>
                                                                    <span class="help-block">Como será chamado o usuário...</span>
                                                                </div>
                                                           </div>
                                                           <div class="col-md-6">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                                    <input type="text" name="email" class="form-control" id="email" value="<?php echo $email; ?>"/>
                                                                    <label for="email">E-mail</label>
                                                                    <span class="help-block">e-mail de contato...</span>
                                                              </div>

                                                           </div>
                                                      </div>
                                                      <div class="row"> 
                                                          <div class="col-md-3">
                                                               <div class="form-group form-md-line-input form-md-floating-label">
                                                         <label class="control-label ">Usuário</label>
                                                         <div class="">
                                                        
                                                       
                                                            <p class="form-control-static"> <strong><?php echo $login; ?></strong> </p>
                                                            
                                                            <?
                                                            //}

                                                        ?>
                                                        
                                                        </div>
                                                        </div>
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
                                                                        <span>** Deixe em branco para manter mesma senha.</span>
                                                               </div>
                                                           </div>
                                                       </div>
                                                        <?php
                                                        $html_hide = 'style="display: none!important;"';
                                                        if(in_array("23", $verifica_lista_permissoes_array_inc)){
                                                        $html_hide = '';
                                                        }
                                                        ?>
                                                       <div class="row" <?php echo $html_hide; ?>>
                                                           <div class="col-md-3">
                                                              <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" id="status" name="status">
                                                                <option value=""></option>
                                                                <option value="N" <?php $sel_nivel = ($del == 'N') ? 'selected=""' : ''; echo $sel_nivel; ?>> Ativo </option>
                                                                <option value="S" <?php $sel_nivel = ($del == 'S') ? 'selected=""' : ''; echo $sel_nivel; ?>> Inativo </option>
                                                                 
                                                            </select>
                                                            <label for="status">Status</label>
                                                          </div>
                                                           </div>
                                                           <div class="col-md-3">
                                                              <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" id="apagar_user" name="apagar_user">
                                                                <option value=""></option>
                                                                <option value="S"> Sim </option>
                                                            </select>
                                                            <label for="apagar_user">Apagar Usuário</label>
                                                          </div>
                                                           </div>
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
                                            if($nivel_usuario == 'A' AND $nivel_status == 0 AND in_array("25", $verifica_lista_permissoes_array_inc)){
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
                                                            $lista_permissoes_array = explode("|", $lista_permissoes);
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
                                                                            $html_check_lista = '';
                                                                            if (in_array($id_permissao, $lista_permissoes_array)) $html_check_lista = "checked='checked'";
                                                                            echo "<div class=\"md-checkbox\" style=\"    width: 23%;\">
                                                                            <input type=\"checkbox\" id=\"separa_permissao_$contar_perm\" name=\"lista_permissoes[]\" class=\"md-check\" value=\"$id_permissao\" $html_check_lista>
                                                                            <label for=\"separa_permissao_$contar_perm\">
                                                                                <span></span>
                                                                                <span class=\"check\"></span>
                                                                                <span class=\"box\"></span> $nome_permissao </label>
                                                                            </div>";
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
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar Usuário</button>
                                                    </div>
                                                </div>            
                                   
                                        
                                    <?php
                                    }
                                    ?> 
                                        
                                            </form>    
                                        </div>
                                    </div>
                                    <!-- End: life time stats -->
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
            <div class="container-fluid"> 2016 &copy; Painel Trail Servicos.
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
        <script src="assets/pages/scripts/editar.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/form-validation-md.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
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
