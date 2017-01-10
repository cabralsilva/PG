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

        <script type="text/javascript">
            $('.dropdown-toggle').dropdown();
            $('.collapse').collapse();
           	function pesquisarCodigo(myfield,e){
           		var keycode;
				if (window.event) keycode = window.event.keyCode;
				else if (e) keycode = e.which;
				else return true;

				if (keycode == 13){
					search($("#inputSearch").val());
					return false;
				}else return true;
			}
			
			$(document).ready(function() {

           		paginacao(1);
           		
			    $("#search").on('click', function(){
			    	search($("#inputSearch").val());
			    });
			});
        </script>
    	
        <br>
        <div class="container">
            <div class="panel-group" id="accordion">
                <div class="panel panel-info">
                    <div class="panel-heading">
                    	<div class="row">
						    <div class="col-md-5">
						    	<span class="nome-empresa"><?php echo $_SESSION["dados_acesso"][0]["NOME"] ?></span> <span class="nome-pagina">  LISTA DE PEDIDOS</span>
							</div>
						    <div class="col-md-3" align="center">
							    
						    	<div class="input-group"> 
						    		<input for="search" id="inputSearch" type="number" class="form-control" placeholder="Código do Pedido" aria-label="Text input with multiple buttons" onKeyPress="pesquisarCodigo(this,event);"/> 
					    			<div class="input-group-btn"> 
					    				<button type="button" class="btn btn-default" id="search">Pesquisar</button> 
						    		</div> 
						    	</div>

							</div>
						    <div class="col-md-4" align="right">Funcionário logado: <?php echo $_SESSION["dados_acesso"][0]["nome_usuario"] ?>
						    	<div class="btn-group">
						    		<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções <span class="caret"></span></button>
					    			<ul class="dropdown-menu">
					    				<li><a href="relatorio.php">Relatório de Transações</a></li>
					    				<li role="separator" class="divider"></li>
					    				<li><a href="../modulos.php">Sistemas</a></li>
					    				<li><a href="../logout.php">Logout</a></li>
					    			</ul>
						    	</div>
                            </div>
						</div>

                        <p>
                        <p>
                    </div>
                    <div class="panel-body">

                    	


						<nav>
						  <ul class="pagination">
						    <li>
						      <a href="javascript:paginacao(1);" aria-label="Previous">
						        <span aria-hidden="true">&laquo;</span>
						      </a>
						    </li>
		                    <?php for ($i = 1; $i <= $_SESSION["qtdepagina"]; $i++) { ?>

	                    		<li><a href="javascript:paginacao(<?php echo $i ?>);"><?php echo $i ?></a></li>
		            		<?php } ?>
            				<li>
						      <a href="javascript:paginacao(<?php echo $_SESSION["qtdepagina"] ?>);" aria-label="Next">
						        <span aria-hidden="true">&raquo;</span>
						      </a>
						    </li>
            			  </ul>
            			</nav>
                    <div id="table-loading"></div>
         			<table id="pedidos" class="table table-hover table-striped table-bordered">
                       	<?php foreach($_SESSION["listaPedidos"] as $pedido){?>
                        	<tr>
                                <td>
                                    <table id="table1"  class="table">
                                        <tr class="info">
                                            <td>
                                                <table id="table2" class="table table-hover table-striped">
                                                    <thead class="headpedido">
                                                        <tr class=" info">
                                                            <td><center>CÓDIGO</center></td>
                                                            <td><center>DATA</center></td>
                                                            <td><center>CLIENTE</center></td>
                                                            <td><center>VALOR TOTAL</center></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="info">
                                                            <td><center><?php echo $pedido["CodPedido"] ?></center></td>
                                                            <td><center><?php $data = strtotime($pedido["DataHoraCriacao"]); echo date("d/m/Y G:i", $data); ?></center></td>
                                                            <td><center><?php echo $pedido["ClienteNome"] ?></center></td>
                                                            <td><center>R$ <?php echo number_format($pedido["TotalPedido"], 2, ',', '.'); ?></center></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr class="warning">
                                            <td>
                                            	<table id="table3"  class="table">
			                                        <tr class="warning">
			                                            <td>
			                                                <table id="table4" class="table table-hover">
			                                                    <thead class="headtabela">
			                                                        <tr class="warning">
				                                                        <td><center>TRANSAÇÃO</center></td>
			                                                            <td><center>STATUS</center></td>
			                                                            <td><center>DATA</center></td>
			                                                            <td><center>OPERADORA</center></td>
			                                                            <td><center>FORMA</center></td>
			                                                            <td><center>PARCELAS</center></td>
			                                                            <td><center>VALOR</center></td>
			                                                            <td><center></center></td>
			                                                        </tr>
			                                                    </thead>
			                                                    <tbody>
                                                    
			                                                   		<?php foreach($pedido["listaPagamentos"] as $transacao){ ?>
			                                                        	<tr class="warning">
			                                                                <td><center>
				                                                                <?php switch ($transacao['IdOperadora']) {
				                                                                    case 1: //cielo
				                                                                        if (array_key_exists("TidTransacao", $transacao)) 
				                                                                            echo "{$transacao['TidTransacao']}";
				                                                                        else echo "--";
				                                                                        break;
				                                                                    case 2: //rede
				                                                                        if (array_key_exists("NumSequencialRede", $transacao)) 
				                                                                            echo "{$transacao['NumSequencialRede']}";
				                                                                        else echo "--";
				                                                                        break;
				                                                                    default:
				                                                                        # code...
				                                                                        break;
				                                                                } ?>
				                                                                	</center></td>
				                                                            <td><center><?php switch ($transacao['StatusTransPag']) {
				                                                                    case 0:
				                                                                        echo "Pendente"; 
				                                                                        break;
				                                                                    case 1:
				                                                                        echo "Autenticada";
				                                                                        break;
				                                                                    case 2:
				                                                                        echo "Não Autenticada";
				                                                                        break;
				                                                                    case 3:
				                                                                        echo "Autorizada";
				                                                                        break;
				                                                                    case 4:
				                                                                        echo "Não Autorizada";
				                                                                        break;
				                                                                    case 5:
				                                                                        echo "Capturada";
				                                                                        break;
				                                                                    case 6:
				                                                                        echo "Cancelada";
				                                                                        break;
				                                                                    case 7:
				                                                                        echo "Indefinida";
				                                                                        break;
				                                                                    
				                                                                } ?></center></td>  
				                                                            <td><center>
			                                                                	<?php 
			                                                                		switch ($transacao["StatusTransPag"]) {
		                                                                                case 1: //AUTENTICAÇÃO
		                                                                                	$data = strtotime($transacao["DataRetornoAutenticacao"]); 
		                                                                                	echo date("d/m/Y G:i", $data);
		                                                                                    break;
		                                                                                case 2://AUTENTICAÇÃO
		                                                                                    $data = strtotime($transacao["DataRetornoAutenticacao"]); 
		                                                                                	echo date("d/m/Y G:i", $data);
		                                                                                    break;
		                                                                                case 3://AUTORIZAÇÃO
			                                                                                $data = strtotime($transacao["DataRetornoAutorizacao"]); 
		                                                                                	echo date("d/m/Y G:i", $data);
		                                                                                    break;
		                                                                                case 4://AUTORIZAÇÃO
			                                                                                $data = strtotime($transacao["DataRetornoAutorizacao"]); 
		                                                                                	echo date("d/m/Y G:i", $data);
		                                                                                    break;
		                                                                                case 5://CAPTURADA
		                                                                                	if ($transacao["DataRetornoCaptura"] != NULL){
		                                                                                		$data = strtotime($transacao["DataRetornoCaptura"]); 
			                                                                                	echo date("d/m/Y G:i", $data);
			                                                                                }
		                                                                                    break;
		                                                                                case 6://CANCELADA
		                                                                                	$data = strtotime($transacao["DataRetornoCancelamento"]); 
		                                                                                	echo date("d/m/Y G:i", $data);
		                                                                                    break;
		                                                                                default:
		                                                                                	echo "--"; 
			                                                                		} 
			                                                                    ?></center></td>  
			                                                                <td><center><?php echo "{$transacao['Operadora']}"; ?></center></td>	
			                                                                <td><center><?php echo "{$transacao['FormaPagamento']}"; ?></center></td>
			                                                                <td><center><?php echo "{$transacao['NumeroParcelasPedPag']}"; ?></center></td>
			                                                                <td align="right">R$ <?php echo number_format($transacao['ValorParcelaPedPag'], 2, ',', '.'); ?></td>
			                                                                <td><center>
			                                                                	<div id="btn-loading-<?php echo $transacao['Codigo']; ?>"></div>
			                                                                    <div class="btn-group" id="btn-<?php echo $transacao['Codigo']; ?>">
			                                                                        <?php if ($transacao["StatusTransPag"] == 7) { ?>
			                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
			                                                                                Aguarde retorno <span class="caret"></span>
			                                                                            </button>
			                                                                        <?php }else { ?>
			                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                                                                                Ações <span class="caret"></span>
			                                                                            </button>
			                                                                        <?php } ?>
			                                                                        
			                                                                        <ul class="dropdown-menu">
			                                                                            <?php switch ($transacao["StatusTransPag"]) {
			                                                                                case 0:
			                                                                                    ?>
			                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="startTransaction(this)">Autorizar</a></li>
			                                                                                    <?php
			                                                                                    
			                                                                                    break;
			                                                                                case 1:
			                                                                                    ?>

			                                                                                    <?php
			                                                                                    echo "Autenticada";
			                                                                                    break;
			                                                                                
			                                                                                case 2:
			                                                                                    ?>

			                                                                                    <?php
			                                                                                    echo "Não Autenticada";
			                                                                                    break;
			                                                                                case 3:
			                                                                                    ?>
			                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="captureTransaction(this)">Capturar</a></li>
			                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="requestCancelTransaction(this)">Cancelar</a></li>
			                                                                                        <li role="separator" class="divider"></li>
			                                                                            			<li><a role="button" data-toggle="collapse" href="#historico-<?php echo $transacao["Codigo"] ?>" aria-expanded="false" aria-controls="historico">
																									  Transações
																									</a></li>
			                                                                                    <?php
			                                                                                    break;
			                                                                                case 4:
			                                                                                    ?>
			                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="startTransaction(this)">Autorizar</a></li>
			                                                                                        <li role="separator" class="divider"></li>
			                                                                            			<li><a role="button" data-toggle="collapse" href="#historico-<?php echo $transacao["Codigo"] ?>" aria-expanded="false" aria-controls="historico">
																									  Transações
																									</a></li>
			                                                                                    <?php
			                                                                                    break;
			                                                                                case 5:
			                                                                                    ?>
			                                                                                        <li>
			                                                                                            <a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="requestCancelTransaction(this)">Cancelar</a>
			                                                                                        </li>
			                                                                                        <li role="separator" class="divider"></li>
			                                                                            			<li><a role="button" data-toggle="collapse" href="#historico-<?php echo $transacao["Codigo"] ?>" aria-expanded="false" aria-controls="historico">
																									  Transações
																									</a></li>
			                                                                                    <?php
			                                                                                    break;
			                                                                                case 6:
			                                                                                    ?>
			                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="startTransaction(this)">Autorizar</a></li>
			                                                                                        <li role="separator" class="divider"></li>
			                                                                            			<li><a role="button" data-toggle="collapse" href="#historico-<?php echo $transacao["Codigo"] ?>" aria-expanded="false" aria-controls="historico">
																									  Transações
																									</a></li>
			                                                                                    <?php
			                                                                                    break;
			                                                                            } ?>
			                                                                            
			                                                                            

			                                                                        </ul>
			                                                                    </div>
			                                                                	</center>
			                                                                </td>
			                                                            </tr>
			                                                            <tr class="success " >
								                                            <td class="zero" colspan="8" ><div id="historico-<?php echo $transacao["Codigo"] ?>" class="collapse">
								                                            	<table id="table5" class="table table-hover sucess">
								                                                    <thead class="headhistorico">
								                                                        <tr class="success">
								                                                            <td><center>CÓD. TRANSAÇÃO</center></td>
								                                                            <td><center>OPERADORA</center></td>
								                                                            <td><center>DATA AUTORIZAÇÃO</center></td>
								                                                            <td><center>DATA CAPTURA</center></td>
								                                                            <td><center>DATA CANCELAMENTO</center> </td>
								                                                            <td><center>
								                                                            	<a data-toggle="collapse" href="#historico-<?php echo $transacao["Codigo"] ?>" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span></a>
								                                                            	</center></td>
								                                                        </tr>
								                                                    </thead>
								                                                    <tbody>
								                                                    	<?php foreach($transacao["listaHistorico"] as $historico){ ?>
								                                                    		<tr class="success">
									                                                    		<td><center><?php echo $historico["CodigoTransacao"]; ?></center></td>
									                                                    		<td><center><?php echo $historico["NomeOperadora"]; ?></center></td>
									                                                    		<td><center><?php if ($historico["DataAutorizacao"] != NULL) echo date("d/m/y G:i", strtotime($historico["DataAutorizacao"])) ; ?></center></td>
									                                                    		<td><center><?php if ($historico["DataCaptura"] != NULL) echo date("d/m/y G:i", strtotime($historico["DataCaptura"])) ; ?></center></td>
									                                                    		<td><center><?php if ($historico["DataCancelamento"] != NULL) echo date("d/m/y G:i", strtotime($historico["DataCancelamento"])) ; ?></center></td>
									                                                    		<td></td>
									                                                    	</tr>
							                                                    		<?php } ?>	 
								                                                    	
								                                                    	
								                                                    </tbody>
								                                                </table></div>
								                                            </td>
								                                        </tr>
			                                                        <?php } ?>
			                                                    </tbody>
			                                                </table>
			                                            </td>
			                                        </tr>
			                                        
			                                    </table>
			                                </td>
			                            </tr>
                                    </table>
                                </td>
                            </tr>
						<?php } ?>
                    </table>
                    </div>
                    <p>
                    <p>
                </div>
                <div>
                    <br>
                    Obs.: O prazo máximo para realizar a captura é de 5 dias corridos após a data da autorização na CIELO e de 30 dias corridos na REDE. Se não for capturada dentro do prazo o sistema cancelará automaticamente.
                    
                </div>
            </div>
        </div>
        
	</body>
	
</html>