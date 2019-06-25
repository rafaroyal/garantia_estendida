<?php

/**
 * @project GED
 * @author Rafael Nogueira
 * @created 01/06/2012
 */
 
require_once('sessao.php');
require_once('inc/conexao.php');
require_once('inc/functions.php');
 
if (empty($_POST))
{
    header("Location: inicio.php");
}

$item   = (empty($_POST['item'])) ? "" : verifica($_POST['item']);
$pasta = base64_decode($_COOKIE["pasta"]);

if ($item == "parceiros")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }
    
    if($tipopessoa == "PF")
    {
        $verifica = result("SELECT COUNT(*) FROM parceiros WHERE cpf = $cpf");    
        $novo_nome_arquivo = createToken('1', '2');
    }
    else
    {
        $verifica = result("SELECT COUNT(*) FROM parceiros WHERE cnpj = $cnpj");
        $novo_nome_arquivo = createToken('3', '4');
    }
    
    
    if ($verifica>0)
    {
        //já existe pasta com esse nome      
        header("Location: adicionar.php?item=$item&msg_status=parceiro_existe");
    } 
    else 
    {
        //$forma_venda_array = implode(",", $forma_venda);
        $nome_parceiro = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
        
        $nome_arquivo = 'avatar.png';
        include('inc/image.php');
        $novo_nome_arquivo_rep = preg_replace("/[^0-9]/","", $novo_nome_arquivo);
        //caso tenha logo
        if (!empty($_FILES['logo1']['name']))
        {
            $pasta_img = "assets/pages/img/logos/";
            $array = explode(".", $_FILES['logo1']['name']);
            //$nome  = normaliza($array[0]);
            $extensao = (end($array));
            $nome_arquivo = $novo_nome_arquivo_rep.".".$extensao;
            $arquivo = $pasta_img . $nome_arquivo ;

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
            $pasta_img = "assets/pages/img/logos/";
            $array = explode(".", $_FILES['logo2']['name']);
            //$nome  = normaliza($array[0]);
            $extensao = (end($array));
            $nome_arquivo2 = $novo_nome_arquivo_rep."2.".$extensao;
            $arquivo = $pasta_img . $nome_arquivo2;

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
        }else{
            $valor_plano = moeda_db($valor_plano);
        }
        
        $metodo_pagamento_array = implode("|", $metodo_pagamento);
        $tipo_pagamento_array = implode("|", $tipo_pagamento);
        $parcelas_boleto_array = implode("|", $parcelas_boleto);
        $parcelas_cartao_array = implode("|", $parcelas_cartao);
        $emissao_boleto_array = implode("|", $emissao_boleto);
        if($tipopessoa == "PF")
        {
            //adiciona registro
            $sql    = "INSERT INTO parceiros (tipo, nome, cpf, rg, cep, endereco, numero, complemento,
                        bairro, cidade, estado, tel_res, tel_com, tel_cel, email, ramo_atividade,
                        modalidade, dt_cadastro, logo, metodo_pagamento, tipo_pagamento, parcelas_boleto, valor_plano_adicional, desconto, emissao_boleto, parcelas_cartao, valor_entrada_automatica, entrada, porcentagem_entrada, id_cidade, logo_proposta)
                        VALUES ('$tipopessoa', '$nome_parceiro', '$cpf', '$rg', '$cep', '$endereco', '$numero', '$complemento',
                        '$bairro', '$cidade', '$estado', '$tel_res', '$tel_com', '$tel_cel', '$email', '$ramo_atividade',
                        '$modalidade', '".agora()."', '$nome_arquivo', '$metodo_pagamento_array', '$tipo_pagamento_array', '$parcelas_boleto_array', '$valor_plano', '$desconto', '$emissao_boleto_array', '$parcelas_cartao_array', '$valor_entrada_automatica', '$entrada', '$porcentagem_entrada', '$id_cidade', '$nome_arquivo2')";
        }
        else
        {
            //adiciona registro
            $sql    = "INSERT INTO parceiros (tipo, nome, cnpj, razao_social, nome_fantasia, cep, endereco, numero, complemento,
                        bairro, cidade, estado, tel_com, email, ramo_atividade,
                        modalidade, dt_cadastro, logo, metodo_pagamento, tipo_pagamento, parcelas_boleto, valor_plano_adicional, desconto, emissao_boleto, parcelas_cartao, valor_entrada_automatica, entrada, porcentagem_entrada, id_cidade, logo_proposta)
                        VALUES ('$tipopessoa', '$nome_parceiro', '$cnpj', '$razao', '$fantasia', '$cep', '$endereco', '$numero', '$complemento',
                        '$bairro', '$cidade', '$estado', '$tel_com', '$email', '$ramo_atividade',
                        '$modalidade', '".agora()."', '$nome_arquivo', '$metodo_pagamento_array', '$tipo_pagamento_array', '$parcelas_boleto_array', '$valor_plano', '$desconto', '$emissao_boleto_array', '$parcelas_cartao_array', '$valor_entrada_automatica', '$entrada', '$porcentagem_entrada', '$id_cidade', '$nome_arquivo2')";
        }
                  
        $query  = mysql_query($sql);

        if ($query)
        {
            $id_parceiro = mysql_insert_id();
            if(isset($add_produto))
            {
                $id_parceiro = mysql_insert_id();
                $nr_colunas = count($add_produto);
                for ($i = 0; $i<$nr_colunas; $i++)
                {
                    $id_produto = $add_produto[$i];  
                    //$comissao_atual = $comissao[$i];   
                    //$valor_atual = $valor[$i];   
                    
                    //$preco_custo = moeda_db($preco_custo);
                    //$preco_venda = moeda_db($preco_venda);    
                    //$valor_atual = moeda_db($valor_atual);
                     //adiciona registro
                    $sql3    = "INSERT INTO parceiros_servicos (id_produto, id_parceiro, preco_custo, preco_venda, id_grupo_produto)
                                VALUES ('$id_produto', '$id_parceiro', '$preco_custo[$i]', '$preco_venda[$i]', '$grupo_produto[$i]')";
                    
                    $query3  = mysql_query($sql3) or die(mysql_error());
                }
            }
            else
            {
                echo 'nao set add_produto';
            }
            
            
            $sql4    = "INSERT INTO parceiros_grupos (id_parceiro, id_grupo_parceiro)
                                VALUES ('$id_parceiro', '$grupo')";     
            $query4  = mysql_query($sql4) or die(mysql_error());
            
            
            header("Location: listar.php?item=$item&msg_status=adicionar_ok");
        }
        else
        {
            header("Location: adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
                
    }
    
}
elseif ($item == "clientes")
{
    $banco_painel = $link;
    
    $tipo           = (empty($_POST['tipo_get'])) ? "" : verifica($_POST['tipo_get']);
    
    if($tipo == 'produto')
    {
        foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
        
        $cpf_1 = $cpf[0];
        $cep_1 = $cep[0];
        $data_nasc_1 = $data_nasc[0];
        $id_venda = '';
        $id_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
        $id_filial      = base64_decode($_COOKIE["usr_filial"]);
        $id_usuario     = base64_decode($_COOKIE["usr_id"]);
        if(empty($select_user_pedido) OR $select_user_pedido == '' ){
            $select_user_pedido = $id_usuario;
        }
        
        if(empty($select_filial) OR $select_filial == '' ){
            $select_filial = $id_filial;
        }
        
        $chave_principal = '';
        $cliente_ok = 0;
        $array_id_base_produto = array();
        $array_slug_base_produto = array();
        $array_produto_id_venda_ordem_pedido = array();
        $contar_produto = count($select_grupo_produto) - 1;
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
            $valor_entrada = 0;
        }
        
        $agora = date('Y-m-d');
                             
        $data_script = '';
        $metodo_pagamento_ordem = $metodo_pagamento;
        if($metodo_pagamento == 'ON')
        {
            if(isset($info_pagamento)){
                $metodo_pagamento_ordem = "PR";
            }
            
           if($forma_pagamento == 'parcelado_cartao'){
                $data_soma_script = somar_datas( 1, 'm'); // adiciona 1 mes a sua data                       
                $data_script = date('d/m/Y', strtotime($data_soma_script));
                $data_script = converte_data($data_script);
           } 
            
        }else{
            $data_soma_script = somar_datas( 1, 'm'); // adiciona 1 mes a sua data                       
            $data_script = date('d/m/Y', strtotime($data_soma_script));
            $data_script = converte_data($data_script);
        }
        $sql_ordem_pedido    = "INSERT INTO ordem_pedidos (ordem_pedido, data_cadastro, metodo_pagamento, data_script, id_usuario_pedido)
                    VALUES ('', '$agora', '$metodo_pagamento_ordem', '$data_script', '$id_usuario')";   
        $query_ordem_pedido    = mysql_query($sql_ordem_pedido, $banco_painel) or die(mysql_error()." - 20");
        $id_ordem_pedido = mysql_insert_id($banco_painel);
        
        $erro_cliente = false;
        for ($p = 0; $p<=$contar_produto; $p++)
        {
            $produto_grupo_slug         = explode("|", $slug_produto[$p]);
            $contar_produto_grupo_slug  = count($produto_grupo_slug) - 1;
            
            for ($pg = 0; $pg<=$contar_produto_grupo_slug; $pg++)
            {
                $array_id_venda_ordem_pedido = null;
                $array_id_venda_ordem_pedido = array();
            //$produto_grupo_slug[$pg]
            // seleciona o id do produto pelo id do grupo
            $sql_base   = "SELECT bpro.id_base_produto, gpro.nome FROM produtos_grupos prog
            JOIN produtos pro ON prog.id_produto = pro.id_produto
            JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
        	JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
            WHERE prog.id_grupo_produto = $select_grupo_produto[$p]
            GROUP BY bpro.id_base_produto";
            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
            
            if (mysql_num_rows($query_base)>0)
            {
                $id_base_produto            = mysql_result($query_base, $pg, 'id_base_produto');
                $nome_grupo_produto         = mysql_result($query_base, 0, 'nome');
                $sql_base_select = "WHERE bpro.id_base_produto = $id_base_produto";
                
                $array_id_base_produto[]    = $id_base_produto;
                
            }
            
            if($select_produto[$p] == '' OR $select_produto[$p] == 'todos')
            {
                $produto = '';

                $tipo_apolice = 'GRU';
            }
            else
            {
                $sql_base_select = "WHERE pro.id_produto = $select_produto[$p]";
                $tipo_apolice = 'IND';
            }
            
            
            // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
            $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                        JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                        $sql_base_select";
                        //echo $sql_base;
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
                $sql_produtos        = "SELECT pro.versao_produto FROM produtos pro
                JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
                JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                WHERE pro.ativo = 'S' AND prog.id_grupo_produto = $select_grupo_produto[$p] AND pser.id_parceiro = $id_parceiro GROUP BY pro.versao_produto ORDER BY pro.versao_produto ";
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
                
                $contar_cliente = count($nome) - 1;
                $cliente_ok = 0;
                $i_contar_add_depen = 0;
                $array_chave_dependente = array();
                for ($i = 0; $i<=$contar_cliente; $i++)
                {
                
                $nome[$i] = strtr(strtoupper($nome[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
                $nome[$i] = remove_acento($nome[$i]);
                
                $endereco[$i] = strtr(strtoupper($endereco[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $endereco[$i] = remove_acento($endereco[$i]);
                
                $complemento[$i] = strtr(strtoupper($complemento[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $complemento[$i] = remove_acento($complemento[$i]);
                
                $bairro[$i] = strtr(strtoupper($bairro[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $bairro[$i] = remove_acento($bairro[$i]);
                
                $cidade[$i] = strtr(strtoupper($cidade[$i]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                $cidade[$i] = remove_acento($cidade[$i]);
                
                $erro_depen = 'nao';
                if($se_dependente[$i] == 'sim')
                {
                    if($erro_cliente == false){
                        
                    
                    $valida_idade = false;
                    if(isset($data_nasc[$i]) AND !empty($data_nasc[$i])){
                        $idade = calcula_idade($data_nasc[$i]);
                        
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
                        $cpf[$i] = limpa_caracteres($cpf[$i]);
                        $cep[$i] = limpa_caracteres($cep[$i]);
                        $data_nasc[$i] = converte_data($data_nasc[$i]);
                    
                        $sql_europ       = "SELECT COUNT(*) FROM clientes 
                                                WHERE cpf = '$cpf[$i]' AND data_nascimento = '$data_nasc[$i]' AND tipo_movimento IN ('IN', 'AL') AND id_produto = $select_grupo_produto[$p] AND (data_termino > '$agora' OR data_termino = '0000-00-00') AND (status = 99 OR status = 0)";
                        $query_europ     = mysql_query($sql_europ, $banco_produto) or die(mysql_error()." - 12");
                        $contar_linha = mysql_result($query_europ, 0, 0);
                        if($contar_linha == 0)
                        { 
                            $sql_ultimo_id       = "SELECT max(id_cliente)'ultimo_id' FROM clientes";
                            $query_ultimo_id     = mysql_query($sql_ultimo_id, $banco_produto) or die(mysql_error()." - 9");
                            
                            $ultimo_id_cliente = mysql_result($query_ultimo_id, 0, 'ultimo_id');
                            
                            $novo_id_cliente = $ultimo_id_cliente + 1;
                            $novo_id_parceiro_cpf = $id_parceiro.$cpf[$i];
                            $chave = createToken($novo_id_cliente, $novo_id_parceiro_cpf);
                            $data_termino = ''; 
                            if($prazo > 0)
                            {                        
                                $data = somar_datas( $prazo, 'm'); // adiciona 3 meses a sua data
                                 
                                $data_termino = date('d/m/Y', strtotime($data));
                                $data_termino = converte_data($data_termino);
                            }
                            
                            // status remessa
                            $status_pedido = "Nao_Finalizado";
                            $status_remessa = '3';
                            if(!empty($nome[$i]) AND !empty($cpf[$i]) AND !empty($data_nasc[$i]) AND !empty($sexo[$i]) AND !empty($endereco[$i]) AND !empty($bairro[$i]) AND !empty($cidade[$i]) AND !empty($estado[$i]))
                            {
                                $status_remessa = 6;
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

                                $sql_c_europ    = "INSERT INTO clientes (id_parceiro, id_filial, id_usuario, id_cliente_principal, tipo_movimento, chave, id_produto, versao_europ, tipo_apolice, data_emissao, data_inicio, data_termino, nome, cpf, data_nascimento, sexo, estado_civil, email, telefone, celular, cep, endereco, numero, complemento, bairro, cidade, estado, prazo, status, nome_tabela)
                                        VALUES ('$id_parceiro', '$select_filial', '$select_user_pedido', '$id_cliente[$v]', 'IN', '$chave', '$select_grupo_produto[$p]', '$array_versao_produto[$v]', '$tipo_apolice', '$agora', '$agora', '$data_termino', '$nome[$i]', '$cpf[$i]', '$data_nasc[$i]', '$sexo[$i]', '$estado_civil[$i]', '$email[$i]', '$telefone[$i]', '$celular[$i]', '$cep[$i]', '$endereco[$i]', '$numero[$i]', '$complemento[$i]', '$bairro[$i]', '$cidade[$i]', '$estado[$i]', '$prazo', $status_remessa, '$pasta')";
                                   //echo $sql_c_europ;
                                $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 11212");
                                
                                if($query_c_europ)
                                {
                                    $id_cliente_insert = mysql_insert_id($banco_produto);
                                    $pt_i = $i - 1;
                                    $sql_c_venda    = "INSERT INTO vendas (id_cliente, id_ordem_pedido, tipo_pagamento, desconto, valor_entrada, dependente, parentesco, valor_dependente, valor_parcela, valor_parcela_total, valor_total, parcelas, prazo, data_venda, metodo_pagamento, comprovante_maquina, status_pedido, tipo_desconto)
                                    VALUES ('$id_cliente_insert', '$id_ordem_pedido', '$forma_pagamento', '$desconto', '$valor_entrada', 'S', '$parentesco[$pt_i]', '$valor_dependente', '$soma_produto_atual', '$soma_produto', '$total_geral_assistencia', '$parcela_parcelas_boleto', '$prazo', '$agora', '$metodo_pagamento', '$comprovante_maquina', '$status_pedido', '$tipo_desconto')";   
                                    $query_c_venda    = mysql_query($sql_c_venda, $banco_produto) or die(mysql_error()." - 11");
                                    
                                    if($query_c_venda)
                                    {
                                        $array_id_venda_ordem_pedido[] = mysql_insert_id($banco_produto);
                                    }
                                    
                                }
                                
                               if($v == 0){
                                $id_cliente_insert_pri = $id_cliente_insert;
                               } 
                            }
                            $ad_dp_i = $i - 1;
                            
                            if($adicional_verifica_add_dependente[$ad_dp_i] == 'S'){
                                
                                        $nr_colunas = $adicional_principal_contar_dependente[$ad_dp_i] - 1;
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
                                                            VALUES ('$id_ordem_pedido', '$concat_tipo_add_depen', '$concat_nome_add_depen', '$convert_data_nasc_dependente', '$id_cliente_insert_pri')";
                                                
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
                else
                {
                    $idade = calcula_idade($data_nasc[$i]);
                    
                    if($idade > 120){
                        
                        // remover os dependentes
                        $contar_chave_depen_excluir = count($array_chave_dependente) - 1;
                        for($dr=0;$dr<=$contar_chave_depen_excluir;$dr++){
                            
                            $sql_up_dep    = "UPDATE clientes SET tipo_movimento = 'EX', data_cancelamento = '".agora()."', status = '99'
                            WHERE chave = '$array_chave_dependente[$dr]'";
                            $query_up_dep  = mysql_query($sql_up_dep, $banco_produto);
                            
                        }
                        $erro_cliente = true;
                        //já existe pasta com esse nome      
                        header("Location: adicionar.php?item=$item&msg_status=adicionar_erro_idade");
                        ob_end_flush();
                    }
                    else
                    {
                        $agora = date('Y-m-d');
                        $cpf[$i] = limpa_caracteres($cpf[$i]);
                        $cep[$i] = limpa_caracteres($cep[$i]);
                        $data_nasc[$i] = converte_data($data_nasc[$i]);
                    
                        $sql_europ       = "SELECT * FROM clientes 
                                                WHERE cpf = '$cpf[$i]' AND data_nascimento = '$data_nasc[$i]' AND tipo_movimento IN ('IN', 'AL') AND id_produto = $select_grupo_produto[$p] AND (data_termino > '$agora' OR data_termino = '0000-00-00') AND (status = 99 OR status = 0)";
                                     
                        $query_europ     = mysql_query($sql_europ, $banco_produto) or die(mysql_error()." - 12");
                        
                        if(mysql_num_rows($query_europ)>0)
                        {
                            // remover os dependentes
                            $contar_chave_depen_excluir = count($array_chave_dependente) - 1;
                            for($dr=0;$dr<=$contar_chave_depen_excluir;$dr++){
                                
                                $sql_up_dep    = "UPDATE clientes SET tipo_movimento = 'EX', data_cancelamento = '".agora()."', status = '99'
                                WHERE chave = '$array_chave_dependente[$dr]'";
                                $query_up_dep  = mysql_query($sql_up_dep, $banco_produto);
                                
                            }
                            $erro_cliente = true;
                            
                            //já existe, não pode inserir   
                            header("Location: adicionar.php?item=$item&msg_status=adicionar_erro_existe");
                            ob_end_flush();
                            
                        }
                        else{
                            
                            $sql_ultimo_id       = "SELECT max(id_cliente)'ultimo_id' FROM clientes";
                            $query_ultimo_id     = mysql_query($sql_ultimo_id, $banco_produto) or die(mysql_error()." - 13");
                            
                            $ultimo_id_cliente = mysql_result($query_ultimo_id, 0, 'ultimo_id');
                            
                            $novo_id_cliente = $ultimo_id_cliente + 1;
                            $novo_id_parceiro_cpf = $id_parceiro.$cpf[$i];
                            $chave = createToken($novo_id_cliente, $novo_id_parceiro_cpf);
                            //$chave = createToken($novo_id_cliente, $id_parceiro);
                             
                            if($prazo > 0)
                            {

                                $data = somar_datas( $prazo, 'm'); // adiciona meses a sua data
                                 
                                $data_termino = date('d/m/Y', strtotime($data));
                                $data_termino = converte_data($data_termino);
                            }
                            
                            $contar_versao = count($array_versao_produto) - 1;
                            $id_cliente = array();
                            
                            $status_remessa = 6;
                            $status_pedido = "Nao_Finalizado";
                            if($metodo_pagamento == 'ON')
                            {
                                if(!isset($info_pagamento)){
                                $status_remessa = 4;
                                $status_pedido = "Nao_Finalizado";
                                }
                            }
                            
                            if(!isset($comprovante_maquina))
                            {
                                $comprovante_maquina = '';
                            }
                            
                            for($v=0;$contar_versao>=$v;$v++)
                            {
                                
                                $sql_c_europ    = "INSERT INTO clientes (id_parceiro, id_filial, id_usuario, id_cliente_principal, tipo_movimento, chave, id_produto, versao_europ, tipo_apolice, data_emissao, data_inicio, data_termino, nome, cpf, data_nascimento, sexo, estado_civil, email, telefone, celular, cep, endereco, numero, complemento, bairro, cidade, estado, prazo, status, nome_tabela)
                                        VALUES ('$id_parceiro', '$select_filial', '$select_user_pedido', 0, 'IN', '$chave', '$select_grupo_produto[$p]', '$array_versao_produto[$v]', '$tipo_apolice', '$agora', '$agora', '$data_termino', '$nome[$i]', '$cpf[$i]', '$data_nasc[$i]', '$sexo[$i]', '$estado_civil[$i]', '$email[$i]', '$telefone[$i]', '$celular[$i]', '$cep[$i]', '$endereco[$i]', '$numero[$i]', '$complemento[$i]', '$bairro[$i]', '$cidade[$i]', '$estado[$i]', '$prazo', $status_remessa, '$pasta')";
                                     
                                $query_c_europ    = mysql_query($sql_c_europ, $banco_produto) or die(mysql_error()." - 14");
                                
                                if($query_c_europ)
                                {
                                    
                                    $id_cliente[] = mysql_insert_id($banco_produto);
                                    
                                    $sql_c_venda    = "INSERT INTO vendas (id_cliente, id_ordem_pedido, tipo_pagamento, desconto, valor_entrada, dependente, valor_dependente, valor_parcela, valor_parcela_total, valor_total, parcelas, prazo, data_venda, metodo_pagamento, comprovante_maquina, status_pedido, tipo_desconto)
                                    VALUES ('$id_cliente[$v]', '$id_ordem_pedido', '$forma_pagamento', '$desconto', '$valor_entrada', 'N', '$valor_dependente', '$soma_produto_atual', '$soma_produto', '$total_geral_assistencia', '$parcela_parcelas_boleto', '$prazo', '$agora', '$metodo_pagamento', '$comprovante_maquina', '$status_pedido', '$tipo_desconto')";   
                                    $query_c_venda    = mysql_query($sql_c_venda, $banco_produto) or die(mysql_error()." - 1599");
                                    if($query_c_venda)
                                    {
                                        $cliente_ok++;
                                        $chave_principal = $chave;
                                        $array_id_venda_ordem_pedido[] = mysql_insert_id($banco_produto);
                                    }
                                    
                                }
  
                            }
                            
                            
 
                        }
  
                    }
                
                }
                
                }
                
                if($cliente_ok > 0){
                    // filiais franquias
                    if(isset($nome_dependente))
                    {
                        
                        $nr_colunas = count($nome_dependente);
                        //echo "nr_col_dep_princ:".$nr_colunas;
                        for ($i = 0; $i<$nr_colunas; $i++)
                        {
                            //echo "nome_dep_pr:".$nome_dependente[$i]."dt_nasc:".$data_nasc_dependente[$i];
                            $convert_data_nasc_dependente = converte_data($data_nasc_dependente[$i]);
                            if(!empty($nome_dependente[$i]) AND !empty($data_nasc_dependente[$i])){
                                $sql3    = "INSERT INTO dependentes_clientes (id_ordem_pedido, tipo_dependente, nome, data_nascimento, id_cliente)
                                        VALUES ('$id_ordem_pedido', '$tipo_dependente[$i]', '$nome_dependente[$i]', '$convert_data_nasc_dependente', '$id_cliente[0]')";
                                //echo "(".$sql3.")";
                                $query3  = mysql_query($sql3) or die(mysql_error());
                            }
                        }
                    }
                }
                
                $implode_array_id_venda_ordem_pedido = implode("-", $array_id_venda_ordem_pedido);
                
                $array_produto_id_venda_ordem_pedido[] = $id_base_produto."_".$implode_array_id_venda_ordem_pedido;
                
            }// fim slug europ
            elseif($produto_grupo_slug[$pg] == 'sorteio_ead' AND $erro_cliente == false)
            {
               
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
                
                if($prazo > 0)
                {                        
                    $data = somar_datas( $prazo, 'm'); // adiciona 3 meses a sua data
                     
                    $data_termino = date('d/m/Y', strtotime($data));
                    $data_termino = converte_data($data_termino);
                }
                    //echo $sorteio;
                    $sql_ck_ti            = "SELECT t.id_titulo FROM titulos t
                                        WHERE utilizado = 'N' AND data_sorteio = '$sorteio' AND id_parceiro = 4
                                        LIMIT 0,2";
                    //echo $sql_ck_ti;
                    $query_ck_ti          =  mysql_query($sql_ck_ti, $banco_produto);

                    $id_titulo1      = mysql_result($query_ck_ti, 0, 0);
                    $id_titulo2      = mysql_result($query_ck_ti, 1, 0);

                    if ($id_titulo1 > 0)
                    {
                        $dt_nascimento = converte_data($data_nasc[0]);
                        //'$id_parceiro', '$id_filial', '$id_usuario',
                        
                        if(isset($info_pagamento)){
                            $status_remessa = 'A';
                            $status_pedido = "Pago";
                        }else{
                            $status_remessa = 'C';
                            $status_pedido = "Nao_Finalizado";
                        }
                        
                        if($metodo_pagamento == 'ON')
                        {
                            if(!isset($info_pagamento)){
                                $status_remessa = 'C';
                                $status_pedido = "Nao_Finalizado";
                            }
                            
                        }
                        $venda_recorrente = 'N';
                        if($forma_pagamento == 'entrada_recorrente_cartao' OR $forma_pagamento == 'recorrente_cartao' OR $forma_pagamento == 'fidelidade' OR $forma_pagamento == 'entrada_parcelado_boleto')
                        {
                            $venda_recorrente = 'S';
                        }
                        
                        if(!isset($comprovante_maquina))
                        {
                            $comprovante_maquina = '';
                        }
                        $agora = date('Y-m-d');
                        $nome[0] = strtr(strtoupper($nome[0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $nome[0] = remove_acento($nome[0]); 
                        
                        $endereco[0] = strtr(strtoupper($endereco[0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $endereco[0] = remove_acento($endereco[0]);
                        
                        $complemento[0] = strtr(strtoupper($complemento[0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $complemento[0] = remove_acento($complemento[0]);
                        
                        $bairro[0] = strtr(strtoupper($bairro[0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $bairro[0] = remove_acento($bairro[0]);
                        
                        $cidade[0] = strtr(strtoupper($cidade[0]),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");  
                        $cidade[0] = remove_acento($cidade[0]);
                        
                        $sql_i      = "INSERT INTO vendas (id_titulo, id_parceiro, nome, cpf, cep,
                                endereco, numero, complemento, bairro, cidade, estado, telefone, email, dt_cadastro, status, dt_nascimento, id_titulo2, id_parceiro_painel, id_usuario_painel, recorrente, data_termino, id_produto, nome_tabela)
                                 VALUES ($id_titulo1, 4, '$nome[0]', '$cpf_1', '$cep_1',
                                 '$endereco[0]', '$numero[0]', '$complemento[0]', '$bairro[0]', '$cidade[0]', '$estado[0]', '$celular[0]', '$email[0]', '".agora()."', '$status_remessa', '$dt_nascimento', '$id_titulo2', '$id_parceiro', '$select_user_pedido', '$venda_recorrente', '$data_termino', '$select_grupo_produto[$p]', '$pasta')";
                        $query_i    = mysql_query($sql_i, $banco_produto) or die(mysql_error());
                        $id_venda = mysql_insert_id($banco_produto);

                        //segundo número da sorte
                        $sql_i2      = "INSERT INTO vendas (id_titulo, id_parceiro, nome, cpf, cep,
                                endereco, numero, complemento, bairro, cidade, estado, telefone, email, dt_cadastro, status, id_venda_mae, dt_nascimento, id_parceiro_painel, id_usuario_painel, recorrente, data_termino, id_produto, nome_tabela)
                                 VALUES ($id_titulo2, 4, '$nome[0]', '$cpf_1', '$cep_1',
                                 '$endereco[0]', '$numero[0]', '$complemento[0]', '$bairro[0]', '$cidade[0]', '$estado[0]', '$celular[0]', '$email[0]', '".agora()."', '$status_remessa', '$id_venda', '$dt_nascimento', '$id_parceiro', '$select_user_pedido', '$venda_recorrente', '$data_termino', '$select_grupo_produto[$p]', '$pasta')";
                        $query_i2    = mysql_query($sql_i2, $banco_produto) or die(mysql_error());
                        $id_venda_2 = mysql_insert_id($banco_produto);
                        if ($query_i)
                        {
                            $sql_up = "UPDATE titulos SET utilizado = 'S' WHERE id_titulo = $id_titulo1";
                            $query_up = mysql_query($sql_up, $banco_produto) or die("3".mysql_error());

                            $sql_up2 = "UPDATE titulos SET utilizado = 'S' WHERE id_titulo = $id_titulo2";
                            $query_up2 = mysql_query($sql_up2, $banco_produto) or die("4".mysql_error());
                            
                            $sql_up3 = "UPDATE vendas SET id_venda_mae = '$id_venda' WHERE id_venda = $id_venda";
                            $query_up3 = mysql_query($sql_up3, $banco_produto) or die("4".mysql_error());
                            
                            $sql_c_venda    = "INSERT INTO vendas_painel (id_venda, id_ordem_pedido, tipo_pagamento, desconto, valor_entrada, valor_parcela, valor_total, parcelas, prazo, data_venda, metodo_pagamento, comprovante_maquina, status_pedido, nome_tabela)
                    VALUES ('$id_venda', '$id_ordem_pedido', '$forma_pagamento', '$desconto', '$valor_entrada', '$soma_produto_atual', '$total_geral_assistencia', '$parcela_parcelas_boleto', '$prazo', '$agora', '$metodo_pagamento', '$comprovante_maquina', '$status_pedido', '$pasta')";   
                            $query_c_venda    = mysql_query($sql_c_venda, $banco_produto) or die(mysql_error()." - 1588");
                            $array_id_venda_ordem_pedido[] = mysql_insert_id($banco_produto);
                            
                            $sql_c_venda    = "INSERT INTO vendas_painel (id_venda, id_ordem_pedido, tipo_pagamento, desconto, valor_entrada, valor_parcela, valor_total, parcelas, prazo, data_venda, metodo_pagamento, comprovante_maquina, status_pedido, nome_tabela)
                    VALUES ('$id_venda_2', '$id_ordem_pedido', '$forma_pagamento', '$desconto', '$valor_entrada', '$soma_produto_atual', '$total_geral_assistencia', '$parcela_parcelas_boleto', '$prazo', '$agora', '$metodo_pagamento', '$comprovante_maquina', '$status_pedido', '$pasta')";   
                            $query_c_venda    = mysql_query($sql_c_venda, $banco_produto) or die(mysql_error()." - 1577");
                            $array_id_venda_ordem_pedido[] = mysql_insert_id($banco_produto);

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
                // erro slug produto
                header("Location: adicionar.php?item=$item&msg_status=adicionar_erro&erro=1");
                ob_end_flush();
            }
            mysql_close($banco_produto);
           }// fim for slug produto
        } // fim for grupo(s)
        
        if($cliente_ok > 0)
        {
            $implode_id_base_produto    = implode("-", $array_id_base_produto);
            $implode_slug_base_produto  = implode("-", $array_slug_base_produto);
            $implode_array_produto_id_venda_ordem_pedido = implode("|", $array_produto_id_venda_ordem_pedido);
            //echo $implode_array_produto_id_venda_ordem_pedido;
            /*$sql_ordem_pedido    = "INSERT INTO ordem_pedidos (ordem_pedido)
                    VALUES ('$implode_array_produto_id_venda_ordem_pedido')";   
            $query_ordem_pedido    = mysql_query($sql_ordem_pedido, $banco_painel) or die(mysql_error()." - 20");
            $id_ordem_pedido = mysql_insert_id($banco_painel);*/
            
            $sql_ordem_pedido    = "UPDATE ordem_pedidos SET ordem_pedido = '$implode_array_produto_id_venda_ordem_pedido'
            WHERE id_ordem_pedido = $id_ordem_pedido";
                              
            $query_c_europ    = mysql_query($sql_ordem_pedido, $banco_painel) or die(mysql_error()." - 14");

            //$expire = time() + 30 * 86400;
            $expire = $_COOKIE["usr_time"];
            setcookie("id_banco",           base64_encode($implode_id_base_produto),    $expire, "/".$pasta);
            setcookie("slug",               base64_encode($implode_slug_base_produto),    $expire, "/".$pasta);
            setcookie("cpf",                base64_encode($cpf[0]),    $expire, "/".$pasta);
            setcookie("id_ordem_pedido",    base64_encode($id_ordem_pedido),    $expire, "/".$pasta);
            //setcookie("cert",               $chave_principal,    $expire, "/");
            //setcookie("id_venda_sort_ead",  $id_venda,    $expire, "/");
            //setcookie("erro_depen",  $erro_depen,    $expire, "/");
            
            $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
                    VALUES ('$id_cliente[0]', '$id_usuario', 'clientes', 'Cadastro do cliente', '".agora()."')";   
            $query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");
            

            $sql_user   = "SELECT valor FROM opcoes
            WHERE nome = 'msg_sms_boas_vindas'";
            $query_user = mysql_query($sql_user, $banco_painel);
            $msg_sms_contato = '';                
            if (mysql_num_rows($query_user)>0)
            {
                $msg_sms_contato = mysql_result($query_user, 0);    
            }
            
            $nome_primeiro_nome = explode(" ", $nome[0]);
            $nome_primeiro_nome_ = $nome_primeiro_nome[0];

            if($sexo[0] == 'H'){
                $bem_vi = 'BEM VINDO';
            }else{
                $bem_vi = 'BEM VINDA';
            }

            $msg = $nome_primeiro_nome_." ".$bem_vi."! ".$msg_sms_contato;
            $celular_titular = $celular[0];
            $celular_titular = str_replace(" ", "", $celular_titular);
            $msg_encode = urlencode($msg);
            $url = "http://209.133.205.2/shortcode/api.ashx?action=sendsms&lgn=43984877846&pwd=16061987&msg=$msg_encode&numbers=$celular_titular";
            //error_log($url);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);

            if($content = trim(curl_exec($curl))) 
            {
                $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
            VALUES ('$id_cliente[0]', '$id_usuario', 'clientes', 'Envio de SMS/BOASVINDAS: $msg', '".agora()."')";   
            $query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");
            curl_close($curl);

            }

            if($metodo_pagamento == 'ON')
            {
                if(!isset($info_pagamento)){
                // status_boleto = 0 (ativo)
                // status_boleto = 1 (concluido)
                // status_boleto = 2 (cancelado)
                $sql_up_boletos    = "UPDATE boletos_clientes SET status_boleto = 1
                WHERE id_ordem_pedido = $id_ordem_pedido";              
                $query_up_boletos    = mysql_query($sql_up_boletos, $banco_painel) or die(mysql_error()." - 14");
                
                //$soma_produto
                //$soma_produto = str_replace(".", "", $soma_produto);
                

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
                $order->Shipping->TargetZipCode = limpa_caracteres($cep[0]);
                $order->Shipping->Address = new stdClass();
                $order->Shipping->Address->Street = $endereco[0];
                $order->Shipping->Address->Number = $numero[0];
                $order->Shipping->Address->Complement = $complemento[0];
                $order->Shipping->Address->District = $bairro[0];
                $order->Shipping->Address->City = $cidade[0];
                $order->Shipping->Address->State = $estado[0];
                
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
                $order->Customer->Identity = limpa_caracteres($cpf[0]);
                $order->Customer->FullName = $nome[0];
                $order->Customer->Email = $email[0];
                $order->Customer->Phone = limpa_caracteres($telefone[0]);
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
                
                $checkoutUrl = $json->settings->checkoutUrl;
                header("Location:$checkoutUrl");
                
                }else{
                    // redirecionar para o comprovante
                    header("Location: listar.php?item=comprovante&url_referencia=painel");
                    ob_end_flush();
                }
                
            }
            elseif($metodo_pagamento == 'BO' OR $metodo_pagamento == 'MA')
            {
                if(isset($comprovante_maquina) AND !empty($comprovante_maquina)){
                    
                   require_once ('/home/trailservicos/public_html/painel_trail/inc/class.phpmailer.php');
                   include_once('/home/trailservicos/public_html/painel_trail/inc/simple_html_dom.php');
                
                
                $img = "<a href=\"#\"><img src=\"/home/trailservicos/public_html/painel_trail/email/logo_empresa.png\" width=\"147\" height=\"46\" alt=\"painel_trail\" /></a>";
                
                $email = 'contato@realizamaissaude.com.br';
                $name = 'ADM - REALIZA SAUDE';
                //$nome = 'rafael';
                //$cpf = '055.';
                $cidade_estado = $cidade[0]." - ".$estado[0];
                $message = file_get_contents('/home/trailservicos/public_html/painel_trail/email/email_ativar_cliente.html');
                $message = str_replace('%imagem%', $img, $message);
                $message = str_replace('%nome%', $nome[0], $message);
                $message = str_replace('%cpf%', $cpf[0], $message);
                $message = str_replace('%dt_nasc%', converte_data($data_nasc[0]), $message);
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
                $mail->Subject = "ATIVAR VENDA CARTAO: ".$cpf[0].' - '.$nome[0].' (REALIZA SAUDE)';
                
                
                $mail->MsgHTML($message);
                                
                $mail->AltBody = strip_tags($message);

                if($mail->Send()) 
                {
                    
                }                                         
                    
                }else{
                    $comprovante_maquina = '';
                    $comprovante_doc = '';
                }
                // status_boleto = 0 (ativo)
                // status_boleto = 1 (concluido)
                // status_boleto = 2 (cancelado)
                $sql_up_boletos    = "UPDATE boletos_clientes SET status_boleto = 1
                WHERE id_ordem_pedido = $id_ordem_pedido";              
                $query_up_boletos    = mysql_query($sql_up_boletos, $banco_painel) or die(mysql_error()." - 14");
                
                $id_parceiro    = base64_decode($_COOKIE["usr_parceiro"]);
                if(!empty($valor_entrada) AND $valor_entrada > 0){
                    
                    $contar_mes = $i + 1;
                    $agora = date('d-m-Y');
                    //$proxima_data = date('d/m/Y', strtotime('+5 days', strtotime($agora)));
                    $proxima_data = $vencimento_entrada;
                    $data = $proxima_data;
                    $partes = explode("-", $data);
                    //$dia = $partes[0];
                    $mes_referencia = $partes[1];
                    $ano_referencia = $partes[2];
                    
                    $proxima_data = converte_data($proxima_data);
                    
                    // status_boleto = 0 (ativo)
                    // status_boleto = 1 (concluido)
                    // status_boleto = 2 (cancelado)
                    $sql_c_boleto    = "INSERT INTO boletos_clientes (id_ordem_pedido, mes_referencia, ano_referencia, entrada, parcela, total_parcelas, valor_parcela, pago, data_cadastro, data_vencimento, tipo_boleto, id_usuario, id_parceiro, comprovante_maquina, comprovante_doc, tipo_recebimento, metodo_pagamento_boleto)
                                    VALUES ('$id_ordem_pedido', '$mes_referencia', '$ano_referencia', 'S', '1', '1', '".moeda_db($valor_entrada)."', 'N', '".agora()."', '$proxima_data', '$emissao_boleto', '$select_user_pedido', '$id_parceiro', '$comprovante_maquina', '$comprovante_doc', '$tipo_recebimento_entrada', '$metodo_pagamento')";  
                                    echo $sql_c_boleto; 
                    $query_c_boleto    = mysql_query($sql_c_boleto, $banco_painel) or die(mysql_error()." - 1566");
                    
                }
                
                
                for($i=1;$i<=$parcela_parcelas_boleto;$i++){
                    
                    $contar_mes = $i - 1;
                    //$agora = date('d-m-Y');
                    if(!isset($vencimento_primeira)){
                        $vencimento_primeira = date('d-m-Y');
                    }
                    
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
                                    VALUES ('$id_ordem_pedido', '$mes_referencia', '$ano_referencia', '$i', '$parcela_parcelas_boleto', '$soma_produto', 'N', '".agora()."', '$proxima_data', '$emissao_boleto', '$select_user_pedido', '$id_parceiro', '$comprovante_maquina', '$comprovante_doc', '$metodo_pagamento')";   
                    $query_c_boleto    = mysql_query($sql_c_boleto, $banco_painel) or die(mysql_error()." - 1555");
                   
                    
                }
                
               /* 
                if($emissao_boleto == 'LOJA'){

                }elseif($emissao_boleto == 'BANCO'){
 
                }
                */
               
            }

             // redirecionar para o comprovante
             header("Location: listar.php?item=comprovante&url_referencia=painel");
             ob_end_flush();
            

        }
        else
        {
            // erro slug produto
            header("Location: adicionar.php?item=$item&msg_status=adicionar_erro&erro=2");
            ob_end_flush();

        }
        
        

    
    }// fim produto
    else{
        // erro produto
        header("Location: adicionar.php?item=$item&msg_status=adicionar_erro&erro=3");
        ob_end_flush();

    }
    
    
  
  
  
}
elseif ($item == "servicos")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
    $verifica = result("SELECT COUNT(*) FROM servicos WHERE nome LIKE '$nome'");
    
    if ($verifica>0)
    {
        //já existe pasta com eesse nome      
        header("Location: adicionar.php?item=$item&msg_status=medico_nome_data");
    } 
    else 
    {
        //adiciona registro
        $sql    = "INSERT INTO servicos (nome, valor, vigencia, max_parcelas, dt_cadastro)
                    VALUES ('$nome', '$valor', '$vigencia', '$numero', '".agora()."')";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: listar.php?item=$item&msg_status=adicionar_ok");  
        }
        else
        {
            header("Location: adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
                
    }
    
}
elseif ($item == "info_servicos")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
    $verifica = result("SELECT COUNT(*) FROM coberturas WHERE nome LIKE '$nome'");
    
    if ($verifica>0)
    {
        //já existe pasta com eesse nome      
        header("Location: adicionar.php?item=$item&msg_status=medico_nome_data");
    } 
    else 
    {
        //adiciona registro
        $sql    = "INSERT INTO coberturas (id_servico, nome, limite, dt_cadastro)
                    VALUES ('$id', '$nome', '$limite', '".agora()."')";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: listar.php?item=$item&id=$id&msg_status=adicionar_ok");  
        }
        else
        {
            header("Location: adicionar.php?item=$item&id=$id&msg_status=adicionar_erro"); 
        }
                
    }
    
}
elseif ($item == "grupos_parceiros")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
    
    $verifica = result("SELECT COUNT(*) FROM grupos_parceiros WHERE nome LIKE '$nome'");
    
    if ($verifica>0)
    {
        //já existe pasta com eesse nome      
        header("Location: adicionar.php?item=$item&msg_status=nome_existe");
    } 
    else 
    {
        $nome = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
        //adiciona registro
        $sql    = "INSERT INTO grupos_parceiros (nome)
                    VALUES ('$nome')";
                    
        $query  = mysql_query($sql);
        
        if ($query)
        {
            header("Location: listar.php?item=$item&msg_status=adicionar_ok");  
        }
        else
        {
            header("Location: adicionar.php?item=$item&msg_status=adicionar_erro"); 
        }
                
    }
    
}
elseif ($item == "usuarios")
{
    foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); }   
        
    $verifica = result("SELECT COUNT(*) FROM usuarios WHERE login LIKE '$login'");
    
    if ($verifica>0)
    {
        //já existe pasta com eesse nome      
        header("Location: adicionar.php?item=$item&msg_status=login_existe");
    } 
    else 
    {
        if (!empty($senha))
        {
            $senha = md5($senha);
            if(count($lista_permissoes) > 0){
                $lista_permissoes_array = implode("|", $lista_permissoes);
            }else{
                $lista_permissoes_array = '';
            }
            
            $nome_user = strtr(strtoupper($nome),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");

            $sql    = "INSERT INTO usuarios (id_parceiro, id_filial, nivel, nome, email, login, senha, dt_cadastro, del, lista_permissoes)
                        VALUES ('$select_parceiro_user', '$select_filial', '$nivel', '$nome_user', '$email', '$login', '$senha', '".agora()."', '$status', '$lista_permissoes_array')";
    
            
            $query  = mysql_query($sql);
            
            if($query)
            {
                header("Location: listar.php?item=$item&msg_status=adicionar_ok");  
            }
            else
            {
                header("Location: adicionar.php?item=$item&msg_status=adicionar_erro");
            }
            
        }else{
            header("Location: adicionar.php?item=$item&msg_status=adicionar_erro");
        }
        
        
        
        
        
        /*if ($query)
        {
            if($nivel_parceiro == "P")
            {
                $img = "<a href=\"#\"><img src=\"".host."img/logo_w.png\" width=\"100\" height=\"33\" alt=\"Vip Assistência\" /></a>";
    
 
            
             $sql_p        = "SELECT nome'nome_parceiro', tipo, email'email_parceiro', modalidade FROM parceiros 
                          WHERE id_parceiro = $id_parceiro";
             $query_p      = mysql_query($sql_p);
            
            
           if (mysql_num_rows($query_p)>0)
           {*/
            
             /*   $nome_parceiro      = mysql_result($query_p, 0, 0);
                $tipo               = mysql_result($query_p, 0, 1);
                $email_parceiro     = mysql_result($query_p, 0, 2);
                $modalidade         = mysql_result($query_p, 0, 3);
                
                if($tipo == "PF")
                {
                    $tipo = "Pessoa Fisica";
                }
                else
                {
                    $tipo = "Pessoa Jurídica";
                }
            }
            //busca template do email
            $message = file_get_contents('email/email_novo_usuario.html');
            $message = str_replace('%imagem%', $img, $message);
            $message = str_replace('%nome%', $nome, $message);
            $message = str_replace('%email%', $email, $message);
            $message = str_replace('%nome_parceiro%', $nome_parceiro, $message);
            $message = str_replace('%tipo%', $tipo, $message);
            $message = str_replace('%email_parceiro%', $email_parceiro, $message);
            $message = str_replace('%modalidade%', $modalidade, $message);
            
            $mail = new PHPMailer(true);
            
            $mail->IsHTML(true);*/
            //$body = eregi_replace("[\]",'',$message);
            
            //$subject = eregi_replace("[\]",'',$subject);
            //$mail->CharSet = 'utf-8';
            
            //$mail->SetFrom = $email; //email do remetente é o e-mail que está no formulário
            
            
            //SELECIONA EMAILS DINAMICAMENTE
                    
           /* $sql_email_adm = "SELECT nome, email FROM usuarios
                              WHERE nivel = 'A' AND del = 'N'";
                
            $query_email_adm = mysql_query($sql_email_adm);
        
            if(mysql_num_rows($query_email_adm) > 0)
            {
                $i=1;
                while($dados_email_adm = mysql_fetch_array($query_email_adm))
                {
                    $nome_adm        = $dados_email_adm['nome'];
                    $email_adm       = $dados_email_adm['email'];
                    //cópia
                    if ($i==1)
                    {
                        //para
                        $mail->AddAddress($email_adm, $nome_adm);
                    }
                    else
                    {
                        //cco
                        $mail->AddCC($email_adm, $nome_adm);
                    }
                    $i++;
                }
            }
            
            $mail->SetFrom = $email; 
            $mail->FromName = "Vip Assistência"; //nome que está no formulário
            $mail->From = $email;
            $mail->Subject = 'Novo Usuário';
            
            $mail->MsgHTML($message);
            $mail->AltBody = strip_tags($message);*/
            //$mail->AltBody = "Para visualizar essa mensagem, utlize um leitor de e-mails HTML."; // optional, comment out and test
        
            /*if($mail->Send()) 
            {
                header("Location: listar.php?item=$item&msg_status=adicionar_verificar_usuario");  
            }
            else
            {
                header("Location: adicionar.php?item=$item&msg_status=adicionar_erro");
            }   */     
                
       /*}
       else
       {*/
            header("Location: listar.php?item=$item&msg_status=adicionar_ok");  
       /*}       
                
    }
  }   */ 
  }  
}
else
{
    echo "test";
}
?>