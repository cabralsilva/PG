<?php

	@include("../odbc.php");
	session_start();
	$host = $_SESSION["dados_empresa"]["host_banco_empresa"];
	$banco = $_SESSION["dados_empresa"]["nome_banco_empresa"];
	$user = $_SESSION["dados_empresa"]["user_banco_empresa"];
	$senha = $_SESSION["dados_empresa"]["senha_banco_empresa"];
	$bancoCliente = new BancoODBC();
	try {
		$bancoCliente->testeBusca($host, $banco, $user, $senha);
		
		
		
		//print_r($_SESSION["listaPedidos"]);
		//header("Location: indextst.php");
	}catch(Exception $e){
		echo "<br />FALHA AO BUSCAR DADOS<br />" .$e->getMessage();
	}
	
	

?>