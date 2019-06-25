<?php
/**
 * @project GED
 * @author Lucas Vinícius Leati
 * @created 01/06/2012
 */

require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');

$agora = agora();
 
    
if (empty($_POST))
{
    if (empty($_GET))
    {
        header("Location: inicio.php"); 
    }
    else
    {
        $item = (empty($_GET['item'])) ? "" : verifica($_GET['item']);
    }
    
        
}
else
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
}
$usr_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id          = base64_decode($_COOKIE["usr_id"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status    = base64_decode($_COOKIE["nivel_status"]);
if ($item == "gui_local_atendimento")
{
        $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

        /*$sql_img = '';
        $novo_nome_arquivo = ($tipopessoa == "PF") ? $cpf : $cnpj;
        if (!empty($_FILES['logo']['name']))
        {
            $novo_nome_arquivo = preg_replace("/[^0-9]/","", $novo_nome_arquivo);
            $pasta = "assets/pages/img/logos/";
            $array = explode(".", $_FILES['logo']['name']);
            $extensao = (end($array));
            $nome_arquivo = $novo_nome_arquivo.".".$extensao;
            $arquivo = $pasta . $nome_arquivo ;
            $sql_img = ", logo = '$nome_arquivo'";
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $arquivo))
            {
                include('inc/image.php');
                $image = new SimpleImage();
                $image->load($arquivo);
                $image->resizeToHeight(100, $arquivo);
                $image->save($arquivo);
            }

        }*/
       
       $sql    = "UPDATE gui_local_atendimento SET conveniado = '$conveniado', nome = '$nome', tipo = '$tipo_local_atendimento', email = '$email', numero_cnes = '$cnes', telefone = '$telefone_com', telefone_alt = '$telefone_alt', celular = '$celular', cep = '$cep', endereco = '$endereco', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', obs = '$observacoes_local', local_pagamento = '$ver_local_pagamento', forma_faturamento = '$tipo_faturamento'
       WHERE id_local_atendimento = $id";
        $query  = mysql_query($sql) OR DIE (mysql_error());
        if ($query)
        {
            if(isset($check_editar_cidades) AND $check_editar_cidades == 'S')
            {
                $sql_4    = "DELETE FROM gui_cidades_locais WHERE id_local_atendimento = $id";
                $query_4  = mysql_query($sql_4);
                
                $nr_linhas = count($lista_cidades_local);

                for ($i = 0; $i<$nr_linhas; $i++)
                {
                    $id_cidade = $lista_cidades_local[$i];  

                    $sql3    = "INSERT INTO gui_cidades_locais (loc_nu_sequencial, id_local_atendimento)
                            VALUES ('$id_cidade', '$id')";
                
                    $query3  = mysql_query($sql3) or die(mysql_error());
                }
            }
            
                
                if(isset($add_procedimento))
                {
                    $nr_colunas = count($add_procedimento);
                    $contar_convenio = 0;
                    
                    for ($i = 0; $i<$nr_colunas; $i++)
                    {
                        $id_procedimento = $add_procedimento[$i];  
                        $nr_colunas_convenio = $add_contar_convenios[$i] - 1;
                        
                        for ($ii = 0; $ii<=$nr_colunas_convenio; $ii++)
                        {
                            $id_convenio = $add_id_convenio[$contar_convenio];
                            $valor_custo_array = moeda_db5($valor_custo[$contar_convenio]);
                            $valor_final_array = moeda_db5($valor_final[$contar_convenio]);
                            
                            $verifica = result("SELECT COUNT(*) FROM gui_local_atendimento_procedimentos WHERE id_local_atendimento = $id AND id_procedimento = $id_procedimento AND id_convenio = $id_convenio");
                            //$cnpj = $_POST['cnpj'];
                                    
                            if ($verifica>0)
                            {
                                
                                $sql2    = "UPDATE gui_local_atendimento_procedimentos SET preco_custo = '$valor_custo_array', preco_venda = '$valor_final_array' WHERE id_local_atendimento = $id AND id_procedimento = $id_procedimento AND id_convenio = $id_convenio";
                                $query2  = mysql_query($sql2) OR DIE (mysql_error());
                                
                            }
                            else
                            {
                                //adiciona registro
                            $sql3    = "INSERT INTO gui_local_atendimento_procedimentos (id_procedimento, id_local_atendimento, id_convenio, preco_custo, preco_venda)
                                        VALUES ('$id_procedimento', '$id', '$id_convenio', '$valor_custo_array', '$valor_final_array')";
                            
                                $query3  = mysql_query($sql3) or die(mysql_error());
                            }
                           
                            $contar_convenio = $contar_convenio + 1;
                            
                        }
                        
                    }
                }

            if(isset($remove_procedimento_local))
            {
                //$id_parceiro = mysql_insert_id();
                $nr_colunas = count($remove_procedimento_local);
                for ($i = 0; $i<$nr_colunas; $i++)
                {
                    $id_remove_procedimento_local = $remove_procedimento_local[$i];  
                    $sql_4    = "DELETE FROM gui_local_atendimento_procedimentos WHERE id_local_atendimento = $id AND id_procedimento = $id_remove_procedimento_local";
                    
                    $query_4  = mysql_query($sql_4);

                }
            }
            
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_ok");
            //echo 'ok';
        }

        else
        {
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_erro");
            //echo 'erro';
        }

}elseif ($item == "gui_convenios")
{
       $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

       $sql    = "UPDATE gui_convenios SET nome = '$nome', ativo = '$ativo'
       WHERE id_convenio = $id";
        $query  = mysql_query($sql) OR DIE (mysql_error());
        if ($query)
        {
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_ok");
            //echo 'ok';
        }

        else
        {
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_erro");
            //echo 'erro';
        }

}elseif ($item == "gui_grupo_procedimentos")
{
       $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

       $sql    = "UPDATE gui_grupo_procedimentos SET nome = '$nome', ativo = '$ativo'
       WHERE id_grupo_procedimento = $id";
        $query  = mysql_query($sql) OR DIE (mysql_error());
        if ($query)
        {
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_ok");
            //echo 'ok';
        }

        else
        {
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_erro");
            //echo 'erro';
        }

}elseif ($item == "gui_procedimentos")
{
       //$nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
       $valor_custo = moeda_db($valor_custo);
       $valor_final =  moeda_db($valor_final);
       $sql    = "UPDATE gui_procedimentos SET id_grupo_procedimento = '$grupo_procedimento', codigo = '$codigo_procedimento', nome = '$nome', obs = '$observacoes_procedimentos', ativo = '$ativo'
       WHERE id_procedimento = $id";
        $query  = mysql_query($sql) OR DIE (mysql_error());
        if ($query)
        {
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_ok");
            //echo 'ok';
        }

        else
        {
            header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_erro");
            //echo 'erro';
        }

}elseif ($item == "gui_profissionais")
{
       $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
       
        $contar_convenios = count($lista_convenios);
        $lista_convenios_array = '';
        if($contar_convenios > 0){
            $lista_convenios_array = implode("|", $lista_convenios);
        }
        
        /*$contar_local = count($lista_local_atendimento);
        $lista_local_atendimento_array = '';
        if($contar_local > 0){
            $lista_local_atendimento_array = implode("|", $lista_local_atendimento);
        }*/
        
        $dt_nasc_profissional = converte_data($dt_nasc_profissional);
         $verifica = result("SELECT COUNT(*) FROM gui_profissionais WHERE conselho = '$conselho' AND registro = '$registro' AND id_profissional <> $id"); 
    
        if ($verifica>0)
        {
            //já existe pasta com esse nome  
            header("Location: gui_adicionar.php?item=$item&msg_status=editar_erro");    
        } 
        else 
        {
           $sql    = "UPDATE gui_profissionais SET nome = '$nome', id_profissao = '$profissao', id_especialidade = '$select_especialidade', ids_convenios = '$lista_convenios_array', conveniado = '$conveniado', data_nascimento = '$dt_nasc_profissional', telefone = '$telefone_com', celular = '$celular', email = '$email', tratamento = '$tratamento_profissional', conselho = '$conselho', registro = '$registro', ativo = '$ativo'
           WHERE id_profissional = $id";
            $query  = mysql_query($sql) OR DIE (mysql_error());
            if ($query)
            {
                
                
                    $sql_4    = "DELETE FROM gui_local_atendimento_profissional WHERE id_profissional = $id";
                    $query_4  = mysql_query($sql_4);
                    
                    $nr_linhas = count($lista_local_atendimento);
    
                    for ($i = 0; $i<$nr_linhas; $i++)
                    {
                        $id_local_atendimento_sel = $lista_local_atendimento[$i];  
    
                        $sql3    = "INSERT INTO gui_local_atendimento_profissional (id_profissional, id_local_atendimento)
                                VALUES ('$id', '$id_local_atendimento_sel')";
                    
                        $query3  = mysql_query($sql3) or die(mysql_error());
                    }
                    
                    if(isset($principal_excluir_id_especialidade_profissional))
                    {
                        //$id_parceiro = mysql_insert_id();
                        $nr_colunas = count($principal_excluir_id_especialidade_profissional);
                        for ($i = 0; $i<$nr_colunas; $i++)
                        {
                            $id_principal_excluir_id_especialidade_profissional = $principal_excluir_id_especialidade_profissional[$i];  
                            $sql_4    = "DELETE FROM gui_especialidades_profissional WHERE id_especialidade_profissional = $id_principal_excluir_id_especialidade_profissional";
                            
                            $query_4  = mysql_query($sql_4);
        
                        }
                    }
                    
                    if(isset($select_especialidade))
                    {
                        $nr_linhas = count($select_especialidade);
                        for ($i = 0; $i<$nr_linhas; $i++)
                        {
                            $id_select_especialidade_sel = $select_especialidade[$i]; 
                            $rqe_sel                     = $rqe[$i];  
                            
                            if(!empty($id_select_especialidade_sel))
                            {
                                $sql3    = "INSERT INTO gui_especialidades_profissional (id_especialidade, id_profissional, rqe)
                                            VALUES ('$id_select_especialidade_sel', '$id', '$rqe_sel')";
                                $query3  = mysql_query($sql3) or die(mysql_error());
                            }
                        }
                    }
                
                
                
                header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_ok");
                //echo 'ok';
            }
    
            else
            {
                header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_erro");
                //echo 'erro';
            }
        }
}elseif ($item == "gui_pacientes")
{
       $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
       
       $data_nasc = converte_data($data_nasc);
       $cpf_paciente = limpa_caracteres($cpf_paciente);
       $cep_paciente = limpa_caracteres($cep_paciente);
       $verifica = result("SELECT COUNT(*) FROM gui_pacientes WHERE nome = '$nome' AND data_nascimento = '$data_nasc' AND sexo = '$sexo' AND id_paciente <> $id"); 
        if ($verifica>0)
        {
            //já existe pasta com esse nome  
            header("Location: gui_adicionar.php?item=$item&msg_status=cliente_existe");    
        } 
        else 
        {
           $sql    = "UPDATE gui_pacientes SET id_cliente = '$id_cliente', id_convenio = '$convenio_paciente', nome = '$nome', cpf = '$cpf_paciente', data_nascimento = '$data_nasc', sexo = '$sexo', email = '$email', telefone = '$telefone', celular = '$celular', comercial = '$comercial', cep = '$cep_paciente', endereco = '$endereco_paciente', numero = '$numero_paciente', complemento = '$complemento_paciente', bairro = '$bairro_paciente', cidade = '$cidade_paciente', estado = '$estado_paciente', dados_completo = 'S', data_modificacao = '".agora()."'
           WHERE id_paciente = $id";
            $query  = mysql_query($sql) OR DIE (mysql_error());
            if ($query)
            {
                header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_ok");
                //echo 'ok';
            }
    
            else
            {
                header("Location: gui_listar.php?item=$item&id=$id&msg_status=editar_erro");
                //echo 'erro';
            }
        }

}elseif ($item == "gui_guias_detalhes_confirmar")
{
       
       
       $verifica = result("SELECT COUNT(*) FROM gui_guias WHERE id_guia = $id AND status = 'AGENDADO' AND del = 'N'"); 
        if ($verifica == 0)
        {
            //não existe pasta com esse nome  
            header("Location: gui_editar.php?item=$item&id=$id&tipo=gui_guias_detalhes&msg_status=editar_erro");    
        } 
        else 
        {
            $sql    = "UPDATE gui_guias SET data_emissao = '".agora()."', id_usuario_emissao = $usr_id, status = 'EMITIDO'
            WHERE id_guia = $id";
            $query  = mysql_query($sql) OR DIE (mysql_error());
            if ($query)
            {
                
                if($local_pagamento == 'LOCAL'){
                    $data_de_pagamento = agora();
                    $status_pago = "PAGO";
                }else{
                    $data_de_pagamento = $data_agendamento;
                    $status_pago = "";
                }
                
                $sql    = "UPDATE gui_pagamentos_guias SET local_pagamento = '$local_pagamento', data_pagamento = '$data_de_pagamento', status = '$status_pago'
                WHERE id_guia = $id";
                $query  = mysql_query($sql) OR DIE (mysql_error());
                if ($query)
                {
                    header("Location: gui_editar.php?item=gui_guias_detalhes&id=$id&tipo=gui_guias_detalhes&msg_status=editar_ok");
                    //echo 'ok';
                }
                
                
            }
    
            else
            {
                header("Location: gui_editar.php?item=gui_guias_detalhes&id=$id&tipo=gui_guias_detalhes&msg_status=editar_erro");
                //echo 'erro';
            }
        }

}elseif ($item == "gui_cancelar_guia")
{
       $id = (empty($_GET['id'])) ? "" : verifica($_GET['id']);
       $verifica = result("SELECT COUNT(*) FROM gui_guias WHERE id_guia = $id AND del = 'N'"); 
        if ($verifica == 0)
        {
            //não existe pasta com esse nome  
            header("Location: gui_editar.php?item=gui_guias_detalhes&id=$id&tipo=gui_guias_detalhes&msg_status=editar_erro");    
        } 
        else 
        {
            $sql    = "UPDATE gui_guias SET status = 'CANCELADO', del = 'S'
            WHERE id_guia = $id";
            $query  = mysql_query($sql) OR DIE (mysql_error());
            if ($query)
            {
                header("Location: gui_editar.php?item=gui_guias_detalhes&id=$id&tipo=gui_guias_detalhes&msg_status=editar_ok");  
            }
    
            else
            {
                header("Location: gui_editar.php?item=gui_guias_detalhes&id=$id&tipo=gui_guias_detalhes&msg_status=editar_erro");
                //echo 'erro';
            }
        }

}elseif ($item == "alterar_horario_agendamento")
{
       $verifica = result("SELECT COUNT(*) FROM gui_guias WHERE id_guia = $alterar_id_guia AND del = 'N'"); 
        if ($verifica == 0)
        {
            //não existe pasta com esse nome  
           echo 'erro'; 
        } 
        else 
        {
            $alterar_data_agendamento       = converte_data($alterar_data_agendamento);
            
            $sql    = "UPDATE gui_guias SET data_agendamento = '$alterar_data_agendamento', hora_agendamento = '$alterar_horario_agendamento'
            WHERE id_guia = $alterar_id_guia";
            $query  = mysql_query($sql) OR DIE (mysql_error());
            if ($query)
            {
                
                $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                $diasemana_numero = date('w', strtotime($alterar_data_agendamento));
                echo $diasemana[$diasemana_numero];
            }else
            {
                echo 'erro';
            }
        }

}elseif ($item == "alterar_profissional_guia")
{
       $verifica = result("SELECT COUNT(*) FROM gui_guias WHERE id_guia = $alterar_id_guia AND del = 'N'"); 
        if ($verifica == 0)
        {
            //não existe pasta com esse nome  
           echo 'erro'; 
        } 
        else 
        {
            //$alterar_data_agendamento       = converte_data($alterar_data_agendamento);
            
            $sql    = "UPDATE gui_guias SET id_profissional = '$id_profissional_sel'
            WHERE id_guia = $alterar_id_guia";
            $query  = mysql_query($sql) OR DIE (mysql_error());
            if ($query)
            {
                
                $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');
                $diasemana_numero = date('w', strtotime($alterar_data_agendamento));
                echo $diasemana[$diasemana_numero];
            }else
            {
                echo 'erro';
            }
        }

}elseif ($item == "confirmar_pagamento_guia")
{ 

        $agora 			= date("Y-m-d");
        if($valor_confirma == 'N'){
             $sql    = "UPDATE gui_pagamentos_guias SET baixa_recebimento = 'S', usuario_baixa = '$id_usuario', data_baixa = '$agora'
                WHERE id_guia = $id_pagamento";
        }else{
             $sql    = "UPDATE gui_pagamentos_guias SET baixa_recebimento = 'N', usuario_baixa = null, data_baixa = null
                WHERE id_guia = $id_pagamento";
        }
        
        $query  = mysql_query($sql);
        
        if ($query)
        {
            if($valor_confirma == 'N'){
                echo 'confirmado';
            }else{
                echo 'nao_confirmado';
            }            
        }
        else
        {
            echo 'erro';   
        }

    
}elseif ($item == "comentarios_atividades")
{ 
    $agora = agora();
    $id_usuario_s    = $usr_id;
    $sql    = "INSERT INTO comentarios_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
    VALUES ('$id_cliente', '$id_usuario_s', 'guias_pacientes', '$comentario', '$agora')";
    
    $query  = mysql_query($sql);
    
    if ($query)
    {
        
        $sql_user   = "SELECT us.nome'nome_usuario' FROM usuarios us
                    WHERE us.id_usuario = '$id_usuario_s'";
        $query_user = mysql_query($sql_user);
        $nome_usuario = '';                
        if (mysql_num_rows($query_user)>0)
        {
            $nome_usuario = mysql_result($query_user, 0);    
        }
        
        
        $agora_convertido = converte_data($agora);
        $vetor = array($nome_usuario, $id_usuario_s, $comentario, $agora_convertido);
        echo implode('%-%', $vetor);
    }
    else
    {
        $vetor = array(1, 2);
        echo implode('%-%', $vetor);
    }

    
}
?>