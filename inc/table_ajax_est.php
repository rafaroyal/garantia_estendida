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
    $id                         = (empty($_GET['id'])) ? "" : verifica($_GET['id']);  
    $tipo                       = (empty($_GET['tipo'])) ? "" : verifica($_GET['tipo']);  
    $id_grupo_parceiro          = (empty($_GET['id_grupo_parceiro'])) ? "" : verifica($_GET['id_grupo_parceiro']); 
    $id_serv_get                = (empty($_GET['id_serv_get'])) ? "" : verifica($_GET['id_serv_get']); 
    $tipo_filtro                = (empty($_GET['tipo_filtro'])) ? "" : verifica($_GET['tipo_filtro']); 
    $id_usuario_pagamento       = (empty($_GET['id_usuario_pagamento'])) ? "" : verifica($_GET['id_usuario_pagamento']);
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
    
    $verifica_lista_permissoes  = '';
    if (base64_decode($_COOKIE["usr_id"]) < 1){
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
    }elseif($item == 'est_movimentacoes'){        
        
        if(isset($_POST['campofiltro']) AND !empty($_POST['campofiltro'])){

        }else{

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