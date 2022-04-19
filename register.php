<?php
//On inclue la fonction du captcha
require_once "includes/recaptchaValid.php";

// Appel des variables
if (isset ($_POST["email"]) &&
    isset ($_POST["password"]) &&
    isset ($_POST["passwordValidation"]) &&
    isset ($_POST["pseudo"]) &&
    isset ($_POST["g-recaptcha-response"])
){
//    Vérification des champs
    if(!filter_var($_POST["email"])){
        $errors[] = "Email invalide";
    }

    if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !\"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u", $_POST["password"])){
        $errors[] = "Le mot de passe doit comprendre au moins 8 caractères dont 1 lettre minuscule, 1 majuscule, un chiffre et un caractère spécial";
    }

    if($_POST["passwordValidation"] != $_POST["password"]){
        $errors[] = "La confirmation ne correspond pas au mot de passe.";
    }

    if(!preg_match("/^.{1,50}$/u", $_POST["pseudo"])){
        $errors[] = "Le pseudonyme doit contenir entre 1 et 50 caractères";
    }

    if(!recaptchaValid($_POST["g-recaptcha-response"], $_SERVER["REMOTE_ADDR"])){
        $errors[] = "Veuillez remplir correctement le captcha";
    }

    // Si pas d'erreur, on se connecte à la BDD pour ajouter le compte user
    if(!isset($errors)){
        require "includes/bdd.php";
//       Définition de la date au format FR

        $insertAccount = $db->prepare("INSERT INTO users (email, password, pseudonym, register_date) VALUES(?, ?, ?, ?)");
        $querySuccess = $insertAccount->execute([
            $_POST['email'],
            $_POST['password'],
            $_POST['pseudo'],
            date('Y-m-d H:i:s'),
            ]);
        $insertAccount->closeCursor();

        if($querySuccess){
            $successMsg = "Votre compte a bien été créé !";
        } else {
            $errors[] = "La création du compte a échoué. Merci de réessayer.";
        }
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php
    require_once "includes/css_includes.php";
    require_once "includes/navbar.php";
    require_once "includes/recaptchaValid.php";
    ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row mt-5">
             <h1 class="col-6 offset-3">Créer un compte sur Wikifruits</h1>
        </div>
        <div class="row">
            <form class="col-6 offset-3 my-5" method="POST" action="" >
                <?php
                if(isset($errors)){
                    foreach ($errors as $error){
                        echo "<p class='alert alert-danger'>" . $error . "</p>";
                    }
                }
                if(isset($successMsg)){
                    echo "<p class='alert alert-success'>" . $successMsg . "</p>";
                } else {
                ?>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="text" class="form-control" name="email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe *</label>
                    <input type="text" class="form-control" name="password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmation mot de passe *</label>
                    <input type="text" class="form-control" name="passwordValidation">
                </div>
                <div class="mb-3">
                    <label class="form-label">Pseudonyme *</label>
                    <input type="text" class="form-control" name="pseudo">
                </div>
                <label class="form-label">Captcha *</label>
                <div class="g-recaptcha mb-3" data-sitekey="6Lf1qIQfAAAAABBzL9cIcobGnrHgAKyHk328clRz"></div>
                <button type="submit" class="btn btn-success col-12">Créer mon compte</button>
                <span class="text-danger">* Champs obligatoires</span>
                <?php
                }
                ?>
            </form>
        </div>
    </div>
    <?php
    require_once "includes/js_includes.php";
    ?>
</body>
</html>
