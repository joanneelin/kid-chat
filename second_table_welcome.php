<?php
    $first = htmlspecialchars($_SESSION["first_name"]);
    $talk = $_GET["talk_to"];
    $group_or_individual = $_GET["group"];
    
    if(array_key_exists('button1', $_POST)) {
        button1();
        header("location: welcome.php?talk_to=" . $_GET['talk_to'] . "&group=" . $_GET["group"]);
        $_POST = array();
        echo $_SERVER["REQUEST_METHOD"];
    }

        require_once "config.php";

        echo "<table style='border: solid 1px black; word-break: break-word;'>";
        
        class RowTableRows extends RecursiveIteratorIterator {
          function __construct($it) {
            parent::__construct($it, self::LEAVES_ONLY);
          }
        
          function current() {
            return "<td style='width:150px;border:1px solid black;'>" . parent::current() . "</td>";
          }
        
          function beginChildren() {
            echo "<tr>";
          }
        
          function endChildren() {
            echo "</tr>" . "\n";
          }
        }

    if ($group_or_individual == "false") {
        echo "Your chat with: " . $talk;
        echo "<tr><th>Time</th><th>$talk</th><th>$first</th></tr>";
        
        try {
            $sql = "SELECT insert_time, CASE WHEN from_who = :others THEN text_message ELSE '' END AS :others, 
            CASE WHEN from_who = :myself THEN text_message ELSE '' END AS :myself FROM 
            (SELECT from_who, to_who, text_message, insert_time 
            FROM kid_chat 
            WHERE (to_who = :others OR to_who = :myself) AND (from_who = :myself OR from_who = :others) AND group_chat = 'no'
            ORDER BY insert_time DESC LIMIT 20) sub 
            ORDER BY insert_time ASC";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":others", $talk, PDO::PARAM_STR);
                $stmt->bindParam(":myself", $first, PDO::PARAM_STR);
                $stmt->execute();

                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach(new RowTableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                    echo $v;
                }
            }
        }
        catch(PDOException $e)
        {
            
        }
        echo "</table>"; 
    } else {
        echo "Your group chat: " . $talk;
        echo "<tr><th>Time</th><th>from_who</th><th>message</th><th>me</th></tr>";
    
        try {
            $sql = "SELECT insert_time, CASE WHEN from_who != :myself THEN from_who ELSE '' END AS 'from_who',
            CASE WHEN from_who != :myself THEN text_message ELSE '' END AS 'message',
            CASE WHEN from_who = :myself THEN text_message ELSE '' END AS 'me' FROM
			(SELECT from_who, to_who, text_message, insert_time 
            FROM kid_chat 
            WHERE to_group = :chat
            ORDER BY insert_time DESC LIMIT 20) sub
            ORDER BY insert_time ASC";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":chat", $talk, PDO::PARAM_STR);
                $stmt->bindParam(":myself", $first, PDO::PARAM_STR);
                $stmt->execute();

                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach(new RowTableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                    echo $v;
                }
            }
        }
        catch(PDOException $e)
        {
            
        }
        echo "</table>"; 
    }

        function button1() { 
            $fname = htmlspecialchars($_SESSION["first_name"]);
            $talk_t = $_GET["talk_to"];
            $g_o_ind = $_GET["group"];
            if ($_POST["test_message"] != null) {
            $text_message = $_POST["test_message"];

            $current_time = $_COOKIE['datetime'];
            //echo "safewafea" . $current_time;

            try{
                $pdo = new PDO("mysql:host=localhost;dbname=your_db_name", "your_db_username", "your_db_password");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                if ($g_o_ind == "false") {
                    //individual
                    $sql = "INSERT INTO kid_chat (from_who, to_who, text_message, insert_time) VALUES (:first_name, :talk_to, :t_message, :dateandtime)";
                } else {
                    //group

                    $sql = "INSERT INTO kid_chat (from_who, to_group, to_who, text_message, group_chat, insert_time) VALUES (:first_name, :talk_to, '', :t_message, 'yes', :dateandtime)";
                }
                if ($stmt = $pdo->prepare($sql)) {
                    $stmt->bindParam(":first_name", $fname, PDO::PARAM_STR);
                    $stmt->bindParam(":talk_to", $talk_t, PDO::PARAM_STR);
                    $stmt->bindParam(":t_message", $text_message, PDO::PARAM_STR);
                    $stmt->bindParam(":dateandtime", $current_time, PDO::PARAM_STR);
                    $stmt->execute();
                }
            } catch(PDOException $e){
                die("ERROR: Could not connect. " . $e->getMessage());
            }
            }
        }
    ?>