<?php
  // Connexion a la base de donnees
  include("dbconf.php");

  $pseudo = $_GET['pseudo'];
  $pass = $_GET['pass'];
  $email = $_GET['email'];

  $pass_hache = sha1($pass);

  // $cnx = mysql_connect($host, $user, $mdp) or die("Connexion a mysql impossible : " . mysql_errno());
  // $cnx = mysqli_connect($host, $user, $mdp);
  
  $cnx = mysqli_connect($host, $user, $mdp);
  if(!cnx){
    die("Connexion a mysql impossible : ".$cnx->connect_error);
  }
  mysqli_select_db($cnx,$bdd);

  $query = "INSERT INTO membres(pseudo, pass, email) VALUES('$pseudo','$pass_hache','$email')";

  $result = $cnx->query($query) or die($cnx->error);
  print("Membre ".$pseudo." ajouté avec email ".$email.".");
?>
