<style>
    #players {
        display: flex;
    }

    .player {
        display: block;
        width: 5em;
    }

    .player img {
        border-radius: 2em;
        width: 4em;
        height: 4em;
        object-fit: cover;
    }
</style>

<?php

require_once('connect.php');

$sql = "SELECT * FROM games ORDER BY date ASC LIMIT 4";
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
                "place" => $row['place'],
                "date" => $row['date'],
                "list" => $row['list'],
                "info" => $row['info']
            );
            array_push($games, $game);
        }
    }
    $result->free_result();
}

function getPlayer($id_list) {
    require('connect.php');
    $playerSql = "SELECT * FROM users WHERE id = " . $id_list;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_errno);
    }
    if ($result = $conn->query($playerSql)) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='player col-sm text-center'>";
                echo "<p>".$row['name']."</p>";
                echo "<img class='shadow' src='./img/users/".$id_list.".png' />";
                echo "</div>";
            }
        }
    }
}

if(sizeof($games)>0){
    echo "<h3>Gry w okolicy:</h3>";
    echo "<div id='games'>";
    foreach($games as $g) {
        echo "<div class='game shadow'>";
        echo "<h2>" . $g["sport"] . "</h2>";
        $boiska = json_decode(file_get_contents('./scripts/json/boiska.json'));
        foreach($boiska as $b) {
            if($b[0] == $g["place"]) {
                echo "<p>" . $b[2] . "</p>";
                echo "<p>" . $b[3] . "</p>";
                break;
            }
        }
        echo "<p>" . $g["date"] . "</p>";
            echo "<div class='row' id='players'>";
                $list = json_decode($g['list']);
                for($i=0; $i < 3; $i++) {
                    getPlayer($list[$i]);
                }
                if(sizeof($list)<3) {
                    for($i=0; $i < 3-sizeof($list); $i++) {
                        echo "<div class='player col-sm text-center'>";
                        echo "<p>wolne</p>";
                        echo "<a href='joining_game_engine.php?id=".$g['id']."'>";
                        echo "<img class='shadow' src='./img/users/0.png' />";
                        echo "</a>";
                        echo "</div>";
                    }
                }
                // }else {
                //     echo "<div class='player col-sm text-center'>";
                //     echo "<p>".(sizeof($list)-3)."</p>";
                //     echo "<img class='shadow' src='./img/users/0.png' />";
                //     echo "</div>";
                // }
            echo "</div>";
        echo "</div>";
    }
    echo "</div>";
}
?>