<?php

/**
 * @author Lucas Vinícius Leati
 * @copyright 2012
 */

switch ($item)
{
    case "clientes":
        $titulo 	= "Clientes";
		$titulo2 	= "Cliente";
		$id_bd		= "id_cliente";
        break;
    case "cliente":
        $titulo 	= "Cliente";
		$titulo2 	= "Cliente";
		$id_bd		= "id_cliente";
        break;
    case "solicitacoes":
        $titulo 	= "Solicitações";
		$titulo2 	= "Solicitaçao";
		$id_bd		= "id_solicitacao";
        break;
    case "vendas":
        $titulo     = "Vendas";
        $titulo2    = "Venda";
        $id_bd      = "id_venda";
        break;
    case "parceiros":
        $titulo     = "Parceiros";
        $titulo2    = "Parceiro";
        $id_bd      = "id_parceiro";
        break;
    case "grupos_parceiros":
        $titulo     = "Grupos de Parceiros";
        $titulo2    = "Grupo do parceiro";
        $id_bd      = "id_grupo_parceiro";
        break;
    case "grupo_parceiro":
        $titulo     = "Grupo de Parceiro";
        $titulo2    = "";
        $id_bd      = "id_grupo_parceiro";
        break;
    case "servicos":
        $titulo     = "Serviços";
        $titulo2    = "Serviços";
        $id_bd      = "id_servico";
        break;
    case "info_utilizacao":
        $titulo     = "Utilização";
        $titulo2    = "Utilização";
        $id_bd      = "";
        break;
    case "filtro_clientes":
        $titulo     = "Pesquisa";
        $titulo2    = "Pesquisa";
        $id_bd      = "";
        break;    
    case "info_servicos":
        $titulo     = "Coberturas";
        $titulo2    = "Cobertura";
        $id_bd      = "id_cobertura";
        break;
     case "info_servicos_servicos":
        $titulo     = "Serviços";
        $titulo2    = "Serviço";
        $id_bd      = "id_servico";
        break;
     case "info_comissoes":
        $titulo     = "Comissões";
        $titulo2    = "Comissão";
        $id_bd      = "id_comissao";
        break;
     case "info_comissoes_det":
        $titulo     = "Detalhes Comissão";
        $titulo2    = "Comissão";
        $id_bd      = "id_comissao";
        break;
     case "alterar_comissao":
        $titulo     = "Detalhes Comissão";
        $titulo2    = "Comissão";
        $id_bd      = "id_comissao";
        break;
     case "relatorios":
        $titulo     = "Relatórios";
        $titulo2    = "Relatório";
        $id_bd      = "";
        break;
     case "blocos":
        $titulo     = "Paginas";
        $titulo2    = "Pagina";
        $id_bd      = "id_bloco";
        break;
    case "tipos_doc":
        $titulo     = "Tipos de documentos";
        $titulo2    = "Tipo de documento";
        $id_bd      = "id_tipo_doc";
        break;
    case "usuarios":
        $titulo     = "Usuários";
        $titulo2    = "Usuário";
        $id_bd      = "id_usuario";
        break;
	case "comprovante":
        $titulo     = "comprovantes";
        $titulo2    = "comprovante";
        $id_bd      = "id_comprovante";
        break;
    case "faturamentos":
        $titulo     = "faturamentos";
        $titulo2    = "faturamento";
        $id_bd      = "id_faturamento";
        break;
    case "pagamentos":
        $titulo     = "pagamentos";
        $titulo2    = "pagamento";
        $id_bd      = "id_pagamento";
        break;
     case "boletos_clientes":
        $titulo     = "Boletos Clientes";
        $titulo2    = "boleto_cliente";
        $id_bd      = "id_boleto";
        break;
     case "boletos_vencidos_clientes":
        $titulo     = "Boletos Clientes";
        $titulo2    = "boleto_cliente";
        $id_bd      = "id_boleto";
        break;
     case "boletos_avencermes_clientes":
        $titulo     = "Boletos Clientes";
        $titulo2    = "boleto_cliente";
        $id_bd      = "id_boleto";
        break;
     case "boletos_pagosmes_clientes":
        $titulo     = "Boletos Clientes";
        $titulo2    = "boleto_cliente";
        $id_bd      = "id_boleto";
        break;
     case "boletos_mes_clientes":
        $titulo     = "Boletos Clientes";
        $titulo2    = "boleto_cliente";
        $id_bd      = "id_boleto";
        break;
     case "pagamentos_clientes":
        $titulo     = "Pagamentos";
        $titulo2    = "Pagamento";
        $id_bd      = "id_pagamento_cliente";
        break;   
     case "atualizar_pagamentos":
        $titulo     = "Pagamentos";
        $titulo2    = "Pagamento";
        $id_bd      = "id_pagamento_cliente";
        break; 
     case "guias":
        $titulo     = "Guias";
        $titulo2    = "Guia";
        $id_bd      = "";
        break;   
     case "gui_local_atendimento":
        $titulo     = "Local Atendimento";
        $titulo2    = "Local Atendimento";
        $id_bd      = "id_local_atendimento";
        break;
     case "gui_convenios":
        $titulo     = "Convenios";
        $titulo2    = "Convenio";
        $id_bd      = "id_convenio";
        break;
      case "gui_procedimentos":
        $titulo     = "Procedimentos";
        $titulo2    = "Procedimento";
        $id_bd      = "id_procedimento";
        break;
      case "gui_grupo_procedimentos":
        $titulo     = "Grupo de Procedimentos";
        $titulo2    = "Grupo de Procedimento";
        $id_bd      = "id_grupo_procedimento";
        break;
      case "gui_profissionais":
        $titulo     = "Profissionais";
        $titulo2    = "Profissional";
        $id_bd      = "id_profissional";
        break;
      case "gui_pacientes":
        $titulo     = "Pacientes";
        $titulo2    = "Paciente";
        $id_bd      = "id_paciente";
        break;
      case "gui_guias":
        $titulo     = "Guias";
        $titulo2    = "Guia";
        $id_bd      = "id_guia";
        break;
      case "gui_guias_detalhes":
        $titulo     = "Detalhes";
        $titulo2    = "Guia";
        $id_bd      = "id_guia";
        break;
       case "gui_pagamentos_guia":
        $titulo     = "Pagamentos";
        $titulo2    = "Pagamento";
        $id_bd      = "id_pagamento";
        break;
       case "gui_pagamentos_guia_pagos":
        $titulo     = "Pagamentos";
        $titulo2    = "Pagamento";
        $id_bd      = "id_pagamento";
        break;
       case "gui_pagamentos_guia_avencer":
        $titulo     = "Pagamentos";
        $titulo2    = "Pagamento";
        $id_bd      = "id_pagamento";
        break;  
       case "gui_pagamentos_guia_vencidos":
        $titulo     = "Pagamentos";
        $titulo2    = "Pagamento";
        $id_bd      = "id_pagamento";
        break;     
       case "gui_pagamentos_guia_todos":
        $titulo     = "Pagamentos";
        $titulo2    = "Pagamento";
        $id_bd      = "id_pagamento";
        break; 
       case "gui_busca_procedimentos":
        $titulo     = "Busca Procedimentos";
        $titulo2    = "Procedimentos";
        $id_bd      = "id_procedimento";
        break;
       case "gui_relatorios":
        $titulo     = "Relatórios";
        $titulo2    = "Relatorio";
        $id_bd      = "";
        break;
       case "gui_relatorio_local_atendimento":
        $titulo     = "Guias - Relatórios";
        $titulo2    = "Guias - Relatorio";
        $id_bd      = "";
        break;
       case "gui_relatorio_faturamentos":
        $titulo     = "Guias - Relatórios Faturamentos";
        $titulo2    = "Guias - Relatório Faturamento";
        $id_bd      = "";
        break;
       case "fluxo_pagamento":
        $titulo     = "Fluxo de pagamento";
        $titulo2    = "Fluxo de pagamento";
        $id_bd      = "id_pagamento";
        break;
       case "personalizar_pagamentos":
        $titulo     = "Personalizar Pagamentos";
        $titulo2    = "Personalizar Pagamentos";
        $id_bd      = "id_boleto";
        break;
       case "pagamentos_usuario":
        $titulo     = "Pagamentos Usuários";
        $titulo2    = "Pagamentos Usuários";
        $id_bd      = "id_boleto";
        break;
       case "est_estoque":
        $titulo     = "Estoque";
        $titulo2    = "Estoque";
        $id_bd      = "";
        break;
       case "est_movimentacoes":
        $titulo     = "Movimentações";
        $titulo2    = "Movimentações";
        $id_bd      = "id_movimentacao";
        break; 
	default:
		header("Location: inicio.php");
		break;

}

?>