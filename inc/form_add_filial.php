<?php
require_once('conexao.php');

$cnpj_filial         = $_GET['cnpj_filial'];
$nome_filial         = $_GET['nome_filial'];
$cidade_filial       = $_GET['cidade_filial'];
$estado_filial       = $_GET['estado_filial'];
$fone_filial         = $_GET['fone_filial'];
$id_box              = $_GET['id_box'];
$id_parceiro         = $_GET['id_parceiro'];

$id_box = $id_box + 1;


$sql3    = "INSERT INTO filiais (id_parceiro, tipo, nome, cnpj, cidade, estado, tel_com)
        VALUES ('$id_parceiro', 'PJ', '$nome_filial', '$cnpj_filial', '$cidade_filial', '$estado_filial', '$fone_filial')";

$query3  = mysql_query($sql3) or die(mysql_error());

$id_filial = mysql_insert_id();

?>



            
<div class="form-body" id="div_add_filial_<?php echo $id_box; ?>">
    <div class="row">
        <div class="col-md-2">
             <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="hidden" class="form-control" id="cnpj_filial_<?php echo $id_box; ?>" name="cnpj_filial[]" value="<?php echo $cnpj_filial; ?>"/>
                        <label for="cnpj_filial"><?php echo $cnpj_filial; ?></label>
                    </div>
        </div>
        <div class="col-md-3">
             <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="hidden" class="form-control" id="nome_filial_<?php echo $id_box; ?>" name="nome_filial[]" value="<?php echo $nome_filial; ?>"/>
                        <label for="nome_filial"><?php echo $nome_filial; ?></label>
                    </div>
        </div>
        <div class="col-md-3">
             <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="hidden" class="form-control" id="cidade_filial_<?php echo $id_box; ?>" name="cidade_filial[]" value="<?php echo $cidade_filial; ?>"/>
                        <label for="cidade_filial"><?php echo $cidade_filial; ?></label>
             </div>
        </div>
        <div class="col-md-1">
             <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="hidden" class="form-control" id="estado_filial_<?php echo $id_box; ?>" name="estado_filial[]" value="<?php echo $estado_filial; ?>" maxlength="2" style="text-transform: uppercase;"/>
                        <label for="estado_filial"><?php echo $estado_filial; ?></label>
             </div>
        </div>
        <div class="col-md-2">
             <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="hidden" class="form-control" id="fone_filial_<?php echo $id_box; ?>" name="fone_filial[]" value="<?php echo $fone_filial; ?>" style="text-transform: uppercase;"/>
                        <label for="fone_filial"><?php echo $fone_filial; ?></label>
             </div>
        </div>
        <div class="col-md-1">
            <span class="input-group-btn btn-right">
                    <a data-toggle="modal" href="#excluir_filial_<?php echo $id_filial; ?>" class="btn btn-sm red btn-outline sbold">
                    <i class="fa fa-times"></i></a>
                        
            </span>
        </div>
    </div>
    <div class="modal fade modal-scroll" id="excluir_filial_<?php echo $id_filial; ?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Excluir Filial</h4>
            </div>
            <div class="modal-body"> Tem certeza que deseja excluir a filial? A alteração não poderá ser revertida! <br />
            Todas as informações vínculadas serão perdidas!<br />
            </div>
           <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                <button type="button" id="bt_cancelar_cliente" onclick="return remove_filial_parceiro('<?php echo $id_box; ?>','<?php echo $id_filial; ?>','<?php echo $id_parceiro; ?>');" data-dismiss="modal" class="btn green" >Sim, confirmar!</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>                                           
</div>