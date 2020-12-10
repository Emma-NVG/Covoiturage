<?php
session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <?php $title = "Bienvenue sur le site de covoiturage de l'IUT."; ?>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="css/stylesheet2.css"/>
</head>
<body>
<header>
    <div id="logo-header">
        <a href="index.php?page=0">
            <img src="image/logo.png" alt="Logo covoiturage IUT" title="Logo covoiturage IUT Limousin"/>
        </a>
    </div>
    <nav>
        <ul id="menu">
            <li class="deroulant">Personne
                <ul class="sous-menu">
                    <li><a href="index.php?page=1">Ajouter</a></li>
                    <li><a href="index.php?page=2">Lister</a></li>
                    <li><a href="index.php?page=3">Modifier</a></li>
                    <li><a href="index.php?page=4">Supprimer</a></li>
                </ul>
            </li>
            <li class="deroulant">Parcours
                <ul class="sous-menu">
                    <li><a href="index.php?page=5">Ajouter</a></li>
                    <li><a href="index.php?page=6">Lister</a></li>
                </ul>
            </li>
            <li class="deroulant">Ville
                <ul class="sous-menu">
                    <li><a href="index.php?page=7">Ajouter</a></li>
                    <li><a href="index.php?page=8">Lister</a></li>
                </ul>
            </li>
            <?php if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) { ?>
            <li class="deroulant">Trajet
                <ul class="sous-menu">
                    <li><a href="index.php?page=9">Proposer</a></li>
                    <li><a href="index.php?page=10">Rechercher</a></li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </nav>
    <div id="connection">
        <?php if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) { ?>
            <a href="index.php?page=12"><button><?php echo $_SESSION['login']; ?> (Déconnexion)</button></a>
        <?php } else { ?>
            <a href="index.php?page=11"><button>Connexion</button></a>
        <?php } ?>
    </div>
</header>
	

