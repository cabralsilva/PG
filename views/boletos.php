<?php
	include '../controllers/remessa.php';
	
	$arquivo = new Remessa();
	
	$arquivo->gerarRemessaBradesco400();
	