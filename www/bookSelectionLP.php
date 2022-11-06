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
				// $baseURL = "https://www.googleapis.com/books/v1/volumes?q=isbn:";
				header('Location: ' . 'bookSearchLP.php?&bookId='.$keywords, true, $statusCode);
			} else {						
				// La Procure
				$urlSearch = "https://api-procure-production.azurewebsites.net/Common/Catalog/SearchWithFacets";
				$data = array(
					'query' => $keywords,
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

				$books_list = $json_response->products->list;
				// var_dump($books_list);

				curl_close($curl);

				// Google
				$baseURL = "https://www.googleapis.com/books/v1/volumes?q=";
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
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
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
					if (sizeof($books_list) > 0) {
						for($i=0; $i < sizeof($books_list); $i++){
							if($books_list[$i]->ean != "") {
							   // recuperation des informations de chaque livres
							   $infoLivre = $books_list[$i]->productAttributes;
							   for ($j=0; $j < sizeof($infoLivre); $j++) { 
								   if ($infoLivre[$j]->key == "SmallCover") {
									   $thumbnail = $infoLivre[$j]->value;
								   }
								   if ($infoLivre[$j]->key == "Authors") {
									   for ($indexAuthors=0; $indexAuthors < sizeof($infoLivre[$j]->value); $indexAuthors++) { 
										   $authors[$indexAuthors] = $infoLivre[$j]->value[$indexAuthors]->name;
									   }
								   }
								   if ($infoLivre[$j]->key == "Publishers") {
									   $publisher = $infoLivre[$j]->value[0]->name;
								   }
								   if ($infoLivre[$j]->key == "Isbn") {
									   $isbn = $infoLivre[$j]->value->isbn;
								   }
							   }
							   $title = $books_list[$i]->name;
							   $ean = $books_list[$i]->ean;
							   $description = $books_list[$i]->description;
							   $prix_float = $books_list[$i]->price->withTax;
   
								print("<form action='bookSearchLP.php' method='get'>");
								print("<input type='hidden' name='bookId' id='bookId' value='".$ean."'/>");
								print("<li>");
							   print("<img src='".$thumbnail."' alt='Pas de couverture disponible'/>");
							   print("</li>");
							   print("<a href='https://www.laprocure.com/product/".$books_list[$i]->id."' target='_blank'>".$title." (".$authors[0].")</a>");
							   print("<p>Editions : ".$publisher."<br>");
							   print("<B>Prix : ".$prix_float." €</B></p>");
							   print("<p>".$description."</p>");
								print("<button type='submit' class='btn btn-default'>");
							   print("sélectionner");
							   print("</button>");
								print("</form>");
						   }
						}
					} else {
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
					}
		 		?>
			</div>
		</div>
	 </body>
 </html>
