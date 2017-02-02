<span class="nome-empresa"><?php echo $_SESSION["dados_acesso"][0]["NOME"] ?></span>
<span class="nome-pagina"> <?= Page ?></span> <span class="usuario">
	<div align="right">Funcionário logado: <?php echo $_SESSION["dados_acesso"][0]["nome_usuario"]?>
    	<div class="btn-group">
			<button type="button" class="btn btn-success dropdown-toggle"
				data-toggle="dropdown" aria-haspopup="true"
				aria-expanded="false">
				Opções <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="home.php">Pagamentos pendentes</a></li>
				<li><a href="consultas.php">Buscas e totalizadores</a></li>
				
				<li role="separator" class="divider"></li>
				<li><a href="formtransacao.php">Gerar Boleto</a></li>
				<li><a href="#modalRemessa" data-toggle="modal" data-target="#modalRemessa">Gerar remessa</a></li>
				<li><a href="#modalRetorno" data-toggle="modal" data-target="#modalRetorno">Processar retorno</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="../../modulos.php">Sistemas</a></li>
				<li><a href="../../logout.php">Logout</a></li>
			</ul>
		</div>
	</div>
</span>