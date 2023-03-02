<?php
include "includes/init.inc.php";

isLogged('./vues/login/login.html.php');
if(!$_SESSION['loggedin']) {
    header('Location: ../login/login.html.php');
exit;
}

$methode = $_GET["methode"] ?? "liste";
$controleur = $_GET["controleur"] ?? "home";
$id = $_GET["id"] ?? null;

$nomClasse = "Controleurs\\" . ucfirst($controleur) . "Controleur";

$controleur = new $nomClasse;
$controleur->$methode($id);