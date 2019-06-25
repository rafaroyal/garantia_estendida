<?php
require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php');
require_once('permissoes.php'); 
$banco_painel = $link;

$id_cliente         = (empty($_GET['id_cliente'])) ? "" : verifica($_GET['id_cliente']);  
$id_produto         = (empty($_GET['id_produto'])) ? "" : verifica($_GET['id_produto']);  
$id_produto_get     = $id_produto;
$tipo               = (empty($_GET['tipo'])) ? "" : verifica($_GET['tipo']);  
$status_cliente     = (empty($_GET['status'])) ? "" : verifica($_GET['status']); 
$nivel_usuario = base64_decode($_COOKIE["usr_nivel"]);
$nivel_status = base64_decode($_COOKIE["nivel_status"]);
$usr_parceiro_sessao = base64_decode($_COOKIE["usr_parceiro"]);
$id_user_sessao = base64_decode($_COOKIE["usr_id"]);
$ativar_plano_cancelado = '';
$agora 			= date("Y-m-d");
if(!empty($tipo) AND $tipo == 'produto')
{
    // FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
    $sql_base   = "SELECT bpro.id_base_produto, bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
                JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
                WHERE pro.id_produto = $id_produto";
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
}

if($slug == 'europ')
{
    
 $sql        = "SELECT * FROM clientes
                WHERE id_cliente = $id_cliente";
    $query      = mysql_query($sql, $banco_produto);
                    
    if (mysql_num_rows($query)>0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);   
        
        if($id_usuario == ''){
            $id_usuario = 138;
        }
        
        $status_list = array(
                array("success" => "Ativo"),
                array("danger" => "Inativo")
                );
        
        if((strtotime($data_termino) > strtotime($agora) OR $data_termino == '0000-00-00') AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0))
        {
            $status_cliente = $status_list[0];
        }
        elseif(strtotime($data_termino) == strtotime($agora) AND $tipo_movimento <> 'EX' AND ($status == 99 OR $status == 0))
        {
            $status_cliente = $status_list[0];
        }
        else
        {
            $status_cliente = $status_list[1];
        }
        
        $status_cliente = current($status_cliente);
    }
    
    if($id_filial > 0)
    {
        $sql        = "SELECT pa.nome'nome_parceiro', fi.nome'nome_filial' FROM parceiros pa
                    JOIN filiais fi ON pa.id_parceiro = fi.id_parceiro
                    WHERE pa.id_parceiro = $id_parceiro AND fi.id_filial = $id_filial";
        $query      = mysql_query($sql, $banco_painel);
        $nome_parceiro  = '';
        $nome_filial    = '';                
        if (mysql_num_rows($query)>0)
        {
            $nome_parceiro  = mysql_result($query, 0,0);
            $nome_filial    = mysql_result($query, 0,1);    
        }
    }else{
        $sql        = "SELECT pa.nome'nome_parceiro' FROM parceiros pa
                    WHERE pa.id_parceiro = $id_parceiro";
        $query      = mysql_query($sql, $banco_painel);
        $nome_parceiro  = '';
        $nome_filial    = '';                
        if (mysql_num_rows($query)>0)
        {
            $nome_parceiro = mysql_result($query, 0);    
        }
    }
    
    $sql        = "SELECT tipo_cobranca, banco, url_boleto FROM cobranca_parceiros
    WHERE id_parceiro = $id_parceiro";
    $query      = mysql_query($sql, $banco_painel);
    $cobr_tipo_cobranca = '';
    $cobr_banco         = '';
    $cobr_url_boleto    = '';

    if (mysql_num_rows($query)>0)
    {
        $cobr_tipo_cobranca = mysql_result($query, 0, 'tipo_cobranca');
        $cobr_banco         = mysql_result($query, 0, 'banco');
        $cobr_url_boleto    = mysql_result($query, 0, 'url_boleto');
    }
    
    ?>
    
    <script>
    
    function lista_filial(){
    {   
        $('#lista_filiais').show();
    }};
    
    function exibe_add_comentario(verifica){
    {
        if(verifica == 'true'){
            $('#form_add_comentario').show('fast').focus();
            $('#exibe_bt_add_comentario').hide('fast');
        }else{
            $('#form_add_comentario').hide('fast');
            $('#exibe_bt_add_comentario').show('fast');
        }
        

    }};
    
    function remove_lista_filial(){
    {   
        $('#lista_filiais').hide();
    }};
    
    
function confirma_add_comentario(){
{
    var id_cliente        = $("#id_cliente_hidden").val();
    var comentario        = $("#campo_add_comentario").val();
    $("#bt_enviar_comentario").attr("disabled", true);
    
    if(comentario.length > 2){
        $.ajax({ 
         type: "POST",
         url:  "editar_db.php",
         data: {
            item: 'comentarios_atividades',
            id_cliente: id_cliente,
            comentario: comentario
            },
         success: function(dados){
            data = dados.split('%-%');
            if(data[0] != 1)
            {
                var data_criacao    = data[3];
                var id_criado_por   = data[1];
                var nome_criado_por = data[0];
                var comentario      = data[2];
                $('#tabela_lista_comentarios').append('<tr><td>' + data_criacao + '</td><td>' + id_criado_por + ' - ' + nome_criado_por + '</td><td>' + comentario + '</td></tr>');
                $('#form_add_comentario').hide('fast');
                $('#exibe_bt_add_comentario').show('fast');
                $("#bt_enviar_comentario").removeAttr("disabled");
                $("#campo_add_comentario").val('');
            }else{
                $("#bt_enviar_comentario").removeAttr("disabled");
            }
         } 
        });
    }
    
        
    
//}

}};

function enviar_sms_html(verifica){
{   
    if(verifica == 'true'){
            $('#btn_enviar_sms_html').hide('fast');
            $("#div_aguarde_envia_sms").show();
            $('#enviar_sms_html').show('fast').focus();
            
        }else{
            $("#div_aguarde_envia_sms").hide();
            $('#btn_enviar_sms_html').show('fast');
            $('#enviar_sms_html').hide('fast');
        }

}};

function enviar_sms(id_cliente, tipo_sms, nome, celular){
{   
    var celular_encode     = encodeURI(celular);
    $("#bt_enviar_sms").attr('disabled', 'true');
    $("#bt_enviar_sms_ok").attr('disabled', 'true');
    $("#bt_enviar_sms_ok").text('Aguarde...');
    $("#div_aguarde_envia_sms").show();
    $.get('inc/enviar_sms.php?id_cliente='+ id_cliente + '&tipo_sms=' + tipo_sms + '&nome=' + nome + '&celular=' + celular_encode,function (dados) { 
        if(dados != 'erro'){
            $("#bt_enviar_sms_txt").html(' <i class="fa fa-check-square-o"></i> SMS Enviado | ');
            $("#div_aguarde_envia_sms").hide();
            $('#btn_enviar_sms_html').show('fast');
            $('#enviar_sms_html').hide('fast');
        }else
        {
            $("#principal_bt_add_dependente").removeAttr('disabled');
            $("#div_aguarde_envia_sms").show();
        }
    });

}};

function exibe_etiqueta(verifica){
    {
        if(verifica == 'true'){
            $('#bloco_etiqueta').show('fast').focus();
            $('#bt_exibe_etiqueta').hide('fast');
        }else{
            $('#bloco_etiqueta').hide('fast');
            $('#bt_exibe_etiqueta').show('fast');
        }
        

    }};



function selectText(containerid) {
    var html_etiqueta = $("#thumbnail_etiqueta").text();
  $("#campo_text_etiqueta").html(html_etiqueta);  
    
  /* Get the text field */
  var copyText = document.getElementById("campo_text_etiqueta");

  /* Select the text field */
  copyText.select();

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  $("#bt_copiar_html").html('<strong>Etiqueta copiada!</strong>');
  
    if (document.selection) { // IE
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select();
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
    }
    
}
    </script>
    
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        
    </div>
    <div class="modal-body" id="modal_ver_cliente">
    
        <?php
            $sql_user   = "SELECT us.nome'nome_usuario' FROM usuarios us
                        WHERE us.id_usuario = $id_usuario";
            $query_user = mysql_query($sql_user, $banco_painel);
            $nome_usuario = '';                
            if (mysql_num_rows($query_user)>0)
            {
                $nome_usuario = mysql_result($query_user, 0);    
            }
        ?>
        <div class="row">
            <div class="col-md-12">
                <h4>Dados do vendedor</h4>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Parceiro:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_parceiro." - ".$nome_filial; ?></p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Usuário:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_usuario; ?> </p>
                            
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
            </div>
        </div>
        <div class="row bg-grey-cararra bg-font-grey-cararra">
                <div class="col-md-12">
                <h4>Dados pessoais <small>(<?php echo $tipo_pessoa; ?>)</small></h4>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>CPF:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $cpf; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Chave (Certificado):</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $chave; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Tipo movimentação:</strong></label>
                        <div class="col-md-12">
                        <?php
                        if($data_termino == '0000-00-00'){
                            $data_termino_exibe = 'Recorente';
                        }else{
                            $data_termino_exibe = converte_data($data_termino);
                        }
                        ?>
                            <p class="form-control-static"> <?php echo $tipo_movimento; ?> <span class="label label-sm label-<?php $label_status = ($status_cliente == 'Ativo') ? 'success' : 'danger'; echo $label_status; ?>"><?php echo $status_cliente; ?></span> <br /> Termino: <?php echo $data_termino_exibe;
                
                $data = somar_datas( 2, 'm'); // adiciona meses a sua data          
                $data_restante = date('d/m/Y', strtotime($data));
                $data_restante = converte_data($data_restante);
                if(strtotime($data_termino) <= strtotime($data_restante) AND $id_cliente_principal == 0 AND $data_termino != '0000-00-00'){
                ?>
                
                <span class="label label-sm label-warning">Renove agora</span>
                <?php
                
                }
                ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Data de Nasc.:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo converte_data($data_nascimento); ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Sexo:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $sexo = ($sexo == 'H') ? 'HOMEM' : 'MULHER'; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Estado Civil:</strong></label>
                        <div class="col-md-12">
                        <?php
                            $estado_civil_list = array("civil" =>
                            array("S" => "Solteiro(a)", "C" => "Casado(a)", "V" => "Viuvo(a)", "D" => "Divorciado(a)"));
                            
                            
                            
                        ?>
                            <p class="form-control-static"> <?php echo $estado_civil_list["civil"][$estado_civil]; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <h4>Endereço/Contato</h4>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>CEP:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $cep; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Endereço:</strong>  <a href="javascript:;" class="btn green"target="_blank" onclick="return exibe_etiqueta('true');" id="bt_exibe_etiqueta">Para Etiqueta</a></label>
                        <div class="col-md-12" id="bloco_etiqueta" style="display: none;">
                            <div id="bt_copiar_html"><small>Clique na etiqueta para copiar!</small> </div>
<div class="thumbnail" id="thumbnail_etiqueta" style="cursor: pointer;margin: 0;" onclick="return selectText('thumbnail_etiqueta');">
<small> Destinatário </small> <br/>
<strong><?php echo $nome; ?></strong> <br/>
<?php echo $endereco; ?>, N° <?php echo $numero;?><br/>
<?php $complemento = (!empty($complemento)) ? $complemento."<br/>" : ''; echo $complemento;?>
<?php echo $bairro; ?><br/>
<?php echo mask_total($cep, "#####-###"); ?> <?php echo $cidade;?> - <?php echo $estado;?><br/>
</div>
                            <textarea class="col-md-12" name="campo_text_etiqueta" rows="1" style="resize: none;z-index: -999999999;" readonly="" id="campo_text_etiqueta"> </textarea>
                        </div>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $endereco; ?>, N° <?php echo $numero;?>, Bairro <?php echo $bairro;?><?php $complemento = (!empty($complemento)) ? ', '.$complemento : ''; echo $complemento;?>,  <?php echo $cidade;?> - <?php echo $estado;?></p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Telefone Residencial:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php 
                            $telefone_str1 = substr($telefone, -4, 4);
                            $telefone_str2 = substr($telefone, 0, -4);
                            $telefone = $telefone_str2."-".$telefone_str1;
                            echo $telefone; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
               <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Telefone Celular:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php 
                            $celular_sms = $celular;
                            $celular_str1 = substr($celular, -4, 4);
                            $celular_str2 = substr($celular, 0, -4);
                            $celular = $celular_str2."-".$celular_str1;
                            echo $celular; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                </div>
                </div>
                <hr />
                <?php
                
                $sql_par_produto        = "SELECT gpro.id_grupo_produto, gpro.nome'nome_grupo', pro.nome'nome_produto' FROM grupos_produtos gpro
JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
JOIN produtos pro ON prog.id_produto = pro.id_produto
WHERE gpro.id_grupo_produto = $id_produto";
                    $query_par_produto      = mysql_query($sql_par_produto, $banco_painel);
                                
                    if (mysql_num_rows($query_par_produto)>0)
                    {
                        $dados = mysql_fetch_array($query_par_produto);
                        extract($dados); 
                    }
                //if($id_cliente_principal == 0)
                //{
                    
                
                
                    /*$sql_adicional        = "SELECT dep_cli.id_dependente'id_dependente_cliente', dep_cli.tipo_dependente, dep_cli.nome'nome_dependente', dep_cli.data_nascimento'data_nasc_dependente' FROM dependentes_clientes dep_cli
                    JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                    WHERE dep_cli.id_cliente = $id_cliente AND dep_cli.status = 0 AND v.dependente = 'N'";*/
                    
                   /* $sql_ver_adicional        = "SELECT dep_cli.id_cliente FROM dependentes_clientes dep_cli
                    JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                    WHERE dep_cli.id_cliente = $id_cliente AND dep_cli.status = 0 ";
                    $query_ver_adicional      = mysql_query($sql_ver_adicional, $banco_produto);
                    if (mysql_num_rows($query_ver_adicional)>0)
                    {
                        ?>
                
                 <h4>Dependentes</h4>
                
                <?php
                        $ver_id_cliente = mysql_result($query_ver_adicional, 0, 'id_cliente');
                        
                        if($ver_id_cliente == 0){
                            $sql_adicional        = "SELECT dep_cli.id_dependente'id_dependente_cliente', dep_cli.tipo_dependente, dep_cli.nome'nome_dependente', dep_cli.data_nascimento'data_nasc_dependente' FROM dependentes_clientes dep_cli
                            JOIN vendas v ON dep_cli.id_ordem_pedido = v.id_ordem_pedido
                            WHERE dep_cli.id_cliente = $id_cliente AND dep_cli.status = 0 AND v.dependente = 'N'";
                        }else{
                            $sql_adicional        = "SELECT dep_cli.id_dependente'id_dependente_cliente', dep_cli.tipo_dependente, dep_cli.nome'nome_dependente', dep_cli.data_nascimento'data_nasc_dependente' FROM dependentes_clientes dep_cli
                            WHERE dep_cli.id_cliente = $id_cliente AND dep_cli.status = 0";
                        }
                       
                    
                    
                    $query_adicional      = mysql_query($sql_adicional, $banco_produto);
                                    
                    if (mysql_num_rows($query_adicional)>0)
                    {
                        
                        while($dados_adicional = mysql_fetch_array($query_adicional)){
                            extract($dados_adicional); 
                        ?>
                        <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label col-md-12"><strong>Tipo:</strong></label>
                                <div class="col-md-12">
                                    <p class="form-control-static"> <?php echo $tipo_dependente; ?> </p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-12"><strong>Nome:</strong></label>
                                <div class="col-md-12">
                                    <p class="form-control-static"> <?php echo $nome_dependente; ?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label col-md-12"><strong>Nasc.:</strong></label>
                                <div class="col-md-12">
                                    <p class="form-control-static"> <?php echo converte_data($data_nasc_dependente); ?> </p>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-3">
                          <a href="inc/comprovantes/html/proposta_assistencia_total/cartao/index_dependente.php?cert=<?php echo $chave; ?>&tipo_plano=<?php echo $id_grupo_produto;?>&id_dependente=<?php echo $id_dependente_cliente; ?>" class="btn green"target="_blank"><i class="fa fa-credit-card"></i></a><br />
                          
                          <?php
                            $verifica           = "SELECT data_impressao FROM controle_impressao 
                                            WHERE id_referencia = $id_dependente_cliente AND tipo_impressao = 'cartao_dependente'";
                            $query_verifica    = mysql_query($verifica, $banco_produto);
                                 
                            if (mysql_num_rows($query_verifica)>0)
                            {
                                $data_impressao = mysql_result($query_verifica, 0,0);
                                
                                echo "Impresso: <br /><strong style=\"font-size: 12px;\">".converte_data($data_impressao)."</strong>";
                            }
                            ?>
                          
                         </div>
                        
                        
                        <!--/span-->
                    </div>
                        
                        <?php
                        }
                    
                    }else{
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Sem dependente</strong></label>
                                    
                                </div>
                            </div>
                        </div>
                        <?php
                    }  
                    
                    }*/
                    
                    //}
                        ?>
                        <hr />
                <?php
                    
                if($id_cliente_principal > 0)
                {
                    
                    $sql_principal        = "SELECT nome'nome_principal', cpf'cpf_principal' FROM clientes
                                            WHERE id_cliente = $id_cliente_principal";
                    $query_principal      = mysql_query($sql_principal, $banco_produto);
                                    
                    if (mysql_num_rows($query_principal)>0)
                    {
                        $dados_principal = mysql_fetch_array($query_principal);
                        extract($dados_principal);   
                    }
                    
                ?>
                
                <h4>Vínculado à CPF: <?php echo $cpf_principal; ?></h4>
                <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome Principal:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome_principal; ?> </p>
                        </div>
                    </div>
                </div>
                </div>
                <?php
                }else{
                    $sql_tipo_mov = "AND c.tipo_movimento IN ('IN', 'AL')";
                    if($tipo_movimento == 'EX'){
                        $sql_tipo_mov = "AND c.tipo_movimento IN ('IN', 'AL', 'EX')";
                    }
                    $sql_adicional        = "SELECT c.id_cliente'id_cliente_adicional', c.chave'chave_adicional', c.nome'nome_adicional', c.cpf'cpf_adicional', c.data_nascimento'data_nascimento_adicional', v.parentesco FROM clientes c
                                            JOIN vendas v ON c.id_cliente = v.id_cliente
                                            WHERE c.id_cliente_principal = $id_cliente $sql_tipo_mov";
                    $query_adicional      = mysql_query($sql_adicional, $banco_produto);
                                    
                    if (mysql_num_rows($query_adicional)>0)
                    {
                        
                        while($dados_adicional = mysql_fetch_array($query_adicional)){
                            extract($dados_adicional);   
                            
                            if($cpf_adicional == ''){
                                $cpf_adicional = 'Não cadastrado. ';
                            }
                        ?>
                            <div class="row portlet box bg-grey-salsa">
                                <div class="portlet-title">
                                    <div class="caption"><?php echo $parentesco; ?> - CPF Adicional: <?php echo $cpf_adicional; if($tipo_movimento <> 'EX'){?> <a class="btn btn-sm white btn-outline filter-cancel" href="editar.php?item=cliente&tipo=produto&id_base=3&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente_adicional; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=sim&msg_status=editar_cliente" target="_blank"><i class="fa fa-edit"></i> EDITAR</a> <?php }?></div>
                                </div>
                            
                            <div class="portlet-body bg-grey-steel bg-font-grey-steel">
                            <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Nome Adicional:</strong></label>
                                    <div class="col-md-12">
                                        <p class="form-control-static"> <?php echo $nome_adicional; ?> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Data Nasc.:</strong></label>
                                    <div class="col-md-12">
                                        <p class="form-control-static"> <?php echo converte_data($data_nascimento_adicional); ?> </p>
                                    </div>
                                </div>
                            </div>
                           <div class="col-md-3">
                              <a href="inc/comprovantes/html/proposta_assistencia_total/cartao/?cert=<?php echo $chave_adicional; ?>&tipo_plano=<?php echo $id_grupo_produto;?>" class="btn green"target="_blank"><i class="fa fa-credit-card"></i></a><br />
                              
                              <?php
                                $verifica           = "SELECT data_impressao FROM controle_impressao 
                                                 WHERE id_referencia = $id_cliente_adicional AND tipo_impressao = 'cartao'";
                                $query_verifica    = mysql_query($verifica, $banco_produto);
                                     
                                if (mysql_num_rows($query_verifica)>0)
                                {
                                    $data_impressao = mysql_result($query_verifica, 0,0);
                                    
                                    echo "Impresso: <br /><strong style=\"font-size: 12px;\">".converte_data($data_impressao)."</strong>";
                                }
                                ?>
                              
                             </div>
                             
                             
                             
                            </div>
                            <?php
                             $sql_ver_adicional        = "SELECT dep_cli.id_dependente'id_dependente_cliente', dep_cli.tipo_dependente, dep_cli.nome'nome_dependente', dep_cli.data_nascimento'data_nasc_dependente' FROM dependentes_clientes dep_cli
                            WHERE dep_cli.id_cliente = $id_cliente_adicional AND dep_cli.status = 0";
                            
                            
                            $query_ver_adicional      = mysql_query($sql_ver_adicional, $banco_produto);
                                    
                    if (mysql_num_rows($query_ver_adicional)>0)
                    {
                        
                        while($dados_ver_adicional = mysql_fetch_array($query_ver_adicional)){
                            extract($dados_ver_adicional); 
                        ?>
                        <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label col-md-12"><strong>Tipo:</strong></label>
                                <div class="col-md-12">
                                    <p class="form-control-static"> <?php echo $tipo_dependente; ?> </p>
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-12"><strong>Nome:</strong></label>
                                <div class="col-md-12">
                                    <p class="form-control-static"> <?php echo $nome_dependente; ?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label col-md-12"><strong>Nasc.:</strong></label>
                                <div class="col-md-12">
                                    <p class="form-control-static"> <?php echo converte_data($data_nasc_dependente); ?> </p>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-3">
                          <a href="inc/comprovantes/html/proposta_assistencia_total/cartao/index_dependente.php?cert=<?php echo $chave_adicional; ?>&tipo_plano=<?php echo $id_grupo_produto;?>&id_dependente=<?php echo $id_dependente_cliente; ?>" class="btn green"target="_blank"><i class="fa fa-credit-card"></i></a><br />
                          
                          <?php
                            $verifica           = "SELECT data_impressao FROM controle_impressao 
                                            WHERE id_referencia = $id_dependente_cliente AND tipo_impressao = 'cartao_dependente'";
                            $query_verifica    = mysql_query($verifica, $banco_produto);
                                 
                            if (mysql_num_rows($query_verifica)>0)
                            {
                                $data_impressao = mysql_result($query_verifica, 0,0);
                                
                                echo "Impresso: <br /><strong style=\"font-size: 12px;\">".converte_data($data_impressao)."</strong>";
                            }
                            ?>
                          
                         </div>
                        
                        
                        <!--/span-->
                    </div>
                        
                        <?php
                        }
                    
                    }else{
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12"><strong>Sem dependente</strong></label>
                                    
                                </div>
                            </div>
                        </div>
                        <?php
                    }  
                             ?>
                             </div>
                             </div>
                        <?php    
                        }
                        
                        
                    }
                    
                }
                ?>
                
                
                <h4>Serviços / Produtos</h4>
                
                <?php
                
                if($id_produto != ''){
                
                    
                            echo "<div class=\"row\">
                                <div class=\"col-md-12\">
                                    <div class=\"form-group\">
                                        <label class=\"control-label col-md-12\"><strong>$nome_grupo</strong></label>
                                    </div>
                                </div>
                                </div>";
                    }else{
                        echo "Sem serviços adicionais";
                    }
                
                
                ?>
                <hr />
                <div class="row">
                    <div class="col-md-12">                                    
                
                 <?php        
                                                
                    $sql_venda        = "SELECT id_ordem_pedido, tipo_pagamento, desconto, valor_entrada, valor_parcela, valor_parcela_total, valor_total, parcelas, prazo, data_venda, metodo_pagamento, comprovante_maquina, comprovante_online, tid, status_pedido FROM vendas
WHERE id_cliente = $id_cliente";
                    $query_par_produto      = mysql_query($sql_venda, $banco_produto);
                                
                    if (mysql_num_rows($query_par_produto)>0)
                    {
                        $clinte_externo = false;
                        $dados = mysql_fetch_array($query_par_produto);
                        extract($dados); 
                        
                        if($desconto == ''){
                            $desconto = 0;
                        }
                        
                        if($valor_entrada == ''){
                            $valor_entrada = 0;
                        }
                        
                        if($prazo == 0){
                            $prazo = "Recorrente";
                        }
                    }else{
                        $clinte_externo = true;
                    }  
                
                if($clinte_externo == false){
                    
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Responsável pelo cadastro:</strong></label>
                            <div class="col-md-12">
                            
                                 <?php 
                                $sql_responsavel        = "SELECT id_usuario_pedido, status_renovacao FROM ordem_pedidos
WHERE id_ordem_pedido = $id_ordem_pedido";
                                $query_responsavel      = mysql_query($sql_responsavel, $banco_painel);
                                            
                                if (mysql_num_rows($query_responsavel)>0)
                                {
                                    
                                    $id_usuario_pedido  = mysql_result($query_responsavel, 0,0);
                                    $status_renovacao   = mysql_result($query_responsavel, 0,1);
                                    
                                    if(!empty($id_usuario_pedido)){
                                        $sql_nome_responsavel        = "SELECT nome FROM usuarios
WHERE id_usuario = $id_usuario_pedido";
                                        $query_nome_responsavel      = mysql_query($sql_nome_responsavel, $banco_painel);
                                        $nome_responsavel = mysql_result($query_nome_responsavel, 0,0);            
                                        
                                        echo $nome_responsavel;
                                    }
                                    
                                    
                                }
                                
                                

                                ?>
                                &nbsp;  <br /><br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong># pedido:</strong></label>
                            <div class="col-md-12">
                                <?php echo $id_ordem_pedido;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Tipo Pgto:</strong></label>
                            <div class="col-md-12">
                                <?php echo str_replace("_", " ", $tipo_pagamento);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Desconto:</strong></label>
                            <div class="col-md-12">
                                <?php echo $desconto."%";?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Entrada:</strong></label>
                            <div class="col-md-12">
                                <?php echo db_moeda($valor_entrada);?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Parcela:</strong></label>
                            <div class="col-md-12">
                                <?php echo db_moeda($valor_parcela_total);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Total:</strong></label>
                            <div class="col-md-12">
                                <?php 
                                
                                
                                /*if($desconto > 0)
                                {
                                    
                                    function porcentagem_xn ( $porcentagem, $total ) {
                                    	return ( $porcentagem / 100 ) * $total;
                                    }
                                    
                                    $calculo_com_desconto = porcentagem_xn ( $desconto, $valor_total );
                                    $novo_valor_parcela = $valor_total - $calculo_com_desconto; 
                                    
                                    $calculo_soma_total = $novo_valor_parcela;
                                }
                                else
                                {*/
                                    
                                    $calculo_soma_total = $valor_total + moeda_db($valor_entrada);
                                //}
                                
                                
                                echo db_moeda($calculo_soma_total);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Parcelas:</strong></label>
                            <div class="col-md-12">
                                <?php echo $parcelas;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Prazo:</strong></label>
                            <div class="col-md-12">
                                <?php echo $prazo;?> meses
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Data venda:</strong></label>
                            <div class="col-md-12">
                                <?php echo converte_data($data_venda);?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Compr. ON:</strong></label>
                            <div class="col-md-12">
                                <?php echo $comprovante_maquina;?>  <?php echo $comprovante_online;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>TID:</strong></label>
                            <div class="col-md-12">
                                <?php echo $tid;?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong>Observações:</strong></label>
                            <div class="col-md-12">
                                <?php echo $observacao;?>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div>
                <hr />
                <div class="row linha_lista_pagamentos">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-12"><strong style="text-transform: uppercase;"><?php echo str_replace("_", " ", $tipo_pagamento);?></strong></label>
                            <div class="col-md-12">
                            <p><span class="label label-sm label-success">&nbsp;</span> pago &nbsp; &nbsp; <span class="label label-sm label-danger">&nbsp;</span> à pagar &nbsp; &nbsp; <span class="label label-sm label-warning">&nbsp;</span> 2° via</p>
                            
                             <?php
                                if($id_cliente_principal == 0 AND ($metodo_pagamento == 'BO' OR $metodo_pagamento == 'MA'))
                                {
                                
                                ?>
                                <p>
                                
                                <?php
                                if($tipo_movimento == 'EX'){
                                    $sql    = "SELECT COUNT(*) FROM boletos_clientes
                                    WHERE id_ordem_pedido = $id_ordem_pedido AND status_boleto = 2";
                                    $query      = mysql_query($sql, $banco_painel);
                                                    
                                    if (mysql_num_rows($query)>0)
                                    {
                                        $parcela_com_entrada = $parcelas;
                                        if($valor_entrada > 0){
                                            $parcela_com_entrada = $parcelas + 1;
                                        }
                                        $total_contar_boletos = mysql_result($query, 0,0);
                                        $total_contar_boletos = $total_contar_boletos - $parcela_com_entrada;
                                        $sql_status_boleto = "AND status_boleto = 2
                                                          ORDER BY id_boleto ASC
                                                          LIMIT $total_contar_boletos,$parcela_com_entrada";
                                        $ativar_plano_cancelado = '&ativar_plano_cancelado=ok';
                                        
                                        
                                        
                                        
                                    }

                                }else{
                                    $sql_status_boleto = 'AND status_boleto = 0';
                                }
                                
                                
                                $sql    = "SELECT * FROM boletos_clientes
                                        WHERE id_ordem_pedido = $id_ordem_pedido $sql_status_boleto";
                                //echo $sql;
                                $query      = mysql_query($sql, $banco_painel);
                                                
                                if (mysql_num_rows($query)>0)
                                {
                                    //$i = 1;
                                    
                                    while($dados = mysql_fetch_array($query)){
                                    extract($dados);
                                    $verificar_entrada = false;
                                    if($entrada == 'N'){
                                        $html_bt_btn = 'btn btn-sm red';
                                        $html_parcela = $parcela."° parcela ".$tipo_recebimento;
                                    }else{
                                        $html_bt_btn = 'btn btn-sm green-haze';
                                        $html_parcela = "Entrada ".$tipo_recebimento;
                                        $verificar_entrada = true;
                                        //$i = $i - 1;
                                    }
                                    
                                    if($pago == 'N'){
                                        if($metodo_pagamento == 'MA'){
                                        ?>
                                        <a href="javascript:;" class="btn btn-sm red"> <i class="fa fa-print"></i> <?php echo $html_parcela;?> <br /> <?php echo converte_data($data_vencimento);?></a> 
                                        <?php
                                        }else{ 
                                            $link_boletos_carne = 'ver_boletos_cliente_confirmacao.php';
                                            if($tipo_boleto == 'LOJA'){
                                                $link_boletos = $cobr_tipo_cobranca.'/'.$cobr_banco.'/ver_boletos_cliente_loja.php';
                                            }elseif($tipo_boleto == 'BANCO'){

                                                    $link_boletos = 'ver_boletos_cliente.php';

                                            }
                                    $cor_botao = 'red';
                                    $verifica           = "SELECT data_impressao FROM controle_impressao 
                                                    WHERE id_referencia = $id_boleto AND tipo_impressao = 'boleto_banco'";
                                    $query_verifica    = mysql_query($verifica, $banco_produto);
                                         
                                    if (mysql_num_rows($query_verifica)>0)
                                    {
                                        $data_impressao = mysql_result($query_verifica, 0,0);
                                        
                                        $cor_botao = 'btn-warning';
                                        
                                    }
                                    
                                    if($entrada == 'S'){
                                        $valor_parcela = $valor_entrada;
                                    }
                                    ?>
                                     <div class="btn-group">
                                           <button type="button" class="btn btn-sm <?php echo $cor_botao;?>" data-toggle="dropdown"><i class="fa fa-print"></i> <?php echo $html_parcela;?> <br /> <?php echo db_moeda($valor_parcela);?><br />  <?php echo converte_data($data_vencimento);?></button> 
                                           <ul class="dropdown-menu hold-on-click">
                                                <li>
                                                    <a href="<?php echo $link_boletos;?>?id_ordem_pedido=<?php echo $id_ordem_pedido; ?>&id_boleto=<?php echo $id_boleto;?>" target="_blank" ><i class="fa fa-print"></i> Imprimir</a> 
                                                </li>
                                                <li class="divider"> </li>
                                                <li>
                                                   <label style="padding: 8px 16px;font-size: 10px;"><?php echo $obs;?></label> 
                                                </li>
                                           </ul>
                                      </div>                                    
                                    
                                    <?php
                                    }
                                    }else{
                                       ?>
                                       <div class="btn-group">
                                           <button type="button" class="btn btn-sm green-haze" data-toggle="dropdown"><?php echo $html_parcela;?> <br /> Pago: <?php echo db_moeda($valor_recebido);?><br /> Em: <?php echo converte_data($data_pagamento);?></button> 
                                           <ul class="dropdown-menu hold-on-click">
                                                <li>
                                                    <label style="padding: 8px 16px;">Vencimento: <br /> <?php echo converte_data($data_vencimento);?></label>
                                                </li>
                                                <li>
                                                    <label style="padding: 8px 16px;">Valor Parcela: <br /> <?php echo db_moeda($valor_parcela);?></label> 
                                                </li>
                                                <li>
                                                    <label style="padding: 8px 16px;"><a href="http://trailservicos.com.br/painel_trail/inc/ver_comprovante_cliente_2.php?id_boleto=<?php echo $id_boleto;?>" target="_blank" class="btn btn-sm purple "><i class="fa fa-barcode"></i> Comprovante</a></label> 
                                                </li>
                                                <li class="divider"> </li>
                                                <li>
                                                   <label style="padding: 8px 16px;font-size: 10px;"><?php echo $obs;?></label> 
                                                </li>
                                           </ul>
                                      </div>
                                    <?php 
                                    }
                                    //$i++;
                                    }
                                }
                                
                                ?>
                                 </p>
                                
                                <?php
                                    if($metodo_pagamento == 'BO'){
                                    
                                    $cor_botao = 'green';
                                    $verifica           = "SELECT data_impressao FROM controle_impressao 
                                                    WHERE id_referencia = $id_ordem_pedido AND tipo_impressao = 'boleto_banco_carnet'";
                                    $query_verifica    = mysql_query($verifica, $banco_produto);
                                         
                                    if (mysql_num_rows($query_verifica)>0)
                                    {
                                        $data_impressao = mysql_result($query_verifica, 0,0);
                                        
                                        $cor_botao = 'btn-warning';
                                        
                                    }
                                    
                                 ?>
                                    <p class="form-control-static">
                                    <div class="btn-group">
                                           <button type="button" class="btn btn-sm <?php echo $cor_botao;?>" data-toggle="dropdown"><i class="fa fa-print"></i> Boletos-carnê</button> 
                                           <ul class="dropdown-menu hold-on-click">
                                                <li>
                                                    <a href="<?php echo $link_boletos_carne;?>?id_ordem_pedido=<?php echo $id_ordem_pedido; ?>" id="bt_boleto_" target="_blank" > <i class="fa fa-print"></i> Abrir</a> 
                                                </li>
                                                <li class="divider"> </li>
                                                <li>
                                                   <label style="padding: 8px 16px;font-size: 10px;"><?php echo $obs;?></label> 
                                                </li>
                                           </ul>
                                      </div> 
                                      </p> 
                                    <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>  
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-group accordion" id="accordion3">
                            <input type="hidden" name="id_cliente_hidden" id="id_cliente_hidden" value="<?php echo $id_cliente; ?>" />
                            
                            <?php
                            
                            if($status_renovacao == 'S'){
                            ?>
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_3">RENOVAÇÃO ANTERIOR</a>
                                    </h4>
                                </div>
                                <div id="collapse_3_3" class="panel-collapse collapse">
                                    <div class="portlet-body bg-grey-cararra">
                                                                           
                
                 <?php        
                                                
                    $sql_venda        = "SELECT tipo_pagamento, desconto, valor_entrada, valor_adicional, valor_parcela, valor_parcela_total, valor_total, parcelas, prazo, data_venda, id_usuario'id_usuario_venda', data_cadastro FROM historico_vendas
                                        WHERE id_ordem_pedido = $id_ordem_pedido
                                        ORDER BY id_historico DESC";
                    $query_par_produto      = mysql_query($sql_venda, $banco_produto);
                                
                    if (mysql_num_rows($query_par_produto)>0)
                    {
                        $clinte_externo = false;
                        while($dados = mysql_fetch_array($query_par_produto))
                        {
                            extract($dados); 
                            
                            
                            if($desconto == ''){
                            $desconto = 0;
                        }
                      
                        if($valor_entrada == ''){
                            $valor_entrada = 0;
                            
                        }
                        
                        if($prazo == 0){
                            $prazo = "Recorrente";
                        }

                        ?>    

                            <div class="row">
                                <div class="col-md-12 linha_lista_pagamentos">
                                    <p>
                                    
                                    <?php
                                    
                                    $sql    = "SELECT COUNT(*) FROM boletos_clientes
                                    WHERE id_ordem_pedido = $id_ordem_pedido AND status_boleto != 0 AND status_boleto != 2";
                                    $query      = mysql_query($sql, $banco_painel);
                                    $sql_status_boleto = '';                  
                                    if (mysql_num_rows($query)>0)
                                    {
                                        $parcela_com_entrada = $parcelas;
                                        if($valor_entrada > 0){
                                            $parcela_com_entrada = $parcelas + 1;
                                        }
                                        $total_contar_boletos = mysql_result($query, 0,0);
                                        
                                        $total_contar_boletos = $total_contar_boletos - $parcela_com_entrada;
                                        if($total_contar_boletos < 0){
                                            $total_contar_boletos = 0;
                                        }
                                        $sql_status_boleto = "AND status_boleto != 0 AND status_boleto != 2
                                                          ORDER BY id_boleto ASC
                                                          LIMIT $total_contar_boletos,$parcela_com_entrada";
                                        //$ativar_plano_cancelado = '&ativar_plano_cancelado=ok';

                                    }
                                    
                                    
                                    //$sql_status_boleto = 'AND status_boleto = 1';
                                    
                                    $sql    = "SELECT * FROM boletos_clientes
                                        WHERE id_ordem_pedido = $id_ordem_pedido $sql_status_boleto
                                        ";
                            //echo $sql;
                                $query      = mysql_query($sql, $banco_painel);
                                                
                                if (mysql_num_rows($query)>0)
                                {
                                    //$i = 1;
                                    
                                    while($dados = mysql_fetch_array($query)){
                                    extract($dados);
                                    $verificar_entrada = false;
                                    if($entrada == 'N'){
                                        $html_bt_btn = 'btn btn-sm red';
                                        $html_parcela = $parcela."° parcela ".$tipo_recebimento;
                                    }else{
                                        $html_bt_btn = 'btn btn-sm green-haze';
                                        $html_parcela = "Entrada ".$tipo_recebimento;
                                        $verificar_entrada = true;
                                        //$i = $i - 1;
                                    }
                                    
                                    if($pago == 'N'){
                                        if($metodo_pagamento == 'MA'){
                                        ?>
                                        <a href="javascript:;" class="btn btn-sm red"> <i class="fa fa-print"></i> <?php echo $html_parcela;?> <br /> <?php echo converte_data($data_vencimento);?></a> 
                                        <?php
                                        }else{ 
                                            $link_boletos_carne = 'ver_boletos_cliente_confirmacao.php';
                                            if($tipo_boleto == 'LOJA'){
                                                $link_boletos = $cobr_tipo_cobranca.'/'.$cobr_banco.'/ver_boletos_cliente_loja.php';
                                                //$link_boletos = 'ver_boletos_cliente_loja.php';
                                            }elseif($tipo_boleto == 'BANCO'){
                                                /*if($verificar_entrada == true){
                                                    $link_boletos = 'ver_boletos_cliente_loja.php';
                                                }else{*/
                                                    $link_boletos = 'ver_boletos_cliente.php';
                                                //}
                                                
                                            }
                                    $cor_botao = 'red';
                                    $verifica           = "SELECT data_impressao FROM controle_impressao 
                                                    WHERE id_referencia = $id_boleto AND tipo_impressao = 'boleto_banco'";
                                    $query_verifica    = mysql_query($verifica, $banco_produto);
                                         
                                    if (mysql_num_rows($query_verifica)>0)
                                    {
                                        $data_impressao = mysql_result($query_verifica, 0,0);
                                        
                                        $cor_botao = 'btn-warning';
                                        
                                    }
                                    
                                    if($entrada == 'S'){
                                        $valor_parcela = $valor_entrada;
                                    }
                                    ?>
                                     <div class="btn-group">
                                           <button type="button" class="btn btn-sm <?php echo $cor_botao;?>" data-toggle="dropdown"><i class="fa fa-print"></i> <?php echo $html_parcela;?> <br /> <?php echo db_moeda($valor_parcela);?><br />  <?php echo converte_data($data_vencimento);?></button> 
                                           <ul class="dropdown-menu hold-on-click">
                                                <li>
                                                    <a href="<?php echo $link_boletos;?>?id_ordem_pedido=<?php echo $id_ordem_pedido; ?>&id_boleto=<?php echo $id_boleto;?>" target="_blank" ><i class="fa fa-print"></i> Imprimir</a> 
                                                </li>
                                                <li class="divider"> </li>
                                                <li>
                                                   <label style="padding: 8px 16px;font-size: 10px;"><?php echo $obs;?></label> 
                                                </li>
                                           </ul>
                                      </div>                                    
                                    
                                    <?php
                                    }
                                    }else{
                                       ?>
                                       <div class="btn-group">
                                           <button type="button" class="btn btn-sm green-haze" data-toggle="dropdown"><?php echo $html_parcela;?> <br /> Pago: <?php echo db_moeda($valor_recebido);?><br /> Em: <?php echo converte_data($data_pagamento);?></button> 
                                           <ul class="dropdown-menu hold-on-click">
                                                <li>
                                                    <label style="padding: 8px 16px;">Vencimento: <br /> <?php echo converte_data($data_vencimento);?></label>
                                                </li>
                                                <li>
                                                    <label style="padding: 8px 16px;">Valor Parcela: <br /> <?php echo db_moeda($valor_parcela);?></label> 
                                                </li>
                                                <li>
                                                    <label style="padding: 8px 16px;"><a href="http://trailservicos.com.br/painel_trail/inc/ver_comprovante_cliente_2.php?id_boleto=<?php echo $id_boleto;?>" target="_blank" class="btn btn-sm purple "><i class="fa fa-barcode"></i> Comprovante</a></label> 
                                                </li>
                                                <li class="divider"> </li>
                                                <li>
                                                   <label style="padding: 8px 16px;font-size: 10px;"><?php echo $obs;?></label> 
                                                </li>
                                           </ul>
                                      </div>
                                    <?php 
                                    }
                                    //$i++;
                                    }
                                }
                                    
                                    ?>
                                    
                                    </p>
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Usuário da venda:</strong></label>
                                        <div class="col-md-12">
                                        
                                             <?php 
            
                                                $sql_nome_responsavel        = "SELECT nome FROM usuarios
                                                                            WHERE id_usuario = $id_usuario_venda";
                                                $query_nome_responsavel      = mysql_query($sql_nome_responsavel, $banco_painel);
                                                $nome_responsavel = mysql_result($query_nome_responsavel, 0,0);            
                                                
                                                echo $nome_responsavel;
            
                                            ?>
                                            &nbsp;  <br /><br />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong># pedido:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo $id_ordem_pedido;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Tipo Pgto:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo str_replace("_", " ", $tipo_pagamento);?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Desconto:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo $desconto."%";?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Entrada:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo db_moeda($valor_entrada);?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Parcela:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo db_moeda($valor_parcela_total);?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Total:</strong></label>
                                        <div class="col-md-12">
                                            <?php 
                                           
                                                $calculo_soma_total = $valor_total + moeda_db($valor_entrada);
            
                                            echo db_moeda($calculo_soma_total);?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Parcelas:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo $parcelas;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Prazo:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo $prazo;?> meses
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Data venda:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo converte_data($data_venda);?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>Data Renovação:</strong></label>
                                        <div class="col-md-12">
                                            <?php echo converte_data($data_cadastro);?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-12"><strong>&nbsp;</strong></label>
                                        <div class="col-md-12">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <hr />
                                                        
                        <?php    
                        }
                        
                        
                        


                    }
                    
                ?>
                
       
                
                                    </div>
                                </div>
                            </div>
                            
                            
                            <?php
                            }
                            
                            
                            ?>
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_0">COMENTÁRIOS <label class="btn btn-transparent red btn-outline btn-circle btn-sm active">
                                        <?php
                                            $sql    = "SELECT COUNT(*) FROM comentarios_atividades
                                                    WHERE id_referencia = $id_cliente AND tipo_historico = 'clientes'";
                                        
                                            $query      = mysql_query($sql, $banco_painel);
                                            $contar_historico = '0';                
                                            if (mysql_num_rows($query)>0)
                                            {
                                                $contar_historico = mysql_result($query, 0,0);
                                                
                                            }
                                            echo $contar_historico;
                                        ?>
                                        </label></a>
                                    </h4>
                                </div>
                                <div id="collapse_3_0" class="panel-collapse collapse">
                                    <div class="portlet-body bg-grey-cararra">
                                        <div class="table-scrollable table-scrollable-borderless">
                                                <table class="table table-hover table-light" id="tabela_lista_comentarios">
                                                    <thead>
                                                        <tr class="uppercase">
                                                            <th width='20%'> Data </th>
                                                            <th width='30%'> Criado por </th>
                                                            <th width='50%'> Descrição </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $sql    = "SELECT * FROM comentarios_atividades
                                                                WHERE id_referencia = $id_cliente AND tipo_historico = 'clientes'";
                                                    
                                                        $query      = mysql_query($sql, $banco_painel);
                                                                        
                                                        if (mysql_num_rows($query)>0)
                                                        {
                                                            while($dados_historico = mysql_fetch_array($query)){
                                                                extract($dados_historico);
                                                                
                                                                $sql_user   = "SELECT us.nome'nome_usuario' FROM usuarios us
                                                                            WHERE us.id_usuario = $id_usuario";
                                                                $query_user = mysql_query($sql_user, $banco_painel);
                                                                $nome_usuario = '';                
                                                                if (mysql_num_rows($query_user)>0)
                                                                {
                                                                    $nome_usuario = mysql_result($query_user, 0);    
                                                                }
                                                              
                                                              echo "<tr>
                                                                    <td> ".converte_data($data_alteracao)." </td>
                                                                    <td> $id_usuario - $nome_usuario </td>
                                                                    <td> $descricao </td>
                                                                </tr>";
                                                            }
                                                        }
                                                        
                                                    ?>

                                                    </tbody>
                                                </table>
                                                
                                            </div>  
                                            <?php
                                             if(in_array("57", $verifica_lista_permissoes_array_inc)){
                                                            $nome_primeiro_nome = explode(" ", $nome);
                                             ?>
                                        <div class="note note-info" style="display: none;" id="enviar_sms_html">
                                                <h4 class="block">Confirmar o envio de SMS de contato? A seguinte mensagem será enviada.</h4>
                                                <p> A REALIZA + SAUDE Assistencia Familiar precisa falar com voce, ligue para 08006022211 ou envie um WhatsApp para 43999941103 - URGENTE</p>
                                                <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" id="bt_enviar_sms_ok" onclick="return enviar_sms('<?php echo $id_cliente; ?>', 'cobranca', '<?php echo $nome_primeiro_nome[0];?>', '<?php echo $celular_sms;?>');" class="btn blue">
                                                            <i class="fa fa-check"></i> Sim, enviar!</button>
                                                        <button type="button" class="btn default" onclick="return enviar_sms_html('false');">Cancelar</button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <?php 
                                        }
                                        ?>   
                                        <div class="form-horizontal form-bordered" style="display: none;" id="form_add_comentario">
                                            <hr />
                                            <div class="form-body">
                                                <div class="form-group last">
                                                    <div class="col-md-12">
                                                        <textarea class="col-md-12" rows="4" cols="4" name="texto_comentario" style="resize: none;" id="campo_add_comentario"> </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn green" onclick="return confirma_add_comentario();" id="bt_enviar_comentario">
                                                            <i class="fa fa-check"></i> Salvar</button>
                                                        <button type="button" class="btn default" onclick="return exibe_add_comentario('false');">Cancelar</button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-actions" id="exibe_bt_add_comentario">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a href="javascript:;" id="bt_add_comentario" class="btn green" onclick="return exibe_add_comentario('true');"> Adicionar Comentário <i class="fa fa-plus"></i></a>
                                                        <?php
                                                        if(in_array("57", $verifica_lista_permissoes_array_inc)){
                                                            //$nome_primeiro_nome = explode(" ", $nome);
                                                            
                                                            $celular_sms_verificar = substr($celular_sms, 3,1);
                                                            if($celular_sms_verificar == 9 OR $celular_sms_verificar == 8){
                                                                
                                                        ?>
                                                                <span id="bt_enviar_sms_txt"><a data-toggle="modal" href="javascript:" class="btn blue" onclick="return enviar_sms_html('true');" id="btn_enviar_sms_html">
                                                                <i class="fa fa-send"></i> Enviar SMS para contato</a> <span id="div_aguarde_envia_sms" style="display: none;"><img src="assets/global/img/loading-spinner-grey.gif"/> Aguardando resposta...</span></span>
                                                                <?php
                                                                $verifica   = "SELECT data_alteracao FROM historicos_atividades 
                                                                            WHERE id_referencia = $id_cliente AND descricao LIKE '%a REALIZA + SAUDE Assistencia Familiar precisa falar com voce%'
                                                                            ORDER BY id_historico DESC
                                                                            LIMIT 0,1";
                                                                $query_verifica    = mysql_query($verifica, $banco_painel);
                                                                     
                                                                if (mysql_num_rows($query_verifica)>0)
                                                                {
                                                                    $data_impressao = mysql_result($query_verifica, 0,0);
                                                                    
                                                                    echo "<span> Data do último SMS enviado: <strong>".converte_data($data_impressao)."</strong></span>";
                                                                }
                                                            }else{
                                                                echo '<span id="bt_enviar_sms_txt"> Sem celular cadastrado para endio de SMS.';
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1"> HISTÓRICO <label class="btn btn-transparent red btn-outline btn-circle btn-sm active">
                                        <?php
                                            $sql    = "SELECT COUNT(*) FROM historicos_atividades
                                                    WHERE id_referencia = $id_cliente AND tipo_historico = 'clientes'";
                                        
                                            $query      = mysql_query($sql, $banco_painel);
                                            $contar_historico = '0';                
                                            if (mysql_num_rows($query)>0)
                                            {
                                                $contar_historico = mysql_result($query, 0,0);
                                                
                                            }
                                            echo $contar_historico;
                                        ?>
                                        </label></a>
                                    </h4>
                                </div>
                                <div id="collapse_3_1" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    <div class="table-scrollable table-scrollable-borderless">
                                                <table class="table table-hover table-light">
                                                    <thead>
                                                        <tr class="uppercase">
                                                            <th width='20%'> Data </th>
                                                            <th width='30%'> Criado por </th>
                                                            <th width='50%'> Descrição </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    
                                                    <?php
                                                        $sql    = "SELECT * FROM historicos_atividades
                                                                WHERE id_referencia = $id_cliente AND tipo_historico = 'clientes'
                                                                ORDER BY id_historico DESC";
                                                    
                                                        $query      = mysql_query($sql, $banco_painel);
                                                                        
                                                        if (mysql_num_rows($query)>0)
                                                        {
                                                            
                                                            while($dados_historico = mysql_fetch_array($query)){
                                                                extract($dados_historico);
                                                                
                                                                $sql_user   = "SELECT us.nome'nome_usuario' FROM usuarios us
                                                                            WHERE us.id_usuario = $id_usuario";
                                                                $query_user = mysql_query($sql_user, $banco_painel);
                                                                $nome_usuario = '';                
                                                                if (mysql_num_rows($query_user)>0)
                                                                {
                                                                    $nome_usuario = mysql_result($query_user, 0);    
                                                                }
                                                              
                                                              echo "<tr>
                                                                    <td> ".converte_data($data_alteracao)." </td>
                                                                    <td> $id_usuario - $nome_usuario </td>
                                                                    <td> $descricao </td>
                                                                </tr>";
                                                            }
                                                        }
                                                        
                                                    ?>
                                                    
                                                        
                                                       
                                                    </tbody>
                                                </table>
                                            </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <?php 
                 }
                 ?>       
                <p></p>
                
            
        
    </div>
    
<?php
}elseif($slug == 'sorteio_ead'){
    
    
    $sql        = "SELECT * FROM vendas v
                JOIN titulos t ON v.id_titulo = t.id_titulo
                WHERE id_venda = $id_cliente";
    $query      = mysql_query($sql, $banco_produto);
                    
    if (mysql_num_rows($query)>0)
    {
        $dados = mysql_fetch_array($query);
        extract($dados);   
  
    }
    
    
    $sql        = "SELECT pa.nome'nome_parceiro' FROM parceiros pa
                WHERE pa.id_parceiro = $id_parceiro";
    $query      = mysql_query($sql, $banco_painel);
    $nome_parceiro = '';                
    if (mysql_num_rows($query)>0)
    {
        $nome_parceiro = mysql_result($query, 0);    
    }
    
    
    ?>
    
    <script>
    
    function lista_filial(){
    {   
        $('#lista_filiais').show();
    }};
    
    function remove_lista_filial(){
    {   
        $('#lista_filiais').hide();
    }};
    </script>
    
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <h4>Dados pessoais <small>(<?php echo "PF"; ?>)</small></h4>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Nome:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $nome; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>CPF:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $cpf; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Número da Sorte:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $numero_sorteio; ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Data de Sorteio:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo converte_data($data_sorteio); ?> <span class="label label-sm label-<?php $label_status = ($status_cliente == 'Ativo') ? 'success' : 'danger'; echo $label_status; ?>"><?php echo $status_cliente; ?></span> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Data de Nasc.:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo converte_data($dt_nascimento); ?> </p>
                        </div>
                    </div>
                </div>
                
                
                <!--/span-->
                </div>
                <h4>Endereço/Contato</small></h4>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>CEP:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $cep; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Endereço:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $endereco; ?>, N° <?php echo $numero;?>, Bairro <?php echo $bairro;?><?php $complemento = (!empty($complemento)) ? ', '.$complemento : ''; echo $complemento;?>,  <?php echo $cidade;?> - <?php echo $estado;?></p>
                        </div>
                    </div>
                </div>
                <!--/span-->
                </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-12"><strong>Telefone:</strong></label>
                        <div class="col-md-12">
                            <p class="form-control-static"> <?php echo $telefone; ?> </p>
                        </div>
                    </div>
                </div>
                <!--/span-->
               
                <!--/span-->
                </div>

                <h4>Serviço contratado</small></h4>
                <?php
                
                if($id_produto != ''){
                
                    $sql_par_produto        = "SELECT gpro.nome'nome_grupo', pro.nome'nome_produto' FROM grupos_produtos gpro
JOIN produtos_grupos prog ON gpro.id_grupo_produto = prog.id_grupo_produto
JOIN produtos pro ON prog.id_produto = pro.id_produto
WHERE gpro.id_grupo_produto = $id_produto";
                    $query_par_produto      = mysql_query($sql_par_produto, $banco_painel);
                                
                    if (mysql_num_rows($query_par_produto)>0)
                    {
                        $dados = mysql_fetch_array($query_par_produto);
                        extract($dados); 
                            echo "<div class=\"row\">
                                <div class=\"col-md-12\">
                                    <div class=\"form-group\">
                                        <label class=\"control-label col-md-12\"><strong>$nome_grupo</strong></label>
                                    </div>
                                </div>
                                </div>";
                    }else{
                        echo "Sem serviços adicionais";
                    }
                
                }else{
                        echo "Sem serviços adicionais";
                }
                ?>
                           
            <?php                
       
            ?>                                
                        
                <p></p>
                
            </div>
        </div>
    </div>


<?php 
}
?>
    <div class="modal-footer">
        <button type="button" class="btn default" data-dismiss="modal">Fechar</button>
        <!--<button type="button" class="btn green default" onclick="return lista_filial();"><i class="fa fa-users"></i> Filiais</button>-->
        <?php
        if($slug == 'europ'){

        $plano_adicional = 'nao';
            if($id_cliente_principal > 0)
        {
            $plano_adicional = 'sim';
        }
        
        
        if($status_cliente =='Ativo')
        {
            
            if($data_termino != "0000-00-00"){
                
                /*$data = somar_datas( 1, 'm'); // adiciona meses a sua data          
                $data_restante = date('d/m/Y', strtotime($data));
                $data_restante = converte_data($data_restante);*/
                if(strtotime($data_termino) <= strtotime($data_restante) AND $id_cliente_principal == 0){
                ?>
                 <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=renovar_venda" class="btn blue">Renovar agora</a>
                 <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=editar_cliente" class="btn blue">Atualizar</a>
                <?php
                
                }
                else
                {
                if($nivel_usuario == 'A'){
                    ?>
                    <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=editar_cliente" class="btn blue">Atualizar</a>
                    
                    <?php 
                    if($status == 99 OR $status == 0){// mudar status finalizar venda
                        if($nivel_status == 0 AND $plano_adicional == 'nao'){
                        ?>
                         <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda&alterar_pagamento=true<?php echo $ativar_plano_cancelado;?>" class="btn btn-link"><i class="fa fa-random"></i> Alterar pagamento</a> 
                        <?php
                        }
                    }
                    }else{
                        if($status == 0 OR in_array("53", $verifica_lista_permissoes_array_inc)){
                        ?>
                        <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=editar_cliente" class="btn blue">Atualizar</a>
                        <?php
                        }else{
                        ?>
                            <a href="#atualizar_cli_usr"  data-toggle="modal" class="btn blue">Atualizar</a>
                        <?php 
                        }
                        
                        
                        }
                }

                
            }else{
                if($nivel_usuario == 'A' OR in_array("53", $verifica_lista_permissoes_array_inc)){
                    ?>
            
            <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=editar_cliente" class="btn blue">Atualizar</a>
            
            <?php 
                }else
                {
                    ?>
            
            <a href="#atualizar_cli_usr"  data-toggle="modal" class="btn blue">Atualizar</a>
            
            <?php 
                }
               
            }
        ?>
        <a href="inc/comprovantes/html/proposta_assistencia_total/?cert=<?php echo $chave; ?>" class="btn green" target="_blank">PROPOSTA</a>    
        <!--<a href="inc/comprovantes/html/proposta_assistencia_total/cert/?cert=<?php echo $chave; ?>" class="btn green"target="_blank">CERTIFICADO</a>-->    
        <a href="http://www.trailservicos.com.br/condicoes_gerais/contrato_assistencia_geral.pdf" class="btn green"target="_blank">CERTIFICADO</a>
        <a href="inc/comprovantes/html/proposta_assistencia_total/cartao/?cert=<?php echo $chave; ?>&tipo_plano=<?php echo $id_grupo_produto;?>" class="btn green"target="_blank">CARTÃO</a>
        <?php
        $verifica           = "SELECT data_impressao FROM controle_impressao 
                        WHERE id_referencia = $id_cliente AND tipo_impressao = 'cartao'";
        $query_verifica    = mysql_query($verifica, $banco_produto);
             
        if (mysql_num_rows($query_verifica)>0)
        {
            $data_impressao = mysql_result($query_verifica, 0,0);
            
            echo "<p> Data da última impressão do CARTÃO: <strong>".converte_data($data_impressao)."</strong></p>";
        }
        ?>
        <?php
        }else{
            ?>
            <a href="http://www.trailservicos.com.br/condicoes_gerais/contrato_assistencia_geral.pdf" class="btn green"target="_blank">CERTIFICADO</a>
            <?php
            if($status == 3){ // é dependente
                if($tipo_movimento == 'IN' OR $tipo_movimento == 'AL'){
                ?>
                <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_cadastro" class="btn blue">Finalizar Cadastro</a>
                <?php 
                }   
            }
            elseif($status == 4){
                
                if($tipo_movimento == 'IN' OR $tipo_movimento == 'AL'){
                    if($data_termino != "0000-00-00")
                    {
                        if(strtotime($data_termino) >= strtotime($data_restante) AND $id_cliente_principal == 0){
                        if($plano_adicional == 'nao'){
                            ?>
                            <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda" class="btn blue">Finalizar Venda</a> 
                            <?php
                            }
                        }else{
                        ?>
                         <a href="adicionar.php?item=clientes&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda" class="btn blue">Nova Venda</a> 
                        <?php
                        }
                    }else{
                        if($plano_adicional == 'nao'){
                        ?>
                        <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda" class="btn blue">Finalizar Venda</a> 
                        <?php
                        }
                    }
                }

            ?>
            <?php
            }
            else{
                if($status == 6){// mudar status finalizar venda
                    if($nivel_status == 0){

                        $sql_status_renovacao        = "SELECT status_renovacao FROM ordem_pedidos
    WHERE id_ordem_pedido = $id_ordem_pedido";
                        $query_status_renovacao      = mysql_query($sql_status_renovacao, $banco_painel);
                        $status_renovacao = '';            
                        if (mysql_num_rows($query_status_renovacao)>0)
                        {
                            $status_renovacao = mysql_result($query_status_renovacao, 0,0);
                           
                        }
                    
                    if($tipo_movimento != 'EX'){        
                    ?>
                    <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=editar_cliente" class="btn blue">Atualizar</a>
                    <?php 
                    }
                    if($plano_adicional == 'nao'){
                    ?>
                    <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda&alterar_pagamento=true<?php echo $ativar_plano_cancelado;?>" class="btn btn-link"><i class="fa fa-random"></i> Alterar pagamento</a> 
                    <?php
                    if($nivel_usuario == 'A'){
                        ?>
                        <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=ativar_venda&status_renovacao=<?php echo $status_renovacao; ?>" class="btn btn-success"> <i class="fa fa-check"></i> Ativar venda!</a> 
                        <?php
                        }
                    }
                    ?>
                        
                        <a href="inc/comprovantes/html/proposta_assistencia_total/?cert=<?php echo $chave; ?>" class="btn green" target="_blank">PROPOSTA</a>
                        
                <?php
                    }else{
              
                    ?>
                    <a href="inc/comprovantes/html/proposta_assistencia_total/?cert=<?php echo $chave; ?>" class="btn green" target="_blank">PROPOSTA</a> 
                    <?php          
                    if($plano_adicional == 'nao'){
                    ?>
                    <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda&alterar_pagamento=true<?php echo $ativar_plano_cancelado;?>" class="btn btn-link"><i class="fa fa-random"></i> Alterar pagamento</a>   
                    <?php
                    }
                    ?>
                     <?php
                     if($tipo_movimento != 'EX'){
                     ?>
                     <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=editar_cliente" class="btn blue">Atualizar</a>
                    <?php
                    }
                    }
                }else{
            if($tipo_movimento != 'EX'){        
            ?>
            <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=editar_cliente" class="btn blue">Atualizar</a>
            <?php
            }
            if(strtotime($data_termino) >= strtotime($agora) AND $id_cliente_principal == 0){
                if($plano_adicional == 'nao' AND $nivel_usuario == 'A'){
            ?>
                <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda&alterar_pagamento=true<?php echo $ativar_plano_cancelado;?>" class="btn btn-link"><i class="fa fa-random"></i> Alterar pagamento (REATIVAR1)</a>
            
            <?php
            }
            }else{
                if($plano_adicional == 'nao'){
                    if($tipo_movimento != 'EX'){ 
            ?>
                <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=renovar_venda" class="btn blue">Renovar agora</a>
                <?php
                }else{
                ?>
                
                <a href="editar.php?item=cliente&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=finalizar_venda&alterar_pagamento=true<?php echo $ativar_plano_cancelado;?>" class="btn btn-link"><i class="fa fa-random"></i> Alterar pagamento (REATIVAR2)</a>                               <?php
                }

                }
            }
            ?>
            <a href="inc/comprovantes/html/proposta_assistencia_total/?cert=<?php echo $chave; ?>" class="btn green" target="_blank">PROPOSTA</a>   
            <!--<a href="adicionar.php?item=clientes&tipo=produto&id_base=<?php echo $id_base_produto; ?>&slug=europ&id_produto=<?php echo $id_produto_get; ?>&id=<?php echo $id_cliente; ?>&id_grupo_produto=<?php echo $id_produto; ?>&plano_adicional=<?php echo $plano_adicional; ?>&msg_status=reativar_cliente" class="btn blue">Nova Venda</a>-->
            <?php
                }
            }
        ?>
        
        <?php
        }
            
        }elseif($slug  == 'sorteio_ead'){
            
             if($status_cliente =='Ativo')
            {
            ?>
            <a href="http://fixou.com.br/painel_cursos/comprovante.php?id=<?php echo md5($id_venda); ?>&exibe_painel=ok" class="btn green" target="_blank">COMPROVANTE</a>
            <?php
            }
        }
            ?>
       
    </div>
<div id="atualizar_cli_usr" class="modal fade modal-scroll" tabindex="-1" style="display: none;">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title" style="text-align: left;">Atualizar Cliente</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                   <p>Para atualizar o cliente, é necessário enviar um e-mail para: <br />
                   <strong>contato@trailservicos.com.br</strong>
                   </p>
                   <p>Enviar as seguintes informações:<br />
                   <strong>ID do Parceiro: </strong> <?php echo $usr_parceiro_sessao; ?> <br />
                   <strong>ID Cliente: </strong> <?php echo $id_cliente; ?> <br />
                   <strong>ID Grupo Produto: </strong> <?php echo $id_produto; ?>
                   </p>
                   <hr />
                   <p>Encaminhe também no corpo do e-mail, novas alterações do cliente.</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn dark btn-outline">Fechar</button>
           
        </div>
    </div>
</div>
</div>
<div id="comprovante_pago_cliente" class="modal fade modal-scroll" tabindex="-1" style="display: none;">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title" style="text-align: left;">Comprovante</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                  Aguarde...
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn dark btn-outline">Fechar</button>
           
        </div>
    </div>
</div>
</div>
<!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
<div class="modal fade modal-scroll" id="ajax2" role="basic" aria-hidden="true" style="padding-left: 0!important;padding-top: 0!important;">
    <div class="modal-dialog" style="margin: 0;">
        <div class="modal-content">
            <div class="modal-body">
                <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading"/>
                <span> &nbsp;&nbsp;Aguarde... </span>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
