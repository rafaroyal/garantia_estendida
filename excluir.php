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

if ($item == "cliente")
{

    $sql_ordem   = "SELECT ordem_pedido, status_recorrencia FROM ordem_pedidos
                    WHERE id_ordem_pedido = $order_number";

    $query_ordem = mysql_query($sql_ordem, $banco_painel) or die(mysql_error()." - 0");
    
    if (mysql_num_rows($query_ordem)>0)
    {
        $ordem_pedido       = mysql_result($query_ordem, 0, 'ordem_pedido');
        $status_recorrencia = mysql_result($query_ordem, 0, 'status_recorrencia');
        $contar_retorno = 0;
        $array_id_base_ids_vendas = explode("|", $ordem_pedido);
        
        $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
        for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
        {
            $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
            $id_base = $array_ids_base_vendas[0];
            $ids_vendas = explode("-", $array_ids_base_vendas[1]);
            
            // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
            $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.id_servico, pro.versao_produto FROM bases_produtos bpro
                        JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                        WHERE bpro.id_base_produto = $id_base";

            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
            
            if (mysql_num_rows($query_base)>0)
            {
                $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                $banco_user             = mysql_result($query_base, 0, 'banco_user');
                $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                $banco_host             = mysql_result($query_base, 0, 'banco_host');
                $slug                   = mysql_result($query_base, 0, 'slug');
                $id_servico             = mysql_result($query_base, 0, 'id_servico');
                $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                
                $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                //$array_slug_base_produto[]  = $slug;
            }
            $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
            
            if($slug == 'europ'){
                $hoje              = date('Y-m-d');
                $contar_ids_vendas = count($ids_vendas) - 1;
                $enviar_info = 'N';
                for($p = 0;$p<=$contar_ids_vendas;$p++)
                {
                    $sql_venda  = "SELECT c.id_cliente, c.tipo_movimento, c.status FROM vendas v
                                JOIN clientes c ON v.id_cliente = c.id_cliente
                                WHERE v.id_venda = $ids_vendas[$p]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente = mysql_result($query_venda, 0, 'id_cliente');
                        $tipo_movimento = mysql_result($query_venda, 0, 'tipo_movimento');
                        $status_cliente = mysql_result($query_venda, 0, 'status');
                        
                        if($p == 0){
                            $id_cliente_historico = $id_cliente;
                        }
                        
                        if($status_cliente == 99){
                            $novo_tipo_movimento = 'EX';
                            $novo_status = 0;
                        }elseif($status_cliente == 0){
                            if($tipo_movimento == 'IN'){
                                $novo_status = 99;
                                $novo_tipo_movimento = 'EX';
                            }elseif($tipo_movimento == 'AL'){
                                $novo_status = 0;
                                $novo_tipo_movimento = 'EX';
                            }
                        }elseif($status_cliente == 4 OR $status_cliente == 3 OR $status_cliente == 6){
                            $novo_status = 99;
                            $novo_tipo_movimento = 'EX';
                        }elseif($status_cliente == 5){
                            $novo_status = 0;
                            $novo_tipo_movimento = 'EX';
                        }
                       
                        $sql_c_europ    = "UPDATE clientes SET tipo_movimento = '$novo_tipo_movimento', data_cancelamento = '$hoje', status = '$novo_status', observacao = '$tipo_cancel', cliente_recorrente = 'N'
                                            WHERE id_cliente = $id_cliente";
                        $query_c_europ  = mysql_query($sql_c_europ, $banco_produto);
                        
                        $sql_c_europ    = "UPDATE clientes SET tipo_movimento = '$novo_tipo_movimento', data_cancelamento = '$hoje', status = '$novo_status', observacao = '$tipo_cancel', cliente_recorrente = 'N'
                                            WHERE id_cliente_principal = $id_cliente AND tipo_movimento IN ('IN', 'AL')";
                        $query_c_europ  = mysql_query($sql_c_europ, $banco_produto);
                        
                        if($query_c_europ){
                            $contar_retorno++;
                        }

                    }
                }
                
               

            }elseif($slug == 'sorteio_ead'){
                $agora 			= date("Y-m-d H:i:s");
                $contar_ids_vendas = count($ids_vendas) - 1;
                for($p = 0;$p<=$contar_ids_vendas;$p++)
                {
                    $sql_venda   = "SELECT c.id_venda FROM vendas_painel v
                                    JOIN vendas c ON v.id_venda = c.id_venda
                                    WHERE v.id_venda_painel = $ids_vendas[$p]";
                        $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 5");
                        
                        if (mysql_num_rows($query_venda)>0)
                        {
                            $id_cliente      = mysql_result($query_venda, 0, 'id_venda');
                            
                            $sql3    = "UPDATE vendas SET status = 'C', dt_modificacao = '$agora' 
                                     WHERE id_venda = $id_cliente";
                            $query3  = mysql_query($sql3, $banco_produto) or die(mysql_error()." - 7");
                            
                            if($query3){
                                $contar_retorno++;
                            }
                                
                        }
                }

            }
        }
    }
    
    if($contar_retorno > 0){
        
        $id_usuario_s    = base64_decode($_COOKIE["usr_id"]);
        $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
        VALUES ('$id_cliente_historico', '$id_usuario_s', 'clientes', 'Alteracao = excluir_ok', '".agora()."')";   
        $query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");
        
        // status_boleto = 0 (ativo)
        // status_boleto = 1 (concluido)
        // status_boleto = 2 (cancelado)
        $sql_up_boletos    = "UPDATE boletos_clientes SET status_boleto = 2
        WHERE id_ordem_pedido = $order_number AND status_boleto != 1";              
        $query_up_boletos    = mysql_query($sql_up_boletos, $banco_painel) or die(mysql_error()." - 14");
        
        
         $sql_ordem   = "UPDATE ordem_pedidos SET status_recorrencia = 'N', enviar_info = 'N'
                                        WHERE id_ordem_pedido = $order_number";
         $query_ordem = mysql_query($sql_ordem, $banco_painel) or die(mysql_error()." - 4.5");
         
         if($query_ordem){
            header("Location: listar.php?item=clientes&id_serv=$id_servico&id=todos&tipo=produto&msg_status=excluir_ok");  
         }
    
    }
    
}
elseif ($item == "servico_parceiro_utilizacao")
{

    $verifica = result("SELECT COUNT(*) FROM parceiros_servicos WHERE id_parceiro = $id_parceiro AND id_servico = $id_servico");
   
    if ($verifica>0)
    {
        $sql    = "DELETE FROM parceiros_servicos WHERE id_parceiro = $id_parceiro AND id_servico = $id_servico";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: editar.php?item=parceiros&id=$id_parceiro&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: ver.php?item=parceiros&id=$id_pasta&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: ver.php?item=parceiros&id=$id_pasta&msg_status=id");
    }
    
}
elseif ($item == "cobertura")
{

    $verifica = result("SELECT COUNT(*) FROM coberturas WHERE id_cobertura = $id");
   
    if ($verifica>0)
    {
        $sql    = "DELETE FROM coberturas WHERE id_cobertura = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: listar.php?item=info_servicos&id=$id_servico&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: listar.php?item=info_servicos&id=$id_servico&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: ver.php?item=info_servicos&id=$id_servico&msg_status=id");
    }
    
}
elseif ($item == "pagamento_faturamento")
{

    $verifica = result("SELECT COUNT(*) FROM pagamentos WHERE id_pagamento = $id");
   
    if ($verifica>0)
    {
        $sql    = "DELETE FROM pagamentos WHERE id_pagamento = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: listar.php?item=pagamentos&msg_status=excluir_ok");  
        }
        else
        {
            header("Location: listar.php?item=pagamentos&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: ver.php?item=pagamentos&msg_status=id");
    }
    
}
elseif  ($item == "usuarios")
{
    
        
    $verifica = result("SELECT COUNT(*) FROM usuarios WHERE id_usuario = $id");
   
    if ($verifica>0)
    {
        //registro existe
    
        $sql    = "DELETE FROM usuarios WHERE id_usuario = $id";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: listar.php?item=$item&msg_status=excluir_ok");    
        }
        else
        {
            header("Location: listar.php?item=$item&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: editar.php?item=$item&id=$id&msg_status=id");
    }
    
}
elseif  ($item == "parceiros")
{
 
    $verifica = "SELECT del FROM parceiros WHERE id_parceiro = '$id'";
    $query_verifica = mysql_query($verifica, $banco_painel) or die(mysql_error()." - 5");
    if (mysql_num_rows($query_verifica)>0)
    {
        //registro existe
        $del      = mysql_result($query_verifica, 0, 'del');
        if($del == 'N'){
            $sql   = "UPDATE parceiros SET del = 'S', dt_del = '".agora()."'
            WHERE id_parceiro = $id";
            $query = mysql_query($sql, $banco_painel) or die(mysql_error()." - parceiro");
        }else{
            $sql    = "DELETE FROM parceiros WHERE id_parceiro = $id";        
            $query  = mysql_query($sql, $banco_painel) or die(mysql_error()." - parceiro_ex");
        }
        
        if ($query)
        {
            header("Location: listar.php?item=$item&msg_status=excluir_ok");    
        }
        else
        {
            header("Location: listar.php?item=$item&msg_status=excluir_erro");  
        }       
        
    } 
    else 
    {
        header("Location: editar.php?item=$item&id=$id&msg_status=id&tipo=parceiro");
    }
    
}
elseif  ($item == "parceiros_filiais")
{
 
    $verifica = "SELECT * FROM filiais WHERE id_filial = '$id_filial' AND id_parceiro = '$id_parceiro'";
    $query_verifica = mysql_query($verifica, $banco_painel) or die(mysql_error()." - 5");
    if (mysql_num_rows($query_verifica)>0)
    {
        $sql_4    = "DELETE FROM filiais WHERE id_parceiro = $id_parceiro AND id_filial = $id_filial";
        $query_4  = mysql_query($sql_4) OR DIE (mysql_error());
        
        if ($query_4)
        {
            echo "ok";
        }
        else
        {
            echo "erro1";
        }       
        
    } 
    else 
    {
        echo "erro2".$verifica;
    }
    
}
?>