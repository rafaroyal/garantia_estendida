<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
require_once('permissoes.php');
$banco_painel = $link;
$contar_dependente         = (empty($_GET['contar_dependente'])) ? "" : verifica($_GET['contar_dependente']);
$contar_dependente = $contar_dependente + 1;
$ver_ordem_pedido          = (empty($_GET['ver_ordem_pedido'])) ? "" : verifica($_GET['ver_ordem_pedido']);
$id_produto_get            = (empty($_GET['id_produto_get'])) ? "" : verifica($_GET['id_produto_get']);

?>
<div class="row" id="linha_dependente_<?php echo $contar_dependente;?>">
<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>
    <script>

//VALIDADORES
function verificarIDADE_dep(d){ 

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
            
            if(idade > 20)
            {
                $("#linha_dependente_<?php echo $contar_dependente;?> .data_nasc_dependente").val('');
                alert('Erro! Idade do dependente é superior à 21 anos e 1 dia.');           
            }
            
          }else{
            if(idade > 69)
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
        <div class="col-md-3">
            <div class="form-group form-md-line-input form-md-floating-label">
                <select class="form-control cliente_sel_dep" name="cliente_sel_dep[]">
                    <option value=""></option>
                    <?php
                    $sql_base   = "SELECT bpro.id_base_produto, bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                                                        JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                                                        WHERE pro.id_produto = $id_produto_get";
                                            $query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 1");
                                        
                                            if (mysql_num_rows($query_base)>0)
                                            {
                                                $id_base_produto        = mysql_result($query_base, 0, 'id_base_produto');
                                                $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
                                                $banco_user             = mysql_result($query_base, 0, 'banco_user');
                                                $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
                                                $banco_host             = mysql_result($query_base, 0, 'banco_host');
                                                $slug                   = mysql_result($query_base, 0, 'slug');
                                                $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
                                                
                                                $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
                                            }
                                            $banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados);
                                            
                    $sql_adicional        = "SELECT c.id_cliente, c.nome FROM clientes c
                    JOIN vendas v ON c.id_cliente = v.id_cliente
                    WHERE v.id_ordem_pedido = $ver_ordem_pedido
                    GROUP BY c.nome";
                    $query_adicional      = mysql_query($sql_adicional, $banco_produto);
                                    
                    if (mysql_num_rows($query_adicional)>0)
                    {
                        
                        while($dados_adicional = mysql_fetch_array($query_adicional)){
                            extract($dados_adicional); 
                            
                            echo "<option value=\"$id_cliente\">$nome</option>";
                            
                        }
                    }
                    
                    ?>
                </select>
                <label for="tipo_dependente">Cliente</label>
            </div>
         &nbsp;
        </div>
        <div class="col-md-2">
            <div class="form-group form-md-line-input form-md-floating-label">
                <select class="form-control tipo_dependente" name="tipo_dependente[]">
                    <option value=""></option>
                    <option value="esposa">Esposa</option>
                    <option value="esposo">Esposo</option>
                    <option value="filho">Filho</option>
                    <option value="filha">Filha</option>
                     <option value="especial">Especial</option>
                </select>
                <label for="tipo_dependente">Tipo</label>
            </div>
         &nbsp;
        </div>
        <div class="col-md-4">
         <div class="form-group form-md-line-input form-md-floating-label">
            <input type="text" name="nome_dependente[]" class="form-control nome_dependente" value=""/>
            <label for="nome_dependente">Nome Dependente</label>
            <span class="help-block">Digite o nome completo do dependente...</span>
         </div>
         &nbsp;
        </div>
        <div class="col-md-2">
         <div class="form-group form-md-line-input form-md-floating-label">
            <input type="text" name="data_nasc_dependente[]" class="form-control data_nasc_dependente" value="" 
                <?php
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
        <div class="col-md-1">
         <div class="form-actions noborder">
         <a href="javascript:" class="btn btn-sm red btn-outline sbold principal_bt_remove_dependente" data="<?php echo $contar_dependente; ?>">
            <i class="fa fa-times"></i> </a>
         </div>
         &nbsp;
        </div>
    </div>
    </div>