<?php
	session_start();
	include("../util/connectionMysql.php");
	
	
	$bancoMysql = new BancoMysql();
	
	try {
		$bancoMysql->connect();
	} catch (Exception $e) {
		echo "Falha na Conexão com Base de Dados" .$e->getMessage();
	}
	
	if ($bancoMysql->getStatusConexao()){
		try {
			$dados = $bancoMysql->login($_POST["email"], $_POST["senha"]);
			$_SESSION["usuario_logado"]["id_usuario"] = $dados["id_usuario"];
			$_SESSION["usuario_logado"]["nome_usuario"] = stripslashes($dados["nome_usuario"]);
			$_SESSION["usuario_logado"]["email_usuario"] = stripslashes($dados["email_usuario"]);
			
			header("location: ".BaseProjeto."/pagamentos-pendentes");
		} catch (Exception $e) {
			$_SESSION["falha_login"] = $e->getMessage(); 
			unset($_SESSION["usuario_logado"]);
			header("location: " . $_SERVER["HTTP_REFERER"] );
			exit();
		}
		
// 		try {
// 			$_SESSION["dados_acesso"] = $bancoMysql->verificarSistemasHabilitados($_SESSION["usuario_logado"]["id_usuario"]);
			
// 			$_SESSION["dados_empresa"]["cod_empresa"] = $_SESSION["dados_acesso"][0]["CODIGO"];
// 			$_SESSION["dados_empresa"]["nome_empresa"] = $_SESSION["dados_acesso"][0]["NOME"];
// 			$_SESSION["dados_empresa"]["host_banco_empresa"] = $_SESSION["dados_acesso"][0]["HOST_BANCO"];
// 			$_SESSION["dados_empresa"]["nome_banco_empresa"] = $_SESSION["dados_acesso"][0]["NOME_BANCO"];
// 			$_SESSION["dados_empresa"]["user_banco_empresa"] = $_SESSION["dados_acesso"][0]["USER_BANCO"];
// 			$_SESSION["dados_empresa"]["senha_banco_empresa"] = $_SESSION["dados_acesso"][0]["SENHA_BANCO"];
			
// 			header("location: ../views/");
// 		} catch (Exception $e) {
// 			$_SESSION["falha_login"] = $e->getMessage(); 
// 			header("location: " . $_SERVER["HTTP_REFERER"] );
// 			exit();
// 		}
	}
	
	