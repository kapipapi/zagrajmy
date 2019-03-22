<?php

session_start();

require_once('connect.php');
if (!isset($_SESSION['email']) || !isset($_SESSION['pass'])) {
    header("Location: index.php");
}

$make_game_err = false;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $make_game_err = true;
    $_SESSION['make_game_err'][0] = "MySQL problem";
}
$make_game_err = false;

//SPORT
if(empty($_POST['sport'])) {
    $_SESSION['make_game_err'][0] = "Sport wymagany";
    $make_game_err = true;
} else {
    $sport = $_POST['sport'];
}

//COUNTER
if(isset($_POST['teamsize'])) {
    $teamsize = $_POST['teamsize'];
} else {
    $make_game_err = true;
}

//DAY OF GAME
if(empty($_POST['dateofgame'])) {
    $_SESSION['make_game_err'][1] = "Dzień wymagany";
    $make_game_err = true;
} else {
    $data = $_POST['dateofgame'];
}

//PLACE
if(empty($_POST['place'])) {
    $_SESSION['make_game_err'][0] = "Miejsce wymagane";
    $make_game_err = true;
} else {
    $place = $_POST['place'];
}
$infoJSON->makername = $_SESSION['name'];
$infoJSON->makeremail = $_SESSION['email'];
$infoJSON->teamsize = $teamsize;
$infoTEXT = json_encode($infoJSON);

// ACCEPTATION
if (!$make_game_err) {
    $sql = "INSERT INTO `games` (
        `id`,
        `sport`,
        `miejsce`,
        `data`,
        `lista`,
        `info`
        ) VALUES (
            NULL,
            '$sport',
            '$place',
            '$data', 
            '{}',
            '$infoTEXT'
            );";
    if (!$conn->query($sql)) {
        $_SESSION['make_game_err'][0] = "Nie udało się utworzyć gry :(";
        header("Location: game_make.php");
    } else {
        $_SESSION['user_info'] = "Utworzyłeś grę!";
        header("Location: user_panel.php");
    }
} else {
    header("Location: game_make.php");
}

?> 