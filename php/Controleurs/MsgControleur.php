<?php

namespace Controleurs;
if($_SERVER['PHP_SELF'] == '/portfolio/php/Controleurs/MsgControleur.php') header('Location: ../vues/login/logout.html.php');


use Modeles\Bdd;
use Modeles\Entites\Msg;
use Modeles\Entites\Pagination;
use Controleurs\PaginationControleur;
use const Modeles\UN;
use const Modeles\ZERO;
use const Modeles\IS_TRUE;
require_once './Modeles/Bdd.php';
require_once './includes/functions.inc.php';
require_once './Modeles/Entites/Pagination.php';
require_once 'PaginationControleur.php';


class MsgControleur{
    // Affiche la liste des messages, gérée par le controleur de pagination
    public function liste()
    {
        PaginationControleur::createPage('contact');    
    }

    public function ajouter(){
        if($_POST){
            //debug($_POST);
            $m = new Msg;
            $m->setNom(htmlspecialchars($_POST['nom']));
            $m->setEmail(htmlspecialchars($_POST['email']));
            $m->setMsg(htmlspecialchars($_POST['msg']));
            $resultat = Bdd::insertMsg($m);
        
            if(!$resultat){
                echo "Erreur SQL lors de l'insertion";
            }
        }   
        
    }

    // Toggle le status d'un message en traité ou non traité
    public function modifier($id){
        $msg = Bdd::selectById("contact", "msg", $id);

        if(isset($msg)){
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                ($msg->getTraitement() == ZERO ? $msg->setTraitement(UN) : $msg->setTraitement(ZERO));
                if(Bdd::updateTraitementMsg($msg, 'contact') == UN){

                    if(Bdd::updateLastActivity('contact', 'traitement_date',  $id)){

                        redirection('index.back.php');
                    }
                } else echo 'erreur';
            }
        } else{
            redirection('index.back.php');
        }
        affichage('msg/modification.html.php', [ "msg" => $msg]);
    }

    // Supprime un message en fonction de son id
    public function supprimer($id)
    {

        if($id) {
            if( is_numeric($id) ) {
                $msg = Bdd::selectById("contact", "msg", $id);

                if($msg) {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteItem($msg, 'contact') == UN) {
                            redirection("index.back.php");
                        }
                    }
                } else {
                    redirection("index.back.php");
                }
            }
        }
        affichage("msg/suppression.html.php", [ "msg" => $msg ]);
    }


    // Supprime tous les messages traités
    public function supprimertous()
    {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteItem('', 'contact', IS_TRUE) == UN) {
                            redirection("index.back.php");
                        } else {
                            redirection("index.back.php");
                        }
                    }
        affichage("msg/suppressiontous.html.php");
    }

}
?>