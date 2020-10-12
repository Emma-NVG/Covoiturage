<?php
    $pdo = new Mypdo();
    $villeManager = new VilleManager($pdo);
    $villes=$villeManager->getAllVilles();
    $number_Villes=$villeManager->numberVilles()
?>

<h1>Lister des villes</h1>

<p>Actuellement <?php echo $number_Villes ?> villes sont enregistrées</p>
<table>
    <tr><th>Numéro</th><th>Nom</th></tr>
    <?php
    foreach ($villes as $ville){ ?>
        <tr><td><?php echo $ville->getVilNum();?>
        </td><td><?php echo $ville->getVilNom();?>
        </td></tr>
    <?php } ?>
</table>
<br />
