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
        $item = (empty($_GET['item'])) ? "" : $_GET['item'];
    }
    
        
}
else
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
    $valor = (!empty($_POST['valor'])) ? $_POST['valor'] : "";
}
$id_usuario_s    = base64_decode($_COOKIE["usr_id"]);

if ($item == "parceiros")
{
       
    $verifica = result("SELECT COUNT(*) FROM parceiros WHERE email LIKE '$email' AND id_cliente <> $id");
    //$cnpj = $_POST['cnpj'];
            
    if ($verifica>0)
    {
        //já existe parceiro com eesse nome
        header("Location: editar.php?item=$item&id=$id&msg_status=email_existe");
    } 
    else 
    {
        $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

        
        
        $sql_img1 = '';
        $sql_img2 = '';
        $novo_nome_arquivo = ($tipopessoa == "PF") ? createToken('0', '3') : createToken('1', '2');
        
        include('inc/image.php');
        $novo_nome_arquivo_rep = preg_replace("/[^0-9]/","", $novo_nome_arquivo);
        //caso tenha logo
        if (!empty($_FILES['logo1']['name']))
        {
            $pasta = "assets/pages/img/logos/";
            $array = explode(".", $_FILES['logo1']['name']);
            //$nome  = normaliza($array[0]);
            $extensao = (end($array));
            $nome_arquivo = $novo_nome_arquivo_rep.".".$extensao;
            $arquivo = $pasta . $nome_arquivo ;
            $sql_img1 = ", logo = '$nome_arquivo'";
            if (move_uploaded_file($_FILES['logo1']['tmp_name'], $arquivo))
            {
                /*$sql_file   = "UPDATE parceiros SET logo = '$nome_arquivo' WHERE id_parceiro = $id";
                $query_file = mysql_query($sql_file);*/

                $image = new SimpleImage();
                $image->load($arquivo);
                $image->resizeToHeight(100, $arquivo);
                $image->save($arquivo);

                //echo "redimensionou";
            }

        }
        
        //caso tenha logo
        if (!empty($_FILES['logo2']['name']))
        {
            $pasta = "assets/pages/img/logos/";
            $array = explode(".", $_FILES['logo2']['name']);
            //$nome  = normaliza($array[0]);
            $extensao = (end($array));
            $nome_arquivo2 = $novo_nome_arquivo_rep."2.".$extensao;
            $arquivo = $pasta . $nome_arquivo2;
            $sql_img2 = ", logo_proposta = '$nome_arquivo2'";
            if (move_uploaded_file($_FILES['logo2']['tmp_name'], $arquivo))
            {
                /*$sql_file   = "UPDATE parceiros SET logo = '$nome_arquivo' WHERE id_parceiro = $id";
                $query_file = mysql_query($sql_file);*/
                $image = new SimpleImage();
                $image->load($arquivo);
                $image->resizeToHeight(100, $arquivo);
                $image->save($arquivo);

                //echo "redimensionou";
            }

        }
        if(empty($valor_plano)){
            $valor_plano = 0;
        }
        $valor_plano = moeda_db($valor_plano);
        
        if(empty($valor_desconto_renova)){
            $valor_desconto_renova = 0;
        }
        $valor_desconto_renova = moeda_db($valor_desconto_renova);
        
        $metodo_pagamento_array = implode("|", $metodo_pagamento);
        $tipo_pagamento_array = implode("|", $tipo_pagamento);
        $parcelas_boleto_array = implode("|", $parcelas_boleto);
        $parcelas_cartao_array = implode("|", $parcelas_cartao);
        $emissao_boleto_array = implode("|", $emissao_boleto);
        if($tipopessoa == "PF")
        {
            $sql    = "UPDATE parceiros SET nome = '$nome', cep = '$cep', endereco = '$endereco', numero = '$numero',
                        complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', 
                        tel_res = '$tel_res', tel_com = '$tel_com', tel_cel = '$tel_cel', email = '$email', 
                        ramo_atividade = '$ramo_atividade', modalidade = '$modalidade', dt_modificacao = '".agora()."', metodo_pagamento = '$metodo_pagamento_array', tipo_pagamento = '$tipo_pagamento_array', parcelas_boleto = '$parcelas_boleto_array', valor_plano_adicional = '$valor_plano', desconto = '$desconto', emissao_boleto = '$emissao_boleto_array',  parcelas_cartao = '$parcelas_cartao_array', valor_entrada_automatica = '$valor_entrada_automatica', entrada = '$entrada', porcentagem_entrada = '$porcentagem_entrada', id_cidade = '$id_cidade', valor_desconto_renovacao = '$valor_desconto_renova', valor_entrada_auto = '$valor_entrada_auto', obs_proposta = '$obs_proposta', del = 'N'$sql_img1 $sql_img2 
                        WHERE id_parceiro = $id";
            $novo_nome_arquivo = $cpf;
        }
        else
        {
            $sql    = "UPDATE parceiros SET nome = '$nome', cep = '$cep', endereco = '$endereco', numero = '$numero',
                        complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', 
                        tel_com = '$tel_com', email = '$email', 
                        ramo_atividade = '$ramo_atividade', modalidade = '$modalidade', dt_modificacao = '".agora()."', metodo_pagamento = '$metodo_pagamento_array', tipo_pagamento = '$tipo_pagamento_array', del = 'N', parcelas_boleto = '$parcelas_boleto_array', valor_plano_adicional = '$valor_plano', desconto = '$desconto', emissao_boleto = '$emissao_boleto_array', parcelas_cartao = '$parcelas_cartao_array', valor_entrada_automatica = '$valor_entrada_automatica', entrada = '$entrada', porcentagem_entrada = '$porcentagem_entrada', id_cidade = '$id_cidade', valor_desconto_renovacao = '$valor_desconto_renova', valor_entrada_auto = '$valor_entrada_auto', obs_proposta = '$obs_proposta', nome_fantasia = '$fantasia'$sql_img1 $sql_img2 WHERE id_parceiro = $id";
            $novo_nome_arquivo = $cnpj;
        }

        $query  = mysql_query($sql) OR DIE (mysql_error());


        if ($query)
        {
            
            // filiais franquias
            /*if(isset($nome_filial))
            {
                
                $nr_colunas = count($nome_filial);
                for ($i = 0; $i<$nr_colunas; $i++)
                {
                    
                    $sql3    = "INSERT INTO filiais (id_parceiro, tipo, nome, cnpj, cidade, estado, tel_com)
                            VALUES ('$id', 'PJ', '$nome_filial[$i]', '$cnpj_filial[$i]', '$cidade_filial[$i]', '$estado_filial[$i]', '$fone_filial[$i]')";
                
                    $query3  = mysql_query($sql3) or die(mysql_error());

                }
            }*/
            
            /*if(isset($remove_filial))
            {
                //$id_parceiro = mysql_insert_id();
                $nr_colunas = count($remove_filial);
                for ($i = 0; $i<$nr_colunas; $i++)
                {
                    $id_filial = $remove_filial[$i];  
                    
                    $sql_4    = "UPDATE filiais SET del = 'S', dt_del = '".agora()."'
                                WHERE id_parceiro = $id AND id_filial = $id_filial";
                    $query_4  = mysql_query($sql_4) OR DIE (mysql_error());

                }
            }*/
            
            
            // produtos
            if(isset($add_produto))
            {
                //$preco_custo = moeda_db($preco_custo);
                //$preco_venda = moeda_db($preco_venda);

                $nr_colunas = count($add_produto);
                for ($i = 0; $i<$nr_colunas; $i++)
                {
                    $id_produto = $add_produto[$i];  
                    $id_parceiro_servico = $add_parceiro_servico[$i]; 
                    //$comissao_atual = $comissao[$i];   
                    //$valor_atual = $valor[$i];   
                    
                    //$preco_custo = moeda_db($preco_custo);
                    //$preco_venda = moeda_db($preco_venda);    
                    //$valor_atual = moeda_db($valor_atual);
                     //adiciona registro
                    $verifica = result("SELECT COUNT(*) FROM parceiros_servicos WHERE id_parceiro = $id AND id_produto = $id_produto AND id_parceiro_servico = $id_parceiro_servico");
                    //$cnpj = $_POST['cnpj'];
                            
                    if ($verifica>0)
                    {
                        
                        $sql2    = "UPDATE parceiros_servicos SET preco_custo = '$preco_custo[$i]', preco_venda = '$preco_venda[$i]', id_grupo_produto = '$grupo_produto[$i]' WHERE id_parceiro = $id AND id_produto = $id_produto AND id_parceiro_servico = $id_parceiro_servico";
                        $query2  = mysql_query($sql2) OR DIE (mysql_error());
                        
                    }
                    else
                    {
                        $sql3    = "INSERT INTO parceiros_servicos (id_produto, id_parceiro, preco_custo, preco_venda, id_grupo_produto)
                                VALUES ('$id_produto', '$id', '$preco_custo[$i]', '$preco_venda[$i]', '$grupo_produto[$i]')";
                    
                        $query3  = mysql_query($sql3) or die(mysql_error());
                    }
  
                }
            }
            
            if(isset($remove_produto))
            {
                //$id_parceiro = mysql_insert_id();
                $nr_colunas = count($remove_produto);
                for ($i = 0; $i<$nr_colunas; $i++)
                {
                    $id_parceiro_servico = $remove_produto[$i];  
                    $sql_4    = "DELETE FROM parceiros_servicos WHERE id_parceiro = $id AND id_parceiro_servico = $id_parceiro_servico";
                    
                    $query_4  = mysql_query($sql_4);

                }
            }
            
            $sql5    = "UPDATE parceiros_grupos SET id_grupo_parceiro = '$grupo' WHERE id_parceiro = $id";
            $query5  = mysql_query($sql5) OR DIE (mysql_error());
            
            header("Location: listar.php?item=$item&id=$id&msg_status=editar_ok");
            //echo 'ok';
        }

        else
        {
            header("Location: listar.php?item=$item&id=$id&msg_status=editar_erro");
            //echo 'erro';
        }
                
    }
    
}
elseif ($item == "cliente")
{
    
    $banco_painel = $link;
    $produto_grupo_slug         = explode("|", $slug_produto);
    $contar_produto_grupo_slug  = count($produto_grupo_slug) - 1;
    $id_parceiro_post = $id_parceiro;
    $id_usuario_post = $id_usuario;
    $verifica_soma_total_geral = false;
    $soma_produto_atual = $soma_produto;
    if(isset($total_geral_assistencia) AND !empty($total_geral_assistencia) AND $total_geral_assistencia > 0 AND ($forma_pagamento == 'parcelado_cartao' OR $forma_pagamento == 'avista')){
        //$soma_produto = $total_geral_assistencia;
        $verifica_soma_total_geral = true;
    }
    
    if(!isset($parcela_parcelas_boleto)){
        $parcela_parcelas_boleto = 0;
        
        if(isset($desconto) AND !empty($desconto) AND $desconto > 0){
            $soma_produto = $soma_produto_com_desconto;
        }
        
    }else{
        if($tipo_calculo_entrada == 'auto'){
                $parcela_parcelas_boleto = $parcela_parcelas_boleto - 1;
            }
        $soma_produto = $valor_parcela_boleto;
    }
    
    if($input_status_entrada == 'N'){
        if($manter_vigencia == 'ok'){
            $valor_entrada = $valor_entrada_atual;
        }else{
            $valor_entrada = 0;
        }
    }

    for ($pg = 0; $pg<=$contar_produto_grupo_slug; $pg++)
    {
        $array_id_venda_ordem_pedido = null;
        $array_id_venda_ordem_pedido = array();
        //$array_produto_id_venda_ordem_pedido = array();
        //$produto_grupo_slug[$pg]
        // seleciona o id do produto pelo id do grupo
        $sql_base   = "SELECT bpro.id_base_produto, gpro.nome FROM produtos_grupos prog
        JOIN produtos pro ON prog.id_produto = pro.id_produto
        JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
        JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
        WHERE prog.id_grupo_produto = $id_grupo_produto
        GROUP BY bpro.id_base_produto";
        $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
        
        if (mysql_num_rows($query_base)>0)
        {
            $id_base_produto            = mysql_result($query_base, $pg, 'id_base_produto');
            $nome_grupo_produto         = mysql_result($query_base, 0, 'nome');
            $sql_base_select = "WHERE bpro.id_base_produto = $id_base_produto";
            
            $array_id_base_produto[]    = $id_base_produto;
            
        }
        
        if($select_produto == '' OR $select_produto == 'todos')
        {
            $produto = '';
        
            $tipo_apolice = 'GRU';
        }
        else
        {
            $sql_base_select = "WHERE pro.id_produto = $select_produto";
            $tipo_apolice = 'IND';
        }
       
        
         
        // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
        $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                    JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                    $sql_base_select";
    
        $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 2");
        
        if (mysql_num_rows($query_base)>0)
        {
            $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
            $banco_user             = mysql_result($query_base, 0, 'banco_user');
            $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
            $banco_host             = mysql_result($query_base, 0, 'banco_host');
            $slug                   = mysql_result($query_base, 0, 'slug');
            $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
            
            $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
            $array_slug_base_produto[]  = $slug;
        }
        $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);    
        
        //$slug_produto = $slug_produto[$p];
        if($produto_grupo_slug[$pg] == 'europ')
        {
            $cliente_ok = 0;
            $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
            $nome = remove_acento($nome);
                
            $endereco = strtr(strtoupper($endereco),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
            $endereco = remove_acento($endereco);
            
            $complemento = strtr(strtoupper($complemento),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
            $complemento = remove_acento($complemento);
            
            $bairro = strtr(strtoupper($bairro),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
            $bairro = remove_acento($bairro);
            
            $cidade = strtr(strtoupper($cidade),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
            $cidade = remove_acento($cidade);
            
            
            $idade = calcula_idade($data_nasc);
            
            if($idade > 120){
                //já existe pasta com esse nome      
                header("Location: adicionar.php?item=$item&msg_status=adicionar_erro_idade");
                ob_end_flush();
            }
            else
            {
                if($msg_status == 'ativar_venda'){
                    $agora = converte_data($imput_dt_inicio_vigencia);
                }else{
                    $agora = date('Y-m-d');
                }

                $cep = limpa_caracteres($cep);
                $cpf = limpa_caracteres($cpf);

                $data_termino = ''; 
                if($prazo > 0)
                {                        
                    $data_termino = date('d/m/Y', strtotime('+'.$prazo.' month', strtotime($agora)));
                    //$data = somar_datas( $prazo, 'm'); // adiciona 3 meses a sua data
                    //$data_termino = date('d/m/Y', strtotime($data));
                    $data_termino = converte_data($data_termino);
                }  
                  
                $data_nasc = converte_data($data_nasc);
                
                $contar_linha = 0;
                $ordem_pedido_metodo_pagamento_correcao = '';
                $sql_ordem_correcao   = "SELECT metodo_pagamento FROM ordem_pedidos
                WHERE id_ordem_pedido = $verifica_id_ordem_pedido_principal";

                $query_ordem_correcao = mysql_query($sql_ordem_correcao, $banco_painel) or die(mysql_error()." - correcao");
                
                if (mysql_num_rows($query_ordem_correcao)>0)
                {
                    $ordem_pedido_metodo_pagamento_correcao       = mysql_result($query_ordem_correcao, 0, 'metodo_pagamento');
                }   
                if($msg_status == 'finalizar_venda' OR $msg_status == 'finalizar_cadastro' OR $msg_status == 'ativar_venda'){
                $sql_europ       = "SELECT COUNT(*) FROM clientes 
                                                WHERE cpf = '$cpf' AND tipo_movimento IN ('IN', 'AL') AND id_produto = $id_grupo_produto AND (data_termino >= '$agora' OR data_termino = '0000-00-00') AND id_cliente <> $id_cliente AND chave <> '$chave_cliente' AND (status = 99 OR status = 0)";
                $query_europ     = mysql_query($sql_europ, $banco_produto) or die(mysql_error()." - 12");
                $contar_linha = mysql_result($query_europ, 0, 0);
                }
                //echo $sql_europ;
                if($contar_linha == 0)
                {
                    
                    $sql_tipo_status   = "SELECT data_inicio, data_cancelamento, status FROM clientes
                                            WHERE chave = '$chave_cliente'";
                                                    
                    $query_tipo_status = mysql_query($sql_tipo_status, $banco_produto);
                    $verifica_tipo_status = '';               
                    if (mysql_num_rows($query_tipo_status)>0)
                    {
                        $verifica_data_inicio       = mysql_result($query_tipo_status, 0,'data_inicio');
                        $verifica_data_cancelamento = mysql_result($query_tipo_status, 0,'data_cancelamento');
                        $verifica_tipo_status       = mysql_result($query_tipo_status, 0,'status');
                        
                        $sql_adicionais_se              = "AND tipo_movimento IN ('IN', 'AL')";
                        $sql_id_vendas_ordem_pedido_se  = "AND c.tipo_movimento IN ('IN', 'AL')";
                        if($ativar_plano_cancelado == 'ok'){
                            $data_cancelamento_cliente_get = $verifica_data_cancelamento;
                            $sql_adicionais_se = "AND data_cancelamento = '$data_cancelamento_cliente_get'";
                            $sql_id_vendas_ordem_pedido_se = "AND c.data_cancelamento = '$data_cancelamento_cliente_get'";
                        }
                    }
                    
                    
                    
                    $msg_hist_mander_vigencia = '';
                    $sql_prazo_verificar = "prazo = '$prazo',";
                    if($manter_vigencia == 'ok'){
                        $msg_hist_mander_vigencia = 'Manter vigencia: sim';
                        $data_termino = $data_termino_atual;
                        $agora = $verifica_data_inicio;
                        
                    }
                    
                    if(isset($excluir_id_dependente ) AND !empty($excluir_id_dependente)){
                        
                        $excluir_id_dependente_contar = count($excluir_id_dependente) - 1;
                        for($i = 0; $i<=$excluir_id_dependente_contar; $i++)
                        {
                            $id_dependente = $excluir_id_dependente[$i];
                            
                            
                            $sql_id_dep        = "SELECT chave, status FROM clientes
                            WHERE id_cliente = $id_dependente";
                            $query_id_dep      = mysql_query($sql_id_dep, $banco_produto);
                            
                            $chave             = mysql_result($query_id_dep, 0, 'chave');       
                            $status_dep        = mysql_result($query_id_dep, 0, 'status');   
                            
                            
                            $novo_status = $status_dep;
                            if($status_dep == '99'){
                                $novo_status = 0;
                            }elseif($status_dep == '3' OR $status_dep == '4' OR $status_dep == '6'){
                                $novo_status = 99;
                            }else{
                                $novo_status = $novo_status;
                            }
                            
                            $sql_up_dep    = "UPDATE clientes SET tipo_movimento = 'EX', data_cancelamento = '".agora()."', status = '$novo_status'
                            WHERE chave = '$chave'";
                            $query_up_dep  = mysql_query($sql_up_dep, $banco_produto);
                            
                        }
                        
                    }
                    
                    if(isset($principal_excluir_id_dependente ) AND !empty($principal_excluir_id_dependente)){
                        
                        $principal_excluir_id_dependente_contar = count($principal_excluir_id_dependente) - 1;
                        for($i = 0; $i<=$principal_excluir_id_dependente_contar; $i++)
                        {
                            $id_dependente = $principal_excluir_id_dependente[$i];
                            
                            
                            $sql_up_dep    = "UPDATE dependentes_clientes SET status = 1
                            WHERE id_dependente = '$id_dependente'";
                            $query_up_dep  = mysql_query($sql_up_dep, $banco_produto);

                        }
                        
                    }
                    
                    $sql_id_venda_vadd        = "SELECT id_venda'id_venda_cliente_vadd' FROM vendas v
                    JOIN clientes c ON v.id_cliente = c.id_cliente
                    WHERE v.id_ordem_pedido = '$verifica_id_ordem_pedido_principal' $sql_id_vendas_ordem_pedido_se";
                    $query_id_venda_vadd      = mysql_query($sql_id_venda_vadd, $banco_produto);
                    if (mysql_num_rows($query_id_venda_vadd)>0)
                    {    
                        while($dados_id_venda_vadd = mysql_fetch_array($query_id_venda_vadd))
                        {
                            extract($dados_id_venda_vadd); 
                            $array_id_venda_ordem_pedido[] = $id_venda_cliente_vadd;
                        }
                    }
                    
                    
                    $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
                    VALUES ('$id_cliente', '$id_usuario_s', 'clientes', 'Alteracao = $msg_status $msg_hist_mander_vigencia', '".agora()."')";   
                    $query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");

                    if($msg_status == 'finalizar_venda' OR $msg_status == 'ativar_venda'){
                    
                    if($msg_status == 'ativar_venda' OR $manter_vigencia == 'ok'){
                        $status_remessa = 0;
                        $status_pedido = "Pago";
                    }else{
                        $status_remessa = 6;
                        $status_pedido = "Nao_Finalizado";
                    }    
                    
                    
                    if($metodo_pagamento == 'ON')
                    {
                        $status_remessa = 4;
                        $status_pedido = "Nao_Finalizado";
                    }
                    
                    if(!isset($comprovante_maquina))
                    {
                        $comprovante_maquina = '';
                    }
                    
                    if($msg_status == 'ativar_venda'){

                        $sql_c_europ    = "UPDATE clientes SET nome = '$nome', cpf = '$cpf', data_nascimento = '$data_nasc', sexo = '$sexo', estado_civil = '$estado_civil', email = '$email', telefone ='$telefone', celular = '$celular', cep = '$cep', endereco = '$endereco', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', prazo = '$prazo', status = '$status_remessa'
                        WHERE chave = '$chave_cliente'";
                        
                    }else{
                        $sql_status_cliente_principal_verificar = '';
                        if($msg_status == 'finalizar_venda' AND $verifica_tipo_status == '99'){
                            $sql_status_cliente_principal_verificar = "tipo_movimento = 'AL',";
                        }
                        
                        if($msg_status == 'finalizar_venda' AND $verifica_tipo_status == '6'){
                            $status_remessa = 6;
                            $status_pedido = "Nao_Finalizado";
                        }
                    
                        $sql_c_europ    = "UPDATE clientes SET $sql_status_cliente_principal_verificar nome = '$nome', cpf = '$cpf', data_inicio = '$agora', data_termino = '$data_termino', data_nascimento = '$data_nasc', sexo = '$sexo', estado_civil = '$estado_civil', email = '$email', telefone ='$telefone', celular = '$celular', cep = '$cep', endereco = '$endereco', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', $sql_prazo_verificar status = '$status_remessa'
                        WHERE chave = '$chave_cliente' ";
                        
                    }    
                        
                             
                        $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 1415");
        
                        if($query_c_europ)
                        { 
                            $sql_principal        = "SELECT id_cliente, id_filial, nome, cpf FROM clientes
                                                    WHERE chave = '$chave_cliente'";
                                                    
                            $query_principal      = mysql_query($sql_principal, $banco_produto);
                                           
                            if (mysql_num_rows($query_principal)>0)
                            {
                                $array_id_cliente_principal = array();
                                
                                while($dados_principal = mysql_fetch_array($query_principal))
                                {
                                    extract($dados_principal);
                                    
                                    if($msg_status == 'finalizar_venda'){
                                        $sql_c_europ    = "UPDATE vendas SET tipo_pagamento = '$forma_pagamento', desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$soma_produto', valor_parcela_total = '$soma_produto', valor_total = '$total_geral_assistencia', parcelas = '$parcela_parcelas_boleto', prazo = '$prazo', metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido'
                                            WHERE id_cliente = $id_cliente";
                                    
                                    }else{
                                        $sql_c_europ    = "UPDATE vendas SET status_pedido = '$status_pedido'
                                        WHERE id_cliente = $id_cliente";
                                    }
                                    $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                                    
                                   
                                    
                                    $array_id_cliente_principal[] = $id_cliente;
                                    
                                    
                                    
                                    $sql_depen        = "SELECT id_cliente'id_cliente_dependente', status'status_dependente' FROM clientes
                                                    WHERE id_cliente_principal = '$id_cliente' $sql_adicionais_se";
                                    $query_depen      = mysql_query($sql_depen, $banco_produto);
                                                    
                                    if (mysql_num_rows($query_depen)>0)
                                    {
                                        //$id_cliente_dependente = mysql_result($query_depen, 0, 'id_cliente');
                                        while($dados_up_depen = mysql_fetch_array($query_depen))
                                        {
                                            extract($dados_up_depen);
                                            $status_dependente_antigo = '';
                                            $status_pedido_dependente = "Nao_Finalizado";
                                            if($msg_status == 'ativar_venda' OR $manter_vigencia == 'ok'){
                                                if($status_dependente == 6){
                                                    $status_dependente = 0;
                                                    $status_pedido_dependente = "Pago";
                                                }
                                                
                                                if($status_dependente == 4){
                                                    $status_dependente = 6;
                                                    $status_pedido_dependente = "Nao_Finalizado";
                                                }
                                                
                                                if($status_dependente == 99){
                                                    $status_dependente_antigo = 99;
                                                    $status_dependente = 0;
                                                    $status_pedido_dependente = "Nao_Finalizado";
                                                }
                                                
                                            }else{
                                                
                                                if($status_dependente == 6){
                                                    $status_dependente = 6;
                                                    $status_pedido_dependente = "Nao_Finalizado";
                                                }
                                                
                                                if($status_dependente == 4){
                                                    $status_dependente = 6;
                                                    $status_pedido_dependente = "Nao_Finalizado";
                                                }
                                                
                                                if($status_dependente == 99){
                                                    $status_dependente_antigo = 99;
                                                    $status_dependente = 6;
                                                    $status_pedido_dependente = "Nao_Finalizado";
                                                }
                                                
                                            } 

                                            if($msg_status == 'finalizar_venda'){
                                                $sql_c_europ    = "UPDATE vendas SET tipo_pagamento = '$forma_pagamento', desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$soma_produto', valor_parcela_total = '$soma_produto', valor_total = '$total_geral_assistencia', parcelas = '$parcela_parcelas_boleto', $sql_prazo_verificar metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido_dependente', tipo_desconto = '$tipo_desconto'
                                                WHERE id_cliente = $id_cliente_dependente";
                                                
                                                /*$sql_id_venda_vadd        = "SELECT id_venda FROM vendas
                                                            WHERE id_cliente = $id_cliente_dependente";
                                                $query_id_venda_vadd      = mysql_query($sql_id_venda_vadd, $banco_produto);
                                                    
                                                $id_venda_cliente_vadd = mysql_result($query_id_venda_vadd, 0, 'id_venda');   
                                                 
                                                $array_id_venda_ordem_pedido[] = $id_venda_cliente_vadd;*/

                                            }else{
                                                    
                                                 $sql_c_europ    = "UPDATE vendas SET status_pedido = '$status_pedido_dependente'
                                                WHERE id_cliente = $id_cliente_dependente";
                                                
                                            }
                                            $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                                            
                                            
                                            if($msg_status == 'ativar_venda' AND $status_renovacao == 'S'){
                                                $sql_c_europ_dep    = "UPDATE clientes SET prazo = '$prazo', status = '$status_dependente'
                                                WHERE id_cliente = $id_cliente_dependente";
                                            }else{
                                                
                                                 if($msg_status == 'finalizar_venda' AND $status_dependente_antigo == '99'){
                                                    $sql_c_europ_dep    = "UPDATE clientes SET tipo_movimento = 'AL', data_inicio = '$agora', data_termino = '$data_termino', data_cancelamento = '', $sql_prazo_verificar status = '$status_dependente'
                                                    WHERE id_cliente = $id_cliente_dependente";
                                                 }else{
                                                    $sql_c_europ_dep    = "UPDATE clientes SET data_inicio = '$agora', data_termino = '$data_termino', $sql_prazo_verificar status = '$status_dependente'
                                                    WHERE id_cliente = $id_cliente_dependente";
                                                 }
 
                                            }
                                 
                                            $query_c_europ_dep    = mysql_query($sql_c_europ_dep, $banco_produto) or die(mysql_error()." - 1416");
                                            
                                        }
                                    }
                                   
                                   
                                        
                                    
                                }
                                
                            }
                            $cliente_ok++;
                            if($msg_status == 'ativar_venda'){
                                $implode_array_id_venda_ordem_pedido = implode("-", $array_id_venda_ordem_pedido);
                                $array_produto_id_venda_ordem_pedido[] = $id_base_produto."_".$implode_array_id_venda_ordem_pedido;
                            }
                            
                        }
                        
                        $sql_c_europ    = "UPDATE clientes SET data_cancelamento = ''
                                            WHERE chave = '$chave_cliente' ";
                    
                        $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 1417");
                        
                    }elseif($msg_status == 'finalizar_cadastro'){
                        // finaliza cadastro de dependente
                        
                        $sql_status_pedido     = "SELECT ve.status_pedido, cl.status, cl.id_cliente_principal FROM vendas ve
                                                JOIN clientes cl ON ve.id_cliente = cl.id_cliente_principal
                                                    WHERE cl.id_cliente = $id_cliente";
                                                    
                        $query_status_pedido   = mysql_query($sql_status_pedido, $banco_produto);
                                       
                        if (mysql_num_rows($query_status_pedido)>0)
                        {
                            $status_pedido          = mysql_result($query_status_pedido, 0, 'status_pedido');
                            $status_cliente         = mysql_result($query_status_pedido, 0, 'status_pedido');
                            $id_cliente_principal   = mysql_result($query_status_pedido, 0, 'id_cliente_principal');
                            
                            $sql_status_pedido     = "SELECT status'status_cliente_principal' FROM clientes 
                                                    WHERE id_cliente = $id_cliente_principal";
                                                    
                            $query_status_pedido   = mysql_query($sql_status_pedido, $banco_produto);
                            $status_cliente_principal = '';              
                            if (mysql_num_rows($query_status_pedido)>0)
                            {
                                $status_cliente_principal = mysql_result($query_status_pedido, 0, 'status_cliente_principal');
                            }
                            
                            if($status_pedido === 'Pago' OR $status_pedido === 'Autorizado'){
                                $status_remessa = 0;
                            }else{
                                
                                $status_remessa = $status_cliente_principal;
                            }
                            
                            $sql_c_europ    = "UPDATE clientes SET nome = '$nome', cpf = '$cpf', data_nascimento = '$data_nasc', sexo = '$sexo', estado_civil = '$estado_civil', email = '$email', telefone ='$telefone', celular = '$celular', cep = '$cep', endereco = '$endereco', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', status = '$status_remessa'
                            WHERE chave = '$chave_cliente'";
                                 
                            $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 1418");
            
                            if($query_c_europ)
                            {
                                $cliente_ok++;
                            }

                        }
                                        
                    }elseif($msg_status == 'editar_cliente'){
                        
                        $sql_status_cliente     = "SELECT tipo_movimento, status FROM clientes
                                                    WHERE chave = '$chave_cliente'";
                                                    
                        $query_status_cliente   = mysql_query($sql_status_cliente, $banco_produto);
                                       
                        if (mysql_num_rows($query_status_cliente)>0)
                        {
                            $status_cliente          = mysql_result($query_status_cliente, 0, 'status');
                            $tipo_movimento_cliente  = mysql_result($query_status_cliente, 0, 'tipo_movimento');
                            
                            if($status_cliente == 99){
                                $novo_status = 0;
                                $tipo_movimento = 'AL';
                            }elseif($status_cliente == 0){
                                $novo_status = 0;
                                $tipo_movimento = $tipo_movimento_cliente;
                            }else{
                                $novo_status = $status_cliente;
                                $tipo_movimento = $tipo_movimento_cliente;
                            }
                            
                            $sql_c_europ    = "UPDATE clientes SET nome = '$nome', cpf = '$cpf', tipo_movimento = '$tipo_movimento', data_nascimento = '$data_nasc', sexo = '$sexo', estado_civil = '$estado_civil', email = '$email', telefone ='$telefone', celular = '$celular', cep = '$cep', endereco = '$endereco', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', status = $novo_status
                            WHERE chave = '$chave_cliente'";
                              
                            $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 1419");
                            
                            if($query_c_europ)
                            {
                                $cliente_ok++;
                            }
                            
                        }
                        
                    
                    }elseif($msg_status == 'renovar_venda'){
                        
                        $sql_c_venda    = "INSERT INTO historico_vendas (id_ordem_pedido, tipo_pagamento, desconto, valor_entrada, valor_adicional, valor_parcela, valor_parcela_total, valor_total, parcelas, prazo, data_venda, id_usuario, data_cadastro)
                                    VALUES ('$hist_renova_id_ordem_pedido', '$hist_renova_tipo_pagamento', '$hist_renova_desconto', '$hist_renova_valor_entrada', '$hist_renova_valor_adicional', '$hist_renova_valor_parcela', '$hist_renova_valor_parcela_total', '$hist_renova_valor_total', '$hist_renova_parcelas', '$hist_renova_prazo', '$hist_renova_data_venda', '$id_usuario', '".agora()."')";   
                        $query_c_venda    = mysql_query($sql_c_venda, $banco_produto) or die(mysql_error()." - 11");
                    
                        
                        $sql_termino_cliente     = "SELECT data_termino FROM clientes
                                                    WHERE chave = '$chave_cliente'";
                                                    
                        $query_termino_cliente   = mysql_query($sql_termino_cliente, $banco_produto);
                        $data_termino = '';               
                        if (mysql_num_rows($query_termino_cliente)>0)
                        {
                            $data_termino  = mysql_result($query_termino_cliente, 0, 'data_termino');
                        }
                    
                        $status_remessa = 6;
                        $status_pedido = "Nao_Finalizado";
                        $vr_valor_parcela_boleto = '';
                        $vr_parcela_parcelas_boleto = '';
                        if($metodo_pagamento == 'ON')
                        {
                            $status_remessa = 4;
                            $status_pedido = "Nao_Finalizado";
                        }
                        
                        if($metodo_pagamento == 'BO' OR $metodo_pagamento == 'MA'){
                            $vr_valor_parcela_boleto = $valor_parcela_boleto;
                            $vr_parcela_parcelas_boleto = $parcela_parcelas_boleto;
                        }
                        
                        if(!isset($comprovante_maquina))
                        {
                            $comprovante_maquina = '';
                        }
                        $agora = date('Y-m-d');
                        
                        if(strtotime($data_termino) < strtotime($agora)){
                            $data_termino = $agora;
                        }
                        $data_termino = converte_data($data_termino);
                        $data = somar_datas( $prazo, 'm'); // adiciona 3 meses a sua data
                        $data_termino = str_replace('/', '-', $data_termino);
                        $data_termino = date('d/m/Y', strtotime($data,strtotime($data_termino)));
                        $data_termino = converte_data($data_termino);
        
                        $sql_c_europ    = "UPDATE clientes SET tipo_movimento = 'AL', data_inicio = '$agora', data_termino = '$data_termino', data_nascimento = '$data_nasc', sexo = '$sexo', estado_civil = '$estado_civil', email = '$email', telefone ='$telefone', celular = '$celular', cep = '$cep', endereco = '$endereco', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', estado = '$estado', prazo = '$prazo', status = '$status_remessa'
                        WHERE chave = '$chave_cliente'";
                             
                        $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 1420");
        
                        if($query_c_europ)
                        { 
                            $sql_principal        = "SELECT id_cliente, nome, cpf FROM clientes
                                                    WHERE chave = '$chave_cliente'";
                                                    
                            $query_principal      = mysql_query($sql_principal, $banco_produto);
                                          
                            if (mysql_num_rows($query_principal)>0)
                            {
                                $array_id_cliente_principal = array(); 
                                while($dados_principal = mysql_fetch_array($query_principal))
                                {
                                    extract($dados_principal);
                                    $array_id_cliente_principal[] = $id_cliente;
                                    $sql_depen        = "SELECT id_cliente'id_cliente_dependente', status'verifica_status_add' FROM clientes
                                                    WHERE id_cliente_principal = $id_cliente";
                                    $query_depen      = mysql_query($sql_depen, $banco_produto);
                                                    
                                    if (mysql_num_rows($query_depen)>0)
                                    {
                                        //$id_cliente_dependente = mysql_result($query_depen, 0, 'id_cliente');
                                        
                                        while($dados_up_depen = mysql_fetch_array($query_depen))
                                        {
                                            extract($dados_up_depen);
                                            $sql_c_europ    = "UPDATE vendas SET tipo_pagamento = '$forma_pagamento', desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$soma_produto_atual', valor_parcela_total = '$vr_valor_parcela_boleto', valor_total = '$total_geral_assistencia', parcelas = '$vr_parcela_parcelas_boleto', prazo = '$prazo',  metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido'
                                            WHERE id_cliente = $id_cliente_dependente";
                                            $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                                            
                                            if($verifica_status_add == 3){
                                                $status_remessa = 3;
                                            }
                                             
                                            $sql_c_europ_dep    = "UPDATE clientes SET tipo_movimento = 'AL', data_inicio = '$agora', data_termino = '$data_termino', prazo = '$prazo', status = '$status_remessa'
                                            WHERE id_cliente = $id_cliente_dependente AND tipo_movimento IN ('IN', 'AL')";
                                 
                                            $query_c_europ_dep    = mysql_query($sql_c_europ_dep, $banco_produto) or die(mysql_error()." - 1421"); 
                                        }
                                    }
                                   
                                    $sql_c_europ    = "UPDATE vendas SET tipo_pagamento = '$forma_pagamento', desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$soma_produto_atual', valor_parcela_total = '$vr_valor_parcela_boleto', valor_total = '$total_geral_assistencia', parcelas = '$vr_parcela_parcelas_boleto', prazo = '$prazo', metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido'
                                    WHERE id_cliente = $id_cliente";
                                    $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                                    
                                    /*$sql_id_venda        = "SELECT id_venda FROM vendas
                                                            WHERE id_cliente = $id_cliente";
                                    $query_id_venda      = mysql_query($sql_id_venda, $banco_produto);
                                        
                                    $id_venda_cliente = mysql_result($query_id_venda, 0, 'id_venda');   
                                     
                                    $array_id_venda_ordem_pedido[] = $id_venda_cliente;*/
                                    
                                }
                                /*$sql_ordem   = "SELECT ordem_pedido FROM ordem_pedidos
                                WHERE id_ordem_pedido = $verifica_id_ordem_pedido_principal";
        
                                $query_ordem = mysql_query($sql_ordem, $banco_painel) or die(mysql_error()." - 696969");
                                
                                if (mysql_num_rows($query_ordem)>0)
                                {
                                    $ordem_pedido_ids_vendas       = mysql_result($query_ordem, 0, 'ordem_pedido');
                                    $array_id_base_ids_vendas = explode("|", $ordem_pedido_ids_vendas);
                                    $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                                    $array_ids_base_vendas_id_venda = explode("_", $array_id_base_ids_vendas[$pg]);
                                    $id_base_id_venda = $array_ids_base_vendas_id_venda[0];
                                    $array_id_venda_v_id_venda = explode("-", $array_ids_base_vendas_id_venda[1]);
                                    foreach($array_id_venda_v_id_venda as $v_id_venda){
                                        $array_id_venda_ordem_pedido[] = $v_id_venda;
                                    }
                                }*/
                            }
                            $cliente_ok++;
                            //$implode_array_id_venda_ordem_pedido = implode("-", $array_id_venda_ordem_pedido);
                            //$array_produto_id_venda_ordem_pedido[] = $id_base_produto."_".$implode_array_id_venda_ordem_pedido;
                        }

                    }
                    
                    

                    //if($cliente_ok > 0){
                    // filiais franquias
                    if(isset($nome_dependente))
                    {
                        $sql_dep_adicional        = "SELECT id_ordem_pedido FROM vendas
                        WHERE id_cliente = $id_cliente";
                    $query_dep_adicional      = mysql_query($sql_dep_adicional, $banco_produto);
                                    
                    if (mysql_num_rows($query_dep_adicional)>0)
                    {
                        $id_ordem_pedido_dep = mysql_result($query_dep_adicional, 0,0);
                    }
                        $nr_colunas = count($nome_dependente);
                        for ($i = 0; $i<$nr_colunas; $i++)
                        {
                            $convert_data_nasc_dependente = converte_data($data_nasc_dependente[$i]);
                            if(!empty($nome_dependente[$i]) AND !empty($data_nasc_dependente[$i])){
                                $sql3    = "INSERT INTO dependentes_clientes (id_ordem_pedido, tipo_dependente, nome, data_nascimento, id_cliente)
                                        VALUES ('$id_ordem_pedido_dep', '$tipo_dependente[$i]', '$nome_dependente[$i]', '$convert_data_nasc_dependente', '$cliente_sel_dep[$i]')";
                            
                                $query3  = mysql_query($sql3) or die(mysql_error());
                            }
                        }
                    }
                //}
                    
                    // adiciona se plano adicional novo
                    if($msg_status == 'renovar_venda' OR $msg_status == 'finalizar_venda'){

                        $sql_produtos        = "SELECT pro.versao_produto FROM produtos pro
                        JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
                        JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                        JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                        WHERE pro.ativo = 'S' AND prog.id_grupo_produto = $id_grupo_produto AND pser.id_parceiro = $id_parceiro GROUP BY pro.versao_produto ORDER BY pro.versao_produto ";
                        $query_produtos      = mysql_query($sql_produtos, $banco_painel);
                                        
                        if (mysql_num_rows($query_produtos)>0)
                        {
                            $array_versao_produto = array();
                            while($dados_versao_produto = mysql_fetch_array($query_produtos))
                            {
                                if($dados_versao_produto['versao_produto'] > 0)
                                {
                                    $array_versao_produto[] = $dados_versao_produto['versao_produto'];
                                }
                                
                            }
                            
                        }
                        
                        
                        
                $contar_cliente = count($nome_add) - 1;
                
                $i_contar_add_depen = 0;
                $array_chave_dependente = array();
                $erro_cliente = false;
                $tipo_apolice = 'GRU';
                for ($i = 0; $i<=$contar_cliente; $i++)
                {
                
                $nome_add[$i] = strtr(strtoupper($nome_add[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $nome_add[$i] = remove_acento($nome_add[$i]);
                
                $endereco_add[$i] = strtr(strtoupper($endereco_add[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $endereco_add[$i] = remove_acento($endereco_add[$i]);
                
                $complemento_add[$i] = strtr(strtoupper($complemento_add[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $complemento_add[$i] = remove_acento($complemento_add[$i]);
                
                $bairro_add[$i] = strtr(strtoupper($bairro_add[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $bairro_add[$i] = remove_acento($bairro_add[$i]);
                
                $cidade_add[$i] = strtr(strtoupper($cidade_add[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $cidade_add[$i] = remove_acento($cidade_add[$i]);
                
                $erro_depen = 'nao';
                if($se_dependente[$i] == 'sim')
                {
                    if($erro_cliente == false){
                        
                    
                    $valida_idade = false;
                    if(isset($data_nasc_add[$i]) AND !empty($data_nasc_add[$i])){
                        $idade = calcula_idade($data_nasc_add[$i]);
                        
                        if($idade > 120)
                        {
                            $valida_idade = true;
                            $erro_depen = 'sim';
                        }
                    }else{
                        $valida_idade = true;
                    }
                    
                    if($valida_idade == false)
                    {   
                        
                        $agora = date('Y-m-d');
                        $cpf_add[$i] = limpa_caracteres($cpf_add[$i]);
                        $cep_add[$i] = limpa_caracteres($cep_add[$i]);
                        $data_nasc_add[$i] = converte_data($data_nasc_add[$i]);
                    
                        $sql_europ       = "SELECT COUNT(*) FROM clientes 
                                                WHERE cpf = '$cpf_add[$i]' AND data_nascimento = '$data_nasc_add[$i]' AND tipo_movimento IN ('IN', 'AL') AND id_produto = $id_grupo_produto AND (data_termino > '$agora' OR data_termino = '0000-00-00') AND (status = 99 OR status = 0)";
                        $query_europ     = mysql_query($sql_europ, $banco_produto) or die(mysql_error()." - 12");
                        $contar_linha = mysql_result($query_europ, 0, 0);
                        if($contar_linha == 0)
                        { 
                            $sql_ultimo_id       = "SELECT max(id_cliente)'ultimo_id' FROM clientes";
                            $query_ultimo_id     = mysql_query($sql_ultimo_id, $banco_produto) or die(mysql_error()." - 9");
                            
                            $ultimo_id_cliente = mysql_result($query_ultimo_id, 0, 'ultimo_id');
                            
                            $novo_id_cliente = $ultimo_id_cliente + 1;
                            $novo_id_parceiro_cpf = $id_parceiro.$cpf_add[$i];
                            $chave = createToken($novo_id_cliente, $novo_id_parceiro_cpf);
                            /*$data_termino = ''; 
                            if($prazo > 0)
                            {                        
                                $data = somar_datas( $prazo, 'm'); // adiciona 3 meses a sua data
                                 
                                $data_termino = date('d/m/Y', strtotime($data));
                                $data_termino = converte_data($data_termino);
                            }*/
                            
                            // status remessa
                            $status_pedido = "Nao_Finalizado";
                            $status_remessa = '3';
                            if(!empty($nome_add[$i]) AND !empty($cpf_add[$i]) AND !empty($data_nasc_add[$i]) AND !empty($sexo_add[$i]) AND !empty($endereco_add[$i]) AND !empty($bairro_add[$i]) AND !empty($cidade_add[$i]) AND !empty($estado_add[$i]))
                            {
                                if($msg_status == 'finalizar_venda' AND $manter_vigencia == 'ok'){
                                    $status_remessa = 0;
                                }else{
                                    $status_remessa = 6;
                                }
                                
                            }else{
                                
                                if($metodo_pagamento == 'ON')
                                {
                                    if(!isset($info_pagamento)){
                                        $status_pedido = "Nao_Finalizado";
                                    }
                                } 
                                
                            }
                            
                            if($status_remessa == 6)
                            {
                                if($metodo_pagamento == 'ON')
                                {
                                    if(!isset($info_pagamento)){
                                        $status_remessa = 4;
                                        $status_pedido = "Nao_Finalizado";
                                    }
                                } 
                            }
                            
                            if(!isset($comprovante_maquina))
                            {
                                $comprovante_maquina = '';
                            }
                            
                            $contar_versao = count($array_versao_produto) - 1;
                            $array_chave_dependente[] = $chave;
                            for($v=0;$contar_versao>=$v;$v++)
                            {

                                $sql_c_europ    = "INSERT INTO clientes (id_parceiro, id_filial, id_usuario, id_cliente_principal, tipo_movimento, chave, id_produto, versao_europ, tipo_apolice, data_emissao, data_inicio, data_termino, nome, cpf, data_nascimento, sexo, estado_civil, email, telefone, celular, cep, endereco, numero, complemento, bairro, cidade, estado, prazo, status)
                                        VALUES ('$id_parceiro', '$id_filial', '$id_usuario', '$array_id_cliente_principal[$v]', 'IN', '$chave', '$id_grupo_produto', '$array_versao_produto[$v]', '$tipo_apolice', '$agora', '$agora', '$data_termino', '$nome_add[$i]', '$cpf_add[$i]', '$data_nasc_add[$i]', '$sexo_add[$i]', '$estado_civil_add[$i]', '$email_add[$i]', '$telefone_add[$i]', '$celular_add[$i]', '$cep_add[$i]', '$endereco_add[$i]', '$numero_add[$i]', '$complemento_add[$i]', '$bairro_add[$i]', '$cidade_add[$i]', '$estado_add[$i]', '$prazo', $status_remessa)";
                                   
                                $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 10");
                                
                                if($query_c_europ)
                                {
                                    $id_cliente_insert = mysql_insert_id($banco_produto);
                                    $pt_i = $i;
                                    $sql_c_venda    = "INSERT INTO vendas (id_cliente, id_ordem_pedido, tipo_pagamento, desconto, valor_entrada, dependente, parentesco, valor_dependente, valor_parcela, valor_parcela_total, valor_total, parcelas, prazo, data_venda, metodo_pagamento, comprovante_maquina, status_pedido)
                                    VALUES ('$id_cliente_insert', '$verifica_id_ordem_pedido_principal', '$forma_pagamento', '$desconto', '$valor_entrada', 'S', '$parentesco_add[$pt_i]', '$valor_dependente', '$soma_produto_atual', '$soma_produto', '$total_geral_assistencia', '$parcela_parcelas_boleto', '$prazo', '$agora', '$metodo_pagamento', '$comprovante_maquina', '$status_pedido')";   
                                    $query_c_venda    = mysql_query($sql_c_venda, $banco_produto) or die(mysql_error()." - 11");
                                    
                                    if($query_c_venda)
                                    {
                                        $array_id_venda_ordem_pedido[] = mysql_insert_id($banco_produto);
                                    }
                                    $cliente_ok++;
                                }
                                
                               if($v == 0){
                                $id_cliente_insert_pri = $id_cliente_insert;
                               } 
                            }
                            $ad_dp_i = $i;
                            
                            if($add_adicional_verifica_add_dependente[$ad_dp_i] == 'S'){
                                
                                        $nr_colunas = $add_adicional_principal_contar_dependente[$ad_dp_i] - 1;
                                        for($ii=0;$ii<=$nr_colunas;$ii++)
                                        {   
                                            if(isset($adicional_nome_dependente[$i_contar_add_depen]))
                                            {
                                                $concat_nome_add_depen = $adicional_nome_dependente[$i_contar_add_depen];
                                                $concat_data_nasc_add_depen = $adicional_data_nasc_dependente[$i_contar_add_depen];
                                                $concat_tipo_add_depen = $adicional_tipo_dependente[$i_contar_add_depen];
                                                $convert_data_nasc_dependente = converte_data($concat_data_nasc_add_depen);
                                                if(!empty($concat_nome_add_depen) AND !empty($concat_data_nasc_add_depen)){
                                                    $sql3    = "INSERT INTO dependentes_clientes (id_ordem_pedido, tipo_dependente, nome, data_nascimento, id_cliente)
                                                            VALUES ('$verifica_id_ordem_pedido_principal', '$concat_tipo_add_depen', '$concat_nome_add_depen', '$convert_data_nasc_dependente', '$id_cliente_insert_pri')";
                                                
                                                    $query3  = mysql_query($sql3) or die(mysql_error());
                                                }
                                            }
                                            
                                            $i_contar_add_depen++;
                                        }
                                    
                                }
                            }
                    
                    }
                    
                    }
                }
                        }
                        
                        //if($msg_status == 'finalizar_venda'){
                            $implode_array_id_venda_ordem_pedido = implode("-", $array_id_venda_ordem_pedido);
                            $array_produto_id_venda_ordem_pedido[] = $id_base_produto."_".$implode_array_id_venda_ordem_pedido;
                        //}
                        
                    }
                    
    
                }
                else
                {
                    // erro slug produto
                    header("Location: adicionar.php?item=$item&msg_status=adicionar_erro_contar_linha_1");
                    ob_end_flush();
                }
            }

        }// fim slug europ
        elseif($produto_grupo_slug[$pg] == 'sorteio_ead')
        {
           // não é necessário atualizar o cliente     
           $cliente_ok = 0;
           if($msg_status == 'finalizar_venda' OR $msg_status == 'ativar_venda'){
                
                if($msg_status == 'ativar_venda' OR $manter_vigencia == 'ok'){
                    $status_remessa = 'A';
                    $status_pedido = "Pago";
                    
                }else{
                    $status_remessa = 'C';
                    $status_pedido = "Nao_Finalizado";
                } 
                    
                
                if($metodo_pagamento == 'ON')
                {
                    $status_remessa = 'C';
                    $status_pedido = "Nao_Finalizado";
                }
                $vr_valor_parcela_boleto = '';
                $vr_parcela_parcelas_boleto = '';
                if($metodo_pagamento == 'BO' OR $metodo_pagamento == 'MA'){
                    $vr_valor_parcela_boleto = $valor_parcela_boleto;
                    $vr_parcela_parcelas_boleto = $parcela_parcelas_boleto;
                }
                
                $venda_recorrente = 'S';
           
           
                if(!isset($comprovante_maquina))
                {
                    $comprovante_maquina = '';
                }
                $data_termino = ''; 
                if($prazo > 0)
                {                        
                    $data_termino = date('d/m/Y', strtotime('+'.$prazo.' month', strtotime($agora)));
                    //$data = somar_datas( $prazo, 'm'); // adiciona 3 meses a sua data
                    //$data_termino = date('d/m/Y', strtotime($data));
                    $data_termino = converte_data($data_termino);
                }  
                
                //$cpf = mask_total($cpf, "###.###.###-##");
                $sql_ck_id_venda          = "SELECT id_venda FROM vendas
WHERE id_parceiro = '4' AND cpf = '$cpf' AND id_produto > 0
                ORDER BY id_titulo DESC
                LIMIT 0,2";

                $query_ck_id_venda          =  mysql_query($sql_ck_id_venda, $banco_produto);

                $id_venda1       = mysql_result($query_ck_id_venda, 0, 0);
                $id_venda2       = mysql_result($query_ck_id_venda, 1, 0);
                
                
                $sql_c_europ    = "UPDATE vendas SET status = '$status_remessa', data_termino = '$data_termino'
                WHERE id_venda = $id_venda2";
                $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                
                if($msg_status == 'ativar_venda'){
                    $sql_c_europ    = "UPDATE vendas_painel SET status_pedido = '$status_pedido'
                    WHERE id_venda = $id_venda2";
                }else{
                    $sql_c_europ    = "UPDATE vendas_painel SET desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$vr_valor_parcela_boleto', valor_total = '$total_geral_assistencia', parcelas = '$vr_parcela_parcelas_boleto', prazo = '$prazo', metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido'
                    WHERE id_venda = $id_venda2";
                } 
                
                $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                
                
                $sql_ck_id_venda          = "SELECT id_venda_painel FROM vendas_painel
                                            WHERE id_venda = '$id_venda2'";
                $query_ck_id_venda          =  mysql_query($sql_ck_id_venda, $banco_produto);

                $id_venda_painel       = mysql_result($query_ck_id_venda, 0, 0);
                
                $array_id_venda_ordem_pedido[] = $id_venda_painel;
                
                
                $sql_c_europ    = "UPDATE vendas SET status = '$status_remessa', data_termino = '$data_termino'
                WHERE id_venda = $id_venda1";
                $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                
                /* 02 */
                if($msg_status == 'ativar_venda'){
                    $sql_c_europ    = "UPDATE vendas_painel SET status_pedido = '$status_pedido'
                    WHERE id_venda = $id_venda1";
                }else{
                    $sql_c_europ    = "UPDATE vendas_painel SET desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$vr_valor_parcela_boleto', valor_total = '$total_geral_assistencia', parcelas = '$vr_parcela_parcelas_boleto', prazo = '$prazo', metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido'
                    WHERE id_venda = $id_venda1";
                }
                
                $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                
                $sql_ck_id_venda          = "SELECT id_venda_painel FROM vendas_painel
                                            WHERE id_venda = '$id_venda1' ";
                $query_ck_id_venda          =  mysql_query($sql_ck_id_venda, $banco_produto);

                $id_venda_painel       = mysql_result($query_ck_id_venda, 0, 0);
                
                $array_id_venda_ordem_pedido[] = $id_venda_painel;
            
                $implode_array_id_venda_ordem_pedido = implode("-", $array_id_venda_ordem_pedido);  
                $array_produto_id_venda_ordem_pedido[] = $id_base_produto."_".$implode_array_id_venda_ordem_pedido;   
                    
           }elseif($msg_status == 'renovar_venda'){
            // echo 'entrou no sorteio ead';
               $hoje           = date('Y-m-d');
               
               $sql_sorteio       = "SELECT sorteio FROM sorteios WHERE inicio <= '$hoje' AND fim >= '$hoje'";
                                     
                $query_sorteio     = mysql_query($sql_sorteio, $banco_produto) or die(mysql_error()." - 12");
                $sorteio = '';
                if(mysql_num_rows($query_sorteio)>0)
                {
                    $sorteio = mysql_result($query_sorteio, 0, 'sorteio');
                }
                $data_termino = '';
                if(!empty($data_termino_atual)){
                   $data_termino = $data_termino_atual;
                }
                
                if($prazo > 0)
                {                        
                    $data_termino = converte_data($data_termino);
                    $data = somar_datas($prazo, 'm'); // adiciona 3 meses a sua data
                    $data_termino = str_replace('/', '-', $data_termino);
                    $data_termino = date('d/m/Y', strtotime($data,strtotime($data_termino)));
                    $data_termino = converte_data($data_termino); 
                }
                    //echo $sorteio;
                    $sql_ck_ti          = "SELECT t.id_titulo FROM titulos t
                                        WHERE utilizado = 'N' AND data_sorteio = '$sorteio' AND id_parceiro = 4
                                        LIMIT 0,2";
                    $query_ck_ti     =  mysql_query($sql_ck_ti, $banco_produto);

                    $id_titulo1      = mysql_result($query_ck_ti, 0, 0);
                    $id_titulo2      = mysql_result($query_ck_ti, 1, 0);

                    if ($id_titulo1 > 0)
                    {
                        $dt_nascimento = converte_data($data_nasc);
                        
                        $status_remessa = 'C';
                        $status_pedido = "Nao_Finalizado";
                        if($metodo_pagamento == 'ON')
                        {
                            $status_remessa = 'C';
                            $status_pedido = "Nao_Finalizado";
                        }
                        $vr_valor_parcela_boleto = '';
                        $vr_parcela_parcelas_boleto = '';
                        if($metodo_pagamento == 'BO' OR $metodo_pagamento == 'MA'){
                            $vr_valor_parcela_boleto = $valor_parcela_boleto;
                            $vr_parcela_parcelas_boleto = $parcela_parcelas_boleto;
                        }
                        
                        $venda_recorrente = 'S';
                        /*if($forma_pagamento == 'entrada_recorrente_cartao' OR $forma_pagamento == 'recorrente_cartao' OR $forma_pagamento == 'fidelidade' OR $forma_pagamento == 'entrada_parcelado_boleto')
                        {
                            $venda_recorrente = 'S';
                        }*/
                        
                        if(!isset($comprovante_maquina))
                        {
                            $comprovante_maquina = '';
                        }
                        
                        //$cpf = mask_total($cpf, "###.###.###-##");
                        //echo $sorteio;
                        $sql_ck_id_venda          = "SELECT id_venda FROM vendas
                        WHERE id_parceiro = '4' AND cpf = '$cpf' AND id_produto > 0
                        ORDER BY id_titulo DESC
                        LIMIT 0,2";

                        $query_ck_id_venda          =  mysql_query($sql_ck_id_venda, $banco_produto);
    
                        $id_venda1       = mysql_result($query_ck_id_venda, 0, 0);
                        $id_venda2       = mysql_result($query_ck_id_venda, 1, 0);
                        
                        
                        $agora = date('Y-m-d');
                        $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $nome = remove_acento($nome); 
                        
                        $endereco = strtr(strtoupper($endereco),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $endereco = remove_acento($endereco);
                        
                        $complemento = strtr(strtoupper($complemento),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $complemento = remove_acento($complemento);
                        
                        $bairro = strtr(strtoupper($bairro),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $bairro = remove_acento($bairro);
                        
                        $cidade = strtr(strtoupper($cidade),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $cidade = remove_acento($cidade);
                        
                        $sql_i      = "INSERT INTO vendas (id_titulo, id_parceiro, nome, cpf, cep,
                                endereco, numero, complemento, bairro, cidade, estado, telefone, email, dt_cadastro, status, dt_nascimento, id_titulo2, id_parceiro_painel, id_usuario_painel, recorrente, data_termino, id_produto)
                                 VALUES ($id_titulo1, 4, '$nome', '$cpf', '$cep',
                                 '$endereco', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$celular', '$email', '".agora()."', '$status_remessa', '$dt_nascimento', '$id_titulo2', '$id_parceiro', '$id_usuario', '$venda_recorrente', '$data_termino', '$id_grupo_produto')";
                        $query_i    = mysql_query($sql_i, $banco_produto) or die(mysql_error());
                        $id_venda = mysql_insert_id($banco_produto);
                        
                        
                        $sql_c_europ    = "UPDATE vendas_painel SET id_venda = $id_venda, tipo_pagamento = '$forma_pagamento', desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$vr_valor_parcela_boleto', valor_total = '$total_geral_assistencia', parcelas = '$vr_parcela_parcelas_boleto', prazo = '$prazo', metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido'
                        WHERE id_venda = $id_venda2";
                        $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                        
                        
                        $sql_ck_id_venda          = "SELECT id_venda_painel FROM vendas_painel
                                                    WHERE id_venda = '$id_venda' ";
                        $query_ck_id_venda          =  mysql_query($sql_ck_id_venda, $banco_produto);
    
                        $id_venda_painel       = mysql_result($query_ck_id_venda, 0, 0);
                        
                        $array_id_venda_ordem_pedido[] = $id_venda_painel;
                        
                        //segundo número da sorte
                        $sql_i2      = "INSERT INTO vendas (id_titulo, id_parceiro, nome, cpf, cep,
                                endereco, numero, complemento, bairro, cidade, estado, telefone, email, dt_cadastro, status, id_venda_mae, dt_nascimento, id_parceiro_painel, id_usuario_painel, recorrente, data_termino, id_produto)
                                 VALUES ($id_titulo2, 4, '$nome', '$cpf', '$cep',
                                 '$endereco', '$numero', '$complemento', '$bairro', '$cidade', '$estado', '$celular', '$email', '".agora()."', '$status_remessa', '$id_venda', '$dt_nascimento', '$id_parceiro', '$id_usuario', '$venda_recorrente', '$data_termino', '$id_grupo_produto')";
                        $query_i2    = mysql_query($sql_i2, $banco_produto) or die(mysql_error());
                        $id_venda_2 = mysql_insert_id($banco_produto);
                        
                        $sql_c_europ    = "UPDATE vendas_painel SET id_venda = $id_venda_2, tipo_pagamento = '$forma_pagamento', desconto = '$desconto', valor_entrada = '$valor_entrada', valor_parcela = '$vr_valor_parcela_boleto', valor_total = '$total_geral_assistencia', parcelas = '$vr_parcela_parcelas_boleto', prazo = '$prazo', metodo_pagamento = '$metodo_pagamento', comprovante_maquina = '$comprovante_maquina', status_pedido = '$status_pedido'
                        WHERE id_venda = $id_venda1";
                        $query_c_europ      = mysql_query($sql_c_europ, $banco_produto);
                        
                        $sql_ck_id_venda          = "SELECT id_venda_painel FROM vendas_painel
                                                    WHERE id_venda = '$id_venda_2' ";
                        $query_ck_id_venda          =  mysql_query($sql_ck_id_venda, $banco_produto);
    
                        $id_venda_painel       = mysql_result($query_ck_id_venda, 0, 0);
                        
                        $array_id_venda_ordem_pedido[] = $id_venda_painel;
                        
                        if ($query_i)
                        {
                            $sql_up = "UPDATE titulos SET utilizado = 'S' WHERE id_titulo = $id_titulo1";
                            $query_up = mysql_query($sql_up, $banco_produto) or die("3".mysql_error());

                            $sql_up2 = "UPDATE titulos SET utilizado = 'S' WHERE id_titulo = $id_titulo2";
                            $query_up2 = mysql_query($sql_up2, $banco_produto) or die("4".mysql_error());
                            
                            $sql_up3 = "UPDATE vendas SET id_venda_mae = '$id_venda' WHERE id_venda = $id_venda";
                            $query_up3 = mysql_query($sql_up3, $banco_produto) or die("4".mysql_error());

                            $cliente_ok++;

                        }

                    }
                    else
                    {
                        //echo "Erro ao registrar venda!";
                    }

              $implode_array_id_venda_ordem_pedido = implode("-", $array_id_venda_ordem_pedido);
                
              $array_produto_id_venda_ordem_pedido[] = $id_base_produto."_".$implode_array_id_venda_ordem_pedido;       
            
            
            
           }
           else
            {
                header("Location: adicionar.php?item=$item&msg_status=adicionar_erro");
                ob_end_flush();
            }                         
        }
        else
        {
            // erro slug produto
            header("Location: adicionar.php?item=$item&msg_status=adicionar_erro");
            ob_end_flush();
        }
        mysql_close($banco_produto);
    }// fim for slug produto
    
    if($cliente_ok > 0)
    {
        if($msg_status == 'finalizar_venda' OR $msg_status == 'renovar_venda' OR $msg_status == 'ativar_venda'){
            
            //echo ' var_em_array_0 '.$array_produto_id_venda_ordem_pedido[0];
            $implode_array_produto_id_venda_ordem_pedido = implode("|", $array_produto_id_venda_ordem_pedido);
            if($msg_status == 'ativar_venda'){
                $sql_ordem_pedido    = "SELECT id_ordem_pedido, ordem_pedido FROM ordem_pedidos 
                                    WHERE ordem_pedido LIKE '%$array_produto_id_venda_ordem_pedido[0]%'";   
                //echo ' sql>> '.$sql_ordem_pedido;
                $query_ordem_pedido  = mysql_query($sql_ordem_pedido, $banco_painel) or die(mysql_error()." - 20.5");
                
                $id_ordem_pedido    = mysql_result($query_ordem_pedido, 0, 'id_ordem_pedido');
                $ordem_pedido       = mysql_result($query_ordem_pedido, 0, 'ordem_pedido');
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
            }else{
                
                $id_ordem_pedido    = $verifica_id_ordem_pedido_principal;
                $ordem_pedido       = $implode_array_produto_id_venda_ordem_pedido;
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                
                $sql_up_ordem_pedido    = "UPDATE ordem_pedidos SET ordem_pedido = '$ordem_pedido'
                WHERE id_ordem_pedido = $id_ordem_pedido";
                //echo $sql_up_ordem_pedido;
                $query_up_c_europ    = mysql_query($sql_up_ordem_pedido, $banco_painel) or die(mysql_error()." - 14.1");
            }
            
            $agora = date('Y-m-d');
            $data_termino_mais_um = '';

            if($metodo_pagamento == 'ON')
            {
                if($forma_pagamento == 'parcelado_cartao'){
                    //echo ' data-termino: '.$data_termino;
                    $data_termino_2 = converte_data($data_termino);
                    //$data = somar_datas( -1, 'm'); // adiciona 3 meses a sua data
                    $data_termino_2 = str_replace('/', '-', $data_termino_2);
                    $data_termino_2 = date('d/m/Y', strtotime('+1 month',strtotime($data_termino_2)));
                    //echo ' dat_term_2: '.$data_termino_2;
                    $data_termino_2 = str_replace('/', '-', $data_termino_2);
                    $menos_prazo = '-'.$prazo.' month';
                    $data_termino_2_2 = date('d/m/Y', strtotime($menos_prazo,strtotime($data_termino_2)));
                    $data_termino_mais_um = converte_data($data_termino_2_2);
                }
            }else{
                if($msg_status == 'renovar_venda'){

                    $data_termino_2 = date('d/m/Y', strtotime('+1 month'));
                    $data_termino_mais_um = converte_data($data_termino_2);
                
                }else{
                    
                    if($manter_vigencia == 'ok'){
                        $data_termino_2 = converte_data($agora);
                        $data_termino_2 = str_replace('/', '-', $data_termino_2);
                        $data_termino_2 = date('d/m/Y', strtotime('+1 month',strtotime($data_termino_2)));
                        $data_termino_mais_um = converte_data($data_termino_2);
                        
                    }else{  
                        /*$data_termino_2 = converte_data($data_termino);
                        $data_termino_2 = str_replace('/', '-', $data_termino_2);
                        $data_termino_2 = date('d/m/Y', strtotime('+1 month',strtotime($data_termino_2)));
                        $data_termino_2 = str_replace('/', '-', $data_termino_2);
                        $menos_prazo = '-'.$prazo.' month';
                        $data_termino_2_2 = date('d/m/Y', strtotime($menos_prazo,strtotime($data_termino_2)));
                        $data_termino_mais_um = converte_data($data_termino_2_2);*/
                        
                        $data_termino_2 = converte_data($agora);
                        $data_termino_2 = str_replace('/', '-', $data_termino_2);
                        $data_termino_2 = date('d/m/Y', strtotime('+1 month',strtotime($data_termino_2)));
                        $data_termino_mais_um = converte_data($data_termino_2);
                        
                    }

                }
                
            }
            
            //echo $data_termino_mais_um;
            //OR $manter_vigencia == 'ok'
            if($msg_status == 'ativar_venda'){
                $sql_up_ordem_pedido    = "UPDATE ordem_pedidos SET status_recorrencia = 'S', enviar_info = 'S', data_modificacao = '$agora', data_script = '$data_termino_mais_um'
                WHERE id_ordem_pedido = $id_ordem_pedido";  
            }else{
                if($msg_status == 'renovar_venda'){
                    $sql_up_ordem_pedido    = "UPDATE ordem_pedidos SET  status_recorrencia = 'S', enviar_info = 'N', data_modificacao = '$agora', metodo_pagamento = '$metodo_pagamento', data_script = '$data_termino_mais_um', status_renovacao = 'S'
                    WHERE id_ordem_pedido = $id_ordem_pedido";
                }else{
                    $enviar_info_manter_vig = " enviar_info = 'N',";
                    if($manter_vigencia == 'ok'){
                        $enviar_info_manter_vig = '';
                    }
                    $sql_up_ordem_pedido    = "UPDATE ordem_pedidos SET status_recorrencia = 'S',$enviar_info_manter_vig data_modificacao = '$agora', metodo_pagamento = '$metodo_pagamento', data_script = '$data_termino_mais_um'
                    WHERE id_ordem_pedido = $id_ordem_pedido";
                }   
            }
                        
            $query_up_c_europ    = mysql_query($sql_up_ordem_pedido, $banco_painel) or die(mysql_error()." - 14.2");
            
            $slug_base = array();
            $id_base = array();
            for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
            {
                $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                $id_base[] = $array_ids_base_vendas[0];
                
                $sql_slug_base    = "SELECT slug FROM bases_produtos 
                                    WHERE id_base_produto = $array_ids_base_vendas[0]";   
    
                $query_slug_base  = mysql_query($sql_slug_base, $banco_painel) or die(mysql_error()." - 20.6");
                
                $slug_base[]    = mysql_result($query_slug_base, 0, 'slug');

            }
            $implode_slug_base_produto = implode("-", $slug_base);
            $implode_id_base = implode("-", $id_base);

            $expire = $_COOKIE["usr_time"];
            $pasta = base64_decode($_COOKIE["pasta"]);
            setcookie("id_banco",           base64_encode($implode_id_base),    $expire, "/".$pasta);
            setcookie("slug",               base64_encode($implode_slug_base_produto),    $expire, "/".$pasta);
            setcookie("cpf",                base64_encode($cpf),    $expire, "/".$pasta);
            setcookie("id_ordem_pedido",    base64_encode($id_ordem_pedido),    $expire, "/".$pasta);

            if($metodo_pagamento == 'ON')
            {
                
                // status_boleto = 0 (ativo)
                // status_boleto = 1 (concluido)
                // status_boleto = 2 (cancelado)
                if(isset($alterar_pagamento) AND $alterar_pagamento == 'true'){
                    $sql_where_boleto = "";
                }else{
                    $sql_where_boleto = "AND pago = 'S'";
                }
                
                $sql_up_boletos    = "UPDATE boletos_clientes SET status_boleto = 1
                WHERE id_ordem_pedido = $id_ordem_pedido $sql_where_boleto";              
                $query_up_boletos    = mysql_query($sql_up_boletos, $banco_painel) or die(mysql_error()." - 14.3");
                //$soma_produto
                $valor_parcela_total = str_replace(".", "", $soma_produto);

                if(!empty($data_termino) AND $verifica_soma_total_geral == true)
                {
                    $total_geral_assistencia = str_replace(".", "", $total_geral_assistencia);
                    $valor_parcela_total = $total_geral_assistencia;
                }
                
                
                $order = new stdClass();
                $order->OrderNumber = $id_ordem_pedido;
                //$order->SoftDescriptor = "Ass. Total";
                
                $order->Cart = new stdClass();
                $order->Cart->Items = array();
                $order->Cart->Items[0] = new stdClass();
                $order->Cart->Items[0]->Name = $nome_grupo_produto;
                $order->Cart->Items[0]->UnitPrice = $valor_parcela_total;
                $order->Cart->Items[0]->Quantity = 1;
                $order->Cart->Items[0]->Type = 'Service';
                
                $order->Shipping = new stdClass();
                $order->Shipping->Type = 'WithoutShipping'; // sem frete
                $order->Shipping->TargetZipCode = limpa_caracteres($cep);
                $order->Shipping->Address = new stdClass();
                $order->Shipping->Address->Street = $endereco;
                $order->Shipping->Address->Number = $numero;
                $order->Shipping->Address->Complement = $complemento;
                $order->Shipping->Address->District = $bairro;
                $order->Shipping->Address->City = $cidade;
                $order->Shipping->Address->State = $estado;
                
                if($verifica_soma_total_geral == false){
                $order->Payment = new stdClass();
                $order->Payment->RecurrentPayment = new stdClass();
                $order->Payment->RecurrentPayment->Interval = 'Monthly';
                
                if(!empty($data_termino))
                {
                    $order->Payment->RecurrentPayment->EndDate = $data_termino;
                }
                }
                
                $order->Customer = new stdClass();
                $order->Customer->Identity = limpa_caracteres($cpf);
                $order->Customer->FullName = $nome;
                $order->Customer->Email = $email;
                $order->Customer->Phone = limpa_caracteres($telefone);
                $order->Options = new stdClass();
                $order->Options->AntifraudEnabled = false;

                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, 'https://cieloecommerce.cielo.com.br/api/public/v1/orders');
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($order));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'MerchantId: 667ab238-460d-4688-9699-3bd2714e0be5',
                    'Content-Type: application/json'
                ));
               
                $response = curl_exec($curl);
                
                $json = json_decode($response);
print_r($json);
                $checkoutUrl = $json->settings->checkoutUrl;

                header("Location: $checkoutUrl");
                
            }elseif($metodo_pagamento == 'BO' OR $metodo_pagamento == 'MA')
            {
                if(isset($comprovante_maquina) AND !empty($comprovante_maquina)){
                    
                }else{
                    $comprovante_maquina = '';
                    $comprovante_doc = '';
                }
                // status_boleto = 0 (ativo)
                // status_boleto = 1 (concluido)
                // status_boleto = 2 (cancelado)
                if(isset($alterar_pagamento) AND $alterar_pagamento == 'true'){
                    $sql_where_boleto = "AND status_boleto = 0";
                    $status_boleto_a = 2;
                }else{
                    $sql_where_boleto = "AND pago = 'S'";
                    $status_boleto_a = 1;
                }
                
                if($manter_vigencia != 'ok'){
                    $sql_up_boletos    = "UPDATE boletos_clientes SET status_boleto = $status_boleto_a
                    WHERE id_ordem_pedido = $id_ordem_pedido $sql_where_boleto";              
                    $query_up_boletos    = mysql_query($sql_up_boletos, $banco_painel) or die(mysql_error()." - 14.4");
                }

                $sql_up_boletos_correcao     = "SELECT metodo_pagamento_boleto FROM boletos_clientes
                                    WHERE id_ordem_pedido = $id_ordem_pedido AND status_boleto = 0
                                    ORDER BY id_boleto ASC";
                $query_up_boletos_correcao      = mysql_query($sql_up_boletos_correcao, $banco_painel);
                                
                if (mysql_num_rows($query_up_boletos_correcao)>0)
                {
                    $verifca_metodo_pagamento_boleto = mysql_result($query_up_boletos_correcao, 0, 'metodo_pagamento_boleto');

                    if($verifca_metodo_pagamento_boleto == ''){
                        $sql_up_boletos_correcao      = "UPDATE boletos_clientes SET metodo_pagamento_boleto = '$ordem_pedido_metodo_pagamento_correcao'
                        WHERE id_ordem_pedido = $id_ordem_pedido AND pago = 'N' AND status_boleto = 0";
                        $query_up_boletos_correcao    = mysql_query($sql_up_boletos_correcao, $banco_painel) or die(mysql_error()." - bol_correcao");
                    }
                    
                }

                    if(!empty($valor_entrada) AND $valor_entrada > 0){
                    
                    $contar_mes = $i + 1;
                    $agora = date('d-m-Y');
                    //$proxima_data = date('d/m/Y', strtotime('+5 days', strtotime($agora)));
                    $proxima_data = $vencimento_entrada;
                    $data = $proxima_data;
                    $partes = explode("/", $data);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    
                    $proxima_data = converte_data($proxima_data);
                    
                    if($manter_vigencia != 'ok'){
                        $sql_c_boleto    = "INSERT INTO boletos_clientes (id_ordem_pedido, mes_referencia, ano_referencia, entrada, parcela, total_parcelas, valor_parcela, pago, data_cadastro, data_vencimento, tipo_boleto, id_usuario, id_parceiro, comprovante_maquina, comprovante_doc, tipo_recebimento, metodo_pagamento_boleto)
                                        VALUES ('$id_ordem_pedido', '$mes_referencia', '$ano_referencia', 'S', '1', '1', '".moeda_db($valor_entrada)."', 'N', '".agora()."', '$proxima_data', '$emissao_boleto', '$id_usuario_post', '$id_parceiro_post', '$comprovante_maquina', '$comprovante_doc', '$tipo_recebimento_entrada', '$metodo_pagamento')";   
                        $query_c_boleto    = mysql_query($sql_c_boleto, $banco_painel) or die(mysql_error()." - 15");
                    }
                }
                
                    if($manter_vigencia == 'ok'){
                        
                        if($ativar_plano_cancelado == 'ok'){
                            
                            if(!empty($valor_entrada_verificar) AND $valor_entrada_verificar > 0){
                                $parcela_parcelas_boleto = $parcela_parcelas_boleto + 1;
                            }
                            $sql_atu_boleto        = "SELECT id_boleto'id_boleto_atual', pago'pago_atual' FROM boletos_clientes
                            WHERE id_ordem_pedido = $id_ordem_pedido AND status_boleto = 2
                            ORDER BY id_boleto DESC
                            LIMIT 0,$parcela_parcelas_boleto";
                            $query_atu_boleto      = mysql_query($sql_atu_boleto, $banco_painel);
                                            
                            if (mysql_num_rows($query_atu_boleto)>0)
                            {
                                while($dados_atu_boleto = mysql_fetch_array($query_atu_boleto))
                                {
                                    extract($dados_atu_boleto);
                                    //if($pago_atual == 'N'){
                                        
                                        $sql_up_boletos    = "UPDATE boletos_clientes SET status_boleto = 0
                                        WHERE id_boleto = $id_boleto_atual";
                                        $query_up_boletos    = mysql_query($sql_up_boletos, $banco_painel) or die(mysql_error()." - 14.4");
                                    
                                    //}
                                    
                                    
                                }
                                
                            }
                            
                        }else{
                            
                            $sql_atu_boleto        = "SELECT id_boleto'id_boleto_atual', pago'pago_atual' FROM boletos_clientes
                            WHERE id_ordem_pedido = $id_ordem_pedido AND pago = 'N' AND entrada = 'N' AND status_boleto = 0";
                            $query_atu_boleto      = mysql_query($sql_atu_boleto, $banco_painel);
                                            
                            if (mysql_num_rows($query_atu_boleto)>0)
                            {
                                while($dados_atu_boleto = mysql_fetch_array($query_atu_boleto))
                                {
                                    extract($dados_atu_boleto);
                                    //if($pago_atual == 'N'){
                                        
                                        $sql_up_boletos    = "UPDATE boletos_clientes SET valor_parcela = '$soma_produto', comprovante_maquina = '$comprovante_maquina', comprovante_doc = '$comprovante_doc', metodo_pagamento_boleto = '$metodo_pagamento'
                                        WHERE id_boleto = $id_boleto_atual";              
                                        $query_up_boletos    = mysql_query($sql_up_boletos, $banco_painel) or die(mysql_error()." - 14.4");
                                    
                                    //}
                                    
                                    
                                }
                                
                            }
                            
                        }
                        
                        
                        
                    }else{
                        for($i=1;$i<=$parcela_parcelas_boleto;$i++){
                        
                        $contar_mes = $i - 1;
                        //$agora = date('d-m-Y');

                        $partes_vencimento_primeira = explode("-", $vencimento_primeira);
                        $partes_vencimento_primeira_dia = $partes_vencimento_primeira[0];
                        $partes_vencimento_primeira_mes = $partes_vencimento_primeira[1];
                        $partes_vencimento_primeira_ano = $partes_vencimento_primeira[2];

                        if($partes_vencimento_primeira_dia == '29' OR $partes_vencimento_primeira_dia == '30' OR $partes_vencimento_primeira_dia == '31'){
                            $vencimento_primeira = '28-'.$partes_vencimento_primeira_mes.'-'.$partes_vencimento_primeira_ano;
                        }

                        $proxima_data = date('d/m/Y', strtotime('+'.$contar_mes.' month', strtotime($vencimento_primeira)));
                        
                        $data = $proxima_data;
                        $partes = explode("/", $data);
                        //$dia = $partes[0];
                        $mes_referencia = $partes[1];
                        $ano_referencia = $partes[2];
                        
                        $proxima_data = converte_data($proxima_data);
    
                        $sql_c_boleto    = "INSERT INTO boletos_clientes (id_ordem_pedido, mes_referencia, ano_referencia, parcela, total_parcelas, valor_parcela, pago, data_cadastro, data_vencimento, tipo_boleto, id_usuario, id_parceiro, comprovante_maquina, comprovante_doc, metodo_pagamento_boleto)
                                        VALUES ('$id_ordem_pedido', '$mes_referencia', '$ano_referencia', '$i', '$parcela_parcelas_boleto', '$soma_produto', 'N', '".agora()."', '$proxima_data', '$emissao_boleto', '$id_usuario_post', '$id_parceiro_post','$comprovante_maquina', '$comprovante_doc', '$metodo_pagamento')";   
                        $query_c_boleto    = mysql_query($sql_c_boleto, $banco_painel) or die(mysql_error()." - 15");
    
                        }
                        
                    }
                    /*if($emissao_boleto == 'LOJA'){
         
                    }elseif($emissao_boleto == 'BANCO'){
      
                    }*/
                
                //}
                
                if($msg_status == 'renovar_venda'){
                
                   require_once ('/home/trailservicos/public_html/painel_trail/inc/class.phpmailer.php');
                   include_once('/home/trailservicos/public_html/painel_trail/inc/simple_html_dom.php');
                
                
                    $img = "<a href=\"#\"><img src=\"/home/trailservicos/public_html/painel_trail/email/logo_empresa.png\" width=\"147\" height=\"46\" alt=\"painel_trail\" /></a>";
                    
                    $email = 'contato@realizamaissaude.com.br';
                    $name = 'ADM - REALIZA SAUDE';
                    //$nome = 'rafael';
                    //$cpf = '055.';
                    $cidade_estado = $cidade." - ".$estado;
                    $message = file_get_contents('/home/trailservicos/public_html/painel_trail/email/email_ativar_cliente.html');
                    $message = str_replace('%imagem%', $img, $message);
                    $message = str_replace('%nome%', $nome, $message);
                    $message = str_replace('%cpf%', $cpf, $message);
                    $message = str_replace('%dt_nasc%', converte_data($data_nasc), $message);
                    $message = str_replace('%cidade_estado%', $cidade_estado, $message);
                    $message = str_replace('%cod_baixa%', " ", $message);
                    $message = str_replace('%data_pagamento%', " ", $message);
                    $message = str_replace('%mes_referencia%', " ", $message);
                    $message = str_replace('%ano_referencia%', " ", $message);
                    $message = str_replace('%valor_pago%', " ", $message);
                    
                    
                    $mail = new PHPMailer(true);
                    $mail->CharSet = "UTF-8";
                    $mail->SMTPSecure = "ssl";
                    $mail->IsSMTP();
                    $mail->Host = "smtp.zoho.com";
                    $mail->Port = "465";
                    $mail->SMTPAuth = true;
                    $mail->Username = "contato@realizamaissaude.com.br";
                    $mail->Password = "senharealiza+saude";
                    $mail->IsHTML(true);
                    
                    //$mail->AddAddress($email, $name);
                    $mail->AddAddress('contato@trailservicos.com.br', 'TRAIL SERVICOS');
                    
                    //$mail->AddReplyTo('contato@trailservicos.com.br', 'ADM - REALIZA SAUDE');
                    
                    $mail->SetFrom = 'contato@realizamaissaude.com.br'; 
                    $mail->FromName = 'REALIZA SAUDE'; 
                    $mail->From = 'contato@realizamaissaude.com.br';
                    $mail->Subject = "VENDA RENOVADA: ".$cpf.' - '.$nome.' (REALIZA SAUDE)';
                    
                    
                    $mail->MsgHTML($message);
                                    
                    $mail->AltBody = strip_tags($message);
    
                    if($mail->Send()) 
                    {
                        
                    }   
                }
                // redirecionar para o comprovante
                header("Location: listar.php?item=comprovante&url_referencia=painel&manter=$manter_vigencia");
                ob_end_flush();
            }else
            {
                // redirecionar para o comprovante
                //header("Location: listar.php?item=comprovante&url_referencia=painel&id_banco=$implode_id_base_produto&slug=$implode_slug_base_produto&cpf=$cpf&cert=$chave_cliente&erro_depen=nao&id_venda_sort_ead=$id_venda");
                header("Location: listar.php?item=comprovante&url_referencia=painel");
                ob_end_flush();
            }
        
        }elseif($msg_status == 'finalizar_cadastro' OR $msg_status == 'editar_cliente'){
            // redirecionar para o comprovante
                header("Location: listar.php?item=clientes&id=$id_produto_get&tipo=produto");
                ob_end_flush();
            
        }/*elseif($msg_status == 'ativar_venda'){
                header("Location: listar.php?item=comprovante&url_referencia=painel");
                ob_end_flush();
        }*/

    }
    else
    {
        // erro slug produto
        header("Location: adicionar.php?item=$item&msg_status=adicionar_erro_cliente_ok_0");
        ob_end_flush();

    }
    
    
}
elseif ($item == "blocos")
{    

    $verifica = result("SELECT COUNT(*) FROM blocos WHERE id_bloco = $id");
                 
    if ($verifica == 0)
    {
        //já existe coleção com eesse nome      
        header("Location: editar.php?item=$item&id=$id&msg_status=editar_erro");
    } 
    else
    {
        //up registro
        $sql3    = "UPDATE blocos SET nome = '$nome', texto = '$content', dt_modificacao = '".agora()."' 
                    WHERE id_bloco = $id";
        
        $query3  = mysql_query($sql3) or die(mysql_error());
        if ($query3)
        {
            header("Location: ver.php?item=$item&id=$id&msg_status=editar_ok"); 
        }
    }
    
    
    
}
elseif ($item == "info_servicos_servicos")
{    

    $verifica = result("SELECT COUNT(*) FROM servicos WHERE id_servico = $id");
              
    if ($verifica == 0)
    {
        //já existe coleção com eesse nome      
        header("Location: editar.php?item=$item&id=$id&msg_status=editar_erro");
    } 
    else
    {
        
        $verifica = result("SELECT COUNT(*) FROM servicos WHERE nome LIKE '$nome' AND id_servico <> $id");
    
        if ($verifica>0)
        {
            //já existe pasta com eesse nome      
            header("Location: editar.php?item=info_servicos_servicos&id=$id&msg_status=nome_existe");
        } 
        else
        {
            //up registro
            $sql3    = "UPDATE servicos SET nome = '$nome', valor = '$valor', vigencia = '$vigencia', max_parcelas = '$numero', dt_modificacao = '".agora()."' 
                        WHERE id_servico = $id";
            
            $query3  = mysql_query($sql3) or die(mysql_error());
            if ($query3)
            {
                header("Location: listar.php?item=info_servicos&id=$id&msg_status=editar_ok"); 
            }
            
         }
    }
    
    
    
}
elseif ($item == "grupos_parceiros")
{    

    $verifica = result("SELECT COUNT(*) FROM grupos_parceiros WHERE nome = '$nome' AND id_grupo_parceiro <> $id");
                 
    if ($verifica > 0)
    {
        //já existe coleção com eesse nome      
        header("Location: editar.php?item=$item&id=$id&msg_status=editar_erro");
    } 
    else
    {
        $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
        //up registro
        $sql3    = "UPDATE grupos_parceiros SET nome = '$nome' 
                    WHERE id_grupo_parceiro = $id";
        
        $query3  = mysql_query($sql3) or die(mysql_error());
        if ($query3)
        {
            header("Location: listar.php?item=$item&msg_status=editar_ok"); 
        }
    }
    
    
    
}
elseif ($item == "usuarios")
{
        $ativo = 'S';
        if (!empty($apagar_user))
        {
            $ativo = 'N';
        } 
        $lista_permissoes_array = implode("|", $lista_permissoes);
        $sql    = "UPDATE usuarios SET nome = '$nome', email = '$email', ativo = '$ativo', del = '$status', lista_permissoes = '$lista_permissoes_array' ";
        
       
        if (!empty($senha))
        {
            $senha = md5($senha);       
            $sql.= ", senha = '$senha'";
        } 
        if (!empty($nivel))
        {
            $sql.= ", nivel = '$nivel'";
        } 
        $sql    .=" WHERE md5(id_usuario) = '$id'";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: listar.php?item=$item&msg_status=editar_ok"); 
        }
        else
        {
            header("Location: editar.php?item=$item&id=$id&msg_status=editar_erro");   
            //echo $sql;
        }
                
    
    
}elseif ($item == "pagamentos")
{ 
        
        $sql    = "UPDATE pagamentos SET pago = '$pagamento', data_vencimento = '".converte_data($vencimento)."', data_pagamento = '".converte_data($recebimento)."', valor_recebido = '".moeda_db($valor_recebido)."', obs = '$observacoes' 
                WHERE id_pagamento = $id_pagamento";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            if($pagamento == 'S'){
                
                if($confirmar_baixa_boletos == 'S'){
                    $banco_painel = $link;
                    
                    $sql_fat = "SELECT id_faturamento FROM pagamentos 
                    WHERE id_pagamento = $id_pagamento";
        
                    $query_fat      = mysql_query($sql_fat, $banco_painel) or die(mysql_error()." - 7");
                    if (mysql_num_rows($query_fat)>0)
                    {
                        $id_faturamento = mysql_result($query_fat, 0,0);
                        $sql    = "UPDATE faturamentos SET baixa_boletos = 'S'
                        WHERE id_faturamento = $id_faturamento";
                        $query  = mysql_query($sql);
                        
                    }

                    $sql_base        = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                    JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                    JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                    JOIN servicos serv ON pro.id_servico = serv.id_servico
                                            WHERE serv.id_servico = $id_grupo_produto
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
                    
                    if($slug == 'europ')
                    {
                        
                        $sql_pag = "SELECT pa.id_parceiro, pa.mes_referencia, pa.ano_referencia, pa.periodo_inicio, pa.periodo_fim, pa.todo_periodo, fa.id_produto_grupo, fa.prazo, fa.id_filial FROM pagamentos pa
                                    JOIN faturamentos fa ON pa.id_faturamento = fa.id_faturamento
                                                WHERE pa.id_pagamento = $id_pagamento";
            
                        $query_pag      = mysql_query($sql_pag, $banco_painel) or die(mysql_error()." - 7");
    
                        if (mysql_num_rows($query_pag)>0)
                        {
                            $id_parceiro            = mysql_result($query_pag, 0,0);
                            $mes_referencia         = mysql_result($query_pag, 0,1);
                            $ano_referencia         = mysql_result($query_pag, 0,2);
                            $data1                  = mysql_result($query_pag, 0,3);
                            $data2                  = mysql_result($query_pag, 0,4);
                            $todos_clientes_ativos  = mysql_result($query_pag, 0,5);
                            $id_produto_plano       = mysql_result($query_pag, 0,6);
                            $prazo                  = mysql_result($query_pag, 0,7);
                            $id_filial              = mysql_result($query_pag, 0,8);
                        }
                        
                        $sql_filial = '';
                        if($id_filial > 0){
                            $sql_filial = "AND id_filial = $id_filial";
                        }
                        
                        
                        if($prazo > 0){
                            $se_prazo = "AND prazo = $prazo";
                        }elseif($prazo == ''){
                            $se_prazo = "";
                        }elseif($prazo == 0){
                            $se_prazo = "AND id_produto = $id_produto_plano";
                        }
                        
                       
                        $data1 = str_replace("-", "/", $data1);
                        $data2 = str_replace("-", "/", $data2);
                        $sql_data_emissao = "(data_inicio BETWEEN '$data1' AND '$data2')";
                        $agora 			= date("Y-m-d");
                        //$ano_referencia = date("Y");
                        
                        if($todos_clientes_ativos == 'S'){
                            $sql_data_emissao = "data_inicio <= '$agora'";
                        }
                        $sql_clientes_ativos = "SELECT * FROM clientes
                        WHERE $sql_data_emissao AND id_parceiro = $id_parceiro $sql_filial AND tipo_movimento IN ('IN', 'AL') $se_prazo AND status IN (99) GROUP BY chave";
            
                        $query_clientes_ativos      = mysql_query($sql_clientes_ativos, $banco_produto) or die(mysql_error()." - 7");
                        
                        if (mysql_num_rows($query_clientes_ativos)>0)
                        { 
                            while($dados_clientes_ativos = mysql_fetch_array($query_clientes_ativos))
                            {
                                extract($dados_clientes_ativos);
                                
                                if($id_cliente_principal == 0){
                                    $sql_vendas = "SELECT id_ordem_pedido FROM vendas 
                                    WHERE id_cliente = $id_cliente";
                        
                                    $query_vendas      = mysql_query($sql_vendas, $banco_produto) or die(mysql_error()." - 7");
                //echo 'ven,';
                                    if (mysql_num_rows($query_vendas)>0)
                                    {
                                        $id_ordem_pedido = mysql_result($query_vendas, 0,0);
                                        
                                        $sql_boletos = "SELECT id_boleto, valor_parcela FROM boletos_clientes
                                        WHERE id_ordem_pedido = $id_ordem_pedido AND mes_referencia = $mes_referencia AND ano_referencia = $ano_referencia AND entrada = 'N' AND pago = 'N' AND status_boleto = 0";
                            
                                        $query_boletos      = mysql_query($sql_boletos, $banco_painel) or die(mysql_error()." - 7");
                                        //echo $sql_boletos;
                                        if (mysql_num_rows($query_boletos)>0)
                                        {
                                            $id_boleto      = mysql_result($query_boletos, 0,0);
                                            $valor_parcela  = mysql_result($query_boletos, 0,1);
    
                                            $sql_u      = "UPDATE boletos_clientes SET pago = 'S', data_pagamento = '$agora', valor_recebido = '$valor_parcela', id_usuario_recebimento = 305, id_parceiro_recebimento = 17, tipo_recebimento = 'BO', baixa_recebimento = 'S', usuario_baixa = 305, data_baixa = '$agora'
                                            WHERE id_boleto = $id_boleto";
                                            $query_u    = mysql_query($sql_u, $banco_painel);
                                        }
                                        
                                        
                                    }
                                }
                                
                            }
                            echo 'pago';
                        }
                        
                        
                    }
                }else{
                    echo 'pago';
                }
                
                
            }else{
                echo 'nao_pago';
            }            

        }
        else
        {
            echo 'erro';   
        }

    
}elseif ($item == "boletos_clientes")
{ 
        $comprovante = '';
        $hora_pagamento_salvar = '0';
        if($pagamento == 'S'){
            $comprovante = createToken($id_pagamento, $id_parceiro);
            $hora_pagamento_salvar = date("H:i:s");
        }else{
            $tipo_recebimento = '0';
        }
        
                
        
        $sql    = "UPDATE boletos_clientes SET pago = '$pagamento', data_vencimento = '".converte_data($vencimento)."', data_pagamento = '".converte_data($recebimento)."', valor_recebido = '".moeda_db($valor_recebido)."', obs = 'texto: $observacoes', id_usuario_recebimento = '$id_usuario', id_parceiro_recebimento = '$id_parceiro', comprovante = '$comprovante', tipo_recebimento = '$tipo_recebimento', hora_pagamento = '$hora_pagamento_salvar'
                WHERE id_boleto = $id_pagamento ";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            $msg_alterar_dt_boletos = '';
            
            if(strlen($observacoes) > 2){
                $agora = agora();
                
                $sql_coment      = "INSERT INTO comentarios_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
                VALUES ('$id_cliente', '$id_usuario_s', 'clientes', 'Observações de pagamento boleto($id_pagamento): $observacoes', '$agora')";
                
                $query_coment    = mysql_query($sql_coment);
            }

                $sql_pag   = "SELECT id_ordem_pedido, mes_referencia, ano_referencia, parcela, total_parcelas FROM boletos_clientes
                    WHERE id_boleto = $id_pagamento AND status_boleto = 0";
                $query_pag = mysql_query($sql_pag);
               
                if (mysql_num_rows($query_pag)>0)
                {
                    $id_ordem_pedido    = mysql_result($query_pag, 0,'id_ordem_pedido');   
                    $mes_referencia     = mysql_result($query_pag, 0,'mes_referencia');
                    $ano_referencia     = mysql_result($query_pag, 0,'ano_referencia');
                    $parcela            = mysql_result($query_pag, 0,'parcela');
                    $total_parcelas     = mysql_result($query_pag, 0,'total_parcelas');  
                    $proximo_mes = $mes_referencia;
                    //$proxima_parcela = $parcela;
                    if($alterar_todos_boletos == 'S'){
                    $contar_mes = 1;
                    for($i=$parcela;$i<=$total_parcelas;$i++){
                    //error_log($vencimento);
                    $partes_vencimento_primeira = explode("-", $vencimento);
                    $partes_vencimento_primeira_dia = $partes_vencimento_primeira[0];
                    $partes_vencimento_primeira_mes = $partes_vencimento_primeira[1];
                    $partes_vencimento_primeira_ano = $partes_vencimento_primeira[2];

                    if($partes_vencimento_primeira_dia == '29' OR $partes_vencimento_primeira_dia == '30' OR $partes_vencimento_primeira_dia == '31'){
                        $vencimento = '28-'.$partes_vencimento_primeira_mes.'-'.$partes_vencimento_primeira_ano;
                    }
                    
                    $proxima_data = date('d/m/Y', strtotime('+'.$contar_mes.' month', strtotime($vencimento)));
                    
                    $data = $proxima_data;
                    $partes = explode("/", $data);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    
                    //$proximo_mes = $proximo_mes + 1;
                    $proxima_parcela = $i + 1;
                    
                    $sql    = "UPDATE boletos_clientes SET mes_referencia = '$mes_referencia', ano_referencia = '$ano_referencia', data_vencimento = '".converte_data($proxima_data)."'
                    WHERE id_ordem_pedido = $id_ordem_pedido AND parcela = '$proxima_parcela' AND pago = 'N' AND status_boleto = 0";
                    
                    $query  = mysql_query($sql);
                   
                    $contar_mes++;
                    }
                    $msg_alterar_dt_boletos = 'alterada todas as datas dos boletos';
                }
  
            }
            
           
            
            if($pagamento == 'S'){
                
                $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
            VALUES ('$id_cliente', '$id_usuario_s', 'clientes', 'Alteracao pagamento id_pagamento = $id_pagamento (PAGO)', '".agora()."')";   
                $query_historico    = mysql_query($sql_historico) or die(mysql_error()." - 998");
                
                if($status_cliente != '0' AND $status_cliente != '99'){
                    
                    $nome_cliente           = "";
                    $cpf_cliente            = "";
                    $data_nasc_cliente      = "";
                    $cidade_cliente         = "";
                    $estado_cliente         = "";  
                    $banco_painel = $link;
                    // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
                    $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
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
                        $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                        
                        $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                        //$array_slug_base_produto[]  = $slug;
                    }
                    $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
                    
                    if($slug == 'europ'){

                        $sql   = "SELECT nome, cpf, data_nascimento, cidade, estado FROM clientes
                            WHERE id_cliente = $id_cliente";
                        $query = mysql_query($sql, $banco_produto);
                       
                        if (mysql_num_rows($query)>0)
                        {
                            $nome_cliente           = mysql_result($query, 0,'nome');
                            $cpf_cliente            = mysql_result($query, 0,'cpf');
                            $data_nasc_cliente      = mysql_result($query, 0,'data_nascimento');
                            $cidade_cliente         = mysql_result($query, 0,'cidade');
                            $estado_cliente         = mysql_result($query, 0,'estado');  
                            $cidade_estado          = $cidade_cliente."-".$estado_cliente;
                        }
                    }
                require_once ('/home/trailservicos/public_html/painel_trail/inc/class.phpmailer.php');
                include_once('/home/trailservicos/public_html/painel_trail/inc/simple_html_dom.php');
                
                
                $img = "<a href=\"#\"><img src=\"/home/trailservicos/public_html/painel_trail/email/logo_empresa.png\" width=\"147\" height=\"46\" alt=\"painel_trail\" /></a>";
                
                $email = 'contato@realizamaissaude.com.br';
                $name = 'ADM - REALIZA SAUDE';
                //$nome = 'rafael';
                //$cpf = '055.';
                $message = file_get_contents('/home/trailservicos/public_html/painel_trail/email/email_ativar_cliente.html');
                $message = str_replace('%imagem%', $img, $message);
                $message = str_replace('%nome%', $nome_cliente, $message);
                $message = str_replace('%cpf%', $cpf_cliente, $message);
                $message = str_replace('%dt_nasc%', converte_data($data_nasc_cliente), $message);
                $message = str_replace('%cidade_estado%', $cidade_estado, $message);
                $message = str_replace('%cod_baixa%', $id_pagamento, $message);
                $message = str_replace('%data_pagamento%', $recebimento, $message);
                $message = str_replace('%mes_referencia%', $mes_referencia, $message);
                $message = str_replace('%ano_referencia%', $ano_referencia, $message);
                $message = str_replace('%valor_pago%', $valor_recebido, $message);
                
                
                $mail = new PHPMailer(true);
                $mail->CharSet = "UTF-8";
                $mail->SMTPSecure = "ssl";
                $mail->IsSMTP();
                $mail->Host = "smtp.zoho.com";
                $mail->Port = "465";
                $mail->SMTPAuth = true;
                $mail->Username = "contato@realizamaissaude.com.br";
                $mail->Password = "senharealiza+saude";
                $mail->IsHTML(true);
                
                //$mail->AddAddress($email, $name);
                $mail->AddAddress('contato@trailservicos.com.br', 'TRAIL SERVICOS');
                
                //$mail->AddReplyTo('contato@trailservicos.com.br', 'ADM - REALIZA SAUDE');
                
                $mail->SetFrom = 'contato@realizamaissaude.com.br'; 
                $mail->FromName = 'REALIZA SAUDE'; 
                $mail->From = 'contato@realizamaissaude.com.br';
                $mail->Subject = "ATIVAR VENDA: ".$cpf_cliente.' - '.$nome_cliente.' (REALIZA SAUDE)';
                
                
                $mail->MsgHTML($message);
                                
                $mail->AltBody = strip_tags($message);

                if($mail->Send()) 
                {
                    echo 'pago';
                }
                }else{
                    echo 'pago';
                }
                
                
                
            }else{
                $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
            VALUES ('$id_cliente', '$id_usuario_s', 'clientes', 'Alteracao pagamento id_pagamento = $id_pagamento ($msg_alterar_dt_boletos)', '".agora()."')";   
                $query_historico    = mysql_query($sql_historico) or die(mysql_error()." - 998");
                echo 'nao_pago';
            }            
            
            
        }
        else
        {
            echo 'erro';   
        }

    
}elseif ($item == "confirmar_pagamentos")
{ 

        $agora 			= date("Y-m-d");
        if($valor_confirma == 'S'){
             $sql    = "UPDATE boletos_clientes SET baixa_recebimento = '$valor_confirma', usuario_baixa = '$id_usuario', data_baixa = '$agora'
                WHERE id_boleto = $id_pagamento";
        }else{
             $sql    = "UPDATE boletos_clientes SET baixa_recebimento = '$valor_confirma', usuario_baixa = null, data_baixa = null
                WHERE id_boleto = $id_pagamento";
        }
        
        $query  = mysql_query($sql);
        
        if ($query)
        {
            if($valor_confirma == 'S'){
                $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
            VALUES ('$id_cliente', '$id_usuario_s', 'clientes', '(Confirmado o recebimento) pagamento id_pagamento = $id_pagamento', '".agora()."')";   
            $query_historico    = mysql_query($sql_historico) or die(mysql_error()." - 998");
                echo 'confirmado';
                
            }else{
                $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
            VALUES ('$id_cliente', '$id_usuario_s', 'clientes', '(Não confirmado o recebimento) pagamento id_pagamento = $id_pagamento', '".agora()."')";   
            $query_historico    = mysql_query($sql_historico) or die(mysql_error()." - 998");
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
    $sql    = "INSERT INTO comentarios_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
    VALUES ('$id_cliente', '$id_usuario_s', 'clientes', '$comentario', '$agora')";
    
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

    
}elseif ($item == "boletos_cliente_confirmacao")
{ 
    $agora = agora();
    $sql    = "INSERT INTO controle_entregas (tipo, tipo_entrega, id_referencia, data_registro, id_usuario, obs)
    VALUES ('$tipo', '$tipo_entrega', '$id_referencia', '$agora', '$id_usuario_s', '$obs')";
    
    $query  = mysql_query($sql);
    
    if ($query)
    {
        $id_controle_entregas = mysql_insert_id();
        
        $agora_convertido = converte_data($agora);
        $vetor = array($id_controle_entregas, $id_referencia);
        echo implode('%-%', $vetor);
    }
    else
    {
        $vetor = array(1, 2);
        echo implode('%-%', $vetor);
    }

    
}
?>