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
		$keywords = urlencode($_POST['Keywords']);
		
		// var_dump($urlSearch);
		if($keywords != ""){
			if(is_numeric($keywords)) {
				$baseURL = "https://www.googleapis.com/books/v1/volumes?q=isbn:";
			} else {
				$baseURL = "https://www.googleapis.com/books/v1/volumes?q=";
			}
			$urlSearch = $baseURL.$keywords."&key=AIzaSyCAn78Zmia4f6N13vOkhq7CO8iyOaBpIbI";
			$searchResult = file_get_contents($urlSearch);
	
			if($searchResult != null){
				$jsonSearch = json_decode($searchResult);
				// var_dump($jsonSearch);
				$items = $jsonSearch->items;
				// var_dump($items);
				// var_dump("info: ".$items[0]->volumeInfo->industryIdentifiers[1]->identifier);
			}
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_FR" xml:lang="en_FR">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	  <!-- mise en page pour impression -->
	  <!-- <link rel="stylesheet" media="print" href="css/print.css">
	  <link rel="stylesheet" href="./css/tableau.css"> -->
	  <script type="text/javascript" src="./jslib/utilBibli.js"></script>
    <script type="text/javascript" src="./jslib/tablesort.js"></script>
		<title>LibrAPIE - Choix du livre</title>
  </head>
  <body>
		<div class="container" id="content" class="content">
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
						<button type="button" class="btn btn-default" title="connexion">
							connexion
						</button>
					</a>
				</div>
			</div>
			<?php
				if(!$session){
					print("<div id='résultats' class='col-sm-12' hidden>");
				} else{
					print("<div id='résultats' class='col-sm-12'>");
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
					<button type="button" title="Saisir manuellement" onclick="javascript:window.location='bookSearch.php?manuel=true'">
						<span class="">Saisir manuellement</span>
					</button>
				</div>
			 	<h2>Liste des résulats :</h2>
				<?php
		 			for($i=0; $i < sizeof($items); $i++){
		 				if($items[$i]->volumeInfo->title != "") {
		 					print("<form action='bookSearch.php' method='get'>");
		 					print("<input type='hidden' name='bookId' id='bookId' value='".$items[$i]->id."'/>");
		 					print("<li><a href=".$items[$i]->volumeInfo->infoLink." target='_blank'>".$items[$i]->volumeInfo->title." (".$items[$i]->volumeInfo->authors[0].")</a></li>");
		 					print("<button type='submit' class='btn btn-default'>");
							print("sélectionner");
							print("</button>");
		 					print("</form>");
						}
		 			}
		 		?>
			</div>
		</div>
	 </body>
 </html>
