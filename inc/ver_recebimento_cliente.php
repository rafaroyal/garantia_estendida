<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>

<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$id_boleto = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']);  
$id_usuario_s   = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s  = base64_decode($_COOKIE["usr_parceiro"]);
$usr_nivel      = base64_decode($_COOKIE["usr_nivel"]);

$sql        = "SELECT * FROM boletos_clientes
            WHERE id_boleto = $id_boleto";

$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);
        
}

if($id_usuario_recebimento == ''){
    $id_usuario_recebimento = 0;
    $id_usuario_html = $id_usuario_s;
}else{
    $id_usuario_html = $id_usuario_recebimento;
}

$sql_parceiro        = "SELECT nome'nome_usuario' FROM usuarios
                                         WHERE id_usuario = $id_usuario_recebimento";
$query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
$nome_usuario = '';   
if (mysql_num_rows($query_parceiro)>0)
{
    $nome_usuario        = mysql_result($query_parceiro, 0,'nome_usuario');
}else{
    
    $sql_parceiro        = "SELECT nome'nome_usuario', nivel_status FROM usuarios
                                         WHERE id_usuario = $id_usuario_s";
    $query_parceiro      = mysql_query($sql_parceiro, $banco_painel);
    $nome_usuario = '';   
    $nivel_status = '';
    if (mysql_num_rows($query_parceiro)>0)
    {
        $nome_usuario        = mysql_result($query_parceiro, 0,'nome_usuario');
        $nivel_status        = mysql_result($query_parceiro, 0,'nivel_status');
    }
    
}

$sql_ordem   = "SELECT ordem_pedido FROM ordem_pedidos
                        WHERE id_ordem_pedido = $id_ordem_pedido";

        $query_ordem = mysql_query($sql_ordem, $banco_painel) or die(mysql_error()." - 0");
        
        if (mysql_num_rows($query_ordem)>0)
        {
            $ordem_pedido       = mysql_result($query_ordem, 0, 'ordem_pedido');
            //$status_recorrencia = mysql_result($query_ordem, 0, 'status_recorrencia');
    
            $array_id_base_ids_vendas = explode("|", $ordem_pedido);
            
            $contar_array_id_base_ids_vendas = count($array_id_base_ids_vendas) - 1;
            for($i=0;$contar_array_id_base_ids_vendas>=$i;$i++)
            {
                $array_ids_base_vendas = explode("_", $array_id_base_ids_vendas[$i]);
                $id_base = $array_ids_base_vendas[0];
                $ids_vendas = explode("-", $array_ids_base_vendas[1]);
                
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
                    
                    $sql_venda  = "SELECT c.id_cliente, c.nome, c.status FROM vendas v
                                    JOIN clientes c ON v.id_cliente = c.id_cliente
                                    WHERE v.id_venda = $ids_vendas[0]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 2");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente     = mysql_result($query_venda, 0, 'id_cliente');
                        $nome_cliente   = mysql_result($query_venda, 0, 'nome');
                        $status_cliente = mysql_result($query_venda, 0, 'status');
                    }
                    $id_base = $id_base;
                }/*elseif($slug == 'sorteio_ead'){
                    
                    $sql_venda   = "SELECT c.id_venda, c.nome FROM vendas_painel v
                                    JOIN vendas c ON v.id_venda = c.id_venda
                                    JOIN titulos t ON c.id_titulo = t.id_titulo
                                    WHERE v.id_venda_painel = $ids_vendas[0]";
                    $query_venda = mysql_query($sql_venda, $banco_produto) or die(mysql_error()." - 5");
                    
                    if (mysql_num_rows($query_venda)>0)
                    {
                        $id_cliente      = mysql_result($query_venda, 0, 'id_venda');
                        $nome_cliente    = mysql_result($query_venda, 0, 'nome');
                    }
                    
                
                }*/
            }
            
         }





if($mes_referencia == '1'){
    $numero_mes_referencia = 'Janeiro';
}elseif($mes_referencia == '2'){
    $numero_mes_referencia = 'Fevereiro';
}elseif($mes_referencia == '3'){
    $numero_mes_referencia = 'Março';
}elseif($mes_referencia == '4'){
    $numero_mes_referencia = 'Abril';
}elseif($mes_referencia == '5'){
    $numero_mes_referencia = 'Maio';
}elseif($mes_referencia == '6'){
    $numero_mes_referencia = 'Junho';
}elseif($mes_referencia == '7'){
    $numero_mes_referencia = 'Julho';
}elseif($mes_referencia == '8'){
    $numero_mes_referencia = 'Agosto';
}elseif($mes_referencia == '9'){
    $numero_mes_referencia = 'Setembro';
}elseif($mes_referencia == '10'){
    $numero_mes_referencia = 'Outubro';
}elseif($mes_referencia == '11'){
    $numero_mes_referencia = 'Novembro';
}elseif($mes_referencia == '12'){
    $numero_mes_referencia = 'Dezembro';
}

$cortar_periodo_inicio_ano = substr($periodo_inicio, 0, 4);

?>

<script>
function check_confirma_pagamento(){
{
  
if($("#confirmar_pagamento").is(':checked'))
    $("#confirmado_recebimento").show();
else
    $("#confirmado_recebimento").hide();
    
}};

function confirma_pagamento(id_pagamento){
{

  $("#bt_salva_pagamento").attr("disabled", true);  
  var vencimento        = $("#vencimento").val();
  var recebimento       = $("#recebimento").val();
  var valor_recebido    = $("#valor_recebido").val();
  var observacoes       = $("#observacoes").val();
  var tipo_recebimento  = $("input[name=tipo_recebimento]:checked").val();
  
  var id_cliente        = $("#id_cliente").val();  
  var id_usuario        = $("#id_usuario").val();
  var id_parceiro       = $("#id_parceiro").val();
  var status_cliente    = $("#status_cliente").val();
  var id_base           = $("#id_base").val();
    
    if($("#confirmar_pagamento").is(':checked')){
        var pagamento = 'S';
        recebimento = recebimento;
        valor_recebido = valor_recebido;
    }else{
        var pagamento = 'N';
        recebimento = '0';
        valor_recebido = '';
    }
    
    if($("#alterar_todos_boletos").is(':checked')){
        var alterar_todos_boletos = 'S';
    }else{
        var alterar_todos_boletos = 'N';
    }
    $(".div_aguarde_2").hide(); 
    
    $.ajax({ 
     type: "POST",
     url:  "editar_db.php",
     data: {
        item: 'boletos_clientes',
        pagamento: pagamento,
        alterar_todos_boletos: alterar_todos_boletos,
        id_pagamento: id_pagamento,
        vencimento: vencimento,
        recebimento: recebimento,
        valor_recebido: valor_recebido,
        observacoes: observacoes,
        tipo_recebimento: tipo_recebimento,
        id_cliente: id_cliente,
        id_usuario: id_usuario,
        id_parceiro: id_parceiro,
        status_cliente: status_cliente,
        id_base: id_base
        },
     success: function(dados){
         if(dados == 'pago'){
            $('#bt_boleto_'+id_pagamento).removeClass('red').addClass('green').html('<i class="fa fa-check"></i>');
            $(".div_aguarde_2").hide(); 
            $('#pg_boleto_'+id_pagamento).removeClass('hide');
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else if(dados == 'nao_pago'){
            $('#bt_boleto_'+id_pagamento).removeClass('green').addClass('red').text('Receber');
            $(".div_aguarde_2").hide(); 
            $('#pg_boleto_'+id_pagamento).addClass('hide');
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else{
            alert('erro');
         }
         
         if(alterar_todos_boletos == 'S'){
            alert('Necessário atualizar a página para verificar novas datas.')
         }
         
        $("#bt_salva_pagamento").removeAttr("disabled");
        $('.modal-content').html('Aguarde...');
     } 
     });    
        
  //}
   
}};
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $nome_cliente; ?> </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_s; ?>"/>
        <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro_s; ?>"/>
        <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>"/>
        <input type="hidden" name="status_cliente" id="status_cliente" value="<?php echo $status_cliente; ?>"/>
        <input type="hidden" name="id_base" id="id_base" value="<?php echo $id_base; ?>"/>
            <h4>Informações</h4>
            <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Parcela:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $parcela." de ".$total_parcelas; ?> </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Referência:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $numero_mes_referencia." de ".$ano_referencia; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Vencimento:</strong></label>
<?php

 
 if($usr_nivel == 'A'){
    if(in_array("61", $verifica_lista_permissoes_array_inc)){
?>
                        <div class="" data-date-format="dd-mm-yyyy" >
        <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" value="<?php echo converte_data($data_vencimento); ?>" readonly >
    </div> 
<?php
    }else{
    ?>
        <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="<?php echo converte_data($data_vencimento); ?>" readonly="" />
    <?php
    }
}elseif($usr_nivel == 'P' OR $usr_nivel == 'U' OR $nivel_status == 1){
?>
<input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline " value="<?php echo converte_data($data_vencimento); ?>" readonly="" />
<?php
}
?>
                    
                </div>
            </div>
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Data Cadastro:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo converte_data($data_cadastro);?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Valor:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo db_moeda($valor_parcela)?></p>
                    </div>
                </div>
            </div>
            <!--/span-->
            </div>
            <?php
            
            if($usr_nivel == 'A' AND in_array("61", $verifica_lista_permissoes_array_inc)){
            ?>
                <div class="row"> 
                    <div class="col-md-4">
                        <div class="md-checkbox-list">
                            <div class="md-checkbox">
                                <input type="checkbox" name="alterar_todos_boletos" value="S" id="alterar_todos_boletos" class="md-check" />
                                <label for="alterar_todos_boletos">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Alterar próximos boletos</label>
                                                        
                            </div>
                            
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <h4>Pagamento</h4>
            <div class="row">
            <div class="col-md-12">
                <div class="md-checkbox-list">
                    
<?php
    $sql_opcoes  = "SELECT valor FROM opcoes
    WHERE nome = 'porcento_multa_vencimento_boleto' OR nome = 'porcento_valor_diario_vencimento_boleto' OR nome = 'dias_nao_receber_atraso_boleto' ";
    $query_opcoes = mysql_query($sql_opcoes, $banco_painel) or die(mysql_error()." - 2");
    
    if (mysql_num_rows($query_opcoes)>0)
    {
        $porcento_multa_vencimento_boleto          = mysql_result($query_opcoes, 0,0);
        $porcento_valor_diario_vencimento_boleto   = mysql_result($query_opcoes, 1,0);
        $dias_nao_receber_atraso_boleto            = mysql_result($query_opcoes, 2, 0);
    }
                    
 
 
 $agora  = date("Y-m-d");

 // Calcula a diferença em segundos entre as datas
 $diferenca = strtotime($agora) - strtotime($data_vencimento);

 //Calcula a diferença em dias
 $dias_de_atrado = floor($diferenca / (60 * 60 * 24));
 $atrasado = false;
 if($dias_de_atrado > $dias_nao_receber_atraso_boleto){
    if($usr_nivel != 'A'){
        if($pago == 'N'){
            $atrasado = true;
        }
        
    }
    
 }
 

 if($atrasado == false OR in_array("50", $verifica_lista_permissoes_array_inc)){
    $input_id_html = 'id="confirmar_pagamento"';
    $input_onclick_html = 'onclick="return check_confirma_pagamento();"';
    $input_txt_nome = "Confirmar Pagamento";
    
    if(in_array("58", $verifica_lista_permissoes_array_inc)){
    }else{
        if($pago == 'S'){
            $input_id_html = '';
            $input_onclick_html = 'onclick="return false;"';
            $input_txt_nome = "Pago!";
        }
        
    }

?>                    
        <div class="md-checkbox">
            <input type="checkbox" name="confirmar_pagamento" value="S" <?php echo $input_id_html;?> class="md-check" <?php echo $input_onclick_html;
            $sel_pagamento = ($pago == 'S') ? 'checked=""' : ''; echo $sel_pagamento; ?> />
            
          
            <label for="confirmar_pagamento">
                <span></span>
                <span class="check"></span>
                <span class="box"></span> <?php echo $input_txt_nome;?></label>

                    
                </div>
                </div>
            </div>
            </div>
            <div class="row" id="confirmado_recebimento" <?php $sel_pagamento = ($pago == 'S') ? 'style="display: block;"' : 'style="display: none;"'; echo $sel_pagamento; ?> >
            
            <?php
            $data_vencimento_semana = date("w", strtotime($data_vencimento)); // 0

            $verifica_prox_dia_util = false;
            if(strtotime($data_vencimento) < strtotime($agora) AND $pago == 'N')
            {
                if($data_vencimento_semana == 0 OR $data_vencimento_semana == 6){
                    // busca proxima dia util de vencimento sem juros
                    
                    /**
                    * Função para calcular o próximo dia útil de uma data
                    * Formato de entrada da $data: AAAA-MM-DD
                    */
                    function proximoDiaUtil($data, $saida = 'd/m/Y') {
                    // Converte $data em um UNIX TIMESTAMP
                    $timestamp = strtotime($data);
                    // Calcula qual o dia da semana de $data
                    // O resultado será um valor numérico:
                    // 1 -> Segunda ... 7 -> Domingo
                    $dia = date('N', $timestamp);
                    // Se for sábado (6) ou domingo (7), calcula a próxima segunda-feira
                    if ($dia >= 6) {
                    $timestamp_final = $timestamp + ((8 - $dia) * 3600 * 24);
                    } else {
                    // Não é sábado nem domingo, mantém a data de entrada
                    $timestamp_final = $timestamp;
                    }
                    return date($saida, $timestamp_final);
                    }
                    
                    // Calcula o próximo dia útil usando uma formatação de saída
                    $prox_dia_util_vencimento = proximoDiaUtil($data_vencimento, 'Y-m-d');
                    // Resultado: 2009-04-06
                    if(strtotime($prox_dia_util_vencimento) == strtotime($agora)){
                        $verifica_prox_dia_util = false;
                    }else{
                        $verifica_prox_dia_util = true;
                    }
                    
                }else{
                    $verifica_prox_dia_util = true;
                }
                
                
                
                if($verifica_prox_dia_util == true){

            ?>
                <div class="col-md-12">
                    <p class="form-control-static">
                    
                     Valor Atual: <?php echo db_moeda($valor_parcela);?> <br />
                     Multa/atraso: <?php echo $porcento_multa_vencimento_boleto;?>% <br />
                     Multa/diário: <?php echo $porcento_valor_diario_vencimento_boleto;?>%<br />
                     <hr />
                     
                     <?php
                     $juros_de_atraso = porcentagem_juros_atraso($valor_parcela, $porcento_multa_vencimento_boleto);
                     echo "Multa de atraso: ".db_moeda($juros_de_atraso).'<br/>';
                     $porcento_valor_diario_vencimento_boleto = $porcento_valor_diario_vencimento_boleto / 30;                    
                     $juros_diario =  (($porcento_valor_diario_vencimento_boleto * $dias_de_atrado));
                     echo "Juros diário:  ".$dias_de_atrado." dia(s) de atraso no valor de ".db_moeda($juros_diario)."<br/>";

                     $valor_atualizado = number_format($juros_de_atraso, 2, '.', '') + $valor_parcela + $juros_diario;
                     
                     //$valor_atualizado = number_format($valor_atualizado, 2, '.', '');
                      $valor_parcela = $valor_atualizado;
                     ?>
                     
                     
                     Valor Atualizado: <strong><?php echo db_moeda($valor_atualizado);?></strong>
                    </p>
                </div>
            
            <?php 
                }
            }               
            ?>
            <div class="col-md-12">
                <div class="form-group form-md-radios">
                    <div class="md-radio-list">
                        <div class="md-radio">
                            <input type="radio" id="tipo_recebimento_av" name="tipo_recebimento" class="md-radiobtn" value="AV" checked=""/>
                            <label for="tipo_recebimento_av">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span> À vista dinheiro </label>
                        </div>
                        <div class="md-radio">
                            <input type="radio" id="tipo_recebimento_ca" name="tipo_recebimento" class="md-radiobtn" value="CA" />
                            <label for="tipo_recebimento_ca">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> Cartão crédito / débito </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Data recebimento:</strong></label>

                        <div class="" data-date-format="dd-mm-yyyy" >
<?php
 
 if($usr_nivel == 'A'){

?>

        <input type="text" name="recebimento" id="recebimento" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" value="<?php
            if($pago == 'S'){
                echo converte_data($data_pagamento);
            }else{
                $agora 			= date("Y-m-d");
                echo converte_data($agora);
            }
             ?>" readonly="" />
<?php
}elseif($usr_nivel == 'P' OR $usr_nivel == 'U'){
?>

 <input type="text" name="recebimento" id="recebimento" class="form-control form-control-inline" value="<?php
            if($pago == 'S'){
                echo converte_data($data_pagamento);
            }else{
                $agora 			= date("Y-m-d");
                echo converte_data($agora);
            }
             ?>" readonly >
<?php
}
?>
    </div> 
    <?php
    if($pago == 'S'){
    ?>
                <small><strong>às <?php echo $hora_pagamento;?></strong></small>
    <?php
    }
    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Valor recebido:</strong></label>

<?php
 
 if($usr_nivel == 'A' OR in_array("48", $verifica_lista_permissoes_array_inc)){
        
        if($pago == 'S'){
            $bloquear_valor_recebido = 'readonly=""';
            if(in_array("58", $verifica_lista_permissoes_array_inc) OR in_array("61", $verifica_lista_permissoes_array_inc)){
                $bloquear_valor_recebido = '';
            } 
        }
       
?>

                    <input type="text" name="valor_recebido" onkeydown="FormataMoeda(this,10,event)" <?php echo $bloquear_valor_recebido;?> onkeypress="return maskKeyPress(event)" class="form-control" id="valor_recebido" placeholder="0,00" value="<?php 
                    if($pago == 'S'){
                        echo str_replace("R$ ", "", db_moeda($valor_recebido));
                    }else{
                        echo str_replace("R$ ", "", db_moeda($valor_parcela));
                    }
                    
                     ?>" maxlength="6" />
                     
<?php
}elseif($usr_nivel == 'P' OR $usr_nivel == 'U'){
?>         
<p class="form-control-static"> <?php if($pago == 'S'){
                        echo db_moeda($valor_recebido);
                    }else{
                        echo db_moeda($valor_parcela);
                    }?></p>
 <input type="hidden" name="valor_recebido" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" class="form-control" id="valor_recebido" placeholder="0,00" value="<?php 
                    if($pago == 'S'){
                        echo str_replace("R$ ", "", db_moeda($valor_recebido));
                    }else{
                        echo str_replace("R$ ", "", db_moeda($valor_parcela));
                    }
                    
                     ?>" maxlength="6" />

<?php
}
?>            
                          
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Operador:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $id_usuario_html." - ".$nome_usuario;?> </p> 
                    </div>
                </div>
            </div>
            </div>
            <h4>Observações</h4>
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea class="form-control" rows="3" id="observacoes" ><?php echo $obs; ?></textarea>     
                </div>
            </div>
            </div>
<?php
}else{
?>    
<div class="row">
    <div class="col-md-12">
        <p class="form-control-static">
        Não foi possível efetuar o pagamento, pois consta com <?php echo $dias_de_atrado;?> dias de atraso.<br />
        Consulte seu Administrador.
        </p>
    </div>
</div>
<?php
}
?>
        </div>
    </div>
</div>
<div class="modal-footer">
<span class="div_aguarde_2" style="display: none;position: relative;width: 100%;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    <?php 
    if(in_array("61", $verifica_lista_permissoes_array_inc) OR in_array("58", $verifica_lista_permissoes_array_inc) OR $pago == 'N'){
    ?>
        <a href="javascript:" onclick="return confirma_pagamento(<?php echo $id_boleto;?>);" class="btn blue" id="bt_salva_pagamento">Salvar pagamento</a>
    <?php        
    }
    ?>
</div>