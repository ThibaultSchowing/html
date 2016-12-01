<?php
   /*
     ---------------------------------------------------------------------------
     Projet      : STI Messenger
     Fichier     : databaseConnection.php
     Auteurs     : Thibault Schowing, S�bastien Henneberger
     Date        : 12.10.2016
     Description : Permet de se connecter � la base de donn�e.
                   Chemin de la base de donn�e:
                   /var/www/databases/database.sqlite
     ---------------------------------------------------------------------------
    */
?>

<?php

   // Set default timezone
   date_default_timezone_set('UTC');

   try {
      // Create (connect to) SQLite database in file TO CHANGE IF USE LINUX

      //$file_db = new PDO('sqlite:/var/www/databases/database.sqlite');
	  $file_db = new PDO('sqlite:C:\wamp\www\phpLiteAdmin_v1-9-6\database.sqlite');
      // Set errormode to exceptions
      $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //echo "[DEBUG] Database connected !";
   } catch (PDOException $e) {
      // Print PDOException message
      echo $e->getMessage();
   }
?>
