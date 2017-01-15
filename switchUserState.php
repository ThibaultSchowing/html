<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : switchUserState.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger
  Date        : 12.10.2016
  Description : Permet de changer l'état d'un utilisateur (actif ou non).
  ---------------------------------------------------------------------------
 */

session_start();
include("checkAdminSession.php");
include("functions.php");



if (isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user']) && $_GET['CSRFToken'] == $_SESSION["CSRFtoken"]) {
    $userId = $_GET['user'];
    $userState = getUserState($userId);
    
    ($userState == 1) ? setUserState($userId, 0) : setUserState($userId, 1);
    header("Location: http://localhost/html/admin.php?msg=stateSwitched");
}
?>
