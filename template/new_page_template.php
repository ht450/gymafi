<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');
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
            <h1>Title</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo esse deserunt nulla reprehenderit ullam minus harum quidem corrupti dolor, magni fuga nihil expedita explicabo cum dolorum earum odit, inventore id.</p>
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