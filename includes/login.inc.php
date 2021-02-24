<?php
// check the user got her via the login button
if (isset($_POST['login_button'])) {

    require('conn.inc.php');
    require('session.inc.php');

    $_SESSION['errors'] = array();

    // grab variables
    $name = $_POST['user_login'];
    $password = $_POST['user_password'];

    // error handlers and validation

    // check if inputs are sanitised
    if (!filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Input is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }
    
    // emptyfields
    if (empty($name)) {
        $_SESSION['errors']['empty_name'] = "Username or Email required";
        header("Location: ../login.php?error=empty_name");
        exit();
    } elseif (empty($password)) {
        $_SESSION['errors']['empty_password'] = "Password required";
        header("Location: ../login.php?error=empty_password&uid=".$name);
        exit();
    }
    else {
        $sql_credential_check = 
        "
        SELECT * 
        FROM WDP_Registered_Users 
        INNER JOIN WDP_User_Details
        ON WDP_Registered_Users.User_ID = WDP_User_Details.User_ID
        INNER JOIN WDP_User_Roles
        ON WDP_User_Details.Role_ID = WDP_User_Roles.Role_ID
        WHERE Username=? OR Email=?
        ";
        $stmt_credential_check = $conn -> prepare($sql_credential_check);
        
        // error check
        if (!$stmt_credential_check) {
            $_SESSION['errors']['db_query'] = "Database error: failed to run query.";
            header("Location: ../login.php?error=db_query");
            exit();
        }
        // no errors - pass stmt
        else {
            $stmt_credential_check->bind_param('ss', $name, $name);
            $stmt_credential_check->execute();
            
            // if the username/email exists - the row is returned
            $result = $stmt_credential_check->get_result();
            $user_row = $result->fetch_assoc();

            // login success
            if(password_verify($password, $user_row['Password'])){
                
                // set session vars
                $_SESSION['user_id'] = $user_row['User_ID'];
                $_SESSION['user_username'] = $user_row['Username'];
                $_SESSION['user_email'] = $user_row['Email'];
                $_SESSION['user_role_id'] = $user_row['Role_ID'];
                $_SESSION['user_role'] = $user_row['Role'];

                // set message
                $_SESSION['flash_message'] = "You are now logged in!";
                $_SESSION['flash_message_class'] = "green lighten-2 white-text";

                // go to profile
                header("Location: ../profile.php");
                exit();
            }
            // incorrect password
            else {
                $_SESSION['errors']['login_fail'] = "Credentials incorrect";
                header("Location: ../login.php?error=login_fail");
                exit();
            }




        }
    }




    
} else {
    // if they didn't access via login button then send them to login page
    header("Location: ../login.php");
    exit();
}