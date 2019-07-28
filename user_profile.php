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
    <title>Zagrajmy - witaj <?php echo $_SESSION['name'];?></title>
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

        #user-panel {
            margin-left: 3vh;
        }

        .p1::after {
            width: 40%;
        }

        .p2::after {
            width: 60%;
        }

        .p3::after {
            width: 80%;
        }

        .p4::after {
            width: 100%;
        }

        .user_photo {
            width: 10vmax;   
        }
        
        .photo_booth {
            text-align: center;
        }

    </style>
</head>
<body>

<!-- LOAD NAVIGATION BAR -->
<?php require_once('./parts/load_nav.php'); ?>

<div id='main' class='container-fluid'>
    <div class='row'>
        <div id='user-panel' class='col-sm-6'>
            <?php 
            if(isset($_SESSION['user_info'])) {
                echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>" . $_SESSION['user_info'] . "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['user_info']);  
            }
            echo "<h1>Witaj " . $_SESSION['name'] . "!</h1>"; 
            ?>

            <hr/>

             <div class='row'>

                 <div class='col-sm-7 photo_booth'>
                    <h3 style='text-align: left;'>Zdjęcie profilowe:</h3>
                    <p>Twoje obecne zdjęcie profilowe.</p>
                    <img class='user_photo shadow' <?php echo "src='./img/users/".json_decode($_SESSION['info'])->photo."'"; ?> /></br></br>
                    <form action="upload_new_photo.php" method="post" enctype="multipart/form-data">
                        Wybierz zdjęcie do dodania:</br>
                        <input type="file" name="file"></br></br>
                        <input class='btn btn-dark' type="submit" value="Zmień zdjęcie!" name="submit">
                    </form>
                </div>

                <div class='col-sm-5'>
                    <p>Muchachos chupitos.</p>
                </div>

            </div>
        </div>
        <div class="col-sm-5">
            USTAWIENIA
        </div>
    </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
<script src="./scripts/script.js"></script>
<script src="./scripts/loadMap.js"></script>
</body>
</html>