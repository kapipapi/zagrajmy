<?php

session_start();

require_once('connect.php');

if (!empty($_POST['email']) && !empty($_POST['pass'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
} else {
    $_SESSION['login-err'] = "Puste dane logowania.";
    header("Location: index.php");
}

$sql = "SELECT * FROM users WHERE email='$email'";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_errno);
}

$login_err = false;

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($pass, $row['haslo'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['imie'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['pass'] = $row['haslo'];
                $_SESSION['sporty'] = $row['sporty'];
                $result->free_result();
                header("Location: user_panel.php");
            }
        }
    }
}

$result->free_result();
$_SESSION['login-err'] = "Błędne dane logowania.";
header("Location: index.php");

?>