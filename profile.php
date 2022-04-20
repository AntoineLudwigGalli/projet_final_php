<?php
session_start();
print_r($_SESSION["account"]);
require "includes/bdd.php";
print_r($_SESSION["account"]["pseudo"]);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
    require_once "includes/css_includes.php";
    require_once "includes/navbar.php"
    ?>
    <title>Mon profil</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row m-4">
            <h1 class="col-4 offset-4">Mon Profil</h1>
        </div>
        <div class="col-6 offset-3 my-4">
            <ul>
                <li>Email : <?php echo $_SESSION["account"]["email"]?></li>
                <li>Pseudo : <?php echo $_SESSION["account"]["pseudo"]["pseudonym"]?></li>
                <li>Date d'inscription : <?php echo date('d/m/Y H:i:s', strtotime($_SESSION["account"]["registration_date"]["register_date"]))?></li>
            </ul>
        </div>
    <?php


    ?>
    <?php
    require_once "includes/js_includes.php";
    ?>
</body>
</html>
