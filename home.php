<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : home.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Page d'accueil de l'utilisateur authentifié.
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
include("checkUserSession.php");
?>

<!DOCTYPE HTML> 

<html>
    <head>
        <meta charset="utf-8" />   
        <title>STI Messenger</title>   

        <?php include("includes/header.php"); ?>

    </head>

    <body>  
        <h1>STI Messenger</h1>
        <div class="container"><div class="alert alert-success"><strong>Wellcome!</strong> You are connected as <?php echo $_SESSION['userName']; ?></div></div>

        <?php include("includes/menu.php"); ?>
        <div class="container"><p></p></div>
                <?php include("includes/footer.php"); ?>

    </body>
</html>
