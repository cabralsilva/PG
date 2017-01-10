<?php 
	session_start(); 
	
	if ($_SESSION["id_usuario"]) header("location:modulos.php"); 

?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MÓDULO IBOLT</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    
</head>
<body>
	<script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">iBolt Systemas Informatizados</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" action="autenticacaoteste2.php" method="post">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control" name="senha" required>
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
          <?php
				if (!$_SESSION["status_login"]) $_SESSION["status_login"] = false; 	
				if ($_SESSION["status_login"] == true){
			?> 
                    <div style="color: red;">
                        ERRO DE ACESSO AO BANCO DE DADOS
		            </div>
            <?php
					//session_unset();
				}
			?>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Bem Vindo aos Sistemas iBolt</h1>
        <p>Um serviço completo para atender as necessidades da sua empresa. Faça loguin para ter acesso aos sistemas da sua empresa!</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Leia mais &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Conheça a iBolt</h2>
          <p>Conheça as vantagens de ter todos os serviços web da sua empresa nas mãos de quem sabe o que faz e conhece as melhores técnicas para atrais novos clientes a sua empresa. </p>
          <p><a class="btn btn-default" href="#" role="button">Leia mais &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Conheça a iBolt</h2>
          <p>Conheça as vantagens de ter todos os serviços web da sua empresa nas mãos de quem sabe o que faz e conhece as melhores técnicas para atrais novos clientes a sua empresa. </p>
          <p><a class="btn btn-default" href="#" role="button">Leia mais &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Conheça a iBolt</h2>
          <p>Conheça as vantagens de ter todos os serviços web da sua empresa nas mãos de quem sabe o que faz e conhece as melhores técnicas para atrais novos clientes a sua empresa. </p>
          <p><a class="btn btn-default" href="#" role="button">Leia mais &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Conheça a iBolt</h2>
          <p>Conheça as vantagens de ter todos os serviços web da sua empresa nas mãos de quem sabe o que faz e conhece as melhores técnicas para atrais novos clientes a sua empresa. </p>
          <p><a class="btn btn-default" href="#" role="button">Leia mais &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Conheça a iBolt</h2>
          <p>Conheça as vantagens de ter todos os serviços web da sua empresa nas mãos de quem sabe o que faz e conhece as melhores técnicas para atrais novos clientes a sua empresa. </p>
          <p><a class="btn btn-default" href="#" role="button">Leia mais &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Conheça a iBolt</h2>
          <p>Conheça as vantagens de ter todos os serviços web da sua empresa nas mãos de quem sabe o que faz e conhece as melhores técnicas para atrais novos clientes a sua empresa. </p>
          <p><a class="btn btn-default" href="#" role="button">Leia mais &raquo;</a></p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; 2015 iBoltSys, Inc.</p>
      </footer>
    </div> <!-- /container -->
    
    
    
</body>
</html>