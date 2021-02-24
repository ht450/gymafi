<?php
session_start();

//unset($_SESSION['user_id']);
//unset($_SESSION['user_username']);
//unset($_SESSION['user_email']);

session_unset();
session_destroy();

session_start();

$_SESSION['flash_message'] = "You are now logged out!";
$_SESSION['flash_message_class'] = "green lighten-2 white-text";

header("Location: ../index.php");
