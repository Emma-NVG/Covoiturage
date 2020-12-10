<?php
$pdo = new Mypdo();
$etudiantManager = new EtudiantManager($pdo);
$salarieManager = new SalarieManager($pdo);

$personneManager = new PersonneManager($pdo);
$lastPersonneId = $personneManager->lastPersonneAdd();
$divisionManager = new DivisionManager($pdo);
$divisions = $divisionManager->getAllDivisions();
$departementManager = new DepartementManager($pdo);
$departements = $departementManager->getAllDepartements();
$fonctionManager = new FonctionManager($pdo);
$fonctions = $fonctionManager->getAllFonctions();
?>
    <h1>Ajouter une personne</h1>
<?php
if (empty($_POST['categorie'])) {
    ?>
    <form action="#" method="POST">
        <label>Nom :</label><input type="text" name="per_nom" required><br>
        <label>Prénom :</label><input type="text" name="per_prenom" required><br>
        <label>Téléphone :</label><input type="tel" name="per_tel" required><br>
        <label>Mail :</label><input type="email" name="per_mail" required><br>
        <label>Login :</label><input type="text" name="per_login" autocomplete="off" required><br>
        <label>Mot de Passe :</label><input type="password" name="per_pwd" autocomplete="off" required><br>
        <label>Catégorie :</label><br>
        <input type="radio" name="categorie" id="etudiant" value="etudiant" checked><label for="etudiant" class="radiobtn">Etudiant</label>
        <input type="radio" name="categorie" id="personnel" value="personnel"><label for="personnel" class="radiobtn">Personnel</label><br>
        <input type="submit" class="btn" name="valider" value="Valider">
    </form>
    <?php
} else {
    $personne = new Personne($_POST);
    $retour = $personneManager->add($personne);

    if ($_POST['categorie'] == ("etudiant") && (empty($_POST['div_num']) || empty($_POST['dep_num']))) {
        ?>
        <h1>Ajouter un etudiant</h1>
        <form action="#" method="POST">
            <label>Année :</label>
            <select name="div_num" required>
                <?php foreach ($divisions as $div) { ?>
                    <option value="<?php echo $div->getDivNum(); ?>"><?php echo $div->getDivNom(); ?></option>
                <?php } ?>
            </select>

            <label>Département :</label>
            <select name="dep_num" required>
                <?php foreach ($departements as $dep) { ?>
                    <option value="<?php echo $dep->getDepNum(); ?>"><?php echo $dep->getDepNom(); ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="valider" value="Valider">
        </form>
    <?php }
    if ($_POST['categorie'] == ("personnel") && (empty($_POST['fon_num']) || empty($_POST['sal_telprof']))) {
        ?>
        <h1>Ajouter un salarié</h1>
        <form action="#" method="POST">
            <label>Téléphone professionnel :</label><input type="tel" name="sal_telprof" required><br>
            <label>Fonction :</label>
            <select name="fon_num" required>
                <?php foreach ($fonctions as $fon) { ?>
                    <option value="<?php echo $fon->getFonNum(); ?>"><?php echo $fon->getFonLibelle(); ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="valider" value="Valider">
        </form>
    <?php }
}
if ((!empty($_POST['dep_num'])) && (!empty($_POST['div_num']))) {
    $arrayPost = array('per_num' => $lastPersonneId, 'dep_num' => $_POST['dep_num'], 'div_num' => $_POST['div_num']);
    $etudiant = new Etudiant($arrayPost);
    $retour = $etudiantManager->add($etudiant);
    header('Location: index.php?page=1', true, 303);
}
if ((!empty($_POST['fon_num'])) && (!empty($_POST['sal_telprof']))) {
    $arrayPost = array('per_num' => $lastPersonneId, 'sal_telprof' => $_POST['sal_telprof'], 'fon_num' => $_POST['fon_num']);
    $salarie = new Salarie($arrayPost);
    $retour = $salarieManager->add($salarie);
    header('Location: index.php?page=1', true, 303);
}
?>