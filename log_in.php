<?php
session_start();
if(isset($_SESSION['account'])){
    header("Location: profile.php");
}
print_r($_SESSION);
// Appel des variables
if (isset ($_POST["email"]) &&
    isset ($_POST["password"])
) {
//    Vérification des champs
    if (!filter_var($_POST["email"])) {
        $errors[] = "Email invalide";
    }

    if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !\"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u", $_POST["password"])) {
        $errors[] = "Le mot de passe doit comprendre au moins 8 caractères dont 1 lettre minuscule, 1 majuscule, un chiffre et un caractère spécial";
    }

    if (!isset($errors)) {
        require "includes/bdd.php";

        $getAccount = $db->prepare("SELECT * FROM users WHERE email = ?");
        $getAccount->execute([
            htmlspecialchars($_POST['email']),
        ]);

        $account = $getAccount->fetch(PDO::FETCH_ASSOC);


        $getAccount->closeCursor();

        if(empty($account)){
            $error[] = "Le compte associé à cette adresse mail n'existe pas";
        } elseif (!password_verify($_POST["password"], $account["password"])) {
            $error[] = "Mot de passe incorrect !";
        } else {
            $accountPseudo = $db->prepare("SELECT pseudonym FROM users WHERE email = ?");
            $accountPseudo->execute([
                $_POST['email'],
            ]);

            $pseudo = $accountPseudo->fetch(PDO::FETCH_ASSOC);
            $accountPseudo->closeCursor();

            $accountDate = $db->prepare("SELECT register_date FROM users WHERE email = ?");
            $accountDate->execute([
                $_POST['email'],
            ]);

            $date = $accountDate->fetch(PDO::FETCH_ASSOC);
            $accountDate->closeCursor();

            $accountUserId = $db->prepare("SELECT id FROM users WHERE email = ?");
            $accountUserId->execute([
                $_POST['email'],
            ]);

            $user_id = $accountUserId->fetch(PDO::FETCH_ASSOC);
            $accountUserId->closeCursor();

            $_SESSION["account"] = [
                'email' => $_POST['email'],
                'password' =>  $_POST['password'],
                'pseudo' => $pseudo,
                'registration_date' =>  $date,
                'user_id' => $user_id,
            ];

            $successMsg = "Vous êtes connecté !";
        }
        $getAccount->closeCursor();
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
    <?php
    require_once "includes/css_includes.php";
    require_once "includes/navbar.php";
    ?>
    <title>Document</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row mt-5">
            <h1 class="col-6 offset-3">Connexion</h1>
        </div
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
                <button type="submit" class="btn btn-primary col-12">Connexion</button>
                <span class="text-danger">* Champs obligatoires</span>
            </form>
        </div>
    </div> <?php
    }
    ?>

<?php
require_once "includes/js_includes.php";
?>
</body>
</html>