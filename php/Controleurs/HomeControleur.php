<?php
namespace Controleurs;

if($_SERVER['PHP_SELF'] == '/portfolio/php/Controleurs/HomeControleur.php') header('Location: ../vues/login/logout.html.php');

class HomeControleur {

    public function liste()
    {
        affichage("home/liste.html.php");
    }
}