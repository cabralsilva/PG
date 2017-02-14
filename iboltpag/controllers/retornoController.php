<?php
require_once '../services/TransacaoService.php';
require_once '../services/EmpresaService.php';
include '../util/funcoes.php';

session_start ();
if (isset($_POST["servico"])){
	switch ($_POST["servico"]){
		case "carregarRetorno":
			carregarRetorno();
			break;
		case "processarTransacoes":
			processarRetorno();
			break;
	}
}


function processarRetorno(){
	$registro = json_decode($_POST["lstTransacoes"], true);
	print_r($_POST["lstTransacoes"]);
	$ts = new TransacaoService();
	
	foreach($registro as $chave => $value){
		if ($registro[$chave]["comando"] == "02"){//Confirmada entrada de título
			$ts->updateStatusTransactionReturn($registro[$chave], 2);
			unset($registro[$chave]);
		}
	}
	
	foreach($registro as $chave => $value){	
		switch ($registro[$chave]["comando"]){
			case "03":
				break;
			case "05":
				break;
			case "06"://Liquidação normal
				$ts->updateStatusTransactionPaymentReturn($registro[$chave], 9);
				break;
			case "07":
				break;
			case "08":
				break;
			case "09":
				break;
			case "10"://Baixa solicitada
				$ts->updateStatusTransactionReturn($registro[$chave], 4);
				break;
			case "11":
				break;
			case "12":
				break;
			case "13":
				break;
			case "14":
				break;
			case "15"://liquidação em cartório
				$ts->liquidarPagamento($_SESSION["dados_acesso"][0]["CODIGO"], $registro[$chave]["origem"], $registro[$chave]["fk_pagamento"], $registro[$chave]["data_pagamento"], $array[$chave]["valor_pago"], 10);
				break;
			case "16":
				break;
			case "19":
				break;
			case "20":
				break;
			case "21":
				break;
			case "22":
				break;
			case "23"://Indicação de encaminhamento a cartório
				$ts->updateStatusTransactionReturn($_SESSION["dados_acesso"][0]["CODIGO"], $registro[$chave]["origem"], $registro[$chave]["fk_pagamento"], 6);
				break;
			case "24":
				break;
			case "25":
				break;
			case "26":
				break;
			case "28":
				break;
			case "31":
				break;
			case "32":
				break;
			case "33":
				break;
			case "34":
				break;
			case "35":
				break;
			case "36":
				break;
			case "37":
				break;
			case "38":
				break;
			case "39":
				break;
			case "41":
				break;
			case "42":
				break;
			case "44":
				break;
			case "46":
				break;
			case "72":
				break;
			case "73":
				break;
			case "96":
				break;
			case "97":
				break;
			case "98":
				break;
		}
	}
}

function carregarRetorno(){
	$arquivoRetorno = fopen ( $_FILES ["arquivo"] ["tmp_name"], "r" );
	if (! validarSintaxeArquivo ( $arquivoRetorno )) {
		echo "ERRO DE SINTAXE NO ARQUIVO";
	} else {
		$listRetorno = array();
		rewind ( $arquivoRetorno );
		$arquivoRetorno = fopen ( $_FILES ["arquivo"] ["tmp_name"], "r" );
		while ( ! feof ( $arquivoRetorno ) ) {
			$linha = fgets ( $arquivoRetorno );
			$tipoRegistro = substr ( $linha, 0, 1 );
			
			if ($tipoRegistro === "0") {
				// HEADER
				$agencia = substr ( $linha, 26, 4 );
				$dg_agencia = substr ( $linha, 30, 1 );
				$conta_corrente = substr ( $linha, 31, 8 );
				$dg_conta_corrente = substr ( $linha, 39, 1 );
				$nome_cedente = substr ( $linha, 46, 30 );
				$data_arquivo_retorno = substr ( $linha, 94, 6 );
				$sequencial_retorno = substr ( $linha, 100, 7 );
				$num_convenio = substr ( $linha, 149, 7 );
				if (!validarInformacoes($agencia, $dg_agencia, $conta_corrente, $dg_conta_corrente)){
					echo "ARQUIVO NÃO RECONHECIDO PARA A CONTA CADASTRADA DESTA EMPRESA PARA O BANCO DO BRASIL";
					break;
				}
			} elseif ($tipoRegistro == "7") {
				$ts = new TransacaoService();
				$nosso_numero = substr($linha, 70, 10);
					
				$origem = substr($nosso_numero, 0, 1);
				$fk_pagamento = ltrim(substr($nosso_numero, 1), '0');
				$nosso_numero = $num_convenio.$nosso_numero;
				$comando = substr($linha, 108, 2);
				$id_transacao = substr($linha, 38, 25);
				$nat_recebimento = substr($linha, 86, 2);
				$data_vencimento = DateTime::createFromFormat('dmy', substr($linha, 146, 6));
				$valor_titulo = ltrim(substr($linha, 152, 13), '0');
				$valor_titulo = ($valor_titulo/100);
				$data_pagamento = DateTime::createFromFormat('dmy', substr($linha, 110, 6));
				$valor_pago = ltrim(substr($linha, 253, 13), '0');
				$valor_pago = ($valor_pago/100);
				$data_credito = DateTime::createFromFormat('dmy', substr($linha, 175, 6));
					
				$transacao = $ts->getTransacaoByNossoNumero($_SESSION["dados_acesso"][0]["CODIGO"], $origem, $fk_pagamento);
					
				if (substr($linha, 146, 6) != "000000")
					$transacao["data_vencimento"] = $data_vencimento->format("d/m/Y");
						
				if (substr($linha, 110, 6))
					$transacao["data_pagamento"] = $data_pagamento->format("d/m/Y");
							
				if (substr($linha, 175, 6) != "000000")
					$transacao["data_credito"] = $data_vencimento->format("d/m/Y");
				
				$dataProcessamento = DateTime::createFromFormat('dmy', $data_arquivo_retorno);
				$transacao["data_processamento"] = $dataProcessamento->format("d/m/Y");
					
				$transacao["nosso_numero"] = $nosso_numero;
				$transacao["origem"] = $origem;
				$transacao["fk_pagamento"] = $fk_pagamento;
				$transacao["valor_titulo"] = $valor_titulo;
				$transacao["valor_pago"] = $valor_pago;
				$transacao["comando"] = $comando;
				$transacao["descricao_comando"] = comandoRetorno($comando);
				$transacao["id_transacao"] = intval($id_transacao);
				array_push($listRetorno, $transacao);
								
								
							// 			if ($comando == "03"){
							//O REGISTRO FOI RECUSADO
							// 				print_r(array("CodStatus" => 2, "Msg" => "Registro $nosso_numero foi recusado. Motivo: $nat_recebimento", "Model" => $linha));
							// 			}else{
	
							// 				switch ($comando){
							// 					case "05" || "06" || "07" || "08" || "15": //LIQUIDAÇÃO
							// 						$transacao["pago"] = true;
							// 						$data_pagamento = substr($linha, 110, 6);
							// 						$valor_pagamento = substr($linha, 253, 13);
	
	
							// 						echo "DT: $data_pagamento\n\n";
	
							// 						$ts = new TransacaoService();
							// 						$ts->liquidarPagamento($_SESSION["dados_acesso"][0]["CODIGO"], $origem, $fk_pagamento, $data_pagamento, $valor_pagamento);
							// 						break;
							// 				}
							// 			}
							// 			echo "\nNN: $nosso_numero";
							// 			echo "\nOR: $origem";
							// 			echo "\nPG: $fk_pagamento";
							// 			echo "\nCM: $comando";
							// 			echo "\nNR: $nat_recebimento\n";
								
							// 			echo "\npercorrendo detalhe registro...\n";
			} elseif ($tipoRegistro == "9") {
				// 			echo "\npercorrendo trailler...\n";
			}
		}
		echo (json_encode($listRetorno));
	}
	fclose ( $arquivoRetorno );
}

function validarInformacoes($agencia, $dg_agencia, $conta_corrente, $dg_conta_corrente) {
	$es = new EmpresaService ();
	
	$infoRetorno = $es->getOperadoraEmpresa($_SESSION["dados_acesso"][0]["CODIGO"], $agencia, $dg_agencia, $conta_corrente, $dg_conta_corrente);
	if ($infoRetorno == 1) return true;
	else return false;
}
function validarSintaxeArquivo($arquivoRetorno) {
	$retorno = false;
	while ( ! feof ( $arquivoRetorno ) ) {
		$linha = fgets ( $arquivoRetorno );
		if ((strlen ( $linha ) < 400) || (strlen ( $linha ) > 402))
			return false;
		
		$tipoRegistro = substr ( $linha, 0, 1 );
		
		if ($tipoRegistro == "0") {
			$tipoOper = substr ( $linha, 1, 1 );
			$literaOp = substr ( $linha, 2, 7 );
			$tipoServ = substr ( $linha, 9, 2 );
			$literaSe = substr ( $linha, 11, 8 );
			$bancoIde = substr ( $linha, 76, 18 );
			$seqRegis = substr ( $linha, 394, 6 );
			
			
			
			if ($tipoOper != "2")
				return false;
			if ($literaOp != "RETORNO")
				return false;
			if ($tipoServ != "01")
				return false;
			if ($literaSe != "COBRANCA")
				return false;
			if ($bancoIde != "001BANCO DO BRASIL")
				return false;
			if ($seqRegis != "000001")
				return false;
		} elseif ($tipoRegistro == "7") {
// 			echo "Novo: $tipoRegistro\n";
		} elseif ($tipoRegistro == "9") {
// 			echo "Novo: $tipoRegistro\n";
			return true;
		} else {
			return false;
		}
	}
	return true;
}
