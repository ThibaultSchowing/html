<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : admin.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger
  Date        : 12.10.2016
  Description : Page d'accueil d'administration.
  Un administrateur a accès aux fonctions suivantes :
  - Ajout / Modification / Suppression d’un utilisateur.
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
include("checkAdminSession.php");
include("databaseConnection.php");
include("functions.php");
?>

<!DOCTYPE HTML> 

<html>

    <head>
        <?php include("includes/header.php"); ?>
    </head>

    <body>  
        <?php include("includes/menu.php"); ?>
		<?php //Reload CSRF
				$_SESSION["CSRFtoken"] = md5(uniqid(mt_rand(), true)); ?>

        <h1>STI Administrator page</h1>
        <h2></h2>

        <?php
        $users = getUsers();
        // Gestion des erreurs GET
        // Messages de confirmation de suppression de messages etc msg=stateSwitched
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] == "ok") {
                echo "<div class=\"container\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Success !</strong> Role switched.</div></div>";
            } else if ($_GET['msg'] == "self") {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error !</strong> You cannot change your own role.</div></div>";
            } else if ($_GET['msg'] == "noUser") {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error !</strong> No valid user.</div></div>";
            } else if ($_GET['msg'] == "oneAdmin") {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error !</strong> There must be at least one administrator. </div></div>";
            } else if ($_GET['msg'] == "created") {
                echo "<div class=\"container\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Yeah !</strong> User created successfully ! </div></div>";
            } else if ($_GET['msg'] == "deleted") {
                echo "<div class=\"container\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Yeah !</strong> User deleted successfully ! </div></div>";
            } else if ($_GET['msg'] == "pwdChanged") {
                echo "<div class=\"container\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Yeah !</strong> Password changed successfully ! </div></div>";
            } else if ($_GET['msg'] == "deletionErrorAdmin") {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error !</strong> Cannot delete the last admin ! </div></div>";
            } else if ($_GET['msg'] == "deletionError") {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error !</strong> A wild generic error appeared ! </div></div>";
            } else if ($_GET['msg'] == "stateSwitched") {
                echo "<div class=\"container\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Success !</strong> State switched successfully ! </div></div>";
            }
        }
        ?>
        <div style="overflow: auto;">
            <table class="table table-striped">
                <tr>
                    <th>User name</th>
                    <th>Reset password</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Delete</th>
                </tr>

                <?php
                while ($row = $users->fetch()) {
                    ?>

                    <tr>
                        <td> <?php echo $row['user_name']; ?> </td>
                        <td> <a class="btn btn-primary" href="changeUserPassword.php?userId=<?php echo $row['user_id']; ?>&userName=<?php echo $row['user_name']; ?>"> Change password </a> </td>
                        <td> <a class="btn btn-primary" href="switchUserState.php?user=<?php echo $row['user_id']; ?>&CSRFToken=<?php echo $_SESSION["CSRFtoken"]; ?> "> <?php
                                if ($row['user_active'] == 1) {
                                    echo "deactivate";
                                } else {
                                    echo "activate";
                                }
                                ?>
                            </a> </td>
                        <td> <?php
                            if ($row['user_role'] == 1) {
                                echo "Admin";
                            } else {
                                echo "User";
                            }
                            ?> </td>
                        <td> <a class="btn btn-primary" href="switchUserRole.php?user=<?php echo $row['user_id']; ?>&CSRFToken=<?php echo $_SESSION["CSRFtoken"]; ?> "> <?php
                                if ($row['user_role'] == 1) {
                                    echo "Make user";
                                } else {
                                    echo "Make admin";
                                }
                                ?>  </a> </td>
                        <td> <a class="btn btn-danger" href="deleteUser.php?userId=<?php echo $row['user_id']; ?>&CSRFToken=<?php echo $_SESSION["CSRFtoken"]; ?> "> Delete user </a> </td>
                    </tr>
                    <?php
                };
                ?>
            </table>
        </div>
        <a class="btn btn-primary" href="newUser.php"> Register new user </a>

        <?php include("includes/footer.php"); ?>

    </body>
</html>
