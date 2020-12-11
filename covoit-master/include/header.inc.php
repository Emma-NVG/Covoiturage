<?php
session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <?php $title = "Bienvenue sur le site de covoiturage de l'IUT."; ?>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
</head>
<body>
<div id="header">
    <div id="entete">
        <div class="logo">
            <a href="index.php?page=0">
                <img src="image/logo.png" alt="Logo covoiturage IUT" title="Logo covoiturage IUT Limousin"/>
            </a>
        </div>
        <div class="title">
            Covoiturage de l'IUT,<br/>Partagez plus que votre véhicule !!!
        </div>
        <div id="connect">
            <?php if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) { ?>
                <a href="index.php?page=12"><?php echo $_SESSION['login']; ?> <i>(déconnexion)</i></a>
            <?php } else { ?>
                <a href="index.php?page=11">Connexion</a>
            <?php } ?>
        </div>
    </div>
</div>



	

