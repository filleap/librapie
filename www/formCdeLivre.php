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
		$annee = $_GET['annee'];
		if($annee == ""){
			// $date = getdate();
			// $annee = $date[year];
			$annee = "2021";
		}
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
	<title>LibrAPIE - Formulaire de commande</title>
 </head>
 <body>
	 <div class="container">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-2">
					<a href="index.php">
						<img height="100px" src="./sapin-noel.png"/>
					</a>
				</div>
				<div class="col-sm-8">
					<h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
					<caption>la bibliothèque du marché de Noël</caption>
				</div>
				<div class="col-sm-2">
						<img height="100px" src="./Logo_APIE.gif"/>
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
				print("<div id='formCdeLivres' class='row' hidden>");
			} else {
				print("<div id='formCdeLivres' class='row'>");
			}
		?>
			<div id="barre_boutons">
				<a href="index.php">
					<button type="button" title="retour à l'acceuil" class="btn btn-default" onclick="javascript:window.location='index.php'">
						<span class="glyphicon glyphicon-home"></span>
					</button>
				</a>
				<a href="listeCdes.php?annee=<?php echo $annee ?>">
					<button title="liste des commandes" type="button" class="btn btn-default">
						liste Cdes
					</button>
				</a>
				<a href="bookCde3.php?annee=<?php echo $annee ?>">
					<button title="liste des commandes par personne" type="button" class="btn btn-default">
						liste Cdes/Pers
					</button>
				</a>
				<a href="livresAprendre.php?annee=<?php echo $annee ?>">
					<button title="liste des livres à prendre" type="button" class="btn btn-default">
						livres Cdés manquant
					</button>
				</a>
			</div>
	    <div>
	      <?php
			if($session){
				// Connexion a la base de donnees
				include("dbconf.php");

				try {
					$dbh = new PDO("mysql:host=".$host.";dbname=".$bdd, $user, $mdp);
					foreach ($dbh->query("SELECT * FROM APIE_Personnes ORDER BY Nom") as $row) {
						$personnesList[] = $row;
					}
					$dbh = null;
				} catch (Exception $e) {
					print("Erreur !: ".$e->getMessage()."<br/>");
					die();
				}
			}
	      ?>
	      <h3>Commande de livres</h3>
	      <form id="addCde" action="addCde.php" method="post">
	        Clients existants
	        <select id="personnes" name="idPersonne">
	        <?php
	          for ($i = 0; $i < sizeof($personnesList); $i++) {
	            print("<option value='".$personnesList[$i][id]."'>".$personnesList[$i][Nom]." ".$personnesList[$i][Prenom]."</option>");
	          }
	        ?>
	        </select>
			<button title="détail de la commande" type="button" class="btn btn-info" onclick="showCde(<?php echo $annee ?>);">
				<span class="glyphicon glyphicon-list-alt"></span>
			</button>
	        <p>
	        Nom : <input type="text" name="nom" id="nom">
	        Prenom : <input type="text" name="prenom" id="prenom">
	        <p>
	        ISBN : <input type="text" name="isbn" id="isbn">
	        Quantite : <input type="text" name="qte" id="qte" size="5">
	        <p>
			<button title="commander" type="submit" class="btn btn-success">
				<span class="glyphicon glyphicon-shopping-cart"></span>
			</button>
	      </form>
		</div>
	</div>
  </body>
</html>
