<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$contar_dependente         = (empty($_GET['contar_dependente'])) ? "" : verifica($_GET['contar_dependente']);
$contar_dependente = $contar_dependente + 1;
?>
<div class="row" id="linha_dependente_<?php echo $contar_dependente;?>">
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
    <script>
 
//VALIDADORES
function verificarIDADE_dep(d){ 
    
    var idade_limite_dependente             = document.getElementById("idade_limite_dependente").value;
    var idade_limite_titular                = document.getElementById("idade_limite_titular").value;
    var idade_maior_limite_titular          = document.getElementById("idade_maior_limite_titular").value;
    var taxa_extra_maior_limite_titular_db  = document.getElementById("taxa_extra_maior_limite_titular").value;
    var taxa_extra_maior_limite_titular     = taxa_extra_maior_limite_titular_db;
    
    //alert(idade_limite_dependente);
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
        
        var tipo_dependente                       = $("#linha_dependente_<?php echo $contar_dependente;?> .tipo_dependente option:selected").val();
  
  
        if(tipo_dependente != '' && typeof(tipo_dependente) !== 'undefined')
        {
          // verificar idade
          if(tipo_dependente == 'filho' || tipo_dependente == 'filha'){
            
            if(idade > idade_limite_dependente)
            {
                $("#linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
                alert('Erro! Idade do dependente é superior à '+idade_limite_dependente+' anos.');           
            }
            
          }else{
            if(idade > idade_limite_titular)
            {
                $("#linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
                alert('Erro! Idade do cliente superior ao limite permitido.');           
            }
          } 
            
        }else{
            $("#linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
            alert('Selecione primeiro o "Tipo de Dependente"!');   
            $("#linha_dependente_<?php echo $contar_dependente;?> .tipo_dependente").focus();
        }

    }
    
}

$( document ).on( "change", "#inserir_mais_dependentes .tipo_dependente", function() {

  //var tipo_dependente                       = $('#tipo_dependente option:selected').val();
  var contar_dependente_atual = $("#principal_contar_dependente_atual").val();
  var contar_dependente       = $("#principal_contar_dependente").val();
   $("#linha_dependente_" + contar_dependente_atual + " .data_nasc_dependente").val('');
   $("#linha_dependente_" + contar_dependente_atual + ".tipo_dependente").focus();
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
                <select class="form-control tipo_dependente" name="tipo_dependente[]">
                    <?php
                    $sql_valor_dep        = "SELECT valor FROM opcoes
                                            WHERE nome = 'tipo_dependente'";
                    $query_valor_dep      = mysql_query($sql_valor_dep, $banco_painel);

                    if (mysql_num_rows($query_valor_dep)>0)
                    {
                        echo "<option value=\"\"></option>";
                        while($dados_valor_dep = mysql_fetch_array($query_valor_dep)){
                            extract($dados_valor_dep); 
                            echo "<option value=\"$valor\">$valor</option>";
                        }
                    }
                    ?>
                </select>
                <label for="tipo_dependente">Tipo de Dependente</label>
            </div>
         &nbsp;
        </div>
        <div class="col-md-5 ">
         <div class="form-group form-md-line-input form-md-floating-label">
            <input type="text" name="nome_dependente[]" class="form-control nome_dependente" value=""/>
            <label for="nome_dependente">Nome Dependente</label>
            <span class="help-block">Digite o nome completo do dependente...</span>
         </div>
         &nbsp;
        </div>
        <div class="col-md-2 ">
         <div class="form-group form-md-line-input form-md-floating-label">
            <input type="text" name="data_nasc_dependente[]" class="form-control data_nasc_dependente" value="" <?php
                if(in_array("49", $verifica_lista_permissoes_array_inc)){
                }else{
                ?>
                onkeyup="return verificarIDADE_dep(this.value)"
                <?php
                }
                ?>
            />
            <label for="data_nasc_dependente">Data de Nascimento</label>
            <span class="help-block">Somente números...</span>
         </div>
         &nbsp;
        </div>
        <div class="col-md-2 ">
         <div class="form-actions noborder">
         <a href="javascript:" class="btn btn-sm red btn-outline sbold principal_bt_remove_dependente" data="<?php echo $contar_dependente; ?>">
            <i class="fa fa-times"></i> Excluir</a>
         </div>
         &nbsp;
        </div>
    </div>
    </div>