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
            <h1>Login</h1>
        </div>

        <!-- text input box -->
        <div class="row">

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

            <form class="col s12" action="includes/login.inc.php" method="POST" id="login_form">

                <!-- Username or Email -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="user_login" id="uidemail" type="text" class="validate" data-length="255" <?php if(isset($_GET['uid'])): $uid = $_GET['uid'] ;echo "value='$uid'"; endif; ?>>
                        <label for="uidemail">Username or Email</label>
                    </div>
                </div>
                <!-- Password -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="user_password" id="password" type="password" class="validate" data-length="55">
                        <label for="password">Password</label>
                    </div>
                </div>

            </form>
        </div>

        <!-- Log In Button -->
        <div class="row">
            <button form="login_form" class="btn waves-effect waves-light" type="submit" name="login_button">Log In
                <i class="material-icons right">send</i>
            </button>
        </div>

        <!-- Register Button -->
        <div class="row">
            <p>Not Registered?</p>
            <p><a href="register.php" class="btn waves-effect">Sign Up<i class="material-icons right">assignment</i></a></p>
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
            $('input#uidemail, input#password ').characterCounter();
        });
    </script>
</body>

</html>