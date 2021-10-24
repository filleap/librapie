<?php
	// vérification de l'utilisateur
	session_start();
	if(isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
		$session = true;
		$annee = $_GET['annee'];
		$suppr = $_GET['suppr'];
		if($annee == ""){
			// $date = getdate();
			// $annee = $date[year];
			$annee = "2019";
		}
	} else {
		$session = false;
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
 <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="./bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="css/default.css"/> -->
  <script type="text/javascript" src="./jslib/jquery.js"></script>
  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
  <script src="./bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	<title>LibrAPIE - Page d'accueil</title>
 </head>
 <body>
	 <div class="container">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-2">
					<a href="index.php">
						<img height="100px" src="./Logo_APIE.gif"/>
					</a>
				</div>
				<div class="col-sm-10">
					<h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
					<caption>la bibliothèque du marché de Noël</caption>
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
				<img src="Cadenas.png">
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
			 		<h2>Liste des livre de l'année <?php print($annee) ?></h2>
					Choisissez une année
			 		<form action="historique.php" method="GET">
						<select id="annee" name="annee">
							<option value="2015"
								<?php if($annee == "2015") print("selected") ?>>2015
							</option>
							<option value="2016"
								<?php if($annee == "2016") print("selected") ?>>2016
							</option>
							<option value="2017"
								<?php if($annee == "2017") print("selected") ?>>2017
							</option>
							<option value="2018"
								<?php if($annee == "2018") print("selected") ?>>2018
							</option>
							<option value="2019"
								<?php if($annee == "2019") print("selected") ?>>2019
							</option>
						</select>
						<!-- Livres supprimés <input type="checkbox" name="suppr" id="suppr"
							<?php if($suppr == "on") print("checked") ?>> -->
						<button title="OK" type="submit" class="btn btn-default">
							<span class="glyphicon glyphicon-ok"></span>
						</button>
					</form>
				</div>
			</div>
		<?php
			if(!$session){
				print("<div class='col-sm-12' hidden>");
			} else {
				print("<div class='col-sm-12'>");
				if ($suppr == "on") {
					include("listeLivresSuppr.php");
				} else {
					include("listeLivres.php");
				}
			}
		?>
		</div>
	</div>
 </body>
</html>
