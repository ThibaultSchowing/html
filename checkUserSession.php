<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : checkUserSession.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Permet de vérifier si l'utilisateur est authentifié.
  S'il n'est pas authentifié, il sera redirigé à la page de
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
