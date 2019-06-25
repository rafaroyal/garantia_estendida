var FormValidationMd = function() {

    var validar_parceiro_pf = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#form_adicionar');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            //ignore: true, // validate all fields including form hidden input
            /*messages: {
                payment: {
                    maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                    minlength: jQuery.validator.format("At least {0} items must be selected")
                },
                'checkboxes1[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                },
                'checkboxes2[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                }
            },*/
            rules: {
                tipopessoa: {
                    required: true
                },
                nome: {
                    minlength: 4,
                    required: true
                },
                cpf: {
                    minlength: 11,
                    required: true
                },
                cnpj: {
                    required: true
                },
                razao: {
                    required: true
                },
                cep: {
                    minlength: 8,
                    required: true
                },
                endereco: {
                    required: true
                },
                bairro: {
                    required: true
                },
                /*cidade: {
                    required: true
                },
                estado: {
                    minlength: 2,
                    required: true
                },*/
                /*email: {
                    required: true,
                    email: true
                },*/
                tel_com: {
                    required: true
                },
                'metodo_pagamento[]': {
                    required: true
                },
                'tipo_pagamento[]': {
                    required: true
                },
                desconto: {
                    required: true
                },
                valor_entrada_automatica: {
                    required: true
                },
                permitir_entrada: {
                    required: true
                },
                entrada: {
                    required: true
                },
                grupo: {
                    required: true
                },
                login: {
                    minlength: 4,
                    maxlength: 20,
                    required: true
                },
                status: {
                    required: true
                },
                select_parceiro_user: {
                    required: true
                },
                nivel: {
                    required: true
                },
                conveniado: {
                    required: true
                },
                tipo_local_atendimento: {
                    required: true
                },
                estado_atendimento: {
                    required: true
                },
                'lista_cidades_local[]': {
                    required: true
                },
                codigo_procedimento: {
                    required: true
                },
                grupo_procedimento: {
                    required: true
                },
                convenio: {
                    required: true
                },
                valor_final: {
                    required: true
                },
                valor_custo: {
                    required: true
                },
                profissao: {
                    required: true
                },
                select_especialidade: {
                    required: true
                },
                'select_especialidade[]': {
                    required: true
                },
                rqe: {
                    required: true
                },
                /*'rqe[]': {
                    required: true
                },*/
                conselho: {
                    required: true
                },
                registro: {
                    required: true
                },
                conveniado: {
                    required: true
                },
                tratamento_profissional: {
                    required: true
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                $('.div_aguarde').hide();
            },

            errorPlacement: function(error, element) {
                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
                
                
            },

            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error');
                    
                
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                    
                
            },

            

        });
    }
    
    var validar_cliente = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#form_adicionar_cliente');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);
        
        form1.validate({
            
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            /*messages: {
                payment: {
                    maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                    minlength: jQuery.validator.format("At least {0} items must be selected")
                },
                'checkboxes1[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                },
                'checkboxes2[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                }
            },*/
            rules: {
                'select_grupo_produto[]': {
                    required: true
                },
                'select_produto[]': {
                    required: true
                },
                forma_pagamento: {
                    required: true
                },
                metodo_pagamento: {
                    required: true
                },
                prazo: {
                    required: true
                },
                comprovante_maquina: {
                    required: true
                },
                comprovante_doc: {
                    required: true
                },
                parcela_parcelas_boleto: {
                    required: true
                },
                emissao_boleto: {
                    required: true
                },
                'nome[]': {
                    minlength: 4,
                    required: true
                },
                'cpf[]': {
                    minlength: 11,
                    required: true
                },
                'data_nasc[]': {
                    required: true
                },
                'sexo[]': {
                    required: true
                },
                'estado_civil[]': {
                    required: true
                },
                'telefone[]': {
                    minlength: 10,
                    required: true
                },
                'celular[]': {
                    minlength: 10,
                    required: true
                },
                /*'email[]': {
                    minlength: 5,
                    required: true
                },*/
                'cep[]': {
                    minlength: 8,
                    required: true
                },
                'endereco[]': {
                    minlength: 5,
                    required: true
                },
                'numero[]': {
                    minlength: 1,
                    required: true
                },
                'bairro[]': {
                    minlength: 2,
                    required: true
                },
                'cidade[]': {
                    minlength: 4,
                    required: true
                },
                'estado[]': {
                    minlength: 2,
                    required: true
                },
                'select_user_pedido': {
                    required: true
                },
                nome: {
                    minlength: 4,
                    required: true
                },
                cpf: {
                    minlength: 11,
                    required: true
                },
                data_nasc: {
                    required: true
                },
                sexo: {
                    required: true
                },
                estado_civil: {
                    required: true
                },
                telefone: {
                    minlength: 10,
                    required: true
                },
                celular: {
                    minlength: 10,
                    required: true
                },
                /*email: {
                    minlength: 5,
                    required: true
                },*/
                cep: {
                    minlength: 8,
                    required: true
                },
                endereco: {
                    minlength: 5,
                    required: true
                },
                numero: {
                    minlength: 1,
                    required: true
                },
                bairro: {
                    minlength: 2,
                    required: true
                },
                cidade: {
                    minlength: 4,
                    required: true
                },
                estado: {
                    minlength: 2,
                    required: true
                },
                convenio_paciente: {
                    required: true
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                //$("#bt_add_submit").attr('disabled', 'false');
                $('.div_aguarde').hide();
            },
            
            errorPlacement: function(error, element) {
                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
               
            },

            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                
            },

            

        });
    }
    
    
    
        var validar_cliente_editar = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#form_editar_cliente');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);
        
        form1.validate({
            
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            /*messages: {
                payment: {
                    maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                    minlength: jQuery.validator.format("At least {0} items must be selected")
                },
                'checkboxes1[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                },
                'checkboxes2[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                }
            },*/
            rules: {
                'select_grupo_produto[]': {
                    required: true
                },
                'select_produto[]': {
                    required: true
                },
                forma_pagamento: {
                    required: true
                },
                metodo_pagamento: {
                    required: true
                },
                prazo: {
                    required: true
                },
                comprovante_maquina: {
                    required: true
                },
                comprovante_doc: {
                    required: true
                },
                parcela_parcelas_boleto: {
                    required: true
                },
                emissao_boleto: {
                    required: true
                },
                nome: {
                    minlength: 4,
                    required: true
                },
                cpf: {
                    minlength: 11,
                    required: true
                },
                data_nasc: {
                    required: true
                },
                sexo: {
                    required: true
                },
                estado_civil: {
                    required: true
                },
                telefone: {
                    minlength: 10,
                    required: true
                },
                celular: {
                    minlength: 10,
                    required: true
                },
                /*email: {
                    minlength: 5,
                    required: true
                },*/
                cep: {
                    minlength: 8,
                    required: true
                },
                endereco: {
                    minlength: 5,
                    required: true
                },
                numero: {
                    minlength: 1,
                    required: true
                },
                bairro: {
                    minlength: 2,
                    required: true
                },
                cidade: {
                    minlength: 4,
                    required: true
                },
                estado: {
                    minlength: 2,
                    required: true
                },
                convenio_paciente: {
                    required: true
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                //$("#bt_add_submit").attr('disabled', 'false');
                $('.div_aguarde').hide();
            },
            
            errorPlacement: function(error, element) {
                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
               
            },

            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                
            },

            

        });
    }
    
    
    
    var validar_guia = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#form_adicionar_guia');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);
        
        form1.validate({
            
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            /*messages: {
                payment: {
                    maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                    minlength: jQuery.validator.format("At least {0} items must be selected")
                },
                'checkboxes1[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                },
                'checkboxes2[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                }
            },*/
            rules: {
                id_paciente: {
                    minlength: 2,
                    required: true
                },
                gui_nome_paciente: {
                    minlength: 3,
                    required: true
                },
                data_nascimento: {
                    required: true
                },
                select_local_guia: {
                    required: true
                },
                /*registro_profissional: {
                    minlength: 2,
                    required: true
                },
                nome_profissional: {
                    minlength: 3,
                    required: true
                },*/
                tipo_convenio_procedimento_paciente: {
                    minlength: 3,
                    required: true
                },
                'add_procedimento[]': {
                    required: true
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                //App.scrollTo(error1, -200);
                //$("#bt_add_submit").attr('disabled', 'false');
                $('.div_aguarde').hide();
            },
            
            errorPlacement: function(error, element) {
                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
               
            },

            highlight: function(element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
                
            },

            

        });
    }

    return {
        //main function to initiate the module
        init: function() {
            validar_parceiro_pf();
            validar_cliente();
            validar_cliente_editar();
            validar_guia();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});