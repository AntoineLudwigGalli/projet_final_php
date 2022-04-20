<?php
session_start();
include "includes/bdd.php";
$fruitList = $db->query("SELECT users.pseudonym FROM users INNER JOIN fruits ON users.id = fruits.user_id WHERE users.id = fruits.user_id");
$userFruits = $fruitList->fetchAll(PDO::FETCH_ASSOC);
$fruitList->closeCursor();

$tableFruits = $db->query("SELECT * FROM fruits");
$fruits = $tableFruits->fetchAll(PDO::FETCH_ASSOC);
$tableFruits->closeCursor();
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
    <title>Liste de fruits</title>
</head>
<body>

    <div class="container-fluid">

        <div class="row">

            <div class="col-12 col-md-8 offset-md-2 py-5">
                <h1 class="pb-4 text-center">Liste des Fruits</h1>
            <?php
            foreach ($fruits as $fruit){?>
            <div class="d-flex justify-content-center flex-wrap">
                <div class="card fruit-card">
                    <img src="images/<?php
                    if($fruit['picture_name'] != NULL) {
                        echo "uploads/".$fruit['picture_name'];
                    } else {
                        echo "no-photo.png";
                    }
                    ?>" class="card-img-top fruit-card-picture" alt="">

                    <div class="card-body">
                        <h2 class="card-title h5 text-center"><?php echo $fruit['name']
                            ?></h2>
                        <p class="text-info text-center my-0">Pays d'origine : <?php
                            if($fruit['origin'] == "fr"){
                                echo "France";
                            } elseif ($fruit['origin'] == "de"){
                                echo "Allemagne";
                            } elseif ($fruit["origin"] == "es"){
                                echo "Espagne";
                            } elseif ($fruit['origin'] == "jp"){
                                echo "Japon";
                            }?>
                        </p>
                        <p class="text-primary text-center">Posté par : <?php
//
                            echo $userFruits[$fruit["user_id"]]["pseudonym"];

                            ?></p>
                        <hr>
                                <?php
                                    if($fruit['description'] != NULL) {
                                        echo "<p class=\"card-text text-center\">" . $fruit['description'] ."</p>";
                                    } else {
                                        echo "<p class=\"card-text text-center text-danger\">Aucune description</p>";
                                    }
                                    ?>
                            </div>
                        </div>
                    </div><?php
            }
            ?>
            </div>
        </div>
    </div>

<?php
require_once "includes/js_includes.php";
?>
</body>
</html>