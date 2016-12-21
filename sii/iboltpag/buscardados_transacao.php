<?php
	
	@include("../odbc.php");
	@include("../Banco.php");
	session_start();
	$host = $_SESSION["dados_empresa"]["host_banco_empresa"];
	$banco = $_SESSION["dados_empresa"]["nome_banco_empresa"];
	$user = $_SESSION["dados_empresa"]["user_banco_empresa"];
	$senha = $_SESSION["dados_empresa"]["senha_banco_empresa"];
	$bancoCliente = new BancoODBC();
	$bancoMysql = new BancoDados();
	try {
		
		$bancoMysql->buscarListaTransacoesPendentes();
		
		
		
		$_SESSION["qtdepagina"] = (int)(count($_SESSION["listaPedidos"])/5);  
		if ((count($_SESSION["listaPedidos"])%5) !== 0) $_SESSION["qtdepagina"] += 1;
 		$novalista = array();
 		
 		
 		
 		
 		foreach($_SESSION["listaPedidos"] as $pedido){
 			
 			
 			
 			$novalistatransacao = array();
 			if (strripos($pedido["TotalPedido"], ".") === false) $pedido["TotalPedido"] = $pedido["TotalPedido"] . ".00";
 			$transacao["StatusTransPag"] = ((array_key_exists("StatusPedido", $pedido)) ? $pedido["StatusPedido"] : NULL);
 				
 			$transacao["TidTransacao"] = (array_key_exists("tid_transacao_cielo", $pedido) ? $pedido["tid_transacao_cielo"] : NULL);
 			$transacao["PanTransacao"] = (array_key_exists("pan_transacao_cielo", $pedido) ? $pedido["pan_transacao_cielo"] : NULL);
 			$transacao["Operadora"] = (array_key_exists("nome_operadora", $pedido) ? $pedido["nome_operadora"] : NULL);
 			
 			if ((array_key_exists("data_hora_retorno_autorizacao", $pedido)) and ($pedido["data_hora_retorno_autorizacao"] != NULL))
 				$transacao["DataRetorno"] = $pedido["data_hora_retorno_autorizacao"];
 			else $transacao["DataRetorno"] = NULL;
 			
 			$transacao["IdOperadora"] = (array_key_exists("id_operadora", $pedido) ? $pedido["id_operadora"] : $pedido["fk_operadora"]);
 			$transacao["IdOperadoraEmpresa"] = (array_key_exists("id_operadora_empresa", $pedido) ? $pedido["id_operadora_empresa"] : NULL);
 				
 			$transacao["NumSequencialRede"] = (array_key_exists("num_sequencial_rede", $pedido) ? $pedido['num_sequencial_rede'] : NULL);
 			$transacao["NumAutorizacaoRede"] = (array_key_exists("num_retorno_autorizacao_rede", $pedido) ? $pedido['num_retorno_autorizacao_rede'] : NULL);
 			$transacao["NumComprovanteRede"] = (array_key_exists("num_retorno_comprovante_rede", $pedido) ? $pedido['num_retorno_comprovante_rede'] : NULL);
 			$transacao["NumAutenticacaoRede"] = (array_key_exists("num_retorno_autenticacao_rede", $pedido) ? $pedido['num_retorno_autenticacao_rede'] : NULL);
 				
 			$transacao["AutorizacaoAutomaticaCielo"] = (array_key_exists("autorizacao_automatica_cielo", $pedido) ? $pedido['autorizacao_automatica_cielo'] : NULL);
 			$transacao["CapturaConfirmacaoAutomatica"] = (array_key_exists("captura_automatica", $pedido) ? $pedido['captura_automatica'] : NULL);
 				
 			if ($transacao["CapturaConfirmacaoAutomatica"] == 1) $transacao["ParametroCapturaAutomatica"] = (array_key_exists("captura_automatica_true", $pedido) ? $pedido['captura_automatica_true'] : NULL);
 			else $transacao["ParametroCapturaAutomatica"] = (array_key_exists("captura_automatica_false", $pedido) ? $pedido['captura_automatica_false'] : NULL);
 			$transacao["UrlWs"] = (array_key_exists("url_webservice", $pedido) ? $pedido['url_webservice'] : NULL);
 			$transacao["WsdlRede"] = (array_key_exists("url_wsdl_rede", $pedido) ? $pedido['url_wsdl_rede'] : NULL);
 				
 			
 			
 			if ((array_key_exists("data_hora_retorno_autorizacao", $pedido)) and ($pedido["data_hora_retorno_autorizacao"] != NULL))
 				$transacao["DataRetornoAutorizacao"] = $pedido["data_hora_retorno_autorizacao"];
 			else $transacao["DataRetornoAutorizacao"] = NULL;
 					
 			if ((array_key_exists("data_hora_retorno_autenticacao", $pedido)) and ($pedido["data_hora_retorno_autenticacao"] != NULL))
 				$transacao["DataRetornoAutenticacao"] = $pedido["data_hora_retorno_autenticacao"];
 			else $transacao["DataRetornoAutenticacao"] = NULL;
 						
 			if ((array_key_exists("data_hora_retorno_captura", $pedido)) and ($pedido["data_hora_retorno_captura"] != NULL))
 				$transacao["DataRetornoCaptura"] = $pedido["data_hora_retorno_captura"];
 			else $transacao["DataRetornoCaptura"] = NULL;
 							
 			if ((array_key_exists("data_hora_retorno_cancelamento", $pedido)) and ($pedido["data_hora_retorno_cancelamento"] != NULL))
 				$transacao["DataRetornoCancelamento"] = $pedido["data_hora_retorno_cancelamento"];
 			else $transacao["DataRetornoCancelamento"] = NULL;
 			
 			$transacao['ValorParcelaPedPag'] = (array_key_exists("ValorParcelaPedPag", $pedido) ? $pedido["ValorParcelaPedPag"] : NULL);
 			if (($transacao['ValorParcelaPedPag'] != null) && (strripos($transacao['ValorParcelaPedPag'], ".") === false)){
 				$transacao['ValorParcelaPedPag'] = $transacao['ValorParcelaPedPag'] . ".00";
 			}
 			
 			
 			
 			$transacao["listaHistorico"] = $bancoMysql->getHistorico($pedido, $_SESSION["dados_acesso"][0]["CODIGO"]);
 			
 			if ((array_key_exists("taxa", $pedido)) and ($pedido["taxa"] != NULL)) $transacao["Taxa"] = $pedido["taxa"];
 			else $transacao["Taxa"] = 0;
 				
 			if ((array_key_exists("valor_liquido", $pedido)) and ($pedido["valor_liquido"] != NULL)) $transacao["Liquido"] = $pedido["valor_liquido"];
 			else $transacao["Liquido"] = floatval($transacao["ValorParcelaPedPag"]) - (floatval($transacao["ValorParcelaPedPag"]) * (floatval($transacao["Taxa"]) / 100));
 			
 			switch ($transacao["StatusTransPag"]) {
 				case 1:
 					if ($transacao["IdOperadora"] == 1) {
 						if ((array_key_exists("msg_retorno_autenticacao_cielo", $pedido)) and ($pedido["msg_retorno_autenticacao_cielo"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_autenticacao_cielo"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}
 						
 					break;
 				case 2:
 					if ($transacao["IdOperadora"] == 1) {
 						if ((array_key_exists("msg_retorno_autenticacao_cielo", $pedido)) and ($pedido["msg_retorno_autenticacao_cielo"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_autenticacao_cielo"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}
 					break;
 				case 3:
 					if ($transacao["IdOperadora"] == 1) {
 						if ((array_key_exists("msg_retorno_autorizacao_cielo", $pedido)) and ($pedido["msg_retorno_autorizacao_cielo"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_autorizacao_cielo"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}elseif ($transacao["IdOperadora"] == 2) {
 						if ((array_key_exists("msg_retorno_autorizacao_rede", $pedido)) and ($pedido["msg_retorno_autorizacao_rede"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_autorizacao_rede"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}
 						
 					break;
 				case 4:
 					if ($transacao["IdOperadora"] == 1) {
 						if ((array_key_exists("msg_retorno_autorizacao_cielo", $pedido)) and ($pedido["msg_retorno_autorizacao_cielo"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_autorizacao_cielo"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}elseif ($transacao["IdOperadora"] == 2) {
 						if ((array_key_exists("msg_retorno_autorizacao_rede", $pedido)) and ($pedido["msg_retorno_autorizacao_rede"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_autorizacao_rede"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}
 					break;
 				case 5:
 					if ($transacao["IdOperadora"] == 1) {
 						if ((array_key_exists("msg_retorno_confirmacao_rede", $pedido)) and ($pedido["msg_retorno_confirmacao_rede"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_confirmacao_rede"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}elseif ($transacao["IdOperadora"] == 2) {
 						if ((array_key_exists("msg_retorno_confirmacao_rede", $pedido)) and ($pedido["msg_retorno_confirmacao_rede"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_confirmacao_rede"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}
 					break;
 				case 6:
 					if ($transacao["IdOperadora"] == 2) {
 						if ((array_key_exists("msg_retorno_estorno_rede", $pedido)) and ($pedido["msg_retorno_estorno_rede"] != NULL))
 							$transacao["MensagemRetorno"] = $pedido["msg_retorno_estorno_rede"];
 							else $transacao["MensagemRetorno"] = NULL;
 					}
 					break;
 				default:
 					$transacao["MensagemRetorno"] = NULL;
 					break;
 			}
 			$transacao["idBoleto"] = (array_key_exists("idBoleto", $pedido) ? $pedido['idBoleto'] : NULL);
 			$transacao["FormaPagamento"] = (array_key_exists("FormaPagamento", $pedido) ? $pedido['FormaPagamento'] : NULL);
 			$transacao["NumeroParcelasPedPag"] = (array_key_exists("num_parcelas", $pedido) ? $pedido['num_parcelas'] : NULL);
 			$transacao["idFormaPagamento"] = (array_key_exists("TipoPagamento", $pedido) ? $pedido['TipoPagamento'] : NULL);
 			
 			array_push($novalistatransacao, $transacao);
 			$pedido["listaPagamentos"] = $novalistatransacao;
 			
 			array_push($novalista, $pedido);
 			
 		}	
 		$_SESSION["listaPedidos"] = $novalista;
//  		foreach ($_SESSION["listaPedidos"] as $pe) {
//  			print_r($pe);
//  			echo "<br><br>";
//  		}
 		
//  		die();
		//foreach($_SESSION["listaPedidos"] as $pedido){
			
			//$pedido = $bancoCliente->buscarListaPedidosPagamento($pedido);
			
			
			
			
			
			//foreach($pedido["listaPagamentos"] as $transacao){
				//echo "<br>verificando2 <br>";
				//esta parte irá setar as variáveis de cada transação na sessão do usuário
				//NÃO ALTERAR A ORDEM EM QUE AS VARIÁVEIS SÃO SETADAS DEVIDO AO USO DO INDICE NUMÉRICO DOS ARRAYS NO JAVASCRIPT DA PÁGINA
				//QUALQUER INCLUSÃO DEVERÁ SER POSTA NA LINHA SUPERIOR DO array_push()
				//recomendável o uso de operadores ternários para sempre setar os valores dos arrays(se forem vazios inserir null)
				//$pedido = array();
				//print_r($transacao);
				//$pedido = $bancoMysql->getTransacao($transacao, $_SESSION["dados_acesso"][0]["CODIGO"]);
				
				//if($pedido != null) {
					
					//elseif ((array_key_exists("data_retorno_autorizacao_rede", $pedido)) and ($pedido["data_retorno_autorizacao_rede"] != NULL)) 
						//$transacao["DataRetorno"] = $pedido["data_retorno_autorizacao_rede"];
					
						
					
					

					
					//PEGAR HISTÓRICO DA TRANSAÇÃO NO BANCO MYSQL

					/*$listaHistorico = array();
					
					$historicoPedPag = $bancoMysql->getHistorico($transacao);	
					//print_r($historicoPedPag);
					if ($historicoPedPag["tid_transacao_cielo"] != NULL) $listaHistorico["CodigoTransacao"] = $historicoPedPag["tid_transacao_cielo"];
					elseif ($historicoPedPag["num_sequencial_rede"] != NULL) $listaHistorico["CodigoTransacao"] = $historicoPedPag["num_sequencial_rede"];

					$listaHistorico["DataAutorizacao"] =  $historicoPedPag['data_hora_retorno_autorizacao'] ;
					$listaHistorico["DataAutenticacao"] = $historicoPedPag['data_hora_retorno_autenticacao'];
					$listaHistorico["DataCaptura"] = $historicoPedPag['data_hora_retorno_captura'];
					$listaHistorico["DataCancelamento"] = $historicoPedPag['data_hora_retorno_cancelamento'];*/
					
					
					
					
										
				//}
				
				
			//}
			
			
	
			
			
		//}
		
		//echo ("Finalizando");
		
		
		//print_r($_SESSION["listaPedidos"]);
		
		header("Location: lista.php");
	}catch(Exception $e){
		echo "<br />FALHA AO BUSCAR DADOS<br />" .$e->getMessage();
	}
	
	

?>