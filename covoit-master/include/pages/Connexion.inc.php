<h1>Pour vous connecter</h1>
<form action="#" method="post">
    <label>Nom d'utilisateur:</label><br><input type="text" name="login"><br>
    <label>Mot de passe</label><br><input type="password" name="pwd"><br>
    <?php $rand1 = rand(1, 9);
    $rand2 = rand(1, 9);
    $captcha = $rand1 + $rand2;
    ?>
    <input type="hidden" name="captcha_result" value="<?php echo $captcha ?>">
    <div id="tab">
        <div class="col"><img src="image/nb/<?php echo $rand1; ?>.jpg"></div>
        <div class="col"><h1>+</h1></div>
        <div class="col"><img src="image/nb/<?php echo $rand2; ?>.jpg"></div>
        <div class="col"><h1>=</h1></div>
        <br>
    </div>
    <input type="number" name="captcha"><br>
    <input type="submit" name="btn-submit" value="Valider">
</form>
<?php
$pdo = new Mypdo();
$personneManager = new PersonneManager($pdo);

if (isset($_POST['login']) && isset($_POST['pwd']) && isset($_POST['captcha'])) {
    $pwdCrypt = sha1(sha1($_POST['pwd']) . SALT);
    if ($personneManager->loginAndPasswordValide($_POST['login'], $pwdCrypt) && $_POST['captcha'] == $_POST['captcha_result']) {
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['pwd'] = $pwdCrypt;
        header('location: index.php?page=0'); //redirection
    } else {
        echo 'Membre non reconnu...';
    }
} else {
    echo 'Veuillez vous connecter.';
}
?>