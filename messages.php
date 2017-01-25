<?php
/*
  ---------------------------------------------------------------------------
  Projet		 : STI Messenger
  Fichier	 : messages.php
  Auteurs	 : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date		 : 12.10.2016
  Description : Page permettant à l'utilisateur de traiter ses messages :
  - Ouverture des messages
  - Répondre à un utilisateur
  - Supprimer le message
  ---------------------------------------------------------------------------
 */
?>

<?php
session_start();
include("checkUserSession.php");
include("databaseConnection.php");
include("functions.php");

//Reload CSRF
$_SESSION["CSRFtoken"] = md5(uniqid(mt_rand(), true)); 
?>

<!DOCTYPE HTML> 

<html>
    <head>
        <?php include("includes/header.php"); ?>
    </head>

    <body>

        <?php include("includes/menu.php"); ?>
        <h1>STI Messenger - Boite de réception</h1>

        <?php
        // Messages de confirmation de suppression de messages etc
        if (isset($_GET['result'])) {
            if ($_GET['result'] == "deleted") {
                echo "<div class=\"container\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Success!</strong> Message deleted.</div></div>";
            } else if ($_GET['result'] == "notdeleted") {
                echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Message not deleted.</div></div>";
            } else {
                echo "<div class=\"container\"><div class=\"alert alert-danger\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error!</strong> Unknown parameter</div></div>";
            }
        }

        if (isset($_GET['msg'])) {
            if ($_GET['msg'] == "pwdChanged") {
                echo "<div class=\"container\"><div class=\"alert alert-success\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Yeah !</strong> Password changed successfully ! </div></div>";
            }
        }
        ?>

        <?php
        // take user messages
        $userId = $_SESSION['userId'];
        $result = getuserMessages($userId);
        ?>

        <table class="table table-striped">
            <tr>
                <th>Date</th>
                <th>Sender</th>
                <th>Subject</th>
                <th>Reply</th>
                <th>Delete</th>
                <th>Open</th>
            </tr>

<?php
foreach ($result as $row) {
    //echo "Row = ";
    //print_r($row);
    ?>
                <?php
                // Get the sender's user_name for each message
                /* $sql2 = 'SELECT user_name FROM users WHERE user_id = ' . $row['message_sender_id'];
                  global $file_db;
                  $name = $file_db->query($sql2);
                  $name->setFetchMode(PDO::FETCH_ASSOC);
                  $name = $name->fetch(); */

                $name = getUserName($row['message_sender_id']);
                ?>
				

                <tr>
					<?php echo "<td>" . $row['message_time'] . "</td>"; ?>
                    <?php echo "<td>" . $name . "</td>"; ?>
                    <?php echo "<td>" . $row['message_subject'] . "</td>"; ?>
                    <?php echo "<td><a class=\"btn btn-primary\" href=\"newMessage.php?message_receiver_id=" . htmlspecialchars($row['message_sender_id']) . "&message_subject=" . htmlspecialchars($row['message_subject']) . "\"> Reply </a></td>"; ?>
                    <?php echo "<td><a class=\"btn btn-primary\" href=\"deleteMessage.php?messageId=" . htmlspecialchars($row['message_id']) . "&CSRFToken=" . $_SESSION["CSRFtoken"] . "\"> Delete </a></td>"; ?>
                    <?php echo "<td><button data-toggle=\"collapse\" data-target=\"#message" . htmlspecialchars($row['message_id']) . "\"> Display / Hide </button></td>"; ?>

                </tr>

    <?php
}; //End foreach

echo "</table>";

//Pour obtenir tous les messages on rÃ©exÃ©cute un foreach (utile surtout pour l'id).
//$result = $file_db->query($sql);
$result = getuserMessages($userId);

foreach ($result as $row) {
    //On veut le nom de l'expÃ©diteur du message

    /* global $file_db;
      $name = $file_db->query($sql2);
      $name->setFetchMode(PDO::FETCH_ASSOC);
      $name = $name->fetch();
      $name = $name['user_name']; */

    $name = getUserName($row['message_sender_id']);
    ?>
                <div class="container">

    <?php echo "<div id=\"message" . $row['message_id'] . "\" class=\"collapse\">"; ?>
                    <?php echo "<table class=\"table table-bordered\"><tr class=\"active\"><td>From: " . $name . "</td></tr>"; ?>
                    <?php echo "<tr class=\"active\"><td>" . $row['message_subject'] . "</td>"; ?>
                    <?php echo "<tr class=\"info\"><td>" . $row['message_message'] . "</td>"; ?>
                    <?php echo "<tr class=\"info\"><td><a class=\"btn btn-primary\" href=\"newMessage.php?message_receiver_id=" . $row['message_sender_id'] . "&message_subject=" . $row['message_subject'] . "\"> Reply </a> "; ?>
                    <?php echo "<a class=\"btn btn-primary\" href=\"deleteMessage.php?messageId=" . htmlspecialchars($row['message_id']) . "&CSRFToken=" . $_SESSION["CSRFtoken"] . "\"> Delete </a>"; ?>
                    <?php echo "<button data-toggle=\"collapse\" data-target=\"#message" . htmlspecialchars($row['message_id']) . "\"> Display / Hide </button></td>"; ?>
                    <?php echo "</tr>"; ?>
                    <?php echo "</table>"; ?>
                    <?php echo "</div>" ?>
                </div>
                    <?php
                };
                ?>

            <?php include("includes/footer.php"); ?>
    </body>
</html>
