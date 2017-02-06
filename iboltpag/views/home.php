<?php
require_once '../util/constantes.php';
include '../controllers/homeController.php';
$hc = new HomeController ();
$hc->buscarPagamentosPendentes ();
$hc->buscarOperadorasBoleto ();
date_default_timezone_set ( 'America/Sao_Paulo' );

// print_r($hc->getListPagamentosPendentes()[0]);
?>
<!DOCTYPE html>
<html>
<title>Área administrativa Cielo</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<HTTP-EQUIV ="PRAGMA" CONTENT="NO-CACHE">
<link href="../resources/css/style.css" rel="stylesheet" type="text/css">

<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="../resources/bootstrap/js/jquery3.3.1.min.js"></script> <script
	src="../resources/bootstrap/js/bootstrap3.3.7.min.js"></script> <!-- DATEPICKER -->
<script src="../resources/datepicker-default/js/bootstrap-datepicker.js"></script>
<script
	src="../resources/datepicker-default/locales/bootstrap-datepicker.pt-BR.min.js"></script>
<link
	href="../resources/datepicker-default/css/bootstrap-datepicker.css"
	rel="stylesheet" type="text/css">

<!-- defaults --> <script src="../resources/default-js.js"></script>

</head>
<body>
	<br>
	<div class="container containerrelatorio">
		<div class="panel-group" id="accordion">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?php include 'layout/header.php';?>
					</h3>
				</div>

				<div class="panel-body">
					<!-- Nav tabs PRINCIPAIS-->
					<div>
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a class="tab-principal"
								href="#tab-todos" aria-controls="tab-todos" role="tab"
								data-toggle="tab">Todos</a></li>
							<li role="presentation"><a class="tab-principal"
								href="#tab-boletos" aria-controls="tab-boletos" role="tab"
								data-toggle="tab">Boleto</a></li>
							<li role="presentation"><a class="tab-principal"
								href="#tab-cartoes" aria-controls="tab-cartoes" role="tab"
								data-toggle="tab">Cartões</a></li>
						</ul>
						<!-- Tab panes PRINCIPAIS-->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="tab-todos">
								<br>
								<div id="tableTransacoes">
									<table id="transacoes" class="table table-hover table-striped ">
										<thead>
											<tr>
												<th class="col-md-1"><center>ORIGEM</center></th>
												<th class="col-md-1"><center>CODIGO</center></th>
												<th class="col-md-1"><center>VENCIMENTO</center></th>
												<th class="col-md-1"><center>STATUS</center></th>
												<th class="col-md-1"><center>FORMA PGTO.</center></th>
												<th class="col-md-1 alignright">BRUTO</th>
												<th class="col-md-1 alignright">LIQUIDO</th>
												<th class="col-md-1"><center></center></th>
												<th class="col-md-1"><center></center></th>
											</tr>
										</thead>
										<tbody id="conteudo-relatorio">
										
										<?php foreach($hc->getListPagamentosPendentes() as $pagamento){?>
										
											<tr class='linha_relatorio'>
												<td class="col-md-1 valign">
													<center>
													<?php
											switch ($pagamento ["id_origem"]) {
												case "0" :
													echo "Avulso";
													break;
												case "1" :
													echo "Pedido";
													break;
												case "2" :
													echo "Faturamento";
													break;
												case "3" :
													echo "Tipo 3";
													break;
												case "4" :
													echo "Tipo 4";
													break;
												case "5" :
													echo "Tipo 5";
													break;
												case "6" :
													echo "Tipo 6";
													break;
												case "7" :
													echo "Tipo 7";
													break;
												case "8" :
													echo "Tipo 8";
													break;
												case "9" :
													echo "Tipo 9";
													break;
												default :
													echo "Não identificada";
													break;
											}
											?>
													</center>
												</td>
												<td class="col-md-1 valign"><center><?= $pagamento["fk_pedido"]?></center></td>
												<td class="col-md-1 valign">
													<center>
														<?= date("d/m/Y", strtotime($pagamento["data_vencimento_boleto"]))?>
													</center>
												</td>
												<td class="col-md-1 valign">
													<center>
													<?php
											switch ($pagamento ["status_geral"]) {
												case "0" :
													echo "Pendente";
													break;
												case "1" :
													echo "Autenticada";
													break;
												case "2" :
													echo "Não Autenticada";
													break;
												case "3" :
													echo "Autorizada";
													break;
												case "4" :
													echo "Não Autorizada";
													break;
												case "5" :
													echo "Capturada";
													break;
												case "6" :
													echo "Cancelada";
													break;
												case "7" :
													echo "Indefinida";
													break;
												case "8" :
													echo "Pago";
													break;
												case "9" :
													echo "Boleto contestado";
													break;
												default :
													echo "Não identificada";
													break;
											}
											?>
													</center>
												</td>
												<td class="col-md-1 valign">
													<center>
													<?= $pagamento["descricao_forma_pagamento"]?>
													</center>
												</td>
												<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
												<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
												<td class="col-md-1 valign" align='right'>
													<button type="button"
														class="btn btn-success dropdown-toggle"
														data-toggle="dropdown"
														data-idt="<?= $pagamento['id_transacao'] ?>"
														onclick="imprimirBoleto(this)">Boleto</button>
												</td>
												<td class="col-md-1 valign alignright">
													<center>
														<a data-toggle="collapse"
															href="#moreinfopagamentopendentes-<?= $pagamento["id_transacao"]?>"
															aria-expanded="false" aria-controls="collapseExample"><span
															id="iconmoreinfopendentes-<?= $pagamento["id_transacao"]?>"
															class="iconmorefinfopendentes-<?= $pagamento["id_transacao"]?> glyphicon glyphicon-plus"
															aria-hidden="true"></span></a>
													</center>
												</td>
											</tr>
											<tr class="success">
												<td class="moredetailpayment" colspan="9">
													<div
														id="moreinfopagamentopendentes-<?= $pagamento["id_transacao"]?>"
														data-idpagamento="<?= $pagamento["id_transacao"]?>"
														class="collapse moreinfopagamentopendentes">
														<table id="table5" class="table table-hover sucess">
															<thead class="headhistorico">
																<tr class="success">
																	<td class="col-md-4 "><center>NOSSO NÚMERO</center></td>
																	<td class="col-md-4 "><center>CLIENTE</center></td>
																	<td class="col-md-4 "><center>DATA CRIAÇÃO</center></td>
																</tr>
															</thead>
															<tbody>
																<tr class="success">
																	<td class="col-md-4 valign"><center><?= $pagamento["nosso_numero"]?></center></td>
																	<td class="col-md-4 valign"><center><?= $pagamento["nome_pagador"]?></center></td>
																	<td class="col-md-4 valign"><center><?= date("d/m/Y", strtotime($pagamento["data_hora_transacao"])) ?></center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</td>
											</tr>
											
										<?php }?>
										<!-- <tr><td colspan='12' align='center'>Preencha os filtros!</td></tr> -->
										</tbody>
									</table>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab-boletos">
								<br>
								<!-- Nav tabs BOLETOS-->
								<div>
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active"><a
											href="#tab-todos-boleto" aria-controls="tab-todos-boleto"
											role="tab" data-toggle="tab">Todos</a></li>
										<li role="presentation"><a href="#tab-remessa-pendente"
											aria-controls="tab-remessa-pendente" role="tab"
											data-toggle="tab">Remessa Pendente</a></li>
										<li role="presentation"><a href="#tab-retorno-pendente"
											aria-controls="tab-retorno-pendente" role="tab"
											data-toggle="tab">Retorno Pendente</a></li>
									</ul>
									<div class="tab-content">
										<div role="tabpanel" class="tab-pane active"
											id="tab-todos-boleto">
											<div id="tableTransacoes">
												<table id="transacoes"
													class="table table-hover table-striped ">
													<thead>
														<tr>
															<th class="col-md-1"><center>ORIGEM</center></th>
															<th class="col-md-1"><center>CODIGO</center></th>
															<th class="col-md-1"><center>VENCIMENTO</center></th>
															<th class="col-md-1"><center>STATUS</center></th>
															<th class="col-md-1"><center>FORMA PGTO.</center></th>
															<th class="col-md-1 alignright">BRUTO</th>
															<th class="col-md-1 alignright">LIQUIDO</th>
															<th class="col-md-1"><center></center></th>
															<th class="col-md-1"><center></center></th>
														</tr>
													</thead>
													<tbody id="conteudo-relatorio">
													<?php foreach($hc->getListPagamentosBoletosPendentes() as $pagamento){?>
													
														<tr class='linha_relatorio'>
															<td class="col-md-1 valign">
																<center>
																<?php
														switch ($pagamento ["id_origem"]) {
															case "0" :
																echo "Avulso";
																break;
															case "1" :
																echo "Pedido";
																break;
															case "2" :
																echo "Faturamento";
																break;
															case "3" :
																echo "Tipo 3";
																break;
															case "4" :
																echo "Tipo 4";
																break;
															case "5" :
																echo "Tipo 5";
																break;
															case "6" :
																echo "Tipo 6";
																break;
															case "7" :
																echo "Tipo 7";
																break;
															case "8" :
																echo "Tipo 8";
																break;
															case "9" :
																echo "Tipo 9";
																break;
															default :
																echo "Não identificada";
																break;
														}
														?>
																</center>
															</td>
															<td class="col-md-1 valign"><center><?= $pagamento["fk_pedido"]?></center></td>
															<td class="col-md-1 valign">
																<center>
																	<?= date("d/m/Y", strtotime($pagamento["data_vencimento_boleto"]))?>
																</center>
															</td>
															<td class="col-md-1 valign">
																<center>
																<?php
														switch ($pagamento ["status_geral"]) {
															case "0" :
																echo "Pendente";
																break;
															case "1" :
																echo "Autenticada";
																break;
															case "2" :
																echo "Não Autenticada";
																break;
															case "3" :
																echo "Autorizada";
																break;
															case "4" :
																echo "Não Autorizada";
																break;
															case "5" :
																echo "Capturada";
																break;
															case "6" :
																echo "Cancelada";
																break;
															case "7" :
																echo "Indefinida";
																break;
															case "8" :
																echo "Pago";
																break;
															case "9" :
																echo "Boleto contestado";
																break;
															default :
																echo "Não identificada";
																break;
														}
														?>
																</center>
															</td>
															<td class="col-md-1 valign">
																<center>
																<?= $pagamento["descricao_forma_pagamento"]?>
																</center>
															</td>
															<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
															<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
															<td class="col-md-1 valign" align='right'>
																<button type="button"
																	class="btn btn-success dropdown-toggle"
																	data-toggle="dropdown"
																	data-idt="<?= $pagamento['id_transacao'] ?>"
																	onclick="imprimirBoleto(this)">Boleto</button>
															</td>
															<td class="col-md-1 valign alignright">
																<center>
																	<a data-toggle="collapse"
																		href="#moreinfopagamentoboletospendentes-<?= $pagamento["id_transacao"]?>"
																		aria-expanded="false" aria-controls="collapseExample"><span
																		id="iconmorefinfoboletospendentes-<?= $pagamento["id_transacao"]?>"
																		class="iconmorefinfoboletospendentes-<?= $pagamento["id_transacao"]?> glyphicon glyphicon-plus"
																		aria-hidden="true"></span></a>
																</center>
															</td>
														</tr>
														<tr class="success">
															<td class="moredetailpayment" colspan="10">
																<div
																	id="moreinfopagamentoboletospendentes-<?= $pagamento["id_transacao"]?>"
																	data-idpagamento="<?= $pagamento["id_transacao"]?>"
																	class="collapse moreinfopagamentoboletospendentes">
																	<table id="table5" class="table table-hover sucess">
																		<thead class="headhistorico">
																			<tr class="success">
																				<td class="col-md-4 "><center>NOSSO NÚMERO</center></td>
																				<td class="col-md-4 "><center>CLIENTE</center></td>
																				<td class="col-md-4 "><center>DATA CRIAÇÃO</center></td>
																			</tr>
																		</thead>
																		<tbody>
																			<tr class="success">
																				<td class="col-md-4 valign"><center><?= $pagamento["nosso_numero"]?></center></td>
																				<td class="col-md-4 valign"><center><?= $pagamento["nome_pagador"]?></center></td>
																				<td class="col-md-4 valign"><center><?= date("d/m/Y", strtotime($pagamento["data_hora_transacao"])) ?></center></td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</td>
														</tr>
														
													<?php }?>
													<!-- <tr><td colspan='12' align='center'>Preencha os filtros!</td></tr> -->
													</tbody>
												</table>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane"
											id="tab-remessa-pendente">
											<div id="tableTransacoes">
												<table id="transacoes"
													class="table table-hover table-striped ">
													<thead>
														<tr>
															<th class="col-md-1"><center>ORIGEM</center></th>
															<th class="col-md-1"><center>CODIGO</center></th>
															<th class="col-md-1"><center>VENCIMENTO</center></th>
															<th class="col-md-1"><center>STATUS</center></th>
															<th class="col-md-1"><center>FORMA PGTO.</center></th>
															<th class="col-md-1 alignright">BRUTO</th>
															<th class="col-md-1 alignright">LIQUIDO</th>
															<th class="col-md-1"><center></center></th>
															<th class="col-md-1"><center></center></th>
														</tr>
													</thead>
													<tbody id="conteudo-relatorio">
													<?php foreach($hc->getListPagamentosBoletosRemessaPendentes() as $pagamento){?>
													
														<tr class='linha_relatorio'>
															<td class="col-md-1 valign">
																<center>
																<?php
														switch ($pagamento ["id_origem"]) {
															case "0" :
																echo "Avulso";
																break;
															case "1" :
																echo "Pedido";
																break;
															case "2" :
																echo "Faturamento";
																break;
															case "3" :
																echo "Tipo 3";
																break;
															case "4" :
																echo "Tipo 4";
																break;
															case "5" :
																echo "Tipo 5";
																break;
															case "6" :
																echo "Tipo 6";
																break;
															case "7" :
																echo "Tipo 7";
																break;
															case "8" :
																echo "Tipo 8";
																break;
															case "9" :
																echo "Tipo 9";
																break;
															default :
																echo "Não identificada";
																break;
														}
														?>
																</center>
															</td>
															<td class="col-md-1 valign"><center><?= $pagamento["fk_pedido"]?></center></td>
															<td class="col-md-1 valign">
																<center>
																	<?= date("d/m/Y", strtotime($pagamento["data_vencimento_boleto"]))?>
																</center>
															</td>
															<td class="col-md-1 valign">
																<center>
																<?php
														switch ($pagamento ["status_geral"]) {
															case "0" :
																echo "Pendente";
																break;
															case "1" :
																echo "Autenticada";
																break;
															case "2" :
																echo "Não Autenticada";
																break;
															case "3" :
																echo "Autorizada";
																break;
															case "4" :
																echo "Não Autorizada";
																break;
															case "5" :
																echo "Capturada";
																break;
															case "6" :
																echo "Cancelada";
																break;
															case "7" :
																echo "Indefinida";
																break;
															case "8" :
																echo "Pago";
																break;
															case "9" :
																echo "Boleto contestado";
																break;
															default :
																echo "Não identificada";
																break;
														}
														?>
																</center>
															</td>
															<td class="col-md-1 valign">
																<center>
																<?= $pagamento["descricao_forma_pagamento"]?>
																</center>
															</td>
															<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
															<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
															<td class="col-md-1 valign" align='right'>
																<button type="button"
																	class="btn btn-success dropdown-toggle"
																	data-toggle="dropdown"
																	data-idt="<?= $pagamento['id_transacao'] ?>"
																	onclick="imprimirBoleto(this)">Boleto</button>
															</td>
															<td class="col-md-1 valign alignright">
																<center>
																	<a data-toggle="collapse"
																		href="#moreinfopagamentoboletosremessapendentes-<?= $pagamento["id_transacao"]?>"
																		aria-expanded="false" aria-controls="collapseExample"><span
																		id="iconmorefinfoboletosremessapendentes-<?= $pagamento["id_transacao"]?>"
																		class="iconmorefinfoboletosremessapendentes-<?= $pagamento["id_transacao"]?> glyphicon glyphicon-plus"
																		aria-hidden="true"></span></a>
																</center>
															</td>
														</tr>
														<tr class="success">
															<td class="moredetailpayment" colspan="10">
																<div
																	id="moreinfopagamentoboletosremessapendentes-<?= $pagamento["id_transacao"]?>"
																	data-idpagamento="<?= $pagamento["id_transacao"]?>"
																	class="collapse moreinfopagamentoboletosremessapendentes">
																	<table id="table5" class="table table-hover sucess">
																		<thead class="headhistorico">
																			<tr class="success">
																				<td class="col-md-4 "><center>NOSSO NÚMERO</center></td>
																				<td class="col-md-4 "><center>CLIENTE</center></td>
																				<td class="col-md-4 "><center>DATA CRIAÇÃO</center></td>
																			</tr>
																		</thead>
																		<tbody>
																			<tr class="success">
																				<td class="col-md-4 valign"><center><?= $pagamento["nosso_numero"]?></center></td>
																				<td class="col-md-4 valign"><center><?= $pagamento["nome_pagador"]?></center></td>
																				<td class="col-md-4 valign"><center><?= date("d/m/Y", strtotime($pagamento["data_hora_transacao"])) ?></center></td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</td>
														</tr>
														
													<?php }?>
													<!-- <tr><td colspan='12' align='center'>Preencha os filtros!</td></tr> -->
													</tbody>
												</table>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane"
											id="tab-retorno-pendente">
											<div id="tableTransacoes">
												<table id="transacoes"
													class="table table-hover table-striped ">
													<thead>
														<tr>
															<th class="col-md-1"><center>ORIGEM</center></th>
															<th class="col-md-1"><center>CODIGO</center></th>
															<th class="col-md-1"><center>VENCIMENTO</center></th>
															<th class="col-md-1"><center>STATUS</center></th>
															<th class="col-md-1"><center>FORMA PGTO.</center></th>
															<th class="col-md-1 alignright">BRUTO</th>
															<th class="col-md-1 alignright">LIQUIDO</th>
															<th class="col-md-1"><center></center></th>
															<th class="col-md-1"><center></center></th>
														</tr>
													</thead>
													<tbody id="conteudo-relatorio">
													<?php foreach($hc->getListPagamentosBoletosRetornoPendentes() as $pagamento){?>
													
														<tr class='linha_relatorio'>
															<td class="col-md-1 valign">
																<center>
																<?php
														switch ($pagamento ["id_origem"]) {
															case "0" :
																echo "Avulso";
																break;
															case "1" :
																echo "Pedido";
																break;
															case "2" :
																echo "Faturamento";
																break;
															case "3" :
																echo "Tipo 3";
																break;
															case "4" :
																echo "Tipo 4";
																break;
															case "5" :
																echo "Tipo 5";
																break;
															case "6" :
																echo "Tipo 6";
																break;
															case "7" :
																echo "Tipo 7";
																break;
															case "8" :
																echo "Tipo 8";
																break;
															case "9" :
																echo "Tipo 9";
																break;
															default :
																echo "Não identificada";
																break;
														}
														?>
																</center>
															</td>
															<td class="col-md-1 valign"><center><?= $pagamento["fk_pedido"]?></center></td>
															<td class="col-md-1 valign">
																<center>
																	<?= date("d/m/Y", strtotime($pagamento["data_vencimento_boleto"]))?>
																</center>
															</td>
															<td class="col-md-1 valign">
																<center>
																<?php
														switch ($pagamento ["status_geral"]) {
															case "0" :
																echo "Pendente";
																break;
															case "1" :
																echo "Autenticada";
																break;
															case "2" :
																echo "Não Autenticada";
																break;
															case "3" :
																echo "Autorizada";
																break;
															case "4" :
																echo "Não Autorizada";
																break;
															case "5" :
																echo "Capturada";
																break;
															case "6" :
																echo "Cancelada";
																break;
															case "7" :
																echo "Indefinida";
																break;
															case "8" :
																echo "Pago";
																break;
															case "9" :
																echo "Boleto contestado";
																break;
															default :
																echo "Não identificada";
																break;
														}
														?>
																</center>
															</td>
															<td class="col-md-1 valign">
																<center>
																<?= $pagamento["descricao_forma_pagamento"]?>
																</center>
															</td>
															<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
															<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
															<td class="col-md-1 valign" align='right'>

																<div class="btn-group">
																	<button type="button"
																		class="btn btn-success dropdown-toggle"
																		data-toggle="dropdown" aria-haspopup="true"
																		aria-expanded="false">
																		Opções <span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu">
																		<li><a data-idt="<?= $pagamento['id_transacao'] ?>"
																			onclick="prepararBaixa(this)" href="#">Dar baixa</a></li>
																		<li><a href="#">Protestar</a></li>

																	</ul>
																</div>
															</td>
															<td class="col-md-1 valign alignright">
																<center>
																	<a data-toggle="collapse"
																		href="#moreinfopagamentoboletosretornopendentes-<?= $pagamento["id_transacao"]?>"
																		aria-expanded="false" aria-controls="collapseExample"><span
																		id="iconmorefinfoboletosretornopendentes-<?= $pagamento["id_transacao"]?>"
																		class="iconmorefinfoboletosretornopendentes-<?= $pagamento["id_transacao"]?> glyphicon glyphicon-plus"
																		aria-hidden="true"></span></a>
																</center>
															</td>
														</tr>
														<tr class="success">
															<td class="moredetailpayment" colspan="10">
																<div
																	id="moreinfopagamentoboletosretornopendentes-<?= $pagamento["id_transacao"]?>"
																	data-idpagamento="<?= $pagamento["id_transacao"]?>"
																	class="collapse moreinfopagamentoboletosretornopendentes">
																	<table id="table5" class="table table-hover sucess">
																		<thead class="headhistorico">
																			<tr class="success">
																				<td class="col-md-4 "><center>NOSSO NÚMERO</center></td>
																				<td class="col-md-4 "><center>CLIENTE</center></td>
																				<td class="col-md-4 "><center>DATA CRIAÇÃO</center></td>
																			</tr>
																		</thead>
																		<tbody>
																			<tr class="success">
																				<td class="col-md-4 valign"><center><?= $pagamento["nosso_numero"]?></center></td>
																				<td class="col-md-4 valign"><center><?= $pagamento["nome_pagador"]?></center></td>
																				<td class="col-md-4 valign"><center><?= date("d/m/Y", strtotime($pagamento["data_hora_transacao"])) ?></center></td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</td>
														</tr>
														
													<?php }?>
													<!-- <tr><td colspan='12' align='center'>Preencha os filtros!</td></tr> -->
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="tab-cartoes">
								<br>
								<div id="tableTransacoes">
									<table id="transacoes" class="table table-hover table-striped ">
										<thead>
											<tr>
												<th class="col-md-1"><center>ORIGEM</center></th>
												<th class="col-md-1"><center>CODIGO</center></th>
												<th class="col-md-1"><center>VENCIMENTO</center></th>
												<th class="col-md-1"><center>STATUS</center></th>
												<th class="col-md-1"><center>FORMA PGTO.</center></th>
												<th class="col-md-1 alignright">BRUTO</th>
												<th class="col-md-1 alignright">LIQUIDO</th>
												<th class="col-md-1"><center></center></th>
												<th class="col-md-1"><center></center></th>
											</tr>
										</thead>
										<tbody id="conteudo-relatorio">
										<?php foreach($hc->getListPagamentosCartoesPendentes() as $pagamento){?>
										
											<tr class='linha_relatorio'>
												<td class="col-md-1 valign">
													<center>
													<?php
											switch ($pagamento ["id_origem"]) {
												case "0" :
													echo "Avulso";
													break;
												case "1" :
													echo "Pedido";
													break;
												case "2" :
													echo "Faturamento";
													break;
												case "3" :
													echo "Tipo 3";
													break;
												case "4" :
													echo "Tipo 4";
													break;
												case "5" :
													echo "Tipo 5";
													break;
												case "6" :
													echo "Tipo 6";
													break;
												case "7" :
													echo "Tipo 7";
													break;
												case "8" :
													echo "Tipo 8";
													break;
												case "9" :
													echo "Tipo 9";
													break;
												default :
													echo "Não identificada";
													break;
											}
											?>
													</center>
												</td>
												<td class="col-md-1 valign"><center><?= $pagamento["fk_pedido"]?></center></td>
												<td class="col-md-1 valign">
													<center>
														<?= date("d/m/Y", strtotime($pagamento["data_vencimento_boleto"]))?>
													</center>
												</td>
												<td class="col-md-1 valign">
													<center>
													<?php
											switch ($pagamento ["status_geral"]) {
												case "0" :
													echo "Pendente";
													break;
												case "1" :
													echo "Autenticada";
													break;
												case "2" :
													echo "Não Autenticada";
													break;
												case "3" :
													echo "Autorizada";
													break;
												case "4" :
													echo "Não Autorizada";
													break;
												case "5" :
													echo "Capturada";
													break;
												case "6" :
													echo "Cancelada";
													break;
												case "7" :
													echo "Indefinida";
													break;
												case "8" :
													echo "Pago";
													break;
												case "9" :
													echo "Boleto contestado";
													break;
												default :
													echo "Não identificada";
													break;
											}
											?>
													</center>
												</td>
												<td class="col-md-1 valign">
													<center>
													<?= $pagamento["descricao_forma_pagamento"]?>
													</center>
												</td>
												<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
												<td class="col-md-1 valign" align='right'>R$ <?php echo number_format($pagamento['valor_transacao'], 2, ',', '.'); ?></td>
												<td class="col-md-1 valign" align='right'>
													<button type="button"
														class="btn btn-success dropdown-toggle"
														data-toggle="dropdown"
														data-idt="<?= $pagamento['id_transacao'] ?>"
														onclick="imprimirBoleto(this)">Boleto</button>
												</td>
												<td class="col-md-1 valign alignright">
													<center>
														<a data-toggle="collapse"
															href="#moreinfopagamentocartoespendentes-<?= $pagamento["id_transacao"]?>"
															aria-expanded="false" aria-controls="collapseExample"><span
															id="iconmorefinfocartoespendentes-<?= $pagamento["id_transacao"]?>"
															class="iconmorefinfocartoespendentes-<?= $pagamento["id_transacao"]?> glyphicon glyphicon-plus"
															aria-hidden="true"></span></a>
													</center>
												</td>
											</tr>
											<tr class="success">
												<td class="moredetailpayment" colspan="10">
													<div
														id="moreinfopagamentocartoespendentes-<?= $pagamento["id_transacao"]?>"
														data-idpagamento="<?= $pagamento["id_transacao"]?>"
														class="collapse moreinfopagamentocartoespendentes">
														<table id="table5" class="table table-hover sucess">
															<thead class="headhistorico">
																<tr class="success">
																	<td class="col-md-4 "><center>NOSSO NÚMERO</center></td>
																	<td class="col-md-4 "><center>CLIENTE</center></td>
																	<td class="col-md-4 "><center>DATA CRIAÇÃO</center></td>
																</tr>
															</thead>
															<tbody>
																<tr class="success">
																	<td class="col-md-4 valign"><center><?= $pagamento["nosso_numero"]?></center></td>
																	<td class="col-md-4 valign"><center><?= $pagamento["nome_pagador"]?></center></td>
																	<td class="col-md-4 valign"><center><?= date("d/m/Y", strtotime($pagamento["data_hora_transacao"])) ?></center></td>
																</tr>
															</tbody>
														</table>
													</div>
												</td>
											</tr>
											
										<?php }?>
										<!-- <tr><td colspan='12' align='center'>Preencha os filtros!</td></tr> -->
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
		<div id="table-loading"></div>
		<br> Obs.: .
		<p>
		
		<br>
		
		
		<p>
		
		
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 3px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 3px solid;"></div>
		<div class="linha-cb-branca" style="border-left: 1px solid;"></div>
		<div class="linha-cb-preta" style="border-left: 1px solid;"></div>
		</p>
	</div>
	
	<?php include 'layout/modais.php';?>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('.moreinfopagamentopendentes').on('show.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfopendentes-" + idPagamento).addClass('glyphicon-minus').removeClass('glyphicon-plus');
			});
			
			$('.moreinfopagamentopendentes').on('hide.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfopendentes-" + idPagamento).addClass('glyphicon-plus').removeClass('glyphicon-minus');
		  	});

		  	
			$('.moreinfopagamentoboletospendentes').on('show.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfoboletospendentes-" + idPagamento).addClass('glyphicon-minus').removeClass('glyphicon-plus');
			});
			
			$('.moreinfopagamentoboletospendentes').on('hide.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfoboletospendentes-" + idPagamento).addClass('glyphicon-plus').removeClass('glyphicon-minus');
		  	});

			$('.moreinfopagamentoboletosremessapendentes').on('show.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfoboletosremessapendentes-" + idPagamento).addClass('glyphicon-minus').removeClass('glyphicon-plus');
			});
			
			$('.moreinfopagamentoboletosremessapendentes').on('hide.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfoboletosremessapendentes-" + idPagamento).addClass('glyphicon-plus').removeClass('glyphicon-minus');
		  	});

			$('.moreinfopagamentoboletosretornopendentes').on('show.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfoboletosretornopendentes-" + idPagamento).addClass('glyphicon-minus').removeClass('glyphicon-plus');
			});
			
			$('.moreinfopagamentoboletosretornopendentes').on('hide.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfoboletosretornopendentes-" + idPagamento).addClass('glyphicon-plus').removeClass('glyphicon-minus');
		  	});

			$('.moreinfopagamentocartoespendentes').on('show.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfocartoespendentes-" + idPagamento).addClass('glyphicon-minus').removeClass('glyphicon-plus');
		  	});
			
			$('.moreinfopagamentocartoespendentes').on('hide.bs.collapse', function() {
				var idPagamento = $(this).data("idpagamento");
				$(".iconmorefinfocartoespendentes-" + idPagamento).addClass('glyphicon-plus').removeClass('glyphicon-minus');
		  	});
		});

		function prepararBaixa(elem){
			console.log("Preparando arquivo para baixa");
			$.ajax({
		    	url : "../controllers/homeController.php",
		        type: 'POST',
		        data: {
			        servico: "prepararBaixa",
			        id_transacao: elem.getAttribute('data-idt')
		        },
		        success: function (data) {
		            console.log(data);
		        }
		    });
		    return false;
		}
// 		$(window).on('beforeunload', function() {
// 		    return 'Your own message goes here...';
// 		    alert("Teste");
// 		});
// 		function teste(){
// 			alert("Teste");
// 		}
	</script>
</body>
</html>