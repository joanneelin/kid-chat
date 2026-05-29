<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>

    <div class="wrapper">
        <h2>Reset Your Password</h2>
        <p>Please type in the verification code and press submit.
        </p>
        <form method="post"> 
        <label for="v_code">verification code</label>
            <input type="text" name="v_code"/>
            <input type="submit" name="button2"
                class="button" value="submit" onclick=""/> 
        </form>
    </div>

<?php
    if(array_key_exists('button2', $_POST)) {
        button2();
    }

    function button2() { 
        if ($_POST["v_code"] != null) {
            if ($_POST["v_code"] == $_GET["hehe"]) {
                header("location: last_step_change_password.php?email=" . $_GET["email"]);
            } else {
                echo "you did not enter the correct code";
            }
        }
    }
?>

</body>
</html>