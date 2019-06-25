<ul class="nav navbar-nav">
    <li  class="menu-dropdown classic-menu-dropdown <?php echo (empty($item) || $item == 'inicio') ? "active" : ""; ?>" >
        <a href="inicio.php"> Painel
            <!--<span class="arrow"></span>-->
        </a>
    </li>
    
    <?php
       if(base64_decode($_COOKIE["usr_nivel"]) == "A" AND base64_decode($_COOKIE["nivel_status"]) == 0 AND in_array("1", $verifica_lista_permissoes_array_inc))
        {
    ?>
            <li class="menu-dropdown classic-menu-dropdown <?php echo ($item == 'parceiros') ? "active" : ""; ?> <?php echo ($item == 'grupos_parceiros') ? "active" : ""; ?>">
                <a href="listar.php?item=parceiros"> Parceiros
                    
                </a>
            </li>
    <?php
       }
    ?>
    
    <?php
       if(in_array("6", $verifica_lista_permissoes_array_inc))
        {
            $item_array = explode("_", $item);
    ?>
            <li class="menu-dropdown classic-menu-dropdown <?php echo ($item_array[0] == 'gui') ? "active" : ""; ?> <?php echo ($item == 'guias') ? "active" : ""; ?>">
                <a href="gui_listar.php?item=guias"> Guias <span class="arrow"></span> </a>
                <ul class="dropdown-menu pull-left">
                    <li> <a href="gui_listar.php?item=gui_busca_procedimentos"> <i class="fa fa-search"></i> BUSCAR VALORES</a> </li>
                    <?php
                    if(in_array("38", $verifica_lista_permissoes_array_inc))
                        {
                            echo '<li> <a href="gui_adicionar.php?item=gui_guias"/> <i class="fa fa-plus"></i> NOVA GUIA</a> </li>';
                        }
                    ?>
                    <li class="<?php echo ($item == 'gui_local_atendimento') ? "active" : ""; ?>">
                        <a href="gui_listar.php?item=gui_local_atendimento" class="nav-link <?php echo ($item == 'gui_local_atendimento') ? "active" : ""; ?> "> Local de atendimento </a>
                    </li>
                    <?php  
                    if(in_array("9", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <li class="<?php echo ($item == 'gui_convenios') ? "active" : ""; ?>">
                        <a href="gui_listar.php?item=gui_convenios" class="nav-link <?php echo ($item == 'gui_convenios') ? "active" : ""; ?> "> Convênios </a>
                    </li>
                    <?php
                    }
                    ?>
                    <li class="<?php echo ($item == 'gui_procedimentos') ? "active" : ""; ?> ">
                        <a href="gui_listar.php?item=gui_procedimentos" class="nav-link <?php echo ($item == 'gui_procedimentos') ? "active" : ""; ?>"> Procedimentos </a>
                    </li>
                     <?php
                    if(in_array("17", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <li class="<?php echo ($item == 'gui_profissionais') ? "active" : ""; ?>">
                        <a href="gui_listar.php?item=gui_profissionais" class="nav-link <?php echo ($item == 'gui_profissionais') ? "active" : ""; ?> "> Profissionais </a>
                    </li>
                    <?php
                    }
                    if(in_array("27", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <li class="<?php echo ($item == 'gui_pacientes') ? "active" : ""; ?>">
                        <a href="gui_listar.php?item=gui_pacientes" class="nav-link <?php echo ($item == 'gui_pacientes') ? "active" : ""; ?> "> Pacientes </a>
                    </li>
                    <?php
                    }
                    if(in_array("31", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <li class="<?php echo ($item == 'gui_guias') ? "active" : ""; ?>">
                        <a href="gui_listar.php?item=gui_guias" class="nav-link <?php echo ($item == 'gui_guias') ? "active" : ""; ?> "> Guias </a>
                    </li>
                    <?php
                    }
                    if(in_array("3", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <li class="<?php echo ($item == 'gui_pagamentos_guia') ? "active" : ""; ?>">
                        <a href="gui_listar.php?item=gui_pagamentos_guia" class="nav-link <?php echo ($item == 'gui_pagamentos_guia') ? "active" : ""; ?> "> Pagamentos </a>
                    </li>
                    <?php
                    }
                    if(in_array("45", $verifica_lista_permissoes_array_inc)){
                    ?>
                    <li class="<?php echo ($item == 'gui_relatorios') ? "active" : ""; ?>">
                        <a href="gui_listar.php?item=gui_relatorios" class="nav-link <?php echo ($item == 'gui_relatorios') ? "active" : ""; ?> "> Relatórios </a>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
            </li>
    <?php
       }
       
       if(in_array("55", $verifica_lista_permissoes_array_inc) AND in_array("56", $verifica_lista_permissoes_array_inc)){
        }else{
    ?>
            <li class="menu-dropdown classic-menu-dropdown <?php echo ($item == 'produtos') ? "active" : ""; ?> <?php echo ($item == 'clientes') ? "active" : ""; ?>">
                <a href="javascript:;"> Clientes
                    <span class="arrow"></span>
                </a>
                <?php
                    
                    $id_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);
                     if(base64_decode($_COOKIE["usr_nivel"]) == "A")
                    {
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
                        
                        echo '<ul class="dropdown-menu pull-left">';
                        if(in_array("56", $verifica_lista_permissoes_array_inc)){  
                        }else{
                            if(base64_decode($_COOKIE["usr_nivel"]) != "A")
                            {
                                echo '<li> <a href="adicionar.php?item=clientes&tipo=produto"/> <i class="fa fa-plus"></i> Incluir Venda </a> </li>';
                            }
                        }
                        if(in_array("55", $verifica_lista_permissoes_array_inc)){
                        }else{
                            while ($dados = mysql_fetch_array($query))
                            {
                                extract($dados);                                                   
                                $item_menu = ($id_serv_get == $id_servico) ? "active" : "";                    
                                echo '<li class="dropdown '.$item_menu.'">
                            <a href="listar.php?item=clientes&id_serv='.$id_servico.'&id=todos&tipo=produto" class="nav-link nav-toggle';
                            echo (empty($item) || $item == "produtos") ? "active" : "";
                            echo '"><i class="fa fa-search"></i> '.$nome_servico.' <!--<span class="arrow"></span>--></a>';
                            
                            /*$sql_2        = "SELECT id_produto, nome'nome_produto' FROM produtos
                                            WHERE id_servico = $id_servico AND ativo = 'S'
                                            ORDER BY nome";
                            $query_2      = mysql_query($sql_2);
                        
                            if (mysql_num_rows($query_2)>0)
                            {
                                echo '<ul class="dropdown-menu">
                                        ';
                                while ($dados_2 = mysql_fetch_array($query_2))
                                {
                                    extract($dados_2);   
                                    $item_menu = ($id == $id_produto) ? "active" : "";          
                                    echo '<li class="'.$item_menu.'">
                                                <a href="listar.php?item=clientes&id_serv='.$id_servico.'&id='.$id_produto.'&tipo=produto" class="nav-link';
                                                echo (empty($item) || $item == 'clientes') ? "active" : ""; 
                                                echo '">'.$nome_produto.'</a>
                                            </li>
                                        
                                    ';
                                }
                                echo '</ul>';
                                
                            }*/
                            echo '</li>';
       
                            }
                        }
                        
                        
                        echo '</ul>';
                    }
                    
                ?>
                
                   
               
            </li>
    
     <?php
        }
        if(in_array("3", $verifica_lista_permissoes_array_inc))
        {
    ?>
    <li class="menu-dropdown classic-menu-dropdown <?php echo ($item == 'pagamentos_clientes') ? "active" : ""; ?>">
        <a href="listar.php?item=pagamentos_clientes&tipo=cliente"> Pagamentos
    <!--<span class="arrow"></span>-->
        </a>
       
       <!-- <ul class="dropdown-menu pull-left">
            <li class=" ">
                <a href="listar.php?item=boletos_clientes&tipo=cliente" class="nav-link <?php echo ($item == 'boletos_clientes') ? "active" : ""; ?> "> Listar todos </a>
            </li>
            <li class=" ">
                <a href="listar.php?item=boletos_vencidos_clientes&tipo=cliente" class="nav-link <?php echo ($item == 'boletos_clientes') ? "active" : ""; ?> "> Vencidos </a>
            </li>
            <li class=" ">
                <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente" class="nav-link <?php echo ($item == 'boletos_clientes') ? "active" : ""; ?> "> À vencer no mês </a>
            </li>
            <li class=" ">
                <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente" class="nav-link <?php echo ($item == 'boletos_clientes') ? "active" : ""; ?> "> Pagos no mês </a>
            </li>
            
        </ul>
        -->
    </li>
     <?php
        }
        if(in_array("70", $verifica_lista_permissoes_array_inc))
        {
    ?>
    <li class="menu-dropdown classic-menu-dropdown <?php echo ($item == 'est_estoque') ? "active" : ""; ?>">
        <a href="est_listar.php?item=est_estoque"> Estoque
    <span class="arrow"></span>
        </a>
       <ul class="dropdown-menu pull-left">
            <li class=" ">
                <a href="est_listar.php?item=est_movimentacoes" class="nav-link <?php echo ($item == 'est_movimentacoes') ? "active" : ""; ?> "> Movimentações </a>
            </li>
            <!--<li class=" ">
                <a href="listar.php?item=boletos_vencidos_clientes&tipo=cliente" class="nav-link <?php echo ($item == 'boletos_clientes') ? "active" : ""; ?> "> Vencidos </a>
            </li>
            <li class=" ">
                <a href="listar.php?item=boletos_avencermes_clientes&tipo=cliente" class="nav-link <?php echo ($item == 'boletos_clientes') ? "active" : ""; ?> "> À vencer no mês </a>
            </li>
            <li class=" ">
                <a href="listar.php?item=boletos_pagosmes_clientes&tipo=cliente" class="nav-link <?php echo ($item == 'boletos_clientes') ? "active" : ""; ?> "> Pagos no mês </a>
            </li>-->
            
        </ul>
    </li>
     <?php
        }
    ?>
    
    <li class="menu-dropdown classic-menu-dropdown <?php echo ($item == 'relatorios') ? "active" : ""; ?>">
        <a href="listar.php?item=relatorios&tipo=cliente"> Relatórios
            <!--<span class="arrow"></span>-->
        </a>
        <!--
        <ul class="dropdown-menu pull-left">
            <li class=" ">
                <a href="listar.php?item=relatorios&tipo=cliente" class="nav-link <?php echo ($item == 'relatorios') ? "active" : ""; ?> "> Vendas </a>
            </li>
            <li class=" ">
                <a href="layout_top_bar_light.html" class="nav-link <?php echo ($item == 'relatorios') ? "active" : ""; ?> "> Financeiro </a>
            </li>
        </ul>
        -->
    </li>
    
     <?php
        if((base64_decode($_COOKIE["usr_nivel"]) == "A" OR base64_decode($_COOKIE["usr_nivel"]) == "P") AND base64_decode($_COOKIE["nivel_status"]) == 0 AND in_array("21", $verifica_lista_permissoes_array_inc))
        {
    ?>
     <li class="menu-dropdown classic-menu-dropdown <?php echo ($item == 'usuarios') ? "active" : ""; ?>">
        <a href="listar.php?item=usuarios"> Usuários
            <span class="arrow"></span>
        </a>
        
     </li>
      <?php
        }
        if(base64_decode($_COOKIE["usr_nivel"]) == "A" AND base64_decode($_COOKIE["nivel_status"]) == 0 AND in_array("24", $verifica_lista_permissoes_array_inc))
        {
      ?>
     <li class="menu-dropdown classic-menu-dropdown <?php echo ($item == 'preferencias') ? "active" : ""; ?> <?php echo ($item == 'sincronizacoes') ? "active" : ""; ?> <?php echo ($item == 'solicitacaoes') ? "active" : ""; ?> ">
        <a href="javascript:;"> Administração
            <span class="arrow"></span>
        </a>
        <ul class="dropdown-menu pull-left">
            <li class=" ">
                <!--<a href="listar.php?item=faturamentos" class="nav-link <?php echo ($item == 'faturamentos') ? "active" : ""; ?> "> Faturamentos </a>-->
            </li>
            <li class=" ">
                <a href="listar.php?item=pagamentos" class="nav-link <?php echo ($item == 'pagamentos') ? "active" : ""; ?> "> Pagamentos</a>
            </li>
            <!--
            <li class=" ">
                <a href="layout_mega_menu_light.html" class="nav-link <?php echo ($item == 'preferencias') ? "active" : ""; ?> "> Preferências </a>
            </li>
            <li class=" ">
                <a href="layout_top_bar_light.html" class="nav-link <?php echo ($item == 'sincronizacoes') ? "active" : ""; ?> "> Sincrinizações </a>
            </li>
            <li class=" ">
                <a href="layout_top_bar_light.html" class="nav-link <?php echo ($item == 'solicitacaoes') ? "active" : ""; ?> "> Solicitações </a>
            </li>
            -->
        </ul>
     </li>
    <?php
        }
    ?>
</ul>

