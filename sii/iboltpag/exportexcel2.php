<?php





/** Error reporting */
error_reporting(E_ALL);




/** Include path **/
ini_set('include_path', ini_get('include_path').';phpexcel/Classes/');

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';

// Create new PHPExcel object
//echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
//echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("IboltPag");
$objPHPExcel->getProperties()->setLastModifiedBy("IboltPag");
$objPHPExcel->getProperties()->setTitle("Lista de Transações");
$objPHPExcel->getProperties()->setSubject("Lista de Transações");
$objPHPExcel->getProperties()->setDescription("Arquivo gerado pelo IboltPag");

session_start();


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
		$transacao["Bruto"] = $linha["valor_transacao"];
		array_push($listaTransacoes, $transacao);
	}


	// filename for download
	$filename = "website_data_" . date('Ymd') . ".xls";

	$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'size'  => 12,
        'name'  => 'Verdana'
    ));

	$flag = false;
	$column = 0;
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Codigo');
	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Operadora');
	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Autorizacao');
	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Captura');
	$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Cancelamento');
	$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Status');
	$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Forma Pagamento');
	$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Parcelas');
	$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Pedido');
	$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Data');
	$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Bruto');
	$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArray);
	$objPHPExcel->setActiveSheetIndex(0);
	$numberline = 3;
  	foreach($listaTransacoes as $row) {
  		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$numberline, $row["Codigo"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$numberline, $row["Operadora"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$numberline, $row["Autorizacao"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$numberline, $row["Captura"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$numberline, $row["Cancelamento"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$numberline, $row["Status"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$numberline, $row["Forma Pagamento"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$numberline, $row["Parcelas"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$numberline, $row["Pedido"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$numberline, $row["Data"]);
  		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$numberline, $row["Bruto"]);
  		$numberline++;
	}

$objPHPExcel->getActiveSheet()->setTitle('Lista');

		
// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomeArquivo = "iBoltPag_Lista_" . date('Ymd') . ".xlsx";
//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$objWriter->save($nomeArquivo);


header("Location: ".$nomeArquivo);

//unlink($nomeArquivo);
?>