<?php 
	session_start();
	unset( $_SESSION['usuario_logado'] );;
	unset( $_SESSION['dados_empresa'] );
	header("location: ../login");
?>