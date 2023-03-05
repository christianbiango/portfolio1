<?php

function debug($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function redirection($url) {
    header("Location: $url");
    exit;
}

function affichage($fichier, $variablesVue = []) {

    extract($variablesVue); 
    include "vues/header.html.php";
    include "vues/$fichier";
    include "vues/footer.html.php";    
}

function lien($controleur, $methode = "liste", $id = null, $outil = "projet" ) {
    return "?controleur=$controleur&methode=$methode" . ($id ? "&id=$id" : "") . "&outil=$outil";
}
?>