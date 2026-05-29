<?php
        $the_first_name = htmlspecialchars($_SESSION["first_name"]);
        $talk = $_GET["talk_to"];
        $current_time = $_COOKIE['datetime'];

        try{
            $pdo = new PDO("mysql:host=localhost;dbname=your_db_name", "your_db_username", "your_db_password");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
            $sql = "UPDATE kid_chat_group SET last_read_time = :date_hehe WHERE group_name = :chat AND accounts = :first_name";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":first_name", $the_first_name, PDO::PARAM_STR);
                $stmt->bindParam(":chat", $talk, PDO::PARAM_STR);
                $stmt->bindParam(":date_hehe", $current_time, PDO::PARAM_STR);
                $stmt->execute();
            }
        } catch(PDOException $e){
            die("ERROR: Could not connect. " . $e->getMessage());
        }

        require_once "config.php";

        echo "<table style='border: solid 1px black;'>";
        echo "<tr><th>Group Chats</th><th>Unread Messages</th></tr>";
        
        class TableRowTableRows extends RecursiveIteratorIterator {
          function __construct($it) {
            parent::__construct($it, self::LEAVES_ONLY);
          }
        
          function current() {
            return "<td style='width:150px;border:1px solid black;'>" . "<a href='welcome.php?talk_to=" . parent::current() . "&group=true'>" . parent::current() . "</a>" . "</td>";
          }
        
          function beginChildren() {
            echo "<tr>";
          }
        
          function endChildren() {
            echo "</tr>" . "\n";
          }
        }
        
        try {
            $sql = "SELECT group_name, sum(cnt) from(
                SELECT group_name, count(*) cnt from
                            (
                            SELECT from_who, to_who, text_message, insert_time, to_group 
                            FROM kid_chat 
                            WHERE to_group != ''
                            ORDER BY insert_time
                            ) msg,
                            (SELECT * FROM kid_chat_group WHERE accounts = :username) acc
                            WHERE last_read_time < insert_time
                            AND from_who != :username
                            AND msg.to_group = acc.group_name
                            GROUP bY group_name
                UNION ALL
                SELECT group_name, 0 cnt FROM kid_chat_group WHERE accounts = :username) fe
                group by group_name
                order by group_name";

            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":username", $the_first_name, PDO::PARAM_STR);
                $stmt->execute();
            }

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach(new TableRowTableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                echo $v;
            }
        }
        catch(PDOException $e)
        {
            die("ERROR: " . $e->getMessage());
        }
        echo "</table>";
    ?>