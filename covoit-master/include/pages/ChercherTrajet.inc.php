<?php if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
    $pdo = new Mypdo();
    $parcoursManager = new ParcoursManager($pdo);
    $proposeManager = new ProposeManager($pdo);
    $personneManager = new PersonneManager($pdo);
    ?>
    <h1>Rechercher un trajet</h1>
    <?php if (empty($_POST['ville_depart']) && empty($_POST['rech_traj'])) { //si la ville de départ n'a pas été choisie?>

        <form action="#" method="post">
            <label>Ville de départ : </label>
            <select name="ville_depart" required>
                <?php $vdep = $parcoursManager->getVillesDepart();
                foreach ($vdep as $vi) {
                    ?>
                    <option value="<?php echo $vi->getVilNum(); ?>"><?php echo $vi->getVilNom(); ?></option>
                <?php } ?>
            </select>
            <input type="submit" value="Valider">
        </form>

    <?php }
    if (empty($_POST['rech_traj']) && !empty($_POST['ville_depart'])) { //si la ville de départ a pas été choisie mais pas le trajet recherché
        $varr = $parcoursManager->getVillesArrivee($_POST['ville_depart']);
        ?>
        <form action="#" method="post">
            <p>Ville de départ : <?php echo $parcoursManager->getVilNomFromVilNum($_POST['ville_depart']); ?></p>
            <input type="hidden" name="vilDep" value="<?php echo $_POST['ville_depart']; ?>">
            <label>Ville de arrivée : </label>
            <select name="rech_traj" required>
                <?php foreach ($varr as $vi) { ?>
                    <option value="<?php echo $vi->getVilNum(); ?>"><?php echo $vi->getVilNom(); ?></option>
                <?php } ?>
            </select><br>
            <label>Date de départ : </label><input type="date" name="date" value="<?php echo date("yy-m-d"); ?>"
                                                   required>
            <label>Précision : </label>
            <select name="precision" required>
                <option value="0">Ce jour</option>
                <option value="1">+/-1 jour</option>
                <option value="2">+/-2 jour</option>
                <option value="3">+/-3 jour</option>
            </select>
            <label>A partir de : </label><input type="time" name="time" value="<?php echo date("H:i:s"); ?>"
                                                required><br>
            <input type="submit" value="Valider">
        </form>
    <?php }
    if (!empty($_POST['rech_traj'])) { //si le trajet à été choisi on affiche la recherche
        $resultat = $proposeManager->getTrajetProposeWithCriteria($_POST['vilDep'], $_POST['rech_traj'], $_POST['date'], $_POST['time'], $_POST['precision']);
        if ($resultat != null) { ?>
            <table>
                <tr>
                    <th>Ville départ</th>
                    <th>Ville arrivée</th>
                    <th>Date départ</th>
                    <th>Heure départ</th>
                    <th>Nombre de place(s)</th>
                    <th>Nom du covoitureur</th>
                </tr>
                <?php foreach ($resultat as $item) { ?>
                    <tr>
                        <td><?php echo $parcoursManager->getVilNomFromVilNum($_POST['vilDep']) ?></td>
                        <td><?php echo $parcoursManager->getVilNomFromVilNum($_POST['rech_traj']); ?></td>
                        <td><?php echo $item->getProDate() ?></td>
                        <td><?php echo $item->getProTime() ?></td>
                        <td><?php echo $item->getProPlace() ?></td>
                        <?php $avis = $proposeManager->recupererAvis($item->getPerNum()); ?>
                        <td><a href=""
                               title="Moyenne des avis : <?php echo $avis['moy_avis'][0]; ?>  Dernier avis : <?php echo $avis['last_avis'][0]; ?> "><?php echo $personneManager->getPersonneFromPerNum($item->getPerNum())->getPerNom() ?></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { //le trajet selon les critères demandés n'existe pas dans la base
            ?>
            <p>Désolé pas de trajet disponible ! </p>
        <?php }
    }
} else {
    header('Location: index.php?page=0', true, 303);
} ?>


