<?php

namespace Controleurs;

use Modeles\Bdd;
use Modeles\Entites\Login;

if(!isset($_GET['methode'])){
    require_once '../../Modeles/Entites/Login.php';
    require_once '../../Modeles/Bdd.php';
} else{
    require_once './Modeles/Entites/Login.php';
    require_once './Modeles/Bdd.php';
}

class LoginControleur{

    public function checkCurrentSession($login){
        session_start();

        // Si l'utilisateur est connecté, renvoyer au back-office
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            header("location: ../../index_back.php");
        exit;
        } else{
            $this->checkFormData($login);
        }
    }

    public function liste(){
        $info = Bdd::selectInfos('admin', 'login', $_SESSION['id']);
        //var_dump($info);

        include "vues/header.html.php";
        include "vues/infos/infos.html.php";
        include "vues/footer.html.php"; 
    }

    public function checkFormData($login){

        // Initialiser la classe
        //$login = new Login();
        //var_dump($login);

        if($_POST){

            // Vérifier si l'username est vide
            if(empty(trim($_POST['username']))){
                $login->setUsernameErr('Veuillez entrer un nom d\'utilisateur');
            } else $login->setUsername(trim($_POST['username']));
    
            // Vérifier si le mot de passe est vide
            if(empty(trim($_POST['password']))){
                $login->setPasswordErr('Veuillez entrer un mot de passe');
            } else $login->setPassword(trim($_POST['password']));
    
            // Valider les champs
            if(empty($login->getUsernameErr()) && empty($login->getPasswordErr())){
                $login->setUsername($_POST['username']);
                $login->setPassword($_POST['password']);
                //var_dump($login->getPassword());
                $resultat = Bdd::loginDataVerify('admin', $login);
                //var_dump($resultat);

                if(gettype($resultat) == 'array'){
                    // Le mot de passe est correct, alors ouvrir une nouvelle session
                    session_start();
                    //var_dump($resultat[1]);
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $resultat[0];
                    $_SESSION['username'] = $resultat[1];

                    $response = Bdd::updateLastActivity('admin', 'last_activity', $_SESSION['id']);

                    header('location: ../../index_back.php');
                }
            }
        }
    }
}