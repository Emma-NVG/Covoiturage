<?php
header('Cache-Control: no-cache');

require_once("include/header.inc.php");
require_once("include/config.inc.php");
require_once("include/autoload.inc.php");
?>
<div id="corps">
    <?php
    require_once("include/menu.inc.php");
    require_once("include/texte.inc.php");
    ?>
</div>

<div id="spacer"></div>
<?php
require_once("include/footer.inc.php"); ?>
