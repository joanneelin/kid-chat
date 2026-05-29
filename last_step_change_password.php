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
        <p>Please fill out this form to reset your password.
        </p>
        <form method="post"> 
            <label for="pass">password</label>
            <input type="text" name="pass"/>

            <label for="confirm_pass">confirm password</label>
            <input type="text" name="confirm_pass"/>

            <input type="submit" name="button3"
                class="button" value="submit" onclick=""/> 
        </form>
    </div>

<?php
    if(array_key_exists('button3', $_POST)) {
        button3();
    }

    function button3() { 
        if ($_POST["pass"] != null && $_POST["confirm_pass"] != null) {
            if ($_POST["pass"] == $_POST["confirm_pass"]) {
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=your_db_name", "your_db_username", "your_db_password");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $hashed = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                    $sql = "UPDATE accounts SET pass = '" . $hashed . "' WHERE email = '" . $_GET['email'] . "'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                }
                catch(PDOException $e) {
                    die("ERROR: Could not connect. " . $e->getMessage());
                }
            } else {
                echo "The two passwords you entered do not match.";
            }
        }
    }
?>

</body>
</html>