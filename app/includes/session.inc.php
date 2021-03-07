<?php

// starting session
session_start();

// if no errors set
if (!isset($_SESSION['errors'])) {
    // set an empty array for error checkers to see
    $_SESSION['errors'] = array();
}
