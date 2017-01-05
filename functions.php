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
	   
		function verifyId($id){
			include("databaseConnection.php");
			// Prepared statement
			$sql = "SELECT * from users WHERE user_id = :id AND user_deleted = 0";
			
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			
			$sth->execute(array(':id' => $id));
			
			$result = $sth->fetchAll();
			
			//print_r($result);
			$file_db = null;
			
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
	   
		function getUserName($id){
			include("databaseConnection.php");
		   
			$sql = "SELECT user_name FROM users WHERE user_id = :id";
		   
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $id));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			$file_db = null;
			return $result['user_name'];
	   } 
	   
	   /*
	   
	   */
	   
		function getUserRoleActive($userId){
			include("databaseConnection.php");
			$sql = "SELECT user_role, user_active FROM users WHERE user_id = :userId";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':userId' => $userId));
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			$file_db = null;
			return $result;
	   }
	   
		/*
		
		
		*/
		
		function getUserMessages($userId){
			include("databaseConnection.php");
			$sql = "SELECT * FROM messages WHERE message_receiver_id = :id ORDER BY message_time DESC";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $userId));
			$file_db = null;
			return $sth;
			
		}
	   

	   /*
		 R�cup�re les utilisateurs non supprim�s
		 Param�tre: aucun
		*/
	   
	   function getUsers(){
			include("databaseConnection.php");
			$sql = "SELECT * FROM users WHERE user_deleted = 0";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array());
			$file_db = null;
			// Le fetch se fait dans la page d'administration
			return $sth;
		   
	   }

	   /*
		 R�cup�re l'id d'un utilisateur �partir de son nom
		 Param�tre: $name
		*/
	   
	   
		function getuserId($name){
			include("databaseConnection.php");
			$sql = "SELECT user_id FROM users WHERE user_name = :name AND user_deleted = 0";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':name' => $name));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			//echo "<br/> debug: " . $result['user_id'];
			
			$file_db = null;
			if(!empty($result['user_id'])){
				return $result['user_id'];
			}
			else{
				return false;
			}
			
		}
		
		/*
			met � jour le mot de passe utilisateur
		
		*/
		
		function updatePassword($newPassordHash, $userID){
			include("databaseConnection.php");
			$sql = "UPDATE users SET user_pwd_hash = :newPasswordHash WHERE user_id = :userId";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			
			// Pas besoin de htmlspecialchars �tant donn� que c'est un hash
			$sth->execute(array(
				':newPasswordHash' => $newPassordHash,
				':userId' => $userID
			));
			
			$file_db = null;
			return;
			
		}
		
		/*
		R�cup�re le destinataire du message (pour v�rifier que c'est bien le destinataire qui supprime le message et pas quelqu'un d'autre)
		
		
		*/
		function getReceiverId($messageId){
			include("databaseConnection.php");
			$sql = "SELECT message_receiver_id FROM messages WHERE message_id = :messageId";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			
			if(is_numeric($messageId)){
				$sth->execute(array(':messageId' => $messageId));
				$result = $sth->fetch(PDO::FETCH_ASSOC);
				print_r($result);
				$file_db = null;
				return $result;
				//return $result->fetch();
			}
			else {
				$file_db = null;
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
	   
	   function sendMessage($userIdTo, $subject, $message){
			include("databaseConnection.php");
			$sql = "INSERT INTO messages (message_subject, message_message, message_sender_id , message_receiver_id) VALUES (:subject, :message, :sender, :userIdTo);";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(
				':subject' => htmlspecialchars($subject), 
				':message' => htmlspecialchars($message),
				':sender' => $_SESSION['userId'],
				':userIdTo' => htmlspecialchars($userIdTo)
			));
			$file_db = null;
			return;
	   }
	   

	   /*
		 R�cup�re l'�tat de l'utilisateur (actif ou non)
		 Param�tre: $userId
		*/
	   
	   
	   function getuserState($userId){
			include("databaseConnection.php");
			$sql = "SELECT user_active FROM users WHERE user_id = :id";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $userId));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			//echo "<br/> debug: " . $result['user_active'];
			$file_db = null;
			return $result['user_active'];
		}
		
		/*
		
		
		*/
		
		function getUser($name){
			include("databaseConnection.php");
			$sql = "SELECT user_id, user_pwd_hash, user_role, user_active, user_deleted FROM users WHERE user_name = :name";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':name' => $name));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			//echo "<br/> debug: " . $result['user_active'];
			$file_db = null;
			return $result;
			
		}
	   
	   

	   /*
		 Permet de fixer l'�tat d'un utilisateur (actif ou non)
		 Param�tres : $userId
		 $state
		*/
	   
	   function setUserstate($userId, $state) {
			include("databaseConnection.php");
			$sql = "UPDATE users SET user_active = :state WHERE user_id = :userId";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':state' => $state, ':userId' => $userId));
			
			$file_db = null;
			return;
	   }

	   /*
		 R�cup�re le r�le d'un utilisateur
		 Param�tre: $userId
		*/

	   
		function getUserRole($userId){
			include("databaseConnection.php");
			$sql = "SELECT user_role FROM users WHERE user_id = :id ";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $userId));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			//echo "<br/> debug: " . $result['user_role'];
			$file_db = null;
			return $result['user_role'];
			
		}
		
		/*
		 D�finit le r�le de l'utilisateur (user / admin) -> (0/1)
		 Param�tres: Id de l'utilisateur, 0 si user et 1 si admin
		*/

		
		function setUserRole($userId, $role){
			include("databaseConnection.php");
			$sql = "UPDATE users SET user_role = :role WHERE user_id = :userId";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':role' => $role, ':userId' => $userId));
			
			$file_db = null;
			return;
			
		}

	   /*
		 R�cup�re le nombre d'adimistrateurs actifs
		 Param�tre: aucun
		*/

	   function getNumberOfAdmin() {
		  include("databaseConnection.php");
		  $sql = "SELECT count(user_role) as nb FROM users GROUP BY user_role HAVING user_role = 1 AND user_active = 1";
		  $admins = $file_db->query($sql);
		  $admins->setFetchMode(PDO::FETCH_ASSOC);
		  $admins = $admins->fetch();
		  
		  $file_db = null;
		  return $admins['nb'];
	   }
	   
	   
		/*
		Cr�� un nouvel utilisateur
		*/
		function newUser($userName, $userPasswordHash, $role, $active, $deleted){
			include("databaseConnection.php");
			
			$sql = "INSERT INTO users (user_name, user_pwd_hash, user_role, user_active, user_deleted) VALUES (:userName, :user_pwd_hash, :user_role, :user_active, :user_deleted)";
            $sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(
				':userName' => htmlspecialchars($userName), 
				':user_pwd_hash' => $userPasswordHash,
				':user_role' => htmlspecialchars($role),
				':user_active' => htmlspecialchars($active),
				'user_deleted' => htmlspecialchars($deleted)				
				));
			
			$file_db = null;
			return; 
		}
	   
	   

	   
	?>
