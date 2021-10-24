<?php
  $EAN = $_POST['EAN'];
  $annee = $_POST['Annee'];

  // Connexion a la base de donnees
  include("dbconf.php");

  // $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
  // $cnx = mysqli_connect($host, $user, $mdp);
  
  $cnx = mysqli_connect($host, $user, $mdp);
  if(!cnx){
    die("Connexion a mysql impossible : ".$cnx->connect_error);
  }
  mysqli_select_db($cnx,$bdd);

  $query = "SELECT * FROM ".$annee."_APIE_Livres WHERE EAN=".$EAN;
  $result = $cnx->query($query) or die($cnx->error);

  while ($row = mysqli_fetch_object($result)) {
    $livres[] = $row;
  }

  // Récupération de la quantité en stock
  $query = "SELECT * FROM ".$annee."_APIE_Stock_Livres WHERE EAN=".$EAN;
  $result = $cnx->query($query) or die($cnx->error);

  while ($row = mysqli_fetch_object($result)) {
    $info[] = $row;
  }

  $livres[0]->Qte = $info[0]->Qte;
  $livres[0]->Cde = $info[0]->Cde;
  $livres[0]->QteCdee = $info[0]->QteCdee;
  $livres[0]->Rendu = $info[0]->Rendu;
  header('Content-type: application/json');
  $livre = json_encode($livres[0]);
  print_r($livre);
  exit();
?>
