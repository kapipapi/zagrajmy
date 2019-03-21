<?php

require_once('connect.php');

$sql = "SELECT * FROM games";
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

foreach($games as $g) {
    echo "<div class='game'>";
    echo "<p>" . $g["sport"] . "</p>";
    echo "<p>" . $g["place"] . "</p>";
    echo "<p>" . $g["date"] . "</p>";
    echo "</div>";
}

$result->free_result();


?>