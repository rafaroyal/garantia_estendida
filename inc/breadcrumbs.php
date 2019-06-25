<?php
/**
 * @project Envy
 * @author Lucas Vinícius Leati
 * @created 14/06/2012
 */
 
$url_digitada = $_SERVER['REQUEST_URI'];
                 
// Transforma a URL em array separando a string a cada barra
$url_array = explode("/", $url_digitada); 
$atual = array_pop($url_array);

 
$bc = "<ul class=\"page-breadcrumb breadcrumb\">
        <li><a href=\"inicio.php\">Home</a><i class=\"fa fa-circle\"></i></li>
        ";
if ($item == "clientes")
{
	if(empty($id))
	{
		if ($atual == 'adicionar.php?item=clientes')
        {
            $bc.= "<li>Clientes<i class=\"fa fa-circle\"></i></li>";
            $bc.= "<li><a href=''>Adicionar Cliente </a></li>";
        }   
        else 
        {
            $bc.= "<li><a href=''>Clientes</a></li>";
        } 
		
	}	
	else
	{
		$bc.= "<li>Clientes<i class=\"fa fa-circle\"></i></li>";
        
        /*if($id == 'todos'){
           $bc.= '<li> Todos </li>'; 
        }else{*/
           $bc.= "<li> ".result("SELECT nome FROM servicos WHERE id_servico = $id_serv_get")."</li>";
        //}
		
	}
	
	
}
elseif ($item == "solicitacoes")
{
    if(empty($id))
    {
        if ($atual == 'adicionar.php?item=solicitacoes')
        {
            $bc.= "<li><a href='listar.php?item=solicitacoes'>Solicitações</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= "<li><a href=''>Adicionar Solicitação</a></li>";
        }
        else
        {
            $bc.= "<li><a href=''>Solicitações</a></li>";
        }

    }
    else
    {
        $bc.= "<li><a href='listar.php?item=solicitacoes'>Solicitações</a><i class=\"fa fa-circle\"></i></li>";
        $bc.= "<li>Solicitação nº $id</li>";
    }
}
elseif ($item == "pesquisa")
{
    $bc.= "<li><a href='inicio.php'>Pesquisa</a><i class=\"fa fa-circle\"></i></li>";
    $bc.= "<li>Pesquisando por \"".$_POST['search']."\"</li>";  
}
elseif ($item == "vendas")
{
    if(empty($id))
	{
		if ($atual == 'adicionar.php?item=vendas')
        {
            $bc.= "<li><a href='listar.php?item=vendas'>Vendas</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= "<li><a href=''>Adicionar Vendas</a></li>";
        }   
        else 
        {
            $bc.= "<li><a href=''>Vendas</a></li>";
        } 
		
	}	
	else
	{
		$bc.= "<li><a href='listar.php?item=vendas'>Vendas</a></li>";
		
	}
    
    
}
if ($item == "parceiros")
{
	if(empty($id))
	{
		if ($atual == 'adicionar.php?item=parceiros')
        {
            $bc.= "<li><a href='listar.php?item=parceiros'>Parceiros</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= " <li> Adicionar Parceiros</li>";
        }   
        else 
        {
            $bc.= "<li>Parceiros</li>";
        } 
		
	}	
	else
	{
		$bc.= "<li><a href='listar.php?item=parceiros'>Parceiros</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li>".result("SELECT nome FROM parceiros WHERE id_parceiro = $id")."</li>";
	}
	
	
}
if ($item == "grupos_parceiros")
{
	if(empty($id))
	{
		if ($atual == 'adicionar.php?item=grupos_parceiros')
        {
            $bc.= "<li><a href='listar.php?item=grupos_parceiros'>Grupos de Parceiros</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= " <li> Adicionar Grupo</li>";
        }   
        else 
        {
            $bc.= "<li>Grupos de Parceiros</li>";
        } 
		
	}	
	else
	{
		$bc.= "<li><a href='listar.php?item=grupos_parceiros'>Grupos de Parceiros</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li>".result("SELECT nome FROM grupos_parceiros WHERE id_grupo_parceiro = $id")."</li>";
	}
	
	
}
if ($item == "grupo_parceiro")
{
	if(empty($id_grupo_parceiro))
	{
		if ($atual == 'adicionar.php?item=grupos_parceiros')
        {
            $bc.= "<li><a href='listar.php?item=grupos_parceiros'>Grupos de Parceiros</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= " <li> Adicionar Grupo</li>";
        }   
        else 
        {
            $bc.= "<li>Grupo Parceiro</li>";
        } 
		
	}	
	else
	{
		$bc.= "<li><a href='listar.php?item=grupos_parceiros'>Grupos de Parceiros</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li>".result("SELECT nome FROM grupos_parceiros WHERE id_grupo_parceiro = $id_grupo_parceiro")."</li>";
	}
	
	
}
if ($item == "info_comissoes")
{
        $bc.= "<li><a href='listar.php?item=parceiros'>Parceiros</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li><a href='ver.php?item=parceiros&id=$id'>".result("SELECT nome FROM parceiros WHERE id_parceiro = $id")."</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li>Pagamentos de Comissões</li>"; 
}

if ($item == "info_comissoes_det")
{
        $bc.= "<li><a href='listar.php?item=parceiros'>Parceiros</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li><a href='ver.php?item=parceiros&id=$id_parceiro'>".result("SELECT nome FROM parceiros WHERE id_parceiro = $id_parceiro")."</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li>Pagamentos de Comissões</li>"; 
}

if ($item == "servicos")
{
	if(empty($id))
	{
		if ($atual == 'adicionar.php?item=servicos')
        {
            $bc.= "<li><a href='listar.php?item=servicos'>Serviços</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= "<li><a href=''>Adicionar Serviços</a></li>";
        }   
        else 
        {
            $bc.= "<li><a href=''>Serviços</a></li>";
        } 
		
	}	
	else
	{
		$bc.= "<li><a href='listar.php?item=servicos'>Servicos</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li><a href=''>".result("SELECT nome FROM servicos WHERE id_servico = $id")."</a></li>";
	}
	
	
}
elseif ($item == "info_servicos")
{
	if(empty($id))
	{
		if ($atual == 'adicionar.php?item=servicos')
        {
            $bc.= "<li><a href='listar.php?item=servicos'>Serviços</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= "<li><a href=''>Adicionar Serviços</a></li>";
        }   
        else 
        {
            $bc.= "<li><a href=''>Serviços</a></li>";
        } 
		
	}	
	else
	{
		$bc.= "<li><a href='listar.php?item=servicos'>Servicos</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li>".result("SELECT nome FROM servicos WHERE id_servico = $id")."</li>";
	}
	
	
}
elseif ($item == "usuarios")
{
    if(empty($id))
	{
		if ($atual == 'adicionar.php?item=parceiros')
        {
            $bc.= "<li><a href='listar.php?item=usuarios'>Usuários</a><i class=\"fa fa-circle\"></i></li>";
            $bc.= " <li> Adicionar Usuário</li>";
        }   
        else 
        {
            $bc.= "<li>Usuários</li>";
        } 
		
	}	
	else
	{
		$bc.= "<li><a href='listar.php?item=usuarios'>Usuários</a><i class=\"fa fa-circle\"></i></li>";
		$bc.= "<li>".result("SELECT nome FROM usuarios WHERE id_usuario = $id")."</li>";
	}
}
$bc.= "</ul>";
?>