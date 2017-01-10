<?php
	define("MYSQL_CONN_ERROR", "Unable to connect to database."); 
	// Ensure reporting is setup correctly 
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
	
	class BancoDados{
		private $nome_banco;
		private $nome_usuario;
		private $senha_banco;
		private $host_banco;
		private $conexao_banco;
		private $status_conexao = true;
		private $status_login = false;
		function __construct(){
			//$this->connect();
		}
		
		public function getStatusConexao(){
			try { 
				$this->connect(); 
				return $this->status_conexao;
			} catch (Exception $e) { 
				echo "Falha na Conexão com Base de Dados" .$e->getMessage(); 
				throw $e; 
				return false;
			} 
		}
		
		public function getStatusLogin(){
			return $this->status_login;
		}
		
		
		public function connect(){
			/*
			$this->nome_banco = "ibolt_empresa";
			$this->nome_usuario = "ibolt_empresa";
			$this->senha_banco = "empresa";
			$this->host_banco = "186.202.152.57:3306";
			*/
			$this->nome_banco = "sii";
			$this->nome_usuario = "root";
			$this->senha_banco = "root";
			$this->host_banco = "127.0.0.1:3306";
			
			try{
				
				$this->conexao_banco = new mysqli($this->host_banco,$this->nome_usuario,$this->senha_banco);
				$this->conexao_banco->set_charset("utf8");
				$this->conexao_banco->select_db($this->nome_banco);
				$this->status_conexao = true;
			}
			catch(mysqli_sql_exception $e){
				$this->status_conexao = false;
				throw $e; 
			}
		}
		
		public function login(){
			try {
				$this->getStatusConexao();
				// Recupera o login 
				$login = isset($_POST["email"]) ? addslashes(trim($_POST["email"])) : FALSE; 
				// Recupera a senha, a criptografando em MD5 
				//$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE;
				$senha = isset($_POST["senha"]) ? $_POST["senha"] : FALSE; 
				
				// Usuário não forneceu a senha ou o login 
				if(!$login || !$senha) $this->falhaLogin();
				
				
				/** 
				* Executa a consulta no banco de dados. 
				* Caso o número de linhas retornadas seja 1 o login é válido, 
				* caso 0 ou mais de 1, inválido. 
				*/ 
				$sql = "SELECT USUARIOS.id_usuario, USUARIOS.nome_usuario, USUARIOS.email_usuario, USUARIOS.senha_usuario FROM USUARIOS 
						WHERE USUARIOS.email_usuario = '" . $login . "'"; 
				$usuario = $this->conexao_banco->query($sql);
				//$usuario = @mysql_query($SQL) or die("Erro no banco de dados!"); 
				$total = $usuario->num_rows;
				//$total = @mysql_num_rows($usuario);
				// Caso o usuário tenha digitado um login válido o número de linhas será 1.. 
				if($total == 1){ 
					// Obtém os dados do usuário, para poder verificar a senha e passar os demais dados para a sessão 
					//$dados = @mysql_fetch_array($usuario); 
					$dados = $usuario->fetch_array(MYSQLI_ASSOC);
					
					// Agora verifica a senha 
					if(!strcmp($senha, $dados["senha_usuario"])){ 	
						// TUDO OK! Agora, passa os dados para a sessão e redireciona o usuário 
						//echo
						$_SESSION["id_usuario"]= $dados["id_usuario"]; 
						$_SESSION["nome_usuario"] = stripslashes($dados["nome_usuario"]); 
						$_SESSION["email_usuario"] = stripslashes($dados["email_usuario"]); 
						$this->status_login = true;
						return; 
					}else $this->falhaLogin(); 
				}else $this->falhaLogin();
			}
			catch (Exception $e) {
				$this->falhaLogin();
			}
		}
		
		private function falhaLogin(){
			unset( $_SESSION['id_usuario'] );
			unset( $_SESSION['nome_usuario'] );
			unset( $_SESSION['email_usuario'] );
			$_SESSION["status_login"] = true;
			header("Location: /sii");
			exit; 
		}
		
		public function verificarSistemasHabilitados(){
			try {
				$this->getStatusConexao();
// 				$sql = "SELECT EMPRESA.CODIGO, EMPRESA.NOME, EMPRESA.HOST_BANCO, EMPRESA.NOME_BANCO, EMPRESA.USER_BANCO, EMPRESA.SENHA_BANCO, SISTEMAS.*, USUARIOS.nome_usuario 
// 						FROM EMPRESA	
// 						INNER JOIN EMPRESA_SISTEMA ON EMPRESA.CODIGO = EMPRESA_SISTEMA.fk_empresa
// 						INNER JOIN SISTEMAS ON SISTEMAS.id_sistema = EMPRESA_SISTEMA.fk_sistema
// 						INNER JOIN USUARIO_EMPRESA_SISTEMA ON USUARIO_EMPRESA_SISTEMA.fk_empresa_sistema = EMPRESA_SISTEMA.id_empresa_sistema
// 						INNER JOIN USUARIOS ON USUARIOS.id_usuario = USUARIO_EMPRESA_SISTEMA.fk_usuario
// 						WHERE USUARIOS.id_usuario = " . $_SESSION["id_usuario"];
				$sql = "SELECT empresa.CODIGO, empresa.NOME, empresa.CNPJ, empresa.SENHA, EMPRESA.HOST_BANCO, EMPRESA.NOME_BANCO, EMPRESA.USER_BANCO, EMPRESA.SENHA_BANCO, sistemas.*, usuarios.nome_usuario  FROM empresa
						INNER JOIN empresa_sistema ON empresa.CODIGO = empresa_sistema.fk_empresa
						INNER JOIN sistemas ON sistemas.id_sistema = empresa_sistema.fk_sistema
						INNER JOIN usuario_empresa_sistema ON usuario_empresa_sistema.fk_empresa_sistema = empresa_sistema.id_empresa_sistema
						INNER JOIN usuarios ON usuarios.id_usuario = usuario_empresa_sistema.fk_usuario
						WHERE USUARIOS.id_usuario = " . $_SESSION["id_usuario"];
					
				echo $sql;
				$sistemas = $this->conexao_banco->query($sql);
				//$result = @mysql_query($SQL) or die("Erro no banco de dados!"); 
				
				// Percorre os registros retornados
				//session_start();
				$_SESSION["dados_acesso"] = array();
				$_SESSION["dados_empresa"] = array();
				
				while($linha = $sistemas->fetch_array(MYSQLI_ASSOC)){
					array_push($_SESSION["dados_acesso"], $linha);
					$_SESSION["dados_empresa"]["cod_empresa"] = $linha["CODIGO"];
					$_SESSION["dados_empresa"]["nome_empresa"] = $linha["NOME"];
					$_SESSION["dados_empresa"]["host_banco_empresa"] = $linha["HOST_BANCO"];
					$_SESSION["dados_empresa"]["nome_banco_empresa"] = $linha["NOME_BANCO"];
					$_SESSION["dados_empresa"]["user_banco_empresa"] = $linha["USER_BANCO"];
					$_SESSION["dados_empresa"]["senha_banco_empresa"] = $linha["SENHA_BANCO"];
					
					//echo $linha[0] . " - " . $linha[1] . " - " . $linha[2] . " - " . $linha[3] . "<br>";
				}
				// Libera o result set
				$sistemas->close();
				//mysql_free_result($result);
			}catch (Exception $e) {
				echo $e;
				//$this->falhaLogin();
			}
		}

		public function getBandeiraCartao($idForma){



			try {
				$this->getStatusConexao();
				$sql = "SELECT forma_pagamento.* FROM forma_pagamento WHERE forma_pagamento.id_forma_pagamento = " . $idForma;

				$result = $this->conexao_banco->query($sql);
				
				if ($result){
					$linha = $result->fetch_assoc();
				}
				$result->close();
				return $linha;
			}catch(Exception $e){
				$this->falhaLogin();
			}
		}
		
		public function setTransacao($transacao, $dadosEmpresa){
			//print_r($transacao);
			if ($transacao->getCodigoOperadora() == 1){
				if ($transacao->getCodigo_Erro_Retorno() == '' or $transacao->getCodigo_Erro_Retorno() == NULL){

					try {
						$this->getStatusConexao();
						if ($transacao->getUrl_Autenticacao_Retorno() == "") $transacao->setUrl_Autenticacao_Retorno('null'); 
						if ($transacao->getCodigo_Erro_Retorno() == "") $transacao->setCodigo_Erro_Retorno('null'); 
						if ($transacao->getMsg_Erro_Retorno() == "") $transacao->setMsg_Erro_Retorno('null'); 
						
						if ($transacao->getAutenticacao_Codigo_Retorno() == "") $transacao->setAutenticacao_Codigo_Retorno('null'); 
						if ($transacao->getAutorizacao_Codigo_Retorno() == "") $transacao->setAutorizacao_Codigo_Retorno('null'); 
						if ($transacao->getAutenticacao_Mensagem_Retorno() == "") $transacao->setAutenticacao_Mensagem_Retorno('null'); 
						if ($transacao->getAutorizacao_Mensagem_Retorno() == "") $transacao->setAutorizacao_Mensagem_Retorno('null'); 
						if ($transacao->getAutorizacao_Lr_Retorno() == "") $transacao->setAutorizacao_Lr_Retorno('null'); 
						if ($transacao->getAutenticacao_Eci_Retorno() == "") $transacao->setAutenticacao_Eci_Retorno('null'); 
						if ($transacao->getAutorizacao_Arp_Retorno() == "") $transacao->setAutorizacao_Arp_Retorno('null'); 
						if ($transacao->getAutorizacao_Nsu_Retorno() == "") $transacao->setAutorizacao_Nsu_Retorno('null'); 
						if ($transacao->getCancelamentoDataHoraRetorno() == "") $transacao->SetCancelamentoDataHoraRetorno(NULL); 
						//if ($transacao->getAutenticacao_Eci_Retorno() == "") $transacao->setAutenticacao_Eci_Retorno('null'); 



						$sql = "SELECT * FROM TRANSACAO WHERE tid_transacao_cielo = '{$transacao->getTid_Retorno()}'";

						$result = $this->conexao_banco->query($sql);
						

						if ($result->num_rows > 0) {
							if ($transacao->getStatusGeral() == 6){
								$sql = "UPDATE TRANSACAO SET 
									status_transacao_cielo = '{$transacao->getStatus_Retorno()}',
									pan_transacao_cielo = '{$transacao->getPan_Retorno()}',
									url_autenticacao_cielo = '{$transacao->getUrl_Autenticacao_Retorno()}',
									cod_retorno_autenticacao_cielo = {$transacao->getAutenticacao_Codigo_Retorno()},
									msg_retorno_autenticacao_cielo = '{$transacao->getAutenticacao_Mensagem_Retorno()}', 
									data_hora_retorno_autenticacao = '{$transacao->getAutenticacao_data_hora_Retorno()}',
									eci_autenticacao_cielo = {$transacao->getAutenticacao_Eci_Retorno()},
									cod_retorno_autorizacao_cielo = {$transacao->getAutorizacao_Codigo_Retorno()},
									msg_retorno_autorizacao_cielo = '{$transacao->getAutorizacao_Mensagem_Retorno()}', 
									data_hora_retorno_autorizacao = '{$transacao->getAutorizacao_Data_Hora_Retorno()}',
									data_hora_retorno_cancelamento = '{$transacao->getCancelamentoDataHoraRetorno()}',
									lr_autorizacao_cielo = {$transacao->getAutorizacao_Lr_Retorno()},
									arp_autorizacao_cielo = {$transacao->getAutorizacao_Arp_Retorno()},
									nsu_autorizacao_cielo = {$transacao->getAutorizacao_Nsu_Retorno()},
									cod_erro_retorno_cielo = '{$transacao->getCodigo_Erro_Retorno()}',
									msg_erro_retorno_cielo = '{$transacao->getMsg_Erro_Retorno()}',
									status_geral = {$transacao->getStatusGeral()},
									fk_operadora = {$transacao->getCodigoOperadora()}
								WHERE TRANSACAO.tid_transacao_cielo = '{$transacao->getTid_Retorno()}'";
							}else{
								$sql = "UPDATE TRANSACAO SET 
									status_transacao_cielo = '{$transacao->getStatus_Retorno()}',
									pan_transacao_cielo = '{$transacao->getPan_Retorno()}',
									url_autenticacao_cielo = '{$transacao->getUrl_Autenticacao_Retorno()}',
									cod_retorno_autenticacao_cielo = {$transacao->getAutenticacao_Codigo_Retorno()},
									msg_retorno_autenticacao_cielo = '{$transacao->getAutenticacao_Mensagem_Retorno()}', 
									data_hora_retorno_autenticacao = '{$transacao->getAutenticacao_data_hora_Retorno()}',
									eci_autenticacao_cielo = {$transacao->getAutenticacao_Eci_Retorno()},
									cod_retorno_autorizacao_cielo = {$transacao->getAutorizacao_Codigo_Retorno()},
									msg_retorno_autorizacao_cielo = '{$transacao->getAutorizacao_Mensagem_Retorno()}', 
									data_hora_retorno_autorizacao = '{$transacao->getAutorizacao_Data_Hora_Retorno()}',
									data_hora_retorno_captura = '{$transacao->getDataHoraCapturaRetorno()}', 
									lr_autorizacao_cielo = {$transacao->getAutorizacao_Lr_Retorno()},
									arp_autorizacao_cielo = {$transacao->getAutorizacao_Arp_Retorno()},
									nsu_autorizacao_cielo = {$transacao->getAutorizacao_Nsu_Retorno()},
									cod_erro_retorno_cielo = '{$transacao->getCodigo_Erro_Retorno()}',
									msg_erro_retorno_cielo = '{$transacao->getMsg_Erro_Retorno()}',
									status_geral = {$transacao->getStatusGeral()},
									fk_operadora = {$transacao->getCodigoOperadora()}
								WHERE TRANSACAO.tid_transacao_cielo = '{$transacao->getTid_Retorno()}'";
							}
							
						} else {
							if ($transacao->getStatusGeral() == 5){
								$sql = "insert into transacao
										(fk_pedido, fk_empresa, fk_operadora, valor_transacao, tid_transacao_cielo, status_transacao_cielo,
										pan_transacao_cielo, 
										data_hora_pedido, 
										url_autenticacao_cielo, 
										cod_retorno_autenticacao_cielo,
										msg_retorno_autenticacao_cielo, 
										data_hora_retorno_autenticacao, 
										eci_autenticacao_cielo, 
										cod_retorno_autorizacao_cielo,
										msg_retorno_autorizacao_cielo, 
										data_hora_retorno_autorizacao,
										data_hora_retorno_captura, 
										lr_autorizacao_cielo, 
										arp_autorizacao_cielo,
										nsu_autorizacao_cielo, 
										cod_erro_retorno_cielo,
										msg_erro_retorno_cielo, 
										forma_pgto_cielo, 
										qtde_parcelas, 
										fk_pedido_pagamento, 
										status_geral,
										fk_forma_pagamento,
										taxa,
										valor_liquido)
									values ({$transacao->getId_Pedido()}, 
										{$dadosEmpresa['cod_empresa']}, 
										{$transacao->getCodigoOperadora()}, 
										{$transacao->getDados_Pd_Valor()}, 
										'{$transacao->getTid_Retorno()}', 
										'{$transacao->getStatus_Retorno()}', 
										'{$transacao->getPan_Retorno()}',
										'{$transacao->getDados_Pd_Data_Hora_Retorno()}',
										'{$transacao->getUrl_Autenticacao_Retorno()}',
										{$transacao->getAutenticacao_Codigo_Retorno()}, 
										'{$transacao->getAutenticacao_Mensagem_Retorno()}',
										'{$transacao->getAutenticacao_data_hora_Retorno()}', 
										{$transacao->getAutenticacao_Eci_Retorno()}, 
										{$transacao->getAutorizacao_Codigo_Retorno()}, 
										'{$transacao->getAutorizacao_Mensagem_Retorno()}',
										'{$transacao->getAutorizacao_Data_Hora_Retorno()}',  
										'{$transacao->getDataHoraCapturaRetorno()}',  
										{$transacao->getAutorizacao_Lr_Retorno()}, 
										{$transacao->getAutorizacao_Arp_Retorno()}, 
										{$transacao->getAutorizacao_Nsu_Retorno()},
										'{$transacao->getCodigo_Erro_Retorno()}', 
										'{$transacao->getMsg_Erro_Retorno()}',
										'{$transacao->getDados_Forma_Pgto_Produto()}',
										{$transacao->getDados_Forma_Pgto_Parcelas()},
										{$transacao->getCodPedidoPagamento()},
										{$transacao->getStatusGeral()},
										{$transacao->getTipoFormaPagamento()},
										{$transacao->getTaxa()},
										{$transacao->getLiquido()}
										)";
							}else{
								$sql = "insert into transacao
										(fk_pedido, fk_empresa, fk_operadora, valor_transacao, tid_transacao_cielo, status_transacao_cielo,
										pan_transacao_cielo, 
										data_hora_pedido, 
										url_autenticacao_cielo, 
										cod_retorno_autenticacao_cielo,
										msg_retorno_autenticacao_cielo, 
										data_hora_retorno_autenticacao, 
										eci_autenticacao_cielo, 
										cod_retorno_autorizacao_cielo,
										msg_retorno_autorizacao_cielo, 
										data_hora_retorno_autorizacao,
										lr_autorizacao_cielo, 
										arp_autorizacao_cielo,
										nsu_autorizacao_cielo, 
										cod_erro_retorno_cielo,
										msg_erro_retorno_cielo, 
										forma_pgto_cielo, 
										qtde_parcelas, 
										fk_pedido_pagamento, 
										status_geral,
										fk_forma_pagamento,
										taxa,
										valor_liquido)
									values ({$transacao->getId_Pedido()}, 
										{$dadosEmpresa['cod_empresa']}, 
										{$transacao->getCodigoOperadora()}, 
										{$transacao->getDados_Pd_Valor()}, 
										'{$transacao->getTid_Retorno()}', 
										'{$transacao->getStatus_Retorno()}', 
										'{$transacao->getPan_Retorno()}',
										'{$transacao->getDados_Pd_Data_Hora_Retorno()}',
										'{$transacao->getUrl_Autenticacao_Retorno()}',
										{$transacao->getAutenticacao_Codigo_Retorno()}, 
										'{$transacao->getAutenticacao_Mensagem_Retorno()}',
										'{$transacao->getAutenticacao_data_hora_Retorno()}', 
										{$transacao->getAutenticacao_Eci_Retorno()}, 
										{$transacao->getAutorizacao_Codigo_Retorno()}, 
										'{$transacao->getAutorizacao_Mensagem_Retorno()}',
										'{$transacao->getAutorizacao_Data_Hora_Retorno()}', 
										{$transacao->getAutorizacao_Lr_Retorno()}, 
										{$transacao->getAutorizacao_Arp_Retorno()}, 
										{$transacao->getAutorizacao_Nsu_Retorno()},
										'{$transacao->getCodigo_Erro_Retorno()}', 
										'{$transacao->getMsg_Erro_Retorno()}',
										'{$transacao->getDados_Forma_Pgto_Produto()}',
										{$transacao->getDados_Forma_Pgto_Parcelas()},
										{$transacao->getCodPedidoPagamento()},
										{$transacao->getStatusGeral()},
										{$transacao->getTipoFormaPagamento()},
										{$transacao->getTaxa()},
										{$transacao->getLiquido()}
										)";
							}
							
						}
						
						//echo $sql;
						$result = $this->conexao_banco->query($sql);
						$this->conexao_banco->close();
					}catch(Exception $e){
						echo "Erro de Inserção: 1 - " .$e->getMessage();
					}
				}
			}else if($transacao->getCodigoOperadora() == 2){//rede

				if ($transacao->getCodRetAutorizacao() == 0){
					
					try {
						$this->getStatusConexao();
						if ($transacao->getDadosAdd() == "") $transacao->setDadosAdd('null'); 
						if ($transacao->getCodRetEstorno() == "") $transacao->setCodRetEstorno('null'); 
						if ($transacao->getMsgRetEstorno() == "") $transacao->setMsgRetEstorno('null'); 

						$sql = "SELECT * FROM TRANSACAO WHERE num_sequencial_rede = '{$transacao->getNumSequencRet()}'";

						$result = $this->conexao_banco->query($sql);
						

						if ($result->num_rows > 0) {
							if ($transacao->getStatusGeral() == 6){
								$sql = "UPDATE TRANSACAO SET 
									cod_retorno_estorno_rede = {$transacao->getCodRetEstorno()},
									msg_retorno_estorno_rede = '{$transacao->getMsgRetEstorno()}',
									status_geral = {$transacao->getStatusGeral()},
									data_hora_retorno_cancelamento = '{$transacao->getDataRetCancelamento()}'
								WHERE TRANSACAO.num_sequencial_rede = {$transacao->getNumSequencRet()}";
							}elseif ($transacao->getStatusGeral() == 5){
								$sql = "UPDATE TRANSACAO SET 
									cod_retorno_estorno_rede = {$transacao->getCodRetEstorno()},
									msg_retorno_estorno_rede = '{$transacao->getMsgRetEstorno()}',
									status_geral = {$transacao->getStatusGeral()},
									data_hora_retorno_captura = '{$transacao->getDataRetCaptura()}'
								WHERE TRANSACAO.num_sequencial_rede = {$transacao->getNumSequencRet()}";
							}else{
								$sql = "UPDATE TRANSACAO SET 
									cod_retorno_estorno_rede = {$transacao->getCodRetEstorno()},
									msg_retorno_estorno_rede = '{$transacao->getMsgRetEstorno()}',
									status_geral = {$transacao->getStatusGeral()},
									data_hora_retorno_autorizacao = '{$transacao->getDataRetAutorizacao()}',
									data_hora_retorno_cancelamento = null
								WHERE TRANSACAO.num_sequencial_rede = {$transacao->getNumSequencRet()}";
							}
							
						} else {
							//date("Ymd", strtotime($_POST["dataAutorizacao"]))
							//$transacao->setDataRetAutorizacao(date("Y-m-d", strtotime($transacao->getDataRetAutorizacao())) . ' ' . date("G:i:s"));
							$data = date("Y-m-d", strtotime($transacao->getDataRetAutorizacao()));
							$hora = date("G:i:s");
							$datatime = $data . ' ' . $hora;
							if ($transacao->getStatusGeral() == 5){


								$sql = "insert into transacao
										(fk_pedido, fk_empresa, fk_operadora, valor_transacao, forma_pgto_rede, qtde_parcelas, 
										dados_adicionais_rede,
										cod_retorno_autorizacao_rede,
										msg_retorno_autorizacao_rede,
										num_retorno_autorizacao_rede,
										data_hora_pedido,
										data_hora_retorno_autorizacao,
										data_hora_retorno_captura,
										num_retorno_comprovante_rede,
										num_retorno_autenticacao_rede,
										num_sequencial_rede,
										num_origem_bin_rede,
										cod_retorno_confirmacao_rede,
										msg_retorno_confirmacao_rede,
										cod_retorno_estorno_rede,
										msg_retorno_estorno_rede,
										fk_pedido_pagamento, 
										status_geral,
										fk_forma_pagamento,
										taxa,
										valor_liquido)
									values ({$transacao->getNumPedido()}, 
										{$dadosEmpresa['cod_empresa']}, 
										{$transacao->getCodigoOperadora()}, 
										{$transacao->getTotalTransacao()}, 
										{$transacao->getTipoTransacao()},
									 	{$transacao->getNumParcelas()},
									 	{$transacao->getDadosAdd()},
									 	{$transacao->getCodRetAutorizacao()},
									 	'{$transacao->getMsgRetAutorizacao()}',
									 	{$transacao->getNumRetAutorizacao()},
									 	'{$transacao->getDados_Pd_Data_Hora_Retorno()}',
									 	'{$datatime}',
									 	'{$transacao->getDataRetCaptura()}',
									 	{$transacao->getNumRetComprovVenda()},
									 	{$transacao->getNumRetAutenticacao()},
									 	{$transacao->getNumSequencRet()},
									 	'{$transacao->getNumOrigemBin()}',
									 	{$transacao->getConfCodRet()},
									 	'{$transacao->getConfMsgRet()}',
									 	{$transacao->getCodRetEstorno()},
									 	'{$transacao->getMsgRetEstorno()}',
										{$transacao->getCodPedidoPagamento()},
										{$transacao->getStatusGeral()},
										{$transacao->getTipoFormaPagamento()},
										{$transacao->getTaxa()},
										{$transacao->getLiquido()}
										)";
							}else{
								$sql = "insert into transacao
										(fk_pedido, 
										fk_empresa, 
										fk_operadora, 
										valor_transacao, 
										forma_pgto_rede, 
										qtde_parcelas, 
										dados_adicionais_rede,
										cod_retorno_autorizacao_rede,
										msg_retorno_autorizacao_rede,
										num_retorno_autorizacao_rede,
										data_hora_pedido,
										data_hora_retorno_autorizacao,
										num_retorno_comprovante_rede,
										num_retorno_autenticacao_rede,
										num_sequencial_rede,
										num_origem_bin_rede,
										cod_retorno_confirmacao_rede,
										msg_retorno_confirmacao_rede,
										cod_retorno_estorno_rede,
										msg_retorno_estorno_rede,
										fk_pedido_pagamento, 
										status_geral,
										fk_forma_pagamento,
										taxa,
										valor_liquido)
									values ({$transacao->getNumPedido()}, 
										{$dadosEmpresa['cod_empresa']}, 
										{$transacao->getCodigoOperadora()}, 
										{$transacao->getTotalTransacao()}, 
										{$transacao->getTipoTransacao()},
									 	{$transacao->getNumParcelas()},
									 	{$transacao->getDadosAdd()},
									 	{$transacao->getCodRetAutorizacao()},
									 	'{$transacao->getMsgRetAutorizacao()}',
									 	{$transacao->getNumRetAutorizacao()},
									 	'{$transacao->getDados_Pd_Data_Hora_Retorno()}',
									 	'{$datatime}',
									 	{$transacao->getNumRetComprovVenda()},
									 	{$transacao->getNumRetAutenticacao()},
									 	{$transacao->getNumSequencRet()},
									 	'{$transacao->getNumOrigemBin()}',
									 	{$transacao->getConfCodRet()},
									 	'{$transacao->getConfMsgRet()}',
									 	{$transacao->getCodRetEstorno()},
									 	'{$transacao->getMsgRetEstorno()}',
										{$transacao->getCodPedidoPagamento()},
										{$transacao->getStatusGeral()},
										{$transacao->getTipoFormaPagamento()},
										{$transacao->getTaxa()},
										{$transacao->getLiquido()}
										)";
							}
							
										
						}
						
						//echo $sql;
						$result = $this->conexao_banco->query($sql);
						$this->conexao_banco->close();

						
					}catch(Exception $e){
						echo "Erro de Inserção: 2 - " .$e->getMessage();
					}
				}
			}
			
			
		
		}

		public function getTransacao($transacao, $codEmpresa){
			//print_r($transacao);
			try {
				$this->getStatusConexao();
				
				/*$sql = "SELECT transacao.*, operadoras.*, operadora_empresa.* FROM TRANSACAO 
							INNER JOIN operadoras ON operadoras.id_operadora = transacao.fk_operadora
							INNER JOIN operadora_empresa ON operadora_empresa.fk_operadora = transacao.fk_operadora AND operadora_empresa.fk_empresa = transacao.fk_empresa
						WHERE transacao.fk_pedido_pagamento = " . $transacao["Codigo"] . " AND transacao.fk_empresa = {$codEmpresa} ORDER BY transacao.id_transacao DESC";*/
				$sql = "SELECT transacao.*, operadoras.*, operadora_empresa.* FROM TRANSACAO 
							left outer join forma_pagamento_operadora_empresa on transacao.fk_forma_pagamento_operadora_empresa = forma_pagamento_operadora_empresa.id_forma_pagamento_operadora_empresa
							inner join operadora_empresa on forma_pagamento_operadora_empresa.fk_operadora_empresa = operadora_empresa.id_operadora_empresa
							inner join operadoras on operadora_empresa.fk_operadora = operadoras.id_operadora
						WHERE transacao.fk_pedido_pagamento = " . $transacao["Codigo"] . " AND operadora_empresa.fk_empresa = {$codEmpresa} ORDER BY transacao.id_transacao DESC";
				//echo "<br><br>$sql";		
				$result = $this->conexao_banco->query($sql);
				if ($result){
					$linha = $result->fetch_assoc();
					if ($linha == NULL){
						//echo "<br>null<br>";
						return null;
						/*
						//echo "<br><br>$sql";
                        $formaPgto = $transacao['TipoPagamento'];
                        try {
                        	echo "--------- SEGUNDO SELECT ------------";
                            $sql1 = "SELECT forma_pagamento_operadora_empresa.*, operadoras.*, operadora_empresa.*  FROM forma_pagamento_operadora_empresa  
                        				INNER JOIN operadora_empresa ON operadora_empresa.id_operadora_empresa = forma_pagamento_operadora_empresa.fk_operadora_empresa 
                        				INNER JOIN operadoras ON operadoras.id_operadora = operadora_empresa.fk_operadora 
                    				WHERE forma_pagamento_operadora_empresa.fk_forma_pagamento = '" . $formaPgto . "'";
							echo "<br>$sql1";
                            $resultOperadora = $this->conexao_banco->query($sql1);
                            
                            if ($resultOperadora) {
                                $linhaOperadora = $resultOperadora->fetch_assoc();
                                
                            }
                            $linha = $linhaOperadora;
                            
                            $resultOperadora->close();
                        }catch(Exception $e){
                            echo $e->getMessage();
                        }*/
                    }
				}
				$result->close();
				return $linha;
			}catch(Exception $e){
				echo $e;
				$this->falhaLogin();
			}
		}

		public function buscarTransacaoRede($numsequencial){
			try {
				$this->getStatusConexao();
				
				$sql = "SELECT transacao.* FROM TRANSACAO
						WHERE transacao.num_sequencial_rede = $numsequencial"; 
				
				$result = $this->conexao_banco->query($sql);
				
				if ($result){
					$linha = $result->fetch_assoc();
				}
				$result->close();
				
				return $linha;
			}catch(Exception $e){
				$this->falhaLogin();
			}
		}

		public function getHistorico($transacao, $codEmpresa){
			
			try {
				$this->getStatusConexao();
				
				/*$sql = "SELECT transacao.*, operadoras.nome_operadora FROM TRANSACAO
							INNER JOIN operadoras ON operadoras.id_operadora = transacao.fk_operadora
						WHERE transacao.fk_pedido_pagamento = " . $transacao["Codigo"] . " AND transacao.fk_empresa = {$codEmpresa} ORDER BY transacao.id_transacao desc"; */
				
				$sql = "SELECT transacao.*, operadoras.nome_operadora FROM TRANSACAO
							left outer join forma_pagamento_operadora_empresa ON transacao.fk_forma_pagamento_operadora_empresa = forma_pagamento_operadora_empresa.id_forma_pagamento_operadora_empresa
							left outer join operadora_empresa ON forma_pagamento_operadora_empresa.fk_operadora_empresa = operadora_empresa.id_operadora_empresa
							inner join operadoras ON operadora_empresa.fk_operadora = operadoras.id_operadora
						WHERE transacao.fk_pedido_pagamento = " . $transacao["CodPedidoPagamento"] . " AND operadora_empresa.fk_empresa = {$codEmpresa} ORDER BY transacao.id_transacao desc";
				//echo "<br><br>$sql<br>";		
				//$result = $this->conexao_banco->query($sql);
				
				$result = $this->conexao_banco->query($sql);
				

				$listaHistorico = array();
				$transacao = array();
				
				
				while($linha = $result->fetch_array(MYSQLI_ASSOC)){

					if ($linha["tid_transacao_cielo"] != NULL) $transacao["CodigoTransacao"] = $linha["tid_transacao_cielo"];
					elseif ($linha["num_sequencial_rede"] != NULL) $transacao["CodigoTransacao"] = $linha["num_sequencial_rede"];
					$transacao["NomeOperadora"] =  $linha['nome_operadora'] ;
					$transacao["DataAutorizacao"] =  $linha['data_hora_retorno_autorizacao'] ;
					$transacao["DataAutenticacao"] = $linha['data_hora_retorno_autenticacao'];
					$transacao["DataCaptura"] = $linha['data_hora_retorno_captura'];
					$transacao["DataCancelamento"] = $linha['data_hora_retorno_cancelamento'];	
					array_push($listaHistorico, $transacao);
				}





				$result->close();
				return $listaHistorico;
			}catch(Exception $e){
				echo "<br/>".$e;
				//$this->falhaLogin();
			}	
		}

		function buscaTransacoesPersonalizada($sql, $codEmpresa){
			
			$sql .= " AND transacao.fk_empresa = {$codEmpresa} order by id_transacao desc";
			//echo $sql;
			
			try {
				$this->getStatusConexao();
				$this->conexao_banco->set_charset("utf8");
				$result = $this->conexao_banco->query($sql);
				$lista = array();
				while($linha = $result->fetch_array(MYSQLI_ASSOC)){
					array_push($lista, $linha);
				}
				$result->close();
				//print_r($lista);
				return $lista;
			}catch(Exception $e){
				$this->falhaLogin();

			}
		}
		
	//ESTA FUNÇÃO BUSCA OS PEDIDOS 'PENDENTES' NO SISTEMA DO CLIENTE
		public function buscarListaTransacoesPendentes(){
			/* STATUS GERAL
			 0 - PENDENTE
			 1 - AUTENTICADA
			 2 - NÃO AUTENTICADA
			 3 - AUTORIZADA
			 4 - NÃO AUTORIZADA
			 5 - CAPTURADA
			 6 - CANCELADA
			 7 - INDEFINIDO
			 */
			
			
			
			try {
				$this->getStatusConexao();
				$this->conexao_banco->set_charset("utf8");
				$sql = "SELECT transacao.fk_pedido AS CodPedido, transacao.data_hora_pedido AS DataPedido, transacao.valor_transacao AS TotalPedido, transacao.status_geral AS StatusPedido,
								transacao.fk_cliente AS CodigoCliente, transacao.nome_pagador AS ClienteNome, transacao.inscricao_pagador AS ClienteCpf, transacao.cep_pagador AS EntregaCep,
								transacao.logradouro_pagador AS EntregaRua, transacao.numero_end_pagador AS EntregaNumero, transacao.complemento_end_pagador AS EntegaComplemento,
								transacao.bairro_pagador AS EntregaBairro, transacao.cidade_pagador AS EntregaCidade, transacao.uf_pagador AS EntregaUf, transacao.cartao_titular AS CartaoTitular,
								transacao.cartao_numero AS CartaoNumero, transacao.cartao_validade AS CartaoValidade, transacao.cartao_cod_seguranca AS CartaoCodigoSeguranca,
								transacao.data_hora_criacao_transacao AS DataHoraCriacao, transacao.fk_pedido_pagamento AS CodPedidoPagamento, transacao.num_parcelas AS NumeroParcelasPedPag,
								transacao.valor_parcela AS ValorParcelaPedPag, transacao.taxa AS Taxa, transacao.tid_transacao_cielo, transacao.pan_transacao_cielo, transacao.autorizacao_automatica_cielo, 
								transacao.tipo_cobranca AS Origem, transacao.identificador_boleto AS idBoleto, transacao.num_parcelas,
								forma_pagamento.descricao_forma_pagamento AS FormaPagamento, forma_pagamento.id_forma_pagamento AS TipoPagamento, 
								operadoras.*, operadora_empresa.*
						FROM transacao 
							LEFT OUTER JOIN forma_pagamento_operadora_empresa ON transacao.fk_forma_pagamento_operadora_empresa = forma_pagamento_operadora_empresa.id_forma_pagamento_operadora_empresa
							INNER JOIN forma_pagamento ON forma_pagamento_operadora_empresa.fk_forma_pagamento = forma_pagamento.id_forma_pagamento
							INNER JOIN operadora_empresa ON forma_pagamento_operadora_empresa.fk_operadora_empresa = operadora_empresa.id_operadora_empresa
							INNER JOIN operadoras ON operadora_empresa.fk_operadora = operadoras.id_operadora 
						WHERE transacao.status_geral = 0";
				
				$result = $this->conexao_banco->query($sql);
				$_SESSION["listaPedidos"] = array();  
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
				//while($row = @odbc_fetch_array($result)){  
					array_push($_SESSION["listaPedidos"], $row);
				}
				$result->close();
			}catch(Exception $e){
				//echo "<br />FALHA DE SELEÇÃO<br />";
				throw $e;
			}
		}
	}
	
?>