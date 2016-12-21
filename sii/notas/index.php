
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<title>Área administrativa Cielo</title>
	<head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    
        <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../bibliotecas/DataTables-1.10.2/media/css/jquery.dataTables.css" />

		<script type="text/javascript" language="javascript" src="../bibliotecas/DataTables-1.10.2/media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../bibliotecas/DataTables-1.10.2/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="../js/popover.js"></script>
        <script type="text/javascript" language="javascript" src="../js/tooltip.js"></script>      
        <script type="text/javascript" language="javascript">
			
		$(document).ready( function() {
			$('#pedidos').dataTable( {
				"oLanguage": {
					"sProcessing": "Aguarde enquanto os dados são carregados ...",
					"sLengthMenu": "Mostrar _MENU_ registros por página",
					"sZeroRecords": "Nenhum registro corresponde ao critério inserido",
					"sInfoEmtpy": "Exibindo 0 a 0 de 0 registros",
					"sInfo": "Exibindo de _START_ a _END_ de _TOTAL_ registros",
					"sInfoFiltered": "",
					"sSearch": "Procurar",
					"oPaginate": {
					   "sFirst":    "Primeiro",
					   "sPrevious": "Anterior",
					   "sNext":     "Próximo",
					   "sLast":     "Último"
					}
				},
				"aaSorting": [[0, 'desc']],
				"aoColumnDefs": [
					{ "bSearchable": false, "aTargets": [ 5,8,10] }
			] } );
		} );
		
		function autorizar(id){
			var valores = id.split("#");
			var numeroC = valores[0];
			var vencimentoC = valores[1];
			var indicador = valores[2];
			var codSegC = valores[3];
			var titularC = valores[4];
			var id = valores[5];
			var valor = valores[6];
			var dataHoraPedido = valores[7];
			var bandeira = valores[8];
			var produto = valores[9];
			var qntdeParcelas = valores[10];
			var codigoPedido = valores[11];
			
			document.getElementById(id).style.display = "none";
			document.getElementById(codigoPedido).style.display = "block";

			$.post('include/intermCielo.php', {'acao':"autorizar", 'codigoPedido':codigoPedido, 'numeroC':numeroC, 'vencimentoC':vencimentoC, 'indicador':indicador, 'codSegC':codSegC, 'titularC':titularC, 'id':id, 'valor':valor, 'dataHoraPedido':dataHoraPedido, 'bandeira':bandeira, 'produto':produto, 'qntdeParcelas':qntdeParcelas}, function(retorno){
				if (retorno[1] == "1") {
					document.location.href = "listapedidos.php";
				} else {
					alert(retorno);
					document.getElementById(codigoPedido).style.display = "none";
					document.getElementById(id).style.display = "block";
				}
			});
		}
			
		function capturar(id){
			var valores = id.split("-");
			var id = valores[0];
			var codigoPedido = valores[1];
			var tid = valores[2];
			var valor = valores[3];
			
			document.getElementById(id).style.display = "none";
			document.getElementById(codigoPedido).style.display = "block";

			$.post('include/intermCielo.php', {'acao':"capturar", 'id':id, 'codigoPedido':codigoPedido, 'tid':tid, 'valor':valor}, function(retorno){
				if (retorno[1] == "1") {
					document.location.href = "listapedidos.php";
				} else {
					alert(retorno);
					document.getElementById(codigoPedido).style.display = "none";
					document.getElementById(id).style.display = "block";
				}
			});
		}
		
		function cancelar(id){
			var valores = id.split("-");
			var id = valores[0];
			var tid = valores[1];
			var codigoPedido = valores[2];
			var codTran = valores[3];
			var statusTran = valores[4];
			
			document.getElementById(id).style.display = "none";
			document.getElementById(codigoPedido).style.display = "block";

			$.post('include/intermCielo.php', {'acao':"cancelar", 'id':id, 'codigoPedido':codigoPedido, 'tid':tid, 'codTran':codTran, 'statusTran':statusTran}, function(retorno){
				if (retorno[1] == "1") {
					document.location.href = "listapedidos.php";
				} else {
					alert(retorno);
					document.getElementById(codigoPedido).style.display = "none";
					document.getElementById(id).style.display = "block";
				}
			});
		}
		
		function cancelarSistema(id){
			var valores = id.split("-");
			var id = valores[0];
			var codigoPedido = valores[1];
			
			document.getElementById(id).style.display = "none";
			document.getElementById(codigoPedido).style.display = "block";

			$.post('include/intermCielo.php', {'acao':"cancelarSistema", 'id':id}, function(retorno){
				if (retorno[1] == "1") {
					document.location.href = "listapedidos.php";
				} else {
					alert("Não foi possível cancelar o pedido, tente novamente.");
					document.getElementById(codigoPedido).style.display = "none";
					document.getElementById(id).style.display = "block";
				}
			});
		}
		
		function atualizar(id){
			var valores = id.split("-");
			var id = valores[0];
			var tid = valores[1];
			var codigoPedido = valores[2];
			var codTran = valores[3];
			var statusTran = valores[4];
			
			document.getElementById(id).style.display = "none";
			document.getElementById(codigoPedido).style.display = "block";

			$.post('include/intermCielo.php', {'acao':"atualizar", 'id':id, 'codigoPedido':codigoPedido, 'tid':tid, 'codTran':codTran, 'statusTran':statusTran}, function(retorno){
				if (retorno[1] == "1") {
					document.location.href = "listapedidos.php";
				} else {
					alert(retorno);
					document.getElementById(codigoPedido).style.display = "none";
					document.getElementById(id).style.display = "block";
				}
			});
		}
		
		function email(id){
			var valores = id.split("#");
			var codigoPedido = valores[0];
			var clienteEmail = valores[1];
			var clienteNome = valores [2];
			var statusTran = valores [3];
			var id = valores [4];
			
			document.getElementById(id).style.display = "none";
			document.getElementById(codigoPedido).style.display = "block";

			$.post('include/intermCielo.php', {'acao':"email",'codigoPedido':codigoPedido, 'clienteEmail':clienteEmail, 'clienteNome':clienteNome, 'statusTran':statusTran}, function(retorno){
				alert(retorno);
				document.getElementById(codigoPedido).style.display = "none";
				document.getElementById(id).style.display = "block";
			});
		}
		
		function mostrarPopover(id){
    		$('#'+id).popover('show');
		}
		
		function esconderPopover(id){
    		$('#'+id).popover('hide');
		}
		
		</script>
        
	</head>
	<body>
    	<br>
        <div class="container">
        <div class="panel-group" id="accordion">
        <div class="panel panel-info">
        <div class="panel-heading">
        	<p>
        	<table width="1120">
            	<tr>
                	<td>
                        <h4 class="panel-title" id="resize_custa">
                        <font size="+3">
                            LISTA DE PEDIDOS
                            </font>
                        </h4>
                    </td>
                    <td  align="right">
                    	<button type="button" class="btn btn-primary" onClick="javascript: location.href='logout.php';">Sair</button>
                    </td>
                </tr>
            </table>
            <p>
        </div>
        <p>
        <p>
		
		
    	<table id="pedidos" class="table table-striped table-bordered table-hover">
   			<thead>
            	<br>
        		<tr>
                	<td>Codigo Pedido</td>
                    <td>Data Pedido</td>
                    <td>Cliente</td>
                    <td>Cpf/Cnpj</td>
                    <td>Forma Pagamento</td>
                    <td>Parcelas</td>
                    <td>Valor</td>
                    <td>Número da transação</td>
                    <td>Data da transação</td>
                    <td>Status da transação</td>
                    <td>Ação</td>
                </tr>
			</thead>
            <tbody>
    		
				<tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>
                <tr>
					<td><center>090909</center></td>
                    <td><center>09/06/2016 08:22</center></td>
					<td><center>Daniel Nadson</center></td>
					<td><center>11402065671</center></td>
                    <td><center>Á vista</center></td>
                    <td><center>01</center></td>
                    <td align="right">R$ 80,00</td>
                    <td><center>1234654897</center></td>
                    <td><center>09/06/2016 08:55</center></td>  
					<td><center>Aguardando Envio</center></td>
				
					<td><div id="" style="display:block;"><button  type="button" class="btn btn-warning btn-xs" id="" onClick="autorizar(id)">Enviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button></div>
                    <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
                </tr>

<!--					
						<td><div id="" style="display:block;"><button type="button" class="btn btn-info btn-xs" id="" onClick="atualizar(id)">Atualizar</button></div>
                        <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
						
						<td><div id="" style="display:block;"><button type="button" class="btn btn-success btn-xs" id="" onClick="capturar(id)">Capturar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelar(id)">Cancelar</button><p><p><button type="button" class="btn btn-default btn-xs" id="" onClick="email(id)">Email</button></div>
                        <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
						
						<td><div id="" style="display:block;"><button type="button" class="btn btn-primary btn-xs" id="" onClick="autorizar(id)">Reenviar</button><p><p><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelarSistema(id)">Cancelar</button><p><p><button type="button" class="btn btn-default btn-xs" id="" onClick="email(id)">Email</button></div>
                        <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
						
						<td><div id="" style="display:block;"><button type="button" class="btn btn-danger btn-xs" id="" onClick="cancelar(id)">Cancelar</button></div>
                        <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
						
						<td><div id="" style="display:block;"><button type="button" class="btn btn-default btn-xs" id="" onClick="email(id)">Email</button></div>
                        <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
						
						<td><div id="" style="display:block;"><button type="button" class="btn btn-info btn-xs" id="" onClick="atualizar(id)">Atualizar</button></div>
                        <div id="" style="display:none;"><img src="imagem/carregando.gif" width=90% height=90%></div></td>
						
					</tr>-->
                    
					
            </tbody>
		</table>
        <p>
        <p>
        </div>
        <div>
		<br>
        Obs.: O prazo máximo para realizar a captura é de 5 dias corridos após a data da autorização, se não for capturada dentro do prazo o sistema cancelará automaticamente.
        </div>
        </div>
        </div>
        
	</body>
	
</html>