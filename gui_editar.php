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
    
    $tipo_get = (empty($_GET['tipo'])) ? "" : $_GET['tipo'];
    
    if($tipo_get <> 'produto' AND $tipo_get <> 'gui_guias_detalhes')
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
        
<style>
.html_info_pagamento, .html_forma_pagamento, #bt_calcular_pagamento{
    display: none;
}

.sel_cid_sel{
    margin: 5px 10px;
    background: #eee;
    padding: 6px 10px;
    float: left;
    position: relative;
}
.resaltar{
    background: #FFEB3B;
}
</style>
       
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
                        <?php
        	               include ('inc/msg_status.php');
                        ?>            
                            <?php
                            if($item == 'gui_pacientes'){
                            ?>
                                <form role="form" name="form" id="form_adicionar_cliente" action="gui_editar_db.php" method="post" enctype="multipart/form-data">
                            <?php
                            }else
                            {
                            ?>
                                <form role="form" name="form" id="form_adicionar" action="gui_editar_db.php" method="post" enctype="multipart/form-data">
                            <?php
                            }
                            ?>           
                            
                            <?php
                               if ($item == 'gui_local_atendimento' AND ($nivel_usuario == 'A' OR $nivel_usuario == 'P') AND in_array("8", $verifica_lista_permissoes_array_inc))
                                {   
                            ?> 
                                    <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Local Atendimento</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group">
                                                
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" id="tipo_local_atendimento" name="tipo_local_atendimento">
                                                                <option value=""></option>
                                                                <option value="CLINICA" <?php $sel_tipo = ($tipo == 'CLINICA') ? 'selected=""' : ''; echo $sel_tipo; ?>>Clinica</option>
                                                                <option value="CONSULTORIO" <?php $sel_tipo = ($tipo == 'CONSULTORIO') ? 'selected=""' : ''; echo $sel_tipo; ?>>Consultório</option>
                                                                <option value="LABORATORIO_CLINICO" <?php $sel_tipo = ($tipo == 'LABORATORIO_CLINICO') ? 'selected=""' : ''; echo $sel_tipo; ?>>Laboratório Clínico</option>
                                                                <option value="LABORATORIO_IMAGEM" <?php $sel_tipo = ($tipo == 'LABORATORIO_IMAGEM') ? 'selected=""' : ''; echo $sel_tipo; ?>>Laboratório de Imagem</option>
                                                            </select>
                                                            <label for="tipo_local_atendimento">Tipo</label>
                                                        </div>
                                                         &nbsp;
                                                      </div>
                                                    <div class="col-md-7">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>" style="text-transform: uppercase;" maxlength="40"/>
                                                            <label for="nome">Nome Completo do Local</label>
                                                            <span class="help-block">Digite o nome completo do local de atendimento...</span>
                                                         </div>
                                                         &nbsp;
                                                   </div>
                                                   <div class="col-md-2">
                                                             <div class="form-group form-md-radios">
                                                                <label>Conveniado</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input  type="radio" id="conveniado_sim" name="conveniado" class="md-radiobtn" value="S" <?php $sel_conveniado = ($conveniado == 'S') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
                                                                        <label for="conveniado_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_nao" name="conveniado" class="md-radiobtn" value="N" <?php $sel_conveniado = ($conveniado == 'N') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
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
                                                                        <input type="radio" id="ver_local_pagamento_l" name="ver_local_pagamento" class="md-radiobtn" value="LOCAL" <?php $sel_conveniado = ($local_pagamento == 'LOCAL') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
                                                                        <label for="ver_local_pagamento_l">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> NA EMISSÃO </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="ver_local_pagamento" name="ver_local_pagamento" class="md-radiobtn" value="CLINICA/CONSULTORIO" <?php $sel_conveniado = ($local_pagamento == 'CLINICA/CONSULTORIO') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
                                                                        <label for="ver_local_pagamento">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> LOCAL ATENDIMENTO </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             &nbsp;
                                                        </div>
                                                      <div class="col-md-4">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" id="tipo_faturamento" name="tipo_faturamento">
                                                                
                                                                <option value="PRECO_CUSTO" <?php $sel_forma_faturamento = ($forma_faturamento == 'PRECO_CUSTO') ? 'selected=""' : ''; echo $sel_forma_faturamento; ?>>PREÇO DE CUSTO</option>
                                                                <option value="PRECO_FINAL" <?php $sel_forma_faturamento = ($forma_faturamento == 'PRECO_FINAL') ? 'selected=""' : ''; echo $sel_forma_faturamento; ?>>PREÇO FINAL</option>
                                                                <option value="REPASSE_FINAL_NENOS_CUSTO" <?php $sel_forma_faturamento = ($forma_faturamento == 'REPASSE_FINAL_NENOS_CUSTO') ? 'selected=""' : ''; echo $sel_forma_faturamento; ?>>REPASSE: PREÇO FINAL MENOS CUSTO</option>
                                                                
                                                            </select>
                                                            <label for="tipo_faturamento">Forma de Faturamento</label>
                                                        </div>
                                                         &nbsp;
                                                      </div>
                                                        <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="email" class="form-control" id="email" value="<?php echo $email; ?>"/>
                                                            <label for="email">E-mail</label>
                                                            <span class="help-block">Digite o e-mail corretamente...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="cnes" class="form-control" id="cnes" value="<?php echo $numero_cnes; ?>" style="text-transform: uppercase;" maxlength="10"/>
                                                                <label for="cnes">CNES</label>
                                                                <span class="help-block">Cadastro Nacional de Estabelecimentos de Saúde</span>
                                                             </div>
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-4">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="telefone_com" class="form-control" id="telefone_com" value="<?php echo $telefone; ?>" />
                                                            <label for="telefone_com">Telefone (Comercial)</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-4">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="telefone_alt" class="form-control" id="telefone_alt" value="<?php echo $telefone_alt; ?>"/>
                                                            <label for="celular">Telefone (Alternativo)</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                       </div>
                                                       <div class="col-md-4">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="celular" class="form-control" id="celular" value="<?php echo $celular; ?>"/>
                                                            <label for="celular">Telefone (Celular)</label>
                                                            <span class="help-block">Somente números...</span>
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
                                                            <input type="text" name="endereco" class="form-control" id="endereco" value="<?php echo $endereco; ?>" maxlength="40"/>
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
                                                            <input type="text" name="complemento" class="form-control" id="complemento" value="<?php echo $complemento; ?>" style="text-transform: uppercase;"/>
                                                            <label for="complemento">Complemento</label>
                                                            <span class="help-block">Ex.: ap. 526</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="bairro" class="form-control" id="bairro" value="<?php echo $bairro; ?>" maxlength="20"/>
                                                            <label for="bairro">Bairro</label>
                                                             <span class="help-block">LIMITE DE 20 CARACTERES...</span>
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
                                                            <input type="text" name="estado" class="form-control" id="estado" value="<?php echo $estado; ?>" />
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
                                                                <textarea class="form-control" rows="3" id="observacoes_local" name="observacoes_local" ><?php echo $obs; ?></textarea>     
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="md-checkbox-list">
                                                    <div class="md-checkbox">
                                                        <input type="checkbox" name="check_editar_cidades" id="check_editar_cidades" value="S" class="md-check"/>
                                                        <label for="check_editar_cidades">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Editar Cidade(s) </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body form" id="editar_sel_cidades" style="display: none;">
                                            <div class="form-body">
                                            <span>Estado de atendimento: <?php echo $estado; ?></span>
                                            <input type="hidden" name="estado_atendimento" value="<?php echo $estado; ?>"  />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                       <div class="form-actions noborder">
                                                       <span class="div_aguarde" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, buscando cidades...</span>
                                                    </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                    
                                                    <div id="listas_cidades_local">
                                                    <div class="row">
    <div class="col-md-4">
         <div class="form-group form-md-line-input has-info">
            <div class="input-group input-group-lg">
                <div class="input-group-control">
                    <input type="text" name="buscar_nome_cidade" class="form-control" id="buscar_nome_cidade" value="" style="text-transform: uppercase;" maxlength="40"/>
                    <label for="buscar_nome_cidade">Buscar e realçar nomes de cidades</label>
                    <span class="help-block">Digite o nome completo da cidade desejada...</span>
                </div>
                <span class="input-group-btn btn-right">
                    <a href="#" class="btn green-haze" id="bt_buscar_realce_cidades">Buscar</a>
                </span>
            </div>
         </div>
         &nbsp;<br />
         <h5>Lista de Cidades Selecionadas</h5> 
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="lista_cidades_selecionada" style="line-height: 3em;">
    
    <?php
                                                        $sql_par_cidade        = "SELECT cid_loc.loc_nu_sequencial, cid_loc.loc_nosub, cid_loc.ufe_sg FROM gui_cidades_locais g_cid_loc
                                                        JOIN log_localidade cid_loc ON g_cid_loc.loc_nu_sequencial = cid_loc.loc_nu_sequencial
                                                        WHERE g_cid_loc.id_local_atendimento = $id_local_atendimento";
                                                        $query_par_cidade      = mysql_query($sql_par_cidade);
                                                                    
                                                        if (mysql_num_rows($query_par_cidade)>0)
                                                        {
                                                            echo "<div class=\"row\">
                                                                    <div class=\"col-md-12\">";
                                                            $id_cidades_array = array();
                                                            while ($dados = mysql_fetch_array($query_par_cidade))
                                                            {
                                                                extract($dados); 
                                                                $id_cidades_array[] =  $loc_nu_sequencial;
                                                                $nome_cidade_tratado = remove_acento($loc_nosub);
                                                                $nome_cidade_tratado = str_replace(" ", "_", $nome_cidade_tratado);
                                                                echo "<span class=\"sel_cid_sel sel_".strtolower($nome_cidade_tratado)."\"> $loc_nosub - $ufe_sg </span>";
                                             
                                                            }
                                                            
                                                            echo "</div>
                                                                    </div>";
                                                            
                                                        }
    ?>
    </div>
</div>
                                                    <div class="form-group form-md-checkboxes">
<label>Cidades</label>
<div class="md-checkbox-list" id="tabela_lista_cidades">
    <?php
        //$estado   = (empty($_POST['estado'])) ? "" : verifica($_POST['estado']);  
        
        $sql_user_pedido        = "SELECT loc_nu_sequencial, loc_nosub, ufe_sg FROM log_localidade
                                WHERE ufe_sg = '$estado'";
        $query_user_pedido      = mysql_query($sql_user_pedido);
        
        $contar_linhas = mysql_num_rows($query_user_pedido);
        if($contar_linhas>0)
        {
            $divisao_colunas = $contar_linhas / 4;
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
               $html_check_sel_cidade = ''; 
               if (in_array($loc_nu_sequencial, $id_cidades_array)) { 
                    $html_check_sel_cidade = 'checked=""';
                }
                
                if($i == 0){
                    echo "<div class=\"col-md-3\">";
                    $i++; 
                }
                $nome_cidade_tratado = remove_acento($loc_nosub);
                $nome_cidade_tratado = str_replace(" ", "_", $nome_cidade_tratado);
                echo " <div class=\"md-checkbox\" id=\"".strtolower($nome_cidade_tratado)."\">
                <input type=\"checkbox\" name=\"lista_cidades_local[]\" value=\"$loc_nu_sequencial\" id=\"$loc_nu_sequencial\" data=\"".strtolower($nome_cidade_tratado)."\" nome=\"$loc_nosub\" class=\"md-check\" $html_check_sel_cidade/>
                <label for=\"$loc_nu_sequencial\">
                    <span></span>
                    <span class=\"check\"></span>
                    <span class=\"box\"></span> $loc_nosub </label>
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
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>  
                                    <div class="portlet light ">
                                    
                                        <div class="row">
                                                <div class="col-md-12">
                                                    <div id="lista_gui_nome_procedimento">
                                                    <?php
                                                    
                                                    $sql        = "SELECT ap.id_local_procedimento, p.id_procedimento, p.codigo, p.nome'nome_procedimento' FROM gui_local_atendimento_procedimentos ap
                                                    JOIN gui_procedimentos p ON ap.id_procedimento = p.id_procedimento
                                                                WHERE ap.id_local_atendimento = '$id'
                                                                GROUP BY ap.id_procedimento
                                                                ORDER BY p.id_procedimento ASC";
                                                    $query      = mysql_query($sql, $banco_painel);
                                                    
                                                    if (mysql_num_rows($query)>0)
                                                    {
                                                        
                                                        while ($dados = mysql_fetch_array($query))
                                                        {
                                                        extract($dados);  
                                                        echo "<div class=\"portlet box green-meadow\" id=\"grupo_procedimento_$id_procedimento\">
                                                         <input type=\"hidden\" name=\"add_procedimento[]\" value=\"$id_procedimento\">
                                                            <div class=\"portlet-title\"><div class=\"caption\">$codigo - $nome_procedimento</div> <div class=\"tools\"><a href=\"javascript:;\" class=\"expand\" data-original-title=\"\" title=\"\"> </a> <a href=\"javascript:;\" onclick=\"return remove_procedimento_local('$id_procedimento')\" class=\"remove\" data-original-title=\"\" title=\"\"></a></div></div>
                                                            <div class=\"portlet-body portlet-collapsed\"><div class=\"row\">";
                                                        $sql_convenio  = "SELECT c.id_convenio, c.nome'nome_convenio', ap.preco_custo, ap.preco_venda FROM gui_convenios c
                                                        JOIN gui_local_atendimento_procedimentos ap ON ap.id_convenio = c.id_convenio
                                                                        WHERE c.ativo = 'S' AND ap.id_procedimento = $id_procedimento AND ap.id_local_atendimento = $id";
                                                        $query_convenio = mysql_query($sql_convenio) or die(mysql_error()." - 145");
                                                        if (mysql_num_rows($query_convenio)>0)
                                                        {
                                                            $contar_convenios = 0;
                                                            while ($dados_conv = mysql_fetch_array($query_convenio))
                                                            {
                                                                extract($dados_conv);  
                                                                
                                                                echo "<div class=\"col-md-2\">
                                                                <strong>$nome_convenio</strong>
                                                                <input type=\"hidden\" name=\"add_id_convenio[]\"  value=\"$id_convenio\">            
                                                                <div class=\"form-group form-md-line-input form-md-floating-label\">
                                                                    <input type=\"text\" name=\"valor_custo[]\" class=\"form-control\" id=\"valor_custo_$id_convenio\" value=\"$preco_custo\" onkeydown=\"FormataMoeda(this,10,event)\" onkeypress=\"return maskKeyPress(event)\"/>
                                                                    <label for=\"valor_custo\">Valor de Custo</label>
                                                                    <span class=\"help-block\">Apenas números....</span>
                                                                 </div>
                                                                 <div class=\"form-group form-md-line-input form-md-floating-label\">
                                                                    <input type=\"text\" name=\"valor_final[]\" class=\"form-control\" id=\"valor_final_$id_convenio\" value=\"$preco_venda\" onkeydown=\"FormataMoeda(this,10,event)\" onkeypress=\"return maskKeyPress(event)\"/>
                                                                    <label for=\"valor_final\">Valor Final</label>
                                                                    <span class=\"help-block\">Apenas números....</span>
                                                                 </div>
                                                                </div>";
                                                                $contar_convenios++;
                                                            }
                                                            echo "<input type=\"hidden\" name=\"add_contar_convenios[]\" value=\"$contar_convenios\">";
                                                        }
                                                            
                                                        echo "</div> </div></div>";
                                                        }
                                                    }else{
                                                        echo "<strong>Sem resultado!</strong>";
                                                    }
                                                    
                                                    ?>
                                                    
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="click_campo_gui_nome_procedimento"></div>
                                                </div>
                                            </div>
                                            <div class="portlet-title">
                                                <div class="caption font-green">
                                                    <i class="fa fa-plus font-green"></i>
                                                    <span class="caption-subject bold uppercase"> Adicionar Procedimentos | <a href="inc/gui_importar_procedimentos_local.php?id_local_atendimento=<?php echo $id;?>" id="" data-target="#ajax" data-toggle="modal" class="btn btn-sm green"> Importar procedimentos</a> | <a href="javascript:" onclick="window.location.href='inc/gui_exportar_arquivo_procedimentos_local.php?id_local_atendimento=<?php echo $id;?>'" class="btn btn-sm purple"> Exportar procedimentos para Excel</a> &nbsp; <span class="div_aguarde_2" id="div_aguarde_2_dados_procedimento" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span></span>
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
                                                            <div id="remove_procedimentos"></div>
                                                        </div>
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
                                                        <button type="submit" class="btn blue">Salvar</button>
                                                    </div>
                                                </div>
                                            
                                    
                                    <?php
                               }elseif($item == 'gui_convenios' AND in_array("12", $verifica_lista_permissoes_array_inc))
                                {   
                            ?> 
                                    <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Convênio</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group btn-group-devided" >
                                                    <?php
                                                    
                                                    if($nivel_usuario == 'A' AND in_array("13", $verifica_lista_permissoes_array_inc)){
                                                    ?>
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                                                    <i class="fa fa-times"></i> Excluir</a>
                                                    <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Excluir Convênio</h4>
                                                        </div>
                                                        <div class="modal-body"> Tem certeza que deseja excluir o Convenio? A alteração não poderá ser revertida! <br />
                                                        Todos as informações vínculadas ficarão sem convênio!<br />
                                                        <strong>Tem certeza que deseja confirmar?</strong></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="window.location.href='gui_excluir.php?item=gui_convenios&id=<?php echo $id; ?>'"  class="btn green">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                                   <?php
                                                    }else{
                                                    ?>
                                                     <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                     <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>" style="text-transform: uppercase;" maxlength="40"/>
                                                            <label for="nome">Nome do Convênio</label>
                                                            <span class="help-block">Digite o nome convênio...</span>
                                                         </div>
                                                         &nbsp;
                                                   </div>
                                                </div>
                                                    <div class="row">
                                                        <div class="col-md-3 ">
                                                             <div class="form-group form-md-radios">
                                                                <label>Desativar Convenio?</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input  type="radio" id="conveniado_sim" name="ativo" class="md-radiobtn" value="N" <?php $sel_conveniado = ($ativo == 'N') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
                                                                        <label for="conveniado_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_nao" name="ativo" class="md-radiobtn" value="S" <?php $sel_conveniado = ($ativo == 'S') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
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
                                                    
                                            </div>
                                        </div>
                                    </div> <!-- FIM BLOCO -->
                                                        
                                            </div>
                                        </div>
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar</button>
                                                    </div>
                                                </div>
                                          <?php
                               }elseif($item == 'gui_grupo_procedimentos' AND in_array("15", $verifica_lista_permissoes_array_inc))
                                {   
                            ?> 
                                    <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar grupo de Procedimentos</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group btn-group-devided" >
                                                    <?php
                                                    
                                                    if($nivel_usuario == 'A' AND in_array("16", $verifica_lista_permissoes_array_inc)){
                                                    ?>
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                                                    <i class="fa fa-times"></i> Excluir</a>
                                                    <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Excluir Grupo de procedimentos</h4>
                                                        </div>
                                                        <div class="modal-body"> Tem certeza que deseja excluir o Grupo? A alteração não poderá ser revertida! <br />
                                                        Todos as informações vínculadas ficarão sem grupo!<br />
                                                        <strong>Tem certeza que deseja confirmar?</strong></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="window.location.href='gui_excluir.php?item=gui_grupo_procedimentos&id=<?php echo $id; ?>'"  class="btn green">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                                   <?php
                                                    }else{
                                                    ?>
                                                     <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                     <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12 ">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>" style="text-transform: uppercase;" maxlength="40"/>
                                                            <label for="nome">Nome do grupo</label>
                                                            <span class="help-block">Digite o nome grupo...</span>
                                                         </div>
                                                         &nbsp;
                                                   </div>
                                                </div>
                                                    <div class="row">
                                                        <div class="col-md-3 ">
                                                             <div class="form-group form-md-radios">
                                                                <label>Desativar Grupo?</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input  type="radio" id="conveniado_sim" name="ativo" class="md-radiobtn" value="N" <?php $sel_conveniado = ($ativo == 'N') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
                                                                        <label for="conveniado_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_nao" name="ativo" class="md-radiobtn" value="S" <?php $sel_conveniado = ($ativo == 'S') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
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
                                                    
                                            </div>
                                        </div>
                                    </div> <!-- FIM BLOCO -->
                                                        
                                            </div>
                                        </div>
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar</button>
                                                    </div>
                                                </div>
                                      <?php
                               }elseif($item == 'gui_procedimentos' AND in_array("15", $verifica_lista_permissoes_array_inc))
                                {   
                            ?> 
                                    <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Procedimento</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group btn-group-devided" >
                                                    <?php
                                                    
                                                    if($nivel_usuario == 'A' AND in_array("16", $verifica_lista_permissoes_array_inc)){
                                                    ?>
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                                                    <i class="fa fa-times"></i> Excluir</a>
                                                    <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Excluir procedimento</h4>
                                                        </div>
                                                        <div class="modal-body"> Tem certeza que deseja excluir? A alteração não poderá ser revertida! <br />
                                                        Todos as informações vínculadas ficarão sem procedimentos!<br />
                                                        <strong>Tem certeza que deseja confirmar?</strong></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="window.location.href='gui_excluir.php?item=gui_procedimentos&id=<?php echo $id; ?>'"  class="btn green">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                                   <?php
                                                    }else{
                                                    ?>
                                                     <a class="btn default" href="<?php echo "gui_listar.php?item=gui_procedimentos"; ?>" target="_self">
                                                     <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                    <div class="row">
                                                    
                                                    <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="codigo_procedimento" class="form-control" id="codigo_procedimento" value="<?php echo $codigo; ?>"/>
                                                            <label for="codigo_procedimento">#Codigo</label>
                                                            <span class="help-block">Código do procedimento...</span>
                                                         </div>
                                                         &nbsp;
                                                    </div>
                                                    <div class="col-md-6">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>"/>
                                                            <label for="nome">Nome</label>
                                                            <span class="help-block">Como será chamado o procedimento...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                    <?php
                                                $sql_grupo_proc        = "SELECT id_grupo_procedimento'id_grupo_procedimento_sql', nome'nome_grupo_procedimento_sql' FROM gui_grupo_procedimentos
                                                                        WHERE ativo = 'S'";
                                                $query_grupo_proc      = mysql_query($sql_grupo_proc);
                                                                
                                                if(mysql_num_rows($query_grupo_proc)>0)
                                                {
                                                    echo "
                                                    <select class=\"form-control\" data-size=\"8\" id=\"grupo_procedimento\" name=\"grupo_procedimento\" ><option value=\"\"></option>";
                                                    
                                                    while($dados_grupo_proc = mysql_fetch_array($query_grupo_proc))
                                                    {
                                                        extract($dados_grupo_proc);
                                                        $html_select = '';
                                                        if($id_grupo_procedimento == $id_grupo_procedimento_sql){
                                                            $html_select = 'selected=""';
                                                        }
                                                        
                                                        echo "<option value=\"$id_grupo_procedimento_sql\" $html_select>$nome_grupo_procedimento_sql</option>";
                                                    }
                                                    
                                                    echo "</select>";
                                                }
                                                
                                            ?>
                                            <label for="grupo_procedimento">Grupo de Procedimentos</label>
                                            </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                      <div class="col-md-3 ">
                                                            <?php
                                                            if($nivel_usuario == 'A' AND in_array("16", $verifica_lista_permissoes_array_inc)){?>
                                                             <div class="form-group form-md-radios">
                                                                <label>Desativar Procedimemnto?</label>
                                                                <div class="md-radio-inline">
                                                                    <div class="md-radio">
                                                                        <input  type="radio" id="procedimento_sim" name="ativo" class="md-radiobtn" value="N" <?php $sel_procedimento = ($ativo == 'N') ? 'checked=""' : ''; echo $sel_procedimento; ?> />
                                                                        <label for="procedimento_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="procedimento_nao" name="ativo" class="md-radiobtn" value="S" <?php $sel_procedimento = ($ativo == 'S') ? 'checked=""' : ''; echo $sel_procedimento; ?> />
                                                                        <label for="procedimento_nao">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Não </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            }else{
                                                            ?>
                                                            <input type="hidden" name="ativo" value="S" />
                                                            <?php
                                                            }
                                                            ?>
                                                             &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                    <h4>Observações</h4>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <textarea class="form-control" rows="2" id="observacoes_procedimentos" name="observacoes_procedimentos" maxlength="100"><?php echo $obs; ?></textarea>     
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div> <!-- FIM BLOCO -->
                                                        
                                            </div>
                                        </div>
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar</button>
                                                    </div>
                                                </div>
                                    <?php
                                    }elseif($item == 'gui_profissionais' AND in_array("19", $verifica_lista_permissoes_array_inc))
                                {   
                            ?> 
                                    <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Profissional</span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group btn-group-devided">
                                                    <?php
                                                    
                                                    if($nivel_usuario == 'A' AND in_array("26", $verifica_lista_permissoes_array_inc)){
                                                    ?>
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                                                    <i class="fa fa-times"></i> Excluir</a>
                                                    <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Excluir Profissional</h4>
                                                        </div>
                                                        <div class="modal-body"> Tem certeza que deseja excluir o profissional? A alteração não poderá ser revertida! <br />
                                                        Todos as informações vínculadas ficarão sem grupo!<br />
                                                        <strong>Tem certeza que deseja confirmar?</strong></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="window.location.href='gui_excluir.php?item=gui_profissionais&id=<?php echo $id; ?>'"  class="btn green">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                                   <?php
                                                    }else{
                                                    ?>
                                                     <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                     <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        <div class="portlet-body form">
                                           <div class="form-body">
                                                    <div class="row">
                                                    <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <select class="form-control" data-size="8" id="tratamento_profissional" name="tratamento_profissional" >
                                                                <option value=""></option>
                                                                <option value="Dr." <?php $sel_tratamento = ($tratamento == 'Dr.') ? 'selected=""' : ''; echo $sel_tratamento; ?> >Dr.</option>
                                                                <option value="Dra." <?php $sel_tratamento = ($tratamento == 'Dra.') ? 'selected=""' : ''; echo $sel_tratamento; ?>>Dra.</option>
                                                                <option value="Sr." <?php $sel_tratamento = ($tratamento == 'Sr.') ? 'selected=""' : ''; echo $sel_tratamento; ?>>Sr.</option>
                                                                <option value="Sra." <?php $sel_tratamento = ($tratamento == 'Sra.') ? 'selected=""' : ''; echo $sel_tratamento; ?>>Sra.</option>
                                                            </select>
                                                            <label for="tratamento_profissional">Tratamento</label>
                                                            <span class="help-block">do profissional...</span>
                                                         </div>
                                                         &nbsp;
                                                    </div>
                                                    <div class="col-md-6">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>"/>
                                                            <label for="nome">Nome</label>
                                                            <span class="help-block">Nome do profissional...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="dt_nasc_profissional" class="form-control" id="dt_nasc_profissional" value="<?php echo converte_data($data_nascimento); ?>"/>
                                                            <label for="nome">Data de Nascimento</label>
                                                            <span class="help-block">somente números...</span>
                                                         </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="telefone_com" class="form-control" id="telefone_com" value="<?php echo $telefone; ?>" />
                                                            <label for="telefone_com">Telefone</label>
                                                            <span class="help-block">Somente números...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                         <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="celular" class="form-control" id="celular" value="<?php echo $celular; ?>"/>
                                                            <label for="celular">Telefone (Celular)</label>
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
                                                        <div class="col-md-3 ">
                                                             &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">

                                                    <?php
                                                $sql_convenio        = "SELECT id_profissao'id_profissao_sql', nome FROM gui_profissoes
                                                                        WHERE ativo = 'S'";
                                                $query_convenio      = mysql_query($sql_convenio);
                                                                
                                                if(mysql_num_rows($query_convenio)>0)
                                                {
                                                    echo "
                                                    <select class=\"form-control\" data-size=\"8\" id=\"profissao\" name=\"profissao\" ><option value=\"\"></option>";
                                                    
                                                    while($dados_convenio = mysql_fetch_array($query_convenio))
                                                    {
                                                        extract($dados_convenio);
                                                        $html_select = '';
                                                        if($id_profissao == $id_profissao_sql){
                                                            $html_select = 'selected=""';
                                                        }
                                                        
                                                        echo "<option value=\"$id_profissao_sql\" $html_select>$nome</option>";
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
                                                   <div id="linha_especialidade_ativo"></div>
                                                   <?php
                        
                                                    $sql_especialidade        = "SELECT esp_pro.id_especialidade_profissional, esp_pro.id_especialidade, esp_pro.rqe, esp.nome'gui_nome_especialidade' FROM gui_especialidades_profissional esp_pro
                                                    JOIN gui_especialidades esp ON esp_pro.id_especialidade = esp.id_especialidade
                                                    WHERE esp_pro.id_profissional = $id";
                                                    $query_especialidade      = mysql_query($sql_especialidade, $banco_painel);
                                                                    
                                                    if (mysql_num_rows($query_especialidade)>0)
                                                    {
                                                        
                                                        while($dados_especialidade = mysql_fetch_array($query_especialidade)){
                                                            extract($dados_especialidade); 
                                                        
                                                        ?>
                                                            <div class="row" id="linha_especialidade_ativo_<?php echo $id_especialidade_profissional;?>">
                                                                <div class="col-md-12" >
                                                                    <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-12"><strong>Especialidade:</strong></label>
                                                                        <div class="col-md-12">
                                                                            <p class="form-control-static"> <?php echo $gui_nome_especialidade;?> </p>
                                                                        </div>
                                                                    </div>
                                                                     &nbsp;
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                     <div class="form-group">
                                                                        <label class="control-label col-md-12"><strong>RQE:</strong></label>
                                                                        <div class="col-md-12">
                                                                            <p class="form-control-static"> <?php echo $rqe;?> </p>
                                                                        </div>
                                                                    </div>
                                                                     &nbsp;
                                                                    </div>
                                                                    <div class="col-md-2 ">
                                                                     <div class="form-actions noborder">
                                                                     <a href="javascript:" onclick="return acao_remove_especialidade_profissional_ativo('<?php echo $id_especialidade_profissional; ?>');" class="btn btn-sm red btn-outline sbold ">
                                                                        <i class="fa fa-times"></i> Excluir</a>
                                                                     </div>
                                                                     &nbsp;
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                
                                                        <?php    
                                                            
                                                        }
                                                    
                                                    
                                                    }
                                                  ?>
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
                                                                <option value="CRAS" <?php $sel_conselho = ($conselho == 'CRAS') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRAS</option>
                                                                <option value="CRBM" <?php $sel_conselho = ($conselho == 'CRBM') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRBM</option>
                                                                <option value="CREFITO" <?php $sel_conselho = ($conselho == 'CREFITO') ? 'selected=""' : ''; echo $sel_conselho; ?>>CREFITO</option>
                                                                <option value="COREM" <?php $sel_conselho = ($conselho == 'COREM') ? 'selected=""' : ''; echo $sel_conselho; ?>>COREM</option>
                                                                <option value="CRF" <?php $sel_conselho = ($conselho == 'CRF') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRF</option>
                                                                <option value="CRFA" <?php $sel_conselho = ($conselho == 'CRFA') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRFA</option>
                                                                <option value="CRM" <?php $sel_conselho = ($conselho == 'CRM') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRM</option>
                                                                <option value="CRN" <?php $sel_conselho = ($conselho == 'CRN') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRN</option>
                                                                <option value="CRO" <?php $sel_conselho = ($conselho == 'CRO') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRO</option>
                                                                <option value="CRP" <?php $sel_conselho = ($conselho == 'CRP') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRP</option>
                                                                <option value="CRT" <?php $sel_conselho = ($conselho == 'CRT') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRT</option>
                                                                <option value="CRNT" <?php $sel_conselho = ($conselho == 'CRNT') ? 'selected=""' : ''; echo $sel_conselho; ?>>CRNT</option>
                                                            </select>
                                                            <label for="conselho">Conselho</label>
                                                            <span class="help-block">...</span>
                                                         </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="registro" class="form-control" id="registro" value="<?php echo $registro; ?>"/>
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
                                                                        <input type="radio" id="ativo_sim" name="ativo" class="md-radiobtn" value="S" <?php $sel_ativo = ($ativo == 'S') ? 'checked=""' : ''; echo $sel_ativo; ?>/>
                                                                        <label for="ativo_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="ativo_nao" name="ativo" class="md-radiobtn" value="N" <?php $sel_ativo = ($ativo == 'N') ? 'checked=""' : ''; echo $sel_ativo; ?> />
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
                                                                        <input type="radio" id="conveniado_sim" name="conveniado" class="md-radiobtn" value="S" <?php $sel_conveniado = ($conveniado == 'S') ? 'checked=""' : ''; echo $sel_conveniado; ?>/>
                                                                        <label for="conveniado_sim">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Sim </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                        <input type="radio" id="conveniado_nao" name="conveniado" class="md-radiobtn" value="N" <?php $sel_conveniado = ($conveniado == 'N') ? 'checked=""' : ''; echo $sel_conveniado; ?> />
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
                                                                    $convenio_array = explode("|", $ids_convenios);
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
                                                                            $html_select = '';
                                                                            if(in_array($id_convenio, $convenio_array))$html_select = "checked='checked'";
                                                                            
                                                                            echo "<div class=\"md-checkbox\" style=\"    width: 23%;\">
                                                                            <input type=\"checkbox\" id=\"separa_permissao_$id_convenio\" name=\"lista_convenios[]\" class=\"md-check\" value=\"$id_convenio\" $html_select>
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
                                                            <div class="form-group">
                                                            <?php
                                                            
                                                                    $sql_local_sel        = "SELECT id_local_atendimento FROM gui_local_atendimento_profissional 
                                                                    WHERE id_profissional = $id";
                                                                    $query_local_sel      = mysql_query($sql_local_sel, $banco_painel);
                                                            
                                                                    if (mysql_num_rows($query_local_sel)>0)
                                                                    {
                                                                        $locais_array = array();
                                                                        while($row =  mysql_fetch_array($query_local_sel)){
                                                                        $locais_array[] = $row['id_local_atendimento'];
                                                                        
                                                                        }
                                                                        $locais_array_imp = implode('|', $locais_array);
                                                                    }
                                                                    
                                                                    $locais_array_final = explode("|", $locais_array_imp);
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
                                                                            $html_select = '';
                                                                            if(in_array($id_local_atendimento, $locais_array_final))$html_select = "checked='checked'";
                                                                            echo "<div class=\"md-checkbox\" style=\"    width: 23%;\">
                                                                            <input type=\"checkbox\" id=\"separa_permissao_$id_local_atendimento\" name=\"lista_local_atendimento[]\" class=\"md-check\" value=\"$id_local_atendimento\" $html_select>
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
                                                                ?>
                                                            </div>
                                                        </div>
                                                    
                                                    
                                                    </div>
                                                </div>
                                        </div>
                                    </div> <!-- FIM BLOCO -->
                                                        
                                            </div>
                                        </div>
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar</button>
                                                    </div>
                                                </div>
                                      <?php
                                    }elseif($item == 'gui_pacientes' AND in_array("29", $verifica_lista_permissoes_array_inc))
                                {   
                            ?> 
                                    <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <i class="fa fa-plus font-green"></i>
                                                <span class="caption-subject bold uppercase"> Editar Paciente | <a href="inc/gui_importar_cliente_paciente.php" id="" data-target="#ajax" data-toggle="modal" class="btn btn-sm green"> Importar cliente</a> &nbsp; <a href="javascript:" class="btn btn-sm red btn-outline sbold" id="bt_cancela_importacao" onclick="return gui_cancela_importacao();" style="display: none;">
                    <i class="fa fa-times"></i> Cancelar Importação</a></span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group btn-group-devided">
                                                    <?php
                                                    
                                                    if($nivel_usuario == 'A' AND in_array("30", $verifica_lista_permissoes_array_inc)){
                                                    ?>
                                                    <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                    <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                                                    <i class="fa fa-times"></i> Excluir</a>
                                                    <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Excluir Paciente</h4>
                                                        </div>
                                                        <div class="modal-body"> Tem certeza que deseja excluir o paciente? A alteração não poderá ser revertida! <br />
                                                        Todos as informações vínculadas ficarão inativas!<br />
                                                        <strong>Tem certeza que deseja confirmar?</strong></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="window.location.href='gui_excluir.php?item=<?php echo $item; ?>&id=<?php echo $id; ?>'"  class="btn green">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                                   <?php
                                                    }else{
                                                    ?>
                                                     <a class="btn default" href="<?php echo "gui_listar.php?item=$item"; ?>" target="_self">
                                                     <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item" value="<?php echo $item; ?>"/>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>"/>
                                        <div class="portlet-body form">
                                           <div class="form-body">
                                                    <div class="row">
                                                      <div class="col-md-9">
                                                         <div class="form-group form-md-line-input form-md-floating-label">
                                                            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $nome; ?>" />
                                                            <label for="nome">Nome do paciente</label>
                                                            <span class="help-block">Nome completo...</span>
                                                         </div>
                                                         &nbsp;
                                                        </div>
                                                        <div class="col-md-3 ">
                                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                                <input type="text" name="data_nasc" class="form-control" id="data_nasc" value="<?php echo converte_data($data_nascimento); ?>" />
                                                                <label for="data_nasc">Data de Nascimento</label>
                                                                <span class="help-block">Somente números...</span>
                                                            </div>
                                                             &nbsp;
                                                        </div>
                                                        </div>
                                                    <div class="row">
                                                    <div class="col-md-3">
                                                     
                                                   <div class="form-group form-md-line-input form-md-floating-label">
                                                           <input type="hidden" name="convenio_paciente" id="convenio_paciente" value="<?php echo $id_convenio;?>"/>
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
                                                            $sql_lista_convenio        = "SELECT id_convenio'id_convenio_sql', nome FROM gui_convenios
                                                                    WHERE ativo = 'S' 
                                                                    ORDER BY nome";
                                                            $query_lista_convenio      = mysql_query($sql_lista_convenio);
                                                                            
                                                            if(mysql_num_rows($query_lista_convenio)>0)
                                                            {
                                                                while($dados_lista_convenio = mysql_fetch_array($query_lista_convenio))
                                                                {
                                                                    extract($dados_lista_convenio);
                                                                    $class_ = '';
                                                                     if($id_convenio_sql == $id_convenio){
                                                                        $class_ = 'exibe_convenio';
                                                                    }
                                                                    echo "<input type=\"text\" name=\"nome_do_convenio\" class=\"form-control lista_id_imput_convenio id_convenio_$id_convenio_sql $class_\" value=\"$nome\" readonly=\"\"/>";
                                                                   
                                                                } 
                                                            }
                                                            
                                                             ?>
                                                             <label for="nome">Convenio</label>
                                                   </div>
                                                   
                                                </div>
                                                    <div class="col-md-3">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="cpf_paciente" class="form-control cpf" id="cpf_paciente" value="<?php echo $cpf;?>" onkeyup="return verificarCPF(this.value)"/>
                                                        <label for="cpf_paciente">CPF</label>
                                                        <span class="help-block">Somente números..</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-3">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                        <select class="form-control" id="sexo" name="sexo">
                                                            <option value=""></option>
                                                            <option value="M" <?php $sel_sexo = ($sexo == 'M') ? 'selected=""' : ''; echo $sel_sexo; ?>>Masculino</option>
                                                            <option value="F" <?php $sel_sexo = ($sexo == 'F') ? 'selected=""' : ''; echo $sel_sexo; ?>>Feminino</option>
                                                        </select>
                                                        <label for="sexo">Sexo</label>
                                                    </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-3">
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
                                                   <div class="col-md-3">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="comercial" class="form-control" id="comercial" value="<?php echo $comercial; ?>"/>
                                                        <label for="celular">Comercial</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                   </div>
                                                 </div>  
                                                <div class="row">
                                                  <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="cep_paciente" class="form-control" id="cep" value="<?php echo $cep;?>" style="text-transform: uppercase;"/>
                                                        <label for="cep_paciente">CEP</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-7 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="endereco_paciente" class="form-control" id="endereco" value="<?php echo $endereco; ?>" maxlength="50"/>
                                                        <label for="endereco">Endereço</label>
                                                        <span class="help-block">Digite o endereço completo...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-2 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="numero_paciente" class="form-control" id="numero" value="<?php echo $numero; ?>" maxlength="4"/>
                                                        <label for="numero">Número</label>
                                                        <span class="help-block">Somente números...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                </div>
                                                <div class="row">
                                                  <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="complemento_paciente" class="form-control" id="complemento" value="<?php echo $complemento; ?>" style="text-transform: uppercase;" maxlength="30"/>
                                                        <label for="complemento">Complemento</label>
                                                        <span class="help-block">Ex.: ap. 526</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-3 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="bairro_paciente" class="form-control" id="bairro" value="<?php echo $bairro; ?>" maxlength="40"/>
                                                        <label for="bairro">Bairro</label>
                                                         <span class="help-block">LIMITE DE 40 CARACTERES...</span>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-4 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="cidade_paciente" class="form-control" id="cidade" value="<?php echo $cidade; ?>" style="text-transform: uppercase;" maxlength="40"/>
                                                        <label for="cidade">Cidade</label>
                                                     </div>
                                                     &nbsp;
                                                    </div>
                                                    <div class="col-md-2 ">
                                                     <div class="form-group form-md-line-input form-md-floating-label">
                                                        <input type="text" name="estado_paciente" class="form-control" id="estado" value="<?php echo $estado; ?>"/>
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
                                    </div> <!-- FIM BLOCO -->
                                                        
                                            </div>
                                        </div>
                                                <div class="col-md-12" >
                                                    <div class="form-actions noborder">
                                                        <button type="submit" class="btn blue">Salvar</button>
                                                    </div>
                                                </div>
                                      <?php
                               }elseif($item == 'gui_guias_detalhes'){
                                    $sql        = "SELECT gui.*, pag_gui.obs_pagamento FROM gui_guias gui
                                            	JOIN gui_pagamentos_guias pag_gui ON gui.id_guia = pag_gui.id_guia
                                            	WHERE gui.id_guia = $id";
                                    $query      = mysql_query($sql);
                                                    
                                    if (mysql_num_rows($query)>0)
                                    {
                                        $dados = mysql_fetch_array($query);
                                        extract($dados);
                                        //$time = mktime(date('H')-3, date('i'), date('s'));
                                        //$hora = gmdate("H:i:s", $time);
                                        $agora = date('Y-m-d H:i:s');
                                        //$agora = $agora." ".$hora;
                                        $verifica_data_agendamento = false;
                                        $completo_data_agendamento = $data_agendamento." ".$hora_agendamento;
                                        if($status <> 'CANCELADO' AND (strtotime($completo_data_agendamento) > strtotime($agora)) OR ($nivel_usuario == 'A' AND in_array("63", $verifica_lista_permissoes_array_inc) AND $status <> 'CANCELADO')){
                                            $verifica_data_agendamento = true;
                                        } 
                                        
                                        
                                        //date_default_timezone_set('America/Araguaina');
                                        //date_default_timezone_set('UTC');
                                        //date_default_timezone_set('America/Sao_Paulo'); 
                                        //putenv("TZ=America/Sao_Paulo"); // i put this also since previous one also not correctly showing..
                                        //echo date('Y-m-d H:i:s');
                                        
                                        
                                        //echo date('Y-m-d H:i:s', strtotime($completo_data_agendamento));
                                        //echo $agora;
                                        $status_guia = $status; 
                                        $data_emissao_guia = $data_emissao;
                                    }
                                     ?>
                                     <div class="row">
                                    <div class="col-md-12">
                                    <!-- Begin: life time stats -->
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-green">
                                                <span class="caption-subject bold uppercase"> GUIA DE ENCAMINHAMENTO # <?php echo $id; 
                                                $verifica_emitido = false;
                                                if($status == 'EMITIDO' AND $del = 'N' AND !empty($data_emissao) AND $verifica_data_agendamento == true){
                                                    echo " &nbsp; | <a href=\"inc/guia_encaminhamento/?id_guia=$id\" class=\"btn btn-sm green\" target=\"_blank\"> Imprimir guia</a>";
                                                    $verifica_emitido = true;
                                                }
                                                
                                                ?></span>
                                            </div>
                                            <div class="actions">
                                                <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                    <?php
                                                    
                                                    if($nivel_usuario == 'A' AND in_array("37", $verifica_lista_permissoes_array_inc)){
                                                    ?>
                                                    
                                                    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                                                    <i class="fa fa-times"></i> Excluir</a>
                                                    <div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Excluir Guia</h4>
                                                        </div>
                                                        <div class="modal-body"> Tem certeza que deseja excluir a guia de encaminhamento? A alteração não poderá ser revertida! <br />
                                                        <strong>Tem certeza que deseja confirmar?</strong></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="window.location.href='gui_excluir.php?item=<?php echo $item; ?>&id=<?php echo $id; ?>'"  class="btn green">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                                   <?php
                                                    }
                                                    if((in_array("36", $verifica_lista_permissoes_array_inc) AND $status == 'AGENDADO') OR (in_array("65", $verifica_lista_permissoes_array_inc) AND $status == 'EMITIDO') ){
                                                    ?>
                                                    
                                                    <a data-toggle="modal" href="#cancelar" class="btn btn-danger">
                                                    <i class="fa fa-times"></i> Cancelar guia</a>
                                                    <div class="modal fade modal-scroll" id="cancelar" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                    <h4 class="modal-title">Cancelar Guia</h4>
                                                                </div>
                                                                <div class="modal-body"> Tem certeza que deseja cancelar a guia de encaminhamento? A alteração não poderá ser revertida! <br />
                                                                <strong>Tem certeza que deseja confirmar?</strong></div>
                                                               <div class="modal-footer">
                                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                                    <button type="button" onclick="window.location.href='gui_editar_db.php?item=gui_cancelar_guia&id=<?php echo $id; ?>'"  class="btn green">Sim, confirmar!</button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <?php
                                                    }/*else{
                                                        if(in_array("65", $verifica_lista_permissoes_array_inc) AND $status == 'EMITIDO'){
                                                    ?>
                                                     
                                                    <a data-toggle="modal" href="#cancelar" class="btn btn-danger">
                                                    <i class="fa fa-times"></i> Cancelar guia</a>
                                                    <div class="modal fade modal-scroll" id="cancelar" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                    <h4 class="modal-title">Cancelar Guia</h4>
                                                                </div>
                                                                <div class="modal-body"> Não é possível cancelar guia EMITIDA, para cancelar a guia por favor, consulte o Administrador <br />
                                                                </div>
                                                               <div class="modal-footer">
                                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                                    
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    <?php
                                                    }
                                                        }*/
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="item" value="gui_guias_detalhes_confirmar"/>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                        
                                     
                                        <div class="portlet-body form">
                                           <div class="form-body">
                                                <div class="row">
                                                 <div class="col-md-12">
            
        <?php
        
                $status_list = array(
                array("info" => "AGENDADO"),
                array("warning" => "ABERTO"),
                array("danger" => "PENDENTE"),
                array("success" => "EMITIDO"),
                array("danger" => "CANCELADO")
                );
                $ativo = $status;
                if($ativo == 'AGENDADO'){
                    $status_ = $status_list[0];
                }elseif($ativo == 'ABERTO'){
                    $status_ = $status_list[1];
                }elseif($ativo == 'PENDENTE'){
                    $status_ = $status_list[2];
                }elseif($ativo == 'EMITIDO'){
                    $status_ = $status_list[3];
                }elseif($ativo == 'CANCELADO'){
                    $status_ = $status_list[4];
                }
                
                $sql   = "SELECT id_paciente, id_cliente, id_convenio, nome, data_nascimento FROM gui_pacientes
                                  WHERE id_paciente = $id_paciente";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 2");
                $id_paciente = '-';
                $nome_paciente  = '-';
                $data_nascimento= '-';
                $id_cliente = '-';
                if (mysql_num_rows($query)>0)
                {
                    $gui_id_paciente                = mysql_result($query, 0, 'id_paciente');
                    $gui_id_cliente                 = mysql_result($query, 0, 'id_cliente');
                    $gui_id_convenio_paciente       = mysql_result($query, 0, 'id_convenio');
                    $gui_nome_paciente              = mysql_result($query, 0, 'nome');
                    $gui_data_nascimento            = mysql_result($query, 0, 'data_nascimento');
                }
                
                $sql   = "SELECT nome, tipo, endereco, numero, complemento, bairro, cidade, estado, obs, local_pagamento FROM gui_local_atendimento
                WHERE id_local_atendimento = $id_local_atendimento AND ativo = 'S'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 3");
                $nome_local_atendimento = '-';
                $tipo_local_atendimento         = '';
                $endereco_local_atendimento     = '';
                $numero_local_atendimento       = '';
                $complemento_local_atendimento  = '';
                $bairro_local_atendimento       = '';
                $cidade_local_atendimento       = '';
                $estado_local_atendimento       = '';
                $obs_local_atendimento          = '';
                
                if (mysql_num_rows($query)>0)
                {
                    $nome_local_atendimento         = mysql_result($query, 0, 'nome');
                    $tipo_local_atendimento         = mysql_result($query, 0, 'tipo');
                    $endereco_local_atendimento     = mysql_result($query, 0, 'endereco');
                    $numero_local_atendimento       = mysql_result($query, 0, 'numero');
                    $complemento_local_atendimento  = mysql_result($query, 0, 'complemento');
                    $bairro_local_atendimento       = mysql_result($query, 0, 'bairro');
                    $cidade_local_atendimento       = mysql_result($query, 0, 'cidade');
                    $estado_local_atendimento       = mysql_result($query, 0, 'estado');
                    $obs_local_atendimento          = mysql_result($query, 0, 'obs');
                    $local_pagamento                = mysql_result($query, 0, 'local_pagamento');
                }
                
                $sql   = "SELECT nome FROM parceiros
                                    WHERE id_parceiro = $id_parceiro AND del = 'N'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 4");
                $nome_parceiro = 'Sem Parceiro';
                if (mysql_num_rows($query)>0)
                {
                    $nome_parceiro = mysql_result($query, 0, 'nome');
                }
                
                $sql   = "SELECT nome FROM usuarios
                                    WHERE id_usuario = $id_usuario AND del = 'N'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 5");
                $nome_user_cadastro = 'Sem Usuario';
                if (mysql_num_rows($query)>0)
                {
                    $nome_user_cadastro = mysql_result($query, 0, 'nome');
                }
                
                if(!empty($id_usuario_emissao)){
                    $sql   = "SELECT nome FROM usuarios
                                    WHERE id_usuario = $id_usuario_emissao AND del = 'N'";
                    $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 5");
                    $nome_user_emissao = 'Sem Usuario';
                    if (mysql_num_rows($query)>0)
                    {
                        $nome_user_emissao = mysql_result($query, 0, 'nome');
                    }
                }
                
                
                $sql   = "SELECT nome FROM gui_convenios
                        WHERE id_convenio = $gui_id_convenio_paciente AND ativo = 'S'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 6");
                $nome_convenio = 'Sem Convenio';
                if (mysql_num_rows($query)>0)
                {
                    $nome_convenio = mysql_result($query, 0, 'nome');
                }
                
                $sql   = "SELECT nome FROM gui_convenios
                        WHERE id_convenio = $id_convenio AND ativo = 'S'";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 6");
                $nome_convenio_guia = 'Sem Convenio';
                if (mysql_num_rows($query)>0)
                {
                    $nome_convenio_guia = mysql_result($query, 0, 'nome');
                }
                
                $sql   = "SELECT nome, tratamento, conselho, registro FROM gui_profissionais
                                  WHERE id_profissional = $id_profissional";
                $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - 2");
                $nome_profissonal        = '-';
                $tratamento_profissional = '-';
                if (mysql_num_rows($query)>0)
                {
                    $nome_profissonal           = mysql_result($query, 0, 'nome');
                    $tratamento_profissional    = mysql_result($query, 0, 'tratamento');
                    $conselho                   = mysql_result($query, 0, 'conselho');
                    $registro                   = mysql_result($query, 0, 'registro');
                }
            ?>
                <input type="hidden" name="id_pagamento" value="<?php echo $id_pagamento; ?>"/>
                <input type="hidden" name="local_pagamento" value="<?php echo $local_pagamento; ?>"/>
                <input type="hidden" name="data_agendamento" value="<?php echo $data_agendamento; ?>"/>
                <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Agendado para:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                            $diasemana_numero = date('w', strtotime($data_agendamento));
                            echo "<span id=\"dia_da_semana\">$diasemana[$diasemana_numero]</span>, dia <span id=\"dia_do_novo_agendamento\">".converte_data($data_agendamento)."</span> às <span id=\"hora_do_novo_agendamento\">$hora_agendamento</span>";
                            
                            if(in_array("32", $verifica_lista_permissoes_array_inc) AND empty($data_emissao) AND $status == 'AGENDADO'){
                             ?> <a data-toggle="modal" href="#editar_data_agendamento" class="btn btn-icon-only red" style="margin-left: 10px;">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a><div class="modal fade modal-scroll" id="editar_data_agendamento" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Editar data de agendamento</h4>
                                                        </div>
                                                        <div class="modal-body"><div class="row">&nbsp;</div><br /> <div class="row">
                                                       
                                                          <div class="col-md-6">
                                                             <div class="form-group">
                                                        <label class="control-label col-md-12">Data de agendamento</label>
                                                        <div class="col-md-3">
                                                            <div class="input-group input-medium date date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
                                                                <input type="text" class="form-control" value="<?php echo converte_data($data_agendamento); ?>" name="alterar_data_agendamento" id="alterar_data_agendamento" readonly  />
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
                                                        <label class="control-label col-md-12">Horário de agendamento</label>
                                                        <div class="col-md-6">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control timepicker timepicker-24" name="alterar_horario_agendamento" id="alterar_horario_agendamento" value="<?php echo $hora_agendamento; ?>"/>
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
                                                    </div><br /><div class="row">&nbsp;</div></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="return editar_data_agendamento(<?php echo $id; ?>)"  class="btn green">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div><?php } ?></p>
                                            
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Status:</strong></label>
                    <div class="col-md-12">
                        <span class="label label-sm label-<?php echo key($status_); ?>"><?php echo $ativo; ?></span></div>
                    </div>
                 </div>
                 <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Data Cadastro:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo converte_data($data_cadastro) ?></p>
                        </div>
                    </div>
                </div>
                <?php
                if(!empty($data_emissao)){
                    echo "<div class=\"col-md-3\">
                    <div class=\"form-group\">
                        <label class=\"control-label col-md-12\"><strong>Data Emissão:</strong></label>
                        <div class=\"col-md-12\">
                            <p class=\"form-control-static\"> ".converte_data($data_emissao)."</p>
                        </div>
                    </div>
                </div>";
                }
                
                ?>
                </div>
                <hr />
                <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Convênio:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_convenio; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>ID:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $gui_id_paciente; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Paciente:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"><?php echo $gui_nome_paciente; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nasc.:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"><?php echo converte_data($gui_data_nascimento) ?></p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                 <div class="col-md-2">
                     <div class="form-group">
                        <label class="control-label col-md-12"><strong>Ação:</strong></label>
                            <div class="col-md-12">
                                <a href="gui_editar.php?item=gui_pacientes&id=<?php echo $gui_id_paciente; ?>" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>
                        </div>
                     </div>
                 </div>
                 <div class="col-md-12">
                 
                 <?php      
                            
                            $verificar_cliente = true;
                            if($gui_id_convenio_paciente <> 5){
                                
                                $id_servico = 2;
                                $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                                JOIN servicos serv ON pro.id_servico = serv.id_servico
                                                        WHERE serv.id_servico = $id_servico
                                GROUP BY serv.id_servico ";
                    
                                $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()."$sql_base");
                    
                                if (mysql_num_rows($query_base)>0)
                                {
                                    $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                                    $banco_user             = mysql_result($query_base, 0, 'banco_user');
                                    $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                                    $banco_host             = mysql_result($query_base, 0, 'banco_host');
                                    $slug                   = mysql_result($query_base, 0, 'slug');
                                    $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                                    
                                    $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                                }

                                $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);  
                                
                                
                                
                                
                                
                                if($slug == 'europ'){
                                
                                $sql    = "SELECT * FROM clientes 
                                        WHERE tipo_movimento IN ('IN', 'AL') AND id_cliente = $gui_id_cliente
                                        GROUP BY chave
                                        ORDER BY id_cliente DESC";    
                                $query      = mysql_query($sql, $banco_produto);
                            
                                if (mysql_num_rows($query)>0)
                                {
                                    $dados = mysql_fetch_array($query);
                                    extract($dados);   
                                    
                                    $status_list = array(
                                    array("success" => "Ativo"),
                                    array("danger" => "Inativo")
                                    );
                
                                    $data_nascimento = converte_data($data_nascimento);
                                    //$data_termino    = converte_data_barra($data_termino);
                                    $idade = calcula_idade($data_nascimento);
                                    $status_cliente = $status;
                                    // status
                                    // Comparando as Datas
                                    //$convert_data_termino = converte_data($data_termino);
                                    $agora 			= date("Y-m-d");
                                    $vencido = '';
                                    if((strtotime($data_termino) < strtotime($agora) AND $data_termino <> '0000-00-00') AND $tipo_movimento <> 'EX'){
                                            $vencido = '<span class="label label-sm label-warning">V</span>';
                                            $verificar_cliente = false;
                                    }
                                        
                                    $data_verif = somar_datas( 1, 'm'); // adiciona meses a sua data          
                                    $data_restante = date('d/m/Y', strtotime($data_verif));
                                    $data_restante = converte_data($data_restante);
                                    if(strtotime($data_termino) <= strtotime($agora) AND $data_termino != '0000-00-00'){
                                        $vencido = '<span class="label label-sm label-warning">V</span>';
                                        $verificar_cliente = false;
                                    }
                                        
                                    if((strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0))
                                    {
                                        $status = $status_list[0];
                                    }
                                    elseif((strtotime($data_termino) == strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0))
                                    {
                                        $status = $status_list[0];
                                    }
                                    else
                                    {
                                        $status = $status_list[1];
                                        $verificar_cliente = false;
                                    }
                                    
                                    $depen = '';
                                    if($id_cliente_principal > 0)
                                    {
                                        $depen = '<span class="label label-sm label-info">A</span>';
                                    } 
                                    
                                    $penden = '';
                                    if($status_cliente == 3)
                                    {
                                        $penden = '<span class="label label-sm label-danger">P</span>';
                                    }  
                                    
                                    
                                    if($status_cliente == 5)
                                    {
                                        $vencido = '<span class="label label-sm label-warning">V</span>';
                                        $status = $status_list[0];
                                        $verificar_cliente = false;
                                    } 
                                    
                                    
                                    
                                    echo '<p><span class="label label-sm label-'.(key($status)).'">('.$tipo_movimento.') '.(current($status)).'</span>'.$depen.' '.$penden.' '.$vencido.'</p>';  
                                    
                                    
                                    
                                    $sql    = "SELECT id_ordem_pedido FROM vendas 
                                        WHERE id_cliente = $gui_id_cliente AND status_pedido = 'Pago'
                                        GROUP BY id_ordem_pedido";    
                                    $query      = mysql_query($sql, $banco_produto);
                                
                                    if (mysql_num_rows($query)>0)
                                    {
                                        $id_ordem_pedido = mysql_result($query, 0, 'id_ordem_pedido');
                                        //$agora 			= date("Y-m-d");
                                        $data_verif = somar_datas('-5', 'd'); // adiciona meses a sua data      
                                        $data_restante = date('d/m/Y', strtotime($data_verif));
                                        $data_restante = converte_data($data_restante);
                                        $sql    = "SELECT data_vencimento FROM boletos_clientes 
                                        WHERE id_ordem_pedido = $id_ordem_pedido AND pago = 'N' AND data_vencimento < '$data_restante' AND status_boleto = 0";    
                                        $query      = mysql_query($sql, $banco_painel);
                                    
                                        if (mysql_num_rows($query)>0)
                                        {
                                            
                                            $data_verif = somar_datas('-30', 'd'); // adiciona meses a sua data      
                                            $data_restante = date('d/m/Y', strtotime($data_verif));
                                            $data_restante = converte_data($data_restante);
                                            $sql    = "SELECT data_vencimento FROM boletos_clientes 
                                            WHERE id_ordem_pedido = $id_ordem_pedido AND pago = 'N' AND data_vencimento < '$data_restante' AND status_boleto = 0";    
                                            $query      = mysql_query($sql, $banco_painel);
                                        
                                            if (mysql_num_rows($query)>0)
                                            {
                                                echo "<div class=\"alert alert-danger\">
                                                <strong>Error!</strong> Cliente com pendências de +30 dias vencidos, por favor, verificar! </div>";
                                                $verificar_cliente = false;
                                            }else{
                                                echo "<div class=\"alert alert-danger\">
                                                <strong>Error!</strong> Cliente com pendências de pagamento com +5 dias vencidos e -30 dias, avise o cliente para regulalização dos débitos.</div>";
                                            }
                                            
                                                
                                        
                                        }
                                        
                                        
                                    }
                                    
                                    
                                }
                                        
                                        
                                        
                                        
                                
                                }
                                
                                
                                
                                
                                
                                
                                
                            }
                            
                            
                            
                            
                            
                            ?>
                 
                 </div>
                <!--/span-->
                </div>
                <hr />
                 <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Local de atendimento:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $tipo_local_atendimento." - ".$nome_local_atendimento; ?> </p>
                            <input type="hidden" name="sel_id_local_atendimento" id="sel_id_local_atendimento" value="<?php echo $id_local_atendimento; ?>" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Endereço:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php 
                            echo $endereco_local_atendimento.", ".$numero_local_atendimento." - ".$complemento_local_atendimento.", ".$bairro_local_atendimento.", ".$cidade_local_atendimento." - ".$estado_local_atendimento   ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="alert alert-info">
                        <strong>Informação de pagamento: </strong> <?php 
                        
                        if($local_pagamento == 'LOCAL'){
                            echo "NA EMISSÃO DESTA GUIA";
                        }else{
                            echo "NO LOCAL DE ATENDIMENTO";
                        }
                        
                        ?> </div>
                    <div class="alert alert-warning">
                        <strong>Observações!</strong>
                        <p><?php echo $obs_local_atendimento; ?></p>
                        <hr />
                        <p><?php echo $obs_guia; ?></p> 
                        <hr />
                        <p><?php echo $obs_pagamento; ?></p> 
                        </div>
                </div>
                <!--/span-->
                </div>
           <hr />
           <h4>Solicitante responsavel</h4>
                <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Parceiro:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_parceiro; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
               
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Usuário:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_user_cadastro; ?> </p>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Responsável pela liberação:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo  $nome_user_emissao; ?> </p>
                            
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <hr />
            <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Conselho / Registro:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $conselho.": ".$registro ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Profissional:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $tratamento_profissional." ".$nome_profissonal; ?> </p>
                        <?php
                            if(in_array("32", $verifica_lista_permissoes_array_inc) AND empty($data_emissao_guia) AND $status_guia == 'AGENDADO'){
                             ?> <a data-toggle="modal" href="#editar_profissonal" class="btn btn-icon-only red" style="margin-left: 10px;">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a><div class="modal fade modal-scroll" id="editar_profissonal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                            <h4 class="modal-title">Editar Profissional</h4><span id="div_aguarde_2_dados_profissional" style="display: none;" ><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span> 
                                                        </div>
                                                        <div class="modal-body"><div class="row">&nbsp;</div><br /> <div class="row">
                                                       
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
                                                    </div><br /><div class="row">
                                                        
                                                        <div class="col-md-12">
                                                         <input type="hidden" name="id_profissional_sel" id="id_profissional_sel" value=""/>
                                                            <div id="resultado_campo_gui_nome_profissional"></div>
                                                        </div>
                                                    </div><div class="row">&nbsp;</div></div>
                                                       <div class="modal-footer">
                                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                                                            <button type="button" onclick="return editar_profissional_guia(<?php echo $id; ?>)"  class="btn green" id="bt_confirmar_alterar_profissional_guia">Sim, confirmar!</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div><?php } ?>
                    </div>
                </div>
            </div>
            </div>
            <hr />
            <hr />
            <div class="row">
            <div class="col-md-12">
            <table class="table table-hover table-light">
            <thead>
                <tr class="uppercase">
                    <th> # </th>
                    <th> Descrição </th>
                    <th> Convênio </th>
                    <?php
                        if(in_array("42", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <th> Custo </th>
                    <?php
                        }
                    ?>
                    <th> Valor </th>
                </tr>
            </thead>
            <tbody>
                
           
                <?php
                    $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";
                    $query_procedimentos      = mysql_query($sql_procedimentos, $banco_painel);
                                
                if (mysql_num_rows($query_procedimentos)>0)
                {  
                    $soma_procedimentos = 0;
                    $soma_preco_custo = 0;
                    while ($dados = mysql_fetch_array($query_procedimentos))
                    {
                        extract($dados); 
                        $preco_custo_calc = moeda_db($preco_custo);
                        
                        $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                        $soma_procedimentos = $soma_procedimentos + $valor_cobrado;
                        echo "<tr>
                                <td> $codigo_procedimnto </td>
                                <td> $nome_procedimento </td>
                                <td> $nome_convenio_guia </td>";
                                
                                if(in_array("42", $verifica_lista_permissoes_array_inc)){
                                    echo "<td> R$ $preco_custo </td>";
                                }
                                   
                                echo "<td> ".db_moeda($valor_cobrado)." </td>
                              </tr>";
                    }
                    ?>
                        </tr>
                      </tbody>
                    </table>
                    <div style="text-align: right;">
                    <?php
                        if(in_array("42", $verifica_lista_permissoes_array_inc)){
                        ?>
                        <div>Total de Custos: <strong><?php echo db_moeda($soma_preco_custo);?></strong></div>
                        <?php
                        }
                        ?>
                        <div>Total: <strong><?php echo db_moeda($soma_procedimentos);?></strong></div>
                        
                        <?php 
                        
                        if($desconto > 0){
                        ?>
                            <div>Desconto de: <strong><?php echo $html_desconto = ($tipo_desconto == 'por') ? $desconto."%" : db_moeda($desconto);?></strong></div>
                            <div>Novo valor total com desconto: <strong><?php echo db_moeda($valor_total_desconto);?></strong></div>

                        <?php
                        } 
                         
                }
            ?> 
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
                                                <?php 
                                                
                                                if(($verificar_cliente == true OR ($nivel_usuario == 'A') AND in_array("46", $verifica_lista_permissoes_array_inc)) AND $verifica_emitido == false AND $status <> 'CANCELADO'){
                                                    if($verifica_data_agendamento == true){
                                                        echo "<button type=\"submit\" class=\"btn  btn-lg blue\">Confirmar Emissão</button>";
                                                    }else{
                                                        //if($verifica_emitido == false){
                                                            echo "Ops! Têm algo errado, por favor, verifique as seguintes informações: <strong>Data de agendamento vencida, cliente com pendência ou guia cancelada.</strong>";
                                                        //}
                                                        
                                                    }
                                                    
                                                    
                                                }else{
                                                    if($verifica_emitido == false AND $status <> 'CANCELADO'){
                                                        echo "<p>Será necessário verificar pendência do cliente para emitir esta guia!</p>"; 
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
        <script src="assets/pages/scripts/editar.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/form-validation-md.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
         <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
          <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>       
         <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>
        <!--<script src="assets/pages/scripts/ui-idletimeout.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
