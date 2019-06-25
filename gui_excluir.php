<?php
/**
 * @project Envy
 * @author Lucas Vinícius Leati
 * @created 01/06/2012
 */

require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
$banco_painel = $link;
if (empty($_GET))
{
    header("Location: inicio.php");       
}
else
{
    foreach ($_GET as $campo => $valor) {  $$campo = verifica($valor); }    
}

if ($item == "gui_convenios")
{
    $verifica = result("SELECT COUNT(*) FROM gui_convenios WHERE id_convenio = $id");
   
    if ($verifica>0)
    {
        $sql    = "DELETE FROM gui_convenios WHERE id_convenio = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: gui_listar.php?item=gui_convenios&id=$id&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: gui_listar.php?item=gui_convenios&id=$id&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: gui_listar.php?item=gui_convenios&id=$id&msg_status=excluir_erro");  
    }
    
}elseif ($item == "gui_grupo_procedimentos")
{
    $verifica = result("SELECT COUNT(*) FROM gui_grupo_procedimentos WHERE id_grupo_procedimento = $id");
   
    if ($verifica>0)
    {
        $sql    = "DELETE FROM gui_grupo_procedimentos WHERE id_grupo_procedimento = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: gui_listar.php?item=gui_grupo_procedimentos&id=$id&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: gui_listar.php?item=gui_grupo_procedimentos&id=$id&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: gui_listar.php?item=gui_grupo_procedimentos&id=$id&msg_status=excluir_erro");  
    }
    
}elseif ($item == "gui_procedimentos")
{
    $verifica = result("SELECT COUNT(*) FROM gui_procedimentos WHERE id_procedimento = $id");
   
    if ($verifica>0)
    {
        $sql    = "DELETE FROM gui_procedimentos WHERE id_procedimento = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: gui_listar.php?item=gui_procedimentos&id=$id&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: gui_listar.php?item=gui_procedimentos&id=$id&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: gui_listar.php?item=gui_procedimentos&id=$id&msg_status=excluir_erro");  
    }
    
}elseif ($item == "gui_profissionais")
{
    $verifica = result("SELECT COUNT(*) FROM gui_profissionais WHERE id_profissional = $id");
   
    if ($verifica>0)
    {
        $sql    = "DELETE FROM gui_profissionais WHERE id_profissional = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: gui_listar.php?item=gui_profissionais&id=$id&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: gui_listar.php?item=gui_profissionais&id=$id&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: gui_listar.php?item=gui_profissionais&id=$id&msg_status=excluir_erro");  
    }
    
}elseif ($item == "gui_pacientes")
{
    $verifica = result("SELECT COUNT(*) FROM gui_pacientes WHERE id_paciente = $id");
   
    if($verifica>0)
    {
        $sql    = "DELETE FROM gui_pacientes WHERE id_paciente = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: gui_listar.php?item=gui_pacientes&id=$id&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: gui_listar.php?item=gui_pacientes&id=$id&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: gui_listar.php?item=gui_pacientes&id=$id&msg_status=excluir_erro");  
    }
    
}elseif ($item == "gui_guias_detalhes")
{
    $verifica = result("SELECT COUNT(*) FROM gui_guias WHERE id_guia = $id");
   
    if($verifica>0)
    {
        $sql    = "DELETE FROM gui_guias WHERE id_guia = $id";         
        $query  = mysql_query($sql);
        
        $sql    = "DELETE FROM gui_procedimentos_guia WHERE id_guia = $id";         
        $query  = mysql_query($sql);
        
        $sql    = "DELETE FROM gui_pagamentos_guias WHERE id_guia = $id";         
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: gui_listar.php?item=gui_guias&id=$id&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: gui_listar.php?item=gui_guias&id=$id&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: gui_listar.php?item=gui_guias&id=$id&msg_status=excluir_erro");  
    }
    
}
?>