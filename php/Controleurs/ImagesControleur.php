<?php

namespace Controleurs;

use Modeles\Bdd;
use Modeles\Entites\Images;
require_once './Modeles/Entites/Images.php';
require_once './Modeles/Bdd.php';

class ImagesControleur{
    public function liste()
    {
        $image = Bdd::select("images", "images");
        
        include "vues/header.html.php";
        include "vues/img/img.interface.html.php";
        include "vues/footer.html.php";     
    }

    public function ajouter(){
        if($_FILES){
            //debug($_POST);
            $i = new Images;
            
            $i->setImg($_FILES['img']);
            //var_dump($f->getImg());

            // Le projet, la table, les colonnes et leurs valeurs qu'on souhaite ajouter en base de données
            $resultat = Bdd::insertProjet($i, "images", "img, name", ":img, :name");
            var_dump($resultat);
        
            if($resultat){
                redirection("index_back.php");
                exit;
            } else{
                echo "Erreur SQL lors de l'insertion";
            }
        }   
        
        // AFFICHAGE

        $i = new Images;

        include "./vues/header.html.php";
        include "./vues/img/img.form.html.php";
        include "./vues/footer.html.php";
    }

    public function supprimer($id)
    {

        if($id) {
            if( is_numeric($id) ) {
                $i = Bdd::selectById("images", "images", $id);

                if($i) {
                    if($_SERVER["REQUEST_METHOD"] == "POST"){
                        if( Bdd::deleteItem($i, 'images') == 1) {
                            redirection("index_back.php");
                        }
                    }
                } else {
                    redirection("index_back.php");
                }
            }
        }
        affichage("img/suppression.img.html.php", [ "images" => $i]);
    }

    public function modifier($id)
    {
        $image = Bdd::selectById("images", 'images', $id);
        //var_dump($projet);
        
        if($_FILES){
            $i = new Images;
            $name = $_FILES['img']['name'] ?? null; 
            $img = $_FILES['img'] ?? null;
            //$resume = $_POST["resume"] ?? null;
            //var_dump($p->getName());
        

                $i->setId($id);
                $i->setName($name);
                $i->setImg($img);
                $i->setDate(date('d-m-Y H:i:s'));
                //var_dump($p->getDate());
                $i->setOldFile($image->getName());
                //var_dump($p->getOldFile());
                // L'image, la table et les colonnes et ses valeurs que l'on souhaite modifier en base de données
                if( Bdd::updateProjet($i, 'images', 'name = :name, img = :img') ){
                    if(!$i->getName() != $i->getOldFile()){
                        redirection("vues/img/img.interface.html.php");
                    } else if(unlink('./uploads/' . $i->getOldFile())){
                       redirection("vues/img/img.interface.html.php");
                    }
                } else {
                    $erreurs["generale"] = "Erreur lors de la modification en bdd";
                }
        
        }
        include "vues/header.html.php";
        include "vues/img/img.form.html.php";
        include "vues/footer.html.php";
    }
}
?>