<?php

function isLogged($url){
    if(!isset($_SESSION)) session_start();
    if(!$_SESSION['loggedin']){
        header('Location: ' . $url);
        exit;
    } else if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        // Duration de la session en secondes
        $duration = 20;

        // Lire la dernière requête de l'utilisateur
        $time = $_SERVER['REQUEST_TIME'];

        if(isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $duration){
            // vider la session
            $_SESSION = array();
            // destruction
            session_destroy();
            header('Location: ../login/login.html.php');
            exit;
        }
        $_SESSION['LAST_ACTIVITY'] = $time;
    }
}