<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// get all events from DB
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
ORDER BY WDP_Classes.Date ASC , WDP_Classes.Start_Time ASC;
";
$stmt_get_events = $conn->prepare($sql_get_events);
// bind_param()
if (!$stmt_get_events->execute()) {
    echo "db error";
}
$events = $stmt_get_events->get_result();


?>
<!DOCTYPE html>
<html>

<head>
    <?php
    require('includes/head.inc.php');
    ?>
    <link rel="stylesheet" href="css/caleandar-theme3.css" />
</head>

<body>

    <?php
    require('includes/header.inc.php');
    ?>

    <div class="container">
        <div class="row">
            <h1>Classes</h1>
        </div>

        <div class="row">
            Show:
            <br>
            <a class="waves-effect waves-light btn" id="show_cal_btn">
                Calendar
                <i class="material-icons left">event</i>
            </a>
            <a class="waves-effect waves-light btn" id="show_list_btn">
                List
                <i class="material-icons left">list</i>
            </a>
        </div>

        <div class="row" id="cal">
            <h2>Calendar</h2>

            <!-- JS Calendar (caleandar) Plugin -->
            <div id="caleandar"></div>

        </div>

        <!-- list of classes -->
        <div class="row" id="list">
            <h2>List</h2>
            <!-- Sort By -->
            <div class="row">
                <div class="input-field col s8 m6 l4">
                    <select id="sort_by_menu">
                        <option value='Date' selected>Date</option>
                        <option value='Type'>Type</option>
                        <option value='Time'>Time</option>
                        <option value='Coach'>Coach</option>
                    </select>
                    <label for="sort_by_menu">Sort Classes By</label>
                </div>
            </div>

            <div id="sorted_cards">Classes...</div>

        </div>


    </div>

    <?php
    require('includes/footer.inc.php');
    ?>

    <script type="text/javascript" src="js/caleandar.js"></script>
    <script>
        var events = [
            <?php
            $events->data_seek(0);
            while ($event = $events->fetch_assoc()) {

                $date_in = date_create_from_format('Y-m-d', $event['Date']);
                // remove a month to fit into the calender month array
                $date_out = $date_in->modify('-1 month')->format('Y, m, d');

                $time_in = date_create_from_format('H:i:s', $event['Start_Time']);
                $time_out = $time_in->format('H:i');

                $title = $event['Class_Title'];
                $id = $event['Class_ID'];

                echo "{
                'Date': new Date($date_out),
                'Title': '$time_out - $title',
                'Link': 'class.php?id=$id'
                },";
            }
            ?>
        ];
        var settings = {

        };
        var element = document.getElementById('caleandar');
        caleandar(element, events, settings);
        //var cal = new Caleandar();
    </script>
    <script>
        // when document is ready...
        $(document).ready(function() {
            // print ready to console
            console.log("ready!");

            // for hamburger menu sidebar on mobile
            $('.sidenav').sidenav();

            // for dropdown menu
            $('select').formSelect();

            // show/hide calendar and list
            $("#show_cal_btn").click(function() {
                $("#cal").toggle();
            });
            $("#show_list_btn").click(function() {
                $("#list").toggle();
            });

            // for sorting classes
            // on load
            var chosenSorter = $('#sort_by_menu').val();
            $('#sorted_cards').load("includes/classes_sort.inc.php", {
                sort_by: chosenSorter
            });
            // on change
            $('#sort_by_menu').change(function() {
                chosenSorter = $('#sort_by_menu').val();
                $('#sorted_cards').load("includes/classes_sort.inc.php", {
                    sort_by: chosenSorter
                });
            });
        });
    </script>
</body>

</html>