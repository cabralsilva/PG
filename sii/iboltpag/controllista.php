<?php
	include("../Banco.php");
	header('Content-Type: text/html;charset=utf-8');
	session_start();
	
	if ($_POST["funcao"] == 'pesquisa') pesquisa();
	elseif ($_POST["funcao"] == 'paginacao') paginacao();
	elseif ($_POST["funcao"] == 'relatorio') relatorio();
	elseif ($_POST["funcao"] == 'exporttoexcel') exportToExcel();
	elseif ($_POST["funcao"] == 'paginacaopesquisa') paginacaoPesquisa();
	function pesquisa(){
		$lista = array();
		foreach($_SESSION["listaPedidos"] as $pedido){
			if (strripos($pedido["CodPedido"], $_POST["text"]) !== false){
				array_push($lista, $pedido);
			}
		}
		$_SESSION["listaPesquisa"] = $lista;
		$lista2 = utf8ize($lista);
		echo json_encode($lista2);
		//echo json_encode($lista);
		
	}
	
	function utf8ize($d) {
		if (is_array($d)) {
			foreach ($d as $k => $v) {
				$d[$k] = utf8ize($v);
			}
		} else if (is_string ($d)) {
			return utf8_encode($d);
		}
		return $d;
	}
	
	function paginacao(){
		$lista = array();
		$inicio = ($_POST["pagina"] * $_POST["numpedidos"]) - $_POST["numpedidos"];
		$fim = ($_POST["pagina"] * $_POST["numpedidos"]) - 1;
		if ($fim >= count($_SESSION["listaPedidos"])) $fim = count($_SESSION["listaPedidos"]) - 1;
		//echo $inicio . " to " . $fim;
		for ($i = $inicio; $i <= $fim; $i++){
			array_push($lista, $_SESSION["listaPedidos"][$i]);
		}
		
		$lista2 = utf8ize($lista);
		echo json_encode($lista2);
		/*
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				echo ' - No errors';
			break;
			case JSON_ERROR_DEPTH:
				echo ' - Maximum stack depth exceeded';
			break;
			case JSON_ERROR_STATE_MISMATCH:
				echo ' - Underflow or the modes mismatch';
			break;
			case JSON_ERROR_CTRL_CHAR:
				echo ' - Unexpected control character found';
			break;
			case JSON_ERROR_SYNTAX:
				echo ' - Syntax error, malformed JSON';
			break;
			case JSON_ERROR_UTF8:
				echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
			break;
			default:
				echo ' - Unknown error';
			break;
		}*/
		
	}

	function paginacaoPesquisa(){
		$lista = array();
		$inicio = ($_POST["pagina"] * $_POST["numpedidos"]) - $_POST["numpedidos"];
		$fim = ($_POST["pagina"] * $_POST["numpedidos"]) - 1;
		if ($fim >= count($_SESSION["listaPesquisa"])) $fim = count($_SESSION["listaPesquisa"]) - 1;
		for ($i = $inicio; $i <= $fim; $i++){
			array_push($lista, $_SESSION["listaPesquisa"][$i]);
		}
		$lista2 = utf8ize($lista);
		echo json_encode($lista2);
		//echo json_encode($lista);
	}
	
	function relatorio(){
		
		$_POST["operadoras"] = substr($_POST["operadoras"], 0, strlen($_POST["operadoras"]) -1 );
		$_POST["status"] = substr($_POST["status"], 0, strlen($_POST["status"]) -1 );
		$_POST["formaPgto"] = substr($_POST["formaPgto"], 0, strlen($_POST["formaPgto"]) -1 ); 
		$listaoperadoras = split(",", $_POST["operadoras"]);
		$listastatus = split(",", $_POST["status"]);
		$listaformapgto = split(",", $_POST["formaPgto"]);
		
		$previous = false;
		$bancoMysql = new BancoDados();

		$sql = "SELECT transacao.*, operadoras_cartao.*, forma_pagamento.* FROM TRANSACAO " .
				" INNER JOIN operadoras_cartao ON operadoras_cartao.id_operadora = transacao.fk_operadora" .
				" INNER JOIN forma_pagamento ON forma_pagamento.id_forma_pagamento = transacao.fk_forma_pagamento";
		if ($listaoperadoras[0] !== ""){
			$previous = true;
			$whereoperadoras = " WHERE (";
			for ($i=0; $i < count($listaoperadoras); $i++) { 
				if ($i == (count($listaoperadoras)-1)) $whereoperadoras .= "fk_operadora = " . $listaoperadoras[$i] . "";
				else $whereoperadoras .= "fk_operadora = " . $listaoperadoras[$i] . " or ";
			}
			$whereoperadoras .= ")";
			$sql .= $whereoperadoras;
		}
		
		if ($listastatus[0] !== ""){

			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$wherestatus = "(";
			for ($i=0; $i < count($listastatus); $i++) {
				if ($i == (count($listastatus)-1)) $wherestatus .= "status_geral = " . $listastatus[$i] . "";
				else $wherestatus .= "status_geral = " . $listastatus[$i] . " or ";
			}
			$wherestatus .= ")";
			$sql .= $wherestatus;
			//echo $sql;
		}

		if ($listaformapgto[0] !== ""){
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$whereformapgto = "(";
			for ($i=0; $i < count($listaformapgto); $i++) {
				if ($i == (count($listaformapgto)-1)) $whereformapgto .= "fk_forma_pagamento = " . $listaformapgto[$i] . "";
				else $whereformapgto .= "fk_forma_pagamento = " . $listaformapgto[$i] . " or ";
			}
			$whereformapgto .= ") ";
			$sql .= $whereformapgto;
		}

		if ($_POST["dataAutorizacaoI"]){
			$_POST["dataAutorizacaoI"] = str_replace('/', '-', $_POST["dataAutorizacaoI"]);
			$_POST["dataAutorizacaoF"] = str_replace('/', '-', $_POST["dataAutorizacaoF"]);
			$_POST["dataAutorizacaoI"] = date("Y-m-d H:i:s", strtotime($_POST["dataAutorizacaoI"] . " 00:00:00"));
			$_POST["dataAutorizacaoF"] = date("Y-m-d H:i:s", strtotime($_POST["dataAutorizacaoF"] . " 23:59:59"));
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$wheredataAutorizacao = "(data_hora_retorno_autorizacao BETWEEN '" . $_POST["dataAutorizacaoI"] . "' AND '" . $_POST["dataAutorizacaoF"] . "')";
			$sql .= $wheredataAutorizacao;
		}

		if ($_POST["dataCapturaI"]){
			$_POST["dataCapturaI"] = str_replace('/', '-', $_POST["dataCapturaI"]);
			$_POST["dataCapturaF"] = str_replace('/', '-', $_POST["dataCapturaF"]);
			$_POST["dataCapturaI"] = date("Y-m-d H:i:s", strtotime($_POST["dataCapturaI"] . " 00:00:00"));
			$_POST["dataCapturaF"] = date("Y-m-d H:i:s", strtotime($_POST["dataCapturaF"] . " 23:59:59"));
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$wheredataAutorizacao = "(data_hora_retorno_captura BETWEEN '" . $_POST["dataCapturaI"] . "' AND '" . $_POST["dataCapturaF"] . "')";
			$sql .= $wheredataAutorizacao;
		}

		if ($_POST["dataCancelamentoI"]){
			$_POST["dataCancelamentoI"] = str_replace('/', '-', $_POST["dataCancelamentoI"]);
			$_POST["dataCancelamentoF"] = str_replace('/', '-', $_POST["dataCancelamentoF"]);
			$_POST["dataCancelamentoI"] = date("Y-m-d H:i:s", strtotime($_POST["dataCancelamentoI"] . " 00:00:00"));
			$_POST["dataCancelamentoF"] = date("Y-m-d H:i:s", strtotime($_POST["dataCancelamentoF"] . " 23:59:59"));
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$wheredataAutorizacao = "(data_hora_retorno_cancelamento BETWEEN '" . $_POST["dataCancelamentoI"] . "' AND '" . $_POST["dataCancelamentoF"] . "')";
			$sql .= $wheredataAutorizacao;
		}

		if ($_POST["dataPedidoI"]){
			$_POST["dataPedidoI"] = str_replace('/', '-', $_POST["dataPedidoI"]);
			$_POST["dataPedidoF"] = str_replace('/', '-', $_POST["dataPedidoF"]);
			$_POST["dataPedidoI"] = date("Y-m-d H:i:s", strtotime($_POST["dataPedidoI"] . " 00:00:00"));
			$_POST["dataPedidoF"] = date("Y-m-d H:i:s", strtotime($_POST["dataPedidoF"] . " 23:59:59"));
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$wheredataAutorizacao = "(data_hora_pedido BETWEEN '" . $_POST["dataPedidoI"] . "' AND '" . $_POST["dataPedidoF"] . "')";
			$sql .= $wheredataAutorizacao;
		}

		if ($_POST["codigoPedido"]){
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$wherePedido = "(fk_pedido = " . $_POST["codigoPedido"] . ")";
			$sql .= $wherePedido;
		}

		if ($_POST["numeroParcelas"]){
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;

			if ((strstr($_POST["numeroParcelas"], ">")) or (strstr($_POST["numeroParcelas"], "<")))
				$whereParcelas = "(qtde_parcelas " . $_POST["numeroParcelas"] . ")";
			else
				$whereParcelas = "(qtde_parcelas = " . $_POST["numeroParcelas"] . ")";
			$sql .= $whereParcelas;
		}

		if ($_POST["valorTransacao"]){
			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$_POST["valorTransacao"] = str_replace(",", ".", $_POST["valorTransacao"]);
			if ((strstr($_POST["valorTransacao"], ">")) or (strstr($_POST["valorTransacao"], "<")))
				$whereValor = "(valor_transacao " . $_POST["valorTransacao"] . ")";
			else
				$whereValor = "(valor_transacao = " . $_POST["valorTransacao"] . ")";
			$sql .= $whereValor;
		}

		if ($_POST["codTransacao"]){

			if ($previous) $sql .= " and "; else $sql .= " WHERE ";
			$previous = true;
			$whereValor = "(tid_transacao_cielo = '" . $_POST["codTransacao"] . "' OR num_sequencial_rede = '" . $_POST["codTransacao"] . "')";
			$sql .= $whereValor;
		}
		

		$_SESSION["listaTransacoes"] = $bancoMysql->buscaTransacoesPersonalizada($sql, $_SESSION["dados_acesso"][0]["CODIGO"]);
		print_r(json_encode($_SESSION["listaTransacoes"]));
		//print_r(json_encode($bancoMysql->buscaTransacoesPersonalizada($sql)));
		//echo json_encode($bancoMysql->buscaTransacoesPersonalizada($sql));
		
	}	

	function exportToExcel(){
		// filename for download
		$filename = "website_data_" . date('Ymd') . ".xls";

		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");


		$flag = false;
	  	foreach($_SESSION["listaTransacoes"] as $row) {
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
	}

	function cleanData(&$str)
	{
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}
?>