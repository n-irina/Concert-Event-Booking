<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>DonkeyEvent</title>
</head>
<body>
<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=DonkeyEvent', 'root');
?>
<div class="content">
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="donkeyEvent.php">DonkeyEvent</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="agenda.php">Agenda</a>
                        </li>
                    </ul>
                    <div class="navdroite">
                        <form action="search.php" method="GET" class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Concert, catégorie, artiste" aria-label="search" name="search" style="width: 280px;">
                            <button class="btn btn-outline-secondary" type="submit">Rechercher</button>
                        </form>
                    </div>
                    <div class="verticalline"></div>
                    <div class="navdroite">
                        <?php
                        if (isset($_SESSION["user"])) {
                            echo '<form action="deconnexion.php" class="d-flex">
                    <button class="btn btn-outline-secondary" type="submit">Déconnexion</button>
                </form>';
                        }
                        if (!isset($_SESSION["user"])) {
                            echo '<form action="connexion.php" class="d-flex">
                    <button class="btn btn-outline-secondary" type="submit">Connexion</button>
                </form>';
                        } ?>
                    </div>
                    <div class="usericon">
                        &nbsp&nbsp<i class="fa-solid fa-user"></i>&nbsp&nbsp&nbsp
                    </div>
                </div>
            </div>
        </nav>
    </header>
</div>
<?php

if (isset($_SESSION["user"])) {
    echo $_SESSION["user"] . " vous êtes connecté(e)";
}
?>

<!--
<footer>
    <div class="container py-4">
        <div class="row mt-3">
            <div class="col-md-12 text-center">
                <p>&copy; 2024 DonkeyEvent.com</p>
            </div>
        </div>
    </div>
</footer>

</body>
</html>

-->