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
            <h1>Details</h1>
        </div>

        <div class="row">

            <?php
            // error check 
            // var_dump($_SESSION['errors']) 
            ?>

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

            <form class="col s12" action="includes/details_update.inc.php" method="POST" id="input_user_details">

                <!-- First Name -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="firstname" id="firstname" type="text" class="validate" data-length="255" <?php echo "value=$user_firstname"; ?>>
                        <label for="firstname">First Name</label>
                    </div>
                </div>

                <!-- Last Name -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="lastname" id="lastname" type="text" class="validate" data-length="255" <?php echo "value=$user_lastname"; ?>>
                        <label for="lastname">Last Name</label>
                    </div>
                </div>

                <!-- Contact Number -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="contact_no" id="contact_no" type="text" class="validate" data-length="255" <?php echo "value='$user_contact_no'"; ?>>
                        <label for="contact_no">Contact Number</label>
                    </div>
                </div>

            </form>

        </div>

        <!-- Save Updates -->
        <div class="row">
            <button class="btn waves-effect waves-light" type="submit" form="input_user_details" name="update_details_button">Update Details
                <i class="material-icons right">send</i>
            </button>
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

            // for character counter in text area
            $('input#firstname, input#lastname, input#contact_no ').characterCounter();
        });
    </script>
</body>

</html>