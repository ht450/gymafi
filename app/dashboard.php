<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');

// if not logged in -  send to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// if not coach or admin - send to profile
if (!$_SESSION['user_role'] == 'coach' || !$_SESSION['user_role'] == 'admin') {
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
    <style>
        .card-content .btn {
            background-color: palevioletred;
            margin-top: 5%;
            width: 100%;
        }
    </style>
</head>

<body>

    <?php
    require('includes/header.inc.php');
    ?>

    <div class="container">
        <div class="row">
            <h1>Dashboard</h1>
        </div>

        <?php if ($_SESSION['user_role'] == 'coach' || $_SESSION['user_role'] == 'admin') : // coach or admin
        ?>
            <h3>Coach</h3>

            <div class="row card">
                <div class="card-content">
                    <span class="card-title">Clients</span>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                1-to-1
                                <i class="material-icons left">add</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Client Profile
                                <i class="material-icons left">pageview</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Invite
                                <i class="material-icons left">email</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Client Diary
                                <i class="material-icons left">book</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Chat to Client
                                <i class="material-icons left">chat</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row card">
                <div class="card-content">
                    <span class="card-title">Classes</span>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Add Class
                                <i class="material-icons left">add</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                New Type
                                <i class="material-icons left">new_releases</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                My Classes
                                <i class="material-icons left">local_play</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Book Client in
                                <i class="material-icons left">person_add</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row card">
                <div class="card-content">
                    <span class="card-title">Groups</span>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Create Group
                                <i class="material-icons left">group_add</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Post to Group
                                <i class="material-icons left">reply_all</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Schedule to Group
                                <i class="material-icons left">schedule</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        <?php
        endif;
        if ($_SESSION['user_role'] == 'admin') : // just admin
        ?>
            <h3>Admin</h3>

            <div class="row card">
                <div class="card-content">
                    <span class="card-title">Staff</span>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Manage
                                <i class="material-icons left">work</i>
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Chat
                                <i class="material-icons left">question_answer</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row card">
                <div class="card-content">
                    <span class="card-title">Website</span>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Site content
                                <i class="material-icons left">description</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row card">
                <div class="card-content">
                    <span class="card-title">Clients</span>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <a href="#" class="waves-effect waves-light btn">
                                Manage
                                <i class="material-icons left">people_outline</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>


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