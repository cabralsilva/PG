<?php
	
		
		$banco = "ibolt_empresa";
		$usuario = "ibolt_empresa";
		$senha = "empresa";
		$hostname = "186.202.152.57:3306";
		$conn = mysql_connect($hostname,$usuario,$senha);
		
		mysql_select_db($banco) or die( "Não foi possível conectar ao banco MySQL");
		
		
		if (!$conn) {
			echo "Não foi possível conectar ao banco MySQL."; 
			
			$_SESSION["ERRO_BANCO"] = true; 
			
			//session_unset();	
			header("location:login.php");
			exit;
		}
		
		verificarUsuario();
		
		verificarEmpresa();
		
		
		mysql_close(); 
		//header("location:modulos.php");
		
		function verificarEmpresa(){
			
			$SQL = "SELECT EMPRESA.NOME, SISTEMAS.descricao_sistema, USUARIO_EMPRESA_SISTEMA.fk_usuario, USUARIOS.nome_usuario FROM EMPRESA
					INNER JOIN EMPRESA_SISTEMA ON EMPRESA.CODIGO = EMPRESA_SISTEMA.fk_empresa
					INNER JOIN SISTEMAS ON SISTEMAS.id_sistema = EMPRESA_SISTEMA.fk_sistema
					INNER JOIN USUARIO_EMPRESA_SISTEMA ON USUARIO_EMPRESA_SISTEMA.fk_empresa_sistema = EMPRESA_SISTEMA.id_empresa_sistema
					INNER JOIN USUARIOS ON USUARIOS.id_usuario = USUARIO_EMPRESA_SISTEMA.fk_usuario
					WHERE USUARIOS.id_usuario = " . $_SESSION["id_usuario"];
					
			$result = @mysql_query($SQL) or die("Erro no banco de dados!"); 
			
			// Percorre os registros retornados
			$_SESSION["dados_acesso"] = array();
		  while($linha = mysql_fetch_array($result, MYSQL_NUM)){
			array_push($_SESSION["dados_acesso"], $linha);
			echo $linha[0] . " - " . $linha[1] . " - " . $linha[2] . " - " . $linha[3] . "<br>";
		  }
		
		  // Libera o result set
		  mysql_free_result($result);
		}
		
		
		
		function verificarUsuario(){
			
			// Recupera o login 
			$login = isset($_POST["email"]) ? addslashes(trim($_POST["email"])) : FALSE; 
			// Recupera a senha, a criptografando em MD5 
			//$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE;
			$senha = isset($_POST["senha"]) ? $_POST["senha"] : FALSE; 
			
			// Usuário não forneceu a senha ou o login 
			if(!$login || !$senha) 
			{ 
				echo "Você deve digitar sua senha e login!"; 
				exit; 
			}
			/** 
			* Executa a consulta no banco de dados. 
			* Caso o número de linhas retornadas seja 1 o login é válido, 
			* caso 0, inválido. 
			*/ 
			$SQL = "SELECT USUARIOS.id_usuario, USUARIOS.nome_usuario, USUARIOS.email_usuario, USUARIOS.senha_usuario FROM USUARIOS WHERE USUARIOS.email_usuario = '" . $login . "'"; 
			$result_id = @mysql_query($SQL) or die("Erro no banco de dados!"); 
			$total = @mysql_num_rows($result_id);



			// Caso o usuário tenha digitado um login válido o número de linhas será 1.. 
			if($total) 
			{ 
				// Obtém os dados do usuário, para poder verificar a senha e passar os demais dados para a sessão 
				$dados = @mysql_fetch_array($result_id); 
				
				// Agora verifica a senha 
				if(!strcmp($senha, $dados["senha_usuario"])) 
				{ 	
					session_start();
					// TUDO OK! Agora, passa os dados para a sessão e redireciona o usuário 
					$_SESSION["id_usuario"]= $dados["id_usuario"]; 
					
					$_SESSION["nome_usuario"] = stripslashes($dados["nome_usuario"]); 
					$_SESSION["email_usuario"] = stripslashes($dados["email_usuario"]); 
					
					header("Location: modulos.php"); 
					//exit; 
				} 
				
				// Senha inválida 
				else 
				{ 
					//echo $dados["senha_usuario"] . "<br />";
					//echo $senha . "<br />";
					echo "Senha inválida!"; 
					exit; 
				} 
			}
			 
			// Login inválido 
			else 
			{ 
				echo "O login fornecido por você é inexistente!"; 
				exit; 
			}
		}
?>
