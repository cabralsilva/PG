<?php
	session_start();
    //date_default_timezone_set('America/Sao_Paulo');
    //print_r($_SESSION);
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
        <script src="controles.js"></script>
        <script type="text/javascript">
            $('.dropdown-toggle').dropdown();

        </script>
    	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <link href="../css/style.css" rel="stylesheet" type="text/css">
        <br>
        <div class="container">
            <div class="panel-group" id="accordion">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <p>
                        <table width="1120" class="table">
                            <tr>
                                <td>
                                    <h4 class="panel-title" id="resize_custa">
                                        <font size="+3">
                                            <?php echo $_SESSION["dados_acesso"][0]["NOME"] ?>
                                        </font>
                                    </h4>
                                    <h2>
                                        <font size="+1">
                                            LISTA DE PEDIDOS
                                        </font>
                                    </h2>
                                </td>
                                <td align="right" class="text-top">
                                    Funcionário logado: <?php echo $_SESSION["dados_acesso"][0]["nome_usuario"] ?></br>
                                    <button type="button" class="btn btn-primary" onClick="javascript: location.href='logout.php';">Sair</button>
                                </td>
                            </tr>
                        </table>
                        <p>
                    </div>
                    <p>
         			<table id="pedidos" class="table table-hover table-striped table-bordered">
                       
                       
                       	<?php foreach($_SESSION["listaPedidos"] as $pedido){?>
                                <?php //print_r($pedido) ?>
                        	<tr>
                                <td>
                                    <table id="table1"  class="table">
                                        <tr class="info">
                                            <td>
                                                <table id="table2" class="table">
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
                                                            <td><center><?php $data = strtotime($pedido["DataHoraCriacao"]); echo date("d-M-y G:i:s", $data); ?></center></td>
                                                            <td><center><?php echo $pedido["ClienteNome"] ?></center></td>
                                                            <td><center>R$ <?php echo $pedido["TotalPedido"] ?></center></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr class="warning">
                                            <td>
                                                <table id="table3" class="table table-hover">
                                                    <thead class="headtabela">
                                                        <tr class="warning">
                                                            <td><center>FORMA PAGAMENTO</center></td>
                                                            <td><center>VALOR</center></td>
                                                            <td><center>PARCELAS</center></td>
                                                            <td><center>OPERADORA</center></td>
                                                            <td><center>STATUS TRANSAÇÃO</center></td>
                                                            <td><center>CÓD. TRANSAÇÃO</center></td>
                                                            <td><center>DATA</center></td>
                                                            <td><center></center></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    
                                                   		<?php foreach($pedido["listaPagamentos"] as $transacao){ ?>
                                                             <?php //print_r($transacao) ?>
                                                        	<tr class="warning">
                                                                
                                                                <td><center><?php echo "{$transacao['FormaPagamento']}"; ?></center></td>
                                                                <td><center>R$ <?php echo "{$transacao['ValorParcelaPedPag']}"; ?></center></td>
                                                                <td><center><?php echo "{$transacao['NumeroParcelasPedPag']}"; ?></center></td>
                                                                <td><center><?php echo "{$transacao['Operadora']}"; ?></center></td>

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
                                                                <td><center><?php if ($transacao["DataRetorno"] != NULL){
                                                                    $data = strtotime($transacao["DataRetorno"]); echo date("d-M-y G:i:s", $data); 
                                                                    } ?></center></td>
                                                                <td><center>
                                                                <div id="response"></div>
                                                                    <div class="btn-group" id="<?php echo $transacao['Codigo']; ?>">
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
                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="startTransaction(this)">Enviar</a></li>
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
                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="captureTransaction(this)">Capturar/Confirmar</a></li>
                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="requestCancelTransaction(this)">Cancelar/Estornar</a></li>
                                                                                        <li role="separator" class="divider"></li>
                                                                            			<li><a href="#">Transações</a></li>
                                                                                    <?php
                                                                                    break;
                                                                                case 4:
                                                                                    ?>
                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="startTransaction(this)">Enviar Novamente</a></li>
                                                                                        <li role="separator" class="divider"></li>
                                                                            			<li><a href="#">Transações</a></li>
                                                                                    <?php
                                                                                    break;
                                                                                case 5:
                                                                                    ?>
                                                                                        <li>
                                                                                            <a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="requestCancelTransaction(this)">Cancelar/Estornar</a>
                                                                                        </li>
                                                                                        <li role="separator" class="divider"></li>
                                                                            			<li><a href="#">Transações</a></li>
                                                                                    <?php
                                                                                    break;
                                                                                case 6:
                                                                                    ?>
                                                                                        <li><a data-pedido="<?php echo implode(',', $pedido); ?>" data-transacao="<?php echo implode(',', $transacao); ?>" data-toggle="dropdown" href="#" onClick="startTransaction(this)">Enviar Novamente</a></li>
                                                                                        <li role="separator" class="divider"></li>
                                                                            			<li><a href="#">Transações</a></li>
                                                                                    <?php
                                                                                    break;
                                                                            } ?>
                                                                            
                                                                            <li><a role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
																				  Link with href
																				</a></li>

                                                                        </ul>
                                                                    </div>
                                                                	</center></td>
                                                            </tr>
                                                            <tr id="collapseExample" class="collapse success">
                                                            	<td>
                                                            		<table id="table4" class="table table-hover">
					                                                    <thead class="headtabela">
					                                                        <tr class="warning">
					                                                            <td><center>ID TRANSAÇÃO</center></td>
					                                                            <td><center>FORMA PAGAMENTO</center></td>
					                                                            <td><center>OPERADORA</center></td>
					                                                            <td><center>DATA AUTORIZAÇÃO</center></td>
					                                                            <td><center>DATA CAPTURA</center></td>
					                                                            <td><center>CANCELADA?</center></td>
					                                                            <td><center>DATA CANCELAMENTO</center></td>
					                                                        </tr>
					                                                    </thead>
					                                                    <tbody>
					                                                    	<tr>
					                                                    		<td><center>123</center></td>
					                                                    		<td><center>HiperCard Crédito</center></td>
					                                                    		<td><center>Rede</center></td>
					                                                    		<td><center>15-06-2016 9:20:45</center></td>
					                                                    		<td><center>15-06-2016 9:20:50</center></td>
					                                                    		<td><center>SIM</center></td>
					                                                    		<td><center>16-06-2016 8:15:22</center></td>
					                                                    	</tr>
					                                                    	<tr>
					                                                    		<td><center>123</center></td>
					                                                    		<td><center>HiperCard Crédito</center></td>
					                                                    		<td><center>Rede</center></td>
					                                                    		<td><center>15-06-2016 9:20:45</center></td>
					                                                    		<td><center>15-06-2016 9:20:50</center></td>
					                                                    		<td><center>SIM</center></td>
					                                                    		<td><center>16-06-2016 8:15:22</center></td>
					                                                    	</tr>
					                                                    	<tr>
					                                                    		<td><center>123</center></td>
					                                                    		<td><center>HiperCard Crédito</center></td>
					                                                    		<td><center>Rede</center></td>
					                                                    		<td><center>15-06-2016 9:20:45</center></td>
					                                                    		<td><center>15-06-2016 9:20:50</center></td>
					                                                    		<td><center>SIM</center></td>
					                                                    		<td><center>16-06-2016 8:15:22</center></td>
					                                                    	</tr>
					                                                    </tbody>
				                                                    </table>
                                                            </tr>
                                                        <?php } ?>
                                                        
                                                    </tbody>
                                                    
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
						<?php } ?>
                    </table>
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