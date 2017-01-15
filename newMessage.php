<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : newMessage.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger
  Date        : 12.10.2016
  Description : Page permettant de rédiger un nouveau message.
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
include("checkUserSession.php");
include("functions.php");
//$from = $_SESSION['userId'];
?>

<!DOCTYPE HTML> 

<html>
    <head>
        <?php include("includes/header.php"); ?>
    </head>

    <body>  
        <?php
        include("includes/menu.php");

        //CSRF protection
        if (isset($_POST['list_deroulante']) && $_POST['CSRFToken'] == $_SESSION["CSRFtoken"]) {

            $id = getUserId($_POST['list_deroulante']);
            $message = $_POST['form_message'];
            $subject = $_POST['form_subject'];
            echo "To: " . $id . " , message: " . $message . " , " . $subject;
            sendMessage($id, $subject, $message);
            header("Location: http://localhost/html/messages.php");
            exit();
        }
        ?>

        <div class="container">
            <h1>STI Write a new message</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 
                <div class="form-group">
                    <label for="email">To:	  

                        <select name="list_deroulante" size="1" class="form-control"> 
                            <?php
                            $replyTo = "";
                            $option = "";
                            if (isset($_GET['message_receiver_id']) && verifyId($_GET['message_receiver_id'])) {
                                // c'est un reply -> on a le destinataire
                                $replyTo = getUserName($_GET['message_receiver_id']);
                            }

                            $users = getUsers();

                            while ($row = $users->fetch()) {

                                // Si l'option de la liste = le destinataire "reply"
                                if ($replyTo == $row['user_name']) {
                                    $option = "selected";
                                }
                                echo "<option " . $option . ">" . $row['user_name'] . "</option>";
                                $option = "";
                            }
                            ?>	

                        </select>			
                    </label>			
                </div>			  



                <div class="form-group">
                    <!--  Récupère le sujet du message auquel on répond TODO sécuriser le get !!!  -->
                    <label for="email">Subject:</label> 
                    <input type="text" name="form_subject" id="form_subject" placeholder="Subject" value="<?php
                            if (isset($_GET['message_subject'])) {
                                echo "re: " . htmlspecialchars($_GET['message_subject']);
                            }
                            ?>"/>
                </div>
                <textarea class="form-control" rows="5" type="text" name="form_message" id="form_message" placeholder="Type your message here" ></textarea>
                <br>
                </div>

                <br>
                <!--CSRF protection -->
				<?php 
				//Reload CSRF
				$_SESSION["CSRFtoken"] = md5(uniqid(mt_rand(), true)); ?>
                <input type="hidden" name="CSRFToken"value="<?php echo $_SESSION["CSRFtoken"]; ?>">

                <div class="container">
                    <input type="submit" class="btn" name="send" value="send">  
                </div>
            </form>
        </div>

    </div>
    <?php include("includes/footer.php"); ?>
</body>
</html>
