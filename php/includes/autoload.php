<?php
if($_SERVER['PHP_SELF'] == '/portfolio/php/includes/autoload.php') header('Location: ../vues/login/logout.html.php');
function chargementClasse($nomClasse) {
    $nomClasse = str_replace("\\", "/", $nomClasse);
    include $nomClasse . ".php";
}

spl_autoload_register("chargementClasse");