<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Wikifruits</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="fruit_list.php">Liste des fruits</a>
                </li>

                <?php
                if(!isset($_SESSION['account'])){
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Inscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="log_in.php">Connexion</a>
                </li><?php
                } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="log_out.php">Déconnexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Mon profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_fruit.php">Ajouter un fruit</a>
                </li>
                    <?php
                }?>
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Chercher un fruit">
                <button class="btn btn-outline-success" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </div>
</nav>