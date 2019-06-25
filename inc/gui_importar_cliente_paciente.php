<script src="assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>  
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
//$id_boleto = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']);  
$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);
$nivel_usuario   = base64_decode($_COOKIE["usr_nivel"]);
?>

<script>
function alterna_import_paciente(){
{   
    if($("input[name=sel_servico]").is(':checked')){
        $("#linha_campo_busca").show();
    }
    
}};

function gui_busca_lista_cliente_importado(id_pagamento){
{

  var nome              = $("#buscar_cliente_nome").val();
  var data_nasc         = $("#buscar_cliente_data_nasc").val();
  var cliente_chave     = $("#buscar_cliente_chave").val();
  var cliente_cpf       = $("#buscar_cliente_cpf").val();
  var sel_servico       = $("input[name=sel_servico]:checked").val();
  
  
  if ($('input[name="gui_import_lista_dependente').is(':checked')) {
        var sel_dependente    = 1;
    }else{
        var sel_dependente    = 0;
    }
  
  var verifica = false;
  //var id_usuario  = $("#id_usuario").val();
  //var id_parceiro = $("#id_parceiro").val();
    
    if(nome.length > 2){
        verifica = true;
    }
    
    if(cliente_chave.length == 20){
        verifica = true;
    }
    
    if(cliente_cpf.length == 11){
        verifica = true;
    }
    
    if(verifica == true)
    {
        $(".div_aguarde_2").show(); 
        $.ajax({ 
         type: "POST",
         url:  "inc/gui_busca_lista_cliente_importado.php",
         data: {
            nome: nome,
            data_nasc: data_nasc,
            sel_servico: sel_servico,
            cliente_chave: cliente_chave,
            cliente_cpf: cliente_cpf,
            sel_dependente: sel_dependente
            },
         success: function(dados){
             $("#resultado_busca_cliente").html(dados);
             $(".div_aguarde_2").hide(); 
            //$('#ajax').modal('hide');
         },
         error: function(){
            $(".div_aguarde_2").hide();
            $("#resultado_busca_cliente").html('Dados Invalidos!');
         }
         });   
     }else{
        $(".div_aguarde_2").hide();
        $("#resultado_busca_cliente").html('Dados Invalidos!');
     }
     
        
  //}
   
}};

$('input[name="gui_import_lista_dependente"]').click(function() {
        if ($(this).is(':checked')) {
            $("#buscar_cliente_chave").hide();
            $("#buscar_cliente_cpf").hide();
            $("#buscar_cliente_chave").val('');
            $("#buscar_cliente_cpf").val('');
        }else{
            $("#buscar_cliente_chave").show();
            $("#buscar_cliente_cpf").show();
        }
    });
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"> Importar cliente</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_s; ?>"/>
        <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro_s; ?>"/>
            <h4>Importar Cliente</h4>
            <div class="row">
            <div class="col-md-12">
                <div class="form-group form-md-radios">
                        <label>Selecione o serviço:</label>
                        <div class="md-radio-inline">
                        <?php
                     if($nivel_usuario == "A")
                    {
                        $sql        = "SELECT ser.id_servico, ser.nome'nome_servico' FROM servicos ser
                        JOIN produtos pro ON ser.id_servico = pro.id_servico
                        JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                        WHERE ser.ativo = 'S' AND pro.ativo = 'S' 
                        GROUP BY ser.id_servico ORDER BY ser.nome";
                    }else{
                         $sql        = "SELECT ser.id_servico, ser.nome'nome_servico' FROM servicos ser
                        JOIN produtos pro ON ser.id_servico = pro.id_servico
                        JOIN parceiros_servicos pser ON pro.id_produto = pser.id_produto
                        WHERE ser.ativo = 'S' AND pro.ativo = 'S' AND pser.id_parceiro = $id_parceiro_s
                        GROUP BY ser.id_servico ORDER BY ser.nome";
                    }
                    $query      = mysql_query($sql, $banco_painel);
                    
                    if (mysql_num_rows($query)>0)
                    {
                        
                        while ($dados = mysql_fetch_array($query))
                        {
                            extract($dados); 
                            if($id_servico == 2){
                                $html_checked = 'checked';
                            }else{
                                $html_checked = '';
                            }
                            echo '<div class="md-radio">';                                                                     
                            echo ' <input type="radio" id="id_servico'.$id_servico.'" name="sel_servico" class="md-radiobtn" onclick="return alterna_import_paciente();" value="'.$id_servico.'" '.$html_checked.'/> <label for="id_servico'.$id_servico.'">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span> '.$nome_servico.' </label>';
                             echo '</div>';
                        }
                       
                    }
                        ?>
                         </div>
                    </div>
            </div>

            </div>
            <div class="row" id="linha_campo_busca">
                <div class="col-md-12">
                    <div class="md-checkbox-list">
                        <div class="md-checkbox">
                            <input type="checkbox" name="gui_import_lista_dependente" value="1" id="gui_import_lista_dependente" class="md-check"/>
                            <label for="gui_import_lista_dependente">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> DEPENDENTE </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <input type="text" name="buscar_cliente_nome" class="form-control" id="buscar_cliente_nome" placeholder="Digite o Nome do cliente..." /> <br />
                </div>
                <div class="col-md-3">
                    <input type="text" name="buscar_cliente_data_nasc" class="form-control data_nasc" id="buscar_cliente_data_nasc" placeholder="Data nasc" /> 
                </div>
                <div class="col-md-8">
                    <input type="text" name="buscar_cliente_chave" class="form-control" id="buscar_cliente_chave" placeholder="N° Certificado" maxlength="20" /> <br />
                </div>
                <div class="col-md-4">
                    <input type="text" name="buscar_cliente_cpf" class="form-control cpf" id="buscar_cliente_cpf" placeholder="CPF" /> 
                </div>
                <hr />
                <div class="col-md-12">
                    <a href="javascript:" onclick="return gui_busca_lista_cliente_importado();" class="btn green-sharp btn-outline  btn-block sbold uppercase"> BUSCAR CLIENTE </a><br />
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-5 col-md-4">
                                <span class="div_aguarde_2" style="display: none;position: relative;width: 100%;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguarde ...</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div id="resultado_busca_cliente"></div>

    </div>
</div>
</div>
<div class="modal-footer">

    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
</div>