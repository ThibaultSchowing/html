<?php
/*
  ---------------------------------------------------------------------------
  Projet      : STI Messenger
  Fichier     : initializeDataBase.php
  Auteurs     : Thibault Schowing, Sébastien Henneberger, Anastasia Zharkova
  Date        : 12.10.2016
  Description : Permet d'initialiser la base de donnée avec des données
  choisies.
  ---------------------------------------------------------------------------

 */
?>

<html>
    <head></head>
    <body>

        <?php
        // Compatibility for php 5.4.16 to use function password_hash
        require('password.php');

        // Set default timezone
        date_default_timezone_set('UTC');

        try {
            // Create (connect to) SQLite database in file
            $file_db = new PDO('sqlite:C:\wamp64\www\phpLiteAdmin_v1-9-6\database.sqlite');
            // Set errormode to exceptions
            $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "[DEBUG] Database database.sqlite connected and opened ! </br>";

            // Create table users
            $file_db->exec("CREATE TABLE IF NOT EXISTS users (
                    user_id INTEGER PRIMARY KEY, 
                    user_name TEXT, 
                    user_pwd_hash TEXT, 
                    user_role INTEGER,
		              user_active INTEGER,
                    user_deleted INTEGER)");

            // Create table messages
            $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                    message_id INTEGER PRIMARY KEY, 
                    message_subject TEXT, 
                    message_message TEXT, 
                    message_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		              message_sender_id INTEGER,
		              message_receiver_id INTEGER,
                    FOREIGN KEY (message_sender_id) REFERENCES users(user_id),
                    FOREIGN KEY (message_receiver_id) REFERENCES users(user_id))");

            echo "[DEBUG] Tables users and messages created ! </br>";

            // Password hash creation
            $user1_pwd_hash = password_hash("thibault", PASSWORD_DEFAULT);
            $user2_pwd_hash = password_hash("sebastien", PASSWORD_DEFAULT);
            $user3_pwd_hash = password_hash("bob", PASSWORD_DEFAULT);

            // Array with some test data to insert to database
            $users = array(
                array('user_name' => 'Thibault',
                    'user_pwd_hash' => $user1_pwd_hash,
                    'user_role' => 0,
                    'user_active' => 1,
                    'user_deleted' => 0),
                array('user_name' => 'Sebastien',
                    'user_pwd_hash' => $user2_pwd_hash,
                    'user_role' => 0,
                    'user_active' => 1,
                    'user_deleted' => 0),
                array('user_name' => 'Bob',
                    'user_pwd_hash' => $user3_pwd_hash,
                    'user_role' => 1,
                    'user_active' => 1,
                    'user_deleted' => 0)
            );

            // Array with some test data to insert to database             
            $messages = array(
                array('message_subject' => 'Hello',
                    'message_message' => 'Hello Thibault ! Just a little hello for testing.',
                    'message_sender_id' => 2,
                    'message_receiver_id' => 1),
                array('message_subject' => 'What ?',
                    'message_message' => 'What are you testing ?',
                    'message_sender_id' => 1,
                    'message_receiver_id' => 2),
                array('message_subject' => 'STI',
                    'message_message' => 'A crazy project for STI course.',
                    'message_sender_id' => 2,
                    'message_receiver_id' => 1),
                array('message_subject' => 'OK',
                    'message_message' => 'Yeah, good luck, see you soon !.',
                    'message_sender_id' => 1,
                    'message_receiver_id' => 2)
            );

            foreach ($users as $u) {
                // $formatted_time = date('Y-m-d H:i:s', $m['time']);
                $file_db->exec("INSERT INTO users (user_name, user_pwd_hash, user_role, user_active, user_deleted)
                VALUES ('{$u['user_name']}', '{$u['user_pwd_hash']}', '{$u['user_role']}', '{$u['user_active']}', '{$u['user_deleted']}')");
            }

            foreach ($messages as $m) {
                // $formatted_time = date('Y-m-d H:i:s', $m['time']);
                $file_db->exec("INSERT INTO messages (message_subject, message_message, message_sender_id, message_receiver_id)
                VALUES ('{$m['message_subject']}', '{$m['message_message']}', '{$m['message_sender_id']}', '{$m['message_receiver_id']}')");
            }

            echo "[DEBUG] Tables users and messages initialized ! </br>";

            // Close file db connection
            $file_db = null;

            // TO CHANGE WHEN USE LINUX !!!
            // Add permissions to database.sqlite
            //chmod("/var/www/databases/database.sqlite", 0777);

            echo "[DEBUG] Database connection closed ! </br>";
        } catch (PDOException $e) {
            // Print PDOException message
            echo $e->getMessage();
        }
        ?>

    </body>
</html>
