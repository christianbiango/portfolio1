<?php
include "includes/init.inc.php";

isLogged('./vues/login/login.html.php');

$methode = $_GET["methode"] ?? "checkCurrentSession";
$controleur = $_GET["controleur"] ?? "login";
$id = $_GET["id"] ?? null;

$nomClasse = "Controleurs\\" . ucfirst($controleur) . "Controleur";

$controleur = new $nomClasse;
$controleur->$methode($id);
