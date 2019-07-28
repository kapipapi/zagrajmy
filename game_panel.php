<?php

session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['pass'])) {
    header("Location: index.php");
    session_destroy();
}

if (!isset($_GET['id'])){
    header("Location: index.php");
}

require_once('connect.php');

$game_id = $_GET['id'];

$sql = "SELECT * FROM games WHERE id = $game_id";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_errno);
}

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $game = array(
                "id" => $row['id'],
                "sport" => $row['sport'],
                "place" => $row['place'],
                "date" => $row['date'],
                "list" => $row['list'],
                "info" => $row['info']
            );
        }
    }
    $result->free_result();
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zagrajmy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <!-- LEAFLET -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <style>

        #user-panel {
            margin-left: 3vh;
        }

        .poziom {
            position: relative;
            display: block;
            width: 80%;
            color: black;
            font-size: 1.5vh;
            padding: .5vh;
            border: 1px gray solid;
            border-radius: 0.5vh;
        }

        .poziom::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: lightblue;
            z-index: -1;
        }

        .p1::after {
            width: 40%;
        }

        .p2::after {
            width: 60%;
        }

        .p3::after {
            width: 80%;
        }

        .p4::after {
            width: 100%;
        }

    </style>
</head>
<body>
    
    <!-- LOAD NAVIGATION BAR -->
    <?php require_once('./parts/load_nav.php'); ?>

    <div id='main' class='container-fluid'>
        <div class='row'>
            <div id='user-panel' class='col-sm-6'>
                <?php 
                if(isset($_SESSION['user_info'])) {
                    echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>" . $_SESSION['user_info'] . "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                    unset($_SESSION['user_info']);  
                }

                echo "<h1>".$game['sport']."</h1>"; 
                echo "<h2>".$game['date']."</h2>";
                echo "<h3>";
                $boiska = json_decode(file_get_contents('./scripts/json/boiska.json'));
                foreach($boiska as $b) {
                    if($b[0] == $game["place"]) {
                        echo $b[2] . "</br>";
                        echo $b[3];
                        break;
                    }
                }
                echo "</h3>";

                echo "<hr/>";

                echo "lista graczy"
                
                ?>
            </div>
            <map-box class="col-sm-5" id="user_map">
                <div id="mapid"></div>
                <div class='row'>
                    <div class='col'>
                        <p class="text-center">Kliknij na mapie boisko aby zobaczyć co się tam dzieje!</p>
                    </div>
                </div>
            </map-box>
        </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="./scripts/script.js"></script>
    <script src="./scripts/loadMap.js"></script>
    <script>
        
        <?php
        foreach($boiska as $b) {
            if($b[0] == $game["place"]) {
                echo "mymap.setView(".json_encode($b[1]).", 14)";
            }
        }
        ?>

    </script>
</body>
</html>