<?php
error_reporting(0);
ini_set('display_errors', 0);
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--<meta http-equiv="refresh" content="900;url=logout.php"/>-->
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        .first_table { float: left; padding: 20px; width: 50%; height: 800px; }
        .second_table { float: left; padding: 20px; width: 50%; height: 800px; }
    </style>
</head>
<body>
    <script>
        var auto_refresh = setInterval('refresh_date()', 1000);
        function refresh_date() {
            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            var datetime = date+' '+time;
            document.cookie="datetime=" + datetime;
        }
    </script>

    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["first_name"]); ?></b>! Welcome to our site.</h1>
    </div>

    <div class="first_table">
        <?php
        require_once "first_table_welcome.php";
        ?>

        <br></br>

        <?php
        require_once "first_table_group_welcome.php";
        ?>

        <br></br>

        <button style="float:left;" onclick="window.location.href='create_group.php';">
        Create Group Chat
        </button>
    </div>

    <?php
    $hidemydiv;
        if ($_GET["talk_to"] != null) {
            $hidemydiv = "block";
        } else {
            $hidemydiv = "none";
        }
    ?>
    <div style="display:<?php echo $hidemydiv ?>" class="second_table">
        <?php
        require_once "second_table_welcome.php";
        ?>

        <form method="post"> 
        <label for="test_message">message</label>
            <input type="text" name="test_message" id="text_m"/>
            <input type="submit" name="button1"
                class="button" value="submit" onclick="redo_window()"/> 
        </form> 
    </div>

    <p>
        <a style="position:fixed; bottom: 5%; left:30%;" href="reset-password.php" class="btn btn-warning">Change Your Password</a>
        <a style="position:fixed; bottom: 5%; right:30%;" href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>

    <script>
        var time_out_counter = window.name;
        var refreshIntervalId = setInterval('autoRefresh()', 10000);
 
        function autoRefresh() {
            var in_middle_of_typing = document.getElementById("text_m").value
            if (in_middle_of_typing == "") {
            window.location = window.location.href;
            }
            time_out_counter++;
            window.name = time_out_counter;
            console.log("time out: " + time_out_counter);
            console.log("window" + window.name);
            if (time_out_counter >= 90) {
                window.location = 'logout.php';
            }
        }

        function redo_window() {
            window.name = 0;
            time_out_counter = 0;
        }
    </script>
</body>
</html>