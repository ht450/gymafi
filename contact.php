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
            <h1>Contact</h1>

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
            
            <!-- Contact information? -->
            <p>Contact info here...<br>Name <br>Address <br>etc...</p>

        </div>

        <!-- Contact Form -->
        <div class="row">

            <h2>Send us an email:</h2>

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

            <form class="col s12" action="includes/contact.inc.php" method="POST" id="send_email_form">

                <!-- Visitor name-->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="visitor_name" id="visitor_name_box" type="text" class="validate" <?php if(isset($_GET['name'])): $name = $_GET['name'] ;echo "value='$name'"; endif; ?>>
                        <label for="visitor_name_box">Your Full Name</label>
                    </div>
                </div>

                <!-- Visitor Email Address -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="visitor_email" id="visitor_email_box" type="email" class="validate" <?php if(isset($_GET['mail'])): $mail = $_GET['mail'] ;echo "value='$mail'"; endif; ?>>
                        <label for="visitor_email_box">Your Email</label>
                        <span class="helper-text" data-error="Email address Invalid"></span>
                    </div>
                </div>

                <!-- Subject -->
                <div class="row">
                    <div class="input-field col s12">
                        <input name="subject" id="subject_box" type="text" data-length="100">
                        <label for="subject_box">Subject</label>
                    </div>
                </div>

                <!-- Message -->
                <div class="row">
                    <div class="input-field col s12">
                        <textarea name="message" id="message_box" class="materialize-textarea" data-length="1000"></textarea>
                        <label for="message_box">Message</label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <button name="send_email_button" class="btn waves-effect waves-light" type="submit" form="send_email_form">Send Email
                        <i class="material-icons right">send</i>
                    </button>
                </div>

            </form>

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
            $('input#subject_box, textarea#message_box').characterCounter();
        });
    </script>
</body>

</html>