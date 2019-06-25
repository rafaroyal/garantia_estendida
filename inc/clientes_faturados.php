
<?php

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
/*
$id_parceiro         = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);  
$id_servico          = (empty($_GET['id_servico'])) ? "" : verifica($_GET['id_servico']);  
$prazo             = $_GET['periodo'];
$grupo_produtos      = (empty($_GET['grupo_produtos'])) ? "" : verifica($_GET['grupo_produtos']);  
$quantidade          = (empty($_GET['quantidade'])) ? "" : verifica($_GET['quantidade']); 
$valor               = (empty($_GET['valor'])) ? "" : verifica($_GET['valor']); 
$valor_adicional     = (empty($_GET['valor_adicional'])) ? "" : verifica($_GET['valor_adicional']); 
$parcelas            = (empty($_GET['parcelas'])) ? "" : verifica($_GET['parcelas']); 
$mes_referencia      = (empty($_GET['mes_referencia'])) ? "" : verifica($_GET['mes_referencia']); 
$dependentes         = (empty($_GET['dependentes'])) ? "" : verifica($_GET['dependentes']); 
$data1               = (empty($_GET['data1'])) ? "" : verifica($_GET['data1']); 
$data2               = (empty($_GET['data2'])) ? "" : verifica($_GET['data2']); 
$soma_total          = (empty($_GET['soma_total'])) ? "" : verifica($_GET['soma_total']); 
$valor_parcelas      = (empty($_GET['valor_parcelas'])) ? "" : verifica($_GET['valor_parcelas']); 
$id_faturamento_atual= (empty($_GET['id_faturamento'])) ? "" : verifica($_GET['id_faturamento']); 
$vencimentos         = (empty($_GET['vencimentos'])) ? "" : verifica($_GET['vencimentos']); 
*/
$id_parceiro            = (empty($_POST['id_parceiro'])) ? "" : verifica($_POST['id_parceiro']);  
$id_servico             = (empty($_POST['id_servico'])) ? "" : verifica($_POST['id_servico']);  
$prazo                  = $_POST['periodo'];
$grupo_produtos         = (empty($_POST['grupo_produtos'])) ? "" : verifica($_POST['grupo_produtos']);  
$quantidade             = (empty($_POST['quantidade'])) ? "" : verifica($_POST['quantidade']); 
$valor                  = (empty($_POST['valor'])) ? "" : verifica($_POST['valor']); 
$valor_adicional        = (empty($_POST['valor_adicional'])) ? "" : verifica($_POST['valor_adicional']); 
$parcelas               = (empty($_POST['parcelas'])) ? "" : verifica($_POST['parcelas']); 
$mes_referencia         = (empty($_POST['mes_referencia'])) ? "" : verifica($_POST['mes_referencia']); 
$dependentes            = (empty($_POST['dependentes'])) ? "" : verifica($_POST['dependentes']); 
$data1                  = (empty($_POST['data1'])) ? "" : verifica($_POST['data1']); 
$data2                  = (empty($_POST['data2'])) ? "" : verifica($_POST['data2']); 
$soma_total             = (empty($_POST['soma_total'])) ? "" : verifica($_POST['soma_total']); 
$valor_parcelas         = (empty($_POST['valor_parcelas'])) ? "" : verifica($_POST['valor_parcelas']); 
$id_faturamento_atual   = (empty($_POST['id_faturamento'])) ? "" : verifica($_POST['id_faturamento']); 
$grupo_plano            = (empty($_POST['grupo_plano'])) ? "" : verifica($_POST['grupo_plano']); 
$id_produto_plano       = (empty($_POST['id_produto_plano'])) ? "" : verifica($_POST['id_produto_plano']); 
$todos_clientes_ativos  = (empty($_POST['todos_clientes_ativos'])) ? "" : verifica($_POST['todos_clientes_ativos']);
$id_filial              = (empty($_POST['id_filial'])) ? "" : verifica($_POST['id_filial']);
$vencimentos            = $_POST['vencimentos']; 

//echo "venc:".$vencimentos[0]['value'];
if($prazo == 'todos'){
    $prazo = '';
    $se_prazo = "";
}else{
    $se_prazo = "AND prazo = $prazo";
}

if($grupo_plano == 'S'){
    $se_prazo = "AND id_produto = $id_produto_plano";
    
    
}
$sql_sel_filial = "";
if($id_filial <> '' AND $id_filial <> 'undefined'){
    // está setado, seleciona clienta apenas da filial x
    $sql_sel_filial = "AND (id_filial = $id_filial OR id_filial_integracao = $id_filial)";
    $verifica_sql_sel_filial = $id_filial;
}

    $sql_base        = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                JOIN servicos serv ON pro.id_servico = serv.id_servico
                                        WHERE serv.id_servico = $id_servico
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
        $sql_nome_grupo_plano = "SELECT nome FROM grupos_produtos
        WHERE id_grupo_produto = $id_produto_plano";
        $query_nome_grupo_plano      = mysql_query($sql_nome_grupo_plano, $banco_painel) or die(mysql_error()." - 7.5");
        $nome_grupo_plano = '';
        if (mysql_num_rows($query_nome_grupo_plano)>0)
        {
            $nome_grupo_plano = mysql_result($query_nome_grupo_plano, 0,0);
        }
        
        $quantidade_total = $quantidade + $dependentes;
        $soma_total_db = moeda_db($valor) + moeda_db($valor_adicional);
        $ano_referencia = explode("-", $data2);
        
        if($id_faturamento_atual > 0){
            $sql2    = "UPDATE faturamentos SET mes_referencia = '$mes_referencia', ano_referencia = '$ano_referencia[0]', periodo_inicio = '$data1', periodo_fim = '$data2', prazo = '$prazo', quantidade = '$quantidade', valor = '".moeda_db($valor)."', quantidade_adicional = '$dependentes', valor_adicional = '".moeda_db($valor_adicional)."', quantidade_total = '$quantidade_total', soma_total = '$soma_total_db', parcelas = '$parcelas', data_cadastro = '".agora()."', todo_periodo = '$todos_clientes_ativos', id_filial = '$id_filial' WHERE id_faturamento = $id_faturamento_atual";
            $query2  = mysql_query($sql2, $banco_painel) OR DIE (mysql_error());
            
            $sql_4    = "DELETE FROM pagamentos WHERE id_faturamento = $id_faturamento_atual";
            $query_4  = mysql_query($sql_4, $banco_painel);
            
            if($parcelas > 0){
                $contar_parcelas = $parcelas - 1;
                for($i=0;$i<=$contar_parcelas;$i++){
                    $n_i = $i + 1;
                    $quantidade_total = $quantidade + $dependentes;
                    $soma_total_db = moeda_db($valor) + moeda_db($valor_adicional);
                    $sql_boleto    = "INSERT INTO pagamentos (id_parceiro, id_faturamento, mes_referencia, ano_referencia, periodo_inicio, periodo_fim, parcela, total_parcelas, valor_parcela, data_cadastro, data_vencimento, obs, todo_periodo)
                    VALUES ('$id_parceiro', '$id_faturamento_atual', '$mes_referencia', '$ano_referencia[0]', '$data1', '$data2', '$n_i', '$parcelas', '".moeda_db($valor_parcelas)."', '".agora()."', '".converte_data($vencimentos[$i]['value'])."', '$nome_grupo_plano', '$todos_clientes_ativos')";       
                    $query_boleto  = mysql_query($sql_boleto, $banco_painel) or die(mysql_error());
                }
     
            }
            
        }else{
            $sql3    = "INSERT INTO faturamentos (id_parceiro, id_grupo_produto, id_produto_grupo, id_servico, mes_referencia, ano_referencia, periodo_inicio, periodo_fim, prazo, quantidade, valor, quantidade_adicional, valor_adicional, quantidade_total, soma_total, parcelas, data_cadastro, todo_periodo, id_filial)
                                VALUES ('$id_parceiro', '$grupo_produtos', '$id_produto_plano', '$id_servico', '$mes_referencia', '$ano_referencia[0]', '$data1', '$data2', '$prazo', '$quantidade', '".moeda_db($valor)."', '$dependentes', '".moeda_db($valor_adicional)."', '$quantidade_total', '$soma_total_db', '$parcelas', '".agora()."', '$todos_clientes_ativos', '$id_filial')";       
            $query3  = mysql_query($sql3, $banco_painel) or die(mysql_error());
            
            $id_faturamento = mysql_insert_id($banco_painel);
            
            if($parcelas > 0){
                $contar_parcelas = $parcelas - 1;
                for($i=0;$i<=$contar_parcelas;$i++){
                    $n_i = $i + 1;
                    $quantidade_total = $quantidade + $dependentes;
                    $soma_total_db = moeda_db($valor) + moeda_db($valor_adicional);
                    $sql_boleto    = "INSERT INTO pagamentos (id_parceiro, id_faturamento, mes_referencia, ano_referencia, periodo_inicio, periodo_fim, parcela, total_parcelas, valor_parcela, data_cadastro, data_vencimento, obs, todo_periodo)
                                            VALUES ('$id_parceiro', '$id_faturamento', '$mes_referencia', '$ano_referencia[0]', '$data1', '$data2', '$n_i', '$parcelas', '".moeda_db($valor_parcelas)."', '".agora()."', '".converte_data($vencimentos[$i]['value'])."', '$nome_grupo_plano', '$todos_clientes_ativos')";       
                    $query_boleto  = mysql_query($sql_boleto, $banco_painel) or die(mysql_error());
                    
                }
     
            }
        }
        
        

    ?>
<script>

$('#printButton_faturamento').on('click', function () {
    
        $('#sample_1_wrapper').css('display', 'none');
        /*$("#" + modalId).css('visibility', 'hidden');
        $("#" + modalId + " .modal-body").css('visibility', 'visible');
        $("#" + modalId + " .modal-footer").css('visibility', 'hidden');*/

        window.print();
        $('#sample_1_wrapper').css('display', 'block');

        /*$("#" + modalId).css('visibility', 'visible');
        $("#" + modalId + " .modal-body").css('visibility', 'visible');
        $("#" + modalId + " .modal-footer").css('visibility', 'visible');*/
    
});

</script>
    <div class="col-xs-12">
       <a class="btn btn-lg blue hidden-print margin-bottom-5" id="printButton_faturamento" > Imprimir Faturamento
            <i class="fa fa-print"></i>
       </a>
    </div>
     <div class="col-xs-12">
        <h4>Clientes Faturados - <?php echo $nome_grupo_plano; ?></h4>
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3">
                    <strong>Valor Titular: </strong>
                </div>
                <div class="col-xs-9">
                    <?php echo "R$ ".db_moeda3($valor); ?>
                </div>
                  <div class="col-xs-3">
                    <strong>Valor Adicional: </strong>
                </div>
                <div class="col-xs-9">
                    <?php echo "R$ ".db_moeda3($valor_adicional); ?>
                </div>
             <table class="table table-condensed table-hover">
             <thead>
                <tr>
                    <th> ID </th>
                    <th> Cliente </th>
                    <th> CPF </th>
                    <th> Data Nascimento </th>
                    <?php
                    if($grupo_plano == 'N'){
                    ?>
                        <th> Prazo </th>
                        <th> Data Início </th>
                    <?php
                    }
                    ?>
                    <th> Valor Mensal </th>
                </tr>
             </thead>
             <tbody>

            <?php
            
            $data1 = str_replace("-", "/", $data1);
            $data2 = str_replace("-", "/", $data2);
            $sql_data_emissao = "(data_inicio BETWEEN '$data1' AND '$data2')";
            $agora 			= date("Y-m-d");
            if($todos_clientes_ativos == 'S'){
                $sql_data_emissao = "data_inicio <= '$agora'";
            }
            $sql_clientes_ativos = "SELECT * FROM clientes
            WHERE $sql_data_emissao
            AND id_parceiro = $id_parceiro $sql_sel_filial AND tipo_movimento IN ('IN', 'AL') $se_prazo AND status IN (99, 0, 3) GROUP BY chave";

            $query_clientes_ativos      = mysql_query($sql_clientes_ativos, $banco_produto) or die(mysql_error()." - 7");
        
            if (mysql_num_rows($query_clientes_ativos)>0)
            { 
                while($dados_clientes_ativos = mysql_fetch_array($query_clientes_ativos))
                {
                    extract($dados_clientes_ativos);

                    $plano_add = '';
                    if($id_cliente_principal == 0){
                        $exibe_id_principal = "";
                        
                      $sql_add_cliente_ativo = "SELECT COUNT(*) FROM clientes
                      WHERE id_cliente_principal = $id_cliente AND status IN (99, 0, 3)";
            
                      $query_add_cliente_ativo      = mysql_query($sql_add_cliente_ativo, $banco_produto) or die(mysql_error()." - 7.5");
                      $contar_add_cliente = 0;
                      if (mysql_num_rows($query_add_cliente_ativo)>0)
                      {
                            $contar_add_cliente = mysql_result($query_add_cliente_ativo, 0,0);
                      }
                       $valor_add_convert = moeda_db($valor_adicional);
                       $valor_titular_convert = moeda_db($valor);
                       $soma_valor_parcela = ($contar_add_cliente * $valor_add_convert) + $valor_titular_convert;
                       
                       $exibe_valor = db_moeda($soma_valor_parcela);  
                        
                    }else{
                        $exibe_id_principal = $id_cliente_principal;
                        $exibe_valor = "<i class=\"fa fa-arrow-up\"></i>";
                    }
                    
                    if($id_cliente_principal > 0)
                    {
                        $plano_add = ' <span class="label label-sm label-info">D</span>';
                    }
                    $tr_adicional = '';
                    $td_fonte = '';
                    if($id_cliente_principal > 0){
                        $tr_adicional = "class=\"info\"";
                        $td_fonte = "style=\"font-size: 10px;\"";
                        
                        
                    }

                        echo "<tr $tr_adicional>
                        <td $td_fonte> $id_cliente </td>
                        <td $td_fonte> $nome $plano_add</td>";
                        if($prazo == 0){$exibe_prazo = "Recorrente";}else{$exibe_prazo = $prazo." meses";}
                        
                        echo "<td $td_fonte> $cpf </td>";
                        echo "<td $td_fonte> ".converte_data($data_nascimento)." </td>";
                        if($grupo_plano == 'N'){
                            echo "<td $td_fonte> $exibe_prazo </td>
                            <td $td_fonte>".converte_data($data_inicio)."</td>";
                        }
                        
                        echo "<td $td_fonte>$exibe_valor</td>
                        </tr>";
                    
                }
            }
            ?>
             <tr>
             <td colspan="5" style="text-align: right;">Quantidade total: <strong><?php  echo  $quantidade + $dependentes; ?></strong> Soma Total: </td>
             <td><strong> <?php echo "R$ ".$soma_total;?></strong></td>
             </tr>
             <tr>
             <td colspan="5" style="text-align: right;"><strong><?php  echo  $parcelas; ?></strong> parcela(s) de</td>
             <td><strong> <?php echo "R$ ".$valor_parcelas;?></strong></td>
             </tr>
             </tbody>
             </table>

                
            </div>
        </div>
     </div>   
        
        
        
    <?php    
    }
    
    
?>

<script>

jQuery(document).ready(function() {
    
   

});

</script>


