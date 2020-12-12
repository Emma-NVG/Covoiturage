<?php
$pdo = new Mypdo();
$parcoursManager = new ParcoursManager($pdo);
$listeParcours = $parcoursManager->getAllParcours();
$numberParcours = $parcoursManager->numberParcours();
?>

<h1>Liste des parcours</h1>

<p>Actuellement <?php echo $numberParcours ?> parcours sont enregistrés</p>
<table>
    <tr>
        <th>Numéro</th>
        <th>Nom ville</th>
        <th>Nom ville</th>
        <th>Nombre de km</th>
    </tr>
    <?php
    foreach ($listeParcours as $par) { ?>
        <tr>
            <td><?php echo $par->getParNum(); ?></td>
            <td><?php echo $parcoursManager->getVilNomFromVilNum($par->getVilNum1()); ?></td>
            <td><?php echo $parcoursManager->getVilNomFromVilNum($par->getVilNum2()); ?></td>
            <td><?php echo $par->getParKm(); ?></td>
        </tr>
    <?php } ?>
</table>
<br/>
