<?php
	session_start();
	include("Banco.php");
	$banco = new BancoDados();
	
	try { 
		$banco->connect(); 
	} catch (Exception $e) { 
		echo "Falha na Conexão com Base de Dados" .$e->getMessage(); 
	} 
	//echo $banco->getStatusConexao();
	
	if ($banco->getStatusConexao()){
		$banco->login();
		//echo $banco->getStatusLogin();
		if ($banco->getStatusLogin()) {
			$banco->verificarSistemasHabilitados();
			//die();
			header("location:iboltpag/views/home.php");
		}
	}
?>
