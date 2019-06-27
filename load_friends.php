<?php

$friends = json_decode($_SESSION['friends']);
$friendsIDsql = " ";

if(sizeof($friends)>0){
    echo "<h3>Twoi znajomi:</h3>";
    echo "<div id='friends'>";
    foreach($friends as $f) {
        $friendsIDsql = $friendsIDsql . "OR (id LIKE " . $f . ") ";
    }

    require_once('connect.php');
    $sql = "SELECT * FROM users WHERE (email NOT LIKE '". $_SESSION['email'] ."') " . $friendsIDsql . "ORDER BY name ASC LIMIT 3";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_errno);}
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='friend shadow'>";
                echo "<p>" . $row['name'] . " " . $row['surname'] ."</p>";
                echo "</div>";
            }
        }
    }
    echo "</div>";
}

?>