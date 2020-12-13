<?php
$pdo = new Mypdo();
$etudiantManager = new EtudiantManager($pdo);
$salarieManager = new SalarieManager($pdo);

$personneManager = new PersonneManager($pdo);
$lastPersonneId = $pdo->lastInsertId();
$divisionManager = new DivisionManager($pdo);
$divisions = $divisionManager->getAllDivisions();
$departementManager = new DepartementManager($pdo);
$departements = $departementManager->getAllDepartements();
$fonctionManager = new FonctionManager($pdo);
$fonctions = $fonctionManager->getAllFonctions();
?>
<?php
if (empty($_POST['categorie'])) {
    ?>
    <h1>Ajouter une personne</h1>
    <form class="form" action="#" method="POST">
        <div class="form-grid">
            <div class="row">
                <label>Nom :</label>
                <input type="text" name="per_nom" pattern="[A-Za-z]" oninvalid="this.setCustomValidity('Entrez un nom valide')" oninput="this.setCustomValidity('')" required><br>
            </div>
            <div class="row">
                <label>Prénom :</label>
                <input type="text" name="per_prenom" pattern="[A-Za-z]" oninvalid="this.setCustomValidity('Entrez un prénom valide')" oninput="this.setCustomValidity('')" required><br>
            </div>
            <div class="row">
                <label>Téléphone :</label>
                <input type="tel" name="per_tel" pattern="[0-9]{10}" oninvalid="this.setCustomValidity('Entrez un numéro de ce type : 0666666666')" oninput="this.setCustomValidity('')" required><br></div>
            <div class="row">
                <label>Mail :</label>
                <input type="email" name="per_mail" required><br>
            </div>
            <div class="row">
                <label>Login :</label>
                <input type="text" name="per_login" autocomplete="off" required><br>
            </div>
            <div class="row">
                <label>Mot de Passe :</label>
                <input type="password" name="per_pwd" autocomplete="off" required><br>
            </div>
            <label>Catégorie :</label>
            <input type="radio" name="categorie" value="etudiant" checked>Etudiant
            <input type="radio" name="categorie" value="personnel">Personnel<br>
        </div>
        <input type="submit" name="valider" value="Valider">
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
            <label>Téléphone professionnel :</label>
            <input type="tel" name="sal_telprof" pattern="[0-9]{10}" oninvalid="this.setCustomValidity('Entrez un numéro de ce type : 0666666666')" oninput="this.setCustomValidity('')" required><br>
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
if (isset($_POST['dep_num']) && isset($_POST['div_num'])) {
    $arrayPost = array('per_num' => $lastPersonneId, 'dep_num' => $_POST['dep_num'], 'div_num' => $_POST['div_num']);
    $etudiant = new Etudiant($arrayPost);
    $retour = $etudiantManager->add($etudiant);
    header('Location: index.php?page=1', true, 303);
}
if (isset($_POST['fon_num']) && isset($_POST['sal_telprof'])) {
    $arrayPost = array('per_num' => $lastPersonneId, 'sal_telprof' => $_POST['sal_telprof'], 'fon_num' => $_POST['fon_num']);
    $salarie = new Salarie($arrayPost);
    $retour = $salarieManager->add($salarie);
    header('Location: index.php?page=1', true, 303);
}
?>