<?php
  // Connexion a la base de donnees
  include("dbconf.php");

  $pseudo = $_POST['pseudo'];
  $pass = $_POST['pass'];

  $pass_hache = sha1($pass);

  // $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
  // $cnx = mysqli_connect($host, $user, $mdp);
  
  $cnx = mysqli_connect($host, $user, $mdp);
  if(!cnx){
    die("Connexion a mysql impossible : ".$cnx->connect_error);
  }
  mysqli_select_db($cnx,$bdd);
  
  $query = "SELECT id FROM membres WHERE pseudo='$pseudo' AND pass='$pass_hache'";
  $result = $cnx->query($query) or die($cnx->error);

  while ($row = mysqli_fetch_object($result)) {
  	$membres[] = $row;
  }

  if(!$membres){
    print("Mauvais identifiant ou mot de passe.");
  } else {
    session_start();
    $_SESSION['id'] = $membres[0]->id;
    $_SESSION['pseudo'] = $pseudo;
    print("ok");
  }
?>
