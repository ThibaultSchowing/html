<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : logout.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger
  Date        : 12.10.2016
  Description : Permet de supprimer la session utilisateur courante.
  Puis, l'utilisateur est redirigé vers la page de login.
  ---------------------------------------------------------------------------
 */

session_start();
include("checkUserSession.php");
// Verify CSRF Token in GET
if ($_GET['CSRFToken'] == $_SESSION["CSRFtoken"]) {
	$_SESSION = array();
    session_destroy();
}

header("Location: http://localhost/html/index.php");
exit();
?>
