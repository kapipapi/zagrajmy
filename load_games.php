<?php

require_once('connect.php');

$sql = "SELECT * FROM games ORDER BY data ASC LIMIT 3";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_errno);
}

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $games = array();
        while ($row = $result->fetch_assoc()) {
            $game = array(
                "id" => $row['id'],
                "sport" => $row['sport'],
                "place" => $row['miejsce'],
                "date" => $row['data'],
                "info" => $row['info']
            );
            array_push($games, $game);
        }
    }
    $result->free_result();
}

if(sizeof($games)>0){
    echo "<h3>Gry w okolicy:</h3>";
    foreach($games as $g) {
        echo "<div class='game'>";
        echo "<p>" . $g["sport"] . "</p>";
        $boiska = json_decode(file_get_contents('./scripts/json/boiska.json'));
        foreach($boiska as $b) {
            if($b[0] == $g["place"]) {
                echo "<p>" . $b[2] . "</p>";
                echo "<p>" . $b[3] . "</p>";
                break;
            }
        }
        echo "<p>" . $g["date"] . "</p>";
        echo "</div>";
    }
}
?>