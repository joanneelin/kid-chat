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
        <p>Please fill in your email and press submit. An email will be sent to you with the verfication code included.
        </p>
        <form method="post"> 
        <label for="email">email</label>
            <input type="text" name="email"/>
            <input type="submit" name="button1"
                class="button" value="submit" onclick=""/> 
        </form>
    </div>

<?php
    $number_one = rand(0,9);
    $upper_one = chr(rand(65,90));
    $lower_one = chr(rand(97,122));
    $number_two = rand(0,9);
    $upper_two = chr(rand(65,90));
    $lower_two = chr(rand(97,122));
    $verfication_code = $number_one . $upper_one . $lower_one . $number_two . $upper_two . $lower_two;

    if(array_key_exists('button1', $_POST)) {
        button1();
    }

    function button1() { 
        if ($_POST["email"] != null) {
        global $verfication_code;
        $to = $_POST["email"];
$subject = "Password Reset";
        $message = "Hello! Your verification code is here: ";
        $message .= $verfication_code;
        echo "v code: " . $verfication_code;
        $headers = 'From: your_email@example.com' . "\r\n" .
        'Reply-To: your_email@example.com';
        mail($to, $subject, $message, $headers);

        header("location: confirm_password_reset.php?hehe=" . $verfication_code . "&email=" . $_POST["email"]);
        }
    }
?>

</body>
</html>