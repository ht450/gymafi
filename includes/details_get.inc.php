<?php
require_once('conn.inc.php');
require_once('session.inc.php');

// query to get users details
$sql_get_details = "
SELECT *
FROM WDP_User_Details
INNER JOIN WDP_User_Roles
ON WDP_User_Details.Role_ID = WDP_User_Roles.Role_ID
WHERE User_ID=?;
";
$stmt_get_details = $conn->prepare($sql_get_details);

// error check
if (!$stmt_get_details) {
    $_SESSION['errors']['db_query'] = "Database error: failed to run query.";
}

// no errors - pass stmt
$stmt_get_details->bind_param('s', $_SESSION['user_id']);
$stmt_get_details->execute();

// the row containing the users details is returned
$get_details_result = $stmt_get_details->get_result();
$user_details_row = $get_details_result->fetch_assoc();

// set vars
$user_firstname = $user_details_row['First_Name'];
$user_lastname = $user_details_row['Last_Name'];
$user_contact_no = $user_details_row['Contact_No'];
$user_role = $user_details_row['Role'];
