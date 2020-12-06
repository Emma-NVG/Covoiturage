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
        <input type="hidden" name="per_num" value="<?php echo $_POST['per_modif']; ?>">
        <label>Nom :</label><input type="text" name="per_nom" value="<?php echo $per_a_modif->getPerNom(); ?>" required><br>
        <label>Prénom :</label><input type="text" name="per_prenom" value="<?php echo $per_a_modif->getPerPrenom(); ?>"
                                      required><br>
        <label>Téléphone :</label><input type="tel" name="per_tel" value="<?php echo $per_a_modif->getPerTel(); ?>"
                                         required><br>
        <label>Mail :</label><input type="email" name="per_mail" value="<?php echo $per_a_modif->getPerMail(); ?>"
                                    required><br>
        <label>Login :</label><input type="text" name="per_login" autocomplete="off"
                                     value="<?php echo $per_a_modif->getPerLogin(); ?>" required><br>
        <label>Mot de Passe :</label><input type="password" name="per_pwd" autocomplete="off" value="**********"
                                            required><br>
        <label>Catégorie :</label>
        <?php $statut = $personneManager->isEtudiant($per_a_modif->getPerNum()); ?>
        <input type="hidden" name="old_categorie" value="<?php echo $statut; ?>">
        <input type="radio" name="categorie" value="etudiant" <?php if ($statut == "etudiant") {
            echo "checked";
        } ?>>Etudiant
        <input type="radio" name="categorie" value="personnel" <?php if ($statut == "salarie") {
            echo "checked";
        } ?>>Personnel<br>
        <style type="text/css">
            .statut {
                display: none;
            }
        </style>

        <div class="etudiant statut">
            <?php
            //if the person is a student
            $etudiantManager = new EtudiantManager($pdo);
            $etu = $etudiantManager->getEtudiantFromPerNum($_POST['per_modif']);
            ?>
            <label>Année :</label>
            <select name="div_num">
                <?php foreach ($divisions as $div) { ?>
                    <option value="<?php echo $div->getDivNum(); ?>" <?php if ($div->getDivNum() == $etu->getDivNum()) {
                        echo "selected";
                    } ?>> <?php echo $div->getDivNom(); ?></option>
                <?php } ?>
            </select>
            <label>Département :</label>
            <select name="dep_num">
                <?php foreach ($departements as $dep) { ?>
                    <option value="<?php echo $dep->getDepNum(); ?>" <?php if ($dep->getDepNum() == $etu->getDepNum()) {
                        echo "selected";
                    } ?>> <?php echo $dep->getDepNom(); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="personnel statut">
            <?php
            //if the person is a employee
            $salarieManager = new SalarieManager($pdo);
            $sal = $salarieManager->getSalarieFromPerNum($_POST['per_modif']);
            ?>
            <label>Téléphone professionnel :</label><input type="tel" name="sal_telprof"
                                                           value="<?php echo $sal->getFonNum(); ?>"><br>
            <label>Fonction :</label>
            <select name="fon_num">
                <?php foreach ($fonctions as $fon) { ?>
                    <option value="<?php echo $fon->getFonNum(); ?>" <?php if ($fon->getFonNum() == $sal->getFonNum()) {
                        echo "selected";
                    } ?>><?php echo $fon->getFonLibelle(); ?></option>
                <?php } ?>
            </select>
        </div>

        <input type="submit" name="valider" value="Valider">
    </form>
<?php }
if (isset($_POST['per_num'])) {
    $personne = new Personne($_POST);
    $retour = $personneManager->modify($personne, $_POST['per_num']);
}
if (isset($_POST['categorie']) && isset($_POST['old_categorie']) && $_POST['categorie'] == $_POST['old_categorie']) {
    if ((!empty($_POST['dep_num'])) && (!empty($_POST['div_num']))) {
        $etudiantManager = new EtudiantManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'dep_num' => $_POST['dep_num'], 'div_num' => $_POST['div_num']);
        $etudiant = new Etudiant($arrayPost);
        $retour = $etudiantManager->modify($etudiant, $_POST['per_num']);
        header('Location: index.php?page=3', true, 303);
    }
    if ((!empty($_POST['fon_num'])) && (!empty($_POST['sal_telprof']))) {
        $salarieManager = new SalarieManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'sal_telprof' => $_POST['sal_telprof'], 'fon_num' => $_POST['fon_num']);
        $salarie = new Salarie($arrayPost);
        $retour = $salarieManager->modify($salarie, $_POST['per_num']);
        header('Location: index.php?page=3', true, 303);
    }
}
?>

<?php
//if person status has changed then we erase every information pertaining to the previous one
if (isset($_POST['categorie']) && isset($_POST['old_categorie']) && $_POST['categorie'] != $_POST['old_categorie']) {
    if ($_POST['categorie'] == "personnel") {
        $etudiantManager = new EtudiantManager($pdo);
        $etudiantManager->deleteEtudiantFromNum($_POST['per_num']);

        $salarieManager = new SalarieManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'sal_telprof' => $_POST['sal_telprof'], 'fon_num' => $_POST['fon_num']);
        $salarie = new Salarie($arrayPost);
        $retour = $salarieManager->add($salarie, $_POST['per_num']);
    }
    if ($_POST['categorie'] == "etudiant") {
        $salarieManager = new SalarieManager($pdo);
        $salarieManager->deleteSalarieFromNum($_POST['per_num']);

        $etudiantManager = new EtudiantManager($pdo);
        $arrayPost = array('per_num' => $_POST['per_num'], 'dep_num' => $_POST['dep_num'], 'div_num' => $_POST['div_num']);
        $etudiant = new Etudiant($arrayPost);
        $retour = $etudiantManager->add($etudiant, $_POST['per_num']);
    }
}
?>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('input[type="radio"]').click(function () {
            var val = $(this).attr("value");
            var target = $("." + val);
            $(".statut").not(target).hide();
            $(target).show();
        });
    });
    $(document).ready(function () {
        var val = $('input[type="radio"]').attr("value");
        var target = $("." + val);
        $(".statut").not(target).hide();
        $(target).show();
    });
</script>

