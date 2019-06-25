<?php
require_once('conexao.php');
?>

<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
<script>
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
                        jQuery('#lista_cidades').html('<input type="text" class="form-control" id="cidade" name="cidade" readonly=""/><label for="cidade">Cidade</label>');
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
    jQuery("#estado").change(function() {
        if (jQuery(this).val().length >= '1') {
        	
            var estado = jQuery("#estado").val();
            //$("#estado").find('option').removeAttr("selected");
            //$('#estado option[value=' + estado + ']').attr('selected','selected');
            jQuery.ajax({
    			type: "POST",
    			url: 'inc/localiza_cidade.php',
    			data: {estado: estado},
                dataType : 'html',
    			success: function( result )
    			{
                    $("#lista_cidades").html(result);
    			},
                erro: function()
                {
                    alert('erro');
                }
                    
     		});
            
 			
             }
     });   
     
     
});
$(function() {
    $('input').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
<div class="col-md-3 ">
 <div class="form-group form-md-line-input form-md-floating-label">
    <input type="text" name="nome" class="form-control" id="nome" value=""/>
    <label for="nome">Nome</label>
    <span class="help-block">Como será chamado o parceiro...</span>
 </div>
 &nbsp;
</div>
<div class="col-md-3 ">
 <div class="form-group form-md-line-input form-md-floating-label">
        <select class="form-control" id="grupo" name="grupo">
            <option value=""></option>
            
            <?php
                
                $sql = "SELECT * FROM grupos_parceiros
                        WHERE del = 'N'";
                $query      = mysql_query($sql);
                         
                if (mysql_num_rows($query)>0)
                {
                    while ($dados = mysql_fetch_array($query))
                    {
                        extract($dados);  
                        
                        echo "<option value=\"$id_grupo_parceiro\">$nome</option>";
                    }
                    
                }
                
            ?> 
        </select>
        <label for="grupo">Nome do grupo</label>
    </div>
 &nbsp;
</div>
<div class="col-md-6 ">
 <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="cnpj" name="cnpj"/>
        <label for="cnpj">CNPJ</label>
        <span class="help-block">Apenas números...</span>
 </div>
 &nbsp;
</div>
<div class="col-md-6 ">
 <div class="form-group form-md-line-input form-md-floating-label">
    <input type="text" name="razao" class="form-control" id="razao" value=""/>
    <label for="razao">Razão Social</label>
 </div>
 &nbsp;
</div>
<div class="col-md-6 ">
 <div class="form-group form-md-line-input form-md-floating-label">
    <input type="text" name="fantasia" class="form-control" id="fantasia" value=""/>
    <label for="fantasia">Nome Fantasia</label>
 </div>
 &nbsp;
</div>
<div class="col-md-6">
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="cep" name="cep"/>
        <label for="cep">CEP</label>
        <span class="help-block">Apenas números...</span>
    </div>
    
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="numero" name="numero"/>
        <label for="numero">Número</label>
        <span class="help-block">Apenas números...</span>
    </div>
    
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="bairro" name="bairro"/>
        <label for="bairro">Bairro</label>
        <span class="help-block">Apenas números...</span>
    </div>
    
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="ramo_atividade" name="ramo_atividade"/>
        <label for="ramo_atividade">Ramo Atividade</label>
    </div>
    
    <div class="form-group form-md-line-input form-md-floating-label">
        <select class="form-control" id="modalidade" name="modalidade">
            <option value=""></option>
            <option value="golden">Golden</option>
            <option value="premium">Premium</option>
        </select>
        <label for="modalidade">Modalidade</label>
    </div>
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="email" name="email"/>
        <label for="email">E-mail</label>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="endereco" name="endereco"/>
        <label for="endereco">Endereço</label>
        
    </div>
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="complemento" name="complemento"/>
        <label for="complemento">Complemento</label>
        <span class="help-block">Ex.: Sala 01</span>
    </div>
    <div class="form-group form-md-line-input form-md-floating-label">
        <?php
        
        $sql_user_pedido        = "SELECT ufe_sg FROM log_localidade
GROUP BY ufe_sg
        ";
        $query_user_pedido      = mysql_query($sql_user_pedido);
                        
        if(mysql_num_rows($query_user_pedido)>0)
        {
            echo "
            <select class=\"form-control\" id=\"estado\" name=\"estado\" ><option value=\"\"></option>";
            
            while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
            {
                extract($dados_user_pedido);

                echo "<option value=\"$ufe_sg\">$ufe_sg</option>";
            }
            
            echo "</select>";
        }
        
        ?>
        
    </div>
    <div class="form-group form-md-line-input form-md-floating-label">
    <input type="hidden" class="form-control" id="id_cidade" name="id_cidade"/>
        <div id="lista_cidades">
            
            <input type="text" class="form-control" id="cidade" name="cidade" readonly=""/>
            <label for="cidade">Cidade</label>
        </div>                                                               
    </div>
    
    
    <div class="form-group form-md-line-input form-md-floating-label">
        <input type="text" class="form-control" id="tel_com" name="tel_com"/>
        <label for="tel_com">Telefone comercial</label>
        <span class="help-block">Ex.: 43 33333333</span>
    </div>
    
    <div class="form-group form-md-line-input form-md-floating-label">
       <div class="fileinput fileinput-new col-md-6" data-provides="fileinput">
            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 50px; height: 50px;"> </div>
            <div style="display: inline-block;">
                <span class="btn red btn-outline btn-file">
                    <span class="fileinput-new"> LOGO PARCEIRO (500 x 161) px </span>
                    <span class="fileinput-exists"> Selecione </span>
                    <input type="file" name="logo1"/> </span>
                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
            </div>
       </div>
       <div class="fileinput fileinput-new col-md-6" data-provides="fileinput">
            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 50px; height: 50px;"> </div>
            <div style="display: inline-block;">
                <span class="btn red btn-outline btn-file">
                    <span class="fileinput-new"> LOGO PROPOSTA (500 x 161) px </span>
                    <span class="fileinput-exists"> Selecione </span>
                    <input type="file" name="logo2"/> </span>
                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
            </div>
        </div>
    </div>
</div>

    