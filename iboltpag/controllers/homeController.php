<?php
	require_once '../services/TransacaoService.php';
	
	class HomeController{
		private $listPagamentosPendentes = array();
		private $listPagamentosBoletosPendentes = array();
		private $listPagamentosCartoesPendentes = array();
		private $listPagamentosBoletosRemessaPendentes = array();
		private $listPagamentosBoletosRetornoPendentes = array();
		function __construct() {
			// server should keep session data for AT LEAST 1 hour
// 			ini_set('session.gc_maxlifetime', 10);
			
			// each client should remember their session id for EXACTLY 1 hour
// 			session_set_cookie_params(10);
			session_start();
			define("Page", " Pagamentos pendentes");
		}
		
		public function buscarPagamentosPendentes(){
			$ts = new TransacaoService();
			$this->listPagamentosPendentes = $ts->getPagamentosPendentes($_SESSION["dados_acesso"][0]["CODIGO"]);
			foreach($this->listPagamentosPendentes as $pagamentoPendente){
				
				if($pagamentoPendente["descricao_forma_pagamento"] == "Boleto à vista")
					array_push($this->listPagamentosBoletosPendentes, $pagamentoPendente);
				else 
					array_push($this->listPagamentosCartoesPendentes, $pagamentoPendente);
			}
			
			foreach($this->listPagamentosBoletosPendentes as $pagamentoBoletoPendente){
				
				if($pagamentoBoletoPendente["data_arquivo"] == null)
					array_push($this->listPagamentosBoletosRemessaPendentes, $pagamentoBoletoPendente);
				else //VERIFICIAR CONDIÇÃO PARA RETORNOS JÁ PROCESSADOS
					array_push($this->listPagamentosBoletosRetornoPendentes, $pagamentoBoletoPendente);
			}
		}
		
		public function buscarOperadorasBoleto(){
			$ts = new TransacaoService();
			$retorno = $ts->getOperadorasBoleto($_SESSION["dados_acesso"][0]["CODIGO"]);
			$_REQUEST["lstOperadoras"] = $retorno;
		}
		
		
		public function getListPagamentosPendentes(){
			return $this->listPagamentosPendentes;
		}
		public function getListPagamentosBoletosPendentes(){
			return $this->listPagamentosBoletosPendentes;
		}
		public function getListPagamentosCartoesPendentes(){
			return $this->listPagamentosCartoesPendentes;
		}
		public function getListPagamentosBoletosRemessaPendentes(){
			return $this->listPagamentosBoletosRemessaPendentes;
		}
		public function getListPagamentosBoletosRetornoPendentes(){
			return $this->listPagamentosBoletosRetornoPendentes;
		}
		
	}
	
	if(isset($_POST["servico"])){
		if($_POST["servico"] == "buscarBoletos") buscarBoletos();
		elseif($_POST["servico"] == "buscarBoletosFiltro") buscarBoletosFiltro();
		elseif($_POST["servico"] == "gerarRemessa") gerarRemessa();
		elseif($_POST["servico"] == "gerarRemessaDia") gerarRemessaDia();
		elseif($_POST["servico"] == "prepararBaixa") prepararBaixa();
	}else{
		//echo "SERVICO NÃO CATALOGADO";
	}
	
	function gerarRemessaDia(){
		$ts = new TransacaoService();
		session_start();
		$_POST["dataRemessa"] = str_replace('/', '-', $_POST["dataRemessa"]);
		$dataI = date("Y-m-d H:i:s", strtotime($_POST["dataRemessa"] . " 00:00:00"));
		$dataF = date("Y-m-d H:i:s", strtotime($_POST["dataRemessa"] . " 23:59:59"));
	
		if($_POST["banco"] == 3) $retorno = $ts->gerarRemessaBradescos400Dia($dataI, $dataF, $_POST["banco"], $_SESSION["dados_acesso"][0]["CODIGO"]);
		elseif($_POST["banco"] == 4) $retorno = $ts->gerarRemessaBancodoBrasil400Dia($dataI, $dataF, $_POST["banco"], $_SESSION["dados_acesso"][0]["CODIGO"]);
		echo json_encode($retorno);
	}
	
	function prepararBaixa(){
		$ts = new TransacaoService();
		$transacaoPai = $ts->getTransacao($_POST["id_transacao"]);
		if ($transacaoPai){
			print_r($ts->insertTransactionBaixa($transacaoPai));
// 			echo "\n\nEncontrou";
		}
		
// 		print_r($_POST);//"Testes de conexão de arquivos php";
	}