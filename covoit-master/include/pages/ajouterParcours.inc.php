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
        <label>Ville 1 : </label>
        <select name="vil_num1">
            <?php foreach ($villes as $ville) { ?>
                <option value="<?php echo $ville->getVilNum(); ?>"><?php echo $ville->getVilNom(); ?></option>
            <?php } ?>
        </select>

        <label>Ville 2 : </label>
        <select name="vil_num2">
            <?php foreach ($villes as $ville) { ?>
                <option value="<?php echo $ville->getVilNum(); ?>"><?php echo $ville->getVilNom(); ?></option>
            <?php } ?>
        </select>

        <label>Nombre de kilomètre(s) : </label><input type="number" name="par_km" min="1" max="9000" oninvalid="this.setCustomValidity('Entrez une distance comprise entre 1 et 9000km')" oninput="this.setCustomValidity('')">
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