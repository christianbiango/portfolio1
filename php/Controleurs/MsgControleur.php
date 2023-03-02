<?php

namespace Controleurs;

use Modeles\Bdd;
use Modeles\Entites\Msg;
use Modeles\Entites\Pagination;
use Controleurs\PaginationControleur;
require_once './Modeles/Bdd.php';
require_once './includes/functions.inc.php';
require_once './Modeles/Entites/Pagination.php';
require_once 'PaginationControleur.php';


class MsgControleur{
    public function liste()
    {
        
        $msg = '';

        $p = PaginationControleur::checkPage($msg);
        //var_dump($pagination);      
    }

    public function ajouter(){
        if($_POST){
            //debug($_POST);
            $m = new Msg;
            $m->setNom($_POST['nom']);
            $m->setEmail($_POST['email']);
            $m->setMsg($_POST['msg']);
            $resultat = Bdd::insertMsg($m);
        
            if(!$resultat){
                echo "Erreur SQL lors de l'insertion";
            }
        }   
        
        // AFFICHAGE

        $msg = new Msg;
/*
        include "../header.html.php";
        include "../msg/form.html.php";
        include "../footer.html.php";
        */
        
    }

    public function modifier($id){
        $msg = Bdd::selectById("contact", "msg", $id);
        //var_dump($msg->getTraitement());
        if(isset($msg)){
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                ($msg->getTraitement() == 0 ? $msg->setTraitement(1) : $msg->setTraitement(0));
                if(Bdd::updateMsg($msg, 'contact') == 1){

                    if(Bdd::updateLastActivity('contact', 'traitement_date',  $id)){

                        redirection('index_back.php');
                    }
                } else echo 'erreur';
            }
        } else{
            redirection('index_back.php');
        }
        affichage('msg/modification.html.php', [ "msg" => $msg]);
    }

    public function supprimer($id)
    {

        if($id) {
            if( is_numeric($id) ) {
                $msg = Bdd::selectById("contact", "msg", $id);

                if($msg) {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteLivre($msg, 'contact') == 1) {
                            redirection("index_back.php");
                        }
                    }
                } else {
                    redirection("index_back.php");
                }
            }
        }
        affichage("msg/suppression.html.php", [ "msg" => $msg ]);
    }

    public function supprimertous()
    {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteAll('contact') == 1) {
                            redirection("index_back.php");
                        } else {
                            redirection("index_back.php");
                        }
                    }
        affichage("msg/suppressiontous.html.php");
    }

    public function trier(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            var_dump($_POST);
        }
    }

}
?>