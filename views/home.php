<?php 
	session_start();
	require_once '../util/constantes.php';
	
	if (!isset($_SESSION["usuario_logado"])){
		$_SESSION["falha_login"] = "Autenticação necessária";
		header("location: login");
	}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>iBoltSys - Sistemas</title>
<link rel="stylesheet" href="<?= BaseProjeto ?>/resources/css/style-home.css">
<link rel="stylesheet" href="<?= BaseProjeto ?>/resources/bootstrap/css/bootstrap.min.css">
</head>
<body>
	
	<div class="container">
		<span class="info-logon">Usuário <?= $_SESSION["usuario_logado"]["nome_usuario"] ?> - <a href="../logout/">Logout</a></span>
		<div class="row lst-sistemas">
			<?php foreach($_SESSION["dados_acesso"] as $modulo){ ?>
				<div class="col-md-6">
					<div class="box-central">
			            <div class="espaco-sistema">	
			            	<a href="../<?= $modulo['diretorio_sistema']; ?>/">
			            		<img alt="" src="../<?= $modulo['diretorio_logo']; ?>">
								<span class="descricao-sistema"><?=  $modulo['descricao_sistema']; ?></span>
							</a>
						</div>
					</div>
				</div>
			<?php }?>
		</div>	
	</div>		
</body>
</html>
