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
		   global $file_db;
		   // Prepared statement
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
	   
		function getUserName($id){
			global $file_db;
		   
			$sql = "SELECT user_name FROM users WHERE user_id = :id";
		   
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $id));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			
			return $result['user_name'];
	   } 
	   
		/*
		
		
		*/
		
		function getUserMessages($userId){
			global $file_db;
			$sql = "SELECT * FROM messages WHERE message_receiver_id = :id ORDER BY message_time DESC";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $userId));
			return $sth;
			
		}
	   

	   /*
		 R�cup�re les utilisateurs non supprim�s
		 Param�tre: aucun
		*/
	   
	   function getUsers(){
			global $file_db;
			$sql = "SELECT * FROM users WHERE user_deleted = 0";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array());
			
			// Le fetch se fait dans la page d'administration
			return $sth;
		   
	   }

	   /*
		 R�cup�re l'id d'un utilisateur �partir de son nom
		 Param�tre: $name
		*/
	   
	   
		function getuserId($name){
			global $file_db;
			$sql = "SELECT user_id FROM users WHERE user_name = :name AND user_deleted = 0";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':name' => $name));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			echo "<br/> debug: " . $result['user_id'];
			
			if(!empty($result['user_id'])){
				return $result['user_id'];
			}
			else{
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
			global $file_db;
			$sql = "INSERT INTO messages (message_subject, message_message, message_sender_id , message_receiver_id) VALUES (:subject, :message, :sender, :userIdTo);";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(
				':subject' => htmlspecialchars($subject), 
				':message' => htmlspecialchars($message),
				':sender' => $_SESSION['userId'],
				':userIdTo' => htmlspecialchars($userIdTo)
			));
				
			return;
	   }
	   

	   /*
		 R�cup�re l'�tat de l'utilisateur (actif ou non)
		 Param�tre: $userId
		*/
	   
	   
	   function getuserState($userId){
			global $file_db;
			$sql = "SELECT user_active FROM users WHERE user_id = :id";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $userId));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			echo "<br/> debug: " . $result['user_active'];
			
			return $result['user_active'];
		}
		
		/*
		
		
		*/
		
		function getUser($name){
			global $file_db;
			$sql = "SELECT user_id, user_pwd_hash, user_role, user_active, user_deleted FROM users WHERE user_name = :name";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':name' => $name));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			echo "<br/> debug: " . $result['user_active'];
			
			return $result;
			
		}
	   
	   

	   /*
		 Permet de fixer l'�tat d'un utilisateur (actif ou non)
		 Param�tres : $userId
		 $state
		*/
	   
	   function setUserstate($userId, $state) {
			global $file_db;
			$sql = "UPDATE users SET user_active = :state WHERE user_id = :userId";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':state' => $state, ':userId' => $userId));
			return;
	   }

	   /*
		 R�cup�re le r�le d'un utilisateur
		 Param�tre: $userId
		*/

	   
		function getUserRole($userId){
			global $file_db;
			$sql = "SELECT user_role FROM users WHERE user_id = :id ";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   
			$sth->execute(array(':id' => $userId));
			
			// R�sultats associatif -> par nom (je crois)
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			
			echo "<br/> debug: " . $result['user_role'];
			
			return $result['user_role'];
			
		}
		
		/*
		 D�finit le r�le de l'utilisateur (user / admin) -> (0/1)
		 Param�tres: Id de l'utilisateur, 0 si user et 1 si admin
		*/

		
		function setUserRole($userId, $role){
			global $file_db;
			$sql = "UPDATE users SET user_role = :role WHERE user_id = :userId";
			$sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':role' => $role, ':userId' => $userId));
			return;
			
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
		Cr�� un nouvel utilisateur
		*/
		function newUser($userName, $userPasswordHash, $role, $active, $deleted){
			$file_db;
			
			$sql = "INSERT INTO users (user_name, user_pwd_hash, user_role, user_active, user_deleted) VALUES (:userName, :user_pwd_hash, :user_role, :user_active, :user_deleted)";
            $sth = $file_db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(
				':userName' => htmlspecialchars($userName), 
				':user_pwd_hash' => $userPasswordHash,
				':user_role' => htmlspecialchars($role),
				':user_active' => htmlspecialchars($active),
				'user_deleted' => htmlspecialchars($deleted)				
				));
			
			return; 
		}
	   
	   

	   
	?>
