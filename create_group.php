<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--<meta http-equiv="refresh" content="900;url=logout.php"/>-->
    <title>Welcome</title>
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        .first_table { float: left; padding: 20px; width: 50%; height: 800px; }
        .second_table { float: left; padding: 20px; width: 50%; height: 800px; }
    </style>
</head>
<body>    
    <?php
        require_once "config.php";

        echo "<form method='post'>";
        echo "<table style='border: solid 1px black;'>";
        echo "<tr><th>Check</th><th>Names</th></tr>";
        
        class TableRows extends RecursiveIteratorIterator {
          function __construct($it) {
            parent::__construct($it, self::LEAVES_ONLY);
          }
        
          function current() {
            //echo '<td><form method="get"><input type="checkbox" name="color[]" id="color" value="' . parent::current() . '"></form></td>';
            echo '<td><input type="checkbox" name="color[]" value="' . parent::current() . '"></td>';
            echo "<td style='width:150px;border:1px solid black;'>" . parent::current() . "</td>";
          }
        
          function beginChildren() {
            echo "<tr>";
          }
        
          function endChildren() {
            echo "</tr>" . "\n";
          }
        }
        
        try {
            $sql = "SELECT first_name FROM accounts";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                echo $v;
            }
        }
        catch(PDOException $e)
        {
            
        }
        echo "</table>";
        echo "<br></br>";
        echo '<label style="float:left;" for="group_name">group name </label><input style="float:left;" type="text" name="group_name"/>';
        echo "<br></br>";
        echo "<input style='float:left;' type='submit' name='submit' value='Submit'/>";
        echo "</form>";
    ?>

    <br></br>

        <?php
            if(isset($_POST['submit'])){
                if ($_POST["group_name"] != null) {
                    $group_name = $_POST["group_name"];

                    if(!empty($_POST['color'])){
                        foreach($_POST['color'] as $selected){
                            //echo $selected."</br>";
                            try{
                                $pdo = new PDO("mysql:host=localhost;dbname=your_db_name", "your_db_username", "your_db_password");
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                                $sql = "INSERT INTO kid_chat_group (group_name, accounts) VALUES (:group, :person)";
                                if ($stmt = $pdo->prepare($sql)) {
                                    $stmt->bindParam(":group", $group_name, PDO::PARAM_STR);
                                    $stmt->bindParam(":person", $selected, PDO::PARAM_STR);
                                    $stmt->execute();
                                }
                            } catch(PDOException $e){
                                die("ERROR: Could not connect. " . $e->getMessage());
                            }
                        }
                        header("location: welcome.php");
                    }
                }
            }
        ?>
</body>
</html>