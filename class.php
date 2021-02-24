<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');

if (!isset($_GET['id'])) {
    header("Location: classes.php");
    exit();
} else if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <?php
    require('includes/head.inc.php');
    ?>

</head>

<body>

    <?php
    require('includes/header.inc.php');
    ?>

    <div class="container">
        <div class="row">
            <h1>Class</h1>

            <?php if (isset($_SESSION['flash_message'])) : ?>
                <!-- Flash message -->
                <div class="row">
                    <div class="card-panel <?php echo $_SESSION['flash_message_class']; ?>">
                        <?php
                        echo $_SESSION['flash_message'];
                        unset($_SESSION['flash_message']);
                        unset($_SESSION['flash_message_class']);
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) : ?>
                <!-- Error Messages -->
                <div class="row">
                    <div class="card-panel red lighten-2 white-text">
                        <?php foreach ($_SESSION['errors'] as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php unset($_SESSION['errors']); ?>

            <?php
            $class_id = $_GET['id'];
            $user_id = $_SESSION['user_id'];


            // get class details
            $sql_get_events =
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
                WHERE WDP_Classes.Class_ID = ?;
                ";
            $stmt_get_event = $conn->prepare($sql_get_events);
            $stmt_get_event->bind_param('s', $class_id);
            if (!$stmt_get_event->execute()) {
                echo "db error";
            }
            $event = $stmt_get_event->get_result()->fetch_assoc();


            // check if user is booked in
            $sql_booking_check = "SELECT * FROM WDP_Bookings WHERE Class_ID = ? AND User_ID = ?;";
            $stmt_booking_check = $conn->prepare($sql_booking_check);
            $stmt_booking_check->bind_param('ss', $class_id, $user_id);
            if ($stmt_booking_check->execute()) {

                $stmt_booking_check->store_result();

                // booking returned
                if ($stmt_booking_check->num_rows() > 0) {
                    $user_isBooked = true;
                }
                // no booking returned
                else {
                    $user_isBooked = false;
                }
            }
            // didn't execute - db erro
            else {
                $_SESSION['errors']['db_cancel_booking'] = "Error with finding booking";
                header("Location: ../class.php?id=$class_id");
                exit();
            }

            // count how many bookings there are
            $sql_class_bookings = "SELECT * FROM WDP_Bookings WHERE Class_ID = ?;";
            $stmt_class_bookings = $conn->prepare($sql_class_bookings);
            $stmt_class_bookings->bind_param('s', $class_id);
            if ($stmt_class_bookings->execute()) {
                $row = $stmt_class_bookings->get_result();
                $number_booked = 0;
                while ($row->fetch_assoc()) {
                    $number_booked++;
                }
            }
            // didn't execute - db erro
            else {
                $_SESSION['errors']['db_get_class_bookings'] = "Error with finding the class bookings";
                header("Location: ../class.php?id=$class_id");
                exit();
            }
            $size = $event['Class_Size'];
            $spaces_left = $size - $number_booked;
            ?>

        </div>
        <div class='row'>
            <div class='col s12'>

                <!-- Profile card -->
                <div class='card'>

                    <!-- main content -->
                    <div class='card-content'>
                        <span class='card-title'>Class Details</span>
                        <div class="row">
                            <div class="col s4">
                                <p>Date: <?php echo $event['Date']; ?></p>
                                <p>Time: <?php echo $event['Start_Time']; ?></p>
                                <p>Finished: <?php echo $event['End_Time']; ?></p>
                                <p>Location: <?php echo $event['Location_Name']; ?></p>
                                <p>Address: <?php echo $event['Location_Address']; ?></p>
                            </div>
                            <div class="col s8">
                                <p>Title: <?php echo $event['Class_Title']; ?></p>
                                <p>Description: <?php echo $event['Class_Description']; ?></p>
                                <p>Coach: <?php echo $event['First_Name'] . " " . $event['Last_Name']; ?></p>
                                <p>Size: <?php echo $event['Class_Size']; ?></p>
                                <p>Spaces Left: <?php echo $spaces_left; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- bottom links -->
                    <div class='card-action'>
                        <div class="row">
                            <div class="col s4" id="book_class_btn">
                                <!-- book class -->
                                <form action="includes/class_book.inc.php" method="POST" id="book_class_form">
                                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>" />
                                    <button class="btn waves-effect waves-light" name="book_class_btn" type="submit">
                                        Book Class
                                        <i class="material-icons right">event_available</i>
                                    </button>
                                </form>
                            </div>
                            <div class="col s4" id="full_class_warning">
                                <div class="card-panel red lighten-2 white-text">
                                    Sorry, class is full!
                                </div>
                            </div>
                            <div class="col s4" id="cancel_book_class_btn">
                                <!-- cancel class -->
                                <form action="includes/class_book.inc.php" method="POST" id="cancel_book_class_form" onsubmit="return confirm('Do you really want to cancel this booking?');">
                                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>" />
                                    <button class="btn waves-effect waves-light" name="cancel_book_class_btn" type="submit">
                                        Cancel Booking
                                        <i class="material-icons right">event_busy</i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div> <!-- card -->
            </div> <!-- col containing card -->
        </div> <!-- row containing card -->

        <div class="row">
            <a href="classes.php" class="btn waves-effect waves-light">
                Back to Classes
                <i class="material-icons left">arrow_back</i>
            </a>
        </div>

    </div>


    <?php
    require('includes/footer.inc.php');
    ?>

    <script>
        // when document is ready...
        $(document).ready(function() {
            // print ready to console
            console.log("ready!");

            // for hamburger menu sidebar on mobile
            $('.sidenav').sidenav();

            <?php if ($user_isBooked) : ?>
                $('#book_class_btn').hide();
                $('#cancel_book_class_btn').show();
                $('#full_class_warning').hide();
            <?php elseif ($spaces_left == 0) : ?>
                $('#book_class_btn').hide();
                $('#cancel_book_class_btn').hide();
                $('#full_class_warning').show();
            <?php else : ?>
                $('#book_class_btn').show();
                $('#cancel_book_class_btn').hide();
                $('#full_class_warning').hide();
            <?php endif; ?>

        });
    </script>
</body>

</html>