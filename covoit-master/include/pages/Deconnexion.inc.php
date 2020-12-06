<?php
session_start(); //démarrage de la session
session_unset(); //détruit les variables de la session
session_destroy(); //détruit la session

header('location: index.php?page=0'); //redirection
?>
	