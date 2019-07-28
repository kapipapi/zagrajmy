<?php

session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['pass'])) {
    header("Location: index.php");
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

$max_teamsize = json_decode($game['info'])->teamsize;
$list_count = sizeof(json_decode($game['list']));

if($max_teamsize > $list_count) {

    $success = TRUE;
    $list = json_decode($game['list']);

    foreach($list as $id) {
        if($id == $_SESSION['id']){
            $success = FALSE;
            $_SESSION['user_info'] = "Jesteś już na liście w wybranej grze.";
            break;
        }
    }
    if($success) {
        array_push($list, $_SESSION['id']);
        $sql_update_list = "UPDATE games SET list = '" . json_encode($list) . "' WHERE id = " . $game_id;
        $conn->query($sql_update_list);
    }
    header("Location: user_panel.php");
} else {
    $_SESSION['user_info'] = "Nie mozna dołaczyć do gry. Lista jest pełna.";
}

?>