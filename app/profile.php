<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');

if (!isset($_SESSION['user_id'])) {
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

    require_once('includes/details_get.inc.php');

    ?>

    <div class="container">

        <div class="row">
            <h1>Profile</h1>
        </div>

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

        <div class='row'>
            <div class='col s12'>

                <!-- Profile card -->
                <div class='card'>

                    <!-- main content -->
                    <div class='card-content'>
                        <span class='card-title'>User Details</span>
                        <p>Username: <?php echo $_SESSION['user_username'] ?></p>
                        <p>Email: <?php echo $_SESSION['user_email'] ?></p>
                        <p>First Name: <?php echo $user_firstname ?></p>
                        <p>Last Name: <?php echo $user_lastname ?></p>
                        <p>Contact No: <?php echo $user_contact_no ?></p>
                        <p>User Role: <?php echo $user_role ?></p>

                    </div>

                    <!-- bottom links -->
                    <div class='card-action'>
                        <a href="details.php" class="btn">Edit Details<i class="material-icons right">edit</i></a>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">



                <!-- Users Booked Classes -->
                <?php
                // get users booked classes
                $sql_get_booked_classes =
                    "
                SELECT * 
                FROM WDP_Bookings
                INNER JOIN WDP_Registered_Users
                ON WDP_Bookings.User_ID = WDP_Registered_Users.User_ID
                INNER JOIN WDP_User_Details
                ON WDP_Registered_Users.User_ID = WDP_User_Details.User_ID
                INNER JOIN WDP_User_Roles
                ON WDP_User_Details.Role_ID = WDP_User_Roles.Role_ID
                INNER JOIN WDP_Classes
                ON WDP_Bookings.Class_ID = WDP_Classes.Class_ID
                INNER JOIN WDP_Class_Type
                ON WDP_Classes.Class_Type_ID = WDP_Class_Type.Class_Type_ID
                INNER JOIN WDP_Class_Location
                ON WDP_Classes.Location_ID = WDP_Class_Location.Location_ID
                WHERE WDP_Bookings.User_ID = ?
                ORDER BY WDP_Classes.Date ASC , WDP_Classes.Start_Time ASC;
                ";
                $stmt_get_booked_classes = $conn->prepare($sql_get_booked_classes);
                $stmt_get_booked_classes->bind_param('s', $_SESSION['user_id']);

                // error check
                if (!$stmt_get_booked_classes->execute()) {
                    $_SESSION['errors']['db_query'] = "Database error: failed to run query.";
                }

                $get_users_classes = $stmt_get_booked_classes->get_result();
                ?>


                <ul class='collection with-header'>
                    
                    <li class="collection-header">
                        <h4>Booked Classes</h4>
                    </li>

                    <?php while ($class = $get_users_classes->fetch_assoc()) : ?>
                        <?php
                        // details of each class
                        $date_in = date_create_from_format('Y-m-d', $class['Date']);
                        $date_out_long = $date_in->format('l jS F Y');
                        $date_out_short = $date_in->format('D d/m/y');

                        $time_in = date_create_from_format('H:i:s', $class['Start_Time']);
                        $time_out = $time_in->format('H:i');

                        $title = $class['Class_Title'];
                        $id = $class['Class_ID'];
                        $loc = $class['Location_Name'];
                        $loc_add = $class['Location_Address'];
                        $coach = $class['First_Name'] . " " . $class['Last_Name'];
                        $descript = $class['Class_Description'];

                        $time_in_end = date_create_from_format('H:i:s', $class['End_Time']);
                        $time_out_end = $time_in->format('H:i');

                        $title_link = "<h6><a href='class.php?id=$id'>$date_out_short $time_out - $title</a></h6>";
                        
                        // print out
                        ?>
                        
                        <li class='collection-item avatar'>
                            <span class='card-title'><?php echo $title_link; ?></span>
                            <div class="row">
                                <div class="col s4">
                                    <p>Date: <?php echo $date_out_long; ?></p>
                                    <p>Start Time: <?php echo $time_out; ?></p>
                                    <p>Finished: <?php echo $time_out_end; ?></p>
                                    <p>Location: <?php echo $loc; ?></p>
                                    <p>Address: <?php echo $loc_add; ?></p>
                                </div>
                                <div class="col s8">
                                    <p>Title: <?php echo $title; ?></p>
                                    <p>Description: <?php echo $descript; ?></p>
                                    <p>Coach: <?php echo $coach; ?></p>
                                </div>
                            </div>
                        </li>

                    <?php endwhile; ?>
                </ul>


            </div>
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
        });
    </script>
</body>

</html>