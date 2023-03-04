<?php

namespace Controleurs;

use Modeles\Bdd;
use Modeles\Entites\Front;
require_once './Modeles/Entites/Front.php';
require_once './Modeles/Bdd.php';

class FrontControleur{
    public function liste()
    {
        $front = Bdd::select("projet", "front");
        
        include "vues/header.html.php";
        include "vues/front/front.interface.html.php";
        include "vues/footer.html.php";     
    }

    public function ajouter(){
        if($_POST){
            //debug($_POST);
            $f = new Front;
            
            $f->setImg($_FILES['img']);
            //var_dump($f->getImg());
            $f->setTitre($_POST['titre']);
            $f->setText($_POST['txt']);

            // Le projet, la table, les colonnes et leurs valeurs qu'on souhaite ajouter en base de données
            $resultat = Bdd::insertProjet($f, "projet", "name, img, titre, text", ":name, :img, :titre, :text");
            var_dump($resultat);
        
            if($resultat){
                redirection("index_back.php");
                exit;
            } else{
                echo "Erreur SQL lors de l'insertion";
            }
        }   
        
        // AFFICHAGE

        $f = new Front;

        include "./vues/header.html.php";
        include "./vues/front/form.html.php";
        include "./vues/footer.html.php";
    }

    public function supprimer($id)
    {

        if($id) {
            if( is_numeric($id) ) {
                $f = Bdd::selectById("projet", "front", $id);

                if($f) {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteItem($f, 'projet') == 1) {
                            redirection("index_back.php");
                        }
                    }
                } else {
                    redirection("index_back.php");
                }
            }
        }
        affichage("front/suppression.projet.html.php", [ "front" => $f ]);
    }

    public function modifier($id)
    {
        $projet = Bdd::selectById("projet", 'front', $id);
        //var_dump($projet);
        
        if($_POST){
            $p = new Front;
            $name = $_FILES['img']['name'] ?? null; 
            $img = $_FILES['img'] ?? null;
            $titre = $_POST['titre'] ?? null;
            $text = $_POST['txt'] ?? null;
            //$resume = $_POST["resume"] ?? null;
            //var_dump($p->getName());
        
            if( !empty($name) && !empty($img) ) {
                if( strlen($titre) < 4 ) {
                    $erreurs["titre"] = "Le titre doit avoir au moins 4 caractères";
                }
                if( strlen($text) > 200 ) {
                    $erreurs["txt"] = "L'auteur ne doit pas dépasser 200 caractères";
                }
            } else {
                $erreurs["generale"] = "Veuillez remplir les champs requis";
            }
        
            if( empty($erreurs) ){
                $p->setId($id);
                $p->setName($name);
                $p->setImg($img);
                $p->setTitre($titre);
                $p->setText($text);
                $p->setDate(date('d-m-Y H:i:s'));
                //var_dump($p->getDate());
                $p->setOldFile($projet->getName());
                //var_dump($p->getOldFile());
                // Le projet, la table, les colonnes et les valeurs que l'on souhaite modifier en base de données
                if( Bdd::updateProjet($p, "projet", "name = :name, img = :img, titre = :titre, text = :text") ){
                    if(!$p->getName() != $p->getOldFile()){
                        redirection("vues/front/front.interface.html.php");
                    } else if(unlink('./uploads/' . $p->getOldFile())){
                       redirection("vues/front/front.interface.html.php");
                    }
                } else {
                    $erreurs["generale"] = "Erreur lors de la modification en bdd";
                }
            }
        
        }
        include "vues/header.html.php";
        include "vues/front/form.html.php";
        include "vues/footer.html.php";
    }
}
?>