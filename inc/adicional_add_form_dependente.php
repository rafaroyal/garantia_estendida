<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$contar_dependente         = (empty($_GET['contar_dependente'])) ? "" : verifica($_GET['contar_dependente']);
$contar_adicional          = (empty($_GET['contar_adicional'])) ? "" : verifica($_GET['contar_adicional']);
$contar_dependente = $contar_dependente + 1;
?>
<div class="row" id="adicional_linha_dependente_<?php echo $contar_dependente;?>">
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
    <script>

//VALIDADORES
function verificarIDADE_dep_adicional_<?php echo $contar_dependente; ?>_<?php echo $contar_adicional; ?>(d){ 

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
        
        var tipo_dependente                       = $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .tipo_dependente option:selected").val();
  
  
        if(tipo_dependente != '' && typeof(tipo_dependente) !== 'undefined')
        {
          // verificar idade
          if(tipo_dependente == 'filho' || tipo_dependente == 'filha'){
            
            if(idade > 21)
            {
                $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
                alert('Erro! Idade do dependente é superior à 21 anos e 1 dia.');           
            }
            
          }else{
            if(idade > 69)
            {
                $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
                alert('Erro! Idade do cliente superior ao limite permitido.');           
            }
          } 
            
        }else{
            $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
            alert('Selecione primeiro o "Tipo de Dependente"!');   
            $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .tipo_dependente").focus();
        }
        
    	
    
    }
    
}

//$( document ).on( "change", "#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .tipo_dependente", function() {
$("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .tipo_dependente").change(function() {      

  //var tipo_dependente                       = $('#tipo_dependente option:selected').val();
  var contar_dependente_atual = $("#adicional_principal_contar_dependente_atual_<?php echo $contar_adicional;?>").val();
  var contar_dependente       = $("#adicional_principal_contar_dependente_<?php echo $contar_adicional;?>").val();
   $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
   $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_<?php echo $contar_dependente;?> .tipo_dependente").focus();
});


$("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> .principal_bt_remove_dependente_<?php echo $contar_dependente;?>_<?php echo $contar_adicional;?>").click(function() {
{   

        var contar_dependente_atual = $("#adicional_principal_contar_dependente_atual_<?php echo $contar_adicional;?>").val();
        
        var contar_dependente       = $("#adicional_principal_contar_dependente_<?php echo $contar_adicional;?>").val();
        var tipo_depen  = $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_" + contar_dependente_atual + " .tipo_dependente").val();
        var nome_depen  = $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_" + contar_dependente_atual + " .nome_dependente").val();
        var data_nasc   = $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").val();
        var id_dependente = $( this ).attr("data");
        
        contar_dependente_n       = parseInt(contar_dependente) - 1;
        $("#adicional_principal_contar_dependente_<?php echo $contar_adicional;?>").val(contar_dependente_n);
        $("#adicional_inserir_mais_dependentes_<?php echo $contar_adicional;?> #adicional_linha_dependente_" + id_dependente).remove();
        if(contar_dependente_n == 0){
            $("#adicional_verifica_add_dependente_<?php echo $contar_adicional;?>").val('N');
        }
        
}

});
$(function() {
    $('input').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
    <div class="col-md-12" >
        <div class="col-md-3 ">
            <div class="form-group form-md-line-input form-md-floating-label">
                <select class="form-control tipo_dependente" name="adicional_tipo_dependente[]">
                    <option value=""></option>
                    <option value="esposa">Esposa</option>
                    <option value="esposo">Esposo</option>
                    <option value="filho">Filho</option>
                    <option value="filha">Filha</option>
                    <option value="especial">Especial</option>
                </select>
                <label for="tipo_dependente">Tipo de Dependente</label>
            </div>
         &nbsp;
        </div>
        <div class="col-md-5 ">
         <div class="form-group form-md-line-input form-md-floating-label">
            <input type="text" name="adicional_nome_dependente[]" class="form-control nome_dependente" value=""/>
            <label for="nome_dependente">Nome Dependente</label>
            <span class="help-block">Digite o nome completo do dependente...</span>
         </div>
         &nbsp;
        </div>
        <div class="col-md-2 ">
         <div class="form-group form-md-line-input form-md-floating-label">
            <input type="text" name="adicional_data_nasc_dependente[]" class="form-control data_nasc_dependente" id="adicional_data_nasc_dependente" value="" 
            <?php
            if(in_array("49", $verifica_lista_permissoes_array_inc)){
            }else{
            ?>
            onkeyup="return verificarIDADE_dep_adicional_<?php echo $contar_dependente; ?>_<?php echo $contar_adicional; ?>(this.value)"
            <?php
            }
            ?>
            />
            <label for="adicional_data_nasc_dependente">Data de Nascimento</label>
            <span class="help-block">Somente números...</span>
         </div>
         &nbsp;
        </div>
        <div class="col-md-2 ">
         <div class="form-actions noborder">
         <a href="javascript:" class="btn btn-sm red btn-outline sbold principal_bt_remove_dependente_<?php echo $contar_dependente;?>_<?php echo $contar_adicional;?>" data="<?php echo $contar_dependente; ?>">
            <i class="fa fa-times"></i> Excluir</a>
         </div>
         &nbsp;
        </div>
    </div>
    </div>