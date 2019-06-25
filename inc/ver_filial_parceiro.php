<script>
var TableDatatablesButtons = function () {

    var initTable1 = function () {
        var table = $('#sample_modal');

        var oTable = table.dataTable({

            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Lista _START_ de _END_ de _TOTAL_ registros",
                "infoEmpty": "Sem registros",
                "infoFiltered": "(filtered1 from _MAX_ total registros)",
                "lengthMenu": "_MENU_ registros",
                "search": "Buscar:",
                "zeroRecords": "Sem registros"
            },
            buttons: [
                { extend: 'print', className: 'btn dark btn-outline' },
                { extend: 'pdf', className: 'btn green btn-outline' },
                { extend: 'excel', className: 'btn yellow btn-outline ' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: true,

            //"ordering": false, disable column ordering 
            "paging": false,

            "order": [
                [0, 'asc']
            ],
            
            
            // set the initial value
            "pageLength": -1,

            "dom": "<'row' <'col-md-12'B>><'row hidden-print'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row hidden-print'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }


    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }
            initTable1();
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesButtons.init();
});
</script>
<style>
#sample_modal_wrapper .dt-buttons{
    top: -9px;
}
#sample_modal_wrapper > .hidden-print{
    display: none;
}
</style>
<?php
require_once('functions.php');
require_once('conexao.php'); 
$banco_painel = $link;
$data1                          = (empty($_GET['data1'])) ? "" : verifica($_GET['data1']);
$data2                          = (empty($_GET['data2'])) ? "" : verifica($_GET['data2']);
$id_parceiro                    = (empty($_GET['id_parceiro'])) ? "" : verifica($_GET['id_parceiro']);
$verifica_sql_sel_filial        = (empty($_GET['verifica_filial'])) ? "" : verifica($_GET['verifica_filial']);
$verifica_sql_versao_produto    = (empty($_GET['verifica_versao_produto'])) ? "" : verifica($_GET['verifica_versao_produto']);
$slug                           = (empty($_GET['slug'])) ? "" : verifica($_GET['slug']);
$todos_clientes_ativos          = (empty($_GET['todos_clientes_ativos'])) ? "" : verifica($_GET['todos_clientes_ativos']);
$sql_sel_filial = '';
if($verifica_sql_sel_filial != 'false'){
    $sql_sel_filial = "AND (id_filial = $verifica_sql_sel_filial OR id_filial_integracao = $verifica_sql_sel_filial)";
}

$sql_versao_produto = '';
if($verifica_sql_versao_produto != 'false'){
   $sql_versao_produto = "AND versao_europ = $verifica_sql_versao_produto";
}

$sql        = "SELECT nome'nome_parceiro' FROM parceiros
            WHERE id_parceiro = $id_parceiro";
$query      = mysql_query($sql, $banco_painel);
$nome_parceiro = '';                
if(mysql_num_rows($query)>0)
{
    $nome_parceiro = mysql_result($query, 0);    
}

// FAZ A CONEXÃO COM BANCO DE DADOS DO PRODUTO
$sql_base   = "SELECT bpro.banco_dados, bpro.banco_user, bpro.banco_senha, bpro.banco_host, bpro.slug, pro.versao_produto FROM bases_produtos bpro
            JOIN produtos pro ON bpro.id_base_produto = pro.id_base_produto
            WHERE bpro.slug = '$slug'";
$query_base = mysql_query($sql_base, $banco_painel) or die(mysql_error()." - 2");

if (mysql_num_rows($query_base)>0)
{
    $banco_dados            = mysql_result($query_base, 0, 'banco_dados');
    $banco_user             = mysql_result($query_base, 0, 'banco_user');
    $banco_senha            = mysql_result($query_base, 0, 'banco_senha');
    $banco_host             = mysql_result($query_base, 0, 'banco_host');
    $slug                   = mysql_result($query_base, 0, 'slug');
    $versao_produto         = mysql_result($query_base, 0, 'versao_produto');
    
    $banco_senha = simple_decrypt($banco_senha, 'senhal7tec');
}
$banco_produto = conexaoDB($banco_host, $banco_user, $banco_senha, $banco_dados); 

$sql_data_emissao = "(data_emissao BETWEEN '$data1' AND '$data2')";
$agora 			= date("Y-m-d");
if($todos_clientes_ativos == 'S'){
    $sql_data_emissao = "data_emissao <= '$agora' AND (data_termino >= '$agora' OR data_termino = '0000-00-00')";
}
            
            $sql_sel_v_filiais = "SELECT id_filial'id_filial_geral', id_filial_integracao'id_filial_integracao_geral', tipo_movimento'tipo_movimento_geral', id_cliente_principal FROM clientes 
            WHERE $sql_data_emissao
            AND id_parceiro = $id_parceiro $sql_sel_filial AND tipo_movimento IN ('IN', 'AL', 'EX') AND (id_filial > 0 OR id_filial_integracao >0) $sql_versao_produto GROUP BY chave ORDER BY (CASE WHEN id_filial != '' THEN id_filial END)  asc,
				 (CASE WHEN id_filial_integracao != '' THEN id_filial_integracao END)  asc";

            $query_sel_v_filiais      = mysql_query($sql_sel_v_filiais, $banco_produto) or die(mysql_error()." - 71");
            echo "
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\"></button>
                        <h4 class=\"modal-title\" style=\"text-align: left;\">Filiais - $nome_parceiro</h4>
                        <div class=\"btn-group pull-right\">
                        <button class=\"btn green  btn-outline dropdown-toggle\" data-toggle=\"dropdown\" style=\"color: #fff;border-color: #fff;background: #fff;\">
                            <i class=\"fa fa-angle-down\"></i>
                        </button>
                        
                    </div>
                    </div>
                    <div class=\"modal-body\">
                        ";             
            if (mysql_num_rows($query_sel_v_filiais)>0)
            { 
                
                $id_filial_atual = '';
                $incr_somar_in = 0;
                $incr_somar_ex = 0;
                //if(($id_filial_geral != '' AND $id_filial_geral != 0) OR ($id_filial_integracao_geral != '' AND $id_filial_integracao_geral != 0)){

                
                            
                            echo "<table id=\"sample_modal\" class=\"table table-condensed table-hover\" cellspacing=\"0\" width=\"100%\">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Ativos</th>
                <th>Cancelados</th>
            </tr>
        </thead>
        <tbody>";

                while($dados_sel_v_filiais = mysql_fetch_array($query_sel_v_filiais))
                {
                    extract($dados_sel_v_filiais);
                    
                    if($id_cliente_principal == 0){

                    if($id_filial_geral != '' AND $id_filial_geral != 0){
                            $id_filial_geral_atual = $id_filial_geral;
                            $sel_id_filial = 'id_filial';
                        }
                                
                        if($id_filial_integracao_geral != '' AND $id_filial_integracao_geral != 0){
                            $id_filial_geral_atual = $id_filial_integracao_geral;
                            $sel_id_filial = 'id_filial_integracao';
                        }
                    
                    if($id_filial_atual == ''){

                        $id_filial_atual = $id_filial_geral_atual;
                        $sql_ver_nome_filial = "SELECT nome FROM filiais
                        WHERE $sel_id_filial = $id_filial_geral_atual";
            
                        $query_ver_nome_filial      = mysql_query($sql_ver_nome_filial, $banco_painel) or die(mysql_error()." - 72");
                                        
                        if (mysql_num_rows($query_ver_nome_filial)>0)
                        {
                            $nome_filial_v = mysql_result($query_ver_nome_filial, 0, 'nome');
                        }

                        if($tipo_movimento_geral == 'IN' OR $tipo_movimento_geral == 'AL'){
                            $incr_somar_in++;
                        }else{
                            $incr_somar_ex++;
                        }
                    
                    }elseif($id_filial_atual == $id_filial_geral_atual){

                        if($tipo_movimento_geral == 'IN' OR $tipo_movimento_geral == 'AL'){
                            $incr_somar_in++;
                        }else{
                            $incr_somar_ex++;
                        }
                        
                        
                    }else{
                        
                        echo "<tr>
                                <td>$nome_filial_v</td>
                                <td><span class=\"label label-sm label-success\">$incr_somar_in</span></td>
                                <td><span class=\"label label-sm label-danger\">$incr_somar_ex</span></td>
                              </tr>";
                            
                        $incr_somar_in = 0;
                        $incr_somar_ex = 0;
                        
                        $id_filial_atual = $id_filial_geral_atual;
                        $sql_ver_nome_filial = "SELECT nome FROM filiais
                        WHERE $sel_id_filial = $id_filial_geral_atual";
            
                        $query_ver_nome_filial      = mysql_query($sql_ver_nome_filial, $banco_painel) or die(mysql_error()." - 73");
                                        
                        if (mysql_num_rows($query_ver_nome_filial)>0)
                        {
                            $nome_filial_v = mysql_result($query_ver_nome_filial, 0, 'nome');
                        }
                        
                        if($tipo_movimento_geral == 'IN' OR $tipo_movimento_geral == 'AL'){
                            $incr_somar_in++;
                        }else{
                            $incr_somar_ex++;
                        }
                    }
                    
                    }
                    
                    
                }
               
                echo "<tr>
                        <td>$nome_filial_v</td>
                        <td><span class=\"label label-sm label-success\">$incr_somar_in</span></td>
                        <td><span class=\"label label-sm label-danger\">$incr_somar_ex</span></td>
                      </tr>";
                
                echo " </tbody>
    </table>";
                    
            //}
            }else{
                
                echo "Sem registros";
                
            }
            echo "</div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" data-dismiss=\"modal\" class=\"btn dark btn-outline\">Fechar</button> 
                    </div>
                ";

?>