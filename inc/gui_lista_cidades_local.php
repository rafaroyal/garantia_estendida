<?php

/**
 * @author [$Rafael]
 * @copyright [$2014]
 */

require_once('../sessao.php');
require_once('functions.php');
require_once('conexao.php'); 

?> 
 <script type='text/javascript' >
    /*$.expr[':'].icontains = function(obj, index, meta, stack){
    return (jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
    };*/
    $(document).ready(function(){
        
        function retira_acentos(palavra) { 
        com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ'; 
        sem_acento = 'aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC'; 
        nova=''; 
        for(i=0;i<palavra.length;i++) { 
            if (com_acento.search(palavra.substr(i,1))>=0) { 
                nova+=sem_acento.substr(com_acento.search(palavra.substr(i,1)),1); 
            } 
            else { 
                nova+=palavra.substr(i,1); 
            } 
        } 
        return nova; 
        }
        
        
        $('#buscar_nome_cidade').keyup(function(){
            buscar = $(this).val();
                    var buscar_sem_acento = retira_acentos(buscar);
                     $(this).val(buscar_sem_acento);
                      //novo_buscar = $(this).val();
                     buscar_hash = $(this).val().toLowerCase();
                     buscar_hash_tratado = buscar_hash.replace(" ", "_");
                     $('#tabela_lista_cidades .md-checkbox').removeClass('resaltar');
                     if(jQuery.trim(buscar) != ''){
                       $("#tabela_lista_cidades #" + buscar_hash_tratado + "").addClass('resaltar');
                       $("#bt_buscar_realce_cidades").attr("href", "#" + buscar_hash_tratado)
                       
                     }
            });
    });
    
    
    //set initial state.
    //$('#textbox1').val($(this).is(':checked'));

    $('input[name="lista_cidades_local[]"]').click(function() {
        var pegar_nome_selecao          = $(this).attr("data");
        var pegar_nome_selecao_completo = $(this).attr("nome");
        if ($(this).is(':checked')) {
            $("#lista_cidades_selecionada").append("<span class='sel_cid_sel sel_" + pegar_nome_selecao + "'>" + pegar_nome_selecao_completo + "</span>");
        }else{
            $("#lista_cidades_selecionada").find(".sel_" + pegar_nome_selecao).remove();
        }
        
        $(window).trigger("hash_cidades");
    });
    
   //function to change hash
function change_hash(aux){
 location.hash = '#pages='+aux;
 document.title = 'Work '+aux;
}

 </script> 
<style>
.resaltar{
    background: #FFEB3B;
}

.sel_cid_sel{
    margin: 5px 10px;
    background: #eee;
    padding: 0px 10px;
    float: left;
    position: relative;
}

</style>
<div class="row">
    <div class="col-md-4">
         <div class="form-group form-md-line-input has-info">
            <div class="input-group input-group-lg">
                <div class="input-group-control">
                    <input type="text" name="buscar_nome_cidade" class="form-control" id="buscar_nome_cidade" value="" style="text-transform: uppercase;" maxlength="40"/>
                    <label for="buscar_nome_cidade">Buscar e realçar nomes de cidades</label>
                    <span class="help-block">Digite o nome completo da cidade desejada...</span>
                </div>
                <span class="input-group-btn btn-right">
                    <a href="#" class="btn green-haze" id="bt_buscar_realce_cidades">Buscar</a>
                </span>
            </div>
         </div>
         &nbsp;<br />
         <h5>Lista de Cidades Selecionadas</h5> 
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="lista_cidades_selecionada" style="line-height: 3em;">
    </div>
</div>
<div class="form-group form-md-checkboxes">
<label>Cidades</label>
<div class="md-checkbox-list" id="tabela_lista_cidades">
    <?php
        $estado   = (empty($_POST['estado'])) ? "" : verifica($_POST['estado']);  
        
        $sql_user_pedido        = "SELECT loc_nu_sequencial, loc_nosub, ufe_sg FROM log_localidade
                                WHERE ufe_sg = '$estado'";
        $query_user_pedido      = mysql_query($sql_user_pedido);
        
        $contar_linhas = mysql_num_rows($query_user_pedido);
        if($contar_linhas>0)
        {
            $divisao_colunas = $contar_linhas / 4;
            $resultado_divisao = ceil($divisao_colunas);
            $i = 0;
            while($dados_user_pedido = mysql_fetch_array($query_user_pedido))
            {
                extract($dados_user_pedido);
                
                if($i > 0){
                    //if($resultado_divisao < $i){
                        $i++; 
                    //
                }
                
               
                
                if($i == 0){
                    echo "<div class=\"col-md-3\">";
                    $i++; 
                }
                $nome_cidade_tratado = remove_acento($loc_nosub);
                $nome_cidade_tratado = str_replace(" ", "_", $nome_cidade_tratado);
                echo " <div class=\"md-checkbox\" id=\"".strtolower($nome_cidade_tratado)."\">
                <input type=\"checkbox\" name=\"lista_cidades_local[]\" value=\"$loc_nu_sequencial\" id=\"$loc_nu_sequencial\" data=\"".strtolower($nome_cidade_tratado)."\" nome=\"$loc_nosub\" class=\"md-check\"/>
                <label for=\"$loc_nu_sequencial\">
                    <span></span>
                    <span class=\"check\"></span>
                    <span class=\"box\"></span> $loc_nosub </label>
                </div>";
                
                
                
                if($i > $resultado_divisao){
                    $i = 0;
                     echo "</div>";
                }/*else{
                    $i++;
                }*/
                
               /* if($resultado_divisao < $i){
                   
                }*/
                
            }
            
            echo "</select></div>";
        }
        
    ?>
    
    
    
       
    </div>
</div>  
