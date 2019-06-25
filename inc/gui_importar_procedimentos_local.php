<script src="../assets/pages/scripts/form-input-mask.js" type="text/javascript"></script>  
<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
//$id_boleto = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']);  
$id_usuario_s = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s = base64_decode($_COOKIE["usr_parceiro"]);
$id_local_atendimento    = (empty($_GET['id_local_atendimento'])) ? "" : verifica($_GET['id_local_atendimento']);

?>

<script>
$('#arquivo_retorno').change(function (event) {
        var file = this.files[0];
        /*name = file.name;
        size = file.size;
        type = file.type;*/

        if(file.name.length < 1) {
            alert("Por favor, selecionar o arquivo retorno!");
        }
        else if(file.type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            alert("aquivos permitidos em formato xlsx");
            $('#arquivo_retorno').val('');
        }
       
    
    });
    
function atualizar_procedimentos(){
{   
    var validar_dados;
    var valida_tipo;
    var url;
    validar_dados   = false;
    valida_tipo     = false;
    
    $('#arquivo_retorno').change(function (event) {
        var file = this.files[0];
        /*name = file.name;
        size = file.size;
        type = file.type;*/

        if(file.name.length < 1) {
            alert("Por favor, selecionar o arquivo!");
        }
        else if(file.type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            alert("aquivos permitidos em formato xlsx");
            $('#arquivo_retorno').val('');
        }
        else { 
            validar_dados = true;
        }
    
    });
    
    var x = document.getElementById("arquivo_retorno");
    var txt = "";
    if ('files' in x) {
        if (x.files.length == 0) {
             alert("Por favor, selecionar o arquivo retorno!");
             validar_dados = false;
        }else{
            validar_dados = true;
        }
    } 

    if(validar_dados == true){
        var formData = new FormData();
        formData.append('arquivo_retorno', $('#arquivo_retorno')[0].files[0]);
        formData.append('id_local_atendimento', $("#id_local_atendimento").val());
        //var campos = $( "#form_dados" ).serialize();
        $(".div_aguarde").show();
        
        var url = 'inc/gui_importar_arquivo_procedimentos_local.php';
        //var id_local_atendimento = $("#id_local_atendimento").val();
        
         $.ajax({
            url: url, // Url do lado server que vai receber o arquivo
            data:formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            type: 'POST',
            success: function (dados) {
                // utilizar o retorno
                $("#html_dados_retorno").html(dados); $("#bt_pesquisar_cliente").hide(); $(".div_aguarde").hide();
            }
        });
        
    }
    
}};

</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"> Importar procedimentos</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12" id="html_dados_retorno">
            <div class="row">
                <div class="col-md-12">
                Faça o download do arquivo MODELO: <a href="arquivos/importar_procedimentos/link_modelo_proc_local.xlsx" title="arquivo modelo" target="_blank" download="modelo_importacao_procedimentos">Planilha Modelo</a>
                </div>
                <hr />
                 <div class="col-md-12">
                Dados de referência para ID CONVÊNIOS: <br />
                    <?php
                $sql_convenio        = "SELECT id_convenio, nome FROM gui_convenios
                                        WHERE ativo = 'S'";
                $query_convenio      = mysql_query($sql_convenio);
                                
                if(mysql_num_rows($query_convenio)>0)
                {
                    
                    while($dados_convenio = mysql_fetch_array($query_convenio))
                    {
                        extract($dados_convenio);
                        
                        echo "ID CONVENIO: <strong>$id_convenio</strong> / $nome  <br/>";
                    }
                    
                    echo "</select>";
                }
                
                ?>

                </div>
            </div>
        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario_s; ?>"/>
        <input type="hidden" name="id_parceiro" id="id_parceiro" value="<?php echo $id_parceiro_s; ?>"/>
        <input type="hidden" name="" id="id_local_atendimento" value="<?php echo $id_local_atendimento; ?>"/>
            <h4>Importar</h4>
            <div class="row">
            <div class="col-md-12">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <label for="arquivo_retorno">Formato permitido: .xlsx</label>
                    <div class="input-group input-large">
                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                            <span class="fileinput-filename"> </span>
                        </div>
                        <span class="input-group-addon btn default btn-file">
                            <span class="fileinput-new"> Selecionar o arquivo </span>
                            <span class="fileinput-exists"> Alterar </span>
                            <input type="file" name="arquivo_retorno" id="arquivo_retorno"/> </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Limpar </a>
                    </div>
                </div>
            </div>

            </div>
    </div>
</div>
</div>
<div class="modal-footer">
<a href="javascript:;" onclick="return atualizar_procedimentos();" class="btn btn-lg blue btn-block" id="bt_pesquisar_cliente"> <i class="fa fa-upload"></i> Salvar e atualizar </a> <span class="div_aguarde" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
</div>