<!-- BEGIN TOP NAVIGATION MENU -->
    <div class="top-menu">
        <ul class="nav navbar-nav pull-right">
            
            <!-- BEGIN USER LOGIN DROPDOWN -->
            <li class="dropdown dropdown-user dropdown-dark">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <?php
                    $id_parceiro_logo = base64_decode($_COOKIE["usr_parceiro"]);
                    $sql_logo_img       = "SELECT logo FROM parceiros WHERE id_parceiro = $id_parceiro_logo";
                    $query_logo_im      = mysql_query($sql_logo_img) or die(mysql_error());
                    
                    if ($query_logo_im)
                    {
                        $logo_img         = mysql_result($query_logo_im, 0, 'logo');
                    }
                    else
                    {
                        $logo_img = "avatar.png";
                    }
                $id_user_sessao_top = md5(base64_decode($_COOKIE["usr_id"]));
                ?>
                
                    <img alt="" class="img-circle" src="assets/pages/img/logos/<?php echo $logo_img; ?>"/>
                    <span class="username username-hide-mobile"><?php echo primeiro_nome(base64_decode($_COOKIE["usr_nome"])); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-default">
                    <li>
                        <a href="editar.php?item=usuarios&id=<?php echo $id_user_sessao_top; ?>&tipo=usuario">
                            <i class="icon-user"></i> Perfil </a>
                    </li>
                    
                    <?php
                    $user_id_acesso = base64_decode($_COOKIE["usr_id"]);
                    $sql_permissoes        = "SELECT id_usuario_acesso FROM usuarios_vinculados
WHERE id_usuario = $user_id_acesso";
                    $query_permissoes      = mysql_query($sql_permissoes);
                                
                    if (mysql_num_rows($query_permissoes)>0)
                    {
                        
                        while($dados_permissoes = mysql_fetch_array($query_permissoes))
                        {
                            extract($dados_permissoes);
                            $sql_vinculados        = "SELECT nome FROM usuarios
WHERE ativo = 'S' AND del = 'N' AND id_usuario = $id_usuario_acesso";
                            $query_vinculados      = mysql_query($sql_vinculados);
                                        
                            if (mysql_num_rows($query_vinculados)>0)
                            {
                                $nome_user_acesso = mysql_result($query_vinculados, 0, 'nome');
                                
                                echo "<li>
                                    <a href=\"inc/topnav_acesso.php?id_user_acesso=$id_usuario_acesso&tipo=acesso_rapido\" data-target=\"#ajax_senha\" data-toggle=\"modal\">
                                        <i class=\"fa fa-exchange\"></i> $nome_user_acesso </a>
                                </li>";
                            }
                            
                        }
                    }
                    ?>
                    
                    
                    <li>
                        <a href="logout.php">
                            <i class="icon-key"></i> Sair </a>
                    </li>
                </ul>
            </li>
            <!-- END USER LOGIN DROPDOWN -->
        </ul>
        <div class="modal fade modal-scroll" id="ajax_senha" role="basic" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                        <span> &nbsp;&nbsp;Aguarde... </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END TOP NAVIGATION MENU -->