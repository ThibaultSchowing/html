<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : changeOwnPassword.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Page permettant à un utilisateur de changer de mot de passe.
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
include("checkUserSession.php");
require('password.php');
include('functions.php');
include("databaseConnection.php");
?>

<!DOCTYPE HTML> 

<html>

    <head>
        <meta charset="utf-8" />      
        <?php include("includes/header.php"); ?>
        <title>Change password</title>
    </head>

    <body>  

        <?php include("includes/menu.php"); ?>
        <?php
        $changePasswordBtn = isset($_POST['changePasswordBtn']) ? $_POST['changePasswordBtn'] : NULL;

        // If changePasswordBtn was clicked 
        if ($changePasswordBtn && $_POST['CSRFToken'] == $_SESSION["CSRFtoken"]) {
            $oldPassword = $newPassword = $confirmNewPassword = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $oldPassword = $_POST["oldPassword"];
                $newPassword = $_POST["newPassword"];
                $confirmNewPassword = $_POST["confirmNewPassword"];

                if (strlen($newPassword) >= 8) {

                    // Check if old password hash and password hash which is stocked in DB are identical
                    $userId = $_SESSION['userId'];
                    $sql = "SELECT user_pwd_hash FROM users WHERE user_id = '$userId'";
                    global $file_db;
                    $result = $file_db->query($sql);
                    $result->setFetchMode(PDO::FETCH_ASSOC);
                    $result = $result->fetch();

                    $user_pwd_hash = $result['user_pwd_hash'];

                    if (password_verify($oldPassword, $user_pwd_hash)) {

                        // Check if confirmation new password is ok
                        if ($newPassword == $confirmNewPassword) {

                            if ($oldPassword != $newPassword) {

                                // Update password in DB 
                                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                                //
                                updatePassword($newPasswordHash, $userId);
                                header("Location: http://localhost/html/messages.php?msg=pwdChanged");
                            } else {
                                echo "<div class=\"container\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Your new password must be different than your old password !</div></div>";
                            }
                        } else {
                            echo "<div class=\"container\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Your confirmation password doesn't match your new password !</div></div>";
                        }
                    } else {
                        echo "<div class=\"container\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Your password is wrong !</div></div>";
                    }
                } else {
                    echo "<div class=\"container\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Your new password must contain at least 8 caracters !</div></div>";
                }
            }
        }
        ?> 

        <div class="container">
            <h1>STI Messenger</h1>
            <h2><?php echo $_SESSION['userName']; ?>, you can change your password here !</h2>
        </div>

        <form method="post">  

            <div class="container">
                <div class="form-group">
                    <input type="password" name="oldPassword" id="oldPassword" placeholder="Old password"/>
                </div>
                <div class="form-group">
                    <input type="password" name="newPassword" id="newPassword" placeholder="New password"/>
                    <br>
                    <input type="password" name="confirmNewPassword" id="confirmNewPassword" placeholder="Confirm new password"/>
                </div>
            </div>

            <br>
            <!--CSRF protection -->
			<?php 
				//Reload CSRF
				$_SESSION["CSRFtoken"] = md5(uniqid(mt_rand(), true)); ?>
            <input type="hidden" name="CSRFToken"value="<?php echo $_SESSION["CSRFtoken"]; ?>">
            <div class="container">
                <div class="form-group">
                    <input type="submit" class="btn" name="changePasswordBtn" value="Change my password">  
                </div>
            </div>

        </form>


        <?php include("includes/footer.php"); ?>
    </body>
</html>
