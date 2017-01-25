<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : databaseConnection.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Permet de se connecter à la base de donnée.
  Chemin de la base de donnée:
  /var/www/databases/database.sqlite
  ---------------------------------------------------------------------------
 */
?>

<?php

// Set default timezone
date_default_timezone_set('UTC');

try {
    // Create (connect to) SQLite database in file TO CHANGE IF USE LINUX
	// If you use the CentOS machine, switch the lines below
	
    //$file_db = new PDO('sqlite:/var/www/databases/database.sqlite');
    $file_db = new PDO('sqlite:C:\wamp64\www\phpLiteAdmin_v1-9-6\database.sqlite');
	
	
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
