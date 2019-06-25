<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;

$contar_dependente         = (empty($_GET['contar_dependente'])) ? "" : verifica($_GET['contar_dependente']);
$id_parceiro               = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);

?>

<div id="dados_dependente_<?php echo $contar_dependente; ?>" >
<script>
$("#bt_add_submit").hide();
$("#bt_add_dependente").hide();
jQuery(document).ready(function() {    
   jQuery(".cep").keyup(function() {
        if (jQuery(this).val().length >= '8') {
        	
            var cep = jQuery(this).val();
            var cep_data = jQuery(this).attr('data');
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
                        jQuery('#dados_dependente_' + cep_data + ' #endereco').val(data[0]);
                        jQuery('#dados_dependente_' + cep_data + ' #endereco').focus();
                        jQuery('#dados_dependente_' + cep_data + ' #bairro').val(data[1]);
                        jQuery('#dados_dependente_' + cep_data + ' #bairro').focus();
                        jQuery('#dados_dependente_' + cep_data + ' #cidade').val(data[2]);
                        jQuery('#dados_dependente_' + cep_data + ' #cidade').focus();
                        jQuery('#dados_dependente_' + cep_data + ' #estado').val(data[3]);
                        jQuery('#dados_dependente_' + cep_data + ' #estado').focus();
                        jQuery('#dados_dependente_' + cep_data + ' #numero').focus();
                        //jQuery('.alerta_cep').html('').addClass('txt_verde');
                    }
                    else
                    {
                        jQuery('#dados_dependente_' + cep_data + ' #endereco').focus();
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
//VALIDADORES
function verificarCPF_dep(c, id_dep){
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
            jQuery("#dados_dependente_" + id_dep + " #cpf").val("");
            alert('CPF inválido, digite novamente!');
        }
        
        for (i = 0; i < 9; i++){
            d1 += c.charAt(i)*(10-i);
        }
        if (d1 == 0){
            //alert("CPF Inválido")
            /*jQuery(".msg_info_matricula").html('<span>'+ s + ' - CPF Inválido!</span>');
            jQuery(".msg_info_matricula").fadeIn('fast');*/
            jQuery("#dados_dependente_" + id_dep + " #cpf").val("");
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
            jQuery("#dados_dependente_" + id_dep + " #cpf").val("");
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
            jQuery("#dados_dependente_" + id_dep + " #cpf").val("");
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
function verificarIDADE_add(d, id_dep){ 

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
    	
    	totalDias =  nasc_dia - dia; 
    	resMes = mes - nasc_mes;
    	totalMes = resMes * 30; 
    	resDiasDoMes = totalMes - totalDias;
    	resAno = ano - nasc_ano;
    	totalAno = resAno * 365;
    	total = resDiasDoMes + totalAno;
        
        idade = total / 365;
        idade = Math.floor(idade);
    	
    	if(idade > 69)
        {
            jQuery("#dados_dependente_" + id_dep + " #data_nasc").val("");
            $("#bt_add_submit").hide();
            $("#bt_add_dependente").hide();
            alert('Idade do cliente superior ao limite permitido.');     
                    
        }
        else
        {
            $("#bt_add_submit").show();
            $("#bt_add_dependente").show();
            $("#info_add_produto_html").show();
            $("#forma_pagamento").removeAttr('readonly');  
            $("#bt_calcular_pagamento").removeAttr('disabled');
        }
    
    }
    else
    {
        $("#bt_add_submit").hide();
        $("#bt_add_dependente").hide();
        $("#info_add_produto_html").hide();
        $("#forma_pagamento").val("").change();
    }
    
}

//VALIDADORES
function verificarIDADE_add_ok(d, id_dep){ 
    var contar = d.replace(/[^0-9]/g,'');
    var contar_int = contar.length;
    if(contar_int == 8)
    {
        $("#bt_add_submit").show();
        $("#bt_add_dependente").show();
        $("#info_add_produto_html").show();
        $("#forma_pagamento").removeAttr('readonly');
    }
    else
    {
        $("#bt_add_submit").hide();
        $("#bt_add_dependente").hide();
        $("#info_add_produto_html").hide();
        $("#forma_pagamento").val("").change();
    }   
}

$("#adicional_principal_bt_add_dependente_<?php echo $contar_dependente;?>").click(function() {
{       
        $(this).hide();
        $("#div_aguarde_sel_plano_add_depe_add").show();
        var contar_dependente_atual = $("#adicional_principal_contar_dependente_atual_<?php echo $contar_dependente;?>").val();
        var contar_dependente       = $("#adicional_principal_contar_dependente_<?php echo $contar_dependente;?>").val();
        var tipo_depen  = $("#adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?> #adicional_linha_dependente_" + contar_dependente_atual + " .tipo_dependente").val();
        var nome_depen  = $("#adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?> #adicional_linha_dependente_" + contar_dependente_atual + " .nome_dependente").val();
        var data_nasc   = $("#adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?> #adicional_linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").val();
        //alert(data_nasc);
        if(data_nasc != '' && nome_depen != ''){
            
           //alert(data_nasc);
            $.get('inc/adicional_add_form_dependente.php?contar_dependente=' + contar_dependente_atual + '&contar_adicional=<?php echo $contar_dependente;?>',function (dados) {
                $("#adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?>  #adicional_linha_dependente_" + contar_dependente_atual + " .tipo_dependente").attr('readonly', true);
                $("#adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?>  #adicional_linha_dependente_" + contar_dependente_atual + " .nome_dependente").attr('readonly', true);
                $("#adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?>  #adicional_linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").attr('readonly', true);
                //$("#linha_dependente_" + contar_dependente_atual + " #principal_bt_add_dependente").hide();
                $("#adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?>").append(dados);
                contar_dependente_atual = parseInt(contar_dependente_atual) + 1;
                contar_dependente       = parseInt(contar_dependente) + 1;
                $("#adicional_principal_contar_dependente_atual_<?php echo $contar_dependente;?>").val(contar_dependente_atual);
                $("#adicional_principal_contar_dependente_<?php echo $contar_dependente;?>").val(contar_dependente);
                
                $("#adicional_verifica_add_dependente_<?php echo $contar_dependente;?>").val('S');
                $("#adicional_principal_bt_add_dependente_<?php echo $contar_dependente;?>").show();
                $("#div_aguarde_sel_plano_add_depe_add").hide();
        });
        
    }else{
         $("#adicional_principal_bt_add_dependente_<?php echo $contar_dependente;?>").show();
         $("#div_aguarde_sel_plano_add_depe_add").hide();
    }
}

});
$(function() {
    $('input').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<hr />
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase"> Plano Adicional #<?php echo $contar_dependente; ?></span>
             <?php
            
                $sql_valor_dep        = "SELECT valor_plano_adicional FROM parceiros
    WHERE id_parceiro = $id_parceiro";
                $query_valor_dep      = mysql_query($sql_valor_dep, $banco_painel);
                                
                if (mysql_num_rows($query_valor_dep)>0)
                {
                    $valor_plano_adicional = mysql_result($query_valor_dep, 0, 'valor_plano_adicional'); 
                }
            
            ?>
            
            <span class="caption-helper" style="color: #ffffff;">Adicionar <?php echo db_moeda($valor_plano_adicional);?> no valor da parcela</span>
        </div>
        <div class="actions">
            <div class="btn-group">
            
                <a class="btn btn-sm white btn-outline filter-cancel" href="javascript:" data="<?php echo $contar_dependente; ?>" id="bt_remove_dependente"><i class="fa fa-times"></i> Remover</a>
                
            </div>
        </div>
    </div>
    <div class="portlet-body form">
        <input type="hidden" name="se_dependente[]" value="sim"/>
            <div class="form-body" style="padding: 15px;">
            <div class="row">
                <div class="col-md-12" style="color: #4DCC01;">
                <h5>** Data de Nascimento do cliente é OBRIGATÓRIO. Limite de idade é de até 69 anos.</h5>
                </div>
            </div>
                <div class="row">
                <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <select class="form-control parentesco" id="parentesco" name="parentesco_add[]">
                            <option value="" ></option>
                            <option value="nao_parente">Não é parente</option>
                            <option value="pai_mae">Pai/Mãe</option>
                            <option value="filho_filha">Filho/Filha</option>
                            <option value="avo_m_avo_f">Avô/Avó</option>
                            <option value="neto_neta">Neto/Neta</option>
                            <option value="irmao_irma">Irmão?Irmã</option>
                            <option value="tio_tia">Tio/Tia</option>
                            <option value="sobrinho_sobrinha">Sobrinho/Sobrinha</option>
                            <option value="Primo_Prima">Primo/Prima</option>
                            <option value="sogro_sogra">Sogro/Sogra</option>
                            <option value="cunhado_cunhada">Cunhado/Cunhada</option>
                            <option value="outros">Outros</option>
                        </select>
                        <label for="sexo"><span style="color: #F44336;">*</span> Grau de Parentesco</label>
                    </div>
                     &nbsp;
                  </div>
                  <div class="col-md-9 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="nome_add[]" class="form-control nome" id="nome" value="" style="text-transform: uppercase;" maxlength="40"/>
                        <label for="nome"> <span style="color: #F44336;">*</span>Nome Completo</label>
                        <span class="help-block">Digite o nome completo do cliente...</span>
                     </div>
                     &nbsp;
                  </div>
                    
                </div>
                <div class="row">
                <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="cpf_add[]" class="form-control cpf" id="cpf" value="" onkeyup="return verificarCPF_dep(this.value, <?php echo $contar_dependente; ?>)"/>
                        <label for="cpf"><span style="color: #F44336;">*</span> CPF</label>
                        <span class="help-block">Somente números..</span>
                     </div>
                     &nbsp;
                    </div>
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="data_nasc_add[]" class="form-control data_nasc" id="data_nasc" value="" 
                        <?php
                        if(in_array("49", $verifica_lista_permissoes_array_inc)){
                        ?>
                        onkeyup="return verificarIDADE_add_ok(this.value, <?php echo $contar_dependente; ?>)"
                        <?php
                        }else{
                        ?>
                        onkeyup="return verificarIDADE_add(this.value, <?php echo $contar_dependente; ?>)"
                        <?php
                        }
                        ?>
                        />
                        <label for="data_nasc"><span style="color: #F44336;">*</span> Data de Nascimento</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <select class="form-control" id="sexo" name="sexo_add[]">
                            <option value="" ></option>
                            <option value="H">Masculino</option>
                            <option value="M">Feminino</option>
                        </select>
                        <label for="sexo"><span style="color: #F44336;">*</span> Sexo</label>
                    </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                    <div class="form-group form-md-line-input form-md-floating-label">
                        <select class="form-control" id="estado_civil" name="estado_civil_add[]">
                            <option value=""  ></option>
                            <option value="S">Solteiro(a)</option>
                            <option value="C">Casado(a)</option>
                            <option value="D">Divorciado(a)</option>
                            <option value="V">Viuvo(a)</option>
                        </select>
                        <label for="estado_civil"><span style="color: #F44336;">*</span> Estado Civil</label>
                    </div>
                     &nbsp;
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="telefone_add[]" class="form-control telefone" id="telefone" value="" />
                        <label for="telefone">Telefone (fixo)</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="celular_add[]" class="form-control celular" id="celular" value=""/>
                        <label for="celular">Celular</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                   </div>
                   <div class="col-md-6">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="email_add[]" class="form-control" id="email" value=""/>
                        <label for="email">E-mail</label>
                        <span class="help-block">Digite o e-mail corretamente...</span>
                     </div>
                     &nbsp;
                   </div>
                </div>
                 <div class="row">
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="cep_add[]" class="form-control cep" id="cep" value="" style="text-transform: uppercase;" data="<?php echo $contar_dependente; ?>"/>
                        <label for="cep">CEP</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-7 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="endereco_add[]" class="form-control" id="endereco" value="" maxlength="40"/>
                        <label for="endereco">Endereço</label>
                        <span class="help-block">Digite o endereço completo...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-2 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="numero_add[]" class="form-control numero" id="numero" value="" maxlength="4"/>
                        <label for="numero">Número</label>
                        <span class="help-block">Somente números...</span>
                     </div>
                     &nbsp;
                    </div>
                </div>
                 <div class="row">
                  <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="complemento_add[]" class="form-control" id="complemento" value="" style="text-transform: uppercase;" maxlength="10"/>
                        <label for="complemento">Complemento</label>
                        <span class="help-block">Ex.: ap. 526</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-3 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="bairro_add[]" class="form-control" id="bairro" value="" maxlength="20"/>
                        <label for="bairro">Bairro</label>
                        <span class="help-block">LIMITE DE 20 CARACTERES...</span>
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-4 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="cidade_add[]" class="form-control" id="cidade" value="" style="text-transform: uppercase;" maxlength="30"/>
                        <label for="cidade">Cidade</label>
                        
                     </div>
                     &nbsp;
                    </div>
                    <div class="col-md-2 ">
                     <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="estado_add[]" class="form-control estado" id="estado" value=""/>
                        <label for="estado">Estado</label>
                        <span class="help-block">Campo com 2 dígitos</span>
                     </div>
                     &nbsp;
                    </div>
                </div>
                <hr />
                <div style="background: #f7f7f7;padding: 4px;">
                <input type="hidden" name="add_adicional_principal_contar_dependente_atual_<?php echo $contar_dependente;?>" id="adicional_principal_contar_dependente_atual_<?php echo $contar_dependente;?>" value="0"/>
                <input type="hidden" name="add_adicional_principal_contar_dependente[]" id="adicional_principal_contar_dependente_<?php echo $contar_dependente;?>" value="0"/>
                <input type="hidden" name="add_adicional_verifica_add_dependente[]" id="adicional_verifica_add_dependente_<?php echo $contar_dependente;?>" value="N"/>
                <div id="adicional_inserir_mais_dependentes_<?php echo $contar_dependente;?>"></div>
                <div class="row"><div class="col-md-6">
                     <div class="form-actions noborder">
                       <a href="javascript:" class="btn green" id="adicional_principal_bt_add_dependente_<?php echo $contar_dependente;?>">Adicionar <i class="fa fa-plus"></i> Dependente do adicional </a> <span id="div_aguarde_sel_plano_add_depe_add" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde, carregando dados...</span>
                     </div>
                     &nbsp;
                    </div></div>
                </div>
                <hr />
            </div>
    </div>
</div>
</div>