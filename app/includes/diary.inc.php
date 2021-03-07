<?php
require_once('conn.inc.php');
require_once('session.inc.php');

// check the user got her via the edit button
if (isset($_POST['edit_diary_entry_button'])) {

    require_once('conn.inc.php');
    require_once('session.inc.php');

    $_SESSION['errors'] = array();

    // grab variables
    $entry_id = $_POST['entry_id'];

    $type_id = $_POST['diary_type_menu'];
    $date = $_POST['date'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // error handling...
    // check if inputs are sanitised
    if (!filter_var($date, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Date is invalid";
        header("Location: ../diary_entry.php?id=$entry_id");
        exit();
    }
    if (!filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Title is invalid";
        header("Location: ../diary_entry.php?id=$entry_id");
        exit();
    }
    if (!filter_var($content, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $_SESSION['errors']['invalid_input'] = "Content is invalid";
        header("Location: ../diary_entry.php?id=$entry_id");
        exit();
    }

    // update details
    $sql_update_diary = 
    "
    UPDATE WDP_Diary_Entries 
    SET 
    Diary_Type_ID = ?,
    Date = ?,
    Title = ?, 
    Content = ?
    WHERE WDP_Diary_Entries.Diary_Entry_ID = ?;
    ";

    $stmt_update_diary = $conn->prepare($sql_update_diary);
    $stmt_update_diary->bind_param('sssss', $type_id, $date, $title, $content, $entry_id);

    // update successful
    if ($stmt_update_diary->execute()) {

        $stmt_update_diary->store_result();

        // set session vars
        $_SESSION['flash_message'] = "Your diary has been updated!";
        $_SESSION['flash_message_class'] = "green lighten-2 white-text";

        // go to profile
        header("Location: ../diary_entry.php?id=$entry_id");
        exit();
    }
    // error with update
    else {
        $_SESSION['errors']['db_update'] = "Database error: failed to update diary.";
        header("Location: ../diary_entry.php?id=$entry_id");
        exit();
    }
}
// if new entry button is pressed
else if (isset($_POST['new_diary_entry_button'])) {

    require_once('conn.inc.php');
    require_once('session.inc.php');

    $_SESSION['errors'] = array();

    $date = date('Y-m-d');
    $user_id = $_SESSION['user_id'];

    // insert blank entry
    $sql_new_diary = 
    "
    INSERT INTO WDP_Diary_Entries (Diary_entry_ID, User_ID, Diary_Type_ID, Date, Title, Content) VALUES (NULL, ? , '1', ?, ' ', ' ');
    ";

    $stmt_new_diary = $conn->prepare($sql_new_diary);
    $stmt_new_diary->bind_param('ss', $user_id, $date);

    // insert successful
    if ($stmt_new_diary->execute()) {
        
        $entry_id = $conn->insert_id;

        $stmt_new_diary->store_result();

        // set session vars
        $_SESSION['flash_message'] = "A new diary entry has been created";
        $_SESSION['flash_message_class'] = "green lighten-2 white-text";

        // go to profile
        header("Location: ../diary_entry.php?id=$entry_id");
        exit();
    }
    // error with insert
    else {
        $_SESSION['errors']['db_insert'] = "Database error: failed to create new entry.";
        header("Location: ../diary.php");
        exit();
    }

}
// check the user got her via the delete button
else if (isset($_POST['delete_diary_entry_button'])) {

    require_once('conn.inc.php');
    require_once('session.inc.php');

    $_SESSION['errors'] = array();

    // grab variables
    $entry_id = $_POST['entry_id'];

    // delete row
    $sql_delete_diary = 
    "
    DELETE FROM WDP_Diary_Entries WHERE WDP_Diary_Entries.Diary_Entry_ID = ?;
    ";

    $stmt_delete_diary = $conn->prepare($sql_delete_diary);
    $stmt_delete_diary->bind_param('s', $entry_id);

    // delete successful
    if ($stmt_delete_diary->execute()) {

        $stmt_delete_diary->store_result();

        // set session vars
        $_SESSION['flash_message'] = "Your entry has been deleted!";
        $_SESSION['flash_message_class'] = "green lighten-2 white-text";

        // go to profile
        header("Location: ../diary.php");
        exit();
    }
    // error with update
    else {
        $_SESSION['errors']['db_delete'] = "Database error: failed to delete diary.";
        header("Location: ../diary_entry.php?id=$entry_id");
        exit();
    }

}
// if they didn't access via any button then send them to details page
else {
    $_SESSION['errors']['test'] = "no button!";
    header("Location: ../diary.php");
    exit();
}
