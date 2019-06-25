<?php
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$id_user_acesso = (empty($_GET['id_user_acesso'])) ? "" : verifica($_GET['id_user_acesso']);  

?>
<script>
function confirma_login(id_user_acesso){
{

  var login        = $("#login").val();
  var senha        = $("#senha").val();
    //alert(senha);
    
    if(senha.length > 1){
        $(".div_aguarde_2").show(); 
        $.ajax({ 
         type: "POST",
         url:  "valida.php",
         data: {
            tipo: 'acesso_rapido',
            login: login,
            senha: senha
            },
         success: function(dados){
             if(dados.length == 2){
                $('#msg_retorno_acesso').html('<div class="alert alert-success"><strong>Sucesso!</strong> Aguarde, atualizando página...</div>');
                $(location).attr('href', 'inicio.php');
                 
                //$('#ajax').modal('hide');
             }else if(dados.length == '4'){
                $('#msg_retorno_acesso').html('<div class="alert alert-danger"><strong>Erro!</strong> Senha inválida, digite novamente. </div>');
                $(".div_aguarde_2").hide(); 
                //$('#ajax').modal('hide');
             }else{
                alert(dados);
                $('#msg_retorno_acesso').html('<div class="alert alert-danger"><strong>Erro!</strong> Senha inválida, digite novamente. </div>');
                $(".div_aguarde_2").hide(); 
             }
      
             
         } 
         }); 
     }  
        
  //}
   
}};
jQuery(document).ready(function() {
 
    jQuery('#senha').focus();
});

/*$(document).keydown(function(e) {
    var nodeName = e.target.nodeName.toLowerCase();

    if (e.which === 8) {
        if ((nodeName === 'input' && e.target.type === 'text' || nodeName === 'input' && e.target.type === 'search' || nodeName === 'input' && e.target.type === 'password') ||
            nodeName === 'textarea' || nodeName === 'select') {
            // do nothing
        } else {
            e.preventDefault();
        }
    }
});*/
</script>
<?php
$sql_vinculados        = "SELECT nome, login FROM usuarios
WHERE ativo = 'S' AND del = 'N' AND id_usuario = $id_user_acesso";
$query_vinculados      = mysql_query($sql_vinculados);
            
if (mysql_num_rows($query_vinculados)>0)
{
    $nome_user_acesso   = mysql_result($query_vinculados, 0, 'nome');
    $login_user_acesso  = mysql_result($query_vinculados, 0, 'login');
}

?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo $nome_user_acesso." (".$login_user_acesso.")"; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Usuário</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Usuário" name="login" id="login" readonly="" value="<?php echo $login_user_acesso; ?>"/> </div>
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Senha</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Senha" name="senha" id="senha" /> </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div id="msg_retorno_acesso">
        </div>
    </div>
</div>
<div class="modal-footer">
<span class="div_aguarde_2" style="display: none;position: relative;width: 100%;padding-left: 155px;padding-top: 6px;height: 100%;"><img src="assets/global/img/loading-spinner-grey.gif"> Aguarde ...</span>
    <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
    
    <a href="javascript:" onclick="return confirma_login(<?php echo $id_user_acesso;?>);" class="btn blue">Acessar</a>
</div>