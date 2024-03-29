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
    $date = $_POST['dateofgame'];
}

//PLACE
if(empty($_POST['place'])) {
    $_SESSION['make_game_err'][0] = "Miejsce wymagane";
    $make_game_err = true;
} else {
    $place = $_POST['place'];
}

// PLAYER LIST
$playerList = array();
array_push($playerList, $_SESSION['id']);
if(!empty($_POST['friend_id'])){
    foreach($_POST['friend_id'] as $f) {
        array_push($playerList, $f);
    }
}
$playerListJSON = json_encode($playerList);

//Maker info
$infoJSON->makerid = $_SESSION['id'];
$infoJSON->makeremail = $_SESSION['email'];
$infoJSON->teamsize = $teamsize;
$infoTEXT = json_encode($infoJSON);

// ACCEPTATION
if (!$make_game_err) {
    $sql = "INSERT INTO `games` (
        `id`,
        `sport`,
        `place`,
        `date`,
        `list`,
        `info`
        ) VALUES (
            NULL,
            '$sport',
            '$place',
            '$date', 
            '$playerListJSON',
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