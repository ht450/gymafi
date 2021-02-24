<?php
// check the user got here via the register button
if (isset($_POST['register_button'])) {

    require('conn.inc.php');
    require('session.inc.php');

    $_SESSION['errors'] = array();

    // grab variables
    $username = $_POST['user_username'];
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];
    $passwordConfirm = $_POST['user_password_confirm'];

    // error handlers and validation
   
    // emptyfields
    if (empty($username)) {
        $_SESSION['errors']['empty_username'] = "Username required";
        header("Location: ../register.php?error=empty_username&uid=" . $username . "&mail=" . $email);
        exit();
    } elseif (empty($email)) {
        $_SESSION['errors']['empty_email'] = "Email required";
        header("Location: ../register.php?error=empty_email&uid=" . $username . "&mail=" . $email);
        exit();
    } elseif (empty($password)) {
        $_SESSION['errors']['empty_password'] = "Password required";
        header("Location: ../register.php?error=empty_password&uid=" . $username . "&mail=" . $email);
        exit();
    } elseif (empty($passwordConfirm)) {
        $_SESSION['errors']['empty_passwordConfirm'] = "Please confirm password";
        header("Location: ../register.php?error=empty_passwordConfirm&uid=" . $username . "&mail=" . $email);
        exit();
    }
    
    // check for invalid username AND invalid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $_SESSION['errors']['invalid_emailandusername'] = "Username and Email are invalid.";
        header("Location: ../register.php?error=invalid_emailandusername");
        exit();
    }
    // check for invalid email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['invalid_email'] = "Email is invalid.";
        header("Location: ../register.php?error=invalid_email&uid=" . $username);
        exit();
    }
    // check for invalid username
    elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username) || !filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_username'] = "Username is invalid.";
        header("Location: ../register.php?error=invalid_username&mail=" . $email);
        exit();
    }
    // check for passwords not matching
    elseif ($password !== $passwordConfirm) {
        $_SESSION['errors']['password_match'] = "Passwords do not match.";
        header("Location: ../register.php?error=password_match&uid=" . $username . "&mail=" . $email);
        exit();
    }
    
    // password strenght
    $pwd_length = strlen($password) >= 8;
    $pwd_uppercase = preg_match('@[A-Z]@', $password);
    $pwd_lowercase = preg_match('@[a-z]@', $password);
    $pwd_number    = preg_match('@[0-9]@', $password);
    $pwd_specialChars = preg_match('@[^\w]@', $password);
    if (!$pwd_length){
        $_SESSION['errors']['password_length'] = "Password must be at least 8 characters long.";
        header("Location: ../register.php?error=password_strength&uid=" . $username . "&mail=" . $email);
        exit();
    }
    else if (!$pwd_uppercase || !$pwd_uppercase || !$pwd_lowercase || !$pwd_number || !$pwd_specialChars){
        $_SESSION['errors']['password_strength'] = "Password must contain at least one upper case letter, one lower case letter, one number, and one special character.";
        header("Location: ../register.php?error=password_strength&uid=" . $username . "&mail=" . $email);
        exit();
    }
    
    // check if email or username already taken
    else {
        $sql_username_check = "SELECT Username FROM WDP_Registered_Users WHERE Username=?";
        $sql_email_check = "SELECT Email FROM WDP_Registered_Users WHERE Email=?";

        $stmt_username_check = $conn->prepare($sql_username_check);
        $stmt_email_check = $conn->prepare($sql_email_check);

        // error check
        if (!$stmt_username_check || !$stmt_email_check) {
            $_SESSION['errors']['db_query'] = "Database error: failed to run query.";
            header("Location: ../register.php?error=db_query");
            exit();
        }

        // no errors - pass stmt
        else {
            $stmt_username_check->bind_param('s', $username);
            $stmt_username_check->execute();
            $stmt_username_check->store_result();

            $stmt_email_check->bind_param('s', $email);
            $stmt_email_check->execute();
            $stmt_email_check->store_result();

            // if there were any rows returned
            // email
            if (($stmt_email_check->num_rows()) > 0) {
                $_SESSION['errors']['exists_email'] = "Email already exists.";
                header("Location: ../register.php?error=exists_email&uid=" . $username);
                exit();
            }
            // username
            elseif (($stmt_username_check->num_rows()) > 0) {
                $_SESSION['errors']['exists_username'] = "Username taken.";
                header("Location: ../register.php?error=exists_username&mail=" . $email);
                exit();
            }
            // neither taken - register user
            else {
                // encrypt password
                $password = password_hash($password, PASSWORD_DEFAULT);

                // sql - prepared statements
                $sql_register_user = "INSERT INTO WDP_Registered_Users (Username, Email, Password) VALUES (?, ?, ?)";
                $stmt_register_user = $conn->prepare($sql_register_user);
                $stmt_register_user->bind_param('sss', $username, $email, $password);

                // registered successfully
                if ($stmt_register_user->execute()) {
                    // get user_id for newly registered user
                    $user_id = $conn->insert_id;

                    // add row into user details table
                    $sql_new_details = "INSERT INTO WDP_User_Details (UserDetails_ID, User_ID, First_Name, Last_Name, Contact_No, Role_ID) VALUES (NULL, ?, ?, '', '', '1');";
                    $stmt_new_details = $conn->prepare($sql_new_details);
                    $stmt_new_details->bind_param('ss', $user_id, $username);
                    // error check
                    if (!$stmt_new_details->execute()) {
                        $_SESSION['errors']['db_new_details'] = "Database error: failed to add user details.";
                    }

                    // set session vars
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_username'] = $username;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role_id'] = '1';
                    $_SESSION['user_role'] = 'client';

                    // set message
                    $_SESSION['flash_message'] = "You are now logged in!";
                    $_SESSION['flash_message_class'] = "green lighten-2 white-text";

                    // go to profile
                    header("Location: ../profile.php");
                    exit();
                }
                // error with registration
                else {
                    $_SESSION['errors']['db_register'] = "Database error: failed to register user.";
                    header("Location: ../register.php?error=db_register&uid=" . $username . "&mail=" . $email);
                    exit();
                }
            }
        }
    }
} else {
    // if they didn't access via register button then send them to register page
    header("Location: ../register.php");
    exit();
}
