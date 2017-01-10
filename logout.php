<?php 
	session_start();
	unset( $_SESSION['id_usuario'] );
	unset( $_SESSION['nome_usuario'] );
	unset( $_SESSION['email_usuario'] );
	unset( $_SESSION['dados_empresa'] );
	$_SESSION["status_login"] = false;
	//session_destroy();
	//unset( $_SESSION );
	
	header("location:login.php");

?>