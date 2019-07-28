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
    <title>Zagrajmy - dodaj znajomych</title>
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
        .user_friend {
            border: 1px solid black;
            margin: 1vh;
        }

        .user_list {
            border-radius: 2em;
            width: 4em;
            height: 4em;
            object-fit: cover;
        }
    </style>
</head>
<body>

<!-- LOAD NAVIGATION BAR -->
<?php require_once('./parts/load_nav.php'); ?>

<div id='main' class='container'>
<?php

echo "<p>[<a href='user_panel.php'><-Powrót</a>]</p>";

$friends = json_decode($_SESSION['friends']);
$friendsIDsql = " ";

foreach($friends as $f) {
    $friendsIDsql = $friendsIDsql . "AND id != " . $f . " ";
}

require_once('connect.php');
$sql = "SELECT * FROM users WHERE email NOT LIKE '". $_SESSION['email'] ."'".$friendsIDsql."ORDER BY name ASC LIMIT 3";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_errno);}
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $user = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "surname" => $row['surname'],
                "info" => $row['info']
            );
            array_push($users, $user);
        }
    }
}

if(sizeof($users)>0) {
    foreach($users as $u) {
        echo "<div class='user_friend'>";
        echo "<div class='row'>";
        echo "<div class='col-sm'><img class='shadow user_list' src='./img/users/".json_decode($u['info'])->photo."' /></div>";
        echo "<div class='col-sm'>".$u["id"]."</div>";
        echo "<div class='col-sm'>".$u["name"]."</div>";
        echo "<div class='col-sm'>".$u["surname"]."</div>";
        echo "<div class='col-sm'><a href='friends_finder_engine.php?id=".$u["id"]."'><input class='btn btn-success float-right' type='submit' value='Dodaj do znajomych'></a></div>";
        echo "</div>";
        echo "</div>";
    }
}else{
    echo "<a class='h2'>Wszyscy już są twoimi znajomymi :)</p>";
}
$result->free_result();

?>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
<script src="./scripts/script.js"></script>
</body>
</html>