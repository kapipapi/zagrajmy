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

$friend_name;
$friend_surname;
$friend_friends;

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $friend_name = $row['imie'];
            $friend_surname = $row['nazwisko'];
            $friend_info =  json_decode($row['info']);
            $friend_friends = $friend_info->friends;// friend friends array
        }
    }
}

class User {
    public $id;
    public $name;
    public $surname;
    public function __construct($Cid,$Cname,$Csurname){
        $this->id = $Cid;
        $this->name = $Cname;
        $this->surname = $Csurname;
    } 
}
$friend = new User($friend_id, $friend_name, $friend_surname); //friend object

$user_info = json_decode($_SESSION['info']);
$user_friends = $user_info->friends; // user friends array
$user = new User($_SESSION['id'], $_SESSION['name'], $_SESSION['surname']); //user object

array_push($friend_friends, $user); //adding user to friend friends
array_push($user_friends, $friend); //adding friend to user friends

$friend_info->friends = $friend_friends;
$user_info->friends = $user_friends;

$_SESSION['info'] = json_encode($user_info); //updating session info

$sql = "UPDATE users SET info = '".json_encode($friend_info)."' WHERE id = " . $friend_id;
if (!$conn->query($sql)) {
    echo "1 zjebalo!";
} else {
    echo "1 poszlo!";
}

$sql = "UPDATE users SET info = '".json_encode($user_info)."' WHERE id = " . $_SESSION['id'];
if (!$conn->query($sql)) {
    echo "2 zjebalo!";
} else {
    echo "2 poszlo!";
}

header("Location: user_panel.php");

?>