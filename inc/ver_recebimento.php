<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

         <script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
         <script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>

<?php
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$id_pagamento            = (empty($_GET['id_pagamento'])) ? "" : verifica($_GET['id_pagamento']);  
$recebimento_faturamento = (empty($_GET['recebimento_faturamento'])) ? "" : verifica($_GET['recebimento_faturamento']);  

$sql        = "SELECT * FROM pagamentos
            WHERE id_pagamento = $id_pagamento";

$query      = mysql_query($sql, $banco_painel);
                
if (mysql_num_rows($query)>0)
{
    $dados = mysql_fetch_array($query);
    extract($dados);
        
}

$sql        = "SELECT nome'nome_parceiro' FROM parceiros
            WHERE id_parceiro = $id_parceiro";
$query      = mysql_query($sql, $banco_painel);
$nome_parceiro = '';                
if (mysql_num_rows($query)>0)
{
    $nome_parceiro = mysql_result($query, 0);    
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

//$cortar_periodo_inicio_ano = substr($periodo_inicio, 0, 4);
$cortar_periodo_inicio_ano = $ano_referencia;
?>

<script>
function check_confirma_pagamento(){
{
  
if($("#confirmar_pagamento").is(':checked'))
    $("#confirmado_recebimento").show();
else
    $("#confirmado_recebimento").hide();
    
}};

function confirma_pagamento(id_pagamento, id_grupo_produto){
{

  var vencimento        = $("#vencimento").val();
  var recebimento       = $("#recebimento").val();
  var valor_recebido    = $("#valor_recebido").val();
  var observacoes       = $("#observacoes").val();
    
    if($("#confirmar_pagamento").is(':checked')){
        var pagamento = 'S';
        recebimento = recebimento;
        valor_recebido = valor_recebido;
    }else{
        var pagamento = 'N';
        recebimento = '';
        valor_recebido = '';
    }
    
    if($("#confirmar_baixa_boletos").is(':checked')){
        var confirmar_baixa_boletos = 'S';
    }else{
        var confirmar_baixa_boletos = 'N';
    }

    $(".div_aguarde_2").show(); 
    
    $.ajax({ 
     type: "POST",
     url:  "editar_db.php",
     data: {
        item: 'pagamentos',
        pagamento: pagamento,
        id_pagamento: id_pagamento,
        vencimento: vencimento,
        recebimento: recebimento,
        valor_recebido: valor_recebido,
        id_grupo_produto: id_grupo_produto,
        confirmar_baixa_boletos: confirmar_baixa_boletos,
        observacoes: observacoes
        },
     success: function(dados){
        
         if(dados == 'pago'){
            $('#bt_pagamento_'+id_pagamento).removeClass('red').addClass('green').text('Pago');
            $(".div_aguarde_2").hide(); 
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else if(dados == 'nao_pago'){
            $('#bt_pagamento_'+id_pagamento).removeClass('green').addClass('red').text('Receber e Baixar parcelas');
            $(".div_aguarde_2").hide(); 
         $('#ajax').hide();
         $('.modal-backdrop').remove();
         $('body').removeClass('modal-open-noscroll').removeClass('modal-open');
         }else{
            alert('erro');
         }

            $('.modal-content').html('Aguarde...');
        
     } 
     });    
        
  //}
   
}};


</script>

<div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    
    <h4 class="modal-title"><?php echo $nome_parceiro; ?> </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
        
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
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Mês Faturado:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php echo $numero_mes_referencia." de ".$cortar_periodo_inicio_ano; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Quantidade faturados:</strong></label>
                    <div class="col-md-12">
                        <p class="form-control-static"> <?php 
                        
                        $sql_quant        = "SELECT id_servico, quantidade, quantidade_adicional, quantidade_total, baixa_boletos FROM faturamentos
                                            WHERE id_faturamento = $id_faturamento";
                        
                        $query_quant      = mysql_query($sql_quant, $banco_painel);
                                        
                        if (mysql_num_rows($query_quant)>0)
                        {
                            $dados = mysql_fetch_array($query_quant);
                            extract($dados);
                            
                            echo "Titular: ".$quantidade.", Adicional: ".$quantidade_adicional." = ".$quantidade_total;    
                        }
                        
                         ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Vencimento:</strong></label>

                        <div class="" data-date-format="dd-mm-yyyy" >
        <input type="text" name="vencimento" id="vencimento" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" value="<?php echo converte_data($data_vencimento); ?>" readonly >
    </div> 
                    
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
            

            <h4>Pagamento</h4>
            <div class="row">
            <div class="col-md-12">
                <div class="md-checkbox-list">
                    <div class="md-checkbox">
                        <input type="checkbox" name="confirmar_pagamento" value="S" id="confirmar_pagamento" class="md-check"  onclick="return check_confirma_pagamento();"  <?php $sel_pagamento = ($pago == 'S') ? 'checked="" disabled=""' : ''; echo $sel_pagamento; ?> />
                        <label for="confirmar_pagamento">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span> Confirmar Pagamento </label>
                    </div>
                    
                </div>
            </div>
            </div>
            <div class="row" id="confirmado_recebimento" <?php $sel_pagamento = ($pago == 'S') ? 'style="display: block;"' : 'style="display: none;"'; echo $sel_pagamento; ?> >
            <?php
            
            if($recebimento_faturamento == 'S'){
            ?>
                <input type="hidden" name="recebimento_faturamento" value="<?php echo $recebimento_faturamento; ?>"/>
                <div class="col-md-12">
                    <div class="md-checkbox-list">
                        <div class="md-checkbox">
                            <input type="checkbox" name="confirmar_baixa_boletos" value="S" id="confirmar_baixa_boletos" class="md-check"  <?php $sel_pagamento = ($baixa_boletos == 'S') ? 'checked="" disabled=""' : ''; echo $sel_pagamento; ?> />
                            <label for="confirmar_baixa_boletos">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> Confirmar Baixa Boleto(s) </label>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-12">
                    <p> Os pagamentos dos clientes vínculados a esse faturamento, serão automaticamente baixados.</p>
                </div>
            <?php
            }?>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Data recebimento:</strong></label>

                        <div class="" data-date-format="dd-mm-yyyy" >
        <input type="text" name="recebimento" id="recebimento" class="form-control form-control-inline date-picker" data-date-format="dd-mm-yyyy" value="<?php
            if($pago == 'S'){
                echo converte_data($data_pagamento);
            }else{
                $agora 			= date("Y-m-d");
                echo converte_data($agora);
            }
             ?>" readonly >
    </div> 
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label col-md-12"><strong>Valor recebido:</strong></label>

                    <input type="text" name="valor_recebido" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" class="form-control" id="valor_recebido" placeholder="0,00" value="<?php 
                    if($pago == 'S'){
                        echo str_replace("R$ ", "", db_moeda($valor_recebido));
                    }else{
                        echo str_replace("R$ ", "", db_moeda($valor_parcela));
                    }
                    
                     ?>" maxlength="6" />     
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
        </div>
    </div>
</div>
<div class="modal-footer">
<span class="div_aguarde_2" style="display: none;position: relative;width: 100%;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
    <a data-toggle="modal" href="#excluir" class="btn btn-sm red btn-outline sbold">
                    <i class="fa fa-times"></i> Cancelar</a>
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    <a href="javascript:" onclick="return confirma_pagamento(<?php echo $id_pagamento.", '$id_servico'";?>);" class="btn blue">Salvar pagamento</a>
</div>
<div class="modal fade modal-scroll" id="excluir" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Excluir Pagamento</h4>
        </div>
        <div class="modal-body"> Tem certeza que deseja excluir o pagamento? A alteração não poderá ser revertida! <br />
        
        <strong>Tem certeza que deseja confirmar?</strong></div>
       <div class="modal-footer">
            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
            <button type="button" onclick="window.location.href='excluir.php?item=pagamento_faturamento&id=<?php echo $id_pagamento; ?>'"  class="btn green">Sim, confirmar!</button>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>