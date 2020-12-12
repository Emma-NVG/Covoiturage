<?php if (isset($_SESSION['login']) && isset($_SESSION['pwd'])) {
    $pdo = new Mypdo();
    $parcoursManager = new ParcoursManager($pdo);
    $vdep = $parcoursManager->getVillesDepart();
    $proposeManager = new ProposeManager($pdo);
    $personneManager = new PersonneManager($pdo);
    ?>
    <h1>Proposer un trajet</h1>
    <?php if (empty($_POST['ville_depart']) && empty($_POST['prop_trajet'])) { //si la ville de départ n'a pas été choisie?>

        <form action="#" method="post">
            <label>Ville de départ : </label>
            <select name="ville_depart" required>
                <?php foreach ($vdep as $vi) { ?>
                    <option value="<?php echo $vi->getVilNum(); ?>"><?php echo $vi->getVilNom(); ?></option>
                <?php } ?>
            </select>
            <input type="submit" value="Valider">
        </form>

    <?php }
    if (empty($_POST['prop_trajet']) && !empty($_POST['ville_depart'])) { //si la ville de départ a pas été choisie mais pas le trajet
        $varr = $parcoursManager->getVillesArrivee($_POST['ville_depart']);
        ?>
        <form action="#" method="post">
            <p>Ville de départ : <?php echo $parcoursManager->getVilNomFromVilNum($_POST['ville_depart']) ?></p>
            <input type="hidden" name="vilDep" value="<?php echo $_POST['ville_depart'] ?>">
            <label>Ville de arrivée : </label>
            <select name="prop_trajet" required>
                <?php foreach ($varr as $vi) { ?>
                    <option value="<?php echo $vi->getVilNum(); ?>"><?php echo $vi->getVilNom(); ?></option>
                <?php } ?>
            </select><br>
            <label>Date de départ : </label><input type="date" name="date" value="<?php echo date("yy-m-d"); ?>"
                    required>
            <label>Heure de départ : </label><input type="time" name="time" value="<?php echo date("H:i:s"); ?>"
                    required><br>
            <label>Nombre de places : </label><input type="number" name="nbrPlaces" min="1" max="200" value="1"
                    required><br>
            <input type="submit" value="Valider">
        </form>
    <?php }
    if (!empty($_POST['prop_trajet'])) { //si le trajet à été choisi
        $per_num = $personneManager->getNumFromLogin($_SESSION['login']);
        $tab = $parcoursManager->getNumParcourAndSens($_POST['vilDep'], $_POST['prop_trajet']);

        $arrayPost = array('par_num' => $tab['par_num'], 'per_num' => $per_num, 'pro_date' => $_POST['date'], 'pro_time' => $_POST['time'], 'pro_place' => $_POST['nbrPlaces'], 'pro_sens' => $tab['sens']);
        $propose = new Propose($arrayPost);
        $retour = $proposeManager->add($propose);
        header('Location: index.php?page=9', true, 303);
    }
} else {
    header('Location: index.php?page=0', true, 303);
} ?>
