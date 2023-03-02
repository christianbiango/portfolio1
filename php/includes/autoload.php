<?php

function chargementClasse($nomClasse) {
    $nomClasse = str_replace("\\", "/", $nomClasse);
    include $nomClasse . ".php";
}

spl_autoload_register("chargementClasse");