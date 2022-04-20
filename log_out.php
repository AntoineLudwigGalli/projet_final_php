<?php
session_start();
print_r($_SESSION['account']);
if(isset($_SESSION['account'])){
    unset($_SESSION['account']);
    $successMsg = "Vous avez bien été déconnecté !";
}
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
    require_once "includes/navbar.php";
    ?>
    <title>Déconnexion</title>
</head>
<body>
    <?php
        if(isset($successMsg)){
        echo "<p class='alert alert-success'>" . $successMsg . "</p>";
    }
    else {
        echo "<p class='alert alert-danger'>Veuillez vous connecter en cliquant <a href='log_in.php'>ici</a> !</p>";
    }
    ?>

    <?php
    require_once "includes/js_includes.php"
    ?>
</body>
</html>
