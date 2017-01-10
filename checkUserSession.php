<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : checkUserSession.php
  Auteurs     : Thibault Schowing, S�bastien Henneberger
  Date        : 12.10.2016
  Description : Permet de v�rifier si l'utilisateur est authentifi�.
  S'il n'est pas authentifi�, il sera redirig� � la page de
  login.
  ---------------------------------------------------------------------------
 */

session_start();
// Check if user session exists
if (!isset($_SESSION['userId'])) {
    header('Location: http://localhost/html/index.php');
    exit();
}
?>
