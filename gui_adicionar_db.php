<?php

/**
 * @project GED
 * @author Lucas Vinícius Leati
 * @created 01/06/2012
 */
 
require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
 
if (empty($_POST))
{
    header("Location: inicio.php");
}

$item = (empty($_POST['item'])) ? "" : verifica($_POST['item']);

//$servicos = (!empty($_POST['servicos'])) ? $_POST['servicos'] : "";
//$comissao = (!empty($_POST['comissao'])) ? $_POST['comissao'] : "";
//$valor = (!empty($_POST['valor'])) ? $_POST['valor'] : "";


if ($item == "gui_local_atendimento")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
        $nome_local = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
        //caso tenha logo
        /*$nome_arquivo = 'avatar.png';
        if (!empty($_FILES['logo']['name']))
        {
            $novo_nome_arquivo = preg_replace("/[^0-9]/","", $novo_nome_arquivo);
            $pasta = "assets/pages/img/logos/";
            $array = explode(".", $_FILES['logo']['name']);
            $extensao = (end($array));
            $nome_arquivo = $novo_nome_arquivo.".".$extensao;
            $arquivo = $pasta . $nome_arquivo ;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $arquivo))
            {
                include('inc/image.php');
                $image = new SimpleImage();
                $image->load($arquivo);
                $image->resizeToHeight(100, $arquivo);
                $image->save($arquivo);
            }

        }*/
       
            //adiciona registro
        $sql    = "INSERT INTO gui_local_atendimento (conveniado, nome, tipo, email, numero_cnes, telefone, telefone_alt, celular, cep, endereco, numero, complemento, bairro, cidade, estado, data_cadastro, obs, local_pagamento)
                        VALUES ('$conveniado', '$nome_local', '$tipo_local_atendimento', '$email', '$cnes', '$telefone_com', '$telefone_alt', '$celular', '$cep', '$endereco', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '".agora()."', '$observacoes_local', '$ver_local_pagamento')";

        $query  = mysql_query($sql);

        if ($query)
        {
            $id_local = mysql_insert_id();
            if(isset($lista_cidades_local))
            {
                $nr_linhas = count($lista_cidades_local);
                for ($i = 0; $i<$nr_linhas; $i++)
                {
                    $id_cidade = $lista_cidades_local[$i];  
                    
                    $sql3    = "INSERT INTO gui_cidades_locais (loc_nu_sequencial, id_local_atendimento)
                                VALUES ('$id_cidade', '$id_local')";
                    
                    $query3  = mysql_query($sql3) or die(mysql_error());
                }
            }
            else
            {
                //echo 'nao set add_produto';
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
                        $valor_custo_array = $valor_custo[$contar_convenio];
                        $valor_final_array = $valor_final[$contar_convenio];
                        //adiciona registro
                        $sql3    = "INSERT INTO gui_local_atendimento_procedimentos (id_procedimento, id_local_atendimento, id_convenio, preco_custo, preco_venda)
                                    VALUES ('$id_procedimento', '$id_local', '$id_convenio', '$valor_custo_array', '$valor_final_array')";
                        
                        $query3  = mysql_query($sql3) or die(mysql_error());
                       
                        $contar_convenio = $contar_convenio + 1;
                        
                    }
                    
                }
            }
            else
            {
                //echo 'nao set add_produto';
            }
            
            
            header("Location: gui_listar.php?item=$item&msg_status=adicionar_ok");
        }
        else
        {
            header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
                
    
}elseif ($item == "gui_convenios")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
        $nome_local = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
       
            //adiciona registro
        $sql    = "INSERT INTO gui_convenios (nome, data_cadastro, ativo)
        VALUES ('$nome_local', '".agora()."', 'S')";

        $query  = mysql_query($sql);

        if ($query)
        {
            header("Location: gui_listar.php?item=$item&msg_status=adicionar_ok");
        }
        else
        {
            header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
                
    
    
}elseif ($item == "gui_grupo_procedimentos")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
        $nome_local = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
       
        //adiciona registro
        $sql    = "INSERT INTO gui_grupo_procedimentos (nome, ativo)
        VALUES ('$nome_local', 'S')";

        $query  = mysql_query($sql);

        if ($query)
        {
            header("Location: gui_listar.php?item=$item&msg_status=adicionar_ok");
        }
        else
        {
            header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
                
    
    
}elseif ($item == "gui_procedimentos")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
        $nome_local = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
        $valor_custo = moeda_db($valor_custo);
        $valor_final = moeda_db($valor_final);
       
        //adiciona registro
        $sql    = "INSERT INTO gui_procedimentos (id_grupo_procedimento, codigo, nome, obs, data_cadastro, ativo)
        VALUES ('$grupo_procedimento', '$codigo_procedimento', '$nome_local', '$observacoes_procedimentos', '".agora()."', 'S')";

        $query  = mysql_query($sql);

        if ($query)
        {
            if(isset($bt_salvar_mais) AND $bt_salvar_mais == '1'){
                header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_ok");
            }else{
                header("Location: gui_listar.php?item=$item&msg_status=adicionar_ok");
            }
        }
        else
        {
            header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
                
    
    
}elseif ($item == "gui_profissionais")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
        $nome_local = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

        $contar_convenios = count($lista_convenios);
        $lista_convenios_array = '';
        if($contar_convenios > 0){
            $lista_convenios_array = implode("|", $lista_convenios);
        }

        $dt_nasc_profissional = converte_data($dt_nasc_profissional);
        $verifica = result("SELECT COUNT(*) FROM gui_profissionais WHERE conselho = '$conselho' AND registro = '$registro'"); 
    
        if ($verifica>0)
        {
            //já existe pasta com esse nome  
            header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro_existe");    
        } 
        else 
        {
            //adiciona registro
            $sql    = "INSERT INTO gui_profissionais (nome, id_profissao, id_especialidade, ids_convenios, conveniado, data_nascimento, telefone, email, celular, tratamento, conselho, registro, data_cadastro, ativo)
            VALUES ('$nome_local', '$profissao', '$select_especialidade', '$lista_convenios_array', '$conveniado', '$dt_nasc_profissional', '$telefone_com', '$email', '$celular', '$tratamento_profissional', '$conselho', '$registro', '".agora()."', '$ativo')";
    echo $sql;
            $query  = mysql_query($sql);
    
            if ($query)
            {
                
                $id_profissional_insert = mysql_insert_id();
                if(isset($lista_local_atendimento))
                {
                    $nr_linhas = count($lista_local_atendimento);
                    for ($i = 0; $i<$nr_linhas; $i++)
                    {
                        $id_local_tendimento_sel = $lista_local_atendimento[$i];  
                        
                        $sql3    = "INSERT INTO gui_local_atendimento_profissional (id_profissional, id_local_atendimento)
                                    VALUES ('$id_profissional_insert', '$id_local_tendimento_sel')";
                        
                        $query3  = mysql_query($sql3) or die(mysql_error());
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
                                        VALUES ('$id_select_especialidade_sel', '$id_profissional_insert', '$rqe_sel')";
                            $query3  = mysql_query($sql3) or die(mysql_error());
                        }
                    }
                }
                
                if(isset($bt_salvar_mais) AND $bt_salvar_mais == '1'){
                    header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_ok");
                }else{
                    header("Location: gui_listar.php?item=$item&msg_status=adicionar_ok");
                }
            }
            else
            {
                //header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro"); 
            }
        }        
    
}elseif ($item == "gui_pacientes")
{
    $usr_parceiro   = base64_decode($_COOKIE["usr_parceiro"]);
    $usr_id         = base64_decode($_COOKIE["usr_id"]);
    $nivel_usuario  = base64_decode($_COOKIE["usr_nivel"]);
    $nivel_status   = base64_decode($_COOKIE["nivel_status"]);
    
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
        
    $nome_local = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    
    $data_nasc = converte_data($data_nasc);
    $cpf_paciente = limpa_caracteres($cpf_paciente);
    $cep_paciente = limpa_caracteres($cep_paciente);
    
    $verifica = result("SELECT COUNT(*) FROM gui_pacientes WHERE nome = '$nome_local' AND data_nascimento = '$data_nasc' AND sexo = '$sexo'"); 
    
    if ($verifica>0)
    {
        //já existe pasta com esse nome  
        header("Location: gui_adicionar.php?item=$item&msg_status=cliente_existe");    
    } 
    else 
    {
        //adiciona registro
        $sql    = "INSERT INTO gui_pacientes (id_cliente, id_parceiro, id_usuario, id_convenio, nome, cpf, data_nascimento, sexo, email, telefone, celular, comercial, cep, endereco, numero, complemento, bairro, cidade, estado, data_cadastro, dados_completo)
        VALUES ('$id_cliente', '$usr_parceiro', '$usr_id', '$convenio_paciente', '$nome_local', '$cpf_paciente', '$data_nasc', '$sexo', '$email', '$telefone', '$celular', '$comercial', '$cep_paciente', '$endereco_paciente', '$numero_paciente', '$complemento_paciente', '$bairro_paciente', '$cidade_paciente', '$estado_paciente', '".agora()."', 'S')";
    
        $query  = mysql_query($sql);
    
        if ($query)
        {
            if(isset($bt_salvar_mais) AND $bt_salvar_mais == '1'){
                header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_ok");
            }else{
                header("Location: gui_listar.php?item=$item&msg_status=adicionar_ok");
            }
        }
        else
        {
            //header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
    }         
    
    
}elseif ($item == "gui_guias")
{
    
    $usr_parceiro   = base64_decode($_COOKIE["usr_parceiro"]);
    $usr_id         = base64_decode($_COOKIE["usr_id"]);
    $nivel_usuario  = base64_decode($_COOKIE["usr_nivel"]);
    $nivel_status   = base64_decode($_COOKIE["nivel_status"]);
    
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
        
    $nome_local = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    
    $data_agendamento = converte_data($data_agendamento);
    $cpf_paciente = limpa_caracteres($cpf_paciente);
    $cep_paciente = limpa_caracteres($cep_paciente);
    $soma_procedimentos = array_sum($add_preco_venda);
    
    if(isset($select_parceiro_user) AND !empty($select_parceiro_user)){
        $usr_parceiro = $select_parceiro_user;
    }
    
    if(empty($nome_profissional) OR empty($registro_profissional)){
        $id_profissional_sel = 176;
    }
    
        //adiciona registro
        $sql    = "INSERT INTO gui_guias (id_parceiro, id_usuario, id_convenio, id_local_atendimento, id_profissional, id_paciente, data_cadastro, data_agendamento, hora_agendamento, obs_guia)
        VALUES ('$usr_parceiro', '$usr_id', '$verifica_grupo_convenio', '$sel_id_local_atendimento', '$id_profissional_sel', '$id_paciente', '".agora()."', '$data_agendamento', '$horario_agendamento', '$tipo_convenio_procedimento_paciente')";
    
        $query  = mysql_query($sql);
        //echo $sql;
        if ($query)
        {
            
            $id_gui_guia = mysql_insert_id();
            if(isset($add_procedimento))
            {
                $nr_linhas = count($add_procedimento);
                for ($i = 0; $i<$nr_linhas; $i++)
                {
                    $id_procedimento_sel = $add_procedimento[$i];  
                    
                    $sql3    = "INSERT INTO gui_procedimentos_guia (id_guia, id_procedimento, valor_cobrado, id_convenio, data_cadastro)
                                VALUES ('$id_gui_guia', '$id_procedimento_sel', '$add_preco_venda[$i]', '$add_id_convenio[$i]', '".agora()."')";
                    
                    $query3  = mysql_query($sql3) or die(mysql_error());
                }
            }
            $desconto_db = '';
            $valor_total_desconto = '';
            if($desconto > 0){
                
                $desconto_db = str_replace(",", ".", $desconto);
                
                 if($tipo_desconto == "por"){
                    $valor_desconto = ($soma_procedimentos*$desconto_db)/100;
                 }else{
                    $valor_desconto = $desconto_db;
                 }
                 
                 $valor_total_desconto = $soma_procedimentos-$valor_desconto;
                
            }
            
            
           
            
            $sql    = "INSERT INTO gui_pagamentos_guias (id_guia, valor_total, desconto, tipo_desconto, valor_total_desconto, obs_pagamento)
            VALUES ('$id_gui_guia', '$soma_procedimentos', '$desconto_db', '$tipo_desconto', '$valor_total_desconto', '$info_obs_pagamento')";
            $query  = mysql_query($sql);
            $id_pagamento_guia = mysql_insert_id();
            
            
            $sql2    = "UPDATE gui_guias SET id_pagamento = '$id_pagamento_guia'
            WHERE id_guia = $id_gui_guia";
            $query2  = mysql_query($sql2) OR DIE (mysql_error());

            if(isset($bt_salvar_mais) AND $bt_salvar_mais == '1'){
                header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_ok");
            }else{
                header("Location: gui_editar.php?item=gui_guias_detalhes&id=$id_gui_guia&msg_status=adicionar_ok&tipo=gui_guias_detalhes");
            }
        }
        else
        {
            //header("Location: gui_adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }       
    
    
}

?>