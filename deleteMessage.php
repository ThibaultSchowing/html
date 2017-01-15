<?php

/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : deleteMessage.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger
  Date        : 12.10.2016
  Description : Permet de supprimer le mesage dont l'id est passé en
  paramètre à l'url.
  ---------------------------------------------------------------------------
 */
?>

<?php

session_start();
include("checkUserSession.php");
include("functions.php");
$messageId = $_GET['messageId'];

// Get the message receiver id	
$result = getReceiverId($messageId);

$messageReceiverId = $result['message_receiver_id'];


// Check if user session exists and if the user has the right to delete the message
if ($_SESSION['userId'] == $messageReceiverId && $_GET['CSRFToken'] == $_SESSION["CSRFtoken"]) {
    
	deleteMessage($messageId);

    header('Location: http://localhost/html/messages.php?result=deleted');
    exit();
} else {
    
    header('Location: http://localhost/html/messages.php?result=notdeleted');
    exit();
}
?>
