<?php
session_start();
$target_dir = "./img/users/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['user_info'] = $_SESSION['user_info'] . "Plik nie jest zdjęciem. ";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $_SESSION['user_info'] = $_SESSION['user_info']. "Plik nie istnieje. ";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["file"]["size"] > 1000000) {
    $_SESSION['user_info'] = $_SESSION['user_info'] . "Plik jest za duży. ";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $_SESSION['user_info'] = $_SESSION['user_info'] . "Tylko pliki o rozszerzeniu jpg, jpeg, png i gif. ";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    $_SESSION['user_info'] = $_SESSION['user_info'] . "Popraw błędy! ";
} else {
    # name format: USERID-DATA.FORMAT
    $new_name = $_SESSION['id'] . "-" . date('d-m-Y') . "." . $imageFileType;
    echo $new_name;
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $new_name)) {
        $_SESSION['user_info'] = $_SESSION['user_info'] . "Udało się zaktualizować zdjęcie!";

        $user_info = json_decode($_SESSION['info']);
        $user_info->photo = $new_name;

        $_SESSION['info'] = json_encode($user_info);

        require_once('connect.php');
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {die("Connection failed: " . $conn->connect_errno);}
        $sql = "UPDATE users SET info = '" . json_encode($user_info) . "' WHERE id = " . $_SESSION['id'];
        $conn->query($sql);
        
        header("Location: user_profile.php");

    } else {
        $_SESSION['user_info'] = $_SESSION['user_info'] . "Nie udało się zaktualizować zdjęcia.";
    }
}
?>