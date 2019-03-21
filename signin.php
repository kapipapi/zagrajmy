<?php

session_start();

require_once('connect.php');

if (isset($_SESSION['email']) || isset($_SESSION['pass'])) {
    header("Location: index.php");
}

$signin_err = false;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $signin_err = true;
    $_SESSION['signin_err'][0] = "MySQL problem";
}

//imie nazwisko
if (empty($_POST['imie'])) {
    $_SESSION['signin_err'][1] = "Imie wymagane";
    $signin_err = true;
} else {
    $imie = $_POST['imie'];
}

if (empty($_POST['nazwisko'])) {
    $_SESSION['signin_err'][2] = "Nazwisko wymagane";
    $signin_err = true;
} else {
    $nazwisko = $_POST['nazwisko'];
}

//EMAIL
if (empty($_POST['email'])) {
    $_SESSION['signin_err'][3] = "Email wymagany";
    $signin_err = true;
} else {
    $email = $_POST['email'];
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['signin_err'][3] = "Błędny email";
    $signin_err = true;
}

$sql_email = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql_email);
if ($result->num_rows > 0) {
    $signin_err = true;
    $_SESSION['signin_err'][3] = "Email zajęty";
}

// PASSWORD
if (empty($_POST['pass']) && empty($_POST['pass2'])) {
    $_SESSION['signin_err'][4] = "Hasło wymagane";
    $signin_err = true;
} else {
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];
}

if (!preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!?@#$%^&*]{8,12}$/", $pass)) {
    $_SESSION['signin_err'][5] = "Hasło musi:</br>
    -zawierać przynajmniej jedną literę i cyfrę</br>
    -zawierać znaki specjalne (np. ! ? * % #)</br>
    -być długości 8-12 znaków";
    $signin_err = true;
}

if ($pass !== $pass2) {
    $_SESSION['signin_err'][5] = "Hasła muszą być takie same";
    $signin_err = true;
} else {
    $pass = crypt($pass);
}

// SPORTY
$sports = json_decode(file_get_contents('./scripts/json/sporty.json'));
$user_sports = array();
foreach($sports as $s) {
    $sport = array();
    if(isset($_POST[$s])){
        array_push($sport, $_POST[$s]);
        array_push($sport, $_POST['poziom'.$s]);
        array_push($user_sports, $sport);
    }
}
$json_user_sports = json_encode($user_sports);

// BIRTHDAY
$user_birthday = $_POST['dateofbirth'];

// ACCEPTATION
if (!$signin_err) {
    $sql = "INSERT INTO `users` (
        `id`,
        `imie`,
        `nazwisko`,
        `login`,
        `email`,
        `haslo`,
        `sporty`,
        `urodziny`,
        `info`
        ) VALUES (
            NULL,
            '$imie',
            '$nazwisko',
            '$login', 
            '$email',
            '$pass',
            '$json_user_sports',
            '$user_birthday',
            '{}'
            );";
    if (!$conn->query($sql)) {
        $_SESSION['signin_err'][0] = "Operacja nie powiodła się :(";
    } else {
        $_SESSION['signin_err'][0] = "Operacja powiodła się!";
    }
    header("Location: index.php");
} else {
    header("Location: index.php");
}
