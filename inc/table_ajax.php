<?php
ini_set("max_execution_time", 300);
set_time_limit(300);
//tamanho de arquivo
//ini_set('memory_limit', '256M');
    require_once('../sessao.php');
    require_once('functions.php');
    require_once('conexao.php'); 
    $banco_painel = $link;
    $item                       = (empty($_GET['item'])) ? "" : verifica($_GET['item']);  
    $item = base64_decode($item);
    $id                         = (empty($_GET['id'])) ? "" : verifica($_GET['id']);
    $id =  base64_decode($id);
    $tipo                       = (empty($_GET['tipo'])) ? "" : verifica($_GET['tipo']);  
    $tipo =  base64_decode($tipo);
    $id_grupo_parceiro          = (empty($_GET['id_grupo_parceiro'])) ? "" : verifica($_GET['id_grupo_parceiro']); 
    $id_grupo_parceiro =  base64_decode($id_grupo_parceiro);
    $id_serv_get                = (empty($_GET['id_serv_get'])) ? "" : verifica($_GET['id_serv_get']);
    $id_serv_get =  base64_decode($id_serv_get); 
    $tipo_filtro                = (empty($_GET['tipo_filtro'])) ? "" : verifica($_GET['tipo_filtro']);
    $tipo_filtro =  base64_decode($tipo_filtro); 
    $id_usuario_pagamento       = (empty($_GET['id_usuario_pagamento'])) ? "" : verifica($_GET['id_usuario_pagamento']);
    $id_usuario_pagamento =  base64_decode($id_usuario_pagamento);
    $busca                      = (empty($_POST['campofiltro'])) ? "" : verifica($_POST['campofiltro']);
    
    $parceiro_user_filtro       = (empty($_POST['parceiro_user_filtro'])) ? "" : verifica($_POST['parceiro_user_filtro']);
    $filial_user_filtro         = (empty($_POST['filial_user_filtro'])) ? "" : verifica($_POST['filial_user_filtro']);
    $usuario_filtro             = (empty($_POST['usuario_filtro'])) ? "" : verifica($_POST['usuario_filtro']);
    $periodo                    = (empty($_POST['periodo'])) ? "" : verifica($_POST['periodo']);
    $todos_clientes_ativos      = (empty($_POST['todos_clientes_ativos'])) ? "" : verifica($_POST['todos_clientes_ativos']);
    $somente_clientes_ativos    = (empty($_POST['somente_clientes_ativos'])) ? "" : verifica($_POST['somente_clientes_ativos']);
    $busca_dependente           = (empty($_POST['customActionItem_dep'])) ? "" : verifica($_POST['customActionItem_dep']);

    $select_profissao_filtro    = (empty($_POST['select_profissao_filtro'])) ? "" : verifica($_POST['select_profissao_filtro']);
    $select_especialidade_filtro= (empty($_POST['select_especialidade_filtro'])) ? "" : verifica($_POST['select_especialidade_filtro']);
    $cidade_filtro_profissional = (empty($_POST['cidade_filtro_profissional'])) ? "" : verifica($_POST['cidade_filtro_profissional']);
    $select_local_atend_filtro  = (empty($_POST['select_local_atend_filtro'])) ? "" : verifica($_POST['select_local_atend_filtro']);

    $usr_id_perm                = base64_decode($_COOKIE["usr_id"]);

    $usr_parceiro   = base64_decode($_COOKIE["usr_parceiro"]);
    $usr_id         = base64_decode($_COOKIE["usr_id"]);
    $nivel_usuario  = base64_decode($_COOKIE["usr_nivel"]);
    $pasta          = base64_decode($_COOKIE["pasta"]);
    $nivel_status   = base64_decode($_COOKIE["nivel_status"]);
    
    $verifica_lista_permissoes  = '';
    if ($usr_id_perm  < 1){
        $usr_id_perm = 0;
        header("Location: login.php");
        exit;
    }
        $sql_perm        = "SELECT lista_permissoes FROM usuarios
                   WHERE id_usuario = $usr_id_perm";
        $query_perm      = mysql_query($sql_perm, $banco_painel);
                    
        if (mysql_num_rows($query_perm)>0){
            $verifica_lista_permissoes = mysql_result($query_perm, 0,0);
            $verifica_lista_permissoes_array_inc = explode("|", $verifica_lista_permissoes);
        }

    if($item == 'parceiros'){        
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
            $sql_contar     = "SELECT COUNT(*) FROM parceiros
                                WHERE (id_parceiro like '%$busca%' OR nome like '%$busca%' OR cpf like '%$busca%' OR cnpj like '%$busca%' OR razao_social like '%$busca%' OR cidade like '%$busca%' )";
            $query_contar   = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            
            $sql        = "SELECT * FROM parceiros
                            WHERE (id_parceiro like '%$busca%' OR nome like '%$busca%' OR cpf like '%$busca%' OR cnpj like '%$busca%' OR razao_social like '%$busca%' OR cidade like '%$busca%' )
                                LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query)){
                    extract($dados);  
                    if($del == 'N'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                    
                    $records["data"][] = array(
                    $id_parceiro,
                    $nome,
                    $cnpj."".$cpf,
                      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                      '<a href="inc/ver_parceiro.php?id_parceiro='.$id_parceiro.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                   );
                }
                
            }
            
        }
        else{
            $sql_contar         = "SELECT COUNT(*) FROM parceiros";
            $query_contar       = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords      = mysql_result($query_contar, 0,0);
            $iDisplayLength     = intval($_REQUEST['length']);
            $iDisplayLength     = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart      = intval($_REQUEST['start']);
            $sEcho              = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            
            $sql        = "SELECT * FROM parceiros
            ORDER BY id_parceiro DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query)){
                    extract($dados);  
                    if($del == 'N'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                    
                    $records["data"][] = array(
                      $id_parceiro,
                      $nome,
                      $cnpj."".$cpf,
                      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                      '<a href="inc/ver_parceiro.php?id_parceiro='.$id_parceiro.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                   );
                }
                
            }
        }
    }elseif($item == 'clientes'){        
        
        if(!empty($tipo) AND $tipo == 'produto'){
            if($id == 'todos'){
                $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                JOIN servicos serv ON pro.id_servico = serv.id_servico
                                        WHERE serv.id_servico = $id_serv_get
                GROUP BY serv.id_servico ";
            }else{
                // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                WHERE pro.id_produto = $id";
            }

            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()."$sql_base");

            if (mysql_num_rows($query_base)>0){
                $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                $banco_user             = mysql_result($query_base, 0, 'banco_user');
                $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                $banco_host             = mysql_result($query_base, 0, 'banco_host');
                $slug                   = mysql_result($query_base, 0, 'slug');
                $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                
                $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
            }

            $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
        }

        // CAMPO DE BUSCA
        if(!empty($_POST['campofiltro']) OR !empty($_POST['parceiro_user_filtro']) OR !empty($_POST['filial_user_filtro']) OR !empty($_POST['filial_user_filtro'])){
            if($slug == 'europ'){
                $agora 			= date("Y-m-d");
                $where = '';
                $where_filial = '';
                $where_usuario = '';
                $where_tipo_movimento = '';
                $where_filtro = "";
                $sql_data_emissao = '';
                $sql_join_renovacao = '';
                $sql_join_renovacao_vendas = '';
                $sql_group_by = 'GROUP BY c.chave';
                
                $sql_order_by = 'ORDER BY c.id_cliente DESC';
                $sql_nome_tabela = "AND (c.nome_tabela IS NULL OR c.nome_tabela = '$pasta')";
                $contar_busca               = strlen($busca);
                $busca = str_replace(".", "", $busca);
                $busca = str_replace(",", "", $busca);
                $busca = str_replace("-", "", $busca);
                //$verifica_sql_busca_data = '';
                
                $buscadata = explode("/","$busca"); // fatia a string $dat em pedados, usando / como refer�ncia
                if(count($buscadata) > 1){
                    if(ValidaData($busca) == 'ok'){
                        //$verifica_sql_busca_data = 'ok';
                        $busca = converte_data($busca);
                    }
                }
                
                $datas = explode(" - ", $periodo);
                
                $data1 = explode(" ", $datas[0]);
                $dia_data1 = $data1[0];
                $mes_data1 = substr($data1[1], 0, -1);
                $ano_data1 = $data1[2];
                
                if($mes_data1 == "Janeiro"){
                $mes_num_data1 = "01";
                }elseif($mes_data1 == "Fevereiro"){
                $mes_num_data1 = "02";
                }elseif($mes_data1 == "Marco"){
                $mes_num_data1 = "03";
                }elseif($mes_data1 == "Abril"){
                $mes_num_data1 = "04";
                }elseif($mes_data1 == "Maio"){
                $mes_num_data1 = "05";
                }elseif($mes_data1 == "Junho"){
                $mes_num_data1 = "06";
                }elseif($mes_data1 == "Julho"){
                $mes_num_data1 = "07";
                }elseif($mes_data1 == "Agosto"){
                $mes_num_data1 = "08";
                }elseif($mes_data1 == "Setembro"){
                $mes_num_data1 = "09";
                }elseif($mes_data1 == "Outubro"){
                $mes_num_data1 = "10";
                }elseif($mes_data1 == "Novembro"){
                $mes_num_data1 = "11";
                }else{
                $mes_num_data1 = "12";
                }
                
                $data1 = $ano_data1.'/'.$mes_num_data1.'/'.$dia_data1;
                $data1_convert = str_replace("/", "-", $data1);
                
                $data2 = explode(" ", $datas[1]);
                $dia_data2 = $data2[0];
                $mes_data2 = substr($data2[1], 0, -1);
                $ano_data2 = $data2[2];
                
                if($mes_data2 == "Janeiro"){
                $mes_num_data2 = "01";
                }elseif($mes_data2 == "Fevereiro"){
                $mes_num_data2 = "02";
                }elseif($mes_data2 == "Marco"){
                $mes_num_data2 = "03";
                }elseif($mes_data2 == "Abril"){
                $mes_num_data2 = "04";
                }elseif($mes_data2 == "Maio"){
                $mes_num_data2 = "05";
                }elseif($mes_data2 == "Junho"){
                $mes_num_data2 = "06";
                }elseif($mes_data2 == "Julho"){
                $mes_num_data2 = "07";
                }elseif($mes_data2 == "Agosto"){
                $mes_num_data2 = "08";
                }elseif($mes_data2 == "Setembro"){
                $mes_num_data2 = "09";
                }elseif($mes_data2 == "Outubro"){
                $mes_num_data2 = "10";
                }elseif($mes_data2 == "Novembro"){
                $mes_num_data2 = "11";
                }else{
                $mes_num_data2 = "12";
                }

                $verifica_filtro_cliente = false;
                
                if(!empty($parceiro_user_filtro)){
                    $usr_parceiro = $parceiro_user_filtro;
                    $verifica_filtro_cliente = true;
                    
                    $data2 = $ano_data2.'/'.$mes_num_data2.'/'.$dia_data2;
                    $data2_convert = str_replace("/", "-", $data2);
    
                    $sql_data_emissao = "AND (c.data_emissao BETWEEN '$data1' AND '$data2')";
                    if($todos_clientes_ativos == 'S'){
                        $sql_data_emissao = "AND c.data_emissao <= '$agora'";
                    }
                    $tipo_filtro            = verifica($_POST['tipo_filtro']);
                }
                
                if(!empty($filial_user_filtro) AND $filial_user_filtro > 0){
                    $verifica_filtro_cliente = true;
                    $where_filial = "AND c.id_filial = '$filial_user_filtro'";
                }

                if(!empty($usuario_filtro) AND $usuario_filtro > 0){
                    $verifica_filtro_cliente = true;
                    $where_usuario = "AND c.id_usuario = '$usuario_filtro'";
                }
                $where_tipo_movimento = "AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                if($somente_clientes_ativos == 'S'){
                    $where_tipo_movimento = "AND c.tipo_movimento IN ('IN', 'AL') AND (c.data_termino >= '$agora' OR c.data_termino = '0000-00-00')";
                }
                
                if($tipo_filtro == 'avencer'){
                    $data_verif = somar_datas( 2, 'm'); // adiciona meses a sua data          
                    $data_restante = date('d/m/Y', strtotime($data_verif));
                    $data_restante = converte_data($data_restante);
                    $where_filtro = "AND c.data_termino BETWEEN '$agora' AND '$data_restante'";
                    $sql_order_by = 'ORDER BY c.data_termino DESC';
                }
                
                if($tipo_filtro == 'vencido'){
                    $where_filtro = "AND (data_termino BETWEEN '2018-08-01' AND '$agora' AND data_termino <> '0000-00-00') AND status = 99";
                    $sql_order_by = 'ORDER BY c.data_termino DESC'; 
                }
                
                if($tipo_filtro == 'pendente'){
                    $where_filtro = "AND (c.status = 3 OR c.status = 4 OR c.status = 6)";
                }
                
                if($tipo_filtro == 'dependente'){
                    $where_filtro = "AND c.id_cliente_principal > 0";
                }
                
                if($tipo_filtro == 'cancelado'){
                    $where_tipo_movimento = "AND c.tipo_movimento = 'EX'";
                    $sql_order_by = 'ORDER BY c.data_cancelamento DESC';
                }
                
                if($tipo_filtro == 'ativos'){
                    $agora_so_ano_mes	= date("Y-m")."-01";
                    $where_filtro = "AND c.data_termino > '$agora_so_ano_mes' AND (c.status = 0 OR c.status = 99 OR c.status = 6)";
                }
                
                if($tipo_filtro == 'renovado'){
                    $data2 = $ano_data2.'/'.$mes_num_data2.'/'.$dia_data2;
                    $data2_convert = str_replace("/", "-", $data2);
                    
                    $sql_data_emissao = "AND (hv.data_cadastro BETWEEN '$data1' AND '$data2')";
                    if($todos_clientes_ativos == 'S'){
                        $sql_data_emissao = "AND hv.data_cadastro <= '$agora'";
                    }
                    $sql_join_renovacao_vendas = "JOIN vendas v ON c.id_cliente = v.id_cliente";
                    $sql_join_renovacao = "JOIN historico_vendas hv ON v.id_ordem_pedido = hv.id_ordem_pedido";
                    $sql_group_by = 'GROUP BY hv.id_ordem_pedido';
                }

                if($nivel_usuario == 'P'){
                    $where .= "AND c.id_parceiro = '$usr_parceiro' $where_filial $where_usuario"; 
                }elseif($verifica_filtro_cliente == true){
                    if($parceiro_user_filtro > 0){
                        $where .= "AND c.id_parceiro = '$usr_parceiro' $where_filial $where_usuario"; 
                    }else{
                        $where .= "AND c.id_parceiro <> '' $where_filial $where_usuario"; 
                    }
                }elseif($nivel_usuario == 'U'){
                    $where .= "AND c.id_parceiro = '$usr_parceiro' AND c.id_usuario = '$usr_id'";
                }

                if(isset($_POST['customActionItem_dep']) AND !empty($_POST['customActionItem_dep'])){
                    if($contar_busca > 2){
                        $sql_contar = "SELECT c.id_cliente FROM dependentes_clientes dep_cli
                            JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                            JOIN clientes c ON v.id_cliente = c.id_cliente
                            $sql_join_renovacao
                            WHERE dep_cli.status = 0 AND v.dependente = 'N' AND (dep_cli.nome like '%$busca%' OR (dep_cli.data_nascimento = '$busca' AND dep_cli.data_nascimento <> '0000-00-00')) $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                            $sql_group_by";
                    }else{
                        $sql_contar = "SELECT c.id_cliente FROM dependentes_clientes dep_cli
                            JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                            JOIN clientes c ON v.id_cliente = c.id_cliente
                            $sql_join_renovacao
                            WHERE dep_cli.status = 0 AND v.dependente = 'N' $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                            $sql_group_by";
                    }
                    
                    $query_contar   = mysql_query($sql_contar, $banco_produto);
                
                }else{
                    if($contar_busca > 2){
                         $sql_contar     = "SELECT c.id_cliente FROM clientes c
                                    $sql_join_renovacao_vendas
                                    $sql_join_renovacao
                                    WHERE (c.nome like '%$busca%' OR c.cpf = '$busca' OR (c.data_nascimento = '$busca' AND c.data_nascimento <> '0000-00-00')) $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                                    $sql_group_by";
                    }else{
                         $sql_contar     = "SELECT c.id_cliente FROM clientes c
                                    $sql_join_renovacao_vendas
                                    $sql_join_renovacao
                                    WHERE c.id_cliente <> '' $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                                    $sql_group_by";
                    }
                   
                $query_contar   = mysql_query($sql_contar, $banco_produto);
                }

                $iTotalRecords = mysql_num_rows($query_contar);
                $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
                $iDisplayStart = intval($_REQUEST['start']);
                $sEcho = intval($_REQUEST['draw']);
                
                $records = array();
                $records["data"] = array(); 
                
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                
                $status_list = array(
                array("success" => "Ativo"),
                array("danger" => "Inativo")
                );
                if(isset($_POST['customActionItem_dep']) AND !empty($_POST['customActionItem_dep'])){
                    if($contar_busca > 2){
                        $sql        = "SELECT c.* FROM dependentes_clientes dep_cli
                            JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                            JOIN clientes c ON v.id_cliente = c.id_cliente
                            $sql_join_renovacao
                            WHERE dep_cli.status = 0 AND v.dependente = 'N' AND (dep_cli.nome like '%$busca%' OR (dep_cli.data_nascimento = '$busca' AND dep_cli.data_nascimento <> '0000-00-00')) $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                                $sql_group_by
                                $sql_order_by
                                    LIMIT $iDisplayStart,$iDisplayLength";
                    }else{
                        $sql        = "SELECT c.* FROM dependentes_clientes dep_cli
                            JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                            JOIN clientes c ON v.id_cliente = c.id_cliente
                            $sql_join_renovacao
                            WHERE dep_cli.status = 0 AND v.dependente = 'N' $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                                $sql_group_by
                                $sql_order_by
                                    LIMIT $iDisplayStart,$iDisplayLength";
                    }
                    $query      = mysql_query($sql, $banco_produto);
                }else{
                    if($contar_busca > 2){
                        $sql        = "SELECT c.* FROM clientes c
                                    $sql_join_renovacao_vendas
                                    $sql_join_renovacao
                                    WHERE (c.nome like '%$busca%' OR c.cpf = '$busca' OR (c.data_nascimento = '$busca' AND c.data_nascimento <> '0000-00-00')) $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                                    $sql_group_by
                                    $sql_order_by
                                        LIMIT $iDisplayStart,$iDisplayLength";
                    }else{
                        $sql        = "SELECT c.* FROM clientes c
                                $sql_join_renovacao_vendas
                                $sql_join_renovacao
                                WHERE c.id_cliente <> '' $where $where_tipo_movimento $where_filtro $sql_data_emissao $sql_nome_tabela
                                $sql_group_by
                                ORDER BY c.id_cliente DESC
                                    LIMIT $iDisplayStart,$iDisplayLength";
                    }
                    
                $query      = mysql_query($sql, $banco_produto);
                }     
                if (mysql_num_rows($query)>0){
                    while ($dados = mysql_fetch_array($query))
                    {
                        extract($dados);  
                        
                        $data_nascimento = converte_data($data_nascimento);
                        //$data_termino    = converte_data_barra($data_termino);
                        $idade = calcula_idade($data_nascimento);
                        
                        // status
                        // Comparando as Datas
                        //$convert_data_termino = converte_data($data_termino);
                        $agora 			= date("Y-m-d");
                        $vencido = '';
                        if((strtotime($data_termino) < strtotime($agora) AND $data_termino <> '0000-00-00') AND $tipo_movimento <> 'EX'){
                            $vencido = '<span class="label label-sm label-warning">V</span>';
                        }
                        
                        $data_verif = somar_datas( 2, 'm'); // adiciona meses a sua data          
                        $data_restante = date('d/m/Y', strtotime($data_verif));
                        $data_restante = converte_data($data_restante);
                        if(strtotime($data_termino) <= strtotime($data_restante) AND $data_termino != '0000-00-00'){
                            $vencido = '<span class="label label-sm label-warning">V</span>';
                        
                        }
                        
                        
                        
                        if((strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0)){
                            $status = $status_list[0];
                        }
                        elseif(strtotime($data_termino) == strtotime($agora) AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0)){
                            $status = $status_list[0];
                        }
                        else{
                            $status = $status_list[1];
                        }
                                                
                        $depen = '';
                        if($id_cliente_principal > 0){
                            $depen = '<span class="label label-sm label-info">A</span>';
                        } 
                        
                        $penden = '';
                        if($status_cliente == 3){
                            $penden = '<span class="label label-sm label-danger">P</span>';
                        }  

                        if($status_cliente == 5){
                            $vencido = '<span class="label label-sm label-warning">V</span>';
                            $status = $status_list[0];
                        }
                        
                        $label_renova = '';
                        $cliente_status_renovacao = ''; 
                        $cliente_id_ordem_pedido = '';
                        
                        $sql_st_ren    = "SELECT id_ordem_pedido FROM vendas 
                                            WHERE id_cliente = $id_cliente";
                        $query_st_ren  = mysql_query($sql_st_ren, $banco_produto);
        
                        if (mysql_num_rows($query_st_ren)>0){
                            $cliente_id_ordem_pedido = mysql_result($query_st_ren, 0,'id_ordem_pedido');
                            
                            $sql_st_ren    = "SELECT status_renovacao FROM ordem_pedidos 
                                            WHERE id_ordem_pedido = $cliente_id_ordem_pedido";
                            $query_st_ren  = mysql_query($sql_st_ren, $banco_painel);
            
                            if (mysql_num_rows($query_st_ren)>0){
                                $cliente_status_renovacao = mysql_result($query_st_ren, 0,'status_renovacao');
                            }
                        }
                        
                        if($cliente_status_renovacao == 'S'){
                            $label_renova = '<span class="label label-sm bg-purple">RE</span>'; 
                        }  
                        
                        $verifica_cartao = '<span class="label label-sm label-default"><i class="fa fa-credit-card"></i></span>';
                        $verifica           = "SELECT data_impressao FROM controle_impressao 
                                            WHERE id_referencia = $id_cliente AND tipo_impressao = 'cartao'";
                        $query_verifica    = mysql_query($verifica, $banco_produto);
                        
                                
                        if (mysql_num_rows($query_verifica)>0){
                            $verifica_cartao  = '';
                        }
                        
                        if($id_filial > 0 OR $id_filial_integracao > 0){
                            if($id_filial_integracao > 0){
                                $sql_item_filial = "fi.id_filial_integracao = '$id_filial_integracao'";
                            }else{
                                $sql_item_filial = "fi.id_filial = '$id_filial'";
                            }
                            $sql_par   = "SELECT par.nome'nome_parceiro', fi.nome'nome_filial' FROM parceiros par
                            JOIN filiais fi ON par.id_parceiro = fi.id_parceiro
                            WHERE par.id_parceiro = $id_parceiro AND $sql_item_filial";
                            $query_par      = mysql_query($sql_par, $banco_painel);    
                            if (mysql_num_rows($query_par) == 0){
                                $sql_par        = "SELECT nome'nome_parceiro' FROM parceiros 
                                            WHERE id_parceiro = $id_parceiro";
                                $id_filial = 0;
                                $id_filial_integracao = 0;
                            }
                            }else{
                                $sql_par        = "SELECT nome'nome_parceiro' FROM parceiros 
                                            WHERE id_parceiro = $id_parceiro";
                            }
                                                        
                        $query_par      = mysql_query($sql_par, $banco_painel);
                        $nome_parceiro = ''; 
                        $nome_filial = '';          
                        if (mysql_num_rows($query_par)>0){
                            $nome_parceiro = mysql_result($query_par, 0, 0);
                            if($id_filial > 0 OR $id_filial_integracao > 0){
                            $nome_filial  = mysql_result($query_par, 0, 'nome_filial');
                            }
                        }

                        $sql_adicional        = "SELECT dep_cli.id_dependente'id_dependente_cliente', dep_cli.tipo_dependente, dep_cli.nome'nome_dependente', dep_cli.data_nascimento'data_nasc_dependente' FROM dependentes_clientes dep_cli
                        JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                        WHERE dep_cli.id_cliente = $id_cliente AND dep_cli.status = 0 AND v.dependente = 'N'";
                        $query_adicional      = mysql_query($sql_adicional, $banco_produto);
                        $html_dependentes = '';        
                        if (mysql_num_rows($query_adicional)>0){
                            while($dados_adicional = mysql_fetch_array($query_adicional)){
                                extract($dados_adicional); 
                            $html_dependentes.= $tipo_dependente.'-'.$nome_dependente.'-'.$data_nasc_dependente.'<br/>';
                            } 
                        }

                        $sql_ver_cli    = "SELECT id_produto'id_produto_cliente' FROM produtos 
                                        WHERE versao_produto = '$versao_europ'";
                        $query_ver_cli  = mysql_query($sql_ver_cli, $banco_painel);
                                    
                        if (mysql_num_rows($query_ver_cli)>0){
                            $id_produto_cliente = mysql_result($query_ver_cli, 0,'id_produto_cliente');
                        }
                        
                        if($data_termino == '0000-00-00'){
                            $data_termino_exibe = 'Recorrente';
                        }else{
                            $data_termino_exibe = converte_data($data_termino);
                        }
                        $exibe_html_dependentes = '';
                        if(isset($_POST['customActionItem_dep']) AND !empty($_POST['customActionItem_dep']))
                        {
                            $exibe_html_dependentes = "<br/><span style='font-size: 10px;'>".$html_dependentes."</span>";
                        }
                        
                            $records["data"][] = array(
                            $id_cliente,
                            "<strong>".$nome.'</strong> '.$exibe_html_dependentes,
                            mask_total($cpf, '###.###.###-##'),
                            $data_nascimento." <br/> (".$idade." anos)",
                            $nome_parceiro." ".$nome_filial,
                            $data_termino_exibe,
                            '<p><span class="label label-sm label-'.(key($status)).'">('.$tipo_movimento.') '.(current($status)).'</span>'.$depen.' '.$penden.' '.$vencido.' '.$verifica_cartao.' '.$label_renova.'</p>',
                        '<a href="inc/ver_cliente.php?id_cliente='.$id_cliente.'&id_produto='.$id_produto_cliente.'&tipo='.$tipo.'&status='.(current($status)).'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                        );
                    }
                    mysql_close($banco_produto);
                }
           }  
           elseif($slug == 'sorteio_ead'){
                $where = '';
                $where = "AND v.nome_tabela = '$pasta' ";
                if($nivel_usuario == 'P'){
                    $where .= "AND v.id_parceiro_painel = '$usr_parceiro' ";
                    
                }elseif($nivel_usuario == 'U'){
                    
                    $where .= "AND v.id_parceiro_painel = '$usr_parceiro' AND v.id_usuario_painel = '$usr_id'";
                }

                $sql_contar     = "SELECT COUNT(*) FROM vendas v
                JOIN titulos t ON v.id_titulo = t.id_titulo
                WHERE (v.nome like '%$busca%' OR t.numero_sorteio like '%$busca%' OR v.cpf like '%$busca%') $where
                ORDER BY v.dt_cadastro DESC ";
                //error_log($sql_contar);
                $query_contar   = mysql_query($sql_contar, $banco_produto);
                $iTotalRecords = mysql_result($query_contar, 0,0);
                $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
                $iDisplayStart = intval($_REQUEST['start']);
                $sEcho = intval($_REQUEST['draw']);
                
                $records = array();
                $records["data"] = array(); 
                
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                
                $status_list = array(
                array("success" => "Ativo"),
                array("danger" => "Inativo")
                );
                
                $sql        = "SELECT v.id_venda, v.nome, t.numero_sorteio, t.data_sorteio, v.cpf, v.status, v.id_parceiro_painel FROM vendas v
                                JOIN titulos t ON v.id_titulo = t.id_titulo
                                WHERE (v.nome like '%$busca%' OR t.numero_sorteio like '%$busca%' OR v.cpf like '%$busca%') $where
                                ORDER BY v.dt_cadastro DESC
                                    LIMIT $iDisplayStart,$iDisplayLength";
                $query      = mysql_query($sql, $banco_produto);
                            
                if (mysql_num_rows($query)>0){
                    while ($dados = mysql_fetch_array($query))
                    {
                        extract($dados);  
                        if($status == 'A'){
                            $status_campo = $status_list[0];
                        }
                        elseif($status == 'C'){
                            $status_campo = $status_list[1];
                        }

                        $nome_paceiro = '';
                        if(!empty($id_parceiro_painel)){
                            $sql_par        = "SELECT nome'nome_parceiro' FROM parceiros 
                                        WHERE id_parceiro = $id_parceiro_painel";
                            $query_par      = mysql_query($sql_par, $banco_painel);

                            $nome_paceiro = mysql_result($query_par, 0,0);
                        }
                        
                        $sql_ver_cli    = "SELECT pro.id_produto'id_produto_cliente' FROM produtos_grupos prog
                        JOIN produtos pro ON prog.id_produto = pro.id_produto
                        JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                        WHERE bpro.slug = '$slug'
                        GROUP BY pro.id_produto";
                        $query_ver_cli  = mysql_query($sql_ver_cli, $banco_painel);
                                    
                        if (mysql_num_rows($query_ver_cli)>0){
                            $id_produto_cliente = mysql_result($query_ver_cli, 0,'id_produto_cliente');
                        }

                            $records["data"][] = array(
                            $id_venda,
                            $nome,
                            $cpf,
                            $numero_sorteio,
                            converte_data($data_sorteio),
                            $nome_paceiro,
                            '<p><span class="label label-sm label-'.(key($status_campo)).'">('.$tipo_movimento.') '.(current($status_campo)).'</span></p> '.$depen.' '.$penden,
                        '<a href="inc/ver_cliente.php?id_cliente='.$id_venda.'&id_produto='.$id_produto_cliente.'&tipo='.$tipo.'&status='.(current($status_campo)).'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                        );
                    }
                    mysql_close($banco_produto);
                }
           }  
        }
        else{
            if($slug == 'europ'){
                $agora 			= date("Y-m-d");
                $where = '';
                $where_filtro = "AND (data_termino > '$agora' OR data_termino = '0000-00-00')";
                $sql_order_by   = 'ORDER BY data_inicio DESC';
                $sql_nome_tabela = "AND (nome_tabela IS NULL OR nome_tabela = '$pasta')";
                
                if($nivel_usuario == 'P'){
                    if($id == 'todos'){
                        if($tipo_filtro == 'renovado'){
                            $where .= "AND c.id_parceiro = '$usr_parceiro'";
                        }else{
                            $where .= "AND id_parceiro = '$usr_parceiro'";
                        }
                    }else{
                        $where .= "AND id_parceiro = '$usr_parceiro'";
                    }

                }elseif($nivel_usuario == 'U'){
                    if($id == 'todos'){
                        if($tipo_filtro == 'renovado'){
                            $where .= "AND c.id_parceiro = '$usr_parceiro' AND c.id_usuario = '$usr_id'";
                            
                        }else{
                            $where .= "AND id_parceiro = '$usr_parceiro' AND id_usuario = '$usr_id'";
                        }

                    }else{
                        $where .= "AND id_parceiro = '$usr_parceiro' AND id_usuario = '$usr_id'";
                    }
                }
                
                $where_tipo_movimento = "tipo_movimento IN ('IN', 'AL')";
                
                if($tipo_filtro == 'avencer'){
                    $data_verif = somar_datas( 2, 'm'); // adiciona meses a sua data          
                    $data_restante = date('d/m/Y', strtotime($data_verif));
                    $data_restante = converte_data($data_restante);
                    $where_filtro = "AND data_termino BETWEEN '$agora' AND '$data_restante'";
                    $sql_order_by = 'ORDER BY data_termino DESC';
                }
                
                if($tipo_filtro == 'vencido'){
                    //$where_filtro = "AND (data_termino < '$agora' AND data_termino <> '0000-00-00') AND status = 99";
                    $where_filtro = "AND (data_termino BETWEEN '2018-08-01' AND '$agora' AND data_termino <> '0000-00-00') AND status = 99";
                    $sql_order_by = 'ORDER BY data_termino DESC';
                }
                
                if($tipo_filtro == 'pendente'){
                    $where_filtro = "AND (status = 3 OR status = 4 OR status = 6)";
                }
                
                if($tipo_filtro == 'dependente'){
                    $where_filtro = "AND id_cliente_principal > 0";
                }
                
                if($tipo_filtro == 'cancelado'){
                    $where_tipo_movimento = "tipo_movimento = 'EX'";
                    $sql_order_by = 'ORDER BY data_cancelamento DESC';
                }
                
                if($tipo_filtro == 'ativos'){
                    $where_filtro = "AND (status = 0 OR status = 99)";
                }
                
                if($tipo_filtro == 'renovado'){
                    $sql_order_by = 'ORDER BY data_inicio DESC';  
                }

                if($id == 'todos'){
                    if($tipo_filtro == 'renovado'){
                        $sql_contar = "SELECT c.id_cliente FROM historico_vendas hv
                        JOIN vendas v ON hv.id_ordem_pedido = v.id_ordem_pedido
                        JOIN clientes c ON v.id_cliente = c.id_cliente
                        WHERE v.dependente = 'N' AND c.tipo_movimento IN ('IN', 'AL') $where
                        GROUP BY c.chave";
                    }else{
                        $sql_contar     = "SELECT id_cliente FROM clientes 
                        WHERE $where_tipo_movimento $where $where_filtro $sql_nome_tabela
                        GROUP BY chave ";
                    }
                }else{
                    $sql_contar     = "SELECT id_cliente FROM clientes 
                    WHERE versao_europ LIKE '%$versao_produto%' AND $where_tipo_movimento $where $where_filtro $sql_nome_tabela
                    GROUP BY chave";
                }
                
                $query_contar      = mysql_query($sql_contar, $banco_produto);
                $iTotalRecords     = mysql_num_rows($query_contar);

                $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
                $iDisplayStart = intval($_REQUEST['start']);
                $sEcho = intval($_REQUEST['draw']);
                
                $records = array();
                $records["data"] = array(); 
                
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                
                $status_list = array(
                array("success" => "Ativo"),
                array("danger" => "Inativo")
                );
                
                if($id == 'todos'){
                    if($tipo_filtro == 'renovado'){
                        $sql = "SELECT c.* FROM historico_vendas hv
                        JOIN vendas v ON hv.id_ordem_pedido = v.id_ordem_pedido
                        JOIN clientes c ON v.id_cliente = c.id_cliente
                        WHERE v.dependente = 'N' AND c.tipo_movimento IN ('IN', 'AL') $where
                        GROUP BY c.chave
                        $sql_order_by
                        LIMIT $iDisplayStart,$iDisplayLength";
                    }else{
                        $sql    = "SELECT * FROM clientes 
                        WHERE $where_tipo_movimento $where $where_filtro $sql_nome_tabela
                        GROUP BY chave
                        $sql_order_by
                        LIMIT $iDisplayStart,$iDisplayLength";
                    }
                }else{
                        $sql    = "SELECT * FROM clientes 
                        WHERE versao_europ LIKE '%$versao_produto%' AND $where_tipo_movimento $where $where_filtro $sql_nome_tabela
                        GROUP BY chave
                        $sql_order_by
                        LIMIT $iDisplayStart,$iDisplayLength";
                }
          
                $query      = mysql_query($sql, $banco_produto);
                            
                if (mysql_num_rows($query)>0){
                    while ($dados = mysql_fetch_array($query))
                    {
                        extract($dados);  
                        
                        $data_nascimento = converte_data($data_nascimento);
                        $idade = calcula_idade($data_nascimento);
                        $status_cliente = $status;
                        $agora 			= date("Y-m-d");
                        $vencido = '';
                        if((strtotime($data_termino) < strtotime($agora) AND $data_termino <> '0000-00-00') AND $tipo_movimento <> 'EX'){
                                $vencido = '<span class="label label-sm label-warning">V</span>';
                        }
                            
                        $data_verif = somar_datas( 2, 'm'); // adiciona meses a sua data          
                        $data_restante = date('d/m/Y', strtotime($data_verif));
                        $data_restante = converte_data($data_restante);
                        if(strtotime($data_termino) <= strtotime($data_restante) AND $data_termino != '0000-00-00'){
                            $vencido = '<span class="label label-sm label-warning">V</span>';
                        }
                        
                        if((strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0)){
                            $status = $status_list[0];
                        }
                        elseif((strtotime($data_termino) == strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0)){
                            $status = $status_list[0];
                        }
                        else{
                            $status = $status_list[1];
                        }
                        
                        $depen = '';
                        if($id_cliente_principal > 0){
                            $depen = '<span class="label label-sm label-info">A</span>';
                        } 
                        
                        $penden = '';
                        if($status_cliente == 3){
                            $penden = '<span class="label label-sm label-danger">P</span>';
                        }  
                        
                        
                        if($status_cliente == 5){
                            $vencido = '<span class="label label-sm label-warning">V</span>';
                            $status = $status_list[0];
                        }   
                        
                        $label_renova = '';
                        $cliente_status_renovacao = ''; 
                        $cliente_id_ordem_pedido = '';
                        
                        $sql_st_ren    = "SELECT id_ordem_pedido FROM vendas 
                                          WHERE id_cliente = $id_cliente";
                        $query_st_ren  = mysql_query($sql_st_ren, $banco_produto);
     
                        if (mysql_num_rows($query_st_ren)>0){
                            $cliente_id_ordem_pedido = mysql_result($query_st_ren, 0,'id_ordem_pedido');
                            
                            $sql_st_ren    = "SELECT status_renovacao FROM ordem_pedidos 
                                          WHERE id_ordem_pedido = $cliente_id_ordem_pedido";
                            $query_st_ren  = mysql_query($sql_st_ren, $banco_painel);
         
                            if (mysql_num_rows($query_st_ren)>0){
                                $cliente_status_renovacao = mysql_result($query_st_ren, 0,'status_renovacao');
                            }
                        }
                        
                        if($cliente_status_renovacao == 'S'){
                            $label_renova = '<span class="label label-sm bg-purple">RE</span>';
                            
                        }                 
                        
                        $verifica_cartao = '<span class="label label-sm label-default"><i class="fa fa-credit-card"></i></span>';
                        $verifica           = "SELECT data_impressao FROM controle_impressao 
                                            WHERE id_referencia = $id_cliente AND tipo_impressao = 'cartao'";
                        $query_verifica    = mysql_query($verifica, $banco_produto);
                        
                             
                        if (mysql_num_rows($query_verifica)>0){
                            $verifica_cartao  = '';
                        }
                            
                        if($id_filial > 0 OR $id_filial_integracao > 0){
                            if($id_filial_integracao > 0){
                                    $sql_item_filial = "fi.id_filial_integracao = '$id_filial_integracao'";
                                }else{
                                    $sql_item_filial = "fi.id_filial = '$id_filial'";
                                }
                                $sql_par   = "SELECT par.nome'nome_parceiro', fi.nome'nome_filial' FROM parceiros par
                                JOIN filiais fi ON par.id_parceiro = fi.id_parceiro
                                WHERE par.id_parceiro = $id_parceiro AND $sql_item_filial";
                            }else{
                                $sql_par        = "SELECT nome'nome_parceiro' FROM parceiros 
                                            WHERE id_parceiro = $id_parceiro";
                            }
                                                        
                        $query_par      = mysql_query($sql_par, $banco_painel);
                        $nome_parceiro = ''; 
                        $nome_filial = '';          
                        if (mysql_num_rows($query_par)>0){
                            $nome_parceiro = mysql_result($query_par, 0, 0);
                            if($id_filial > 0 OR $id_filial_integracao > 0){
                            $nome_filial  = mysql_result($query_par, 0, 'nome_filial');
                            }
                        }
                        
                        $sql_ver_cli    = "SELECT id_produto'id_produto_cliente' FROM produtos 
                                            WHERE versao_produto = '$versao_europ'";
                        $query_ver_cli  = mysql_query($sql_ver_cli, $banco_painel);
                                    
                        if (mysql_num_rows($query_ver_cli)>0){
                            $id_produto_cliente = mysql_result($query_ver_cli, 0,'id_produto_cliente');
                        }
                        
                        if($data_termino == '0000-00-00'){
                            $data_termino_exibe = 'Recorrente';
                        }else{
                            $data_termino_exibe = converte_data($data_termino);
                        }
                        
                         $records["data"][] = array(
                          $id_cliente,
                          $nome,
                          mask_total($cpf, '###.###.###-##'),
                          $data_nascimento." <br/>(".$idade." anos)",
                          $nome_parceiro." ".$nome_filial,
                          $data_termino_exibe,
                          '<p><span class="label label-sm label-'.(key($status)).'">('.$tipo_movimento.') '.(current($status)).'</span>'.$depen.' '.$penden.' '.$vencido.' '.$verifica_cartao.' '.$label_renova.'</p>',
                          '<a href="inc/ver_cliente.php?id_cliente='.$id_cliente.'&id_produto='.$id_produto_cliente.'&tipo='.$tipo.'&status='.(current($status)).'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                       );
                    }
                    mysql_close($banco_produto);
                }
            }elseif($slug == 'sorteio_ead'){
                $where = "WHERE v.nome_tabela = '$pasta'";
                if($nivel_usuario == 'P'){
                    $where .= " AND v.id_parceiro_painel = '$usr_parceiro'";
                    
                }elseif($nivel_usuario == 'U'){
                    
                    $where .= " AND v.id_parceiro_painel = '$usr_parceiro' AND v.id_usuario_painel = '$usr_id'";
                }
                
                $sql_contar        = "SELECT COUNT(*) FROM vendas v
                                    JOIN titulos t ON v.id_titulo = t.id_titulo 
                                    $where
                                    ORDER BY v.dt_cadastro DESC";
                $query_contar      = mysql_query($sql_contar, $banco_produto);
                
                $iTotalRecords = mysql_result($query_contar, 0,0);
                $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
                $iDisplayStart = intval($_REQUEST['start']);
                $sEcho = intval($_REQUEST['draw']);
                
                $records = array();
                $records["data"] = array(); 
                
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                
                $status_list = array(
                array("success" => "Ativo"),
                array("danger" => "Inativo")
                );
                
                $sql        = "SELECT v.id_venda, v.nome, t.numero_sorteio, t.data_sorteio, v.cpf, v.status, v.id_parceiro_painel FROM vendas v
                                JOIN titulos t ON v.id_titulo = t.id_titulo  
                            $where 
                            ORDER BY v.dt_cadastro DESC
                            LIMIT $iDisplayStart,$iDisplayLength";
                $query      = mysql_query($sql, $banco_produto);
                            
                if (mysql_num_rows($query)>0){
                    while ($dados = mysql_fetch_array($query))
                    {
                        extract($dados);  
                        if($status == 'A'){
                            $status_campo = $status_list[0];
                        }
                        elseif($status == 'C'){
                            $status_campo = $status_list[1];
                        }
                        $nome_paceiro = '';
                        if(!empty($id_parceiro_painel)){
                            $sql_par        = "SELECT nome'nome_parceiro' FROM parceiros 
                                        WHERE id_parceiro = $id_parceiro_painel";
                            $query_par      = mysql_query($sql_par, $banco_painel);

                            $nome_paceiro = mysql_result($query_par, 0,0);
                        }

                        $sql_ver_cli    = "SELECT pro.id_produto'id_produto_cliente' FROM produtos_grupos prog
                        JOIN produtos pro ON prog.id_produto = pro.id_produto
                        JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                        WHERE bpro.slug = '$slug'
                        GROUP BY pro.id_produto";
                        $query_ver_cli  = mysql_query($sql_ver_cli, $banco_painel);
                                    
                        if (mysql_num_rows($query_ver_cli)>0){
                            $id_produto_cliente = mysql_result($query_ver_cli, 0,'id_produto_cliente');
                        }

                            $records["data"][] = array(
                            $id_venda,
                            $nome,
                            $cpf,
                            $numero_sorteio,
                            converte_data($data_sorteio),
                            $nome_paceiro,
                            '<p><span class="label label-sm label-'.(key($status_campo)).'">('.$tipo_movimento.') '.(current($status_campo)).'</span></p> '.$depen.' '.$penden,
                        '<a href="inc/ver_cliente.php?id_cliente='.$id_venda.'&id_produto='.$id_produto_cliente.'&tipo='.$tipo.'&status='.(current($status_campo)).'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                        );
                    }
                    mysql_close($banco_produto);
                }
                
                
            }
        }

     }elseif($item == 'grupos_parceiros'){  
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
            $sql_contar     = "SELECT COUNT(*) FROM grupos_parceiros
                                WHERE (nome like '%$busca%')";
            $query_contar   = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            
            $sql        = "SELECT * FROM grupos_parceiros
                            WHERE (nome like '%$busca%')
                                LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    if($del == 'N'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                    
                     $records["data"][] = array(
                      $id_parceiro,
                      $nome,
                      $cnpj."".$cpf,
                      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                      '<a href="inc/ver_parceiro.php?id_parceiro='.$id_parceiro.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                   );
                }
            }
            
        }
        else{
            $sql_contar        = "SELECT COUNT(*) FROM grupos_parceiros";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            
            $sql        = "SELECT * FROM grupos_parceiros
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
            
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    if($del == 'N'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }

                    $sql_parceiro        = "SELECT COUNT(par.id_parceiro) FROM grupos_parceiros gpar
                    JOIN parceiros_grupos par ON gpar.id_grupo_parceiro = par.id_grupo_parceiro
                    JOIN parceiros pa ON par.id_parceiro = pa.id_parceiro
                    WHERE gpar.id_grupo_parceiro = $id_grupo_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $total_parceiro = 0;                
                    if (mysql_num_rows($query_parceiro)>0){
                        $total_parceiro = mysql_result($query_parceiro, 0,0);
                    }

                     $records["data"][] = array(
                      $id_grupo_parceiro,
                      $nome,
                      $total_parceiro,
                      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                      '<a href="listar.php?item=grupo_parceiro&id_grupo_parceiro='.$id_grupo_parceiro.'" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Listar&nbsp;</a><a href="editar.php?item=grupos_parceiros&id='.$id_grupo_parceiro.'&tipo=grupo_parceiro" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Editar</a>',
                   );
                }
                
            }
        }

     }elseif($item == 'grupo_parceiro'){  
     
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
            $sql_contar     = "SELECT COUNT(*) FROM parceiros pa
                                JOIN parceiros_grupos pg ON pa.id_parceiro = pg.id_parceiro
                                WHERE pg.id_grupo_parceiro = $id_grupo_parceiro AND (pa.id_parceiro like '%$busca%' OR pa.nome like '%$busca%' OR pa.cpf like '%$busca%'
                                OR pa.cnpj like '%$busca%' OR pa.razao_social like '%$busca%' OR pa.cidade like '%$busca%' )";
            $query_contar   = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            
            $sql        = "SELECT * FROM parceiros pa
                                JOIN parceiros_grupos pg ON pa.id_parceiro = pg.id_parceiro
                                WHERE pg.id_grupo_parceiro = $id_grupo_parceiro AND (pa.id_parceiro like '%$busca%' OR pa.nome like '%$busca%' OR pa.cpf like '%$busca%'
                                OR pa.cnpj like '%$busca%' OR pa.razao_social like '%$busca%' OR pa.cidade like '%$busca%' )
                                LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    
                    if($del == 'N'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                    
                     $records["data"][] = array(
                      $id_parceiro,
                      $nome,
                      $cnpj."".$cpf,
                      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                      '<a href="inc/ver_parceiro.php?id_parceiro='.$id_parceiro.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                   );
                }
            }
            
        }
        else{
            $sql_contar        = "SELECT COUNT(*) FROM parceiros_grupos
                                WHERE id_grupo_parceiro = $id_grupo_parceiro";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            
            $sql        = "SELECT id_parceiro FROM parceiros_grupos
                            WHERE id_grupo_parceiro = $id_grupo_parceiro
                            LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    $sql_parceiro        = "SELECT nome, cpf, cnpj, cidade, estado FROM parceiros
                                        WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                       
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome        = mysql_result($query_parceiro, 0,'nome');
                        $cpf         = mysql_result($query_parceiro, 0,'cpf');
                        $cnpj        = mysql_result($query_parceiro, 0,'cnpj');
                        $cidade      = mysql_result($query_parceiro, 0,'cidade');
                        $estado      = mysql_result($query_parceiro, 0,'estado');
                    }
                    
                    
                     $records["data"][] = array(
                      $id_parceiro,
                      $nome,
                      $cpf."".$cnpj,
                      $cidade."/".$estado,
                      '<a href="inc/ver_parceiro.php?id_parceiro='.$id_parceiro.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                   );
                }
                
            }
        }

     }elseif($item == 'usuarios'){        
        $where = '';
        if($nivel_usuario == 'P'){
            $where .= " AND id_parceiro = '$usr_parceiro' AND nivel <> 'A'";
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
            $sql_contar     = "SELECT COUNT(*) FROM usuarios
                                WHERE (id_usuario like '%$busca%' OR nome like '%$busca%' OR login like '%$busca%') $where";
            $query_contar   = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            
            $sql        = "SELECT * FROM usuarios
                            WHERE (id_usuario like '%$busca%' OR nome like '%$busca%' OR login like '%$busca%') $where
                                LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    
                    if($del == 'N'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }

                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                        WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                       
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
                    $nome_filial = '';
                    if($id_filial > 0){
                        $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                                            WHERE id_filial = $id_filial";
                        $query_filial      = mysql_query($sql_filial, $banco_painel);
                       
                        if (mysql_num_rows($query_filial)>0){
                            $nome_filial       = mysql_result($query_filial, 0,'nome_filial');
                        }
                    }
                    
                    if($nivel == 'A'){
                        $exibe_nivel = 'Administrador';
                    }elseif($nivel == 'P'){
                        $exibe_nivel = 'Parceiro';
                    }elseif($nivel == 'U'){
                        $exibe_nivel = 'Usu�rio';
                    }
                    $nome = strtr(strtoupper($nome),"������������������������������","������������������������������");
                    $md5_id_usuario = md5($id_usuario);
                     $records["data"][] = array(
                      $id_usuario,
                      $nome,
                      "(".$id_parceiro.") ".$nome_parceiro,
                      $nome_filial,
                      $exibe_nivel,
                      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                      '<a href="editar.php?item=usuarios&id='.$md5_id_usuario.'&tipo=usuario" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Editar</a>',
                   );
                }
                
            }
            
        }
        else{
            $sql_contar        = "SELECT COUNT(*) FROM usuarios
                                   WHERE ativo = 'S' $where ";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
            array("success" => "Ativo"),
            array("danger" => "Inativo")
            );
            
            $sql        = "SELECT * FROM usuarios
            WHERE ativo = 'S' $where 
            ORDER BY id_usuario DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    
                    if($del == 'N'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }

                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                        WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                       
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
                    $nome_filial = '';
                    if($id_filial > 0){
                        $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                                            WHERE id_filial = $id_filial";
                        $query_filial      = mysql_query($sql_filial, $banco_painel);
                       
                        if (mysql_num_rows($query_filial)>0)
                        {
                            $nome_filial       = mysql_result($query_filial, 0,'nome_filial');
                        }
                    }
                    
                    if($nivel == 'A'){
                        $exibe_nivel = 'Administrador';
                    }elseif($nivel == 'P'){
                        $exibe_nivel = 'Parceiro';
                    }elseif($nivel == 'U'){
                        $exibe_nivel = 'Usu�rio';
                    }
                    $nome = strtr(strtoupper($nome),"������������������������������","������������������������������");
                    $md5_id_usuario = md5($id_usuario);
                     $records["data"][] = array(
                      $id_usuario,
                      $nome,
                      "(".$id_parceiro.") ".$nome_parceiro,
                      $nome_filial,
                      $exibe_nivel,
                      '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                      '<a href="editar.php?item=usuarios&id='.$md5_id_usuario.'&tipo=usuario" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Editar</a>',
                   );
                }
                
            }
        
        }
        
     }elseif($item == 'faturamentos'){        
        
        $where = '';

            $sql_contar        = "SELECT COUNT(*) FROM faturamentos";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT * FROM faturamentos
            ORDER BY id_faturamento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);                
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
                    
                    $sql_grupo           = "SELECT nome'nome_plano' FROM grupos_produtos
                                         WHERE id_grupo_produto = $id_grupo_produto";
                    $query_grupo         = mysql_query($sql_grupo, $banco_painel);
                    $nome_plano = '';   
                    if (mysql_num_rows($query_grupo)>0){
                        $nome_plano      = mysql_result($query_grupo, 0,'nome_plano');
                    }
                    
                    $calculo_soma_total = $soma_total * $quantidade_total;
                    
                    if($prazo == 0){
                        $exibe_prazo = "Recorr.";
                    }elseif($prazo > 0){
                        $exibe_prazo = $prazo." meses";
                    }else{
                        $exibe_prazo = "-";
                    }
                    
                    if($mes_referencia == '1'){
                        $numero_mes_referencia = 'Janeiro';
                    }elseif($mes_referencia == '2'){
                        $numero_mes_referencia = 'Fevereiro';
                    }elseif($mes_referencia == '3'){
                        $numero_mes_referencia = 'Mar�o';
                    }elseif($mes_referencia == '4'){
                        $numero_mes_referencia = 'Abril';
                    }elseif($mes_referencia == '5'){
                        $numero_mes_referencia = 'Maio';
                    }elseif($mes_referencia == '6'){
                        $numero_mes_referencia = 'Junho';
                    }elseif($mes_referencia == '7'){
                        $numero_mes_referencia = 'Julho';
                    }elseif($mes_referencia == '8'){
                        $numero_mes_referencia = 'Agosto';
                    }elseif($mes_referencia == '9'){
                        $numero_mes_referencia = 'Setembro';
                    }elseif($mes_referencia == '10'){
                        $numero_mes_referencia = 'Outubro';
                    }elseif($mes_referencia == '11'){
                        $numero_mes_referencia = 'Novembro';
                    }elseif($mes_referencia == '12'){
                        $numero_mes_referencia = 'Dezembro';
                    }
                    
                     $records["data"][] = array(
                     $id_faturamento,
                     $nome_parceiro,
                     $exibe_prazo." - ".$nome_plano,
                     converte_data($periodo_inicio)." - ".converte_data($periodo_fim),
                     $mes_referencia." - ".$numero_mes_referencia,
                     $quantidade_total,
                     "R$ ".$calculo_soma_total,
                     $parcelas,
                     converte_data($data_cadastro),
                   );
                }
                
            }
        
    }elseif($item == 'pagamentos'){        
        
        $where = '';

            $sql_contar        = "SELECT COUNT(*) FROM pagamentos";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT * FROM pagamentos
            ORDER BY id_pagamento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
            
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);                
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
                    
                    $sql_faturamento     = "SELECT id_filial FROM faturamentos
                                         WHERE id_faturamento = $id_faturamento";
                    $query_faturamento   = mysql_query($sql_faturamento, $banco_painel);
                    $id_filial = '';   
                    if (mysql_num_rows($query_faturamento)>0){
                        $id_filial        = mysql_result($query_faturamento, 0, 0);
                    }

                    $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                                         WHERE id_filial = '$id_filial'";
                    $query_filial      = mysql_query($sql_filial, $banco_painel);
                    $nome_filial = '';   
                    if (mysql_num_rows($query_filial)>0){
                        $nome_filial        = mysql_result($query_filial, 0, 'nome_filial');
                    }

                    $calculo_soma_total = $soma_total * $quantidade_total;
                    
                    if($prazo == 0){
                        $exibe_prazo = "Recorr.";
                    }elseif($prazo > 0){
                        $exibe_prazo = $prazo." meses";
                    }else{
                        $exibe_prazo = "-";
                    }
                    
                    $status_list = array(
                        array("green" => "Pago"),
                        array("red" => "Receber")
                    );
                    
                    if($pago == 'N'){
                        $status = $status_list[1];
                    }
                    else{
                        $status = $status_list[0];
                    }
                    
                    $agora 			= date("Y-m-d");
                    $exibe_ven = '';        
                    if(strtotime($data_vencimento) < strtotime($agora)){
                        $exibe_ven = " <span class=\"label label-sm label-danger\">V</span>";
                    }

                     $records["data"][] = array(
                     $id_pagamento,
                     $nome_parceiro." - ".$nome_filial,
                     $obs,
                     /*$exibe_prazo."-".$nome_plano,*/
                     converte_data($data_vencimento)." ".$exibe_ven,
                     $parcela." de ".$total_parcelas,
                     db_moeda($valor_parcela),
                     '<a href="inc/ver_recebimento.php?id_pagamento='.$id_pagamento.'&recebimento_faturamento=S" id="bt_pagamento_'.$id_pagamento.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a>',
                     converte_data($data_cadastro),
                   );
                }
                
            }
        
   }elseif($item == 'boletos_clientes'){        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
        }else{

        $where = '';
        if($nivel_usuario == 'P'){
            $where = " AND (id_parceiro = '$usr_parceiro' OR id_parceiro_recebimento = '$usr_parceiro')";
        }
        
        if($nivel_usuario == 'U'){
            $where = " AND (id_usuario = '$usr_id' OR id_usuario_recebimento = '$usr_id')";
        }
            $agora 			= date("Y-m-d");
            $agora_normal   = date('d-m-Y');
            if($tipo_filtro == 'neste_mes'){
                $mes = date("m");      // M�s desejado, pode ser por ser obtido por POST, GET, etc.
                $ano = date("Y"); // Ano atual
                $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // M�gica, plim!
                
                $data_final = $ano.'-'.$mes;
                
                $sql_verifica_filtro = "AND data_vencimento LIKE '%$data_final%'";
                
            }elseif($tipo_filtro == 'ultimo_mes'){
                //$data = somar_datas( '-1', 'm');
                $data_proxima = date('d/m/Y', strtotime('-1 month', strtotime($agora_normal)));
                //$data_ultimo_dia = date('t/m/Y', strtotime($data));
                
                $partes = explode("/", $data_proxima);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    $data_final = $ano_referencia.'-'.$mes_referencia;
                //$data_anterior = converte_data($data_anterior);
                
                $sql_verifica_filtro = "AND data_vencimento LIKE '%$data_final%'";
            }elseif($tipo_filtro == 'proximo_mes'){
                //$data = somar_datas( '-1', 'm');
                $data_proxima = date('d/m/Y', strtotime('+1 month', strtotime($agora_normal)));
                //$data_ultimo_dia = date('t/m/Y', strtotime($data));
                
                $partes = explode("/", $data_proxima);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    $data_final = $ano_referencia.'-'.$mes_referencia;
                //$data_anterior = converte_data($data_anterior);
                
                $sql_verifica_filtro = "AND data_vencimento LIKE '%$data_final%'";
            }elseif($tipo_filtro == 'todos'){
                $sql_verifica_filtro = "";        
            }

            $sql_contar        = "SELECT COUNT(*) FROM boletos_clientes
                                WHERE status_boleto <> 2 $sql_verifica_filtro $where ";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
                array("green" => "Pago"),
                array("red" => "Receber")
            );
            
            $sql        = "SELECT * FROM boletos_clientes
            WHERE status_boleto <> 2 $sql_verifica_filtro $where
            ORDER BY data_vencimento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query          = mysql_query($sql, $banco_painel);
            $query_contar   = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){ 
                while ($dados = mysql_fetch_array($query_contar))
                {
                    $valor_parcela_soma = $dados['valor_parcela'];
                    $somar_valores = $valor_parcela_soma + $somar_valores;
                }
                
                $records["data"][] = array(
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     db_moeda($somar_valores),
                     '##',
                     '##',
                   );
                   
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
               
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
  
                    
                $sql_cliente_ordem        = "SELECT ordem_pedido FROM ordem_pedidos
                                             WHERE id_ordem_pedido = $id_ordem_pedido";
                $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                   
                if (mysql_num_rows($query_cliente_ordem)>0){
                    $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                }        
       
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                $pegar_nome_ok = false;
                $nome_cliente = '';
                for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++){
                    $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                    $id_base = $array_ids_base_vendas[0];
                    $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                    
                    // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                    $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                WHERE bpro.id_base_produto = $id_base";
    
                    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                    
                    if (mysql_num_rows($query_base)>0){
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
                        
                        if($pegar_nome_ok == false){
                            
                            $sql_venda  = "SELECT c.nome, v.metodo_pagamento FROM vendas v
                                        JOIN clientes c ON v.id_cliente = c.id_cliente
                                        WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                            
                            if (mysql_num_rows($query_venda)>0)
                            {
                                $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $pegar_nome_ok = true;
                            }
                        
                        }
                    
                    }elseif($slug == 'sorteio_ead'){
                        if($pegar_nome_ok == false){
                            $sql_venda   = "SELECT c.nome, v.metodo_pagamento FROM vendas_painel v
                                        JOIN vendas c ON v.id_venda = c.id_venda
                                        JOIN titulos t ON c.id_titulo = t.id_titulo
                                        WHERE v.id_venda_painel = $ids_vendas[0] AND c.status = 'A'";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 5");
                            if (mysql_num_rows($query_venda)>0)
                            {
                                $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $pegar_nome_ok = true;
                            }
                        }
                    }
                    
                }
                    $status_list = array(
                        array("green" => "Pago"),
                        array("red" => "Receber")
                    );
                    
                    if($pago == 'N'){
                        $status = $status_list[1];
                    }
                    else{
                        $status = $status_list[0];
                    }
                    
                    $pg_html_exibe = 'hide';
                    if($pago == 'S'){
                        $pg_html_exibe = '';
                    }
                    
                    if($metodo_pagamento == 'BO'){
                        $html_metodo_pagamento = 'BOLETO';
                    }elseif($metodo_pagamento == 'MA'){
                        $html_metodo_pagamento = 'MAQUINA';
                    }elseif($metodo_pagamento = 'ON'){
                        $html_metodo_pagamento = 'ON-LINE';
                    }
                    
                    $html_tipo = '';
                    if($entrada == 'S'){
                        $html_tipo = "ENTRADA-".$tipo_recebimento;
                    }else{
                        $html_tipo = $html_metodo_pagamento;
                    }
                    
                    $html_bt_pago = '';
                    if($metodo_pagamento == 'BO' AND $tipo_boleto == 'LOJA'){
                        $html_bt_pago = '<a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>';
                    }
                    
                     $records["data"][] = array(
                     $id_boleto,
                     $nome_cliente,
                     $nome_parceiro,
                     $parcela.' / '.$total_parcelas,
                     $html_tipo,
                     $valor_parcela,
                     $valor_parcela,
                     converte_data($data_vencimento),
                     $html_bt_pago,
                   );
                }
                
            }
     }   
    }elseif($item == 'boletos_vencidos_clientes'){        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
        }else{
        $where = '';
        $sql_grupo_ordem = '';
        if($nivel_usuario == 'P'){
            $where = " AND (id_parceiro = '$usr_parceiro' OR id_parceiro_recebimento = '$usr_parceiro')";
        }
        
        if($nivel_usuario == 'U'){
            $where = " AND (id_usuario = '$usr_id' OR id_usuario_recebimento = '$usr_id')";
        }
            $agora 			= date("Y-m-d");
            $agora_normal   = date('d-m-Y');
            if($tipo_filtro == 'neste_mes'){
                $mes = date("m");      // M�s desejado, pode ser por ser obtido por POST, GET, etc.
                $ano = date("Y"); // Ano atual
                $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // M�gica, plim!
                
                $data_final = $ano.'-'.$mes.'-01';
                
                $sql_verifica_filtro = "data_vencimento BETWEEN '$data_final' AND '$agora'";
                
            }elseif($tipo_filtro == 'ultimo_mes'){
                $data_proxima = date('d/m/Y', strtotime('-1 month', strtotime($agora_normal)));
                $partes = explode("/", $data_proxima);
                //$dia = $partes[0];
                $mes_referencia = $partes[1];
                $ano_referencia = $partes[2];
                $data_final = $ano_referencia.'-'.$mes_referencia;
                $sql_verifica_filtro = "data_vencimento LIKE '%$data_final%'";
            }elseif($tipo_filtro == 'todos'){
                $sql_verifica_filtro = "data_vencimento < '$agora'";        
            }elseif($tipo_filtro == 'mais_tres_meses'){
                $data_anterior = date('d/m/Y', strtotime('-2 month', strtotime($agora_normal)));
                $data_anterior_array = explode('/', $data_anterior);
                $data_anterior_array_like = $data_anterior_array[2]."-".$data_anterior_array[1]."-".$data_anterior_array[0];
                $sql_verifica_filtro = "data_vencimento < '$data_anterior_array_like' AND entrada = 'N'";
                $sql_grupo_ordem = 'GROUP BY id_ordem_pedido';
            }elseif($tipo_filtro == 'mais_trinta_dias'){
                $data_anterior = date('d/m/Y', strtotime('-30 days', strtotime($agora_normal)));
                $data_anterior_array = explode('/', $data_anterior);
                $data_anterior_array_like = $data_anterior_array[2]."-".$data_anterior_array[1]."-".$data_anterior_array[0];
                $data_anterior_array_like_2 = $data_anterior_array[2]."-".$data_anterior_array[1]."-".$data_anterior_array[0];
                $sql_verifica_filtro = "data_vencimento BETWEEN '$data_anterior_array_like_2' AND '$data_anterior_array_like' AND entrada = 'N'";
                $sql_grupo_ordem = 'GROUP BY id_ordem_pedido';
            }

            $sql_contar        = "SELECT COUNT(*) FROM boletos_clientes
                                    WHERE status_boleto = 0 AND $sql_verifica_filtro AND pago = 'N' $where";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
                array("green" => "Pago"),
                array("red" => "Receber")
            );
            
            // status_boleto = 0 (ativo)
            // status_boleto = 1 (concluido)
            // status_boleto = 2 (cancelado)
            $sql        = "SELECT * FROM boletos_clientes
            WHERE status_boleto = 0 AND $sql_verifica_filtro AND pago = 'N' $where
            $sql_grupo_ordem 
            ORDER BY data_vencimento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            //error_log($sql);
            $query          = mysql_query($sql, $banco_painel);
            $query_contar   = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                 $somar_valores = 0;
                while ($dados = mysql_fetch_array($query_contar))
                {
                    $valor_parcela_soma = $dados['valor_parcela'];
                    $somar_valores = $valor_parcela_soma + $somar_valores;
                }
                
                $records["data"][] = array(
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     db_moeda($somar_valores),
                     '##',
                     '##',
                   );
                   
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
               
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }

                $sql_cliente_ordem        = "SELECT ordem_pedido FROM ordem_pedidos
                                             WHERE id_ordem_pedido = $id_ordem_pedido";
                $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                   
                if (mysql_num_rows($query_cliente_ordem)>0){
                    $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                }        
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                $pegar_nome_ok = false;
                $nome_cliente = '';
                for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++){
                    $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                    $id_base = $array_ids_base_vendas[0];
                    $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                    
                    // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                    $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                WHERE bpro.id_base_produto = $id_base";
    
                    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                    
                    if (mysql_num_rows($query_base)>0){
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
                        if($pegar_nome_ok == false){
                            $sql_venda  = "SELECT c.id_cliente, c.id_filial, c.id_produto, c.data_termino, c.nome, c.telefone, c.celular, c.cep, c.endereco, c.numero, c.complemento, c.bairro, c.cidade, v.metodo_pagamento FROM vendas v
                                        JOIN clientes c ON v.id_cliente = c.id_cliente
                                        WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                            
                            if (mysql_num_rows($query_venda)>0){
                                $id_cliente             = mysql_result($query_venda, 0, 'id_cliente');
                                $id_filial              = mysql_result($query_venda, 0, 'id_filial');
                                $id_produto_cliente     = mysql_result($query_venda, 0, 'id_produto');
                                $nome_cliente           = mysql_result($query_venda, 0, 'nome');
                                $data_termino           = mysql_result($query_venda, 0, 'data_termino');
                                $telefone_cliente       = mysql_result($query_venda, 0, 'telefone');
                                $celular_cliente        = mysql_result($query_venda, 0, 'celular');
                                $cep_cliente            = mysql_result($query_venda, 0, 'cep');
                                $endereco_cliente       = mysql_result($query_venda, 0, 'endereco');
                                $numero_cliente         = mysql_result($query_venda, 0, 'numero');
                                $complemento_cliente    = mysql_result($query_venda, 0, 'complemento');
                                $bairro_cliente         = mysql_result($query_venda, 0, 'bairro');
                                $cidade_cliente         = mysql_result($query_venda, 0, 'cidade');
                                $metodo_pagamento       = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $pegar_nome_ok = true;
                                
                                $sql_plano        = "SELECT nome'nome_plano' FROM grupos_produtos
                                         WHERE id_grupo_produto = $id_produto_cliente";
                                $query_plano      = mysql_query($sql_plano, $banco_painel);
                                $nome_plano = '';
                                if (mysql_num_rows($query_plano)>0){
                                    $nome_plano        = mysql_result($query_plano, 0,'nome_plano');
                                }
                                $nome_filial = '';  
                                if($id_filial != ''){
                                    $sql_filial             = "SELECT nome'nome_filial' FROM filiais
                                         WHERE id_filial = $id_filial";
                                    $query_filial               = mysql_query($sql_filial, $banco_painel);
                                     
                                    if (mysql_num_rows($query_filial)>0){
                                        $nome_filial        = mysql_result($query_filial, 0,'nome_filial');
                                    }
                                }
                                
                            }
                        
                        }
                    
                    }
                    
                }
                    $status_list = array(
                        array("green" => "Pago"),
                        array("red" => "Receber")
                    );
                    
                    if($pago == 'N'){
                        $status = $status_list[1];
                    }
                    else{
                        $status = $status_list[0];
                    }
                    $html_tipo = '';
                    
                    $pg_html_exibe = 'hide';
                    if($pago == 'S'){
                        $pg_html_exibe = '';
                    }
                    
                    if($metodo_pagamento == 'BO'){
                        $html_metodo_pagamento = 'BOLETO';
                    }elseif($metodo_pagamento == 'MA'){
                        $html_metodo_pagamento = 'MAQUINA';
                    }elseif($metodo_pagamento = 'ON'){
                        $html_metodo_pagamento = 'ON-LINE';
                    }
                    
                    $html_tipo = '';
                    if($entrada == 'S'){
                        $html_tipo = "ENTRADA-".$tipo_recebimento;
                    }else{
                        $html_tipo = $html_metodo_pagamento;
                    }
                    
                    if((strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00')){
                        $status_plano = 'ATIVO';
                    }
                    elseif(strtotime($data_termino) == strtotime($agora)){
                        $status_plano = 'ATIVO';
                    }
                    else{
                        $status_plano = 'INATIVO';
                    }
                    
                    $html_bt_pago = '';
                    if($metodo_pagamento == 'BO' AND $tipo_boleto == 'LOJA'){
                        $html_bt_pago = '<a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>';
                    }
                    //c.telefone, c.celular, c.cep, c.endereco, c.numero, c.complemento, c.bairro, c.cidade,
                     $records["data"][] = array(
                     "<span style='font-size: 10px;'>$id_boleto</span>",
                     "<span style='font-size: 10px;'>$nome_cliente</span>",
                     "<span style='font-size: 10px;'>$nome_parceiro $nome_filial</span>",
                     "<span style='font-size: 10px;'>$telefone_cliente / $celular_cliente </span>",
                     "<span style='font-size: 10px;'> $endereco_cliente, $numero_cliente $complemento_cliente</span>",
                     "<span style='font-size: 10px;'>$bairro_cliente</span>",
                     "<span style='font-size: 10px;'>$cidade_cliente</span>",
                     "<span style='font-size: 10px;'>$cep_cliente</span>",
                     "<span style='font-size: 10px;'>$parcela / $total_parcelas</span>",
                     "<span style='font-size: 10px;'>$html_tipo</span>",
                     "<span style='font-size: 10px;'>$valor_parcela<span>",
                     "<span style='font-size: 10px;'>".converte_data($data_vencimento)."</span>",
                     "<span style='font-size: 10px;'>$nome_plano - <a href=\"inc/ver_cliente.php?id_cliente=$id_cliente&id_produto=6&tipo=produto\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn btn-sm btn-outline grey-salsa\"><i class=\"fa fa-search\"></i> Ver cliente</a></span>",
                   );
                }
                
            }
     }   
    }elseif($item == 'boletos_avencermes_clientes'){        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
        }else{
            $where = '';
            if($nivel_usuario == 'P'){
                $where = " AND (id_parceiro = '$usr_parceiro' OR id_parceiro_recebimento = '$usr_parceiro')";
            }
            
            if($nivel_usuario == 'U'){
                $where = " AND (id_usuario = '$usr_id' OR id_usuario_recebimento = '$usr_id')";
            }
                
            $agora 			= date("Y-m-d");
            $agora_normal   = date('d-m-Y');
            if($tipo_filtro == 'hoje'){
                $sql_verifica_filtro = "data_pagamento = '$agora'";
            }elseif($tipo_filtro == 'amanha'){
                $data_ontem = date('d/m/Y', strtotime('+1 day', strtotime($agora_normal)));
                $data_ontem = converte_data($data_ontem);
                $sql_verifica_filtro = "data_vencimento = '$data_ontem'";
            }elseif($tipo_filtro == 'proximos_7'){
                $data_proximo = date('d/m/Y', strtotime('+7 days', strtotime($agora_normal)));
                $data_proximo = converte_data($data_proximo);
                $sql_verifica_filtro = "data_vencimento BETWEEN '$agora' AND '$data_proximo'";
            }elseif($tipo_filtro == 'proximos_30'){
                $data_proximo = date('d/m/Y', strtotime('+30 days', strtotime($agora_normal)));
                $data_proximo = converte_data($data_proximo);
                $sql_verifica_filtro = "data_vencimento BETWEEN '$agora' AND '$data_proximo'";
            }elseif($tipo_filtro == 'neste_mes'){
                $mes = date("m");      // M�s desejado, pode ser por ser obtido por POST, GET, etc.
                $ano = date("Y"); // Ano atual
                $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // M�gica, plim!
                $data_final = $ano.'-'.$mes.'-'.$ultimo_dia;
                $sql_verifica_filtro = "data_vencimento BETWEEN '$agora' AND '$data_final'";
            }elseif($tipo_filtro == 'proximo_mes'){
                $data_proxima = date('d/m/Y', strtotime('+1 month', strtotime($agora_normal)));
                $partes = explode("/", $data_proxima);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    $data_final = $ano_referencia.'-'.$mes_referencia;
                
                $sql_verifica_filtro = "data_vencimento LIKE '%$data_final%'";
            }

                $sql_contar        = "SELECT COUNT(*) FROM boletos_clientes
                                    WHERE status_boleto = 0 AND $sql_verifica_filtro AND pago = 'N' $where ";
                $query_contar      = mysql_query($sql_contar, $banco_painel);
                
                $iTotalRecords = mysql_result($query_contar, 0,0);
                $iDisplayLength = intval($_REQUEST['length']);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
                $iDisplayStart = intval($_REQUEST['start']);
                $sEcho = intval($_REQUEST['draw']);
                
                $records = array();
                $records["data"] = array(); 
                
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                
                $status_list = array(
                    array("green" => "Pago"),
                    array("red" => "Receber")
                );
                
                // status_boleto = 0 (ativo)
                // status_boleto = 1 (concluido)
                // status_boleto = 2 (cancelado)
                $sql        = "SELECT * FROM boletos_clientes
                WHERE status_boleto = 0 AND $sql_verifica_filtro AND pago = 'N' $where
                ORDER BY data_vencimento DESC
                            LIMIT $iDisplayStart,$iDisplayLength";
                $query          = mysql_query($sql, $banco_painel);
                $query_contar   = mysql_query($sql, $banco_painel);           
                if (mysql_num_rows($query)>0){
                    while ($dados = mysql_fetch_array($query_contar))
                    {
                        $valor_parcela_soma = $dados['valor_parcela'];
                        $somar_valores = $valor_parcela_soma + $somar_valores;
                    }
                    
                    $records["data"][] = array(
                        '##',
                        '##',
                        '##',
                        '##',
                        '##',
                        '##',
                        db_moeda($somar_valores),
                        '##',
                        '##',
                        '##',
                    );
                    
                    while ($dados = mysql_fetch_array($query))
                    {
                        extract($dados);  
                
                        $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                            WHERE id_parceiro = $id_parceiro";
                        $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                        $nome_parceiro = '';   
                        if (mysql_num_rows($query_parceiro)>0){
                            $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                        }
    
                        
                    $sql_cliente_ordem        = "SELECT ordem_pedido FROM ordem_pedidos
                                                WHERE id_ordem_pedido = $id_ordem_pedido";
                    $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                    
                    if (mysql_num_rows($query_cliente_ordem)>0){
                        $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                    }        
        
                    $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                    
                    $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                    $pegar_nome_ok = false;
                    $nome_cliente = '';
                    for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++){
                        $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                        $id_base = $array_ids_base_vendas[0];
                        $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                        
                        // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                        $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                    JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                    WHERE bpro.id_base_produto = $id_base";
        
                        $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                        
                        if (mysql_num_rows($query_base)>0){
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
                            if($pegar_nome_ok == false){
                                $sql_venda  = "SELECT c.nome, v.metodo_pagamento FROM vendas v
                                            JOIN clientes c ON v.id_cliente = c.id_cliente
                                            WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                                $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                                
                                if (mysql_num_rows($query_venda)>0)
                                {
                                    $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                    $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                    $pegar_nome_ok = true;
                                }
                            }
                        }
                    }
                        $status_list = array(
                            array("green" => "Pago"),
                            array("red" => "Receber")
                        );
                        
                        if($pago == 'N'){
                            $status = $status_list[1];
                        }
                        else{
                            $status = $status_list[0];
                        }
                        
                        $pg_html_exibe = 'hide';
                        if($pago == 'S'){
                            $pg_html_exibe = '';
                        }
                        $html_bt_pago = '';
                        
                        if($metodo_pagamento == 'BO'){
                            $html_metodo_pagamento = 'BOLETO';
                        }elseif($metodo_pagamento == 'MA'){
                            $html_metodo_pagamento = 'MAQUINA';
                        }elseif($metodo_pagamento = 'ON'){
                            $html_metodo_pagamento = 'ON-LINE';
                        }
                        
                        $html_tipo = '';
                        if($entrada == 'S'){
                            $html_tipo = "ENTRADA-".$tipo_recebimento;
                        }else{
                            $html_tipo = $html_metodo_pagamento;
                        }
                        
                        if($metodo_pagamento == 'BO' AND $tipo_boleto == 'LOJA'){
                            $html_bt_pago = '<a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>';
                        }
                        
                        $records["data"][] = array(
                        $id_boleto,
                        $nome_cliente,
                        $nome_parceiro,
                        $parcela.' / '.$total_parcelas,
                        $html_tipo,
                        $valor_parcela,
                        $valor_parcela,
                        '&nbsp;',
                        converte_data($data_vencimento),
                        $html_bt_pago,
                    );
                    }
                }
        }   
    }elseif($item == 'boletos_pagosmes_clientes'){  
        $somar_valores = 0;
        if(!empty($_POST['campofiltro']) OR !empty($_POST['parceiro_user_filtro']) OR !empty($_POST['filial_user_filtro']) OR !empty($_POST['filial_user_filtro'])){
            $where = '';
            $where_filial = '';
            $where_usuario = '';
            $where_tipo_movimento = '';
            $where_filtro = '';
            $sql_data_emissao = '';
            $sql_join_filial = '';
            $sql_verifica_filtro = '';

            $contar_busca   = strlen($busca);
            $agora 			= date("Y-m-d");
            
            if($tipo_filtro == 'personalizar_boletos_pagos'){
                $datas = explode(" - ", $periodo);
                $data1 = explode(" ", $datas[0]);
                $dia_data1 = $data1[0];
                $mes_data1 = substr($data1[1], 0, -1);
                $ano_data1 = $data1[2];
                
                if($mes_data1 == "Janeiro"){
                $mes_num_data1 = "01";
                }elseif($mes_data1 == "Fevereiro"){
                $mes_num_data1 = "02";
                }elseif($mes_data1 == "Marco"){
                $mes_num_data1 = "03";
                }elseif($mes_data1 == "Abril"){
                $mes_num_data1 = "04";
                }elseif($mes_data1 == "Maio"){
                $mes_num_data1 = "05";
                }elseif($mes_data1 == "Junho"){
                $mes_num_data1 = "06";
                }elseif($mes_data1 == "Julho"){
                $mes_num_data1 = "07";
                }elseif($mes_data1 == "Agosto"){
                $mes_num_data1 = "08";
                }elseif($mes_data1 == "Setembro"){
                $mes_num_data1 = "09";
                }elseif($mes_data1 == "Outubro"){
                $mes_num_data1 = "10";
                }elseif($mes_data1 == "Novembro"){
                $mes_num_data1 = "11";
                }else{
                $mes_num_data1 = "12";
                }
                
                $data1 = $ano_data1.'/'.$mes_num_data1.'/'.$dia_data1;
                $data1_convert = str_replace("/", "-", $data1);
                $data2 = explode(" ", $datas[1]);
                $dia_data2 = $data2[0];
                $mes_data2 = substr($data2[1], 0, -1);
                $ano_data2 = $data2[2];
                
                if($mes_data2 == "Janeiro"){
                $mes_num_data2 = "01";
                }elseif($mes_data2 == "Fevereiro"){
                $mes_num_data2 = "02";
                }elseif($mes_data2 == "Marco"){
                $mes_num_data2 = "03";
                }elseif($mes_data2 == "Abril"){
                $mes_num_data2 = "04";
                }elseif($mes_data2 == "Maio"){
                $mes_num_data2 = "05";
                }elseif($mes_data2 == "Junho"){
                $mes_num_data2 = "06";
                }elseif($mes_data2 == "Julho"){
                $mes_num_data2 = "07";
                }elseif($mes_data2 == "Agosto"){
                $mes_num_data2 = "08";
                }elseif($mes_data2 == "Setembro"){
                $mes_num_data2 = "09";
                }elseif($mes_data2 == "Outubro"){
                $mes_num_data2 = "10";
                }elseif($mes_data2 == "Novembro"){
                $mes_num_data2 = "11";
                }else{
                $mes_num_data2 = "12";
                }
                
                $data2 = $ano_data2.'/'.$mes_num_data2.'/'.$dia_data2;
                $data2_convert = str_replace("/", "-", $data2);

                $sql_verifica_filtro = "AND (bc.data_pagamento BETWEEN '$data1' AND '$data2')";
                if($todos_clientes_ativos == 'S'){
                    $sql_verifica_filtro = "AND bc.data_pagamento <= '$agora'";
                }
            }

            $verifica_filtro_cliente = false;
            
            if(!empty($parceiro_user_filtro)){
                $usr_parceiro = $parceiro_user_filtro;
                $verifica_filtro_cliente = true;
            }
            
            if(!empty($filial_user_filtro) AND $filial_user_filtro > 0){
                $verifica_filtro_cliente = true;
                $where_filial = "AND c.id_filial = '$filial_user_filtro'";
            }
            
            if(!empty($usuario_filtro) AND $usuario_filtro > 0){
                $verifica_filtro_cliente = true;
                $where_usuario = "AND bc.id_usuario = '$usuario_filtro'";
            }
            
            $where_tipo_movimento = "AND bc.tipo_movimento IN ('IN', 'AL')";

            if($nivel_usuario == 'P' OR $verifica_filtro_cliente == true){
                if($parceiro_user_filtro != 'todos'){
                  $where .= "AND (bc.id_parceiro = '$usr_parceiro') $where_usuario";   
                }
                
            }elseif($nivel_usuario == 'U'){
                $where .= "AND bc.id_parceiro = '$usr_parceiro' AND bc.id_usuario = '$usr_id'";
            }

            $agora 			= date("Y-m-d");
            $agora_normal   = date('d-m-Y');
            if($tipo_filtro == 'hoje'){
                $sql_verifica_filtro = "AND bc.data_pagamento = '$agora'";
            }elseif($tipo_filtro == 'ontem'){
                //$data = somar_datas( '-1', 'd');
                $data_ontem = date('d/m/Y', strtotime('-1 day', strtotime($agora_normal)));
                //$data_ontem = date('d/m/Y', strtotime($data));
                $data_ontem = converte_data($data_ontem);
                
                $sql_verifica_filtro = "AND bc.data_pagamento = '$data_ontem'";
                
            }elseif($tipo_filtro == 'ultimos_7'){

                $data_anterior = date('d/m/Y', strtotime('-7 days', strtotime($agora_normal)));
                $data_anterior = converte_data($data_anterior);
                $sql_verifica_filtro = "AND bc.data_pagamento BETWEEN '$data_anterior' AND '$agora'";

            }elseif($tipo_filtro == 'ultimos_30'){
                $data_anterior = date('d/m/Y', strtotime('-30 days', strtotime($agora_normal)));
                $data_anterior = converte_data($data_anterior);
                $sql_verifica_filtro = "AND bc.data_pagamento BETWEEN '$data_anterior' AND '$agora'";
            }elseif($tipo_filtro == 'neste_mes'){
                $mes = date("m");
                $ano = date("Y");
                $data_final = $ano.'-'.$mes;
                $sql_verifica_filtro = "AND bc.data_pagamento LIKE '%$data_final%'";
                
            }elseif($tipo_filtro == 'ultimo_mes'){
                $data_anterior = date('d/m/Y', strtotime('-1 month', strtotime($agora_normal)));
                $partes = explode("/", $data_anterior);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    $data_final = $ano_referencia.'-'.$mes_referencia;
                $sql_verifica_filtro = "AND bc.data_pagamento LIKE '%$data_final%'";
            }
            
            $sql_contar        = "SELECT COUNT(*) FROM boletos_clientes bc
            WHERE bc.id_boleto > 0 $sql_verifica_filtro AND bc.pago = 'S' $where";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
                array("green" => "Pago"),
                array("red" => "Receber")
            );
            
            // status_boleto = 0 (ativo)
            // status_boleto = 1 (concluido)
            // status_boleto = 2 (cancelado)
            $sql        = "SELECT bc.* FROM boletos_clientes bc
                        WHERE bc.id_boleto > 0 $sql_verifica_filtro AND bc.pago = 'S' $where
                        ORDER BY bc.data_pagamento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query          = mysql_query($sql, $banco_painel);
            $query_contar   = mysql_query($sql, $banco_painel);                
            if (mysql_num_rows($query)>0)
            {
                while ($dados = mysql_fetch_array($query_contar))
                {
                    $valor_parcela_soma = $dados['valor_recebido'];
                    $somar_valores = $valor_parcela_soma + $somar_valores; 
                }
                
                $records["data"][] = array(
                        '##',
                        '##',
                        '##',
                        '##',
                        '##',
                        '##',
                        db_moeda($somar_valores),
                        '##',
                        '##',
                        '##',
                    );
            
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados); 
                    $verifica_lista_filial = false;
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                            WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
                    
                $sql_cliente_ordem        = "SELECT ordem_pedido FROM ordem_pedidos
                                                WHERE id_ordem_pedido = $id_ordem_pedido";
                $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                    
                if (mysql_num_rows($query_cliente_ordem)>0){
                    $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                }        
        
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                $pegar_nome_ok = false;
                $nome_cliente = '';
                for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++){
                    $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                    $id_base = $array_ids_base_vendas[0];
                    $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                    
                    // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                    $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                WHERE bpro.id_base_produto = $id_base";
    
                    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                    
                    if (mysql_num_rows($query_base)>0){
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
                        if($pegar_nome_ok == false){
                            $sql_venda  = "SELECT v.metodo_pagamento, c.id_filial, c.nome FROM vendas v
                                        JOIN clientes c ON v.id_cliente = c.id_cliente
                                        WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                            
                            if (mysql_num_rows($query_venda)>0){
                                $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $id_filial          = mysql_result($query_venda, 0, 'id_filial');
                                $pegar_nome_ok = true;
                            }
                            if($id_filial == ''){
                                $id_filial = 0; 
                            }
                            if(!empty($filial_user_filtro) AND $filial_user_filtro > 0){
                                if($id_filial == $filial_user_filtro){
                                    $verifica_lista_filial = true;                                     
                                }    
                            }
                                
                            $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                                            WHERE id_filial = $id_filial";
                            $query_filial      = mysql_query($sql_filial, $banco_painel);
                            $nome_filial = '';   
                            if (mysql_num_rows($query_filial)>0){
                                $nome_filial        = mysql_result($query_filial, 0,'nome_filial');
                                if(!empty($filial_user_filtro) AND $filial_user_filtro > 0){ 
                                }
                            }
                        }
                    }
                }

                    $status_list = array(
                        array("green" => "Pago"),
                        array("red" => "Receber")
                    );
                    
                    $status_list_confirma = array(
                        array("info" => "Confirmar"),
                        array("danger" => "Cancelar")
                    );

                    $html_bt_confirma = '';
                    if($nivel_usuario == 'A'){
                        if($baixa_recebimento == 'S'){
                            $status_conf = $status_list_confirma[1];
                        }
                        else{
                            $status_conf = $status_list_confirma[0];
                        }
                    }

                    if($pago == 'N'){
                        $status = $status_list[1];
                    }
                    else{
                        $status = $status_list[0];
                    }
                    
                    $pg_html_exibe = 'hide';
                    if($pago == 'S'){
                        $pg_html_exibe = '';
                    }
                    
                    if($metodo_pagamento == 'BO'){
                        $html_metodo_pagamento = 'BOLETO';
                    }elseif($metodo_pagamento == 'MA'){
                        $html_metodo_pagamento = 'MAQUINA';
                    }elseif($metodo_pagamento = 'ON'){
                        $html_metodo_pagamento = 'ON-LINE';
                    }
                    
                    $html_tipo = '';
                    if($entrada == 'S'){
                        $html_tipo = "ENTRADA-".$tipo_recebimento;
                    }else{
                        $html_tipo = $tipo_recebimento.' '.$html_metodo_pagamento;
                    }
                    if(!empty($filial_user_filtro) AND $filial_user_filtro > 0){
                        if($verifica_lista_filial == true){
                            $records["data"][] = array(
                                $id_boleto,
                                $nome_cliente,
                                $nome_parceiro." ".$nome_filial,
                                $parcela.' / '.$total_parcelas,
                                $html_tipo,
                                $valor_parcela,
                                $valor_recebido,
                                converte_data($data_pagamento),
                                converte_data($data_vencimento),
                                '<a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>'.$html_bt_confirma,
                            );
                        } 
                    }
                    else{
                        $records["data"][] = array(
                        $id_boleto,
                        $nome_cliente,
                        $nome_parceiro." ".$nome_filial,
                        $parcela.' / '.$total_parcelas,
                        $html_tipo,
                        $valor_parcela,
                        $valor_recebido,
                        converte_data($data_pagamento),
                        converte_data($data_vencimento),
                        '<a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>'.$html_bt_confirma,
                        );
                    }
                }   
            }
        }else{
        $where = '';
        if($nivel_usuario == 'P'){
            $where = " AND (id_parceiro = '$usr_parceiro' OR id_parceiro_recebimento = '$usr_parceiro')";
        }
        
        if($nivel_usuario == 'U'){
            $where = " AND (id_usuario = '$usr_id' OR id_usuario_recebimento = '$usr_id')";
        }
        $agora 			= date("Y-m-d");
        $agora_normal   = date('d-m-Y');
        if($tipo_filtro == 'hoje'){
            $sql_verifica_filtro = "data_pagamento = '$agora'";
        }elseif($tipo_filtro == 'ontem'){
            //$data = somar_datas( '-1', 'd');
            $data_ontem = date('d/m/Y', strtotime('-1 day', strtotime($agora_normal)));
            //$data_ontem = date('d/m/Y', strtotime($data));
            $data_ontem = converte_data($data_ontem);
            
            $sql_verifica_filtro = "data_pagamento = '$data_ontem'";
            
        }elseif($tipo_filtro == 'ultimos_7'){
            
            //$data = somar_datas( '-7', 'd');
            $data_anterior = date('d/m/Y', strtotime('-7 days', strtotime($agora_normal)));
            //$data_anterior = date('d/m/Y', strtotime($data));
            $data_anterior = converte_data($data_anterior);
            
            $sql_verifica_filtro = "data_pagamento BETWEEN '$data_anterior' AND '$agora'";
            
            
        }elseif($tipo_filtro == 'ultimos_30'){
            //$data = somar_datas( '-30', 'd');
            $data_anterior = date('d/m/Y', strtotime('-30 days', strtotime($agora_normal)));
            //$data_anterior = date('d/m/Y', strtotime($data));
            $data_anterior = converte_data($data_anterior);
            
            $sql_verifica_filtro = "data_pagamento BETWEEN '$data_anterior' AND '$agora'";
        }elseif($tipo_filtro == 'neste_mes'){
            $mes = date("m");
            $ano = date("Y");
            $data_final = $ano.'-'.$mes;
            
            $sql_verifica_filtro = "data_pagamento LIKE '%$data_final%'";
            
        }elseif($tipo_filtro == 'ultimo_mes'){
            //$data = somar_datas( '-1', 'm');
            //$data_anterior = date('d/m/Y', strtotime('-1 month -2 days', strtotime($agora_normal)));
            $data_anterior = date('d/m/Y', time()-2678400);
            //$data_ultimo_dia = date('t/m/Y', strtotime($data));
            
            //error_log($data_anterior);
            $partes = explode("/", $data_anterior);
                //$dia = $partes[0];
                $mes_referencia = $partes[1];
                $ano_referencia = $partes[2];
                $data_final = $ano_referencia.'-'.$mes_referencia;
            //$data_anterior = converte_data($data_anterior);
            $sql_verifica_filtro = "data_pagamento LIKE '%$data_final%'";
        }elseif($tipo_filtro == 'personalizar_boletos_pagos'){
            $sql_verifica_filtro = "data_pagamento > '$agora'";
        }
            
        $sql_contar        = "SELECT COUNT(*) FROM boletos_clientes
                            WHERE id_boleto > 0 AND $sql_verifica_filtro AND pago = 'S' $where ";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $status_list = array(
            array("green" => "Pago"),
            array("red" => "Receber")
        );

        // status_boleto = 0 (ativo)
        // status_boleto = 1 (concluido)
        // status_boleto = 2 (cancelado)
        $sql        = "SELECT * FROM boletos_clientes
                    WHERE id_boleto > 0 AND $sql_verifica_filtro AND pago = 'S' $where
        ORDER BY data_pagamento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query          = mysql_query($sql, $banco_painel);
        $query_contar   = mysql_query($sql, $banco_painel);                
        if (mysql_num_rows($query)>0)
        {
            while ($dados = mysql_fetch_array($query_contar))
            {
                $valor_parcela_soma = $dados['valor_recebido'];
                $somar_valores = $valor_parcela_soma + $somar_valores; 
            }
            
            $records["data"][] = array(
                    '##',
                    '##',
                    '##',
                    '##',
                    '##',
                    '##',
                    db_moeda($somar_valores),
                    '##',
                    '##',
                    '##',
                );
        
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados); 
            
                $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                        WHERE id_parceiro = $id_parceiro";
                $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                $nome_parceiro = '';   
                if (mysql_num_rows($query_parceiro)>0){
                    $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                }
                
            $sql_cliente_ordem        = "SELECT ordem_pedido, status_renovacao FROM ordem_pedidos
                                            WHERE id_ordem_pedido = $id_ordem_pedido";
            $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                
            if (mysql_num_rows($query_cliente_ordem)>0){
                $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                $status_renovacao    = mysql_result($query_cliente_ordem, 0,'status_renovacao');
            }  
            $label_renova = '';    
            if($status_renovacao == 'S'){
                $label_renova = '<span class="label label-sm bg-purple">RE</span>';  
            }   
    
            $array_id_base_ids_vendas = explode("|", $ordem_pedido);
            $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
            $pegar_nome_ok = false;
            $nome_cliente = '';
            for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++){
                $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                $id_base = $array_ids_base_vendas[0];
                $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                
                // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                            JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                            WHERE bpro.id_base_produto = $id_base";

                $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                
                if (mysql_num_rows($query_base)>0){
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
                    if($pegar_nome_ok == false){
                        $sql_venda  = "SELECT v.metodo_pagamento, c.id_filial, c.nome FROM vendas v
                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                    WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                        $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                        
                        if (mysql_num_rows($query_venda)>0){
                            $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                            $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                            $id_filial          = mysql_result($query_venda, 0, 'id_filial');
                            $pegar_nome_ok = true;
                        }
                        if($id_filial == ''){
                            $id_filial = 0;
                        }
                        $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                                        WHERE id_filial = $id_filial";
                        $query_filial      = mysql_query($sql_filial, $banco_painel);
                        $nome_filial = '';   
                        if (mysql_num_rows($query_filial)>0){
                            $nome_filial        = mysql_result($query_filial, 0,'nome_filial');
                        }
                    }
                }
            }

                $status_list = array(
                    array("green" => "Pago"),
                    array("red" => "Receber")
                );
                
                $status_list_confirma = array(
                    array("info" => "Confirmar"),
                    array("danger" => "Cancelar")
                );

                $html_bt_confirma = '';
                if($nivel_usuario == 'A'){
                    if($baixa_recebimento == 'S'){
                        $status_conf = $status_list_confirma[1];
                    }
                    else
                    {
                        $status_conf = $status_list_confirma[0];
                    }

                }
                
                
                    
                
                
                if($pago == 'N')
                {
                    $status = $status_list[1];
                }
                else
                {
                    $status = $status_list[0];
                }
                
                $pg_html_exibe = 'hide';
                if($pago == 'S'){
                    $pg_html_exibe = '';
                }
                
                if($metodo_pagamento == 'BO'){
                    $html_metodo_pagamento = 'BOLETO';
                }elseif($metodo_pagamento == 'MA'){
                    $html_metodo_pagamento = 'MAQUINA';
                }elseif($metodo_pagamento = 'ON'){
                    $html_metodo_pagamento = 'ON-LINE';
                }
                
                $html_tipo = '';
                if($entrada == 'S'){
                    $html_tipo = "ENTRADA-".$tipo_recebimento;
                }else{
                    $html_tipo = $tipo_recebimento.' '.$html_metodo_pagamento;
                }
                    $records["data"][] = array(
                    $id_boleto,
                    $nome_cliente,
                    $nome_parceiro." ".$nome_filial,
                    $parcela.' / '.$total_parcelas,
                    $html_tipo,
                    $valor_parcela,
                    $valor_recebido,
                    converte_data($data_pagamento),
                    converte_data($data_vencimento),
                    $label_renova.' <a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>'.$html_bt_confirma,
                );
            }
            
        }
        }   
    }elseif($item == 'boletos_mes_clientes'){        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
        }else{
            $where = '';
            if($nivel_usuario == 'P'){
                $where = " AND (id_parceiro = '$usr_parceiro' OR id_parceiro_recebimento = '$usr_parceiro')";
            }
            
            if($nivel_usuario == 'U'){
                $where = " AND (id_usuario = '$usr_id' OR id_usuario_recebimento = '$usr_id')";
            }
            
            $mes = date("m");      // M�s desejado, pode ser por ser obtido por POST, GET, etc.
            $ano = date("Y"); // Ano atual
            $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // M�gica, plim!
            
            $data_final = $ano.'-'.$mes;
            
            $agora 			= date("Y-m-d");
            $sql_contar        = "SELECT COUNT(*) FROM boletos_clientes
WHERE status_boleto = 0 AND data_vencimento LIKE '%$data_final%' $where ";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
                array("green" => "Pago"),
                array("red" => "Receber")
            );
            
            // status_boleto = 0 (ativo)
            // status_boleto = 1 (concluido)
            // status_boleto = 2 (cancelado)
            $sql        = "SELECT * FROM boletos_clientes
WHERE status_boleto = 0 AND data_vencimento LIKE '%$data_final%' $where
            ORDER BY data_vencimento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query          = mysql_query($sql, $banco_painel);
            $query_contar   = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0)
            {
                while ($dados = mysql_fetch_array($query_contar))
                {
                    $valor_parcela_soma = $dados['valor_parcela'];
                    $somar_valores = $valor_parcela_soma + $somar_valores; 
                }
                $records["data"][] = array(
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     db_moeda($somar_valores),
                     '##',
                     '##',
                   );
                   
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
               
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0){
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }
 
                $sql_cliente_ordem        = "SELECT ordem_pedido FROM ordem_pedidos
                                             WHERE id_ordem_pedido = $id_ordem_pedido";
                $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                   
                if (mysql_num_rows($query_cliente_ordem)>0){
                    $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                }        
       
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                $pegar_nome_ok = false;
                $nome_cliente = '';
                for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
                {
                    $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                    $id_base = $array_ids_base_vendas[0];
                    $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                    
                    // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                    $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                WHERE bpro.id_base_produto = $id_base";
    
                    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                    
                    if (mysql_num_rows($query_base)>0){
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
                        if($pegar_nome_ok == false){
                            $sql_venda  = "SELECT c.nome, v.metodo_pagamento FROM vendas v
                                        JOIN clientes c ON v.id_cliente = c.id_cliente
                                        WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                            
                            if (mysql_num_rows($query_venda)>0){
                                $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $pegar_nome_ok = true;
                            }
                        }
                    
                    }elseif($slug == 'sorteio_ead'){
                        if($pegar_nome_ok == false){
                            $sql_venda   = "SELECT c.nome, v.metodo_pagamento FROM vendas_painel v
                                        JOIN vendas c ON v.id_venda = c.id_venda
                                        JOIN titulos t ON c.id_titulo = t.id_titulo
                                        WHERE v.id_venda_painel = $ids_vendas[0] AND c.status = 'A'";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 5");
                            if (mysql_num_rows($query_venda)>0)
                            {
                                $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $pegar_nome_ok = true;
                            }
                        }
                    } 
                }

                    $status_list = array(
                        array("green" => "Pago"),
                        array("red" => "Receber")
                    );
                    
                    if($pago == 'N'){
                        $status = $status_list[1];
                    }
                    else{
                        $status = $status_list[0];
                    }

                    $html_tipo = '';
                    if($entrada == 'S'){
                        $html_tipo = "ENTRADA";
                    }
                    $pg_html_exibe = 'hide';
                    if($pago == 'S'){
                        $pg_html_exibe = '';
                    }
                    $html_bt_pago = '';
                    if($metodo_pagamento == 'BO' AND $tipo_boleto == 'LOJA'){
                        $html_bt_pago = '<a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>';
                    }
                     $records["data"][] = array(
                     $id_boleto,
                     $nome_cliente,
                     $nome_parceiro,
                     $parcela.' / '.$total_parcelas,
                     $html_tipo,
                     $valor_parcela,
                     converte_data($data_vencimento),
                     $html_bt_pago,
                   );
                }
            }
     }   
    }elseif($item == 'gui_local_atendimento'){        
        $where = '';
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
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

            $sql_contar        = "SELECT COUNT(*) FROM gui_local_atendimento
            WHERE nome LIKE '%$busca%'";

            $query_contar      = mysql_query($sql_contar, $banco_painel);
                        
            $iTotalRecords = mysql_result($query_contar, 0, 0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            $records = array();
            $records["data"] = array(); 
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT g_loc_ate.* FROM gui_cidades_locais g_cid_loc
                        JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
                        WHERE g_loc_ate.nome LIKE '%$busca%'
                        GROUP BY g_loc_ate.id_local_atendimento
                        ORDER BY g_loc_ate.id_local_atendimento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                
                $status_list = array(
                    array("success" => "Ativo"),
                    array("danger" => "Inativo")
                );
                
                if($ativo == 'S'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }
                
                 $records["data"][] = array(
                 $id_local_atendimento,
                 $nome,
                 $tipo,
                 $cidade.'-'.$estado,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                '<a href="inc/gui_ver_local_atendimento.php?id_local_atendimento='.$id_local_atendimento.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
               );
                }
            }
        }
        else{
            $where_id_cidade = '';
            if($nivel_usuario != 'A'){
                $sql        = "SELECT id_cidade FROM parceiros
                    WHERE id_parceiro = $usr_parceiro";
                $query      = mysql_query($sql, $banco_painel);
                                
                if (mysql_num_rows($query)>0){
                    $id_cidade = mysql_result($query, 0,0); 
                    $where_id_cidade = "WHERE g_cid_loc.loc_nu_sequencial = $id_cidade";
                }
            }
            
            $sql_contar        = "SELECT COUNT(*) FROM gui_local_atendimento
            WHERE ativo = 'S'";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT g_loc_ate.* FROM gui_cidades_locais g_cid_loc
                        JOIN gui_local_atendimento g_loc_ate ON g_cid_loc.id_local_atendimento = g_loc_ate.id_local_atendimento
                        WHERE g_loc_ate.ativo = 'S'
                        GROUP BY g_loc_ate.id_local_atendimento
                        ORDER BY g_loc_ate.id_local_atendimento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    $status_list = array(
                        array("success" => "Ativo"),
                        array("danger" => "Inativo")
                    );
                    
                    if($ativo == 'S'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                    
                    $records["data"][] = array(
                    $id_local_atendimento,
                    $nome,
                    $tipo,
                    $cidade.'-'.$estado,
                    '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                            '<a href="inc/gui_ver_local_atendimento.php?id_local_atendimento='.$id_local_atendimento.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Ver</a>',
                    
                );
                } 
            }
        }

   }elseif($item == 'gui_convenios'){        
        $where = '';
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
        }
        else{
            $sql_contar        = "SELECT COUNT(*) FROM gui_convenios";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT * FROM gui_convenios
                        ORDER BY id_convenio DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    $status_list = array(
                        array("success" => "Ativo"),
                        array("danger" => "Inativo")
                    );
                    
                    if($ativo == 'S'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                    
                    $records["data"][] = array(
                    $id_convenio,
                    $nome,
                    converte_data($data_cadastro),
                    '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                            '<a href="gui_editar.php?item=gui_convenios&id='.$id_convenio.'" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>',
                    
                );
                }  
            }
        
        }
       
   }elseif($item == 'gui_procedimentos'){        
        $where = '';
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
            $where_busca = "AND nome LIKE '%$busca%' OR codigo = '$busca' "; 
            
            $sql_contar        = "SELECT COUNT(*) FROM gui_procedimentos
                            WHERE ativo = 'S' $where_busca";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT * FROM gui_procedimentos
                        WHERE ativo = 'S' $where_busca
                        ORDER BY id_procedimento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    
                    $status_list = array(
                        array("success" => "Ativo"),
                        array("danger" => "Inativo")
                    );
                    
                    if($ativo == 'S')
                    {
                        $status = $status_list[0];
                    }
                    else
                    {
                        $status = $status_list[1];
                    }
                
                    $sql_grupo  = "SELECT g.nome FROM gui_grupo_procedimentos g
                                            JOIN gui_procedimentos p ON p.id_grupo_procedimento = p.id_grupo_procedimento
                                            WHERE g.id_grupo_procedimento = $id_grupo_procedimento AND p.id_procedimento = $id_procedimento";
                    $query_grupo = mysql_query($sql_grupo) or die(mysql_error()." - 145");
                    $nome_grupo = 'Sem grupo';
                    if (mysql_num_rows($query_grupo)>0)
                    {
                        $nome_grupo = mysql_result($query_grupo, 0, 'nome');
                    }
                
                    $records["data"][] = array(
                    $codigo,
                    $nome,
                    $nome_grupo,
                    '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                            '<a href="gui_editar.php?item=gui_procedimentos&id='.$id_procedimento.'" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>',
                    
                );
            }    
           }
        }
        else{
        $sql_contar        = "SELECT COUNT(*) FROM gui_procedimentos";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT * FROM gui_procedimentos
                    ORDER BY id_procedimento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0){
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                $status_list = array(
                    array("success" => "Ativo"),
                    array("danger" => "Inativo")
                );
                
                if($ativo == 'S'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }

                $sql_grupo  = "SELECT g.nome FROM gui_grupo_procedimentos g
                                        JOIN gui_procedimentos p ON p.id_grupo_procedimento = p.id_grupo_procedimento
                                        WHERE g.id_grupo_procedimento = $id_grupo_procedimento AND p.id_procedimento = $id_procedimento";
                $query_grupo = mysql_query($sql_grupo) or die(mysql_error()." - 145");
                $nome_grupo = 'Sem grupo';
                if (mysql_num_rows($query_grupo)>0){
                    $nome_grupo = mysql_result($query_grupo, 0, 'nome');
                }
               
                 $records["data"][] = array(
                 $codigo,
                 $nome,
                 $nome_grupo,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                          '<a href="gui_editar.php?item=gui_procedimentos&id='.$id_procedimento.'" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>',
                 
               );
            }
            
        }
        
        }
       
  }elseif($item == 'gui_grupo_procedimentos'){        
        $where = '';
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
        }
        else{
        
        $sql_contar        = "SELECT COUNT(*) FROM gui_grupo_procedimentos";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT * FROM gui_grupo_procedimentos
                    ORDER BY id_grupo_procedimento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0){
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                $status_list = array(
                    array("success" => "Ativo"),
                    array("danger" => "Inativo")
                );
                
                if($ativo == 'S'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }
                 $records["data"][] = array(
                 $id_grupo_procedimento,
                 $nome,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                          '<a href="gui_editar.php?item=gui_grupo_procedimentos&id='.$id_grupo_procedimento.'" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>',
                 
               );
            }
        }
        
        }
       
   }elseif($item == 'gui_profissionais'){        
        $where = '';
        if((isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])) OR !empty($_POST['select_profissao_filtro']) OR !empty($_POST['select_especialidade_filtro']) OR !empty($_POST['cidade_filtro_profissional']) OR !empty($_POST['select_local_atend_filtro'])){
            $where_busca_nome = "pro_is.nome LIKE '%$busca%'";
            $join_profissoes = '';
            $join_especialidades = '';
            $join_local_atendimento = '';
            $join_cidades = '';
            $where_busca = '';
            if($select_profissao_filtro > 0){
                $join_profissoes = "JOIN gui_profissoes pro_es ON pro_is.id_profissao = pro_es.id_profissao";
                $where_busca.= "AND pro_es.id_profissao = $select_profissao_filtro";
            }
            
            if($select_especialidade_filtro > 0){
                $join_especialidades = "JOIN gui_especialidades_profissional esp_pro ON pro_is.id_profissional = esp_pro.id_profissional";
                $where_busca.= " AND esp_pro.id_especialidade = $select_especialidade_filtro";
            }
            
            if($select_local_atend_filtro > 0){
                $join_local_atendimento = "JOIN gui_local_atendimento_profissional loc_at_pro ON pro_is.id_profissional = loc_at_pro.id_profissional";
                $where_busca.= " AND loc_at_pro.id_local_atendimento = $select_local_atend_filtro";
            }
            
            if(strlen($cidade_filtro_profissional) > 2){
                if($select_local_atend_filtro == ''){
                    $join_local_atendimento_cidade = "JOIN gui_local_atendimento_profissional loc_at_pro ON pro_is.id_profissional = loc_at_pro.id_profissional";
                }
                $join_cidades = "$join_local_atendimento_cidade
                                JOIN gui_cidades_locais cid_loc ON loc_at_pro.id_local_atendimento = cid_loc.id_local_atendimento
                                JOIN log_localidade lo_loc ON cid_loc.loc_nu_sequencial = lo_loc.loc_nu_sequencial";
                $where_busca_nome = "lo_loc.loc_no LIKE '%$cidade_filtro_profissional%'";
            }

            $sql_contar        = "SELECT pro_is.nome FROM gui_profissionais pro_is
                                $join_profissoes
                                $join_especialidades
                                $join_local_atendimento
                                $join_cidades
                                WHERE $where_busca_nome $where_busca
                                GROUP BY pro_is.id_profissional";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            $iTotalRecords = mysql_num_rows($query_contar);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            $records = array();
            $records["data"] = array(); 
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT pro_is.* FROM gui_profissionais pro_is
                                $join_profissoes
                                $join_especialidades
                                $join_local_atendimento
                                $join_cidades
                                WHERE $where_busca_nome $where_busca
                                GROUP BY pro_is.id_profissional
                    LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                          
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    $status_list = array(
                    array("success" => "Ativo"),
                    array("danger" => "Inativo")
                    );
                    
                    if($ativo == 'S'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                   
                    $sql_profissao  = "SELECT nome FROM gui_profissoes
                                            WHERE id_profissao = $id_profissao AND ativo = 'S'";
                    $query_profissao = mysql_query($sql_profissao, $banco_painel) or die(mysql_error()." - 145");
                    $nome_profissao = 'Sem Profiss�o';
                    if (mysql_num_rows($query_profissao)>0){
                        $nome_profissao = mysql_result($query_profissao, 0, 'nome');
                    }
                    
                    $sql_especialidade  = "SELECT gu_esp.nome FROM gui_especialidades gu_esp
                                        JOIN gui_especialidades_profissional gu_esp_p ON gu_esp.id_especialidade = gu_esp_p.id_especialidade
                                        WHERE gu_esp_p.id_profissional = $id_profissional";
                    $query_especialidade = mysql_query($sql_especialidade, $banco_painel) or die(mysql_error()." - 145");
                    $nome_especialidade = 'Sem especialidade';
                    if (mysql_num_rows($query_especialidade)>0){
                        $nome_especialidade = mysql_result($query_especialidade, 0, 'nome');
                    }
                    
                    $sql_local  = "SELECT gu_at.cidade FROM gui_local_atendimento gu_at
                                JOIN gui_local_atendimento_profissional gu_at_p ON gu_at.id_local_atendimento = gu_at_p.id_local_atendimento
                                WHERE gu_at_p.id_profissional = $id_profissional";
                    $query_local = mysql_query($sql_local, $banco_painel) or die(mysql_error()." - 145");
                    $cidade_local = '';
                    if (mysql_num_rows($query_local)>0){
                        $cidade_local = mysql_result($query_local, 0, 'cidade');
                    }
                     $records["data"][] = array(
                     $id_profissional,
                     $nome,
                     $cidade_local,
                     $nome_profissao,
                     $nome_especialidade,
                     '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                     '<a href="gui_editar.php?item=gui_profissionais&id='.$id_profissional.'" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>',
                   );
                }
            } 
        }
        else{
        $sql_contar        = "SELECT COUNT(*) FROM gui_profissionais";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT * FROM gui_profissionais
                    ORDER BY id_profissional DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0){
        
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                $status_list = array(
                    array("success" => "Ativo"),
                    array("danger" => "Inativo")
                );
                
                if($ativo == 'S'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }
               
                $sql_profissao  = "SELECT nome FROM gui_profissoes
                WHERE id_profissao = $id_profissao AND ativo = 'S'";
                $query_profissao = mysql_query($sql_profissao, $banco_painel) or die(mysql_error()." - 145");
                $nome_profissao = 'Sem Profiss�o';
                if (mysql_num_rows($query_profissao)>0){
                    $nome_profissao = mysql_result($query_profissao, 0, 'nome');
                }
                
                $sql_especialidade  = "SELECT gu_esp.nome'nome_especialidade' FROM gui_especialidades gu_esp
                                    JOIN gui_especialidades_profissional gu_esp_p ON gu_esp.id_especialidade = gu_esp_p.id_especialidade
                                    WHERE gu_esp_p.id_profissional = $id_profissional";
                $query_especialidade = mysql_query($sql_especialidade, $banco_painel) or die(mysql_error()." - 145");
                $nome_especialidade = '';
                if (mysql_num_rows($query_especialidade)>0){
                    $nome_especialidade_array = array();
                    while($dados_especialidade = mysql_fetch_array($query_especialidade))
                    {
                        extract($dados_especialidade);
                        if(!in_array($nome_especialidade, $nome_especialidade_array)){
                            $nome_especialidade_array[] = $nome_especialidade;
                        }
                    }
                    
                    $nome_especialidade = implode(', ', $nome_especialidade_array);

                }
                
                $sql_local  = "SELECT gu_at.cidade'cidade_local' FROM gui_local_atendimento gu_at
                            JOIN gui_local_atendimento_profissional gu_at_p ON gu_at.id_local_atendimento = gu_at_p.id_local_atendimento
                            WHERE gu_at_p.id_profissional = $id_profissional";
                $query_local = mysql_query($sql_local, $banco_painel) or die(mysql_error()." - 145");
                $cidade_local = '';
                if (mysql_num_rows($query_local)>0){
                    $cidade_local_array = array();
                    while($dados_local = mysql_fetch_array($query_local))
                    {
                        extract($dados_local);
                        if(!in_array($cidade_local, $cidade_local_array)){
                            $cidade_local_array[] = $cidade_local;
                        }
                        
                    }
                    $cidade_local = implode(', ', $cidade_local_array);
                }

                 $records["data"][] = array(
                 $id_profissional,
                 $nome,
                 $cidade_local,
                 $nome_profissao,
                 $nome_especialidade,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                 '<a href="inc/gui_ver_profissional.php?id='.$id_profissional.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa" style="margin-top: 5px;"><i class="fa fa-search"></i> Ver</a>',
                 
               );
            }
            
        }
        
        }
      
   }elseif($item == 'gui_pacientes'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND id_parceiro = $usr_parceiro";
        }else{
            if(in_array("44", $verifica_lista_permissoes_array_inc)){
                
            }else{
                $where_id_parceiro = "AND id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){
            $dt_nasc        = verifica($_POST['customActionItem_dt_nasc']);
            if($nivel_usuario != 'A'){
                $where_busca = "AND nome LIKE '%$busca%' AND data_nascimento = '".converte_data($dt_nasc)."' "; 
            }else{
                if(!empty($dt_nasc)){
                    $where_busca = "AND nome LIKE '%$busca%' AND data_nascimento = '".converte_data($dt_nasc)."' "; 
                }else{
                    $where_busca = "AND nome LIKE '%$busca%'"; 
                }
               
                // nivel adm porem menos permi��es
                if($nivel_status == '1'){
                    $where_id_parceiro = "AND id_parceiro = $usr_parceiro";
                }
            }
            
            $sql_contar        = "SELECT COUNT(*) FROM gui_pacientes
                            WHERE ativo = 'S' $where_id_parceiro $where_busca";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT * FROM gui_pacientes
                        WHERE ativo = 'S' $where_id_parceiro $where_busca
                        ORDER BY id_paciente DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    $status_list = array(
                        array("success" => "Ativo"),
                        array("danger" => "Inativo")
                    );
                    
                    if($ativo == 'S'){
                        $status = $status_list[0];
                    }
                    else{
                        $status = $status_list[1];
                    }
                    
                    if($nivel_usuario == 'A'){
                        $sql_parceiro  = "SELECT nome FROM parceiros
                                            WHERE id_parceiro = $id_parceiro AND del = 'N'";
                        $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                        $coluna_extra = 'Sem Parceiro';
                        if (mysql_num_rows($query_parceiro)>0)
                        {
                            $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        }
                    }else{
                        $coluna_extra = $celular;
                    }

                    $sql_guia  = "SELECT data_cadastro FROM gui_guias
                                      WHERE id_paciente = $id_paciente
                                      ORDER BY id_paciente DESC
                                      LIMIT 0,1";
                    $query_guia = mysql_query($sql_guia, $banco_painel) or die(mysql_error()." - 185");
                    $ultima_consulta = '-';
                    if (mysql_num_rows($query_guia)>0){
                        $ultima_consulta = mysql_result($query_guia, 0, 'data_cadastro');
                        $ultima_consulta = converte_data($ultima_consulta);
                    }
                    
                    $sql_convenio  = "SELECT nome FROM gui_convenios
                                      WHERE id_convenio = $id_convenio";
                    $query_convenio = mysql_query($sql_convenio, $banco_painel) or die(mysql_error()." - 185");
                    $nome_convenio = '-';
                    if (mysql_num_rows($query_convenio)>0){
                        $nome_convenio = mysql_result($query_convenio, 0, 'nome');
                    }
                    $data_nascimento = converte_data($data_nascimento);
                    $idade = calcula_idade($data_nascimento);
                    $html_dados_completo = '';
                     if($dados_completo == 'N'){
                        $html_dados_completo = '<span class="label label-sm label-warning"> Pendente </span><br/>';
                     }
                     $records["data"][] = array(
                     $id_paciente,
                     $html_dados_completo.''.$nome,
                     $coluna_extra,
                     $ultima_consulta,
                     $data_nascimento." (".$idade." anos)",
                     $nome_convenio,
                              '<a href="gui_editar.php?item=gui_pacientes&id='.$id_paciente.'" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>',
                     
                   );
            }
                
           }
            
        }
        else
        {
            $sql_contar        = "SELECT COUNT(*) FROM gui_pacientes
                                WHERE ativo = 'S' $where_id_parceiro";
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $sql        = "SELECT * FROM gui_pacientes
                        WHERE ativo = 'S' $where_id_parceiro 
                        ORDER BY id_paciente DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query      = mysql_query($sql, $banco_painel);
                            
            if (mysql_num_rows($query)>0)
            {
            
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados);  
                    
                    $status_list = array(
                        array("success" => "Ativo"),
                        array("danger" => "Inativo")
                    );
                    
                    if($ativo == 'S')
                    {
                        $status = $status_list[0];
                    }
                    else
                    {
                        $status = $status_list[1];
                    }
                    
                    if($nivel_usuario == 'A'){
                        $sql_parceiro  = "SELECT nome FROM parceiros
                                            WHERE id_parceiro = $id_parceiro AND del = 'N'";
                        $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                        $coluna_extra = 'Sem Parceiro';
                        if (mysql_num_rows($query_parceiro)>0)
                        {
                            $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        }
                    }else
                    {
                        $coluna_extra = $celular;
                    }
                    
                
                    $sql_guia  = "SELECT data_agendamento FROM gui_guias
                                    WHERE id_paciente = $id_paciente
                                    ORDER BY id_paciente DESC
                                    LIMIT 0,1";
                    $query_guia = mysql_query($sql_guia, $banco_painel) or die(mysql_error()." - 185");
                    $ultima_consulta = '-';
                    if (mysql_num_rows($query_guia)>0)
                    {
                        $ultima_consulta = mysql_result($query_guia, 0, 'data_agendamento');
                        $ultima_consulta = converte_data($ultima_consulta);
                    }
                    
                    $sql_convenio  = "SELECT nome FROM gui_convenios
                                    WHERE id_convenio = $id_convenio";
                    $query_convenio = mysql_query($sql_convenio, $banco_painel) or die(mysql_error()." - 185");
                    $nome_convenio = '-';
                    if (mysql_num_rows($query_convenio)>0)
                    {
                        $nome_convenio = mysql_result($query_convenio, 0, 'nome');
                    }
                    
                    $data_nascimento = converte_data($data_nascimento);
                    $idade = calcula_idade($data_nascimento);
                    $html_dados_completo = '';
                    if($dados_completo == 'N'){
                        $html_dados_completo = '<span class="label label-sm label-warning"> Pendente </span><br/>';
                    }
                    
                    $records["data"][] = array(
                    $id_paciente,
                    $html_dados_completo."".$nome,
                    $coluna_extra,
                    $ultima_consulta,
                    $data_nascimento." (".$idade." anos)",
                    $nome_convenio,
                            '<a href="gui_editar.php?item=gui_pacientes&id='.$id_paciente.'" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Editar</a>',
                    
                );
                }
                
            }
        
        }
       
  }elseif($item == 'gui_guias'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
            if(in_array("44", $verifica_lista_permissoes_array_inc)){                
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro']))
        {
            if($nivel_usuario != 'A'){
                $where_id_parceiro = "AND gui_gui.id_parceiro = $usr_parceiro";
            }
            else{
                if(in_array("44", $verifica_lista_permissoes_array_inc)){
                
                }else{
                    $where_id_parceiro = "AND gui_gui.id_parceiro = $usr_parceiro";
                }
            }
            
            $where_busca = "AND gui_pac.nome LIKE '%$busca%' OR gui_gui.id_guia = '$busca'"; 
               
        $sql_contar        = "SELECT COUNT(*) FROM gui_guias gui_gui
                            JOIN gui_pacientes gui_pac ON gui_gui.id_paciente = gui_pac.id_paciente
                            WHERE (gui_gui.del = 'N' OR gui_gui.del = 'S') $where_id_parceiro $where_busca";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gui_gui.*, gui_pac.id_paciente, gui_pac.nome, gui_pac.data_nascimento, gui_pac.dados_completo FROM gui_guias gui_gui
                    JOIN gui_pacientes gui_pac ON gui_gui.id_paciente = gui_pac.id_paciente
                    WHERE (gui_gui.del = 'N' OR gui_gui.del = 'S') $where_id_parceiro $where_busca
                    ORDER BY gui_gui.id_guia DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0)
        {
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                $status_list = array(
                array("info" => "AGENDADO"),
                array("warning" => "ABERTO"),
                array("danger" => "PENDENTE"),
                array("success" => "EMITIDO"),
                array("danger" => "CANCEL")
                );
                $ativo = $status;
                if($ativo == 'AGENDADO'){
                    $status = $status_list[0];
                }elseif($ativo == 'ABERTO'){
                    $status = $status_list[1];
                }elseif($ativo == 'PENDENTE'){
                    $status = $status_list[2];
                }elseif($ativo == 'EMITIDO'){
                    $status = $status_list[3];
                }elseif($ativo == 'CANCELADO'){
                    $status = $status_list[4];
                }
                    
                if($nivel_usuario == 'A'){
                    $sql_parceiro  = "SELECT nome FROM parceiros
                                        WHERE id_parceiro = $id_parceiro AND del = 'N'";
                    $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                    $coluna_extra = 'Sem Parceiro';
                    $coluna_extra_2 = '';
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        $coluna_extra_2 = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                    }
                }else
                {
                    $coluna_extra = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                }
                
                $sql_local_atendimento  = "SELECT nome FROM gui_local_atendimento
                                  WHERE id_local_atendimento = $id_local_atendimento";
                $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
                $nome_local_atendimento = '-';
                if (mysql_num_rows($query_local_atendimento)>0)
                {
                    $nome_local_atendimento = mysql_result($query_local_atendimento, 0, 'nome');
                }

                 $data_nascimento = converte_data($data_nascimento);
                 $html_dados_completo = '';
                 if($dados_completo == 'N'){
                    $html_dados_completo = '<span class="label label-sm label-warning"> Dados incompletos </span><br/>';
                 }

               $records["data"][] = array(
                 $id_guia,
                 $html_dados_completo.' <a href="gui_editar.php?item=gui_pacientes&id='.$id_paciente.'" target="_blank">'.$nome.'</a>',
                 converte_data($data_agendamento),
                 $hora_agendamento,
                 $coluna_extra,
                 $nome_local_atendimento,
                 $coluna_extra_2.' <a href="inc/gui_ver_guia.php?id_guia='.$id_guia.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa" style="margin-top: 5px;"><i class="fa fa-search"></i></a>',
                 
               );
            }
            
        }

        }else{
        $sql_contar        = "SELECT COUNT(*) FROM gui_guias gu
                            JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                            WHERE gu.del = 'N' $where_id_parceiro";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gu.* FROM gui_guias gu
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $where_id_parceiro 
                    ORDER BY gu.id_guia DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0){
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                $status_list = array(
                array("info" => "AGENDADO"),
                array("warning" => "ABERTO"),
                array("danger" => "PENDENTE"),
                array("success" => "EMITIDO"),
                array("danger" => "CANCEL")
                );
                $ativo = $status;
                if($ativo == 'AGENDADO'){
                    $status = $status_list[0];
                }elseif($ativo == 'ABERTO'){
                    $status = $status_list[1];
                }elseif($ativo == 'PENDENTE'){
                    $status = $status_list[2];
                }elseif($ativo == 'EMITIDO'){
                    $status = $status_list[3];
                }elseif($ativo == 'CANCELADO'){
                    $status = $status_list[4];
                }
                    
                if($nivel_usuario == 'A'){
                    $sql_parceiro  = "SELECT nome FROM parceiros
                                        WHERE id_parceiro = $id_parceiro AND del = 'N'";
                    $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                    $coluna_extra = 'Sem Parceiro';
                    $coluna_extra_2 = '';
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        $coluna_extra_2 = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                    }
                }else{
                    $coluna_extra = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                }
                
                $sql_paciente   = "SELECT id_paciente, nome, data_nascimento, dados_completo FROM gui_pacientes
                                  WHERE id_paciente = $id_paciente";
                $query_paciente = mysql_query($sql_paciente, $banco_painel) or die(mysql_error()." - 12185");
                $id_paciente = '';
                $nome_paciente  = '-';
                $data_nascimento= '-';
                $dados_completo = '-';
                if (mysql_num_rows($query_paciente)>0){
                    $id_paciente        = mysql_result($query_paciente, 0, 'id_paciente');
                    $nome_paciente      = mysql_result($query_paciente, 0, 'nome');
                    $data_nascimento    = mysql_result($query_paciente, 0, 'data_nascimento');
                    $dados_completo     = mysql_result($query_paciente, 0, 'dados_completo');
                }

                $sql_local_atendimento  = "SELECT nome FROM gui_local_atendimento
                                  WHERE id_local_atendimento = $id_local_atendimento";
                $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
                $nome_local_atendimento = '-';
                if (mysql_num_rows($query_local_atendimento)>0){
                    $nome_local_atendimento = mysql_result($query_local_atendimento, 0, 'nome');
                }

                 $data_nascimento = converte_data($data_nascimento);
                 //$idade = calcula_idade($data_nascimento);
                 $html_dados_completo = '';
                 if($dados_completo == 'N'){
                    $html_dados_completo = '<span class="label label-sm label-warning"> Dados incompletos </span><br/>';
                 }
                 
                 $records["data"][] = array(
                 $id_guia,
                 $html_dados_completo.' <a href="gui_editar.php?item=gui_pacientes&id='.$id_paciente.'" target="_blank">'.$nome_paciente.'</a>',
                 converte_data($data_agendamento),
                 $hora_agendamento,
                 $coluna_extra,
                 $nome_local_atendimento,
                 $coluna_extra_2.' <a href="inc/gui_ver_guia.php?id_guia='.$id_guia.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa" style="margin-top: 5px;"><i class="fa fa-search"></i></a>',
                 
               );
            }
            
        }
        
        }
       
  }elseif($item == 'gui_pagamentos_guia'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
            if(in_array("44", $verifica_lista_permissoes_array_inc)){
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){    
        }
        else{
        $sql_contar        = "SELECT COUNT(*) FROM gui_pagamentos_guias pag
                            JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                            WHERE gu.del = 'N' $where_id_parceiro";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gu.id_guia, pa.id_paciente, pa.nome, pag.* FROM gui_pagamentos_guias pag
                    JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $where_id_parceiro 
                    ORDER BY pag.id_pagamento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0)
        {
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                
                $status_list = array(
                    array("success" => "Pago"),
                    array("danger" => "Nao pago")
                );
                
                if($status == 'PAGO'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }

                if($local_pagamento == 'LOCAL'){
                    $html_local_pagamento = "Na emissao desta guia";
                }else{
                    $html_local_pagamento = "No local de atendimento";
                }

                $data_pagamento = converte_data($data_pagamento);

                 $records["data"][] = array(
                 '<a href="gui_editar.php?item=gui_guias_detalhes&id='.$id_guia.'&tipo=gui_guias_detalhes" target="_blank">'.$id_guia.'</a>',
                 $nome,
                 $html_local_pagamento,
                 $data_pagamento,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                 db_moeda($valor_total)
               );
            }
            
        }
        
        }
       
  }elseif($item == 'gui_relatorio_local_atendimento'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
            
            if(in_array("44", $verifica_lista_permissoes_array_inc)){
                
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){  
        }
        else{
        $mes = date("m");      // M�s desejado, pode ser por ser obtido por POST, GET, etc.
        $ano = date("Y"); // Ano atual
        $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // M�gica, plim!
        
        $data_final = $ano.'-'.$mes;
        
        $sql_verifica_filtro = "AND data_agendamento LIKE '%$data_final%'"; 
        
        $sql_contar        = "SELECT COUNT(*) FROM gui_guias gu
                        JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                        WHERE gu.del = 'N' $where_id_parceiro $sql_verifica_filtro";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gu.* FROM gui_guias gu
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $where_id_parceiro $sql_verifica_filtro
                    ORDER BY gu.id_guia DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0){
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                $status_list = array(
                array("info" => "AGENDADO"),
                array("warning" => "ABERTO"),
                array("danger" => "PENDENTE"),
                array("success" => "EMITIDO"),
                array("danger" => "CANCEL")
                );
                $ativo = $status;
                if($ativo == 'AGENDADO'){
                    $status = $status_list[0];
                }elseif($ativo == 'ABERTO'){
                    $status = $status_list[1];
                }elseif($ativo == 'PENDENTE'){
                    $status = $status_list[2];
                }elseif($ativo == 'EMITIDO'){
                    $status = $status_list[3];
                }elseif($ativo == 'CANCELADO'){
                    $status = $status_list[4];
                }
                    
                if($nivel_usuario == 'A'){
                    $sql_parceiro  = "SELECT nome FROM parceiros
                                        WHERE id_parceiro = $id_parceiro AND del = 'N'";
                    $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                    $coluna_extra = 'Sem Parceiro';
                    $coluna_extra_2 = '';
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        $coluna_extra_2 = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                    }
                    
                    
                    $sql_procedimentos        = "SELECT gui_pro.codigo'codigo_procedimnto', gui_pro.nome'nome_procedimento', gui_loc_pro.preco_custo, gui_pro_gui.valor_cobrado, gui_pag_gui.desconto, gui_pag_gui.tipo_desconto, gui_pag_gui.valor_total_desconto, gui_pag_gui.baixa_recebimento FROM gui_procedimentos_guia gui_pro_gui
    JOIN gui_procedimentos gui_pro ON gui_pro_gui.id_procedimento = gui_pro.id_procedimento
    JOIN gui_local_atendimento_procedimentos gui_loc_pro ON gui_pro_gui.id_procedimento = gui_loc_pro.id_procedimento
    JOIN gui_pagamentos_guias gui_pag_gui ON gui_pro_gui.id_guia = gui_pag_gui.id_guia
    WHERE gui_pro_gui.id_guia = $id_guia AND gui_loc_pro.id_convenio = gui_pro_gui.id_convenio AND gui_loc_pro.id_local_atendimento = $id_local_atendimento";
                    $query_procedimentos      = mysql_query($sql_procedimentos);
                            
                    if (mysql_num_rows($query_procedimentos)>0)
                    {  
                        $soma_preco_custo = 0;
                        $soma_valor_cobrado = 0;
                        while ($dados = mysql_fetch_array($query_procedimentos))
                        {
                            extract($dados); 
                            $preco_custo_calc = moeda_db($preco_custo);
                            $valor_cobrado_exibe = db_moeda($valor_cobrado);
                            
                            $soma_preco_custo = $soma_preco_custo + $preco_custo_calc;
                            $soma_valor_cobrado = $soma_valor_cobrado + $valor_cobrado;
  
                        }
                        if(in_array("42", $verifica_lista_permissoes_array_inc)){
                            $coluna_extra_3 = "<td>".db_moeda2($soma_preco_custo)."</td>";
                        }else{
                            $coluna_extra_3 = "<td> &nbsp; </td>";
                        }
                        
                        if($desconto > 0){
                            $soma_valor_cobrado = $valor_total_desconto;
                        }
                        
                        $coluna_extra_4 = "<td>".db_moeda2($soma_valor_cobrado)."</td>";
                        
                    }  
                }else{
                    $sql_parceiro  = "SELECT nome FROM usuarios
                                        WHERE id_usuario = $id_usuario";
                    $query_parceiro = mysql_query($sql_parceiro, $banco_painel) or die(mysql_error()." - 14589");
                    $coluna_extra = 'Sem Usuario';
                    $coluna_extra_2 = '';
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $coluna_extra = mysql_result($query_parceiro, 0, 'nome');
                        $coluna_extra_2 = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                    }
                    //$coluna_extra = '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>';
                }
                
                $sql_paciente   = "SELECT id_paciente, nome, data_nascimento, dados_completo FROM gui_pacientes
                                  WHERE id_paciente = $id_paciente";
                $query_paciente = mysql_query($sql_paciente, $banco_painel) or die(mysql_error()." - 12185");
                $id_paciente = '';
                $nome_paciente  = '-';
                $data_nascimento= '-';
                $dados_completo = '-';
                if (mysql_num_rows($query_paciente)>0){
                    $id_paciente        = mysql_result($query_paciente, 0, 'id_paciente');
                    $nome_paciente      = mysql_result($query_paciente, 0, 'nome');
                    $data_nascimento    = mysql_result($query_paciente, 0, 'data_nascimento');
                    $dados_completo     = mysql_result($query_paciente, 0, 'dados_completo');
                }

                $sql_local_atendimento  = "SELECT nome, cidade FROM gui_local_atendimento
                                  WHERE id_local_atendimento = $id_local_atendimento";
                $query_local_atendimento= mysql_query($sql_local_atendimento, $banco_painel) or die(mysql_error()." - 185");
                $nome_local_atendimento = '-';
                if (mysql_num_rows($query_local_atendimento)>0){
                    $nome_local_atendimento = mysql_result($query_local_atendimento, 0, 'nome');
                    $cidade_local_atendimento = mysql_result($query_local_atendimento, 0, 'cidade');
                }

                 $data_nascimento = converte_data($data_nascimento);
                 //$idade = calcula_idade($data_nascimento);
                 $html_dados_completo = '';
                 if($dados_completo == 'N'){
                    $html_dados_completo = '<span class="label label-sm label-warning"> Dados incompletos </span><br/>';
                 }

                 $status_list_confirma = array(
                       array("info" => "Confirmar"),
                       array("danger" => "Cancelar")
                 );
    
                 $html_bt_confirma = '';
                 if($nivel_usuario == 'A' AND $ativo == 'EMITIDO'){
                    if($baixa_recebimento == 'S'){
                        $status_conf = $status_list_confirma[1];
                    }
                    else
                    {
                        $status_conf = $status_list_confirma[0];
                    }
                        
                    $html_bt_confirma = '<a href="inc/gui_ver_confirmar_pagamento_guia.php?id_guia='.$id_guia.'" id="bt_confirmar_'.$id_guia.'" data-target="#ajax" data-toggle="modal" class="btn btn-'.(key($status_conf)).' btn-block btn-sm" style="margin-top: 5px;">'.(current($status_conf)).'</a>';
                    }
                 
                 $records["data"][] = array(
                 $html_dados_completo." ".$nome_paciente,
                 $nome_local_atendimento,
                 $cidade_local_atendimento,
                 $coluna_extra,
                 $coluna_extra_2.' <a href="inc/gui_ver_guia.php?id_guia='.$id_guia.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm btn-outline grey-salsa" style="margin-top: 5px;"><i class="fa fa-search"></i></a>',
                 $coluna_extra_3,
                 $coluna_extra_4.' '.$html_bt_confirma
               );
            }
            
        }
        }
      
  }elseif($item == 'gui_pagamentos_guia_pagos'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
            if(in_array("44", $verifica_lista_permissoes_array_inc)){
                
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){ 
        }
        else{
        $agora 			= date("Y-m-d");
        $agora_normal   = date('d-m-Y');
        if($tipo_filtro == 'hoje'){
            $sql_verifica_filtro = "AND pag.data_pagamento = '$agora'";
        }elseif($tipo_filtro == 'ontem'){
            //$data = somar_datas( '-1', 'd');
            $data_ontem = date('d/m/Y', strtotime('-1 day', strtotime($agora_normal)));
            //$data_ontem = date('d/m/Y', strtotime($data));
            $data_ontem = converte_data($data_ontem);
            
            $sql_verifica_filtro = "AND pag.data_pagamento = '$data_ontem'";
            
        }elseif($tipo_filtro == 'ultimos_7'){
            
            //$data = somar_datas( '-7', 'd');
            $data_anterior = date('d/m/Y', strtotime('-7 days', strtotime($agora_normal)));
            //$data_anterior = date('d/m/Y', strtotime($data));
            $data_anterior = converte_data($data_anterior);
            
            $sql_verifica_filtro = "AND pag.data_pagamento BETWEEN '$data_anterior' AND '$agora'";
            
            
        }elseif($tipo_filtro == 'ultimos_30'){
            //$data = somar_datas( '-30', 'd');
            $data_anterior = date('d/m/Y', strtotime('-30 days', strtotime($agora_normal)));
            //$data_anterior = date('d/m/Y', strtotime($data));
            $data_anterior = converte_data($data_anterior);
            
            $sql_verifica_filtro = "AND pag.data_pagamento BETWEEN '$data_anterior' AND '$agora'";
        }elseif($tipo_filtro == 'neste_mes'){
            $mes = date("m");
            $ano = date("Y");
            $data_final = $ano.'-'.$mes;
            
            $sql_verifica_filtro = "AND pag.data_pagamento LIKE '%$data_final%'";
            
        }elseif($tipo_filtro == 'ultimo_mes'){
            //$data = somar_datas( '-1', 'm');
            $data_anterior = date('d/m/Y', strtotime('-1 month', strtotime($agora_normal)));
            //$data_ultimo_dia = date('t/m/Y', strtotime($data));
            
            $partes = explode("/", $data_anterior);
                //$dia = $partes[0];
                $mes_referencia = $partes[1];
                $ano_referencia = $partes[2];
                $data_final = $ano_referencia.'-'.$mes_referencia;
            //$data_anterior = converte_data($data_anterior);
            
            $sql_verifica_filtro = "AND pag.data_pagamento LIKE '%$data_final%'";
        }    
        
        $sql_contar        = "SELECT COUNT(*) FROM gui_pagamentos_guias pag
                            JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                            WHERE gu.del = 'N' $sql_verifica_filtro AND pag.status = 'PAGO' $where_id_parceiro";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gu.id_guia, pa.id_paciente, pa.nome, pag.* FROM gui_pagamentos_guias pag
                    JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $sql_verifica_filtro AND pag.status = 'PAGO' $where_id_parceiro 
                    ORDER BY pag.id_pagamento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query              = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0)
        {
            $sql_soma_geral        = "SELECT SUM(pag.valor_total)'valor_total_geral' FROM gui_pagamentos_guias pag
                    JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $sql_verifica_filtro AND pag.status = 'PAGO' $where_id_parceiro 
                    ORDER BY pag.id_pagamento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
            $query_soma_geral              = mysql_query($sql_soma_geral, $banco_painel);
            $valor_total_geral = mysql_result($query_soma_geral, 0, 'valor_total_geral');

            $records["data"][] = array(
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     db_moeda($valor_total_geral)
                   );
        
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                
                $status_list = array(
                    array("success" => "Pago"),
                    array("danger" => "Nao pago")
                );
                
                if($status == 'PAGO'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }

                if($local_pagamento == 'LOCAL'){
                    $html_local_pagamento = "Na emissao desta guia";
                }else{
                    $html_local_pagamento = "No local de atendimento";
                }
                        
                
                
                $data_pagamento = converte_data($data_pagamento);

                 $records["data"][] = array(
                 '<a href="gui_editar.php?item=gui_guias_detalhes&id='.$id_guia.'&tipo=gui_guias_detalhes" target="_blank">'.$id_guia.'</a>',
                 $nome,
                 $html_local_pagamento,
                 $data_pagamento,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                 db_moeda($valor_total)
               );
            } 
        }
        
        }
       
  }elseif($item == 'gui_pagamentos_guia_avencer'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
           if(in_array("44", $verifica_lista_permissoes_array_inc)){
                
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){    
        }
        else{
        $sql_contar        = "SELECT COUNT(*) FROM gui_pagamentos_guias pag
                            JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                            WHERE gu.del = 'N' $where_id_parceiro";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gu.id_guia, pa.id_paciente, pa.nome, pag.* FROM gui_pagamentos_guias pag
                    JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $where_id_parceiro 
                    ORDER BY pag.id_pagamento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0)
        {
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                
                $status_list = array(
                    array("success" => "Pago"),
                    array("danger" => "Nao pago")
                );
                
                if($status == 'PAGO'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }
                
                if($local_pagamento == 'LOCAL'){
                    $html_local_pagamento = "Na emissao desta guia";
                }else{
                    $html_local_pagamento = "No local de atendimento";
                }
                    
                $data_pagamento = converte_data($data_pagamento);

                 $records["data"][] = array(
                 '<a href="gui_editar.php?item=gui_guias_detalhes&id='.$id_guia.'&tipo=gui_guias_detalhes" target="_blank">'.$id_guia.'</a>',
                 $nome,
                 $html_local_pagamento,
                 $data_pagamento,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                 '<a href="#" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Ver</a>'
               );
            }
            
        }
        
        }
      
  }elseif($item == 'gui_pagamentos_guia_vencidos'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
            if(in_array("44", $verifica_lista_permissoes_array_inc)){
                
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){   
        }
        else{
   
        $agora 			= date("Y-m-d");
            $agora_normal   = date('d-m-Y');
            if($tipo_filtro == 'neste_mes'){
                $mes = date("m");      // M�s desejado, pode ser por ser obtido por POST, GET, etc.
                $ano = date("Y"); // Ano atual
                $ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // M�gica, plim!
                
                $data_final = $ano.'-'.$mes.'-01';
                
                $sql_verifica_filtro = "AND gu.data_agendamento < '$data_final'";
                
            }elseif($tipo_filtro == 'ultimo_mes'){
                //$data = somar_datas( '-1', 'm');
                $data_proxima = date('d/m/Y', strtotime('-1 month', strtotime($agora_normal)));
                //$data_ultimo_dia = date('t/m/Y', strtotime($data));
                
                $partes = explode("/", $data_proxima);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    $data_final = $ano_referencia.'-'.$mes_referencia;
                //$data_anterior = converte_data($data_anterior);
                
                $sql_verifica_filtro = "AND gu.data_agendamento LIKE '%$data_final%'";
            }elseif($tipo_filtro == 'todos'){
                $sql_verifica_filtro = "AND gu.data_agendamento < '$agora'";        
            }
            
        $sql_contar        = "SELECT COUNT(*) FROM gui_pagamentos_guias pag
                            JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                            WHERE gu.del = 'N' $sql_verifica_filtro AND pag.status = '' $where_id_parceiro";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gu.id_guia, pa.id_paciente, pa.nome, pag.* FROM gui_pagamentos_guias pag
                    JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $sql_verifica_filtro AND pag.status = '' $where_id_parceiro 
                    ORDER BY pag.id_pagamento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0){
            $sql_soma_geral        = "SELECT SUM(pag.valor_total)'valor_total_geral' FROM gui_pagamentos_guias pag
                                JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                                JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                                WHERE gu.del = 'N' $sql_verifica_filtro AND pag.status = '' $where_id_parceiro 
                                ORDER BY pag.id_pagamento DESC
                                LIMIT $iDisplayStart,$iDisplayLength";
            $query_soma_geral      = mysql_query($sql_soma_geral, $banco_painel);
            $valor_total_geral = mysql_result($query_soma_geral, 0, 'valor_total_geral');

            $records["data"][] = array(
                     '##',
                     '##',
                     '##',
                     '##',
                     '##',
                     db_moeda($valor_total_geral)
                   );
        
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                
                $status_list = array(
                    array("success" => "Pago"),
                    array("danger" => "Nao pago")
                );
                
                if($status == 'PAGO'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }

                if($local_pagamento == 'LOCAL'){
                    $html_local_pagamento = "Na emissao desta guia";
                }else{
                    $html_local_pagamento = "No local de atendimento";
                }

                $data_pagamento = converte_data($data_pagamento);

                 $records["data"][] = array(
                 '<a href="gui_editar.php?item=gui_guias_detalhes&id='.$id_guia.'&tipo=gui_guias_detalhes" target="_blank">'.$id_guia.'</a>',
                 $nome,
                 $html_local_pagamento,
                 $data_pagamento,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                 db_moeda($valor_total)
               );
            }  
        }
        
        }
      
  }elseif($item == 'gui_pagamentos_guia_todos'){        
        $where = '';
        $where_id_parceiro = '';
        $where_busca = '';
        if($nivel_usuario != 'A'){
            $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
        }else{
            if(in_array("44", $verifica_lista_permissoes_array_inc)){
                
            }else{
                $where_id_parceiro = "AND gu.id_parceiro = $usr_parceiro";
            }
        }
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){  
        }
        else{
        $sql_contar        = "SELECT COUNT(*) FROM gui_pagamentos_guias pag
                            JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                            WHERE gu.del = 'N' $where_id_parceiro";
        $query_contar      = mysql_query($sql_contar, $banco_painel);
        
        $iTotalRecords = mysql_result($query_contar, 0,0);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);
        
        $records = array();
        $records["data"] = array(); 
        
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        
        $sql        = "SELECT gu.id_guia, pa.id_paciente, pa.nome, pag.* FROM gui_pagamentos_guias pag
                    JOIN gui_guias gu ON pag.id_guia = gu.id_guia
                    JOIN gui_pacientes pa ON gu.id_paciente = pa.id_paciente
                    WHERE gu.del = 'N' $where_id_parceiro 
                    ORDER BY pag.id_pagamento DESC
                    LIMIT $iDisplayStart,$iDisplayLength";
        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0){
            while ($dados = mysql_fetch_array($query))
            {
                extract($dados);  
                
                $status_list = array(
                    array("success" => "Pago"),
                    array("danger" => "Nao pago")
                );
                
                if($status == 'PAGO'){
                    $status = $status_list[0];
                }
                else{
                    $status = $status_list[1];
                }
                if($local_pagamento == 'LOCAL'){
                    $html_local_pagamento = "Na emissao desta guia";
                }else{
                    $html_local_pagamento = "No local de atendimento";
                }

                $data_pagamento = converte_data($data_pagamento);

                 $records["data"][] = array(
                 '<a href="gui_editar.php?item=gui_guias_detalhes&id='.$id_guia.'&tipo=gui_guias_detalhes" target="_blank">'.$id_guia.'</a>',
                 $nome,
                 $html_local_pagamento,
                 $data_pagamento,
                 '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                 '<a href="#" class="btn btn-sm btn-outline green"><i class="fa fa-search"></i> Ver</a>'
               );
            }  
        }
        
        }
      
  }elseif($item == 'pagamentos_usuario'){
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){   
        }else{
            $where = '';
            $data_inicio = '2018-11-01';
            $agora 			= date("Y-m-d");
            $sql_contar        = "SELECT COUNT(*) FROM boletos_clientes
                            WHERE pago = 'S' AND ((baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2)) OR (baixa_recebimento = 'S' AND status_boleto IN (0,1,2) AND data_baixa = '$agora')) AND id_usuario_recebimento = $id_usuario_pagamento AND tipo_recebimento IN ('AV', 'BO', 'CA')";
                                               
            $query_contar      = mysql_query($sql_contar, $banco_painel);
            
            $iTotalRecords = mysql_result($query_contar, 0,0);
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);
            
            $records = array();
            $records["data"] = array(); 
            
            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;
            
            $status_list = array(
                array("green" => "Pago"),
                array("red" => "Receber")
            );
            
            // status_boleto = 0 (ativo)
            // status_boleto = 1 (concluido)
            // status_boleto = 2 (cancelado)
            $sql        = "SELECT * FROM boletos_clientes
            WHERE pago = 'S' AND ((baixa_recebimento = 'N' AND data_pagamento > '$data_inicio' AND status_boleto IN (0,1,2)) OR (baixa_recebimento = 'S' AND status_boleto IN (0,1,2) AND data_baixa = '$agora')) AND id_usuario_recebimento = $id_usuario_pagamento AND tipo_recebimento IN ('AV', 'BO', 'CA')
                        ORDER BY data_pagamento DESC
                        LIMIT $iDisplayStart,$iDisplayLength";
            $query          = mysql_query($sql, $banco_painel);               
            if (mysql_num_rows($query)>0){
                while ($dados = mysql_fetch_array($query))
                {
                    extract($dados); 
                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                         WHERE id_parceiro = $id_parceiro";
                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                    $nome_parceiro = '';   
                    if (mysql_num_rows($query_parceiro)>0)
                    {
                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                    }

                $sql_cliente_ordem        = "SELECT ordem_pedido FROM ordem_pedidos
                                             WHERE id_ordem_pedido = $id_ordem_pedido";
                $query_cliente_ordem      = mysql_query($sql_cliente_ordem, $banco_painel);
                   
                if (mysql_num_rows($query_cliente_ordem)>0){
                    $ordem_pedido        = mysql_result($query_cliente_ordem, 0,'ordem_pedido');
                }        
       
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                $pegar_nome_ok = false;
                $nome_cliente = '';
                for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++){
                    $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                    $id_base = $array_ids_base_vendas[0];
                    $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                    
                    // FAZ A CONEX�O COM BANCO DE DADOS DO PRODUTO
                    $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                WHERE bpro.id_base_produto = $id_base";
    
                    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                    
                    if (mysql_num_rows($query_base)>0){
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
                        
                        if($pegar_nome_ok == false){
                            
                            $sql_venda  = "SELECT v.metodo_pagamento, c.nome FROM vendas v
                                        JOIN clientes c ON v.id_cliente = c.id_cliente
                                        WHERE v.id_venda = $ids_vendas[0] AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                            
                            if (mysql_num_rows($query_venda)>0)
                            {
                                $nome_cliente       = mysql_result($query_venda, 0, 'nome');
                                $metodo_pagamento   = mysql_result($query_venda, 0, 'metodo_pagamento');
                                $pegar_nome_ok = true;
                            }
                        
                        }
                    
                    }
                    
                }
                    
                    $html_exibe_bt_confirma = '';
                    if($nivel_usuario == 'A' AND in_array("41", $verifica_lista_permissoes_array_inc)){
                        
                        if($baixa_recebimento == 'N'){
                            $html_texto_bt_confirma = "Confirmar";
                            $html_class_cor = "btn-info";
                        }else{
                            $html_texto_bt_confirma = "Cancelar";
                            $html_class_cor = "btn-danger";
                        }

                    }
   
                    $status_list = array(
                        array("green" => "Pago"),
                        array("red" => "Receber")
                    );
                    
                    if($pago == 'N'){
                        $status = $status_list[1];
                    }
                    else{
                        $status = $status_list[0];
                    }
                    
                    $pg_html_exibe = 'hide';
                    if($pago == 'S'){
                        $pg_html_exibe = '';
                    }
                    
                    if($metodo_pagamento == 'BO'){
                        $html_metodo_pagamento = 'BOLETO';
                    }elseif($metodo_pagamento == 'MA'){
                        $html_metodo_pagamento = 'MAQUINA';
                    }elseif($metodo_pagamento = 'ON'){
                        $html_metodo_pagamento = 'ON-LINE';
                    }
                    
                    $html_tipo = '';
                    if($entrada == 'S'){
                        $html_tipo = "ENTRADA-".$tipo_recebimento;
                    }else{
                        $html_tipo = $tipo_recebimento.' '.$html_metodo_pagamento;
                    }
                     $records["data"][] = array(
                     $id_boleto,
                     $nome_cliente,
                     $nome_parceiro,
                     $parcela.' / '.$total_parcelas,
                     $html_tipo,
                     $valor_recebido,
                     $valor_recebido,
                     converte_data($data_pagamento),
                     converte_data($data_vencimento),
                     '<a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a>'.$html_exibe_bt_confirma,
                   );
                }
                
            }
        
     }   
    }

    if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
        $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
        $records["customActionMessage"] = "Lista carregada com sucesso!"; // pass custom message(useful for getting status of group actions)
        }
        
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        
        echo json_encode($records);      

?>