<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : checkAdminSession.php
  Auteurs     : Thibault Schowing, S�bastien Henneberger
  Date        : 12.10.2016
  Description : Permet de v�rifier si l'utilisateur authentifi� est admin.
  S'il s'agit d'un utilisateur, il sera redirig� � la page de
  login.
  ---------------------------------------------------------------------------
 */

session_start();
// Check if admin session exists
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] != 1) {
    header('Location: http://localhost/html/index.php');
    exit();
}
?>