<?php
	$session = false;
	// vérification de l'utilisateur
	session_start();
	if(isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
		$session = true;
	} else {
		$session = false;
	}

	if($session){
		$idLivre = $_POST['idLivre'];
		$annee = addslashes($_POST['Annee']);
		$ean = addslashes($_POST['EAN']);
		$choix = addslashes($_POST['Choix']);
		$titre = addslashes($_POST['Titre']);
		$auteur = addslashes($_POST['Auteur']);
		$editeur = addslashes($_POST['Editeur']);
		$categorie = addslashes($_POST['Categorie']);
		$prix = $_POST['Prix'];
		$qte = $_POST['Qte'];
		$cde = $_POST['Cde'];
		$qteCdee = $_POST['QteCdee'];
		$rendu = $_POST['Rendu'];
		$qteRendu = $_POST['QteRendue'];


		if($cde == "on"){
			$cde = 1;
		} else{
			$cde = 0;
			// ça n'est plus une commande il n'y a donc plus de quantité
			$qteCdee = 0;
		}

		if($rendu == "on"){
			$rendu = 1;
			// on vérifie qu'on ne rend pas plus de livre qu'on a en stock
			if($qte >= $qteRendu){
				$qte = $qte - $qteRendu;
			}
		} else{
			$rendu = 0;
		}

    // Connexion a la base de donnees
    include("dbconf.php");

    //mysql_query("SET NAMES UTF8");

	// $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
	// $cnx = mysqli_connect($host, $user, $mdp);
	
	$cnx = mysqli_connect($host, $user, $mdp);
	if(!cnx){
		die("Connexion a mysql impossible : ".$cnx->connect_error);
	}
	mysqli_select_db($cnx,$bdd);

	// Mise à jour des information sur le livre
	$query = "UPDATE ".$annee."_APIE_Livres SET Choix='$choix', Titre='$titre', Auteur='$auteur', Editeur='$editeur', Prix='$prix', Categorie='$categorie' WHERE EAN='$ean'";

    if ($result = $cnx->query($query)){
			// Mise à jour de la quantité dans le stock
			$query = "UPDATE ".$annee."_APIE_Stock_Livres SET Qte='$qte', Cde='$cde', Rendu='$rendu', QteCdee='$qteCdee' WHERE EAN='$ean'";
			if ($result = $cnx->query($query)){
	    	$msg = "mise à jour effectuée.";
				header("Location: ./bookStock.php");
				exit();
			}
		}
    else
    	$msg = "erreur !";
  }
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
	  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
    <title>LibrAPIE - Mise à jour</title>
    <body>
  		<div class="container" id="content" class="content">
  	 		<div class="page-header" id="entete-APIE">
  	 			<div class="row">
  	 				<div class="col-sm-2">
  						<a href="index.php">
  							<img height="100px" src="./Logo_APIE.gif"/>
  						</a>
  	 				</div>
  	 				<div class="col-sm-8">
  						<h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
  	 					<caption>la bibliothèque du marché de Noël</caption>
  	 				</div>
  	 				<div class="col-sm-2">
  						<img height="100px" src="pile-de-livres.jpg"/>
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
  						<button type="button" class="btn btn-default" title="connexion">
  							connexion
  						</button>
  					</a>
  				</div>
  			</div>
  			<?php
  				if(!$session){
  					print("<div id='miseAjour' hidden>");
  				} else {
  					print("<div id='miseAjour'>");
  				}
  			?>
  				<div id="barre_boutons">
  					<button type="button" title="page précédente" class="btn btn-default" onclick="history.go(-1)">
  						<span class="glyphicon glyphicon-arrow-left"></span>
  					</button>
  					<a href="index.php">
  						<button type="button" title="retour à l'acceuil" class="btn btn-default" onclick="javascript:window.location='index.php'">
  							<span class="glyphicon glyphicon-home"></span>
  						</button>
  					</a>
  				</div>
  				<div class="col-sm-12">
            <h2><?php print($msg); ?></h2>
          </div>
        </div>
      </div>
    </body>
  </html>
