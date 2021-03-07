<?php
require_once('session.inc.php');
require_once('conn.inc.php');

if (!isset($_POST['sort_by'])) {
    header('Location: ../index.php');
    exit();
}

$order_by_type = "ORDER BY WDP_Class_Type.Class_Title ASC;";
$order_by_coach = "ORDER BY WDP_User_Details.First_Name ASC;";
$order_by_date = "ORDER BY WDP_Classes.Date ASC , WDP_Classes.Start_Time ASC;";
$order_by_time = "ORDER BY WDP_Classes.Start_Time ASC, WDP_Classes.Date ASC;";

$sort_by = $_POST['sort_by'];

if ($sort_by == 'Type') {
    $order_by = $order_by_type;
} else if ($sort_by == 'Coach') {
    $order_by = $order_by_coach;
} else if ($sort_by == 'Date') {
    $order_by = $order_by_date;
} else if ($sort_by == 'Time') {
    $order_by = $order_by_time;
} else {
    $order_by = $order_by_date;
}

$sql_get_events_sorted =
    "
    SELECT * 
    FROM WDP_Classes
    INNER JOIN WDP_Registered_Users
    ON WDP_Classes.Coach_ID = WDP_Registered_Users.User_ID
    INNER JOIN WDP_User_Details
    ON WDP_Registered_Users.User_ID = WDP_User_Details.User_ID
    INNER JOIN WDP_User_Roles
    ON WDP_User_Details.Role_ID = WDP_User_Roles.Role_ID
    INNER JOIN WDP_Class_Type
    ON WDP_Classes.Class_Type_ID = WDP_Class_Type.Class_Type_ID
    INNER JOIN WDP_Class_Location
    ON WDP_Classes.Location_ID = WDP_Class_Location.Location_ID
    "
    . $order_by;
$stmt_get_events_sorted = $conn->prepare($sql_get_events_sorted);
// bind_param()
if (!$stmt_get_events_sorted->execute()) {
    echo "db error";
}
$events_sorted = $stmt_get_events_sorted->get_result();

while ($event = $events_sorted->fetch_assoc()) {

    $date_in = date_create_from_format('Y-m-d', $event['Date']);
    $date_out_long = $date_in->format('l jS F Y');
    $date_out_short = $date_in->format('D d/m/y');

    $time_in = date_create_from_format('H:i:s', $event['Start_Time']);
    $time_out = $time_in->format('H:i');

    $title = $event['Class_Title'];
    $id = $event['Class_ID'];
    $loc = $event['Location_Name'];
    $coach = $event['First_Name'] . " " . $event['Last_Name'];
    $descript = $event['Class_Description'];
    $size = $event['Class_Size'];

    $time_in_end = date_create_from_format('H:i:s', $event['End_Time']);
    $time_out_end = $time_in->format('H:i');

    // count how many bookings there are
    $sql_class_bookings = "SELECT * FROM WDP_Bookings WHERE Class_ID = ?;";
    $stmt_class_bookings = $conn->prepare($sql_class_bookings);
    $stmt_class_bookings->bind_param('s', $id);
    if ($stmt_class_bookings->execute()) {
        $row = $stmt_class_bookings->get_result();
        $number_booked = 0;
        while ($row->fetch_assoc()) {
            $number_booked = $number_booked + 1;
        }
    }
    // didn't execute - db erro
    else {
        $_SESSION['errors']['db_get_class_bookings'] = "Error with finding the class bookings";
        header("Location: ../class.php?id=$id");
        exit();
    }

    $spaces_left = ($size - $number_booked);
    

    // echo out event card
    echo "
        <div class='card' href='class.php?id=$id'>
            <div class='card-content'>
                <span class='card-title'><a href = 'class.php?id=$id'>$date_out_short $time_out - $title</a></span>
                <div class='row'>
                    <div class='col s4'>
                        Date: $date_out_long <br>
                        Start Time: $time_out <br>
                        End Time: $time_out_end <br>
                        Location: $loc <br>
                    </div>
                    <div class='col s8'>
                        Coach: $coach <br>
                        Description: $descript <br>
                        Size: $size <br>
                        Spaces Left: $spaces_left <br>
                    </div>
                </div>
            </div>
        </div>
        ";
}
