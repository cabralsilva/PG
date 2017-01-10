<?php
	session_start();
	date_default_timezone_set('America/Sao_Paulo');
    //date_default_timezone_set('America/Sao_Paulo');
    //print_r($_SESSION["listaPedidos"]);
?>
<!DOCTYPE html>
<html>
	<title>Área administrativa Cielo</title>
	<head>
        <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8">-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    
    </head>
	<body>
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	    <script src="../bootstrap/js/bootstrap.min.js"></script>
	    <script src="../js/moment.js"></script>
        <script src="controles.js"></script>

        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet" type="text/css">

        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

		<!-- DATEPICKER -->
		<script src="../js/datepicker/js/bootstrap-datepicker.js"></script>
		<script src="../js/datepicker/locales/bootstrap-datepicker.pt-BR.min.js"></script>
		<link href="../js/datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">

        <script type="text/javascript">
           	
			$(document).ready(function() {
				

			    $('.input-daterange').datepicker({
				    format: "dd/mm/yyyy",
				    weekStart: 0,
				    language: "pt-BR",
				    multidate: false,
				    daysOfWeekHighlighted: "0",
				    todayHighlight: true,
				    autoclose: true
				});
			    /*
				$("#btnExportExcel").click(function(event) {
					exportExcel();
					//$("#table").val( $("<div>").append( $("#transacoes").eq(0).clone()).html());
					//$("#FormularioExportacao").submit();
				});
				*/

			});
        </script>
    	
        <br>
        <div class="container containerrelatorio">
            <div class="panel-group" id="accordion">
                <div class="panel panel-success">
                    <div class="panel-heading">
                    	<h3 class="panel-title">
                    		<span class="nome-empresa"><?php echo $_SESSION["dados_acesso"][0]["NOME"] ?></span> 
                    		<span class="nome-pagina">  Relatório Transações</span>
                    		<span class="usuario">
	                    		<div align="right">Funcionário logado: <?php echo $_SESSION["dados_acesso"][0]["nome_usuario"] ?>
							    	<div class="btn-group">
							    		<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções <span class="caret"></span></button>
						    			<ul class="dropdown-menu">
						    				<li><a href="buscardados_transacao.php">Lista Pedidos</a></li>
						    				<li role="separator" class="divider"></li>
						    				<li><a href="../modulos.php">Sistemas</a></li>
						    				<li><a href="../logout.php">Logout</a></li>
						    			</ul>
							    	</div>
	                            </div>
	                    	</span>

                    	</h3>
					</div>
					<div class="panel-body">
						<div align="right">
							    <button type="button" class="btn btn-success " id="btnExportExcel"  onclick="window.location.href='exportexcel.php'">Excel  <span class="glyphicon glyphicon-export"></span></button>
							
							
				    	</div>
				    	<div id="tableTransacoes">
						<table id="transacoes" class="table table-hover table-striped ">
                   			<thead>
                   				<tr>
	                                <th class="col-md-1"><center>
	                                	<input id="codTransacao" type="text" class="form-control"  aria-label="Text input with multiple buttons" min="0" pattern="\d*" step="1"/> 
	                                	TRANSAÇÃO</center></th> 
	                                <th class="col-md-1"><center>
	                                	<select class="form-control selectpicker" id="selecaoOperadora" multiple data-selected-text-format="count" title="Todos" data-width="100%" multiple>
											<option value="1">Cielo</option>
											<option value="2">Rede</option>
									  	</select>
	                                	OPERADORA</center></th>
	                                <th class="col-md-1"><center><div class="input-daterange input-group" id="datepicker">
								    	<input type="text" class="input-sm form-control" id="dataAutorizacaoI" />
								        <span class="input-group-addon labelrangedate">até</span>
								        <input type="text" class="input-sm form-control" id="dataAutorizacaoF" />
								  	</div>
	                                	AUTORIZAÇÃO</center></th>
	                                <th class="col-md-1"><center><div class="input-daterange input-group" id="datepicker">
								    	<input type="text" class="input-sm form-control" id="dataCapturaI" />
								        <span class="input-group-addon labelrangedate">até</span>
								        <input type="text" class="input-sm form-control" id="dataCapturaF" />
								  	</div>
	                                	CAPTURA</center></th>
	                                <th class="col-md-1"><center><div class="input-daterange input-group" id="datepicker">
								    	<input type="text" class="input-sm form-control" id="dataCancelamentoI" />
								        <span class="input-group-addon labelrangedate">até</span>
								        <input type="text" class="input-sm form-control" id="dataCancelamentoF" />
								  	</div>
	                                	CANCELAMENTO</center></th>
	                                <th class="col-md-1"><center>
	                                	<select class="form-control selectpicker" id="selecaoStatus" multiple data-selected-text-format="count" title="Todos" data-width="100%" multiple>
										    <option value="0">Pendentes</option>
										    <option value="1">Autenticada</option>
										    <option value="2">Não Autencada</option>
										    <option value="3">Autorizadas</option>
										    <option value="4">Não Autorizadas</option>
										    <option value="5">Capturadas</option>
										    <option value="6">Canceladas</option>
									  	</select>
                            			STATUS</center></th>
	                                <th class="col-md-1" style="width: 10%;"><center>
		                                <select class="form-control selectpicker" id="selecaoFormaPagamento" multiple data-selected-text-format="count" title="Todos" data-width="100%" multiple>
										    <option value="1">Visa Crédito</option>
										    <option value="3">Visa Débito</option>
										    <option value="4">MasterCard Crédito</option>
										    <option value="6">MasterCard Débito</option>
										    <option value="7">Americam Express Crédito</option>
										    <option value="9">Elo Crédito</option>
										    <option value="11">Diners Club Crédito</option>
										    <option value="13">Discover Crédito</option>
										    <option value="14">JCB Crédito</option>
										    <option value="16">Aura Crédito</option>
										    <option value="18">Hiper Crédito</option>
										    <option value="20">Hipercard Crédito</option>
									  	</select>
	                                	FORMA PGTO.</center></th>
	                                <th class="col-md-1"><center><input id="numParcelas" type="text" placeholder="use > ou <" class="form-control inputvalortransacao" aria-label="Numero Parcelas">
	                                	PARCELAS</center></th>
	                                <th class="col-md-1"><center>
	                                	<input id="codPedido" type="number" class="form-control"  aria-label="Text input with multiple buttons" min="0" pattern="\d*" step="1"/> 
                            			PEDIDO</center></th>
	                                <th class="col-md-1"><center><div class="input-daterange input-group" id="datepicker">
								    	<input type="text" class="input-sm form-control" id="dataPedidoI" />
								        <span class="input-group-addon labelrangedate">até</span>
								        <input type="text" class="input-sm form-control" id="dataPedidoF" />
								  	</div>DATA</center></th>
	                                <th class="col-md-1" style="width: 9%; text-align: right"><div class="input-group">
									  	<span class="input-group-addon labelcifrao">R$</span>
									  	<input id="valorTransacao" type="text" placeholder="use > ou <" class="form-control inputvalortransacao" aria-label="Valor">
									</div>BRUTO</th>
	                                <th class="col-md-1" style="width: 9%;text-align: right"><center>
	                                	<button onclick="relatorio()" id="btnFiltrar" type="button" class="btn btn-success"><span class="glyphicon glyphicon-filter"></span></button>
	                                	LIQUIDO</center></th>
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


			<div id="table-loading"></div>
 	        <br>
            Obs.: O prazo máximo para realizar a captura é de 5 dias corridos após a data da autorização na CIELO e de 30 dias corridos na REDE. Se não for capturada dentro do prazo o sistema cancelará automaticamente.
        </div>
    </body>
</html>