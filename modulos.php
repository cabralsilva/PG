<?php 

	session_start(); 
	if (!isset($_SESSION["id_usuario"])) header("location:index.php"); 

?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MÓDULO IBOLT</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <div class="container-fluid">
        <header class="row">
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
                    <div class="navbar navbar-right" style="color: #CCC">
                        <?php echo "Olá " . $_SESSION["nome_usuario"] ?> - <a href="logout.php">LOGOUT</a>
                    </div>
                </div><!--/.navbar-collapse -->
              </div>
            </nav>
        </header>
        
     
        <div class="container principal">
          <!-- Example row of columns -->
            <div class="row">
            <?php
                foreach($_SESSION["dados_acesso"] as $modulo){
            ?>
          
                <div class="col-md-5">
                    <div class="box-<?php echo $modulo['cor_sistema']; ?>">
                        <div class="texto" style="top: 45%; position:relative; text-align:center;"><a href="<?php echo $modulo['diretorio_sistema']; ?>/buscardados_transacao.php"><?php echo $modulo['descricao_sistema']; ?></a></div>
                                    
                    </div>
                </div>
            <?php
                    
                }
                
            ?>
            </div>
        
            <hr>
            <footer class="row">
                <p>&copy; 2015 iBoltSys, Inc.</p>
            </footer>
          
        </div> <!-- /container -->
    </div>
</body>
</html>