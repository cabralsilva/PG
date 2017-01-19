<?php
	require_once '../util/constantes.php';
	include '../controllers/consultasController.php';
	$cc = new ConsultasController();
	//$hc->buscarPagamentosPendentes();
	$cc->buscarOperadorasBoleto();
	date_default_timezone_set ( 'America/Sao_Paulo' );
?>
<!DOCTYPE html>
<html>
	<title>Área administrativa Cielo</title>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<HTTP-EQUIV ="PRAGMA" CONTENT="NO-CACHE"> 
	<link href="../resources/css/style.css" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="../resources/bootstrap/js/jquery3.3.1.min.js"></script>
	<script src="../resources/bootstrap/js/bootstrap3.3.7.min.js"></script>
	
	<!-- DATEPICKER -->
	<script src="../resources/datepicker-default/js/bootstrap-datepicker.js"></script>
	<script src="../resources/datepicker-default/locales/bootstrap-datepicker.pt-BR.min.js"></script>
	<link href="../resources/datepicker-default/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
		
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
	
	<!-- defaults -->
	<script src="../resources/default-js.js"></script>
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
															<option value="<?= $operadora["id_operadora"]?>"><?= $operadora["nome_operadora"]?></option>
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
													<option value="0">Pendentes</option>
													<option value="1">Autenticada</option>
													<option value="2">Não Autencada</option>
													<option value="3">Autorizadas</option>
													<option value="4">Não Autorizadas</option>
													<option value="5">Capturadas</option>
													<option value="6">Canceladas</option>
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
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		
        function buscarBoletosFiltro() {
        	var operadoras = "";
        	$("#selecaoOperadoraConsulta option:selected").each(function(ind, elem) {
        		operadoras += elem.value + ",";
        	});

        	var status = "";
        	$("#selecaoStatusConsulta option:selected").each(function(ind, elem) {
        		status += elem.value + ",";
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
	        		url : " <?= BaseProjeto ?>/controllers/consultasController.php",
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
	        			var obj = JSON.parse(e);
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
        	
        	var trs = "";
        	for (i in obj){
        		trs += 	"<tr class='linha_relatorio'>" +
        					"<td class=\"col-md-1\"><center>" +  ((obj[i].id_transacao != null)? obj[i].id_transacao : "") + "</center></td>" +
        					"<td class=\"col-md-1\"><center>" +  ((obj[i].tipo_cobranca != null)? obj[i].tipo_cobranca : "") + "</center></td>" +
        					"<td class=\"col-md-1\"><center><a href=\"lista.php?pedido=" + obj[i].fk_pedido + "\">" + obj[i].fk_pedido + "</a></center></td>" +
        					"<td class=\"col-md-2\"><center>" + obj[i].nome_operadora + "</center></td>" +
        					
        					"<td class=\"col-md-2\"><center>";
	        					if (obj[i].data_hora_pedido){
        							var dt = new Date(obj[i].data_hora_pedido);
        	                    	var d, m, mm;
        	                    	if (dt.getDate() <= 9) d = "0" + dt.getDate(); else d = dt.getDate();
        	                    	if ((dt.getMonth()+1) <= 9) m = "0" + (dt.getMonth()+1); else m = dt.getMonth()+1;
        	                    	if (dt.getMinutes() <= 9) mm = "0" + dt.getMinutes(); else mm = dt.getMinutes();
        	                    	trs +=  d + "/" + m + "/" + dt.getFullYear() + " " + dt.getHours() + ":" + mm;
        						}
        					trs += "</center></td>" +
        					"<td class=\"col-md-1\"><center>";
        						switch (obj[i].status_geral) {
                                    case "0":
                                        trs += "Pendente"; 
                                        break;
                                    case "1":
                                        trs += "Autenticada";
                                        break;
                                    case "2":
                                        trs += "Não Autenticada";
                                        break;
                                    case "3":
                                        trs += "Autorizada";
                                        break;
                                    case "4":
                                        trs += "Não Autorizada";
                                        break;
                                    case "5":
                                        trs += "Capturada";
                                        break;
                                    case "6":
                                        trs += "Cancelada";
                                        break;
                                    case "7":
                                        trs += "Indefinida";
                                        break;
                            	}
        						
        					trs += "</center></td>" +
        					"<td class=\"col-md-1\"><center>" + obj[i].descricao_forma_pagamento + "</center></td> " +
							"<td class=\"col-md-2\" align='right'>R$ ";
        						if (obj[i].valor_transacao.indexOf(".") == -1) {
        							var decimais = obj[i].valor_transacao.substr(-2, 2);
        							obj[i].valor_transacao = obj[i].valor_transacao.substr(0, obj[i].valor_transacao.length-2);
        							obj[i].valor_transacao = (parseFloat(obj[i].valor_transacao) + parseFloat(decimais)/100).toFixed(2);
        						}
        					obj[i].valor_transacao = parseFloat(obj[i].valor_transacao).toFixed(2);
        					trs += obj[i].valor_transacao.replace(".", ",") +
        					"</td>" +
        					"<td class=\"col-md-1\" align=\"right\">" +
    							"<button type=\"button\" class=\"btn btn-success dropdown-toggle\" data-toggle=\"dropdown\" data-idt=\"" + obj[i].id_transacao + "\" onclick=\"imprimirBoleto(this)\">Boleto</button>" +
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