<?php
   /*
     ---------------------------------------------------------------------------
     Projet      : STI Messenger
     Fichier     : logout.php
     Auteurs     : Thibault Schowing, S�bastien Henneberger
     Date        : 12.10.2016
     Description : Permet de supprimer la session utilisateur courante.
                   Puis, l'utilisateur est redirig� vers la page de login.
     ---------------------------------------------------------------------------
    */
   
   session_start();
   include("checkUserSession.php");
   session_destroy();
   header("Location: http://localhost/html/index.php");
   exit();
?>
