<?php

session_start();

require_once('connect.php');

if (isset($_SESSION['email']) || isset($_SESSION['pass'])) {
    header("Location: user_panel.php");
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>ZagrajMY</title>
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
    nav {
        margin-bottom: 0vh;
    }
    </style>
</head>
<body>

    <!-- LOAD NAVIGATION BAR -->
    <?php require_once('./parts/load_nav.php'); ?>

    <div id='main' class='container-fluid'>
        <div class='row'>

            <map-box class='col-sm-7'>
                <h2>Szukaj gier w okolicy!</h2>
                <div id="mapid"></div>
            </map-box>

            <login-panel class='col-sm-5'>
                <div id="login-panel-menu" class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                    <label class="btn btn-primary active" id="login_radio">
                        <input type="radio" name="login" checked> Zaloguj się
                    </label>
                    <label class="btn btn-success" id="signin_radio">
                        <input type="radio" name="signin"> Zarejestruj się
                    </label>
                </div>
                <div id="form">
                    <div id="login_form">
                        <form action="login.php" method="post">
                            <h3>Logowanie:</h3>
                            <div class="form-group">
                                <label for="userEmail">E-mail:</label>
                                <input type="email" name="email" placeholder="twój email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="userPassword">Hasło:</label>
                                <input type="password" name="pass" placeholder="twoje hasło" class="form-control">
                            </div>
                            <?php
                                if(!empty($_SESSION['login-err'])){
                                    echo "<small class='err'>".$_SESSION['login-err']."</small>";
                                    unset($_SESSION['login-err']);
                                }
                            ?>
                            <div class="form-group">
                                <input class="btn btn-light" type="submit" value="Zaloguj się">
                            </div>
                        </form>
                    </div>
                    <div class="collapse" id="signin_form">
                        <form action="signin.php" method="post">
                            <h3>Rejestracja:</h3>
                            <div class="form-group">
                                <label for="userEmail">Imie</label>
                                <input type="text" name="name" placeholder="Imię" class="form-control">
                                <label for="userEmail">Nazwisko:</label>
                                <input type="text" name="surname" placeholder="Nazwisko" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="userEmail">E-mail:</label>
                                <input type="email" name="email" placeholder="twój email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="userPassword">Hasło:</label>
                                <input type="password" name="pass" placeholder="twoje hasło" class="form-control">
                                <input type="password" name="pass2" placeholder="powtórz hasło" class="form-control">
                            </div>
                            <hr/>
                            <div class="form-group">
                                <label for="userSports">Wybierz swoje sporty:</label>
                                <?php
                                    $index = 0;
                                    $sports = json_decode(file_get_contents('./scripts/json/sporty.json'));
                                    foreach($sports as $s) {
                                        echo "<div class='row'>";
                                        echo "<div class='col-sm'>";
                                        echo "<input class='form-check-input' type='checkbox' value='$s' name='$s'>";
                                        echo "<label class='form-check-label'>$s</label>";
                                        echo "</div>";
                                        echo "<div class='col-sm'>";
                                        echo "<select name='poziom$s' onclick='checksport(\"$s\");'>
                                                <option value='1'>Świeżak</option>
                                                <option value='2'>Pomocnik do gry</option>
                                                <option value='3'>Mocny zawodnik</option>
                                                <option value='4'>Mistrz</option>
                                            </select>";
                                        echo "</div>";
                                        echo "</div>";
                                    };
                                ?>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <label for="userPassword">Data urodzenia:</label>
                                <input type='date' name='dateofbirth'>
                            </div>
                            <div class="form-group">
                                <?php
                                if(isset($_SESSION['signin_err'])){
                                    foreach($_SESSION['signin_err'] as $err) {
                                        if(!empty($err)){
                                            echo "<small class='err'>$err</small><br/>";
                                        }
                                    }
                                    unset($_SESSION['signin_err']);
                                }
                                ?>
                                <input class="btn btn-light" type="submit" value="Zarejestruj się">
                            </div>
                        </form>
                    </div>
                </div>
            </login-panel>
        </div>
        <div class='row'>
            <footer class='col-sm'>
                <small>Kacper Ledwosiński <b>styczeń 2019</b></small>
            </footer>
        </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="./scripts/script.js"></script>
    <script src="./scripts/loadMap.js"></script>
    <script src="./scripts/mainPage.js"></script>
</body>

</html> 