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
    ?>

    <div class="container">
        <h1>Diary</h1>

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

        <?php if (count($_SESSION['errors']) > 0) : ?>
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

        <!-- New Diary Button -->
        <form action="includes/diary.inc.php" method="POST" id="new_diary_entry_form">
            <button class="btn waves-effect waves-light" name="new_diary_entry_button" type="submit">
                New Diary
                <i class="material-icons left">add</i>
            </button>
        </form>

        <!-- Users Diary Enteries -->
        <?php
        // get users diary enteries
        $sql_get_diary_entries = "
        SELECT *
        FROM WDP_Diary_Entries
        INNER JOIN WDP_Diary_Types
        ON WDP_Diary_Entries.Diary_Type_ID = WDP_Diary_Types.Diary_Type_ID
        WHERE User_ID=?
        ORDER BY Date DESC;
        ";
        $stmt_get_diary_entries = $conn->prepare($sql_get_diary_entries);
        $stmt_get_diary_entries->bind_param('s', $_SESSION['user_id']);

        // error check
        if (!$stmt_get_diary_entries->execute()) {
            $_SESSION['errors']['db_query'] = "Database error: failed to run query.";
        }

        $get_users_diarys = $stmt_get_diary_entries->get_result();

        echo "<ol class='collection'>";
        while ($diary_entry = $get_users_diarys->fetch_assoc()) {
            // for each diary entry

            // set vars
            $id = $diary_entry['Diary_Entry_ID'];
            $type = $diary_entry['Diary_Type'];
            $date = $diary_entry['Date'];
            $date_long = date_create_from_format('Y-m-d', $date)->format('l jS F Y');
            $title = $diary_entry['Title'];
            $content = $diary_entry['Content'];
            $content_short = substr($content, 0, 100);

            // echo them out in a list
            echo "
            <li class='collection-item avatar'>
                <span class='title'>$title</span>
                <p>$date_long</p>
                <p>$type</p>
                <p>$content_short</p>
                <a name='edit_diary_button' href='diary_entry.php?id=$id' class='secondary-content btn'>Edit<i class='material-icons right'>edit</i></a>
            </li>
            ";
        }
        echo "</ol>";

        ?>

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