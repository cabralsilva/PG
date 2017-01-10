<?php
/*
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: filename=arquivoExcel.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	echo $_POST['table'];*/
	session_start();
	function cleanData(&$str)
	{
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
		$str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
	}

	$listaTransacoes = array();
	$transacao = array();
	foreach($_SESSION["listaTransacoes"] as $linha) {
		if ($linha["fk_operadora"] == 1 ) $transacao["Codigo"] = $linha["tid_transacao_cielo"];
		elseif ($linha["fk_operadora"] == 2) $transacao["Codigo"] = $linha["num_sequencial_rede"];
		$transacao["Operadora"] = $linha["nome_operadora"];
		$transacao["Autorizacao"] = $linha["data_hora_retorno_autorizacao"];
		$transacao["Captura"] = $linha["data_hora_retorno_captura"];
		$transacao["Cancelamento"] = $linha["data_hora_retorno_cancelamento"];

		switch ($linha["status_geral"]) {
			case 0:
				$transacao["Status"] = "Pendente";
				break;
			case 1:
				$transacao["Status"] = "Autenticada";
				break;
			case 2:
				$transacao["Status"] = "Não Autenticada";
				break;
			case 3:
				$transacao["Status"] = "Autorizada";
				break;
			case 4:
				$transacao["Status"] = "Não Autorizada";
				break;
			case 5:
				$transacao["Status"] = "Capturada";
				break;
			case 6:
				$transacao["Status"] = "Cancelada";
				break;
			default:
				break;
		}

		$transacao["Forma Pagamento"] = $linha["descricao_forma_pagamento"];
		$transacao["Parcelas"] = $linha["qtde_parcelas"];
		$transacao["Pedido"] = $linha["fk_pedido"];
		$transacao["Data"] = $linha["data_hora_pedido"];

		if (strstr($linha["valor_transacao"], ".")){
			$transacao["Bruto"] = str_replace(".", ",", $linha["valor_transacao"]);	
		}else{
			$transacao["Bruto"] = substr($linha["valor_transacao"], 0, -2);
			$decimais = substr($linha["valor_transacao"], -2, 2);
			$transacao["Bruto"] = $transacao["Bruto"] . ",".$decimais;
		}
		//$transacao["Bruto"] = $linha["valor_transacao"];
		$transacao["Liquido"] = str_replace(".", ",", $linha["valor_liquido"]);
		array_push($listaTransacoes, $transacao);
	}


	// filename for download
	$filename = "website_data_" . date('Ymd') . ".xls";

	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");


	$flag = false;
  	foreach($listaTransacoes as $row) {
	    if(!$flag) {
	      // display field/column names as first row
	      echo implode("\t", array_keys($row)) . "\r\n";
	      $flag = true;
	    }
	    array_walk($row, __NAMESPACE__ . '\cleanData');
	    echo implode("\t", array_values($row)) . "\r\n";
	}
	
	//$html = $_POST["table"];
    //echo $html;
	

	
?>