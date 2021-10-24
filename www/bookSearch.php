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
		$baseURL = "https://www.googleapis.com/books/v1/volumes/";
		$isbn = urlencode($_GET['bookId']);

		$urlSearch = $baseURL.$isbn;
		// var_dump($urlSearch);
		if($isbn != "") {
			$searchResult = file_get_contents($urlSearch);
			if($searchResult != null){
				$jsonSearch = json_decode($searchResult);
				// var_dump($jsonSearch);
				$book = $jsonSearch;
				// var_dump($items);
				// var_dump("info: ".$items[0]->volumeInfo->industryIdentifiers[1]->identifier);
			}
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_fr" xml:lang="en_fr">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="./bootstrap-3.3.5-dist/css/bootstrap.min.css">
  <script src="./bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
  <title>LibrAPIE - Détails du livre</title>
 </head>
 <body onload="javascript:document.getElementById('Prix').focus();">
	 <div class="container">
		<div class="page-header">
			<div class="row">
				<div class="col-sm-2">
					<img height="100px" src="./Logo_APIE.gif"/>
				</div>
				<div class="col-sm-10">
					<h1>Bienvenue dans librAPIE <?php print($_SESSION['pseudo']); ?></h1>
					<caption>la bibliothèque du marché de Noël</caption>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-2">
				<img src="<?php 
					if($book->volumeInfo->imageLinks->thumbnail) {
						echo($book->volumeInfo->imageLinks->thumbnail);
					} else {
						echo("https://books.google.fr/googlebooks/images/no_cover_thumb.gif");
					}
				?>" alt="Pas de couverture disponible"/>
			</div>
			<div class="col-sm-10">
			 	<form action="addBook.php" method="post">
					 <input type="hidden" name="Annee" id="Annee" value="<?php echo($annee) ?>">
					<p><b>Titre :</b> <?php
					 	if($_GET['manuel']){
							print("<input class='text' name='Titre' id='Titre'>");
						} else {
						print ($book->volumeInfo->title);
						//if ($book->volumeInfo->subtitle != "")
						//	print(" (" .$book->volumeInfo->subtitle. ")");
						print("<input type='hidden' class='text' name='Titre' id='Titre' value='".$book->volumeInfo->title."'>");
						print("</p>");
						}
					?>
					<input type="hidden" name="URL" id="URL" value="<?php 
						if($book->volumeInfo->imageLinks->thumbnail) {
							echo($book->volumeInfo->imageLinks->thumbnail);
						} else {
							echo("https://books.google.fr/googlebooks/images/no_cover_thumb.gif");
						}
					?>"/>
			 		<p><b>ISBN / EAN :</b> <?php
						// correction si EAN manquant
						if ($book->volumeInfo->industryIdentifiers[1]->identifier) {
							print($book->volumeInfo->industryIdentifiers[0]->identifier." / ");
							print("<input class='text' name='EAN' id='EAN' value='".$book->volumeInfo->industryIdentifiers[1]->identifier."'/>");
							print("<input type='hidden' class='text' name='ISBN' id='ISBN' value='".$book->volumeInfo->industryIdentifiers[0]->identifier."'/>");
						} elseif ($xml->Items->Item->ItemAttributes->EISBN) {
							print($xml->Items->Item->ItemAttributes->ISBN." / ");
							print("<input class='text' name='EAN' id='EAN' value='".$xml->Items->Item->ItemAttributes->EISBN."'/>");
							print("<input type='hidden' class='text' name='ISBN' id='ISBN' value='".$xml->Items->Item->ItemAttributes->ISBN."'/>");
						} else {							
							print("<input class='text' name='EAN' id='EAN' value='".$xml->Items->Item->ItemAttributes->EISBN."'/>");
						}
					 ?></p>
			 		<!-- <input type="hidden" class="text" name="ISBN" id="ISBN" value="<?php print($xml->Items->Item->ItemAttributes->ISBN); ?>"/>
			 		<input type="hidden" class="text" name="EAN" id="EAN" value="<?php print($xml->Items->Item->ItemAttributes->EAN); ?>"/> -->
			 		<p><b>Auteur :</b> <?php
					 	if($_GET['manuel']){
							 print("<input class='text' name='Auteur' id='Auteur'/>");
						 } else {
							for ($i = 0; $i < sizeof($book->volumeInfo->authors); $i++){
								if(sizeof($book->volumeInfo->authors) == 1) {
									print($book->volumeInfo->authors[$i]);
								} else {
									if($i == sizeof($book->volumeInfo->authors)-1){
										print($book->volumeInfo->authors[$i]);
									} else {
										print($book->volumeInfo->authors[$i]." ; ");
									}
								}
							}
							print("</p>");
						 	print("<input type='hidden' class='text' name='Auteur' id='Auteur' value=");
							for ($i = 0; $i < sizeof($book->volumeInfo->authors); $i++){
								if(sizeof($book->volumeInfo->authors) == 1) {
									print($book->volumeInfo->authors[$i]);
								} else {
									if($i == sizeof($book->volumeInfo->authors)-1){
										print($book->volumeInfo->authors[$i]);
									} else {
										print($book->volumeInfo->authors[$i]." ; ");
									}
								}
							}
							print(">");
						}
					?>
					 <p><b>Editeur :</b> <?php
						if($_GET['manuel']){
							print("<input class='text' name='Editeur' id='Editeur'/>");
						} else {
							echo($book->volumeInfo->publisher);
							print("</p>");
							print("<input type='hidden' class='text' name='Editeur' id='Editeur' value=".$book->volumeInfo->publisher.">");
						}
					?>
					<?php
						$prix = explode(" ",$xml->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice)[1];
						$prix_float = str_replace(",", ".", $prix);
					?>
			 		<p><b>Prix de vente :</b> <input type="text" name="Prix" id="Prix" value="<?php echo($prix_float); ?>"/></p>
			 		<p><b>Catégorie :</b>
			 		<select name="Categorie" id="Categorie">
			 			<option value="A">Adulte</option>
			 			<option value="AR">Adulte Religieux</option>
			 			<option value="ADO">Ado</option>
						<option value="ADOR">Ado Religieux</option>
			 			<option value="J">Jeunesse</option>
			 			<option value="JR">Jeunesse Religieux</option>
			 		</select></p>
			 		<p><b>Selection La Procure :</b>
			 			<input id='sel_laprocure' name='sel_laprocure' type='checkbox'>
			 		</p>
			 		<p><b>Choix de :</b>
						<input type="text" name="Choix" id="Choix" value="<?php print($_SESSION['pseudo']); ?>" readonly>
					</p>
					<button type="submit" title="valider" class="btn btn-success">
						<span class="glyphicon glyphicon-ok"></span>
					</button>
					<button type="button" title="retour à l'acceuil" class="btn btn-default" onclick="javascript:window.location='index.php'">
						<span class="glyphicon glyphicon-home"></span>
					</button>
			 	</form>
			</div>
		</div>
	</div>
 </body>
 </html>
