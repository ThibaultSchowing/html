<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : index.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Page d'accueil de l'application web.
  Permet uniquement à un utilisateur de s'authentifier.
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
require('password.php');
include('functions.php');
include("databaseConnection.php");
?>

<?php
$logInBtn = isset($_POST['logInBtn']) ? $_POST['logInBtn'] : NULL;

if ($logInBtn) {
    $userName = $userPassword = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userName = $_POST["userName"];
        $userPwd = $_POST["userPwd"];
        $captcha = $_POST["captcha"];

        // Check if user exists in DB

        if ($captcha == $_SESSION["captcha"]) {
            echo $_POST;

            $result = getUser($userName);

            // Check if user exists
            if ($result['user_id']) {

                // Check if user isn't deleted
                if ($result['user_deleted'] != 1) {

                    // Check if user is active
                    $userActive = $result['user_active'];
                    if ($userActive != 0) {

                        $user_pwd_hash = $result['user_pwd_hash'];

                        // Check if password entered references the password hash in database
                        if (password_verify($userPwd, $user_pwd_hash)) {

                            // User session creation
                            $_SESSION['userName'] = $userName;
                            $_SESSION['userId'] = $result['user_id'];
                            $_SESSION['userRole'] = $result['user_role'];

                            //TODO - create CSRF Token here
                            //
								//
								//
								//
								// make a random id then used to protect forms POST
                            $_SESSION["CSRFtoken"] = md5(uniqid(mt_rand(), true));

                            header('Location: http://localhost/html/home.php');
                            exit();
                        } else {
                            echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong>  Username and/or password entered are incorrect ! </div></div>";
                        }
                    } else {
                        echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong>  <h2>Account desactivated, contact your administrator !</div></div>";
                    }
                } else {
                    echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong>  User have been deleted ! Go away ! </div></div>";
                }
            } else {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong>  Username and/or password entered are incorrect ! </div></div>";
            }
        } else {
            echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong>  Remplissez tous les champs ! </div></div>";
        }
    }
}
?>

<!DOCTYPE HTML> 

<html>
    <head>
        <meta charset="utf-8" />      
        <title>STI Messenger</title>
<?php include("includes/header.php"); ?>
    </head>

    <body>  
        <div class="wrapper" style="min-height: 100%;
             height: auto !important; /* This line and the next line are not necessary unless you need IE6 support */
             height: 100%;
             margin: 0 auto -155px; /* the bottom margin is the negative value of the footer's height */">
            <div class="container">
                <h1>STI Messenger</h1>
                <h4>Please, Log In to enjoy !</h4>
                <div class="container">
                    <form method="post">  
                        <div class="container">
                            <input type="text" name="userName" id="userName" placeholder="Username"/>
                            <br>
                            <input type="password" name="userPwd" id="userPassword" placeholder="Password"/>
                            <br><br>
                            <label for="captcha">Recopiez le mot : "<?php echo captcha(); ?>"</label>
                            <br>
                            <input type="text" name="captcha" id="captcha" /><br />

                        </div>
                        <br>
                        <div class="container">
                            <input type="submit" class="btn" name="logInBtn" value="Log in">  
                        </div>
                    </form>
                </div>
            </div>


<?php include("includes/footer.php"); ?>
    </body>
</html>
