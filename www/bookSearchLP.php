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
		// La Procure
		$thumbnail = "";
		$authors = [];
		$publisher = "";
		$description = "";
		$title = "";
		$isbn = "";
		$prix_float = 0.0;

		$ean = urlencode($_GET['bookId']);
		$urlSearch = "https://api-procure-production.azurewebsites.net/Common/Catalog/SearchWithFacets";
		$data = array(
			'query' => $ean,
			'bounce' => null,
			'filters' => null,
			'fields' => ["Title", "Author", "Collection", "Ean", "Publisher"],
			'datatable' => array(
				"sortBy" => "pertinence",
				"descending" => true,
				"page" => 1,
				"rowsPerPage" => 16,
				"totalItems" => 0
			)
		);
		// var_dump($data);
		$data_json = json_encode($data);
		// var_dump($data_json);

		$curl = curl_init($urlSearch);
		// curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
				'Accept: application/json',
				'Content-type: application/json',
				'Origin: https://www.laprocure.com',
				'Referer: https://www.laprocure.com',
				'Sec-Fetch-Dest: empty',
				'Sec-Fetch-Site: cross-site',
				'Sec-Fetch-Mode: cors',
				'nova-guid: 914d9f70-2406-baa3-3b68-38a3d43bbc6e'
			)
		);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);

		$response = curl_exec($curl);
		// var_dump($response);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// var_dump($status);

		if ( $status != 200 ) {
			die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		}
		$json_response = json_decode($response);
		// var_dump($json_response);

		$infoLivre = $json_response->products->list[0]->productAttributes;
		for ($i=0; $i < sizeof($infoLivre); $i++) { 
			if ($infoLivre[$i]->key == "SmallCover") {
				$thumbnail = $infoLivre[$i]->value;
			}
			if ($infoLivre[$i]->key == "Authors") {
				for ($indexAuthors=0; $indexAuthors < sizeof($infoLivre[$i]->value); $indexAuthors++) { 
					$authors[$indexAuthors] = $infoLivre[$i]->value[$indexAuthors]->name;
				}
			}
			if ($infoLivre[$i]->key == "Publishers") {
				$publisher = $infoLivre[$i]->value[0]->name;
			}
			if ($infoLivre[$i]->key == "RawCatalogResult") {
				$description = $infoLivre[$i]->value->resumeElectre.$infoLivre[$i]->value->quatriemeDeCouvertureResume;
			}
			if ($infoLivre[$i]->key == "Isbn") {
				$isbn = $infoLivre[$i]->value->isbn;
			}
		}
		$title = $json_response->products->list[0]->name;
		$ean = $json_response->products->list[0]->ean;
		$prix_float = $json_response->products->list[0]->price->withTax;
		
		curl_close($curl);
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <title>LibrAPIE - Détails du livre</title>
 </head>
 <body onload="javascript:document.getElementById('Prix').focus();">
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
		<div class="row">
			<div class="col-sm-2">
				<img src="<?php 
					if($thumbnail) {
						echo($thumbnail);
					} else {
						echo("https://books.google.fr/googlebooks/images/no_cover_thumb.gif");
					}
				?>" alt="Pas de couverture disponible"/>
			</div>
			<div class="col-sm-4">
			 	<form action="addBook.php" method="post">
					 <input type="hidden" name="Annee" id="Annee" value="<?php echo($annee) ?>">
					<p><b>Titre :</b> <?php
					 	if($_GET['manuel']){
							print("<input class='text' name='Titre' id='Titre'>");
						} else {
						print ($title);
						print('<input type="hidden" class="text" name="Titre" id="Titre" value="'.$title.'">');
						print("</p>");
						}
					?>
					<input type="hidden" name="URL" id="URL" value="<?php 
						if($thumbnail) {
							echo($thumbnail);
						} else {
							echo("https://books.google.fr/googlebooks/images/no_cover_thumb.gif");
						}
					?>"/>
			 		<p><b>ISBN / EAN :</b> <?php
						print($isbn." / ");
						print("<input class='text' name='EAN' id='EAN' value='".$ean."'/>");
						print("<input type='hidden' class='text' name='ISBN' id='ISBN' value='".$isbn."'/>");
					 ?></p>
			 		<!-- <input type="hidden" class="text" name="ISBN" id="ISBN" value="<?php print($xml->Items->Item->ItemAttributes->ISBN); ?>"/>
			 		<input type="hidden" class="text" name="EAN" id="EAN" value="<?php print($xml->Items->Item->ItemAttributes->EAN); ?>"/> -->
			 		<p><b>Auteur :</b> <?php
						for ($i = 0; $i < sizeof($authors); $i++){
							if(sizeof($authors) == 1) {
								print($authors[$i]);
							} else {
								if($i == sizeof($authors)-1){
									print($authors[$i]);
								} else {
									print($authors[$i]." ; ");
								}
							}
						}
						print("</p>");
						print('<input type="hidden" class="text" name="Auteur" id="Auteur" value="');
						for ($i = 0; $i < sizeof($authors); $i++){
							if(sizeof($authors) == 1) {
								print($authors[$i]);
							} else {
								if($i == sizeof($authors)-1){
									print($authors[$i]);
								} else {
									print($authors[$i].' ; ');
								}
							}
						}
						print('">');
					?>
					 <p><b>Editeur :</b> <?php
						if($_GET['manuel']){
							print("<input class='text' name='Editeur' id='Editeur'/>");
						} else {
							echo($publisher);
							print("</p>");
							print('<input type="hidden" class="text" name="Editeur" id="Editeur" value="'.$publisher.'">');
						}
					?>
					<?php
						$prix = explode(" ",$xml->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice)[1];
						if ($prix_float == 0.0) {
							$prix_float = str_replace(",", ".", $prix);
						}
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
			<div class="col-sm-6">
				<p><b>Description</b></p>
				<p><?php print($description)?></p>
			</div>
		</div>
	</div>
 </body>
 </html>
