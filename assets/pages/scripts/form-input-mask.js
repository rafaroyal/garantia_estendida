var FormInputMask = function () {
    
    var handleInputMasks = function () {
        
        $("#nome").inputmask({
            casing: "upper"});
         $(".nome").inputmask({
            casing: "upper"});
        $("#cpf").inputmask("999.999.999-99", {
           autoUnmask: true,
           clearMaskOnLostFocus: true
        }); //direct mask  
        $(".cpf").inputmask("999.999.999-99", {
           autoUnmask: true,
           clearMaskOnLostFocus: true
        }); //direct mask  
        $("#cnpj").inputmask("99.999.999/9999-99", {
           autoUnmask: true,
           clearMaskOnLostFocus: true
        }); //direct mask  
        $("#rg").inputmask('99999999999', {
            placeholder: " "
        }); 
        $("#cep").inputmask('99999-999', {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $(".cep").inputmask('99999-999', {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $("#numero").inputmask({
            "mask": "9",
            "repeat": 4,
            "greedy": false
        }); 
        $(".numero").inputmask({
            "mask": "9",
            "repeat": 4,
            "greedy": false
        }); 
        $("#comprovante_maquina").inputmask('999999[9][9][9][9]',  {
            placeholder: " "
        }); 
        $("#comprovante_doc").inputmask('999999[9][9][9][9]',  {
            placeholder: " "
        }); 
        $("#estado").inputmask('AA', {
            placeholder: " "
        }); 
        $(".estado").inputmask('AA', {
            placeholder: " "
        }); 
        $("#tel_res").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $("#telefone").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $(".telefone").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $("#telefone_com").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $("#telefone_alt").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        });
        $("#tel_cel").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $("#celular").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $(".celular").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        });
        $("#tel_com").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $("#comercial").inputmask('99 99999999[9]',  {
            autoUnmask: true,
            clearMaskOnLostFocus: true
        }); 
        $("#cnes").inputmask('9999[9][9][9]',  {
            placeholder: " "
        });
        /*$("#desconto").inputmask('99[.9]',  {
            placeholder: " "
        });*/
        /*$("#desconto").inputmask({
            mask: "99[.9]",
            greedy: false,
            definitions: {
              '*': {
                validator: "[0-9]"
              }
            }
        });*/
        $("#porcentagem_entrada").inputmask('9[9]',  {
            placeholder: " "
        }); 
        $("#cod_barras").inputmask('99-9999999-999999-99999',  {
            placeholder: " "
        }); 
        $("#cod_aut").inputmask('999999[9]',  {
            placeholder: " "
        }); 
        $("#cod_baixa").inputmask('99999[9]',  {
            placeholder: " "
        }); 
        $("#parcelas_faturamento").inputmask('9[9]',  {
            placeholder: " "
        }); 
        $("#codigo_procedimento").inputmask('9999999[9][9][9]',  {
            placeholder: " "
        });
        $(".id_paciente_mask").inputmask('999[9][9][9]',  {
            placeholder: " "
        });
        $("#data_nasc").inputmask("d/m/y", {
            //"placeholder": ""
        }); //change the placeholder
        $(".data_nasc").inputmask("d/m/y", {
            //"placeholder": ""
        }); //change the placeholder
        $(".data_nasc_dependente").inputmask("d/m/y", {
            //"placeholder": ""
        }); //change the placeholder
        $("#dt_nasc_profissional").inputmask("d/m/y", {
            //"placeholder": ""
        }); //change the placeholder
        $("#registro").inputmask('9999[9][9][9][9]',  {
            placeholder: " "
        });
        $(".rqe").inputmask('9999[9][9][9][9][9][9]',  {
            placeholder: " "
        });
        $(".registro_profissional").inputmask('9999[9][9][9][9]',  {
            placeholder: " "
        });
        $("#mask_date2").inputmask("d/m/y", {
            "placeholder": "dd/mm/yyyy"
        }); //multi-char placeholder
        $("#mask_number").inputmask({
            "mask": "9",
            "repeat": 10,
            "greedy": false
        }); // ~ mask "9" or mask "99" or ... mask "9999999999"
        
    }

    return {
        //main function to initiate the module
        init: function () {
            handleInputMasks();
        }
    };

}();

if (App.isAngularJsApp() === false) { 
    jQuery(document).ready(function() {
        FormInputMask.init(); // init metronic core componets
    });
}