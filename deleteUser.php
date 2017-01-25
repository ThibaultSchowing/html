<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : deleteUser.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Permet à un administrateur de supprimer un
  utilisateur (administrateur compris).
  Il doit toujours exister un administrateur actif.
  Un administrateur ne peut supprimer son propre compte.
  ---------------------------------------------------------------------------
 */

session_start();
include("checkAdminSession.php");
include("functions.php");

        $isDeletionOk = 0;

        // User we want to delete
        $userId = $_GET['userId'];

        if ($_SESSION['userId'] == $userId || !is_numeric($userId)) {
            header("Location: http://localhost/html/admin.php?msg=deletionError");
        } else {
			
            // Check if the user we want to delete is admin
            if (getUserRole($userId) == 1) {

                // Check if the user we want to delete is active
                if (getUserStatus($userId) == 1) {

                    // Check how many admin are active
                    $nbAdminActive = getNumberOfAdmin();
                    if ($nbAdminActive == 1) {
						header("Location: http://localhost/html/admin.php?msg=deletionErrorAdmin");
                    } else {
                        $isDeletionOk = 1;
                    }
                } else {
                    $isDeletionOk = 1;
                }
            } else {
                $isDeletionOk = 1;
            }

            if ($isDeletionOk == 1 && $_GET['CSRFToken'] == $_SESSION["CSRFtoken"]) {
				deleteUser($userId);
                header("Location: http://localhost/html/admin.php?msg=deleted");
            }
        }
        ?>