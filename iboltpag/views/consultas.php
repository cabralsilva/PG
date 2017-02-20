<?php
	require_once '../util/constantes.php';
	include '../controllers/consultasController.php';
	$cc = new ConsultasController();
	
	if (!isset($_SESSION["usuario_logado"])){
		$_SESSION["falha_login"] = "Autenticação necessária";
		header("location: " . BaseProjeto . "/../");
	}
	//$hc->buscarPagamentosPendentes();
	$cc->buscarOperadorasBoleto();
	$cc->buscarStatus();
	date_default_timezone_set ( 'America/Sao_Paulo' );
?>
<!DOCTYPE html>
<html>
	<title>Área administrativa Cielo</title>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<HTTP-EQUIV ="PRAGMA" CONTENT="NO-CACHE"> 
	<link href="<?= BaseProjeto ?>/resources/css/style.css" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="<?= BaseProjeto ?>/resources/bootstrap/js/jquery3.3.1.min.js"></script>
	<script src="<?= BaseProjeto ?>/resources/bootstrap/js/bootstrap3.3.7.min.js"></script>
	
	<!-- DATEPICKER -->
	<script src="<?= BaseProjeto ?>/resources/datepicker-default/js/bootstrap-datepicker.js"></script>
	<script src="<?= BaseProjeto ?>/resources/datepicker-default/locales/bootstrap-datepicker.pt-BR.min.js"></script>
	<link href="<?= BaseProjeto ?>/resources/datepicker-default/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
		
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
	
	<!-- defaults -->
	<script src="<?= BaseProjeto ?>/resources/default-js.js"></script>
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
				    	<br>
						<div id="tableTransacoes">
							<table id="transacoes" class="table table-hover table-striped ">
								<thead>
									<tr>
										<th class="col-md-1"><center>
												<input id="identificadorTransacao" type="text"
													class="form-control"
													aria-label="Text input with multiple buttons" min="0"
													pattern="\d*" step="1" /> <span href="#" data-toggle="tooltip" title="Ex.: Nosso número">IDENTIFICADOR</span>
											</center></th>
										<th class="col-md-1"><center>
												<select class="form-control selectpicker"
													id="selecaoOrigem" 
													data-selected-text-format="count" title="Selecione..."
													data-width="100%" multiple>
														<option value="0">Avulso</option>
														<option value="1">Pedido</option>
														<option value="2">Faturamento</option>
												</select> ORIGEM
											</center></th>
										<th class="col-md-1"><center>
												<input id="codOrigem" type="number" class="form-control"
													aria-label="Text input with multiple buttons" min="0"
													pattern="\d*" step="1" /> CÓDIGO
											</center></th>
										<th class="col-md-2"><center>
												<select class="form-control selectpicker"
													id="selecaoOperadoraConsulta" multiple
													data-selected-text-format="count" title="Selecione..."
													data-width="100%" multiple>
														<?php foreach ($_REQUEST["lstOperadoras"] as $operadora){?>
															<optgroup label="<?= $operadora["nomeOperadora"]?>">
																<?php foreach ($operadora["contas"] as $contas){?>
																	<option title="<?= $operadora["nomeOperadora"]?> - Ag: <?= $contas["agencia"]?> - CC:<?= $contas["conta"]?> - Cart: <?= $contas["carteira"]?>" value="<?= $contas["idOperadoraEmp"]?>">
																		Carteira: <?= $contas["carteira"]?> - Ag.: <?= $contas["agencia"]?> - Conta: <?= $contas["conta"]?>
																	</option>
																<?php }?>
																
															</optgroup>
															
														<?php }?>
												</select> OPERADOR
											</center></th>
										
										<th class="col-md-2"><center>
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control"
														id="dataPeriodoI" /> <span
														class="input-group-addon labelrangedate">até</span> <input
														type="text" class="input-sm form-control" id="dataPeriodoF" />
												</div>
												PERÍODO
											</center></th>
										<th class="col-md-1"><center>
												<select class="form-control selectpicker" id="selecaoStatusConsulta"
													multiple data-selected-text-format="count" title="Selecione..."
													data-width="100%" multiple>
													<optgroup label="Todos">
													<?php foreach ($_REQUEST["lstStatusTodos"] as $statusTodos){?>
														<option value="<?= $statusTodos["id_status"]?>"><?= $statusTodos["estado"]?></option>
													<?php }?>
													</optgroup>
													<optgroup label="Boletos">
													<?php foreach ($_REQUEST["lstStatusBoleto"] as $statusBoleto){?>
														<option value="<?= $statusBoleto["id_status"]?>"><?= $statusBoleto["estado"]?></option>
													<?php }?>
													</optgroup>
													<optgroup label="Cartao">
													<?php foreach ($_REQUEST["lstStatusCartao"] as $statusCartao){?>
														<option value="<?= $statusCartao["id_status"]?>"><?= $statusCartao["estado"]?></option>
													<?php }?>
													</optgroup>
												</select> STATUS
											</center></th>
										<th class="col-md-1">
											<center>
												<select class="form-control selectpicker" id="selecaoFormaPgtoConsulta"
													multiple data-selected-text-format="count" title="Selecione..."
													data-width="100%" multiple>
													<option value="0">Boleto</option>
													<option value="1">Cartão</option>
												</select> PAGAMENTO
											</center>
										</th>
										<th class="col-md-2">
											<div class="input-group">
												<span class="input-group-addon labelcifrao">R$ </span> <input
													id="valorBrutoTransacao" type="text"
													placeholder="use > ou <" class=" form-control
													inputvalortransacao" aria-label="Valor">
											</div>
											<center>VALOR</center>
										</th>
										
										<th class="col-md-1"
											style="text-align: right; vertical-align: top;">
											<button style="width: 100%;" onclick="buscarBoletosFiltro()"
												id="btnFiltrar" type="button" class="btn btn-success">
												<span class="glyphicon glyphicon-filter"></span>
											</button>
										</th>
									</tr>
								</thead>
								<tbody id="conteudo-relatorio">
	                   				<tr><td colspan='12' align='center'>Preencha os filtros!</td></tr> 
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="table-loading"></div>
		<br> Obs.: .
	</div>
	
	<?php include 'layout/modais.php';?>
	
	<script type="text/javascript">

		var obj;
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});

		function solicitarAlteracaoStatus(elem){

			$.ajax({
		    	url : "<?= BaseProjeto ?>/controllers/consultasController.php",
		        type: 'POST',
		        data: {
			        servico: "alterarStatus",
			        id_transacao: elem.getAttribute('data-idt'),
			        id_status: elem.getAttribute('data-st'),
			        id_remessa: elem.getAttribute('data-rem')
		        },
		        success: function (data) {
// 		            console.log(data);
		            var obj2 = JSON.parse(data);
// 					console.log(obj2);
					for (i in obj){
						if (obj[i].id_transacao == obj2["Model"]["id_transacao"]){
							obj[i].fk_status = obj2["Model"]["id_status"];
						 	obj[i].descricao_status = obj2["Model"]["descricao_status"];
						}
					}
// 		            $("#status"+elem.getAttribute('data-idt')).html(obj["Model"]["descricao_status"]);
		            $("#conteudo-relatorio").html(contruirRelatorio(obj));
		        }
		    });
		    return false;
		}
				
		
        function buscarBoletosFiltro() {
        	var operadoras = "";
        	$("#selecaoOperadoraConsulta option:selected").each(function(ind, elem) {
        		operadoras += elem.value + ",";
        	});

        	var status = "";
        	$("#selecaoStatusConsulta option:selected").each(function(ind, elem) {
        		status += elem.text + ",";
        	});

        	var origem = "";
        	$("#selecaoOrigem option:selected").each(function(ind, elem) {
        		origem += elem.value + ",";
        	});

        	var formapgto = "";
        	$("#selecaoFormaPgtoConsulta option:selected").each(function(ind, elem) {
        		formapgto += elem.value + ",";
        	});
        	
        	
        	var dtai = document.getElementById('dataPeriodoI').value;
        	var dtaf = document.getElementById('dataPeriodoF').value;

        	var identificador = document.getElementById("identificadorTransacao").value;
        	var codOrigem = document.getElementById('codOrigem').value;
        	var valorTransacao = document.getElementById('valorBrutoTransacao').value;
        	var codigoTransacao = document.getElementById('identificadorTransacao').value;

        	if (dtai == "") alert("Preencha o periodo desejado!");
        	else{
	        	$.ajax({
	        		async : true,
	        		type : 'POST',
	        		url : "<?= BaseProjeto ?>/controllers/consultasController.php",
	        		data : {
	        			servico: "buscarBoletosFiltro",
	        			identificador: identificador,
	        			origem: origem,
	        			codOrigem : codOrigem,
	        			operadoras : operadoras,
	        			dataPeriodoI : dtai,
	        			dataPeriodoF : dtaf,
	        			status : status,
	        			formaPgto: formapgto,
	        			valorTransacao : valorTransacao
	        		},
	        		success : function(e) {
// 	        			console.log(e);
	        			obj = JSON.parse(e);
	        			if (obj.length == 0)
	        				$("#conteudo-relatorio").html("<tr><td colspan='12' align='center'>Nenhum Registro encontrado!</td></tr>");
	        			else
	        				$("#conteudo-relatorio").html(contruirRelatorio(obj));
	        			// $("#conteudo-relatorio").html(table);
	
	        		},
	        		error : function(error) {
	        			// console.log(eval(error));
	        		}
	        	});
        	}
        	return false;
        }

        function contruirRelatorio(obj){
        	console.log(obj);
        	var trs = "";
        	for (i in obj){
        		trs += 	"<tr class='linha_relatorio'>" +
        					"<td class=\"col-md-1\"><center>" + obj[i].identificador + "</center></td>" +
        					"<td class=\"col-md-1\"><center>" + obj[i].descricao_origem + "</center></td>" +
        					"<td class=\"col-md-1\"><center>" + obj[i].codigo_origem + "</a></center></td>" +
        					"<td class=\"col-md-2\"><center>" + obj[i].nome_operadora + " - Ag: " + obj[i].numero_agencia + "- CC: " + obj[i].numero_conta + " - Cart: " + obj[i].codigo_carteira + "</center></td>" +
        					
        					"<td class=\"col-md-2\"><center>";
	        					if (obj[i].data_criacao_origem){
		        					var array = obj[i].data_criacao_origem.split("-");
	        						var dt = new Date(array[1] + "-" + array[2] + "-" + array[0]);
        	                    	var d, m, mm;
        	                    	
        	                    	if (dt.getDate() <= 9) d = "0" + dt.getDate(); else d = dt.getDate();
        	                    	if ((dt.getMonth()+1) <= 9) m = "0" + (dt.getMonth()+1); else m = dt.getMonth()+1;
        	                    	if (dt.getMinutes() <= 9) mm = "0" + dt.getMinutes(); else mm = dt.getMinutes();
        	                    	trs +=  d + "/" + m + "/" + dt.getFullYear();
        						}
        					trs += "</center></td>" +
        					"<td class=\"col-md-1\"><center id=\"status" + obj[i].id_transacao + "\">" + obj[i].estado + "</center></td>" +
        					"<td class=\"col-md-1\"><center>" + obj[i].descricao_forma_pagamento + "</center></td> " +
							"<td class=\"col-md-2\" align='right'>R$ ";
								
//         						if (obj[i].valor_transacao.indexOf(".") == -1) {
//         							var decimais = obj[i].valor_transacao.substr(-2, 2);
//         							obj[i].valor_transacao = obj[i].valor_transacao.substr(0, obj[i].valor_transacao.length-2);
//         							obj[i].valor_transacao = (parseFloat(obj[i].valor_transacao) + parseFloat(decimais)/100).toFixed(2);
//         						}
        						obj[i].valor_transacao = parseFloat(obj[i].valor_transacao).toFixed(2);
        						trs += obj[i].valor_transacao.replace(".", ",") +
        					"</td>" +
        					"<td class=\"col-md-1\" align=\"right\">" +
        						"<div class=\"btn-group\">" +
								"<button type=\"button\"" +
									"class=\"btn btn-success dropdown-toggle\"" +
									"data-toggle=\"dropdown\" aria-haspopup=\"true\"" +
									"aria-expanded=\"false\">" +
									"Ações <span class=\"caret\"></span>" +
								"</button>" +
								"<ul class=\"dropdown-menu\">";
// 								console.log(obj[i].fk_status);
								switch (obj[i].fk_status){
									case "1": //Solicitação de registro
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" data-st=\"4\" data-rem=\"" + obj[i].fk_arquivo + "\" onclick=\"solicitarAlteracaoStatus(this)\" href=\"#\">Cancelar</a></li>";
										trs += "<li role=\"separator\" class=\"divider\"></li>";
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" onclick=\"imprimirBoleto(this)\" href=\"#\">Boleto</a></li>";
										break;
									case "2": //Registrado
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" data-st=\"11\" data-rem=\"" + obj[i].fk_arquivo + "\" onclick=\"solicitarAlteracaoStatus(this)\" href=\"#\">Cancelar</a></li>";
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" data-st=\"12\" data-rem=\"" + obj[i].fk_arquivo + "\" onclick=\"solicitarAlteracaoStatus(this)\" href=\"#\">Protestar</a></li>"; 
										trs += "<li role=\"separator\" class=\"divider\"></li>";
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" onclick=\"imprimirBoleto(this)\" href=\"#\">Boleto</a></li>";
										break;
									case "4":
										trs += "<li class=\"dropdown-header\">Sem ações possíveis</li>";
										break;
									case "8": //Pendente
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" data-st=\"4\" data-rem=\"" + obj[i].fk_arquivo + "\" onclick=\"solicitarAlteracaoStatus(this)\" href=\"#\">Cancelar</a></li>";
										trs += "<li role=\"separator\" class=\"divider\"></li>";
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" onclick=\"imprimirBoleto(this)\" href=\"#\">Boleto</a></li>";
										break;
									default:
										trs += "<li class=\"dropdown-header\">Sem ações possíveis</li>";
										trs += "<li role=\"separator\" class=\"divider\"></li>";
										trs += "<li><a data-idt=\"" + obj[i].id_transacao + "\" onclick=\"imprimirBoleto(this)\" href=\"#\">Boleto</a></li>";
										break;
								}
								
								trs += "</ul>" +
								"</div>" +
							"</td>" + 
						"</tr>";
        	}
        	
        	//TOTALIZADORES
        	trs += 	"<tr class='info'>" +
        				"<td colspan=6></td>" + 
        				"<td align='right'><b>TOTAL</b></td>" +
        				"<td align='right'>R$ "; 
        					var total = 0;
        					for (i in obj){
        						total += parseFloat(obj[i].valor_transacao);
        					}
        					total = total.toFixed(2);
        					trs += String(total).replace(".", ",") +
        					"</td>" + 
           				"<td align='right'></td>"+ 
//         				"<td align='right' style='width: 9%;'>R$ "; 
//         					var totalliquido = 0;
//         					for (i in obj){
//         						totalliquido += parseFloat(obj[i].valor_liquido);
//         					}
//         					totalliquido = totalliquido.toFixed(2);
//         					trs += String(totalliquido).replace(".", ",") +
//         					"</td>" +
        			"</tr>"; 
        	return trs;
        }
	</script>
</body>
</html>