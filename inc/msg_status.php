<?php
if (isset($_GET['msg_status']))
{
    $msg_status = $_GET['msg_status'];

    switch ($msg_status) 
    {
        //geral
        case "permissao":
            echo status_erro('Página não acessada. Erro: permissão.');
            break;
        case "id":
            echo status_erro('ID inválido para consulta');
            break; 
		case "login_erro":
			echo status_erro('Login e/ou senha não conferem');
            break;
		case "nome_existe":
			echo status_erro('Já existe um registro com o mesmo nome.');
            break;
        case "email_existe":
			echo status_erro('Já existe um registro com o mesmo email.');
            break;
        case "login_existe":
            echo status_erro('Já existe um usuário com o mesmo nome ou login.');
            break;
        case "parceiro_existe":
            echo status_erro('Já existe um parceiro com o esse registro.');
            break;
         case "cliente_existe":
            echo status_erro('Já existe um cliente com o esse registro.');
            break;
         case "adicionar_erro_passo3":
            echo status_erro('Já existe um registro referente ao PASSO 3.');
            break;
         case "placa_existe":
            echo status_erro('Já existe um "Bem Pessoal" com esse registro.');
            break;
		case "adicionar_ok":
			echo status_ok('Registro adicionado com sucesso.');
            break;
		case "adicionar_erro":
			echo status_erro('Erro ao adicionar registro.');
            break;
        case "adicionar_erro_idade":
			echo status_erro('Erro ao adicionar registro, idade do cliente é superior à 70 anos.');
            break;
        case "adicionar_erro_existe":
			echo status_erro('Erro ao adicionar registro, já existe um registro ativo no sistema.');
            break;
		case "editar_ok":
			echo status_ok('Registro editado com sucesso.');
            break;
        case "reativar_cliente":
			echo status_ok('É necessário registrar nova venda.');
            break;
         case "renovar_venda":
			echo status_ok('Renovar venda à vencer, se necessário atualize os campos abaixo.');
            break;
        case "editar_cliente":
			echo status_ok('Atualize os campos abaixo.');
            break;
        case "finalizar_cadastro":
			echo status_ok('Venda Não finalizada, preencher todos os campos abaixo.');
            break;
        case "finalizar_venda":
			echo status_ok('Venda Não finalizada, finalize a venda aqui!');
            break;
		case "editar_erro":
			echo status_erro('Erro ao editar registro.');
            break;
        case "excluir_ok":
			echo status_ok('Registro excluído com sucesso.');
            break;
		case "excluir_erro":
			echo status_erro('Erro ao excluir registro.');
            break;
        case "remessa_erro":   
            echo status_erro('Erro ao gerar arquivo remessa.');
            break;
		case "remessa_ok":   
            echo status_ok('Arquivo remessa criado com sucesso.');
            break;
		case "data_arquivo":
			echo status_erro('ATENÇÃO: já foi criado um arquivo de remessa hoje. Caso seja criado novamente, ele será substituído.');
            break;
		case "arquivo_erro":
			echo status_erro('Erro ao fazer download do arquivo. (Ou arquivo inexistente.)');
            break;
        case "adicionar_verificar_usuario":   
            echo status_erro('Usuário adicionado com sucesso, mas está em processo de liberação.');
            break;
        case "saldo":
            echo status_erro('Saldo insuficiente de cursos/VIP.');
            break;
    }

}
?>