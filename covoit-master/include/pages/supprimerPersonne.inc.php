<?php
$pdo = new Mypdo();
$personneManager = new PersonneManager($pdo);
$personnes = $personneManager->getAllPersonnes();
?>
<h1>Suppression d'une personne</h1>
<?php if (empty($_POST['per_suppr']) && empty($_POST['confirmer'])) { ?>
    <form action="#" method="POST">
        <label>Personne : </label>
        <select name="per_suppr">
            <?php foreach ($personnes as $personne) { ?>
                <option value="<?php echo $personne->getPerNum(); ?>"><?php echo $personne->getPerNom() . " " . $personne->getPerPrenom(); ?></option>
            <?php } ?>
        </select>
        <input type="submit" name="supprimer" value="Supprimer">
    </form>
<?php }
if (isset($_POST['supprimer']) && empty($_POST['conf'])) { ?>
    <form action="#" method="POST">
        <?php $per_a_suppr = $personneManager->getPersonneFromPerNum($_POST['per_suppr']) ?>
        <fieldset>
            <label>Êtes-vous sûr de vouloir supprimer <?php echo $per_a_suppr->getPerNom() . " " . $per_a_suppr->getPerPrenom() ?> ? </label>
            <input type="hidden" name="per_num" value="<?php echo $_POST['per_suppr']; ?>">
            <input type="radio" id="oui" name="conf" value="Oui"><label for="oui">Oui</label>
            <input type="radio" id="non" name="conf" value="Non" checked><label for="non">Non</label>
        </fieldset>
        <input type="submit" name="confirmer" value="Confirmer">
    </form>
<?php }
if (isset($_POST['conf'])) {
    if ($_POST['conf'] == "Oui") {
        $proposeManager = new ProposeManager($pdo);
        $etudiantManager = new EtudiantManager($pdo);
        $salarieManager = new SalarieManager($pdo);

        $proposeManager->deleteAvisFromPerNum($_POST['per_num']);
        $proposeManager->deleteProposeFromPerNum($_POST['per_num']);
        $etudiantManager->deleteEtudiantFromNum($_POST['per_num']);
        $salarieManager->deleteSalarieFromNum($_POST['per_num']);
        $personneManager->deletePersonneFromNum($_POST['per_num']);
        ?>
        <p>Personne supprimée</p>
    <?php } else {
        header('Location: index.php?page=4', true, 303);
    }
}
?>

