<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');


function get_content($html_element_id){
    require('includes/conn.inc.php');
    // get content from database
    $sql_get_content =
    "
    SELECT * FROM WDP_Site_Content WHERE WDP_Site_Content.HTML_Element_ID = ? ;
    ";
    $stmt_get_content = $conn->prepare($sql_get_content);
    $stmt_get_content->bind_param('s', $html_element_id);
    if (!$stmt_get_content->execute()) {
        //error
        header("Location: ../about.php?error");
        exit();
    }
    $result = $stmt_get_content->get_result();
    $row = $result->fetch_assoc();
    echo $row['Content'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <?php
    require('includes/head.inc.php');
    ?>
    <script src=js/jquery.jeditable.min.js></script>
    <script src=js/jquery.jeditable.autogrow.min.js></script>
    <script src=js/jquery.autogrowtextarea.min.js></script>
</head>

<body>

    <?php
    require('includes/header.inc.php');
    ?>

    <div class="container">

        <div class="row" id="about_title">
            <h1>About</h1>
        </div>

        <div id="about_para1" <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {echo 'class="row edit"';} else {echo 'class="row"';} ?>>
            <?php
            get_content('about_para1')
            ?>
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
        
            // for edit in line function
            $('.edit').editable('includes/edit.inc.php', {
                type      : 'autogrow',
                cancel    : 'Cancel',
                event     : "dblclick",
                tooltip   : "Doubleclick to edit...",
                submit    : 'Save',
                cssclass : 'css/materialize.min.css',
                cancelcssclass : 'btn red lighten-2',
                submitcssclass : 'btn green lighten-2',
                callback : function(){
                    $('#about_para1').load('about.php #about_para1');
                }
            });
        });
    </script>
</body>

</html>