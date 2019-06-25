<?php
require_once('sessao.php');
require_once('inc/functions.php');
require_once('inc/conexao.php'); 
$banco_painel = $link;
$id_ordem_pedido = (empty($_GET['id_ordem_pedido'])) ? "" : verifica($_GET['id_ordem_pedido']);  
//$id_ordem_pedido = '2466';  
$id_boleto_get   = (empty($_GET['id_boleto'])) ? "" : verifica($_GET['id_boleto']); 
$id_usuario_s    = base64_decode($_COOKIE["usr_id"]);
$id_parceiro_s   = base64_decode($_COOKIE["usr_parceiro"]);
$pasta           = base64_decode($_COOKIE["pasta"]);

$sql    = "SELECT id_parceiro, tipo_boleto FROM boletos_clientes
        WHERE id_ordem_pedido = $id_ordem_pedido";
//echo $sql;
$query      = mysql_query($sql, $banco_painel);
$tipo_boleto='';
$id_parceiro_db='';          
if (mysql_num_rows($query)>0)
{
    $id_parceiro_db = mysql_result($query, 0,0);
    $tipo_boleto    = mysql_result($query, 0,1);
    
    if($tipo_boleto == 'LOJA'){
        //ajuste para link;
        $tipo_boleto = strtolower($tipo_boleto);
        $tipo_boleto = '_'.$tipo_boleto;
    }else{
        $tipo_boleto=''; 
    }

}

$sql        = "SELECT tipo_cobranca, banco, url_boleto FROM cobranca_parceiros
            WHERE id_parceiro = $id_parceiro_db";
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
<html lang="pt_br">

<head>
        <meta charset="utf-8" />
        <title>Boletos</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/pages/css/invoice.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        
        <link rel="shortcut icon" href="favicon.ico" /> 
         <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script>

jQuery(document).ready(function() {    
   

});

function confirma_controle_entregas(){
{
    var tipo                = $("#tipo").val();
    var tipo_boleto         = $("#tipo_boleto").val();
    var tipo_entrega        = $('input[name=tipo_impressao]:checked').val();
    var id_referencia       = $("#id_ordem_pedido").val();
    var obs                 = $("#campo_add_comentario").val();

    var cobr_tipo_cobranca  = $("#cobr_tipo_cobranca").val();
    var cobr_banco          = $("#cobr_banco").val();
    var cobr_url_boleto     = $("#cobr_url_boleto").val();
    
    $("#bt_enviar_dados").attr("disabled", true);
    $("input[name=tipo_impressao]").attr("disabled", true);
    $("#campo_add_comentario").attr("disabled", true);

        $.ajax({ 
         type: "POST",
         url:  "editar_db.php",
         data: {
            item: 'boletos_cliente_confirmacao',
            tipo: tipo,
            tipo_entrega: tipo_entrega,
            id_referencia: id_referencia,
            obs: obs
            },
         success: function(dados){
            data = dados.split('%-%');
            
            if(data[0] != 1)
            {
                var id_referencia         = data[1];
                var id_controle_entregas  = data[0];
                
                if(cobr_url_boleto.length === 0){
                    var nome_arquivo_url = 'ver_boletos_cliente_'+cobr_banco+'.php';
                }else{
                    var nome_arquivo_url = cobr_url_boleto;
                }
                
                var url = 'boletos/'+cobr_tipo_cobranca+'/'+cobr_banco+'/'+nome_arquivo_url+'?id_ordem_pedido='+id_referencia+'&id_controle_entregas='+id_controle_entregas;
                $('#div_bt_controle').html('<a href="'+url+'" class="btn green"><i class="fa fa-print"></i> Imprimir!</a>');
               
            }else{
                $("#bt_enviar_dados").removeAttr("disabled");
                
            }
         } 
        });
    
    
        
    
//}

}};

</script>
<style>
.row_boleto div{
    float: left;
    position: relative; 
}
.linha{
    margin-bottom: 10px;
}

</style>
</head>
<body class="page-container-bg-solid page-header-menu-fixed">



<div class="modal-header">
    <a href="javascript:history.back()" type="button" class="btn default" id="fechar_janela" >Voltar</a>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-2">
            <input type="hidden" class="form-control form-filter input-sm" name="id_ordem_pedido" id="id_ordem_pedido" value="<?php echo $id_ordem_pedido; ?>"/>
            <input type="hidden" class="form-control form-filter input-sm" name="tipo" id="tipo" value="boletos"/>
            <input type="hidden" class="form-control form-filter input-sm" name="tipo_boleto" id="tipo_boleto" value="<?php echo $tipo_boleto;?>"/>

            <input type="hidden" class="form-control form-filter input-sm" name="cobr_tipo_cobranca" id="cobr_tipo_cobranca" value="<?php echo $cobr_tipo_cobranca;?>"/>
            <input type="hidden" class="form-control form-filter input-sm" name="cobr_banco" id="cobr_banco" value="<?php echo $cobr_banco;?>"/>
            <input type="hidden" class="form-control form-filter input-sm" name="cobr_url_boleto" id="cobr_url_boleto" value="<?php echo $cobr_url_boleto;?>"/>
        </div>
        <div class="col-md-4">
        <h3>Selecione uma opção abaixo para impressão:</h3>
            <div class="form-group form-md-radios">
                <div class="md-radio-list">
                    <div class="md-radio">
                        <input type="radio" id="checkbox1_1" name="tipo_impressao" class="md-radiobtn" value="apenas_imprimir" checked="" />
                        <label for="checkbox1_1">
                            <span class="inc"></span>
                            <span class="check"></span>
                            <span class="box"></span>1. Apenas imprimir.</label>
                    </div>
                    <div class="md-radio">
                        <input type="radio" id="checkbox1_2" name="tipo_impressao" class="md-radiobtn" value="entrega_em_maos" />
                        <label for="checkbox1_2">
                            <span class="inc"></span>
                            <span class="check"></span>
                            <span class="box"></span>2. Imprimir e/ou confirmar entrega em mãos.</label>
                    </div>
                    <div class="md-radio">
                        <input type="radio" id="checkbox1_3" name="tipo_impressao" class="md-radiobtn" value="entrega_via_correios" />
                        <label for="checkbox1_3">
                            <span class="inc"></span>
                            <span class="check"></span>
                            <span class="box"></span>3. Imprimir e/ou confirmar entrega via correios.</label>
                    </div>
                    
                </div>
            </div>
            <label>Observações:</label>
            <textarea class="col-md-12" rows="4" cols="4" name="texto_comentario" style="resize: none;" id="campo_add_comentario"> </textarea>
                                                   
        </div>
        <div class="col-md-4">
        <strong>Histórico de impressão:</strong>
        
        <?php
        $sql        = "SELECT * FROM controle_entregas
                    WHERE tipo = 'boletos' AND id_referencia = $id_ordem_pedido
                    ORDER BY id_entrega DESC";
        $query      = mysql_query($sql, $banco_painel);
                    
        if (mysql_num_rows($query)>0)
        {
            while($dados = mysql_fetch_array($query))
            {
                extract($dados); 
                
                $sql_u        = "SELECT nome FROM usuarios
                    WHERE id_usuario = $id_usuario";
                $query_u      = mysql_query($sql_u, $banco_painel);
                $nome_usuario = '';            
                if (mysql_num_rows($query_u)>0)
                {
                    $nome_usuario = mysql_result($query_u, 0,0);
                }
            
            echo "<div class=\"note note-info\">
                <strong>Tipo de entrega:</strong> $id_entrega - $tipo_entrega <br/>
                <strong>Data do registro:</strong> ".converte_data($data_registro)."<br/>
                <strong>Usuário:</strong> $id_usuario - $nome_usuario <br/>
                <strong>Foi Impresso:</strong> $imprimir <br/>
                <strong>Observação:</strong> $obs <br/>
                
                
            </div>";
            
                
            }
        }else{
            echo "<p>Sem informação.</p>";
        }
        ?>
        
        
        </div><span></span>
        <div class="col-md-2">
        &nbsp;
        </div>
    </div>     
        
</div>
<div class="modal-footer">
<div class="col-md-2">
        &nbsp;
        </div>
        <div class="col-md-4" id="div_bt_controle">
        <button type="button" id="bt_enviar_dados" onclick="return confirma_controle_entregas();"  class="btn gray" >Sim, confirmar!</button>
        </div>
        
                            
                       </div>

        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
       
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
    
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>
