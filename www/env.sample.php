<?php
  $variables = [
      'DB_HOST' => 'mysql_server',
      'DB_USER' => 'user',
      'DB_MDP' => 'mdp',
      'DB_NAME' => 'db_name',
  ];

  foreach ($variables as $key => $value) {
      putenv("$key=$value");
  }
?>