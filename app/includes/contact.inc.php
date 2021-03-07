<?php
// check the user got her via the send email button
if (isset($_POST['send_email_button'])) {

    require('session.inc.php');
    $_SESSION['errors'] = array();

    // get data from form
    $name = $_POST['visitor_name'];
    $mail_from = $_POST['visitor_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // set vars for email
    $mail_to = $_ENV['DB_NAME'];
    $headers = "From: " . $mail_from;
    $txt = "You have received an email from " . $name . ".\n\n" . $message;

    // error handling...
    // check if inputs are sanitised
    if (!filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Name is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }
    if (!filter_var($mail_from, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['invalid_input'] = "Email is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }
    if (!filter_var($subject, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Subject is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }
    if (!filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Message is invalid";
        header("Location: ../details.php?error=invalid_input");
        exit();
    }


    // send email
    mail($mail_to, $subject, $txt, $headers);

    // set flash message to display to user
    $_SESSION['flash_message'] = "Your email has been sent!";
    $_SESSION['flash_message_class'] = "green lighten-2 white-text";

    // return them to the contact screen
    header("Location: ../contact.php?mailsent");
    exit();
}
// if they didn't access via the send email button then send them to contact page
else {
    header("Location: ../contact.php");
    exit();
}
