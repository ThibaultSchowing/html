<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : changeUserPassword.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Page permettant à un admin de changer le mot de passe d'un
  utilisateur.
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
include("checkAdminSession.php");
require('password.php');
include("databaseConnection.php");
include("functions.php");
// User that the admin wants to change password
$userId = $_GET['userId'];
$userName = $_GET['userName'];
?>

<!DOCTYPE HTML> 

<html>

    <head>

        <meta charset="utf-8" />

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <title>Change password</title>

    </head>

    <body>  
        <?php include("includes/menu.php"); 
		
        $changePasswordBtn = isset($_POST['changePasswordBtn']) ? $_POST['changePasswordBtn'] : NULL;

        // If changePasswordBtn was clicked 
		
		
        if ($changePasswordBtn && $_POST['CSRFToken'] == $_SESSION["CSRFtoken"]) {
            $newPassword = $confirmNewPassword = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $newPassword = $_POST["newPassword"];
                $confirmNewPassword = $_POST["confirmNewPassword"];

                if (strlen($newPassword) >= 8) {

                    // Check if confirmation new password is ok
                    if ($newPassword == $confirmNewPassword) {

                        // Update password in DB 
                        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                        updatePassword($newPasswordHash, $userId);

                        header("Location: http://localhost/html/admin.php?msg=pwdChanged");
                    } else {
                        echo "<div class=\"container\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Confirmation password doesn't match new password !</div></div>";
                    }
                } else {
                    echo "<div class=\"container\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Your new password must contain at least 8 caracters !</div></div>";
                }
            }
        }
        ?>

        <h1>STI Messenger</h1>

        <h2><?php echo $_SESSION['userName']; ?>, you can change <?php echo $userName; ?>'s password here !</h2>

        <form method="post">  

            <div class="container">
                <input type="password" name="newPassword" id="newPassword" placeholder="New password"/>
                <br>
                <input type="password" name="confirmNewPassword" id="confirmNewPassword" placeholder="Confirm new password"/>
            </div>

            <br>
            <!--CSRF protection -->
			<?php 
				//Reload CSRF
				$_SESSION["CSRFtoken"] = md5(uniqid(mt_rand(), true)); ?>
            <input type="hidden" name="CSRFToken"value="<?php echo $_SESSION["CSRFtoken"]; ?>">

            <div class="container">
                <input type="submit" class="btn" name="changePasswordBtn" value="Change the password">  
            </div>

        </form>


        <?php include("includes/footer.php"); ?>
    </body>
</html>
