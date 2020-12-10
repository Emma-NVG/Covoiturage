<?php
$pdo = new Mypdo();
$villeManager = new VilleManager($pdo);
$villes = $villeManager->getAllVilles();
?>

    <h1>Ajouter un parcours</h1>
<?php
if (empty($_POST['vil_num1']) || empty($_POST['vil_num2'])) {
    ?>
    <form action="#" method="POST">
        <fieldset>
            <label>Ville 1 : </label>
            <select name="vil_num1" class="selectparc">
                <?php foreach ($villes as $ville) { ?>
                    <option value="<?php echo $ville->getVilNum(); ?>"><?php echo $ville->getVilNom(); ?></option>
                <?php } ?>
            </select>
        </fieldset>
        <fieldset>
        <label>Ville 2 : </label>
        <select name="vil_num2" class="selectparc">
            <?php foreach ($villes as $ville) { ?>
                <option value="<?php echo $ville->getVilNum(); ?>"><?php echo $ville->getVilNom(); ?></option>
            <?php } ?>
        </select>
        </fieldset>
        <br>
        <label>Nombre de kilomètre(s) : </label><input type="text" name="par_km">
        <input type="submit" name="valider" value="Valider">
    </form>
    <?php
} else {
    $parcoursManager = new ParcoursManager($pdo);
    $parcours = new Parcours($_POST);
    $retour = $parcoursManager->add($parcours);
    if ($retour != 0) {
        ?>
        <div>
            <p>Le parcours a été ajouté</p>
        </div>
        <?php
    } else { ?>
        <div>
            <p>Le parcours existe déjà !</p>
        </div>
    <?php }
}
?>