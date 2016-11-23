<?php
   /*
     ---------------------------------------------------------------------------
     Projet      : STI Messenger
     Fichier     : functions.php
     Auteurs     : Thibault Schowing, S�bastien Henneberger
     Date        : 12.10.2016
     Description : Fonctions permettant de manipuler la base de donn�e :
                     - verifyId()
                     - getUserName()
                     - getUsers()
                     - getUserId()
                     - sendMessage()
                     - getUserState()
                     - setUserState()
                     - getUserRole()
                     - getNumberOfAdmin()
                     - setUserRole()
					 
	Utile: http://php.net/manual/fr/mysqli-stmt.bind-param.php
     ---------------------------------------------------------------------------
    */


   /*
     V�rifie qu'un id correspond bien �un utilisateur non supprim�
     Param�tre: $id
    */

	
   /* function verifyId($id) {
      global $file_db;
      //TODO use prepared statment
      $sql = "SELECT * from users WHERE user_id = " . $id . " AND user_deleted = 0";
      echo "[debug sql]" . $sql;
      $result = $file_db->query($sql);
      $result->setFetchMode(PDO::FETCH_ASSOC);
      print_r($result);
      $result = $result->fetch();
      print_r($result);
      if (empty($result)) {
         return false;
      } else {
         return true;
      }
   } */
   
    function verifyId($id){
	   global $file_db;
	   // Prepared statement, stage 1: prepare
		$sql = "SELECT * from users WHERE user_id = :id AND user_deleted = 0";
		
		$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		
		$sth->execute(array(':id' => $id));
		
		$result = $sth->fetchAll();
		
		print_r($result);
		
		if (empty($result)) {
			return false;
		} else {
			return true;
		}
		
	   
   } 

   /*
     R�cup�re le nom d'utilisateur �partir de l'id
     Param�tre: $id
    */

   /* function getUserName($id) {
      global $file_db;
      //TODO use prepared statment 
      $sql = "SELECT user_name FROM users WHERE user_id = " . $id;
      //echo "<br/>[debug] sql: ". $sql;
      $name = $file_db->query($sql);
      $name->setFetchMode(PDO::FETCH_ASSOC);
      $name = $name->fetch();
      $name = $name['user_name'];
      return $name;
   } */
   
    function getUserName($id){
	   global $file_db;
	   
		$sql = "SELECT user_name FROM users WHERE user_id = :id";
	   
		$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	   
		$sth->execute(array(':id' => $id));
		
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		
		
		return $result['user_name'];
   } 
   

   /*
     R�cup�re les utilisateurs non supprim�s
     Param�tre: aucun
    */

   function getUsers() {
      $sql = "SELECT * FROM users WHERE user_deleted = 0";
      //echo "<br/>[debug]". $sql;
      global $file_db;
      $result = $file_db->query($sql);
      $result->setFetchMode(PDO::FETCH_ASSOC);
      //$result = $result->fetch();
      //echo "<br/>[debug] result: ";
      //print_r( $result);
      return $result;
   }

   /*
     R�cup�re l'id d'un utilisateur �partir de son nom
     Param�tre: $name
    */

   function getUserId($name) {
      global $file_db;
      //TODO use prepared statment
      $sql = "SELECT user_id FROM users WHERE user_name = '" . $name . "' AND user_deleted = 0";
      echo "<br/>[debug] sql: ". $sql;
      $id = $file_db->query($sql);

      $id->setFetchMode(PDO::FETCH_ASSOC);
      $id = $id->fetch();
	  echo "<br/>[debug] in getuserId : ". $id['user_id'];

      if (isset($id['user_id'])) {
         // row exists. do whatever you would like to do.
         $id = $id['user_id'];
         //echo "[debug] In getUserId -> " . $id;
         return $id;
      } else {
         echo "<br/>[debug] returning false, user id don't exist - fct = getUserId(name)";
         return false;
      }
   }

   /*
     Envoie un message
     Param�tres : $UserIdTo (id du destinataire)
     $subject (sujet du message)
     $message (message)
     Remarque   : l'id de l'exp�diteur est obtenue ci-dessous par la variable
     session.
    */

   function sendMessage($userIdTo, $subject, $message) {
      // Enregistre dans DB
      echo "<br/>[debug] Saving message in database";

      global $file_db;
      //TODO use prepared statment
      $sql = "INSERT INTO messages (message_subject, message_message, message_sender_id , message_receiver_id)
		VALUES ('" . $subject . "','" . $message . "'," . $_SESSION['userId'] . "," . $userIdTo . ")";

      //echo "<br/>[debug] sql: ". $sql;
      $file_db->exec($sql);

      return;
   }

   /*
     R�cup�re l'�tat de l'utilisateur (actif ou non)
     Param�tre: $userId
    */

   function getUserState($userId) {
      global $file_db;
      //TODO user prepared statment
      $sql = "SELECT user_active FROM users WHERE user_id = " . $userId;
      $state = $file_db->query($sql);
      $state->setFetchMode(PDO::FETCH_ASSOC);
      $state = $state->fetch();
      $state = $state['user_active'];
      return $state;
   }

   /*
     Permet de fixer l'�tat d'un utilisateur (actif ou non)
     Param�tres : $userId
     $state
    */

   function setUserState($userId, $state) {
      global $file_db;
      //TODO user prepared statment
      $sql = "UPDATE users SET user_active = " . $state . " WHERE user_id = " . $userId;
      echo "<br/>[debug] setUserState request: " . $sql;
      $file_db->exec($sql);
      echo "<br/>[debug] request done";
      return;
   }

   /*
     R�cup�re le r�le d'un utilisateur
     Param�tre: $userId
    */

   function getUserRole($userId) {
      global $file_db;
      //TODO use prepared statment
      $sql = "SELECT user_role FROM users WHERE user_id = " . $userId;
      $role = $file_db->query($sql);
      print_r($role);
      $role->setFetchMode(PDO::FETCH_ASSOC);
      $role = $role->fetch();
      $role = $role['user_role'];
      return $role;
   }

   /*
     R�cup�re le nombre d'adimistrateurs actifs
     Param�tre: aucun
    */

   function getNumberOfAdmin() {
      global $file_db;
      $sql = "SELECT count(user_role) as nb FROM users GROUP BY user_role HAVING user_role = 1 AND user_active = 1";
      $admins = $file_db->query($sql);
      $admins->setFetchMode(PDO::FETCH_ASSOC);
      $admins = $admins->fetch();
      return $admins['nb'];
   }

   /*
     D�finit le r�le de l'utilisateur (user / admin) -> (0/1)
     Param�tres: Id de l'utilisateur, 0 si user et 1 si admin
    */

   function setUserRole($userId, $role) {
      global $file_db;
      //TODO user prepared statment
      $sql = "UPDATE users SET user_role = " . $role . " WHERE user_id = " . $userId;
      echo "<br/>[debug] setUserRole request: " . $sql;
      $file_db->exec($sql);
      echo "<br/>[debug] request done";
      return;
   }
?>
