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
    <title>Zagrajmy - witaj <?php echo $_SESSION['name'];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <!-- LEAFLET -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<header class='container-fluid'>
    <a href="./index.php"><h1>ZagrajMY</h1></a>
</header>

<div id='main' class='container'>
    <div class='row'>
        <div class='col-sm-5'>
            <?php 
            if(isset($_SESSION['user_info'])) {
                echo "<script>alert(\"". $_SESSION['user_info']."\");</script>";
                unset($_SESSION['user_info']);
            }
            echo "<h1>Witaj " . $_SESSION['email'] . "</h1>"; 
            echo "<p>[<a href='logout.php'>Wyloguj się</a>]</p>";
            echo "<p>[<a href='game_make.php'>Strórz grę!</a>]</p>";
            echo "<hr/>";
            if(isset($_SESSION['sporty'])){
                echo "<h3>Twoje sporty:</h3><ul>";
                foreach(json_decode($_SESSION['sporty']) as $s) {
                    echo "<li>$s[0] - $s[1]</li>";
                }
                echo "</ul>";
            }
            echo "<hr/>";
            echo "<h3>Gry w okolicy:</h3>";
            require_once('load_games.php');
            ?>
        </div>
        <map-box class="col-sm" id="user_map">
            <div id="mapid"></div>
        </map-box>
    </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
<script src="./scripts/script.js"></script>
<script src="./scripts/loadMap.js"></script>
</body>
</html>