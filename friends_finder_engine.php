<?php

session_start();
require_once('connect.php');
if (!isset($_SESSION['email']) || !isset($_SESSION['pass'])) {
    header("Location: index.php");
}

$friend_id = $_GET['id'];

$sql = "SELECT * FROM users WHERE id = ".$friend_id;

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_errno);}

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $friend_friends =  json_decode($row['friends']);
        }
    }
}

$user_friends = json_decode($_SESSION['friends']);

array_push($friend_friends, (int)$_SESSION['id']); //adding user to friend friends
array_push($user_friends, (int)$friend_id); //adding friend to user friends

$_SESSION['friends'] = json_encode($user_friends); //updating session info

$sql = "UPDATE users SET friends = '" . json_encode($friend_friends) . "' WHERE id = " . $friend_id;
$conn->query($sql);

$sql = "UPDATE users SET friends = '" . json_encode($user_friends) . "' WHERE id = " . $_SESSION['id'];
$conn->query($sql);

header("Location: user_panel.php");

?>