<?php
  session_start();
	// vérification de l'utilisateur
	if(isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
    $_SESSION = array();
    session_destroy();

    setcookie('login', '');
    setcookie('pass_hache', '');

    $message = "Déconnexion effectuée.";
  } else {
    $message = "Vous n'êtes pas connecté.";
  }
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
 <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="css/default.css"/> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<title>LibrAPIE - Page de déconnexion</title>
 </head>
 <body>
	 <div class="container">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-2">
					<a href="index.php">
						<img height="100px" src="./images/Logo_APIE.gif"/>
					</a>
				</div>
				<div class="col-sm-10">
					<h1>Bienvenue <?php print($_SESSION['pseudo']); ?> dans librAPIE</h1>
					<caption>la bibliothèque du marché de Noël</caption>
				</div>
			</div>
		</div>
    <div class="row">
      <h2><?php print($message); ?></h2>
			<button type="button" title="retour à l'acceuil" class="btn btn-default" onclick="javascript:window.location='index.php'">
				<span class="glyphicon glyphicon-home"></span>
			</button>
    </div>
 </body>
</html>
