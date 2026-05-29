    <?php
        $the_first_name = htmlspecialchars($_SESSION["first_name"]);
        $talk = $_GET["talk_to"];

        try{
          $pdo = new PDO("mysql:host=localhost;dbname=your_db_name", "your_db_username", "your_db_password");
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $sql = "UPDATE kid_chat SET unread = 'no' WHERE from_who = '" . $talk . "' AND to_who = '" . $the_first_name . "' AND unread = 'yes'";
          //$sql1= "UPDATE kid_chat SET unread = 'no' WHERE from_who = 'first_name' AND to_who = 'test_one' AND unread = 'yes'";
          //echo($sql);
          //echo($sql1);
          
          $stmt = $pdo->prepare($sql);
          $stmt->execute();
      } catch(PDOException $e){
          die("ERROR: Could not connect. " . $e->getMessage());
      }

        require_once "config.php";

        echo "<table style='border: solid 1px black;'>";
        echo "<tr><th>Names</th><th>Unread Messages</th></tr>";
        
        class TableRows extends RecursiveIteratorIterator {
          function __construct($it) {
            parent::__construct($it, self::LEAVES_ONLY);
          }
        
          function current() {
            return "<td style='width:150px;border:1px solid black;'>" . "<a href='welcome.php?talk_to=" . parent::current() . "&group=false'>" . parent::current() . "</a>" . "</td>";
          }
        
          function beginChildren() {
            echo "<tr>";
          }
        
          function endChildren() {
            echo "</tr>" . "\n";
          }
        }
        
        try {
            //$sql = "SELECT first_name FROM accounts WHERE NOT first_name = :username";
            $sql = "SELECT from_who, sum(cnt) FROM
            (SELECT from_who, sum(CASE WHEN unread='yes' then 1 else 0 END) cnt
            FROM kid_chat WHERE to_who = :username GROUP BY from_who
            UNION ALL
            SELECT DISTINCT from_who, 0 cnt FROM kid_chat) as aaa 
            WHERE from_who != :username
            GROUP BY from_who";

            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":username", $the_first_name, PDO::PARAM_STR);
                $stmt->execute();
            }

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                echo $v;
            }
        }
        catch(PDOException $e)
        {
            
        }
        echo "</table>";
    ?>