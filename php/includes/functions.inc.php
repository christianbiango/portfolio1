<?php

function isLogged($url){
    if(!isset($_SESSION)) session_start();
    if(!$_SESSION['loggedin']){
        
        redirection($url);
        exit;
    }
    
}

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

function lien($controleur, $methode = "liste", $id = null ) {
    return "?controleur=$controleur&methode=$methode" . ($id ? "&id=$id" : "");
}
?>