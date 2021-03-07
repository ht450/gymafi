<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
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
        
        <!-- title -->
        <div class="row">
            <h1>Register</h1>
        </div>

        <!-- text input box -->
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

            <form class="col s12" action="includes/register.inc.php" method="POST" id="input_user_details">

                <!-- Username-->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="user_username" id="user_username" type="text" class="validate" data-length="255" <?php if(isset($_GET['uid'])): $uid = $_GET['uid'] ;echo "value='$uid'"; endif; ?>>
                        <label for="user_username">Username</label>
                    </div>
                </div>

                <!-- Email address -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="user_email" id="email" type="email" class="validate" data-length="255" <?php if(isset($_GET['mail'])): $mail = $_GET['mail'] ;echo "value='$mail'"; endif; ?>>
                        <label for="email">Email</label>
                        <span class="helper-text" data-error="Email address Invalid"></span>
                    </div>
                </div>

                <!-- Password -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="user_password" id="password" type="password" class="validate" data-length="55">
                        <label for="password">Password</label>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="user_password_confirm" id="password_confirm" type="password" class="validate" data-length="55">
                        <label for="password">Confirm Password</label>
                    </div>
                </div>

            </form>

        </div>

        <!-- Register Button -->
        <div class="row">
            <button class="btn waves-effect waves-light" type="submit" form="input_user_details" name="register_button">Register
                <i class="material-icons right">send</i>
            </button>
        </div>

        <!-- Log In Button -->
        <div class="row">
            <p>Already Registered?</p>
            <p><a href="login.php" class="btn waves-effect">Log In<i class="material-icons right">lock_outline</i></a></p>
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
            $('input#user_username, input#email, input#password, input#password_confirm').characterCounter();
        });
    </script>

</body>

</html>