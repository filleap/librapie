<?php
	// vérification de l'utilisateur
	session_start();
	if(isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
		$session = true;
		$annee = $_GET['annee'];
		if($annee == ""){
			// $date = getdate();
			// $annee = $date[year];
			$annee = "2022";
		}
	} else {
		$session = false;
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
 <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="css/default.css"/> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function focus() {
			document.getElementById("Keywords").focus();
		}
	</script>
	<title>LibrAPIE - Page d'accueil</title>
 </head>
 <body onload="focus();">
	 <div class="container">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-2">
					<a href="index.php">
						<img height="100px" src="./images/sapin-noel.png"/>
					</a>
				</div>
				<div class="col-sm-8">
					<h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
					<caption>la bibliothèque du marché de Noël</caption>
				</div>
				<div class="col-sm-2">
						<img height="100px" src="./images/Logo_APIE.gif"/>
				</div>
			</div>
		</div>
		<?php
			if(!$session){
				print("<div id='warning' class='row'>");
			} else {
				print("<div id='warning' class='row' hidden>");
			}
		?>
			<div class="col-sm-4">
				<img src="images/Cadenas.png">
			</div>
			<div class="col-sm-8">
				<h2>Vous n'êtes pas autorisé à accéder à cette page.</h2>
				<p>Veuillez vous connecter</p>
				<a href="connexion.php">
					<button type="button" class="btn btn-info" title="connexion">
						connexion
					</button>
				</a>
			</div>
		</div>
		<?php
			if(!$session){
				print("<div class='row' hidden>");
			} else {
				print("<div class='row'>");
			}
		?>
				<div class="col-sm-8">
			 		<h2>Ajout de livres</h2>
					Entrez un nom de livre ou un num&eacute;ro ISBN
			 		<form action="bookSelectionLP.php" method="POST">
				 		<input type="text" size="40" name="Keywords" id="Keywords"/>
						<button title="rechercher" type="submit" class="btn btn-default">
							<span class="glyphicon glyphicon-search"></span>
						</button>
						<p>
						Historique des sélections
						<button title="historique" type="button" class="btn btn-default" onclick="javascript:window.location='historique.php'">
							<span class="glyphicon glyphicon-calendar"></span>
						</button>
				</div>
				<div class="col-sm-2">
					<a href="formCdeLivre.php">
						<h3>Gestion des commandes</h3>
						<img src="./images/commande.jpg" height="70px">
					</a>
				</div>
				<div class="col-sm-2">
					<a href="bookStock.php">
						<h3>Gestion du stock</h3>
						<img src="./images/pile-de-livres.jpg" height="70px">
					</a>
				</div>
		</div>
		<?php
			if(!$session){
				print("<div class='col-sm-12' hidden>");
			} else {
				print("<div class='col-sm-12'>");
				include("listeLivres.php");
			}
		?>
		</div>
	</div>
 </body>
</html>
