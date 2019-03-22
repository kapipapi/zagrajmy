<?php
if(isset($_SESSION['info'])){
    $info = json_decode($_SESSION['info']);
    $friends = $info->friends;
    if(sizeof($friends)>0){
        echo "<h3>Twoi znajomi:</h3>";
        foreach($friends as $f) {
            echo "<div class='friend'>";
            echo "<p>" . $f->name . " " . $f->surname ."</p>";
            echo "</div>";
        }
    }
}
?>