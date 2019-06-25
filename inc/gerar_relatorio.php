

        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script>
<style>
.dt-buttons{
    position: absolute;
    right: 0;
    top: -90px;
}
#sample_1_filter, #sample_1_info{
    display: none;
}
.table-scrollable{
    overflow: visible;
}

@media print {
    .btn-outline {display: none; }
}
</style>
<?php

require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$periodo                = (empty($_GET['periodo'])) ? "" : verifica($_GET['periodo']);  
$tipo                   = (empty($_GET['tipo'])) ? "" : verifica($_GET['tipo']);  
$grupo                  = (empty($_GET['grupo'])) ? "" : verifica($_GET['grupo']);  
$parceiro               = (empty($_GET['parceiro'])) ? "" : verifica($_GET['parceiro']);  
$filial                 = (empty($_GET['filial'])) ? "" : verifica($_GET['filial']);  
$usuario_vendedor       = (empty($_GET['usuario_vendedor'])) ? "" : verifica($_GET['usuario_vendedor']);
$grupo_produto          = (empty($_GET['grupo_produto'])) ? "" : verifica($_GET['grupo_produto']);  
$produto                = (empty($_GET['produto'])) ? "" : verifica($_GET['produto']);  
$todos_clientes_ativos  = (empty($_GET['todos_clientes_ativos'])) ? "" : verifica($_GET['todos_clientes_ativos']);
$exibir_adicionais      = (empty($_GET['exibir_adicionais'])) ? "" : verifica($_GET['exibir_adicionais']);
$apenas_ativos          = (empty($_GET['apenas_ativos'])) ? "" : verifica($_GET['apenas_ativos']);  

// converter string periodo em data
// exemplo: 06 Janeiro, 2016 - 04 Fevereiro, 2016

$datas = explode(" - ", $periodo);

$data1 = explode(" ", $datas[0]);
$dia_data1 = $data1[0];
$mes_data1 = substr($data1[1], 0, -1);
$ano_data1 = $data1[2];

if($mes_data1 == "Janeiro"){
$mes_num_data1 = "01";
}elseif($mes_data1 == "Fevereiro"){
$mes_num_data1 = "02";
}elseif($mes_data1 == "Marco"){
$mes_num_data1 = "03";
}elseif($mes_data1 == "Abril"){
$mes_num_data1 = "04";
}elseif($mes_data1 == "Maio"){
$mes_num_data1 = "05";
}elseif($mes_data1 == "Junho"){
$mes_num_data1 = "06";
}elseif($mes_data1 == "Julho"){
$mes_num_data1 = "07";
}elseif($mes_data1 == "Agosto"){
$mes_num_data1 = "08";
}elseif($mes_data1 == "Setembro"){
$mes_num_data1 = "09";
}elseif($mes_data1 == "Outubro"){
$mes_num_data1 = "10";
}elseif($mes_data1 == "Novembro"){
$mes_num_data1 = "11";
}else{
$mes_num_data1 = "12";
}

$data1 = $ano_data1.'/'.$mes_num_data1.'/'.$dia_data1;
$data1_convert = str_replace("/", "-", $data1);

$data2 = explode(" ", $datas[1]);
$dia_data2 = $data2[0];
$mes_data2 = substr($data2[1], 0, -1);
$ano_data2 = $data2[2];

if($mes_data2 == "Janeiro"){
$mes_num_data2 = "01";
}elseif($mes_data2 == "Fevereiro"){
$mes_num_data2 = "02";
}elseif($mes_data2 == "Marco"){
$mes_num_data2 = "03";
}elseif($mes_data2 == "Abril"){
$mes_num_data2 = "04";
}elseif($mes_data2 == "Maio"){
$mes_num_data2 = "05";
}elseif($mes_data2 == "Junho"){
$mes_num_data2 = "06";
}elseif($mes_data2 == "Julho"){
$mes_num_data2 = "07";
}elseif($mes_data2 == "Agosto"){
$mes_num_data2 = "08";
}elseif($mes_data2 == "Setembro"){
$mes_num_data2 = "09";
}elseif($mes_data2 == "Outubro"){
$mes_num_data2 = "10";
}elseif($mes_data2 == "Novembro"){
$mes_num_data2 = "11";
}else{
$mes_num_data2 = "12";
}

$data2 = $ano_data2.'/'.$mes_num_data2.'/'.$dia_data2;
$data2_convert = str_replace("/", "-", $data2);

if($produto == '' OR $produto == 'todos')
{
    // seleciona o id do produto pelo id do grupo
    
    if($grupo_produto == 'todos'){
        $sel_grupo_produto = '';
    }else{
        $sel_grupo_produto = "WHERE prog.id_grupo_produto = $grupo_produto";
    }

    
    $sql_base   = "SELECT bpro.id_base_produto, gpro.nome FROM produtos_grupos prog
    JOIN produtos pro ON prog.id_produto = pro.id_produto
    JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
	JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
    $sel_grupo_produto
    GROUP BY bpro.id_base_produto";
    $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
    
    if (mysql_num_rows($query_base)>0)
    {
        //$id_base_produto            = mysql_result($query_base, 0, 'id_base_produto');
        $id_base_produto              = 3;
        $nome_grupo_produto            = mysql_result($query_base, 0, 'nome');
        $sql_base_select = "WHERE bpro.id_base_produto = $id_base_produto";
        
    }
    
    $produto = '';
}
else
{
    $sql_base_select = "WHERE pro.id_produto = $produto";
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
    
}
$banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);   



?>

<script>

function gerar_faturamento(id_parceiro, periodo, grupo_produtos, id_servico, quantidade, id_produto, mes_referencia, dependentes, data1, data2, id_produto_plano, grupo_plano, todos_clientes_ativos, id_filial){
{   
    $(".div_aguarde").show();
    $.get('inc/calculo_faturamento.php?id_parceiro='+id_parceiro+'&periodo='+periodo+'&grupo_produtos='+grupo_produtos+'&quantidade='+quantidade+'&id_produto='+id_produto+'&id_servico='+id_servico+'&mes_referencia='+mes_referencia+'&dependentes='+dependentes+'&data1='+data1+'&data2='+data2+'&id_produto_plano='+id_produto_plano+'&grupo_plano='+grupo_plano+'&todos_clientes_ativos='+todos_clientes_ativos+'&id_filial='+id_filial,function (dados) { $("#resultado_calculo_faturamento").html(dados); $(".div_aguarde").hide();});

}};

</script>

<?php

// tipo de relatorio
if($tipo == 'vendas')
{
    // tipo de base de dados
    if($slug == 'europ'){
        
        // selecionar o parceiro
        if($grupo == 'todos'){
            // seleciona venda de todos os parceiros
            
            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND gpro.id_grupo_produto = $grupo_produto";
            }

            $sql_base   = "SELECT par.id_parceiro FROM parceiros par
            JOIN parceiros_servicos pser ON par.id_parceiro = pser.id_parceiro
            JOIN produtos pro ON pser.id_produto = pro.id_produto
            JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
            JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
            WHERE pro.ativo = 'S' $sel_grupo_produto
            $sql_sel_produto";
            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 3");
            
                      
            if (mysql_num_rows($query_base)>0)
            {
                //$ids_parceiros = mysql_fetch_array($query_base, MYSQL_BOTH);
                $array_ids_parceiros = array();
                while($dados_ids_parceiros = mysql_fetch_array($query_base))
                {
                    $array_ids_parceiros[] = $dados_ids_parceiros['id_parceiro'];
                }
            }
            
            
            
            $grupo = '';
            $verifica_sql_sel_filial = 'false';
        }else{
           // id do gripo e parceiro está setado
           
           // verifica se esta setado o id do parceiro
           if($parceiro == '' OR $parceiro == 'todos'){
                // id do parceiro nao está setado, seleciona todos os parceiros do grupo x
                $sel_id_par = false;
                $verifica_sql_sel_filial = 'false';
                
           }else{

                $sql_img_parceiro   = "SELECT logo, logo_proposta FROM parceiros 
                WHERE id_parceiro = $parceiro";
                $query_img_parceiro = mysql_query($sql_img_parceiro, $banco_painel) or die(mysql_error()." - 789");
                
                $img_logo_parceiro = '';
                $img_logo_parceiro_proposta = '';          
                if (mysql_num_rows($query_img_parceiro)>0)
                {
                    $img_logo_parceiro = mysql_result($query_img_parceiro, 0,0);
                    $img_logo_parceiro_proposta = mysql_result($query_img_parceiro, 0,1);
                }
                // id do parceiro está setado, seleciona os clientes do parceiro x
                $sel_id_par = true;
                $array_ids_parceiros = 1;
                $sql_sel_filial = "";
                $verifica_sql_sel_filial = 'false';
                // verifica se está setado o id da filial
                if($filial <> '' AND $filial <> 'undefined' AND $filial <> 'todos'){
                    // está setado, seleciona clienta apenas da filial x
                    $sql_sel_filial = "AND (id_filial = $filial OR id_filial_integracao = $filial)";
                    $verifica_sql_sel_filial = $filial;
                }else{
                    if($filial == ''){
                        $sql_sel_filial = "AND (id_filial = 0 OR id_filial_integracao = 0)";
                    }
                }
                
           }
           
        }
        
        
        // seleciona o 
        if($produto == '' OR $produto == 'todos'){
        $sql_sel_produto = '';
        $produto = '';
        
                if($grupo_produto == 'todos'){
                    $sel_grupo_produto = '';
                }else{
                    $sel_grupo_produto = "AND prog.id_grupo_produto = $grupo_produto";
                }

                $sql_produtos        = "SELECT pro.nome FROM produtos pro
                JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
                JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                WHERE pro.ativo = 'S' $sel_grupo_produto";
                $query_produtos      = mysql_query($sql_produtos, $banco_painel) or die(mysql_error()." - 2.3");
                            
                if (mysql_num_rows($query_produtos)>0)
                {
                    $array_nomes_produtos = array();
                    while($dados_nomes_produtos = mysql_fetch_array($query_produtos))
                    {
                        $array_nomes_produtos[] = $dados_nomes_produtos['nome'];
                    }
                    $versao_produto_ver = false;
                    $nome_dos_produtos = implode('<br/>', $array_nomes_produtos);
                    
                }
        
        
        }else{
            $sql_sel_produto = "AND pro.id_produto = $produto"; 
            
            $sql_produtos        = "SELECT nome, versao_produto FROM produtos 
                    WHERE id_produto = $produto";
                    $query_produtos      = mysql_query($sql_produtos, $banco_painel) or die(mysql_error()." - 2.4");
                                
                    if (mysql_num_rows($query_produtos)>0)
                    {
                        $nome_dos_produtos    = mysql_result($query_produtos, 0, 'nome');
                        $versao_produto_europ = mysql_result($query_produtos, 0, 'versao_produto');
                        
                        $versao_produto_ver = true;
                    } 
        }
?>
<div class="invoice">
                                <div class="row invoice-logo">
                                    <div class="col-xs-3 invoice-logo-space">
                                    <img src="assets/pages/img/logos/<?php echo $img_logo_parceiro_proposta; ?>" class="img-responsive" alt=""/> </div>
                                    <div class="col-xs-5">
                                       <a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();"> Imprimir
                                            <i class="fa fa-print"></i>
                                        </a>
                                    </div>
                                    <div class="col-xs-4 invoice-logo-space">
                                        <img src="assets/pages/img/logos/<?php echo $img_logo_parceiro; ?>" class="img-responsive" alt=""/> </div>
                                </div>
                                <hr/>
                                <div class="row">
                                   
                                    <div class="col-xs-12 invoice-payment">
                                        <h3>Info:</h3>
                                         <p> Data do relatório: <?php echo date('d-m-Y H:i'); ?> <br />
                                            <span class="muted"> Lista de <?php echo $tipo; ?> por período: <br /> <?php 
                                            if($todos_clientes_ativos == 'S'){
                                                echo "Todos ativos!";
                                            }else{
                                                echo $periodo;
                                            }
                                             ?> </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <!--<table class="table table-condensed table-hover">-->
                                        <table class="table table-condensed table-hover" id="sample_1">
<?php

        
        
        if($grupo == '')
        {
            $sql_grupo = '';
        }else{
            $sql_grupo = "AND parg.id_grupo_parceiro = $grupo";
        }
        
        if($sel_id_par == false)
        {
            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND gpro.id_grupo_produto = $grupo_produto";
            }
                       
            $sql_base   = "SELECT par.id_parceiro FROM parceiros par
            JOIN parceiros_servicos pser ON par.id_parceiro = pser.id_parceiro
            JOIN produtos pro ON pser.id_produto = pro.id_produto
            JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
            JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
            JOIN parceiros_grupos parg ON pser.id_parceiro = parg.id_parceiro
            WHERE pro.ativo = 'S' $sel_grupo_produto 
            $sql_sel_produto $sql_grupo 
            GROUP BY par.id_parceiro";
            //echo $sql_base;
            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 4");
            
            if (mysql_num_rows($query_base)>0)
            {
                //$ids_parceiros = mysql_fetch_array($query_base, MYSQL_BOTH);
                $array_ids_parceiros = array();
                while($dados_ids_parceiros = mysql_fetch_array($query_base))
                {
                    $array_ids_parceiros[] = $dados_ids_parceiros['id_parceiro'];
                }
            }
            
        }
        
        
       $contar = count($array_ids_parceiros) - 1;
       //echo $contar;
       $contar_ativos       = 0;
       $contar_cancelados   = 0;
       $contar_pendente = 0;
       $contar_dependentes = 0;
                if($verifica_sql_sel_filial == 'false'){
                
                //echo $nome_parceiro;
                $linha_filial = false;
                echo "<thead>
                    <tr>
                        <th> ID </th>
                        <th> Parceiro </th>
                        <th> Filial </th>

                        <th> Titular </th>
                        <th> Cancel.</th>
                    </tr>
                </thead>
                <tbody>";
                
            }else{
                
                $linha_filial = true;
                echo "<thead>
                    <tr>
                        <th> ID </th>
                        <th> Parceiro </th>
                        <th> &nbsp; </th>
                        <th> Filial </th>

                        <th> Títular </th>
                        <th> Cancel.</th>
                    </tr>
                </thead>
                <tbody>";
            }
      
      $total_soma_vendas = 0;
      $total_soma_ativos = 0;
      $total_soma_cancelados = 0;
      $total_soma_adicionais = 0;
      $new_prazos = array();
      $prazo_atual = null;
       for($i=0;$contar>=$i;$i++)
       {
          $contar_ativos = 0;
          $contar_cancelados = 0;
          $contar_pendente = 0;
          $contar_dependentes = 0;
          if($sel_id_par == false)
          {
            $id_parceiro = $array_ids_parceiros[$i];
          }
          else
          {
            $id_parceiro = $parceiro;
          }
          
            if($linha_filial == false)
            {
                $sql_par_sel   = "SELECT nome FROM parceiros
                WHERE id_parceiro = $id_parceiro";
                $query_par_sel = mysql_query($sql_par_sel, $banco_painel) or die(mysql_error()." - 5");
                
                $nome_parceiro = mysql_result($query_par_sel, 0, 'nome');
            
            }
            else
            {
                $sql_par_sel   = "SELECT par.nome'nome_parceiro', fi.nome'nome_filial' FROM parceiros par
                JOIN filiais fi ON par.id_parceiro = fi.id_parceiro
                WHERE par.id_parceiro = $id_parceiro AND (fi.id_filial = $filial OR fi.id_filial_integracao)";
                $query_par_sel = mysql_query($sql_par_sel, $banco_painel) or die(mysql_error()." - 6");
                $nome_parceiro = mysql_result($query_par_sel, 0, 'nome_parceiro');
                $nome_filial = mysql_result($query_par_sel, 0, 'nome_filial');
            }
            $verifica_sql_versao_produto = 'false';
            if($versao_produto_ver == true){
                $sql_versao_produto = "AND versao_europ = $versao_produto_europ";
                $verifica_sql_versao_produto = $versao_produto_europ;
            }else{
                $sql_versao_produto = '';
            }
            $sql_data_emissao = "(data_emissao BETWEEN '$data1' AND '$data2')";
            $agora 			= date("Y-m-d");
            if($todos_clientes_ativos == 'S'){
                $sql_data_emissao = "data_emissao <= '$agora'";
            }
            
            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND id_produto = $grupo_produto";
            } 
            
            $sql_lista_usuario_vendedor = "";
            if($usuario_vendedor != 'todos' AND $usuario_vendedor != 'undefined'){
                $sql_lista_usuario_vendedor = "AND id_usuario = $usuario_vendedor";
            }

            $sql_clientes_ativos = "SELECT id_cliente_principal, data_termino, prazo, status FROM clientes
            WHERE $sql_data_emissao 
            AND id_parceiro = $id_parceiro $sql_sel_filial $sql_lista_usuario_vendedor $sel_grupo_produto AND tipo_movimento IN ('IN', 'AL') $sql_versao_produto GROUP BY chave ORDER BY prazo ASC";
            //echo "s".$sql_clientes_ativos;
            $query_clientes_ativos      = mysql_query($sql_clientes_ativos, $banco_produto) or die(mysql_error()." - 7");
                      
            if (mysql_num_rows($query_clientes_ativos)>0)
            { 
                
                
                //$new_prazos = array();
                while($dados_clientes_ativos = mysql_fetch_array($query_clientes_ativos))
                {
                    extract($dados_clientes_ativos);
                    
                    //$data_termino    = converte_data_barra($data_termino);
                    
                    // status
                    // Comparando as Datas
                    //$convert_data_termino = converte_data($data_termino);
                    $agora 			= date("Y-m-d");
                    
                    if((strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0 OR $status == 3 OR $status == 6))
                    {
                        if($status == 3)
                        {
                            $pendente = true;
                            $contar_pendente++;
                        }
                        else{
                            
                            if($id_cliente_principal > 0){
                                if($status == 99 OR $status == 0 OR $status == 6)
                                {
                                    $contar_dependentes++;
                                }
                            }else{
                                if($status == 99 OR $status == 0 OR $status == 6)
                                {
                                    $contar_ativos++;
                                }
                            }
                            if($status == 99 OR $status == 0 OR $status == 6)
                            {
                            
                                if($prazo_atual == $prazo){
                                   $contar_prazo++;
                                   $new_prazos[$prazo] = $contar_prazo;
                                }else{
                                    
                                    $contar_new_prazos = count($new_prazos);
                                    if($contar_new_prazos == 0){
                                        $contar_prazo = 1;
                                        $new_prazos = array($prazo => $contar_prazo);
                                    }else{
                                        $valor_atual_contar_prazo = $new_prazos[$prazo];
                                        $new_prazos[$prazo] = $valor_atual_contar_prazo;
                                        $contar_prazo = $valor_atual_contar_prazo;
                                        $contar_prazo++;
                                        //$new_prazos = array_merge($new_prazos, array($prazo => $contar_prazo));
                                    }
                                }
                                
                                $prazo_atual = $prazo;
                            }
                            
                            
                        }
                        
                    }
                    elseif(strtotime($data_termino) == strtotime($agora) AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0 OR $status == 3 OR $status == 6))
                    {
                        if($status == 3)
                        {
                            $pendente = true;
                            $contar_pendente++;
                        }
                        else{
                            if($id_cliente_principal > 0){
                                if($status == 99 OR $status == 0 OR $status == 6)
                                {
                                    $contar_dependentes++;
                                }
                            }else{
                                if($status == 99 OR $status == 0 OR $status == 6)
                                {
                                    $contar_ativos++;
                                }
                            }
                            if($status == 99 OR $status == 0 OR $status == 6)
                            {
                                if($prazo_atual == $prazo){
                                   $contar_prazo++;
                                   $new_prazos[$prazo] = $contar_prazo;
                                }else{
                                    $contar_prazo = 1;
                                    $contar_new_prazos = count($new_prazos);
                                    if($contar_new_prazos == 0){
                                        $new_prazos = array($prazo => $contar_prazo);
                                    }else{
                                       
                                        $new_prazos[$prazo] = $contar_prazo;
                                        //$new_prazos = array_merge($new_prazos, array($prazo => $contar_prazo));
                                    }
                                }
                                $prazo_atual = $prazo;
                            }
                        }
                    }
                    else
                    {
                        $contar_cancelados++;
                    }
                    
                    
                }
            }
            
            $sql_clientes_cancelados = "SELECT id_cliente FROM clientes
            WHERE $sql_data_emissao
            AND id_parceiro = $id_parceiro $sql_sel_filial AND tipo_movimento = 'EX' $sql_versao_produto GROUP BY chave ORDER BY prazo ASC";
           
            $query_clientes_cancelados      = mysql_query($sql_clientes_cancelados, $banco_produto) or die(mysql_error()." - 8");
                            
            if (mysql_num_rows($query_clientes_cancelados)>0)
            {
                
                $cancelados            = mysql_num_rows($query_clientes_cancelados);
                $contar_cancelados = $cancelados + $contar_cancelados;
            }
            
            $total_registros = $contar_ativos + $contar_cancelados + $contar_dependentes;
            
            echo "<tr>
            <td> $id_parceiro </td>
            <td> $nome_parceiro </td>";
            
            /*echo "<td><a href=\"#ver_filiais_$id_parceiro\"  data-toggle=\"modal\" class=\"btn btn-sm btn-outline grey-salsa\"><i class=\"fa fa-search\"></i> Ver</a></td>";*/
            
            
            echo "<td><a href=\"inc/ver_filial_parceiro.php?verifica_filial=$verifica_sql_sel_filial&verifica_versao_produto=$verifica_sql_versao_produto&data1=$data1&data2=$data2&id_parceiro=$id_parceiro&slug=$slug&todos_clientes_ativos=$todos_clientes_ativos\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn btn-sm btn-outline grey-salsa hidden-print\"><i class=\"fa fa-search\"></i> Ver</a></td>";
            if($linha_filial == true)
            {
                echo "<td> $nome_filial </td>";
            }

            /*echo "<td> $total_registros </td>";*/
            echo "<td> <span class=\"label label-sm label-success\"> $contar_ativos </span> </td>";
            
            /*echo "<td>
                <span class=\"label label-sm label-info\"> $contar_dependentes </span> 
            </td>";*/
            
            echo "<td>
                <span class=\"label label-sm label-danger\"> $contar_cancelados </span> 
            </td>";
            /*if($pendente == true)
            {
                echo "<td> <span class=\"label label-sm label-warning\"> $contar_pendente </span> </td>";
            }
            else
            {
                echo "<td> &nbsp; </td>";
            }*/
            echo "
            </tr>";
            
            
            
            
            
            
            $total_soma_vendas = $total_registros + $total_soma_vendas;
            $total_soma_ativos = $contar_ativos + $total_soma_ativos;
            $total_soma_cancelados = $contar_cancelados + $total_soma_cancelados;
            $total_soma_adicionais = $contar_dependentes + $total_soma_adicionais;
       } 
       
       if($i == 0)
       {
            echo "<tr>
            <td>Sem registros</td>
            </tr>";
       }
       
       
       echo "</tbody></table> 
               </div>
                </div>";
                
    
   
   
    }elseif($slug == 'sorteio_ead'){
        
    }
  
}elseif($tipo == 'clientes')
{
    
        // tipo de base de dados
    if($slug == 'europ'){
        
        // selecionar o parceiro
        if($grupo == 'todos'){
            // seleciona venda de todos os parceiros
            
            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND gpro.id_grupo_produto = $grupo_produto";
            }
            
            
            $sql_base   = "SELECT par.id_parceiro FROM parceiros par
            JOIN parceiros_servicos pser ON par.id_parceiro = pser.id_parceiro
            JOIN produtos pro ON pser.id_produto = pro.id_produto
            JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
            JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
            WHERE pro.ativo = 'S' $sel_grupo_produto $sql_sel_produto";
            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 3");
            
                      
            if (mysql_num_rows($query_base)>0)
            {
                //$ids_parceiros = mysql_fetch_array($query_base, MYSQL_BOTH);
                $array_ids_parceiros = array();
                while($dados_ids_parceiros = mysql_fetch_array($query_base))
                {
                    $array_ids_parceiros[] = $dados_ids_parceiros['id_parceiro'];
                }
            }
            
            
            
            $grupo = '';
            $verifica_sql_sel_filial = 'false';
        }else{
           // id do gripo e parceiro está setado
           
           // verifica se esta setado o id do parceiro
           if($parceiro == '' OR $parceiro == 'todos'){
                // id do parceiro nao está setado, seleciona todos os parceiros do grupo x
                $sel_id_par = false;
                $verifica_sql_sel_filial = 'false';
                
           }else{

                $sql_img_parceiro   = "SELECT logo, logo_proposta FROM parceiros 
                WHERE id_parceiro = $parceiro";
                $query_img_parceiro = mysql_query($sql_img_parceiro, $banco_painel) or die(mysql_error()." - 789");
                
                $img_logo_parceiro = '';
                $img_logo_parceiro_proposta = '';          
                if (mysql_num_rows($query_img_parceiro)>0)
                {
                    $img_logo_parceiro = mysql_result($query_img_parceiro, 0,0);
                    $img_logo_parceiro_proposta = mysql_result($query_img_parceiro, 0,1);
                }
            
                // id do parceiro está setado, seleciona os clientes do parceiro x
                $sel_id_par = true;
                $array_ids_parceiros = 1;
                $sql_sel_filial = "";
                $verifica_sql_sel_filial = 'false';
                // verifica se está setado o id da filial
                if($filial <> '' AND $filial <> 'undefined' AND $filial <> 'todos'){
                    // está setado, seleciona clienta apenas da filial x
                    $sql_sel_filial = "AND (id_filial = $filial OR id_filial_integracao = $filial)";
                    $verifica_sql_sel_filial = $filial;
                }else{
                    if($filial == ''){
                        $sql_sel_filial = "AND (id_filial = 0 OR id_filial_integracao = 0)";
                    }
                }
                
           }
           
        }
        
        
        // seleciona o 
        if($produto == '' OR $produto == 'todos'){
        $sql_sel_produto = '';
        $produto = '';
        

                if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
                }else{
                    $sel_grupo_produto = "AND prog.id_grupo_produto = $grupo_produto";
                } 
                $sql_produtos        = "SELECT pro.nome FROM produtos pro
                JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
                JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
                WHERE pro.ativo = 'S' $sel_grupo_produto";
                $query_produtos      = mysql_query($sql_produtos, $banco_painel) or die(mysql_error()." - 2.3");
                            
                if (mysql_num_rows($query_produtos)>0)
                {
                    $array_nomes_produtos = array();
                    while($dados_nomes_produtos = mysql_fetch_array($query_produtos))
                    {
                        $array_nomes_produtos[] = $dados_nomes_produtos['nome'];
                    }
                    $versao_produto_ver = false;
                    $nome_dos_produtos = implode('<br/>', $array_nomes_produtos);
                    
                }
        
        
        }else{
            
            $sql_sel_produto = "AND pro.id_produto = $produto"; 
            
            $sql_produtos        = "SELECT nome, versao_produto FROM produtos 
                    WHERE id_produto = $produto";
                    $query_produtos      = mysql_query($sql_produtos, $banco_painel) or die(mysql_error()." - 2.4");
                                
                    if (mysql_num_rows($query_produtos)>0)
                    {
                        $nome_dos_produtos    = mysql_result($query_produtos, 0, 'nome');
                        $versao_produto_europ = mysql_result($query_produtos, 0, 'versao_produto');
                        
                        $versao_produto_ver = true;
                    } 
        }
?>
<div class="invoice">
                                <div class="row invoice-logo">
                                    <div class="col-xs-3 invoice-logo-space">
                                    <img src="assets/pages/img/logos/<?php echo $img_logo_parceiro_proposta; ?>" class="img-responsive" alt=""/> </div>
                                    <div class="col-xs-5">
                                       <a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();"> Imprimir
                                            <i class="fa fa-print"></i>
                                        </a>
                                        <div class="tools"> </div>
                                    </div>
                                    <div class="col-xs-4 invoice-logo-space">
                                        <img src="assets/pages/img/logos/<?php echo $img_logo_parceiro; ?>" class="img-responsive" alt=""/> </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    
                                    <div class="col-xs-12 invoice-payment">
                                        <h3>Info:</h3>
                                         <p> Data do relatório: <?php echo date('d-m-Y H:i'); ?> <br />
                                            <span class="muted"> Lista de <?php echo $tipo; ?> por período: <br /> <?php 
                                            if($todos_clientes_ativos == 'S'){
                                                echo "Todos ativos!";
                                            }else{
                                                echo $periodo;
                                            } ?> </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <!--<table class="table table-condensed table-hover">-->
                                        <!--<table class="table table-striped table-bordered table-hover" id="sample_1">-->
                                        <table class="table table-condensed table-hover" id="sample_1">
<?php

        
        
        if($grupo == '')
        {
            $sql_grupo = '';
        }else{
            $sql_grupo = "AND parg.id_grupo_parceiro = $grupo";
        }
        
        if($sel_id_par == false)
        {
            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND gpro.id_grupo_produto = $grupo_produto";
            }   
                       
            $sql_base   = "SELECT par.id_parceiro FROM parceiros par
            JOIN parceiros_servicos pser ON par.id_parceiro = pser.id_parceiro
            JOIN produtos pro ON pser.id_produto = pro.id_produto
            JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
            JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
            JOIN parceiros_grupos parg ON pser.id_parceiro = parg.id_parceiro
            WHERE pro.ativo = 'S' $sel_grupo_produto $sql_sel_produto $sql_grupo 
            GROUP BY par.id_parceiro";
            //echo $sql_base;
            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 4");
            
            if (mysql_num_rows($query_base)>0)
            {
                //$ids_parceiros = mysql_fetch_array($query_base, MYSQL_BOTH);
                $array_ids_parceiros = array();
                while($dados_ids_parceiros = mysql_fetch_array($query_base))
                {
                    $array_ids_parceiros[] = $dados_ids_parceiros['id_parceiro'];
                }
            }
            
        }
        
        
       $contar = count($array_ids_parceiros) - 1;
       //echo $contar;
       $contar_ativos       = 0;
       $contar_cancelados   = 0;
       $contar_inativos     = 0;

                if($verifica_sql_sel_filial == 'false'){
                
                //echo $nome_parceiro;
                $linha_filial = false;
                echo "<thead>
                    <tr>
                        <th> ID </th>
                        <th> Cliente </th>
                        <th> CPF </th>
                        <th> Prazo</th>
                        <th> Emissão</th>
                        <th> Parceiro </th>
                        <th> Usuário </th>
                        <th> Status </th>
                    </tr>
                </thead>
                <tbody>";
                
            }else{
                
                $linha_filial = true;
                echo "<thead>
                     <tr>
                        <th> ID </th>
                        <th> Cliente </th>
                        <th> CPF </th>
                        <th> Prazo</th>
                        <th> Emissão</th>
                        <th> Parceiro </th>
                        <th> Usuário </th>
                        <th> Status </th>
                    </tr>
                </thead>
                <tbody>";
            }
      
      $total_soma_vendas = 0;
      $total_soma_ativos = 0;
      $total_soma_cancelados = 0;
      $total_soma_inativos = 0;
      $new_prazos = array();
       for($i=0;$contar>=$i;$i++)
       {
          $contar_ativos = 0;
          $contar_cancelados = 0;
          $contar_dependentes = 0;
          $contar_inativos     = 0;
          if($sel_id_par == false)
          {
            $id_parceiro = $array_ids_parceiros[$i];
          }
          else
          {
            $id_parceiro = $parceiro;
          }
          
            if($linha_filial == false)
            {
                $filial = 0;
                $sql_par_sel   = "SELECT nome FROM parceiros
                WHERE id_parceiro = $id_parceiro";

                $query_par_sel = mysql_query($sql_par_sel, $banco_painel) or die(mysql_error()." - 5");
                
                $nome_parceiro = mysql_result($query_par_sel, 0, 'nome');
            
            }
            else
            {
                $sql_par_sel   = "SELECT par.nome'nome_parceiro', fi.nome'nome_filial' FROM parceiros par
                JOIN filiais fi ON par.id_parceiro = fi.id_parceiro
                WHERE par.id_parceiro = $id_parceiro AND (fi.id_filial = $filial OR fi.id_filial_integracao = $filial)";
                $query_par_sel = mysql_query($sql_par_sel, $banco_painel) or die(mysql_error()." - 6");
                $nome_parceiro = mysql_result($query_par_sel, 0, 'nome_parceiro');
                $nome_filial = mysql_result($query_par_sel, 0, 'nome_filial');
            }
            
            if($versao_produto_ver == true){
                $sql_versao_produto = "AND versao_europ = $versao_produto_europ";
            }else{
                $sql_versao_produto = '';
            }
            
            $sql_data_emissao = "(data_inicio BETWEEN '$data1' AND '$data2')";
            $agora 			= date("Y-m-d");
            if($todos_clientes_ativos == 'S'){
                $sql_data_emissao = "data_inicio <= '$agora'";
            }
            $sql_exibir_adicionais = 'AND id_cliente_principal = 0';
            if($exibir_adicionais == 'S'){
                $sql_exibir_adicionais = "";
            }
            
            $sql_lista_status = "AND status IN (99, 0, 3, 6)";
            if($apenas_ativos == 'S'){
                $sql_lista_status = "AND status IN (99, 0)";
            }

            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND id_produto = $grupo_produto";
            } 
            
            $sql_lista_usuario_vendedor = "";
            if($usuario_vendedor != 'todos' AND $usuario_vendedor != 'undefined'){
                $sql_lista_usuario_vendedor = "AND id_usuario = $usuario_vendedor";
            }

            $sql_clientes_ativos = "SELECT * FROM clientes
            WHERE $sql_data_emissao AND id_parceiro = $id_parceiro $sql_sel_filial $sql_lista_usuario_vendedor $sql_exibir_adicionais AND tipo_movimento IN ('IN', 'AL') $sel_grupo_produto $sql_versao_produto $sql_lista_status
            GROUP BY chave ORDER BY prazo ASC";
            //echo $sql_clientes_ativos;
            $query_clientes_ativos      = mysql_query($sql_clientes_ativos, $banco_produto) or die(mysql_error()."$usuario_vendedor");
                    
            if (mysql_num_rows($query_clientes_ativos)>0)
            { 
                while($dados_clientes_ativos = mysql_fetch_array($query_clientes_ativos))
                {
                    extract($dados_clientes_ativos);
                    
                    //$data_termino    = converte_data_barra($data_termino);
                    
                    // status
                    // Comparando as Datas
                    //$convert_data_termino = converte_data($data_termino);
                    $filial = '';
                    if($id_filial != '' AND $id_filial != 0){
                        $filial = $id_filial;
                    }
                    
                    if($id_filial_integracao != '' AND $id_filial_integracao != 0){
                        $filial = $id_filial_integracao;
                    }
                    
                    if($filial > 0){
                        $sql_fil_sel   = "SELECT nome'nome_filial' FROM filiais 
                    WHERE (id_filial = '$filial' OR id_filial_integracao = '$filial')";
                    
                    $query_fil_sel = mysql_query($sql_fil_sel, $banco_painel) or die(mysql_error()." - 6");
                    
                    if(mysql_num_rows($query_fil_sel) > 0){
                        $nome_filial = mysql_result($query_fil_sel, 0, 'nome_filial');
                    }else{
                        $nome_filial = '';
                    }
                    
                    }else{
                        $nome_filial = '';
                    }
                    $nome_user = ' ';
                    if($id_usuario > 0){
                    $sql_user_sel   = "SELECT nome'nome_usuario' FROM usuarios 
                                        WHERE id_usuario = $id_usuario";
                    //echo $sql_user_sel;
                    $query_user_sel = mysql_query($sql_user_sel, $banco_painel) or die(mysql_error()." - 67");
                    $nome_user = mysql_result($query_user_sel, 0, 'nome_usuario');
                    }
                    
                    $sql_ver_cli    = "SELECT id_produto'id_produto_cliente' FROM produtos 
                                    WHERE versao_produto = '$versao_europ'";
                    $query_ver_cli  = mysql_query($sql_ver_cli, $banco_painel);
                                
                    if (mysql_num_rows($query_ver_cli)>0)
                    {
                        $id_produto_cliente = mysql_result($query_ver_cli, 0,'id_produto_cliente');
                    }
                    $status_list = array(
                    array("success" => "Ativo"),
                    array("danger" => "Inativo")
                    );
                    $plano_add = '';
                    if($id_cliente_principal == 0){
                        $exibe_id_principal = "";
                    }else{
                        $exibe_id_principal = $id_cliente_principal;
                    }
                    
                    if($id_cliente_principal > 0)
                    {
                        $plano_add = ' <span class="label label-sm label-info">D</span>';
                    }
                    $tr_adicional = '';
                    if($id_cliente_principal > 0){
                        $tr_adicional = "class=\"info\"";
                        $tr_adicional = "";
                    }
                    
                    $label_renova = '';
                    $cliente_status_renovacao = ''; 
                    $cliente_id_ordem_pedido = '';
                    
                    $sql_st_ren    = "SELECT id_ordem_pedido FROM vendas 
                                      WHERE id_cliente = $id_cliente";
                    $query_st_ren  = mysql_query($sql_st_ren, $banco_produto);
 
                    if (mysql_num_rows($query_st_ren)>0)
                    {
                        $cliente_id_ordem_pedido = mysql_result($query_st_ren, 0,'id_ordem_pedido');
                        
                        $sql_st_ren    = "SELECT status_renovacao FROM ordem_pedidos 
                                      WHERE id_ordem_pedido = $cliente_id_ordem_pedido";
                        $query_st_ren  = mysql_query($sql_st_ren, $banco_painel);
     
                        if (mysql_num_rows($query_st_ren)>0)
                        {
                            $cliente_status_renovacao = mysql_result($query_st_ren, 0,'status_renovacao');
                        }
                    }
                    
                    if($cliente_status_renovacao == 'S')
                    {
                        $label_renova = '<span class="label label-sm bg-purple">RE</span>';
                        
                    }  
                    
                    //AND ($status == 99 OR $status == 0 OR $status == 3 OR $status == 6)
                    if((strtotime($data_termino) >= strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX')
                    {
                        if($cliente_status_renovacao == 'N'){
                        
                        if($status == 3)
                        {
                            // pendente
                            $status_linha = "<span class=\"label label-sm label-warning\"> $tipo_movimento P </span>";
                            $status_nome = $status_list[1];
                        }
                        else
                        {
                            if($id_cliente_principal > 0){
                                if($status == 99 OR $status == 0 OR $status == 6)
                                {
                                    $contar_dependentes++;
                                    $status_nome = $status_list[0];
                                    if($status == 6)
                                    {
                                        $status_nome = $status_list[1];
                                    }
                                }
                            }else{
                                if($status == 99 OR $status == 0)
                                {
                                    $contar_ativos++;
                                    $status_nome = $status_list[0];
                                }
                                
                                if($status == 6)
                                {
                                    $contar_inativos++;
                                    $status_nome = $status_list[1];
                                }
                            }
                            if($status == 99 OR $status == 0 OR $status == 6)
                            {
                            if($prazo_atual == $prazo){
                               $contar_prazo++;
                               $new_prazos[$prazo] = $contar_prazo;
                            }else{
                                $contar_prazo = 1;
                                $contar_new_prazos = count($new_prazos);
                                if($contar_new_prazos == 0){
                                    $new_prazos = array($prazo => $contar_prazo);
                                }else{
                                   
                                    $new_prazos[$prazo] = $contar_prazo;
                                    //$new_prazos = array_merge($new_prazos, array($prazo => $contar_prazo));
                                }
                                
                            }
                                $prazo_atual = $prazo;
                                $status_linha = "<span class=\"label label-sm label-success\">$tipo_movimento</span>";
                                if($status == 6)
                                {
                                    //$status_linha = "<span class=\"label label-sm label-danger\"> $tipo_movimento</span>";
                                    $status_linha = "<span class=\"label label-sm label-danger\"> PA</span>";
                                }
                            }
                            
                        }
                        
                            echo "<tr $tr_adicional>
                            <td> $id_cliente </td>
                            <td> $nome $plano_add</td>";
                            if($prazo == 0){$exibe_prazo = "Recorrente";}else{$exibe_prazo = $prazo." meses";}
                            echo "<td> $cpf </td>
                            <td>$exibe_prazo</td>
                            <td>".converte_data($data_emissao)."</td>
                            <td> $nome_parceiro  $nome_filial </td>
                            <td> $nome_user </td>
                            <td>";
                            echo $label_renova." ".$status_linha;
                            echo "<a href=\"inc/ver_cliente.php?id_cliente=$id_cliente&id_produto=$id_produto_cliente&tipo=produto&status=".(current($status_nome))."\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn btn-sm btn-outline grey-salsa\"><i class=\"fa fa-search\"></i></a></td>
                            </tr>";
                        }
                    }
                    /*elseif(strtotime($data_termino) == strtotime($agora) AND $tipo_movimento <> 'EX' AND ($exibir_apenas_ativos))
                    {
                        if($status == 3)
                        {
                            $status_linha = "<span class=\"label label-sm label-warning\"> $tipo_movimento P </span>";
                            $status_nome = $status_list[1];
                        }
                        else
                        {
                            if($id_cliente_principal > 0){
                                if($status == 99 OR $status == 0 OR $status == 6)
                                {
                                    $contar_dependentes++;
                                    $status_nome = $status_list[0];
                                }
                                if($status == 6)
                                {
                                    $status_nome = $status_list[1];
                                }
                            }else{
                                if($status == 99 OR $status == 0)
                                {
                                    $contar_ativos++;
                                    $status_nome = $status_list[0];
                                }
                                
                                if($status == 6)
                                {
                                    $contar_inativos++;
                                    $status_nome = $status_list[1];
                                }
                            }
                            
                            if($status == 99 OR $status == 0 OR $status == 6)
                            {
                            if($prazo_atual == $prazo){
                               $contar_prazo++;
                               $new_prazos[$prazo] = $contar_prazo;
                            }else{
                                $contar_prazo = 1;
                                $contar_new_prazos = count($new_prazos);
                                if($contar_new_prazos == 0){
                                    $new_prazos = array($prazo => $contar_prazo);
                                }else{
                                   
                                    $new_prazos[$prazo] = $contar_prazo;
                                    //$new_prazos = array_merge($new_prazos, array($prazo => $contar_prazo));
                                }
                            }
                            
                                $prazo_atual = $prazo;
                                $status_linha = "<span class=\"label label-sm label-success\"> $tipo_movimento</span>";
                                if($status == 6)
                                {
                                    $status_linha = "<span class=\"label label-sm label-danger\"> $tipo_movimento</span>";
                                }
                            }
                        }
                        
                        //$status_linha = "<span class=\"label label-sm label-success\"> ($tipo_movimento) Ativo </span>";
                        echo "<tr $tr_adicional>
                        <td> $id_cliente </td>
                        <td> $nome $plano_add</td>";
                        if($prazo == 0){$exibe_prazo = "Recorrente";}else{$exibe_prazo = $prazo." meses";}
                        echo "<td> $cpf </td>
                        <td> $exibe_prazo </td>
                        <td>".converte_data($data_emissao)."</td>
                        <td> $nome_parceiro  $nome_filial </td>
                        <td> $nome_user </td>
                        <td>";
                        echo "$status_linha";
                        echo "<a href=\"inc/ver_cliente.php?id_cliente=$id_cliente&id_produto=$id_produto_cliente&tipo=produto&status=".(current($status_nome))."\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn btn-sm btn-outline grey-salsa\"><i class=\"fa fa-search\"></i></a></td>
                        </tr>";
            
                    }*/
                    else
                    {
                        /*
                        $contar_cancelados++;
                        $status_nome = $status_list[1];
                        $status_linha = "<span class=\"label label-sm label-danger\"> $tipo_movimento</span>";
                        echo "<tr $tr_adicional> 
                        <td> $id_cliente </td>
                        <td> $nome $plano_add</td>";
                        if($prazo == 0){$exibe_prazo = "Recorrente";}else{$exibe_prazo = $prazo." meses";}
                        echo "<td> $cpf </td>
                        <td> $exibe_prazo </td>
                        <td>".converte_data($data_emissao)."</td>
                        <td> $nome_parceiro  $nome_filial </td>
                        <td> $nome_user </td>
                        <td>";
                        echo "$status_linha";
                        echo "<a href=\"inc/ver_cliente.php?id_cliente=$id_cliente&id_produto=$id_produto_cliente&tipo=produto&status=".(current($status_nome))."\" data-target=\"#ajax\" data-toggle=\"modal\" class=\"btn btn-sm btn-outline grey-salsa\"><i class=\"fa fa-search\"></i></a></td>
                        </tr>";
                        */
                    }
                    
                    
                }
            }
            
            /*$sql_clientes_cancelados = "SELECT COUNT(*) FROM clientes
            WHERE (data_inicio BETWEEN '$data1' AND '$data2')
            AND id_parceiro = $id_parceiro $sql_sel_filial AND tipo_movimento = 'EX' $sql_versao_produto";
           
            $query_clientes_cancelados      = mysql_query($sql_clientes_cancelados, $banco_produto) or die(mysql_error()." - 8");
                            
            if (mysql_num_rows($query_clientes_cancelados)>0)
            {
                $cancelados            = mysql_result($query_clientes_cancelados, 0, 0);
                
            }*/
            $total_soma_cancelados = $contar_cancelados + $total_soma_cancelados;
            $total_registros = $contar_ativos + $contar_cancelados + $contar_dependentes;
            $total_soma_vendas = $total_registros + $total_soma_vendas;
            $total_soma_ativos = $contar_ativos + $total_soma_ativos;
            $total_soma_inativos = $contar_inativos + $total_soma_inativos;
            $total_soma_adicionais = $contar_dependentes + $total_soma_adicionais;
            
       } 
       
       if($i == 0)
       {
            echo "<tr>
            <td>Sem registros</td>
            </tr>";
       }
       
       
       echo "</tbody></table> 
               </div>
                </div>";
                
    
   
   
    }elseif($slug == 'sorteio_ead'){
        
    }
  
  
    
}elseif($tipo == 'faturamento')
{
    
        // tipo de base de dados
    if($slug == 'europ' AND $grupo_produto != 'todos'){
        
        // selecionar o parceiro
        if($grupo == 'todos'){
            // seleciona venda de todos os parceiros

            $sql_base   = "SELECT par.id_parceiro FROM parceiros par
            JOIN parceiros_servicos pser ON par.id_parceiro = pser.id_parceiro
            JOIN produtos pro ON pser.id_produto = pro.id_produto
            JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
            JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
            WHERE gpro.id_grupo_produto = $grupo_produto $sql_sel_produto";
            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 3");
            
                      
            if (mysql_num_rows($query_base)>0)
            {
                //$ids_parceiros = mysql_fetch_array($query_base, MYSQL_BOTH);
                $array_ids_parceiros = array();
                while($dados_ids_parceiros = mysql_fetch_array($query_base))
                {
                    $array_ids_parceiros[] = $dados_ids_parceiros['id_parceiro'];
                }
            }
            
            
            
            $grupo = '';
            $verifica_sql_sel_filial = 'false';
        }else{
           // id do gripo e parceiro está setado
           
           // verifica se esta setado o id do parceiro
           if($parceiro == '' OR $parceiro == 'todos'){
                // id do parceiro nao está setado, seleciona todos os parceiros do grupo x
                $sel_id_par = false;
                $verifica_sql_sel_filial = 'false';
                
           }else{
                // id do parceiro está setado, seleciona os clientes do parceiro x
                
                
                $sql_img_parceiro   = "SELECT logo, logo_proposta FROM parceiros 
                WHERE id_parceiro = $parceiro";
                $query_img_parceiro = mysql_query($sql_img_parceiro, $banco_painel) or die(mysql_error()." - 789");
                
                $img_logo_parceiro = '';
                $img_logo_parceiro_proposta = '';          
                if (mysql_num_rows($query_img_parceiro)>0)
                {
                    $img_logo_parceiro = mysql_result($query_img_parceiro, 0,0);
                    $img_logo_parceiro_proposta = mysql_result($query_img_parceiro, 0,1);
                }
                
                $sel_id_par = true;
                $array_ids_parceiros = 1;
                $sql_sel_filial = "";
                $id_filial = '';
                $verifica_sql_sel_filial = 'false';
                // verifica se está setado o id da filial
                if($filial <> '' AND $filial <> 'undefined' AND $filial <> 'todos'){
                    // está setado, seleciona clienta apenas da filial x
                    $sql_sel_filial = "AND (id_filial = $filial OR id_filial_integracao = $filial)";
                    $id_filial = $filial;
                    $verifica_sql_sel_filial = $filial;
                }else{
                    if($filial == ''){
                        $sql_sel_filial = "AND (id_filial = 0 OR id_filial_integracao = 0)";
                    }
                }
                
           }
           
        }
        
        
        // seleciona o 
        if($produto == '' OR $produto == 'todos'){
        $sql_sel_produto = '';
        $produto = '';
        
        if($grupo_produto == 'todos'){
        $sel_grupo_produto = '';
        }else{
            $sel_grupo_produto = "AND prog.id_grupo_produto = $grupo_produto";
        } 

        $sql_produtos        = "SELECT pro.nome FROM produtos pro
        JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
        JOIN bases_produtos bpro ON pro.id_base_produto = bpro.id_base_produto
        WHERE pro.ativo = 'S' $sel_grupo_produto";
        $query_produtos      = mysql_query($sql_produtos, $banco_painel) or die(mysql_error()." - 2.3");
                    
        if (mysql_num_rows($query_produtos)>0)
        {
            $array_nomes_produtos = array();
            while($dados_nomes_produtos = mysql_fetch_array($query_produtos))
            {
                $array_nomes_produtos[] = $dados_nomes_produtos['nome'];
            }
            $versao_produto_ver = false;
            $nome_dos_produtos = implode('<br/>', $array_nomes_produtos);
            
        }
        
        
        }else{
            $sql_sel_produto = "AND pro.id_produto = $produto"; 
            
            $sql_produtos        = "SELECT nome, versao_produto FROM produtos 
                    WHERE id_produto = $produto";
                    $query_produtos      = mysql_query($sql_produtos, $banco_painel) or die(mysql_error()." - 2.4");
                                
                    if (mysql_num_rows($query_produtos)>0)
                    {
                        $nome_dos_produtos    = mysql_result($query_produtos, 0, 'nome');
                        $versao_produto_europ = mysql_result($query_produtos, 0, 'versao_produto');
                        
                        $versao_produto_ver = true;
                    } 
        }
?>
<div class="invoice">
                                <div class="row invoice-logo">
                                    <div class="col-xs-4 invoice-logo-space">
                                    <img src="assets/pages/img/logos/<?php echo $img_logo_parceiro_proposta; ?>" class="img-responsive" alt=""/> </div>
                                        <div class="col-xs-4 invoice-logo-space">
                                        <img src="assets/pages/img/logos/<?php echo $img_logo_parceiro; ?>" class="img-responsive" alt=""/> </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-xs-12 invoice-payment">
                                        <h3>Info:</h3>
                                         <p> Data do relatório: <?php echo date('d-m-Y H:i'); ?> <br />
                                            <span class="muted"> Lista de <?php echo $tipo; ?> por período: <?php 
                                            if($todos_clientes_ativos == 'S'){
                                                echo "Todos ativos!";
                                            }else{
                                                echo $periodo;
                                            } ?> </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <!--<table class="table table-condensed table-hover">-->
                                        <table class="table table-condensed table-hover" id="sample_1">
<?php

        
        
        if($grupo == '')
        {
            $sql_grupo = '';
        }else{
            $sql_grupo = "AND parg.id_grupo_parceiro = $grupo";
        }
        
        if($sel_id_par == false)
        {
            if($grupo_produto == 'todos'){
            $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND gpro.id_grupo_produto = $grupo_produto";
            }
            $sql_base   = "SELECT par.id_parceiro FROM parceiros par
            JOIN parceiros_servicos pser ON par.id_parceiro = pser.id_parceiro
            JOIN produtos pro ON pser.id_produto = pro.id_produto
            JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
            JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
            JOIN parceiros_grupos parg ON pser.id_parceiro = parg.id_parceiro
            WHERE pro.ativo = 'S' $sel_grupo_produto $sql_sel_produto $sql_grupo 
            GROUP BY par.id_parceiro";
            //echo $sql_base;
            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 4");
            
            if (mysql_num_rows($query_base)>0)
            {
                //$ids_parceiros = mysql_fetch_array($query_base, MYSQL_BOTH);
                $array_ids_parceiros = array();
                while($dados_ids_parceiros = mysql_fetch_array($query_base))
                {
                    $array_ids_parceiros[] = $dados_ids_parceiros['id_parceiro'];
                }
            }
            
        }
        
        if($grupo_produto == 'todos'){
        $sel_grupo_produto = '';
        }else{
            $sel_grupo_produto = "AND gpro.id_grupo_produto = $grupo_produto";
        }

        $sql_id_servico   = "SELECT ser.id_servico FROM parceiros par
        JOIN parceiros_servicos pser ON par.id_parceiro = pser.id_parceiro
        JOIN produtos pro ON pser.id_produto = pro.id_produto
        JOIN servicos ser ON pro.id_servico = ser.id_servico
        JOIN produtos_grupos prog ON pro.id_produto = prog.id_produto
        JOIN grupos_produtos gpro ON prog.id_grupo_produto = gpro.id_grupo_produto
        WHERE pro.ativo = 'S' $sel_grupo_produto";
        $query_id_servico = mysql_query($sql_id_servico, $banco_painel) or die(mysql_error()." - 3");
                  
        if (mysql_num_rows($query_id_servico)>0)
        {
            $id_servico = mysql_result($query_id_servico, 0, 'id_servico');
        }
        
        
       $contar = count($array_ids_parceiros) - 1;
       //echo $contar;
       $contar_ativos       = 0;
       $contar_cancelados   = 0;
       $contar_pendente = 0;

                if($verifica_sql_sel_filial == 'false'){
                
                //echo $nome_parceiro;
                $linha_filial = false;
                echo "<thead>
                    <tr>
                        <th> ID </th>
                        <th> Parceiro </th>
                        <th> Total </th>
                        <th> Ativos </th>
                        <th> Adicional </th>
                        <th> Cancelados </th>
                        <th> Pendentes </th>
                        <th> Faturamento </th>
                        <th> Total Geral </th>
                    </tr>
                </thead>
                <tbody>";
                
            }else{
                
                $linha_filial = true;
                echo "<thead>
                    <tr>
                        <th> ID </th>
                        <th> Parceiro </th>
                        <th> Filial </th>
                        <th> Total </th>
                        <th> Ativos </th>
                        <th> Adicional </th>
                        <th> Cancelados </th>
                        <th> Pendentes </th>
                        <th> Faturamento </th>
                        <th> Total Geral </th>
                    </tr>
                </thead>
                <tbody>";
            }
      
      $total_soma_vendas = 0;
      $total_soma_ativos = 0;
      $total_soma_cancelados = 0;
       for($i=0;$contar>=$i;$i++)
       {
          $contar_ativos = 0;
          $contar_cancelados = 0;
          $contar_pendente = 0;
          $contar_dependentes = 0;
          
          
          $new_prazos = array();
          if($sel_id_par == false)
          {
            $id_parceiro = $array_ids_parceiros[$i];
          }
          else
          {
            $id_parceiro = $parceiro;
          }
          
            if($linha_filial == false)
            {
                $sql_par_sel   = "SELECT nome FROM parceiros
                WHERE id_parceiro = $id_parceiro";
                $query_par_sel = mysql_query($sql_par_sel, $banco_painel) or die(mysql_error()." - 5");
                
                $nome_parceiro = mysql_result($query_par_sel, 0, 'nome');
            
            }
            else
            {
                $sql_par_sel   = "SELECT par.nome'nome_parceiro', fi.nome'nome_filial' FROM parceiros par
                JOIN filiais fi ON par.id_parceiro = fi.id_parceiro
                WHERE par.id_parceiro = $id_parceiro AND (fi.id_filial = $filial OR fi.id_filial_integracao)";
                $query_par_sel = mysql_query($sql_par_sel, $banco_painel) or die(mysql_error()." - 6");
                $nome_parceiro = mysql_result($query_par_sel, 0, 'nome_parceiro');
                $nome_filial = mysql_result($query_par_sel, 0, 'nome_filial');
            }
            
            if($versao_produto_ver == true){
                $sql_versao_produto = "AND versao_europ = $versao_produto_europ";
            }else{
                $sql_versao_produto = '';
            }
            
            $sql_data_emissao = "(data_inicio BETWEEN '$data1' AND '$data2')";
            $agora 			= date("Y-m-d");
            if($todos_clientes_ativos == 'S'){
                $sql_data_emissao = "data_inicio <= '$agora'";
            }
            
            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND id_produto = $grupo_produto";
            } 

            $sql_lista_usuario_vendedor = "";
            if($usuario_vendedor != 'todos'){
                $sql_lista_usuario_vendedor = "AND id_usuario = $usuario_vendedor";
            }

            $sql_clientes_ativos = "SELECT id_cliente_principal, id_produto, data_termino, prazo, status FROM clientes
            WHERE $sql_data_emissao
            AND id_parceiro = $id_parceiro $sql_sel_filial $sql_lista_usuario_vendedor $sel_grupo_produto AND tipo_movimento IN ('IN', 'AL') $sql_versao_produto GROUP BY chave ORDER BY prazo ASC";
            
            $query_clientes_ativos      = mysql_query($sql_clientes_ativos, $banco_produto) or die(mysql_error()." - 7");
            $prazo_atual = null;
            $new_prazos = array();
           
            if (mysql_num_rows($query_clientes_ativos)>0)
            { 
                
                while($dados_clientes_ativos = mysql_fetch_array($query_clientes_ativos))
                {
                    extract($dados_clientes_ativos);

                    $agora 			= date("Y-m-d");
                    
                    //if(strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00')
                    //{
                        if($status == 3)
                        {
                            $pendente = true;
                            $contar_pendente++;
                        }
                        else{
                            if($id_cliente_principal > 0){
                                if($status == 99 OR $status == 0)
                                {
                                    $contar_dependentes++;
                                }
                            }else{
                                if($status == 99 OR $status == 0)
                                {
                                    $contar_ativos++;
                                }
                            }
                            
                            if($status == 99 OR $status == 0)
                            {
                                if($prazo_atual == $prazo){
                                   $contar_prazo++;
                                   $new_prazos[$prazo] = $contar_prazo;
                                }else{
                                    $contar_prazo = 1;
                                    
                                    $contar_new_prazos = count($new_prazos);
                                    if($contar_new_prazos == 0){
                                        $new_prazos = array($prazo => $contar_prazo);
                                        
                                    }else{
                                        $new_prazos[$prazo] = $contar_prazo;
                                    }
                                    $prazo_atual = $prazo;
                                }
                                
                            }
                            
                        }
                   /* }
                    elseif(strtotime($data_termino) == strtotime($agora))
                    {
                        if($status == 3)
                        {
                            $pendente = true;
                            $contar_pendente++;
                        }
                        else{
                            if($id_cliente_principal > 0){
                                if($status == 99 OR $status == 0)
                                {
                                    $contar_dependentes++;
                                }
                            }else{
                                if($status == 99 OR $status == 0)
                                {
                                    $contar_ativos++;
                                }
                            }
                            
                            if($status == 99 OR $status == 0)
                            {
                                if($prazo_atual == $prazo){
                                   $contar_prazo++;
                                   $new_prazos[$prazo] = $contar_prazo;
                                }else{
                                    $contar_prazo = 1;
                                    
                                    $contar_new_prazos = count($new_prazos);
                                    if($contar_new_prazos == 0){
                                        $new_prazos = array($prazo => $contar_prazo);
                                        
                                    }else{
                                        $new_prazos[$prazo] = $contar_prazo;
                                    }
                                    $prazo_atual = $prazo;
                                }
                                
                            }
                            
                        }
                    }
                    else
                    {
                        $contar_cancelados++;
                    }*/
                //$i++;
                }
            }
            
            if($grupo_produto == 'todos'){
                $sel_grupo_produto = '';
            }else{
                $sel_grupo_produto = "AND id_produto = $grupo_produto";
            } 

            $sql_lista_usuario_vendedor = "";
            if($usuario_vendedor != 'todos'){
                $sql_lista_usuario_vendedor = "AND id_usuario = $usuario_vendedor";
            }

            $sql_clientes_ativos = "SELECT id_cliente_principal, id_produto, data_termino, prazo, status FROM clientes
            WHERE $sql_data_emissao
            AND id_parceiro = $id_parceiro $sql_sel_filial $sql_lista_usuario_vendedor $sel_grupo_produto AND tipo_movimento IN ('IN', 'AL') $sql_versao_produto GROUP BY chave ORDER BY id_produto ASC";
            
            $query_clientes_ativos      = mysql_query($sql_clientes_ativos, $banco_produto) or die(mysql_error()." - 7");
            
            $grupo_atual = null;
            $new_grupos = array();   
  
            if (mysql_num_rows($query_clientes_ativos)>0)
            { 
                
                while($dados_clientes_ativos = mysql_fetch_array($query_clientes_ativos))
                {
                    extract($dados_clientes_ativos);

                    $agora 			= date("Y-m-d");
                    
                    //if(strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00')
                    //{
                        if($status == 99 OR $status == 0 OR $status == 3)
                        {
                            //grupos
                            if($grupo_atual == $id_produto){
                               $contar_grupo++;
                               $new_grupos[$id_produto] = $contar_grupo;
                            }else{
                                $contar_grupo = 1;
                                
                                $contar_new_grupos = count($new_grupos);
                                if($contar_new_grupos == 0){
                                    $new_grupos = array($id_produto => $contar_grupo);
                                    
                                }else{
                                    $new_grupos[$id_produto] = $contar_grupo;
                                }
                                $grupo_atual = $id_produto;
                            }
                            
                        }
                        
                    /*}elseif(strtotime($data_termino) == strtotime($agora))
                    {
                        if($status == 99 OR $status == 0 OR $status == 3)
                        {
                            //grupos
                            if($grupo_atual == $id_produto){
                               $contar_grupo++;
                               $new_grupos[$id_produto] = $contar_grupo;
                            }else{
                                $contar_grupo = 1;
                                
                                $contar_new_grupos = count($new_grupos);
                                if($contar_new_grupos == 0){
                                    $new_grupos = array($id_produto => $contar_grupo);
                                    
                                }else{
                                    $new_grupos[$id_produto] = $contar_grupo;
                                }
                                $grupo_atual = $id_produto;
                            }
                            
                        }
                        
                    }*/
                }
            }
            
            $sql_clientes_cancelados = "SELECT id_cliente FROM clientes
            WHERE $sql_data_emissao
            AND id_parceiro = $id_parceiro $sql_sel_filial AND tipo_movimento = 'EX' $sql_versao_produto GROUP BY chave ORDER BY prazo ASC";
           
            $query_clientes_cancelados      = mysql_query($sql_clientes_cancelados, $banco_produto) or die(mysql_error()." - 8");
                            
            if (mysql_num_rows($query_clientes_cancelados)>0)
            {
                $cancelados            = mysql_num_rows($query_clientes_cancelados);
                $contar_cancelados = $cancelados + $contar_cancelados;
            }
            
            $total_registros = $contar_ativos + $contar_dependentes + $contar_cancelados;
            
            echo "<tr>
            <td> $id_parceiro </td>
            <td> $nome_parceiro </td>";
            
            if($linha_filial == true)
            {
                echo "<td> $nome_filial </td>";
            }

            echo "<td> $total_registros </td>
            <td> <span class=\"label label-sm label-success\"> $contar_ativos </span> </td>";
            echo "";
            
            echo "<td>
                <span class=\"label label-sm label-info\"> $contar_dependentes </span> 
            </td>";    
                
            echo "<td><span class=\"label label-sm label-danger\"> $contar_cancelados </span> 
            </td>";
            if($pendente == true)
            {
                echo "<td> <span class=\"label label-sm label-warning\"> $contar_pendente </span> </td>";
            }
            else
            {
                echo "<td> &nbsp; </td>";
            }
            echo "<td>";
            if(isset($new_prazos) AND !empty($new_prazos)){
                
                echo "<div class=\"btn-group\">
                        <button class=\"btn green btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"> Por:
                            <i class=\"fa fa-angle-down\"></i>
                        </button>
                        <ul class=\"dropdown-menu\" role=\"menu\">
                            
                            
                        ";
                if($produto == ''){
                    $produto = "todos";
                }    
                //print_r($new_prazos);   
                
                foreach($new_prazos as $key=>$value)
                {
                    //echo $array_dependentes[$ie];
                  if($key == '0'){
                        $key_ = "recorrente";
                        $key_numero = $key;
                    }else{
                        $key_ = $key." meses";
                        $key_numero = $key;
                    }
                    
                    $sql_clientes_dependente = "SELECT id_cliente FROM clientes
                    WHERE $sql_data_emissao
                    AND id_parceiro = $id_parceiro $sql_sel_filial AND tipo_movimento IN ('IN', 'AL')  $sql_versao_produto AND prazo = $key AND id_cliente_principal > 0 AND status IN (99, 0, 3) GROUP BY chave";
                   
                    $query_clientes_dependente      = mysql_query($sql_clientes_dependente, $banco_produto) or die(mysql_error()." - 8");
                            $contar_dependentes_prazo = 0;        
                    if (mysql_num_rows($query_clientes_dependente)>0)
                    {
                        $contar_dependentes_prazo           = mysql_num_rows($query_clientes_dependente);
                    }
                    
                    
                    $value_p = $value - $contar_dependentes_prazo;
                    
                    echo "
                    <li>
                    <a onclick=\"return gerar_faturamento($id_parceiro, $key_numero, $grupo_produto, $id_servico, $value_p, '$produto', '$mes_data1', $contar_dependentes_prazo, '$data1_convert', '$data2_convert', $id_produto, 'N', '$todos_clientes_ativos');\" href=\"javascript:\"><i class=\"fa fa-calculator\"></i> $value - $key_ </a>
                    </li>
                    ";
                   
                }
                
 
                
                //grupos
                foreach($new_grupos as $key_grupo=>$value_grupo)
                {
                    //echo $array_dependentes[$ie];
                  if($key_grupo == '8'){
                    $key_ = "INDIVIDUAL";
                    $key_numero = $key_grupo;
                }else{
                    $key_ = "FAMILIAR";
                    $key_numero = $key_grupo;
                }
                
                
                    
                    $sql_clientes_dependente = "SELECT id_cliente FROM clientes
                    WHERE $sql_data_emissao
                    AND id_parceiro = $id_parceiro $sql_sel_filial AND tipo_movimento IN ('IN', 'AL')  $sql_versao_produto AND id_produto = $key_grupo AND id_cliente_principal > 0 AND status IN (99, 0, 3) GROUP BY chave";
                   
                    $query_clientes_dependente      = mysql_query($sql_clientes_dependente, $banco_produto) or die(mysql_error()." - 8");
                    $contar_dependentes_grupo = 0;        
                    if (mysql_num_rows($query_clientes_dependente)>0)
                    {
                        $contar_dependentes_grupo           = mysql_num_rows($query_clientes_dependente);
                    }
                    
                    
                    $value_p = $value_grupo - $contar_dependentes_grupo;
                    
                    echo "
                    <li>
                    <a onclick=\"return gerar_faturamento($id_parceiro, '$key_', $grupo_produto, $id_servico, $value_p, '$produto', '$mes_data1', $contar_dependentes_grupo, '$data1_convert', '$data2_convert', $key_grupo, 'S', '$todos_clientes_ativos', '$id_filial');\" href=\"javascript:\"><i class=\"fa fa-calculator\"></i> $value_grupo - $key_ </a>
                    </li>
                    ";
                   
                }
                
                echo "</ul>
                    </div>";
                
            }else{
                echo "Sem venda ativa";
            }
            echo "</td>";
            $total_ativos = $total_registros - $contar_cancelados;
            
            echo "<td>";
            if($total_ativos > 0){
                echo "  <a onclick=\"return gerar_faturamento($id_parceiro, 'todos', $grupo_produto, $id_servico, $contar_ativos, '$produto', '$mes_data1', $contar_dependentes, '$data1_convert', '$data2_convert', $id_produto, 'N');\" href=\"javascript:\" ><i class=\"fa fa-calculator\"></i> $total_ativos - Todos                    
                    </a>";
            }else{
                echo "&nbsp;";
            }
  
            echo "</td>";
            echo "</tr>";
            
            $total_soma_vendas = $total_registros + $total_soma_vendas;
            $total_soma_ativos = $contar_ativos + $total_soma_ativos;
            $total_soma_cancelados = $contar_cancelados + $total_soma_cancelados;
            $total_soma_adicionais = $contar_dependentes + $total_soma_adicionais;
       } 
       
       if($i == 0)
       {
            echo "<tr>
            <td>Sem registros</td>
            </tr>";
       }
       
       
       echo "</tbody></table> 
               </div>
                </div>
                
                <div class=\"row\">
                    <div id=\"resultado_calculo_faturamento\">
                    
                    </div>
                </div>";
                
    
   
   
    }elseif($slug == 'sorteio_ead'){
        
    }

}
?>
                                   
                                <div class="row">
                                    <div class="col-xs-4">
                                        <?php 
                                        if($slug == 'europ' AND $tipo != 'faturamento'){
                                        ?>
                                            <ul class="list-unstyled amounts">
                                                <?php
                                                foreach($new_prazos as $key=>$value)
                                                {
                                                  if($key == '0'){
                                                        $key_ = "Recorrente";
                                                    }else{
                                                        $key_ = $key." meses";
                                                    }
                                                    
                                                    echo "<li><strong>$key_:</strong> $value</li>";
                                                }
                                                
                                                ?>

                                            </ul>
                                        <?php  
                                        }
                                        ?>
                                        
                                        
                                    </div>
                                    <div class="col-xs-8 invoice-block">
                                        <?php 
                                        if($slug == 'europ' AND $tipo != 'faturamento'){
                                        ?>
                                        <ul class="list-unstyled amounts">
                                            <li>
                                                <strong>Total de Vendas:</strong> <?php echo $total_soma_vendas; ?> </li>
                                                <li>
                                                <strong>Vendas Titular Ativas:</strong> <?php echo $total_soma_ativos; ?> </li>
                                                <li>
                                                <strong>Vendas Adicionais Ativas:</strong> <?php echo $total_soma_adicionais; ?> </li>
                                                <li>
                                                <strong>Vendas Titular Inativas:</strong> <?php echo $total_soma_inativos; ?> </li>
                                                <li>
                                                <strong>Vendas Canceladas:</strong> <?php echo $total_soma_cancelados; ?> </li>
                                                <li>
                                                <strong>Total Geral Ativas:</strong> <?php echo $total_soma_vendas - $total_soma_cancelados; ?> </li>
                                            
                                            
                                        </ul>
                                         <?php  
                                        }
                                        ?>
                                        <br/>
                                        
                                    </div>
                                    <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                                                    <span> &nbsp;&nbsp;Aguarde... </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
   