# Set up and user manual

## Set up

1. Clone the repo https://github.com/Shenn299/html.git  in /var/www/ AFTER having removed the previous <i>http</i> folder.

2. Run `sudo systemctl start httpd`

3. run `php /var/www/html/initializeDataBase.php` once or open your web brother and go to the page: localhost/initializeDataBase.php. This will create a database containing three users/passwords couples:

  * <b>Bob/bob</b> the administrator

  * <b>Thibault/thibault</b> the user

  * <b>Sebastien/sebastien</b> the other user

4. You can now go to `localhost/index.php` to enjoy this amazing website.

5. To run on W/M/LAMP change the information in the file "databaseConnection.php"

## User manual

1. Login

  Once you're on the login screen, enter your credentials to access the home page. The home page will simply present the menu with the different pages you can access.

  ![alt text](./img/user_login.PNG)

2. Inbox

  This page contains all your received messages. For each message, you can see the sender, the subject and the options to apply to the message.

    ![alt text](./img/user_home.PNG)

3. Display the message

  To display or hide the message content, simply clic on <i>Display/hide</i>

  ![alt text](./img/user_inbox.PNG)

  And after clic on <i>Display/hide</i>:

  ![alt text](./img/user_display.PNG)

4. Reply messages

  To reply to a message, clic on <i>Reply</i>. This will open the message creation interface and it will pre-complete the fields <i>To: </i> and <i>Subject</i> for you.

  ![alt text](./img/user_reply.PNG)

5. Delete a message

  To delete a message, simply clic on <i>Delete</i>.

3. Send a new message

  To send a new message, clic on the <i>Write message</i> option in the menu. This will open the same interface than when you reply a message, without prefilling the fields. To send a message, you need to now the exact spelling of the user you want to send a message.

6. Change your password

  To change your password, go clic on <i>Change password</i> and fill the requested fields.

  ![alt text](./img/user_password.PNG)

## Administrator manual

All pages are the same for the admin, except the <i>Administration page</i> that allows the users with the correct rights to do some cool stuffs like:

1. Change the user password

2. Activate or desactivate a user (can no longer login)

3. Make a simple user become an admin and vice et versa

4. Register a new user

  The user name must be unique

5. Delete a user.

  Once a user is deleted, you can no longer: create a user with the same name, send this user messages, re-create it etc. The deleted users don't appear in the users lists.

![alt text](./img/admin_admin.PNG)

# Web site security remarks

For all of the security features we suppose that the site is running under an SSL connection.
Password policy: we should force the user to use alphanumerical and special caracters.
Anti CSRF: only a beggining, not realy secure -> lack of time
Redirection to 404 should be done if access id denied AND if page doesn't exist to avoid information disclosure.


## Sanitizing user inputs

## CSRF Tokens
