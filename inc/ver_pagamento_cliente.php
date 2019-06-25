<script>

function confirma_pagamento_direto(id_pagamento, id_usuario, id_parceiro, id_cliente, valor_confirma){
{

  $("#bt_confirma_pagamento_"+id_pagamento).attr("disabled", true);
  //var id_usuario       = $("#id_usuario").val();
  //var id_parceiro      = $("#id_parceiro").val();
  //var valor_confirma   = $("#valor_confirma").val();
  //var id_cliente       = $("#id_cliente").val();
    
    
    //$(".div_aguarde_2").hide(); 
    
    $.ajax({ 
     type: "POST",
     url:  "editar_db.php",
     data: {
        item: 'confirmar_pagamentos',
        id_pagamento: id_pagamento,
        id_usuario: id_usuario,
        id_parceiro: id_parceiro,
        id_cliente: id_cliente,
        valor_confirma: valor_confirma
        },
     success: function(dados){
         if(dados == 'confirmado'){
            $('#bt_confirmar_'+id_pagamento).html('<a href="inc/ver_confirmar_pagamento.php?id_boleto='+id_pagamento+'" id="bt_confirmar_'+id_pagamento+'" data-target="#ajax" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>');
            $(".div_aguarde_2").hide();
            //$('#pg_boleto_'+id_pagamento).removeClass('hide');
            $('#ajax').hide();
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else if(dados == 'nao_confirmado'){
            valor_confirma = 'S';
            $('#bt_confirmar_'+id_pagamento).html("<a href=\"javascript:\" onclick=\"return confirma_pagamento_direto('"+id_pagamento+"','"+id_usuario+"','"+id_parceiro+"','"+id_cliente+"','"+valor_confirma+"');\" class=\"btn btn-danger\" id=\"bt_confirma_pagamento_"+id_pagamento+"\"><i class=\"fa fa-thumbs-up\"></i></a>");
            $(".div_aguarde_2").hide(); 
            //$('#pg_boleto_'+id_pagamento).addClass('hide');
            $('#ajax').hide();
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else{
            alert('erro');
         }
        $("#bt_confirma_pagamento_"+id_pagamento).removeAttr("disabled");
     } 
     });    
        
  //}
   
}};
</script>
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;

$nome_cliente       = (empty($_GET['nome'])) ? "" : verifica($_GET['nome']);  
$cpf                = (empty($_GET['cpf'])) ? "" : verifica($_GET['cpf']); 
$cod_barras         = (empty($_GET['cod_barras'])) ? "" : verifica($_GET['cod_barras']);  
$cod_aut            = (empty($_GET['cod_aut'])) ? "" : verifica($_GET['cod_aut']);  
$cod_baixa          = (empty($_GET['cod_baixa'])) ? "" : verifica($_GET['cod_baixa']);  
$historico          = (empty($_GET['historico'])) ? "" : verifica($_GET['historico']);  


$nivel_usuario          = base64_decode($_COOKIE["usr_nivel"]);
$usr_parceiro_sessao    = base64_decode($_COOKIE["usr_parceiro"]);
$usr_id                 = base64_decode($_COOKIE["usr_id"]);
$where_parceiro = '';
if($nivel_usuario == "A"){
    $sql        = "SELECT * FROM bases_produtos 
                WHERE ativo = 'S'";
                
    
}else{
    $sql        = "SELECT b_pro.id_base_produto FROM bases_produtos b_pro
JOIN produtos pro ON b_pro.id_base_produto = pro.id_base_produto
JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
WHERE pser.id_parceiro = $usr_parceiro_sessao
GROUP BY b_pro.id_base_produto";


}
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
    $id_ordem_pedido_array    = array();
    $id_parceiro_db           = array();
    $id_filial_db             = array();
    $id_cliente_db            = array();
    $versao_europ_db          = array();
    $tipo_movimento_array     = array();
    $data_termino_array       = array();
    $status_array             = array();
    $metodo_pagamento_array   = array();
    
    if(!empty($cod_barras)){
    
      $array_cod_barras = explode("-", $cod_barras);
      
      $id_cliente = $array_cod_barras[2];
      $id_boleto  = $array_cod_barras[3];
      
      if($nivel_usuario != "A"){
        $where_parceiro = ' AND bcli.id_parceiro = '.$usr_parceiro_sessao;
      }
      
      $whele_cod_barras = "AND id_boleto = ".$id_boleto;  
      $sql        = "SELECT bcli.*, op.ordem_pedido FROM boletos_clientes bcli
                    JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                    WHERE bcli.id_boleto = $id_boleto $where_parceiro";

        $query      = mysql_query($sql, $banco_painel);
                        
        if (mysql_num_rows($query)>0)
        {
            $dados = mysql_fetch_array($query);
            extract($dados); 
            $id_ordem_pedido_array[] = $id_ordem_pedido;  
        }
        
        
        $array_id_base_ids_vendas = explode("|", $ordem_pedido);
            
        $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
        for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
        {
            $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
            $id_base = $array_ids_base_vendas[0];
            $ids_vendas = explode("-", $array_ids_base_vendas[1]);
            
            if($id_base == $id_base_produto){
                
                $sql_venda  = "SELECT v.metodo_pagamento, c.id_cliente, c.id_parceiro, c.id_filial, c.nome FROM vendas v
                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                    WHERE v.id_venda = $ids_vendas[0]";
                $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                
                if (mysql_num_rows($query_venda)>0)
                {
                    $metodo_pagamento_array[]   = mysql_result($query_venda, 0, 'metodo_pagamento');
                    $id_cliente_db              = mysql_result($query_venda, 0, 'id_cliente');
                    $nome_cliente_db_array[]    = mysql_result($query_venda, 0, 'nome');
                    $id_parceiro_db[]           = mysql_result($query_venda, 0, 'id_parceiro');
                    $id_filial_db[]             = mysql_result($query_venda, 0, 'id_filial');

                }
                
            }
        }
        
        if($id_cliente_db == $id_cliente){
            $valida_dados = true;
        }
        
        
        
    }else{
       
       if($nivel_usuario != "A"){
        $where_parceiro = ' AND c.id_parceiro = '.$usr_parceiro_sessao;
       }
       
       if(!empty($nome_cliente)){
        
            
            
            $sql_venda  = "SELECT c.id_cliente, c.id_parceiro, c.id_filial, c.tipo_movimento, c.versao_europ, c.data_termino, c.nome, c.status, v.id_ordem_pedido, v.metodo_pagamento FROM vendas v
                            JOIN clientes c ON v.id_cliente = c.id_cliente
                            WHERE c.nome LIKE '%$nome_cliente%' $where_parceiro
                            GROUP BY v.id_ordem_pedido";
                            //echo $sql_venda;
            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
            
            if (mysql_num_rows($query_venda)>0)
            {
                
                $i_clientes = 0;
                $valida_dados = true;
                while($dados_clientes = mysql_fetch_array($query_venda))
                {
                   extract($dados_clientes);
                   
                   $id_cliente_db[]         = $id_cliente; 
                   $id_parceiro_db[]        = $id_parceiro;
                   $id_filial_db[]          = $id_filial;
                   $nome_cliente_db_array[] = $nome;
                   $versao_europ_db[]       =  $versao_europ;
                   $id_ordem_pedido_array[] = $id_ordem_pedido;
                   $tipo_movimento_array[]  = $tipo_movimento;
                   $data_termino_array[]    = $data_termino;
                   $status_array[]          = $status;
                   $array_clientes = true;
                   $metodo_pagamento_array[]= $metodo_pagamento;
                   $i_clientes++;
                }
                
            }else{
                $id_ordem_pedido_array[]    = 0;
            }
        
                
        }elseif(!empty($cpf)){
            
            
            
            $sql_venda  = "SELECT c.id_cliente, c.id_parceiro, c.id_filial, c.tipo_movimento, c.versao_europ, c.data_termino, c.nome, c.status, v.id_ordem_pedido, v.metodo_pagamento FROM vendas v
                            JOIN clientes c ON v.id_cliente = c.id_cliente
                            WHERE c.cpf LIKE '%$cpf%' $where_parceiro
                            GROUP BY v.id_ordem_pedido";
                            //echo $sql_venda;
            $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
            
            if (mysql_num_rows($query_venda)>0)
            {
                
                $i_clientes = 0;
                $valida_dados = true;
                while($dados_clientes = mysql_fetch_array($query_venda))
                {
                   extract($dados_clientes);
                   
                   $id_cliente_db[]         = $id_cliente;
                   $id_parceiro_db[]        = $id_parceiro;
                   $id_filial_db[]          = $id_filial;
                   $nome_cliente_db_array[] = $nome;
                   $versao_europ_db[]       = $versao_europ; 
                   $id_ordem_pedido_array[] = $id_ordem_pedido;
                   $tipo_movimento_array[]  = $tipo_movimento;
                   $data_termino_array[]    = $data_termino;
                   $status_array[]          = $status;
                   $array_clientes = true;
                   $metodo_pagamento_array[]= $metodo_pagamento;
                   $i_clientes++;
                }
                
            }else{
                $id_ordem_pedido_array[]    = 0;
            }
            
            
        }elseif(!empty($cod_aut)){
              if($nivel_usuario != "A"){
                $where_parceiro = ' AND bcli.id_parceiro = '.$usr_parceiro_sessao;
              }
              
              $whele_cod_barras = "AND comprovante_maquina = '$cod_aut'";  
              $sql        = "SELECT bcli.*, op.ordem_pedido FROM boletos_clientes bcli
                            JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                            WHERE bcli.comprovante_maquina = '$cod_aut' $where_parceiro";
        
                $query      = mysql_query($sql, $banco_painel);
                                
                if (mysql_num_rows($query)>0)
                {
                    $dados = mysql_fetch_array($query);
                    extract($dados); 
                    $id_ordem_pedido_array[] = $id_ordem_pedido;  
                }
                
                
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                    
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
                {
                    $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                    $id_base = $array_ids_base_vendas[0];
                    $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                    
                    if($id_base == $id_base_produto){
                        
                        $sql_venda  = "SELECT v.metodo_pagamento, c.id_cliente, c.id_parceiro, c.id_filial, c.tipo_movimento, c.versao_europ, c.data_termino, c.nome, c.status FROM vendas v
                                            JOIN clientes c ON v.id_cliente = c.id_cliente
                                            WHERE v.id_venda = $ids_vendas[0]";
                        $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                        
                        if (mysql_num_rows($query_venda)>0)
                        {
                            $id_cliente_db[]            = mysql_result($query_venda, 0, 'id_cliente');
                            $nome_cliente_db_array[]    = mysql_result($query_venda, 0, 'nome');
                            $id_parceiro_db[]           = mysql_result($query_venda, 0, 'id_parceiro');
                            $id_filial_db[]             = mysql_result($query_venda, 0, 'id_filial');
                            $versao_europ_db[]          = mysql_result($query_venda, 0, 'versao_europ');
                            $tipo_movimento_array[]     = mysql_result($query_venda, 0, 'tipo_movimento');
                            $status_array[]             = mysql_result($query_venda, 0, 'status');
                            $data_termino_array[]       = mysql_result($query_venda, 0, 'data_termino');
                            $metodo_pagamento_array[]   = mysql_result($query_venda, 0, 'metodo_pagamento');
                        }
                        $valida_dados = true;
                    }
                }
                
                
        
            
        }elseif(!empty($cod_baixa)){
            
            if($nivel_usuario != "A"){
                $where_parceiro = ' AND bcli.id_parceiro = '.$usr_parceiro_sessao;
              }
              
              $whele_cod_barras = "AND id_boleto = '$cod_baixa'";  
              $sql        = "SELECT bcli.*, op.ordem_pedido FROM boletos_clientes bcli
                            JOIN ordem_pedidos op ON bcli.id_ordem_pedido = op.id_ordem_pedido
                            WHERE bcli.id_boleto = '$cod_baixa' $where_parceiro";
        
                $query      = mysql_query($sql, $banco_painel);
                                
                if (mysql_num_rows($query)>0)
                {
                    $dados = mysql_fetch_array($query);
                    extract($dados); 
                    $id_ordem_pedido_array[] = $id_ordem_pedido;
                    
                }
                
                
                $array_id_base_ids_vendas = explode("|", $ordem_pedido);
                    
                $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
                for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
                {
                    $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                    $id_base = $array_ids_base_vendas[0];
                    $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                    
                    if($id_base == $id_base_produto){
                        
                        $sql_venda  = "SELECT v.metodo_pagamento, c.id_cliente, c.id_parceiro, c.id_filial, c.tipo_movimento, c.versao_europ, c.data_termino, c.nome, c.status FROM vendas v
                                            JOIN clientes c ON v.id_cliente = c.id_cliente
                                            WHERE v.id_venda = $ids_vendas[0]";
                        $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                        
                        if (mysql_num_rows($query_venda)>0)
                        {
                            $id_cliente_db[]            = mysql_result($query_venda, 0, 'id_cliente');
                            $nome_cliente_db_array[]    = mysql_result($query_venda, 0, 'nome');
                            $id_parceiro_db[]           = mysql_result($query_venda, 0, 'id_parceiro');
                            $id_filial_db[]             = mysql_result($query_venda, 0, 'id_filial');
                            $versao_europ_db[]          = mysql_result($query_venda, 0, 'versao_europ');
                            $tipo_movimento_array[]     = mysql_result($query_venda, 0, 'tipo_movimento');
                            $data_termino_array[]       = mysql_result($query_venda, 0, 'data_termino');
                            $status_array[]             = mysql_result($query_venda, 0, 'status');
                            $metodo_pagamento_array[]   = mysql_result($query_venda, 0, 'metodo_pagamento');
                        }
                        $valida_dados = true;
                    }
                }
                
                
                    
                
        
            
        }
        
           
        
        /*$sql        = "SELECT * FROM clientes
                    WHERE id_cliente = $id_cliente_db";
        $query      = mysql_query($sql, $banco_produto);
                        
        if (mysql_num_rows($query)>0)
        {
            $dados = mysql_fetch_array($query);
            extract($dados);   
        }*/
        
    }
    
    
    if($valida_dados == true){
    
        if($array_clientes == true){
            $contar_array = $i_clientes - 1;
        }else{
            $contar_array = 0;
        }
        
        if($historico == 'S'){
            $where_historico = "status_boleto IN(0,1)";
        }else{
            $where_historico = "status_boleto = 0";
        }
        
        for($i=0;$i<=$contar_array;$i++){
            
            if($tipo_movimento_array[$i] == 'EX' AND $historico == 'S'){
                $where_historico = "status_boleto IN(1,2)";
            }
    
        $sql        = "SELECT id_boleto, entrada, parcela, total_parcelas, valor_parcela, pago, data_vencimento, valor_recebido, tipo_boleto, status_boleto, tipo_recebimento, baixa_recebimento FROM boletos_clientes
                       WHERE id_ordem_pedido = $id_ordem_pedido_array[$i] AND $where_historico $whele_cod_barras";
                       //echo $sql;
                      //AND 
                    $query      = mysql_query($sql, $banco_painel);
                                
                    if (mysql_num_rows($query)>0)
                    {

                        $sql_parceiro        = "SELECT nome'nome_parceiro' FROM parceiros
                                                WHERE id_parceiro = $id_parceiro_db[$i]";
                        $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
                        $nome_parceiro = '';   
                        if (mysql_num_rows($query_parceiro)>0)
                        {
                            $nome_parceiro        = mysql_result($query_parceiro, 0,'nome_parceiro');
                        }
    
                        $sql_filial        = "SELECT nome'nome_filial' FROM filiais
                             WHERE id_filial = $id_filial_db[$i]";
                        $query_filial      = mysql_query($sql_filial, $banco_painel);
                        $nome_filial = '';   
                        if (mysql_num_rows($query_filial)>0)
                        {
                            $nome_filial        = mysql_result($query_filial, 0,'nome_filial');
                        }
                        
                        
                        $sql_ver_cli    = "SELECT id_produto'id_produto_cliente' FROM produtos 
                                            WHERE versao_produto = '".$versao_europ_db[$i]."'";
                        $query_ver_cli  = mysql_query($sql_ver_cli, $banco_painel);
                        $id_produto_cliente = '';            
                        if (mysql_num_rows($query_ver_cli)>0)
                        {
                            $id_produto_cliente = mysql_result($query_ver_cli, 0,'id_produto_cliente');
                        }
                        
                        $agora 			= date("Y-m-d");
                        $status_list = array(
                            array("success" => "Ativo"),
                            array("danger" => "Inativo")
                        );
                        
                        if((strtotime($data_termino_array[$i]) > strtotime($agora) OR $data_termino_array[$i] == '0000-00-00') AND $tipo_movimento_array[$i] <> 'EX' AND ($status_array[$i] == 99 OR $status_array[$i] == 0))
                        {
                            $status_nome = $status_list[0];
                        }
                        elseif((strtotime($data_termino_array[$i]) == strtotime($agora) OR $data_termino_array[$i] == '0000-00-00') AND $tipo_movimento_array[$i] <> 'EX' AND ($status_array[$i] == 99 OR $status_array[$i] == 0))
                        {
                            $status_nome = $status_list[0];
                        }
                        else
                        {
                            $status_nome = $status_list[1];
                        }
                        
                        
                        echo " <div class=\"portlet-body\">
        <div class=\"table-scrollable\">
            <table class=\"table table-hover\">
                <thead>
                    <tr>
                        <th width='5%'> # </th>
                        <th width='25%'> Cliente </th>
                        <th> Status </th>
                        <th width='15%'> Parceiro </th>
                        <th width='5%'> Parc. </th>
                        <th width='10%'> Tipo </th>
                        <th width='10%'> Valor Par. </th>
                        <th width='10%'> Valor Rec. </th>
                        <th width='5%'> Venc. </th>
                        <th width='20%'> Pago </th>
                    </tr>
                </thead>
                <tbody>";   
                        while($dados = mysql_fetch_array($query))
                        {
                           extract($dados);
                            
                            
                           /* $status_list_confirma = array(
                                array("info" => "Confirmar"),
                                array("danger" => "Cancelar")
                            );
                            $html_bt_confirma = '';
                            if($nivel_usuario == 'A' AND in_array("41", $verifica_lista_permissoes_array_inc) AND $pago == 'S'){
                                if($baixa_recebimento == 'S'){
                                    $status_conf = $status_list_confirma[1];
                                }
                                else
                                {
                                    $status_conf = $status_list_confirma[0];
                                }
                                
                                $html_bt_confirma = '<a href="inc/ver_confirmar_pagamento.php?id_boleto='.$id_boleto.'" id="bt_confirmar_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-'.(key($status_conf)).' btn-block btn-sm" style="margin-top: 5px;">'.(current($status_conf)).'</a>';
                            }*/
                            
                            /*$status_list = array(
                                array("green" => '<i class="fa fa-check"></i>'),
                                array("red" => "Receber")
                            );*/
                            
                            $html_exibe_bt_confirma = '';
                            if($nivel_usuario == 'A' AND in_array("41", $verifica_lista_permissoes_array_inc)){
                                
                                if($baixa_recebimento == 'N'){
                                    $html_texto_bt_confirma = "<i class=\"fa fa-thumbs-up\"></i>";
                                    $html_class_cor = "btn-danger";
                                    $valor_confirma = 'S';
                                    //$html_bt_confirmacao = "Cancelar Confirmação";
                                    //$hml_cor_bt = 'red';    
                                    $html_exibe_bt_confirma = "<span id=\"bt_confirmar_$id_boleto\"><a href=\"javascript:\" onclick=\"return confirma_pagamento_direto('$id_boleto','$usr_id','$usr_parceiro','$id_cliente','$valor_confirma');\" class=\"btn $html_class_cor\" id=\"bt_confirma_pagamento_$id_boleto\">$html_texto_bt_confirma</a></span>";
                                }else{
                                    $html_texto_bt_confirma = "<i class=\"fa fa-check\"></i>";
                                    $html_class_cor = "btn-success";
                                    $valor_confirma = 'N';
                                    //$html_bt_confirmacao = "Confirmar Pagamento";
                                    //$hml_cor_bt = 'blue'; 
                                     $html_exibe_bt_confirma = '<span id="bt_confirmar_'.$id_boleto.'"><a href="inc/ver_confirmar_pagamento.php?id_boleto='.$id_boleto.'" id="bt_confirmar_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a></span>';
                                }
                                
                              
        
                            
                                
                            }
                            
                            $status_list = array(
                                array("green" => "<i class=\"fa fa-money\"></i>"),
                                array("red" => "Receber")
                            );
                            
                            
                            
                            if($pago == 'N')
                            {
                                $status = $status_list[1];
                                $html_exibe_bt_confirma = '';
                            }
                            else
                            {
                                $status = $status_list[0];
                            }
                            $html_tipo = $tipo_recebimento;
                            if($entrada == 'S'){
                                $html_tipo = "ENTRADA".$tipo_recebimento;
                            }
                            $pg_html_exibe = 'hide';
                            if($pago == 'S'){
                                $pg_html_exibe = '';
                            }
                            
                            if($metodo_pagamento_array[$i] == 'BO'){
                                $html_metodo_pagamento = 'BOLETO';
                            }elseif($metodo_pagamento_array[$i] == 'MA'){
                                $html_metodo_pagamento = 'MAQUINA';
                            }elseif($metodo_pagamento_array[$i] = 'ON'){
                                $html_metodo_pagamento = 'ON-LINE';
                            }
                            
                            $class_tr = '';
                            if($status_boleto > 0)
                            {
                                $class_tr = 'class="danger"';
                            }
                            
                            echo '<tr '.$class_tr.'>
                                <td> '.$id_boleto.' </td>
                                <td> <a href="inc/ver_cliente.php?id_cliente='.$id_cliente_db[$i].'&id_produto='.$id_produto_cliente.'&tipo=produto&status='.(current($status_nome)).'" data-target="#ajax" data-toggle="modal">'.$nome_cliente_db_array[$i].'</a> </td>
                                <td> <span class="label label-sm label-'.(key($status_nome)).'">('.$tipo_movimento_array[$i].') '.(current($status_nome)).'</span> </td>
                                <td> '.$nome_parceiro.' '.$nome_filial.' </td>
                                <td> '.$parcela.' /'.$total_parcelas.' </td>
                                <td> '.$html_tipo.' '.$html_metodo_pagamento.' </td>
                                <td> '.db_moeda($valor_parcela).' </td>
                                <td> '.db_moeda($valor_recebido).' </td>
                                <td> '.converte_data($data_vencimento).' </td>';
                            if($nivel_usuario == "A" OR ($nivel_usuario == "P" AND $metodo_pagamento == "BO" AND ($tipo_boleto == 'LOJA' OR $entrada == 'S') OR $pago == 'S' OR in_array("4", $verifica_lista_permissoes_array_inc))){
                                
                                echo '<td> <a href="inc/ver_recebimento_cliente.php?id_boleto='.$id_boleto.'" id="bt_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm '.(key($status)).'">'.(current($status)).'</a> <a href="inc/ver_comprovante_cliente.php?id_boleto='.$id_boleto.'" id="pg_boleto_'.$id_boleto.'" data-target="#ajax" data-toggle="modal" class="btn btn-sm purple '.$pg_html_exibe.'"><i class="fa fa-print"></i></a>';
                                
                                //echo "<a href=\"inc/ver_cliente.php?id_cliente=".$id_cliente_db[$i]."&id_produto=$id_produto_cliente&tipo=produto&status=".(current($status_nome))."\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn btn-sm btn-outline grey-salsa\"><i class=\"fa fa-search\"></i></a>";
                                echo $html_exibe_bt_confirma;
                                
                                echo '</td>';
                            }else{
                                echo '<td> &nbsp; </td>';
                            }  
                            
                                
                            echo '</tr>';
                            
                        }
                        
                        echo "</tbody>
                            </table>
                        </div>";
                    
                    echo "</div>";
                        
                    }
                    
                }

            }else{
                echo "Dados Inválidos. Digite novamente";
            }
}
    }
}
?>
