// Chargement de la base de données à partir de l'export d'un panier de la procure

<?php
    $csvFile = $_GET['csvFile'];
    $Choix = $_GET['Choix'];
    var_dump($csvFile);
    var_dump($Choix);
    
    // Connexion a la base de donnees
    include("dbconf.php");

    $cnx = mysqli_connect($host, $user, $mdp);
    if(!cnx){
      die("Connexion a mysql impossible : ".$cnx->connect_error);
    }
    mysqli_select_db($cnx,$bdd);

    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // var_dump($data);
            $EAN = $data[5];
            $Titre = addslashes($data[0]);
            $Auteur = addslashes($data[1]);
            // $Editeur = addslashes($data[2]." , ".$data[3]);
            $urlCouverture = "";
            $Editeur = addslashes($data[2]);
            $Prix = $data[12];
            $Qte = $data[6];

            // suppression de l'identifiant
            if (explode(",", $Auteur)[1] != "") {
                $Auteur = explode(",", $Auteur)[1];
                // suppression du dernier ]
                $Auteur = explode("]", $Auteur)[0]; 
            }

            // remplacement de ',' par '.' dans le tarif
            $Prix = str_replace(",", ".", $Prix);

            // interrogation La Procure pour URL de la couverture du Livre
            // La Procure
            $urlSearch = "https://api-procure-production.azurewebsites.net/Common/Catalog/SearchWithFacets";
            $data = array(
                'query' => $EAN,
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
            $urlCouverture = $json_response->products->list[0]->pictures[0]->absoluteUrl;

            curl_close($curl);

            // insertion dans tables Livres
            $queryLivres = "INSERT INTO `2022_APIE_Livres` (`EAN`, `urlCouverture`, `Titre`, `Auteur`, `Editeur`, `Prix`, `Categorie`, `Choix`) VALUES ('$EAN', '$urlCouverture', '$Titre', '$Auteur', '$Editeur', '$Prix', 'A', '$Choix')";
            // var_dump($queryLivres );
            $result = $cnx->query($queryLivres) or die($cnx->error);

            // insertion dans table Stock
            $queryStock = "INSERT INTO `2022_APIE_Stock_Livres` (`EAN`, `Titre`, `Prix`) VALUES ('$EAN', '$Titre', '$Prix')";
            // var_dump($queryStock);
            $result = $cnx->query($queryStock) or die($cnx->error);
        }
    }
?>