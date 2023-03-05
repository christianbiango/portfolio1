<?php

namespace Controleurs;
if($_SERVER['PHP_SELF'] == '/portfolio/php/Controleurs/LoginControleur.php') header('Location: ../vues/login/logout.html.php');

use Modeles\Bdd;
use Modeles\Entites\Login;

use const Modeles\IS_TRUE;
use const Modeles\TEMPS_CONNEXION;

// Problème de chemin, je n'ai pas trouvé de solution plus ergonomique que celle-ci pour le moment.
if(!isset($_GET['methode'])){
    require_once '../../Modeles/Entites/Login.php';
    require_once '../../Modeles/Bdd.php';
} else{
    require_once './Modeles/Entites/Login.php';
    require_once './Modeles/Bdd.php';
}

class LoginControleur{

    // Vérifications de la connexion en entrant l'url du formulaire de connexion
    public function checkCurrentSession($login){
        if(!isset($_SESSION)) session_start();

        // Si l'utilisateur est connecté, renvoyer au back-office
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === IS_TRUE){
            header("location: ../../index.back.php");
        exit;
        } else{
            // Si l'utilisateur tente de se connecter pour la première fois, vérifier les données du formulaire
            $this->checkFormData($login);
        }
    }

    // Vérification de la connexion à chaque clique dans le back-office
    /*
    public static function checkConnexion(){
        if(!isset($_SESSION)) session_start();
        if(!$_SESSION['loggedin']){
            header('Location: ../login/login.html.php');
            exit;
        } else if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === IS_TRUE){
            // Duration de la session en secondes
            $duration = TEMPS_CONNEXION;

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
    */

    // Liste les informations du compte actif
    public function liste(){
        $info = Bdd::selectInfosUtilisateur('admin', 'login', $_SESSION['id']);

        include "vues/header.html.php";
        include "vues/infos/infos.html.php";
        include "vues/footer.html.php"; 
    }

    public function checkFormData($login){

        if($_POST){
            $username = htmlspecialchars(trim($_POST['username']));
            $password = htmlspecialchars(trim($_POST['password']));

            // Vérifier si l'username est vide
            if(empty($username)){
                $login->setUsernameErr('Veuillez entrer un nom d\'utilisateur');
            } else $login->setUsername($username);
    
            // Vérifier si le mot de passe est vide
            if(empty($password)){
                $login->setPasswordErr('Veuillez entrer un mot de passe');
            } else $login->setPassword($password);
    
            // Valider les champs
            if(empty($login->getUsernameErr()) && empty($login->getPasswordErr())){
                $login->setUsername($username);
                $login->setPassword($password);
                //var_dump($login->getPassword());
                $resultat = Bdd::loginDataVerify('admin', $login);

                if(gettype($resultat) == 'array'){
                    // Le mot de passe est correct, alors ouvrir une nouvelle session
                    session_start();
                    $_SESSION['loggedin'] = IS_TRUE;
                    $_SESSION['id'] = $resultat['rowId'];
                    $_SESSION['username'] = $resultat['rowUsername'];

                    Bdd::updateLastActivity('admin', 'last_activity', $_SESSION['id']);

                    header('location: ../../index.back.php');
                }
            }
        }
    }
}