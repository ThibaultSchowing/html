<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : checkAdminSession.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Permet de vérifier si l'utilisateur authentifié est admin.
  S'il s'agit d'un utilisateur, il sera redirigé à la page de
  login.
  ---------------------------------------------------------------------------
 */

session_start();
// Check if admin session exists
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 1) {
    header('Location: http://localhost/html/index.php');
	//header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
    exit();
}
?>