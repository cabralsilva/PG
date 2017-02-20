<?php
require_once '../services/TransacaoService.php';
require_once '../services/StatusService.php';
class ConsultasController {
	function __construct() {
		session_start ();
		define ( "Page", " Buscas e totalizadores" );
	}
	public function buscarOperadorasBoleto() {
		$ts = new TransacaoService ();
		$retorno = $ts->getOperadorasBoleto ( $_SESSION ["dados_acesso"] [0] ["CODIGO"] );
		
		$operadoras = array ();
		foreach ( $retorno as $linha ) {
			$conta = array (
					"idOperadoraEmp" => $linha ["id_operadora_empresa"],
					"carteira" => $linha ["codigo_carteira"],
					"agencia" => $linha ["numero_agencia"],
					"conta" => $linha ["numero_conta"] 
			);
			
			$operador = array (
					"idOperadora" => $linha ["id_operadora"],
					"nomeOperadora" => $linha ["nome_operadora"],
					"contas" => array (
							$conta 
					) 
			);
			
			$existe = false;
			foreach ( $operadoras as $operadora ) {
				if ($operadora ["idOperadora"] == $operador ["idOperadora"]) {
					$existe = true;
					break;
				}
			}
			
			if (! $existe) {
				array_push ( $operadoras, $operador );
			} else {
				foreach ( $operadoras as $key => $value ) {
					if ($operadoras [$key] ["idOperadora"] == $operador ["idOperadora"]) {
						array_push ( $operadoras [$key] ["contas"], $conta );
						break;
					}
				}
			}
		}
		$_REQUEST ["lstOperadoras"] = $operadoras;
	}
	public function buscarStatus() {
		$ss = new StatusService ();
		$retorno = $ss->getStatusBoleto ();
		$_REQUEST ["lstStatusBoleto"] = $retorno;
		
		$retorno = $ss->getStatusCartao ();
		$_REQUEST ["lstStatusCartao"] = $retorno;
		
		$retorno = $ss->getStatusTodos ();
		$_REQUEST ["lstStatusTodos"] = $retorno;
	}
}

if (isset ( $_POST ["servico"] )) {
	if ($_POST ["servico"] == "buscarBoletos")
		buscarBoletos ();
	elseif ($_POST ["servico"] == "buscarBoletosFiltro")
		buscarBoletosFiltro ();
	elseif ($_POST ["servico"] == "gerarRemessa")
		gerarRemessa ();
	elseif ($_POST ["servico"] == "gerarRemessaDia")
		gerarRemessaDia ();
	elseif ($_POST ["servico"] == "alterarStatus")
		alterarStatus ();
} else {
	// echo "SERVICO NÃO CATALOGADO";
}
function alterarStatus() {
	$ts = new TransacaoService ();
	$newStatus = $_POST ["id_status"];
	$idTransacao = $_POST ["id_transacao"];
	$idRemessa = $_POST ["id_remessa"];
	try {
		
		$ts->updateStatusTransaction ( $idTransacao, $newStatus, $idRemessa );
		
		$model = $ts->getDescricaoStatusTransaction ( $newStatus );
		$model ["id_transacao"] = $idTransacao;
		$model ["id_status"] = $newStatus;
		echo json_encode ( array (
				'CodStatus' => 1,
				'Msg' => 'Atualizado com sucesso',
				'Model' => $model 
		) );
	} catch ( Exception $e ) {
		
		echo json_encode ( array (
				'CodStatus' => 2,
				'Msg' => $e->getMessage (),
				'Model' => null 
		) );
	}
}
function buscarBoletosFiltro() {
	$ts = new TransacaoService ();
	session_start ();
	$identificador = $_POST ["identificador"];
	$listOrigem = split ( ",", $_POST ["origem"] );
	$codOrigem = $_POST ["codOrigem"];
	$listaOperadoras = split ( ",", $_POST ["operadoras"] );
	$_POST ["dataPeriodoI"] = str_replace ( '/', '-', $_POST ["dataPeriodoI"] );
	$_POST ["dataPeriodoF"] = str_replace ( '/', '-', $_POST ["dataPeriodoF"] );
	$_POST ["dataPeriodoI"] = date ( "Y-m-d H:i:s", strtotime ( $_POST ["dataPeriodoI"] . " 00:00:00" ) );
	$_POST ["dataPeriodoF"] = date ( "Y-m-d H:i:s", strtotime ( $_POST ["dataPeriodoF"] . " 23:59:59" ) );
	$dateI = $_POST ["dataPeriodoI"];
	$dateF = $_POST ["dataPeriodoF"];
	$listaStatus = split ( ",", $_POST ["status"] );
	$listaFormaPgto = split ( ",", $_POST ["formaPgto"] );
	$valorTransacao = str_replace ( ",", ".", $_POST ["valorTransacao"] );
	
	$listaPagamentos = $ts->buscarPersonalizadaPagamentos ( $identificador, $listOrigem, $codOrigem, $listaOperadoras, $dateI, $dateF, $listaStatus, $listaFormaPgto, $valorTransacao );
	// $_SESSION["listaTransacoesBoletos"] = array();
	// foreach ($listaPagamentos as $value){
	// array_push($_SESSION["listaTransacoesBoletos"], $value);
	// }
	// echo json_encode($_SESSION["listaTransacoesBoletos"]);
	echo json_encode ( $listaPagamentos );
}
	
	