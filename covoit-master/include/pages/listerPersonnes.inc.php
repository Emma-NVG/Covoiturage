<?php
$pdo = new Mypdo();
$personneManager = new PersonneManager($pdo);
$personnes = $personneManager->getAllPersonnes();
$number_Personnes = $personneManager->numberPersonne();
?>

<?php if (empty($_POST['categorie'])) { ?>
    <h1>Liste des personnes enregistrées</h1>

    <p>Actuellement <?php echo $number_Personnes ?> personnes sont enregistrées</p>
    <table>
        <tr>
            <th>Numéro</th>
            <th>Nom</th>
            <th>Prénom</th>
        </tr>
        <?php
        foreach ($personnes as $pers) { ?>
            <tr>
                <td>
                    <form action="#" method="POST" id="<?php echo $pers->getPerNum() ?>">
                        <input type="hidden" value="<?php echo $pers->getPerNum() ?>" name="per_num">
                        <?php $categorie = $personneManager->isEtudiant($pers->getPerNum()) ?>
                        <input type="hidden" value="<?php echo $categorie ?>" name="categorie">
                    </form>

                    <a href="#" onclick="document.getElementById(<?php echo $pers->getPerNum() ?>).submit()">
                        <?php echo $pers->getPerNum(); ?>
                    </a>
                </td>
                <td><?php echo $pers->getPerNom(); ?></td>
                <td><?php echo $pers->getPerPrenom(); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } else {
    if ($_POST['categorie'] == 'etudiant') {
        $etudiantManager = new EtudiantManager($pdo);
        $depManager = new DepartementManager($pdo);
        $divManager = new DivisionManager($pdo);
        $pers = $personneManager->getPersonneFromPerNum($_POST['per_num']);
        $etudiant = $etudiantManager->getEtudiantFromPerNum($_POST['per_num']);
        $dep = $depManager->getDepFromDepNum($etudiant->getDepNum());
        $div = $divManager->getDivFromDivNum($etudiant->getDivNum());
        ?>
        <h1>Détails sur l'étudiant <?php echo $pers->getPerNom(); ?></h1>
        <table>
            <tr>
                <th>Prénom</th>
                <th>Mail</th>
                <th>Tel</th>
                <th>Departement</th>
                <th>Ville</th>
            </tr>
            <tr>
                <td><?php echo $pers->getPerPrenom(); ?></td>
                <td><?php echo $pers->getPerMail(); ?></td>
                <td><?php echo $pers->getPerTel(); ?></td>
                <td><?php echo $dep->getDepNom(); ?></td>
                <td><?php echo $div->getDivNom(); ?></td>
            </tr>
        </table>
    <?php } else {
        $salManager = new SalarieManager($pdo);
        $fonManager = new FonctionManager($pdo);
        $pers = $personneManager->getPersonneFromPerNum($_POST['per_num']);
        $salarie = $salManager->getSalarieFromPerNum($_POST['per_num']);
        $fonction = $fonManager->getFonFromFonNum($salarie->getFonNum());
        ?>
        <h1>Détails sur le salarié <?php echo $pers->getPerNom(); ?></h1>
        <table>
            <tr>
                <th>Prénom</th>
                <th>Mail</th>
                <th>Tel</th>
                <th>TelProf</th>
                <th>Fonction</th>
            </tr>
            <tr>
                <td><?php echo $pers->getPerPrenom(); ?></td>
                <td><?php echo $pers->getPerMail(); ?></td>
                <td><?php echo $pers->getPerTel(); ?></td>
                <td><?php echo $salarie->getSalTelprof(); ?></td>
                <td><?php echo $fonction->getFonLibelle(); ?></td>
            </tr>
        </table>
    <?php } ?>
<?php } ?>