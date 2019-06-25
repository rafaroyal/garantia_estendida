<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;

$data                   = (empty($_GET['data'])) ? "" : verifica($_GET['data']); 
$tipo_pagamento         = (empty($_GET['info_pagamento'])) ? "" : verifica($_GET['info_pagamento']); 

$dependente_titular     = (empty($_GET['dependente_titular'])) ? "" : verifica($_GET['dependente_titular']); 
$dependente_adicional   = (empty($_GET['dependente_adicional'])) ? "" : verifica($_GET['dependente_adicional']); 
$data = explode("|", $data);

$id_parceiro = base64_decode($_COOKIE["usr_parceiro"]);
$sql_valor_dep        = "SELECT valor_plano_adicional, idade_limite_dependente, idade_limite_titular, idade_maior_limite_titular, taxa_extra_maior_limite_titular FROM parceiros
WHERE id_parceiro = $id_parceiro";
$query_valor_dep      = mysql_query($sql_valor_dep, $banco_painel);

$valor_plano_adicional              = '';
$idade_limite_dependente            = '';
$idade_limite_titular               = '';
$idade_maior_limite_titular         = '';
$taxa_extra_maior_limite_titular    = '';
if (mysql_num_rows($query_valor_dep)>0)
{
    $valor_plano_adicional              = mysql_result($query_valor_dep, 0, 'valor_plano_adicional');
    $idade_limite_dependente            = mysql_result($query_valor_dep, 0, 'idade_limite_dependente');
    $idade_limite_titular               = mysql_result($query_valor_dep, 0, 'idade_limite_titular');
    $idade_maior_limite_titular         = mysql_result($query_valor_dep, 0, 'idade_maior_limite_titular');
    $taxa_extra_maior_limite_titular    = mysql_result($query_valor_dep, 0, 'taxa_extra_maior_limite_titular');
    $valor_plano_adicional_c = str_replace(',', '.', $valor_plano_adicional);
}

?>

<script>
$( "#bt_add_dependente" ).click(function() {
{   
        $("#bt_add_dependente").hide();
        $("#info_add_produto_html").hide();
        $("#div_aguarde_sel_plano_add").show();
        $("#forma_pagamento").val("").change();
        $("#radioMA").prop( "checked", false );
        $("#radioON").prop( "checked", false );
        $("#radioBO").prop( "checked", false );
        
        $("#div_parcela_parcelas_boleto").hide();
        $(".txt_parcela_boleto").hide();
        
        var dependente_adicional = $("#dependente_adicional").val();

        var contar_dependente       = parseFloat($('#contar_dependente').val());
        var contar_dependente_atual = parseFloat($('#contar_dependente_atual').val());
        contar_dependente_atual     = parseFloat(contar_dependente_atual + 1);
        $('#contar_dependente_atual').val(contar_dependente_atual);
        var valor_dependente        = parseFloat($('#valor_dependente').val());
        
        $.get('inc/form_add_dependente.php?contar_dependente=' + contar_dependente_atual + '&dependente_adicional=' + dependente_adicional,function (dados) { $("#form_add_depenente").append(dados);
        var quantidade_depenente    = parseFloat($('#contar_dependente').val());
        contar_dependente = parseFloat(contar_dependente + 1);
        $('#contar_dependente').val(contar_dependente);
        
        var tel_telefone        = $("#telefone").val();
        var tel_celular         = $("#celular").val();
        var tel_cep             = $("#cep").val();
        var tel_endereco        = $("#endereco").val();
        var tel_numero          = $("#numero").val();
        var tel_complemento     = $("#complemento").val();
        var tel_bairro          = $("#bairro").val();
        var tel_cidade          = $("#cidade").val();
        var tel_estado          = $("#estado").val();
        
        $("#dados_dependente_"+contar_dependente_atual+" #telefone").val(tel_telefone).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #celular").val(tel_celular).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #cep").val(tel_cep).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #endereco").val(tel_endereco).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #numero").val(tel_numero).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #complemento").val(tel_complemento).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #bairro").val(tel_bairro).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #cidade").val(tel_cidade).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #estado").val(tel_estado).focus();
        $("#dados_dependente_"+contar_dependente_atual+" #nome").focus();


        var valor_parcela           = parseFloat($('#soma_produto_atual').val());
        var soma_dependente_html    = parseFloat(valor_dependente * contar_dependente);
        soma_dependente_html = soma_dependente_html.toFixed(2);
        soma_dependente_html = soma_dependente_html.replace(".", ",");
        var calcular_dependente     = parseFloat((valor_dependente * contar_dependente) + valor_parcela);
        
        var prazo                   = $('#prazo option:selected').val();
        var calcular_soma_total     = parseInt(prazo) * parseFloat(calcular_dependente);

        calcular_soma_total = calcular_soma_total.toFixed(2);
        calcular_dependente = calcular_dependente.toFixed(2);
        
        
        
        
        //var calcular_desconto_input = parseFloat(valor_parcela - calcular_desconto);
        
        //calcular_desconto_input = calcular_desconto_input.toFixed(2);
        var calcular_desconto_html;
        calcular_desconto_html = calcular_dependente.replace(".", ",");
        var calcular_desconto_html  = calcular_dependente.replace(".", ",");
        
        var calcular_soma_total_html;
        calcular_soma_total_html = calcular_soma_total.replace(".", ",");
        var calcular_soma_total_html  = calcular_soma_total.replace(".", ",");
        
        //calcular_desconto = calcular_desconto_novo.replace(".", ",");
        
        $('.exibe_total_parcela').html(': R$ ' + calcular_desconto_html);
        
        $('#soma_produto').val(calcular_dependente);
        $('.exibe_dependente').html(" + R$ " + soma_dependente_html);
        
        if(prazo != '' && typeof(prazo) !== 'undefined')
        {
            $('#total_geral_assistencia').val(calcular_soma_total);
            $('.exibe_soma_total').html(" + R$ " + calcular_soma_total_html);
        }
        $("#div_aguarde_sel_plano_add").hide();
        $("#bt_add_dependente").show();
        });
        
    }

});

//$( document ).on("click", "#principal_bt_add_dependente", function() {
$("#principal_bt_add_dependente").click(function() {
{       
        $("#div_aguarde_sel_plano_add_depe").show();
        $("#principal_bt_add_dependente").hide();
        var contar_dependente_atual = $("#principal_contar_dependente_atual").val();
        var contar_dependente       = $("#principal_contar_dependente").val();
        var tipo_depen  = $("#linha_dependente_" + contar_dependente_atual + " .tipo_dependente").val();
        var nome_depen  = $("#linha_dependente_" + contar_dependente_atual + " .nome_dependente").val();
        var data_nasc   = $("#linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").val();
        //alert(data_nasc);
        if(data_nasc != '' && nome_depen != ''){
            
           //alert(data_nasc);
            $.get('inc/add_form_dependente.php?contar_dependente=' + contar_dependente_atual,function (dados) {
                $("#linha_dependente_" + contar_dependente_atual + " .tipo_dependente").attr('readonly', true);
                $("#linha_dependente_" + contar_dependente_atual + " .nome_dependente").attr('readonly', true);
                $("#linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").attr('readonly', true);
                //$("#linha_dependente_" + contar_dependente_atual + " #principal_bt_add_dependente").hide();
                $("#inserir_mais_dependentes").append(dados);
                contar_dependente_atual = parseInt(contar_dependente_atual) + 1;
                contar_dependente       = parseInt(contar_dependente) + 1;
                $("#principal_contar_dependente_atual").val(contar_dependente_atual);
                $("#principal_contar_dependente").val(contar_dependente);
                $("#div_aguarde_sel_plano_add_depe").hide();
                $("#principal_bt_add_dependente").show();
                
        });
        
    }else{
        $("#div_aguarde_sel_plano_add_depe").hide();
        $("#principal_bt_add_dependente").show();
    }
    
}

});

$( document ).on("click", ".principal_bt_remove_dependente", function() {
{       
        var contar_dependente_atual = $("#principal_contar_dependente_atual").val();
        var contar_dependente       = $("#principal_contar_dependente").val();
        var tipo_depen  = $("#linha_dependente_" + contar_dependente_atual + " .tipo_dependente").val();
        var nome_depen  = $("#linha_dependente_" + contar_dependente_atual + " .nome_dependente").val();
        var data_nasc   = $("#linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").val();
        var id_dependente = $( this ).attr("data");
        $("#linha_dependente_" + id_dependente).remove();
        
}

});


jQuery(document).ready(function() {    
   jQuery("#cep").keyup(function() {
        if (jQuery(this).val().length >= '8') {
        	
            var cep = jQuery("#cep").val();
            //jQuery('.alerta_cep').html('Aguarde...').addClass('txt_verde');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/verifica_cep.php',
    			data: {cep: cep},
                dataType : 'html',
    			success: function( result )
    			{
                    data = result.split('%-%');
                    if(data[0] != 1)
                    {
                        jQuery('#endereco').val(data[0]);
                        jQuery('#endereco').focus();
                        jQuery('#bairro').val(data[1]);
                        jQuery('#bairro').focus();
                        jQuery('#cidade').val(data[2]);
                        jQuery('#cidade').focus();
                        jQuery('#estado').val(data[3]);
                        jQuery('#estado').focus();
                        jQuery('#numero').focus();
                        //jQuery('.alerta_cep').html('').addClass('txt_verde');
                    }
                    else
                    {
                        jQuery('#endereco').focus();
                        //jQuery('.alerta_cep').html('').addClass('txt_verde');
                    }
    			},
                erro: function()
                {
                    alert('erro');
                }
                    
     		});
            
 			
             }
    });    
   
});

/*function verifica_telefone(valor){
    
    var contar = valor.length;
    alert(contar);
    if(contar < 11){
        alert('fdfd');
    }
    
}*/

//VALIDADORES
function verificarCPF(c){ 
    var contar = c.length;
    var i;
    s = c;
    var c = s.substr(0,9);
    var dv = s.substr(9,2);
    var d1 = 0;
    var v = false;
   
    
    if(contar == 11)
    {
        if(s == '11111111111' || s == '22222222222' || s == '33333333333' || s == '44444444444' || s == '55555555555' || s == '66666666666' || s == '77777777777' || s == '88888888888' || s == '99999999999')
        {
            jQuery("#cpf").val("");
            alert('CPF inválido, digite novamente!');
        }
        
        for (i = 0; i < 9; i++){
            d1 += c.charAt(i)*(10-i);
        }
        if (d1 == 0){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery("#cpf").val("");
            alert('CPF inválido, digite novamente!');
            v = true;
            return false;
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9) d1 = 0;
        if (dv.charAt(0) != d1){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery("#cpf").val("");
            alert('CPF inválido, digite novamente!');
            v = true;
            return false;
        }
     
        d1 *= 2;
        for (i = 0; i < 9; i++){
            d1 += c.charAt(i)*(11-i);
        }
        d1 = 11 - (d1 % 11);
        if (d1 > 9) d1 = 0;
        if (dv.charAt(1) != d1){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery("#cpf").val("");
            alert('CPF inválido, digite novamente!');
            v = true;
            return false;
        }
        if (!v) {
            //alert(c + "nCPF Válido")
            jQuery(".msg_info_matricula").html('');
            jQuery(".msg_info_matricula").fadeOut('fast');
            
        }
    
    }
    
}

//VALIDADORES
function verificarIDADE(d){ 

    var idade_limite_dependente             = jQuery("#idade_limite_dependente").val();
    var idade_limite_titular                = jQuery("#idade_limite_titular").val();
    var idade_maior_limite_titular          = jQuery("#idade_maior_limite_titular").val();
    var taxa_extra_maior_limite_titular_db  = jQuery("#taxa_extra_maior_limite_titular").val();
    var taxa_extra_maior_limite_titular     = taxa_extra_maior_limite_titular_db;
    $("#calcular_taxa_extra_maior_limite_titular").val("");

    var contar = d.replace(/[^0-9]/g,'');
    var contar_int = contar.length;
    if(contar_int == 8)
    {
        var nasc_dia = contar.substr(0,2);
        var nasc_mes = contar.substr(2,2);
        var nasc_ano = contar.substr(4,4);
    
    //variaveis do dia/mes/ano de hj	
    	data = new Date();
    	dia = data.getDate();
    	mes = data.getMonth()+1;
    	ano = data.getFullYear();
    	
    	totalDias = nasc_dia - dia; 
    	resMes = mes - nasc_mes;
    	totalMes = resMes * 30; 
    	resDiasDoMes = totalMes - totalDias;
    	resAno = ano - nasc_ano;
    	totalAno = resAno * 365;
    	total = resDiasDoMes + totalAno;
        
        idade = total / 365;
        idade = Math.floor(idade);
    	//alert(totalDias + '||' + totalMes + '||' + resAno + '||' + total + '||' + idade);
    	if(idade > idade_limite_titular || idade < 18)
        { 
            $("#data_nasc").val(" ");
            if(idade > 18){
                /*if(idade_maior_limite_titular > 0){
                    $("#calcular_taxa_extra_maior_limite_titular").val(taxa_extra_maior_limite_titular);
                    alert('ATENÇÃO! Data de nascimento maior que '+idade_limite_titular+', será cobrada uma taxa adicional no valor de R$ '+taxa_extra_maior_limite_titular+'.');  
                }else{*/
                    alert('Erro! Idade do cliente superior ao limite permitido.');  
                //}
            }else{
                alert('Erro! Idade do cliente inferior à 18 anos');   
            }
                      
        }
    
    }
    
}

//VALIDADORES
function verificarIDADE_18(d){ 

    var contar = d.replace(/[^0-9]/g,'');
    var contar_int = contar.length;
    if(contar_int == 8)
    {
        var nasc_dia = contar.substr(0,2);
        var nasc_mes = contar.substr(2,2);
        var nasc_ano = contar.substr(4,4);
    
    //variaveis do dia/mes/ano de hj	
    	data = new Date();
    	dia = data.getDate();
    	mes = data.getMonth()+1;
    	ano = data.getFullYear();
    	
    	totalDias = nasc_dia - dia; 
    	resMes = mes - nasc_mes;
    	totalMes = resMes * 30; 
    	resDiasDoMes = totalMes - totalDias;
    	resAno = ano - nasc_ano;
    	totalAno = resAno * 365;
    	total = resDiasDoMes + totalAno;
        
        idade = total / 365;
        idade = Math.floor(idade);
    	//alert(totalDias + '||' + totalMes + '||' + resAno + '||' + total + '||' + idade);
    	if(idade < 18)
        {
            $("#data_nasc").val(" ");
            
            if(idade > 18){
                alert('Erro! Idade do cliente superior à 70 anos e 1 dia.');   
            }else{
                alert('Erro! Idade do cliente inferior à 18 anos');   
            }
                      
        }
    
    }
    
}
$(function() {
    $('input').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});
</script>

<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<input type="hidden" name="se_dependente[]" value="nao"/>

<input type="hidden" name="idade_limite_dependente" id="idade_limite_dependente" value="<?php echo $idade_limite_dependente; ?>"/>
<input type="hidden" name="idade_limite_titular" id="idade_limite_titular" value="<?php echo $idade_limite_titular; ?>"/>
<input type="hidden" name="idade_maior_limite_titular" id="idade_maior_limite_titular" value="<?php echo $idade_maior_limite_titular; ?>"/>
<input type="hidden" name="taxa_extra_maior_limite_titular" id="taxa_extra_maior_limite_titular" value="<?php echo $taxa_extra_maior_limite_titular; ?>"/>
<input type="hidden" name="dependente_adicional" id="dependente_adicional" value="<?php echo $dependente_adicional; ?>"/>

<input type="hidden" name="calcular_taxa_extra_maior_limite_titular" id="calcular_taxa_extra_maior_limite_titular" value="0"/>

<div class="portlet light " id="dados_cliente">
    <div class="portlet-title">
        <div class="caption font-green">
            <i class="fa fa-plus font-green"></i>
            <span class="caption-subject bold uppercase"> Dados pessoais</span>
        </div>
        <!--<div class="actions">
            <div class="btn-group">
                <a class="btn default" href="">
                <i class="fa fa-arrow-left fa-fw"></i> Voltar </a>
                
            </div>
        </div>-->
    </div>
    <div class="portlet-body form">
            <div class="form-body">
                <div class="row">
                <div class="col-md-12" style="color: #4DCC01;">
                <h5>** Limite de idade é de até <?php echo $idade_limite_titular; ?> anos. Mínimo de 18 anos.</h5>
                </div>
                  <div class="col-md-12 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="nome[]" class="form-control" id="nome" value="" style="text-transform: uppercase;" maxlength="40"/>
                        <label for="nome">Nome Completo</label>
                        <span class="help-block">Digite o nome completo do cliente...</span>
                     </div>
                     &nbsp;
                  </div>
                    
                </div>
                <div class="row">
                <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="cpf[]" class="form-control" id="cpf" value="" onkeyup="return verificarCPF(this.value)"/>
                        <label for="cpf">CPF</label>
                        <span class="help-block">Somente números..</span>
                     </div>
                     &nbsp;
                    </div>
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="data_nasc[]" class="form-control" id="data_nasc" value=""
                        <?php
                        if(in_array("49", $verifica_lista_permissoes_array_inc)){
                        }else{
                        ?>
                        onkeyup="return <?php $verifica_info_pagamento = ($tipo_pagamento == 'N') ? 'verificarIDADE_18' : 'verificarIDADE'; echo $verifica_info_pagamento; ?> (this.value)"
                        <?php   
                        }
                        ?>
                        />
                        <label for="data_nasc">Data de Nascimento</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <select class="form-control" id="sexo" name="sexo[]">
                            <option value=""></option>
                            <option value="H">Masculino</option>
                            <option value="M">Feminino</option>
                        </select>
                        <label for="sexo">Sexo</label>
                    </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                    <div class="form-group form-md-line-input form-md-floating-label">
                        <select class="form-control" id="estado_civil" name="estado_civil[]">
                            <option value=""  ></option>
                            <option value="S">Solteiro(a)</option>
                            <option value="C">Casado(a)</option>
                            <option value="D">Divorciado(a)</option>
                            <option value="V">Viuvo(a)</option>
                        </select>
                        <label for="estado_civil">Estado Civil</label>
                    </div>
                     &nbsp;
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="telefone[]" class="form-control" id="telefone" value="" />
                        <label for="telefone">Telefone (fixo)</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="celular[]" class="form-control" id="celular" value=""/>
                        <label for="celular">Celular</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                   </div>
                   <div class="col-md-6">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="email[]" class="form-control" id="email" value=""/>
                        <label for="email">E-mail</label>
                        <span class="help-block">Digite o e-mail corretamente...</span>
                     </div>
                     &nbsp;
                   </div>
                </div>
                 <div class="row">
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="cep[]" class="form-control" id="cep" value="" style="text-transform: uppercase;"/>
                        <label for="cep">CEP</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-7 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="endereco[]" class="form-control" id="endereco" style="text-transform: uppercase;" value="" maxlength="40"/>
                        <label for="endereco">Endereço</label>
                        <span class="help-block">Digite o endereço completo...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-2 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="numero[]" class="form-control" id="numero" value="" maxlength="4"/>
                        <label for="numero">Número</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                </div>
                 <div class="row">
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="complemento[]" class="form-control" id="complemento" value="" style="text-transform: uppercase;" maxlength="10"/>
                        <label for="complemento">Complemento</label>
                        <span class="help-block">Ex.: ap. 526</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="bairro[]" class="form-control" id="bairro" style="text-transform: uppercase;" value="" maxlength="20"/>
                        <label for="bairro">Bairro</label>
                         <span class="help-block">LIMITE DE 20 CARACTERES...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-4 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="cidade[]" class="form-control" id="cidade" value="" style="text-transform: uppercase;" maxlength="30"/>
                        <label for="cidade">Cidade</label>
                        
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-2 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="estado[]" class="form-control" style="text-transform: uppercase;" id="estado" value=""/>
                        <label for="estado">Estado</label>
                        <span class="help-block">Campo com 2 dígitos</span>
                     </div>
                     &nbsp;
                    </div>
                </div>
                
<?php
if (in_array("europ", $data)) { 

?>
    <hr />
    <div style="background: #f7f7f7;padding: 4px;">
    <input type="hidden" name="principal_contar_dependente_atual" id="principal_contar_dependente_atual" value="0"/>
    <input type="hidden" name="principal_contar_dependente" id="principal_contar_dependente" value="0"/>
    <div id="inserir_mais_dependentes"></div>
    <div class="row"><div class="col-md-6">
        <?php
        $exibe_bt_add = '';
        if($dependente_titular == 'N'){
            $exibe_bt_add = 'style="display: none;"';
        }
        ?>
            <div class="form-actions noborder">
            <a href="javascript:" class="btn green" id="principal_bt_add_dependente" <?php echo $exibe_bt_add; ?>>Adicionar <i class="fa fa-plus"></i> Dependente </a> <span id="div_aguarde_sel_plano_add_depe" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, carregando dados...</span>
            </div>
            &nbsp;
        </div></div>
    </div>
    <hr />
    <div class="row">
     <div class="col-md-12" >
        <div id="form_add_depenente"></div>
     </div>
    </div>
    <div class="row">
     <div class="col-md-12" >
        <div class="form-actions noborder">
            <input type="hidden" name="contar_dependente_atual" id="contar_dependente_atual" value="0"/>
            <input type="hidden" name="contar_dependente" id="contar_dependente" value="0"/>
            
            <?php

            if($valor_plano_adicional > 0 AND !empty($valor_plano_adicional)){
            ?>
                <input type="hidden" name="valor_dependente" id="valor_dependente" value="<?php echo $valor_plano_adicional_c;?>"/>
            
                <?php
                $exibe_bt_add = '';
                /*if($dependente_adicional == 'N'){
                    $exibe_bt_add = 'style="display: none;"';
                }*/
                ?>
                    <a href="javascript:" class="btn green" id="bt_add_dependente" <?php echo $exibe_bt_add; ?>><i class="fa fa-plus"></i> Plano Adicional</a> <span id="div_aguarde_sel_plano_add" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, carregando dados...</span> <div <?php echo $exibe_bt_add; ?>> ** Cada Adicional, valor de <?php echo db_moeda($valor_plano_adicional);?> por parcela. </div>
                <?php
            }
            ?>
        </div>
     </div>
    </div>
<?php
}
?>                
            </div>
    </div>
</div>
