<script>
$(document).ready(function () {

    function exportTableToCSV($table, filename) {

        var $rows = $table.find('tr:has(td)'),

            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character

            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',

            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');

                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();

                    return text.replace(/"/g, '""'); // escape double quotes

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"';

				// Deliberate 'false', see comment below
        if (false && window.navigator.msSaveBlob) {

						var blob = new Blob([decodeURIComponent(csv)], {
	              type: 'text/csv;charset=utf8'
            });
            
            // Crashes in IE 10, IE 11 and Microsoft Edge
            // See MS Edge Issue #10396033: https://goo.gl/AEiSjJ
            // Hence, the deliberate 'false'
            // This is here just for completeness
            // Remove the 'false' at your own risk
            window.navigator.msSaveBlob(blob, filename);
            
        } else if (window.Blob && window.URL) {
						// HTML5 Blob        
            var blob = new Blob([csv], { type: 'text/csv;charset=utf8' });
            var csvUrl = URL.createObjectURL(blob);

            $(this)
            		.attr({
                		'download': filename,
                		'href': csvUrl
		            });
				} else {
            // Data URI
            var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

						$(this)
                .attr({
               		  'download': filename,
                    'href': csvData,
                    'target': '_blank'
            		});
        }
    }

    // This must be a hyperlink
    $("#bt_export_xls").on('click', function (event) {
        // CSV
        var args = [$('#conteudo_bt_export_xls>table'), 'export.csv'];
        
        exportTableToCSV.apply(this, args);
        
        // If CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });
});

</script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 

ini_set("max_execution_time", 300);
set_time_limit(300);

//tamanho de arquivo
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');

//exibir erro e log
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$banco_painel = $link;


$nivel_usuario          = base64_decode($_COOKIE["usr_nivel"]);
$usr_parceiro_sessao    = base64_decode($_COOKIE["usr_parceiro"]);
$id_usuario_s           = base64_decode($_COOKIE["usr_id"]);
$where_parceiro = '';

$sql        = "SELECT id_base_produto FROM bases_produtos 
                WHERE ativo = 'S'";  
$query      = mysql_query($sql, $banco_painel);

if (mysql_num_rows($query)>0)
{
    while($dados = mysql_fetch_array($query)){
        extract($dados);  

        // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
        $sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                    JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                    WHERE bpro.id_base_produto = $id_base_produto";
        
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
        }
        $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);

        if($slug == 'europ')
        {
            $valida_dados = false;
            $whele_cod_barras = '';
            $array_clientes = false;
            
            $nome_cliente_db_array    = array();
            

//foreach ($_POST as $campo => $valor) {  $$campo = verifica($valor); } 

//$arquivo            = (empty($_POST['arquivo_retorno'])) ? "" : verifica($_POST['arquivo_retorno']);  
//$tipo_retorno       = (empty($_POST['tipo_retorno'])) ? "" : verifica($_POST['tipo_retorno']);

/*$fileName = $_FILES['arquivo_retorno']['name'];
$fileType = $_FILES['arquivo_retorno']['type'];
$fileError = $_FILES['arquivo_retorno']['error'];
$fileContent = file_get_contents($_FILES['arquivo_retorno']['tmp_name']);
*/
$horario = date('His');
    
    $nome_arquivo_sem_extencao = pathinfo($_FILES['arquivo_retorno']['name'], PATHINFO_FILENAME);
    $extensao_arquivo = $extensao = pathinfo($_FILES['arquivo_retorno']['name'], PATHINFO_EXTENSION);  


    $uploaddir      = '../arquivos/retorno/boleto/';
    $uploadfile     = $uploaddir . $nome_arquivo_sem_extencao."_".$horario.".".$extensao_arquivo;
    $nome_arquivo   = $nome_arquivo_sem_extencao."_".$horario.".".$extensao_arquivo;


    if (move_uploaded_file($_FILES['arquivo_retorno']['tmp_name'], $uploaddir . $nome_arquivo))
    {
        //upload com sucesso
        //verifica o número de títulos
        $arquivo = file($uploadfile);
        $nr_linhas = count($arquivo);

        $ult_linha = (substr($arquivo[$nr_linhas-1], 0,1) == 'Z') ? $nr_linhas-1 : $nr_linhas-2;

        $num_titulos = intval(substr($arquivo[$ult_linha],1,6));
        
        $data_arquivo        = substr($arquivo[0],65,8);
        $data_arquivo = mask_total($data_arquivo, '####-##-##');
        $numero_sequencial   = substr($arquivo[0],73,6);
        
        echo "<strong>Informações do arquivo</strong> <br/>";
        echo "Data do arquivo: ".$data_arquivo."<br/>";
        echo "Número sequencial (nsa): <strong>".$numero_sequencial."</strong><br/>
        <a href=\"javascript:;\" class=\"btn btn-sm blue\" id=\"bt_export_xls\"> <i class=\"fa fa-upload\"></i> Exportar para Excel </a>";

        
        if (substr($arquivo[0],0,2) != 'A2')
        {
            echo "<b>ATENÇÃO:</b> este nao é um arquivo de retorno válido.<br>Favor verificar!";
            //exit();
        }else{
               
               
                $sql_contar        = "SELECT COUNT(*) FROM arquivos_boletos 
                            WHERE sequencia = '$numero_sequencial' AND processado = 'S'";
        
                $query_contar      = mysql_query($sql_contar, $banco_painel);
                $validar_arquivo_sequencia = '';                
                if (mysql_num_rows($query_contar)>0)
                {
                    $validar_arquivo_sequencia = mysql_result($query_contar, 0,0); 
                }

               if($validar_arquivo_sequencia == 0){

                $sql_a      = "INSERT INTO arquivos_boletos (nome_arquivo, sequencia, data_arquivo, data_cadastro, processado, registro_total)
                                                VALUES ('$nome_arquivo', '$numero_sequencial', '$data_arquivo', '".agora()."', 'N', 0)";
                $query_a    = mysql_query($sql_a, $banco_painel);
                $id_arquivo = mysql_insert_id($banco_painel);
                $contar_processados = 0;
                $contar_erros = 0;
                $contar_recusados = 0;
                //processa e gera as chaves
                echo " <div class=\"portlet-body\">
                        <div class=\"table-scrollable\" id=\"conteudo_bt_export_xls\">
                            <table class=\"table table-hover\">
                                <thead>
                                    <tr>
                                        <td> <strong>#</strong> </td>
                                        <td> <strong>Cliente</strong> </td>
                                        <td> <strong>Parceiro</strong> </td>
                                        <td> <strong>Parcelas</strong> </td>
                                        <td> <strong>Valor Recebido</strong> </td>
                                        <td> <strong>Data Pagamento</strong> </td>
                                        <td> <strong>Vencimento</strong> </td>
                                        <td> <strong>Pago</strong> </td>
                                        <td> <strong>Status</strong> </td>
                                    </tr>
                                </thead>
                                <tbody>";   
                for ($i = 1; $i<= $ult_linha-1; $i++)
                {
                    // verifica linha
                    $cod_registro          = substr($arquivo[$i], 0,1);
                    //$id_ordem_pedido_array    = array();
                    if($cod_registro == 'G'){
                        
                        $data_pagamento      = substr($arquivo[$i], 21,8);
                        $r_data_pagamento    = mask_total($data_pagamento, '####-##-##');
                        $codigo_barras       = substr($arquivo[$i], 37,44);
                        $id_boleto           = ltrim(substr($codigo_barras, 31, 7), '0');
                        $valor_recebido      = ltrim(substr($arquivo[$i], 81,12), '0');
                        $contar_len_valor    = strlen($valor_recebido) -2;
                        $r_valor_recebido      = substr($valor_recebido, 0, $contar_len_valor).".".substr($valor_recebido, -2);  
                        $numero_autenticacao = substr($arquivo[$i], 117,23);
                        //$tipo_transacao      = substr($arquivo[$i], 149,1); //0 = Efetivada, 2 = Estornada
                        $tipo_transacao      = 0;
                        
                        
                        
                        
                        $whele_cod_barras = "AND id_boleto = ".$id_boleto;  
                        $sql        = "SELECT bcli.*, op.ordem_pedido, op.status_recorrencia, op.enviar_info FROM boletos_clientes bcli
                                    JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                                    WHERE bcli.id_boleto = $id_boleto";
                        //echo '---'.$sql.'---';
                        $query      = mysql_query($sql, $banco_painel);
                                        
                        if (mysql_num_rows($query)>0)
                        {
                            $dados = mysql_fetch_array($query);
                            extract($dados); 
                            $id_ordem_pedido_array = $id_ordem_pedido;  
                        }
                        
                        $sql_opcoes  = "SELECT valor FROM opcoes
                        WHERE nome = 'porcento_multa_vencimento_boleto' OR nome = 'porcento_valor_diario_vencimento_boleto' OR nome = 'dias_nao_receber_atraso_boleto' OR nome = 'valor_desconto_pagamento_em_dia' ";
                        $query_opcoes = mysql_query($sql_opcoes, $banco_painel) or die(mysql_error()." - 2");
                        
                        if (mysql_num_rows($query_opcoes)>0)
                        {
                            $porcento_multa_vencimento_boleto          = mysql_result($query_opcoes, 0,0);
                            $porcento_valor_diario_vencimento_boleto   = mysql_result($query_opcoes, 1,0);
                            $dias_nao_receber_atraso_boleto            = mysql_result($query_opcoes, 2, 0);
                            $valor_desconto_pagamento_em_dia           = mysql_result($query_opcoes, 3, 0);
                        }
                        
                        $valor_parcela = $valor_parcela - $valor_desconto_pagamento_em_dia;
                        if($r_valor_recebido >= $valor_parcela){
                            if($tipo_transacao == 0){
                            $r_pago = 'S';
                            }else{
                                $r_pago = 'N';
                            }
                            
                            $novo_status_boleto = $status_boleto;
                            if($status_recorrencia == 'S' AND $enviar_info == 'S' AND $novo_status_boleto = '2'){
                                $novo_status_boleto = 0;
                            }
                            $hora_pagamento_salvar = date("H:i:s");
                            
                            $sql_u      = "UPDATE boletos_clientes SET pago = '$r_pago', data_pagamento = '$r_data_pagamento', valor_recebido = '$r_valor_recebido', comprovante = '$numero_autenticacao', id_usuario_recebimento = 138, id_parceiro_recebimento = 17, status_boleto = '$novo_status_boleto', tipo_recebimento = 'BO', baixa_recebimento = '$r_pago', usuario_baixa = '138', data_baixa = '$r_data_pagamento', hora_pagamento = '$hora_pagamento_salvar'
                            WHERE id_boleto = $id_boleto";
                            $query_u    = mysql_query($sql_u, $banco_painel);
                            
                            
                                
                            
                            if($status_boleto == 0){
                                $contar_processados++;
                            }else{
                                $contar_recusados++;
                            }
                            
                        }else{
                            $r_pago = 'N';
                            $contar_recusados++;
                        }
                        
                        
      
                        /*$whele_cod_barras = "AND id_boleto = ".$id_boleto;  
                        $sql        = "SELECT bcli.*, op.ordem_pedido FROM boletos_clientes bcli
                                    JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                                    WHERE bcli.id_boleto = $id_boleto";
                
                        $query      = mysql_query($sql, $banco_painel);
                                        
                        if (mysql_num_rows($query)>0)
                        {
                            $dados = mysql_fetch_array($query);
                            extract($dados); 
                            $id_ordem_pedido_array[] = $id_ordem_pedido;  
                        }*/
        
        
                        $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                            
                        $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                        for($ib=0;$contar_array_id_base_ids_vendas>=$ib;$ib++)
                        {
                            $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$ib]);
                            $id_base = $array_ids_base_vendas[0];
                            $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                            
                            if($id_base == $id_base_produto){
                                
                                $sql_venda  = "SELECT c.id_cliente, c.id_parceiro, c.id_filial, c.tipo_movimento, c.data_termino, c.nome, c.status FROM vendas v
                                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                                    WHERE v.id_venda = $ids_vendas[0]";
                                $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                                
                                if (mysql_num_rows($query_venda)>0)
                                {
                                    $id_cliente_db          = mysql_result($query_venda, 0, 'id_cliente');
                                    $id_parceiro_db         = mysql_result($query_venda, 0, 'id_parceiro');
                                    $id_filial_db           = mysql_result($query_venda, 0, 'id_filial');
                                    $nome_cliente_db_array  = mysql_result($query_venda, 0, 'nome');
                                    $tipo_movimento_array   = mysql_result($query_venda, 0, 'tipo_movimento');
                                    $data_termino_array     = mysql_result($query_venda, 0, 'data_termino');
                                    $status_array           = mysql_result($query_venda, 0, 'status');
                                    
                                    
                                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                                            WHERE id_parceiro = $id_parceiro_db";
                                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                                    $nome_parceiro = '';   
                                    if (mysql_num_rows($query_parceiro)>0)
                                    {
                                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                                        $nome_parceiro = remove_acento($nome_parceiro);
                                        
                                    }

                                    $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                                         WHERE id_filial = $id_filial_db";
                                    $query_filial      = mysql_query($sql_filial, $banco_painel);
                                    $nome_filial = '';   
                                    if (mysql_num_rows($query_filial)>0)
                                    {
                                        $nome_filial        = mysql_result($query_filial, 0,'nome_filial');
                                        $nome_filial = remove_acento($nome_filial);
                                    }
                                    
                                    $agora 			= date("Y-m-d");
                                    $status_list_status = array(
                                        array("success" => "Ativo"),
                                        array("danger" => "Inativo")
                                    );
                                    
                                    if((strtotime($data_termino_array) > strtotime($agora) OR $data_termino_array == '0000-00-00') AND $tipo_movimento_array <> 'EX' AND ($status_array == 99 OR $status_array == 0))
                                    {
                                        $status_nome = $status_list_status[0];
                                    }
                                    elseif((strtotime($data_termino_array) == strtotime($agora) OR $data_termino_array == '0000-00-00') AND $tipo_movimento_array <> 'EX' AND ($status_array == 99 OR $status_array == 0))
                                    {
                                        $status_nome = $status_list_status[0];
                                    }
                                    else
                                    {
                                        $status_nome = $status_list_status[1];
                                    }
                    
                                    $contar_array = $ult_linha-2;
                                    //for($ic=0;$ic<=$contar_array;$ic++){
                                    //echo 'contar_linha:'.$contar_array;
                                    $sql = "SELECT id_boleto, entrada, parcela, total_parcelas, valor_parcela, pago, data_vencimento FROM boletos_clientes
                                       WHERE id_ordem_pedido = $id_ordem_pedido_array $whele_cod_barras";
                                    //echo $sql;
                                    $query      = mysql_query($sql, $banco_painel);
                                                
                                    if (mysql_num_rows($query)>0)
                                    {
                                        
                                        //echo "if01";
                                        $dados = mysql_fetch_array($query);
                                        //while()
                                        //{
                                           extract($dados);
                                            //echo "while01";
                                            $status_list = array(
                                                array("green" => "Pago"),
                                                array("red" => "Receber")
                                            );
                                            $class_tr = '';
                                            if($r_pago == 'N' OR $status_boleto > 0)
                                            {
                                                $status = $status_list[0];
                                                $class_tr = 'class="danger"';
                                            }
                                            else
                                            {
                                                $status = $status_list[0];
                                            }
                                            /*$html_tipo = '';
                                            if($entrada == 'S'){
                                                $html_tipo = "ENTRADA";
                                            }*/
                                            
                                            $pg_html_exibe = 'hide';
                                            if($r_pago == 'S'){
                                                $pg_html_exibe = '';
                                            }
                                            
                                            echo '<tr '.$class_tr.'>
                                                <td> '.$id_boleto.' </td>
                                                <td> '.$nome_cliente_db_array.' </td>
                                                <td> '.$nome_parceiro.' '.$nome_filial.' </td>
                                                <td> '.$parcela.' /'.$total_parcelas.' </td>
                                                <td> '.db_moeda($r_valor_recebido).' </td>
                                                <td> '.converte_data($r_data_pagamento).' </td>
                                                <td> '.converte_data($data_vencimento).' </td>';
                                            //if($metodo_pagamento == 'BO'){
                                                echo '<td> <a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a> </td>';
                                           /* }else{
                                                 echo '<td> &nbsp; </td>';
                                            }  */
                                            echo '<td> <span class="label label-sm label-'.(key($status_nome)).'">('.$tipo_movimento_array.') '.(current($status_nome)).'</span> </td>';
                                                
                                            echo '</tr>';
                                            
                                        //}
                                        if($r_pago == 'S'){
                                
                                            $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
                                        VALUES ('$id_cliente_db', '138', 'clientes', 'Alteracao pagamento id_pagamento = $id_boleto (PAGO) BANCO', '".agora()."')";   
                                            $query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");
                                            
                                        }else{
                                            
                                            $sql_historico    = "INSERT INTO historicos_atividades (id_referencia, id_usuario, tipo_historico, descricao, data_alteracao)
                                        VALUES ('$id_cliente_db', '138', 'clientes', 'Alteracao pagamento id_pagamento = $id_boleto', '".agora()."')";   
                                            $query_historico    = mysql_query($sql_historico, $banco_painel) or die(mysql_error()." - 998");
                                            
                                        }    
                                        
                                        
                                    }
                                            }
                                
                            }
                        }
                                //}
                        
                    }else{
                        $contar_erros++;
                    }
                }
                
                echo "</tbody>
                        </table>
                    </div>";
                    $num_titulos = $num_titulos - 2;
                echo "Total de registros: ".$num_titulos." / Processados: ".$contar_processados." / Erros: ".$contar_erros." / Títulos recusados: ".$contar_recusados;
                echo "</div>";
                
                if($contar_processados > 0){
                    
                    $sql_u_a      = "UPDATE arquivos_boletos SET processado = 'S', registro_total = $num_titulos, registro_processado = $contar_processados, registro_erro = $contar_erros, registro_recusado = $contar_recusados
                    WHERE id_arquivo = $id_arquivo";
                    $query_u_a    = mysql_query($sql_u_a, $banco_painel);    
                    
                    
                    $sql_a      = "INSERT INTO controle_retorno (nsa, data_cadastro, data_retorno, data_credito, total_titulos, titulos_emitidos, titulos_erros, titulos_recusados, emitido, id_usuario, nome_arquivo, banco)
                                                VALUES ('$numero_sequencial', '".agora()."', '$data_arquivo', '".agora()."', '$num_titulos', '$contar_processados', '$contar_erros', '$contar_recusados', 'S', '$id_usuario_s', '$nome_arquivo', 'arrecadacao')";
                    $query_a    = mysql_query($sql_a, $banco_painel);
                }

            }else{
                echo "Erro ao processar o arquivo. Arquivo já processado. Selecione a próxima sequência";
                
                
                
                
              /*$sql_a      = "INSERT INTO arquivos_boletos (nome_arquivo, sequencia, data_arquivo, data_cadastro, processado, registro_total)
                                                VALUES ('$nome_arquivo', '$numero_sequencial', '$data_arquivo', '".agora()."', 'N', 0)";
                $query_a    = mysql_query($sql_a, $banco_painel);
                $id_arquivo = mysql_insert_id($banco_painel);*/
                $contar_processados = 0;
                $contar_erros = 0;
                $contar_recusados = 0;
                //processa e gera as chaves
                echo " <div class=\"portlet-body\">
                        <div class=\"table-scrollable\" id=\"conteudo_bt_export_xls\">
                            <table class=\"table table-hover\">
                                <thead>
                                    <tr>
                                        <td> <strong>#</strong> </td>
                                        <td> <strong>Cliente</strong> </td>
                                        <td> <strong>Parceiro</strong> </td>
                                        <td> <strong>Parcelas</strong> </td>
                                        <td> <strong>Valor Recebido</strong> </td>
                                        <td> <strong>Data Pagamento</strong> </td>
                                        <td> <strong>Vencimento</strong> </td>
                                        <td> <strong>Pago</strong> </td>
                                        <td> <strong>Status</strong> </td>
                                    </tr>
                                </thead>
                                <tbody>";   
                for ($i = 1; $i<= $ult_linha-1; $i++)
                {
                    // verifica linha
                    $cod_registro          = substr($arquivo[$i], 0,1);
                    //$id_ordem_pedido_array    = array();
                    if($cod_registro == 'G'){
                        
                        $data_pagamento      = substr($arquivo[$i], 21,8);
                        $r_data_pagamento    = mask_total($data_pagamento, '####-##-##');
                        $codigo_barras       = substr($arquivo[$i], 37,44);
                        $id_boleto           = ltrim(substr($codigo_barras, 31, 7), '0');
                        $valor_recebido      = ltrim(substr($arquivo[$i], 81,12), '0');
                        $contar_len_valor    = strlen($valor_recebido) -2;
                        $r_valor_recebido      = substr($valor_recebido, 0, $contar_len_valor).".".substr($valor_recebido, -2);  
                        $numero_autenticacao = substr($arquivo[$i], 117,23);
                        //$tipo_transacao      = substr($arquivo[$i], 149,1); //0 = Efetivada, 2 = Estornada
                        $tipo_transacao      = 0;
                        
                        
                        
                        
                        $whele_cod_barras = "AND id_boleto = ".$id_boleto;  
                        $sql        = "SELECT bcli.*, op.ordem_pedido FROM boletos_clientes bcli
                                    JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                                    WHERE bcli.id_boleto = $id_boleto";
                        //echo '---'.$sql.'---';
                        $query      = mysql_query($sql, $banco_painel);
                                        
                        if (mysql_num_rows($query)>0)
                        {
                            $dados = mysql_fetch_array($query);
                            extract($dados); 
                            $id_ordem_pedido_array = $id_ordem_pedido;  
                        }
                        
                        $sql_opcoes  = "SELECT valor FROM opcoes
                        WHERE nome = 'porcento_multa_vencimento_boleto' OR nome = 'porcento_valor_diario_vencimento_boleto' OR nome = 'dias_nao_receber_atraso_boleto' OR nome = 'valor_desconto_pagamento_em_dia' ";
                        $query_opcoes = mysql_query($sql_opcoes, $banco_painel) or die(mysql_error()." - 2");
                        
                        if (mysql_num_rows($query_opcoes)>0)
                        {
                            $porcento_multa_vencimento_boleto          = mysql_result($query_opcoes, 0,0);
                            $porcento_valor_diario_vencimento_boleto   = mysql_result($query_opcoes, 1,0);
                            $dias_nao_receber_atraso_boleto            = mysql_result($query_opcoes, 2, 0);
                            $valor_desconto_pagamento_em_dia           = mysql_result($query_opcoes, 3, 0);
                        }
                        
                        $valor_parcela = $valor_parcela - $valor_desconto_pagamento_em_dia;
                        if($r_valor_recebido >= $valor_parcela){
                            if($tipo_transacao == 0){
                            $r_pago = 'S';
                            }else{
                                $r_pago = 'N';
                            }
                            /*$sql_u      = "UPDATE boletos_clientes SET pago = '$r_pago', data_pagamento = '$r_data_pagamento', valor_recebido = '$r_valor_recebido', comprovante = '$numero_autenticacao', id_usuario_recebimento = 138, id_parceiro_recebimento = 17, status_boleto = $tipo_transacao, tipo_recebimento = 'BO'
                            WHERE id_boleto = $id_boleto";
                            $query_u    = mysql_query($sql_u, $banco_painel);*/
                            
                            if($status_boleto == 0){
                                $contar_processados++;
                            }else{
                                $contar_recusados++;
                            }
                            
                        }else{
                            $r_pago = 'N';
                            $contar_recusados++;
                        }
                        
                        
      
                        /*$whele_cod_barras = "AND id_boleto = ".$id_boleto;  
                        $sql        = "SELECT bcli.*, op.ordem_pedido FROM boletos_clientes bcli
                                    JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                                    WHERE bcli.id_boleto = $id_boleto";
                
                        $query      = mysql_query($sql, $banco_painel);
                                        
                        if (mysql_num_rows($query)>0)
                        {
                            $dados = mysql_fetch_array($query);
                            extract($dados); 
                            $id_ordem_pedido_array[] = $id_ordem_pedido;  
                        }*/
        
        
                        $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                            
                        $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                        for($ib=0;$contar_array_id_base_ids_vendas>=$ib;$ib++)
                        {
                            $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$ib]);
                            $id_base = $array_ids_base_vendas[0];
                            $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                            
                            if($id_base == $id_base_produto){
                                
                                $sql_venda  = "SELECT c.id_cliente, c.id_parceiro, c.id_filial, c.tipo_movimento, c.data_termino, c.nome, c.status FROM vendas v
                                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                                    WHERE v.id_venda = $ids_vendas[0]";
                                $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                                
                                if (mysql_num_rows($query_venda)>0)
                                {
                                    $id_cliente_db          = mysql_result($query_venda, 0, 'id_cliente');
                                    $id_parceiro_db         = mysql_result($query_venda, 0, 'id_parceiro');
                                    $id_filial_db           = mysql_result($query_venda, 0, 'id_filial');
                                    $nome_cliente_db_array  = mysql_result($query_venda, 0, 'nome');
                                    $tipo_movimento_array   = mysql_result($query_venda, 0, 'tipo_movimento');
                                    $data_termino_array     = mysql_result($query_venda, 0, 'data_termino');
                                    $status_array           = mysql_result($query_venda, 0, 'status');
                                    
                                    $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                                            WHERE id_parceiro = $id_parceiro_db";
                                    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                                    $nome_parceiro = '';   
                                    if (mysql_num_rows($query_parceiro)>0)
                                    {
                                        $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                                        $nome_parceiro = remove_acento($nome_parceiro);
                                    }

                                    $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                                         WHERE id_filial = $id_filial_db";
                                    $query_filial      = mysql_query($sql_filial, $banco_painel);
                                    $nome_filial = '';   
                                    if (mysql_num_rows($query_filial)>0)
                                    {
                                        $nome_filial        = mysql_result($query_filial, 0,'nome_filial');
                                        $nome_filial = remove_acento($nome_filial);
                                    }
                        
                                    $agora 			= date("Y-m-d");
                                    $status_list_status = array(
                                        array("success" => "Ativo"),
                                        array("danger" => "Inativo")
                                    );
                                    
                                    if((strtotime($data_termino_array) > strtotime($agora) OR $data_termino_array == '0000-00-00') AND $tipo_movimento_array <> 'EX' AND ($status_array == 99 OR $status_array == 0))
                                    {
                                        $status_nome = $status_list_status[0];
                                    }
                                    elseif((strtotime($data_termino_array) == strtotime($agora) OR $data_termino_array == '0000-00-00') AND $tipo_movimento_array <> 'EX' AND ($status_array == 99 OR $status_array == 0))
                                    {
                                        $status_nome = $status_list_status[0];
                                    }
                                    else
                                    {
                                        $status_nome = $status_list_status[1];
                                    }
    
                    
                                    $contar_array = $ult_linha-2;
                                    //for($ic=0;$ic<=$contar_array;$ic++){
                                    //echo 'contar_linha:'.$contar_array;
                                    $sql  = "SELECT id_boleto, entrada, parcela, total_parcelas, valor_parcela, pago, data_vencimento FROM boletos_clientes
                                       WHERE id_ordem_pedido = $id_ordem_pedido_array $whele_cod_barras";
                                    //echo $sql;
                                    $query      = mysql_query($sql, $banco_painel);
                                                
                                    if (mysql_num_rows($query)>0)
                                    {
                                        
                                        //echo "if01";
                                        $dados = mysql_fetch_array($query);
                                        //while()
                                        //{
                                           extract($dados);
                                            //echo "while01";
                                            $status_list = array(
                                                array("green" => "Pago"),
                                                array("red" => "Receber")
                                            );
                                            $class_tr = '';
                                            if($r_pago == 'N' OR $status_boleto > 0)
                                            {
                                                $status = $status_list[0];
                                                $class_tr = 'class="danger"';
                                            }
                                            else
                                            {
                                                $status = $status_list[0];
                                            }
                                            /*$html_tipo = '';
                                            if($entrada == 'S'){
                                                $html_tipo = "ENTRADA";
                                            }*/
                                            
                                            $pg_html_exibe = 'hide';
                                            if($r_pago == 'S'){
                                                $pg_html_exibe = '';
                                            }
                                            
                                            echo '<tr '.$class_tr.'>
                                                <td> '.$id_boleto.' </td>
                                                <td> '.$nome_cliente_db_array.' </td>
                                                <td> '.$nome_parceiro.' '.$nome_filial.' </td>
                                                <td> '.$parcela.' /'.$total_parcelas.' </td>
                                                <td> '.db_moeda($r_valor_recebido).' </td>
                                                <td> '.converte_data($r_data_pagamento).' </td>
                                                <td> '.converte_data($data_vencimento).' </td>';
                                            //if($metodo_pagamento == 'BO'){
                                                echo '<td> <a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-barcode"></i></a> </td>';
                                           /* }else{
                                                 echo '<td> &nbsp; </td>';
                                            }  */
                                            echo '<td> <span class="label label-sm label-'.(key($status_nome)).'">('.$tipo_movimento_array.') '.(current($status_nome)).'</span> </td>';
                                                
                                            echo '</tr>';
                                            
                                        //}
                                        
                                        
                                        
                                    }
                                            }
                                
                            }
                        }
                                //}
                        
                    }else{
                        $contar_erros++;
                    }
                }
                
                echo "</tbody>
                        </table>
                    </div>";
                    $num_titulos = $num_titulos - 2;
                echo "Total de registros: ".$num_titulos." / Processados: ".$contar_processados." / Erros: ".$contar_erros." / Títulos recusados: ".$contar_recusados;
                echo "</div>";
                
               /* if($contar_processados > 0){
                    
                    $sql_u_a      = "UPDATE arquivos_boletos SET processado = 'S', registro_total = $num_titulos, registro_processado = $contar_processados, registro_erro = $contar_erros, registro_recusado = $contar_recusados
                    WHERE id_arquivo = $id_arquivo";
                    $query_u_a    = mysql_query($sql_u_a, $banco_painel);                    
                }  */
                
            }
   
        }

    }
    else
    {
        if ($_FILES["arquivo_retorno"]["error"] > 0)
        {
            echo "Error: " . $_FILES["arquivo_retorno"]["error"] . "<br>";
        }
        echo "Erro ao processar requisição. Tentar novamente.";
    }






      
}
    }
}

?>
