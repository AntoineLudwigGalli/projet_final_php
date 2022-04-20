<?php
session_start();
$maxPictureSize = 5242880;
$allowedMimeTypes = [
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
];
if(
    (isset($_POST["name"])) &&
    (isset($_POST["country"]))
) {
    if (!preg_match("/^.{1,50}$/u", $_POST["name"])) {
        $errors[] = "Le nom doit contenir entre 1 et 50 caractères.";
    }
    if ($_POST["country"] != "fr" && $_POST["country"] != "es" && $_POST["country"] != "de" && $_POST["country"] != "jp") {
        $errors[] = "Le pays d'origine doit être un de ceux listés";
    }

    if (isset($_POST["description"])) {
        if (!preg_match("/^.{5,20000}$/u", $_POST["description"])) {
            $errors[] = "La description doit compter entre 5 et 20000 caractères.";
        } elseif(empty($_POST["description"])) {
            $_POST["description"] = NULL;
        }
    }

    if (!empty($_FILES["picture"])) {
        $fileErrorCode = $_FILES['picture']['error'];

        // 1er niveau de vérification du fichier : son code d'erreur et sa taille
        if ($fileErrorCode == 1 || $fileErrorCode == 2 || $_FILES['picture']['size'] > $maxPictureSize) {
            $errors[] = 'Le fichier est trop volumineux.';

        } elseif ($fileErrorCode == 3) {
            $errors[] = 'Le fichier n\'a pas été chargé correctement, veuillez ré-essayer.';

        } elseif ($fileErrorCode == 4) {
            $_FILES["picture"] = NULL;

        } elseif ($fileErrorCode == 6 || $fileErrorCode == 7 || $fileErrorCode == 8) {
            $errors[] = 'Problème serveur, veuillez ré-essayer plus tard.';

        } elseif ($fileErrorCode == 0) {

            // 2eme niveau de vérification du fichier : son type MIME
            // On a besoin de faire cette vérification dans un 2eme temps sinon on risque d'essayer de tester le type MIME d'un fichier qui n'existe pas, ce qui ferait une erreur PHP

            // Récupération du vrai type MIME du fichier
            $fileMIMEType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['picture']['tmp_name']);

            // Si le type MIME du fichier n'est pas dans l'array des types MIME autorisé, on crée une erreur
            if (!in_array($fileMIMEType, $allowedMimeTypes)) {
                $errors[] = 'Seuls les fichiers jpg, png et gif sont autorisés !';
            }

        } else {

            // Si on rentre ici, c'est qu'il y a un autre code d'erreur inconnu (peut-être PHP en ajoutera un jour ?)
            // On fait donc une erreur pour mettre le formulaire en échec quand même
            $errors[] = 'Problème inconnu';
        }
        if (!isset($errors) && $_FILES["picture"] != NULL) {

            $ext = array_search($fileMIMEType, $allowedMimeTypes);

            // On génère un hash md5 d'une chaînes aléatoire d'une taille de 50 pour le nom du nom de fichier, et ce dans une boucle jusqu'à ce qu'on ait un nom de fichier pas déjà pris par un autre
            do {
                $newFileName = md5(random_bytes(50)) . '.' . $ext;
            } while (file_exists('images/uploads/' . $newFileName));

            // Sauvegarde du fichier avec son nouveau nom dans le dossier "images/"
            move_uploaded_file($_FILES['picture']['tmp_name'], 'images/uploads/' . $newFileName);

        }
    }
    if(!isset($errors)){
        include_once "includes/bdd.php";
        $addFruit = $db->prepare("INSERT INTO fruits(name, origin, description, picture_name, user_id) VALUES (?, ?, ?, ?, ?)");
        $newFruit = $addFruit->execute([
                $_POST["name"],
                $_POST["country"],
                $_POST["description"],
                $_FILES["picture"]["name"],
                $_SESSION["account"]["user_id"]["id"],
        ]);
        $addFruit->closeCursor();
        $successMsg = 'Votre fruit a bien été créé !';

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
    require_once "includes/navbar.php"
    ?>
    <title>Ajouter un fruit</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row mt-5">
            <h1 class="col-6 offset-4">Ajouter un fruit</h1>
        </div>
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
        <div class="row">
            <form class="col-6 offset-3 my-5" method="POST" action=""  enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nom *</label>
                    <input type="text" class="form-control" name="name" placeholder="Banane">
                </div>
                <label class="form-label">Pays d'origine *</label>
                <select class="form-select" name="country">
                    <option selected>Sélectionner un pays</option>
                    <option value="fr">France</option>
                    <option value="de">Allemagne</option>
                    <option value="es">Espagne</option>
                    <option value="jp">Japon</option>
                </select>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Photo</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?= $maxPictureSize ?>">
                    <input class="form-control" type="file" accept="<?= implode(', ', $allowedMimeTypes) ?>" name="picture">
                </div>
                <label class="form-label">Description</label>
                <div class="input-group">
                    <textarea class="form-control" type="text" placeholder="Description..." rows="10" name="description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary col-12">Créer le fruit</button>
                <span class="text-danger">* Champs obligatoires</span>
            </form>
        </div>
        <?php
        }?>
    </div>
    <?php
    require_once "includes/js_includes.php";
    ?>
</body>
</html>
