<?php
 
 set_error_handler(function($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
 });
	class BancoODBC{
		private $alias_odbc;
		private $host_odbc;
		private $banco_odbc;
		private $user_odbc;
		private $senha_odbc;
		private $conexao_odbc;
		private $status_conexao = false;
		private $status_login = false;
		
		
		public function getStatusConexao(){
			try { 
				$this->connectDinamic(); 
			} catch (Exception $e) { 
				throw $e; 
			} 
		}
		
		public function connect(){
			$this->alias_odbc = "VarejoWeb";
			$this->user_odbc = "odbc";
			$this->senha_odbc = "odbc";
			
			try{
				$this->status_conexao = true;
				$this->conexao_odbc = odbc_connect($this->alias_odbc,'odbc','odbc');
				//echo "CONECTADO COM SUCESSO";
			}
			catch(Exception $e){
				$this->status_conexao = false;
				//echo "FALHA NA CONEXÃO ODBC: " .$e->getMessage();
				throw $e; 
			}
		}
		
		public function connectDinamic(){


			$this->alias_odbc = 	"DRIVER={FileMaker ODBC};
									 SERVER=". $this->host_odbc .";
									 DATABASE=" . $this->banco_odbc.";
									 USELONGVARCHAR=Yes;
									 AUTODETECTENCODING=No;
									 MULTIBYTEENCODING=UTF-8";
			$this->user_odbc = "odbc";
			$this->senha_odbc = "odbc";
			
			try{
				$this->status_conexao = true;
				$this->conexao_odbc = odbc_connect('plander_x', $this->user_odbc, $this->senha_odbc);
				//echo "CONECTADO COM SUCESSO";
			}
			catch(Exception $e){
				$this->status_conexao = false;
				//echo "FALHA NA CONEXÃO ODBC: " .$e->getMessage();
				throw $e; 
			}
		}
		
		public function selectTeste(){
			try {
				$this->getStatusConexao();
				$sql = "select Cliente.Id, Cliente.Telefone, Cliente.Cpf from Cliente where Cliente.Telefone = '99161059'";
				$qry = odbc_exec($this->conexao_odbc, $sql);			
				
				$x=0;
				while($rs = odbc_fetch_array($qry)){
					$x++;	
					//echo "<br>Ids: ".$rs['Id'];
				}
			}catch(Exception $e){
				//echo "<br />FALHA DE SELEÇÃO<br />";
			}
		}
		
		public function buscarDadosERPIbolt($host, $banco, $user, $senha){
			$this->host_odbc = $host;
			$this->banco_odbc = $banco;
			$this->user_odbc = $user;
			$this->senha_odbc = $senha;
			
			try {
				$this->getStatusConexao();
				$sql = "SELECT Pedido.Codigo AS CodPedido, Pedido.Data AS DataPedido, Pedido.ValorFinal AS TotalPedido, Pedido.NumeroParcelas AS 
							NumParcelasPedido, Pedido.Status AS StatusPedido,
							Pedido.CodigoCliente, Pedido.ClienteNome, Pedido.ClienteCpf,
							Pedido.EntregaCep, Pedido.EntregaRua, Pedido.EntregaNumero, Pedido.EntregaComplemento, Pedido.EntregaBairro, 
							Pedido.EntregaMunicipio, Pedido.EntregaUf, Pedido.CartaoTitular, Pedido.CartaoNumero, Pedido.CartaoValidade, 
							Pedido.CartaoCodigoSeguranca, Pedido.ClienteCliente,	FormaPagamento.Nome AS FormaPagamento, 
							FormaPagamento.CodigoFormaPagamentoIboltPag AS TipoPagamento, PedidoPagamento.Codigo, PedidoPagamento.Parcelas AS 
							NumeroParcelasPedPag, PedidoPagamento.ValorParcela AS ValorParcelaPedPag, PedidoPagamento.StatusIboltPag AS StatusTransPag
						FROM Pedido 
							INNER JOIN PedidoPagamento ON Pedido.Codigo = PedidoPagamento.CodigoPedido	
							INNER JOIN FormaPagamento ON FormaPagamento.Codigo = PedidoPagamento.CodigoFormaPagamento
						WHERE (Pedido.Codigo > 41182 AND Pedido.Status = 'Pendente')";
				
				$result = @odbc_exec($this->conexao_odbc, $sql);
				
				$_SESSION["listaPagamentos"] = array();  
				while($row = @odbc_fetch_array($result)){  
					array_push($_SESSION["listaPagamentos"], $row);
					  
				}
				
			}catch(Exception $e){
				//echo "<br />FALHA DE SELEÇÃO<br />";
				throw $e;
			}
		}
		
		
		//ESTA FUNÇÃO BUSCA OS PEDIDOS 'PENDENTES' NO SISTEMA DO CLIENTE
		public function buscarListaPedidosPendentes($host, $banco, $user, $senha){
			$this->host_odbc = $host;
			$this->banco_odbc = $banco;
			$this->user_odbc = $user;
			$this->senha_odbc = $senha;
			
			try {
				$this->getStatusConexao();
				$sql2 = "SELECT Pedido.Codigo AS CodPedido, Pedido.Data AS DataPedido, Pedido.ValorFinal AS TotalPedido, Pedido.Status AS StatusPedido,
								Pedido.CodigoCliente, Pedido.ClienteNome, Pedido.ClienteCpf, Pedido.EntregaCep, Pedido.EntregaRua, Pedido.EntregaNumero, 									
								Pedido.EntregaComplemento, Pedido.EntregaBairro, 
								Pedido.EntregaMunicipio, Pedido.EntregaUf, Pedido.CartaoTitular, Pedido.CartaoNumero, Pedido.CartaoValidade, 
								Pedido.CartaoCodigoSeguranca, Pedido.DataHoraCriacao
						FROM Pedido 
								INNER JOIN PedidoPagamento ON PedidoPagamento.CodigoPedido = Pedido.Codigo
						WHERE Pedido.Codigo > 42800 AND Pedido.Status = 'Pendente' AND Pedido.Loja = 'Virtual'";

				$sql = "SELECT Pedido.Codigo AS CodPedido, Pedido.Data AS DataPedido, Pedido.ValorFinal AS TotalPedido, Pedido.Status AS StatusPedido,
								Pedido.CodigoCliente, Pedido.ClienteNome, Pedido.ClienteCpf, Pedido.EntregaCep, Pedido.EntregaRua, Pedido.EntregaNumero, 									
								Pedido.EntregaComplemento, Pedido.EntregaBairro, 
								Pedido.EntregaMunicipio, Pedido.EntregaUf, Pedido.CartaoTitular, Pedido.CartaoNumero, Pedido.CartaoValidade, 
								Pedido.CartaoCodigoSeguranca, Pedido.DataHoraCriacao
						FROM Pedido 
						WHERE Pedido.Codigo > 42800 AND Pedido.Status = 'Pendente' AND Pedido.Loja = 'Virtual'";
				
				$result = @odbc_exec($this->conexao_odbc, $sql);
				
				$_SESSION["listaPedidos"] = array();  
				while($row = @odbc_fetch_array($result)){  
					array_push($_SESSION["listaPedidos"], $row);
				}
				
			}catch(Exception $e){
				//echo "<br />FALHA DE SELEÇÃO<br />";
				throw $e;
			}
		}
		
		//ESTA FUNÇÃO BUSCA OS DADOS DOS CLIENTE QUE NÃO ESTÃO NA TABELA PEDIDOS E AS TRANSAÇÕES PARA PAGAMENTO DE CADA PEDIDO
		public function buscarListaPedidosPagamento($pedido){
			if ($pedido['CodigoCliente'] != ""){				
				try {
					$this->getStatusConexao();
					$sql = "SELECT Cliente.Codigo, Cliente.Cpf, Cliente.Nome FROM Cliente WHERE Cliente.Codigo = " . $pedido['CodigoCliente'];
					$result = @odbc_exec($this->conexao_odbc, $sql);
					while($row = @odbc_fetch_array($result)){  
						$pedido['ClienteNome'] = $row['Nome'];
						$pedido['ClienteCpf'] = $row['Cpf'];
					}
				}catch(Exception $e){
					//echo "<br />FALHA AO BUSCAR DADOS DO CLIENTE<br />";
					throw $e;	
				}
			}

			try {
				$this->getStatusConexao();
				$sql = "SELECT 	PedidoPagamento.Codigo, PedidoPagamento.CodigoPedido, PedidoPagamento.Parcelas AS NumeroParcelasPedPag, PedidoPagamento.Valor AS ValorParcelaPedPag, 		
								PedidoPagamento.StatusIboltPag AS StatusTransPag, 
								FormaPagamento.Nome AS FormaPagamento, FormaPagamento.CodigoFormaPagamentoIboltPag AS TipoPagamento,
								Pedido.CartaoTitular, Pedido.CartaoNumero, Pedido.CartaoValidade, Pedido.CartaoCodigoSeguranca,
								FormaPagamento.Taxa
						FROM PedidoPagamento 
							INNER JOIN FormaPagamento ON FormaPagamento.Codigo = PedidoPagamento.CodigoFormaPagamento
							INNER JOIN Pedido ON Pedido.Codigo = PedidoPagamento.CodigoPedido
						WHERE PedidoPagamento.CodigoPedido = " . $pedido["CodPedido"] ;
						//echo "------  $sql <br>";
				$result = @odbc_exec($this->conexao_odbc, $sql);
				$listaPagamentos = array();
				while($row = @odbc_fetch_array($result)){  
					array_push($listaPagamentos, $row);
				}
				$pedido["listaPagamentos"] = $listaPagamentos;
				return $pedido;

			}catch(Exception $e){
				//echo "<br />FALHA DE SELEÇÃO<br />";
				throw $e;
			}
		}
		
		
		
		public function atualizarPedidoPagamento($transacao){
					
				try {
					$this->getStatusConexao();
					$sql = "UPDATE PedidoPagamento SET StatusIboltPag = '" . $transacao->getStatusGeral() . "' WHERE PedidoPagamento.Codigo = " . $transacao->getCodPedidoPagamento();
					//echo $sql;
					$result = @odbc_exec($this->conexao_odbc, $sql);
					
				}catch(Exception $e){
					//echo "<br />FALHA AO BUSCAR DADOS DO CLIENTE<br />";
					throw $e;	
				}
			
		}
	}
?>