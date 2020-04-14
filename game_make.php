<?php

session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['pass'])) {
    header("Location: index.php");
    session_destroy();
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zagrajmy - tworzenie gry</title>
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

    #sport_selector {
        /*  */
    }

    .btn {
        width: 40%;
    }

    .sport_radio {
        width: 7vmax;
        height: 7vmax;
        border: 1px solid #000;
        border-radius: 1em;
        cursor: pointer;
        padding: 0px 1vmax;
    }

    .sport_radio img {
        width: 5vmax;
    }

    .sport_radio p {
        text-align: center;
    }

    .sport_radio input[type=radio] { 
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .sport_radio input[type=radio]:checked + img + p{
        font-weight: bold;
    }

    .sport.radio input[type=radio]:checked 

    .custom-range {
        width: 70%;
    }
    
    .friend{
        padding-left: 10%;
    }

    </style>
</head>
<body>

<!-- LOAD NAVIGATION BAR -->
<?php require_once('./parts/load_nav.php'); ?>

<div id='main' class='container'>
    <div class='row'>
        <div class='col-sm-5'>
        <form action="game_make_engine.php" method="post">
            <h3>Tworzenie gry:</h3>
            <div>
            <?php
            foreach($_SESSION['make_game_err'] as $err) {
                echo "<p class='err'>". $err . "</p>";
            }
            unset($_SESSION['make_game_err']);
            ?>
            </div>
            <!-- SPORT SELECTION -->
            <div id='sport_selector' class="form-group">
                <?php
                foreach(json_decode(file_get_contents('./scripts/json/sporty.json')) as $s) {
                    echo" <label class='sport_radio shadow'><input type='radio' name='sport' value='$s'><img src='./img/sports/$s.png'/><p>$s</p></input></label>";
                }
                ?>
            </div>
            <hr/>
            <!-- TEAMSIZE SLIDER  -->
            <div class="form-group">
                <label>Ile osób będzie uczestniczyło w grze?</label>
                <input id="playersInput" type="range" class="custom-range" min="2" max="10" name="teamsize">
                <span id='counter_show'>6</span>
            </div>
            <hr/>
            <!-- DATE SELECT -->
            <div class="form-group">
                <label>Kiedy odbędzie się rozgrywka?</label></br>
                <input type="date" name="dateofgame">
            </div>
            <!-- PLACE SELECT -->
            <div class="form-group">
                <label>Gdzie chcecie grać?</label></br>
                <select id='place' name='place'>
                <option style="display:none;" selected>Wybierz boisko</option>
                <?php
                    $boiska = json_decode(file_get_contents('./scripts/json/boiska.json'));
                    foreach ($boiska as $b){
                        echo "<option value='$b[0]'>$b[2] ($b[3])</option>";
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <?php
                    $friends = json_decode($_SESSION['friends']);
                    if(sizeof($friends)>0){
                        echo "<label>Kogo ze znajomych chcesz zaprosić?</label></br>";
                        foreach($friends as $f) {
                            echo "<div class='friend'>";
                            echo "<input class='form-check-input' type='checkbox' value='" . $f . "' name='friend_id[]'>";
                            echo "<label class='form-check-label'>" . $f . " ". $f . "</label>";
                            echo "</div>";
                        }
                    }
                ?>
            </div>
            <a href="user_panel.php"><input class="btn btn-danger" value="Anuluj"></a>
            <input class="btn btn-success" type="submit" value="Dodaj grę">
        </div>
        <map-box class="col-sm" id="user_map">
            <div id="mapid"></div>
        </map-box>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        let range = document.getElementById('playersInput');
        range.addEventListener('input', ()=>{
            document.getElementById('counter_show').innerHTML = range.value;
        })
    });
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
<script src="./scripts/script.js"></script>
<script src="./scripts/loadMap_gamemaker.js"></script>
</body>
</html>