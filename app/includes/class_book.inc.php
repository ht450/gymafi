<?php
// got her via the book class button
if (isset($_POST['book_class_btn'])) {

    require('conn.inc.php');
    require('session.inc.php');

    $_SESSION['errors'] = array();

    // grab variables
    $class_id = $_POST['class_id'];
    $user_id = $_SESSION['user_id'];

    // error handlers and validation

    // check if inputs are sanitised
    if (!filter_var($class_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        header("Location: ../classes.php");
        exit();
    }

    // check if booking exists first
    $sql_book_class_check = "SELECT * FROM WDP_Bookings WHERE Class_ID = ? AND User_ID = ?;";
    $stmt_book_class_check = $conn->prepare($sql_book_class_check);
    $stmt_book_class_check->bind_param('ss', $class_id, $user_id);
    // executed
    if ($stmt_book_class_check->execute()) {

        $stmt_book_class_check->store_result();

        // check if there is already a booking
        if ($stmt_book_class_check->num_rows() > 0) {
            $_SESSION['errors']['is_booked'] = "Booking already exists";
            header("Location: ../class.php?id=$class_id");
            exit();
        }
    }
    // didn't execute - db erro
    else {
        $_SESSION['errors']['db_cancel_booking'] = "Error with finding booking";
        header("Location: ../class.php?id=$class_id");
        exit();
    }

    // is there any room in the class
    // get class size
    $sql_class_size = "SELECT * FROM WDP_Classes WHERE Class_ID = ? ;";
    $stmt_class_size = $conn->prepare($sql_class_size);
    $stmt_class_size->bind_param('s', $class_id);
    if (!$stmt_class_size->execute()) {
        // didn't execute - db erro
        $_SESSION['errors']['db_get_class_size'] = "Error with finding the class size";
        header("Location: ../class.php?id=$class_id");
        exit();
    }
    $result = $stmt_class_size->get_result();
    $row = $result->fetch_assoc();
    $class_size = $row['Class_Size'];

    // get bookings for class
    $sql_class_bookings = "SELECT * FROM WDP_Bookings WHERE Class_ID = ? ;";
    $stmt_class_bookings = $conn->prepare($sql_class_bookings);
    $stmt_class_bookings->bind_param('s', $class_id);
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
        header("Location: ../class.php?id=$class_id");
        exit();
    }

    // if not spaces return to class page
    $spaces_left = $class_size - $number_booked;
    if ($spaces_left == 0) {
        $_SESSION['errors']['class_full'] = "Sorry, the class is full. size=$class_size .";
        header("Location: ../class.php?id=$class_id");
        exit();
    }

    // booking doesn't exist - book class
    $sql_book_class = "INSERT INTO WDP_Bookings (Class_ID, User_ID) VALUES (?, ?);";
    $stmt_book_class = $conn->prepare($sql_book_class);
    $stmt_book_class->bind_param('ss', $class_id, $user_id);

    // booked successfully
    if ($stmt_book_class->execute()) {

        $_SESSION['flash_message'] = "The class is now booked";
        $_SESSION['flash_message_class'] = "green lighten-2 white-text";

        // go to class page
        header("Location: ../class.php?id=$class_id");
        exit();
    }
    // error with booking
    else {
        $_SESSION['errors']['db_book_class'] = "Error with booking";
        header("Location: ../class.php?id=$class_id");
        exit();
    }
}
// got here via cancel booking
else if (isset($_POST['cancel_book_class_btn'])) {

    require('conn.inc.php');
    require('session.inc.php');

    $_SESSION['errors'] = array();

    // grab variables
    $class_id = $_POST['class_id'];
    $user_id = $_SESSION['user_id'];

    // error handlers and validation

    // check if inputs are sanitised
    if (!filter_var($class_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        header("Location: ../classes.php");
        exit();
    }

    // check if booking exists first
    $sql_book_class_check = "SELECT * FROM WDP_Bookings WHERE Class_ID = ? AND User_ID = ?;";
    $stmt_book_class_check = $conn->prepare($sql_book_class_check);
    $stmt_book_class_check->bind_param('ss', $class_id, $user_id);
    // executed
    if ($stmt_book_class_check->execute()) {

        $stmt_book_class_check->store_result();

        // check if there is no booking
        if ($stmt_book_class_check->num_rows() == 0) {
            $_SESSION['errors']['no_booking'] = "No booking found";
            header("Location: ../class.php?id=$class_id");
            exit();
        }
    }
    // didn't execute - db erro
    else {
        $_SESSION['errors']['db_cancel_booking'] = "Error with finding booking";
        header("Location: ../class.php?id=$class_id");
        exit();
    }

    // booking exists - cancel booking
    $sql_cancel_book_class = "DELETE FROM WDP_Bookings WHERE Class_ID = ? AND User_ID = ?;";
    $stmt_cancel_book_class = $conn->prepare($sql_cancel_book_class);
    $stmt_cancel_book_class->bind_param('ss', $class_id, $user_id);

    // deleted successfully
    if ($stmt_cancel_book_class->execute()) {

        $_SESSION['flash_message'] = "Your booking is now cancelled";
        $_SESSION['flash_message_class'] = "green lighten-2 white-text";

        // go to class page
        header("Location: ../class.php?id=$class_id");
        exit();
    }
    // error with cancellation
    else {
        $_SESSION['errors']['db_cancel_booking'] = "Error with cancellation";
        header("Location: ../class.php?id=$class_id");
        exit();
    }
}
// if they didn't access via any button then send them to classes page (will then redirect to index if not logged in)
else {
    header("Location: ../classes.php");
    exit();
}
