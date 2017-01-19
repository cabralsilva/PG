<?php
require_once '../services/TransacaoService.php';
require_once '../services/EmpresaService.php';

// print_r ( $_POST );
// print_r ( $_FILES );

$arquivoRetorno = fopen ( $_FILES ["arquivo"] ["tmp_name"], "r" );
// echo "\n\n";
if (! validarSintaxeArquivo ( $arquivoRetorno )) {
	echo "ERRO DE SINTAXE NO ARQUIVO";
} else {
	rewind ( $arquivoRetorno );
	$arquivoRetorno = fopen ( $_FILES ["arquivo"] ["tmp_name"], "r" );
	while ( ! feof ( $arquivoRetorno ) ) {
		$linha = fgets ( $arquivoRetorno );
		$tipoRegistro = substr ( $linha, 0, 1 );
		
		if ($tipoRegistro == "0") {
			// HEADER
// 			echo "\npercorrendo header: " . substr ( $linha, 1, 1 );
			
			$agencia = substr ( $linha, 26, 4 );
			$dg_agencia = substr ( $linha, 30, 1 );
			$conta_corrente = substr ( $linha, 31, 8 );
			$dg_conta_corrente = substr ( $linha, 39, 1 );
			$nome_cedente = substr ( $linha, 46, 30 );
			$data_arquivo_retorno = substr ( $linha, 94, 6 );
			$sequencial_retorno = substr ( $linha, 100, 7 );
			$num_convenio = substr ( $linha, 149, 7 );
// 			echo "\nAgencia: $agencia";
// 			echo "\nDG. Age: $dg_agencia";
// 			echo "\nConta C: $conta_corrente";
// 			echo "\nDG:  CC: $dg_conta_corrente";
// 			echo "\nCedente: $nome_cedente";
// 			echo "\nData Ar: $data_arquivo_retorno";
// 			echo "\nSeq Ret: $sequencial_retorno";
// 			echo "\nConveni: $num_convenio";
			if (!validarInformacoes($agencia, $dg_agencia, $conta_corrente, $dg_conta_corrente)){
				echo "ARQUIVO NÃO RECONHECIDO PARA A CONTA CADASTRADA DESTA EMPRESA PARA O BANCO DO BRASIL";
				break;
			}
		} elseif ($tipoRegistro == "7") {
			$nosso_numero = substr($linha, 63, 17);
// 			echo "\npercorrendo detalhe registro...\n";
		} elseif ($tipoRegistro == "9") {
// 			echo "\npercorrendo trailler...\n";
		}
	}
}
fclose ( $arquivoRetorno );
function validarInformacoes($agencia, $dg_agencia, $conta_corrente, $dg_conta_corrente) {
	$es = new EmpresaService ();
	session_start ();
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
			if ($bancoIde != "001BANCODOBRASIL  ")
				return false;
			if ($seqRegis != "000001")
				return false;
		} elseif ($tipoRegistro == "7") {
			
		} elseif ($tipoRegistro == "9") {
		} else {
			return false;
		}
	}
	return true;
}
