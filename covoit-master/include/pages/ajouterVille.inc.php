<h1>Ajouter une ville</h1>
<?php
if (empty($_POST['vil_nom'])) {
    ?>
    <form action="#" method="POST">
        <label>Nom : </label><input type="text" name="vil_nom" pattern="[A-Za-z]{0,26}" oninvalid="this.setCustomValidity('Entrez un nom de ville valide')" oninput="this.setCustomValidity('')" required>
        <input type="submit" name="valider" value="Valider">
    </form>
    <?php
} else {
    $pdo = new Mypdo();
    $villeManager = new VilleManager($pdo);
    $ville = new Ville($_POST);
    $retour = $villeManager->add($ville);
    if ($retour != 0) {
        ?>
        <div>
            <p>La ville "<?php echo $_POST['vil_nom'] ?>" a été ajoutée</p>
        </div>
        <?php
    }
}
?>

