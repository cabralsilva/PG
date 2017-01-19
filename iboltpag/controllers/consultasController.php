<?php
	require_once '../services/TransacaoService.php';
	
	class ConsultasController{
		
		function __construct() {
			session_start();
			define("Page", " Buscas e totalizadores");
		}
		
		public function buscarOperadorasBoleto(){
			$ts = new TransacaoService();
			$retorno = $ts->getOperadorasBoleto($_SESSION["dados_acesso"][0]["CODIGO"]);
			$_REQUEST["lstOperadoras"] = $retorno;
		}
		
	}
	
	if(isset($_POST["servico"])){
		if($_POST["servico"] == "buscarBoletos") buscarBoletos();
		elseif($_POST["servico"] == "buscarBoletosFiltro") buscarBoletosFiltro();
		elseif($_POST["servico"] == "gerarRemessa") gerarRemessa();
		elseif($_POST["servico"] == "gerarRemessaDia") gerarRemessaDia();
	}else{
		//echo "SERVICO NÃO CATALOGADO";
	}
	
	function buscarBoletosFiltro(){
		$ts = new TransacaoService();
		session_start();
		$identificador = $_POST["identificador"];
		$listOrigem = split(",", $_POST["origem"]);
		$codOrigem = $_POST["codOrigem"];
		$listaOperadoras = split(",", $_POST["operadoras"]);
		$_POST["dataPeriodoI"] = str_replace('/', '-', $_POST["dataPeriodoI"]);
		$_POST["dataPeriodoF"] = str_replace('/', '-', $_POST["dataPeriodoF"]);
		$_POST["dataPeriodoI"] = date("Y-m-d H:i:s", strtotime($_POST["dataPeriodoI"] . " 00:00:00"));
		$_POST["dataPeriodoF"] = date("Y-m-d H:i:s", strtotime($_POST["dataPeriodoF"] . " 23:59:59"));
		$dateI = $_POST["dataPeriodoI"];
		$dateF = $_POST["dataPeriodoF"];
		$listaStatus = split(",", $_POST["status"]);
		$listaFormaPgto = split(",", $_POST["formaPgto"]);
		$valorTransacao = str_replace(",", ".", $_POST["valorTransacao"]);
		
		
		$listaPagamentos = $ts->buscarPersonalizadaPagamentos($identificador, $listOrigem, $codOrigem, $listaOperadoras, $dateI, $dateF, $listaStatus, $listaFormaPgto, $valorTransacao);
// 		$_SESSION["listaTransacoesBoletos"] = array();
// 		foreach ($listaPagamentos as $value){
// 			array_push($_SESSION["listaTransacoesBoletos"], $value);
// 		}
// 		echo json_encode($_SESSION["listaTransacoesBoletos"]);
		echo json_encode($listaPagamentos);
	}
	
	