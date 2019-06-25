<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
//$banco_painel = $link;

$data               = (empty($_GET['data'])) ? "" : verifica($_GET['data']);  
$id_grupo_produto   = (empty($_GET['id_grupo_produto'])) ? "" : verifica($_GET['id_grupo_produto']);  
$id_produto         = (empty($_GET['id_produto'])) ? "" : verifica($_GET['id_produto']); 
$id_parceiro = base64_decode($_COOKIE["usr_parceiro"]);

//$data = explode("|", $data);
//if (in_array("europ", $data)) { 

//if($data == 'europ')
//{
    $sql_produto = '';
    if(!empty($id_produto))
    {
        $sql_produto = "AND pro.id_produto = $id_produto";
    }
    
    $sql_grupo_produto        = "SELECT pser.preco_venda FROM grupos_produtos gpro
    JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
    JOIN produtos pro ON prog.id_produto = pro.id_produto
    JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
    WHERE gpro.del = 'N' AND pser.id_parceiro = $id_parceiro AND gpro.id_grupo_produto = $id_grupo_produto $sql_produto AND pser.id_grupo_produto = $id_grupo_produto";
    $query_grupo_produto      = mysql_query($sql_grupo_produto);
                    
    if (mysql_num_rows($query_grupo_produto)>0)
    {
        $soma_produto = 0;
        while($dados_grupo_produto = mysql_fetch_array($query_grupo_produto))
        {
            extract($dados_grupo_produto);
            
            $soma_produto = $soma_produto + moeda_db($preco_venda);

        }
        
        
    }
    
    
?>
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>  
<script>

$( "#forma_pagamento" ).change(function() {
  $("#bt_add_submit").hide();  
  $('#bt_calcular_pagamento').hide();
  $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
  var valor         = $('#forma_pagamento option:selected').val();
  $('#select_prazo').html('');
  
  $('.html_info_pagamento').hide();
  $('.html_forma_pagamento').hide();
  //$('#valor_entrada').val('');
  //$('#desconto').val('');
  //$('#prazo').val('');
  $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
  $("#desconto").removeAttr('readonly');  
  $("#tipo_desconto").removeAttr('readonly');  
    $("#valor_entrada").removeAttr('readonly');  
  if(valor == 'recorrente_cartao' || valor == 'fidelidade' || valor == 'entrada_recorrente_cartao') 
  {
    
    $('.exibe_soma_total').html('-');
    $('.exibe_soma_valor_total_parcela').html('-');
    $('#total_geral_assistencia').val('');
    $('.exibe_prazo').html('-');
    $('.prazo_periodo').hide();
    $('.exibe_inicio').html(' ');
    $('.exibe_termino').html(' ');
    
    $("#radioMA").prop( "checked", false );
    $("#radioON").prop( "checked", false );
    $("#radioBO").prop( "checked", false );
    
    $("#div_parcela_parcelas_boleto").hide();
    
    
    $("#div_radioMA").show();
    $("#div_radioON").show();
    $("#div_radioBO").hide();
    $('#bt_calcular_pagamento').show();
    $("#bt_calcular_pagamento").show();  
        
  }
  else
  {
    $("#radioMA").prop( "checked", false );
    $("#radioON").prop( "checked", false );
    $("#radioBO").prop( "checked", false );
    $("#div_parcela_parcelas_boleto").hide();
    
    if(valor == 'entrada_parcelado_boleto') 
    {
        $("#div_radioMA").hide();
        $("#div_radioON").hide();
        $("#div_radioBO").show();
    }else{
        $("#div_radioMA").show();
        $("#div_radioON").show();
        if(valor == 'avista') 
        {
            $("#div_radioBO").show();
        }else{
            $("#div_radioBO").hide();
        }
    }
    
    $('.exibe_soma_total').html('-');
    $('.exibe_soma_valor_total_parcela').html('-');
    $('#total_geral_assistencia').val('');
    $('.exibe_prazo').html('-');
    $('.prazo_periodo').hide();
    $('.exibe_inicio').html(' ');
    $('.exibe_termino').html(' ');
    $('#select_prazo').html("<select class='form-control' id='prazo' name='prazo'><option value=''></option><option value='12'>12 (Doze meses)</option></select><label for='estado_civil'>Prazo Vigência</label><span class='help-block'>Vigência em meses...</span>");
    $('#select_prazo option[value=""]').attr('selected','selected');
    
    //$('.prazo_periodo').show();
    
    $('#forma_pagamento').focus();
  }
  
});

function f_permitir_entrada(){
{   
    if ($('input[name=permitir_entrada]:checked').val() == 'S')
    {
        $('#bloco_entrada').show();
        $('#input_status_entrada').val('S');
        $('#valor_entrada').focus();
    }
   
    if ($('input[name=permitir_entrada]:checked').val() == 'N')
    {
         $('#bloco_entrada').hide(); 
         $('#input_status_entrada').val('N');
         //$('#valor_entrada').val('');
    } 
    
}}; 

//sel_metodo_pagamento
function sel_metodo_pagamento(){
{   
    $("#html_emissao_boleto").hide();
    if ($('input[name=metodo_pagamento]:checked').val() == 'MA')
    {
        $.get('inc/html_parcelas_cartao.php',function (dados) { $('.txt_metodo_pagamento').html(dados);});
        
        //$('.txt_metodo_pagamento').html("<strong>Máquina de Cartão</strong>. Antes de registrar venda, confirme o pagamento na máquina de cartão.<div class='col-md-5'><div class='form-group form-md-line-input form-md-floating-label'><div class='input-group input-group-sm'><div class='input-group-control'><input type='text' name='comprovante_maquina' class='form-control' id='comprovante_maquina' value=''/><label for='comprovante_maquina'>AUT. Comprovante</label><span class='help-block'>Somente números..</span></div><span class='input-group-btn btn-right'><a class='btn red btn-outline sbold' data-toggle='modal' href='#responsive'><i class='fa fa-info'></i></a></span></div></div></div>");
        
    }
    
    if ($('input[name=metodo_pagamento]:checked').val() == 'ON')
    {
        $('.txt_metodo_pagamento').html('<strong>Checkout Online</strong>. Você será redirecionado para página de Checkout da CIELO.');
        $("#bt_add_submit").show();
    }
    
    if ($('input[name=metodo_pagamento]:checked').val() == 'BO')
    {
        $.get('inc/html_parcelas_boleto.php',function (dados) { $('.txt_metodo_pagamento').html(dados);});
        
        /*$('.txt_metodo_pagamento').html("<strong>Boleto Parcelado</strong>. Após Finalizar cadastro, solicitar boleto(s) no Banco emissor.<input type=\"hidden\" name=\"valor_parcela_boleto\" id=\"valor_parcela_boleto\" value=\"\"/><div class='col-md-5' id=\"div_parcela_parcelas_boleto\"><div class='form-group form-md-line-input form-md-floating-label'><select class='form-control' id='parcela_parcelas_boleto' name='parcela_parcelas_boleto'><option value=''></option><option value='2'>2x</option><option value='3'>3x</option><option value='4'>4x</option><option value='5'>5x</option><option value='6'>6x</option><option value='7'>7x</option><option value='8'>8x</option><option value='9'>9x</option><option value='10'>10x</option><option value='11'>11x</option><option value='12'>12x</option></select><label for='parcela_parcelas_boleto'>Parcelar em:</label></div></div><div class='col-md-5 txt_parcela_boleto' style='margin-top: 30px;'></div>");*/
        //$("#parcela_parcelas_boleto").focus();
        $("#html_emissao_boleto").show();
    }
    
    $('#box_pro').show();
    
    
  
}};

$( document ).on( "change", "#prazo", function() {
//$( "#prazo" ).change(function() {
  $("#radioMA").prop( "checked", false );
  $("#radioON").prop( "checked", false );
  $("#radioBO").prop( "checked", false );  
  $("#div_parcela_parcelas_boleto").hide();
  $(".txt_parcela_boleto").hide();
  var prazo                         = $('#prazo option:selected').val();
  var valor_parcela                 = $('#soma_produto').val();
  
  var valor_entrada_auto_parceiro   = $("#valor_entrada_auto_parceiro").val();
  var divide_entrada_prazo = parseFloat(valor_entrada_auto_parceiro) / parseFloat(prazo);
  divide_entrada_prazo = divide_entrada_prazo.toFixed(2);
  var subtrai_valor_parcela_entrada = parseFloat(valor_parcela) - parseFloat(divide_entrada_prazo);
  subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.toFixed(2);
  subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.replace(".", ",");
  
  if(prazo != '' && typeof(prazo) !== 'undefined')
  {
    var soma_total = parseInt(prazo) * parseFloat(valor_parcela);
    soma_total = soma_total.toFixed(2);
    $('#total_geral_assistencia').val(soma_total);
    soma_total = soma_total.replace(".", ",");
    var valor_parcela_exibe_html = valor_parcela.replace(".", ",");
    $('.exibe_soma_total').html(' R$ ' + soma_total);
    $('.exibe_soma_valor_total_parcela').html(' R$ ' + subtrai_valor_parcela_entrada);
    var prazo_selecionado;
    //var 
    if(prazo == 1){
        prazo_selecionado = '1 (um) mês de contratacão';
    }else if(prazo == 6){
        prazo_selecionado = '6 (Seis) meses de contratacão';
    }else if(prazo == 12){
        prazo_selecionado = '12 (Doze) meses de contratacão';
    }else if(prazo == 24){
        prazo_selecionado = '24 (Vinte e quatro) meses de contratacão';
    }else if(prazo == 36){
        prazo_selecionado = '36 (Trinta e seis) meses de contratacão';
    }else if(prazo == 48){
        prazo_selecionado = '48 (Quarenta e oito) meses de contratacão';
    }
    
   var data      = new Date(); // instancia um objeto da classe Date com a data do sistema.
   var data_hoje = new Date(); // instancia um objeto da classe Date com a data do sistema.
    data.setMonth(data.getMonth() + parseInt(prazo) + 1); 
    
    data_hoje.setMonth(data_hoje.getMonth() + 1);

	//alert(data_hoje);
    
    $('.exibe_prazo').html(prazo_selecionado);
    
    //var dt = new Date();
    var month = data.getMonth();
    var day = data.getDate();
    var year = data.getFullYear();
    
    function setDateZero(date){
        return date < 10 ? '0' + date : date;
    }
    
    if(month == 0)
    {
        month = 1;
    }
    
    var mes_atual = data_hoje.getMonth();
    
    if(data_hoje.getMonth() == 0)
    {
        mes_atual = 1;
    }
    
    $('.exibe_termino').html(setDateZero(day) + '-' + setDateZero(month) + '-' + year);
    
    $('.exibe_inicio').html(setDateZero(data_hoje.getDate()) + '-' + setDateZero(mes_atual) + '-' + data_hoje.getFullYear());
    
    $('#bt_calcular_pagamento').show();
    $("#bt_calcular_pagamento").show();  
  }
  else
  {
    $('#bt_calcular_pagamento').hide();
    $("#bt_calcular_pagamento").hide();
    $('.exibe_soma_total').html('-');
    $('.exibe_soma_valor_total_parcela').html('-');
    $('#total_geral_assistencia').val('');
  }
  
  
});

$( document ).on( "change", "#parcela_parcelas_boleto", function() {

  var prazo                       = $('#prazo option:selected').val();
  var parcela_parcelas_boleto     = $('#parcela_parcelas_boleto option:selected').val();
  //var valor_parcela               = $('#soma_produto').val();
  var total_geral_assistencia     = $('#total_geral_assistencia').val();
  
  
  if(prazo != '' && typeof(prazo) !== 'undefined' && parcela_parcelas_boleto != '' && typeof(parcela_parcelas_boleto) !== 'undefined')
  {
    if ($('#input_status_entrada').val() == 'S')
    {
        var soma_total_parcela = parseFloat(total_geral_assistencia) / parseFloat(parcela_parcelas_boleto);
        soma_total_parcela = soma_total_parcela.toFixed(2);
        
        $('#valor_parcela_boleto').val(soma_total_parcela);
        
        soma_total_parcela = soma_total_parcela.replace(".", ",");
        var valor_entrada = $('#valor_entrada').val();
        var tipo_valor_entrada = $('#tipo_calculo_entrada').val();
        
        $(".div_aguarde_2").show();
        $("#bt_add_submit").hide();
        if(tipo_valor_entrada == 'auto'){
            var parcela_menos_um =  parseFloat(parcela_parcelas_boleto) - 1;
            var valor_entrada_auto        = $("#valor_entrada_auto").val();
            valor_entrada_auto = valor_entrada_auto.replace(".", ",");
            var soma_total_parcela_calc   = soma_total_parcela.replace(",", ".");
            var soma_valor_entrada        = parseFloat(soma_total_parcela_calc) + parseFloat(valor_entrada_auto);
            soma_valor_entrada            = soma_valor_entrada.toFixed(2);
            soma_valor_entrada            = soma_valor_entrada.replace(".", ",");
            //var valor_entrada_auto = $('#valor_entrada_auto').val();
            $.get('inc/html_parcelas_datas.php?valor_entrada='+soma_valor_entrada+'&soma_total_parcela='+soma_total_parcela+'&parcela_menos_um='+parcela_menos_um,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 

          
          //valor_entrada_verifica = valor_entrada_verifica.replace(",", ".");
          //var valor_entrada             = parseFloat($("#valor_entrada").val());
          //$("#valor_entrada").val(valor_entrada_verifica);
          $("#valor_entrada").val(soma_valor_entrada);
        }else if(tipo_valor_entrada == 'manual'){

            var parcela_menos_um =  parseFloat(parcela_parcelas_boleto);
             $.get('inc/html_parcelas_datas.php?valor_entrada='+valor_entrada+'&soma_total_parcela='+soma_total_parcela+'&parcela_menos_um='+parcela_menos_um,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 
        
        }else{

            $.get('inc/html_parcelas_uma_data.php?soma_total_parcela='+soma_total_parcela,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 
        }

        $('.exibe_total_parcela').html('-');
        
    }else{

        $(".div_aguarde_2").show();
        $("#bt_add_submit").hide();
        //var permite_desconto = $('input[name=permitir_entrada]:checked').val();
        var soma_total_parcela = parseFloat(total_geral_assistencia) / parseFloat(parcela_parcelas_boleto);
        soma_total_parcela = soma_total_parcela.toFixed(2);
        
        $('#valor_parcela_boleto').val(soma_total_parcela);
        soma_total_parcela = soma_total_parcela.replace(".", ",");
         $.get('inc/html_parcelas_uma_data.php?soma_total_parcela='+soma_total_parcela,function (dados) {$(".div_aguarde_2").hide(); $("#bt_add_submit").show(); $(".txt_parcela_boleto").html(dados);}); 
        
        $('.exibe_total_parcela').html('-');
        
    }
    
    
  }
  else
  {
    //$('.exibe_soma_total').html('-');
    //$('#total_geral_assistencia').val('');
    $('#valor_parcela_boleto').val('');
    $('.txt_parcela_boleto').html(' de R$ -');
  }
  
  
});
//onkeydown
$( document ).on( "keyup", "#valor_entrada", function() {
    var valor_entrada_verifica  = $("#valor_entrada").val();
    valor_entrada_verifica = valor_entrada_verifica.replace(",", ".");

    var valor_minimo_entrada    = $("#valor_entrada").attr('data');
    var total_geral_assistencia = $('#total_geral_assistencia').val();
    
    var calcular_minimo = parseFloat((total_geral_assistencia * valor_minimo_entrada)/100);
    //calcular_minimo = calcular_minimo.toFixed(2);
    
    if(valor_entrada_verifica.length > 0){
        if(calcular_minimo > valor_entrada_verifica){
        $("#valor_entrada").parent('div').addClass('has-error');
        $('#bt_calcular_pagamento').hide();
        }else{
            $('#bt_calcular_pagamento').show(); 
        }
    }else{
        $('#bt_calcular_pagamento').show(); 
    }
    
    
});

$( document ).on( "click", "#bt_calcular_pagamento", function() {

  var valor                 = $('#forma_pagamento option:selected').val();
  var desconto_verifica     = 0;
  //alert('calculado');
  if(document.getElementById('desconto')){
    var desconto_verifica_input  = $("#desconto").val();
    desconto_verifica            = desconto_verifica_input.replace(",", ".");
    var desconto                 = parseFloat(desconto_verifica);
    var tipo_desconto            = $("#tipo_desconto").val();
  }  

  //var valor_entrada             = parseFloat($("#valor_entrada").val());
  
  if(valor == 'recorrente_cartao' || valor == 'fidelidade' || valor == 'entrada_recorrente_cartao') 
  {

    if(desconto_verifica.length < 7 && desconto_verifica.length > 0)
    {
        var valor_parcela     = parseFloat($('#soma_produto').val());
        if(tipo_desconto == '%'){
            var calcular_desconto = parseFloat((valor_parcela * desconto)/100);
        }else{
            var calcular_desconto = desconto;
        }
        
        calcular_desconto = calcular_desconto.toFixed(2);
        var calcular_desconto_input = parseFloat(valor_parcela - calcular_desconto);
        calcular_desconto_input = calcular_desconto_input.toFixed(2);
        var calcular_desconto_html;
        calcular_desconto_html = calcular_desconto_input.replace(".", ",");
       
        $('.exibe_desconto').html(' - ' + desconto + '%');
        $('.exibe_total_parcela').html(': R$ ' + calcular_desconto_html);
        
        $('#soma_produto').val(calcular_desconto_input);
        $('#exibe_novo_valor_total_geral_assistencia').show();

    }
    else{
        $('.exibe_desconto').html(' ');
        $('.exibe_total_parcela').html('');  
        $('#exibe_novo_valor_total_geral_assistencia').hide();

    }

  }else{
    
    var valor_parcela_atual     = parseFloat($('#soma_produto').val());
    valor_parcela_atual = valor_parcela_atual.toFixed(2);
    if(desconto_verifica.length < 7 && desconto_verifica.length > 0)
    {
        
        var valor_parcela     = parseFloat($('#soma_produto').val());
        if(tipo_desconto == '%'){
            var calcular_desconto = parseFloat((valor_parcela * desconto)/100);
        }else{
            var calcular_desconto = desconto;
        }
        
        //var valor_parcela     = parseFloat($('#soma_produto').val());
        //var calcular_desconto = parseFloat((valor_parcela * desconto)/100);
        
        calcular_desconto = calcular_desconto.toFixed(2);
        var calcular_desconto_input = parseFloat(valor_parcela - calcular_desconto);
        calcular_desconto_input = calcular_desconto_input.toFixed(2);
        var calcular_desconto_html;
        calcular_desconto_html = calcular_desconto_input.replace(".", ",");
       
        $('.exibe_desconto').html(' - ' + desconto + '%');
        $('.exibe_total_parcela').html(': R$ ' + calcular_desconto_html);
        
        $('#soma_produto_com_desconto').val(calcular_desconto_input);
        
        var valor_parcela_atual     = parseFloat($('#soma_produto_com_desconto').val());
        $('#exibe_novo_valor_total_geral_assistencia').show();

    }
    else{
        $('.exibe_desconto').html(' ');
        $('.exibe_total_parcela').html(''); 
        $('#exibe_novo_valor_total_geral_assistencia').hide(); 

    }
    
    var prazo             = $('#prazo option:selected').val();
    var soma_total = parseInt(prazo) * parseFloat(valor_parcela_atual);
    var novo_valor_com_desconto = soma_total;

    novo_valor_com_desconto = novo_valor_com_desconto.toFixed(2);
    novo_valor_com_desconto = novo_valor_com_desconto.replace(".", ",");
    
    var valor_entrada_verifica    = $("#valor_entrada").val();
    valor_entrada_verifica        = valor_entrada_verifica.replace(",", ".");

    if($("#tipo_desconto_entrada").is(':checked')){
        var tipo_desconto_entrada = 'S';
    }else{
        var tipo_desconto_entrada = 'N';
    }
    //soma_total = soma_total.toFixed(2);

    if(valor_parcela_atual == '0.00'){
        soma_total = '0.00';
        var novo_valor_com_desconto = soma_total;
    }/*else{
        novo_valor_com_desconto = novo_valor_com_desconto.toFixed(2);
        novo_valor_com_desconto = novo_valor_com_desconto.replace(".", ",");
    }*/
    
    var valor_entrada_verifica    = $("#valor_entrada").val();
    valor_entrada_verifica = valor_entrada_verifica.replace(",", ".");

    if($("#tipo_desconto_entrada").is(':checked')){
        var tipo_desconto_entrada = 'S';
    }else{
        var tipo_desconto_entrada = 'N';
    }
    if ($('input[name=permitir_entrada]:checked').val() == 'N')
    {
        valor_entrada_verifica = '';
    }

    if(valor_entrada_verifica.length > 0 && tipo_desconto_entrada == 'N') 
    {
        var calcular_com_entrada = parseFloat(soma_total) - parseFloat(valor_entrada_verifica);
        soma_total = calcular_com_entrada;
    }
    /*if(valor_entrada_verifica.length > 0 && tipo_desconto_entrada == 'N')
    {
        var calcular_com_entrada = parseFloat(soma_total) - parseFloat(valor_entrada_verifica);
        soma_total = calcular_com_entrada;
        soma_total = soma_total.toFixed(2);
    }*/

    var novo_valor_com_desconto = soma_total;
    novo_valor_com_desconto = novo_valor_com_desconto.toFixed(2);
    novo_valor_com_desconto = novo_valor_com_desconto.replace(".", ",");
    
    soma_total = soma_total.toFixed(2);
    $('#total_geral_assistencia').val(soma_total);
    
    $('#novo_valor_total_geral_assistencia').html("R$ " + novo_valor_com_desconto);
    
    var valor_entrada_auto_parceiro   = $("#valor_entrada_auto_parceiro").val();
    var divide_entrada_prazo = parseFloat(valor_entrada_auto_parceiro) / parseFloat(prazo);
    divide_entrada_prazo = divide_entrada_prazo.toFixed(2);
    var subtrai_valor_parcela_entrada = parseFloat(valor_parcela_atual) - parseFloat(divide_entrada_prazo);
    subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.toFixed(2);
    subtrai_valor_parcela_entrada = subtrai_valor_parcela_entrada.replace(".", ",");
    
    $('#novo_valor_parcela_com_desconto').html("R$ " + subtrai_valor_parcela_entrada);
    /*if ($('input[name=permitir_entrada]:checked').val() == 'N')
    {
        valor_parcela_atual_desconta_entrada = parseFloat(valor_parcela_atual) - parseFloat(divide_entrada_prazo);
        valor_parcela_atual_desconta_entrada = valor_parcela_atual_desconta_entrada.toFixed(2);
        //$("#soma_produto_atual").val(valor_parcela_atual_desconta_entrada);
        
        
        //valor_entrada_auto_parceiro = valor_entrada_auto_parceiro.toFixed(2);
        soma_total_desconta_estrada = parseFloat(soma_total) - parseFloat(valor_entrada_auto_parceiro);
        if(soma_total == '0.00'){
            soma_total_desconta_estrada = '0.00';
        }
        $('#total_geral_assistencia').val(soma_total_desconta_estrada);
    }*/
    //soma_total = soma_total.replace(".", ",");
    //$('.exibe_soma_total').html(' R$ ' + soma_total);

  }
  
  $("#bt_calcular_pagamento").attr('disabled', 'true');
  $("#bt_cancelar_calcular_pagamento").removeAttr('disabled');
  
  $('.html_info_pagamento').show();
  $('.html_forma_pagamento').show();
  
  $("#desconto").attr('readonly', 'true');
  $("#tipo_desconto").attr('readonly', 'true');

  $("#bloco_verifica_entrada").hide();
  $("#valor_entrada").attr('readonly', 'true');
  
  $("#forma_pagamento").attr('readonly', 'true');
  $("#prazo").attr('readonly', 'true');
  

});

$( document ).on( "click", "#bt_cancelar_calcular_pagamento", function() {
    
    //$('.html_info_pagamento').hide();
    $('#parcela_parcelas_boleto').val('');
    $('.txt_parcela_boleto').html('');
    $('.html_forma_pagamento').hide();
    $("#bt_add_submit").hide();  
    /*$('#valor_entrada').val('');
    $('#desconto').val('');
    $('#forma_pagamento').val('');
    $('#prazo').val('');*/
    $("#bt_cancelar_calcular_pagamento").attr('disabled', 'true');
    $("#desconto").removeAttr('readonly');  
    $("#tipo_desconto").removeAttr('readonly');  
    $("#bloco_verifica_entrada").show();
    $("#valor_entrada").removeAttr('readonly');
    
    $("#bt_calcular_pagamento").removeAttr('disabled');
    //$('#bt_calcular_pagamento').hide(); 
    //$('#exibe_novo_valor_total_geral_assistencia').hide(); 
    $("#forma_pagamento").removeAttr('readonly');
    $("#prazo").removeAttr('readonly');
});   

//$("#parcela_parcelas_boleto").focus();
</script>

<style>
.html_info_pagamento, .html_forma_pagamento, #bt_calcular_pagamento{
    display: none;
}
</style>


<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
    <input type="hidden" name="slug_produto[]" value="<?php echo $data; ?>" />
    <!--<div class="portlet-title">
        <div class="caption font-green">
            <span class="caption-subject bold uppercase"> Informações de Pagamento</span>
        </div>
    </div>-->
    <div class="portlet-body form">
        <div class="form-body">
            <div class="row">
            <div class="col-md-3">
                 <div class="form-group form-md-line-input form-md-floating-label">
                    <select class="form-control" id="forma_pagamento" name="forma_pagamento">
                        <option value=""></option>
                        
                         <?php
                            $sql_tipo_pagamnto        = "SELECT tipo_pagamento, desconto, valor_entrada_automatica, entrada, porcentagem_entrada, valor_entrada_auto FROM parceiros
            WHERE id_parceiro = $id_parceiro";
                            $query_tipo_pagamnto      = mysql_query($sql_tipo_pagamnto);
                                            
                            if (mysql_num_rows($query_tipo_pagamnto)>0)
                            {
                                $tipo_pagamento = mysql_result($query_tipo_pagamnto, 0, 'tipo_pagamento');
                                
                                $permitir_desconto = mysql_result($query_tipo_pagamnto, 0, 'desconto');
                                
                                $verifica_valor_entrada_automatica = mysql_result($query_tipo_pagamnto, 0, 'valor_entrada_automatica');
                                
                                $verifica_entrada = mysql_result($query_tipo_pagamnto, 0, 'entrada');

                                $verifica_porcentagem_entrada = mysql_result($query_tipo_pagamnto, 0, 'porcentagem_entrada');
                                $valor_entrada_auto = mysql_result($query_tipo_pagamnto, 0, 'valor_entrada_auto');
                                
                                if(empty($verifica_porcentagem_entrada)){
                                    $verifica_porcentagem_entrada = 0;
                                }
                                                                
                                $tipo_pagamento_array = explode("|", $tipo_pagamento);
                                
                                $tipo_pagamento_contar = count($tipo_pagamento_array) - 1;
                                
                                for($i=0; $i<=$tipo_pagamento_contar; $i++)
                                {
                                    if($tipo_pagamento_array[$i] == 'avista'){
                                        $nome_tipo = "À vista";
                                    }elseif($tipo_pagamento_array[$i] == 'entrada_recorrente_cartao'){
                                        $nome_tipo = "Entrada + Recorrente Crédito";
                                    }elseif($tipo_pagamento_array[$i] == 'parcelado_cartao'){
                                        $nome_tipo = "Cartão";
                                    }elseif($tipo_pagamento_array[$i] == 'parcelado_cartao_recorrente'){
                                    $nome_tipo = "Parcelado Crédito Recorrente";
                                    }elseif($tipo_pagamento_array[$i] == 'recorrente_cartao'){
                                        $nome_tipo = "Recorrente Crédito";
                                    }elseif($tipo_pagamento_array[$i] == 'fidelidade'){
                                        $nome_tipo = "Cartão Fidelidade";
                                    }elseif($tipo_pagamento_array[$i] == 'entrada_parcelado_boleto'){
                                        $nome_tipo = "Boleto";
                                    }
                                    
                                    echo "<option value=\"$tipo_pagamento_array[$i]\">$nome_tipo</option>";
                                }
                                
                                
                            }
                
                        ?>
                    </select>
                    <label for="forma_pagamento">Tipo pagamento</label>
                    
                </div>
              </div>
            
              <div class="col-md-6">
                 <div class="form-group form-md-line-input form-md-floating-label " style="display: block!important;">
                    <strong>Valor Total da Assistência:</strong><span class="exibe_soma_total">-</span> <br /> 
                    <strong>Valor por parcela sem desconto:</strong><span class="exibe_soma_valor_total_parcela">-</span> <br /> <br />
                    <?php
                    $soma_produto_decimal = explode(".", $soma_produto);
                    if(strlen($soma_produto_decimal[1]) == 1)
                    {
                        $soma_produto_decimal[1] = $soma_produto_decimal[1].'0';
                    }elseif(strlen($soma_produto_decimal[1]) == 0)
                    {
                        $soma_produto_decimal[1] = '00';
                    }
                    $soma_produto_decimal_correto = $soma_produto_decimal[0].".".$soma_produto_decimal[1];
                    ?>
                     <input type="hidden" name="soma_produto" id="soma_produto" value="<?php echo $soma_produto_decimal_correto; ?>"/>
                     <input type="hidden" name="soma_produto_com_desconto" id="soma_produto_com_desconto" value=""/>
                      <input type="hidden" name="total_geral_assistencia" id="total_geral_assistencia" value=""/>
                      <input type="hidden" name="soma_produto_atual" id="soma_produto_atual" value="<?php echo $soma_produto; ?>"/>
                    <div id="exibe_novo_valor_total_geral_assistencia" style="display: none;"><strong>TOTAL com desconto: </strong> <span id="novo_valor_total_geral_assistencia" class="bg-green-jungle bg-font-green-jungle" style="font-size: 20px;padding: 5px;"></span> <br />
                    <strong>PARCELA com desconto: </strong> <span id="novo_valor_parcela_com_desconto" class="bg-green-jungle bg-font-green-jungle" style="font-size: 20px;padding: 5px;"></span> </div> 
                    <!--<strong>Valor produto: </strong><span class="exibe_soma_produto"> <?php echo db_moeda($soma_produto); ?> </span> <span class="exibe_desconto"></span> <span class="exibe_dependente"></span> <strong><span class="exibe_total_parcela"></span></strong> <br />-->

                </div>
              </div>    
            </div> 
            
            <div class="row">
              <div class="col-md-3">
                 <div class="form-group form-md-line-input form-md-floating-label" id="select_prazo">
                    
                </div>
                &nbsp;
              </div>
              <div class="col-md-6" >
                 <div class="form-group form-md-line-input form-md-floating-label prazo_periodo html_info_pagamento">
                    <strong>Prazo selecionado:</strong> <span class="exibe_prazo">-</span> <br />
                    <strong>Início vigência:</strong> <span class="exibe_inicio">-</span> / <strong> Termino Vigência:</strong> <span class="exibe_termino">-</span>
                </div>
              </div>
              
            </div>
            
            <?php
            if($permitir_desconto == 'S'){
            ?>
            <div class="row">
                <div class="col-md-2 note note-info">
                   <div class="form-group form-md-line-input form-md-floating-label">
                       <input type="text" name="desconto" class="form-control" id="desconto" value="" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" maxlength="6"/>
                       <label for="nome">Desconto</label>
                        <span class="help-block">Por Parcela</span>
                    </div>
                </div>
                <div class="col-md-1 note note-info">
                   <div class="form-group form-md-line-input form-md-floating-label">
                       <select class="form-control" id="tipo_desconto" name="tipo_desconto">
                           <option value="%" selected="">%</option>
                           <option value="R$">R$</option>
                       </select>
                       <label for="tipo_desconto">&nbsp;</label>
                   </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-md-line-input form-md-floating-label" style="display: block;">
                        <small>Campo NÃO obrigatório.</small>
                    </div>
               </div>
            </div>
            <?php
            }
           ?>
           <div class="row">
          
            <div class="note note-info col-md-3" id="bloco_verifica_entrada">
             <?php
                $status_verifica_entrada = false;
                $check_entrada = "";
                $valor_se_entrada = 'N';
                $css_display_entrada = 'style="display: none;"';
                if($verifica_entrada == 'S' ){
                    if($verifica_valor_entrada_automatica == 'S' ){
                        $status_verifica_entrada = true;
                        $check_entrada = 'checked=""';
                        $valor_se_entrada = 'S';
                        $css_display_entrada = '';
                    }
                    }
                    ?>
                     <input type="hidden" value="<?php echo $valor_se_entrada;?>" id="input_status_entrada" name="input_status_entrada" />
            <h5>TAXA ADESÃO?</h5>
                <div class="form-group form-md-radios">
                    <div class="md-radio-list">
                        <div class="md-radio">
                            <input type="radio" id="entrada1" name="permitir_entrada" class="md-radiobtn" onclick="return f_permitir_entrada();" value="S" <?php echo $check_entrada;?>/>
                            <label for="entrada1">
                                <span class="inc"></span>
                                <span class="check"></span>
                                <span class="box"></span> SIM! </label>
                        </div>
                        <?php
                        if($status_verifica_entrada == false){
                            
                        ?>
                            <div class="md-radio">
                                <input type="radio" id="entrada2" name="permitir_entrada" class="md-radiobtn" onclick="return f_permitir_entrada();" value="N" checked=""/>
                                <label for="entrada2">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> NÃO! </label>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            
            </div>
            <div class="col-md-3">
                    <div class="form-group form-md-line-input form-md-floating-label" style="display: block;">
                        <small>Campo NÃO obrigatório.</small>
                    </div>
               </div>
           </div>
           <div id="bloco_entrada" <?php echo $css_display_entrada;?>>
               <?php
                if($verifica_entrada == 'S' ){
                    if($verifica_valor_entrada_automatica == 'N' ){
                    ?>
                    <div class="row">
                       <div class="col-md-2 ">
                        <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="hidden" name="tipo_calculo_entrada" id="tipo_calculo_entrada" value="manual"/>
                        <input type="hidden" name="valor_entrada_auto_parceiro" id="valor_entrada_auto_parceiro" value="<?php echo moeda_db($valor_entrada_auto); ?>"/>
                            <input type="text" name="valor_entrada" class="form-control" id="valor_entrada" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)" maxlength="6" value="<?php echo $valor_entrada_auto; ?>" data="<?php echo $verifica_porcentagem_entrada; ?>"/>
                            
                            <label for="nome">Valor de adesão</label>
                            
                        </div>
                       </div>
                       <div class="col-md-3">
                                <div class="md-checkbox-list">
                                    <div class="md-checkbox">
                                        <input type="checkbox" name="tipo_desconto_entrada" value="ok" id="tipo_desconto_entrada" class="md-check"/>
                                        <label for="tipo_desconto_entrada">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Cobrar entrada individual.</label>
                                            <span class="help-block" style="color: #00BCD4;">&nbsp; <br />
                                            </span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                       <div class="col-md-6">
                            <span class='help-block' style="color: #00BCD4;">** Valor mínimo de <strong><?php echo $verifica_porcentagem_entrada;?>%</strong> no Valor Total da Assistência.</span>
                       </div>
                    </div>
                    <?php
                        }else{
                            ?>
                    <div class="row">
                       <div class="col-md-12">
                           <input type="hidden" name="valor_entrada" id="valor_entrada" value="0"/>
                           <input type="hidden" name="tipo_calculo_entrada" id="tipo_calculo_entrada" value="auto"/>
                           <input type="hidden" name="valor_entrada_auto" id="valor_entrada_auto" value="<?php echo moeda_db($valor_entrada_auto); ?>"/>
                           <p><br/> Valor da Adesão será calculada automaticamente. Valor da adesão R$ <?php echo $valor_entrada_auto; ?></p>
                       </div>
                    </div>
                    <?php
                        }
                        
                    ?>
                    <h5>FORMA DE PAGAMENTO DA ADESÃO!</h5>
                    <div class="form-group form-md-radios">
                        <div class="md-radio-list">
                            <div class="md-radio">
                                <input type="radio" id="tipo_recebimento_entrada1" name="tipo_recebimento_entrada" class="md-radiobtn" value="BO" checked=""/>
                                <label for="tipo_recebimento_entrada1">
                                    <span class="inc"></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Boleto / à vista </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" id="tipo_recebimento_entrada2" name="tipo_recebimento_entrada" class="md-radiobtn" value="CA" />
                                <label for="tipo_recebimento_entrada2">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Cartão crédito / débito </label>
                            </div>
                        </div>
                    </div>
                <?php
                }
                else{
                ?>
                <p><br/>Você não tem permissão para vender com valor de Entrada.</p>
                 <input type="hidden" name="valor_entrada" id="valor_entrada" value="0"/>
                 <input type="hidden" name="tipo_calculo_entrada" id="tipo_calculo_entrada" value="sem_permissao"/>
                <?php
                }
                ?>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-12 " >
                    <button type="button" class="btn btn-info" id="bt_calcular_pagamento"><i class="fa fa-calculator"></i> Calcular</button>
                    <button type="button" disabled="" class="btn btn-danger" id="bt_cancelar_calcular_pagamento"><i class="fa fa-close"></i> Cancelar</button>
                </div>
            </div>
            <hr />
            <div class="row">
            <div class="col-md-8 html_forma_pagamento" >
                    <div class="form-group form-md-radios">
            <h5>Forma de Pagamento</h5>
            <span class="div_aguarde_2" style="position: absolute;width: 90%;padding-top: 10px;height: 100%;text-align: center;display: none;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
             <div class="md-radio-inline">
            <?php
                $sql_forma_pagamnto        = "SELECT metodo_pagamento FROM parceiros
WHERE id_parceiro = $id_parceiro";
                $query_forma_pagamnto      = mysql_query($sql_forma_pagamnto);
                                
                if (mysql_num_rows($query_forma_pagamnto)>0)
                {
                    $metodo_pagamento = mysql_result($query_forma_pagamnto, 0, 'metodo_pagamento');
                    
                    $metodo_pagamento_array = explode("|", $metodo_pagamento);
                    
                    $metodo_pagamento_contar        = count($metodo_pagamento_array) - 1;
                    
                    $metodo_pagamento_contar_igual  = count($metodo_pagamento_array);
                    
                    if($metodo_pagamento_contar_igual >= 1)
                    {
                        for($i = 0; $i<=$metodo_pagamento_contar;$i++){
                            
                            if($metodo_pagamento_array[$i] == 'MA'){
                                $nome_forma_pagamento = "Máquina de Cartão";
                            }elseif($metodo_pagamento_array[$i] == 'ON'){
                                $nome_forma_pagamento = "Checkout Online";
                            }elseif($metodo_pagamento_array[$i] == 'BO'){
                                $nome_forma_pagamento = "Boleto Parcelado";
                            }
                            

                            echo "<div class=\"md-radio\" id=\"div_radio$metodo_pagamento_array[$i]\">
                            <input type=\"radio\" id=\"radio$metodo_pagamento_array[$i]\" name=\"metodo_pagamento\" class=\"md-radiobtn\" onclick=\"return sel_metodo_pagamento();\" value=\"$metodo_pagamento_array[$i]\"/>
                            <label for=\"radio$metodo_pagamento_array[$i]\">
                                <span class=\"inc\"></span>
                                <span class=\"check\"></span>
                                <span class=\"box\"></span> $nome_forma_pagamento </label>
                        </div>"; 
                        }
                       /* echo "<div class=\"md-radio\" id=\"div_radio1\">
                            <input type=\"radio\" id=\"radio1\" name=\"metodo_pagamento\" class=\"md-radiobtn\" onclick=\"return sel_metodo_pagamento();\" value=\"MA\"/>
                            <label for=\"radio1\">
                                <span class=\"inc\"></span>
                                <span class=\"check\"></span>
                                <span class=\"box\"></span> Máquina de Cartão </label>
                        </div>
                        
                        <div class=\"md-radio\" id=\"div_radio2\">
                            <input type=\"radio\" id=\"radio2\" name=\"metodo_pagamento\" class=\"md-radiobtn\" onclick=\"return sel_metodo_pagamento();\" value=\"ON\"/>
                            <label for=\"radio2\">
                                <span class=\"inc\"></span>
                                <span class=\"check\"></span>
                                <span class=\"box\"></span> Checkout Online </label>
                        </div>
                        <div class=\"md-radio\" id=\"div_radio3\">
                            <input type=\"radio\" id=\"radio3\" name=\"metodo_pagamento\" class=\"md-radiobtn\" onclick=\"return sel_metodo_pagamento();\" value=\"BO\"/>
                            <label for=\"radio3\">
                                <span class=\"inc\"></span>
                                <span class=\"check\"></span>
                                <span class=\"box\"></span> Boleto Parcelado </label>
                        </div>";*/
                        echo "<div class='txt_metodo_pagamento'></div>";
                    }
                    else
                    {
                        if($metodo_pagamento_array[0] == 'MA')
                        {
                            echo "<strong>Máquina de Cartão</strong>. Antes de registrar venda, confirme o pagamento na máquina de cartão.
                            <input type=\"hidden\" name=\"metodo_pagamento\" value=\"MA\"/>";
                            $id_parceiro_login = base64_decode($_COOKIE["usr_parceiro"]);
                            $sql_parcelas_parceiro        = "SELECT parcelas_cartao FROM parceiros
                            WHERE id_parceiro = $id_parceiro_login";
                            $query_parcelas_parceiro      = mysql_query($sql_parcelas_parceiro);        
                            if (mysql_num_rows($query_parcelas_parceiro)>0)
                            {
                                echo "<input type=\"hidden\" name=\"valor_parcela_boleto\" id=\"valor_parcela_boleto\" value=\"\"/><div class='col-md-5' id=\"div_parcela_parcelas_boleto_2\" style=\"display: block;\"><div class='form-group form-md-line-input form-md-floating-label'><select class='form-control' id='parcela_parcelas_boleto' name='parcela_parcelas_boleto'><option value=''></option>";
                                
                                $parcelas_boleto = mysql_result($query_parcelas_parceiro, 0, 'parcelas_cartao');
                                $parcelas_boleto_array = explode("|", $parcelas_boleto);
                                $contar_array = count($parcelas_boleto_array) - 1;
                                
                                for($i = 0; $i<=$contar_array;$i++){
                                    $txt_parc = 'vezes';
                                    if($parcelas_boleto_array[$i] == 1){
                                        $txt_parc = 'vez';
                                    }
                                    echo "<option value='$parcelas_boleto_array[$i]'>$parcelas_boleto_array[$i] $txt_parc</option>";
                                    
                                }
                                
                                echo "</select><label for='parcela_parcelas_boleto'>Parcelar em:</label></div></div><div class='col-md-8 txt_parcela_boleto' style='margin-top: 30px;display: block!important;'></div>";
                            }
                            
                            echo "<div class='col-md-5'><div class='form-group form-md-line-input form-md-floating-label'><div class='input-group input-group-sm'><div class='input-group-control'><input type='text' name='comprovante_maquina' class='form-control' id='comprovante_maquina' value=''/><label for='comprovante_maquina'>AUT. Comprovante</label><span class='help-block'>Somente números..</span></div><span class='input-group-btn btn-right'><a class='btn red btn-outline sbold' data-toggle='modal' href='#responsive'><i class='fa fa-info'></i></a></span></div></div></div>";
                        }
                        elseif($metodo_pagamento_array[0] == 'ON')
                        {
                            echo "<strong>Checkout Online</strong>. Você será redirecionado para página de Checkout da CIELO.
                            <input type=\"hidden\" name=\"metodo_pagamento\" value=\"ON\"/>";
                        }elseif($metodo_pagamento_array[0] == 'BO')
                        {
                            echo "<input type=\"hidden\" name=\"metodo_pagamento\" value=\"BO\"/>";
                            $id_parceiro_login = base64_decode($_COOKIE["usr_parceiro"]);
 
                            $sql_parcelas_parceiro        = "SELECT parcelas_boleto FROM parceiros
                            WHERE id_parceiro = $id_parceiro_login";
                            $query_parcelas_parceiro      = mysql_query($sql_parcelas_parceiro);
                                            
                            if (mysql_num_rows($query_parcelas_parceiro)>0)
                            {
                                
                                echo "<input type=\"hidden\" name=\"valor_parcela_boleto\" id=\"valor_parcela_boleto\" value=\"\"/><div class='col-md-5' id=\"div_parcela_parcelas_boleto_2\" style=\"display: block;\"><div class='form-group form-md-line-input form-md-floating-label'><select class='form-control' id='parcela_parcelas_boleto' name='parcela_parcelas_boleto'><option value=''></option>";
                                
                                $parcelas_boleto = mysql_result($query_parcelas_parceiro, 0, 'parcelas_boleto');
                                $parcelas_boleto_array = explode("|", $parcelas_boleto);
                                $contar_array = count($parcelas_boleto_array) - 1;
                                
                                for($i = 0; $i<=$contar_array;$i++){
                                    $txt_parc = 'vezes';
                                    if($parcelas_boleto_array[$i] == 1){
                                        $txt_parc = 'vez';
                                    }
                                    echo "<option value='$parcelas_boleto_array[$i]'>$parcelas_boleto_array[$i] $txt_parc</option>";
                                    
                                }
                                
                                echo "</select><label for='parcela_parcelas_boleto'>Parcelar em:</label></div></div><div class='col-md-8 txt_parcela_boleto' style='margin-top: 30px;display: block!important;'></div>";
                            }
                        }
                        
                        
                    }
                }
            ?>
                
            <div id="responsive" class="modal fade modal-scroll" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Modelo de Comprovante Estabelecimento</h4>
                        </div>
                        <div class="modal-body">
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; "><div class="scroller" style="text-align: center; overflow: hidden; width: auto;" data-always-visible="1" data-rail-visible1="1" data-initialized="1">
                                <img src="assets/pages/img/comprovante_cielo_aut.png"/>
                            </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn dark btn-outline">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
                <div class="col-md-12" id="html_emissao_boleto" style="display: none;">
                
                <?php
                    $sql_forma_pagamnto        = "SELECT emissao_boleto FROM parceiros
    WHERE id_parceiro = $id_parceiro";
                    $query_forma_pagamnto      = mysql_query($sql_forma_pagamnto);
                                    
                    if (mysql_num_rows($query_forma_pagamnto)>0)
                    {
                        ?>
                        <div class="form-group form-md-radios">
                        <label>Emissão Boleto</label>
                        <div class="md-radio-inline">
                        
                        <?php
                        
                        $emissao_boleto = mysql_result($query_forma_pagamnto, 0, 'emissao_boleto');
                        
                        $emissao_boleto_array = explode("|", $emissao_boleto);
                        
                        $emissao_boleto_contar = count($emissao_boleto_array) - 1;
                        
                        if(!empty($emissao_boleto))
                        {
                            for($i = 0; $i<=$emissao_boleto_contar;$i++){
                                
                                if($i == 0){
                                    $checked = 'checked="checked"';
                                }
                                echo "<div class=\"md-radio\" >
                                <input type=\"radio\" id=\"radio_$emissao_boleto_array[$i]\" name=\"emissao_boleto\" class=\"md-radiobtn\" value=\"$emissao_boleto_array[$i]\" $checked/>
                                <label for=\"radio_$emissao_boleto_array[$i]\">
                                    <span class=\"inc\"></span>
                                    <span class=\"check\"></span>
                                    <span class=\"box\"></span> $emissao_boleto_array[$i] </label>
                            </div>";
                                
                            }
                        }
                        ?>
                            </div>
                        </div>
                    <?php 
                    }
                ?>
                
                    
                            
                            
                       
                </div>
                <div class="col-md-12">
                    <div id="form_PF_PJ"></div>
                </div>
                
            </div>
            
        </div>   
    </div>

<?php
//}
?>