<?php
include "includes/init.inc.php";
include 'connexioncheck.php';
isLogged('vues/login/login.html.php');

$methode = $_GET["methode"] ?? "liste";
$controleur = $_GET["controleur"] ?? "home";
$id = $_GET["id"] ?? null;
$outil = $_GET["outil"] ?? "projet";

$nomClasse = "Controleurs\\" . ucfirst($controleur) . "Controleur";

$controleur = new $nomClasse;
$controleur->$methode($id);