<?php
   /*
     ---------------------------------------------------------------------------
     Projet      : STI Messenger
     Fichier     : newUser.php
     Auteurs     : Thibault Schowing, S�bastien Henneberger
     Date        : 12.10.2016
     Description : Page permettant � un admin de cr�er un nouvel utilisateur.
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
       <?php include("includes/menu.php"); ?>	  

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
               User <Input type = 'Radio' Name = 'role' value = 'User'>

            </div>
            <br>
            <div class="container">
               <input type="submit" class="btn" name="createUserBtn" value="Create user">  
            </div>
         </form>
      </div>

      <?php
         $createUserBtn = isset($_POST['createUserBtn']) ? $_POST['createUserBtn'] : NULL;

         if ($createUserBtn) {
            $userName = $userPassword = $confirmPassword = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
               $userName = $_POST["userName"];
               $userPassword = $_POST["userPassword"];
               $confirmationPassword = $_POST['confirmationPassword'];
			   echo "<br/>[debug] post: ";
			   print_r($_POST);
               //$admin = $_POST["adminRole"];
               //$user = $_POST["userRole"];
			   $role = $_POST['role'];

               // Check if user exists
               global $file_db;
               $sql = "SELECT user_id FROM users WHERE user_name = '$userName'";
               $result = $file_db->query($sql);
               $result->setFetchMode(PDO::FETCH_ASSOC);
               $result = $result->fetch();

               if ($result['user_id']) {

                  echo "<div class=\"container\"><div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Krap !</strong> This username is not available ! Choose another one ! </div></div>";
               } else {

                  // Check password length
                  if ($userPassword != "") {

                     // Check if password and confirmation password are identical
                     if ($userPassword == $confirmationPassword) {

                        $userPasswordHash = password_hash($userPassword, PASSWORD_DEFAULT);

                        /*if ($admin == 'role') {
                           $role = 1;
                        } else {
                           $role = 0;
                        }*/

                        $sql = "INSERT INTO users (user_name, user_pwd_hash, user_role, user_active, user_deleted)
                                                           VALUES ('$userName', '$userPasswordHash', '$role', '1', '0')";
                        $result = $file_db->query($sql);

                        echo "<h2>New user created successfully !</h2>";
                        header("Location: http://localhost/html/admin.php?msg=created");
                     } else {
                        echo "<h2>Password and confirmation password must match !</h2>";
                     }
                  } else {
                     echo "<h2>Password must contain at least one caracter !</h2>";
                  }
               }
            }
         }
      ?>
      <?php include("includes/footer.php"); ?>
   </body>
</html>
