<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : switchUserRole.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Permet de changer le rôle d'un utilisateur.
  ---------------------------------------------------------------------------
 */
?>

<?php

session_start();
include("checkAdminSession.php");
include("databaseConnection.php");
include("functions.php");

if (isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user']) && $_GET['CSRFToken'] == $_SESSION["CSRFtoken"]) {

    $userId = $_GET['user'];
    if (!verifyId($userId)) {
        //header("Location: http://localhost/admin.php?msg=noUser");
        //exit();
        echo "user not verified";
    }

    $nbAdmins = getNumberOfAdmin();
    $userRole = getUserRole($userId);
    $currentUserId = $_SESSION['userId'];

    // Si c'est un admin, on ne change pas son role s'il est le seul (de base on devrait jamais arriver ici vu qu'on peut pas se supprimer/changer de role soi-même)
    if ($userRole == 1 && $nbAdmins <= 1) {
        header("Location: http://localhost/html/admin.php?msg=oneAdmin");
        exit();
    }

    // Il doit y avoir au moins un admin, et on ne peut pas changer son propre status (admin/user)(1/0)
    // si true, on inverse 0 et 1 ou vice versa
    if ($userId != $currentUserId) {

        if ($userRole == 1) {
            setUserRole($userId, 0);
            header("Location: http://localhost/html/admin.php?msg=ok");
            exit();
        } else {
            setUserRole($userId, 1);
            header("Location: http://localhost/html/admin.php?msg=ok");
            exit();
        }
    } else {
        header("Location: http://localhost/html/admin.php?msg=self");
        exit();
    }
} else {
    header("Location: http://localhost/html/admin.php?msg=noUser");
    exit();
}
?>
