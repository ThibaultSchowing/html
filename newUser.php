<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : newUser.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Page permettant à un admin de créer un nouvel utilisateur.
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
include("checkAdminSession.php");
include("databaseConnection.php");
include("functions.php");
include("password.php");
?>

<!DOCTYPE HTML> 

<html>
    <head>
        <?php include("includes/header.php"); ?>
    </head>

    <body>  
        <?php
        include("includes/menu.php");

        $createUserBtn = isset($_POST['createUserBtn']) ? $_POST['createUserBtn'] : NULL;

        if ($createUserBtn) {
            $userName = $userPassword = $confirmPassword = "";

            // vérif des données formulaire + csrf token check + captcha (variable "nobot")
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userName"]) && isset($_POST["userPassword"]) && isset($_POST['confirmationPassword']) && isset($_POST['role']) && isset($_POST['nobot']) && $_POST['CSRFToken'] == $_SESSION["CSRFtoken"]) {
                $userName = $_POST["userName"];
                $userPassword = $_POST["userPassword"];
                $confirmationPassword = $_POST['confirmationPassword'];


                $role = $_POST['role'];

                // Check if user exists
                $userId = getUserId($_POST["userName"]);

                // echo "<br/>[debug] userId verify: " . $userId;
                if ($userId != false) {

                    echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong> This username is not available ! Choose another one ! </div></div>";
                } else {

                    // Check password length
                    if (strlen($userPassword) >= 8) {

                        // Check if password and confirmation password are identical
                        if ($userPassword == $confirmationPassword) {

                            $userPasswordHash = password_hash(htmlspecialchars($userPassword), PASSWORD_DEFAULT);

                            if ($role == 'Admin') {
                                $role = 1;
                            } else {
                                $role = 0;
                            }

                            $result = newUser($userName, $userPasswordHash, $role, 1, 0);

                            //echo "<h2>New user created successfully !</h2>";
                            header("Location: http://localhost/html/admin.php?msg=created");
                        } else {
                            echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong>  Passwords don't match ! </div></div>";
                        }
                    } else {
						echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong>  Password must contain at least 8 caracters ! </div></div>";
                    }
                }
            } else {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong> All fields must be filled ! </div></div>";
            }
        }
        ?>

        <div class="container">
            <h1>STI Create a new user</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 

                <div class="form-group">
                    <!-- <label for="form_name">Name</label> <br/> -->
                    <input type="text" name="userName" id="form_name" placeholder="Username"/>
                    </br>
                    </br>
                    <!-- <label for="form_newPassword">Password</label> -->

                    <input type="password" name="userPassword" id="form_newPassword" placeholder="Password"/>
                    </br>
                    </br>
                    <!-- <label for="form_confirmNewPassword">Confirm Password</label><br/> -->
                    <input type="password" name="confirmationPassword" id="form_confirmNewPassword" placeholder="Confirm password"/>
                    </br>
                    </br>

                    Admin <Input type = 'Radio' Name = 'role' value = 'Admin'>
                    &nbsp;
                    User <Input type = 'Radio' Name = 'role' value = 'User' checked="true">

                </div>
                <br/>
                <input type="checkbox" name="nobot" required="true">Je confirme être un humain.
				<br/>
                <!--CSRF protection -->
				<?php //Reload CSRF
				$_SESSION["CSRFtoken"] = md5(uniqid(mt_rand(), true)); ?>
                <input type="hidden" name="CSRFToken" value="<?php echo htmlspecialchars($_SESSION["CSRFtoken"]); ?>">
				<br/>
                <div class="container">
                    <input type="submit" class="btn" name="createUserBtn" value="Create user">  
                </div>
            </form>
        </div>


        <?php include("includes/footer.php"); ?>
    </body>
</html>
