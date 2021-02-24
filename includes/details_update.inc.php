<?php
// check the user got her via the register button
if (isset($_POST['update_details_button'])) {

    require_once('conn.inc.php');
    require_once('session.inc.php');

    $_SESSION['errors'] = array();

    // grab variables
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact_no = $_POST['contact_no'];

    // error handling...
    // check if inputs are sanitised
    if (!filter_var($firstname, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Input is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }
    if (!filter_var($lastname, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Input is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }
    if (!filter_var($contact_no, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Input is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }

    // update details
    $sql_update_details = "
    UPDATE WDP_User_Details
    SET First_Name = ?, 
    Last_Name = ?, 
    Contact_No = ? 
    WHERE WDP_User_Details.User_ID = ?;

    ";
    
    $stmt_update_details = $conn->prepare($sql_update_details);
    $stmt_update_details->bind_param('ssss', $firstname, $lastname, $contact_no, $_SESSION['user_id']);
    
    // update successful
    if ($stmt_update_details->execute()) {
        
        $stmt_update_details->store_result();
        
        // set session vars
        $_SESSION['flash_message'] = "Your details have been updated!";
        $_SESSION['flash_message_class'] = "green lighten-2 white-text";

        // go to profile
        header("Location: ../profile.php");
        exit();
    }
    // error with update
    else {
        $_SESSION['errors']['db_register'] = "Database error: failed to register user.";
        header("Location: ../details.php?error=db_register");
        exit();
    }

} else {
    // if they didn't access via update button then send them to details page
    header("Location: ../details.php");
    exit();
}
