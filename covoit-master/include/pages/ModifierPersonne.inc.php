<?php
$pdo = new Mypdo();
$personneManager = new PersonneManager($pdo);
$personnes = $personneManager->getAllPersonnes();
?>
<h1>Modifier une personne</h1>
<!--if no person has been selected for modification-->
<?php if (empty($_POST['per_modif'])) { ?>
    <form action="#" method="POST">
        <label>Personne : </label>
        <select name="per_modif">
            <?php foreach ($personnes as $personne) { ?>
                <option value="<?php echo $personne->getPerNum(); ?>"><?php echo $personne->getPerNom() . " " . $personne->getPerPrenom(); ?></option>
            <?php } ?>
        </select>
        <input type="submit" name="modifier" value="Modifier">
    </form>
<?php }

//if a person has been selected for modification
if (isset($_POST['per_modif'])) {
    $per_a_modif = $personneManager->getPersonneFromPerNum($_POST['per_modif']);
    $divisionManager = new DivisionManager($pdo);
    $divisions = $divisionManager->getAllDivisions();
    $departementManager = new DepartementManager($pdo);
    $departements = $departementManager->getAllDepartements();
    $fonctionManager = new FonctionManager($pdo);
    $fonctions = $fonctionManager->getAllFonctions();
    ?>
    <form action="#" method="POST">
        <div class="form-grid">
            <input type="hidden" name="per_num" value="<?php echo $_POST['per_modif']; ?>">
            <div class="row">
                <label>Nom :</label>
                <input type="text" name="per_nom" value="<?php echo $per_a_modif->getPerNom(); ?>" pattern="[A-Za-z]" oninvalid="this.setCustomValidity('Entrez un nom valide')" oninput="this.setCustomValidity('')" required>
            </div>
            <div class="row">
                <label>Prénom :</label>
                <input type="text" name="per_prenom" value="<?php echo $per_a_modif->getPerPrenom(); ?>" pattern="[A-Za-z]" oninvalid="this.setCustomValidity('Entrez un prénom valide')" oninput="this.setCustomValidity('')" required>
            </div>
            <div class="row">
                <label>Téléphone :</label>
                <input type="tel" name="per_tel" value="<?php echo $per_a_modif->getPerTel(); ?>" pattern="[0-9]{10}" oninvalid="this.setCustomValidity('Entrez un numéro de ce type : 0666666666')" oninput="this.setCustomValidity('')" required>
            </div>
            <div class="row">
                <label>Mail :</label>
                <input type="email" name="per_mail" value="<?php echo $per_a_modif->getPerMail(); ?>" required>
            </div>
            <div class="row">
                <label>Login :</label>
                <input type="text" name="per_login" autocomplete="off" value="<?php echo $per_a_modif->getPerLogin(); ?>" required>
            </div>
            <div class="row">
                <label>Mot de Passe :</label>
                <input type="password" name="per_pwd" autocomplete="off" value="**********" required>
            </div>
            <label>Catégorie :</label>
            <?php $statut = $personneManager->isEtudiant($per_a_modif->getPerNum()); ?>
            <input type="hidden" name="old_categorie" value="<?php echo $statut; ?>">
            <input type="radio" name="categorie" value="etudiant" class="modifier" <?php if ($statut == "etudiant") {echo "checked";} ?>>Etudiant
            <input type="radio" name="categorie" value="salarie" class="modifier" <?php if ($statut == "salarie") {echo "checked";} ?>>Personnel<br>
        </div>
        <div class="form-grid">
            <div class="etudiant statut row">
                <?php
                //if the person is a student
                $etudiantManager = new EtudiantManager($pdo);
                $etu = $etudiantManager->getEtudiantFromPerNum($_POST['per_modif']);
                ?>
                <label>Année :
                    <select name="div_num">
                        <?php foreach ($divisions as $div) { ?>
                            <option value="<?php echo $div->getDivNum(); ?>" <?php if ($div->getDivNum() == $etu->getDivNum()) {echo "selected";} ?>> <?php echo $div->getDivNom(); ?></option>
                        <?php } ?>
                    </select></label>
                <br>
                <label>Département :
                    <select name="dep_num">
                        <?php foreach ($departements as $dep) { ?>
                            <option value="<?php echo $dep->getDepNum(); ?>" <?php if ($dep->getDepNum() == $etu->getDepNum()) {echo "selected";} ?>> <?php echo $dep->getDepNom(); ?></option>
                        <?php } ?>
                    </select></label>
            </div>
            <div class="salarie statut row">
                <?php
                //if the person is a employee
                $salarieManager = new SalarieManager($pdo);
                $sal = $salarieManager->getSalarieFromPerNum($_POST['per_modif']);
                ?>
                <label>Téléphone professionnel :<input type="tel" name="sal_telprof" value="<?php echo $sal->getSalTelprof(); ?>" pattern="[0-9]{10}"></label><br>
                <label>Fonction :
                    <select name="fon_num">
                        <?php foreach ($fonctions as $fon) { ?>
                            <option value="<?php echo $fon->getFonNum(); ?>" <?php if ($fon->getFonNum() == $sal->getFonNum()) {echo "selected";} ?>><?php echo $fon->getFonLibelle(); ?></option>
                        <?php } ?>
                    </select></label>
            </div>
        </div>
        <input type="submit" name="valider" value="Valider">
    </form>
<?php }
if (isset($_POST['per_num'])) {
    $personne = new Personne($_POST);
    $retour = $personneManager->modify($personne, $_POST['per_num']);
}
if (isset($_POST['categorie']) && isset($_POST['old_categorie']) && ($_POST['categorie'] == $_POST['old_categorie'])) {
    if ((!empty($_POST['dep_num'])) && (!empty($_POST['div_num']))) {
        $etudiantManager = new EtudiantManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'dep_num' => $_POST['dep_num'], 'div_num' => $_POST['div_num']);
        $etudiant = new Etudiant($arrayPost);
        $retour = $etudiantManager->modify($etudiant);
        header('Location: index.php?page=3', true, 303);
    }
    if ((!empty($_POST['fon_num'])) && (!empty($_POST['sal_telprof']))) {
        $salarieManager = new SalarieManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'sal_telprof' => $_POST['sal_telprof'], 'fon_num' => $_POST['fon_num']);
        $salarie = new Salarie($arrayPost);
        $retour = $salarieManager->modify($salarie);
        header('Location: index.php?page=3', true, 303);
    }
}
?>

<?php
//if person status has changed then we erase every information pertaining to the previous one
if (isset($_POST['categorie']) && isset($_POST['old_categorie']) && ($_POST['categorie'] != $_POST['old_categorie'])) {
    if ($_POST['categorie'] == "salarie") {
        $etudiantManager = new EtudiantManager($pdo);
        $etudiantManager->deleteEtudiantFromNum($_POST['per_num']);

        $salarieManager = new SalarieManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'sal_telprof' => $_POST['sal_telprof'], 'fon_num' => $_POST['fon_num']);
        $salarie = new Salarie($arrayPost);
        $retour = $salarieManager->add($salarie);
    }
    if ($_POST['categorie'] == "etudiant") {
        $salarieManager = new SalarieManager($pdo);
        $salarieManager->deleteSalarieFromNum($_POST['per_num']);

        $etudiantManager = new EtudiantManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'dep_num' => $_POST['dep_num'], 'div_num' => $_POST['div_num']);
        $etudiant = new Etudiant($arrayPost);
        $retour = $etudiantManager->add($etudiant);
    }
}
?>
