<?php
require_once('conn.inc.php');
require_once('session.inc.php');

// if not logged in -  send to login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
// if not coach or admin - send to profile
if (!$_SESSION['user_role'] == 'admin') {
    header("Location: ../profile.php");
    exit();
}



// values POSTed:
// id=element_id&value=user_edited_content
$html_element_id = $_POST['id'];
$content = nl2br($_POST['value']);

// echo $html_element_id;
// echo $content;

$sql_update_content =
"
UPDATE WDP_Site_Content 
SET Content = ? 
WHERE WDP_Site_Content.HTML_Element_ID = ? ;
";
$stmt_update_content = $conn->prepare($sql_update_content);
$stmt_update_content->bind_param('ss', $content, $html_element_id);
if (!$stmt_update_content->execute()) {
    //error
    header("Location: ../about.php?error");
    exit();
}
