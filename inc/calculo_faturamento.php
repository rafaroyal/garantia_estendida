

<script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>

<?php

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;

$id_parceiro            = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);  
$periodo                = (empty($_GET['periodo'])) ? "" : verifica($_GET['periodo']);
$grupo_produtos         = (empty($_GET['grupo_produtos'])) ? "" : verifica($_GET['grupo_produtos']);  
$quantidade             = (empty($_GET['quantidade'])) ? "" : verifica($_GET['quantidade']); 
$id_produto             = (empty($_GET['id_produto'])) ? "" : verifica($_GET['id_produto']); 
$id_servico             = (empty($_GET['id_servico'])) ? "" : verifica($_GET['id_servico']); 
$mes_referencia         = (empty($_GET['mes_referencia'])) ? "" : verifica($_GET['mes_referencia']); 
$dependentes            = (empty($_GET['dependentes'])) ? "" : verifica($_GET['dependentes']); 
$data1                  = (empty($_GET['data1'])) ? "" : verifica($_GET['data1']); 
$data2                  = (empty($_GET['data2'])) ? "" : verifica($_GET['data2']); 
$id_produto_plano       = (empty($_GET['id_produto_plano'])) ? "" : verifica($_GET['id_produto_plano']); 
$grupo_plano            = (empty($_GET['grupo_plano'])) ? "" : verifica($_GET['grupo_plano']); 
$todos_clientes_ativos  = (empty($_GET['todos_clientes_ativos'])) ? "" : verifica($_GET['todos_clientes_ativos']);
$id_filial              = (empty($_GET['id_filial'])) ? "" : verifica($_GET['id_filial']);
  
if($mes_referencia == 'Janeiro'){
    $numero_mes_referencia = '1';
}elseif($mes_referencia == 'Fevereiro'){
    $numero_mes_referencia = '2';
}elseif($mes_referencia == 'Marco'){
    $numero_mes_referencia = '3';
}elseif($mes_referencia == 'Abril'){
    $numero_mes_referencia = '4';
}elseif($mes_referencia == 'Maio'){
    $numero_mes_referencia = '5';
}elseif($mes_referencia == 'Junho'){
    $numero_mes_referencia = '6';
}elseif($mes_referencia == 'Julho'){
    $numero_mes_referencia = '7';
}elseif($mes_referencia == 'Agosto'){
    $numero_mes_referencia = '8';
}elseif($mes_referencia == 'Setembro'){
    $numero_mes_referencia = '9';
}elseif($mes_referencia == 'Outubro'){
    $numero_mes_referencia = '10';
}elseif($mes_referencia == 'Novembro'){
    $numero_mes_referencia = '11';
}elseif($mes_referencia == 'Dezembro'){
    $numero_mes_referencia = '12';
}

    $sql        = "SELECT nome'nome_parceiro' FROM parceiros
                        WHERE id_parceiro = $id_parceiro";
    $query      = mysql_query($sql, $banco_painel);
    $nome_parceiro = '';                
    if (mysql_num_rows($query)>0)
    {
        $nome_parceiro = mysql_result($query, 0, 'nome_parceiro');    
    }

?>

<script>

jQuery(document).ready(function() {
    
   var preco_uni_custo   = $("#preco_uni_custo").val();
   preco_uni_custo = preco_uni_custo.replace(",", ".");
   
   var quantidade_vendas = parseInt($("#quantidade_vendas").val());
   var soma_total = parseFloat(preco_uni_custo) * quantidade_vendas;
   soma_total = soma_total.toFixed(2);
   soma_total = soma_total.replace(".", ",");
   $("#valor_total").val(soma_total);

   if($("#preco_uni_custo").val().length < 1){
        $("#valor_total").val('');
   }else{
        $("#valor_total").val(soma_total);
   }
   

   $("#valor_total_geral").val(soma_total);

});

$( "#preco_uni_custo" ).keyup(function() {
  
  $("#parcelas_faturamento").val('');
  $(".info_parcelas").html('');
  
   var preco_uni_custo   = $("#preco_uni_custo").val();
   preco_uni_custo = preco_uni_custo.replace(",", ".");
   
   var quantidade_vendas = parseInt($("#quantidade_vendas").val());
   var soma_total = parseFloat(preco_uni_custo) * quantidade_vendas;
   soma_total = soma_total.toFixed(2);
   soma_total = soma_total.replace(".", ",");
   $("#valor_total").val(soma_total);

   if($("#preco_uni_custo").val().length < 1){
        $("#valor_total").val('');
        $("#bt_registrar_fat").attr("disabled","");
   }else{
        $("#valor_total").val(soma_total);
   }
   
   
   if($("#valor_total").val().length < 1){
        var valor_total  = 0;
    }else{
        var valor_total            = $("#valor_total").val();
        valor_total                = valor_total.replace(",", ".");
    }
   

   if($("#valor_total_adicional").val().length < 1){
        var valor_total_adicional  = 0;
    }else{
        var valor_total_adicional  = $("#valor_total_adicional").val();
        valor_total_adicional      = valor_total_adicional.replace(",", ".");
    }
  
  
   var soma_total_geral = parseFloat(valor_total) + parseFloat(valor_total_adicional);
   
   soma_total_geral = soma_total_geral.toFixed(2);
   soma_total_geral = soma_total_geral.replace(".", ",");
   $("#valor_total_geral").val(soma_total_geral);      
});

$( "#preco_uni_adicional" ).keyup(function() {
  
  $("#parcelas_faturamento").val('');
  $(".info_parcelas").html('');
  
  var preco_uni_adicional  = $("#preco_uni_adicional").val();
  preco_uni_adicional = preco_uni_adicional.replace(",", ".");
   
  var quantidade_adicional = parseInt($("#quantidade_adicionais").val());
  var soma_total_adicional = parseFloat(preco_uni_adicional) * quantidade_adicional;
  
  soma_total_adicional = soma_total_adicional.toFixed(2);
  soma_total_adicional = soma_total_adicional.replace(".", ",");
  $("#valor_total_adicional").val(soma_total_adicional);
  
  if($("#preco_uni_adicional").val().length < 1){
        $("#valor_total_adicional").val('');
        $("#bt_registrar_fat").attr("disabled","");
   }else{
        $("#valor_total_adicional").val(soma_total_adicional);
   }
   
   var valor_total            = $("#valor_total").val();
   valor_total                = valor_total.replace(",", ".");
   
   
   if($("#valor_total_adicional").val().length < 1){
        var valor_total_adicional  = 0;
    }else{
        var valor_total_adicional  = $("#valor_total_adicional").val();
        valor_total_adicional      = valor_total_adicional.replace(",", ".");
    }
   
  
   var soma_total_geral = parseFloat(valor_total) + parseFloat(valor_total_adicional);
   
   soma_total_geral = soma_total_geral.toFixed(2);
   soma_total_geral = soma_total_geral.replace(".", ",");
   $("#valor_total_geral").val(soma_total_geral);
   
   
});

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$( "#parcelas_faturamento" ).keyup(function() {
    $(".div_aguarde_2").show();
  delay(function(){
   var valor_total            = $("#valor_total_geral").val();
   valor_total = valor_total.replace(",", ".");
   
   var parcelas_faturamento   = parseInt($("#parcelas_faturamento").val());
   
   
   
   var valor_parcelas  = parseFloat(valor_total) / parcelas_faturamento;
   valor_parcelas = valor_parcelas.toFixed(2);
   valor_parcelas = valor_parcelas.replace(".", ",");
   
   var html_parcelas;
   //var junta_html_parcelas;
   $(".info_parcelas").html('');
   //for(i = 1;i <= parcelas_faturamento; i++){

   $.get('inc/html_parcelas_novo.php?parcelas='+parcelas_faturamento+'&valor_parcela='+valor_parcelas,function (dados) { $(".div_aguarde_2").hide(); $(".info_parcelas").append(dados);}); 

   //html_parcelas_novo = '<div class="col-xs-6">&nbsp;</div><div class="col-xs-2"><h4>' + i + '</h4></div><div class="col-xs-2"> <input class="form-control form-control-inline input-medium date-picker" size="16" type="text" value="" /><span class="help-block"> Select date </span></div><div class="col-xs-2"><h4 style="float: right;">R$ ' + valor_parcelas + '</h4></div>';
   
   //$(".info_parcelas").append(html_parcelas_novo);
  //}
   
   //html_parcelas_novo = '<div class="col-xs-6">&nbsp;</div><div class="col-xs-3"><h4>' + parcelas_faturamento + '</h4></div><div class="col-xs-3"><h4 style="float: right;">R$ ' + valor_parcelas + '</h4><input type="hidden" id="valor_parcelas" value="' + valor_parcelas + '" disabled=""/> </div>';
   //$(".info_parcelas").html(html_parcelas_novo);
   
   if($("#parcelas_faturamento").val().length < 1 && $("#parcelas_faturamento").val() == 0 ){
       $("#bt_registrar_fat").attr("disabled","");
   }else{
       $("#bt_registrar_fat").removeAttr('disabled');
   }
   }, 1000);
});

function fechar_tela_faturamento(){
{   
    $("#resultado_calculo_faturamento").html('');
}};

function registrar_faturamento(id_parceiro, grupo_produtos, id_servico, mes_referencia, periodo,quantidade, dependentes, data1, data2, id_faturamento, id_produto_plano, grupo_plano, todos_clientes_ativos, id_filial){
{   
    $(".div_aguarde_2").show();
    
    var valor = $("#preco_uni_custo").val();
    var valor_adicional = $("#preco_uni_adicional").val();
    var parcelas        = $("#parcelas_faturamento").val();
    var soma_total      = $("#valor_total_geral").val();
    var valor_parcelas   = $("#valor_parcelas").val();
    var mes_referencia = $('#mes_referencia option:selected').val();
    var texto_mes_referencia = $('#mes_referencia option:selected').text();
    var id_faturamento   = $("#id_faturamento_atual").val();
    //input:checkbox[name=ProductCode]
    var vencimentos   = $("input[name='vencimentos[]']").serializeArray();
    //alert(vencimentos);
    //$.get('inc/clientes_faturados.php?id_parceiro='+id_parceiro+'&id_servico='+id_servico+'&periodo='+periodo+'&grupo_produtos='+grupo_produtos+'&quantidade='+quantidade+'&mes_referencia='+mes_referencia+'&dependentes='+dependentes+'&valor='+valor+'&valor_adicional='+valor_adicional+'&parcelas='+parcelas+'&data1='+data1+'&data2='+data2+'&soma_total='+soma_total+'&valor_parcelas='+valor_parcelas+'&id_faturamento='+id_faturamento+'&vencimentos='+vencimentos,function (dados) { $("#lista_cliente_faturado").html(dados); $(".div_aguarde_2").hide(); $('#campos_calculos_faturamento').remove(); $('#sel_mes_ref').html(texto_mes_referencia);});
     $.ajax({ 
     type: "POST",
     url:  "inc/clientes_faturados.php",
     data: {
        id_parceiro: id_parceiro,
        id_servico: id_servico,
        periodo: periodo,
        grupo_produtos: grupo_produtos,
        quantidade: quantidade,
        mes_referencia: mes_referencia,
        dependentes: dependentes,
        valor: valor,
        valor_adicional: valor_adicional,
        parcelas: parcelas,
        data1: data1,
        data2: data2,
        soma_total: soma_total,
        valor_parcelas: valor_parcelas,
        id_faturamento: id_faturamento,
        id_produto_plano: id_produto_plano,
        grupo_plano: grupo_plano,
        todos_clientes_ativos: todos_clientes_ativos,
        id_filial: id_filial,
        vencimentos: vencimentos
        },
     success: function(dados){
     $("#lista_cliente_faturado").html(dados); 
    $(".div_aguarde_2").hide(); 
    $('#campos_calculos_faturamento').remove(); 
    $('#sel_mes_ref').html(texto_mes_referencia);
     } 
     });    
    
    /*$.post('inc/clientes_faturados.php', {
        id_parceiro: id_parceiro,
        id_servico: id_servico,
        periodo: periodo,
        grupo_produtos: grupo_produtos,
        quantidade: quantidade,
        mes_referencia: mes_referencia,
        dependentes: dependentes,
        valor: valor,
        valor_adicional: valor_adicional,
        parcelas: parcelas,
        data1: data1,
        data2: data2,
        soma_total: soma_total,
        valor_parcelas: valor_parcelas,
        id_faturamento: id_faturamento,
        vencimentos: vencimentos
        
    }).done(function( dados ) {
    $("#lista_cliente_faturado").html(dados); 
    $(".div_aguarde_2").hide(); 
    $('#campos_calculos_faturamento').remove(); 
    $('#sel_mes_ref').html(texto_mes_referencia);
  });*/

}};
$( "#mes_referencia" ).change(function() {
  var mes_referencia    = $('#mes_referencia option:selected').val();
  var id_servico        = $("#id_servico").val();
  var grupo_produtos    = $("#grupo_produtos").val();
  var periodo           = $("#periodo_").val();
  var data1             = $("#data1").val();
  var id_parceiro       = $("#id_parceiro_get").val();
  var id_produto_plano  = $("#id_produto_plano").val();
  var id_filial_get     = $("#id_filial_get").val();
  
  if(mes_referencia.length > '')
  {
    $.get('inc/ver_info_faturado.php?numero_mes_referencia='+mes_referencia+"&grupo_produtos="+grupo_produtos+"&id_produto_plano="+id_produto_plano+"&id_servico="+id_servico+"&periodo="+periodo+"&data1="+data1+"&id_parceiro="+id_parceiro+"&id_filial="+id_filial_get,function (dados) { $("#info_faturado").html(dados);});
  }
   
});

</script>
<div class="modal-header">
        <button type="button" class="close" onclick="return fechar_tela_faturamento();"></button>
        <h4 class="modal-title"><?php echo $nome_parceiro; 
        $sql_sel_filial = '';
        if($id_filial > 0){
            $sql_filial        = "SELECT nome FROM filiais
            WHERE id_filial = $id_filial";
            $query_filial      = mysql_query($sql_filial, $banco_painel);
            $nome_filial = '';     
                       
            if (mysql_num_rows($query_filial)>0)
            {
                $nome_filial = mysql_result($query_filial, 0, 0);  
                echo $nome_filial;  
                
                $sql_sel_filial = 'AND id_filial = '.$id_filial;
            }
        }
        
        ?> - <?php 
                        if($periodo == 'todos'){
                            $exibe_pediodo = "Todos os períodos";
                            $se_prazo = '';
                        }elseif($periodo == 0){
                            $exibe_pediodo = "Recorrente";
                            
                            $se_prazo = "AND prazo = $periodo";
                        }else{
                            $exibe_pediodo = $periodo." meses";
                            $se_prazo = "AND prazo = $periodo";
                        }
                        
                        if($periodo == 'INDIVIDUAL'){
                            $exibe_pediodo = "FATURAMENTO INDIVIDUAL";
                            $se_prazo = '';
                        }
                        
                        if($periodo == 'FAMILIAR'){
                            $exibe_pediodo = "FATURAMENTO FAMILIAR";
                            $se_prazo = '';
                        }
                        $ano_referencia = date('Y');
                        echo $exibe_pediodo." - <span id=\"sel_mes_ref\"></span> - $ano_referencia"; 
                        
                        ?>
        </h4>
               
</div>
        <div class="col-xs-12" id="campos_calculos_faturamento">
            <div class="row">
            <div class="col-md-12">
            
            <input type="hidden" name="grupo_produtos" id="grupo_produtos" value="<?php echo $grupo_produtos;?>" />
            <input type="hidden" name="id_servico" id="id_servico" value="<?php echo $id_servico;?>" />
            <input type="hidden" name="periodo_" id="periodo_" value="<?php echo $periodo;?>" />
            <input type="hidden" name="data1" id="data1" value="<?php echo $data1;?>" />
            <input type="hidden" name="id_parceiro_get" id="id_parceiro_get" value="<?php echo $id_parceiro;?>" />
            <input type="hidden" name="id_produto_plano" id="id_produto_plano" value="<?php echo $id_produto_plano;?>" />
            <input type="hidden" name="id_filial_get" id="id_filial_get" value="<?php echo $id_filial;?>" />
            
            <div class="col-md-3" style="padding: 0;">
            
                <div class="form-group">
                <label class="control-label ">Mês Referência</label>
                <select class="bs-select form-control" data-live-search="true" data-size="8" id="mes_referencia" name="mes_referencia">
                        <option value="1" <?php $mes_referencia_sel = ($mes_referencia == 'Janeiro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Janeiro</option>
                        <option value="2" <?php $mes_referencia_sel = ($mes_referencia == 'Fevereiro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Fevereiro</option>
                        <option value="3" <?php $mes_referencia_sel = ($mes_referencia == 'Marco') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Março</option>
                        <option value="4" <?php $mes_referencia_sel = ($mes_referencia == 'Abril') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Abril</option>
                        <option value="5" <?php $mes_referencia_sel = ($mes_referencia == 'Maio') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Maio</option>
                        <option value="6" <?php $mes_referencia_sel = ($mes_referencia == 'Junho') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Junho</option>
                        <option value="7" <?php $mes_referencia_sel = ($mes_referencia == 'Julho') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Julho</option>
                        <option value="8" <?php $mes_referencia_sel = ($mes_referencia == 'Agosto') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Agosto</option>
                        <option value="9" <?php $mes_referencia_sel = ($mes_referencia == 'Setembro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Setembro</option>
                        <option value="10" <?php $mes_referencia_sel = ($mes_referencia == 'Outubro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Outubro</option>
                        <option value="11" <?php $mes_referencia_sel = ($mes_referencia == 'Novembro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Novembro</option>
                        <option value="12" <?php $mes_referencia_sel = ($mes_referencia == 'Dezembro') ? 'selected=""' : ''; echo $mes_referencia_sel; ?>>Dezembro</option>
                    </select>
                </div>
                 &nbsp;
                </div>
            </div>
            <div id="info_faturado">
            <?php
                $cortar_data1 = substr($data1, 0, 4);
                $sql_verifica_faturamento = "SELECT id_faturamento, quantidade, valor, quantidade_adicional, valor_adicional, quantidade_total, parcelas, data_cadastro FROM faturamentos
        WHERE id_parceiro = $id_parceiro $sql_sel_filial AND id_grupo_produto = $grupo_produtos AND id_produto_grupo = $id_produto_plano AND id_servico = $id_servico $se_prazo AND mes_referencia = $numero_mes_referencia AND ano_referencia LIKE '$cortar_data1%'";
                    //echo $sql_verifica_faturamento;
                    $query_verifica_faturamento      = mysql_query($sql_verifica_faturamento, $banco_painel) or die(mysql_error()." - 7");
                    $id_faturamento_atual = '';
                    if (mysql_num_rows($query_verifica_faturamento)>0)
                    { 
                        $id_faturamento_atual = mysql_result($query_verifica_faturamento, 0, 'id_faturamento');
                        $quantidade_atual           = mysql_result($query_verifica_faturamento, 0, 'quantidade');
                        $valor_atual                = mysql_result($query_verifica_faturamento, 0, 'valor');
                        $quantidade_adicional_atual = mysql_result($query_verifica_faturamento, 0, 'quantidade_adicional');
                        $valor_adicional_atual      = mysql_result($query_verifica_faturamento, 0, 'valor_adicional');
                        $quantidade_total_atual     = mysql_result($query_verifica_faturamento, 0, 'quantidade_total');
                        $parcelas_atual = mysql_result($query_verifica_faturamento, 0, 'parcelas');
                        $data_cadastro_atual = mysql_result($query_verifica_faturamento, 0, 'data_cadastro');
                        $data_cadastro_atual = converte_data($data_cadastro_atual);
                        echo "<div class=\"col-xs-12\"><h5 style='color: red;'> Período de <strong>$mes_referencia</strong> já faturado em $data_cadastro_atual. Os registros abaixo serão substituido:</h5>
                        <p>Vendas: <strong>$quantidade_atual</strong></p>
                        <p>Valor: <strong>$valor_atual</strong></p>
                        <p>Adicional: <strong>$quantidade_adicional_atual</strong></p>
                        <p>Valor Adicional: <strong>$valor_adicional_atual</strong></p>
                        <p>Quant. Total: <strong>$quantidade_total_atual</strong></p>
                        <p>Parcelas: <strong>$parcelas_atual</strong></p></div>
                        ";
                    }
            echo "<input type=\"hidden\" name=\"id_faturamento_atual\" id=\"id_faturamento_atual\" value=\"$id_faturamento_atual\" />";
            ?>  
            </div>       
            <div class="col-xs-2">
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <div class="col-xs-12">
                        <p class="form-control-static"> &nbsp; </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label class="control-label col-xs-12"><strong>Vendas:</strong></label>
                    <div class="col-xs-12">
                        <p class="form-control-static"> <?php echo $quantidade; ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <div class="col-xs-12">
                        <p class="form-control-static"> &nbsp; </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label class="control-label col-xs-12"><strong>Adicional:</strong></label>
                    <div class="col-xs-12">
                        <p class="form-control-static"> <?php echo $dependentes; ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label class="control-label col-xs-12"><strong>Total:</strong></label>
                    <div class="col-xs-12">
                        <p class="form-control-static"> <?php echo $quantidade + $dependentes; ?> </p>
                    </div>
                </div>
            </div>
             <div class="col-xs-2">
                <div class="form-group">
                    <label class="control-label col-xs-12">&nbsp;</label>
                    <div class="col-xs-12">
                        <p class="form-control-static"> &nbsp; </p>
                    </div>
                </div>
            </div>
            <!--/span-->
            <hr />
            </div>
            
            <!--<div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12"><strong>Grupo do produto:</strong></label>
                    <div class="col-xs-12">
                        <p class="form-control-static"> <?php
                        
                        $sql        = "SELECT nome'nome_grupo' FROM grupos_produtos
                        WHERE id_grupo_produto = $grupo_produtos";
                        $query      = mysql_query($sql, $banco_painel);
                        $nome_parceiro = '';                
                        if (mysql_num_rows($query)>0)
                        {
                            $nome_grupo_produtos = mysql_result($query, 0, 'nome_grupo');    
                        }
                        
                         echo $nome_grupo_produtos; ?> </p>
                    </div>
                </div>
            </div>
            </div>-->            
            <!--/span-->
            <!--<div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12"><strong>Produto(s):</strong></label>
                    
                         <?php 
                        
                        $sql_par_produto        = "SELECT pro.nome'nome_produto', pas.preco_custo FROM grupos_produtos gpro
                        JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
                        JOIN produtos pro ON prog.id_produto = pro.id_produto
                        JOIN parceiros_servicos pas ON prog.id_produto = pas.id_produto
                        WHERE gpro.id_grupo_produto = $grupo_produtos AND pas.id_parceiro = $id_parceiro";
                $query_par_produto      = mysql_query($sql_par_produto, $banco_painel);
                            
                        if (mysql_num_rows($query_par_produto)>0)
                        {
                            $soma_preco = 0;
                            while ($dados = mysql_fetch_array($query_par_produto))
                            {
                                extract($dados); 
                                echo "<div class=\"col-xs-8\"><p class=\"form-control-static\">$nome_produto</p></div><div class=\"col-xs-4\"><p class=\"form-control-static\">".db_moeda($preco_custo)."</p></div>";
                                $soma_preco = $soma_preco + moeda_db($preco_custo);
                            }
                        }
                        
                         ?> 
                    
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12"></label>
                    
                         <?php 
                        echo "<div class=\"col-xs-8\"><p class=\"form-control-static\"><strong>Soma total por unidade:</strong></p></div><div class=\"col-xs-4\"><p class=\"form-control-static\"><strong>".db_moeda($soma_preco)."</strong></p></div>";

                         ?> 
                    
                </div>
            </div>
            
            </div>-->
            <!--/span-->
            <!--<h4>Calculos para Faturamento</h4> -->
            <input type="hidden" id="quantidade_vendas" value="<?php echo $quantidade; ?>" disabled=""/> 
            <input type="hidden" id="quantidade_adicionais" value="<?php echo $dependentes; ?>" disabled=""/> 
            <div class="row">
            
            <div class="col-xs-2">
            <div class="form-group">
                <label>Valor:</label>
                <div class="input-icon">
                    <i class="fa fa-dollar font-green"></i>
                    <input type="text" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" class="form-control" id="preco_uni_custo" placeholder="0,00" value="<?php echo str_replace("R$ ", "", db_moeda($soma_preco)); ?>" maxlength="6"/> 
                </div>
            </div>  
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                <label>Total:</label>
                <div class="input-icon">
                    <i class="fa fa-dollar font-green"></i>
                    <input type="text" class="form-control" id="valor_total" placeholder="Preço/Custo" value="" disabled=""/> 
                </div>
                </div>
            </div>
            <div class="col-xs-2">
            <div class="form-group">
            <?php
            if($dependentes == ''){
                $exibe_adicional = 'disabled=""';
                $dependentes = 0;
            }else{
                $exibe_adicional = '';
            }
            ?>
            <label>Adicional:</label>
                <div class="input-icon">
                    <i class="fa fa-dollar font-green"></i>
                    <input <?php echo $exibe_adicional;?> type="text" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" class="form-control" id="preco_uni_adicional" placeholder="0,00" value="" maxlength="6"/> 
                </div>
            </div>  
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                
                <label>Total Ad.:</label>
                <div class="input-icon">
                    <i class="fa fa-dollar font-green"></i>
                    <input type="text" class="form-control" id="valor_total_adicional" placeholder="Preço/Custo" value="" disabled=""/> 
                </div>
              
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                <label>Soma Total:</label>
                <div class="input-icon">
                    <i class="fa fa-dollar font-green"></i>
                    <input type="text" class="form-control" id="valor_total_geral" placeholder="Preço/Custo" value="" disabled=""/> 
                </div>
                </div>
            </div>

            <div class="col-xs-2">
                <div class="form-group">
                <label>Parc.:</label>
                <div class="input-icon">
                    <i class="fa fa-scissors font-green"></i>
                    <input type="text" class="form-control" id="parcelas_faturamento" placeholder="Parcelar em:" value=""/> 
                    
                </div>
                </div> 
            </div>
            <!--/span-->
            </div>
            <div class="row">
                <div class="col-xs-6">
                &nbsp;
                </div>
                <div class="col-xs-2">
                <strong><h5>Parcelas</h5></strong>
                </div>
                <div class="col-xs-2">
                <strong><h5>Vencimentos</h5></strong>
                </div>
                <div class="col-xs-2">
                <strong><h5 style="float: right;">Parcela de</h5></strong>
                </div>
                <div class="info_parcelas">

                </div>
            </div>
            <div class="modal-footer">
            <span class="div_aguarde_2" style="display: none;position: absolute;width: 100%;right: 320px;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
                <button type="button" class="btn default" onclick="return fechar_tela_faturamento();">Fechar</button>
                <?php
                echo "<button type=\"button\" class=\"btn blue\" id=\"bt_registrar_fat\" disabled=\"\" onclick=\"return registrar_faturamento($id_parceiro, $grupo_produtos, $id_servico, '$mes_referencia', '$periodo', $quantidade, $dependentes, '$data1', '$data2', '$id_faturamento_atual', $id_produto_plano, '$grupo_plano', '$todos_clientes_ativos', '$id_filial');\">Registrar Faturamento</button> ";
                ?>
                
            </div>
        </div>

<div id="lista_cliente_faturado">
   
</div>

