<script src="assets/pages/scripts/moeda.js" type="text/javascript"></script>
<?php
require_once('conexao.php'); 
$id_produto       = $_GET['id_produto'];
$nome             = $_GET['nome'];

?>

<script>
function remove_produto_parceiro_novo(id, nome){
{   
    $('#box_add_produto_' + id).remove();
    /*$('#selecionar_produtos').append($('<option>', {
    value: id,
    text: nome
    }));*/
    
}};
</script>


<div class="col-md-3" id="box_add_produto_<?php echo $id_produto; ?>">
<input type="hidden" name="add_produto[]" id="add_produto" value="<?php echo $id_produto; ?>"/>
<input type="hidden" name="add_parceiro_servico[]" id="add_parceiro_servico" value="0"/>
<div class="portlet light">
    <div class="portlet-title">
        <div class="col-md-9">
            <div class="caption font-green">
                <h5 ><?php echo $nome; ?></h5>
            </div>
        </div>
        <div class="col-md-3">
        <div class="actions">
            <div class="btn-group">
             <button class="btn btn-sm red btn-outline filter-cancel" type="button" onclick="return remove_produto_parceiro_novo(<?php echo $id_produto; ?>, '<?php echo $nome; ?>');"><i class="fa fa-times"></i></button>  
            </div>
        </div>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="preco_custo[]" class="form-control" id="preco_custo" value="" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)"/>
                                <label for="preco_custo">Preço de custo</label>
                                <span class="help-block">Somente números...</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="preco_venda[]" class="form-control" id="preco_venda" value="" onkeydown="FormataMoeda(this,10,event)" onkeypress="return maskKeyPress(event)"/>
                                <label for="preco_venda">Preço de Venda</label>
                                <span class="help-block">Somente números...</span>
                            </div>
                            
                            
                                    
                                    <?php
                                        
                                        $sql_grupo = "SELECT gp.id_grupo_produto, gp.nome'nome_grupo_produto' FROM grupos_produtos gp
JOIN produtos_grupos pg ON gp.id_grupo_produto = pg.id_grupo_produto
WHERE pg.id_produto = $id_produto AND gp.del = 'N'";
                                        $query_grupo      = mysql_query($sql_grupo);
                                                 
                                        if (mysql_num_rows($query_grupo)>0)
                                        {
                                            echo "<div class=\"form-group form-md-line-input form-md-floating-label\">
                                            <select class=\"form-control edited\" id=\"grupo_produto\" name=\"grupo_produto[]\">
                                                <option value=\"\"></option>";
                                            
                                            while ($dados_grupo = mysql_fetch_array($query_grupo))
                                            {
                                                extract($dados_grupo);  
                                                
                                                echo "<option value=\"$id_grupo_produto\">$nome_grupo_produto</option>";
                                            }
                                            
                                            echo "</select>
                                            <label for=\"grupo\">Nome do grupo</label>
                                          </div>";
                                            
                                        }
                                        else
                                        {
                                        ?>
                                        <input type="hidden" name="grupo_produto[]" id="grupo_produto" value="0"/>
                                        <?php
                                        }
                                        
                                    ?> 
                                
                       
                </div>
            </div>
        </div>  
    </div>
</div>
</div>

